<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldTrack($request)) {
            try {
                $visit = Visit::firstOrCreate(
                    [
                        'date' => now()->toDateString(),
                        'ip' => (string) $request->ip(),
                        'path' => '/'.ltrim($request->path(), '/'),
                    ],
                    ['hits' => 0]
                );

                $visit->increment('hits');
            } catch (\Throwable $e) {
                // Sayaç hatası siteyi asla durdurmasın
            }
        }

        return $next($request);
    }

    protected function shouldTrack(Request $request): bool
    {
        if (! $request->isMethod('GET') || $request->ajax()) {
            return false;
        }

        // Admin, livewire ve statik dosyaları sayma
        if ($request->is('admin', 'admin/*', 'livewire/*', 'storage/*', 'images/*', 'css/*', 'js/*', 'favicon*', 'up')) {
            return false;
        }

        // Botları ele
        $ua = strtolower((string) $request->userAgent());

        if ($ua === '' || preg_match('/bot|crawl|spider|slurp|curl|wget|facebookexternalhit|whatsapp|preview/i', $ua)) {
            return false;
        }

        return true;
    }
}
