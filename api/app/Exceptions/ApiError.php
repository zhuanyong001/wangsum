<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiError extends Exception
{
    protected $statusCode;
    protected $errorCode;
    protected $errorMessage;

    public function __construct($errorMessage = null, $errorCode = null, $statusCode = 400)
    {
        parent::__construct($errorMessage, $statusCode);

        $this->statusCode = $statusCode;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }

    public function render($request): JsonResponse
    {
        return response()->json([
            'code' => -1,
            'msg' => $this->errorMessage,
        ], $this->statusCode);
    }
}
