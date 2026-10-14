<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TableServiceController extends Controller
{
    private function filePath(): string
    {
        return storage_path('app/table_services.json');
    }

    private function readServices(): array
    {
        $path = $this->filePath();

        if (!File::exists($path)) {
            File::ensureDirectoryExists(dirname($path));

            $services = collect(config('table_service', []))
                ->map(function ($service, $key) {
                    return [
                        'id' => (string) $key,
                        'name' => $service['name'] ?? '',
                        'value' => $service['value'] ?? 0,
                        'currency' => $service['currency'] ?? 'ل.س',
                        'active' => true,
                    ];
                })
                ->values()
                ->all();

            $this->writeServices($services);

            return $services;
        }

        $content = File::get($path);
        $services = json_decode($content, true);

        if (!is_array($services)) {
            throw new \RuntimeException('ملف الخدمات غير صالح. يرجى التحقق من صيغة JSON.');
        }

        return $services;
    }

    private function writeServices(array $services): void
    {
        File::ensureDirectoryExists(dirname($this->filePath()));

        $json = json_encode(
            array_values($services),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        if ($json === false || File::put($this->filePath(), $json . PHP_EOL, true) === false) {
            throw new \RuntimeException('تعذّر حفظ ملف الخدمات.');
        }
    }

    public function index()
    {
        $services = $this->readServices();

        return view('admin.settings', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'currency' => ['required', 'string', 'max:10'],
        ]);

        $services = $this->readServices();

        $services[] = [
            'id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'value' => $validated['value'],
            'currency' => strtoupper($validated['currency']),
            'active' => true,
        ];

        $this->writeServices($services);

        return back()->with('status', 'تمت إضافة الخدمة بنجاح.');
    }

    public function update(Request $request, string $serviceId)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'currency' => ['required', 'string', 'max:10'],
        ]);

        $services = $this->readServices();
        $found = false;

        foreach ($services as &$service) {
            if ((string) ($service['id'] ?? '') === $serviceId) {
                $service['name'] = $validated['name'];
                $service['value'] = $validated['value'];
                $service['currency'] = strtoupper($validated['currency']);
                $found = true;
                break;
            }
        }

        unset($service);

        if (!$found) {
            abort(404);
        }

        $this->writeServices($services);

        return back()->with('status', 'تم تعديل الخدمة بنجاح.');
    }

    public function destroy(string $serviceId)
    {
        $services = $this->readServices();
        $filtered = array_values(array_filter(
            $services,
            fn($service) => (string) ($service['id'] ?? '') !== $serviceId
        ));

        if (count($filtered) === count($services)) {
            abort(404);
        }

        $this->writeServices($filtered);

        return back()->with('status', 'تم حذف الخدمة بنجاح.');
    }

    public function toggleStatus(string $serviceId)
    {
        $services = $this->readServices();
        $found = false;

        foreach ($services as &$service) {
            if ((string) ($service['id'] ?? '') === $serviceId) {
                $service['active'] = !($service['active'] ?? true);
                $found = true;
                break;
            }
        }

        unset($service);

        if (!$found) {
            abort(404);
        }

        $this->writeServices($services);

        return back()->with('status', 'تم تحديث حالة الخدمة بنجاح.');
    }
}
