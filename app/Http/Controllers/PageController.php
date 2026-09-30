<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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
