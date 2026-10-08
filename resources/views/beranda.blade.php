@extends('layouts.guest')

@section('title', 'Beranda | UKM-Pers BINARY')

@section('content')
    {{-- ===== HERO ===== --}}
    <div class="glass-card reveal grid gap-5 px-4 pt-3 pb-6 lg:grid-cols-[2fr_1.1fr]">
        <div class="shimmer h-57.5 rounded-xl sm:h-90"></div>
        <aside>
        <h3 class="mb-2 text-[28px]">Berita Terkini</h3>
        <div class="shimmer h-57.5 rounded-xl sm:h-82.5"></div>
        </aside>
    </div>

    {{-- ===== IDENTITAS ===== --}}
    @include('partials.binary-banner')

    {{-- ===== SUBMIT KARYA ===== --}}
    <div class="reveal pt-5 text-center">
        <a href="#" class="btn-3d inline-block no-underline">Submit Karya</a>
    </div>

    {{-- ===== ARTIKEL + BULETIN ===== --}}
    <section class="reveal mt-4 grid gap-3.5 lg:grid-cols-[1fr_320px]">

        <div class="glass-card p-2.5">
        <h3 class="mb-2.5 text-[28px]">Artikel Terupdate</h3>

        <div class="grid gap-6.5 lg:grid-cols-2">
            {{-- Kolom kiri --}}
            <div class="lg:border-r-[3px] lg:border-neutral-900 lg:pr-5.5">
            @foreach (range(1, 3) as $i)
                @include('partials.article-mini')
            @endforeach
            </div>
            {{-- Kolom kanan --}}
            <div>
            @foreach (range(4, 6) as $i)
                @include('partials.article-mini')
            @endforeach
            </div>
        </div>
        </div>

        <div class="glass-card p-2.5">
        <h3 class="mb-2.5 text-[28px]">Buletin Terbaru</h3>
        <div class="grid grid-cols-2 gap-5">
            @foreach ([2026, 2026, 2025, 2024] as $tahun)
            <a href="#"
                class="grid h-23.5 place-items-center rounded-xl bg-binary-gray font-display text-[28px] no-underline transition duration-200 hover:-translate-y-2 hover:bg-binary-red hover:text-white">
                {{ $tahun }}
            </a>
            @endforeach
        </div>
        </div>
    </section>

@endsection
