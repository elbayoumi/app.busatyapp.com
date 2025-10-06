<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class HttpResponseException extends Exception
{
    protected $errors;
    protected $status;

    public function __construct($errors,$status=500)
    {
        $this->errors = $errors;
        $this->status = $status;
        parent::__construct('Validation failed');
    }

    public function render($request): JsonResponse
    {
        Log::error('Failed render: ' . $this->errors);

        return response()->json([
            'errors' => true,
            'messages' => $this->errors
        ], $this->status);
    }
}
