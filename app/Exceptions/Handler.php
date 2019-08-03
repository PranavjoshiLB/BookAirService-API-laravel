<?php

namespace App\Exceptions;

use App\Model\Error;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
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
        ApiKeyException::class,
        MethodNotAllowedHttpException::class,
        AmadeusServiceException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     */
    /**
     * @param \Illuminate\Http\Request $request
     * @param Exception $e
     * @return \Illuminate\Http\Response|Response
     */
    public function render($request, Exception $e)
    {

        if (env('APP_DEBUG')) {
            //return parent::render($request, $e);
        }
        // Send error to sentry
        if ($this->shouldReport($e)) {
            $error = new Error();
            $error->user_id = Auth::id();
            $error->module = $e->getFile();
            $error->messsage = $e->getMessage();
            $error->status = "pending";
            $error->error_type ="PHP";
            $error->save();
        }
        // construct a friendly response
        $response = null;
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($e instanceof ApiKeyException) {
            return $e->getResponse();
        } elseif ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof AmadeusServiceException) {
            $status = Response::HTTP_EXPECTATION_FAILED;
            $response = $e->errors();
        } elseif ($e instanceof ValidationException) {
            $status = Response::HTTP_UNPROCESSABLE_ENTITY;
            $response = $e->errors();
        } elseif ($e instanceof ModelNotFoundException) {
            $status = Response::HTTP_NOT_FOUND;
            $response = $e->getMessage();
        } elseif ($e instanceof AuthorizationException) {
            $status = Response::HTTP_UNAUTHORIZED;
            $response = $e->getMessage();
        } elseif ($e instanceof HttpResponseException) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response = $e->getMessage();
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            $status = Response::HTTP_METHOD_NOT_ALLOWED;
            $e = new MethodNotAllowedHttpException([], 'HTTP_METHOD_NOT_ALLOWED', $e);
            $response = $e->getMessage();
        } elseif ($e instanceof NotFoundHttpException) {
            $status = Response::HTTP_NOT_FOUND;
            $e = new NotFoundHttpException('HTTP_NOT_FOUND', $e);
            $response = $e->getMessage();
        } elseif ($e instanceof AuthorizationException) {
            $status = Response::HTTP_FORBIDDEN;
            $e = new AuthorizationException('HTTP_FORBIDDEN', $status);
            $response = $e->getMessage();
        } elseif ($e instanceof UnauthorizedHttpException) {
            $status = Response::HTTP_UNAUTHORIZED;
            $response = $e->getMessage();
        } elseif ($e instanceof UnauthorizedException) {
            $status = Response::HTTP_UNAUTHORIZED;
            $response = $e->getMessage();
        } elseif ($e instanceof \Dotenv\Exception\ValidationException && $e->getResponse()) {
            $status = Response::HTTP_BAD_REQUEST;
            $e = new \Dotenv\Exception\ValidationException('HTTP_BAD_REQUEST', $status, $e);
            $response = $e->getMessage();
        } elseif ($e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $e = new HttpException($status, 'HTTP_INTERNAL_SERVER_ERROR');
            $response = $e->getMessage();
        }

        return response()->json([
            'msg' => 'Application Error',
            'statusCode' => $status,
            'status' => false,
            'errors' => $response,
        ], $status);
    }
}
