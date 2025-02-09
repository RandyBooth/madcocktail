<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Jrean\UserVerification\Exceptions\UserNotVerifiedException;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Token Mismatch Exception'], 401);
            }

            return redirect()->back()->withInput()->with('danger', 'Your session was expired. Please try again.');
        }

        if ($exception instanceof UserNotVerifiedException) {
            return redirect()->route('home')->with('danger', 'You received an email for confirming your registration. Please check your email. <a href="'.route('email-verification.resend').'">Click here</a> to try again.');
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->view('errors.404', [], 404);
        }

        if ($this->isHttpException($exception)) {
            if (! \Auth::check()) {
                return redirect()->route('login');
            }

            $statusCode = $exception->getStatusCode($exception);

            if (in_array($statusCode, array(403, 404, 500, 503))){
                return response()->view('errors.' . $statusCode, [], $statusCode);
            }
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        return redirect()->guest('login');
    }
}
