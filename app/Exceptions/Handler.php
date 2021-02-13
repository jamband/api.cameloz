<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * @param Request $request
     * @param Throwable $e
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        if ($e instanceof ModelNotFoundException) {
            $data['message'] = 'Model Not Found';
            return response($data, Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof NotFoundHttpException) {
            $data['message'] = 'Not Found';
            return response($data, $e->getStatusCode());
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            $data['message'] = 'Method Not Allowed';
            return response($data, $e->getStatusCode());
        }

        if (
            $e instanceof HttpException &&
            Response::HTTP_SERVICE_UNAVAILABLE === $e->getStatusCode()
        ) {
            $data['message'] = $e->getMessage();
            $headers['Retry-After'] = Config::get('retry_after');
            return response($data, $e->getStatusCode(), $headers);
        }

        if ($e instanceof HttpException) {
            $data['message'] = $e->getMessage();
            return response($data, $e->getStatusCode());
        }

        return parent::render($request, $e);
    }
}
