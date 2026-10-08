<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'UKM-Pers BINARY - Nafas Intelektual dan Nurani Mahasiswa, Fakultas Ilmu Komputer Universitas Jember.')">

    <title>@yield('title', 'UKM-Pers BINARY')</title>

    <link rel="icon" href="{{ asset('assets/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="flex min-h-screen flex-col">

    <noscript>
        <div class="bg-binary-red p-2 text-center text-sm text-white">
        Aktifkan JavaScript agar semua fitur situs ini berjalan dengan baik.
        </div>
    </noscript>

    {{-- Efek cahaya mengikuti kursor --}}
    <div data-cursor-glow
        class="pointer-events-none fixed -z-10 size-65 -translate-x-1/2 -translate-y-1/2 rounded-full"
        style="background: radial-gradient(circle, rgba(191,14,24,.16), transparent 65%)"></div>

    {{-- ===== NAVBAR ===== --}}
    <header class="sticky top-0 z-20 flex flex-wrap items-center gap-4 bg-white/80 px-[5%] py-3 backdrop-blur-md lg:h-22 lg:flex-nowrap lg:gap-8 lg:px-[7.5%] lg:py-0"
            style="box-shadow: 0 5px 0 rgba(0,0,0,.75), 0 15px 35px rgba(0,0,0,.12)">

        <a href="{{ route('beranda') }}" class="flex items-center gap-2.5 text-inherit no-underline">
        <img src="{{ asset('assets/logo.png') }}" alt="Logo Binary"
            class="size-16 object-contain drop-shadow-[0_10px_15px_rgba(191,14,24,.25)] lg:size-20">
        <div class="font-display leading-[.9]">
            <small class="block text-sm lg:text-[17px]">UKM-Pers</small>
            <strong class="text-2xl lg:text-[31px]">BINARY</strong>
        </div>
        </a>

        <button type="button" data-hamburger aria-label="Buka menu" class="ml-auto border-0 bg-transparent lg:hidden">
        <span class="m-1.25 block h-1 w-7.5 rounded bg-neutral-900"></span>
        <span class="m-1.25 block h-1 w-7.5 rounded bg-neutral-900"></span>
        <span class="m-1.25 block h-1 w-7.5 rounded bg-neutral-900"></span>
        </button>

        <nav data-nav-menu class="order-4 hidden w-full flex-col items-stretch gap-2 text-center lg:order-0 lg:ml-auto lg:flex lg:w-auto lg:flex-row lg:items-center lg:gap-4">
        @php
            // Aktifkan route lain di sini setelah halamannya dibuat.
            $menu = [
            ['Beranda', route('beranda'), request()->routeIs('beranda')],
            ['Profil',  '#',              request()->routeIs('profil')],
            ['Berita',  '#',              request()->routeIs('berita.*')],
            ['Produk',  '#',              request()->routeIs('produk')],
            ['Galeri',  '#',              request()->routeIs('galeri')],
            ['Kontak',  '#',              request()->routeIs('kontak')],
            ];
        @endphp

        @foreach ($menu as [$label, $url, $aktif])
            <a href="{{ $url }}" class="nav-link {{ $aktif ? 'is-active' : '' }}"
            @if ($aktif) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
        </nav>

        <label class="order-3 flex w-full items-center gap-2 rounded-full bg-neutral-200 px-3.5 py-2 shadow-inner lg:order-0 lg:w-auto">
        <input type="search" name="q" placeholder="Cari di sini ..."
                class="w-full border-0 bg-transparent font-display text-base outline-none lg:w-37.5">
        <span class="text-2xl text-neutral-500" aria-hidden="true">⌕</span>
        </label>
    </header>

    {{-- ===== KONTEN HALAMAN ===== --}}
    <main class="mx-auto mt-7 w-[97%] max-w-285 flex-1 lg:w-[95%]">
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="mt-8 bg-binary-gray px-4 py-4 text-center text-sm text-neutral-700">
        <p class="font-display text-xl tracking-wide text-binary-ink">UKM-Pers BINARY</p>
        <p class="mt-1">Nafas Intelektual dan Nurani Mahasiswa</p>
        <p class="mt-2 text-xs">&copy;2026 UKM-Pers Binary, Fakultas Ilmu Komputer Universitas Jember.</p>
    </footer>

    {{-- Notifikasi SweetAlert (flash session + error validasi) --}}
    @include('partials.alerts')

    @stack('scripts')
</body>
</html>
