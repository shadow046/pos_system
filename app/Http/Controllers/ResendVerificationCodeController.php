<?php

namespace App\Http\Controllers;

use App\Actions\SendVerificationCode;
use Illuminate\Http\JsonResponse;

class ResendVerificationCodeController extends Controller
{
    /**
     * Resend verification.
     * 
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        SendVerificationCode::run(auth()->user());

        return response()->json([
            'message' => 'Verification code has been sent.'
        ], 201);
    }
}
