@extends('layouts.admin')

@section('title', 'Manajemen Berita')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
        <p class="text-sm font-semibold uppercase tracking-[.18em] text-binary-red">Konten</p>
            <h2 class="mt-1 text-3xl">Manajemen Berita</h2>
            <p class="mt-2 text-sm text-neutral-600">{{ $isArchived ? 'Daftar berita yang diarsipkan.' : 'Daftar berita aktif.' }}</p>
        </div>
        <a href="{{ route('admin.berita.create') }}" class="inline-flex justify-center rounded-lg bg-binary-red px-4 py-3 font-display tracking-wide text-white no-underline shadow-[0_4px_0_#72070d] transition hover:-translate-y-0.5 hover:bg-binary-red-dark">
            + Tambah Berita
        </a>
    </div>

    <nav aria-label="Filter status berita" class="mt-6 flex gap-2">
        <a href="{{ route('admin.berita.index') }}" @class([
            'rounded-full px-4 py-2 text-sm font-semibold no-underline',
            'bg-binary-red text-white' => ! $isArchived,
            'bg-white text-neutral-700 ring-1 ring-neutral-300' => $isArchived,
        ])>Aktif</a>
        <a href="{{ route('admin.berita.index', ['archived' => 1]) }}" @class([
            'rounded-full px-4 py-2 text-sm font-semibold no-underline',
            'bg-binary-red text-white' => $isArchived,
            'bg-white text-neutral-700 ring-1 ring-neutral-300' => ! $isArchived,
        ])>Arsip</a>
    </nav>

    <div class="mt-6 overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-150 text-left text-sm">
                <thead class="bg-neutral-100 text-neutral-700">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-semibold">Judul</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Slug</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Diperbarui</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse ($articles as $article)
                        <tr>
                            <td class="px-4 py-3 font-semibold">
                                {{ $article->judul }}
                                @if ($article->is_archived)
                                    <span class="ml-2 rounded-full bg-neutral-200 px-2 py-1 text-xs font-medium text-neutral-700">Diarsipkan</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-neutral-600">{{ $article->slug }}</td>
                            <td class="px-4 py-3 text-neutral-600">{{ $article->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.berita.show', $article) }}" class="font-semibold text-binary-red underline underline-offset-2">Detail</a>
                                    <a href="{{ route('admin.berita.edit', $article) }}" class="font-semibold text-neutral-700 underline underline-offset-2">Ubah</a>
                                    @unless ($article->is_archived)
                                        <form method="POST" action="{{ route('admin.berita.archive', $article) }}" data-confirm="Arsipkan berita ini?">
                                            @csrf
                                            <button type="submit" class="cursor-pointer border-0 bg-transparent p-0 font-semibold text-neutral-700 underline underline-offset-2">Arsipkan</button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-neutral-600">
                                {{ $isArchived ? 'Belum ada berita yang diarsipkan.' : 'Belum ada berita.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $articles->links() }}</div>
@endsection