<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
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
     * Report or log an exception.
     *
     * @param Throwable $e
     * @return void
     *
     * @throws Exception|Throwable
     */
    public function report(Throwable $e)
    {
        Log::channel("error")->error($this->renderMessage($e));
    }

    private function renderMessage(Throwable $exception): string
    {
        $dataToRender = [];
        if (App::runningInConsole()) {
            $dataToRender[] = json_encode(data_get($_SERVER, 'argv', []));
        } else {

            $request = json_encode(request()->all());
            if (strlen($request) > 1000) {
                $request = substr($request, 0, 300);
            }
            $dataToRender[] = request()->getPathInfo();
            $dataToRender[] = $request;
            $dataToRender[] = request()->method();
        }

        $dataToRender = array_filter($dataToRender);

        /** stack trace should be last */
        $dataToRender[] = $exception->getMessage();
        $dataToRender[] = $exception->getTraceAsString();

        if($exception instanceof ValidationException){
            $dataToRender[] = json_encode($exception->errors());
        }

        return implode(PHP_EOL, $dataToRender) . PHP_EOL;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param Throwable $e
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        return $this->prepareJsonResponse($request, $e);
    }
}
