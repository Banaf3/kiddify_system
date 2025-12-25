<!-- AI Chat Widget Component -->
<div id="ai-chat-widget" class="fixed z-50">
    <!-- Floating Chat Button (Mid-Right) -->
    <button id="ai-chat-toggle"
        class="ai-chat-toggle-btn text-white rounded-full shadow-2xl hover:shadow-[0_0_30px_rgba(84,150,175,0.6)] transition-all duration-300 flex items-center justify-center group"
        style="background: #5496AF;" aria-label="Open Kiddify Assistant">
        <!-- Chat Icon -->
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
            </path>
        </svg>

        <!-- Pulse Animation -->
        <span class="absolute inline-flex h-full w-full rounded-full opacity-75 animate-ping"
            style="background: #5496AF;"></span>
    </button>

    <!-- Chat Panel (Hidden by default) -->
    <div id="ai-chat-panel"
        class="ai-chat-panel hidden bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden border-2 border-blue-200">

        <!-- Header -->
        <div class="ai-chat-header text-white px-5 py-4 flex items-center justify-between" style="background: #5496AF;">
            <div class="flex items-center space-x-3">
                <!-- Logo/Icon -->
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="color: #5496AF;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg">Kiddify Assistant</h3>
                    <p class="text-xs opacity-90">Here to help! 🌟</p>
                </div>
            </div>

            <!-- Close Button -->
            <button id="ai-chat-close"
                class="text-white hover:bg-white/20 rounded-full p-2 transition-colors duration-200"
                aria-label="Close chat">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div id="ai-chat-messages" class="ai-chat-messages flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
            <!-- Welcome Message -->
            <div class="ai-message flex items-start space-x-2 animate-fade-in">
                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                    style="background: #5496AF;">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="ai-message-bubble bg-white rounded-2xl rounded-tl-none px-4 py-3 shadow-md max-w-[85%]">
                    <p class="text-sm text-gray-800">
                        Hi! I'm Kiddify Assistant. I can help you with <strong>preschool learning</strong> (letters,
                        numbers, colors)
                        or <strong>using the Kiddify system</strong>. What would you like to know? 😊
                    </p>
                </div>
            </div>
        </div>

        <!-- Typing Indicator (Hidden by default) -->
        <div id="ai-typing-indicator" class="hidden px-4 pb-2">
            <div class="flex items-start space-x-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                    style="background: #5496AF;">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="bg-white rounded-2xl rounded-tl-none px-4 py-3 shadow-md">
                    <div class="flex space-x-2">
                        <div class="w-2 h-2 rounded-full animate-bounce"
                            style="background: #5496AF; animation-delay: 0s"></div>
                        <div class="w-2 h-2 rounded-full animate-bounce"
                            style="background: #5496AF; animation-delay: 0.2s"></div>
                        <div class="w-2 h-2 rounded-full animate-bounce"
                            style="background: #5496AF; animation-delay: 0.4s"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="ai-chat-input-area border-t border-gray-200 bg-white p-4">
            <form id="ai-chat-form">
                <!-- Input Container -->
                <div
                    class="flex items-center gap-2 bg-gray-50 rounded-full px-4 py-2 border border-gray-200 focus-within:border-gray-300 transition-all">
                    <!-- Input Field -->
                    <input type="text" id="ai-chat-input" placeholder="Enter your message..." maxlength="800"
                        class="flex-1 bg-transparent border-none outline-none text-gray-700 text-sm placeholder-gray-400 py-1"
                        autocomplete="off">

                    <!-- Character Counter and Send Button -->
                    <div class="flex items-center gap-2">
                        <span id="char-counter" class="text-xs text-gray-400 font-medium">
                            <span id="char-count">0</span>/800
                        </span>

                        <!-- Send Button (Circular) -->
                        <button type="submit" id="ai-chat-send-btn"
                            class="w-10 h-10 rounded-full text-white transition-all duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0 shadow-md hover:shadow-lg"
                            style="background: #5496AF;"
                            onmouseover="if(!this.disabled) this.style.background='#4a8399'"
                            onmouseout="this.style.background='#5496AF'" disabled>
                            <svg id="send-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
                            </svg>
                            <svg id="loading-spinner" class="w-5 h-5 animate-spin hidden" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Clear Chat Button -->
            <button id="ai-chat-clear-btn"
                class="mt-3 text-xs text-gray-500 transition-colors duration-200 flex items-center space-x-1"
                onmouseover="this.style.color='#5496AF'" onmouseout="this.style.color=''">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
                <span>Clear chat history</span>
            </button>
        </div>
    </div>
</div>

<style>
    /* Floating button positioning - Mid-Right */
    .ai-chat-toggle-btn {
        position: fixed;
        top: 50%;
        right: 1.5rem;
        transform: translateY(-50%);
        width: 60px;
        height: 60px;
        z-index: 1000;
    }

    /* Chat panel positioning */
    .ai-chat-panel {
        position: fixed;
        top: 50%;
        right: 1.5rem;
        transform: translateY(-50%);
        width: 420px;
        height: 650px;
        max-height: 90vh;
    }

    /* Messages container */
    .ai-chat-messages {
        scrollbar-width: thin;
        scrollbar-color: #d1d5db #f9fafb;
    }

    .ai-chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    .ai-chat-messages::-webkit-scrollbar-track {
        background: #f9fafb;
    }

    .ai-chat-messages::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }

    .ai-chat-messages::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    /* Smooth animations */
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }

    /* Error message styling */
    .ai-message-bubble.error-message {
        background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        border: 2px solid #93C5FD;
    }

    .ai-message-bubble a {
        color: #2563EB;
        text-decoration: underline;
        font-weight: 500;
    }

    .ai-message-bubble a:hover {
        color: #1E40AF;
    }

    /* Responsive design */
    @media (max-width: 640px) {
        .ai-chat-toggle-btn {
            width: 50px;
            height: 50px;
            right: 1rem;
        }

        .ai-chat-panel {
            width: calc(100vw - 2rem);
            right: 1rem;
            height: 500px;
            max-height: 80vh;
        }
    }

    @media (max-width: 768px) {
        .ai-chat-toggle-btn {
            top: auto;
            bottom: 1.5rem;
            transform: none;
        }

        .ai-chat-panel {
            top: auto;
            bottom: 1.5rem;
            transform: none;
            height: 550px;
        }
    }
</style>
