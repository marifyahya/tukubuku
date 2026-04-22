# Rate Limiter Plan

## Objective
Implement a public API rate limiter for `search/suggestions` (and potentially other public endpoints). The requirement is 1000 requests per minute. If the limit is exceeded, the user should be placed on a 30-minute cooldown.

## Implementation Steps
1. **Create Middleware**: Create a custom middleware `app/Http/Middleware/PublicApiRateLimiter.php` that implements the logic:
   - Check if the user is in the 30-minute cooldown cache. If so, return a `429 Too Many Requests` response with the remaining cooldown time.
   - Use Laravel's `RateLimiter` to allow 1000 requests per minute.
   - If the rate limit is exceeded, place the user's identifier (IP or User ID) into the cooldown cache for 30 minutes.
2. **Register Middleware**: Register the new middleware in `bootstrap/app.php` using a middleware alias `public.api.rate_limit`.
3. **Apply Middleware**: Apply the middleware alias to the `search/suggestions` route in `routes/api.php` or apply it globally to the API group if requested.