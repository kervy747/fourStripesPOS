@props(['active' => '', 'guest' => false])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/tab-logo.png') }}">
    <title>FourStripesPOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-100 font-body select-none">

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        @if(!$guest)
            <x-sidebar :active="$active" />
        @endif

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col">
            {{ $slot }}
        </div>

    </div>

</body>
</html>