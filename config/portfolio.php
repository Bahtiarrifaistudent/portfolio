<?php

/*
|--------------------------------------------------------------------------
| Konten Portfolio
|--------------------------------------------------------------------------
| Semua isi website diambil dari file ini. Ubah teks, project, atau stack
| di sini tanpa perlu menyentuh komponen Vue.
|
| Ikon memakai nama dari simple-icons (https://simpleicons.org) tanpa
| awalan "si", contoh: "laravel", "vuedotjs", "tailwindcss".
*/

return [

    'profile' => [
        'name' => 'Bahtiar Rifai',
        'short_name' => 'Bahtiar',
        'initials' => 'BR',
        'role' => 'Fullstack Developer',
        'focus' => 'Laravel Backend',
        'campus' => 'Politeknik Negeri Indramayu',
        'location' => 'Indramayu, Jawa Barat',
        'email' => 'bachtiarrifai55@gmail.com',
        // Isi dengan path foto di folder public, contoh: '/images/profile.jpg'
        'photo' => null,
        // Isi dengan path CV di folder public, contoh: '/files/cv-bahtiar-rifai.pdf'
        'cv' => null,
        'available' => true,
        'tagline' => 'Membangun aplikasi web yang rapi di belakang layar, nyaman di depan layar.',
        'typed_roles' => [
            'Fullstack Developer',
            'Laravel Backend Engineer',
            'Vue.js Enthusiast',
            'API & Realtime Builder',
        ],
        'bio' => [
            'Saya mahasiswa Politeknik Negeri Indramayu yang fokus di pengembangan web fullstack, dengan porsi terbesar di sisi backend menggunakan Laravel.',
            'Project terbesar saya adalah sistem monitoring perangkat realtime berbasis Laravel 12, Inertia.js, dan Vue 3, lengkap dengan REST API, WebSocket, dan remote desktop berbasis WebRTC.',
            'Di luar web, saya pernah bereksperimen di keamanan siber dan AI. Pengalaman itu membuat saya lebih peduli pada keamanan dan kualitas data di setiap aplikasi yang saya bangun.',
        ],
        'socials' => [
            ['label' => 'GitHub', 'icon' => 'github', 'url' => 'https://github.com/Bahtiarrifaistudent', 'handle' => 'Bahtiarrifaistudent'],
            ['label' => 'LinkedIn', 'icon' => 'linkedin', 'url' => 'https://www.linkedin.com/in/bahtiarrifai3', 'handle' => 'in/bahtiarrifai3'],
            ['label' => 'Email', 'icon' => 'gmail', 'url' => 'mailto:bachtiarrifai55@gmail.com', 'handle' => 'bachtiarrifai55@gmail.com'],
        ],
    ],

    'stats' => [
        ['value' => '8', 'label' => 'Repository publik'],
        ['value' => '137', 'label' => 'Kontribusi setahun terakhir'],
        ['value' => '3', 'label' => 'Bidang dijelajahi'],
    ],

    /*
    | Lapisan arsitektur untuk section "Cara Saya Membangun Aplikasi".
    */
    'architecture' => [
        [
            'key' => 'frontend',
            'title' => 'Frontend',
            'subtitle' => 'Vue 3 + Inertia.js',
            'color' => 'fuchsia',
            'description' => 'Halaman reaktif dengan Vue 3 Composition API (<script setup>), dihubungkan ke Laravel lewat Inertia.js tanpa perlu membangun API terpisah untuk UI.',
            'items' => ['Vue 3', 'Inertia.js', 'Tailwind CSS v4', 'Vite', 'useForm & usePage'],
        ],
        [
            'key' => 'backend',
            'title' => 'Backend',
            'subtitle' => 'Laravel 12 / PHP 8.4',
            'color' => 'red',
            'description' => 'Inti aplikasi: routing, middleware, validasi dengan FormRequest, autentikasi API, event & broadcasting, sampai dokumentasi OpenAPI otomatis.',
            'items' => ['Routing & Middleware', 'FormRequest', 'Sanctum & Token Guard', 'Reverb Broadcasting', 'Scramble + Scalar'],
        ],
        [
            'key' => 'data',
            'title' => 'Data',
            'subtitle' => 'MySQL + Eloquent',
            'color' => 'cyan',
            'description' => 'Skema relasional lewat migration, relasi Eloquent (hasMany, belongsToMany), serta Laravel Storage untuk screenshot, rekaman, dan file.',
            'items' => ['MySQL', 'Eloquent ORM', 'Migrations', 'Laravel Storage', 'CSV & Excel Export'],
        ],
        [
            'key' => 'tooling',
            'title' => 'Tooling',
            'subtitle' => 'Laragon + Git',
            'color' => 'violet',
            'description' => 'Lingkungan lokal dengan Laragon, dependency lewat Composer dan npm, serta alur kerja Git berbasis branch di GitHub.',
            'items' => ['Laragon', 'Composer', 'npm', 'Git & GitHub', 'Postman'],
        ],
    ],

    /*
    | Tech stack. "level": core = dipakai di project utama, used = pernah dipakai.
    */
    'stack' => [
        'Frontend' => [
            ['name' => 'Vue.js', 'icon' => 'vuedotjs', 'level' => 'core', 'note' => 'Composition API'],
            ['name' => 'Inertia.js', 'icon' => 'inertia', 'level' => 'core', 'note' => 'Laravel ↔ Vue'],
            ['name' => 'Tailwind CSS', 'icon' => 'tailwindcss', 'level' => 'core', 'note' => 'v4'],
            ['name' => 'Vite', 'icon' => 'vite', 'level' => 'core', 'note' => 'Build tool'],
            ['name' => 'JavaScript', 'icon' => 'javascript', 'level' => 'used', 'note' => 'ES6+'],
            ['name' => 'HTML5', 'icon' => 'html5', 'level' => 'used', 'note' => 'Semantic'],
        ],
        'Backend' => [
            ['name' => 'Laravel', 'icon' => 'laravel', 'level' => 'core', 'note' => 'v12'],
            ['name' => 'PHP', 'icon' => 'php', 'level' => 'core', 'note' => '8.4'],
            ['name' => 'MySQL', 'icon' => 'mysql', 'level' => 'core', 'note' => 'Relational DB'],
            ['name' => 'Laravel Reverb', 'icon' => 'laravel', 'level' => 'core', 'note' => 'WebSocket'],
            ['name' => 'Sanctum', 'icon' => 'laravel', 'level' => 'core', 'note' => 'API Auth'],
            ['name' => 'OpenAPI', 'icon' => 'openapiinitiative', 'level' => 'used', 'note' => 'Scramble'],
            ['name' => 'WebRTC', 'icon' => 'webrtc', 'level' => 'used', 'note' => 'Signaling'],
            ['name' => 'Blade', 'icon' => 'laravel', 'level' => 'used', 'note' => 'Templating'],
        ],
        'Tooling' => [
            ['name' => 'Git', 'icon' => 'git', 'level' => 'core', 'note' => 'Branching'],
            ['name' => 'GitHub', 'icon' => 'github', 'level' => 'core', 'note' => 'Repository'],
            ['name' => 'Composer', 'icon' => 'composer', 'level' => 'core', 'note' => 'PHP deps'],
            ['name' => 'npm', 'icon' => 'npm', 'level' => 'core', 'note' => 'JS deps'],
            ['name' => 'Postman', 'icon' => 'postman', 'level' => 'used', 'note' => 'API testing'],
            ['name' => 'Laragon', 'icon' => 'laravel', 'level' => 'used', 'note' => 'Local server'],
        ],
        'Eksplorasi Lain' => [
            ['name' => 'Python', 'icon' => 'python', 'level' => 'used', 'note' => 'Agent & scripting'],
            ['name' => 'Jupyter', 'icon' => 'jupyter', 'level' => 'used', 'note' => 'NLP notebook'],
            ['name' => 'Kali Linux', 'icon' => 'kalilinux', 'level' => 'used', 'note' => 'Security lab'],
            ['name' => 'OWASP', 'icon' => 'owasp', 'level' => 'used', 'note' => 'Web security'],
        ],
    ],

    /*
    | Project. Setiap project punya halaman detail di /project/{slug}.
    | slug     : alamat halaman detail (huruf kecil, pakai tanda -)
    | category : web | security | ai
    | featured : true = tampil besar di Beranda dan paling atas di halaman Project
    | url      : link repository (null jika privat)
    | demo     : link demo/live (null jika tidak ada)
    | image    : path gambar di folder public, contoh '/images/projects/monitoring.webp'
    */
    'projects' => [
        [
            'slug' => 'monitoring-app',
            'title' => 'Monitoring App',
            'category' => 'web',
            'featured' => true,
            'year' => '2026',
            'role' => 'Fullstack Developer',
            'summary' => 'Sistem monitoring perangkat realtime berbasis Laravel 12, Inertia.js, dan Vue 3, dengan desktop agent Python yang berkomunikasi lewat REST API.',
            'description' => [
                'Admin memantau dan mengatur perangkat dari dashboard web, sementara desktop agent Python yang terpasang di perangkat mengirim status, screenshot, dan aktivitas lewat REST API.',
                'Update ke dashboard dikirim secara realtime melalui Laravel Reverb, dan admin bisa membuka remote desktop langsung dari browser menggunakan WebRTC.',
            ],
            'features' => [
                ['title' => 'REST API + Sanctum', 'text' => 'API untuk agent dengan token Sanctum, plus token guard terpisah untuk API admin.'],
                ['title' => 'Realtime Reverb', 'text' => 'Private channel per device, dengan fallback HTTP polling saat WebSocket tidak tersedia.'],
                ['title' => 'Remote Desktop', 'text' => 'Signaling WebRTC (offer, answer, ICE) untuk mengakses layar perangkat dari browser.'],
                ['title' => 'Geofencing', 'text' => 'Deteksi masuk dan keluar zona dengan algoritma ray-casting yang saya tulis sendiri.'],
                ['title' => 'Policy Engine', 'text' => 'Kebijakan per device/group: screenshot, filter URL/USB/download, kontrol WiFi & Bluetooth.'],
                ['title' => 'API Docs Otomatis', 'text' => 'OpenAPI digenerate dengan Scramble dan ditampilkan lewat Scalar.'],
            ],
            'tags' => ['Laravel 12', 'PHP 8.4', 'Inertia.js', 'Vue 3', 'Tailwind v4', 'Reverb', 'Sanctum', 'WebRTC', 'MySQL', 'Python Agent'],
            'url' => null,
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'smart-city-indramayu',
            'title' => 'Smart City Indramayu',
            'category' => 'web',
            'featured' => false,
            'year' => null,
            'role' => null,
            'summary' => 'Prototype platform Smart City untuk Indramayu: smart governance, smart waste management, dan layanan publik digital.',
            'description' => [],
            'features' => [],
            'tags' => ['HTML', 'UI/UX', 'Responsive'],
            'url' => 'https://github.com/Bahtiarrifaistudent/smartcity-indramayu',
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'floral-innovators',
            'title' => 'Floral Innovators',
            'category' => 'web',
            'featured' => false,
            'year' => null,
            'role' => null,
            'summary' => 'Aplikasi web berbasis Laravel dengan tampilan yang dibangun menggunakan Blade.',
            'description' => [],
            'features' => [],
            'tags' => ['Laravel', 'Blade'],
            'url' => 'https://github.com/Bahtiarrifaistudent/Floral-Innovators',
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'simulasi-brute-force',
            'title' => 'Simulasi Brute Force Attack',
            'category' => 'security',
            'featured' => false,
            'year' => null,
            'role' => null,
            'summary' => 'Tugas besar perkuliahan: mensimulasikan serangan brute force menggunakan Python untuk memahami cara kerjanya.',
            'description' => [],
            'features' => [],
            'tags' => ['Python', 'Cyber Security'],
            'url' => 'https://github.com/Bahtiarrifaistudent/Simulasi-Brute-Force-attack',
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'penanganan-brute-force',
            'title' => 'Penanganan Brute Force Attack',
            'category' => 'security',
            'featured' => false,
            'year' => null,
            'role' => null,
            'summary' => 'Lanjutan dari simulasi: menerapkan teknik penanganan untuk melindungi sistem dari serangan brute force.',
            'description' => [],
            'features' => [],
            'tags' => ['Python', 'Mitigasi'],
            'url' => 'https://github.com/Bahtiarrifaistudent/Penanganan-Brute-Force-Attack',
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'asisten-nlp-polindra',
            'title' => 'Asisten NLP Polindra',
            'category' => 'ai',
            'featured' => false,
            'year' => null,
            'role' => null,
            'summary' => 'Asisten virtual berbasis Natural Language Processing untuk lingkungan Politeknik Negeri Indramayu.',
            'description' => [],
            'features' => [],
            'tags' => ['Jupyter', 'NLP'],
            'url' => 'https://github.com/Bahtiarrifaistudent/Asisten-20berbasis-20NLP-20Polindra',
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'ricescanai',
            'title' => 'RiceScanAI',
            'category' => 'ai',
            'featured' => false,
            'year' => null,
            'role' => null,
            'summary' => 'Eksplorasi pemanfaatan AI untuk membantu pemeriksaan tanaman padi.',
            'description' => [],
            'features' => [],
            'tags' => ['AI', 'Agriculture'],
            'url' => 'https://github.com/Bahtiarrifaistudent/ricescanai',
            'demo' => null,
            'image' => null,
        ],
    ],

    /*
    | Pengalaman: pendidikan, magang/kerja, organisasi.
    | type   : education | work | organization
    | period : teks bebas, contoh '2024 - Sekarang'
    | Entri dengan 'example' => true adalah CONTOH (tampil dengan label "Contoh").
    | Ganti isinya lalu ubah menjadi false, atau hapus entrinya.
    */
    'experience' => [
        [
            'type' => 'education',
            'title' => 'Mahasiswa',
            'place' => 'Politeknik Negeri Indramayu',
            'period' => 'Tahun masuk - Sekarang',
            'description' => 'Isi program studi dan fokus perkuliahan Anda di sini.',
            'points' => [],
            'example' => false,
        ],
        [
            'type' => 'work',
            'title' => 'Web Developer Intern',
            'place' => 'Nama Perusahaan',
            'period' => 'Bulan Tahun - Bulan Tahun',
            'description' => 'Ringkasan tanggung jawab Anda selama magang atau bekerja.',
            'points' => ['Pencapaian pertama', 'Pencapaian kedua'],
            'example' => true,
        ],
        [
            'type' => 'organization',
            'title' => 'Anggota Divisi',
            'place' => 'Nama Organisasi / UKM',
            'period' => 'Tahun - Tahun',
            'description' => 'Peran Anda di organisasi atau kepanitiaan.',
            'points' => [],
            'example' => true,
        ],
    ],

    /*
    | Sertifikat.
    | image : path gambar sertifikat di folder public (opsional)
    | url   : link verifikasi / credential (opsional)
    | Entri dengan 'example' => true adalah CONTOH. Ganti atau hapus.
    */
    'certificates' => [
        [
            'title' => 'Nama Sertifikat Backend',
            'issuer' => 'Nama Penerbit',
            'date' => 'Bulan Tahun',
            'category' => 'Backend',
            'image' => null,
            'url' => null,
            'example' => true,
        ],
        [
            'title' => 'Nama Sertifikat Web',
            'issuer' => 'Nama Penerbit',
            'date' => 'Bulan Tahun',
            'category' => 'Web',
            'image' => null,
            'url' => null,
            'example' => true,
        ],
        [
            'title' => 'Nama Sertifikat Security',
            'issuer' => 'Nama Penerbit',
            'date' => 'Bulan Tahun',
            'category' => 'Security',
            'image' => null,
            'url' => null,
            'example' => true,
        ],
    ],

    /*
    | Roadmap. status: done | progress | next
    */
    'journey' => [
        ['status' => 'done', 'title' => 'Fondasi Laravel', 'text' => 'Routing, Eloquent, Blade, migration, dan validasi.'],
        ['status' => 'done', 'title' => 'Eksplorasi Security & AI', 'text' => 'Simulasi dan penanganan brute force, asisten NLP, dan RiceScanAI.'],
        ['status' => 'done', 'title' => 'SPA dengan Inertia + Vue 3', 'text' => 'Membangun dashboard monitoring dengan Composition API dan Tailwind v4.'],
        ['status' => 'done', 'title' => 'API, Realtime & WebRTC', 'text' => 'Sanctum, Reverb, broadcasting, dan remote desktop berbasis WebRTC.'],
        ['status' => 'progress', 'title' => 'Automated Testing', 'text' => 'Menulis test untuk API dengan Pest / PHPUnit.'],
        ['status' => 'next', 'title' => 'Docker & CI/CD', 'text' => 'Containerization dan deployment otomatis dengan GitHub Actions.'],
    ],
];
