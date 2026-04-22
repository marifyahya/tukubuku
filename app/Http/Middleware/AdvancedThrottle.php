<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AdvancedThrottle
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle($request, Closure $next)
    {
        // Get a unique key based on user ID or IP
        $key = $this->resolveKey($request);

        // === RULE 1: 1000 requests per minute ===
        $perMinuteLimit = 1000;
        $perMinuteWindow = 60; // 60 seconds

        // === RULE 2: 20 requests per second (Spike Arrest) ===
        $perSecondLimit = 20;
        $perSecondWindow = 1; // 1 second

        // Check per-second rule first (more restrictive)
        $secondKey = $key . ':second';
        if ($this->limiter->tooManyAttempts($secondKey, $perSecondLimit)) {
            return $this->buildTooManyAttemptsResponse(
                $this->limiter->availableIn($secondKey),
                $perSecondLimit,
                'per_second'
            );
        }

        // Check per-minute rule
        $minuteKey = $key . ':minute';
        if ($this->limiter->tooManyAttempts($minuteKey, $perMinuteLimit)) {
            return $this->buildTooManyAttemptsResponse(
                $this->limiter->availableIn($minuteKey),
                $perMinuteLimit,
                'per_minute'
            );
        }

        // Increment both counters
        $this->limiter->hit($secondKey, $perSecondWindow);
        $this->limiter->hit($minuteKey, $perMinuteWindow);

        $response = $next($request);

        // Add rate limit headers
        return $this->addHeaders($response, $minuteKey, $perMinuteLimit);
    }

    protected function resolveKey($request)
    {
        // Prioritize user ID if logged in, fallback to IP
        return $request->user()?->id ?? $request->ip();
    }

    protected function buildTooManyAttemptsResponse($retryAfter, $limit, $type)
    {
        $message = $type === 'per_second'
            ? 'Too many requests per second. Maximum 20 requests per second allowed.'
            : 'Too many requests. Maximum 1000 requests per minute allowed.';

        return new JsonResponse([
            'success' => false,
            'message' => $message,
            'code' => 429,
            'retry_after' => $retryAfter,
            'limit_type' => $type,
            'limit' => $limit
        ], 429);
    }

    protected function addHeaders($response, $key, $limit)
    {
        $remaining = $this->limiter->remaining($key, $limit);
        $response->headers->add([
            'X-RateLimit-Limit' => $limit,
            'X-RateLimit-Remaining' => $remaining,
            'X-RateLimit-Reset' => time() + $this->limiter->availableIn($key),
        ]);

        return $response;
    }
}
