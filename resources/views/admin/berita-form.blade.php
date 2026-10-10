@extends('layouts.admin')

@section('title', $article ? 'Ubah Berita' : 'Tambah Berita')

@section('content')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[.18em] text-binary-red">Konten</p>
            <h2 class="mt-1 text-3xl">{{ $article ? 'Ubah Berita' : 'Tambah Berita' }}</h2>
        </div>
        <a href="{{ route('admin.berita.index') }}" class="font-semibold text-neutral-700 underline underline-offset-4">Kembali ke daftar</a>
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
        action="{{ $article ? route('admin.berita.update', $article) : route('admin.berita.store') }}"
        enctype="multipart/form-data"
        class="mt-6 space-y-5 rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm sm:p-7"
    >
        @csrf
        @if ($article)
            @method('PUT')
        @endif

        <div>
            <label for="judul" class="mb-2 block text-sm font-semibold">Judul berita</label>
            <input
                id="judul"
                name="judul"
                type="text"
                value="{{ old('judul', $article?->judul) }}"
                maxlength="255"
                required
                class="w-full rounded-lg border border-neutral-300 px-4 py-3 outline-none focus:border-binary-red focus:ring-2 focus:ring-binary-red/20"
            >
        </div>

        <div>
            <label for="ringkasan" class="mb-2 block text-sm font-semibold">Ringkasan <span class="font-normal text-neutral-500">(opsional)</span></label>
            <textarea
                id="ringkasan"
                name="ringkasan"
                rows="3"
                maxlength="1000"
                class="w-full rounded-lg border border-neutral-300 px-4 py-3 outline-none focus:border-binary-red focus:ring-2 focus:ring-binary-red/20"
            >{{ old('ringkasan', $article?->ringkasan) }}</textarea>
        </div>

        <fieldset>
            <legend class="mb-2 block text-sm font-semibold">Konten berita</legend>
            <div data-rich-toolbar hidden class="flex flex-wrap gap-2 rounded-t-lg border border-neutral-300 bg-neutral-100 p-2">
                <button type="button" data-rich-command="bold" aria-label="Tebalkan" title="Tebalkan" class="size-10 rounded border border-neutral-300 bg-white font-bold hover:border-binary-red">B</button>
                <button type="button" data-rich-command="italic" aria-label="Miringkan" title="Miringkan" class="size-10 rounded border border-neutral-300 bg-white italic hover:border-binary-red">I</button>
                <button type="button" data-rich-command="underline" aria-label="Garis bawah" title="Garis bawah" class="size-10 rounded border border-neutral-300 bg-white underline hover:border-binary-red">U</button>
                <span aria-hidden="true" class="mx-1 w-px bg-neutral-300"></span>
                <button type="button" data-rich-command="insertOrderedList" aria-label="Daftar bernomor" title="Daftar bernomor" class="h-10 rounded border border-neutral-300 bg-white px-3 font-semibold hover:border-binary-red">1. Daftar</button>
                <button type="button" data-rich-command="insertUnorderedList" aria-label="Daftar bullet" title="Daftar bullet" class="h-10 rounded border border-neutral-300 bg-white px-3 font-semibold hover:border-binary-red">&bull; Daftar</button>
            </div>

            <textarea
                id="konten"
                name="konten"
                rows="12"
                required
                maxlength="100000"
                data-rich-input
                class="w-full rounded-b-lg border border-neutral-300 px-4 py-3 outline-none focus:border-binary-red focus:ring-2 focus:ring-binary-red/20"
            >{{ $kontenAman }}</textarea>

            <div
                data-rich-editor
                contenteditable="true"
                role="textbox"
                aria-multiline="true"
                aria-label="Konten berita"
                class="prose prose-neutral hidden min-h-64 max-w-none rounded-b-lg border border-t-0 border-neutral-300 bg-white px-4 py-3 outline-none focus:border-binary-red focus:ring-2 focus:ring-binary-red/20"
            >{!! $kontenAman !!}</div>
            <p class="mt-2 text-xs text-neutral-500">Format yang tersedia: tebal, miring, garis bawah, daftar bernomor, dan daftar bullet.</p>
        </fieldset>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="gambar" class="mb-2 block text-sm font-semibold">Gambar sampul <span class="font-normal text-neutral-500">(opsional)</span></label>
                @if ($article?->gambar)
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($article->gambar) }}" alt="{{ $article->caption_image ?: $article->judul }}" class="mb-3 h-36 w-full rounded-lg object-cover">
                @endif
                <input id="gambar" name="gambar" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-neutral-100 file:px-3 file:py-2">
                <p class="mt-1 text-xs text-neutral-500">JPG, PNG, atau WebP; maksimal 4 MB.</p>
            </div>

            <div>
                <label for="caption_image" class="mb-2 block text-sm font-semibold">Caption gambar <span class="font-normal text-neutral-500">(opsional)</span></label>
                <input
                    id="caption_image"
                    name="caption_image"
                    type="text"
                    value="{{ old('caption_image', $article?->caption_image) }}"
                    maxlength="255"
                    class="w-full rounded-lg border border-neutral-300 px-4 py-3 outline-none focus:border-binary-red focus:ring-2 focus:ring-binary-red/20"
                >
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-neutral-200 pt-5 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.berita.index') }}" class="rounded-lg border border-neutral-300 px-5 py-3 text-center font-semibold text-neutral-700 no-underline hover:bg-neutral-50">Batal</a>
            <button type="submit" class="cursor-pointer rounded-lg border-0 bg-binary-red px-5 py-3 font-display tracking-wide text-white shadow-[0_4px_0_#72070d] hover:bg-binary-red-dark">
                {{ $article ? 'Simpan Perubahan' : 'Simpan Berita' }}
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('[data-rich-editor]')?.closest('form');
            const editor = form?.querySelector('[data-rich-editor]');
            const input = form?.querySelector('[data-rich-input]');
            const toolbar = form?.querySelector('[data-rich-toolbar]');

            if (!form || !editor || !input || !toolbar) {
                return;
            }

            editor.innerHTML = input.value;
            editor.classList.remove('hidden');
            input.classList.add('hidden');
            toolbar.hidden = false;

            toolbar.addEventListener('click', (event) => {
                const button = event.target.closest('[data-rich-command]');

                if (!button) {
                    return;
                }

                editor.focus();
                document.execCommand(button.dataset.richCommand, false);
            });

            editor.addEventListener('input', () => {
                input.value = editor.innerHTML;
            });

            form.addEventListener('submit', () => {
                input.value = editor.innerHTML;
            });
        });
    </script>
@endpush
