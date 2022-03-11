<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

final class LogRequestMiddleware
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->logger->info(json_encode([
            'method'  => $request->getMethod(),
            'uri'     => $request->getPathInfo(),
            'body'    => $request->all(),
            'headers' => $request->headers->all(),
        ]), [
            'request_id' => Uuid::uuid4()->toString()
        ]);

        return $next($request);
    }
}
