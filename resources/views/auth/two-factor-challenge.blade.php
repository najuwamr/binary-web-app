<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="referrer" content="no-referrer">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verifikasi Dua Langkah | UKM-Pers BINARY</title>

    <link rel="icon" href="{{ asset('assets/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center px-4 py-10">
    <main class="w-full max-w-md">
        <a href="{{ route('beranda') }}" class="mb-8 flex items-center justify-center gap-3 text-binary-ink no-underline">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo BINARY" class="size-16 object-contain">
            <span class="font-display leading-none">
                <small class="block text-sm">UKM-Pers</small>
                <strong class="text-3xl">BINARY</strong>
            </span>
        </a>

        <section class="glass-card rounded-3xl p-6 sm:p-9">
            <p class="font-display tracking-[.2em] text-binary-red">VERIFIKASI TAMBAHAN</p>
            <h1 class="mt-2 text-4xl">Verifikasi dua langkah</h1>
            <p class="mt-2 text-sm text-neutral-600">Masukkan kode dari aplikasi autentikator atau gunakan kode pemulihan.</p>

            @if ($errors->any())
                <div role="alert" class="mt-6 rounded-lg border border-binary-red/30 bg-binary-red/5 px-4 py-3 text-sm text-binary-red">
                    Kode verifikasi tidak valid. Silakan coba lagi.
                </div>
            @endif

            <form method="POST" action="{{ route('two-factor.login.store') }}" class="mt-7 space-y-4">
                @csrf

                <div>
                    <label for="code" class="mb-2 block text-sm font-semibold">Kode autentikator</label>
                    <input
                        id="code"
                        name="code"
                        type="text"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        pattern="[0-9]{6}"
                        maxlength="6"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-3 text-center text-xl tracking-[.4em] outline-none transition focus:border-binary-red focus:ring-2 focus:ring-binary-red/20"
                        aria-invalid="{{ $errors->has('code') ? 'true' : 'false' }}"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full cursor-pointer rounded-lg border-0 bg-binary-red px-4 py-3 font-display text-xl tracking-wide text-white shadow-[0_5px_0_#72070d] transition hover:-translate-y-0.5 hover:bg-binary-red-dark hover:shadow-[0_7px_0_#72070d] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-binary-red"
                >
                    Verifikasi kode
                </button>
            </form>

            <div class="my-6 flex items-center gap-3 text-xs uppercase tracking-widest text-neutral-500">
                <span class="h-px flex-1 bg-neutral-300"></span>
                atau
                <span class="h-px flex-1 bg-neutral-300"></span>
            </div>

            <form method="POST" action="{{ route('two-factor.login.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="recovery_code" class="mb-2 block text-sm font-semibold">Kode pemulihan</label>
                    <input
                        id="recovery_code"
                        name="recovery_code"
                        type="text"
                        autocomplete="off"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-3 font-mono outline-none transition focus:border-binary-red focus:ring-2 focus:ring-binary-red/20"
                        aria-invalid="{{ $errors->has('recovery_code') ? 'true' : 'false' }}"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full cursor-pointer rounded-lg border border-neutral-300 bg-white px-4 py-3 font-semibold text-binary-ink transition hover:border-binary-red hover:text-binary-red focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-binary-red"
                >
                    Gunakan kode pemulihan
                </button>
            </form>
        </section>
    </main>
</body>
</html>
