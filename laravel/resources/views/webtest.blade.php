<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Permissions-Policy" content="interest-cohort=()">
    <title>Chat App Socket.io</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">

    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        body {
            background-color: #f4f6f9;
            font-family: 'figtree', sans-serif;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        ul li {
            padding: 1rem;
            background: #e9f0fa;
            border-radius: 15px;
            margin-bottom: 1rem;
            max-width: 75%;
            word-wrap: break-word;
            opacity: 0;
            transform: translateY(20px);
            animation: slideIn 0.5s ease-in-out forwards;
        }

        ul li:nth-child(2n) {
            background-color: #cfe2ff;
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-input {
            border: 1px solid #ced4da;
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-height: 50px;
            max-height: 150px;
            overflow-y: auto;
            outline: none;
            transition: box-shadow 0.3s ease;
        }

        .chat-input:focus {
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
        }

        .user {
            color: #007bff;
            font-weight: bold;
        }

        .chat-section {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            padding: 20px;
            animation: fadeIn 0.5s ease-in;
            max-width: 100%;
            width: 100%;
        }

        .chat-box {
            max-height: 300px;
            overflow-y: auto;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 10px;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }

        .chat-conent {
            height: 220px;
            overflow-y: auto;
            padding: 10px;
            margin-top: 10px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }

        .chat-conent::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: linear-gradient(to bottom, rgba(248, 249, 250, 1), rgba(248, 249, 250, 0));
            z-index: 1;
        }

        .type {
            color: #28a745;
            font-style: italic;
        }

        .notification {
            background-color: #ffc107;
            color: #212529;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            font-size: 0.9rem;
            display: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* محاذاة العناصر إلى اليمين في الشاشات الكبيرة */
        @media (min-width: 768px) {
            .chat-section {
                margin-left: auto;
                margin-right: 0;
            }
        }

        .online-status {
            font-size: 14px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 10px;
        }

        .online {
            background-color: #28a745;
            color: white;
            animation: pulse 1.5s infinite;
        }

        .btn {
            background-color: #3528a7;
            color: white;
            /* animation: pulse 1.5s infinite; */
        }

        .offline {
            background-color: #dc3545;
            color: white;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <div class='container'>
        <div class="col-lg-12">
            <a href="{{ route('dashboard.home') }}" class="btn  mb-3">
                <= Back to Products</a>
                    <div class="notification">New message received!</div>
                    <div class="chat-section">
                        <!-- Status Indicator -->
                        <div id="status" class="online-status offline">
                            Server Status: Offline

                            <button id="run-pm2" class="btn btn-primary">Run Server</button>
                        </div>
                        <div class="chat-conent border border-primary p-4">
                            <ul>
                                <li class="li d-none"></li>
                            </ul>
                        </div>
                        <div class="col-lg-12">
                            <input type="text" id="on" class="form-control" placeholder="Type a message...">
                            <input type="text" id="emit" class="form-control" placeholder="Emit a message...">
                            <button id="make" class="btn btn-primary mt-2 ">make</button>
                            <button id="resetMake" class="btn btn-danger mt-2 ">reset Make</button>
                            <button id="testSend" class="btn btn-warning mt-2 ">test send</button>
                            <button onclick="location.reload();" class="btn btn-success mt-2">test stop</button>
                        </div>
                        <div class="m-3 col-lg-12">
                            <span id="currentEmit" class="btn btn-success "></span>
                            <span id="currentOn" class="btn btn-success "></span>
                        </div>

                        <h4 class="mb-4">Realtime Chat <span class="type"></span></h4>
                        <div class="chat-box">
                            <div class="chat-input" id="chatInput" contenteditable=""></div>
                        </div>

                    </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
        crossorigin="anonymous"></script>
    <script src="https://cdn.socket.io/4.6.0/socket.io.min.js"
        integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous">
    </script>
    <script>
        $(function() {
            let idUser = localStorage.getItem('on') ?? "83"
            let emitUser = localStorage.getItem('emit') ?? "drivers"
            $('#currentEmit').text(emitUser)
            $('#currentOn').text(idUser)

            $('#emit').attr('placeholder', `Current Emit : ${emitUser}`);
            $('#on').attr('placeholder', `Current On : ${idUser}`);

            $('#resetMake').on('click', () => {
                localStorage.setItem("on", "83")
                localStorage.setItem("emit", "drivers")
                location.reload()
            })

            let make = $('#make').on('click', () => {
                let on = $('#on').val();
                on = on != '' ? on : idUser
                let emit = $('#emit').val();
                emit = emit != '' ? emit : emitUser

                localStorage.setItem("on", on)
                localStorage.setItem("emit", emit)
                location.reload()
            });

            let socket = io('https://node.busatyapp.com/', {
                query: {
                    token: '71e456b873f87f214f139799878b911a'
                }
            });
            let status = $('#status');
            let runBtn = $('#run-pm2');

            // خليه يظهر بس لما السيرفر يكون أوفلاين
            runBtn.hide();

            socket.on('connect', function() {
                status.removeClass('offline').addClass('online');
                status.text('Server Status: Online');
                runBtn.hide(); // نخفي الزر لو السيرفر شغال
            });

            socket.on('disconnect', function() {
                status.removeClass('online').addClass('offline');
                status.text('Server Status: Offline');
                runBtn.show(); // نظهر الزر لما السيرفر يكون واقف
            });

            // عند الضغط على زر تشغيل السيرفر
            runBtn.on('click', function() {
                $.ajax({
                    url: 'dashboard/run-pm2', // لازم تتأكد إنها نفس route backend
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // لو route في web middleware
                    },
                    success: function(res) {
                        alert(res.message || 'PM2 run triggered');
                    },
                    error: function(err) {
                        alert('Error running PM2: ' + err.responseJSON?.error ||
                            'Unknown error');
                    }
                });
            });


            let username = JSON.parse(localStorage.getItem("user"))?.username || 'Anonymous';
            let chatInput = $('#chatInput');

            // عند الضغط على زر الإدخال (Enter)
            chatInput.keypress(function(e) {
                let message = `<span class='user'>${username}</span>: ` + $(this).html();
                if (e.which === 13 && !e.shiftKey) {
                    socket.emit(emitUser, idUser, message);
                    chatInput.html("");
                    return false;
                }
            });

            // استقبال الرسالة وإظهارها في مكان التنبيهات
            socket.on(idUser, function(data) {
                let newMessage = $("<li>" + data + "</li>").hide().fadeIn(500); // الرسالة تظهر بشكل متدرج

                $('.chat-conent ul').append(newMessage);
                $('.chat-conent').animate({
                    scrollTop: $('.chat-conent')[0].scrollHeight
                }, 1000);
                setTimeout(function() {
                    newMessage.fadeOut(500, function() {
                        $(this).remove(); // إزالة الرسالة بعد التلاشي
                    });
                }, 5000);

            });

            // عرض رسالة جديدة
            socket.on(emitUser, function(data) {
                $('.notification').show();
                $('.notification').text(`New message from: ${data}`);
                setTimeout(() => {
                    $('.notification').fadeOut();
                }, 5000);
            });

            // عند الضغط على زر الإدخال (Enter)
            $('#testSend').on('click', function(e) {
                setInterval(() => {
                    socket.emit(emitUser, idUser,
                        'Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo possimus autem debitis! Nisi saepe, repellendus, vero asperiores veritatis magni, porro suscipit est rem qui accusantium sed reprehenderit consectetur praesentium culpa.'
                    );
                    chatInput.html("");
                }, 1000);

            });

        });
    </script>
</body>

</html>
