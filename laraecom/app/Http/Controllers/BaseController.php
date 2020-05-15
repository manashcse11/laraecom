<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    public function prepareMessage($key, $resource='Resource'){
        $message['index'] = sprintf("%s retrieved successfully.", $resource);
        $message['store'] = sprintf("%s created successfully.", $resource);
        $message['show'] = sprintf("%s retrieved successfully.", $resource);
        $message['update'] = sprintf("%s updated successfully.", $resource);
        $message['destroy'] = sprintf("%s deleted successfully.", $resource);
        return isset($message[$key]) ? $message[$key] : "";
    }
}
