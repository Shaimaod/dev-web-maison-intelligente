<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogUserActivity
{
    public function __construct()
    {
        file_put_contents(storage_path('logs/laravel.log'), "LogUserActivity middleware initialized at " . now()->toDateTimeString() . "\n", FILE_APPEND);
    }

    public function handle(Request $request, Closure $next)
    {
        file_put_contents(storage_path('logs/laravel.log'), "LogUserActivity handle method started at " . now()->toDateTimeString() . "\n", FILE_APPEND);

        try {
            if (auth()->check()) {
                file_put_contents(storage_path('logs/laravel.log'), "User is authenticated: " . auth()->id() . "\n", FILE_APPEND);

                if (!session()->has('logged_in')) {
                    file_put_contents(storage_path('logs/laravel.log'), "Attempting to log user login: " . auth()->id() . "\n", FILE_APPEND);

                    try {
                        $activityLog = ActivityLog::create([
                            'user_id' => auth()->id(),
                            'action_type' => 'login',
                            'description' => 'Connexion à l\'application',
                            'details' => [
                                'ip' => $request->ip(),
                                'user_agent' => $request->userAgent(),
                                'timestamp' => now()->toDateTimeString(),
                                'route' => $request->route() ? $request->route()->getName() : 'unknown'
                            ],
                            'ip_address' => $request->ip(),
                            'user_agent' => $request->userAgent()
                        ]);

                        file_put_contents(storage_path('logs/laravel.log'), "Login activity log created successfully: " . $activityLog->id . "\n", FILE_APPEND);

                        session()->put('logged_in', true);
                        file_put_contents(storage_path('logs/laravel.log'), "Session updated with logged_in flag\n", FILE_APPEND);
                    } catch (\Exception $e) {
                        file_put_contents(storage_path('logs/laravel.log'), "Failed to create login activity log: " . $e->getMessage() . "\n", FILE_APPEND);
                    }
                } else {
                    file_put_contents(storage_path('logs/laravel.log'), "User already marked as logged in\n", FILE_APPEND);
                }
            } else {
                file_put_contents(storage_path('logs/laravel.log'), "User is not authenticated\n", FILE_APPEND);
            }
        } catch (\Exception $e) {
            file_put_contents(storage_path('logs/laravel.log'), "Error in LogUserActivity handle method: " . $e->getMessage() . "\n", FILE_APPEND);
        }

        $response = $next($request);

        file_put_contents(storage_path('logs/laravel.log'), "LogUserActivity handle method completed\n", FILE_APPEND);

        return $response;
    }

    public function terminate($request, $response)
    {
        file_put_contents(storage_path('logs/laravel.log'), "LogUserActivity terminate method started\n", FILE_APPEND);

        try {
            if (auth()->check() && session()->has('logged_in') && $request->session()->get('logging_out', false)) {
                file_put_contents(storage_path('logs/laravel.log'), "Attempting to log user logout: " . auth()->id() . "\n", FILE_APPEND);

                try {
                    $activityLog = ActivityLog::create([
                        'user_id' => auth()->id(),
                        'action_type' => 'logout',
                        'description' => 'Déconnexion de l\'application',
                        'details' => [
                            'ip' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                            'timestamp' => now()->toDateTimeString(),
                            'route' => $request->route() ? $request->route()->getName() : 'unknown'
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]);

                    file_put_contents(storage_path('logs/laravel.log'), "Logout activity log created successfully: " . $activityLog->id . "\n", FILE_APPEND);

                    session()->forget('logged_in');
                    file_put_contents(storage_path('logs/laravel.log'), "Session logged_in flag removed\n", FILE_APPEND);
                } catch (\Exception $e) {
                    file_put_contents(storage_path('logs/laravel.log'), "Failed to create logout activity log: " . $e->getMessage() . "\n", FILE_APPEND);
                }
            }
        } catch (\Exception $e) {
            file_put_contents(storage_path('logs/laravel.log'), "Error in LogUserActivity terminate method: " . $e->getMessage() . "\n", FILE_APPEND);
        }

        file_put_contents(storage_path('logs/laravel.log'), "LogUserActivity terminate method completed\n", FILE_APPEND);
    }
} 