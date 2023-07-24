<?php

namespace Modules\Api\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Modules\Api\Traits\ApiResponseTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler extends ExceptionHandler
{
    use ApiResponseTrait;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  Exception|Throwable $e
     * @return JsonResponse
     */
    public function render($request, Exception|Throwable $e): JsonResponse
    {
        return match (true) {
            $e instanceof ValidationException     => $this->errorResponse($e->validator->getMessageBag()->toArray(),$e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY),
            $e instanceof NotFoundHttpException   => $this->processParseError($e->getMessage(), $e, Response::HTTP_NOT_FOUND),
            $e instanceof AuthorizationException  => $this->processParseError($e->getMessage(), $e, Response::HTTP_FORBIDDEN),
            $e instanceof AuthenticationException => $this->processParseError($e->getMessage(), $e, Response::HTTP_UNAUTHORIZED),
            $e instanceof QueryException          => $this->processParseError($e->getMessage(), $e),

            default => $this->processParseError($e->getMessage(), $e),
        };
    }
}
