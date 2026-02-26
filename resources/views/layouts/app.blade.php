<!DOCTYPE html>
<html lang="en" x-data="{ mobileMenu: false, cartCount: 0 }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Huzaifa Store') — Premium Shopping</title>
    <meta name="description"
        content="@yield('meta_description', 'Discover premium products at Huzaifa Store. Shop electronics, fashion, home & living, and more with fast delivery.')">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b'
                        },
                        neon: {
                            cyan: '#06b6d4',
                            blue: '#3b82f6',
                            purple: '#8b5cf6',
                            pink: '#ec4899',
                            green: '#10b981',
                            orange: '#f59e0b',
                        },
                        dark: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617'
                        },
                        accent: { 400: '#f87171', 500: '#ef4444', 600: '#dc2626' },
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Alpine.js Intersect Plugin (MUST load before Alpine) -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <style>
        * {
            font-family: 'Inter', system-ui, sans-serif;
            box-sizing: border-box;
        }

        /* ===== SCROLL FIX — Absolute guarantee scrolling works ===== */
        html {
            overflow-x: hidden;
            overflow-y: scroll !important;
            scroll-behavior: smooth;
            height: auto !important;
            min-height: 100vh;
            -webkit-overflow-scrolling: touch;
        }
        body {
            overflow-x: hidden;
            overflow-y: visible !important;
            height: auto !important;
            min-height: 100vh;
            touch-action: pan-y pinch-zoom;
            -webkit-overflow-scrolling: touch;
            position: relative;
        }

        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        .glass-light {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(99, 102, 241, 0.1);
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .glass-card:hover {
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 8px 40px -10px rgba(99, 102, 241, 0.2), 0 0 60px -20px rgba(6, 182, 212, 0.1);
            transform: translateY(-2px);
        }

        /* 3D Card */
        .card-3d {
            transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.6s ease;
            transform-style: preserve-3d;
            will-change: transform;
        }
        .card-3d:hover {
            transform: translateY(-14px) scale(1.03) rotateX(2deg);
            box-shadow: 0 35px 70px -15px rgba(99, 102, 241, 0.25), 0 0 40px -10px rgba(6, 182, 212, 0.15);
        }

        /* Gradient Text Variants */
        .gradient-text {
            background: linear-gradient(135deg, #818cf8, #06b6d4, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .gradient-text-fire {
            background: linear-gradient(135deg, #ef4444, #f97316, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .gradient-text-emerald {
            background: linear-gradient(135deg, #10b981, #06b6d4, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .gradient-text-rose {
            background: linear-gradient(135deg, #f43f5e, #ec4899, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .gradient-text-gold {
            background: linear-gradient(135deg, #f59e0b, #eab308, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Gradient shimmer text */
        .gradient-text-shimmer {
            background: linear-gradient(90deg, #818cf8, #06b6d4, #a78bfa, #ec4899, #818cf8);
            background-size: 300% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmerText 4s linear infinite;
        }
        @keyframes shimmerText {
            0% { background-position: 300% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ===== ANIMATIONS ===== */

        /* Float */
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-12px) rotate(1deg); }
            66% { transform: translateY(-6px) rotate(-1deg); }
        }
        .animate-float { animation: float 4s ease-in-out infinite; }

        @keyframes floatSlow {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }
        .animate-float-slow { animation: floatSlow 6s ease-in-out infinite; }

        /* Morphing blob */
        @keyframes morph {
            0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            25% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
            50% { border-radius: 50% 60% 30% 60% / 30% 60% 70% 40%; }
            75% { border-radius: 60% 40% 60% 30% / 60% 30% 40% 70%; }
        }
        .animate-morph { animation: morph 8s ease-in-out infinite; }

        /* Pulse glow */
        .glow-brand { animation: glowBrand 3s ease-in-out infinite; }
        @keyframes glowBrand {
            0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.15); }
            50% { box-shadow: 0 0 50px rgba(99, 102, 241, 0.35), 0 0 80px rgba(6, 182, 212, 0.1); }
        }

        /* Bounce in */
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.95); }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-bounce-in { animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.27, 1.55); }

        /* Slide in from left */
        @keyframes slideInLeft {
            from { transform: translateX(-60px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .animate-slide-left { animation: slideInLeft 0.7s ease-out; }

        /* Slide in from right */
        @keyframes slideInRight {
            from { transform: translateX(60px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .animate-slide-right { animation: slideInRight 0.7s ease-out; }

        /* Slide up */
        @keyframes slideUp {
            from { transform: translateY(40px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-slide-up { animation: slideUp 0.6s ease-out; }

        /* Wobble */
        @keyframes wobble {
            0%,100% { transform: translateX(0); }
            15% { transform: translateX(-10px) rotate(-3deg); }
            30% { transform: translateX(8px) rotate(2deg); }
            45% { transform: translateX(-6px) rotate(-1.5deg); }
            60% { transform: translateX(4px) rotate(1deg); }
            75% { transform: translateX(-2px) rotate(-0.5deg); }
        }
        .animate-wobble { animation: wobble 1s ease-in-out; }

        /* Glow pulse ring */
        @keyframes glowRing {
            0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.5); }
            70% { box-shadow: 0 0 0 15px rgba(99, 102, 241, 0); }
            100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
        }
        .animate-glow-ring { animation: glowRing 2s infinite; }

        /* Gradient border sweep */
        @keyframes borderSweep {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-border-sweep {
            background: linear-gradient(270deg, #6366f1, #06b6d4, #8b5cf6, #ec4899, #6366f1);
            background-size: 400% 400%;
            animation: borderSweep 4s ease infinite;
        }

        /* Hero gradient */
        .hero-gradient {
            background: linear-gradient(135deg, #020617 0%, #0f172a 20%, #1e1b4b 40%, #0f172a 60%, #020617 80%);
        }

        /* ===== Aurora background — absolutely can't block scrolling ===== */
        .aurora-bg { position: relative; }
        .aurora-bg::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: conic-gradient(from 0deg at 50% 50%,
                transparent 0deg, rgba(99, 102, 241, 0.03) 60deg,
                transparent 120deg, rgba(6, 182, 212, 0.03) 180deg,
                transparent 240deg, rgba(139, 92, 246, 0.03) 300deg,
                transparent 360deg);
            animation: auroraRotate 30s linear infinite;
            pointer-events: none !important;
            z-index: 0;
            user-select: none;
            touch-action: none;
        }
        @keyframes auroraRotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #6366f1, #06b6d4); border-radius: 3px; }

        /* Page enter */
        .page-enter { animation: pageEnter 0.6s cubic-bezier(0.23, 1, 0.32, 1); }
        @keyframes pageEnter {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Image zoom */
        .img-zoom { overflow: hidden; }
        .img-zoom img { transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1); }
        .img-zoom:hover img { transform: scale(1.08); }

        /* Skeleton shimmer */
        .skeleton {
            background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        /* Particles */
        .particle {
            position: absolute; width: 3px; height: 3px; border-radius: 50%;
            pointer-events: none; animation: particleFloat 8s ease-in-out infinite;
        }
        .particle-cyan { background: rgba(6, 182, 212, 0.5); }
        .particle-purple { background: rgba(139, 92, 246, 0.4); }
        .particle-blue { background: rgba(99, 102, 241, 0.4); }
        .particle-pink { background: rgba(236, 72, 153, 0.4); }
        .particle-gold { background: rgba(245, 158, 11, 0.4); }

        @keyframes particleFloat {
            0%, 100% { transform: translateY(0) translateX(0) scale(1); opacity: 0.3; }
            25% { transform: translateY(-30px) translateX(15px) scale(1.5); opacity: 0.7; }
            50% { transform: translateY(-10px) translateX(-10px) scale(0.8); opacity: 0.5; }
            75% { transform: translateY(-40px) translateX(20px) scale(1.2); opacity: 0.6; }
        }

        /* Neon line */
        .neon-line {
            height: 2px;
            background: linear-gradient(90deg, transparent, #6366f1, #06b6d4, #8b5cf6, transparent);
            animation: neonPulse 3s ease-in-out infinite;
        }
        @keyframes neonPulse { 0%, 100% { opacity: 0.4; } 50% { opacity: 1; } }

        /* Badge pulse */
        .badge-pulse::after {
            content: ''; position: absolute; top: -2px; right: -2px;
            width: 10px; height: 10px; background: #ef4444; border-radius: 50%;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse { 0% { transform: scale(1); opacity: 1; } 100% { transform: scale(2); opacity: 0; } }

        /* Button glow */
        .btn-glow { position: relative; overflow: hidden; }
        .btn-glow::after {
            content: ''; position: absolute; top: 50%; left: 50%;
            width: 0; height: 0; border-radius: 50%; background: rgba(255, 255, 255, 0.1);
            transition: width 0.6s, height 0.6s, top 0.6s, left 0.6s;
        }
        .btn-glow:hover::after { width: 300px; height: 300px; top: -100px; left: -100px; }

        /* Nav link underline */
        .nav-link-anim { position: relative; }
        .nav-link-anim::after {
            content: ''; position: absolute; bottom: -4px; left: 0;
            width: 0; height: 2px;
            background: linear-gradient(90deg, #6366f1, #06b6d4);
            transition: width 0.3s ease; border-radius: 2px;
        }
        .nav-link-anim:hover::after { width: 100%; }

        /* Scroll reveal */
        .reveal-up { opacity: 0; transform: translateY(40px); transition: opacity 0.8s ease, transform 0.8s ease; }
        .reveal-up.revealed { opacity: 1; transform: translateY(0); }

        /* Glowing border */
        .glow-border { position: relative; }
        .glow-border::before {
            content: ''; position: absolute; inset: -1px; border-radius: inherit;
            background: linear-gradient(135deg, #6366f1, #06b6d4, #8b5cf6, #ec4899);
            opacity: 0; transition: opacity 0.5s ease; z-index: -1;
        }
        .glow-border:hover::before { opacity: 1; }

        /* Typing cursor */
        .typing-cursor::after { content: '|'; animation: blink 1s step-end infinite; }
        @keyframes blink { 50% { opacity: 0; } }

        /* Wave */
        @keyframes wave {
            0% { transform: rotate(0deg); } 10% { transform: rotate(14deg); }
            20% { transform: rotate(-8deg); } 30% { transform: rotate(14deg); }
            40% { transform: rotate(-4deg); } 50% { transform: rotate(10deg); }
            60%,100% { transform: rotate(0deg); }
        }
        .animate-wave { animation: wave 2.5s ease-in-out infinite; display: inline-block; transform-origin: 70% 70%; }

        /* Spin slow */
        @keyframes spinSlow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .animate-spin-slow { animation: spinSlow 20s linear infinite; }

        /* Scale in */
        @keyframes scaleIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .animate-scale-in { animation: scaleIn 0.5s ease-out; }

        /* Border glow */
        @keyframes borderGlow {
            0%, 100% { border-color: rgba(99, 102, 241, 0.2); }
            50% { border-color: rgba(6, 182, 212, 0.4); }
        }
        .animate-border-glow { animation: borderGlow 3s ease-in-out infinite; }

        /* Ripple */
        @keyframes ripple { 0% { transform: scale(0.8); opacity: 1; } 100% { transform: scale(2.4); opacity: 0; } }
        .animate-ripple { animation: ripple 1.5s ease-out infinite; }

        /* Mobile backdrop */
        .mobile-backdrop { background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(8px); }

        /* Content z-index */
        body > * { position: relative; z-index: 1; }

        /* Counter */
        @keyframes countUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .count-up { animation: countUp 0.8s ease-out; }

        /* Marquee */
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .animate-marquee { animation: marquee 25s linear infinite; }
        .animate-marquee:hover { animation-play-state: paused; }

        /* === NEW: Tilt glow on cards === */
        .tilt-card {
            transform-style: preserve-3d;
            transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.6s ease;
        }
        .tilt-card:hover {
            box-shadow: 0 20px 60px -10px rgba(99, 102, 241, 0.3), 0 0 40px rgba(6, 182, 212, 0.08);
        }

        /* === NEW: Animated gradient background sections === */
        .gradient-section {
            background: linear-gradient(-45deg, #0f172a, #1e1b4b, #0f172a, #020617);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* === NEW: Line drawing animation === */
        @keyframes drawLine {
            from { width: 0; } to { width: 100%; }
        }
        .animate-draw-line { animation: drawLine 1s ease-out forwards; }

        /* === NEW: Fade stagger children === */
        .stagger-children > *:nth-child(1) { animation-delay: 0s; }
        .stagger-children > *:nth-child(2) { animation-delay: 0.1s; }
        .stagger-children > *:nth-child(3) { animation-delay: 0.2s; }
        .stagger-children > *:nth-child(4) { animation-delay: 0.3s; }
        .stagger-children > *:nth-child(5) { animation-delay: 0.4s; }
        .stagger-children > *:nth-child(6) { animation-delay: 0.5s; }

        /* === NEW: Hover rotate 3D === */
        .hover-rotate-3d {
            transition: transform 0.6s ease;
            transform: perspective(800px) rotateY(0deg);
        }
        .hover-rotate-3d:hover {
            transform: perspective(800px) rotateY(8deg) scale(1.02);
        }

        /* === NEW: Neon text shadow === */
        .neon-text-cyan { text-shadow: 0 0 10px rgba(6,182,212,0.5), 0 0 40px rgba(6,182,212,0.2); }
        .neon-text-purple { text-shadow: 0 0 10px rgba(139,92,246,0.5), 0 0 40px rgba(139,92,246,0.2); }
        .neon-text-pink { text-shadow: 0 0 10px rgba(236,72,153,0.5), 0 0 40px rgba(236,72,153,0.2); }

        /* === NEW: Countdown timer flip === */
        .countdown-box {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(99, 102, 241, 0.2);
            backdrop-filter: blur(10px);
        }

        /* === NEW: Image magnify on product hover === */
        .img-magnify { overflow: hidden; cursor: zoom-in; }
        .img-magnify img { transition: transform 0.8s cubic-bezier(0.23, 1, 0.32, 1); }
        .img-magnify:hover img { transform: scale(1.15); }

        /* ===== COMPREHENSIVE RESPONSIVE ===== */

        /* Large tablets & small desktops */
        @media (max-width: 1024px) {
            .product-grid { grid-template-columns: repeat(3, 1fr) !important; }
        }

        /* Tablets */
        @media (max-width: 768px) {
            .hero-gradient { min-height: auto; padding: 2rem 0; }
            .card-3d:hover { transform: translateY(-4px) scale(1.01); }
            .glass-card:hover { transform: none; }
            .product-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 12px !important; }

            /* Hero text */
            .hero-gradient h1, [class*="text-5xl"], [class*="text-6xl"], [class*="text-7xl"] {
                font-size: 2rem !important; line-height: 1.2 !important;
            }
            [class*="text-4xl"] { font-size: 1.75rem !important; }
            [class*="text-3xl"] { font-size: 1.5rem !important; }

            /* Sections padding */
            section, .py-16, .py-20, .py-24 {
                padding-top: 2.5rem !important; padding-bottom: 2.5rem !important;
            }
            .px-6, .px-8, .px-10, .px-12 { padding-left: 1rem !important; padding-right: 1rem !important; }

            /* Grid layouts */
            .grid-cols-4, .lg\:grid-cols-4 { grid-template-columns: repeat(2, 1fr) !important; }
            .grid-cols-3, .lg\:grid-cols-3, .md\:grid-cols-3 { grid-template-columns: repeat(2, 1fr) !important; }

            /* Footer columns */
            footer .grid { grid-template-columns: 1fr !important; gap: 1.5rem !important; }

            /* Tables */
            table { display: block; overflow-x: auto; white-space: nowrap; }

            /* Hide 3D decorations on mobile */
            .hover-rotate-3d:hover { transform: none; }
            .tilt-card:hover { box-shadow: 0 8px 20px -5px rgba(99,102,241,0.2); }
        }

        /* Small phones */
        @media (max-width: 480px) {
            h1 { font-size: 1.6rem !important; }
            h2 { font-size: 1.35rem !important; }
            h3 { font-size: 1.15rem !important; }
            .product-grid { grid-template-columns: 1fr !important; }
            .grid-cols-2 { grid-template-columns: 1fr !important; }

            /* Buttons */
            .btn-glow, a[class*="px-5"], a[class*="px-6"], a[class*="px-8"] {
                padding-left: 1rem !important; padding-right: 1rem !important;
                font-size: 0.8rem !important;
            }

            /* Cards */
            .glass-card, .card-3d { border-radius: 1rem; }

            /* Login button */
            a[class*="rounded-xl"][class*="text-sm"] { padding: 0.5rem 0.75rem !important; font-size: 0.75rem !important; }

            /* Search input */
            input[name="q"] { font-size: 0.875rem; }
        }

        /* Chatbot responsive */
        @media (max-width: 640px) {
            .fixed.bottom-6.right-6 { bottom: 1rem !important; right: 1rem !important; }
            .fixed.bottom-6.right-6 > div[x-show] {
                width: calc(100vw - 2rem) !important;
                max-height: 80vh !important;
                right: 0 !important;
                bottom: 4.5rem !important;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
    @yield('styles')
</head>

<body class="bg-dark-950 text-gray-100 antialiased aurora-bg">

    <!-- Navbar -->
    <nav class="bg-dark-950/80 backdrop-blur-2xl sticky top-0 z-50 border-b border-white/[0.04]"
        x-data="{ searchOpen: false }">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group flex-shrink-0">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-brand-500 via-neon-cyan to-brand-600 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/25 group-hover:shadow-brand-500/50 transition-all duration-500 group-hover:scale-110">
                        <span class="text-white font-black text-xl">H</span>
                    </div>
                    <span class="text-xl font-bold gradient-text-shimmer hidden sm:block">Huzaifa Store</span>
                </a>

                <!-- Desktop Nav Links -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}"
                        class="text-gray-300 hover:text-white font-medium transition-colors nav-link-anim">
                        Home
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="text-gray-300 hover:text-white font-medium transition-colors nav-link-anim">
                        Products
                    </a>
                    <div class="relative" x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false">
                        <button
                            class="text-gray-300 hover:text-white font-medium transition-colors flex items-center gap-1 nav-link-anim">
                            Categories
                            <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                            class="absolute top-full left-0 pt-3 w-56 z-50">
                            <div class="glass-card rounded-2xl shadow-2xl shadow-black/60 py-2">
                                @php $navCategories = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get(); @endphp
                                @foreach($navCategories as $cat)
                                    <a href="{{ route('categories.show', $cat) }}"
                                        class="block px-4 py-2.5 text-gray-300 hover:bg-brand-600/15 hover:text-brand-300 transition-all rounded-lg mx-2">{{ $cat->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('chatbot.page') }}"
                        class="text-gray-300 hover:text-white font-medium transition-colors nav-link-anim flex items-center gap-1.5">
                        <span class="text-lg">🤖</span> AI Agent
                    </a>
                </div>

                <!-- Search + Icons -->
                <div class="flex items-center gap-3">
                    <!-- Search -->
                    <div class="relative" x-data="{ q: '' }">
                        <form action="{{ route('search') }}" method="GET" class="hidden md:flex items-center">
                            <div class="relative">
                                <input type="text" name="q" placeholder="Search products..."
                                    class="w-64 pl-10 pr-4 py-2 bg-dark-800/60 border border-white/[0.06] rounded-xl text-sm text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500/30 transition-all">
                                <svg class="w-4 h-4 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </form>
                    </div>

                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}"
                        class="relative p-2.5 text-gray-400 hover:text-neon-cyan transition-all duration-300 hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </a>

                    <!-- Auth -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open=!open"
                                class="flex items-center gap-2 p-1">
                                <div
                                    class="w-9 h-9 bg-gradient-to-br from-brand-500 via-neon-purple to-neon-cyan rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg shadow-brand-500/20 hover:shadow-brand-500/40 transition-shadow">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </button>
                            <div x-show="open" @click.outside="open=false" x-transition
                                class="absolute right-0 top-full mt-3 w-52 glass-card rounded-2xl shadow-2xl shadow-black/60 py-2 z-50">
                                <a href="{{ route('profile.index') }}"
                                    class="block px-4 py-2.5 text-gray-300 hover:bg-brand-600/15 hover:text-brand-300 transition-all rounded-lg mx-2">My Profile</a>
                                <a href="{{ route('wishlist.index') }}"
                                    class="block px-4 py-2.5 text-gray-300 hover:bg-brand-600/15 hover:text-brand-300 transition-all rounded-lg mx-2">❤️ Wishlist</a>
                                <a href="{{ route('orders.index') }}"
                                    class="block px-4 py-2.5 text-gray-300 hover:bg-brand-600/15 hover:text-brand-300 transition-all rounded-lg mx-2">My Orders</a>
                                <hr class="my-1.5 border-white/[0.06] mx-2">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2.5 text-accent-500 hover:bg-accent-500/10 transition-all rounded-lg mx-2">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-gradient-to-r from-brand-600 via-brand-500 to-neon-cyan text-white px-5 py-2 rounded-xl text-sm font-semibold hover:shadow-lg hover:shadow-brand-500/30 transition-all duration-300 btn-glow hover:scale-105">Login</a>
                    @endauth

                    <!-- Mobile Menu Toggle -->
                    <button @click="mobileMenu=!mobileMenu" class="md:hidden p-2 text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                x-show="!mobileMenu" d="M4 6h16M4 12h16M4 18h16" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                x-show="mobileMenu" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-4"
            class="md:hidden glass-card border-t border-white/[0.04] px-4 py-4 space-y-2">
            <form action="{{ route('search') }}" method="GET" class="mb-3">
                <input type="text" name="q" placeholder="Search products..."
                    class="w-full px-4 py-2.5 bg-dark-800/60 border border-white/[0.06] rounded-xl text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-500/50">
            </form>
            <a href="{{ route('home') }}" class="block py-2.5 text-gray-300 font-medium hover:text-brand-400 transition-colors">Home</a>
            <a href="{{ route('products.index') }}" class="block py-2.5 text-gray-300 font-medium hover:text-brand-400 transition-colors">Products</a>
            <a href="{{ route('chatbot.page') }}" class="block py-2.5 text-gray-300 font-medium hover:text-brand-400 transition-colors">🤖 AI Agent</a>
            @php $mobileCategories = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get(); @endphp
            <div class="border-t border-white/[0.06] pt-2 mt-2">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Categories</p>
                @foreach($mobileCategories as $cat)
                    <a href="{{ route('categories.show', $cat) }}" class="block py-2 text-gray-400 hover:text-brand-400 transition-colors text-sm pl-2">{{ $cat->name }}</a>
                @endforeach
            </div>
            <a href="{{ route('cart.index') }}" class="block py-2.5 text-gray-300 font-medium hover:text-brand-400 transition-colors">Cart</a>
            @auth
                <a href="{{ route('profile.index') }}" class="block py-2.5 text-gray-300 font-medium hover:text-brand-400 transition-colors">Profile</a>
                <a href="{{ route('orders.index') }}" class="block py-2.5 text-gray-300 font-medium hover:text-brand-400 transition-colors">Orders</a>
                <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t border-white/[0.06]">
                    @csrf
                    <button type="submit" class="block w-full text-left py-2.5 text-accent-500 font-medium hover:text-accent-400 transition-colors">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
            class="fixed top-20 right-4 z-50 bg-emerald-500/90 backdrop-blur-lg text-white px-6 py-3 rounded-xl shadow-2xl shadow-emerald-500/30 flex items-center gap-3 border border-emerald-400/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
            class="fixed top-20 right-4 z-50 bg-accent-500/90 backdrop-blur-lg text-white px-6 py-3 rounded-xl shadow-2xl shadow-accent-500/30 flex items-center gap-3 border border-accent-400/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed top-20 right-4 z-50 bg-accent-500/90 backdrop-blur-lg text-white px-6 py-3 rounded-xl shadow-2xl shadow-accent-500/30 border border-accent-400/20">
            <ul class="text-sm list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Content -->
    <main class="page-enter min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="relative bg-dark-950 border-t border-white/[0.04] text-gray-400 overflow-hidden">
        <!-- Subtle aurora glow in footer -->
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-600/[0.03] rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-neon-cyan/[0.02] rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 py-16 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-brand-500 via-neon-cyan to-brand-600 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/20">
                            <span class="text-white font-black text-xl">H</span>
                        </div>
                        <span class="text-xl font-bold text-white">Huzaifa Store</span>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed">Your premium destination for quality products. We
                        bring you the best in electronics, fashion, and lifestyle.</p>
                    <!-- Social Icons -->
                    <div class="flex gap-3 mt-4">
                        <a href="#" class="w-9 h-9 bg-dark-800 rounded-lg flex items-center justify-center text-gray-500 hover:bg-brand-600 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-dark-800 rounded-lg flex items-center justify-center text-gray-500 hover:bg-brand-600 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-dark-800 rounded-lg flex items-center justify-center text-gray-500 hover:bg-brand-600 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-brand-400 transition-colors">Home</a></li>
                        <li><a href="{{ route('products.index') }}"
                                class="hover:text-brand-400 transition-colors">Products</a></li>
                        <li><a href="{{ route('cart.index') }}"
                                class="hover:text-brand-400 transition-colors">Cart</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Customer Service</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#" class="hover:text-brand-400 transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-brand-400 transition-colors">Shipping Policy</a></li>
                        <li><a href="#" class="hover:text-brand-400 transition-colors">Return Policy</a></li>
                        <li><a href="#" class="hover:text-brand-400 transition-colors">FAQs</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Newsletter</h3>
                    <p class="text-gray-500 text-sm mb-3">Get updates on new products and deals.</p>
                    <div class="flex">
                        <input type="email" placeholder="Your email"
                            class="flex-1 px-4 py-2.5 bg-dark-800/60 border border-white/[0.06] rounded-l-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand-500/50">
                        <button
                            class="px-5 py-2.5 bg-gradient-to-r from-brand-600 to-neon-cyan text-white rounded-r-xl text-sm font-semibold hover:opacity-90 transition-opacity">Subscribe</button>
                    </div>
                </div>
            </div>
            <div class="neon-line mt-12 mb-6"></div>
            <div class="flex flex-col md:flex-row items-center justify-between gap-3 text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} Huzaifa Store. All rights reserved.</p>
                <p class="text-gray-400">Created by <span class="font-semibold text-brand-400">M.HUZAIFA</span> &middot; <a href="mailto:mhuzaifa2503a@aptechorangi.com" class="text-neon-cyan/70 hover:text-neon-cyan transition-colors">mhuzaifa2503a@aptechorangi.com</a></p>
            </div>
        </div>
    </footer>

    <!-- AI Chatbot Widget -->
    @include('partials.chatbot')

    <!-- GSAP Animations -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.registerPlugin(ScrollTrigger);

            // Animate elements on scroll
            gsap.utils.toArray('.animate-on-scroll').forEach(el => {
                gsap.from(el, {
                    scrollTrigger: { trigger: el, start: 'top 85%', toggleActions: 'play none none none' },
                    y: 50, opacity: 0, duration: 0.9, ease: 'power3.out'
                });
            });

            // Stagger product cards — safe animation that never hides permanently
            gsap.utils.toArray('.product-grid').forEach(grid => {
                // Set initial visible state, then animate from offscreen
                gsap.set(grid.children, { opacity: 1, y: 0 });
                gsap.from(grid.children, {
                    scrollTrigger: { trigger: grid, start: 'top 98%', toggleActions: 'play none none none' },
                    y: 40, opacity: 0, duration: 0.6, stagger: 0.08, ease: 'power3.out',
                    clearProps: 'all' // Remove inline styles after animation completes
                });
            });

            // Parallax section headings
            gsap.utils.toArray('.parallax-heading').forEach(el => {
                gsap.to(el, {
                    scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: 1 },
                    y: -30
                });
            });

            // Counter animation
            document.querySelectorAll('.counter-value').forEach(el => {
                const target = parseInt(el.getAttribute('data-target'));
                gsap.from(el, {
                    scrollTrigger: { trigger: el, start: 'top 85%' },
                    innerText: 0,
                    duration: 2,
                    snap: { innerText: 1 },
                    ease: 'power2.out'
                });
            });
        });
    </script>

    @yield('scripts')
</body>

</html>
