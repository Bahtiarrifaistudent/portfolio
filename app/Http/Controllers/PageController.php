<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

/**
 * All portfolio pages. Data comes from config/portfolio.php.
 * Profile data is shared with every page via HandleInertiaRequests.
 */
class PageController extends Controller
{
    /**
     * All projects from config. If 'image' is empty, an image is looked up in
     * public/images/projects/{slug}.(webp|jpg|jpeg|png).
     */
    private function allProjects(): Collection
    {
        return collect(config('portfolio.projects'))->map(function (array $project) {
            $project['screenshots'] = $this->screenshots($project);

            // Second image on the cover: 'cover_second' => 'remote' picks the screenshot whose
            // caption or file name contains that word (otherwise the next screenshot is used)
            $project['cover_second_src'] = null;
            if (! empty($project['cover_second'])) {
                foreach ($project['screenshots'] as $shot) {
                    if (Str::contains(Str::lower($shot['caption'].' '.basename($shot['src'])), Str::lower($project['cover_second']))) {
                        $project['cover_second_src'] = $shot['src'];
                        break;
                    }
                }
            }

            // No cover image set: use {slug}.(webp|jpg|...) or else the first screenshot
            if (empty($project['image']) && ! empty($project['screenshots']) && ! $this->coverFile($project['slug'])) {
                $project['image'] = $project['screenshots'][0]['src'];
            }

            if (empty($project['image'])) {
                foreach (['webp', 'jpg', 'jpeg', 'png'] as $ext) {
                    if (file_exists(public_path("images/projects/{$project['slug']}.{$ext}"))) {
                        $project['image'] = "/images/projects/{$project['slug']}.{$ext}";
                        break;
                    }
                }
            }

            return $project;
        })->values();
    }

    private function coverFile(string $slug): bool
    {
        foreach (['webp', 'jpg', 'jpeg', 'png'] as $ext) {
            if (file_exists(public_path("images/projects/{$slug}.{$ext}"))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Project screenshots for the gallery.
     * - From config: 'screenshots' => ['/images/...jpg', ...] or [['src' => ..., 'caption' => ..., 'type' => 'mobile'], ...]
     * - Otherwise every image in public/images/projects/{slug}/ (sorted by file name).
     *   The caption comes from the file name: "02-remote-desktop.jpg" becomes "Remote desktop".
     *   Add "-mobile" to the name for portrait phone screenshots, e.g. "07-agent-mobile.jpg".
     *
     * @return array<int, array{src: string, caption: string, type: string}>
     */
    private function screenshots(array $project): array
    {
        $caption = fn (string $file) => Str::ucfirst(trim(preg_replace(['/^(\d+[-_ ]*)+/', '/[-_ ]*mobile$/i', '/[-_]+/'], ['', '', ' '], pathinfo($file, PATHINFO_FILENAME))));

        // Width / height of an image in public/ (used so the gallery keeps the real screenshot shape)
        $ratio = function (string $src): float {
            $size = @getimagesize(public_path(ltrim($src, '/')));

            return $size && $size[1] ? round($size[0] / $size[1], 4) : 1.6;
        };

        if (! empty($project['screenshots'])) {
            return collect($project['screenshots'])->map(fn ($s) => is_string($s)
                ? ['src' => $s, 'caption' => $caption($s), 'type' => Str::contains(Str::lower($s), 'mobile') ? 'mobile' : 'desktop', 'ratio' => $ratio($s)]
                : ['src' => $s['src'], 'caption' => $s['caption'] ?? $caption($s['src']), 'type' => $s['type'] ?? 'desktop', 'ratio' => $ratio($s['src'])])->values()->all();
        }

        $dir = public_path("images/projects/{$project['slug']}");
        if (! is_dir($dir)) {
            return [];
        }

        // Optional captions.json in the same folder: {"01-dashboard.jpg": "Dashboard", ...}
        $captions = is_file("{$dir}/captions.json") ? (json_decode(file_get_contents("{$dir}/captions.json"), true) ?: []) : [];

        return collect(scandir($dir))
            ->filter(fn ($f) => $f[0] !== '.' && preg_match('/\.(webp|jpe?g|png)$/i', $f))
            ->sort(SORT_NATURAL)
            ->map(fn ($f) => [
                'src' => "/images/projects/{$project['slug']}/{$f}",
                'caption' => $captions[$f] ?? $caption($f),
                'type' => preg_match('/mobile\.[a-z]+$/i', $f) ? 'mobile' : 'desktop',
                'ratio' => $ratio("/images/projects/{$project['slug']}/{$f}"),
            ])
            ->values()
            ->all();
    }

    public function home(): Response
    {
        return Inertia::render('Home', [
            'stats' => config('portfolio.stats'),
            'projects' => $this->allProjects(),
            'stackPreview' => collect(config('portfolio.stack'))
                ->flatten(1)
                ->where('level', 'core')
                ->values(),
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('About', [
            'architecture' => config('portfolio.architecture'),
            'stack' => config('portfolio.stack'),
            'journey' => config('portfolio.journey'),
        ]);
    }

    public function projects(): Response
    {
        return Inertia::render('Projects/Index', [
            'projects' => $this->allProjects(),
        ]);
    }

    public function project(string $slug): Response
    {
        $projects = $this->allProjects();
        $index = $projects->search(fn ($p) => $p['slug'] === $slug);

        abort_if($index === false, 404);

        return Inertia::render('Projects/Show', [
            'project' => $projects[$index],
            'prev' => $index > 0 ? Arr::only($projects[$index - 1], ['slug', 'title']) : null,
            'next' => $index < $projects->count() - 1 ? Arr::only($projects[$index + 1], ['slug', 'title']) : null,
        ]);
    }

    public function experience(): Response
    {
        return Inertia::render('Experience', [
            'experience' => config('portfolio.experience'),
        ]);
    }

    public function certificates(): Response
    {
        return Inertia::render('Certificates', [
            'certificates' => config('portfolio.certificates'),
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('Contact');
    }
}
