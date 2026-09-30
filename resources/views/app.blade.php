<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Bahtiar Rifai - Fullstack Developer focused on Laravel backend, Inertia.js and Vue 3. Student at Politeknik Negeri Indramayu.">
    <meta name="author" content="Bahtiar Rifai">
    <meta property="og:title" content="Bahtiar Rifai | Fullstack Developer">
    <meta property="og:description" content="Laravel backend, Vue 3 frontend, realtime & API.">
    <meta property="og:type" content="website">
    <meta name="theme-color" content="#0b0616">


    {{-- Apply the theme before render to avoid a flash --}}
    <script>
        (function () {
            try {
                var saved = localStorage.getItem('theme');
                var dark = saved ? saved === 'dark' : true;
                document.documentElement.classList.toggle('dark', dark);
            } catch (e) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>
