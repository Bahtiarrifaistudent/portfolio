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
        'role' => 'Fullstack Developer & AI Engineer',
        'focus' => 'Fullstack & AI/LLM',
        'campus' => 'Politeknik Negeri Indramayu',
        'location' => 'Indramayu, Jawa Barat',
        'email' => 'bachtiarrifai55@gmail.com',
        // Isi dengan path foto di folder public, contoh: '/images/profile.jpg'
        'photo' => '/images/profile.jpg',
        // Isi dengan path CV di folder public, contoh: '/files/cv-bahtiar-rifai.pdf'
        'cv' => '/files/cv-bahtiar-rifai.pdf',
        'available' => true,
        'tagline' => 'Membangun aplikasi yang rapi dari backend sampai layar, lalu menghadirkan AI di dalamnya.',
        'typed_roles' => [
            'Fullstack Developer',
            'AI Engineer',
            'LLM & AI Agent Builder',
            'Laravel + Vue Developer',
            'Flutter Mobile Developer',
        ],
        'bio' => [
            'Mahasiswa Sarjana Terapan Sistem Informasi Kota Cerdas di Politeknik Negeri Indramayu dengan pengalaman membangun aplikasi web, mobile, dan desktop secara fullstack, serta minat kuat di AI Engineering & LLM Development.',
            'Terbiasa mengintegrasikan LLM API, melakukan fine-tuning model dengan LoRA/QLoRA, dan membangun AI agent dengan LangGraph dan RAG. Saya juga membangun ChatBot PMB berbasis NLP untuk Polindra dan RiceScanAI, sistem deteksi penyakit daun padi berbasis Deep Learning.',
            'Di sisi produksi, saat magang sebagai Full Stack Web Developer di Samara saya mengembangkan sistem Device Monitoring real-time (Laravel, Vue, Reverb, WebRTC, dan agent Windows) serta SIMUH, platform manajemen umrah & haji multi-peran. Saya terbiasa merancang role-based access, sistem real-time, dan integrasi pihak ketiga seperti payment gateway.',
        ],
        'socials' => [
            ['label' => 'GitHub', 'icon' => 'github', 'url' => 'https://github.com/Bahtiarrifaistudent', 'handle' => 'Bahtiarrifaistudent'],
            ['label' => 'LinkedIn', 'icon' => 'linkedin', 'url' => 'https://www.linkedin.com/in/bahtiarrifai3', 'handle' => 'in/bahtiarrifai3'],
            ['label' => 'Email', 'icon' => 'gmail', 'url' => 'mailto:bachtiarrifai55@gmail.com', 'handle' => 'bachtiarrifai55@gmail.com'],
        ],
    ],

    'stats' => [
        ['value' => '13', 'label' => 'Project dibangun'],
        ['value' => '137', 'label' => 'Kontribusi setahun terakhir'],
        ['value' => '4', 'label' => 'Web, mobile, desktop, AI'],
    ],

    /*
    | Lapisan arsitektur untuk section "Cara Saya Membangun Aplikasi".
    */
    'architecture' => [
        [
            'key' => 'frontend',
            'title' => 'Frontend',
            'subtitle' => 'Vue, React, Flutter',
            'color' => 'fuchsia',
            'description' => 'Antarmuka web dengan Vue 3 + Inertia.js atau React/Next.js, aplikasi mobile dengan Flutter, dan aplikasi desktop dengan Electron.',
            'items' => ['Vue 3 + Inertia.js', 'React / Next.js', 'Flutter', 'Electron', 'Tailwind CSS v4'],
        ],
        [
            'key' => 'backend',
            'title' => 'Backend',
            'subtitle' => 'Laravel, Node.js, Django',
            'color' => 'red',
            'description' => 'REST API, autentikasi, role-based access untuk sistem multi-peran, sistem real-time (Reverb, Socket.IO, FCM), WebRTC, dan integrasi pihak ketiga seperti payment gateway.',
            'items' => ['Laravel 12 + Sanctum', 'Node.js / Express', 'Reverb & Socket.IO', 'WebRTC', 'Payment Gateway'],
        ],
        [
            'key' => 'ai',
            'title' => 'AI Layer',
            'subtitle' => 'LLM + AI Agent',
            'color' => 'emerald',
            'description' => 'Integrasi LLM API, fine-tuning model dengan LoRA/QLoRA, AI agent dengan LangGraph dan RAG, serta serving model dengan vLLM atau Ollama.',
            'items' => ['LoRA / QLoRA', 'LangGraph Agent', 'RAG', 'Hugging Face', 'vLLM & Ollama'],
        ],
        [
            'key' => 'data',
            'title' => 'Data',
            'subtitle' => 'SQL + Cache + BaaS',
            'color' => 'cyan',
            'description' => 'Skema relasional lewat migration dan Eloquent, cache dan antrean dengan Redis, serta Firebase/Supabase untuk aplikasi mobile.',
            'items' => ['MySQL / PostgreSQL', 'SQLite', 'Redis', 'Firebase / Supabase', 'Eloquent ORM'],
        ],
        [
            'key' => 'tooling',
            'title' => 'DevOps',
            'subtitle' => 'Git, Docker, CI/CD',
            'color' => 'violet',
            'description' => 'Versioning dengan Git, container dengan Docker di Linux, perancangan pipeline CI/CD, serta desain UI di Figma.',
            'items' => ['Git & GitHub', 'Docker', 'Linux', 'CI/CD Pipeline', 'Figma'],
        ],
    ],

    /*
    | Tech stack. "level": core = dipakai di project utama, used = pernah dipakai.
    */
    'stack' => [
        'Languages' => [
            ['name' => 'PHP', 'icon' => 'php', 'level' => 'core', 'note' => '8.4'],
            ['name' => 'Python', 'icon' => 'python', 'level' => 'core', 'note' => 'AI, agent, scripting'],
            ['name' => 'TypeScript', 'icon' => 'typescript', 'level' => 'core', 'note' => 'Typed JS'],
            ['name' => 'JavaScript', 'icon' => 'javascript', 'level' => 'core', 'note' => 'ES6+'],
            ['name' => 'Dart', 'icon' => 'dart', 'level' => 'used', 'note' => 'Flutter'],
            ['name' => 'Java', 'icon' => 'openjdk', 'level' => 'used', 'note' => 'OOP'],
            ['name' => 'Go', 'icon' => 'go', 'level' => 'used', 'note' => 'Backend service'],
            ['name' => 'SQL', 'icon' => null, 'level' => 'used', 'note' => 'Query & schema'],
        ],
        'Frontend' => [
            ['name' => 'Vue.js', 'icon' => 'vuedotjs', 'level' => 'core', 'note' => 'Composition API'],
            ['name' => 'Inertia.js', 'icon' => 'inertia', 'level' => 'core', 'note' => 'Laravel ↔ Vue'],
            ['name' => 'Tailwind CSS', 'icon' => 'tailwindcss', 'level' => 'core', 'note' => 'v4'],
            ['name' => 'React', 'icon' => 'react', 'level' => 'used', 'note' => 'Hooks'],
            ['name' => 'Next.js', 'icon' => 'nextdotjs', 'level' => 'used', 'note' => 'React framework'],
            ['name' => 'Vite', 'icon' => 'vite', 'level' => 'core', 'note' => 'Build tool'],
            ['name' => 'HTML5', 'icon' => 'html5', 'level' => 'used', 'note' => 'Semantic'],
        ],
        'Mobile & Desktop' => [
            ['name' => 'Flutter', 'icon' => 'flutter', 'level' => 'core', 'note' => 'Mobile architecture'],
            ['name' => 'Electron', 'icon' => 'electron', 'level' => 'used', 'note' => 'Desktop app'],
            ['name' => 'Python Agent', 'icon' => 'python', 'level' => 'core', 'note' => 'Windows agent'],
            ['name' => 'Firebase FCM', 'icon' => 'firebase', 'level' => 'used', 'note' => 'Push notification'],
        ],
        'Backend' => [
            ['name' => 'Laravel', 'icon' => 'laravel', 'level' => 'core', 'note' => 'v12'],
            ['name' => 'Node.js', 'icon' => 'nodedotjs', 'level' => 'used', 'note' => 'Runtime'],
            ['name' => 'Express', 'icon' => 'express', 'level' => 'used', 'note' => 'REST API'],
            ['name' => 'Django', 'icon' => 'django', 'level' => 'used', 'note' => 'Python web'],
            ['name' => 'REST API', 'icon' => 'openapiinitiative', 'level' => 'core', 'note' => 'Scramble / OpenAPI'],
            ['name' => 'Laravel Reverb', 'icon' => 'laravel', 'level' => 'core', 'note' => 'WebSocket'],
            ['name' => 'Socket.IO', 'icon' => 'socketdotio', 'level' => 'used', 'note' => 'Real-time'],
            ['name' => 'WebRTC', 'icon' => 'webrtc', 'level' => 'core', 'note' => 'Remote desktop'],
            ['name' => 'Sanctum', 'icon' => 'laravel', 'level' => 'core', 'note' => 'API auth'],
            ['name' => 'Role-based Access', 'icon' => null, 'level' => 'core', 'note' => 'Multi-role design'],
            ['name' => 'Payment Gateway', 'icon' => null, 'level' => 'used', 'note' => 'Third-party API'],
            ['name' => 'Document Parsing', 'icon' => null, 'level' => 'used', 'note' => 'XLSX/DOCX/PPTX'],
        ],
        'AI & LLM' => [
            ['name' => 'LLM Fine-tuning', 'icon' => null, 'level' => 'core', 'note' => 'LoRA / QLoRA'],
            ['name' => 'AI Agent', 'icon' => null, 'level' => 'core', 'note' => 'Architecture & deploy'],
            ['name' => 'LangGraph', 'icon' => 'langgraph', 'level' => 'core', 'note' => 'Agent workflow'],
            ['name' => 'RAG', 'icon' => null, 'level' => 'core', 'note' => 'Retrieval-Augmented Gen.'],
            ['name' => 'Hugging Face', 'icon' => 'huggingface', 'level' => 'core', 'note' => 'Transformers'],
            ['name' => 'vLLM', 'icon' => 'vllm', 'level' => 'used', 'note' => 'Model serving'],
            ['name' => 'Ollama', 'icon' => 'ollama', 'level' => 'used', 'note' => 'Local LLM'],
            ['name' => 'NLP', 'icon' => null, 'level' => 'used', 'note' => 'ChatBot PMB'],
            ['name' => 'Deep Learning', 'icon' => null, 'level' => 'used', 'note' => 'InceptionV3'],
            ['name' => 'Google Colab', 'icon' => 'googlecolab', 'level' => 'used', 'note' => 'Training'],
            ['name' => 'Jupyter', 'icon' => 'jupyter', 'level' => 'used', 'note' => 'Notebook'],
        ],
        'Database' => [
            ['name' => 'MySQL', 'icon' => 'mysql', 'level' => 'core', 'note' => 'Relational DB'],
            ['name' => 'PostgreSQL', 'icon' => 'postgresql', 'level' => 'used', 'note' => 'Relational DB'],
            ['name' => 'SQLite', 'icon' => 'sqlite', 'level' => 'used', 'note' => 'Embedded DB'],
            ['name' => 'Redis', 'icon' => 'redis', 'level' => 'used', 'note' => 'Cache & queue'],
            ['name' => 'Firebase', 'icon' => 'firebase', 'level' => 'used', 'note' => 'BaaS'],
            ['name' => 'Supabase', 'icon' => 'supabase', 'level' => 'used', 'note' => 'BaaS'],
        ],
        'Tools & DevOps' => [
            ['name' => 'Git', 'icon' => 'git', 'level' => 'core', 'note' => 'Branching'],
            ['name' => 'GitHub', 'icon' => 'github', 'level' => 'core', 'note' => 'Repository'],
            ['name' => 'Docker', 'icon' => 'docker', 'level' => 'used', 'note' => 'Container'],
            ['name' => 'Linux', 'icon' => 'linux', 'level' => 'used', 'note' => 'Server'],
            ['name' => 'CI/CD', 'icon' => 'githubactions', 'level' => 'used', 'note' => 'Pipeline design'],
            ['name' => 'Postman', 'icon' => 'postman', 'level' => 'used', 'note' => 'API testing'],
            ['name' => 'VS Code', 'icon' => null, 'level' => 'core', 'note' => 'Editor'],
            ['name' => 'Composer', 'icon' => 'composer', 'level' => 'core', 'note' => 'PHP deps'],
            ['name' => 'npm / pnpm', 'icon' => 'pnpm', 'level' => 'core', 'note' => 'JS deps'],
            ['name' => 'Laragon', 'icon' => null, 'level' => 'used', 'note' => 'Local server'],
            ['name' => 'Figma', 'icon' => 'figma', 'level' => 'used', 'note' => 'UI design'],
            ['name' => 'Canva', 'icon' => null, 'level' => 'used', 'note' => 'Graphic design'],
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
            'title' => 'Device Monitoring App',
            'category' => 'web',
            'featured' => true,
            'year' => '2026',
            'role' => 'Fullstack Developer (Magang di Samara)',
            'summary' => 'Sistem monitoring perangkat realtime berbasis Laravel 12, Inertia.js, dan Vue 3, dengan agent Python di Windows yang berkomunikasi lewat REST API. Dikerjakan penuh dari backend, frontend, hingga agent.',
            'description' => [
                'Admin memantau dan mengatur perangkat dari dashboard web, sementara agent Python yang berjalan di Windows mengirim status, screenshot, dan aktivitas lewat REST API.',
                'Update ke dashboard dikirim secara realtime melalui Laravel Reverb, dan admin bisa membuka remote desktop langsung dari browser menggunakan WebRTC.',
                'Dikembangkan selama magang di Samara - PT Satya Amarta Prima. Saya mengerjakan keseluruhan fitur aplikasi: backend, frontend, dan agent Windows.',
            ],
            'features' => [
                ['title' => 'REST API + Sanctum', 'text' => 'API untuk agent dengan token Sanctum, plus token guard terpisah untuk API admin.'],
                ['title' => 'Realtime Reverb', 'text' => 'Private channel per device, dengan fallback HTTP polling saat WebSocket tidak tersedia.'],
                ['title' => 'Remote Desktop', 'text' => 'Signaling WebRTC (offer, answer, ICE) untuk mengakses layar perangkat dari browser.'],
                ['title' => 'Geofencing', 'text' => 'Deteksi masuk dan keluar zona dengan algoritma ray-casting yang saya tulis sendiri.'],
                ['title' => 'Policy Engine', 'text' => 'Kebijakan per device/group: screenshot, filter URL/USB/download, kontrol WiFi & Bluetooth.'],
                ['title' => 'API Docs Otomatis', 'text' => 'OpenAPI digenerate dengan Scramble dan ditampilkan lewat Scalar.'],
            ],
            'tags' => ['Laravel 12', 'PHP 8.4', 'Inertia.js', 'Vue 3', 'Tailwind v4', 'Reverb', 'Sanctum', 'WebRTC', 'MySQL', 'Python Agent (Windows)'],
            'url' => null,
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'simuh',
            'title' => 'SIMUH: Sarana Integrasi Manajemen Umrah & Haji',
            'category' => 'web',
            'featured' => true,
            'year' => '2026',
            'role' => 'Backend & Frontend Developer (Magang di Samara)',
            'summary' => 'Platform manajemen umrah & haji multi-peran untuk Travel, Petugas Lapangan, Jamaah, dan mitra terkait lainnya.',
            'description' => [
                'SIMUH mengintegrasikan proses manajemen umrah dan haji dalam satu platform yang digunakan oleh banyak peran: Travel, Petugas Lapangan, Jamaah, dan mitra terkait lainnya.',
                'Dikembangkan selama magang di Samara - PT Satya Amarta Prima. Saya berkontribusi di sisi backend dan frontend, meliputi pengembangan berbagai fitur aplikasi, penguatan keamanan data, serta penyempurnaan antarmuka pengguna.',
            ],
            'features' => [
                ['title' => 'Multi-peran', 'text' => 'Akses dan alur kerja terpisah untuk Travel, Petugas Lapangan, Jamaah, dan mitra.'],
                ['title' => 'Pengembangan Fitur', 'text' => 'Membangun berbagai fitur aplikasi di sisi backend dan frontend.'],
                ['title' => 'Keamanan Data', 'text' => 'Penguatan keamanan data pada aplikasi.'],
                ['title' => 'Antarmuka Pengguna', 'text' => 'Penyempurnaan tampilan agar nyaman dipakai setiap peran.'],
            ],
            'tags' => ['Backend', 'Frontend', 'Multi-role', 'Keamanan Data'],
            'url' => null,
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'ricescanai',
            'title' => 'RiceScanAI',
            'category' => 'ai',
            'featured' => false,
            'year' => '2026',
            'role' => null,
            'summary' => 'Sistem deteksi penyakit daun padi berbasis Deep Learning menggunakan InceptionV3 sebagai solusi Smart Agriculture dalam ekosistem Smart City.',
            'description' => [
                'RiceScanAI mendeteksi penyakit pada daun padi dari gambar menggunakan model Deep Learning berarsitektur InceptionV3.',
                'Proyek ini diposisikan sebagai solusi Smart Agriculture dalam ekosistem Smart City. Dikerjakan Jan 2026 - Mei 2026.',
            ],
            'features' => [],
            'tags' => ['Python', 'Deep Learning', 'InceptionV3', 'Computer Vision', 'Google Colab'],
            'url' => 'https://github.com/Bahtiarrifaistudent/ricescanai',
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'asisten-nlp-polindra',
            'title' => 'Asisten NLP Polindra: ChatBot PMB',
            'category' => 'ai',
            'featured' => false,
            'year' => '2025',
            'role' => 'NLP & Integration Developer',
            'summary' => 'ChatBot berbasis Natural Language Processing untuk membantu calon mahasiswa baru memperoleh informasi penerimaan di Polindra.',
            'description' => [
                'Sistem asisten cerdas berbasis NLP yang menjawab pertanyaan seputar pendaftaran, jalur seleksi, persyaratan, biaya, jadwal, dan informasi PMB lainnya secara otomatis dan responsif.',
                'Kontribusi saya: merancang dan mengimplementasikan modul NLP untuk memahami pertanyaan calon mahasiswa, mengembangkan integrasi front-end dan back-end, serta mengelola dataset tanya-jawab dan melatih model agar jawaban semakin akurat.',
                'Tujuannya mempermudah akses informasi PMB secara digital sekaligus mengurangi beban pelayanan manual dari pihak kampus. Dikerjakan Agt 2025 - Nov 2025.',
            ],
            'features' => [
                ['title' => 'Tanya Jawab Otomatis', 'text' => 'Layanan tanya jawab berbasis NLP untuk informasi Penerimaan Mahasiswa Baru.'],
                ['title' => 'Respons Real-time', 'text' => 'Menjawab pertanyaan jalur masuk, syarat pendaftaran, jadwal seleksi, dan biaya.'],
                ['title' => 'Manajemen Data PMB', 'text' => 'Admin dapat mengelola dan memperbarui data informasi PMB.'],
                ['title' => 'Antarmuka Percakapan', 'text' => 'Tampilan chat sederhana yang mudah digunakan calon mahasiswa.'],
            ],
            'tags' => ['Python', 'NLP', 'ChatBot', 'Jupyter', 'Dataset & Training'],
            'url' => 'https://github.com/Bahtiarrifaistudent/Asisten-20berbasis-20NLP-20Polindra',
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'krti-2025-racing-plane',
            'title' => 'KRTI 2025 - Racing Plane',
            'category' => 'robotics',
            'featured' => false,
            'year' => '2025',
            'role' => 'Programmer',
            'summary' => 'Sistem kontrol otomatis robot pesawat balap untuk Kontes Robot Terbang Indonesia (KRTI) 2025 divisi Racing Plane.',
            'description' => [
                'Sebagai programmer, saya merancang dan mengimplementasikan sistem kontrol otomatis yang memungkinkan pesawat mengikuti jalur yang telah ditentukan dengan tingkat akurasi tinggi.',
                'Mencakup pengembangan perangkat lunak untuk kontrol penerbangan, navigasi, serta integrasi sensor dan aktuator pada robot. Saya juga mengurus administrasi tim dan mengoordinasikan anggota. Dikerjakan Mar 2025 - Okt 2025.',
            ],
            'features' => [
                ['title' => 'Kontrol Penerbangan', 'text' => 'Perangkat lunak kontrol otomatis agar pesawat mengikuti jalur dengan akurat.'],
                ['title' => 'Navigasi', 'text' => 'Pengembangan sistem navigasi jalur penerbangan.'],
                ['title' => 'Sensor & Aktuator', 'text' => 'Integrasi sensor dan aktuator pada robot pesawat.'],
            ],
            'tags' => ['Flight Control', 'Navigasi', 'Sensor & Aktuator', 'Robotika'],
            'url' => null,
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'floral-innovators-mobile',
            'title' => 'Floral Innovators Mobile',
            'category' => 'mobile',
            'featured' => false,
            'year' => '2025',
            'role' => 'Mobile Developer',
            'summary' => 'Aplikasi mobile pelatihan interaktif untuk meningkatkan keterampilan UMKM buket bunga di Indramayu.',
            'description' => [
                'Menyediakan materi pelatihan interaktif, panduan pembuatan buket, serta fitur evaluasi sehingga pelaku usaha dapat belajar kapan saja dan di mana saja melalui perangkat mobile.',
                'Kontribusi saya: mengembangkan fitur pelatihan interaktif dan modul evaluasi, mengimplementasikan autentikasi, manajemen pengguna, dan integrasi dengan layanan back-end, serta berkolaborasi dalam perancangan UI/UX dan pengujian fungsionalitas. Dikerjakan Mar 2025 - Jul 2025.',
            ],
            'features' => [
                ['title' => 'Modul Pelatihan', 'text' => 'Video, artikel, dan langkah pembuatan buket yang dapat diakses dari aplikasi.'],
                ['title' => 'Kuis & Evaluasi', 'text' => 'Mengukur tingkat pemahaman peserta.'],
                ['title' => 'Dashboard Progres', 'text' => 'Perkembangan belajar peserta dalam bentuk ringkas.'],
                ['title' => 'Manajemen Terpusat', 'text' => 'Admin mengelola materi dan peserta lewat sistem terpusat.'],
            ],
            'tags' => ['Mobile', 'Autentikasi', 'REST API', 'UI/UX'],
            'url' => null,
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'floral-innovators',
            'title' => 'Floral Innovators Web',
            'category' => 'web',
            'featured' => false,
            'year' => '2025',
            'role' => 'Web Developer',
            'summary' => 'Sistem informasi pelatihan interaktif berbasis website untuk meningkatkan keterampilan UMKM buket bunga di Indramayu.',
            'description' => [
                'Platform yang menyediakan materi pelatihan interaktif, panduan pembuatan produk, serta fitur evaluasi untuk meningkatkan kompetensi dan produktivitas pelaku usaha secara digital.',
                'Kontribusi saya: mengembangkan fitur pelatihan interaktif dan modul evaluasi, mengimplementasikan manajemen pengguna dan materi pelatihan, serta berkolaborasi dalam perancangan tampilan dan pengujian sistem. Dikerjakan Feb 2025 - Jul 2025.',
            ],
            'features' => [
                ['title' => 'Modul Pelatihan', 'text' => 'Video, artikel, dan langkah pembuatan buket.'],
                ['title' => 'Kuis & Evaluasi', 'text' => 'Mengukur pemahaman peserta.'],
                ['title' => 'Dashboard Peserta', 'text' => 'Memantau perkembangan peserta pelatihan.'],
                ['title' => 'Manajemen Admin', 'text' => 'Pengelolaan materi pelatihan dan peserta.'],
            ],
            'tags' => ['Laravel', 'Blade', 'MySQL'],
            'url' => 'https://github.com/Bahtiarrifaistudent/Floral-Innovators',
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'krti-2024-racing-plane',
            'title' => 'KRTI 2024 - Racing Plane',
            'category' => 'robotics',
            'featured' => false,
            'year' => '2024',
            'role' => 'Programmer',
            'summary' => 'Sistem kontrol otomatis robot pesawat balap untuk KRTI 2024 divisi Racing Plane. Tim meraih sertifikat Peserta Wilayah.',
            'description' => [
                'Sebagai programmer, saya merancang dan mengimplementasikan sistem kontrol otomatis yang memungkinkan pesawat mengikuti jalur yang telah ditentukan dengan tingkat akurasi tinggi.',
                'Mencakup pengembangan perangkat lunak untuk kontrol penerbangan, navigasi, serta integrasi sensor dan aktuator. Saya juga mengurus administrasi tim dan mengoordinasikan anggota. Dikerjakan Jan 2024 - Sep 2024 bersama Darmawan.',
            ],
            'features' => [
                ['title' => 'Kontrol Penerbangan', 'text' => 'Perangkat lunak kontrol otomatis agar pesawat mengikuti jalur dengan akurat.'],
                ['title' => 'Navigasi', 'text' => 'Pengembangan sistem navigasi jalur penerbangan.'],
                ['title' => 'Sensor & Aktuator', 'text' => 'Integrasi sensor dan aktuator pada robot pesawat.'],
            ],
            'tags' => ['Flight Control', 'Navigasi', 'Program Management', 'Communication'],
            'url' => null,
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'inventaris-bmn',
            'title' => 'Sistem Inventaris & Peminjaman BMN',
            'category' => 'web',
            'featured' => false,
            'year' => '2024',
            'role' => 'Web Developer',
            'summary' => 'Sistem informasi pengelolaan inventaris dan peminjaman Barang Milik Negara (BMN) di Jurusan Teknik Informatika.',
            'description' => [
                'Memudahkan pendataan inventaris, pengajuan peminjaman, persetujuan, hingga pengembalian barang secara terstruktur dan terdigitalisasi.',
                'Kontribusi saya: merancang dan mengimplementasikan modul peminjaman serta pengembalian barang, mengembangkan integrasi front-end dan back-end untuk pencatatan BMN, serta berkolaborasi dalam desain antarmuka dan pengujian. Dikerjakan Feb 2024 - Jul 2024.',
            ],
            'features' => [
                ['title' => 'Inventaris Digital', 'text' => 'Pendataan inventaris BMN secara digital dan terpusat.'],
                ['title' => 'Peminjaman Online', 'text' => 'Pengajuan peminjaman barang secara online.'],
                ['title' => 'Pelacakan Status', 'text' => 'Status peminjaman dan pengembalian secara real-time.'],
                ['title' => 'Riwayat & Laporan', 'text' => 'Riwayat penggunaan barang serta laporan otomatis.'],
            ],
            'tags' => ['Web', 'Inventory', 'Front-end & Back-end'],
            'url' => null,
            'demo' => null,
            'image' => null,
        ],
        [
            'slug' => 'pkm-vokasi-2024',
            'title' => 'PKM Vokasi 2024: Kewirausahaan Digital Marketing',
            'category' => 'community',
            'featured' => false,
            'year' => '2024',
            'role' => 'Anggota Tim',
            'summary' => 'Program Penerapan Iptek untuk meningkatkan kewirausahaan berbasis digital marketing pada ekonomi kreatif masyarakat desa.',
            'description' => [
                'Memperluas akses UMKM untuk memanfaatkan teknologi digital dalam mempromosikan produk dan layanan.',
                'Meningkatkan kesadaran serta keterampilan masyarakat dalam berinovasi di bidang pemasaran guna meningkatkan daya saing. Dilaksanakan Nov 2023 - Jan 2024.',
            ],
            'features' => [],
            'tags' => ['Digital Marketing', 'UMKM', 'Communication'],
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
            'type' => 'work',
            'title' => 'Full Stack Web Developer',
            'place' => 'Samara - PT Satya Amarta Prima',
            'period' => 'Jul 2026 - Present · Internship',
            'description' => 'Berkontribusi dalam pengembangan dua sistem berbasis web, dari backend, frontend, hingga agent Windows. Pengalaman ini memperkuat kemampuan saya membangun sistem kompleks dari awal hingga siap digunakan.',
            'points' => [
                'Device Monitoring: mengerjakan keseluruhan fitur, termasuk monitoring perangkat real-time serta pengelolaan dan pengendalian jarak jauh.',
                'SIMUH: platform manajemen umrah & haji multi-peran; pengembangan fitur, penguatan keamanan data, dan penyempurnaan antarmuka.',
            ],
            'company' => [
                'name' => 'Samara - PT Satya Amarta Prima',
                'location' => 'Tangerang Selatan, Banten (On-site)',
                'description' => 'Tempat saya magang sebagai Full Stack Web Developer sejak Juli 2026.',
            ],
            'projects' => [
                [
                    'title' => 'Device Monitoring App',
                    'role' => 'Fullstack Developer',
                    'description' => 'Sistem monitoring perangkat real-time dengan fitur pengelolaan dan pengendalian jarak jauh. Saya mengerjakan keseluruhan fitur: backend, frontend, hingga agent yang berjalan di Windows.',
                    'tech' => ['Laravel 12', 'Vue 3', 'Inertia.js', 'Reverb', 'WebRTC', 'Python Agent'],
                    'url' => '/projects/monitoring-app',
                ],
                [
                    'title' => 'SIMUH: Sarana Integrasi Manajemen Umrah & Haji',
                    'role' => 'Backend & Frontend Developer',
                    'description' => 'Platform manajemen umrah & haji multi-peran (Travel, Petugas Lapangan, Jamaah, dan mitra terkait). Berkontribusi pada pengembangan fitur, penguatan keamanan data, dan penyempurnaan antarmuka pengguna.',
                    'tech' => ['Backend', 'Frontend', 'Multi-role'],
                    'url' => '/projects/simuh',
                ],
            ],
        ],
        [
            'type' => 'work',
            'title' => 'Management Billing',
            'place' => 'PT Dinamika Energy Indonesia',
            'period' => 'Mar 2025 - Present · Part-time',
            'description' => 'Pengelolaan data pelanggan dan kWh meter listrik secara online di lapangan.',
            'points' => [
                'Input angka kWh meter listrik prabayar dan pascabayar ke sistem secara online menggunakan smartphone.',
                'Input dan pembaruan data pelanggan ke dalam sistem PLN.',
                'Pendataan kondisi dan peruntukan kWh meter prabayar untuk keperluan administrasi.',
                'Penataan dan penginputan Data Induk Langganan (PDIL).',
                'Penginputan titik koordinat lokasi pelanggan sesuai data lapangan.',
            ],
            'company' => [
                'name' => 'PT Dinamika Energy Indonesia',
                'location' => 'On-site',
            ],
        ],
        [
            'type' => 'work',
            'title' => 'Copywriter',
            'place' => 'PT Winnicode Garuda Indonesia',
            'period' => 'Jan 2025 - May 2025 · Internship',
            'description' => 'Menulis dan mengoptimalkan artikel untuk website secara remote.',
            'points' => [
                'Menyusun brief serta rencana penulisan artikel.',
                'Menulis konten berkualitas dan mempublikasikannya pada website.',
                'Mengoptimalkan konten untuk keperluan SEO.',
                'Mengevaluasi dan mengembangkan konten agar tetap relevan dan efektif.',
            ],
            'company' => [
                'name' => 'PT Winnicode Garuda Indonesia',
                'location' => 'Daerah Istimewa Yogyakarta (Remote)',
            ],
        ],
        [
            'type' => 'volunteer',
            'title' => 'Public Relations',
            'place' => 'PKKMB POLINDRA 2024',
            'period' => 'Aug 2024',
            'description' => 'Panitia Pengenalan Kehidupan Kampus bagi Mahasiswa Baru Politeknik Negeri Indramayu.',
            'points' => [
                'Menjadi narasumber (Campus Leader) dan contact person untuk mitra eksternal.',
                'Mengirim surat delegasi, rekomendasi, dan peminjaman barang, serta berkoordinasi dengan sie acara.',
                'Menjadi penghubung antara mahasiswa baru dengan kelompok program studinya.',
                'Mengumpulkan RAB dari setiap organisasi, melakukan evaluasi, dan menyusun laporan akhir.',
            ],
        ],
        [
            'type' => 'volunteer',
            'title' => 'Field Coordinator',
            'place' => 'Ospek Prodi Sistem Informasi Kota Cerdas JTI 2024',
            'period' => 'Aug 2024',
            'description' => 'Koordinator lapangan pada kegiatan orientasi program studi.',
            'points' => [
                'Menyiapkan lokasi kegiatan, termasuk pengecekan fasilitas dan sarana prasarana.',
                'Berkolaborasi dengan tim acara, keamanan, dan kesehatan untuk kelancaran kegiatan.',
                'Memantau progres kegiatan sesuai jadwal dan menyelesaikan kendala di lapangan.',
                'Menyusun evaluasi dan laporan lengkap dengan rekomendasi perbaikan.',
            ],
        ],
        [
            'type' => 'organization',
            'title' => 'Bendahara Umum & Divisi Riset Robot Racing Plane',
            'place' => 'Robotika Politeknik Negeri Indramayu',
            'period' => 'Jul 2024 - Oct 2025',
            'description' => 'Berperan aktif berkontribusi dan berinovasi untuk memajukan organisasi robotika, sekaligus menjadi programmer tim Racing Plane di KRTI 2024 dan 2025.',
            'points' => [
                'Bendahara Umum: mengelola keuangan anggota, menyusun laporan keuangan bulanan hingga tahunan, mengalokasikan dana proyek, dan audit internal.',
                'Divisi Racing Plane: mengawasi desain, pengujian, dan sistem kontrol, memberi masukan teknis, serta menyusun laporan perkembangan.',
            ],
            'company' => [
                'name' => 'Robotika Politeknik Negeri Indramayu',
                'location' => 'Indramayu, Jawa Barat',
                'description' => 'Organisasi robotika di Politeknik Negeri Indramayu yang mengikuti Kontes Robot Terbang Indonesia (KRTI).',
            ],
            'projects' => [
                [
                    'title' => 'KRTI 2025 - Racing Plane',
                    'role' => 'Programmer',
                    'description' => 'Merancang sistem kontrol otomatis agar pesawat mengikuti jalur dengan akurasi tinggi: kontrol penerbangan, navigasi, serta integrasi sensor dan aktuator.',
                    'tech' => ['Flight Control', 'Navigasi', 'Sensor & Aktuator'],
                    'url' => '/projects/krti-2025-racing-plane',
                ],
                [
                    'title' => 'KRTI 2024 - Racing Plane',
                    'role' => 'Programmer',
                    'description' => 'Sistem kontrol otomatis robot pesawat balap, sekaligus mengurus administrasi dan koordinasi tim. Tim meraih sertifikat Peserta Wilayah.',
                    'tech' => ['Flight Control', 'Navigasi', 'Program Management'],
                    'url' => '/projects/krti-2024-racing-plane',
                ],
            ],
            'certificates' => [
                [
                    'title' => 'Peserta Wilayah - KRTI 2024 Divisi Racing Plane',
                    'issuer' => 'Balai Pengembangan Talenta Indonesia (Puspresnas)',
                    'date' => 'Aug 2024',
                    'credential_id' => '22960/BPTI/DIKTI/2024',
                    'file' => '/files/certificates/krti-2024-racing-plane.pdf',
                    'photos' => ['/images/certificates/krti-2024-racing-plane.jpg'],
                ],
            ],
        ],
        [
            'type' => 'education',
            'title' => 'Sarjana Terapan - Sistem Informasi Kota Cerdas',
            'place' => 'Politeknik Negeri Indramayu',
            'period' => 'Sep 2023 - Present',
            'description' => 'Program Sarjana Terapan (Applied Bachelor) Sistem Informasi Kota Cerdas, Jurusan Teknik Informatika. Fokus pada pengembangan web fullstack, dengan proyek kampus di bidang web, mobile, AI, dan robotika.',
            'points' => [],
            'company' => [
                'name' => 'Politeknik Negeri Indramayu',
                'location' => 'Indramayu, Jawa Barat',
            ],
            'projects' => [
                [
                    'title' => 'Asisten NLP Polindra: ChatBot PMB',
                    'description' => 'ChatBot berbasis NLP untuk informasi Penerimaan Mahasiswa Baru.',
                    'tech' => ['NLP', 'ChatBot'],
                    'url' => '/projects/asisten-nlp-polindra',
                ],
                [
                    'title' => 'RiceScanAI',
                    'description' => 'Deteksi penyakit daun padi berbasis Deep Learning (InceptionV3).',
                    'tech' => ['Deep Learning', 'InceptionV3'],
                    'url' => '/projects/ricescanai',
                ],
                [
                    'title' => 'Floral Innovators Web & Mobile',
                    'description' => 'Sistem pelatihan interaktif untuk UMKM buket bunga di Indramayu, versi website dan mobile.',
                    'tech' => ['Laravel', 'Mobile'],
                    'url' => '/projects/floral-innovators',
                ],
                [
                    'title' => 'Sistem Inventaris & Peminjaman BMN',
                    'description' => 'Pengelolaan inventaris dan peminjaman Barang Milik Negara di Jurusan Teknik Informatika.',
                    'tech' => ['Web'],
                    'url' => '/projects/inventaris-bmn',
                ],
            ],
        ],
        [
            'type' => 'work',
            'title' => 'Member',
            'place' => 'Prakerja',
            'period' => 'Oct 2022 - Jan 2023 · Seasonal',
            'description' => 'Penerima manfaat Program Kartu Prakerja untuk meningkatkan keterampilan kerja.',
            'points' => [
                'Mengikuti pelatihan online di bidang yang diminati melalui platform resmi.',
                'Memperoleh sertifikasi pelatihan dan menambah pengetahuan yang relevan.',
            ],
        ],
        [
            'type' => 'volunteer',
            'title' => 'Participant, Our APBN Class',
            'place' => 'Kementerian Keuangan Republik Indonesia',
            'period' => 'Jul 2021',
            'description' => 'Kegiatan edukasi mengenai Anggaran Pendapatan dan Belanja Negara (APBN) untuk pelajar dan mahasiswa.',
            'points' => [
                'Menyebarkan informasi mengenai APBN kepada masyarakat, khususnya pelajar dan mahasiswa.',
                'Mengikuti workshop, diskusi, dan seminar bersama ahli ekonomi mengenai APBN.',
                'Kampanye edukasi publik melalui media sosial dan kegiatan lapangan.',
            ],
        ],
        [
            'type' => 'work',
            'title' => 'Member',
            'place' => 'mentorluluskampus',
            'period' => 'May 2021 - Sep 2022 · Seasonal',
            'description' => 'Program mentoring pengembangan diri dan persiapan kuliah.',
            'points' => [
                'Mengembangkan keterampilan profesional dan pribadi melalui interaksi dengan mentor.',
                'Mengikuti kursus dan menyelesaikan tugas sesuai tenggat waktu.',
            ],
        ],
        [
            'type' => 'work',
            'title' => 'Accountant',
            'place' => 'LPA Mitrabijak Surakarta',
            'period' => 'Feb 2021 - Mar 2021 · Internship',
            'description' => 'Magang akuntansi secara remote.',
            'points' => [
                'Menganalisis dan menyusun laporan keuangan bulanan secara online.',
                'Menyelesaikan tugas administrasi umum dan pelaporan pajak bulanan.',
                'Mempresentasikan tugas akhir magang dalam bentuk video simulasi.',
            ],
        ],
        [
            'type' => 'education',
            'title' => 'Akuntansi dan Keuangan Lembaga',
            'place' => 'SMK Negeri 1 Indramayu',
            'period' => 'Aug 2019 - Jun 2022',
            'description' => 'Jurusan Akuntansi dan Keuangan Lembaga.',
            'points' => [],
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
            'title' => 'Dasar dan Penggunaan Generatif AI',
            'issuer' => 'CODEPOLITAN',
            'date' => 'Apr 2026',
            'expires' => 'Apr 2031',
            'category' => 'AI',
            'credential_id' => 'CPRAI-CR/2026/IV/2216',
            'description' => 'Kelas Dasar dan Penggunaan Generatif AI (15 JP: live session + online course). Bagian dari program AI Opportunity Fund: Asia Pasifik, berkolaborasi dengan AVPN dan didukung Google.org serta Asian Development Bank.',
            'skills' => ['Generative AI', 'Prompt Engineering', 'Responsible AI', 'Produktivitas dengan Alat AI'],
            'image' => '/images/certificates/codepolitan-generatif-ai.jpg',
            'file' => '/files/certificates/codepolitan-generatif-ai.pdf',
        ],
        [
            'title' => 'Belajar Dasar AI',
            'issuer' => 'Dicoding Indonesia',
            'date' => 'Oct 2025',
            'expires' => 'Oct 2028',
            'category' => 'AI',
            'credential_id' => '98XWO54D9ZM3',
            'description' => 'Kelas dasar AI untuk pemula (10 jam): konsep dasar AI, data untuk AI, pengantar Machine Learning, dan Deep Learning, diakhiri ujian akhir kelas.',
            'skills' => ['Artificial Intelligence (AI)', 'Data untuk AI', 'Machine Learning', 'Deep Learning'],
            'image' => '/images/certificates/dicoding-belajar-dasar-ai.jpg',
            'file' => '/files/certificates/dicoding-belajar-dasar-ai.pdf',
        ],
        [
            'title' => 'Junior Web Developer',
            'issuer' => 'Badan Nasional Sertifikasi Profesi (BNSP)',
            'date' => 'Sep 2024',
            'expires' => 'Sep 2027',
            'category' => 'Web Development',
            'credential_id' => 'No. 62090 2513 3 0108289 2024',
            'description' => 'Sertifikat kompetensi bidang Pengembangan Website dengan kualifikasi Junior Web Developer, diterbitkan LSP Teknologi Digital atas nama BNSP (No. Reg. TIK 1565 30800 2024). Mencakup 6 unit kompetensi.',
            'skills' => ['Pemrograman Terstruktur', 'User Interface', 'Library & Komponen Pre-existing', 'Perintah Eksekusi Bahasa Pemrograman', 'Organisasi Kode & Fungsi', 'Guidelines & Best Practices'],
            'image' => '/images/certificates/bnsp-junior-web-developer.jpg',
            'file' => '/files/certificates/bnsp-junior-web-developer.pdf',
        ],
        [
            'title' => 'Junior Web Developer',
            'issuer' => 'Kominfo',
            'date' => 'Aug 2024',
            'category' => 'Web Development',
            'credential_id' => '19393121140-19/VSGA/BLSDM.Kominfo/2024',
            'description' => 'Pelatihan Junior Web Developer program VSGA dari BLSDM Kominfo.',
            'skills' => ['MySQL', 'User Interface Design'],
            'image' => '/images/certificates/kominfo-junior-web-developer.jpg',
            'file' => '/files/certificates/kominfo-junior-web-developer.pdf',
        ],
        [
            'title' => 'Peserta Wilayah - Kontes Robot Terbang Indonesia (KRTI) 2024',
            'issuer' => 'Balai Pengembangan Talenta Indonesia (Puspresnas, Kemendikbudristek)',
            'date' => 'Aug 2024',
            'category' => 'Award',
            'credential_id' => '22960/BPTI/DIKTI/2024',
            'description' => 'Penghargaan sebagai Peserta Wilayah divisi Racing Plane pada Kontes Robot Terbang Indonesia (KRTI) Wilayah 2024, mewakili Politeknik Negeri Indramayu. Diselenggarakan secara daring bekerja sama dengan Universitas Negeri Yogyakarta, 12-17 Agustus 2024.',
            'skills' => ['Robotika', 'Racing Plane'],
            'image' => '/images/certificates/krti-2024-racing-plane.jpg',
            'file' => null,
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
