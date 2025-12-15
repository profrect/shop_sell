define(["jquery", "easy-admin", "etChatSDK"], function ($, ea, etChatSDK) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'mall.files/index',
        ajax_url: 'getSign',
    };

    var Controller = {

        index: function () {
            let client = null;
            let userData = {};
            let sessionListData = [];
            let ossUrl = '';
            let targetName = '';
            let targetId = '';
            let targetAvatar = '';
            let sessionType = '';
            let groupId = null;
            let msgList = [];
            let senderId = '';
            // 初始化表格参数配置
            $.ajax({
                type: 'GET',
                url: init.ajax_url,
                dataType: 'json',
                data: {},
                beforeSend: function (xhr) {
                    // 添加CSRF token（根据项目实际情况）
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success: async function (res) {
                    senderId = res.data.chatId;
                    const clients = await etChatSDK.ETChatClient.init(
                        {
                            baseURL: 'https://czapi.et.chat/',
                            timeout: 5000,
                        },
                        {
                            loginType: 'signature',
                            signature: res.data.sign,
                            appId: res.data.appId,
                            userId: res.data.chatId,
                        },
                    );
                    client = clients[0];
                    sessionListData = await client.getConversationList();
                    sessionListData = sessionListData.filter(item => item.type === 1)
                    console.log('sessionListData', sessionListData)
                    renderSessionList();

                    client.on('message_received', (message) => {
                        if (message.key === 'GROUPCHAT' && message.receiverId === groupId) {
                            // if ()
                            msgList.push(message);
                            msgList.sort((a, b) => a.sendTimeStamp - b.sendTimeStamp);
                            renderMessage(msgList);
                        }
                    });
                },
                error: function (xhr, status, error) {
                    console.error('错误详情:', {
                        status: xhr.status,
                        error: error
                    });
                }
            });

            const webSocketOnMessage = (res) => {
                const Body = res.Body
                if (Body.ReceiverId === String(targetId)) {
                    if (sessionType === 0) {
                        const params = {
                            size: 100,
                            senderId: userData.userId,
                            receiverId: String(targetId)
                        };
                        EtChat.getPersonalList(params).then((res) => {
                            renderMessage(res.items);
                        })
                    }
                    if (sessionType === 1) {
                        const params = {
                            size: 100,
                            groupId: String(targetId)
                        };
                        EtChat.getGroupDataList(params).then((res) => {
                            renderMessage(res.items);
                        })
                    }
                }
                if (res.Key === 'PERSONCHAT' || res.Key === 'GROUPCHAT') {
                    EtChat.getSessionList().then((res) => {
                        sessionListData = res;
                        renderSessionList();
                    })
                }
            };

            const renderSessionList = function () {
                // 保存当前选中项的targetId
                const selectedId = $('.list-item.active').data('target-id');

                const html = sessionListData.map((item, index) => `
                    <div
                        class="list-item"
                        data-name="${item.nickname}"
                        data-target-id="${item.targetId}"
                        data-session-type="${item.type}"
                        data-avatar="static/admin/images/head.jpg"
                    >
                        <img src="/static/admin/images/head.jpg" alt="" class="item-img">
                        <div class="item-right">
                            <div class="right-one">
                                <div class="item-name">${item.nickname}</div>
                                <div class="item-time">${formatTime(item.lastMessageTime)}</div>
                            </div>
                            <div class="right-two">
                                <div class="item-message">${!item.lastMessageType ? item.lastMessageData.text : '[图片]'}</div>
                                ${item.unread > 0 ? `<div class="item-state">${item.unread}</div>` : ''}
                            </div>
                        </div>
                    </div>
                `).join('');
                $('.left-list').html(html);

                // 渲染完成后恢复选中状态
                $('.list-item').each(function () {
                    if ($(this).data('target-id') === selectedId) {
                        $(this).addClass('active');
                    }
                });

                // 点击事件处理
                $('.left-list').on('click', '.list-item', function () {
                    // 清空消息列表
                    $('.right-box .message-list').empty();

                    targetName = $(this).data('name');
                    targetId = $(this).data('target-id');
                    targetAvatar = 'static/admin/images/head.jpg';
                    sessionType = $(this).data('session-type');

                    selectSession();

                    $(this).addClass('active').siblings().removeClass('active');
                });
            };

            const selectSession = async function () {
                $('.right-box').show();
                $('.right-box .left-name').text(targetName);
                $('.right-box .left-img').attr('src', targetAvatar);

                groupId = String(targetId);
                const res = await client.getGroupMessageList(String(targetId), undefined, 20, true);
                msgList = res.items;
                msgList.sort((a, b) => a.sendTimeStamp - b.sendTimeStamp);
                renderMessage(msgList);
                console.log(res, '-----------')
            };

            const renderMessage = function (list) {
                if (list.length > 0) {
                    const html = list.map((item, index) => `
                        <div class="message-item ${item.senderId === senderId ? 'right' : 'left'}">
                            ${messageRenderers[item.messageType]?.(item)}
                        </div>
                    `).join('');
                    $('.right-box .message-list').html(html);

                    // 在渲染完成后滚动到底部
                    $('.message-list').scrollTop($('.message-list')[0].scrollHeight);
                }
            };

            const messageRenderers = {
                0: (item) => `<div class="item-text">
                    <div class="text">${item.data.text}</div>
                    <div class="time">${formatTime(item.sendTimeStamp)}</div>
                </div>`,
                1: (item) => `<div class="item-img">
                    <img src="${item.data.url}" alt="聊天图片" class="img">
                    <div class="time">${formatTime(item.sendTimeStamp)}</div>
                </div>`
            };

            function formatTime(timestamp) {
                const date = new Date(timestamp);
                const hours = ('0' + date.getHours()).slice(-2);
                const minutes = ('0' + date.getMinutes()).slice(-2);
                return `${hours}:${minutes}`;
            }

            // 图片上传功能
            $(document).on('change', '.upload-input', function (e) {
                const file = e.target.files[0];
                if (!file?.type.startsWith('image/')) {
                    alert('请选择图片文件');
                    return;
                }

                sendMessage('img', file);
            });

            const sendMessage = async function (type, data) {
                try {
                    if (type === 'text') {
                        await client.sendGroupTextMessage(String(targetId), data);
                        $('.enter-box').val('');
                    } else if (type === 'img') {
                        await client.sendGroupImageMessage(String(targetId), data);
                    }
                } catch (error) {
                    console.log(error)
                }
            };

            $(document).on('click', '.operate-right', function () {
                const message = $('.enter-box').val().trim();
                if (!message) {
                    alert('消息内容不能为空');
                    return;
                }
                sendMessage('text', message);
                $('.enter-box').val('');
            });

            // 添加输入框回车发送支持
            $('.enter-box').on('keypress', function (e) {
                if (e.which === 13 && !e.shiftKey) {
                    e.preventDefault();
                    $('.operate-right').trigger('click');
                }
            });


            // ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
    };
    return Controller;
});
