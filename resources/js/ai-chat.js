/**
 * AI Chat Widget JavaScript
 * Handles chat interactions with Kiddify Assistant
 */

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const chatWidget = document.getElementById('ai-chat-widget');
    const toggleBtn = document.getElementById('ai-chat-toggle');
    const closeBtn = document.getElementById('ai-chat-close');
    const chatPanel = document.getElementById('ai-chat-panel');
    const chatMessages = document.getElementById('ai-chat-messages');
    const chatForm = document.getElementById('ai-chat-form');
    const chatInput = document.getElementById('ai-chat-input');
    const sendBtn = document.getElementById('ai-chat-send-btn');
    const clearBtn = document.getElementById('ai-chat-clear-btn');
    const typingIndicator = document.getElementById('ai-typing-indicator');

    let isOpen = false;
    let isLoading = false;
    let historyLoaded = false;

    // Toggle chat panel
    function toggleChat() {
        isOpen = !isOpen;

        if (isOpen) {
            chatPanel.classList.remove('hidden');
            toggleBtn.classList.add('hidden');
            chatInput.focus();
            updateSendButton();

            // Load history on first open
            if (!historyLoaded) {
                loadHistory();
                historyLoaded = true;
            }
        } else {
            chatPanel.classList.add('hidden');
            toggleBtn.classList.remove('hidden');
        }
    }

    // Update send button state based on input
    function updateSendButton() {
        const hasText = chatInput.value.trim().length > 0;
        sendBtn.disabled = !hasText || isLoading;

        // Update character counter
        const charCount = document.getElementById('char-count');
        if (charCount) {
            charCount.textContent = chatInput.value.length;
        }
    }

    // Load chat history
    async function loadHistory() {
        try {
            const response = await fetch('/ai/chat/history', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            const data = await response.json();

            if (data.success && data.messages.length > 0) {
                // Clear welcome message if history exists
                const welcomeMsg = chatMessages.querySelector('.ai-message');
                if (welcomeMsg) {
                    welcomeMsg.remove();
                }

                // Append history messages
                data.messages.forEach(msg => {
                    appendMessage(msg.content, msg.role, false);
                });

                scrollToBottom();
            }
        } catch (error) {
            console.error('Failed to load chat history:', error);
        }
    }

    // Send message
    async function sendMessage(message) {
        if (!message.trim() || isLoading) return;

        isLoading = true;
        sendBtn.disabled = true;
        chatInput.disabled = true;

        // Show loading spinner in button
        const sendIcon = document.getElementById('send-icon');
        const loadingSpinner = document.getElementById('loading-spinner');
        sendIcon.classList.add('hidden');
        loadingSpinner.classList.remove('hidden');

        // Append user message
        appendMessage(message, 'user');
        chatInput.value = '';

        // Show typing indicator
        typingIndicator.classList.remove('hidden');
        scrollToBottom();

        try {
            const response = await fetch('/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();

            // Hide typing indicator
            typingIndicator.classList.add('hidden');

            if (data.success) {
                // Append assistant response
                appendMessage(data.assistant_message.content, 'assistant');
            } else {
                // Show error message with appropriate styling
                const errorCode = data.code || response.status;
                const errorType = getErrorType(errorCode, data.error);

                appendMessage(
                    data.message || 'Sorry, I encountered an error. Please try again.',
                    'assistant',
                    true,
                    errorType
                );
            }

        } catch (error) {
            console.error('Chat error:', error);
            typingIndicator.classList.add('hidden');
            appendMessage(
                'The assistant is temporarily unavailable. Please try again in a moment.',
                'assistant',
                true,
                'network'
            );
        } finally {
            isLoading = false;
            chatInput.disabled = false;
            chatInput.focus();
            updateSendButton();

            // Hide loading spinner, show send icon
            const sendIcon = document.getElementById('send-icon');
            const loadingSpinner = document.getElementById('loading-spinner');
            sendIcon.classList.remove('hidden');
            loadingSpinner.classList.add('hidden');

            scrollToBottom();
        }
    }

    // Determine error type based on code and error string
    function getErrorType(code, errorString) {
        if (code === 401 || code === 403 || errorString === 'AUTH_ERROR') {
            return 'auth';
        } else if (code === 429 || errorString === 'RATE_LIMIT') {
            return 'rate_limit';
        } else if (code === 500 || errorString === 'SERVER_ERROR' || errorString === 'CONFIG_ERROR') {
            return 'server';
        } else if (errorString === 'VALIDATION_ERROR') {
            return 'validation';
        }
        return 'general';
    }

    // Append message to chat
    function appendMessage(content, role, isError = false, errorType = 'general') {
        const messageDiv = document.createElement('div');
        messageDiv.className = `${role}-message flex items-start space-x-2 animate-fade-in`;

        if (role === 'user') {
            messageDiv.classList.add('flex-row-reverse', 'space-x-reverse');
            messageDiv.innerHTML = `
                <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="user-message-bubble text-white rounded-2xl rounded-tr-none px-4 py-3 shadow-md max-w-[85%]" style="background: #5496AF;">
                    <p class="text-sm">${escapeHtml(content)}</p>
                </div>
            `;
        } else {
            if (isError) {
                // Blue-themed error message with icon
                const icon = getErrorIcon(errorType);
                messageDiv.innerHTML = `
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        ${icon}
                    </div>
                    <div class="ai-message-bubble bg-blue-50 border-2 border-blue-200 rounded-2xl rounded-tl-none px-4 py-3 shadow-md max-w-[85%]">
                        <div class="flex items-start space-x-2">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-900">
                                <p class="font-medium mb-1">AI Assistant Unavailable</p>
                                <p class="text-blue-700">${formatMessage(content)}</p>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                // Normal assistant message
                messageDiv.innerHTML = `
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background: #5496AF;">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ai-message-bubble bg-white rounded-2xl rounded-tl-none px-4 py-3 shadow-md max-w-[85%]">
                        <p class="text-sm text-gray-800">${formatMessage(content)}</p>
                    </div>
                `;
            }
        }

        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    // Get appropriate icon for error type
    function getErrorIcon(errorType) {
        const iconClass = "w-5 h-5 text-blue-600";

        switch(errorType) {
            case 'auth':
                return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>`;
            case 'rate_limit':
                return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>`;
            case 'server':
                return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                </svg>`;
            default:
                return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>`;
        }
    }

    // Format message (preserve line breaks and basic formatting)
    function formatMessage(text) {
        return escapeHtml(text)
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\n/g, '<br>');
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Scroll to bottom
    function scrollToBottom() {
        setTimeout(() => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 100);
    }

    // Clear chat history
    async function clearHistory() {
        if (!confirm('Are you sure you want to clear all chat history?')) {
            return;
        }

        try {
            const response = await fetch('/ai/chat/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            const data = await response.json();

            if (data.success) {
                // Clear all messages except welcome
                chatMessages.innerHTML = `
                    <div class="ai-message flex items-start space-x-2 animate-fade-in">
                        <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="ai-message-bubble bg-white rounded-2xl rounded-tl-none px-4 py-3 shadow-md max-w-[85%]">
                            <p class="text-sm text-gray-800">
                                Hi! I'm Kiddify Assistant. I can help you with <strong>preschool learning</strong> (letters, numbers, colors)
                                or <strong>using the Kiddify system</strong>. What would you like to know? 😊
                            </p>
                        </div>
                    </div>
                `;

                // Show success message temporarily
                appendMessage('Chat history cleared! 🎉', 'assistant');
            } else {
                appendMessage('Failed to clear chat history. Please try again.', 'assistant', true);
            }
        } catch (error) {
            console.error('Failed to clear chat:', error);
            appendMessage('An error occurred while clearing chat history.', 'assistant', true);
        }
    }

    // Event listeners
    toggleBtn.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', toggleChat);

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (message && !isLoading) {
            sendMessage(message);
        }
    });

    clearBtn.addEventListener('click', clearHistory);

    // Update button state on input
    chatInput.addEventListener('input', updateSendButton);

    // Handle Enter key (Shift+Enter for new line)
    chatInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (message && !isLoading) {
                sendMessage(message);
            }
        }
    });

    // Close panel when clicking outside (optional)
    document.addEventListener('click', function(e) {
        if (isOpen &&
            !chatPanel.contains(e.target) &&
            !toggleBtn.contains(e.target)) {
            // Uncomment to enable click-outside-to-close
            // toggleChat();
        }
    });
});
