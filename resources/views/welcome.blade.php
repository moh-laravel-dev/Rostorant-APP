<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', config('restaurant.brand.name', 'مطعمنا')) }}</title>
    <meta name="description" content="{{ config('restaurant.hero.description') }}">

    @include('components.theme-mode')
    @vite(['resources/css/colors.css'])
    <script>
        window.tailwind = window.tailwind || {};
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gray: {
                            100: 'rgb(var(--restaurant-cdn-gray-100) / <alpha-value>)',
                            200: 'rgb(var(--restaurant-cdn-gray-200) / <alpha-value>)',
                            300: 'rgb(var(--restaurant-cdn-gray-300) / <alpha-value>)',
                            400: 'rgb(var(--restaurant-cdn-gray-400) / <alpha-value>)',
                            500: 'rgb(var(--restaurant-cdn-gray-500) / <alpha-value>)',
                            600: 'rgb(var(--restaurant-cdn-gray-600) / <alpha-value>)',
                            700: 'rgb(var(--restaurant-cdn-gray-700) / <alpha-value>)',
                        },
                        green: {
                            50: 'rgb(var(--restaurant-cdn-green-50) / <alpha-value>)',
                            400: 'rgb(var(--restaurant-cdn-green-400) / <alpha-value>)',
                            500: 'rgb(var(--restaurant-cdn-green-500) / <alpha-value>)',
                            700: 'rgb(var(--restaurant-cdn-green-700) / <alpha-value>)',
                            900: 'rgb(var(--restaurant-cdn-green-900) / <alpha-value>)',
                        },
                        red: {
                            50: 'rgb(var(--restaurant-cdn-red-50) / <alpha-value>)',
                            100: 'rgb(var(--restaurant-cdn-red-100) / <alpha-value>)',
                            400: 'rgb(var(--restaurant-cdn-red-400) / <alpha-value>)',
                            500: 'rgb(var(--restaurant-cdn-red-500) / <alpha-value>)',
                            600: 'rgb(var(--restaurant-cdn-red-600) / <alpha-value>)',
                        },
                        slate: {
                            800: 'rgb(var(--restaurant-cdn-slate-800) / <alpha-value>)',
                        },
                        white: 'rgb(var(--restaurant-cdn-white) / <alpha-value>)',
                    },
                },
            },
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Amiri:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        :root {
            --primary: var(--restaurant-color-hex-174c3c);
            --primary-dark: var(--restaurant-color-hex-10382d);
            --gold: var(--restaurant-color-hex-d7a85b);
            --cream: var(--restaurant-color-hex-faf7f0);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: 'Cairo', sans-serif;
            background: var(--cream);
            color: var(--restaurant-color-hex-25352d);
        }

        .heading-font {
            font-family: 'Amiri', serif;
        }

        .hero-bg {
            background:
                linear-gradient(270deg, var(--restaurant-color-hex-0d2b20f0), var(--restaurant-color-rgb-13-43-32-0-point-58)),
                url("{{ asset('storage/Home/hero-bg.avif') }}") center / cover no-repeat;
        }

        .gold-text {
            color: var(--gold);
        }

        .gold-button {
            background: var(--gold);
            color: var(--restaurant-color-hex-19382c);
            transition: .25s ease;
        }

        .gold-button:hover {
            background: var(--restaurant-color-hex-e6bb76);
            transform: translateY(-1px);
        }

        .green-button {
            background: var(--primary);
            transition: .25s ease;
        }

        .green-button:hover {
            background: var(--primary-dark);
        }

        .form-input {
            width: 100%;
            padding: 13px 15px;
            background: var(--restaurant-color-hex-fff);
            border: 1px solid var(--restaurant-color-hex-e5e5dc);
            border-radius: 10px;
            outline: none;
            transition: .2s;
            font-family: inherit;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--restaurant-color-rgb-23-76-60-point-1);
        }

        .food-card {
            transition: transform .3s ease;
        }

        .food-card:hover {
            transform: translateY(-5px);
        }

        [x-cloak] {
            display: none !important;
        }


        #main-header.scrolled {
            background: var(--restaurant-color-hex-0d2b20b5);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom-color: var(--restaurant-color-rgb-255-255-255-0-point-1);
        }
    </style>
