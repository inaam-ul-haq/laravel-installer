<?php

namespace Inaam\Installer\Middleware;

use App\Repositories\Contracts\ExtensionRepositoryInterface;
use Closure;

class ApplicationCheckLicense
{
    public function handle($request, Closure $next)
    {
        return $next($request);

        // TODO: check license 6.4 version
        // return app(ExtensionRepositoryInterface::class)->check($request, $next);
    }
}
