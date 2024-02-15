/**
 * NOTE: okay, this source code is shit, but this task has a limitation for 6 hours
 * and I have done it for ~3 hours. I think there's not any other explanation for this :>
 * - Yshmeel
 */



// allow scripts to be init after dom loaded
document.addEventListener("DOMContentLoaded", () => {
    const $loadingScreen = document.getElementById('loading-screen');
    const $loginScreen = document.getElementById('login-screen');
    const $appScreen = document.getElementById('app-screen');

    /* Close all screens before opening a new */
    const closeAllScreens = () => {
        $loginScreen.classList.add('screen-hidden');
        $loadingScreen.classList.add('screen-hidden');
        $appScreen.classList.add('screen-hidden');
    };

    /* Separate all screens by modules */

    /* --- LOGIN MODULE --- */
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

    /* Main office application */
    const application = () => {
        let users = [];
        let rooms = [];
        let currentUser = null;
        let isDragging = false;

        /** @type {HTMLCanvasElement} */
        const $canvas = document.getElementById('office-canvas');

        const $officeImageAsset = document.getElementById('office-image');

        const updateUserInfo = (isFirst, user) => {
            if(isFirst) {
                $appScreen.querySelector('#user-avatar')
                    .setAttribute('src', `/avatars/avatar-${user.avatar_id.toString().padStart(2, '0')}.svg`)

                $appScreen.querySelector('#username').innerHTML = user.name;
            }

            const $userCreatedAtTimer = document.querySelector('#user-created-at-timer');
            const duration = calculateTimeDifference(user.created_at);

            $userCreatedAtTimer.innerText = `${parseInt(duration.hours) + 1}h ${Math.abs(duration.minutes) - 1}m`;
        };

        /*
        Does HTTP routine every interval iteration and recreates page from scratch
         */
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

            updateUserInfo(isFirst, response.user);

            // Clear rooms container to render rooms again
            const $roomsContainer = $appScreen.querySelector('#rooms-container');
            $roomsContainer.innerHTML = '';


            response.rooms.forEach((room) => {
                const element = document.createElement('div');
                element.classList.add('app__aside__room');
                element.classList.add('p-2', 'px-6', 'bg-gray-100', 'border');
                element.innerHTML = `<b class="mb-2 text-xl">${room.name} (${room.users_count}/${room.max_users})</b><ul></ul>`;

                if(room.users_count === room.max_users) {
                    element.classList.add('room-full')
                }

                const $ulContainer = element.querySelector('ul');

                room.users.forEach((user) => {
                    const $element = document.createElement('li');
                    $element.innerHTML = `${user.name}`;

                    $ulContainer.classList.add('p-2', 'px-4');
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
                    const date = new Date(message.created_at);

                    const element = document.createElement('div');
                    element.classList.add('app__aside__chat__item', 'mb-3');

                    element.innerHTML = `
                    <div class="app__aside__chat__item--head flex justify-between">
                        <b class="mb-1">${message.user.name}</b>
                        <span>${(date.getDate() + 1).toString().padStart(2, '0')} Feb</span>
                    </div>

                    <p>${message.message}</p>`;

                    $chatContainer.appendChild(element);
                })
            }

            // Assign users and rooms to its own variables, to assign them later
            users = response.users;
            rooms = response.rooms;

            if(!isDragging) {
                currentUser = response.user;
            }

            // Every routine we need to draw canvas again, if some data is cahnged
            drawCanvasOffice();
        };

        /**
         * Logout user by clicking Logout button
         * It is a handler for a button
         * @returns {Promise<void>}
         */
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

        /**
         * Sending messages in chat.
         * It is a handler for Send button
         * @returns {Promise<void>}
         */
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

        // --- CANVAS STRUCTURE ---

        let mouseX, mouseY = 0;

        /* Drag&drop: writing mouseX and mouseY in variables.
        330 is a magic number
         */
        $canvas.onmousemove = function (e) {
            mouseX = e.clientX - 330;
            mouseY = e.clientY;

            if(isDragging) {
                currentUser.x = mouseX;
                currentUser.y = mouseY;
            }

            drawCanvasOffice();
        };

        /* Drag&drop: user is pressing LMB, and we need to turn dragging mode if user click on itself */
        $canvas.onmousedown = function(e) {
            let minX = currentUser.x;
            let maxX = currentUser.x + 48;
            let minY = currentUser.y;
            let maxY = currentUser.y + 64;

            if(!(mouseX >= minX && mouseX <= maxX && mouseY >= minY && mouseY <= maxY)) {
                return;
            }

            isDragging = true;
            currentUser.x = mouseX;
            currentUser.y = mouseY;
        };

        /* Drag&drop: user is releasing LMB, and we need to check in which room user placed its avatar */
        $canvas.onmouseup = function(e) {
            if(!isDragging) {
                return;
            }

            isDragging = false;

            const context = $canvas.getContext('2d');
            rooms.forEach((room) => {
                // @todo MASSIVE DRY AS 373-432. fix later @wontfix
                let points = [];

                if(room.points.length === 0) {
                    points = [
                        { x: room.x, y: room.y },
                        { x: room.x + room.width, y: room.y },
                        { x: room.x + room.width, y: room.y + room.height },
                        { x: room.x, y: room.y + room.height },
                    ];
                } else {
                    points = room.points;
                }

                let minX = 0;
                let maxX = 0;
                let minY = 0;
                let maxY = 0;

                points.forEach((v) => {
                    if(minX === 0 || minX > v.x) {
                        minX = v.x;
                    }

                    if(v.x > maxX) {
                        maxX = v.x;
                    }

                    if(minY === 0 || minY > v.y) {
                        minY = v.y;
                    }

                    if(v.y > maxY) {
                        maxY = v.y;
                    }
                })

                context.beginPath();
                context.moveTo(points[0].x, points[0].y);
                for (let i = 1; i < points.length; i++) {
                    context.lineTo(points[i].x, points[i].y);
                }

                context.fillStyle = 'rgba(0, 0, 0, .3)';
                context.closePath();
                if(context.isPointInPath(mouseX, mouseY)) {
                    if(room.users_count >= room.max_users && currentUser.selected_room_id !== room.id) {
                        alert('This room is busy');
                        return;
                    }

                    fetch("/api/select-room", {
                        method: "PATCH",
                        headers: {
                            Accept: "application/json",
                            'Content-Type': 'application/json',
                            Authorization: `Bearer ${localStorage.getItem('token')}`
                        },
                        body: JSON.stringify({
                            room_id: room.id,
                            x: mouseX,
                            y: mouseY,
                        })
                    });
                }
            });
        };

        const drawCanvasOffice = () => {
            // Static width and height because we do not need adaptive on <1920w devices
            $canvas.width = 1560;
            $canvas.height = 800;

            const context = $canvas.getContext('2d');
            context.drawImage($officeImageAsset, 0, 0, $canvas.width, $canvas.height);

            context.textAlign = 'center';
            context.font = 'normal normal bold 24px Arial';

            [currentUser, ...users.filter((v) => v.id !== currentUser.id)]
                .forEach((user) => {
                    context.drawImage(document.querySelector(`.asset-avatar[data-id="${user.avatar_id}"]`), user.x, user.y, 48, 48);
                    context.fillStyle = user.color;
                    context.fillText(user.name, user.x + 24, user.y + 64, 150);
                });

            rooms.forEach((room) => {
                let points = [];

                if(room.points.length === 0) {
                    points = [
                        { x: room.x, y: room.y },
                        { x: room.x + room.width, y: room.y },
                        { x: room.x + room.width, y: room.y + room.height },
                        { x: room.x, y: room.y + room.height },
                    ];
                } else {
                    points = room.points;
                }

                let minX = 0;
                let maxX = 0;
                let minY = 0;
                let maxY = 0;

                points.forEach((v) => {
                    if(minX === 0 || minX > v.x) {
                        minX = v.x;
                    }

                    if(v.x > maxX) {
                        maxX = v.x;
                    }

                    if(minY === 0 || minY > v.y) {
                        minY = v.y;
                    }

                    if(v.y > maxY) {
                        maxY = v.y;
                    }
                })

                if(!(mouseX >= minX && mouseX <= maxX && mouseY >= minY && mouseY <= maxY)) {
                    return;
                }

                context.beginPath();
                context.moveTo(points[0].x, points[0].y);
                for (let i = 1; i < points.length; i++) {
                    context.lineTo(points[i].x, points[i].y);
                }

                context.closePath();
                if(context.isPointInPath(mouseX, mouseY) && isDragging) {

                    context.fillStyle = 'rgba(207, 64, 64, .6)';
                    context.fill();

                    context.font = 'normal normal bold 16px Arial';
                    context.textAlign = 'left';
                    context.fillStyle = '#000';
                    context.fillText(room.name, points[0].x + 10, points[0].y + 40);

                    context.stroke();
                }
            });
        }

        $appScreen.querySelector('aside form').addEventListener('submit', (e) => {
            e.preventDefault();
            sendMessage();
        });

        // initial scripts

        let intervalID = setInterval(doRoutine, 300);
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


// utils

/**
 * Calculates difference between current date and target date to get hours and minutes as duration
 * @param date
 * @returns {{hours: string, minutes: string}|null}
 */
function calculateTimeDifference(date) {
    try {
        const targetDate = new Date(date);
        const currentDate = new Date();

        const difference = targetDate - currentDate;
        const seconds = Math.floor(difference / 1000);

        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);

        return {
            hours: Math.abs(hours).toString().padStart(2, '0'),
            minutes: minutes.toString().padStart(2, '0'),
        };
    } catch (error) {
        return null;
    }
}
