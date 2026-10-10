@extends('layouts.admin')

@section('title', $bulletin->judul)

@section('content')
    <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
        <div class="flex gap-4">
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($bulletin->sampul) }}" alt="Sampul {{ $bulletin->judul }}" class="h-36 w-28 rounded-lg object-cover shadow-md sm:h-44 sm:w-36">
            <div class="flex flex-col gap-2">
                <p class="text-sm font-semibold uppercase tracking-[.18em] text-binary-red">Edisi {{ $bulletin->edisi }} · {{ $bulletin->tahun }}</p>
                <h2 class="text-3xl">{{ $bulletin->judul }}</h2>
                <p class="max-w-2xl text-sm leading-6 text-neutral-600">{{ $bulletin->deskripsi }}</p>
            </div>
        </div>
        <div class="flex shrink-0 flex-wrap gap-2">
            <a href="{{ route('admin.buletin.index') }}" class="rounded-lg border border-neutral-300 bg-white px-4 py-2 font-semibold text-neutral-700 no-underline hover:border-binary-red">Kembali</a>
            <a href="{{ route('admin.buletin.edit', $bulletin) }}" class="rounded-lg bg-binary-red px-4 py-2 font-semibold text-white no-underline hover:bg-binary-red-dark">Ubah</a>
            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($bulletin->file_pdf) }}" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-neutral-300 bg-white px-4 py-2 font-semibold text-neutral-700 no-underline hover:border-binary-red">Buka PDF</a>
        </div>
    </div>

    <section class="mt-7 rounded-2xl border border-neutral-200 bg-neutral-900 p-4 shadow-xl sm:p-6" aria-label="Pembaca buletin">
        <div class="flex flex-wrap items-center justify-between gap-3 text-white">
            <p class="font-display text-xl">Baca edisi ini</p>
            <p data-flip-page-label class="text-sm text-neutral-300">Memuat halaman...</p>
        </div>

        <div class="mt-5 flex min-h-100 items-center justify-center overflow-hidden rounded-xl bg-neutral-800 p-3 sm:p-6">
            <div
                data-pdf-flipbook
                data-pdf-url="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($bulletin->file_pdf) }}"
                data-cover-url="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($bulletin->sampul) }}"
                class="w-full"
                aria-label="Flipbook {{ $bulletin->judul }}"
            ></div>
            <p data-flip-error hidden role="alert" class="max-w-xl text-center text-sm text-white"></p>
        </div>

        <div class="mt-5 flex items-center justify-center gap-3">
            <button type="button" data-flip-prev disabled class="rounded-lg border border-white/30 px-4 py-2 font-semibold text-white transition hover:bg-white/10 disabled:cursor-not-allowed disabled:opacity-40">← Sebelumnya</button>
            <button type="button" data-flip-next disabled class="rounded-lg bg-binary-red px-4 py-2 font-semibold text-white transition hover:bg-binary-red-dark disabled:cursor-not-allowed disabled:opacity-40">Berikutnya →</button>
        </div>
    </section>

    <p class="mt-3 text-center text-xs text-neutral-500">Gunakan tombol, klik sisi halaman, atau geser halaman untuk membalik.</p>
@endsection
