<div id="chat-container">
    <div id="messages-container">
        <!-- Messages will be loaded here -->
    </div>

    <form id="chat-form">
        <input type="text" id="message" name="message" placeholder="Type your message...">
        <button type="submit">Send</button>
    </form>
</div>

<script>
    const messagesContainer = document.getElementById('messages-container');
    const messageInput = document.getElementById('message');
    const chatForm = document.getElementById('chat-form');

    // Replace with the receiver ID you want to chat with
    const receiverId = 2;

    // Load messages on page load
    window.onload = () => {
        loadMessages();
    };

    // Fetch messages
    function loadMessages() {
        fetch(`/chat/${receiverId}`)
            .then(response => response.json())
            .then(messages => {
                messagesContainer.innerHTML = '';
                messages.forEach(message => {
                    const messageDiv = document.createElement('div');
                    messageDiv.textContent = message.message;
                    messagesContainer.appendChild(messageDiv);
                });
            });
    }

    // Send message
    chatForm.onsubmit = (e) => {
        e.preventDefault();

        const message = messageInput.value;

        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                message: message,
                receiver_id: receiverId,
            })
        })
        .then(response => response.json())
        .then(message => {
            loadMessages(); // Reload messages
            messageInput.value = ''; // Clear input
        });
    };
</script>
