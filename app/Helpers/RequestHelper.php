<?php

use Illuminate\Database\Eloquent\Collection;

function json_response(array $data, int | null $code = 200)
{
    return response()->json((array) $data, $code);
}

function success_response($results, $message = null, int $code = 200)
{
    $response = [
        'success' => true,
        'results' => $results instanceof Collection || is_array($results) ? $results : [$results]
    ];

    if (!is_null($message)) {
        $response['message'] = $message;
    }

    return json_response($response, $code);
}

function error_response(string $message, array | null $errors = null, int | null $code = 500)
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