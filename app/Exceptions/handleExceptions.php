<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Illuminate\Validation\ValidationException;

function handleExceptions(Exceptions $exceptions)
{
    return $exceptions->renderable(function (NotFoundHttpException $e, $request) {
        return error_response('The requested URL does not match any valid resource.', null, Response::HTTP_NOT_FOUND);
    })->renderable(function (NotFoundResourceException $e, $request) {
        return error_response($e->getMessage(), null, Response::HTTP_NOT_FOUND);
    })->renderable(function (MethodNotAllowedHttpException $e, $request) {
        return error_response('The HTTP method used is not allowed for this resource.', null, Response::HTTP_METHOD_NOT_ALLOWED);
    })->renderable(function (ValidationException $e, $request) {
        return error_response($e->getMessage(), $e->errors(), Response::HTTP_BAD_REQUEST);
    })->renderable(function (HttpException $e, $request) {
        return error_response($e->getMessage(), null, $e->getStatusCode());
    })->renderable(function (AuthenticationException $e, $request) {
        return error_response($e->getMessage(), null, Response::HTTP_UNAUTHORIZED);
    })->renderable(function (Exception $e, $request) {
        return error_response($e, null, Response::HTTP_INTERNAL_SERVER_ERROR);
    });
}
