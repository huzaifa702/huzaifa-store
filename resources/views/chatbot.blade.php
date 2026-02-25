@extends('layouts.app')
@section('title', 'AI Shopping Assistant')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-950 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-400 to-indigo-600 flex items-center justify-center text-3xl shadow-lg shadow-brand-500/30">🤖</div>
                <div class="text-left">
                    <h1 class="text-3xl font-bold text-white">Huzaifa AI Agent</h1>
                    <p class="text-brand-400 text-sm font-medium">● Online — Your intelligent shopping assistant</p>
                </div>
            </div>
            <p class="text-gray-400 max-w-xl mx-auto">Ask me about products, get recommendations, search by image, listen to responses, and receive exclusive deals via email!</p>
        </div>

        <!-- Feature Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-slate-900/60 backdrop-blur border border-slate-800 rounded-2xl p-4 text-center hover:border-brand-500/50 transition-all hover:shadow-lg hover:shadow-brand-500/10">
                <div class="text-2xl mb-2">🔍</div>
                <p class="text-sm font-semibold text-gray-200">Product Search</p>
                <p class="text-xs text-gray-500 mt-1">Find any product instantly</p>
            </div>
            <div class="bg-slate-900/60 backdrop-blur border border-slate-800 rounded-2xl p-4 text-center hover:border-brand-500/50 transition-all hover:shadow-lg hover:shadow-brand-500/10">
                <div class="text-2xl mb-2">📸</div>
                <p class="text-sm font-semibold text-gray-200">Image Analysis</p>
                <p class="text-xs text-gray-500 mt-1">Upload photos to find matches</p>
            </div>
            <div class="bg-slate-900/60 backdrop-blur border border-slate-800 rounded-2xl p-4 text-center hover:border-brand-500/50 transition-all hover:shadow-lg hover:shadow-brand-500/10">
                <div class="text-2xl mb-2">🔊</div>
                <p class="text-sm font-semibold text-gray-200">Voice Responses</p>
                <p class="text-xs text-gray-500 mt-1">Listen to AI replies aloud</p>
            </div>
            <div class="bg-slate-900/60 backdrop-blur border border-slate-800 rounded-2xl p-4 text-center hover:border-brand-500/50 transition-all hover:shadow-lg hover:shadow-brand-500/10">
                <div class="text-2xl mb-2">📧</div>
                <p class="text-sm font-semibold text-gray-200">Email Deals</p>
                <p class="text-xs text-gray-500 mt-1">Get exclusive offers in your inbox</p>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-3xl shadow-2xl shadow-black/30 overflow-hidden" id="chatPage">
            <!-- Chat Messages Area -->
            <div id="chatMessages" class="h-[500px] overflow-y-auto p-6 space-y-4 scroll-smooth">
                <!-- Welcome Message -->
                <div class="flex gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-400 to-indigo-600 flex items-center justify-center text-lg flex-shrink-0 shadow-md">🤖</div>
                    <div class="bg-slate-800/80 backdrop-blur rounded-2xl rounded-tl-md px-5 py-3.5 max-w-[80%] border border-slate-700/50">
                        <p class="text-gray-200 text-sm leading-relaxed">
                            Hello! 👋 I'm your <strong>AI Shopping Assistant</strong>. I can help you with:
                        </p>
                        <ul class="text-gray-300 text-sm mt-2 space-y-1">
                            <li>🔍 <strong>Search products</strong> — Try "show me headphones" or "find budget laptops"</li>
                            <li>📸 <strong>Image search</strong> — Upload a photo and I'll find similar products</li>
                            <li>🔊 <strong>Listen</strong> — Click the speaker icon to hear my responses</li>
                            <li>📧 <strong>Email deals</strong> — Say "send me deals" to get offers in your inbox</li>
                            <li>📦 <strong>Track orders</strong> — Ask "my orders" to see your order status</li>
                        </ul>
                        <p class="text-gray-400 text-xs mt-3">Try the quick actions below or just type your question!</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="px-6 py-3 border-t border-slate-800/50">
                <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                    <button onclick="sendQuickMessage('Show featured products')" class="quick-action-btn flex-shrink-0 px-4 py-2 bg-slate-800 hover:bg-brand-500/20 border border-slate-700 hover:border-brand-500/50 rounded-full text-xs font-medium text-gray-300 hover:text-brand-400 transition-all">⭐ Featured Products</button>
                    <button onclick="sendQuickMessage('Show electronics')" class="quick-action-btn flex-shrink-0 px-4 py-2 bg-slate-800 hover:bg-brand-500/20 border border-slate-700 hover:border-brand-500/50 rounded-full text-xs font-medium text-gray-300 hover:text-brand-400 transition-all">💻 Electronics</button>
                    <button onclick="sendQuickMessage('Show fashion')" class="quick-action-btn flex-shrink-0 px-4 py-2 bg-slate-800 hover:bg-brand-500/20 border border-slate-700 hover:border-brand-500/50 rounded-full text-xs font-medium text-gray-300 hover:text-brand-400 transition-all">👗 Fashion</button>
                    <button onclick="sendQuickMessage('Show deals and offers')" class="quick-action-btn flex-shrink-0 px-4 py-2 bg-slate-800 hover:bg-brand-500/20 border border-slate-700 hover:border-brand-500/50 rounded-full text-xs font-medium text-gray-300 hover:text-brand-400 transition-all">🏷️ Hot Deals</button>
                    <button onclick="sendQuickMessage('What categories do you have?')" class="quick-action-btn flex-shrink-0 px-4 py-2 bg-slate-800 hover:bg-brand-500/20 border border-slate-700 hover:border-brand-500/50 rounded-full text-xs font-medium text-gray-300 hover:text-brand-400 transition-all">📂 Categories</button>
                    <button onclick="sendQuickMessage('Show best selling products')" class="quick-action-btn flex-shrink-0 px-4 py-2 bg-slate-800 hover:bg-brand-500/20 border border-slate-700 hover:border-brand-500/50 rounded-full text-xs font-medium text-gray-300 hover:text-brand-400 transition-all">🔥 Best Sellers</button>
                </div>
            </div>

            <!-- Input Area -->
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/50">
                <form id="chatForm" class="flex items-center gap-3">
                    @csrf
                    <label for="imageUploadPage" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 flex items-center justify-center cursor-pointer transition-all hover:border-brand-500/50" title="Upload Image">
                        <span class="text-gray-400 hover:text-brand-400">📸</span>
                        <input type="file" id="imageUploadPage" accept="image/*" class="hidden">
                    </label>
                    <div class="flex-1 relative">
                        <input type="text" id="chatInput" placeholder="Ask me anything about products..." class="w-full px-5 py-3 bg-slate-800 border border-slate-700 rounded-2xl text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent placeholder-gray-500" autocomplete="off">
                    </div>
                    <button type="submit" id="sendBtn" class="w-10 h-10 rounded-xl bg-gradient-to-r from-brand-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 transition-all hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed" title="Send">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </form>
                <div id="imagePreview" class="hidden mt-3 flex items-center gap-3 bg-slate-800 rounded-xl p-3 border border-slate-700">
                    <img id="previewImg" class="w-16 h-16 object-cover rounded-lg">
                    <div class="flex-1">
                        <p class="text-sm text-gray-300 font-medium">Image attached</p>
                        <p class="text-xs text-gray-500">Will be analyzed by AI</p>
                    </div>
                    <button onclick="clearImage()" class="text-gray-400 hover:text-red-400 transition-colors">✕</button>
                </div>
            </div>
        </div>

        <!-- Powered By -->
        <p class="text-center text-gray-600 text-xs mt-6">Powered by Google AI Studio · ElevenLabs TTS · Resend Email</p>
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    #chatMessages::-webkit-scrollbar { width: 6px; }
    #chatMessages::-webkit-scrollbar-track { background: transparent; }
    #chatMessages::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.3); border-radius: 3px; }
    #chatMessages::-webkit-scrollbar-thumb:hover { background: rgba(99,102,241,0.5); }
    .typing-dot { animation: typingBounce 1.4s infinite ease-in-out both; }
    .typing-dot:nth-child(2) { animation-delay: 0.16s; }
    .typing-dot:nth-child(3) { animation-delay: 0.32s; }
    @keyframes typingBounce { 0%,80%,100%{transform:scale(0);} 40%{transform:scale(1);} }
    .chat-product-card { transition: all 0.2s; }
    .chat-product-card:hover { transform: translateY(-2px); box-shadow: 0 4px 20px rgba(99,102,241,0.15); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const imageUpload = document.getElementById('imageUploadPage');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const sendBtn = document.getElementById('sendBtn');
    let selectedImage = null;

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message && !selectedImage) return;

        if (selectedImage) {
            sendImageMessage(message);
        } else {
            sendTextMessage(message);
        }
    });

    imageUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            selectedImage = file;
            const reader = new FileReader();
            reader.onload = (ev) => {
                previewImg.src = ev.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    window.clearImage = function() {
        selectedImage = null;
        imageUpload.value = '';
        imagePreview.classList.add('hidden');
    };

    window.sendQuickMessage = function(text) {
        chatInput.value = text;
        sendTextMessage(text);
    };

    async function sendTextMessage(message) {
        appendUserMessage(message);
        chatInput.value = '';
        sendBtn.disabled = true;
        showTyping();

        try {
            const res = await fetch('/chatbot/chat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value },
                body: JSON.stringify({ message })
            });
            const data = await res.json();
            removeTyping();
            appendBotMessage(data);
        } catch (err) {
            removeTyping();
            appendBotMessage({ text: 'Sorry, something went wrong. Please try again.', type: 'text' });
        }
        sendBtn.disabled = false;
    }

    async function sendImageMessage(message) {
        appendUserMessage(message || '📸 Analyzing image...', true);
        chatInput.value = '';
        sendBtn.disabled = true;
        showTyping();

        const formData = new FormData();
        formData.append('image', selectedImage);
        if (message) formData.append('message', message);
        formData.append('_token', document.querySelector('input[name=_token]').value);

        try {
            const res = await fetch('/chatbot/image-search', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            removeTyping();
            appendBotMessage(data);
        } catch (err) {
            removeTyping();
            appendBotMessage({ text: 'Sorry, image analysis failed. Please try again.', type: 'text' });
        }
        clearImage();
        sendBtn.disabled = false;
    }

    function appendUserMessage(text, hasImage = false) {
        const div = document.createElement('div');
        div.className = 'flex justify-end';
        div.innerHTML = `<div class="bg-gradient-to-r from-brand-500 to-indigo-600 text-white rounded-2xl rounded-tr-md px-5 py-3 max-w-[75%] shadow-lg shadow-brand-500/20">
            <p class="text-sm">${hasImage ? '📸 ' : ''}${escapeHtml(text)}</p>
        </div>`;
        chatMessages.appendChild(div);
        scrollToBottom();
    }

    function appendBotMessage(data) {
        const div = document.createElement('div');
        div.className = 'flex gap-3';
        let content = '';

        if (data.type === 'products' && data.products) {
            content = `<p class="text-gray-200 text-sm mb-3">${data.text || 'Here are some products:'}</p>
                <div class="space-y-2">
                    ${data.products.map(p => `
                        <a href="/products/${p.slug}" class="chat-product-card flex items-center gap-3 bg-slate-700/50 hover:bg-slate-700 rounded-xl p-3 border border-slate-600/30 hover:border-brand-500/30">
                            <img src="${p.image || '/images/placeholder.png'}" class="w-14 h-14 object-cover rounded-lg flex-shrink-0" onerror="this.src='/images/placeholder.png'">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-200 truncate">${escapeHtml(p.name)}</p>
                                <p class="text-brand-400 font-bold text-sm">$${parseFloat(p.price).toFixed(2)}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    `).join('')}
                </div>`;
        } else {
            const text = (data.text || '').replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>').replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" class="text-brand-400 hover:text-brand-300 underline">$1</a>');
            content = `<p class="text-gray-200 text-sm leading-relaxed">${text}</p>`;
        }

        // Listen button
        const listenBtn = `<button onclick="listenToResponse(this)" data-text="${escapeAttr(data.text || '')}" class="mt-2 flex items-center gap-1.5 text-xs text-gray-500 hover:text-brand-400 transition-colors">
            <span>🔊</span> <span>Listen</span>
        </button>`;

        div.innerHTML = `<div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-400 to-indigo-600 flex items-center justify-center text-lg flex-shrink-0 shadow-md">🤖</div>
            <div class="bg-slate-800/80 backdrop-blur rounded-2xl rounded-tl-md px-5 py-3.5 max-w-[80%] border border-slate-700/50">
                ${content}
                ${listenBtn}
            </div>`;
        chatMessages.appendChild(div);
        scrollToBottom();
    }

    function showTyping() {
        const div = document.createElement('div');
        div.id = 'typingIndicator';
        div.className = 'flex gap-3';
        div.innerHTML = `<div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-400 to-indigo-600 flex items-center justify-center text-lg flex-shrink-0">🤖</div>
            <div class="bg-slate-800/80 rounded-2xl rounded-tl-md px-5 py-4 border border-slate-700/50">
                <div class="flex gap-1.5"><div class="w-2 h-2 bg-brand-400 rounded-full typing-dot"></div><div class="w-2 h-2 bg-brand-400 rounded-full typing-dot"></div><div class="w-2 h-2 bg-brand-400 rounded-full typing-dot"></div></div>
            </div>`;
        chatMessages.appendChild(div);
        scrollToBottom();
    }

    function removeTyping() {
        const el = document.getElementById('typingIndicator');
        if (el) el.remove();
    }

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function escapeAttr(str) {
        return str.replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/'/g,'&#39;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    window.listenToResponse = async function(btn) {
        const text = btn.dataset.text;
        if (!text) return;
        btn.innerHTML = '<span>⏳</span> <span>Loading...</span>';
        try {
            const res = await fetch('/chatbot/tts', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value },
                body: JSON.stringify({ text })
            });
            const data = await res.json();
            if (data.audio) {
                const audio = new Audio('data:audio/mpeg;base64,' + data.audio);
                audio.play();
                btn.innerHTML = '<span>🔊</span> <span>Playing...</span>';
                audio.onended = () => { btn.innerHTML = '<span>🔊</span> <span>Listen</span>'; };
            } else {
                btn.innerHTML = '<span>🔊</span> <span>Listen</span>';
            }
        } catch(e) {
            btn.innerHTML = '<span>🔊</span> <span>Listen</span>';
        }
    };

    // Focus input on load
    chatInput.focus();
});
</script>
@endsection