</head>

<body>

    {{-- Navigation --}}
    <header id="main-header"
        class="fixed right-0 left-0 top-0 z-20 border-b border-white/10 transition-all duration-300">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8">

            <a href="#home" class="flex items-center gap-3 text-white">
                <div
                    class="flex h-11 w-11 items-center justify-center rounded-xl border border-[var(--restaurant-color-hex-d7a85b)] text-2xl">
                    <i class="fa-solid fa-utensils"></i>
                </div>

                <div>
                    <span class="heading-font block text-2xl font-bold">
                        {{ config('app.name', config('restaurant.brand.name', 'مطعمنا')) }}
                    </span>
                    <span class="text-[10px] tracking-widest text-[var(--restaurant-color-hex-d7a85b)]">
                        {{ config('restaurant.brand.tagline') }}
                    </span>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden items-center gap-7 text-sm font-semibold text-white/90 md:flex">
                <a href="{{ route('menu.page') }}" class="transition hover:text-[var(--restaurant-color-hex-d7a85b)]">
                    المينيو
                </a>
                <a href="#home" class="transition hover:text-[var(--restaurant-color-hex-d7a85b)]">
                    {{ config('restaurant.navigation.home') }}
                </a>
                <a href="#about" class="transition hover:text-[var(--restaurant-color-hex-d7a85b)]">
                    {{ config('restaurant.navigation.about') }}
                </a>
                <a href="#specialties" class="transition hover:text-[var(--restaurant-color-hex-d7a85b)]">
                    {{ config('restaurant.navigation.specialties') }}
                </a>
                <a href="#contact" class="transition hover:text-[var(--restaurant-color-hex-d7a85b)]">
                    {{ config('restaurant.navigation.contact') }}
                </a>
                <a href="{{ route('admin.login') }}"
                    class="transition hover:text-[var(--restaurant-color-hex-d7a85b)]">
                    {{ config('restaurant.navigation.login') }}
                </a>
            </div>

            {{-- Mobile Menu Button --}}
            <button id="mobile-menu-button" type="button"
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-white/20 text-white transition hover:border-[var(--restaurant-color-hex-d7a85b)] hover:text-[var(--restaurant-color-hex-d7a85b)] md:hidden"
                aria-label="فتح القائمة" aria-expanded="false">
                <i id="mobile-menu-icon" class="fa-solid fa-bars text-xl"></i>
            </button>
        </nav>

        {{-- Mobile Navigation --}}
        <div id="mobile-menu"
            class="hidden border-t border-white/10 bg-[var(--restaurant-color-hex-0d2b20f0)] px-5 py-4 backdrop-blur-xl md:hidden">
            <div class="mx-auto flex max-w-7xl flex-col gap-1 text-sm font-semibold text-white/90">

                <a href="{{ route('menu.page') }}"
                    class="mobile-menu-link rounded-lg px-4 py-3 transition hover:bg-white/5 hover:text-[var(--restaurant-color-hex-d7a85b)]">
                    المينيو
                </a>
                <a href="#home"
                    class="mobile-menu-link rounded-lg px-4 py-3 transition hover:bg-white/5 hover:text-[var(--restaurant-color-hex-d7a85b)]">
                    {{ config('restaurant.navigation.home') }}
                </a>
                <a href="#about"
                    class="mobile-menu-link rounded-lg px-4 py-3 transition hover:bg-white/5 hover:text-[var(--restaurant-color-hex-d7a85b)]">
                    {{ config('restaurant.navigation.about') }}
                </a>
                <a href="#specialties"
                    class="mobile-menu-link rounded-lg px-4 py-3 transition hover:bg-white/5 hover:text-[var(--restaurant-color-hex-d7a85b)]">
                    {{ config('restaurant.navigation.specialties') }}
                </a>
                <a href="#contact"
                    class="mobile-menu-link rounded-lg px-4 py-3 transition hover:bg-white/5 hover:text-[var(--restaurant-color-hex-d7a85b)]">
                    {{ config('restaurant.navigation.contact') }}
                </a>
                <a href="{{ route('admin.login') }}"
                    class="mobile-menu-link rounded-lg px-4 py-3 transition hover:bg-white/5 hover:text-[var(--restaurant-color-hex-d7a85b)]">
                    {{ config('restaurant.navigation.login') }}
                </a>
            </div>
        </div>
    </header>



    {{-- Hero Section --}}
    <section id="home" class="hero-bg relative flex min-h-[650px] items-center pt-24 text-white sm:min-h-[720px]">

        <div class="mx-auto grid w-full max-w-7xl items-center gap-12 px-5 py-20 sm:px-8 lg:grid-cols-2">

            <div class="max-w-2xl">
                <p class="gold-text mb-6 flex items-center gap-3 text-sm font-bold">
                    <span class="h-px w-10 bg-[var(--restaurant-color-hex-d7a85b)]"></span>
                    {{ config('restaurant.hero.eyebrow') }}
                </p>

                <h1 class="heading-font text-5xl font-bold leading-tight sm:text-6xl lg:text-7xl">
                    {{ config('restaurant.hero.title') }}

                    <span class="gold-text block italic">
                        {{ config('restaurant.hero.subtitle') }}
                    </span>
                </h1>

                <p class="mt-7 max-w-xl text-base leading-9 text-white/80 sm:text-lg">
                    {{ config('restaurant.hero.description') }}
                </p>

                <div class="mt-9 flex flex-wrap gap-4">
                    <a href="#specialties" class="gold-button rounded-lg px-7 py-3.5 text-sm font-bold">
                        {{ config('restaurant.hero.primary_button') }}
                    </a>

                    <a href="#about"
                        class="rounded-lg border border-white/40 px-7 py-3.5 text-sm font-semibold transition hover:bg-white/10">
                        {{ config('restaurant.hero.secondary_button') }}
                    </a>
                </div>

                <div class="mt-12 grid grid-cols-3 gap-4 border-t border-white/20 pt-7">
                    @foreach (config('restaurant.hero.features', []) as $feature)
                        <div>
                            <p class="heading-font gold-text text-2xl font-bold sm:text-3xl">
                                {{ $feature['title'] }}
                            </p>

                            <p class="mt-1 text-xs text-white/70">
                                {{ $feature['description'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Login Card --}}
            {{--
            <div id="login" class="mx-auto w-full max-w-md scroll-mt-24 lg:mr-auto">
                <div class="rounded-2xl border border-white/50 bg-[var(--restaurant-color-hex-faf7f0)] p-6 text-slate-800 shadow-2xl sm:p-8">

                    <div class="mb-7 text-center">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[var(--restaurant-color-hex-174c3c)] text-2xl text-white">
                            <i class="fa-solid fa-user"></i>
                        </div>

                        <p class="mb-2 text-xs font-bold tracking-widest text-[var(--restaurant-color-hex-b28743)]">
                            {{ config('restaurant.login.eyebrow') }}
                        </p>

                        <h2 class="heading-font text-3xl font-bold text-[var(--restaurant-color-hex-174c3c)]">
                            {{ config('restaurant.login.title') }}
                        </h2>

                        <p class="mt-2 text-sm text-gray-500">
                            {{ config('restaurant.login.description') }}
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-red-100 bg-red-50 p-3 text-sm text-red-600">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="/admin/login" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">
                                {{ config('restaurant.login.email_label') }}
                            </label>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                placeholder="{{ config('restaurant.login.email_placeholder') }}"
                                autocomplete="username"
                                required
                                autofocus
                                dir="ltr"
                                class="form-input text-left">
                        </div>

                        <div x-data="{ showPassword: false }">
                            <div class="mb-2 flex items-center justify-between">
                                <label for="password" class="text-sm font-semibold text-gray-700">
                                    {{ config('restaurant.login.password_label') }}
                                </label>

                                <a href="/admin/forgot-password"
                                    class="text-xs font-semibold text-[var(--restaurant-color-hex-174c3c)] hover:underline">
                                    {{ config('restaurant.login.forgot_password') }}
                                </a>
                            </div>

                            <div class="relative">
                                <input
                                    id="password"
                                    name="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    placeholder="{{ config('restaurant.login.password_placeholder') }}"
                                    autocomplete="current-password"
                                    required
                                    dir="ltr"
                                    class="form-input pl-16 text-left">

                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-[var(--restaurant-color-hex-174c3c)]"
                                    x-text="showPassword
                                        ? @js(config('restaurant.login.hide_password'))
                                        : @js(config('restaurant.login.show_password'))">
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input
                                id="remember"
                                name="remember"
                                type="checkbox"
                                value="1"
                                class="h-4 w-4 rounded border-gray-300 accent-[var(--restaurant-color-hex-174c3c)]">

                            <label for="remember" class="mr-2 text-sm text-gray-600">
                                {{ config('restaurant.login.remember_me') }}
                            </label>
                        </div>

                        <button type="submit"
                            class="green-button flex w-full items-center justify-center gap-2 rounded-lg px-4 py-3.5 font-bold text-white shadow-lg shadow-green-900/10">
                            {{ config('restaurant.login.submit') }}
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                    </form>

                    <div class="my-6 flex items-center gap-4">
                        <div class="h-px flex-1 bg-gray-200"></div>

                        <span class="text-[10px] font-semibold text-gray-400">
                            {{ config('restaurant.login.authorized_note') }}
                        </span>

                        <div class="h-px flex-1 bg-gray-200"></div>
                    </div>

                    <p class="text-center text-xs leading-6 text-gray-500">
                        {{ config('restaurant.login.support_question') }}

                        <a href="mailto:{{ config('communication.email') }}"
                            class="font-semibold text-[var(--restaurant-color-hex-174c3c)] hover:underline">
                            {{ config('restaurant.login.support_link') }}
                        </a>
                    </p>
                </div>
            </div>
            --}}
        </div>
    </section>

    {{-- About Section --}}
    <section id="about" class="px-5 py-20 sm:px-8 lg:py-28">
        <div class="mx-auto grid max-w-7xl items-center gap-12 lg:grid-cols-2">

            <div class="grid grid-cols-2 gap-4">
                <img src="{{ asset('storage/Home/about-1.avif') }}"
                    alt="{{ config('restaurant.about.image_alt.about_1') }}"
                    class="food-card h-64 w-full rounded-2xl object-cover sm:h-80" loading="lazy">

                <img src="{{ asset('storage/Home/about-2.avif') }}"
                    alt="{{ config('restaurant.about.image_alt.about_2') }}"
                    class="food-card mt-8 h-64 w-full rounded-2xl object-cover sm:h-80" loading="lazy">
            </div>

            <div>
                <p class="mb-4 text-xs font-bold tracking-widest text-[var(--restaurant-color-hex-b28743)]">
                    {{ config('restaurant.about.eyebrow') }}
                </p>

                <h2
                    class="heading-font text-4xl font-bold leading-tight text-[var(--restaurant-color-hex-174c3c)] sm:text-5xl">
                    {{ config('restaurant.about.title') }}

                    <span class="block italic">
                        {{ config('restaurant.about.subtitle') }}
                    </span>
                </h2>

                @foreach (config('restaurant.about.paragraphs', []) as $paragraph)
                    <p class="mt-6 leading-9 text-gray-600">
                        {{ $paragraph }}
                    </p>
                @endforeach

                <a href="#specialties"
                    class="mt-7 inline-flex items-center gap-3 font-bold text-[var(--restaurant-color-hex-174c3c)] hover:text-[var(--restaurant-color-hex-b28743)]">
                    {{ config('restaurant.about.button') }}
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Specialties Section --}}
    <section id="specialties" class="bg-white px-5 py-20 sm:px-8 lg:py-24">
        <div class="mx-auto max-w-7xl">

            <div class="mb-12 text-center">
                <p class="mb-3 text-xs font-bold tracking-widest text-[var(--restaurant-color-hex-b28743)]">
                    {{ config('restaurant.specialties.eyebrow') }}
                </p>

                <h2 class="heading-font text-4xl font-bold text-[var(--restaurant-color-hex-174c3c)] sm:text-5xl">
                    {{ config('restaurant.specialties.title') }}
                </h2>

                <p class="mx-auto mt-4 max-w-xl leading-8 text-gray-500">
                    {{ config('restaurant.specialties.description') }}
                </p>
            </div>

            @php
                $specialtyImages = ['specialty-1.avif', 'specialty-2.avif', 'specialty-3.avif'];
            @endphp

            <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                @foreach (config('restaurant.specialties.items', []) as $index => $item)
                    <article
                        class="food-card overflow-hidden rounded-2xl border border-gray-100 bg-[var(--restaurant-color-hex-faf7f0)] {{ $loop->last && $loop->count === 3 ? 'sm:col-span-2 lg:col-span-1' : '' }}">

                        <img src="{{ asset('storage/Home/' . ($specialtyImages[$index] ?? $specialtyImages[0])) }}"
                            alt="{{ $item['image_alt'] }}" class="h-56 w-full object-cover" loading="lazy">

                        <div class="p-6">
                            <span class="text-xs font-bold text-[var(--restaurant-color-hex-b28743)]">
                                {{ $item['number'] }} / {{ $item['category'] }}
                            </span>

                            <h3 class="heading-font mt-3 text-2xl font-bold text-[var(--restaurant-color-hex-174c3c)]">
                                {{ $item['title'] }}
                            </h3>

                            <p class="mt-3 text-sm leading-8 text-gray-600">
                                {{ $item['description'] }}
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Contact Section --}}
    <section id="contact"
        class="bg-[var(--restaurant-color-hex-174c3c)] px-5 py-16 text-center text-white sm:px-8 lg:py-20">

        <p class="mb-4 text-xs font-bold tracking-widest text-[var(--restaurant-color-hex-d7a85b)]">
            {{ config('restaurant.contact.eyebrow') }}
        </p>

        <h2 class="heading-font text-4xl font-bold sm:text-5xl">
            {{ config('restaurant.contact.title') }}
        </h2>

        <p class="mx-auto mt-5 max-w-2xl leading-8 text-white/75">
            {{ config('restaurant.contact.description') }}
        </p>

        <div class="mx-auto mt-12 grid max-w-5xl gap-5 sm:grid-cols-2 lg:grid-cols-4">

            {{-- WhatsApp --}}
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('communication.whatsapp', '')) }}"
                target="_blank" rel="noopener noreferrer"
                class="rounded-2xl border border-white/10 bg-white/5 p-6 transition hover:-translate-y-1 hover:bg-white/10">

                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-green-500/20 text-3xl">
                    <i class="fa-brands fa-whatsapp text-green-400"></i>
                </div>

                <h3 class="mb-2 text-lg font-bold">
                    <i class="fa-brands fa-whatsapp ml-2 text-green-400"></i>
                    {{ config('restaurant.contact.whatsapp.title') }}
                </h3>

                <p class="text-sm text-white/70" dir="ltr">
                    {{ config('communication.whatsapp') }}
                </p>

                <span class="mt-3 inline-block text-sm font-semibold text-[var(--restaurant-color-hex-d7a85b)]">
                    {{ config('restaurant.contact.whatsapp.button') }}
                    <i class="fa-solid fa-arrow-left mr-1"></i>
                </span>
            </a>

            {{-- Phone --}}
            <a href="tel:{{ config('communication.phone') }}"
                class="rounded-2xl border border-white/10 bg-white/5 p-6 transition hover:-translate-y-1 hover:bg-white/10">

                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[var(--restaurant-color-hex-5bc0d7)]/20 text-3xl">
                    <i class="fa-solid fa-phone text-[var(--restaurant-color-hex-5bc0d7)]"></i>
                </div>

                <h3 class="mb-2 text-lg font-bold">
                    <i class="fa-solid fa-phone ml-2 text-[var(--restaurant-color-hex-5bc0d7)]"></i>
                    {{ config('restaurant.contact.phone.title') }}
                </h3>

                <p class="text-sm text-white/70" dir="ltr">
                    {{ config('communication.phone') }}
                </p>

                <span class="mt-3 inline-block text-sm font-semibold text-[var(--restaurant-color-hex-5bc0d7)]">
                    {{ config('restaurant.contact.phone.button') }}
                    <i class="fa-solid fa-arrow-left mr-1"></i>
                </span>
            </a>

            {{-- Email --}}
            <a href="mailto:{{ config('communication.email') }}"
                class="rounded-2xl border border-white/10 bg-white/5 p-6 transition hover:-translate-y-1 hover:bg-white/10">

                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[var(--restaurant-color-hex-d7a85b)]/20 text-3xl">
                    <i class="fa-solid fa-envelope text-[var(--restaurant-color-hex-d7a85b)]"></i>
                </div>

                <h3 class="mb-2 text-lg font-bold">
                    <i class="fa-solid fa-envelope ml-2 text-[var(--restaurant-color-hex-d7a85b)]"></i>
                    {{ config('restaurant.contact.email.title') }}
                </h3>

                <p class="break-all text-sm text-white/70" dir="ltr">
                    {{ config('communication.email') }}
                </p>

                <span class="mt-3 inline-block text-sm font-semibold text-[var(--restaurant-color-hex-d7a85b)]">
                    {{ config('restaurant.contact.email.button') }}
                    <i class="fa-solid fa-arrow-left mr-1"></i>
                </span>
            </a>

            {{-- Location --}}
            <a href="{{ config('communication.map_url', config('communication.location')) }}" target="_blank"
                rel="noopener noreferrer"
                class="rounded-2xl border border-white/10 bg-white/5 p-6 transition hover:-translate-y-1 hover:bg-white/10">

                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-500/20 text-3xl">
                    <i class="fa-solid fa-location-dot text-red-400"></i>
                </div>

                <h3 class="mb-2 text-lg font-bold">
                    <i class="fa-solid fa-map-location-dot ml-2 text-red-400"></i>
                    {{ config('restaurant.contact.location.title') }}
                </h3>

                <p class="text-sm text-white/70">
                    {{ config('restaurant.contact.location.description') }}
                </p>

                <span class="mt-3 inline-block text-sm font-semibold text-[var(--restaurant-color-hex-d7a85b)]">
                    {{ config('restaurant.contact.location.button') }}
                    <i class="fa-solid fa-arrow-left mr-1"></i>
                </span>
            </a>

        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-[var(--restaurant-color-hex-10382d)] px-5 py-6 text-center text-sm text-white/60">
        <p>
            © {{ date('Y') }}
            {{ config('app.name', config('restaurant.brand.name', 'مطعمنا')) }}.
            {{ config('restaurant.brand.copyright') }}
        </p>
    </footer>

</body>
<script>
    const header = document.getElementById('main-header');

    window.addEventListener('scroll', () => {
        header.classList.toggle('scrolled', window.scrollY > 50);
    }, {
        passive: true
    });

    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuIcon = document.getElementById('mobile-menu-icon');

    mobileMenuButton.addEventListener('click', () => {
        const isClosed = mobileMenu.classList.toggle('hidden');

        mobileMenuButton.setAttribute('aria-expanded', String(!isClosed));
        mobileMenuIcon.classList.toggle('fa-bars', isClosed);
        mobileMenuIcon.classList.toggle('fa-xmark', !isClosed);
    });

    document.querySelectorAll('.mobile-menu-link').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
            mobileMenuIcon.classList.remove('fa-xmark');
            mobileMenuIcon.classList.add('fa-bars');
        });
    });
</script>

</html>
