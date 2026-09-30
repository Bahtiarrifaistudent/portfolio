<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Semua halaman portfolio. Data diambil dari config/portfolio.php.
 * Data profil dikirim ke semua halaman lewat HandleInertiaRequests.
 */
class PageController extends Controller
{
    public function home(): Response
    {
        $projects = collect(config('portfolio.projects'));

        return Inertia::render('Home', [
            'stats' => config('portfolio.stats'),
            'featured' => $projects->firstWhere('featured', true),
            'latestProjects' => $projects->where('featured', false)->take(3)->values(),
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
            'projects' => config('portfolio.projects'),
        ]);
    }

    public function project(string $slug): Response
    {
        $projects = collect(config('portfolio.projects'))->values();
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
