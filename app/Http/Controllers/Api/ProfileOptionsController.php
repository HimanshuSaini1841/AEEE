<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DynamicFieldCacheService;
use Illuminate\Http\JsonResponse;

class ProfileOptionsController extends Controller
{
    /**
     * Get dynamic options for mobile app complete profile screen.
     * Classes, States, and Target Exams (Preparing For) managed by Admin.
     * Served from RAM Cache (Redis / memory) for sub-millisecond response.
     */
    public function index(): JsonResponse
    {
        $options = DynamicFieldCacheService::getActiveProfileOptions();

        return response()->json([
            'success' => true,
            'data' => $options,
        ]);
    }
}
