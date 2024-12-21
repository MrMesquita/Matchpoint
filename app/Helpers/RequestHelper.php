<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

function json_response(array $data, int $code = Response::HTTP_OK)
{
    return response()->json((array) $data, $code);
}

function success_response($results, $message = null, int $code = Response::HTTP_OK)
{
    $response = ['success' => true];
    
    if (!is_null($message)) {
        $response['message'] = $message;
    }

    if (!is_null($results)) {
        $response['results'] = $results instanceof Collection || is_array($results) ? $results : [$results];
    }


    return json_response($response, $code);
}

function error_response(string $message, array | null $errors = null, int | null $code = Response::HTTP_INTERNAL_SERVER_ERROR)
{
    $data = [
        'success' => false,
        'message' => $message,
    ];

    if (!is_null($errors)) {
        $data['errors'] = $errors;
    }

    return json_response($data, $code);
}