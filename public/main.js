// allow scripts to be init after dom loaded
document.addEventListener("DOMContentLoaded", () => {
    const $loadingScreen = document.getElementById('loading-screen');
    const $loginScreen = document.getElementById('login-screen');
    const $appScreen = document.getElementById('app-screen');

    const closeAllScreens = () => {
        $loginScreen.classList.add('screen-hidden');
        $loadingScreen.classList.add('screen-hidden');
        $appScreen.classList.add('screen-hidden');
    };

    const cleanUsersDocument = (users) => {
        return users.map((v) => ({
            id: v.id,
            name: v.id,
            selected_room_id: v.selected_room_id,
            x: v.x,
            y: v.y
        }))
    };

    // small division by modules??

    const login = () => {
        const $loginForm = document.getElementById('login-form');

        const unblockLoginButton = () => {
            const $nameField = $loginForm.querySelector('#name');
            const $selectedAvatar = $loginForm.querySelector('input[type="radio"]:checked');

            const $loginButton = $loginForm.querySelector('button');

            if($selectedAvatar === null || $nameField.value?.trim().length === 0) {
                $loginButton.setAttribute('disabled', 'disabled');
            } else {
                $loginButton.removeAttribute('disabled');
            }
        };

        $loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const $nameField = $loginForm.querySelector('#name');
            const $selectedAvatar = $loginForm.querySelector('input[type="radio"]:checked');

            const $loginButton = $loginForm.querySelector('button');

            if($selectedAvatar === null || $nameField.value?.trim().length === 0) {
                alert('Please, make sure your inputs are not empty');
                return;
            }

            $loginButton.setAttribute('disabled', 'disabled');

            const request = await fetch("/api/user", {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: $nameField.value,
                    avatar_id: $selectedAvatar.value,
                })
            });

            const response = await request.json();
            const token = response.token;

            localStorage.setItem('token', token);

            application();
        });

        $loginForm.querySelector('#name').addEventListener('keyup', () => {
            unblockLoginButton();
        });

        Array.from($loginForm.querySelectorAll('input[type="radio"]'))
            .forEach((e) => {
                e.addEventListener('change', () => {
                    unblockLoginButton();
                });
            });
    };

    const application = () => {
        let isDragging = false;
        let users = [];

        const doRoutine = async (isFirst = false) => {
            const request = await fetch("/api/routine", {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
            });

            const response = await request.json();

            if(isFirst) {
                $appScreen.querySelector('#user-avatar')
                    .setAttribute('src', `/avatars/avatar-${response.user.avatar_id.toString().padStart(2, '0')}.svg`)

                $appScreen.querySelector('#username').innerHTML = response.user.name;
            }

            const $roomsContainer = $appScreen.querySelector('#rooms-container');
            $roomsContainer.innerHTML = '';

            response.rooms.forEach((room) => {
                const element = document.createElement('div');
                element.classList.add('app__aside__room');
                element.innerHTML = `<b class="mb-2">${room.name} (${room.users_count}/${room.max_users})</b><ul></ul>`;

                if(room.users_count === room.max_users) {
                    element.classList.add('room-full')
                }

                const $ulContainer = element.querySelector('ul');

                room.users.forEach((user) => {
                    const $element = document.createElement('li');
                    $element.innerHTML = user.name;

                    $ulContainer.append($element);
                });

                $roomsContainer.appendChild(element);
            });

            const $chatContainer = $appScreen.querySelector('#chat-container');
            $chatContainer.innerHTML = '';

            if(response.messages.length === 0) {
                $chatContainer.innerHTML = `<span>No messages found</span>`;
            } else {
                response.messages.forEach((message) => {
                    const element = document.createElement('div');
                    element.classList.add('app__aside__chat__item');

                    element.innerHTML = `
                    <div class="app__aside__chat__item--head">
                        <b>${message.user.name}</b>
                        <span>04 Feb</span>
                    </div>

                    <p>${message.message}</p>`;

                    $chatContainer.appendChild(element);
                })
            }

            if(!isDragging && JSON.stringify(cleanUsersDocument(users)) !== JSON.stringify(cleanUsersDocument(response.users))) {
                users = response.users;

                const $officeUsers = $appScreen.querySelector('#office-users');
                $officeUsers.innerHTML = '';

                response.users.forEach((user) => {
                    const element = document.createElement('div');
                    element.classList.add('app__office__user');
                    element.innerHTML = `<img src="/avatars/avatar-${user.avatar_id.toString().padStart(2, '0')}.svg" alt="user-avatar" />`

                    $officeUsers.appendChild(element);
                });
            }
        };

        let intervalID = setInterval(doRoutine, 300);

        const logout = async () => {
            window.clearInterval(intervalID);

            await fetch("/api/user", {
                method: "DELETE",
                headers: {
                    Accept: "application/json",
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
            });

            localStorage.removeItem('token');
            closeAllScreens();
            $loginScreen.classList.remove('screen-hidden');
        };

        const sendMessage = async() => {
            const $input = $appScreen.querySelector('aside form input');
            const value = $appScreen.querySelector('aside form input')?.value;

            if(value.trim().length === 0) {
                return;
            }

            await fetch("/api/send-message", {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({
                    message: value
                }),
            });

            $input.value = "";
        };

        $appScreen.querySelector('aside form').addEventListener('submit', (e) => {
            e.preventDefault();
            sendMessage();
        });

        // initial scripts

        doRoutine(true);

        setTimeout(() => {
            closeAllScreens();
            $appScreen.classList.remove('screen-hidden');
        }, 300);

        $appScreen.querySelector('#logout-button').addEventListener('click', () => {
            logout();
        });
    };

    login();

    if(!!localStorage.getItem('token')) {
        application();
    } else {
        setTimeout(() => {
            closeAllScreens();
            $loginScreen.classList.remove('screen-hidden');
        }, 300);
    }
});
