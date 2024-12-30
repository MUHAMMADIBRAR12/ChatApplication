import axios from 'axios';
import './bootstrap';

const usersList = document.querySelectorAll('.user-item');
const messagesEl = document.getElementById('messages');
const messageInput = document.getElementById('message_input');
const sendMessageButton = document.getElementById('send_message');
const exportButton = document.getElementById('export_messages');
const userId = document.head.querySelector('meta[name="user-id"]').content;
const userItems = document.querySelectorAll('.user-item');

let activeUserId = null;

usersList.forEach((user) => {
    user.addEventListener('click', function () {
        activeUserId = this.getAttribute('data-user-id');
        messagesEl.innerHTML = '<p>Loading messages...</p>';

        axios.get(`/fetch-messages/${activeUserId}`)
            .then((response) => {
                messagesEl.innerHTML = '';
                response.data.forEach((message) => {
                    const messageBubble = document.createElement('div');
                    messageBubble.classList.add('message-bubble', message.sender_id == activeUserId ? 'received' : 'sent');
                    messageBubble.textContent = message.message;
                    messagesEl.appendChild(messageBubble);
                });
            })
            .catch((error) => {
                console.error(error);
                messagesEl.innerHTML = '<p>Failed to load messages.</p>';
            });
    });
});
userItems.forEach((userItem) => {
    userItem.addEventListener('click', () => {
        userItems.forEach((item) => {
            item.classList.remove('selected');
        });
        userItem.classList.add('selected');
    });
});
sendMessageButton.addEventListener('click', function () {
    if (!activeUserId) {
        alert('Please select a user to chat with.');
        return;
    }

    const message = messageInput.value.trim();
    if (!message) {
        alert('Please type a message.');
        return;
    }

    axios.post('/sendmessage', {
        receiver_id: activeUserId,
        message: message,
    }).then((response) => {
        if (response.data.success) {
            const messageBubble = document.createElement('div');
            messageBubble.classList.add('message-bubble', 'sent');
            messageBubble.textContent = message;
            messagesEl.appendChild(messageBubble);
            messageInput.value = '';
        }
    }).catch((error) => {
        console.error(error);
        alert('Failed to send message.');
    });
});
 // Export Messages
 exportButton.addEventListener('click', function () {
    if (!activeUserId) {
        alert('Please select a user to export messages.');
        return;
    }
    const exportUrl = `/export-messages/${activeUserId}`;
    window.location.href = exportUrl;
});

function loadChat(senderId) {
    activeUserId = senderId;
    axios.get(`/fetch-messages/${senderId}`)
        .then((response) => {
            const messagesEl = document.getElementById('messages');
            messagesEl.innerHTML = '';
            response.data.forEach((message) => {
                const messageBubble = document.createElement('div');
                messageBubble.classList.add('message-bubble', message.sender_id === activeUserId ? 'received' : 'sent');
                messageBubble.textContent = message.message;
                messagesEl.appendChild(messageBubble);
            });
        })
        .catch((error) => {
            console.error('Failed to load chat:', error);
        });
}

window.Echo.channel('chat').listen('.MessageSent', (event) => {
        const messageBubble = document.createElement('div');
        messageBubble.classList.add('message-bubble', 'received');
        messageBubble.textContent = event.message.message;
        messagesEl.appendChild(messageBubble);
});

window.Echo.channel(`notification`)
    .listen('.SentNotification', (data) => {
        console.log('New notification:', data);
        const notificationContainer = document.getElementById('notification');

        const newNotification = document.createElement('div');
        newNotification.classList.add('notification');
        newNotification.innerHTML = `
            <strong>New Message:</strong> ${data.message} <br>
            <small>${data.time}</small>
            <button class="close-notification" aria-label="Close">×</button>
        `;
        notificationContainer.appendChild(newNotification);

        const closeButton = newNotification.querySelector('.close-notification');
        if (closeButton) {
            closeButton.addEventListener('click', () => {
                newNotification.remove();
            });
        } else {
            console.error('Close button not found inside notification.');
        }
        setTimeout(() => newNotification.remove(), 5000);
    });


