<?php

namespace Themes\SodaExample\Handlers;

use Exception;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionHandler extends Handler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    public function render($request, Exception $e)
    {
        // If the request wants JSON (AJAX doesn't always want JSON)
        if ($request->wantsJson()) {
            // Define the response
            $response = [
                'error' => 'Sorry, something went wrong.',
            ];

            // If the app is in debug mode
            if (config('app.debug')) {
                // Add the exception class name, message and stack trace to response
                $response['exception'] = get_class($e); // Reflection might be better here
                $response['message'] = $e->getMessage();
                $response['trace'] = $e->getTrace();
            }

            // Default response of 400
            $status = 400;

            if ($this->isTokenMismatchException($e)) {
                $status = 403;
            }

            // If this exception is an instance of HttpException
            if ($this->isHttpException($e)) {
                // Grab the HTTP status code from the Exception
                $status = $e->getStatusCode();
            }

            // Return a JSON response with the response array and status code
            return response()->json($response, $status);
        } else {
            if ($this->isHttpException($e)) {
                return $this->renderHttpException($e);
            } elseif ($this->isTokenMismatchException($e)) {
                return $this->renderTokenMismatchException($e);
            } else {
                if (! config('app.debug')) {
                    abort(500);
                } // Return our http error page in all cases, if possible
            }
        }

        return parent::render($request, $e);
    }

    /**
     * Render the given HttpException.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpException $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpException $e)
    {
        $status = $e->getStatusCode();

        if (view()->exists("soda-example::errors.{$status}")) {
            return response()->view("soda-example::errors.{$status}", ['exception' => $e], $status, $e->getHeaders());
        } else {
            return response()->view('soda-example::errors.other', ['exception' => $e], $status, $e->getHeaders());
        }
    }

    /**
     * Render the given TokenMismatchException.
     *
     * @param  TokenMismatchException $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderTokenMismatchException(TokenMismatchException $e)
    {
        if (view()->exists('soda-example::errors.token')) {
            return response()->view('soda-example::errors.token', ['exception' => $e], '403');
        } else {
            return $this->convertExceptionToResponse($e);
        }
    }

    protected function isTokenMismatchException(Exception $e)
    {
        return $e instanceof TokenMismatchException;
    }
}
