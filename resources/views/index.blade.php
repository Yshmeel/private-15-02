<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Office</title>
        <link href="/main.css" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="antialiased">
        <div class="loading__screen flex w-screen h-screen justify-center items-center" id="loading-screen">
            <span class="font-bold text-6xl">XOXO</span>
        </div>

        <div class="login__screen w-screen h-screen flex bg-gradient-to-r from-pink-500  via-red-500 to-yellow-500 flex-col" id="login-screen">
            <div class="w-7/12 h-full bg-white p-16 m-12 mx-24 flex justify-center flex-col">
                <div class="flex items-center p-2 pl-0 mb-8">
                    <span class="font-bold text-6xl tracking-widest">XOXO</span>
                </div>

                <form action="#" method="post" class="" id="login-form">
                    <div class="mb-6 flex flex-col">
                        <label for="name" class="mb-2">Your name</label>
                        <input type="text" name="name" class="form-control px-4 py-3 border border-gray-400 rounded" id="name">
                    </div>

                    <div class="mb-6">
                        <span class="mb-2">Select your avatar:</span>

                        <div class="flex flex-wrap my-4 selectable-avatar-list border border-gray-400 p-3 rounded">

                            @for($i = 1; $i <= 19; $i++)
                                <div class="w-2/12 h-2/12 selectable-avatar">
                                    <input type="radio" style="display: none;" name="avatar_id" value="{{$i}}" id="avatar-{{$i}}" />
                                    <label for="avatar-{{$i}}">
                                        <img src="/avatars/avatar-{{$i <= 9 ? '0' . $i : $i }}.svg" alt="">
                                    </label>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <button type="submit" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow hover:opacity-70 cursor-pointer transition-opacity" disabled>Join server</button>
                </form>

            </div>

            <div class="w-7/12 h-auto flex mx-24 pb-12 text-center align-middle">
                <span class="text-white text-center">&copy; XOXO. 2024. Task by Ruslan Yussupov</span>
            </div>
        </div>

        <div class="app__screen screen-hidden flex w-full flex-row h-screen" id="app-screen">
            <aside class="border border-gray-100 max-h-screen flex-auto flex-grow-0 flex flex-col overflow-auto">
                <div class="app__aside__header bg-white p-4">
                    <div class="app__aside__header__user flex flex-row items-center gap-6">
                        <div class="app__aside__header__user--left w-24 h-24">
                            <img src="" id="user-avatar" class="w-full h-full">
                        </div>

                        <div class="app__aside__header__user--right flex flex-col">
                            <b id="username" class="text-3xl mb-1"></b>
                            <span>Being at work <b id="user-created-at-timer">0h 0m</b></span>
                        </div>
                    </div>
                </div>

                <div class="w-full p-6 py-0">
                    <button class="w-full bg-red-500 hover:bg-gray-100 hover:text-black text-white font-semibold py-2 px-4 rounded shadow hover:opacity-70 cursor-pointer transition-opacity" id="logout-button">Logout</button>
                </div>

                <div class="app__aside__rooms mt-4">
                    <b class="mb-4 text-3xl block mx-6">Rooms</b>

                    <div class="app__aside__rooms__container" id="rooms-container"></div>

                </div>

                <div class="app__aside__chat mt-7 h-full">
                    <b class="mb-4 text-3xl block mx-6">Chat</b>

                    <div class="app__aside__chat__container flex-col-reverse bg-gray-100 border py-2 px-6 max-h-55 h-full" id="chat-container"></div>

                    <div class="app__aside__chat__form px-6">
                        <form action="#" method="post" class="flex flex-row gap-2 message-form">
                            <input type="text" class="form-control px-4 py-3 border border-gray-400 rounded" id="message" />
                            <button type="submit" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow hover:opacity-70 cursor-pointer transition-opacity">Send</button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="app__office">
                <canvas id="office-canvas"></canvas>
            </div>
        </div>

        <img src="/assets/office.jpg" id="office-image" style="display: none;" alt="">
        @for($i = 1; $i <= 19; $i++)
            <img src="/avatars/avatar-{{$i <= 9 ? '0' . $i : $i }}.svg" alt="" style="display: none;" class="asset-avatar" data-id="<?= $i; ?>">
        @endfor

        <script src="/main.js"></script>
    </body>
</html>
