@extends('layouts.admin')

@section('title', 'Manajemen Buletin')

@section('content')
    <div>
        <p class="text-sm font-semibold uppercase tracking-[.18em] text-binary-red">Publikasi</p>
        <h2 class="mt-1 text-3xl">Manajemen Buletin</h2>
        <p class="mt-2 text-sm text-neutral-600">Daftar edisi buletin terbaru.</p>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-125 text-left text-sm">
                <thead class="bg-neutral-100 text-neutral-700">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-semibold">Judul</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Edisi</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Tahun</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse ($bulletins as $bulletin)
                        <tr>
                            <td class="px-4 py-3 font-semibold">{{ $bulletin->judul }}</td>
                            <td class="px-4 py-3 text-neutral-600">{{ $bulletin->edisi }}</td>
                            <td class="px-4 py-3 text-neutral-600">{{ $bulletin->tahun }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-10 text-center text-neutral-600">Belum ada buletin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $bulletins->links() }}</div>
@endsection