<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Handler extends ExceptionHandler
{
    public $incidentCode;

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
     * @param \Throwable $e
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $e)
    {
        if (App::environment() != 'testing') {
            $exceptionClass = get_class($e);

            switch ($exceptionClass) {
                case AuthenticationException::class:
                case AuthorizationException::class:
                    Log::warning($e->getMessage(), [
                        'incident_code' => Str::uuid()->toString(),
                        'context' => ['url' => url()->current()]
                    ]);
                    break;
                default:
                    Log::emergency($e->getMessage(), [
                            'incident_code' => Str::uuid()->toString(),
                            'context' => $e->getTrace()
                        ]
                    );
                    break;
            }
        }
    }

}
