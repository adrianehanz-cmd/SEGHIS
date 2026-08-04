<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Infrastructure\ExternalAPI\PSGC\PsgcAddressService;

final class AddressController
{
    public function __construct(private readonly PsgcAddressService $psgc)
    {
    }

    public function regions(): void
    {
        Response::json($this->psgc->regions(), 'Regions retrieved successfully.');
    }

    public function provinces(Request $request): void
    {
        $region = $this->code($request, 'region');
        Response::json($region === '' ? [] : $this->psgc->provinces($region), 'Provinces retrieved successfully.');
    }

    public function municipalities(Request $request): void
    {
        $region = $this->code($request, 'region');
        $province = $this->code($request, 'province');
        Response::json($region === '' ? [] : $this->psgc->municipalities($region, $province), 'Municipalities retrieved successfully.');
    }

    public function barangays(Request $request): void
    {
        $municipality = $this->code($request, 'municipality');
        Response::json($municipality === '' ? [] : $this->psgc->barangays($municipality), 'Barangays retrieved successfully.');
    }

    private function code(Request $request, string $key): string
    {
        $value = (string) ($request->query()[$key] ?? '');
        return preg_match('/^[0-9]{4,12}$/', $value) ? $value : '';
    }
}
