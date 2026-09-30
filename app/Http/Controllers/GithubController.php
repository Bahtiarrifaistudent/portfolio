<?php

namespace App\Http\Controllers;

use App\Services\GithubContributions;
use Illuminate\Http\JsonResponse;

class GithubController extends Controller
{
    public function contributions(GithubContributions $github): JsonResponse
    {
        $data = $github->get();

        return $data
            ? response()->json($data)->header('Cache-Control', 'public, max-age=1800')
            : response()->json(['message' => 'Contribution data could not be loaded.'], 503);
    }
}
