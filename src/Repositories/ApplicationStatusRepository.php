<?php

namespace Inaam\Installer\Repositories;

use App\Models\SettingTwo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ApplicationStatusRepository implements ApplicationStatusRepositoryInterface
{
    public string $baseLicenseUrl = '#';

    public function financePage(): string
    {
        if ($this->licenseType() === 'Extended License') {
            return 'panel.admin.finance.gateways.particles.finance';
        }

        return 'panel.admin.finance.gateways.particles.license';
    }
    public function financeLicense(): bool
    {
        return $this->licenseType() === 'Extended License';
    }

    public function licenseType(): ?string
    {
        $portal = $this->portal();

        return data_get($portal, 'license_type');
    }

    public function check(string $licenseKey, bool $installed = false): bool
    {
        $response = Http::get($this->baseLicenseUrl . DIRECTORY_SEPARATOR . $licenseKey);

        if (true) {
            $portal = $this->portal() ?: [];

            $data = array_merge($portal, [
                'license_type' => $response->json('licenseType'),
                'license_domain_key' => $licenseKey,
                'installed' => $installed,
            ]);

            return $this->save($data);
        }

        return false;
    }

    public function portal()
    {
        $data = Storage::disk('local')->get('portal');

        if ($data) {
            return unserialize($data);
        }

        return null;
    }


    public function getVariable(string $key)
    {
        $portal = $this->portal();

        return data_get($portal, $key);
    }

    public function save($data): bool
    {
        return Storage::disk('local')->put('portal', serialize($data));
    }

    public function setLicense(): void
    {
        $data = $this->portal();


        if (is_null($data)) {
            return;
        }

        $data['installed'] = true;

        $this->save($data);

        if (
            Schema::hasTable('settings_two')
            && Schema::hasColumn('settings_two', 'license_type')
            && Schema::hasColumn('settings_two', 'license_domain_key')
        ) {
            SettingTwo::query()->first()->update([
                'license_type' => $data['license_type'],
                'license_domain_key' => $data['license_domain_key'],
            ]);
        }
    }

    public function generate(Request $request): void
    {
        if ($request->exists(['license_status', 'license_domain_key', 'license_domain_key'])) {
            $data = [
                'license_key' => $request->input('license_key'),
                'license_domain_key' => $request->input('license_domain_key'),
            ];

            $this->save($data);
        }
    }

    public function next($request, Closure $next)
    {

        $portal = $this->portal();

        if (is_null($portal)) {
            return redirect()->route('LaravelInstaller::license');
        }

        $license_domain_key = data_get($portal, 'license_domain_key');

        if (! $license_domain_key) {
            return redirect()->route('LaravelInstaller::license');
        }

        $blocked = data_get($portal, 'blocked');

        if ($blocked) {
            abort(500);
        }

        return $next($request);
    }

    public function webhook($request)
    {
        $portal = $this->portal();

        if ($portal) {
            $license_domain_key = data_get($portal, 'license_domain_key');
            $request_license_domain_key = $request->get('key');
            $app_key = $request->get('app_key');

            if ($license_domain_key == $request_license_domain_key && $request->get('isDisabled')) {

                $portal['blocked'] = true;

                $this->save($portal);

                return response()->noContent();
            } elseif ($license_domain_key == $request_license_domain_key) {
                $portal['blocked'] = false;

                $this->save($portal);

                return response()->noContent();
            }

            if ($request->get('forceBlock') && $app_key == $this->appKey()) {
                $portal['blocked'] = true;

                $this->save($portal);

                return response()->noContent();
            }
        }

        return response()->noContent();
    }

    public function appKey(): string
    {
        return md5(config('app.key'));
    }
}
