@extends('layouts.admin')

@section('title', $bulletin ? 'Ubah Buletin' : 'Tambah Buletin')

@section('content')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[.18em] text-binary-red">Publikasi</p>
            <h2 class="mt-1 text-3xl">{{ $bulletin ? 'Ubah Buletin' : 'Tambah Buletin' }}</h2>
        </div>
        <a href="{{ route('admin.buletin.index') }}" class="font-semibold text-neutral-700 underline underline-offset-4">Kembali ke daftar</a>
    </div>

    @if ($errors->any())
        <div role="alert" class="mt-5 rounded-lg border border-binary-red/30 bg-binary-red/5 p-4 text-sm text-binary-red">
            <p class="font-semibold">Periksa kembali isian berikut:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ $bulletin ? route('admin.buletin.update', $bulletin) : route('admin.buletin.store') }}"
        enctype="multipart/form-data"
        class="mt-6 space-y-5 rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm sm:p-7"
    >
        @csrf
        @if ($bulletin)
            @method('PUT')
        @endif

        <div>
            <label for="judul" class="mb-2 block text-sm font-semibold">Judul buletin</label>
            <input id="judul" name="judul" type="text" value="{{ old('judul', $bulletin?->judul) }}" maxlength="255" required class="w-full rounded-lg border border-neutral-300 px-4 py-3 outline-none focus:border-binary-red focus:ring-2 focus:ring-binary-red/20">
        </div>

        <div>
            <label for="deskripsi" class="mb-2 block text-sm font-semibold">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="5000" required class="w-full rounded-lg border border-neutral-300 px-4 py-3 outline-none focus:border-binary-red focus:ring-2 focus:ring-binary-red/20">{{ old('deskripsi', $bulletin?->deskripsi) }}</textarea>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="edisi" class="mb-2 block text-sm font-semibold">Edisi</label>
                <input id="edisi" name="edisi" type="text" value="{{ old('edisi', $bulletin?->edisi) }}" maxlength="50" required placeholder="Contoh: 01" class="w-full rounded-lg border border-neutral-300 px-4 py-3 outline-none focus:border-binary-red focus:ring-2 focus:ring-binary-red/20">
            </div>
            <div>
                <label for="tahun" class="mb-2 block text-sm font-semibold">Tahun</label>
                <input id="tahun" name="tahun" type="number" value="{{ old('tahun', $bulletin?->tahun ?? now()->year) }}" min="1900" max="2100" required class="w-full rounded-lg border border-neutral-300 px-4 py-3 outline-none focus:border-binary-red focus:ring-2 focus:ring-binary-red/20">
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="sampul" class="mb-2 block text-sm font-semibold">Gambar sampul {{ $bulletin ? '(opsional jika tidak diganti)' : '' }}</label>
                @if ($bulletin?->sampul)
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($bulletin->sampul) }}" alt="Sampul {{ $bulletin->judul }}" class="mb-3 h-40 w-full rounded-lg object-cover">
                @endif
                <input id="sampul" name="sampul" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" @required(! $bulletin) class="block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-neutral-100 file:px-3 file:py-2">
                <p class="mt-1 text-xs text-neutral-500">JPG, PNG, atau WebP; maksimal 4 MB.</p>
            </div>

            <div>
                <label for="file_pdf" class="mb-2 block text-sm font-semibold">File PDF {{ $bulletin ? '(opsional jika tidak diganti)' : '' }}</label>
                @if ($bulletin?->file_pdf)
                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($bulletin->file_pdf) }}" target="_blank" rel="noopener noreferrer" class="mb-3 inline-flex text-sm font-semibold text-binary-red underline underline-offset-2">Lihat PDF saat ini</a>
                @endif
                <input id="file_pdf" name="file_pdf" type="file" accept=".pdf,application/pdf" @required(! $bulletin) class="block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-neutral-100 file:px-3 file:py-2">
                <p class="mt-1 text-xs text-neutral-500">PDF; maksimal 50 MB.</p>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-neutral-200 pt-5 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.buletin.index') }}" class="rounded-lg border border-neutral-300 px-5 py-3 text-center font-semibold text-neutral-700 no-underline hover:bg-neutral-50">Batal</a>
            <button type="submit" class="cursor-pointer rounded-lg border-0 bg-binary-red px-5 py-3 font-display tracking-wide text-white shadow-[0_4px_0_#72070d] hover:bg-binary-red-dark">{{ $bulletin ? 'Simpan Perubahan' : 'Simpan Buletin' }}</button>
        </div>
    </form>
@endsection
