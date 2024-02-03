<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Office</title>

        <link href="/bootstrap.min.css" rel="stylesheet" />
        <link href="/main.css" rel="stylesheet" />

    </head>
    <body class="antialiased">
        <div class="loading__screen vh-100 d-flex justify-content-center align-items-center" id="loading-screen">
            <div class="login__screen--logotype">
                <span>XOXO</span>
            </div>
        </div>

        <div class="login__screen justify-content-center d-flex align-items-center vh-100 flex-column screen-hidden" id="login-screen">
            <div class="card">
                <div class="card-body d-flex flex-column p-5 bg-body-tertiary">
                    <div class="login__screen--logotype mb-3">
                        <span>XOXO</span>
                    </div>

                    <form action="#" method="post" id="login-form" class="w-100">
                        <div class="form-group">
                            <label for="name" class="mb-2">Your name</label>
                            <input type="text" name="name" class="form-control" id="name">
                        </div>

                        <div class="d-flex flex-wrap mb-4 selectable-avatar-list p-3 mt-2 bg-body">
                            @for($i = 1; $i <= 19; $i++)
                                <div class="col-md-2 selectable-avatar">
                                    <input type="radio" style="display: none;" name="avatar_id" value="{{$i}}" id="avatar-{{$i}}" />
                                    <label for="avatar-{{$i}}">
                                        <img src="/avatars/avatar-{{$i <= 9 ? '0' . $i : $i }}.svg" alt="">
                                    </label>
                                </div>
                            @endfor
                        </div>

                        <button type="submit" class="btn btn-primary w-100 p-2" disabled>Join server</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="app__screen screen-hidden d-flex" id="app-screen">
            <aside>
                <div class="app__aside__header bg-body-tertiary">
                    <div class="app__aside__header__user d-flex flex-row align-items-center gap-3">
                        <div class="app__aside__header__user--left">
                            <img src="" id="user-avatar">
                        </div>

                        <div class="app__aside__header__user--right d-flex flex-column">
                            <b id="username"></b>
                            <span class="mb-2">Being at work <b id="user-created-at-timer">0h 0m</b></span>

                            <button class="btn btn-danger" id="logout-button">Logout</button>
                        </div>
                    </div>
                </div>

                <div class="app__aside__rooms mt-2">
                    <div class="app__aside__rooms__header border-1 p-3 bg-body-tertiary">
                        <b>Rooms</b>
                    </div>

                    <div class="app__aside__rooms__container p-3" id="rooms-container"></div>

                </div>

                <div class="app__aside__chat">
                    <div class="app__aside__chat__header p-3 border-1  bg-body-tertiary">
                        <b>Chat</b>
                    </div>

                    <div class="app__aside__chat__container p-3 pb-0" id="chat-container"></div>

                    <div class="app__aside__chat__form p-2">
                        <form action="#" method="post" class="d-flex flex-row gap-2 message-form">
                            <input type="text" class="form-control" id="message" />
                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="app__office">
                <div class="app__office__rooms__zones" id="zones-container"></div>
                <div class="app__office--image"></div>
                <div class="app__office--users" id="office-users"></div>
            </div>
        </div>

        <script src="/main.js"></script>
    </body>
</html>
