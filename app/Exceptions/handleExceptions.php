<?php

namespace App\Exceptions;

use App\Helpers\TraceIdHelper;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Illuminate\Validation\ValidationException;
use Throwable;

function handleExceptions(Exceptions $exceptions): Exceptions
{
    return $exceptions->renderable(function (NotFoundHttpException $e) {
        $previous = $e->getPrevious();
        if ($previous instanceof ModelNotFoundException) {
            $modelName = class_basename($previous->getModel());
            return error_response(
                "{$modelName} not found",
                null,
                Response::HTTP_NOT_FOUND
            );
        }

        return error_response('The requested URL does not match any valid resource.', null, Response::HTTP_NOT_FOUND);
    })->renderable(function (NotFoundResourceException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_NOT_FOUND);
    })->renderable(function (MethodNotAllowedHttpException $e) {
        return error_response('The HTTP method used is not allowed for this resource.', null, Response::HTTP_METHOD_NOT_ALLOWED);
    })->renderable(function (ValidationException $e) {
        return error_response($e->getMessage(), $e->errors(), Response::HTTP_BAD_REQUEST);
    })->renderable(function (HttpException $e) {
        return error_response($e->getMessage(), null, $e->getStatusCode());
    })->renderable(function (AuthenticationException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_UNAUTHORIZED);
    })->renderable(function (UnauthorizedException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_UNAUTHORIZED);
    })->renderable(function (ModelNotFoundException $e) {
        return error_response($e->getMessage() . " a " . $e->getModel(), null, Response::HTTP_NOT_FOUND);
    })->renderable(function (ReservationCanceledException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_CONFLICT);
    })->renderable(function (Throwable $e) {
        $messageWithTraceId = "";
        $traceId = TraceIdHelper::get();

        if (config('app.env') === 'production') {
            Log::channel('slack-error')->critical($e->getMessage(), [
                'trace_id' => $traceId,
                'exception' => (string)$e,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => optional(auth()->user())->id,
                'url' => request()->fullUrl(),
                'ip' => request()->ip()
            ]);

            $messageWithTraceId = " trace_id= " . $traceId;
        }

        return error_response("An unknown error occurred! Please contact support." . $messageWithTraceId, null, Response::HTTP_INTERNAL_SERVER_ERROR);
    });
}
