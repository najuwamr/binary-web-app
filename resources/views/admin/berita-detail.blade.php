@extends('layouts.admin')

@section('title', 'Detail Berita')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <p class="text-sm font-semibold uppercase tracking-[.18em] text-binary-red">Detail Berita</p>
                @if ($article->is_archived)
                    <span class="rounded-full bg-neutral-200 px-3 py-1 text-xs font-semibold text-neutral-700">Diarsipkan</span>
                @endif
            </div>
            <h2 class="mt-2 text-3xl">{{ $article->judul }}</h2>
            <p class="mt-2 text-sm text-neutral-500">Diperbarui {{ $article->updated_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.berita.index', ['archived' => $article->is_archived ? 1 : null]) }}" class="rounded-lg border border-neutral-300 bg-white px-4 py-2 font-semibold text-neutral-700 no-underline hover:border-binary-red">Kembali</a>
            <a href="{{ route('admin.berita.edit', $article) }}" class="rounded-lg bg-binary-red px-4 py-2 font-semibold text-white no-underline hover:bg-binary-red-dark">Ubah</a>
            @unless ($article->is_archived)
                <form method="POST" action="{{ route('admin.berita.archive', $article) }}" data-confirm="Arsipkan berita ini?">
                    @csrf
                    <button type="submit" class="cursor-pointer rounded-lg border border-neutral-300 bg-white px-4 py-2 font-semibold text-neutral-700 hover:border-binary-red hover:text-binary-red">Arsipkan</button>
                </form>
            @endunless
        </div>
    </div>

    <article class="mt-6 rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm sm:p-8">
        @if ($article->gambar)
            <figure class="mb-7">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($article->gambar) }}" alt="{{ $article->caption_image ?: $article->judul }}" class="max-h-120 w-full rounded-xl object-cover">
                @if ($article->caption_image)
                    <figcaption class="mt-2 text-sm text-neutral-500">{{ $article->caption_image }}</figcaption>
                @endif
            </figure>
        @endif

        @if ($article->ringkasan)
            <p class="mb-6 border-l-4 border-binary-red pl-4 text-lg font-semibold text-neutral-700">{{ $article->ringkasan }}</p>
        @endif

        <div class="rich-content max-w-none text-base leading-8 text-neutral-800">{!! $kontenAman !!}</div>
    </article>
@endsection
