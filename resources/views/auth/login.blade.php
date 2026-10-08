<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="referrer" content="no-referrer">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login Admin | UKM-Pers BINARY</title>

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
            <p class="font-display tracking-[.2em] text-binary-red">AREA TERBATAS</p>
            <h1 class="mt-2 text-4xl">Masuk Admin</h1>
            <p class="mt-2 text-sm text-neutral-600">Gunakan akun admin yang telah terdaftar.</p>

            @if ($errors->any())
                <div role="alert" class="mt-6 rounded-lg border border-binary-red/30 bg-binary-red/5 px-4 py-3 text-sm text-binary-red">
                    Email atau kata sandi tidak valid. Silakan periksa kembali.
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="mt-7 space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        inputmode="email"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-3 outline-none transition focus:border-binary-red focus:ring-2 focus:ring-binary-red/20"
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                    >
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold">Kata sandi</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-3 outline-none transition focus:border-binary-red focus:ring-2 focus:ring-binary-red/20"
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                    >
                </div>

                <label for="remember" class="flex items-center gap-2 text-sm text-neutral-700">
                    <input id="remember" name="remember" type="checkbox" value="1" class="size-4 accent-binary-red">
                    Ingat saya di perangkat ini
                </label>

                <button
                    type="submit"
                    class="w-full cursor-pointer rounded-lg border-0 bg-binary-red px-4 py-3 font-display text-xl tracking-wide text-white shadow-[0_5px_0_#72070d] transition hover:-translate-y-0.5 hover:bg-binary-red-dark hover:shadow-[0_7px_0_#72070d] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-binary-red"
                >
                    Masuk
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-neutral-500">
                Akses tercatat dan dilindungi pembatasan percobaan login.
            </p>
        </section>

        <p class="mt-6 text-center text-xs text-neutral-600">
            <a href="{{ route('beranda') }}" class="underline decoration-neutral-400 underline-offset-4 hover:text-binary-red">Kembali ke beranda</a>
        </p>
    </main>
</body>
</html>
