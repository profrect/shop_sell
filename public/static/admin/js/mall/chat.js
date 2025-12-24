define(["jquery", "easy-admin", "etChatSDK"], function ($, ea, etChatSDK) {

    var init = {
        ajax_url: 'getSign',
    };

    var Controller = {

        index: function () {

            let client = null;
            let sessionListData = [];
            let targetName = '';
            let targetId = '';
            let targetAvatar = '';
            let groupId = null;
            let msgList = [];
            let senderId = '';

            /* ================== 消息提示音 ================== */
            const notifyAudio = new Audio('/static/message.mp3');
            notifyAudio.volume = 0.6;

            let audioUnlocked = false;

            const unlockAudio = () => {
                if (audioUnlocked) return;
                notifyAudio.play().then(() => {
                    notifyAudio.pause();
                    notifyAudio.currentTime = 0;
                    audioUnlocked = true;
                }).catch(() => {});
                document.removeEventListener('click', unlockAudio);
                document.removeEventListener('keydown', unlockAudio);
            };

            document.addEventListener('click', unlockAudio, { once: true });
            document.addEventListener('keydown', unlockAudio, { once: true });

            /* ================== Notification 权限 ================== */
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }

            /* ================== 初始化 SDK ================== */
            $.ajax({
                type: 'GET',
                url: init.ajax_url,
                dataType: 'json',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader(
                        'X-CSRF-TOKEN',
                        $('meta[name="csrf-token"]').attr('content')
                    );
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
                        }
                    );

                    client = clients[0];

                    sessionListData = await client.getConversationList();
                    sessionListData = sessionListData.filter(item => item.type === 1);
                    renderSessionList();

                    /* ================== 接收消息 ================== */
                    client.on('message_received', (message) => {

                        if (message.key !== 'GROUPCHAT') return;

                        const msgGroupId = String(message.receiverId);
                        const isCurrentSession = msgGroupId === String(groupId);

                        /* 当前会话：直接渲染 */
                        if (isCurrentSession) {
                            msgList.push(message);
                            msgList.sort((a, b) => a.sendTimeStamp - b.sendTimeStamp);
                            renderMessage(msgList);
                            return;
                        }

                        /* 非当前会话：红点 */
                        const session = sessionListData.find(
                            item => String(item.targetId) === msgGroupId
                        );

                        if (session) {
                            session.unread = (session.unread || 0) + 1;
                            session.lastMessageTime = message.sendTimeStamp;
                            session.lastMessageType = message.messageType;
                            session.lastMessageData = message.data;
                        }

                        renderSessionList();

                        /* 前台：语音提示 */
                        if (!document.hidden) {
                            if (audioUnlocked) {
                                notifyAudio.currentTime = 0;
                                notifyAudio.play().catch(() => {});
                            }
                            return;
                        }

                        /* 后台：系统通知 */
                        if ('Notification' in window && Notification.permission === 'granted') {
                            const notification = new Notification('新消息提醒', {
                                body: session?.nickname || '你有一条新消息',
                                icon: '/static/admin/images/head.jpg',
                                silent: false,
                            });

                            notification.onclick = function () {
                                window.focus();
                            };
                        }
                    });
                }
            });

            /* ================== 会话列表 ================== */
            const renderSessionList = function () {
                const selectedId = $('.list-item.active').data('target-id');

                const html = sessionListData.map(item => `
                    <div class="list-item"
                         data-name="${item.nickname}"
                         data-target-id="${item.targetId}">
                        <img src="/static/admin/images/head.jpg" class="item-img">
                        <div class="item-right">
                            <div class="right-one">
                                <div class="item-name">${item.nickname}</div>
                                <div class="item-time">${formatTime(item.lastMessageTime)}</div>
                            </div>
                            <div class="right-two">
                                <div class="item-message">
                                    ${!item.lastMessageType ? item.lastMessageData.text : '[图片]'}
                                </div>
                                ${item.unread > 0 ? `<div class="item-state">${item.unread}</div>` : ''}
                            </div>
                        </div>
                    </div>
                `).join('');

                $('.left-list').html(html);

                $('.list-item').each(function () {
                    if ($(this).data('target-id') === selectedId) {
                        $(this).addClass('active');
                    }
                });
            };

            /* ================== 选择会话 ================== */
            $(document).on('click', '.list-item', async function () {

                $('.right-box .message-list').empty();

                targetName = $(this).data('name');
                targetId = $(this).data('target-id');
                targetAvatar = '/static/admin/images/head.jpg';

                const session = sessionListData.find(
                    item => String(item.targetId) === String(targetId)
                );
                if (session) session.unread = 0;

                renderSessionList();

                $('.right-box .left-name').text(targetName);
                $('.right-box .left-img').attr('src', targetAvatar);

                groupId = String(targetId);
                const res = await client.getGroupMessageList(groupId, undefined, 20, true);
                msgList = res.items.sort((a, b) => a.sendTimeStamp - b.sendTimeStamp);
                renderMessage(msgList);

                if (window.innerWidth <= 768) {
                    $('.right-box').addClass('show');
                }
            });

            /* ================== 返回 ================== */
            $(document).on('click', '.back-btn', function () {
                $('.right-box').removeClass('show');
            });

            /* ================== 渲染消息 ================== */
            const renderMessage = function (list) {
                const html = list.map(item => `
                    <div class="message-item ${item.senderId === senderId ? 'right' : 'left'}">
                        ${messageRenderers[item.messageType]?.(item)}
                    </div>
                `).join('');

                $('.message-list').html(html);
                $('.message-list').scrollTop($('.message-list')[0].scrollHeight);
            };

            const messageRenderers = {
                0: item => `
                    <div class="item-text">
                        <div class="text">${item.data.text}</div>
                        <div class="time">${formatTime(item.sendTimeStamp)}</div>
                    </div>
                `,
                1: item => `
                    <div class="item-img">
                        <img src="${item.data.url}" class="img">
                        <div class="time">${formatTime(item.sendTimeStamp)}</div>
                    </div>
                `
            };

            /* ================== 时间格式 ================== */
            function formatTime(timestamp) {
                const date = new Date(timestamp);
                const now = new Date();
                const pad = n => n.toString().padStart(2, '0');

                const isToday =
                    date.getFullYear() === now.getFullYear() &&
                    date.getMonth() === now.getMonth() &&
                    date.getDate() === now.getDate();

                const h = pad(date.getHours());
                const m = pad(date.getMinutes());

                if (isToday) return `${h}:${m}`;

                return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ${h}:${m}`;
            }
        }
    };

    return Controller;
});
