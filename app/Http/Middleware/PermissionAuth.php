<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class PermissionAuth
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, ...$guards)
	{
		$authed = false;
		if (empty($guards)) {
			$authed = Auth::check();
		} else {
			$authed = Auth::guard($guards[0])->check();
		}

		if (!$authed) {
			$status = config('status.AUTH_FORBIDDEN');
			return response([
				'code' => 'AUTH_FORBIDDEN',
				'msg' => $status['msg']
			], $status['code']);
		}

		return $next($request);
	}
}