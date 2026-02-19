<?php

namespace App\Domain\Provider\Fawaterak\Actions;

use App\Domain\Provider\Contract\IPaymentAction;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class RenderIFrameAction implements IPaymentAction
{
    public function execute(...$data): Factory|View|Application
    {
        [$data] = $data;
        $data = Cache::get($data['signature_key']);
        return view('vendor.providers.fawaterak.iframe', ['pluginConfig' => $data]);
    }
}
