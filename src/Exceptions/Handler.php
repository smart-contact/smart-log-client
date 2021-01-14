<?php
namespace SmartContact\SmartLogClient\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
     * @param Throwable  $e
     * @return void
     *
     * @throws Throwable
     */
    public function report(Throwable $e)
    {
        if (App::environment() != 'testing') {
            $this->incidentCode = Str::uuid()->toString();
            $exceptionClass = get_class($e);

            switch ($exceptionClass) {
                case AuthenticationException::class:
                case AuthorizationException::class:
                    Log::warning($e->getMessage(), [
                        'incident_code' => $this->incidentCode,
                        'context' => ['url' => url()->current()]
                    ]);
                    break;
                case NotFoundHttpException::class:
                    $message = 'HTTP 404 Not Found';

                    Log::notice($message,[
                        'incident_code' => $this->incidentCode,
                        'context' => [ 'Request URI' => request()->getRequestUri()]
                    ]);
                    break;
                default:
                    Log::emergency($e->getMessage(), [
                            'incident_code' => $this->incidentCode,
                            'context' => $e->getTrace()
                        ]
                    );
                    break;
            }
        }
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
