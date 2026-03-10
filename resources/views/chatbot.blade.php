@extends('layouts.app')
@section('title', 'AI Shopping Assistant')

@section('content')
<div class="chatbot-page-wrapper">
    <div class="chatbot-container">
        <!-- Header -->
        <div class="text-center chatbot-header">
            <div class="inline-flex items-center gap-3 mb-3">
                <div class="chatbot-avatar-lg">🤖</div>
                <div class="text-left">
                    <h1 class="chatbot-title">Huzaifa AI Agent</h1>
                    <p class="text-brand-400 text-sm font-medium">● Online — Your intelligent shopping assistant</p>
                </div>
            </div>
            <p class="text-gray-400 max-w-xl mx-auto text-sm">Ask me about products, get recommendations, search by image, listen to responses, and receive exclusive deals via email!</p>
        </div>

        <!-- Feature Cards -->
        <div class="chatbot-features-grid">
            <div class="chatbot-feature-card">
                <div class="text-2xl mb-2">🔍</div>
                <p class="text-sm font-semibold text-gray-200">Product Search</p>
                <p class="text-xs text-gray-500 mt-1">Find any product instantly</p>
            </div>
            <div class="chatbot-feature-card">
                <div class="text-2xl mb-2">📸</div>
                <p class="text-sm font-semibold text-gray-200">Image Analysis</p>
                <p class="text-xs text-gray-500 mt-1">Upload photos to find matches</p>
            </div>
            <div class="chatbot-feature-card">
                <div class="text-2xl mb-2">🔊</div>
                <p class="text-sm font-semibold text-gray-200">Voice Responses</p>
                <p class="text-xs text-gray-500 mt-1">Listen to AI replies aloud</p>
            </div>
            <div class="chatbot-feature-card">
                <div class="text-2xl mb-2">📧</div>
                <p class="text-sm font-semibold text-gray-200">Email Deals</p>
                <p class="text-xs text-gray-500 mt-1">Get exclusive offers in your inbox</p>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="chatbot-chat-container" id="chatPage">
            <!-- Chat Messages Area -->
            <div id="chatMessages" class="chatbot-messages-area">
                <!-- Welcome Message -->
                <div class="flex gap-3">
                    <div class="chatbot-avatar-sm">🤖</div>
                    <div class="chatbot-bot-bubble">
                        <p class="text-gray-200 text-sm leading-relaxed">
                            Hello! 👋 I'm your <strong>AI Shopping Assistant</strong>. I can help you with:
                        </p>
                        <ul class="text-gray-300 text-sm mt-2 space-y-1">
                            <li>🔍 <strong>Search products</strong> — Try "show me headphones" or "find budget laptops"</li>
                            <li>📸 <strong>Image search</strong> — Upload a photo and I'll find similar products</li>
                            <li>🔊 <strong>Listen</strong> — Click the speaker icon to hear my responses</li>
                            <li>📧 <strong>Email deals</strong> — Use the email panel below to get offers in your inbox</li>
                            <li>📦 <strong>Track orders</strong> — Ask "my orders" to see your order status</li>
                        </ul>
                        <p class="text-gray-400 text-xs mt-3">Try the quick actions below or just type your question!</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions (Dynamic from Admin Panel categories) -->
            <div class="chatbot-quick-actions">
                <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                    <button onclick="sendQuickMessage('Show featured products')" class="quick-action-btn">⭐ Featured</button>
                    @foreach($categories->take(4) as $cat)
                        <button onclick="sendQuickMessage('Show {{ $cat->name }} products')" class="quick-action-btn">{{ $cat->name }}</button>
                    @endforeach
                    <button onclick="sendQuickMessage('Show deals and offers')" class="quick-action-btn">🏷️ Hot Deals</button>
                    <button onclick="sendQuickMessage('What categories do you have?')" class="quick-action-btn">📂 Categories</button>
                </div>
            </div>

            <!-- Input Area -->
            <div class="chatbot-input-area">
                <!-- Floating Stop Bar (visible ONLY during generation) -->
                <div id="stopBar" style="display:none" class="chatbot-stop-bar">
                    <button onclick="stopGeneration()" class="chatbot-stop-bar-btn">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><rect x="6" y="6" width="12" height="12" rx="2"/></svg>
                        Stop generating
                    </button>
                </div>
                <form id="chatForm" class="chatbot-input-form">
                    @csrf
                    <label for="imageUploadPage" class="chatbot-upload-btn" title="Upload Image">
                        <span class="text-gray-400 hover:text-brand-400">📸</span>
                        <input type="file" id="imageUploadPage" accept="image/*" class="hidden">
                    </label>
                    <button type="button" id="micBtn" onclick="toggleVoiceInput()" class="chatbot-mic-btn" title="Voice Input">
                        <span id="micIcon">🎤</span>
                    </button>
                    <div class="flex-1 relative">
                        <input type="text" id="chatInput" placeholder="Ask anything — code, math, products, general knowledge..." class="chatbot-text-input" autocomplete="off">
                    </div>
                    <!-- Send button (transforms to Stop button during generation) -->
                    <button type="submit" id="sendBtn" class="chatbot-send-btn" title="Send">
                        <svg id="sendIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                    <button type="button" id="stopBtn" style="display:none" class="chatbot-stop-btn" onclick="stopGeneration()" title="Stop generating">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><rect x="6" y="6" width="12" height="12" rx="2"/></svg>
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

        <!-- Marketing Email Panel -->
        <div class="chatbot-email-panel" id="emailPanel">
            <div class="chatbot-email-header">
                <span class="text-lg">📧</span>
                <div>
                    <h3 class="text-sm font-bold text-white">Get Exclusive Deals via Email</h3>
                    <p class="text-xs text-gray-500">Receive hot deals, newsletters & welcome offers</p>
                </div>
            </div>
            <div class="chatbot-email-form-wrapper">
                <div class="chatbot-email-row">
                    <input type="email" id="emailInput" placeholder="Enter your email address..." class="chatbot-email-input">
                    <select id="emailType" class="chatbot-email-select">
                        <option value="deals">🔥 Hot Deals</option>
                        <option value="newsletter">📬 Newsletter</option>
                        <option value="welcome">👋 Welcome</option>
                    </select>
                </div>
                <button onclick="sendMarketingEmail()" id="emailSendBtn" class="chatbot-email-send-btn">
                    <span>📤</span> Send Email
                </button>
            </div>
            <div id="emailStatus" class="hidden mt-2 text-xs font-medium px-1"></div>
        </div>

        <!-- Powered By -->
        <p class="text-center text-gray-600 text-xs mt-4">Powered by Google AI Studio · ElevenLabs TTS · Resend Email</p>
    </div>
