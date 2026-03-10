<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        Admin::create([
            'name' => 'M. Huzaifa',
            'email' => 'mhuzaifa2503a@aptechorangi.com',
            'password' => Hash::make('M.HUZAIFA5566'),
            'role' => 'super_admin',
        ]);

        // Create Test User
        User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'phone' => '03001234567',
            'address' => '123 Main Street',
            'city' => 'Lahore',
            'state' => 'Punjab',
            'zip_code' => '54000',
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Latest electronic gadgets and devices', 'sort_order' => 1],
            ['name' => 'Fashion', 'slug' => 'fashion', 'description' => 'Trendy clothing and accessories', 'sort_order' => 2],
            ['name' => 'Home & Living', 'slug' => 'home-living', 'description' => 'Furniture, decor and home essentials', 'sort_order' => 3],
            ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports equipment and activewear', 'sort_order' => 4],
            ['name' => 'Books', 'slug' => 'books', 'description' => 'Books, eBooks and educational materials', 'sort_order' => 5],
            ['name' => 'Beauty', 'slug' => 'beauty', 'description' => 'Skincare, makeup and beauty products', 'sort_order' => 6],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Create Products — 6+ per category = 36+ total
        $products = [
            // ── Electronics (category_id: 1) ───────────────────────────────
            ['category_id' => 1, 'name' => 'Wireless Bluetooth Headphones', 'slug' => 'wireless-bluetooth-headphones', 'short_description' => 'Premium noise-cancelling headphones', 'description' => 'Experience crystal-clear audio with our premium wireless Bluetooth headphones featuring active noise cancellation, 30-hour battery life, and ultra-comfortable ear cushions.', 'price' => 149.99, 'sale_price' => 119.99, 'sku' => 'ELEC-001', 'stock' => 50, 'is_featured' => true],
            ['category_id' => 1, 'name' => 'Smart Watch Pro', 'slug' => 'smart-watch-pro', 'short_description' => 'Advanced smartwatch with health tracking', 'description' => 'Stay connected and monitor your health with GPS tracking, heart rate monitoring, blood oxygen levels, and a stunning AMOLED display.', 'price' => 299.99, 'sale_price' => 249.99, 'sku' => 'ELEC-002', 'stock' => 35, 'is_featured' => true],
            ['category_id' => 1, 'name' => 'Portable Power Bank 20000mAh', 'slug' => 'portable-power-bank-20000mah', 'short_description' => 'High-capacity portable charger', 'description' => 'Never run out of battery with this compact 20000mAh power bank featuring fast charging, dual USB ports, and LED indicator.', 'price' => 49.99, 'sku' => 'ELEC-003', 'stock' => 100],
            ['category_id' => 1, 'name' => 'Mechanical Gaming Keyboard', 'slug' => 'mechanical-gaming-keyboard', 'short_description' => 'RGB backlit mechanical keyboard', 'description' => 'Dominate your games with Cherry MX switches, per-key RGB lighting, programmable macros, and aircraft-grade aluminum frame.', 'price' => 129.99, 'sale_price' => 99.99, 'sku' => 'ELEC-004', 'stock' => 45, 'is_featured' => true],
            ['category_id' => 1, 'name' => 'True Wireless Earbuds', 'slug' => 'true-wireless-earbuds', 'short_description' => 'Ultra-compact wireless earbuds with case', 'description' => 'Enjoy immersive sound with active noise cancellation, 8-hour battery life, and IPX5 water resistance. Perfect for workouts and commuting.', 'price' => 79.99, 'sale_price' => 59.99, 'sku' => 'ELEC-005', 'stock' => 80],
            ['category_id' => 1, 'name' => '4K Ultra HD Webcam', 'slug' => '4k-ultra-hd-webcam', 'short_description' => 'Professional streaming and video call webcam', 'description' => 'Crystal-clear 4K video with auto-focus, built-in ring light, noise-cancelling microphone, and universal mount for monitors and tripods.', 'price' => 119.99, 'sku' => 'ELEC-006', 'stock' => 40],
            ['category_id' => 1, 'name' => 'Wireless Gaming Mouse', 'slug' => 'wireless-gaming-mouse', 'short_description' => 'Precision gaming mouse with 16K DPI', 'description' => 'Ultra-fast 1ms wireless response, 16000 DPI optical sensor, 11 programmable buttons, and 70-hour battery life. Built for competitive gamers.', 'price' => 89.99, 'sale_price' => 69.99, 'sku' => 'ELEC-007', 'stock' => 55],

            // ── Fashion (category_id: 2) ──────────────────────────────────
            ['category_id' => 2, 'name' => 'Premium Leather Jacket', 'slug' => 'premium-leather-jacket', 'short_description' => 'Genuine leather biker jacket', 'description' => 'Classic style meets modern craftsmanship. This genuine leather jacket features a tailored fit, multiple pockets, and durable YKK zippers.', 'price' => 299.99, 'sale_price' => 199.99, 'sku' => 'FASH-001', 'stock' => 25, 'is_featured' => true],
            ['category_id' => 2, 'name' => 'Designer Sneakers', 'slug' => 'designer-sneakers', 'short_description' => 'Comfortable and stylish sneakers', 'description' => 'Step up your footwear game with breathable mesh upper, memory foam insole, and a sleek contemporary design.', 'price' => 89.99, 'sku' => 'FASH-002', 'stock' => 60],
            ['category_id' => 2, 'name' => 'Classic Aviator Sunglasses', 'slug' => 'classic-aviator-sunglasses', 'short_description' => 'UV400 protection aviator shades', 'description' => 'Iconic aviator design with polarized UV400 lenses, lightweight metal frame, and spring-loaded temples for all-day comfort.', 'price' => 59.99, 'sale_price' => 39.99, 'sku' => 'FASH-003', 'stock' => 80],
            ['category_id' => 2, 'name' => 'Slim Fit Chinos', 'slug' => 'slim-fit-chinos', 'short_description' => 'Modern stretch cotton chinos', 'description' => 'Versatile slim-fit chinos made from premium stretch cotton. Perfect for both business casual and weekend outings. Available in multiple colors.', 'price' => 54.99, 'sku' => 'FASH-004', 'stock' => 70],
            ['category_id' => 2, 'name' => 'Wool Blend Overcoat', 'slug' => 'wool-blend-overcoat', 'short_description' => 'Elegant winter overcoat', 'description' => 'Stay warm in style with this luxurious wool-blend overcoat. Features a double-breasted design, notch lapels, and satin lining.', 'price' => 249.99, 'sale_price' => 179.99, 'sku' => 'FASH-005', 'stock' => 18, 'is_featured' => true],
            ['category_id' => 2, 'name' => 'Canvas Backpack', 'slug' => 'canvas-backpack', 'short_description' => 'Rugged canvas everyday backpack', 'description' => 'Durable waxed canvas with leather trim, padded laptop compartment, multiple pockets, and water-resistant lining. Perfect for work or travel.', 'price' => 74.99, 'sku' => 'FASH-006', 'stock' => 45],

            // ── Home & Living (category_id: 3) ────────────────────────────
            ['category_id' => 3, 'name' => 'Minimalist Desk Lamp', 'slug' => 'minimalist-desk-lamp', 'short_description' => 'LED desk lamp with touch controls', 'description' => 'Illuminate your workspace with this sleek LED desk lamp featuring adjustable brightness, color temperature control, and wireless charging base.', 'price' => 69.99, 'sku' => 'HOME-001', 'stock' => 40, 'is_featured' => true],
            ['category_id' => 3, 'name' => 'Scented Candle Set', 'slug' => 'scented-candle-set', 'short_description' => 'Premium soy wax candle collection', 'description' => 'Set of 4 hand-poured soy wax candles in calming lavender, vanilla bean, cedarwood, and ocean breeze fragrances.', 'price' => 34.99, 'sku' => 'HOME-002', 'stock' => 70],
            ['category_id' => 3, 'name' => 'Ergonomic Office Chair', 'slug' => 'ergonomic-office-chair', 'short_description' => 'Posture-correcting mesh office chair', 'description' => 'High-back mesh chair with adjustable lumbar support, headrest, 4D armrests, and breathable mesh back. Rated for 8+ hours of comfortable sitting.', 'price' => 349.99, 'sale_price' => 279.99, 'sku' => 'HOME-003', 'stock' => 15, 'is_featured' => true],
            ['category_id' => 3, 'name' => 'Ceramic Plant Pots Set', 'slug' => 'ceramic-plant-pots-set', 'short_description' => 'Handcrafted matte ceramic pots', 'description' => 'Set of 3 handcrafted matte ceramic plant pots in minimalist design. Includes drainage holes and bamboo saucers. Perfect for succulents and herbs.', 'price' => 44.99, 'sku' => 'HOME-004', 'stock' => 50],
            ['category_id' => 3, 'name' => 'Smart LED Strip Lights', 'slug' => 'smart-led-strip-lights', 'short_description' => 'WiFi-controlled RGB LED strips', 'description' => '10-meter WiFi smart LED strip lights with 16 million colors, music sync, voice control via Alexa/Google, and app-controlled scenes.', 'price' => 29.99, 'sale_price' => 22.99, 'sku' => 'HOME-005', 'stock' => 90],
            ['category_id' => 3, 'name' => 'Weighted Blanket', 'slug' => 'weighted-blanket', 'short_description' => '15lb premium weighted blanket', 'description' => 'Plush 15lb weighted blanket with glass bead filling and breathable cotton cover. Promotes deeper sleep and reduces anxiety. Machine washable.', 'price' => 79.99, 'sku' => 'HOME-006', 'stock' => 35],

            // ── Sports (category_id: 4) ───────────────────────────────────
            ['category_id' => 4, 'name' => 'Yoga Mat Premium', 'slug' => 'yoga-mat-premium', 'short_description' => 'Non-slip eco-friendly yoga mat', 'description' => 'Extra thick 6mm eco-friendly TPE yoga mat with alignment lines, non-slip surface, and carrying strap included.', 'price' => 39.99, 'sku' => 'SPRT-001', 'stock' => 55],
            ['category_id' => 4, 'name' => 'Adjustable Dumbbell Set', 'slug' => 'adjustable-dumbbell-set', 'short_description' => '5-50 lbs adjustable dumbbells', 'description' => 'Space-saving adjustable dumbbells from 5 to 50 lbs. Quick-change weight system with ergonomic grip and durable construction.', 'price' => 249.99, 'sale_price' => 199.99, 'sku' => 'SPRT-002', 'stock' => 20, 'is_featured' => true],
            ['category_id' => 4, 'name' => 'Running Shoes Ultra', 'slug' => 'running-shoes-ultra', 'short_description' => 'Lightweight performance running shoes', 'description' => 'Engineered mesh upper with responsive foam midsole, carbon fiber plate, and rubber outsole. Designed for speed and long-distance comfort.', 'price' => 159.99, 'sale_price' => 129.99, 'sku' => 'SPRT-003', 'stock' => 40],
            ['category_id' => 4, 'name' => 'Resistance Bands Set', 'slug' => 'resistance-bands-set', 'short_description' => '5-piece resistance bands with handles', 'description' => 'Complete exercise band set with 5 resistance levels, foam handles, ankle straps, door anchor, and travel carry bag. Perfect for home workouts.', 'price' => 24.99, 'sku' => 'SPRT-004', 'stock' => 100],
            ['category_id' => 4, 'name' => 'Insulated Water Bottle', 'slug' => 'insulated-water-bottle', 'short_description' => '32oz vacuum insulated steel bottle', 'description' => 'Triple-wall vacuum insulation keeps drinks cold 24h or hot 12h. BPA-free, leak-proof lid, wide mouth, and powder-coated finish.', 'price' => 34.99, 'sku' => 'SPRT-005', 'stock' => 75],
            ['category_id' => 4, 'name' => 'Jump Rope Pro', 'slug' => 'jump-rope-pro', 'short_description' => 'Speed jump rope with ball bearings', 'description' => 'Professional speed rope with precision ball bearings, adjustable steel cable, anti-slip foam handles, and carry pouch. Ideal for HIIT and boxing.', 'price' => 19.99, 'sku' => 'SPRT-006', 'stock' => 120],

            // ── Books (category_id: 5) ────────────────────────────────────
            ['category_id' => 5, 'name' => 'The Art of Programming', 'slug' => 'the-art-of-programming', 'short_description' => 'Comprehensive programming guide', 'description' => 'Master the fundamentals and advanced concepts of programming with this 500-page comprehensive guide covering algorithms, data structures, and design patterns.', 'price' => 44.99, 'sku' => 'BOOK-001', 'stock' => 100],
            ['category_id' => 5, 'name' => 'Business Strategy Masterclass', 'slug' => 'business-strategy-masterclass', 'short_description' => 'Learn business strategy fundamentals', 'description' => 'Discover proven business strategies from industry leaders with case studies, frameworks, and actionable insights for entrepreneurs.', 'price' => 29.99, 'sku' => 'BOOK-002', 'stock' => 85],
            ['category_id' => 5, 'name' => 'Design Thinking Handbook', 'slug' => 'design-thinking-handbook', 'short_description' => 'Creative problem-solving through design', 'description' => 'Learn the five-stage design thinking process with real-world case studies from Apple, Google, and IDEO. Includes worksheets and templates.', 'price' => 36.99, 'sale_price' => 27.99, 'sku' => 'BOOK-003', 'stock' => 60, 'is_featured' => true],
            ['category_id' => 5, 'name' => 'Mindful Living Guide', 'slug' => 'mindful-living-guide', 'short_description' => 'Practical mindfulness for daily life', 'description' => 'Transform your daily routine with evidence-based mindfulness practices. Includes guided meditations, journaling prompts, and stress-reduction techniques.', 'price' => 22.99, 'sku' => 'BOOK-004', 'stock' => 90],
            ['category_id' => 5, 'name' => 'Financial Freedom Blueprint', 'slug' => 'financial-freedom-blueprint', 'short_description' => 'Personal finance and investing guide', 'description' => 'Step-by-step guide to building wealth through budgeting, saving, investing in stocks, real estate, and building passive income streams.', 'price' => 32.99, 'sale_price' => 24.99, 'sku' => 'BOOK-005', 'stock' => 75],
            ['category_id' => 5, 'name' => 'Creative Writing Workshop', 'slug' => 'creative-writing-workshop', 'short_description' => 'Master fiction and non-fiction writing', 'description' => 'Comprehensive guide to creative writing covering storytelling, character development, world-building, dialogue, and getting published.', 'price' => 28.99, 'sku' => 'BOOK-006', 'stock' => 65],

            // ── Beauty (category_id: 6) ───────────────────────────────────
            ['category_id' => 6, 'name' => 'Vitamin C Serum', 'slug' => 'vitamin-c-serum', 'short_description' => 'Anti-aging brightening serum', 'description' => '20% Vitamin C serum with hyaluronic acid and vitamin E for brighter, younger-looking skin. Dermatologist recommended.', 'price' => 24.99, 'sku' => 'BEAU-001', 'stock' => 90],
            ['category_id' => 6, 'name' => 'Luxury Skincare Gift Set', 'slug' => 'luxury-skincare-gift-set', 'short_description' => 'Premium 5-piece skincare kit', 'description' => 'Complete skincare routine in a luxury gift box: cleanser, toner, serum, moisturizer, and eye cream. Made with natural ingredients.', 'price' => 89.99, 'sale_price' => 69.99, 'sku' => 'BEAU-002', 'stock' => 30, 'is_featured' => true],
            ['category_id' => 6, 'name' => 'Professional Hair Dryer', 'slug' => 'professional-hair-dryer', 'short_description' => 'Ionic technology salon hair dryer', 'description' => 'Salon-grade 2200W hair dryer with ionic technology to reduce frizz, 3 heat settings, cool shot button, and diffuser attachment included.', 'price' => 69.99, 'sale_price' => 54.99, 'sku' => 'BEAU-003', 'stock' => 40],
            ['category_id' => 6, 'name' => 'Retinol Night Cream', 'slug' => 'retinol-night-cream', 'short_description' => 'Anti-wrinkle retinol moisturizer', 'description' => 'Powerful 2.5% retinol night cream with shea butter and jojoba oil. Reduces fine lines, evens skin tone, and boosts collagen production.', 'price' => 34.99, 'sku' => 'BEAU-004', 'stock' => 55],
            ['category_id' => 6, 'name' => 'Makeup Brush Set', 'slug' => 'makeup-brush-set', 'short_description' => '15-piece professional brush collection', 'description' => 'Premium 15-piece synthetic brush set with rose gold handles, vegan bristles, and faux leather travel case. Includes face, eye, and lip brushes.', 'price' => 42.99, 'sale_price' => 32.99, 'sku' => 'BEAU-005', 'stock' => 50],
            ['category_id' => 6, 'name' => 'Essential Oils Starter Kit', 'slug' => 'essential-oils-starter-kit', 'short_description' => '8-piece pure essential oils set', 'description' => 'Therapeutic-grade essential oils set: lavender, eucalyptus, tea tree, peppermint, lemon, orange, rosemary, and frankincense. With wooden storage box.', 'price' => 38.99, 'sku' => 'BEAU-006', 'stock' => 60],

            // ══════════════════════════════════════════════════════
            // ADDITIONAL 30 PRODUCTS — 5 per category
            // ══════════════════════════════════════════════════════

            // ── Electronics (extras) ─────────────────────────────
            ['category_id' => 1, 'name' => 'USB-C Docking Station', 'slug' => 'usb-c-docking-station', 'short_description' => '12-in-1 USB-C hub for laptops', 'description' => 'Transform your laptop into a powerhouse workstation with dual HDMI 4K output, Ethernet, USB 3.0 ports, SD/microSD card reader, and 100W power delivery pass-through.', 'price' => 79.99, 'sale_price' => 64.99, 'sku' => 'ELEC-008', 'stock' => 60],
            ['category_id' => 1, 'name' => 'Smart Home Speaker', 'slug' => 'smart-home-speaker', 'short_description' => 'Voice-controlled smart speaker', 'description' => 'Premium 360° sound smart speaker with built-in voice assistant, multi-room audio, Bluetooth 5.2, and minimalist fabric design. Control your smart home hands-free.', 'price' => 99.99, 'sku' => 'ELEC-009', 'stock' => 45, 'is_featured' => true],
            ['category_id' => 1, 'name' => 'Portable SSD 1TB', 'slug' => 'portable-ssd-1tb', 'short_description' => 'Ultra-fast external solid state drive', 'description' => '1TB portable NVMe SSD with transfer speeds up to 1050MB/s, shock-resistant aluminum shell, USB-C and USB-A cables included.', 'price' => 109.99, 'sale_price' => 89.99, 'sku' => 'ELEC-010', 'stock' => 70],
            ['category_id' => 1, 'name' => 'Noise Cancelling Microphone', 'slug' => 'noise-cancelling-microphone', 'short_description' => 'USB condenser mic for streaming', 'description' => 'Studio-quality USB condenser microphone with AI noise cancellation, cardioid pickup, tap-to-mute, RGB ring light, and adjustable desktop stand.', 'price' => 69.99, 'sku' => 'ELEC-011', 'stock' => 55],
            ['category_id' => 1, 'name' => 'Digital Drawing Tablet', 'slug' => 'digital-drawing-tablet', 'short_description' => '10-inch drawing tablet with stylus', 'description' => '10-inch digital drawing tablet with 8192 pressure levels, tilt support, 6 customizable shortcut keys, and battery-free stylus. Compatible with all major design software.', 'price' => 59.99, 'sale_price' => 49.99, 'sku' => 'ELEC-012', 'stock' => 35],

            // ── Fashion (extras) ─────────────────────────────────
            ['category_id' => 2, 'name' => 'Cashmere Crewneck Sweater', 'slug' => 'cashmere-crewneck-sweater', 'short_description' => 'Luxury 100% cashmere knit', 'description' => 'Indulge in pure luxury with this 100% Mongolian cashmere crewneck. Ultra-soft, lightweight warmth with ribbed cuffs and hem. Dry clean only.', 'price' => 189.99, 'sale_price' => 149.99, 'sku' => 'FASH-007', 'stock' => 20, 'is_featured' => true],
            ['category_id' => 2, 'name' => 'Leather Belt Premium', 'slug' => 'leather-belt-premium', 'short_description' => 'Full-grain Italian leather belt', 'description' => 'Handcrafted full-grain Italian leather belt with brushed nickel buckle. Featuring hand-stitched edges and a timeless design that pairs with any outfit.', 'price' => 49.99, 'sku' => 'FASH-008', 'stock' => 80],
            ['category_id' => 2, 'name' => 'Linen Summer Shirt', 'slug' => 'linen-summer-shirt', 'short_description' => 'Breathable premium linen shirt', 'description' => 'Stay cool in this 100% French linen shirt. Features a relaxed fit, mother-of-pearl buttons, chest pocket, and pre-washed softness. Perfect for warm weather.', 'price' => 64.99, 'sku' => 'FASH-009', 'stock' => 50],
            ['category_id' => 2, 'name' => 'Minimalist Analog Watch', 'slug' => 'minimalist-analog-watch', 'short_description' => 'Japanese quartz dress watch', 'description' => 'Elegant minimalist watch with Japanese Miyota quartz movement, sapphire crystal, genuine leather strap, and 40mm stainless steel case. Water-resistant to 50m.', 'price' => 129.99, 'sale_price' => 99.99, 'sku' => 'FASH-010', 'stock' => 30],
            ['category_id' => 2, 'name' => 'Merino Wool Scarf', 'slug' => 'merino-wool-scarf', 'short_description' => 'Super-soft merino wool scarf', 'description' => 'Luxuriously soft merino wool scarf in a generous 200cm x 70cm size. Features a herringbone weave pattern and finished edges. Hypoallergenic and itch-free.', 'price' => 44.99, 'sku' => 'FASH-011', 'stock' => 65],

            // ── Home & Living (extras) ───────────────────────────
            ['category_id' => 3, 'name' => 'French Press Coffee Maker', 'slug' => 'french-press-coffee-maker', 'short_description' => 'Double-wall insulated French press', 'description' => '34oz double-wall stainless steel French press with 4-level filtration system, heat-resistant handle, and keeps coffee hot for 60+ minutes. Dishwasher safe.', 'price' => 39.99, 'sku' => 'HOME-007', 'stock' => 55],
            ['category_id' => 3, 'name' => 'Wall Art Canvas Set', 'slug' => 'wall-art-canvas-set', 'short_description' => '3-piece abstract canvas wall art', 'description' => 'Modern 3-piece abstract canvas art set in black, blue, and gold. Gallery-wrapped on solid wood frames, ready to hang. Each panel measures 24x36 inches.', 'price' => 89.99, 'sale_price' => 69.99, 'sku' => 'HOME-008', 'stock' => 25, 'is_featured' => true],
            ['category_id' => 3, 'name' => 'Bamboo Cutting Board Set', 'slug' => 'bamboo-cutting-board-set', 'short_description' => '3-piece organic bamboo boards', 'description' => 'Set of 3 premium organic bamboo cutting boards in small, medium, and large sizes. Knife-friendly, antimicrobial, and includes juice grooves.', 'price' => 29.99, 'sku' => 'HOME-009', 'stock' => 80],
            ['category_id' => 3, 'name' => 'Aroma Diffuser', 'slug' => 'aroma-diffuser', 'short_description' => 'Ultrasonic essential oil diffuser', 'description' => '400ml ultrasonic aroma diffuser with 7 LED colors, whisper-quiet operation, auto shut-off, and up to 12 hours of continuous mist. Wooden grain finish.', 'price' => 34.99, 'sale_price' => 27.99, 'sku' => 'HOME-010', 'stock' => 60],
            ['category_id' => 3, 'name' => 'Memory Foam Pillow', 'slug' => 'memory-foam-pillow', 'short_description' => 'Contour cervical support pillow', 'description' => 'Orthopedic contour memory foam pillow with cooling gel infusion, bamboo charcoal cover, and ergonomic dual-height design for side and back sleepers.', 'price' => 49.99, 'sku' => 'HOME-011', 'stock' => 45],

            // ── Sports (extras) ──────────────────────────────────
            ['category_id' => 4, 'name' => 'Foam Roller Massager', 'slug' => 'foam-roller-massager', 'short_description' => 'Deep tissue muscle roller', 'description' => 'High-density EVA foam roller with textured surface for deep tissue massage, muscle recovery, and flexibility. 18-inch length with carrying bag.', 'price' => 29.99, 'sku' => 'SPRT-007', 'stock' => 70],
            ['category_id' => 4, 'name' => 'Cycling Gloves Pro', 'slug' => 'cycling-gloves-pro', 'short_description' => 'Padded half-finger cycling gloves', 'description' => 'Professional half-finger cycling gloves with gel padding, anti-slip silicone grip, breathable mesh back, and magnetic clasp closure. Reflective strips for safety.', 'price' => 24.99, 'sale_price' => 19.99, 'sku' => 'SPRT-008', 'stock' => 85],
            ['category_id' => 4, 'name' => 'Kettlebell Cast Iron 20kg', 'slug' => 'kettlebell-cast-iron-20kg', 'short_description' => 'Single cast iron kettlebell', 'description' => 'Premium single-cast iron kettlebell with wide flat base for stability, smooth handle for comfortable grip, and powder-coated finish to resist corrosion.', 'price' => 54.99, 'sku' => 'SPRT-009', 'stock' => 40],
            ['category_id' => 4, 'name' => 'Sports Compression Socks', 'slug' => 'sports-compression-socks', 'short_description' => '3-pack graduated compression socks', 'description' => '3-pack of 20-30 mmHg graduated compression socks for running, recovery, and travel. Moisture-wicking fabric with arch support and cushioned heel.', 'price' => 19.99, 'sku' => 'SPRT-010', 'stock' => 100],
            ['category_id' => 4, 'name' => 'Pull-Up Bar Doorway', 'slug' => 'pull-up-bar-doorway', 'short_description' => 'Multi-grip doorway pull-up bar', 'description' => 'Heavy-duty steel doorway pull-up bar with 6 grip positions, foam padded handles, and no-screw installation. Supports up to 300 lbs. Fits doors 26-36 inches wide.', 'price' => 34.99, 'sale_price' => 27.99, 'sku' => 'SPRT-011', 'stock' => 55, 'is_featured' => true],

            // ── Books (extras) ───────────────────────────────────
            ['category_id' => 5, 'name' => 'Data Science Fundamentals', 'slug' => 'data-science-fundamentals', 'short_description' => 'Complete data science learning guide', 'description' => 'From Python to machine learning. This 600-page guide covers statistics, data visualization, pandas, scikit-learn, and deep learning with TensorFlow.', 'price' => 49.99, 'sale_price' => 39.99, 'sku' => 'BOOK-007', 'stock' => 55, 'is_featured' => true],
            ['category_id' => 5, 'name' => 'Leadership Principles', 'slug' => 'leadership-principles', 'short_description' => 'Modern leadership for the digital age', 'description' => 'Learn leadership frameworks from CEOs of Fortune 500 companies. Covers remote team management, emotional intelligence, and decision-making under uncertainty.', 'price' => 27.99, 'sku' => 'BOOK-008', 'stock' => 70],
            ['category_id' => 5, 'name' => 'Photography Masterclass', 'slug' => 'photography-masterclass', 'short_description' => 'Professional photography techniques', 'description' => 'Comprehensive photography guide covering composition, lighting, post-processing, landscape, portrait, and street photography with 200+ full-color examples.', 'price' => 42.99, 'sale_price' => 34.99, 'sku' => 'BOOK-009', 'stock' => 40],
            ['category_id' => 5, 'name' => 'Healthy Meal Prep Cookbook', 'slug' => 'healthy-meal-prep-cookbook', 'short_description' => '100+ easy meal prep recipes', 'description' => 'Simplify your week with 100+ healthy, delicious meal prep recipes. Includes shopping lists, nutrition info, storage tips, and 30-day meal plans for various diets.', 'price' => 24.99, 'sku' => 'BOOK-010', 'stock' => 80],
            ['category_id' => 5, 'name' => 'Cybersecurity Essentials', 'slug' => 'cybersecurity-essentials', 'short_description' => 'Protect your digital life', 'description' => 'Practical cybersecurity guide for professionals and beginners. Covers network security, ethical hacking, encryption, incident response, and compliance frameworks.', 'price' => 39.99, 'sku' => 'BOOK-011', 'stock' => 50],

            // ── Beauty (extras) ──────────────────────────────────
            ['category_id' => 6, 'name' => 'Jade Facial Roller Set', 'slug' => 'jade-facial-roller-set', 'short_description' => 'Natural jade roller and gua sha', 'description' => 'Authentic natural jade roller and gua sha set for facial massage, lymphatic drainage, and reducing puffiness. Comes in a silk-lined gift box.', 'price' => 29.99, 'sale_price' => 22.99, 'sku' => 'BEAU-007', 'stock' => 75],
            ['category_id' => 6, 'name' => 'Micellar Cleansing Water', 'slug' => 'micellar-cleansing-water', 'short_description' => 'Gentle all-in-one cleanser', 'description' => 'Gentle micellar water that cleanses, removes makeup, and tones in one step. Formulated with rose extract and hyaluronic acid. No rinse needed.', 'price' => 14.99, 'sku' => 'BEAU-008', 'stock' => 100],
            ['category_id' => 6, 'name' => 'Hair Straightener Ceramic', 'slug' => 'hair-straightener-ceramic', 'short_description' => 'Tourmaline ceramic flat iron', 'description' => 'Professional tourmaline ceramic flat iron with floating plates, adjustable temp 250-450°F, auto shut-off, dual voltage for travel, and heat-resistant pouch.', 'price' => 54.99, 'sale_price' => 42.99, 'sku' => 'BEAU-009', 'stock' => 45],
            ['category_id' => 6, 'name' => 'SPF 50 Sunscreen Lotion', 'slug' => 'spf-50-sunscreen-lotion', 'short_description' => 'Mineral sunscreen broad spectrum', 'description' => 'Reef-safe mineral sunscreen with SPF 50 broad spectrum protection, lightweight non-greasy formula, water-resistant 80 minutes, enriched with aloe and vitamin E.', 'price' => 18.99, 'sku' => 'BEAU-010', 'stock' => 90],
            ['category_id' => 6, 'name' => 'Organic Lip Balm Set', 'slug' => 'organic-lip-balm-set', 'short_description' => '6-pack organic lip balm assortment', 'description' => 'USDA certified organic lip balm set of 6 flavors: vanilla, honey, strawberry, mint, coconut, and mango. Made with beeswax, coconut oil, and shea butter.', 'price' => 12.99, 'sku' => 'BEAU-011', 'stock' => 120],

            // ══════════════════════════════════════════════════════
            // 50 MORE PRODUCTS — ~8 per category
            // ══════════════════════════════════════════════════════

            // ── Electronics (8 more) ─────────────────────────────
            ['category_id' => 1, 'name' => 'Curved Gaming Monitor 27"', 'slug' => 'curved-gaming-monitor-27', 'short_description' => '165Hz QHD curved display', 'description' => '27-inch QHD 2560x1440 curved VA panel with 165Hz refresh rate, 1ms response time, FreeSync Premium, and HDR400 for immersive gaming.', 'price' => 399.99, 'sale_price' => 329.99, 'sku' => 'ELEC-013', 'stock' => 20, 'is_featured' => true],
            ['category_id' => 1, 'name' => 'Wireless Charging Pad Duo', 'slug' => 'wireless-charging-pad-duo', 'short_description' => 'Dual 15W Qi wireless charger', 'description' => 'Charge two devices simultaneously with this sleek dual wireless charging pad. 15W fast charge for compatible phones, AirPods, and more.', 'price' => 39.99, 'sku' => 'ELEC-014', 'stock' => 80],
            ['category_id' => 1, 'name' => 'Drone Camera 4K GPS', 'slug' => 'drone-camera-4k-gps', 'short_description' => 'Foldable GPS drone with 4K camera', 'description' => 'Foldable quadcopter with 4K UHD camera, 3-axis gimbal stabilization, GPS return-to-home, 30-min flight time, and obstacle avoidance sensors.', 'price' => 499.99, 'sale_price' => 399.99, 'sku' => 'ELEC-015', 'stock' => 15, 'is_featured' => true],
            ['category_id' => 1, 'name' => 'Smart LED Light Bulb Pack', 'slug' => 'smart-led-light-bulb-pack', 'short_description' => '4-pack WiFi smart LED bulbs', 'description' => '4-pack of WiFi-enabled smart LED bulbs with 16M colors, dimmable white 2700K-6500K, voice control via Alexa/Google, and scheduling through app.', 'price' => 34.99, 'sale_price' => 27.99, 'sku' => 'ELEC-016', 'stock' => 90],
            ['category_id' => 1, 'name' => 'Portable Bluetooth Speaker', 'slug' => 'portable-bluetooth-speaker', 'short_description' => 'Waterproof 360° sound speaker', 'description' => 'IPX7 waterproof Bluetooth 5.3 speaker with 360° immersive sound, 24-hour playtime, built-in microphone, and rugged design for outdoor adventures.', 'price' => 69.99, 'sku' => 'ELEC-017', 'stock' => 55],
            ['category_id' => 1, 'name' => 'E-Reader 7" Paperwhite', 'slug' => 'e-reader-7-paperwhite', 'short_description' => 'Anti-glare e-ink reading tablet', 'description' => '7-inch 300ppi Paperwhite display with adjustable warm light, waterproof IPX8, 32GB storage, weeks of battery life, and USB-C charging.', 'price' => 139.99, 'sale_price' => 119.99, 'sku' => 'ELEC-018', 'stock' => 30],
            ['category_id' => 1, 'name' => 'Robot Vacuum Smart', 'slug' => 'robot-vacuum-smart', 'short_description' => 'AI-powered robot vacuum & mop', 'description' => 'LiDAR navigation robot vacuum with 4000Pa suction, auto-emptying dock, intelligent mopping, multi-floor mapping, and app/voice control.', 'price' => 349.99, 'sale_price' => 279.99, 'sku' => 'ELEC-019', 'stock' => 18],
            ['category_id' => 1, 'name' => 'Mini Projector HD', 'slug' => 'mini-projector-hd', 'short_description' => 'Portable 1080p LED projector', 'description' => 'Compact 1080p native LED projector with 200 ANSI lumens, auto keystone, WiFi 6, Bluetooth, built-in speaker, and up to 150-inch projection.', 'price' => 199.99, 'sku' => 'ELEC-020', 'stock' => 25],

            // ── Fashion (8 more) ─────────────────────────────────
            ['category_id' => 2, 'name' => 'Silk Pocket Square Set', 'slug' => 'silk-pocket-square-set', 'short_description' => '5-piece Italian silk pocket squares', 'description' => 'Set of 5 hand-rolled Italian silk pocket squares in classic patterns: paisley, polka dot, solid, striped, and floral. Gift-boxed.', 'price' => 59.99, 'sale_price' => 44.99, 'sku' => 'FASH-012', 'stock' => 40],
            ['category_id' => 2, 'name' => 'Denim Trucker Jacket', 'slug' => 'denim-trucker-jacket', 'short_description' => 'Classic washed denim jacket', 'description' => 'Iconic trucker silhouette in premium washed denim with sherpa-lined collar option, chest pockets, and adjustable waist tabs.', 'price' => 89.99, 'sku' => 'FASH-013', 'stock' => 35],
            ['category_id' => 2, 'name' => 'Bamboo Fiber T-Shirt Pack', 'slug' => 'bamboo-fiber-tshirt-pack', 'short_description' => '3-pack sustainable bamboo tees', 'description' => 'Ultra-soft 3-pack of bamboo fiber crew-neck t-shirts. Naturally antibacterial, moisture-wicking, breathable, and eco-friendly.', 'price' => 44.99, 'sale_price' => 34.99, 'sku' => 'FASH-014', 'stock' => 70],
            ['category_id' => 2, 'name' => 'Tactical Sports Watch', 'slug' => 'tactical-sports-watch', 'short_description' => 'Military-grade outdoor watch', 'description' => 'Rugged military-grade tactical watch with compass, altimeter, barometer, thermometer, 100m water resistance, and sapphire crystal.', 'price' => 179.99, 'sale_price' => 139.99, 'sku' => 'FASH-015', 'stock' => 25, 'is_featured' => true],
            ['category_id' => 2, 'name' => 'Crossbody Messenger Bag', 'slug' => 'crossbody-messenger-bag', 'short_description' => 'Vintage canvas messenger', 'description' => 'Vintage-style waxed canvas crossbody messenger bag with leather flap, adjustable strap, and multiple organizer pockets.', 'price' => 64.99, 'sku' => 'FASH-016', 'stock' => 50],
            ['category_id' => 2, 'name' => 'Performance Polo Shirt', 'slug' => 'performance-polo-shirt', 'short_description' => 'Moisture-wicking stretch polo', 'description' => 'Premium performance polo with 4-way stretch, moisture-wicking DryFit technology, UPF 50+ sun protection, and anti-odor treatment.', 'price' => 49.99, 'sku' => 'FASH-017', 'stock' => 60],
            ['category_id' => 2, 'name' => 'Leather Chelsea Boots', 'slug' => 'leather-chelsea-boots', 'short_description' => 'Genuine leather chelsea boots', 'description' => 'Handcrafted genuine leather Chelsea boots with Goodyear welt construction, elastic side panels, pull tab, and cushioned insole.', 'price' => 199.99, 'sale_price' => 159.99, 'sku' => 'FASH-018', 'stock' => 22],
            ['category_id' => 2, 'name' => 'Titanium Frame Sunglasses', 'slug' => 'titanium-frame-sunglasses', 'short_description' => 'Lightweight titanium polarized shades', 'description' => 'Ultra-lightweight titanium frame sunglasses with polarized TAC lenses, anti-scratch coating, spring hinges, and includes hard case.', 'price' => 79.99, 'sku' => 'FASH-019', 'stock' => 45],

            // ── Home & Living (8 more) ───────────────────────────
            ['category_id' => 3, 'name' => 'Cast Iron Dutch Oven', 'slug' => 'cast-iron-dutch-oven', 'short_description' => '6-quart enameled dutch oven', 'description' => '6-quart enameled cast iron dutch oven with self-basting lid, heat-safe handles up to 500°F, even heat distribution.', 'price' => 89.99, 'sale_price' => 69.99, 'sku' => 'HOME-012', 'stock' => 30, 'is_featured' => true],
            ['category_id' => 3, 'name' => 'Smart Thermostat WiFi', 'slug' => 'smart-thermostat-wifi', 'short_description' => 'Learning smart thermostat', 'description' => 'AI-powered learning thermostat that adjusts to your schedule. WiFi-enabled, voice compatible, energy usage reports.', 'price' => 179.99, 'sale_price' => 149.99, 'sku' => 'HOME-013', 'stock' => 25],
            ['category_id' => 3, 'name' => 'Luxury Bath Towel Set', 'slug' => 'luxury-bath-towel-set', 'short_description' => '6-piece Turkish cotton towels', 'description' => '6-piece set of 700 GSM Turkish cotton towels: 2 bath towels, 2 hand towels, 2 washcloths. Ultra-plush, quick-drying.', 'price' => 59.99, 'sku' => 'HOME-014', 'stock' => 40],
            ['category_id' => 3, 'name' => 'Electric Standing Desk', 'slug' => 'electric-standing-desk', 'short_description' => 'Dual-motor sit-stand desk', 'description' => '60x30 inch electric standing desk with dual motors, memory presets, cable management tray, anti-collision sensor, and bamboo desktop.', 'price' => 449.99, 'sale_price' => 379.99, 'sku' => 'HOME-015', 'stock' => 12, 'is_featured' => true],
            ['category_id' => 3, 'name' => 'Indoor Herb Garden Kit', 'slug' => 'indoor-herb-garden-kit', 'short_description' => 'LED hydroponic herb garden', 'description' => 'Countertop hydroponic herb garden with full-spectrum LED grow lights, auto water circulation, and 6 pod capacity.', 'price' => 49.99, 'sku' => 'HOME-016', 'stock' => 35],
            ['category_id' => 3, 'name' => 'Velvet Throw Pillow Set', 'slug' => 'velvet-throw-pillow-set', 'short_description' => '4-pack luxury velvet cushions', 'description' => '4-pack of 18x18 inch luxury velvet throw pillows with hidden zippers, hypoallergenic fill, in emerald, sapphire, burgundy, and gold.', 'price' => 39.99, 'sale_price' => 29.99, 'sku' => 'HOME-017', 'stock' => 55],
            ['category_id' => 3, 'name' => 'Automatic Espresso Machine', 'slug' => 'automatic-espresso-machine', 'short_description' => 'Bean-to-cup espresso maker', 'description' => 'Fully automatic bean-to-cup espresso machine with built-in grinder, 15-bar pump, steam wand, and programmable drink sizes.', 'price' => 299.99, 'sale_price' => 249.99, 'sku' => 'HOME-018', 'stock' => 15],
            ['category_id' => 3, 'name' => 'Geometric Wall Shelf Set', 'slug' => 'geometric-wall-shelf-set', 'short_description' => '3-piece floating wall shelves', 'description' => '3-piece set of hexagonal geometric floating wall shelves in matte black metal with wooden bases. Perfect for displaying decor.', 'price' => 44.99, 'sku' => 'HOME-019', 'stock' => 45],

            // ── Sports (8 more) ──────────────────────────────────
            ['category_id' => 4, 'name' => 'Smart Fitness Tracker', 'slug' => 'smart-fitness-tracker', 'short_description' => 'Advanced fitness band with GPS', 'description' => 'Slim fitness tracker with built-in GPS, heart rate, SpO2, sleep tracking, 14-day battery, 5ATM waterproof, and 100+ workout modes.', 'price' => 79.99, 'sale_price' => 59.99, 'sku' => 'SPRT-012', 'stock' => 65, 'is_featured' => true],
            ['category_id' => 4, 'name' => 'Boxing Training Gloves', 'slug' => 'boxing-training-gloves', 'short_description' => '12oz premium boxing gloves', 'description' => '12oz premium synthetic leather boxing gloves with multi-layer foam padding, moisture-wicking interior, and hook-and-loop wrist closure.', 'price' => 44.99, 'sku' => 'SPRT-013', 'stock' => 40],
            ['category_id' => 4, 'name' => 'Camping Hammock Ultra', 'slug' => 'camping-hammock-ultra', 'short_description' => 'Lightweight portable travel hammock', 'description' => 'Ultra-lightweight parachute nylon hammock that holds 500 lbs. Includes tree-friendly straps, carabiners, and stuff sack.', 'price' => 29.99, 'sale_price' => 22.99, 'sku' => 'SPRT-014', 'stock' => 80],
            ['category_id' => 4, 'name' => 'Smart Jump Rope Digital', 'slug' => 'smart-jump-rope-digital', 'short_description' => 'LED counter speed rope', 'description' => 'Digital smart jump rope with LED counter display, calorie tracking, adjustable length, and ball-bearing mechanism.', 'price' => 24.99, 'sku' => 'SPRT-015', 'stock' => 90],
            ['category_id' => 4, 'name' => 'Yoga Block Set Cork', 'slug' => 'yoga-block-set-cork', 'short_description' => '2-pack natural cork yoga blocks', 'description' => '2-pack of premium natural cork yoga blocks with rounded edges, non-slip surface, and included cotton yoga strap.', 'price' => 19.99, 'sku' => 'SPRT-016', 'stock' => 100],
            ['category_id' => 4, 'name' => 'Running Vest Hydration', 'slug' => 'running-vest-hydration', 'short_description' => 'Trail running hydration vest', 'description' => 'Lightweight trail running vest with 2L hydration bladder, 6 pockets for nutrition, reflective strips, and adjustable chest straps.', 'price' => 54.99, 'sale_price' => 42.99, 'sku' => 'SPRT-017', 'stock' => 30],
            ['category_id' => 4, 'name' => 'Ab Roller Wheel Pro', 'slug' => 'ab-roller-wheel-pro', 'short_description' => 'Wide-wheel core trainer', 'description' => 'Premium wide-wheel ab roller with ergonomic handles, built-in resistance spring, knee pad included, and ultra-quiet bearing.', 'price' => 29.99, 'sku' => 'SPRT-018', 'stock' => 70],
            ['category_id' => 4, 'name' => 'Swim Goggles Anti-Fog', 'slug' => 'swim-goggles-anti-fog', 'short_description' => 'UV protection racing goggles', 'description' => 'Professional swim goggles with anti-fog coating, UV protection, adjustable nose bridge, and silicone gaskets for leak-proof seal.', 'price' => 16.99, 'sku' => 'SPRT-019', 'stock' => 110],

            // ── Books (9 more) ───────────────────────────────────
            ['category_id' => 5, 'name' => 'AI & Machine Learning Guide', 'slug' => 'ai-machine-learning-guide', 'short_description' => 'Practical AI handbook for developers', 'description' => 'Hands-on guide to building AI applications with PyTorch and TensorFlow. Covers neural networks, NLP, and computer vision.', 'price' => 54.99, 'sale_price' => 44.99, 'sku' => 'BOOK-012', 'stock' => 40, 'is_featured' => true],
            ['category_id' => 5, 'name' => 'Stoicism Daily Practice', 'slug' => 'stoicism-daily-practice', 'short_description' => 'Ancient wisdom for modern life', 'description' => '365 daily meditations inspired by Marcus Aurelius, Seneca, and Epictetus. Each entry includes a quote and practical exercise.', 'price' => 19.99, 'sku' => 'BOOK-013', 'stock' => 85],
            ['category_id' => 5, 'name' => 'UX Design Principles', 'slug' => 'ux-design-principles', 'short_description' => 'User experience design fundamentals', 'description' => 'Comprehensive UX design reference covering user research, wireframing, prototyping, usability testing, and design systems.', 'price' => 39.99, 'sale_price' => 32.99, 'sku' => 'BOOK-014', 'stock' => 50],
            ['category_id' => 5, 'name' => 'World History Illustrated', 'slug' => 'world-history-illustrated', 'short_description' => 'Visual journey through history', 'description' => 'Stunning 400-page illustrated world history from ancient civilizations to modern era. Features 800+ maps and infographics.', 'price' => 49.99, 'sku' => 'BOOK-015', 'stock' => 30],
            ['category_id' => 5, 'name' => 'Startup Founder Playbook', 'slug' => 'startup-founder-playbook', 'short_description' => 'Launch your startup the right way', 'description' => 'Step-by-step guide from idea validation to Series A. Covers MVP development, customer discovery, and fundraising.', 'price' => 34.99, 'sale_price' => 27.99, 'sku' => 'BOOK-016', 'stock' => 60],
            ['category_id' => 5, 'name' => 'Gourmet Baking Bible', 'slug' => 'gourmet-baking-bible', 'short_description' => '200+ artisan baking recipes', 'description' => 'Master artisan baking with 200+ recipes for sourdough, croissants, pastries, cakes, and confections.', 'price' => 42.99, 'sku' => 'BOOK-017', 'stock' => 45],
            ['category_id' => 5, 'name' => 'Psychology of Habits', 'slug' => 'psychology-of-habits', 'short_description' => 'Build better habits with science', 'description' => 'Science-backed guide to habit formation drawing on neuroscience and behavioral psychology. Includes 30-day tracker workbook.', 'price' => 24.99, 'sku' => 'BOOK-018', 'stock' => 75],
            ['category_id' => 5, 'name' => 'Space Exploration Encyclopedia', 'slug' => 'space-exploration-encyclopedia', 'short_description' => 'Complete guide to space and cosmos', 'description' => 'Comprehensive encyclopedia of space exploration from Mercury program to Mars missions. Features NASA imagery.', 'price' => 39.99, 'sale_price' => 31.99, 'sku' => 'BOOK-019', 'stock' => 35],
            ['category_id' => 5, 'name' => 'Digital Marketing Mastery', 'slug' => 'digital-marketing-mastery', 'short_description' => 'Complete digital marketing course', 'description' => 'Master SEO, social media marketing, email campaigns, PPC advertising, content strategy, and analytics.', 'price' => 37.99, 'sku' => 'BOOK-020', 'stock' => 55],

            // ── Beauty (9 more) ──────────────────────────────────
            ['category_id' => 6, 'name' => 'Collagen Face Mask Pack', 'slug' => 'collagen-face-mask-pack', 'short_description' => '10-pack hydrating sheet masks', 'description' => '10-pack of Korean collagen-infused sheet masks with hyaluronic acid, vitamin C, and snail mucin.', 'price' => 19.99, 'sale_price' => 14.99, 'sku' => 'BEAU-012', 'stock' => 80],
            ['category_id' => 6, 'name' => 'Nail Art Kit Professional', 'slug' => 'nail-art-kit-professional', 'short_description' => 'Complete nail art supplies set', 'description' => '120-piece professional nail art kit with UV/LED lamp, 36 gel polishes, nail tips, tools, glitters, and stickers.', 'price' => 69.99, 'sale_price' => 54.99, 'sku' => 'BEAU-013', 'stock' => 25],
            ['category_id' => 6, 'name' => 'Sonic Facial Cleansing Brush', 'slug' => 'sonic-facial-cleansing-brush', 'short_description' => 'Silicone sonic face cleanser', 'description' => 'Silicone sonic facial cleansing brush with 8000 vibrations/min, 5 intensity levels, waterproof IPX7, and USB rechargeable.', 'price' => 39.99, 'sku' => 'BEAU-014', 'stock' => 50],
            ['category_id' => 6, 'name' => 'Argan Oil Hair Treatment', 'slug' => 'argan-oil-hair-treatment', 'short_description' => 'Pure cold-pressed argan oil', 'description' => '100ml pure cold-pressed Moroccan argan oil for hair and skin. Rich in vitamin E, reduces frizz, adds shine.', 'price' => 24.99, 'sale_price' => 18.99, 'sku' => 'BEAU-015', 'stock' => 65],
            ['category_id' => 6, 'name' => 'Luxury Perfume Collection', 'slug' => 'luxury-perfume-collection', 'short_description' => '4-piece mini perfume gift set', 'description' => 'Gift set of 4 mini luxury perfumes (15ml each) in floral, woody, citrus, and oriental fragrances.', 'price' => 79.99, 'sale_price' => 64.99, 'sku' => 'BEAU-016', 'stock' => 30, 'is_featured' => true],
            ['category_id' => 6, 'name' => 'Teeth Whitening Kit', 'slug' => 'teeth-whitening-kit', 'short_description' => 'LED teeth whitening system', 'description' => 'Professional-grade LED teeth whitening kit with accelerator light, 3 whitening gel pens, and desensitizing gel.', 'price' => 34.99, 'sku' => 'BEAU-017', 'stock' => 55],
            ['category_id' => 6, 'name' => 'Body Scrub Coffee', 'slug' => 'body-scrub-coffee', 'short_description' => 'Arabica coffee body exfoliator', 'description' => 'Invigorating body scrub made with organic Arabica coffee grounds, coconut oil, shea butter, and dead sea salt.', 'price' => 22.99, 'sku' => 'BEAU-018', 'stock' => 70],
            ['category_id' => 6, 'name' => 'Heated Eyelash Curler', 'slug' => 'heated-eyelash-curler', 'short_description' => 'USB rechargeable lash curler', 'description' => 'Electric heated eyelash curler with rapid heat-up, 3 temperature settings, and silicone pad for lash protection.', 'price' => 16.99, 'sku' => 'BEAU-019', 'stock' => 85],
            ['category_id' => 6, 'name' => 'Vitamin E Night Repair Oil', 'slug' => 'vitamin-e-night-repair-oil', 'short_description' => 'Overnight rich repair facial oil', 'description' => 'Concentrated vitamin E facial oil with rosehip, jojoba, and argan oils. Repairs skin overnight. Vegan and cruelty-free.', 'price' => 27.99, 'sale_price' => 21.99, 'sku' => 'BEAU-020', 'stock' => 60],
        ];

        // Picsum photo seed IDs — 116 total (66 original + 50 new)
        $imageSeeds = [
            // Original Electronics (12)
            1, 2, 3, 4, 5, 6, 7,
            // Original Fashion (11)
            10, 11, 12, 13, 14, 15,
            // Original Home & Living (11)
            20, 21, 22, 23, 24, 25,
            // Original Sports (11)
            30, 31, 32, 33, 34, 35,
            // Original Books (11)
            40, 41, 42, 43, 44, 45,
            // Original Beauty (11)
            50, 51, 52, 53, 54, 55,
            // Extra batch Electronics
            60, 61, 62, 63, 64,
            // Extra batch Fashion
            70, 71, 72, 73, 74,
            // Extra batch Home
            80, 81, 82, 83, 84,
            // Extra batch Sports
            90, 91, 92, 93, 94,
            // Extra batch Books
            95, 96, 97, 98, 99,
            // Extra batch Beauty
            101, 102, 103, 104, 105,
            // ── 50 New products ──
            // Electronics (8)
            110, 111, 112, 113, 114, 115, 116, 117,
            // Fashion (8)
            120, 121, 122, 123, 124, 125, 126, 127,
            // Home & Living (8)
            130, 131, 132, 133, 134, 135, 136, 137,
            // Sports (8)
            140, 141, 142, 143, 144, 145, 146, 147,
            // Books (9)
            150, 151, 152, 153, 154, 155, 156, 157, 158,
            // Beauty (9)
            160, 161, 162, 163, 164, 165, 166, 167, 168,
        ];

        foreach ($products as $index => $productData) {
            $product = Product::create($productData);

            $seed = $imageSeeds[$index] ?? ($index + 200);
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => "https://picsum.photos/seed/{$seed}/600/600",
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }
    }
}
