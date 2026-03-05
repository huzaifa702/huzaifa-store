<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Services\AiAgentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    protected AiAgentService $ai;

    public function __construct(AiAgentService $ai)
    {
        $this->ai = $ai;
    }

    /**
     * Show the dedicated chatbot page.
     */
    public function page()
    {
        return view('chatbot');
    }

    /**
     * Handle a chatbot message — uses rule-based first, then Google AI fallback.
     */
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        $message = trim($request->message);
        $messageLower = strtolower($message);

        // SECURITY: Check for prompt injection attempts
        if ($this->ai->isPromptInjection($message)) {
            return response()->json([
                'reply' => "🔒 I can't process that request. I'm here to help with shopping, products, and general questions! Try asking about our **products**, **deals**, or **categories**.",
                'products' => [],
                'type' => 'text',
            ]);
        }

        // PRIORITY 1: Rule-based website knowledge
        $response = $this->processMessage($messageLower);

        // PRIORITY 2: If rule-based returned a fallback, try Google AI Studio
        if (isset($response['is_fallback']) && $response['is_fallback']) {
            $context = $this->buildWebsiteContext();
            $aiResponse = $this->ai->askGoogleAI($message, $context);

            if ($aiResponse) {
                $response = [
                    'text' => "🧠 " . $aiResponse,
                    'products' => [],
                    'type' => 'text',
                    'source' => 'ai',
                ];
            } else {
                // AI also failed — give a helpful fallback
                $fallbacks = [
                    "🤔 I'm not sure about that. Try asking about **products**, **deals**, **categories**, or **orders**!",
                    "😅 I didn't quite understand. You can ask me things like:\n• \"Show electronics\"\n• \"What's on sale?\"\n• \"Track my order\"\n• \"Products under \$50\"",
                    "🤖 Hmm, let me suggest some things:\n• Type **\"help\"** to see all my features\n• Try **\"show deals\"** for current sales\n• Ask about a **category** like \"fashion\" or \"electronics\"",
                ];
                $response['text'] = $fallbacks[array_rand($fallbacks)];
                unset($response['is_fallback']);
            }
        }

        return response()->json([
            'reply' => $response['text'],
            'products' => $response['products'] ?? [],
            'type' => $response['type'] ?? 'text',
        ]);
    }

    /**
     * Analyze an image using Google AI Studio multimodal.
     */
    public function imageSearch(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'message' => 'nullable|string|max:1000', // Added validation for message
        ]);

        $file = $request->file('image');
        $path = $file->store('chatbot-uploads', 'public');
        $fullPath = storage_path('app/public/' . $path);

        // Use user's text as prompt if provided, otherwise default
        $userMessage = $request->input('message', '');
        $prompt = $userMessage
            ? "The user uploaded this image and asks: \"{$userMessage}\". Analyze the image and answer their question. If relevant, mention product categories from our store. Be concise (under 150 words)."
            : "Analyze this image. If it shows a product, identify its category and features. Be concise (under 100 words).";

        // Try AI-powered image analysis
        $analysis = $this->ai->analyzeImage($fullPath, $prompt);

        if ($analysis) {
            // Use AI analysis to search for matching products
            $products = $this->searchProductsFromAnalysis($analysis);

            return response()->json([
                'reply' => "🔍 **AI Image Analysis:**\n\n" . $analysis . "\n\n" . ($products->isNotEmpty() ? "Here are matching products:" : "I couldn't find exact matches, but browse our categories!"),
                'products' => $products,
                'type' => $products->isNotEmpty() ? 'products' : 'text',
                'uploaded_image' => asset('storage/' . $path),
            ]);
        }

        // Fallback: return featured products if AI analysis fails
        $products = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('primaryImage', 'category')
            ->inRandomOrder()
            ->take(4)
            ->get()
            ->map(fn($p) => $this->formatProduct($p));

        return response()->json([
            'reply' => "📸 I received your image! Here are some products you might like:",
            'products' => $products,
            'type' => 'products',
            'uploaded_image' => asset('storage/' . $path),
        ]);
    }

    /**
     * Generate TTS audio using ElevenLabs.
     */
    public function synthesizeSpeech(Request $request)
    {
        $request->validate(['text' => 'required|string|max:2000']);

        $audio = $this->ai->generateSpeech($request->text);

        if ($audio) {
            return response($audio, 200)
                ->header('Content-Type', 'audio/mpeg')
                ->header('Content-Disposition', 'inline');
        }

        return response()->json(['error' => 'Voice service temporarily unavailable. Please try again later.'], 503);
    }

    /**
     * Send a marketing email using Resend.
     */
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'type' => 'in:deals,newsletter,welcome',
        ]);

        $email = $request->email;
        $type = $request->type ?? 'deals';
        $userId = Auth::id();

        // Build email content based on type
        $content = $this->buildEmailContent($type);

        $result = $this->ai->sendMarketingEmail(
            $email,
            $content['subject'],
            $content['html'],
            $userId
        );

        return response()->json($result);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // PRIVATE HELPERS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Format a product for JSON response.
     */
    private function formatProduct(Product $p): array
    {
        return [
            'id' => $p->id,
            'name' => $p->name,
            'price' => $p->is_on_sale ? $p->sale_price : $p->price,
            'image' => $p->primary_image_url,
            'url' => route('products.show', $p),
            'category' => $p->category->name ?? 'Unknown',
        ];
    }

    /**
     * Build context about the store for AI responses.
     */
    private function buildWebsiteContext(): array
    {
        return [
            'store_name' => 'Huzaifa Store',
            'email' => 'mhuzaifa2503a@aptechorangi.com',
            'categories' => Category::where('is_active', true)->pluck('name')->toArray(),
            'total_products' => Product::where('is_active', true)->count(),
            'has_sales' => Product::whereNotNull('sale_price')->exists(),
            'shipping' => 'Standard 5-7 days (free over $50), Express 2-3 days ($9.99)',
            'returns' => '30-day hassle-free returns',
            'payments' => 'Credit/Debit Cards, COD, Bank Transfer',
        ];
    }

    /**
     * Search products from AI analysis text.
     */
    private function searchProductsFromAnalysis(string $analysis)
    {
        $analysisLower = strtolower($analysis);

        // Try to match a category from the analysis
        $categories = Category::where('is_active', true)->get();
        $matchedCategory = null;

        foreach ($categories as $cat) {
            if (str_contains($analysisLower, strtolower($cat->name))) {
                $matchedCategory = $cat;
                break;
            }
        }

        $query = Product::where('is_active', true)->with('primaryImage', 'category');

        if ($matchedCategory) {
            $query->where('category_id', $matchedCategory->id);
        }

        return $query->inRandomOrder()
            ->take(4)
            ->get()
            ->map(fn($p) => $this->formatProduct($p));
    }

    /**
     * Build marketing email content by type.
     */
    private function buildEmailContent(string $type): array
    {
        $storeName = 'Huzaifa Store';

        return match($type) {
            'deals' => [
                'subject' => "🔥 Hot Deals at {$storeName} — Don't Miss Out!",
                'html' => $this->renderEmailTemplate('deals'),
            ],
            'newsletter' => [
                'subject' => "📬 What's New at {$storeName}",
                'html' => $this->renderEmailTemplate('newsletter'),
            ],
            'welcome' => [
                'subject' => "👋 Welcome to {$storeName}!",
                'html' => $this->renderEmailTemplate('welcome'),
            ],
            default => [
                'subject' => "🛍️ Check Out {$storeName}",
                'html' => $this->renderEmailTemplate('deals'),
            ],
        };
    }

    /**
     * Render a simple HTML email template.
     */
    private function renderEmailTemplate(string $type): string
    {
        $storeUrl = config('app.url');
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('primaryImage')
            ->take(3)
            ->get();

        $productsHtml = '';
        foreach ($featuredProducts as $p) {
            $price = $p->is_on_sale ? '$' . number_format($p->sale_price, 2) : '$' . number_format($p->price, 2);
            $productsHtml .= "<tr><td style='padding:12px;border-bottom:1px solid #eee;'>"
                . "<strong>{$p->name}</strong><br>"
                . "<span style='color:#6366f1;font-weight:bold;'>{$price}</span>"
                . "</td></tr>";
        }

        $greeting = match($type) {
            'welcome' => '<h2 style="color:#6366f1;">Welcome to Huzaifa Store! 🎉</h2><p>Thank you for joining! Explore our amazing collection of products.</p>',
            'newsletter' => '<h2 style="color:#6366f1;">What\'s New This Week 📰</h2><p>Check out our latest products and amazing deals!</p>',
            default => '<h2 style="color:#6366f1;">Hot Deals Just For You! 🔥</h2><p>Don\'t miss these amazing offers at Huzaifa Store:</p>',
        };

        return <<<HTML
        <div style="max-width:600px;margin:0 auto;font-family:Arial,sans-serif;background:#f9fafb;padding:24px;border-radius:12px;">
            <div style="text-align:center;padding:20px 0;">
                <h1 style="color:#1e293b;margin:0;">🛍️ Huzaifa Store</h1>
            </div>
            <div style="background:white;padding:24px;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                {$greeting}
                <table style="width:100%;border-collapse:collapse;margin-top:16px;">
                    {$productsHtml}
                </table>
                <div style="text-align:center;margin-top:24px;">
                    <a href="{$storeUrl}" style="display:inline-block;padding:12px 32px;background:#6366f1;color:white;text-decoration:none;border-radius:8px;font-weight:bold;">Shop Now →</a>
                </div>
            </div>
            <p style="text-align:center;color:#94a3b8;font-size:12px;margin-top:16px;">
                © 2026 Huzaifa Store · mhuzaifa2503a@aptechorangi.com<br>
                <a href="#" style="color:#94a3b8;">Unsubscribe</a>
            </p>
        </div>
        HTML;
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // RULE-BASED MESSAGE PROCESSING (Priority 1)
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    private function processMessage(string $message): array
    {
        // Greeting
        if (preg_match('/^(hi|hello|hey|salaam|salam|assalam|good\s*(morning|evening|afternoon)|howdy|sup)/i', $message)) {
            $name = Auth::check() ? ' ' . Auth::user()->name : '';
            $greetings = [
                "Hey{$name}! 👋 Welcome to Huzaifa Store! How can I help you today?",
                "Hello{$name}! 😊 I'm your AI shopping assistant. Ask me about products, deals, or anything!",
                "Hi there{$name}! 🌟 I'm powered by AI and ready to help — products, general knowledge, anything!",
            ];
            return ['text' => $greetings[array_rand($greetings)], 'type' => 'text'];
        }

        // Help / What can you do
        if (preg_match('/(help|what can you|what do you|features|capabilities|menu)/i', $message)) {
            return ['text' => "🤖 **I'm the Huzaifa Store AI Agent!** Here's what I can do:\n\n"
                . "🛍️ **Product Search** — \"Show me electronics\" or \"find laptops\"\n"
                . "🔥 **Deals & Sales** — \"What's on sale?\" or \"show deals\"\n"
                . "📦 **Order Tracking** — \"Track my order\" or \"order status\"\n"
                . "🏷️ **Categories** — \"Show categories\" or \"what do you sell?\"\n"
                . "💡 **Recommendations** — \"Suggest products\" or \"what's popular?\"\n"
                . "📸 **Image Analysis** — Upload a photo and I'll analyze it with AI!\n"
                . "🔊 **Voice Mode** — Toggle voice to hear my responses!\n"
                . "🧠 **General Knowledge** — Ask me anything! Math, science, history...\n"
                . "📧 **Email Deals** — Get exclusive deals sent to your email!\n\n"
                . "Just type or speak! 💬", 'type' => 'text'];
        }

        // Categories
        if (preg_match('/(categor|what.*sell|what.*have|browse|shop)/i', $message)) {
            $categories = Category::where('is_active', true)->withCount('activeProducts')->get();
            $list = $categories->map(fn($c) => "• **{$c->name}** ({$c->active_products_count} items)")->implode("\n");
            return ['text' => "🏷️ **Our Categories:**\n\n{$list}\n\n💡 Ask me about any category to see products!", 'type' => 'text'];
        }

        // Deals / Sales
        if (preg_match('/(deal|sale|discount|offer|cheap|bargain|save|on sale)/i', $message)) {
            $products = Product::where('is_active', true)
                ->whereNotNull('sale_price')
                ->with('primaryImage', 'category')
                ->inRandomOrder()->take(4)->get()
                ->map(fn($p) => $this->formatProduct($p));
            return ['text' => "🔥 **Hot Deals Right Now!** Save big on these items:", 'products' => $products, 'type' => 'products'];
        }

        // Popular / Featured
        if (preg_match('/(popular|featured|recommend|suggest|best|top|trending|new arrival)/i', $message)) {
            $products = Product::where('is_active', true)
                ->where('is_featured', true)
                ->with('primaryImage', 'category')
                ->inRandomOrder()->take(4)->get()
                ->map(fn($p) => $this->formatProduct($p));
            return ['text' => "⭐ **Our Top Picks For You:**", 'products' => $products, 'type' => 'products'];
        }

        // Specific category search
        $categories = Category::where('is_active', true)->get();
        foreach ($categories as $cat) {
            if (str_contains($message, strtolower($cat->name)) || str_contains($message, strtolower($cat->slug))) {
                $products = Product::where('category_id', $cat->id)
                    ->where('is_active', true)
                    ->with('primaryImage', 'category')
                    ->inRandomOrder()->take(4)->get()
                    ->map(fn($p) => $this->formatProduct($p));
                return ['text' => "📦 **{$cat->name} Products:**", 'products' => $products, 'type' => 'products'];
            }
        }

        // Order tracking
        if (preg_match('/(order|track|delivery|shipping|status|where.*order|my order)/i', $message)) {
            if (!Auth::check()) {
                return ['text' => "🔒 Please **log in** to track your orders! [Login here](/login)", 'type' => 'text'];
            }
            $orders = Order::where('user_id', Auth::id())->latest()->take(3)->get();
            if ($orders->isEmpty()) {
                return ['text' => "📦 You don't have any orders yet. Start shopping and your orders will appear here!", 'type' => 'text'];
            }
            $list = $orders->map(fn($o) => "• Order **#{$o->id}** — Status: **{$o->status}** — Total: **$" . number_format((float)$o->total, 2) . "**")->implode("\n");
            return ['text' => "📦 **Your Recent Orders:**\n\n{$list}\n\n[View all orders](/orders)", 'type' => 'text'];
        }

        // Shipping
        if (preg_match('/(ship|deliver|how long|when.*arrive|free shipping)/i', $message)) {
            return ['text' => "🚚 **Shipping Info:**\n\n• **Standard:** 5-7 business days (Free over \$50)\n• **Express:** 2-3 business days (\$9.99)\n• **Overnight:** Next day delivery (\$19.99)\n\n📍 We ship nationwide! All orders include tracking.", 'type' => 'text'];
        }

        // Returns
        if (preg_match('/(return|refund|exchange|money back|cancel)/i', $message)) {
            return ['text' => "🔄 **Return Policy:**\n\n• **30-day** hassle-free returns\n• Items must be unused and in original packaging\n• Refunds processed within 5-7 business days\n• Free return shipping on defective items\n\nContact us at mhuzaifa2503a@aptechorangi.com for returns!", 'type' => 'text'];
        }

        // Payment
        if (preg_match('/(pay|payment|credit card|debit|method|cash on delivery|cod)/i', $message)) {
            return ['text' => "💳 **Payment Methods:**\n\n• Credit/Debit Cards (Visa, Mastercard)\n• Cash on Delivery (COD)\n• Bank Transfer\n• Easy Installments available\n\n🔒 All payments are secured with SSL encryption!", 'type' => 'text'];
        }

        // Email sending via chat — detect "send deals/email to user@example.com"
        if (preg_match('/(send|email|mail|subscribe).*?([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/i', $message, $emailMatches)) {
            $targetEmail = $emailMatches[2];
            if (filter_var($targetEmail, FILTER_VALIDATE_EMAIL)) {
                $userId = Auth::id();
                $content = $this->buildEmailContent('deals');
                $result = $this->ai->sendMarketingEmail($targetEmail, $content['subject'], $content['html'], $userId);
                if ($result['success']) {
                    return ['text' => "📧 **Email sent!** We've sent exclusive deals to **{$targetEmail}**. Check your inbox! 🎉\n\nWant more? You can also use the email panel below to choose different email types (Deals, Newsletter, Welcome).", 'type' => 'text'];
                } else {
                    return ['text' => "❌ Sorry, I couldn't send the email: **{$result['error']}**\n\nPlease try again later or use a different email address.", 'type' => 'text'];
                }
            }
        }

        // Contact
        if (preg_match('/(contact|support|phone|call|reach)/i', $message)) {
            return ['text' => "📞 **Contact Us:**\n\n📧 Email: mhuzaifa2503a@aptechorangi.com\n🕐 Support Hours: Mon-Sat, 9AM-6PM\n💬 Or just ask me anything here!\n\nWe typically respond within 24 hours.", 'type' => 'text'];
        }

        // Price search
        if (preg_match('/(?:under|below|less than|cheaper than)\s*\$?(\d+)/i', $message, $matches)) {
            $maxPrice = (float)$matches[1];
            $products = Product::where('is_active', true)
                ->where('price', '<=', $maxPrice)
                ->with('primaryImage', 'category')
                ->inRandomOrder()->take(4)->get()
                ->map(fn($p) => $this->formatProduct($p));
            if ($products->isEmpty()) {
                return ['text' => "😅 No products found under \${$maxPrice}. Try a higher budget!", 'type' => 'text'];
            }
            return ['text' => "💰 **Products Under \${$maxPrice}:**", 'products' => $products, 'type' => 'products'];
        }

        // Keyword product search
        $keywords = array_filter(explode(' ', $message), fn($w) => strlen($w) > 2 && !in_array($w, ['the','and','for','can','you','show','find','get','want','need','looking','search','buy','any','what','how','where','when','does','this','that','with']));
        if (!empty($keywords)) {
            $query = Product::where('is_active', true);
            foreach ($keywords as $kw) {
                $kwLower = strtolower($kw);
                $query->where(function($q) use ($kwLower) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$kwLower}%"])
                      ->orWhereRaw('LOWER(description) LIKE ?', ["%{$kwLower}%"]);
                });
            }
            $products = $query->with('primaryImage', 'category')->take(4)->get()
                ->map(fn($p) => $this->formatProduct($p));
            if ($products->count() > 0) {
                return ['text' => "🔍 **Search Results:**", 'products' => $products, 'type' => 'products'];
            }
        }

        // Thank you
        if (preg_match('/(thank|thanks|thx|appreciate|great|awesome|perfect)/i', $message)) {
            return ['text' => "😊 You're welcome! Happy to help. Is there anything else you'd like to know? 🛍️", 'type' => 'text'];
        }

        // Goodbye
        if (preg_match('/(bye|goodbye|see you|later|cya)/i', $message)) {
            return ['text' => "👋 Goodbye! Thanks for visiting Huzaifa Store. Come back anytime! 🌟", 'type' => 'text'];
        }

        // FALLBACK → marked for AI escalation
        return ['text' => "🤔 Let me think about that...", 'type' => 'text', 'is_fallback' => true];
    }
}
