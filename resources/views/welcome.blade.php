<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Servis AC Profesional - Layanan Cepat & Terpercaya</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .hero-pattern {
            background-color: #ffffff;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23667eea' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="font-sans antialiased">

    <!-- Navigation -->
    <nav class="glass-effect shadow-lg fixed w-full z-50 top-0 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center">
                    <div class="w-12 h-12 gradient-bg rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <span class="font-bold text-2xl text-gray-900">CoolPro</span>
                        <p class="text-xs text-gray-500">AC Service Expert</p>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    <a href="#" class="font-medium text-gray-700 hover:text-indigo-600 transition">Dashboard</a>
                    <a href="#layanan" class="font-medium text-gray-700 hover:text-indigo-600 transition">Layanan</a>
                    <a href="#keunggulan" class="font-medium text-gray-700 hover:text-indigo-600 transition">Keunggulan</a>
                    <a href="#testimoni" class="font-medium text-gray-700 hover:text-indigo-600 transition">Testimoni</a>
                    @guest
                    <a href="{{ route('login') }}" class="gradient-bg text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition duration-300">Log in</a>
                    @else
                    <a href="{{ url('/dashboard') }}" class="gradient-bg text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                        Dashboard
                    </a>
                    @endguest
                </div>

                <div class="md:hidden">
                    <button class="text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20">

        <!-- Hero Section -->
        <section class="hero-pattern relative overflow-hidden">
            <div class="max-w-7xl mx-auto py-20 md:py-32 px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <div class="inline-block mb-4">
                            <span class="bg-indigo-100 text-indigo-700 text-sm font-semibold px-4 py-2 rounded-full">
                                ⭐ Dipercaya 10,000+ Pelanggan
                            </span>
                        </div>
                        <h1 class="text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight">
                            Solusi AC <span class="gradient-text">Profesional</span> untuk Kenyamanan Anda
                        </h1>
                        <p class="mt-6 text-xl text-gray-600 leading-relaxed">
                            Layanan service AC berkualitas tinggi dengan teknisi bersertifikat. Cuci AC, isi freon, hingga perbaikan menyeluruh - semua dalam satu platform.
                        </p>
                        <div class="mt-10 flex flex-col sm:flex-row gap-4">
                            @auth
                            <a href="{{ url('/dashboard') }}" class="gradient-bg text-white font-bold py-4 px-8 rounded-xl text-lg shadow-xl hover:shadow-2xl transition duration-300 text-center">
                                🚀 Pesan Sekarang
                            </a>
                            @else
                            <a href="{{ route('login') }}" class="gradient-bg text-white font-bold py-4 px-8 rounded-xl text-lg shadow-xl hover:shadow-2xl transition duration-300 text-center">
                                🚀 Pesan Sekarang
                            </a>
                            @endauth
                            <a href="#testimoni" class="bg-white text-gray-800 font-bold py-4 px-8 rounded-xl text-lg shadow-lg hover:shadow-xl transition duration-300 border-2 border-gray-200 text-center">
                                📞 Hubungi Kami
                            </a>
                        </div>
                        <div class="mt-10 flex items-center gap-8">
                            <div>
                                <p class="text-3xl font-bold text-gray-900">4.9/5.0</p>
                                <p class="text-sm text-gray-600">Rating Pelanggan</p>
                            </div>
                            <div class="h-12 w-px bg-gray-300"></div>
                            <div>
                                <p class="text-3xl font-bold text-gray-900">24/7</p>
                                <p class="text-sm text-gray-600">Layanan Darurat</p>
                            </div>
                            <div class="h-12 w-px bg-gray-300"></div>
                            <div>
                                <p class="text-3xl font-bold text-gray-900">2 Jam</p>
                                <p class="text-sm text-gray-600">Respon Cepat</p>
                            </div>
                        </div>
                    </div>
                    <div class="relative hidden md:block">
                        <div class="float-animation">
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 400'%3E%3Crect fill='%23667eea' width='400' height='400' rx='40'/%3E%3Cpath fill='%23ffffff' d='M200 100c-55.2 0-100 44.8-100 100s44.8 100 100 100 100-44.8 100-100-44.8-100-100-100zm0 180c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z'/%3E%3Ccircle fill='%23ffffff' cx='200' cy='200' r='30'/%3E%3Cpath fill='%23ffffff' opacity='0.5' d='M200 120v20m0 120v20m-80-80h20m120 0h20'/%3E%3C/svg%3E" alt="AC Unit" class="rounded-3xl shadow-2xl" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="layanan" class="bg-gradient-to-br from-gray-50 to-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wide">Layanan Kami</span>
                    <h2 class="text-4xl font-bold text-gray-900 mt-2">Paket Lengkap untuk AC Anda</h2>
                    <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">Berbagai pilihan layanan profesional dengan harga terjangkau dan garansi kepuasan</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-2xl shadow-lg card-hover border border-gray-100">
                        <div class="w-16 h-16 gradient-bg rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Cuci AC Reguler</h3>
                        <p class="text-gray-600 mb-6">Pembersihan menyeluruh filter, evaporator, dan komponen AC untuk performa optimal.</p>
                        <div class="mb-6">
                            <span class="text-3xl font-bold text-gray-900">Rp 75K</span>
                            <span class="text-gray-500">/unit</span>
                        </div>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Cuci filter & evaporator
                            </li>
                            <li class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Cek tekanan freon
                            </li>
                            <li class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Garansi 30 hari
                            </li>
                        </ul>
                        <a href="{{ auth()->check() ? url('/dashboard') : route('register') }}" class="block w-full text-center gradient-bg text-white font-semibold py-3 rounded-xl hover:shadow-lg transition">
                            Pilih Paket
                        </a>
                    </div>

                    <div class="bg-gradient-to-br from-indigo-600 to-purple-600 p-8 rounded-2xl shadow-2xl card-hover relative overflow-hidden transform scale-105">
                        <div class="absolute top-0 right-0 bg-yellow-400 text-gray-900 text-xs font-bold px-4 py-2 rounded-bl-xl">
                            TERPOPULER
                        </div>
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">Service Premium</h3>
                        <p class="text-indigo-100 mb-6">Paket lengkap dengan tambah freon dan pengecekan komprehensif semua komponen.</p>
                        <div class="mb-6">
                            <span class="text-3xl font-bold text-white">Rp 150K</span>
                            <span class="text-indigo-200">/unit</span>
                        </div>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center text-white">
                                <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Semua fitur paket reguler
                            </li>
                            <li class="flex items-center text-white">
                                <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Isi freon R32/R410
                            </li>
                            <li class="flex items-center text-white">
                                <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Garansi 90 hari
                            </li>
                        </ul>
                        <a href="{{ auth()->check() ? url('/dashboard') : route('register') }}" class="block w-full text-center bg-white text-indigo-600 font-semibold py-3 rounded-xl hover:shadow-lg transition">
                            Pilih Paket
                        </a>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg card-hover border border-gray-100">
                        <div class="w-16 h-16 gradient-bg rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Perbaikan AC</h3>
                        <p class="text-gray-600 mb-6">Diagnosa dan perbaikan kerusakan AC dengan spare part original dan bergaransi.</p>
                        <div class="mb-6">
                            <span class="text-3xl font-bold text-gray-900">Mulai Rp 100K</span>
                        </div>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Diagnosa gratis
                            </li>
                            <li class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Spare part original
                            </li>
                            <li class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Garansi 6 bulan
                            </li>
                        </ul>
                        <a href="{{ auth()->check() ? url('/dashboard') : route('register') }}" class="block w-full text-center gradient-bg text-white font-semibold py-3 rounded-xl hover:shadow-lg transition">
                            Konsultasi Gratis
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section id="keunggulan" class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wide">Keunggulan Kami</span>
                    <h2 class="text-4xl font-bold text-gray-900 mt-2">Mengapa Memilih CoolPro?</h2>
                    <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">Komitmen kami adalah memberikan layanan terbaik dengan standar profesional</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="w-20 h-20 gradient-bg rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Teknisi Bersertifikat</h3>
                        <p class="text-gray-600">Semua teknisi kami memiliki sertifikasi resmi dan pengalaman minimal 5 tahun di bidang AC.</p>
                    </div>

                    <div class="text-center">
                        <div class="w-20 h-20 gradient-bg rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Layanan 24/7</h3>
                        <p class="text-gray-600">Tersedia kapan saja untuk kebutuhan darurat Anda, termasuk hari libur dan weekend.</p>
                    </div>

                    <div class="text-center">
                        <div class="w-20 h-20 gradient-bg rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Harga Transparan</h3>
                        <p class="text-gray-600">Tidak ada biaya tersembunyi. Harga sudah termasuk jasa, material, dan transportasi.</p>
                    </div>

                    <div class="text-center">
                        <div class="w-20 h-20 gradient-bg rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Garansi Terjamin</h3>
                        <p class="text-gray-600">Garansi hingga 6 bulan untuk setiap perbaikan dan penggantian spare part.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonial Section -->
        <section id="testimoni" class="bg-gradient-to-br from-indigo-50 to-purple-50 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wide">Testimoni</span>
                    <h2 class="text-4xl font-bold text-gray-900 mt-2">Apa Kata Pelanggan Kami?</h2>
                    <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">Ribuan pelanggan puas dengan layanan kami</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-2xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-4 italic">"Pelayanan sangat cepat dan profesional. AC di rumah saya yang sudah 3 tahun tidak dingin sekarang dingin kembali seperti baru. Teknisinya ramah dan menjelaskan dengan detail."</p>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-lg">
                                BR
                            </div>
                            <div class="ml-3">
                                <p class="font-semibold text-gray-900">Budi Raharjo</p>
                                <p class="text-sm text-gray-500">Jakarta Selatan</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-4 italic">"Harga sangat transparan dan tidak ada biaya tambahan. Booking online juga mudah banget. Teknisi datang tepat waktu dan pekerjaannya rapi. Sangat recommended!"</p>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-lg">
                                SW
                            </div>
                            <div class="ml-3">
                                <p class="font-semibold text-gray-900">Siti Wahyuni</p>
                                <p class="text-sm text-gray-500">Tangerang</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-4 italic">"Sudah 2x pakai jasa mereka untuk maintenance AC kantor. Hasilnya selalu memuaskan. Spare part original dan garansinya jelas. Tim yang profesional!"</p>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center text-pink-600 font-bold text-lg">
                                AP
                            </div>
                            <div class="ml-3">
                                <p class="font-semibold text-gray-900">Ahmad Pratama</p>
                                <p class="text-sm text-gray-500">Bekasi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="gradient-bg rounded-3xl shadow-2xl overflow-hidden">
                    <div class="px-6 py-16 sm:px-12 sm:py-20 lg:flex lg:items-center lg:justify-between">
                        <div class="lg:w-0 lg:flex-1">
                            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                                Siap Membuat AC Anda Dingin Kembali?
                            </h2>
                            <p class="mt-4 max-w-3xl text-lg text-indigo-100">
                                Dapatkan konsultasi gratis dan penawaran terbaik untuk kebutuhan AC Anda. Tim kami siap membantu 24/7.
                            </p>
                        </div>
                        <div class="mt-8 lg:mt-0 lg:ml-8">
                            <a href="{{ auth()->check() ? url('/dashboard') : route('login') }}" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-lg font-bold rounded-xl text-white hover:bg-white hover:text-indigo-600 transition duration-300">
                                Mulai Sekarang
                                <svg class="ml-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="bg-gray-50 py-20">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wide">FAQ</span>
                    <h2 class="text-4xl font-bold text-gray-900 mt-2">Pertanyaan yang Sering Diajukan</h2>
                </div>

                <div class="space-y-4">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <details class="group">
                            <summary class="flex justify-between items-center cursor-pointer p-6 font-semibold text-gray-900 text-lg hover:bg-gray-50">
                                <span>Berapa lama waktu pengerjaan service AC?</span>
                                <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>
                            <div class="px-6 pb-6 text-gray-600">
                                Untuk cuci AC reguler membutuhkan waktu sekitar 45-60 menit per unit. Service premium dengan isi freon membutuhkan waktu 1-2 jam. Perbaikan tergantung jenis kerusakan, biasanya 2-4 jam.
                            </div>
                        </details>
                    </div>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <details class="group">
                            <summary class="flex justify-between items-center cursor-pointer p-6 font-semibold text-gray-900 text-lg hover:bg-gray-50">
                                <span>Apakah ada garansi untuk service yang dilakukan?</span>
                                <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>
                            <div class="px-6 pb-6 text-gray-600">
                                Ya, kami memberikan garansi untuk setiap layanan. Cuci AC reguler 30 hari, service premium 90 hari, dan perbaikan dengan penggantian spare part 6 bulan.
                            </div>
                        </details>
                    </div>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <details class="group">
                            <summary class="flex justify-between items-center cursor-pointer p-6 font-semibold text-gray-900 text-lg hover:bg-gray-50">
                                <span>Area mana saja yang dilayani?</span>
                                <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>
                            <div class="px-6 pb-6 text-gray-600">
                                Kami melayani seluruh area Jabodetabek (Jakarta, Bogor, Depok, Tangerang, Bekasi). Untuk area luar Jabodetabek, silakan hubungi customer service kami.
                            </div>
                        </details>
                    </div>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <details class="group">
                            <summary class="flex justify-between items-center cursor-pointer p-6 font-semibold text-gray-900 text-lg hover:bg-gray-50">
                                <span>Bagaimana cara pembayarannya?</span>
                                <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>
                            <div class="px-6 pb-6 text-gray-600">
                                Kami menerima pembayaran tunai, transfer bank, e-wallet (GoPay, OVO, Dana), dan kartu kredit/debit. Pembayaran dilakukan setelah pekerjaan selesai dan Anda puas dengan hasilnya.
                            </div>
                        </details>
                    </div>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <details class="group">
                            <summary class="flex justify-between items-center cursor-pointer p-6 font-semibold text-gray-900 text-lg hover:bg-gray-50">
                                <span>Apakah bisa service di hari libur atau weekend?</span>
                                <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>
                            <div class="px-6 pb-6 text-gray-600">
                                Tentu saja! Kami buka 7 hari seminggu termasuk hari libur nasional. Layanan darurat 24 jam juga tersedia untuk kebutuhan mendesak Anda.
                            </div>
                        </details>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                        </div>
                        <span class="font-bold text-2xl ml-3 text-white">CoolPro</span>
                    </div>
                    <p class="text-gray-400 mb-4 max-w-md">
                        Penyedia layanan service AC profesional terpercaya dengan teknisi bersertifikat dan pengalaman lebih dari 10 tahun.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-indigo-600 rounded-lg flex items-center justify-center transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-indigo-600 rounded-lg flex items-center justify-center transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-indigo-600 rounded-lg flex items-center justify-center transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-indigo-600 rounded-lg flex items-center justify-center transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">Layanan</h3>
                    <ul class="space-y-2">
                        <li><a href="#layanan" class="hover:text-white transition">Cuci AC</a></li>
                        <li><a href="#layanan" class="hover:text-white transition">Isi Freon</a></li>
                        <li><a href="#layanan" class="hover:text-white transition">Perbaikan AC</a></li>
                        <li><a href="#layanan" class="hover:text-white transition">Maintenance Rutin</a></li>
                        <li><a href="#layanan" class="hover:text-white transition">Instalasi AC</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">Perusahaan</h3>
                    <ul class="space-y-2">
                        <li><a href="#keunggulan" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#testimoni" class="hover:text-white transition">Testimoni</a></li>
                        <li><a href="#" class="hover:text-white transition">Karir</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="text-sm">
                        <p>&copy; {{ date('Y') }} CoolPro - PT. Servis AC Sejahtera. All rights reserved.</p>
                    </div>
                    <div class="text-sm md:text-right space-x-4">
                        <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                        <span>•</span>
                        <a href="#" class="hover:text-white transition">Syarat & Ketentuan</a>
                        <span>•</span>
                        <a href="#" class="hover:text-white transition">Sitemap</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>