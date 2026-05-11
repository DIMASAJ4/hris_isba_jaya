<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRIS ISBA JAYA — Sistem Manajemen Keanggotaan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .hero-gradient {
            background: radial-gradient(circle at top right, #2E86C1, #1E3A5F);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-white text-gray-900 overflow-x-hidden">

    {{-- NAVBAR --}}
    <nav class="fixed top-0 w-full z-50 glass-nav">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-navy rounded-xl flex items-center justify-center shadow-lg shadow-navy/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="font-extrabold text-navy text-lg leading-none">ISBA JAYA</h1>
                    <p class="text-[10px] text-gray-500 font-bold tracking-widest uppercase mt-0.5">HRIS Management</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/admin/dashboard') }}" class="text-sm font-bold text-navy hover:text-primary transition-colors">DASHBOARD</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-navy text-white px-8 py-3 rounded-full text-sm font-bold shadow-xl shadow-navy/20 hover:scale-105 transition-all">MASUK SISTEM</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section class="relative min-h-screen hero-gradient flex items-center pt-20 overflow-hidden">
        {{-- Decorative Blobs --}}
        <div class="absolute top-1/4 -left-20 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 -right-20 w-96 h-96 bg-amber/5 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center relative z-10">
            <div class="space-y-8 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 rounded-full border border-white/20 backdrop-blur-sm">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-white text-xs font-bold tracking-widest uppercase">Versi 1.0 Ready</span>
                </div>
                
                <h2 class="text-5xl lg:text-7xl font-black text-white leading-[1.1]">
                    Kelola Organisasi <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-yellow-500">Lebih Cerdas.</span>
                </h2>
                
                <p class="text-blue-100 text-lg leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Sistem Manajemen Sumber Daya Mahasiswa (HRIS) terintegrasi untuk ISBA JAYA. Dari data anggota hingga laporan otomatis, semua dalam satu genggaman.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('login') }}" class="px-10 py-5 bg-white text-navy font-black rounded-2xl shadow-2xl hover:bg-gray-100 transition-all text-center">
                        Mulai Sekarang
                    </a>
                    <a href="#features" class="px-10 py-5 bg-transparent border-2 border-white/30 text-white font-bold rounded-2xl hover:bg-white/10 transition-all text-center">
                        Lihat Fitur
                    </a>
                </div>

                <div class="pt-10 flex items-center justify-center lg:justify-start gap-8 opacity-60">
                    <div class="text-center">
                        <p class="text-white text-2xl font-black">500+</p>
                        <p class="text-blue-200 text-xs uppercase font-bold">Anggota</p>
                    </div>
                    <div class="w-px h-10 bg-white/20"></div>
                    <div class="text-center">
                        <p class="text-white text-2xl font-black">12</p>
                        <p class="text-blue-200 text-xs uppercase font-bold">Departemen</p>
                    </div>
                    <div class="w-px h-10 bg-white/20"></div>
                    <div class="text-center">
                        <p class="text-white text-2xl font-black">100%</p>
                        <p class="text-blue-200 text-xs uppercase font-bold">Digital</p>
                    </div>
                </div>
            </div>

            <div class="hidden lg:block relative">
                {{-- Mockup Image --}}
                <div class="animate-float relative z-20">
                     <div class="bg-white/10 p-4 rounded-[40px] border border-white/20 backdrop-blur-md shadow-2xl">
                         <div class="bg-white rounded-[32px] overflow-hidden shadow-inner aspect-[4/3] flex items-center justify-center text-navy font-black text-4xl">
                             <div class="p-8 text-center">
                                <div class="w-20 h-20 bg-navy/10 rounded-3xl flex items-center justify-center mx-auto mb-6 text-navy">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </div>
                                <p class="text-navy text-2xl">Dashboard Admin</p>
                                <p class="text-gray-400 text-sm font-medium mt-2">Pratinjau Antarmuka Modern</p>
                             </div>
                         </div>
                     </div>
                </div>
                {{-- Back Accents --}}
                <div class="absolute -top-10 -right-10 w-full h-full bg-amber/20 rounded-[40px] rotate-6 z-10"></div>
            </div>
        </div>
    </section>

    {{-- FEATURES SECTION --}}
    <section id="features" class="py-32 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-20 space-y-4">
                <h3 class="text-primary text-sm font-black uppercase tracking-widest">Fitur Unggulan</h3>
                <h2 class="text-4xl font-black text-navy leading-tight">Solusi Terbaik Untuk Manajemen Mahasiswa</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="bg-white p-10 rounded-[32px] shadow-xl shadow-gray-200/50 border border-gray-100 hover:-translate-y-2 transition-all group">
                    <div class="w-16 h-16 bg-blue-50 text-primary rounded-2xl flex items-center justify-center mb-8 group-hover:bg-primary group-hover:text-white transition-all">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h4 class="text-xl font-bold text-navy mb-4">Database Anggota</h4>
                    <p class="text-gray-500 leading-relaxed">Kelola ribuan data anggota dengan mudah, lengkap dengan filter departemen dan angkatan.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="bg-white p-10 rounded-[32px] shadow-xl shadow-gray-200/50 border border-gray-100 hover:-translate-y-2 transition-all group">
                    <div class="w-16 h-16 bg-amber-50 text-amber rounded-2xl flex items-center justify-center mb-8 group-hover:bg-amber group-hover:text-white transition-all">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="text-xl font-bold text-navy mb-4">Verifikasi Otomatis</h4>
                    <p class="text-gray-500 leading-relaxed">Sistem verifikasi cepat untuk setiap calon anggota baru yang mendaftar ke organisasi.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="bg-white p-10 rounded-[32px] shadow-xl shadow-gray-200/50 border border-gray-100 hover:-translate-y-2 transition-all group">
                    <div class="w-16 h-16 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-green-500 group-hover:text-white transition-all">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h4 class="text-xl font-bold text-navy mb-4">Laporan Real-time</h4>
                    <p class="text-gray-500 leading-relaxed">Generate laporan PDF dan Excel secara instan untuk kebutuhan birokrasi dan dokumentasi.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-navy py-12 text-center border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <p class="text-blue-200 text-sm font-medium">HRIS ISBA JAYA &copy; {{ date('Y') }} — Dikembangkan untuk Kemajuan Organisasi.</p>
        </div>
    </footer>

</body>
</html>
