@include('admin.layout.head')
<div class="panel-body">
    <div class="left-box">
        <form action="" method="get" class="sidebar-form" onsubmit="return false;">
            <div class="layui-form-item" style="display: flex; align-items: center;">
                <input type="text" name="q" class="layui-input" style="width: 70%;" placeholder="输入关键词以搜索...">
                <button type="submit" name="search" id="search-btn" class="layui-btn layui-btn-primary"><i class="layui-icon layui-icon-search"></i></button>
                <div class="menuresult list-group sidebar-form hide"></div>
            </div>
        </form>
        <div class="left-list"></div>
    </div>
    <div class="right-box">
        <div class="right-head">
            <div class="head-left">
                <img src="/public/static/admin/images/head.jpg" alt="" class="left-img">
                <div>
                    <div class="left-name"></div>
                    <!--                        <div class="left-online">在线</div>-->
                </div>
            </div>
            <img src="/public/static/more.png" alt="" class="head-right">
        </div>
        <div class="message-list">

        </div>
        <div class="right-bot">
            <textarea name="" id="" cols="30" rows="10" placeholder="这里输入聊天内容..." class="enter-box"></textarea>
            <div class="right-operate">
                <div class="operate-left">
                    <label>
                        <input type="file" class="upload-input" accept="image/*, .jpg, .jpeg, .png"
                               style="display: none;">
                        <img src="/public/static/img.png" alt="点击上传">
                    </label>
                </div>
                <div class="operate-right">
                    <img src="/public/static/send.png" alt="">
                    <div>发送</div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.layout.foot')

<style>
    .panel-body {
        background: #f1f4f6;
        display: flex;
        gap: 12px;
        height: calc(100vh - 32px);
    }

    .left-box {
        min-width: 280px;
        max-width: 280px;
        background: #FFFFFF;
        padding: 20px 12px;
        border-radius: 8px;

        .left-list {
            margin-top: 20px;
            height: calc(100% - 73px);
            overflow-y: auto;
            display: flex;
            flex-direction: column;

            &::-webkit-scrollbar {
                display: none;
            }

            .list-item {
                display: flex;
                align-items: center;
                gap: 16px;
                padding: 12px 18px;

                .item-img {
                    min-width: 48px;
                    height: 48px;
                    border-radius: 50%;
                    border: 1px solid #ccc;
                }

                .item-right {
                    width: 100%;

                    .right-one {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;

                        .item-time {
                            font-size: 12px;
                            color: #999;
                        }
                    }

                    .right-two {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;

                        .item-message {
                            font-size: 14px;
                            width: 156px;
                            overflow: hidden;
                        }

                        .item-state {
                            width: 8px;
                            height: 8px;
                            border-radius: 50%;
                            background-color: #07c160;
                        }
                    }
                }

                &.active {
                    background: #f5f5f5;
                    border-radius: 8px;
                }
            }
        }
    }

    .right-box {
        width: calc(100% - 292px);
        /*height: 100%;*/
        background: #FFFFFF;
        border-radius: 8px;
        padding: 20px 20px 10px;
        display: none;

        .right-head {
            display: flex;
            align-items: center;
            justify-content: space-between;

            .head-left {
                display: flex;
                align-items: center;
                gap: 8px;

                .left-img {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    border: 1px solid #ccc;
                }

                .left-online {
                    font-size: 12px;
                }
            }

            .head-right {
                width: 24px;
                height: 24px;
            }
        }

        .message-list {
            height: calc(100% - 218px);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            padding: 10px;
            gap: 12px;

            .message-item {
                display: flex;

                .item-text {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
            }

            .left {
                .item-text {
                    padding: 6px 12px;
                    border-radius: 8px;
                    background: #f1f4f6;
                }

                .item-img {
                    width: 160px;
                    height: 160px;
                    position: relative;

                    .img {
                        width: 100%;
                        height: 100%;
                        border-radius: 12px;
                    }

                    .time {
                        position: absolute;
                        right: 6px;
                        bottom: 2px;
                        color: #FFF;
                    }
                }
            }

            .right {
                justify-content: end;

                .item-text {
                    color: #FFF;
                    padding: 6px 12px;
                    border-radius: 8px;
                    background: #0baf0b;
                }

                .item-img {
                    width: 160px;
                    height: 160px;
                    position: relative;

                    .img {
                        width: 100%;
                        height: 100%;
                        border-radius: 12px;
                    }

                    .time {
                        position: absolute;
                        right: 6px;
                        bottom: 2px;
                        color: #FFF;
                    }
                }
            }

            &::-webkit-scrollbar {
                display: none;
            }
        }

        .right-bot {
            background: #f1f4f6;
            border: 3px solid #f1f4f6;
            border-radius: 8px;

            .enter-box {
                width: 100%;
                height: 80px;
                padding: 6px;
                resize: none;
                border: none;
                outline: none;
                border-radius: 8px;
            }

            .right-operate {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 8px;

                .operate-left {
                    display: flex;
                    align-items: center;
                    gap: 12px;

                    img {
                        width: 16px;
                        height: 16px;
                    }
                }

                .operate-right {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 6px;
                    background: #0baf0b;
                    color: #fff;
                    padding: 3px 8px;
                    border-radius: 6px;

                    img {
                        width: 18px;
                        height: 18px;
                    }
                }
            }
        }
    }
</style>
