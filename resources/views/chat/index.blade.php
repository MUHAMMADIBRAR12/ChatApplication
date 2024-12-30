<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <title>Chat Application</title> 
    @vite(['resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #6f42c1;
        }
        .chat-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            background-color: #3ca9c4; 
            color: #fff;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
            position: relative; 
        }
        .main-chat {
            display: flex;
            flex-grow: 1;
            background-color: #806abd; 
        }
        .users-list {
            width: 25%;
            background-color: #6f42c1; 
            color: #fff;
            overflow-y: auto;
        }
        .users-list .user-item {
            padding: 10px 15px;
            cursor: pointer;
            list-style: none;
        }
        .users-list .user-item:hover {
            background-color: #5a36a5;
        }
        .user-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .user-item.selected {
            font-weight: bold;
            background-color: #f8d7da; 
            color: #000; 
            border-radius: 5px; 
            padding: 5px;
        }
        .tick-icon {
            color: green;
            font-size: 16px;
            margin-left: 10px;
        }

        .chat-window {
            display: flex;
            flex-direction: column;
            width: 75%;
            height: 100%;
        }
        .chat-messages {
            flex-grow: 1;
            overflow-y: auto;
            padding: 15px;
            background-color: #28b1d3;
            border: 1px solid #dee2e6;
        }
        .message-bubble {
            margin: 5px 0;
            padding: 10px 15px;
            border-radius: 15px;
            max-width: 70%;
        }
        .message-bubble.sent {
            background-color: #d1e7dd;
            align-self: flex-end;
        }
        .message-bubble.received {
            background-color: #f8d7da;
            align-self: flex-start;
        }
        .message-input {
            padding: 10px 15px;
            border-top: 1px solid #dee2e6;
            display: flex;
            align-items: center;
        }
        .send-button {
            background-color: #6f42c1; 
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
        .send-button:hover {
            background-color: #5a36a5;
        }
        .send-button i {
            font-size: 1.2rem;
        }
        .notifications-container {
            position: absolute;
            top: 10px;
            right: 15px;
            width: 300px;
            z-index: 1000;
            text-align: right;
        }
        .notifications-container .notification .close-notification {
            position: absolute;
            top: 5px;
            left: 10px;
            border: none;
            background: none;
            font-size: 26px;
            cursor: pointer;
        }
        .notification {
            background-color: #6f42c1;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="chat-header d-flex justify-content-between align-items-center position-relative">
        <div class="align-items: center">CHAT APPLICATION</div>
        <div id="notification" class="notifications-container close-notification"></div>
        <div>
            <button id="export_messages" class="btn btn-light">Export Messages</button>
        </div>
    </div>    
    <div class="container-fluid chat-container">
        <div class="main-chat">
            <div class="users-list" id="users">
                <h5 class="p-3">Users</h5>
                <ul>
                    @foreach ($users as $user)
                    <li class="user-item" data-user-id="{{ $user->id }}">
                        {{ $user->name }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="chat-window">
                <div class="chat-messages" id="messages"></div>
                <div class="message-input">
                    <input type="text" id="message_input" class="form-control me-2" placeholder="Type a message...">
                    <button type="submit" class="send-button" id="send_message">
                        <i class="bi bi-send"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>
</body>
</html>
