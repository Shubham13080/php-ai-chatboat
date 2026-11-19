
const chatToggle = document.getElementById("chatToggle");
const chatWindow = document.getElementById("chatWindow");
const chatClose = document.getElementById("chatClose");
const chatForm = document.getElementById("chatForm");
const messageInput = document.getElementById("messageInput");
const categorySelect = document.getElementById("category");
const chatMessages = document.getElementById("chatMessages");

function openChat() {
    chatWindow.hidden = false;
    chatToggle.textContent = "✕";
    messageInput.focus();
}

function closeChat() {
    chatWindow.hidden = true;
    chatToggle.textContent = "💬";
}

chatToggle.addEventListener("click", () => {
    if (chatWindow.hidden) {
        openChat();
    } else {
        closeChat();
    }
});

chatClose.addEventListener("click", closeChat);

function addMessage(text, type) {
    const message = document.createElement("div");
    message.className = "message " + type;

    // textContent prevents AI replies from being treated as HTML.
    message.textContent = text;

    chatMessages.appendChild(message);
    chatMessages.scrollTop = chatMessages.scrollHeight;

    return message;
}

chatForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const message = messageInput.value.trim();
    const category = categorySelect.value;

    if (!message) return;

    addMessage(message, "user");
    messageInput.value = "";
    messageInput.disabled = true;

    const sendButton = chatForm.querySelector("button");
    sendButton.disabled = true;

    const loading = addMessage("Thinking...", "bot");

    try {
        const response = await fetch("chat.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ message, category })
        });

        const data = await response.json();
        loading.textContent = data.reply || "No response received.";

        if (!response.ok) {
            loading.textContent = data.reply || "Request failed.";
        }
    } catch (error) {
        loading.textContent = "Connection error. Please try again.";
        console.error(error);
    } finally {
        messageInput.disabled = false;
        sendButton.disabled = false;
        messageInput.focus();
    }
});
