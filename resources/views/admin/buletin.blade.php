@extends('layouts.admin')

@section('title', 'Manajemen Buletin')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[.18em] text-binary-red">Publikasi</p>
            <h2 class="mt-1 text-3xl">Manajemen Buletin</h2>
            <p class="mt-2 text-sm text-neutral-600">Koleksi buletin dan majalah BINARY.</p>
        </div>
        <a href="{{ route('admin.buletin.create') }}" class="inline-flex justify-center rounded-lg bg-binary-red px-4 py-3 font-display tracking-wide text-white no-underline shadow-[0_4px_0_#72070d] transition hover:-translate-y-0.5 hover:bg-binary-red-dark">
            + Tambah Buletin
        </a>
    </div>

    @if ($bulletins->isEmpty())
        <div class="glass-card mt-6 rounded-2xl p-10 text-center">
            <p class="font-display text-2xl">Belum ada buletin</p>
            <p class="mt-2 text-sm text-neutral-600">Tambahkan edisi pertama untuk memulai koleksi.</p>
        </div>
    @else
        <div class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($bulletins as $bulletin)
                <article class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <a href="{{ route('admin.buletin.show', $bulletin) }}" class="block aspect-[4/3] overflow-hidden bg-neutral-100">
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($bulletin->sampul) }}"
                            alt="Sampul {{ $bulletin->judul }}"
                            loading="lazy"
                            class="h-full w-full object-cover transition duration-300 hover:scale-105"
                        >
                    </a>
                    <div class="flex flex-col gap-3 p-5">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="line-clamp-2 text-xl">{{ $bulletin->judul }}</h3>
                            <span class="shrink-0 rounded-full bg-binary-red/10 px-3 py-1 text-sm font-semibold text-binary-red">{{ $bulletin->tahun }}</span>
                        </div>
                        <p class="line-clamp-3 text-sm leading-6 text-neutral-600">{{ $bulletin->deskripsi }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Edisi {{ $bulletin->edisi }}</p>
                        <div class="mt-1 flex items-center gap-3 border-t border-neutral-200 pt-4">
                            <a href="{{ route('admin.buletin.show', $bulletin) }}" class="font-semibold text-binary-red underline underline-offset-2">Buka buletin</a>
                            <a href="{{ route('admin.buletin.edit', $bulletin) }}" class="font-semibold text-neutral-700 underline underline-offset-2">Ubah</a>
                            <form method="POST" action="{{ route('admin.buletin.destroy', $bulletin) }}" data-confirm="Hapus buletin ini beserta file sampul dan PDF secara permanen?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cursor-pointer border-0 bg-transparent p-0 font-semibold text-binary-red underline underline-offset-2">Hapus</button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    <div class="mt-6">{{ $bulletins->links() }}</div>
@endsection