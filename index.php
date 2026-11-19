
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP AI Chatbot</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <button id="chatToggle" class="chat-toggle" aria-label="Open chat">
        💬
    </button>

    <section id="chatWindow" class="chat-window" hidden>
        <header class="chat-header">
            <div>
                <h2>AI Assistant</h2>
                <p>How can I help you?</p>
            </div>
            <button id="chatClose" aria-label="Close chat">✕</button>
        </header>

        <div class="chat-settings">
            <label for="category">Choose category</label>
            <select id="category">
                <option value="IT">IT & Programming</option>
                <option value="Medical">Medical & Health</option>
                <option value="ShareMarket">Share Market & Investing</option>
            </select>
        </div>

        <div id="chatMessages" class="chat-messages" aria-live="polite">
            <div class="message bot">
                Hello! Select a category and ask your question.
            </div>
        </div>

        <form id="chatForm" class="chat-form">
            <input
                id="messageInput"
                type="text"
                placeholder="Type your question..."
                maxlength="5000"
                required
            >
            <button type="submit">Send</button>
        </form>
    </section>

    <script src="js/chat.js"></script>
</body>
</html>
