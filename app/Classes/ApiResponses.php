<?php

namespace App\Classes;

use Exception;

class ApiResponses
{
    /**
     * Create a new class instance.
     */
    public static function sendSuccessResponse($message,  $success = true, $data){
        return response()->json([
            "success" => $success,
            "message" => $message,
            "data" => $data
        ], 200);
    }

    public static function sendErrorResponse($message = "Request failed", Exception $exception){
        $status = $exception->getCode();
        if ($status < 100 || $status > 599) {
            $status = 500;
        }

        return response()->json([
            "success" => false,
            "message" => $message,
            "exception" => $exception->getMessage(),
            "data" => null
        ], $status);
    }
}
