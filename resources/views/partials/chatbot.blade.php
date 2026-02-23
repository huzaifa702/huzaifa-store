<!-- AI Agent Chatbot Widget -->
<div x-data="aiAgent()" x-cloak class="fixed bottom-6 right-6 z-[100]">
    <!-- Chat Toggle Button -->
    <button @click="toggle()" class="relative w-16 h-16 bg-gradient-to-br from-brand-500 via-brand-600 to-neon-cyan rounded-2xl shadow-2xl shadow-brand-500/40 flex items-center justify-center text-white hover:scale-110 transition-all duration-500 group"
            :class="isOpen ? 'rotate-0 scale-90' : 'animate-glow-ring'">
        <span x-show="!isOpen" class="text-2xl transition-all">🤖</span>
        <span x-show="isOpen" class="text-2xl transition-all">✕</span>
        <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full animate-pulse border-2 border-dark-950" x-show="!isOpen"></div>
    </button>

    <!-- Chat Window -->
    <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90 translate-y-4"
         class="absolute bottom-20 right-0 w-[400px] max-w-[calc(100vw-2rem)] max-h-[560px] bg-dark-900/95 backdrop-blur-2xl rounded-3xl shadow-2xl shadow-black/60 border border-white/[0.08] flex flex-col overflow-hidden">

        <!-- Header (NO voice toggle icon) -->
        <div class="bg-gradient-to-r from-brand-600 via-brand-500 to-neon-cyan p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl backdrop-blur-sm">🤖</div>
            <div class="flex-1">
                <h3 class="text-white font-bold text-sm">Huzaifa AI Agent</h3>
                <p class="text-white/70 text-xs flex items-center gap-1">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full inline-block animate-pulse"></span>
                    Online — Ask me anything!
                </p>
            </div>
            <button @click="isOpen = false" class="text-white/60 hover:text-white transition p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3 min-h-[280px] max-h-[340px]" x-ref="messages">
            <template x-for="(msg, i) in messages" :key="i">
                <div :class="msg.sender === 'bot' ? 'flex gap-2' : 'flex gap-2 justify-end'">
                    <!-- Bot Avatar -->
                    <div x-show="msg.sender === 'bot'" class="w-7 h-7 rounded-lg bg-gradient-to-br from-brand-500 to-neon-cyan flex-shrink-0 flex items-center justify-center text-xs mt-1">
                        <span x-text="msg.source === 'ai' ? '🧠' : '🤖'"></span>
                    </div>
                    <div :class="msg.sender === 'bot' ? 'bg-dark-800 border border-white/5 text-gray-200 rounded-2xl rounded-tl-md' : 'bg-gradient-to-r from-brand-600 to-brand-500 text-white rounded-2xl rounded-tr-md'"
                         class="px-4 py-2.5 max-w-[85%] text-sm leading-relaxed shadow-lg">
                        <div x-html="formatMessage(msg.text)"></div>
                        <!-- Product cards -->
                        <template x-if="msg.products && msg.products.length > 0">
                            <div class="mt-3 space-y-2">
                                <template x-for="(p, pi) in msg.products" :key="pi">
                                    <a :href="p.url" class="flex items-center gap-3 bg-dark-700/50 rounded-xl p-2 hover:bg-dark-700 transition-all group border border-white/5">
                                        <img :src="p.image" :alt="p.name" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 group-hover:scale-105 transition-transform">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-gray-200 truncate" x-text="p.name"></p>
                                            <p class="text-xs text-brand-400 font-bold" x-text="'$' + Number(p.price).toFixed(2)"></p>
                                        </div>
                                        <span class="text-gray-500 text-xs">→</span>
                                    </a>
                                </template>
                            </div>
                        </template>
                        <!-- Per-message Listen button + AI badge -->
                        <div x-show="msg.sender === 'bot'" class="mt-2 flex items-center gap-2">
                            <button @click="speakTTS(msg.text, i)" class="text-xs text-gray-500 hover:text-brand-400 transition flex items-center gap-1" title="Read aloud">
                                <span x-text="playingIndex === i ? '⏹️' : '🔊'"></span>
                                <span class="text-[10px]" x-text="playingIndex === i ? 'Stop' : 'Listen'"></span>
                            </button>
                            <span x-show="msg.source === 'ai'" class="text-[10px] text-neon-cyan/60 font-mono">AI</span>
                        </div>
                    </div>
                </div>
            </template>
            <!-- Typing indicator -->
            <div x-show="isTyping" class="flex gap-2">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-brand-500 to-neon-cyan flex-shrink-0 flex items-center justify-center text-xs">🧠</div>
                <div class="bg-dark-800 border border-white/5 rounded-2xl rounded-tl-md px-4 py-3">
                    <div class="flex gap-1 items-center">
                        <div class="w-2 h-2 bg-brand-400 rounded-full animate-bounce" style="animation-delay:0ms;"></div>
                        <div class="w-2 h-2 bg-brand-400 rounded-full animate-bounce" style="animation-delay:150ms;"></div>
                        <div class="w-2 h-2 bg-brand-400 rounded-full animate-bounce" style="animation-delay:300ms;"></div>
                        <span class="text-[10px] text-gray-600 ml-2">AI is thinking...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="px-3 pb-2 flex gap-1.5 flex-wrap" x-show="messages.length <= 2">
            <button @click="sendQuick('Show deals')" class="px-3 py-1.5 bg-dark-800 border border-white/5 rounded-full text-[11px] text-gray-400 hover:text-brand-400 hover:border-brand-500/30 transition-all">🔥 Deals</button>
            <button @click="sendQuick('Show categories')" class="px-3 py-1.5 bg-dark-800 border border-white/5 rounded-full text-[11px] text-gray-400 hover:text-brand-400 hover:border-brand-500/30 transition-all">🏷️ Categories</button>
            <button @click="sendQuick('What\'s popular?')" class="px-3 py-1.5 bg-dark-800 border border-white/5 rounded-full text-[11px] text-gray-400 hover:text-brand-400 hover:border-brand-500/30 transition-all">⭐ Popular</button>
            <button @click="sendQuick('Track my order')" class="px-3 py-1.5 bg-dark-800 border border-white/5 rounded-full text-[11px] text-gray-400 hover:text-brand-400 hover:border-brand-500/30 transition-all">📦 Orders</button>
            <button @click="showEmailInput = true" class="px-3 py-1.5 bg-dark-800 border border-white/5 rounded-full text-[11px] text-gray-400 hover:text-brand-400 hover:border-brand-500/30 transition-all">📧 Get Deals</button>
            <button @click="sendQuick('Help')" class="px-3 py-1.5 bg-dark-800 border border-white/5 rounded-full text-[11px] text-gray-400 hover:text-brand-400 hover:border-brand-500/30 transition-all">❓ Help</button>
        </div>

        <!-- Email Input -->
        <div x-show="showEmailInput" x-transition class="px-3 pb-2">
            <div class="flex gap-2 items-center bg-dark-800 rounded-xl p-2 border border-brand-500/20">
                <span class="text-lg">📧</span>
                <input type="email" x-model="emailInput" placeholder="Your email for exclusive deals..."
                       class="flex-1 bg-transparent text-sm text-gray-200 placeholder-gray-600 focus:outline-none">
                <button @click="subscribeEmail()" :disabled="!emailInput || emailSending"
                        class="px-3 py-1.5 bg-brand-500 text-white rounded-lg text-xs font-bold hover:bg-brand-600 transition disabled:opacity-40">
                    <span x-text="emailSending ? '...' : 'Send'"></span>
                </button>
                <button @click="showEmailInput = false" class="text-gray-500 hover:text-gray-300 text-xs">✕</button>
            </div>
            <p x-show="emailStatus" x-text="emailStatus" class="text-xs mt-1 px-2" :class="emailSuccess ? 'text-emerald-400' : 'text-red-400'"></p>
        </div>

        <!-- Input Area -->
        <div class="p-3 border-t border-white/5">
            <div class="flex items-center gap-2">
                <!-- Image Upload -->
                <label class="p-2 text-gray-500 hover:text-brand-400 transition cursor-pointer hover:bg-dark-800 rounded-xl" title="Upload image for AI analysis">
                    <input type="file" accept="image/*" class="hidden" @change="uploadImage($event)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </label>
                <!-- Voice Input (STT) -->
                <button @click="toggleVoiceInput()" :class="isRecording ? 'text-red-400 bg-red-500/10 animate-pulse' : 'text-gray-500 hover:text-brand-400 hover:bg-dark-800'"
                        class="p-2 rounded-xl transition-all" title="Voice input">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                </button>
                <!-- Text Input -->
                <input type="text" x-model="input" @keydown.enter="send()" :placeholder="isRecording ? '🎤 Listening...' : 'Ask me anything...'"
                       class="flex-1 px-4 py-2.5 bg-dark-800/60 border border-white/[0.06] rounded-xl text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-500/50 transition-all">
                <!-- Send Button -->
                <button @click="send()" :disabled="!input.trim()" class="p-2.5 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl hover:shadow-lg hover:shadow-brand-500/25 transition-all disabled:opacity-30 disabled:cursor-not-allowed hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function aiAgent() {
    return {
        isOpen: false,
        isTyping: false,
        isRecording: false,
        input: '',
        currentAudio: null,
        playingIndex: -1,
        recognition: null,
        showEmailInput: false,
        emailInput: '',
        emailSending: false,
        emailStatus: '',
        emailSuccess: false,
        messages: [
            { sender: 'bot', text: "👋 Hi! I'm the **Huzaifa Store AI Agent** — powered by advanced AI.\n\nI can help you with:\n🛍️ Product search & deals\n🧠 Any question (I use AI!)\n📸 Image analysis\n🔊 Listen to responses\n📧 Email exclusive deals\n\nTry the buttons below or just ask!", products: [], source: 'bot' }
        ],

        toggle() { this.isOpen = !this.isOpen; },

        formatMessage(text) {
            if (!text) return '';
            return text
                .replace(/\*\*(.*?)\*\*/g, '<strong class="text-white">$1</strong>')
                .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" class="text-brand-400 underline hover:text-brand-300">$1</a>')
                .replace(/\n/g, '<br>');
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const el = this.$refs.messages;
                if (el) el.scrollTop = el.scrollHeight;
            });
        },

        async send() {
            const msg = this.input.trim();
            if (!msg) return;
            this.messages.push({ sender: 'user', text: msg, products: [] });
            this.input = '';
            this.scrollToBottom();
            this.isTyping = true;
            this.scrollToBottom();

            try {
                const res = await fetch('/chatbot/chat', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ message: msg })
                });
                const data = await res.json();
                this.isTyping = false;
                this.messages.push({ sender: 'bot', text: data.reply, products: data.products || [], source: data.source || 'rules' });
            } catch (e) {
                this.isTyping = false;
                this.messages.push({ sender: 'bot', text: '😵 Oops! Something went wrong. Please try again.', products: [] });
            }
            this.scrollToBottom();
        },

        sendQuick(msg) { this.input = msg; this.send(); },

        async uploadImage(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.messages.push({ sender: 'user', text: '📸 [Uploaded image for AI analysis]', products: [] });
            this.isTyping = true;
            this.scrollToBottom();

            const formData = new FormData();
            formData.append('image', file);

            try {
                const res = await fetch('/chatbot/image-search', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                });
                const data = await res.json();
                this.isTyping = false;
                this.messages.push({ sender: 'bot', text: data.reply, products: data.products || [], source: 'ai' });
            } catch (e) {
                this.isTyping = false;
                this.messages.push({ sender: 'bot', text: '😵 Could not analyze the image. Try again!', products: [] });
            }
            this.scrollToBottom();
            event.target.value = '';
        },

        // ── Per-message TTS (ElevenLabs → browser fallback) ──
        async speakTTS(text, index) {
            // Toggle off if already playing this message
            if (this.playingIndex === index && this.currentAudio) {
                this.currentAudio.pause();
                this.currentAudio = null;
                this.playingIndex = -1;
                return;
            }
            // Stop any current audio
            if (this.currentAudio) { this.currentAudio.pause(); this.currentAudio = null; }
            if ('speechSynthesis' in window) window.speechSynthesis.cancel();

            this.playingIndex = index;

            try {
                const res = await fetch('/chatbot/tts', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ text: text })
                });

                if (res.ok) {
                    const blob = await res.blob();
                    const url = URL.createObjectURL(blob);
                    this.currentAudio = new Audio(url);
                    this.currentAudio.onended = () => { this.playingIndex = -1; this.currentAudio = null; };
                    this.currentAudio.onerror = () => { this.playingIndex = -1; this.speakBrowser(text); };
                    this.currentAudio.play();
                } else {
                    this.speakBrowser(text);
                }
            } catch (e) {
                this.speakBrowser(text);
            }
        },

        speakBrowser(text) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const clean = text.replace(/\*\*/g, '').replace(/\[.*?\]\(.*?\)/g, '').replace(/[^\p{L}\p{N}\p{P}\s]/gu, '');
                const u = new SpeechSynthesisUtterance(clean);
                u.rate = 0.95;
                u.onend = () => { this.playingIndex = -1; };
                window.speechSynthesis.speak(u);
            } else {
                this.playingIndex = -1;
            }
        },

        // ── Voice Input (STT via Web Speech API) ──
        toggleVoiceInput() {
            if (this.isRecording) {
                if (this.recognition) this.recognition.stop();
                this.isRecording = false;
                return;
            }

            if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                this.messages.push({ sender: 'bot', text: '🎤 Voice input is not supported in this browser. Try Chrome!', products: [] });
                this.scrollToBottom();
                return;
            }

            const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
            this.recognition = new SR();
            this.recognition.continuous = false;
            this.recognition.interimResults = false;
            this.recognition.lang = 'en-US';

            this.recognition.onresult = (event) => {
                this.input = event.results[0][0].transcript;
                this.isRecording = false;
                this.send();
            };
            this.recognition.onerror = () => { this.isRecording = false; };
            this.recognition.onend = () => { this.isRecording = false; };

            this.recognition.start();
            this.isRecording = true;
        },

        // ── Email Subscription ──
        async subscribeEmail() {
            if (!this.emailInput) return;
            this.emailSending = true;
            this.emailStatus = '';

            try {
                const res = await fetch('/chatbot/email', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ email: this.emailInput, type: 'deals' })
                });
                const data = await res.json();
                this.emailSending = false;
                if (data.success) {
                    this.emailSuccess = true;
                    this.emailStatus = '✅ ' + data.message;
                    this.messages.push({ sender: 'bot', text: `📧 Exclusive deals sent to **${this.emailInput}**! Check your inbox. 🎉`, products: [] });
                    this.emailInput = '';
                    setTimeout(() => { this.showEmailInput = false; this.emailStatus = ''; }, 3000);
                } else {
                    this.emailSuccess = false;
                    this.emailStatus = '❌ ' + (data.error || 'Failed to send');
                }
            } catch (e) {
                this.emailSending = false;
                this.emailSuccess = false;
                this.emailStatus = '❌ Could not connect. Try again later.';
            }
            this.scrollToBottom();
        }
    };
}
</script>
