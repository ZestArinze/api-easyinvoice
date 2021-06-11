<?php

namespace App\Exceptions;

use App\Utils\AppHttpUtils;
use App\Utils\ErrorUtils;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // handle all 404
        $this->renderable(function (NotFoundHttpException $e, $request) {
            return AppHttpUtils::appJsonResponse(false, Response::HTTP_NOT_FOUND, null, null, "404, Not Found!");
        });

        // handle validation exception
        $this->renderable(function (ValidationException $e, $request) {
            return AppHttpUtils::appJsonResponse(false, Response::HTTP_UNPROCESSABLE_ENTITY, null, ErrorUtils::formatErrorBlock($e), "Unprocessable entity.");
        });

        // handle all AuthorizationException
        $this->renderable(function (AuthorizationException $e, $request) {
            $res = AppHttpUtils::appJsonResponse(false, Response::HTTP_UNAUTHORIZED, null, null, "Unauthorized.");
        });

        // handle all AuthenticationException
        $this->renderable(function (AuthenticationException $e, $request) {
            $res = AppHttpUtils::appJsonResponse(false, Response::HTTP_UNAUTHORIZED, null, null, "Unauthorized.");
        });
    }
}
