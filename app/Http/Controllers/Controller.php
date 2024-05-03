<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($message, $code = 200, $data = [])
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['result'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendBadRequest($error, $errorMessages = [])
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['result'] = $errorMessages;
        }

        return response()->json($response, 400);
    }

    public function sendUnauthorized($message)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        return response()->json($response, 403);
    }

    public function sendNotFound($message)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        return response()->json($response, 404);
    }

    public function sendInternalError($message, $errorMessages = [])
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];
        if (!empty($errorMessages)) {
            $response['result'] = $errorMessages;
        }

        return response()->json($response, 500);
    }
}
