<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        'senha'
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {

        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof UnauthorizedException) {
            return response()->json(
                [
                    'message' => $e->getMessage()
                ],
                401
            );
        }

        if ($e instanceof \InvalidArgumentException) {
            return response()->json(
                [
                    'message' => $e->getMessage()
                ],
                400
            );
        }


        return response()->json(
            [
                'message' => "Ocorreu um erro ao executar sua requisição."
            ],
            500
        );
        
    }

}
