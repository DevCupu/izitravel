<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethodSafe() || $response->getStatusCode() >= 400 || ! $request->user()
            || in_array('errors', $request->session()->get('_flash.new', []), true)) {
            return $response;
        }

        $route = $request->route();
        $routeName = $route?->getName();

        // A preview only reads the import file; it does not change application data.
        if (! $routeName || str_ends_with($routeName, '.preview')) {
            return $response;
        }

        $parts = explode('.', $routeName);
        $operation = end($parts);
        $action = match ($operation) {
            'store', 'confirm', 'notes', 'followups', 'upload-image' => 'create',
            'update', 'bulk-update', 'bulk-pic', 'bulk-status', 'bulk-room', 'bulk-group',
            'assign', 'move', 'stage', 'pipeline', 'pipeline-details', 'note', 'followup',
            'email', 'status', 'pic', 'group', 'room', 'visa-status', 'passport-expiry',
            'checklist', 'complete', 'bulk-complete', 'rename-album', 'reset-password',
            'reset', 'bulk', 'bulk-assign' => 'update',
            'destroy', 'bulk-destroy', 'delete-album' => 'delete',
            'archive', 'bulk-archive' => 'archive',
            'restore', 'bulk-restore' => 'restore',
            default => 'other',
        };

        $resource = $parts[1] === 'crm' ? ($parts[2] ?? 'crm') : $parts[1];
        $subject = match ($resource) {
            'packages' => in_array('jemaah', $parts, true) ? 'Jemaah Paket' : 'Paket Umrah',
            'jemaah' => 'Jemaah',
            'documents' => 'Dokumen Jemaah',
            'operations' => 'Operasional',
            'registrations' => 'Pendaftaran',
            'categories' => 'Kategori',
            'testimonials' => 'Testimoni',
            'galleries' => 'Galeri',
            'faqs' => 'FAQ',
            'articles' => 'Artikel',
            'teams' => 'Tim',
            'partners' => 'Mitra',
            'promos' => 'Promo',
            'whatsapp-cs' => 'Akun CS',
            'campaigns' => in_array('ads', $parts, true) ? 'Iklan Campaign' : 'Campaign',
            'settings' => 'Pengaturan Web',
            'system' => 'Pengaturan Sistem',
            'leads' => 'Lead CRM',
            'followups', 'tasks' => 'Tugas CRM',
            'pipeline', 'stages' => 'Pipeline CRM',
            'password' => 'Kata Sandi',
            default => ucfirst(str_replace('-', ' ', $resource)),
        };

        $subjectId = null;
        foreach (array_reverse($route->parameters()) as $parameter) {
            $id = $parameter instanceof Model ? $parameter->getKey() : $parameter;
            if (is_scalar($id) && ctype_digit((string) $id)) {
                $subjectId = (string) $id;
                break;
            }
        }

        ActivityLog::create([
            'user_id' => $request->user()->getKey(),
            'actor_name' => $request->user()->name,
            'action' => $action,
            'subject' => $subject,
            'subject_id' => $subjectId,
            'route_name' => $routeName,
            'ip_address' => $request->ip(),
        ]);

        return $response;
    }
}
