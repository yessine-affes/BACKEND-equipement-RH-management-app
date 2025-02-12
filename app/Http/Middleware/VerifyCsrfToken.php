<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'admins/*',       // Exclude CSRF for all admin routes
        'projects/*',     // Exclude CSRF for all project routes
        'tasks/*',        // Exclude CSRF for all task routes
        'task-assignments/*',
        'employees/*',
        'equipment/*',
        'certifications/*',
        'reports/*',
    ];
}
