<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function successResponse(array $data = [], string $message = null, int $statusCode = 200)
    {
        $payload = [
            'status' => true,
            'data' => $data,
        ];

        if ($message !== null) {
            $payload['message'] = $message;
        }

        return response()->json($payload, $statusCode);
    }
}
