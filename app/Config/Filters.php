<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\Cifilter;

class Filters extends BaseFilters
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array<string, class-string|list<class-string>>
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        'cifilter'      => \App\Filters\Cifilter::class, // Add Cifilter alias
    ];

    /**
     * List of special required filters.
     *
     * Filters listed here are applied before and after
     * other filters and always executed.
     *
     * @var array{before: list<string>, after: list<string>}
     */
    public array $required = [
        'before' => [
            'forcehttps', // Force global secure requests
        ],
        'after' => [
            'performance', // Collect performance metrics
            'toolbar',     // Debug toolbar
        ],
    ];

    /**
     * Global filters for all requests.
     *
     * Filters applied here run on every request.
     *
     * @var array<string, array<string, array<string, string>>>|array<string, list<string>>
     */
    public array $globals = [
        'before' => [
            // Uncomment the following as needed:
            // 'csrf',
            // 'cifilter:auth', // Apply authentication globally (if required)
        ],
        'after' => [
            // Uncomment the following as needed:
            // 'secureheaders',
        ],
    ];

    /**
     * Filters applied to specific HTTP methods.
     *
     * Example: 'POST' => ['foo', 'bar']
     *
     * @var array<string, list<string>>
     */
    public array $methods = [
        // Example: 'POST' => ['csrf', 'cifilter:auth'],
    ];

    /**
     * Filters applied to specific URI patterns.
     *
     * Example: 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array<string, array<string, list<string>>>
     */
    public array $filters = [
        // Enforce authentication on admin routes
        'cifilter' => [
            'before' => ['admin/*'], // Apply authentication to all admin routes
        ],
    ];
}
