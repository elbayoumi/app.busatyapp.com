<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ErrorController extends Controller
{

    public function badRequest(): JsonResponse
    {
        return $this->respondWithError(400, 'Bad Request');
    }

    public function unauthorized(): JsonResponse
    {
        return $this->respondWithError(401, 'Unauthorized');
    }

    public function forbidden(): JsonResponse
    {
        return $this->respondWithError(403, 'Forbidden');
    }

    public function notFound(): JsonResponse
    {
        return $this->respondWithError(404, 'Not Found');
    }

    public function methodNotAllowed(): JsonResponse
    {
        return $this->respondWithError(405, 'Method Not Allowed');
    }

    public function conflict(): JsonResponse
    {
        return $this->respondWithError(409, 'Conflict');
    }

    public function unprocessableEntity(): JsonResponse
    {
        return $this->respondWithError(422, 'Unprocessable Entity');
    }

    public function serverError(): JsonResponse
    {
        return $this->respondWithError(500, 'Internal Server Error');
    }

    /**
     * Helper: build JSON response using translation.
     */
    private function respondWithError(int $statusCode, string $messageKey): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status' => $statusCode,
            'message_en' => $messageKey,
            'message_ar' => __($messageKey), // Laravel will read from resources/lang/ar.json
        ], $statusCode);
    }
}
