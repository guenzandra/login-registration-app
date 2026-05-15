<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Session Driver
    |--------------------------------------------------------------------------
    |
    | SECURITY: Using 'database' driver provides better session management
    | and allows us to invalidate specific user sessions when needed
    | (e.g., password change, account lockout).
    |
    */
    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | SECURITY: 120 minutes (2 hours) is a good balance between usability
    | and security. Shorter sessions reduce the risk of session hijacking.
    |
    | For high-security applications, consider reducing to 30-60 minutes.
    |
    */
    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    /*
    |--------------------------------------------------------------------------
    | Expire on Close
    |--------------------------------------------------------------------------
    |
    | SECURITY: When set to true, session expires when user closes browser.
    | This prevents session hijacking on shared/public computers.
    |
    | Set to true by default for better security.
    |
    */
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', true), // Changed to true

    /*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    |
    | SECURITY: Encrypting session data prevents attackers from reading
    | session contents even if they gain access to storage (database/files).
    | 
    | IMPORTANT: Always enable in production!
    |
    */
    'encrypt' => env('SESSION_ENCRYPT', true), // Changed to true

    /*
    |--------------------------------------------------------------------------
    | Session File Location
    |--------------------------------------------------------------------------
    |
    */
    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Database Connection
    |--------------------------------------------------------------------------
    |
    */
    'connection' => env('SESSION_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Session Database Table
    |--------------------------------------------------------------------------
    |
    */
    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Cache Store
    |--------------------------------------------------------------------------
    |
    */
    'store' => env('SESSION_STORE'),

    /*
    |--------------------------------------------------------------------------
    | Session Sweeping Lottery
    |--------------------------------------------------------------------------
    |
    */
    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Name
    |--------------------------------------------------------------------------
    |
    | SECURITY: Using a unique, application-specific cookie name prevents
    | conflicts with other apps on the same domain.
    |
    */
    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug((string) env('APP_NAME', 'laravel'), '_') . '_session' // Added underscore for security
    ),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Path
    |--------------------------------------------------------------------------
    |
    */
    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Domain
    |--------------------------------------------------------------------------
    |
    | SECURITY: Explicitly set to null to only allow current domain.
    | Never use wildcard domains unless absolutely necessary.
    |
    */
    'domain' => env('SESSION_DOMAIN', null), // Explicitly set to null

    /*
    |--------------------------------------------------------------------------
    | HTTPS Only Cookies
    |--------------------------------------------------------------------------
    |
    | SECURITY: When true, cookies are only sent over HTTPS connections.
    | This prevents session hijacking through network sniffing (MITM attacks).
    |
    | MUST be true in production when using HTTPS!
    |
    */
    'secure' => env('SESSION_SECURE_COOKIE', true), // Changed to true for production

    /*
    |--------------------------------------------------------------------------
    | HTTP Access Only
    |--------------------------------------------------------------------------
    |
    | SECURITY: When true, JavaScript cannot access the cookie.
    | This prevents XSS attacks from stealing session cookies.
    |
    | ALWAYS keep this true!
    |
    */
    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Same-Site Cookies
    |--------------------------------------------------------------------------
    |
    | SECURITY: 'lax' allows same-site navigation while preventing CSRF attacks.
    | 'strict' is more secure but may break some legitimate cross-site requests.
    | 
    | 'lax' is the best balance for most applications.
    |
    */
    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Partitioned Cookies
    |--------------------------------------------------------------------------
    |
    */
    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];
