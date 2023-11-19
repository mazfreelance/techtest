<?php

namespace App\Exceptions;

use App\Support\Responder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\{
    MethodNotAllowedHttpException,
    NotFoundHttpException,
    ServiceUnavailableHttpException
};
use Throwable;

class Handler extends ExceptionHandler
{
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
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // return parent::render($request, $exception);
        if ($exception instanceof AuthenticationException) {
            return Responder::unauthorized();
        } else if ($exception instanceof AuthorizationException) {
            return Responder::forbiddenAction();
        } else if ($exception instanceof ModelNotFoundException) {
            return Responder::notFound();
        } else if ($exception instanceof ValidationException) {
            return Responder::inputError($exception->errors());
        } else if ($exception instanceof BadRequestException) {
            return Responder::error($exception->getMessage());
        } else if ($exception instanceof ThrottleRequestsException) {
            return Responder::tooManyAttempts();
        } else if ($exception instanceof ServiceUnavailableHttpException) {
            return Responder::serverBusy();
        } else if ($exception instanceof MethodNotAllowedHttpException) {
            return Responder::methodNotAllowed();
        } else if ($exception instanceof GeneralHttpException) {
            $errorNo = ($exception->getCode() === 2) ? $exception->getCode() : 1;
            return Responder::error($exception->getMessage(), $exception->getStatusCode(), $errorNo);
        } else if ($exception instanceof NotFoundHttpException) {
            return Responder::notFound(__('Route Not Found'));
        } else {
            return Responder::serverError($exception->getMessage());
        }
    }
}
