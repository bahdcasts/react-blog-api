<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function sendResponse($status = 'success', $data, $message = '', $statusCode = 200) {
        return response()->json([
            'status' => $status,
            'data' => $data, 
            'message' => $message
        ], $statusCode);
    }
}
