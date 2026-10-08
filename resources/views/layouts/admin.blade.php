<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="noindex, nofollow">

  <title>@yield('title', 'Dashboard') | Admin UKM-Pers BINARY</title>

  <link rel="icon" href="{{ asset('assets/logo.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>
<body class="bg-none bg-white before:hidden">

@php
    $nama    = auth()->user()?->name ?? 'User';
    $tanggal = now()->locale('id')->translatedFormat('l, d F Y');

    // Menu sidebar. Route yang belum dibuat otomatis jadi "#".
    $menu = [
        ['Dashboard', 'admin.dashboard',  'admin.dashboard'],
        ['Manajemen Berita', 'admin.berita.index',  'admin.berita.*'],
        ['Manajemen Buletin','admin.buletin.index', 'admin.buletin.*'],
        ['Arsip',            'admin.arsip.index',   'admin.arsip.*'],
    ];
    $url = fn ($nama) => \Illuminate\Support\Facades\Route::has($nama) ? route($nama) : '#';
@endphp

<div class="flex min-h-screen">

  {{-- Overlay (mobile) --}}
  <div data-sidebar-overlay class="fixed inset-0 z-30 hidden bg-black/40 lg:hidden"></div>

  {{-- ===== SIDEBAR ===== --}}
  <aside data-sidebar
         class="fixed inset-y-0 left-0 z-40 flex w-60 -translate-x-full flex-col rounded-r-3xl bg-white px-5 py-5 transition-transform duration-300 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
         style="box-shadow: 4px 0 12px rgba(0,0,0,.18)">

    <a href="{{ $url('admin.dashboard') }}" class="flex items-center gap-2 text-inherit no-underline pb-4">
        <img src="{{ asset('assets/logo.png') }}" alt="Logo Binary" class="size-12 object-contain">
        <div class="font-display leading-[.9]">
            <small class="block text-[14px]">UKM-Pers</small>
            <strong class="text-2xl">BINARY</strong>
        </div>
    </a>

    <div class="mt-2 border-t border-neutral-200"></div>

    <a href="{{ $url('admin.berita.create') }}"
        class="mt-8 block rounded-lg bg-binary-red px-3 py-2.5 text-center font-display text-lg tracking-wide text-white no-underline shadow-md transition hover:bg-binary-red-dark">
        Tambah Berita +
    </a>

    <nav class="mt-6 flex flex-col gap-1">
      @foreach ($menu as [$label, $route, $pola])
        @php $aktif = request()->routeIs($pola); @endphp
        <a href="{{ $url($route) }}"
           @if ($aktif) aria-current="page" @endif
           class="rounded-md border-l-4 px-3 py-2 font-display text-[17px] tracking-wide no-underline transition
                  {{ $aktif ? 'border-binary-red bg-binary-red/10 text-binary-red' : 'border-transparent text-black hover:border-binary-red/40 hover:text-binary-red' }}">
          {{ $label }}
        </a>
      @endforeach
    </nav>
  </aside>

  {{-- ===== AREA KANAN ===== --}}
  <div class="flex min-w-0 flex-1 flex-col">

    <header class="sticky top-0 z-20 flex items-center gap-3 border-b-2 border-neutral-400 bg-white px-4 py-4 sm:px-8">
      <button type="button" data-sidebar-toggle aria-label="Buka menu" class="border-0 bg-transparent lg:hidden">
        <span class="m-1 block h-0.5 w-6 bg-neutral-900"></span>
        <span class="m-1 block h-0.5 w-6 bg-neutral-900"></span>
        <span class="m-1 block h-0.5 w-6 bg-neutral-900"></span>
      </button>

      <div class="min-w-0">
        <h1 class="truncate font-sans text-xl font-bold tracking-normal sm:text-2xl">Selamat Datang {{ $nama }}!</h1>
        <p class="text-base text-neutral-700 sm:text-lg">{{ $tanggal }}</p>
      </div>

      <form method="POST" action="{{ $url('logout') }}" data-confirm="Yakin ingin keluar?" class="ml-auto">
        @csrf
        <button type="submit"
                class="cursor-pointer rounded-md border-0 bg-binary-red px-4 py-2 font-display text-lg tracking-wide text-white transition hover:bg-binary-red-dark">
          Logout
        </button>
      </form>
    </header>

    <main class="flex-1 p-4 sm:p-8">
      @yield('content')
    </main>
  </div>
</div>

{{-- Notifikasi SweetAlert --}}
@include('partials.alerts')

@stack('scripts')
</body>
</html>
