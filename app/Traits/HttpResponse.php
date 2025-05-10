<?php

namespace App\Traits;

trait HttpResponse
{
    // Http response for success case
    public static function success($status,$data, $message, $code)
    {
        return response()->json([
            'status' => $status,
            'statusCode' => $code,
            'message' => $message,
            'data' => $data
        ],$code);
    }

    // Http response for fail case
    public static function fail($status, $data, $message, $code){
        return response()->json([
            'status' => $status,
            'statusCode' => $code,
            'message' => $message,
            'data' => $data
        ],$code);
    }
}
