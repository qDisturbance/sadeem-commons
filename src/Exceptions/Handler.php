<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
    'password',
    'password_confirmation',
  ];

  /**
   * Report or log an exception.
   *
   * @param Throwable $exception
   * @return void
   */
  public function report(Throwable $exception)
  {
    parent::report($exception);
  }

  /**
   * Render an exception into an HTTP response.
   *
   * @param Request $request
   * @param Throwable $exception
   * @return Response
   */
  public function render($request, Throwable $exception)
  {
    if ($exception instanceof QueryException) {

      return response([
        'code' => 3007,
        'msg' => $exception->getMessage()
      ]);
    }

    if ($exception instanceof ModelNotFoundException) {

      return response([
        'code' => 3007,
        'msg' => $exception->getMessage()
      ]);
    }

    if ($exception instanceof UnauthorizedException) {
      return response([
        'code' => 3017,
        'msg' => 'User unauthorized'
      ]);
    }

    if ($exception instanceof ValidationException) {
      return response([
        "code" => 3024,
        'msg' => 'Input validation failed',
        'errors' => $exception->errors()
      ]);
    }

    return parent::render($request, $exception);
  }
}
