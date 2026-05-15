<?php
// app/Http/Middleware/SecurityHeaders.php

namespace App\Http\Middleware;

use Closure;

class SecurityHeaders
{
  /**
   * Security: Add security headers to prevent XSS, clickjacking, etc.
   */
  public function handle($request, Closure $next)
  {
    $response = $next($request);

    // Prevent XSS by forcing browser to trust Content-Type
    $response->headers->set('X-Content-Type-Options', 'nosniff');

    // Prevent clickjacking
    $response->headers->set('X-Frame-Options', 'DENY');

    // Enable XSS filtering
    $response->headers->set('X-XSS-Protection', '1; mode=block');

    // Strict Transport Security (force HTTPS)
    $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

    // Referrer Policy
    $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

    // Content Security Policy
    $response->headers->set('Content-Security-Policy', "default-src 'self'");

    return $response;
  }
}
