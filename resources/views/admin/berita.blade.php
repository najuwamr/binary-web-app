@extends('layouts.admin')

@section('title', 'Manajemen Berita')

@section('content')
    <div>
        <p class="text-sm font-semibold uppercase tracking-[.18em] text-binary-red">Konten</p>
        <h2 class="mt-1 text-3xl">Manajemen Berita</h2>
        <p class="mt-2 text-sm text-neutral-600">Daftar berita terbaru.</p>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-150 text-left text-sm">
                <thead class="bg-neutral-100 text-neutral-700">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-semibold">Judul</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Slug</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Diperbarui</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse ($articles as $article)
                        <tr>
                            <td class="px-4 py-3 font-semibold">{{ $article->judul }}</td>
                            <td class="px-4 py-3 text-neutral-600">{{ $article->slug }}</td>
                            <td class="px-4 py-3 text-neutral-600">{{ $article->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-10 text-center text-neutral-600">Belum ada berita.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $articles->links() }}</div>
@endsection