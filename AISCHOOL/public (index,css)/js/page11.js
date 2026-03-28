document.addEventListener('DOMContentLoaded', () => {
    const chatForm = document.getElementById('chatForm');
    const chatBox = document.getElementById('chatBox');
    const userInput = document.getElementById('userInput');
    const chatHistoryList = document.getElementById('chatHistoryList');
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const newChatButton = document.getElementById('newChatButton');

    // Declare and initialize currentSessionId
    let currentSessionId = localStorage.getItem('currentSessionId') || null;

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
        sidebarToggle.classList.toggle('hidden');
        sidebarToggle.textContent = sidebar.classList.contains('hidden') ? '◀' : '➤'; // Update arrow direction
    });

    async function fetchWithErrorHandling(url, options) {
        try {
            const response = await fetch(url, options);
            if (!response.ok) {
                const errorText = await response.text();
                console.error("Error response:", errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error("Fetch error:", error);
            throw error;
        }
    }

    async function loadSidebarSessions() {
        try {
            const data = await fetchWithErrorHandling('/AISCHOOL/app%20MVC/Controllers/page11.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'getSessions' })
            });

            chatHistoryList.innerHTML = ""; // Clear the sidebar
            data.sessions.forEach(session => {
                const listItem = document.createElement('li');
                listItem.textContent = session.title || 'دردشة جديدة';
                listItem.className = "chat-item";
                listItem.addEventListener('click', async () => {
                    currentSessionId = session.session_id;
                    localStorage.setItem('currentSessionId', currentSessionId); // Save session ID to localStorage
                    await loadCurrentSession(); // Load the selected session
                });
                chatHistoryList.appendChild(listItem);
            });
        } catch (error) {
            console.error("Error loading sidebar sessions:", error);
            alert("❌ حدث خطأ أثناء تحميل الجلسات. يرجى المحاولة مرة أخرى."); // Notify the user
        }
    }

    async function loadCurrentSession() {
        if (!currentSessionId) return;

        try {
            const messagesData = await fetchWithErrorHandling('/AISCHOOL/app%20MVC/Controllers/page11.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'getMessages', sessionId: currentSessionId })
            });

            chatBox.innerHTML = ""; // Clear the chat box
            messagesData.messages.forEach(({ text, sender }) => {
                displayMessage(text, sender, false);
            });
        } catch (error) {
            console.error("Error loading current session:", error);
        }
    }

    async function saveSession(title, messages) {
        try {
            if (!currentSessionId) {
                currentSessionId = 'session_' + Date.now(); // Generate a unique session ID
            }

            if (!title) {
                const timestamp = new Date().toLocaleString('en-US', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                });
                title = `دردشة ${timestamp}`; // Default title with timestamp
            }

            if (!title || !Array.isArray(messages)) {
                console.error("Invalid session data:", { currentSessionId, title, messages });
                throw new Error("Invalid session data.");
            }

            const data = await fetchWithErrorHandling('/AISCHOOL/app%20MVC/Controllers/page11.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'saveSession', sessionId: currentSessionId, title, messages })
            });

            console.log("Save session response:", data); // Log the response
            if (!data.success) {
                console.error("Error saving session:", data.error);
            }
        } catch (error) {
            console.error("Error saving session:", error);
        }
    }

    async function deleteChat(sessionId) {
        if (!confirm('هل أنت متأكد أنك تريد حذف هذه الدردشة؟')) return;

        try {
            const response = await fetch('/AISCHOOL/app%20MVC/Controllers/page11.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'deleteChat', sessionId })
            });

            if (!response.ok) {
                throw new Error('Failed to delete chat');
            }

            const result = await response.json();
            if (result.success) {
                alert('تم حذف الدردشة بنجاح');
                location.reload(); // Reload the page to update the chat list
            } else {
                alert('فشل في حذف الدردشة');
            }
        } catch (error) {
            console.error('Error deleting chat:', error);
            alert('حدث خطأ أثناء حذف الدردشة');
        }
    }

    function displayMessage(message, sender, save = true) {
        if (!['user', 'bot'].includes(sender)) {
            console.warn("Unknown sender:", sender);
            return;
        }

        const messageElement = document.createElement('div');
        messageElement.classList.add('message');
        messageElement.classList.add(sender === 'user' ? 'user-message' : 'bot-message');

        if (sender === 'bot') {
            // Convert Markdown to clean text with formatting (e.g., bold, lists)
            const cleanMessage = convertMarkdownToFormattedText(message);
            messageElement.innerHTML = `
                <div style="direction: rtl; text-align: right; unicode-bidi: embed;">${cleanMessage}</div>
                <div class="timestamp">${new Date().toLocaleTimeString()}</div>
            `;
        } else {
            // Render the user's message as plain text
            messageElement.innerHTML = `
                <div style="direction: rtl; text-align: right; unicode-bidi: embed;">${escapeHtml(message)}</div>
                <div class="timestamp">${new Date().toLocaleTimeString()}</div>
            `;
        }

        chatBox.appendChild(messageElement);
        chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to the bottom

        if (save) {
            const sessions = JSON.parse(localStorage.getItem("chatSessions")) || [];
            const currentSession = sessions.find(session => session.id === currentSessionId);
            if (currentSession) {
                currentSession.messages.push({ text: message, sender });
                saveSession(currentSession.title, currentSession.messages);
            }
        }
    }

    /**
     * Converts a Markdown string to clean text with formatting.
     * Preserves bold, lists, and other desired formatting.
     * @param {string} markdown - The Markdown string to convert.
     * @returns {string} - The formatted text version of the input.
     */
    function convertMarkdownToFormattedText(markdown) {
        if (!markdown) return '';

        // Use Showdown.js to convert Markdown to HTML
        const converter = new showdown.Converter();
        const html = converter.makeHtml(markdown);

        // Return the HTML as-is to preserve formatting
        return html;
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, (m) => map[m]);
    }

    newChatButton.addEventListener('click', async () => {
        currentSessionId = 'session_' + Date.now();
        localStorage.setItem('currentSessionId', currentSessionId); // Save new session ID to localStorage
        const timestamp = new Date().toLocaleString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });

        const sessionTitle = `دردشة ${timestamp}`; // Name the chat with the current time and date
        await saveSession(sessionTitle, []);
        chatBox.innerHTML = ""; // Clear the chat box
        loadSidebarSessions(); // Update the sidebar
    });

    chatForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const userInputValue = userInput.value.trim();
        if (!userInputValue) return;

        displayMessage(userInputValue, 'user');
        userInput.value = ''; // Clear the input field after sending the message

        try {
            const sessions = JSON.parse(localStorage.getItem("chatSessions")) || [];
            let currentSession = sessions.find(session => session.id === currentSessionId);

            // If currentSession is undefined, initialize a new session
            if (!currentSession) {
                currentSession = { id: currentSessionId, title: "دردشة جديدة", messages: [] };
                sessions.push(currentSession);
                localStorage.setItem("chatSessions", JSON.stringify(sessions));
            }

            // Add the user's message to the current session
            currentSession.messages.push({ role: 'user', content: userInputValue });
            localStorage.setItem("chatSessions", JSON.stringify(sessions)); // Update localStorage

            const data = await fetchWithErrorHandling('/AISCHOOL/app%20MVC/Controllers/page11.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'getResponse', messages: currentSession.messages })
            });

            console.log("API Response:", data); // Log the API response

            if (data.reply) {
                displayMessage(data.reply, 'bot');
            } else {
                console.warn("No reply found in response:", data);
                displayMessage('⚠️ لم يتم العثور على رد.', 'bot');
            }
        } catch (error) {
            console.error("Error during chat submission:", error);
            displayMessage('❌ حدث خطأ، حاول مجدداً.', 'bot');
        }
    });

    document.getElementById('deleteAllChatsButton').addEventListener('click', async () => {
        if (!confirm('هل أنت متأكد أنك تريد حذف جميع الدردشات؟')) return;

        try {
            const response = await fetch('/AISCHOOL/app%20MVC/Controllers/page11.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'deleteAllChats' })
            });

            if (!response.ok) {
                throw new Error('Failed to delete all chats');
            }

            const result = await response.json();
            if (result.success) {
                alert('تم حذف جميع الدردشات بنجاح');
                localStorage.removeItem('currentSessionId'); // Clear the saved session ID
                location.reload(); // Reload the page to update the chat list
            } else {
                alert('فشل في حذف جميع الدردشات');
            }
        } catch (error) {
            console.error('Error deleting all chats:', error);
            alert('حدث خطأ أثناء حذف جميع الدردشات');
        }
    });

    loadSidebarSessions(); // Load the sidebar sessions
    loadCurrentSession(); // Load the current session if it exists
});
