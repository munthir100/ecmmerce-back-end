<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return $this->handleException($exception, 404, class_basename($exception->getModel()) . ' not found', false);
        } elseif ($exception instanceof NotFoundHttpException) {
            return $this->handleException($exception, 404, 'Page not found', false);
        }elseif($exception instanceof MethodNotAllowedHttpException){
            return $this->handleException($exception,405,$exception->getMessage(),false);
        }

        return parent::render($request, $exception);
    }

    protected function handleException(Throwable $exception, int $statusCode, string $message, bool $success): JsonResponse
    {
        return new JsonResponse([
            'message' => $message,
            'success' => $success,
            'statuscode' => $statusCode,
        ]);
    }

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
