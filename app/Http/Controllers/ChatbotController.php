<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Simple keyword-based chat assistant.
 * Answers are built from config/portfolio.php, so they update automatically
 * whenever the portfolio content changes. Keywords cover English and Indonesian.
 */
class ChatbotController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:300'],
        ]);

        $text = Str::of($data['message'])->lower()->squish()->toString();

        return response()->json($this->reply($text));
    }

    /**
     * Intents: [keywords, method]. Order defines priority.
     *
     * @return array<int, array{0: array<int, string>, 1: string}>
     */
    private function intents(): array
    {
        return [
            [['halo', 'hai', 'hi', 'hello', 'hey', 'good morning', 'good afternoon', 'good evening', 'pagi', 'siang', 'sore', 'malam', 'assalamualaikum', 'permisi'], 'greeting'],
            [['terima kasih', 'makasih', 'thanks', 'thank you', 'thx', 'tengkyu'], 'thanks'],
            [['cv', 'resume', 'curriculum'], 'cv'],
            [['kontak', 'hubungi', 'email', 'e-mail', 'kirim pesan', 'contact', 'reach', 'get in touch', 'wa ', 'whatsapp'], 'contact'],
            [['magang', 'intern', 'freelance', 'kerja sama', 'kolaborasi', 'collaborat', 'hire', 'hiring', 'rekrut', 'lowongan', 'tersedia', 'available', 'open to', 'job'], 'availability'],
            [['sertifikat', 'sertifikasi', 'certificate', 'kursus', 'course'], 'certificates'],
            [['pengalaman', 'experience', 'organisasi', 'organization', 'riwayat', 'pendidikan', 'education', 'kuliah', 'work history'], 'experience'],
            [['monitoring', 'reverb', 'webrtc', 'geofenc', 'remote desktop', 'project utama', 'main project', 'featured', 'best project', 'unggulan', 'terbaik', 'paling bangga', 'proudest'], 'featured'],
            [['project', 'proyek', 'portofolio', 'portfolio', 'karya', 'aplikasi', 'app', 'built', 'work', 'bikin apa', 'buat apa'], 'projects'],
            [['backend', 'back-end', 'laravel', 'php', 'api', 'database', 'mysql', 'sanctum'], 'backend'],
            [['frontend', 'front-end', 'vue', 'inertia', 'tailwind', 'ui'], 'frontend'],
            [['skill', 'keahlian', 'stack', 'teknologi', 'technolog', 'bahasa pemrograman', 'language', 'tools', 'bisa apa', 'menguasai', 'good at'], 'skills'],
            [['security', 'keamanan', 'siber', 'cyber', 'brute force', 'ai', 'nlp', 'machine learning'], 'otherFields'],
            [['kampus', 'campus', 'university', 'college', 'kuliah di', 'polindra', 'politeknik', 'lokasi', 'location', 'tinggal', 'domisili', 'dimana', 'di mana', 'where', 'live'], 'location'],
            [['github', 'linkedin', 'sosial', 'social', 'sosmed', 'instagram'], 'socials'],
            [['siapa', 'who', 'tentang', 'about', 'introduce', 'kenalan', 'profil', 'profile', 'bahtiar', 'rifai'], 'about'],
        ];
    }

    /**
     * @return array{reply: string, links: array<int, array{label: string, url: string}>, suggestions: array<int, string>}
     */
    private function reply(string $text): array
    {
        // Questions about a specific project (match the project title)
        foreach (config('portfolio.projects') as $project) {
            $title = Str::lower($project['title']);
            $firstWord = Str::before($title, ' ');
            if (Str::contains($text, $title) || (strlen($firstWord) > 4 && Str::contains($text, $firstWord))) {
                return $this->project($project);
            }
        }

        foreach ($this->intents() as [$keywords, $method]) {
            foreach ($keywords as $keyword) {
                if ($this->matches($text, $keyword)) {
                    return $this->{$method}();
                }
            }
        }

        return $this->fallback();
    }

    /**
     * Short keywords (<= 3 letters, e.g. "ai", "hi", "ui") must stand alone
     * so they do not match inside other words such as "kasih" or "rifai".
     */
    private function matches(string $text, string $keyword): bool
    {
        return mb_strlen(trim($keyword)) <= 3
            ? (bool) preg_match('/\b'.preg_quote(trim($keyword), '/').'\b/u', $text)
            : Str::contains($text, $keyword);
    }

    private function answer(string $reply, array $links = [], ?array $suggestions = null): array
    {
        return [
            'reply' => $reply,
            'links' => $links,
            'suggestions' => $suggestions ?? ['What are the skills?', 'Show projects', 'How to get in touch'],
        ];
    }

    private function profile(string $key): mixed
    {
        return config("portfolio.profile.{$key}");
    }

    private function greeting(): array
    {
        return $this->answer(
            "Hi! I'm {$this->profile('name')}'s virtual assistant. I can tell you about skills, projects, experience, or how to get in touch. What would you like to know?",
            [],
            ["Who is {$this->profile('short_name')}?", 'What are the skills?', 'Show projects', 'Download CV'],
        );
    }

    private function thanks(): array
    {
        return $this->answer("You're welcome! If you have any other questions, just type them here.", [], ['How to get in touch', 'Show projects']);
    }

    private function about(): array
    {
        return $this->answer(
            "{$this->profile('name')} is a {$this->profile('role')} focused on {$this->profile('focus')}, studying at {$this->profile('campus')}. His biggest project is Monitoring App, a realtime device monitoring system built with Laravel and Vue.",
            [['label' => 'About page', 'url' => '/about']],
            ['What are the skills?', 'Main project', 'Experience'],
        );
    }

    private function skills(): array
    {
        $lines = collect(config('portfolio.stack'))
            ->map(fn ($items, $group) => "• {$group}: ".collect($items)->pluck('name')->join(', '))
            ->join("\n");

        return $this->answer(
            "Here is the tech stack:\n{$lines}",
            [['label' => 'See the full tech stack', 'url' => '/about']],
            ['Backend stack?', 'Frontend stack?', 'Show projects'],
        );
    }

    private function backend(): array
    {
        $backend = collect(config('portfolio.stack.Backend', []))->pluck('name')->join(', ');

        return $this->answer(
            "The backend is his main focus. Stack: {$backend}. In Monitoring App he built a REST API with Sanctum, realtime broadcasting with Laravel Reverb, and automatic OpenAPI documentation.",
            [['label' => 'Monitoring App details', 'url' => '/projects/monitoring-app']],
            ['Frontend stack?', 'Main project', 'Download CV'],
        );
    }

    private function frontend(): array
    {
        $frontend = collect(config('portfolio.stack.Frontend', []))->pluck('name')->join(', ');

        return $this->answer(
            "On the frontend he uses {$frontend}. Vue 3 with the Composition API is connected to Laravel through Inertia.js, so no separate API is needed for the UI. This website is built with the same stack.",
            [['label' => 'See the tech stack', 'url' => '/about']],
            ['Backend stack?', 'Show projects'],
        );
    }

    private function projects(): array
    {
        $list = collect(config('portfolio.projects'))
            ->map(fn ($p) => '• '.$p['title'].($p['featured'] ?? false ? ' (featured)' : ''))
            ->join("\n");

        return $this->answer(
            "Here are some of the projects:\n{$list}\n\nAsk about any title for a short summary.",
            [['label' => 'All projects', 'url' => '/projects']],
            ['Main project', 'Smart City Indramayu', 'RiceScanAI'],
        );
    }

    private function featured(): array
    {
        $featured = collect(config('portfolio.projects'))->firstWhere('featured', true);

        return $featured ? $this->project($featured) : $this->projects();
    }

    private function project(array $project): array
    {
        $reply = "{$project['title']}: {$project['summary']}";
        if (! empty($project['features'])) {
            $reply .= "\n\nKey features:\n".collect($project['features'])->take(4)->map(fn ($f) => "• {$f['title']}")->join("\n");
        }
        if (! empty($project['tags'])) {
            $reply .= "\n\nTech stack: ".implode(', ', $project['tags']).'.';
        }

        $links = [['label' => 'View project details', 'url' => "/projects/{$project['slug']}"]];
        if (! empty($project['url'])) {
            $links[] = ['label' => 'GitHub repository', 'url' => $project['url']];
        }

        return $this->answer($reply, $links, ['Other projects', 'What are the skills?', 'How to get in touch']);
    }

    private function experience(): array
    {
        $items = collect(config('portfolio.experience'))
            ->reject(fn ($e) => $e['example'] ?? false)
            ->map(fn ($e) => "• {$e['title']}, {$e['place']} ({$e['period']})")
            ->join("\n");

        return $this->answer(
            $items ? "{$this->profile('short_name')}'s experience:\n{$items}" : "{$this->profile('short_name')} is currently studying at {$this->profile('campus')}.",
            [['label' => 'Experience page', 'url' => '/experience']],
            ['Certificates', 'Show projects', 'Download CV'],
        );
    }

    private function certificates(): array
    {
        $items = collect(config('portfolio.certificates'))
            ->reject(fn ($c) => $c['example'] ?? false)
            ->map(fn ($c) => "• {$c['title']} - {$c['issuer']}")
            ->join("\n");

        return $this->answer(
            $items ? "Certificates:\n{$items}" : 'The certificate list is being updated. Please check the Certificates page.',
            [['label' => 'Certificates page', 'url' => '/certificates']],
            ['Experience', 'What are the skills?'],
        );
    }

    private function cv(): array
    {
        $cv = $this->profile('cv');

        return $cv
            ? $this->answer('Sure! You can download the CV with the link below, or the "Download CV" button at the top of the page.', [['label' => 'Download CV', 'url' => $cv, 'download' => true]], ['How to get in touch', 'Show projects'])
            : $this->answer('The CV has not been uploaded yet. For now, please get in touch by email.', [['label' => 'Send an email', 'url' => 'mailto:'.$this->profile('email')]]);
    }

    private function contact(): array
    {
        return $this->answer(
            "You can reach {$this->profile('short_name')} by email at {$this->profile('email')}, on LinkedIn, or through the form on the Contact page.",
            [
                ['label' => 'Contact page', 'url' => '/contact'],
                ['label' => 'Send an email', 'url' => 'mailto:'.$this->profile('email')],
            ],
            ['Open to internships?', 'Download CV'],
        );
    }

    private function availability(): array
    {
        $status = $this->profile('available')
            ? "Yes, {$this->profile('short_name')} is open to internships, freelance work, and collaboration, especially in web development with Laravel and Vue."
            : "{$this->profile('short_name')} is currently busy, but you are still welcome to get in touch.";

        return $this->answer($status, [['label' => 'Get in touch', 'url' => '/contact']], ['Download CV', 'What are the skills?']);
    }

    private function otherFields(): array
    {
        return $this->answer(
            'Beyond web development, he has worked on cyber security projects (simulating and mitigating brute force attacks with Python) and AI (an NLP assistant for Polindra and RiceScanAI).',
            [['label' => 'Show projects', 'url' => '/projects']],
            ['Simulasi Brute Force', 'Asisten NLP Polindra'],
        );
    }

    private function location(): array
    {
        return $this->answer(
            "{$this->profile('short_name')} studies at {$this->profile('campus')} and is based in {$this->profile('location')}.",
            [],
            ['Open to internships?', 'How to get in touch'],
        );
    }

    private function socials(): array
    {
        $links = collect($this->profile('socials'))->map(fn ($s) => ['label' => $s['label'], 'url' => $s['url']])->all();

        return $this->answer('Here are the accounts you can visit:', $links, ['Show projects', 'Download CV']);
    }

    private function fallback(): array
    {
        return $this->answer(
            "Sorry, I don't understand that question yet. Try asking about skills, projects, experience, certificates, the CV, or how to get in touch.",
            [['label' => 'Ask through the contact form', 'url' => '/contact']],
            ["Who is {$this->profile('short_name')}?", 'What are the skills?', 'Show projects', 'How to get in touch'],
        );
    }
}
