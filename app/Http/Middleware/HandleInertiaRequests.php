<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Get site settings from cache
        $generalSettings = Cache::get('settings.general', [
            'site_name' => config('app.name', 'İnci Villa'),
            'site_description' => 'Türkiye\'nin en prestijli villa kiralama platformu',
            'logo' => '',
            'contact_email' => 'info@incivilla.com',
            'contact_phone' => '+90 555 123 45 67',
            'address' => 'İstanbul, Türkiye'
        ]);

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'settings' => [
                'general' => $generalSettings,
            ],
        ];
    }
}