</div>

<style>
    /* ===== CHATBOT PAGE STYLES ===== */
    .chatbot-page-wrapper {
        min-height: 100vh;
        background: linear-gradient(135deg, #020617 0%, #1e1b4b 50%, #020617 100%);
        padding: 2rem 1rem;
    }
    .chatbot-container {
        max-width: 56rem;
        margin: 0 auto;
    }
    .chatbot-header {
        margin-bottom: 1.5rem;
    }
    .chatbot-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: white;
    }
    .chatbot-avatar-lg {
        width: 3.5rem; height: 3.5rem;
        border-radius: 1rem;
        background: linear-gradient(135deg, #818cf8, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.75rem;
        box-shadow: 0 8px 25px -5px rgba(99, 102, 241, 0.3);
    }
    .chatbot-avatar-sm {
        width: 2.25rem; height: 2.25rem;
        border-radius: 0.75rem;
        background: linear-gradient(135deg, #818cf8, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.12rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px -2px rgba(99, 102, 241, 0.3);
    }

    /* Feature cards */
    .chatbot-features-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .chatbot-feature-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(51, 65, 85, 0.5);
        border-radius: 1rem;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    .chatbot-feature-card:hover {
        border-color: rgba(99, 102, 241, 0.4);
        box-shadow: 0 4px 20px -5px rgba(99, 102, 241, 0.15);
    }

    /* Chat container */
    .chatbot-chat-container {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(51, 65, 85, 0.5);
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
        overflow: hidden;
    }
    .chatbot-messages-area {
        height: 500px;
        overflow-y: auto;
        padding: 1.5rem;
        scroll-behavior: smooth;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .chatbot-bot-bubble {
        background: rgba(30, 41, 59, 0.8);
        backdrop-filter: blur(8px);
        border-radius: 1rem;
        border-top-left-radius: 0.375rem;
        padding: 0.875rem 1.25rem;
        max-width: 80%;
        border: 1px solid rgba(51, 65, 85, 0.4);
    }
    .chatbot-user-bubble {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
        border-radius: 1rem;
        border-top-right-radius: 0.375rem;
        padding: 0.75rem 1.25rem;
        max-width: 75%;
        box-shadow: 0 4px 15px -3px rgba(99, 102, 241, 0.3);
    }

    /* Quick actions */
    .chatbot-quick-actions {
        padding: 0.75rem 1.5rem;
        border-top: 1px solid rgba(51, 65, 85, 0.3);
    }
    .quick-action-btn {
        flex-shrink: 0;
        padding: 0.5rem 1rem;
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid rgba(51, 65, 85, 0.5);
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        color: #d1d5db;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .quick-action-btn:hover {
        background: rgba(99, 102, 241, 0.15);
        border-color: rgba(99, 102, 241, 0.4);
        color: #818cf8;
    }

    /* Input area */
    .chatbot-input-area {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(51, 65, 85, 0.3);
        background: rgba(15, 23, 42, 0.5);
    }
    .chatbot-input-form {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .chatbot-upload-btn {
        width: 2.5rem; height: 2.5rem;
        border-radius: 0.75rem;
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid rgba(51, 65, 85, 0.5);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .chatbot-upload-btn:hover {
        border-color: rgba(99, 102, 241, 0.4);
    }
    .chatbot-text-input {
        width: 100%;
        padding: 0.75rem 1.25rem;
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid rgba(51, 65, 85, 0.5);
        border-radius: 1rem;
        font-size: 0.875rem;
        color: #e2e8f0;
        outline: none;
        transition: all 0.2s;
    }
    .chatbot-text-input:focus {
        border-color: rgba(99, 102, 241, 0.5);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }
    .chatbot-text-input::placeholder { color: #64748b; }
    .chatbot-send-btn {
        width: 2.5rem; height: 2.5rem;
        border-radius: 0.75rem;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        color: white;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 15px -3px rgba(99, 102, 241, 0.4);
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .chatbot-send-btn:hover { transform: scale(1.05); box-shadow: 0 6px 20px -5px rgba(99, 102, 241, 0.5); }
    .chatbot-send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    /* Stop button (replaces send during generation) */
    .chatbot-stop-btn {
        width: 2.5rem; height: 2.5rem;
        border-radius: 0.75rem;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        display: flex; align-items: center; justify-content: center;
        color: white;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 15px -3px rgba(239, 68, 68, 0.4);
        transition: all 0.2s;
        flex-shrink: 0;
        animation: stopPulse 1.5s ease-in-out infinite;
    }
    .chatbot-stop-btn:hover { transform: scale(1.05); box-shadow: 0 6px 20px -5px rgba(239, 68, 68, 0.5); }
    @keyframes stopPulse {
        0%, 100% { box-shadow: 0 4px 15px -3px rgba(239, 68, 68, 0.4); }
        50% { box-shadow: 0 4px 25px -3px rgba(239, 68, 68, 0.7); }
    }

    /* Floating stop bar */
    .chatbot-stop-bar {
        display: flex;
        justify-content: center;
        padding: 0.5rem 0;
        margin-bottom: 0.5rem;
    }
    .chatbot-stop-bar-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 9999px;
        color: #f87171;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .chatbot-stop-bar-btn:hover {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.5);
    }

    /* Mic button */
    .chatbot-mic-btn {
        width: 2.5rem; height: 2.5rem;
        border-radius: 0.75rem;
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid rgba(51, 65, 85, 0.5);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
        font-size: 1rem;
    }
    .chatbot-mic-btn:hover {
        border-color: rgba(99, 102, 241, 0.4);
    }
    .chatbot-mic-btn.recording {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.5);
        animation: micPulse 1.5s ease-in-out infinite;
    }
    @keyframes micPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        50% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
    }

    /* Email Panel */
    .chatbot-email-panel {
        margin-top: 1rem;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(51, 65, 85, 0.5);
        border-radius: 1.25rem;
        padding: 1rem 1.25rem;
    }
    .chatbot-email-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    .chatbot-email-form-wrapper {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .chatbot-email-row {
        display: flex;
        gap: 0.5rem;
    }
    .chatbot-email-input {
        flex: 1;
        padding: 0.625rem 1rem;
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid rgba(51, 65, 85, 0.5);
        border-radius: 0.75rem;
        font-size: 0.875rem;
        color: #e2e8f0;
        outline: none;
        min-width: 0;
    }
    .chatbot-email-input:focus {
        border-color: rgba(99, 102, 241, 0.5);
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
    }
    .chatbot-email-input::placeholder { color: #64748b; }
    .chatbot-email-select {
        padding: 0.625rem 0.75rem;
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid rgba(51, 65, 85, 0.5);
        border-radius: 0.75rem;
        font-size: 0.8rem;
        color: #e2e8f0;
        outline: none;
        cursor: pointer;
        min-width: 130px;
    }
    .chatbot-email-send-btn {
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border: none;
        border-radius: 0.75rem;
        color: white;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    .chatbot-email-send-btn:hover { box-shadow: 0 4px 15px -3px rgba(99, 102, 241, 0.4); transform: translateY(-1px); }
    .chatbot-email-send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    /* Scrollbars */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .chatbot-messages-area::-webkit-scrollbar { width: 5px; }
    .chatbot-messages-area::-webkit-scrollbar-track { background: transparent; }
    .chatbot-messages-area::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.3); border-radius: 3px; }
    .chatbot-messages-area::-webkit-scrollbar-thumb:hover { background: rgba(99,102,241,0.5); }

    /* Typing dots */
    .typing-dot { animation: typingBounce 1.4s infinite ease-in-out both; }
    .typing-dot:nth-child(2) { animation-delay: 0.16s; }
    .typing-dot:nth-child(3) { animation-delay: 0.32s; }
    @keyframes typingBounce { 0%,80%,100%{transform:scale(0);} 40%{transform:scale(1);} }

    /* Product card */
    .chat-product-card { transition: all 0.2s; display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
    .chat-product-card:hover { transform: translateY(-2px); box-shadow: 0 4px 20px rgba(99,102,241,0.15); }

    /* ===== RESPONSIVE ===== */

    /* Tablets */
    @media (max-width: 768px) {
        .chatbot-page-wrapper { padding: 1rem 0.75rem; }
        .chatbot-title { font-size: 1.35rem; }
        .chatbot-features-grid { grid-template-columns: repeat(2, 1fr); gap: 0.5rem; }
        .chatbot-feature-card { padding: 0.75rem; }
        .chatbot-messages-area { height: 400px; padding: 1rem; }
        .chatbot-bot-bubble { max-width: 88%; }
        .chatbot-user-bubble { max-width: 85%; }
        .chatbot-input-area { padding: 0.75rem 1rem; }
        .chatbot-quick-actions { padding: 0.5rem 1rem; }
        .chatbot-email-row { flex-direction: column; }
        .chatbot-email-select { min-width: 100%; }
    }

    /* Small phones */
    @media (max-width: 480px) {
        .chatbot-page-wrapper { padding: 0.5rem; }
        .chatbot-header { margin-bottom: 1rem; }
        .chatbot-title { font-size: 1.15rem; }
        .chatbot-avatar-lg { width: 2.75rem; height: 2.75rem; font-size: 1.4rem; border-radius: 0.75rem; }
        .chatbot-features-grid { grid-template-columns: repeat(2, 1fr); gap: 0.4rem; margin-bottom: 1rem; }
        .chatbot-feature-card { padding: 0.6rem; border-radius: 0.75rem; }
        .chatbot-feature-card .text-2xl { font-size: 1.25rem; margin-bottom: 0.25rem; }
        .chatbot-feature-card .text-sm { font-size: 0.7rem; }
        .chatbot-feature-card .text-xs { font-size: 0.6rem; }
        .chatbot-chat-container { border-radius: 1rem; }
        .chatbot-messages-area { height: 55vh; padding: 0.75rem; gap: 0.75rem; }
        .chatbot-bot-bubble { max-width: 92%; padding: 0.7rem 1rem; }
        .chatbot-user-bubble { max-width: 90%; padding: 0.6rem 1rem; }
        .chatbot-bot-bubble .text-sm, .chatbot-user-bubble .text-sm { font-size: 0.8rem; }
        .chatbot-input-area { padding: 0.6rem 0.75rem; }
        .chatbot-input-form { gap: 0.5rem; }
        .chatbot-text-input { padding: 0.6rem 0.85rem; font-size: 16px; /* prevent iOS zoom */ border-radius: 0.75rem; }
        .chatbot-upload-btn, .chatbot-send-btn, .chatbot-mic-btn { width: 2.25rem; height: 2.25rem; border-radius: 0.625rem; }
        .chatbot-quick-actions { padding: 0.4rem 0.75rem; }
        .quick-action-btn { padding: 0.375rem 0.75rem; font-size: 0.68rem; }
        .chatbot-email-panel { padding: 0.75rem; border-radius: 1rem; margin-top: 0.75rem; }
        .chatbot-email-input { font-size: 16px; padding: 0.5rem 0.75rem; }
        .chatbot-email-select { font-size: 14px; padding: 0.5rem 0.75rem; }
    }

    /* Very small phones */
    @media (max-width: 360px) {
        .chatbot-messages-area { height: 50vh; }
        .chatbot-features-grid { grid-template-columns: 1fr 1fr; }
    }
</style>

<script>
// Helper: always get CSRF token from meta tag (works on all browsers/mobile)
function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) return meta.getAttribute('content');
    const input = document.querySelector('input[name="_token"]');
    return input ? input.value : '';
}

document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const imageUpload = document.getElementById('imageUploadPage');
    const stopBtn = document.getElementById('stopBtn');
    const stopBar = document.getElementById('stopBar');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const sendBtn = document.getElementById('sendBtn');
    let selectedImage = null;
    let isGenerating = false;

    // ── AbortController for real request cancellation ──
    let chatAbortController = null;
    let ttsAbortController = null;
    let currentAudio = null;

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

    // ── Show/hide generation UI ──
    function setGenerating(state) {
        isGenerating = state;
        if (state) {
            sendBtn.style.display = 'none';
            stopBtn.style.display = '';
            stopBar.style.display = '';
            chatInput.disabled = true;
        } else {
            sendBtn.style.display = '';
            stopBtn.style.display = 'none';
            stopBar.style.display = 'none';
            sendBtn.disabled = false;
            chatInput.disabled = false;
        }
    }

    // ── STOP GENERATION: Cancel fetch + stop audio ──
    window.stopGeneration = function() {
        if (chatAbortController) {
            chatAbortController.abort();
            chatAbortController = null;
        }
        if (ttsAbortController) {
            ttsAbortController.abort();
            ttsAbortController = null;
        }
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
            currentAudio = null;
        }
        if ('speechSynthesis' in window) window.speechSynthesis.cancel();
        removeTyping();
        setGenerating(false);
        // Remove any inline stop buttons
        document.querySelectorAll('.stop-gen-btn').forEach(b => b.remove());
    };

    async function sendTextMessage(message) {
        appendUserMessage(message);
        chatInput.value = '';
        setGenerating(true);
        showTyping();

        // Create AbortController
        chatAbortController = new AbortController();

        try {
            const res = await fetch('/chatbot/chat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
                body: JSON.stringify({ message }),
                signal: chatAbortController.signal
            });
            const data = await res.json();
            removeTyping();
            chatAbortController = null;
            appendBotMessage(data);
        } catch (err) {
            removeTyping();
            chatAbortController = null;
            if (err.name === 'AbortError') {
                appendBotMessage({ reply: '⏹️ Response stopped.', type: 'text' });
            } else {
                appendBotMessage({ reply: 'Sorry, something went wrong. Please try again.', type: 'text' });
            }
        }
        setGenerating(false);
    }

    async function sendImageMessage(message) {
        appendUserMessage(message || '📸 Analyzing image...', true);
        chatInput.value = '';
        setGenerating(true);
        showTyping();

        chatAbortController = new AbortController();
        const formData = new FormData();
        formData.append('image', selectedImage);
        if (message) formData.append('message', message);
        formData.append('_token', getCsrfToken());

        try {
            const res = await fetch('/chatbot/image-search', {
                method: 'POST',
                body: formData,
                signal: chatAbortController.signal
            });
            const data = await res.json();
            removeTyping();
            chatAbortController = null;
            appendBotMessage(data);
        } catch (err) {
            removeTyping();
            chatAbortController = null;
            if (err.name === 'AbortError') {
                appendBotMessage({ reply: '⏹️ Image analysis stopped.', type: 'text' });
            } else {
                appendBotMessage({ reply: 'Sorry, image analysis failed. Please try again.', type: 'text' });
            }
        }
        clearImage();
        setGenerating(false);
    }

    function appendUserMessage(text, hasImage = false) {
        const div = document.createElement('div');
        div.className = 'flex justify-end';
        div.innerHTML = `<div class="chatbot-user-bubble">
            <p class="text-sm">${hasImage ? '📸 ' : ''}${escapeHtml(text)}</p>
        </div>`;
        chatMessages.appendChild(div);
        scrollToBottom();
    }

    function appendBotMessage(data) {
        const div = document.createElement('div');
        div.className = 'flex gap-3';
        let content = '';

        const replyText = data.reply || data.text || '';

        if (data.type === 'products' && data.products && data.products.length > 0) {
            content = `<p class="text-gray-200 text-sm mb-3">${replyText || 'Here are some products:'}</p>
                <div class="space-y-2">
                    ${data.products.map(p => `
                        <a href="${escapeAttr(p.url || '/products/' + p.id)}" class="chat-product-card bg-slate-700/50 hover:bg-slate-700 rounded-xl p-3 border border-slate-600/30 hover:border-brand-500/30">
                            <img src="${p.image || '/images/placeholder.png'}" class="w-14 h-14 object-cover rounded-lg flex-shrink-0" onerror="this.src='/images/placeholder.png'" alt="${escapeAttr(p.name)}">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-200 truncate">${escapeHtml(p.name)}</p>
                                <p class="text-brand-400 font-bold text-sm">$${parseFloat(p.price).toFixed(2)}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    `).join('')}
                </div>`;
        } else {
            // Enhanced markdown rendering with code blocks support
            let formatted = replyText;
            // Code blocks: ```lang\ncode\n``` → styled pre/code
            formatted = formatted.replace(/```(\w*)\n?([\s\S]*?)```/g, function(m, lang, code) {
                return '<div style="margin:8px 0;border-radius:8px;overflow:hidden;border:1px solid rgba(99,102,241,0.2)">' +
                    '<div style="background:rgba(99,102,241,0.15);padding:4px 10px;font-size:11px;color:#818cf8;font-weight:600">' + (lang || 'code') + '</div>' +
                    '<pre style="background:rgba(15,23,42,0.8);padding:12px;margin:0;overflow-x:auto;font-size:13px;line-height:1.5"><code style="color:#e2e8f0;font-family:monospace;white-space:pre">' + escapeHtml(code.trim()) + '</code></pre>' +
                    '</div>';
            });
            // Inline code: `code`
            formatted = formatted.replace(/`([^`]+)`/g, '<code style="background:rgba(99,102,241,0.15);padding:2px 6px;border-radius:4px;font-size:0.85em;color:#a5b4fc;font-family:monospace">$1</code>');
            // Bold
            formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            // Italic
            formatted = formatted.replace(/\*(.*?)\*/g, '<em>$1</em>');
            // Links
            formatted = formatted.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" class="text-brand-400 hover:text-brand-300 underline" target="_blank">$1</a>');
            // Line breaks
            formatted = formatted.replace(/\n/g, '<br>');
            content = `<div class="text-gray-200 text-sm leading-relaxed">${formatted}</div>`;
        }

        // Listen button
        const listenBtn = `<button onclick="listenToResponse(this)" data-text="${escapeAttr(replyText)}" class="mt-2 flex items-center gap-1.5 text-xs text-gray-500 hover:text-brand-400 transition-colors" style="background:none;border:none;cursor:pointer;padding:0;">
            <span>🔊</span> <span>Listen</span>
        </button>`;

        div.innerHTML = `<div class="chatbot-avatar-sm">🤖</div>
            <div class="chatbot-bot-bubble">
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
        div.innerHTML = `<div class="chatbot-avatar-sm">🤖</div>
            <div class="chatbot-bot-bubble">
                <div class="flex gap-1.5 items-center">
                    <div class="w-2 h-2 bg-brand-400 rounded-full typing-dot"></div>
                    <div class="w-2 h-2 bg-brand-400 rounded-full typing-dot"></div>
                    <div class="w-2 h-2 bg-brand-400 rounded-full typing-dot"></div>
                    <button onclick="stopGeneration()" class="stop-gen-btn ml-3 px-3 py-1 bg-red-500/20 text-red-400 hover:bg-red-500/30 rounded-lg text-xs font-semibold transition-all flex items-center gap-1" style="border:none;cursor:pointer;">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><rect x="6" y="6" width="12" height="12" rx="2"/></svg>
                        Stop
                    </button>
                </div>
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
        return String(str).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/'/g,'&#39;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    // ===== FIXED TTS: ElevenLabs with browser speech fallback =====
    window.listenToResponse = async function(btn) {
        const text = btn.dataset.text;
        if (!text) return;

        // If currently playing, stop it
        if (btn.dataset.playing === 'true') {
            if (currentAudio) { currentAudio.pause(); currentAudio.currentTime = 0; currentAudio = null; }
            if ('speechSynthesis' in window) window.speechSynthesis.cancel();
            if (ttsAbortController) { ttsAbortController.abort(); ttsAbortController = null; }
            btn.innerHTML = '<span>🔊</span> <span>Listen</span>';
            btn.dataset.playing = 'false';
            return;
        }

        // Stop any other playing audio first
        if (currentAudio) { currentAudio.pause(); currentAudio = null; }
        if ('speechSynthesis' in window) window.speechSynthesis.cancel();

        btn.innerHTML = '<span>⏳</span> <span>Loading...</span>';
        btn.dataset.playing = 'true';

        ttsAbortController = new AbortController();

        try {
            const res = await fetch('/chatbot/tts', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
                body: JSON.stringify({ text }),
                signal: ttsAbortController.signal
            });
            ttsAbortController = null;

            if (res.ok) {
                const blob = await res.blob();
                if (blob.size > 0 && blob.type.includes('audio')) {
                    const audioUrl = URL.createObjectURL(blob);
                    currentAudio = new Audio(audioUrl);
                    btn.innerHTML = '<span>⏹️</span> <span>Stop</span>';
                    currentAudio.play();
                    currentAudio.onended = () => {
                        btn.innerHTML = '<span>🔊</span> <span>Listen</span>';
                        btn.dataset.playing = 'false';
                        currentAudio = null;
                        URL.revokeObjectURL(audioUrl);
                    };
                    currentAudio.onerror = () => {
                        btn.dataset.playing = 'false';
                        currentAudio = null;
                        URL.revokeObjectURL(audioUrl);
                        speakBrowserFallback(text, btn);
                    };
                } else {
                    // Not valid audio, use browser fallback
                    speakBrowserFallback(text, btn);
                }
            } else {
                // Server returned error, use browser speech fallback
                speakBrowserFallback(text, btn);
            }
        } catch(e) {
            ttsAbortController = null;
            if (e.name === 'AbortError') {
                btn.innerHTML = '<span>🔊</span> <span>Listen</span>';
                btn.dataset.playing = 'false';
            } else {
                speakBrowserFallback(text, btn);
            }
        }
    };

    // Browser speech synthesis fallback
    function speakBrowserFallback(text, btn) {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const clean = text.replace(/\*\*/g, '').replace(/\[.*?\]\(.*?\)/g, '').replace(/[^\p{L}\p{N}\p{P}\s]/gu, '');
            const u = new SpeechSynthesisUtterance(clean);
            u.rate = 0.95;
            btn.innerHTML = '<span>⏹️</span> <span>Stop</span>';
            u.onend = () => {
                btn.innerHTML = '<span>🔊</span> <span>Listen</span>';
                btn.dataset.playing = 'false';
            };
            u.onerror = () => {
                btn.innerHTML = '<span>🔊</span> <span>Listen</span>';
                btn.dataset.playing = 'false';
            };
            window.speechSynthesis.speak(u);
        } else {
            btn.innerHTML = '<span>❌</span> <span>Unavailable</span>';
            btn.dataset.playing = 'false';
            setTimeout(() => { btn.innerHTML = '<span>🔊</span> <span>Listen</span>'; }, 2000);
        }
    }

    // ===== MARKETING EMAIL SENDER =====
    window.sendMarketingEmail = async function() {
        const emailInput = document.getElementById('emailInput');
        const emailType = document.getElementById('emailType');
        const emailSendBtn = document.getElementById('emailSendBtn');
        const emailStatus = document.getElementById('emailStatus');
        const email = emailInput.value.trim();

        if (!email) {
            showEmailStatus('❌ Please enter a valid email address.', 'text-red-400');
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showEmailStatus('❌ Please enter a valid email address.', 'text-red-400');
            return;
        }

        emailSendBtn.disabled = true;
        emailSendBtn.innerHTML = '<span>⏳</span> Sending...';

        try {
            const res = await fetch('/chatbot/email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({ email: email, type: emailType.value })
            });
            const data = await res.json();

            if (data.success) {
                showEmailStatus('✅ ' + (data.message || 'Email sent successfully!'), 'text-emerald-400');
                emailInput.value = '';
                appendBotMessage({ reply: `📧 **Email sent!** We've sent a ${emailType.value} email to **${email}**. Check your inbox!`, type: 'text' });
            } else {
                showEmailStatus('❌ ' + (data.error || 'Failed to send email.'), 'text-red-400');
            }
        } catch(e) {
            showEmailStatus('❌ Network error. Please try again.', 'text-red-400');
        }

        emailSendBtn.disabled = false;
        emailSendBtn.innerHTML = '<span>📤</span> Send Email';
    };

    function showEmailStatus(msg, colorClass) {
        const el = document.getElementById('emailStatus');
        el.textContent = msg;
        el.className = 'mt-2 text-xs font-medium px-1 ' + colorClass;
        el.classList.remove('hidden');
        setTimeout(() => el.classList.add('hidden'), 5000);
    }

    // ===== SPEECH-TO-TEXT (STT) =====
    let sttRecognition = null;
    let isRecording = false;

    window.toggleVoiceInput = function() {
        const micBtn = document.getElementById('micBtn');
        const micIcon = document.getElementById('micIcon');

        if (isRecording) {
            if (sttRecognition) sttRecognition.stop();
            isRecording = false;
            micBtn.classList.remove('recording');
            micIcon.textContent = '🎤';
            chatInput.placeholder = 'Ask me anything about products...';
            return;
        }

        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            appendBotMessage({ reply: '🎤 Voice input is not supported in this browser. Please use Chrome!', type: 'text' });
            return;
        }

        const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
        sttRecognition = new SR();
        sttRecognition.continuous = false;
        sttRecognition.interimResults = false;
        sttRecognition.lang = 'en-US';

        sttRecognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            chatInput.value = transcript;
            isRecording = false;
            micBtn.classList.remove('recording');
            micIcon.textContent = '🎤';
            chatInput.placeholder = 'Ask me anything about products...';
            sendTextMessage(transcript);
        };

        sttRecognition.onerror = function() {
            isRecording = false;
            micBtn.classList.remove('recording');
            micIcon.textContent = '🎤';
            chatInput.placeholder = 'Ask me anything about products...';
        };

        sttRecognition.onend = function() {
            isRecording = false;
            micBtn.classList.remove('recording');
            micIcon.textContent = '🎤';
            chatInput.placeholder = 'Ask me anything about products...';
        };

        sttRecognition.start();
        isRecording = true;
        micBtn.classList.add('recording');
        micIcon.textContent = '⏹️';
        chatInput.placeholder = '🎤 Listening... speak now!';
    };

    // Focus input on load
    chatInput.focus();
});
</script>
@endsection

