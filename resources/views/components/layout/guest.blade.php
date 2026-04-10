<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Poliklinik' }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet" />
    
    {{-- Font Awesome (Versi 6.5.1 yang stabil) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        .brand-serif {
            font-family: 'Instrument Serif', serif;
        }

        /* Perbaikan gradien agar lebih halus di perangkat mobile */
        .bg-gradient-custom {
            background: linear-gradient(135deg, #1e2d6b 0%, #2d4499 60%, #1a2d7a 100%);
            background-attachment: fixed;
        }
    </style>
</head>

<body class="bg-gradient-custom min-h-screen flex items-center justify-center p-6 text-slate-900">

    {{-- Slot isi konten --}}
    {{ $slot }}

    {{-- Stack untuk script tambahan --}}
    @stack('scripts')
</body>

</html>