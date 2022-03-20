<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    /**
     * Error detail.
     *
     * @var mixed
     */
    protected $detail;

    public function __construct($message, $detail = '', $code = 'system_error') {
        $this->message = $message;
        $this->detail = $detail;
        $this->code = $code;
    }

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        return true;
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'error' => [
                'code' => $this->code,
                'message' => $this->message,
                'detail' => $this->detail,
            ]
        ], 500);
    }
}
