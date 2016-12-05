<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\TokenMismatchException;
use Log;

class VerifyCsrfToken extends BaseVerifier {

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
            //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next) {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $ex) {
            Log::error('csrf token mismatch.', ['url' => $request->fullUrl()]);
            if (!$request->ajax()) {
                throw $ex;
            } else {
                return response()->json([
                            'result' => 'no',
                            'msg' => trans('message.error.csrf_mismatch'),
                            'detail' => [],
                            'data' => [],
                ]);
            }
        }
    }

}
