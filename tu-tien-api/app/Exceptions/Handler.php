<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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

    /**
     * Trả lỗi khi request không đăng nhập (unauthenticated)
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn chưa đăng nhập hoặc token không hợp lệ',
            ], 401);
        }

        return parent::unauthenticated($request, $exception);
    }

    /**
     * Trả lỗi khi validate thất bại (422)
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'status' => false,
            'message' => 'Dữ liệu không hợp lệ',
            'errors' => $exception->errors(),
        ], 422);
    }

    /**
     * Ghi đè toàn bộ render lỗi để trả JSON cho tất cả exception
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);
        }

        return parent::render($request, $exception);
    }
}