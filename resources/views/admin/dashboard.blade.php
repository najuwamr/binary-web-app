@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[.18em] text-binary-red">Ringkasan</p>
            <h2 class="mt-1 text-3xl">Dashboard Admin</h2>
        </div>
        <p class="text-sm text-neutral-600">Kelola konten UKM-Pers BINARY.</p>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <a href="{{ route('admin.berita.index') }}" class="glass-card rounded-2xl p-6 text-inherit no-underline transition hover:-translate-y-1">
            <p class="text-sm font-semibold text-neutral-600">Jumlah berita</p>
            <p class="mt-2 font-display text-5xl text-binary-red">{{ number_format($jumlahBerita) }}</p>
            <span class="mt-3 inline-block text-sm font-semibold">Buka manajemen berita &rarr;</span>
        </a>

        <a href="{{ route('admin.buletin.index') }}" class="glass-card rounded-2xl p-6 text-inherit no-underline transition hover:-translate-y-1">
            <p class="text-sm font-semibold text-neutral-600">Jumlah buletin</p>
            <p class="mt-2 font-display text-5xl text-binary-red">{{ number_format($jumlahBuletin) }}</p>
            <span class="mt-3 inline-block text-sm font-semibold">Buka manajemen buletin &rarr;</span>
        </a>
    </div>

    <a href="{{ route('admin.arsip.index') }}" class="mt-5 inline-flex rounded-lg border border-neutral-300 bg-white px-4 py-3 font-semibold text-inherit no-underline transition hover:border-binary-red hover:text-binary-red">
        Buka arsip
    </a>
@endsection