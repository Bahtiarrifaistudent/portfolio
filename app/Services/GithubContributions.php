<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Fetches the full GitHub contribution history (all years) and summarizes it.
 *
 * Data source: https://github-contributions-api.jogruber.de (public, no token),
 * which reads the contribution graph on the GitHub profile.
 * Results are cached for 6 hours to keep the site fast.
 */
class GithubContributions
{
    private const CACHE_HOURS = 6;

    public function username(): ?string
    {
        $github = collect(config('portfolio.profile.socials', []))->firstWhere('icon', 'github');

        return $github ? trim((string) parse_url($github['url'], PHP_URL_PATH), '/') ?: null : null;
    }

    public function get(): ?array
    {
        $username = $this->username();
        if (! $username) {
            return null;
        }

        $key = "github-contributions:{$username}";
        if ($cached = Cache::get($key)) {
            return $cached;
        }

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->get("https://github-contributions-api.jogruber.de/v4/{$username}", ['y' => 'all']);

            if (! $response->successful() || ! is_array($response->json('contributions'))) {
                return null;
            }

            $data = $this->summarize($username, $response->json());
            Cache::put($key, $data, now()->addHours(self::CACHE_HOURS));

            return $data;
        } catch (Throwable $e) {
            Log::warning('Failed to fetch GitHub contributions: '.$e->getMessage());

            return null;
        }
    }

    private function summarize(string $username, array $raw): array
    {
        $today = CarbonImmutable::today()->toDateString();

        // Sort by date & drop future dates (the API fills up to Dec 31)
        $days = collect($raw['contributions'])
            ->filter(fn ($d) => $d['date'] <= $today)
            ->sortBy('date')
            ->values();

        $firstActive = $days->firstWhere('count', '>', 0);
        $years = collect($raw['total'] ?? [])
            ->map(fn ($total, $year) => ['year' => (int) $year, 'total' => (int) $total])
            ->filter(fn ($y) => ! $firstActive || $y['year'] >= (int) substr($firstActive['date'], 0, 4))
            ->sortBy('year')
            ->values();

        // Streak
        $longest = 0;
        $run = 0;
        foreach ($days as $d) {
            $run = $d['count'] > 0 ? $run + 1 : 0;
            $longest = max($longest, $run);
        }
        $current = 0;
        foreach ($days->reverse()->values() as $i => $d) {
            if ($d['count'] > 0) {
                $current++;
            } elseif ($i > 0) { // today may be empty; the streak counts from yesterday
                break;
            }
        }

        $best = $days->sortByDesc('count')->first();

        return [
            'username' => $username,
            'url' => "https://github.com/{$username}",
            'total' => (int) $years->sum('total'),
            'years' => $years->all(),
            'days' => $days->map(fn ($d) => [$d['date'], (int) $d['count'], (int) $d['level']])->all(),
            'stats' => [
                'active_days' => $days->where('count', '>', 0)->count(),
                'longest_streak' => $longest,
                'current_streak' => $current,
                'best_day' => $best && $best['count'] > 0 ? ['date' => $best['date'], 'count' => (int) $best['count']] : null,
                'since' => $firstActive['date'] ?? null,
            ],
            'updated_at' => now()->toIso8601String(),
        ];
    }
}
