<?php

namespace Inaam\Installer\Middleware;

use Closure;
use Inaam\Installer\Repositories\ApplicationStatusRepositoryInterface;

class ApplicationStatus
{
    public function handle($request, Closure $next)
    {
        return app(ApplicationStatusRepositoryInterface::class)->next($request, $next);
    }
}
