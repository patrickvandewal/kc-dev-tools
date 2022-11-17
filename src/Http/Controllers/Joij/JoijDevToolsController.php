<?php

declare(strict_types=1);

namespace KingsCode\DevTools\Http\Controllers\Joij;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\Console\Command\Command;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\View;

final class JoijDevToolsController extends Controller
{
    public function overview(Request $request): View
    {
        return match ($request->input('type')) {
            'change-environment' => $this->handleAsEnvironmentChange($request),
            default => $this->handleAsView($request),
        };
    }

    private function handleAsView(Request $request, array $data = []): View
    {
        return view('kc-dev-tools::joij-overview',
            array_merge($request->post(),
                [
                    'change_environment_message' => data_get($data, 'change_environment_message', null),
                ]));
    }

    private function handleAsEnvironmentChange(Request $request): View
    {
        try {
            $slug = $request->input('change-environment.slug');

            $items = \App\Models\Environment::all();
            if (empty($items)) {
                throw new \Exception('No environment have been found');
            }

            if (empty($slug)) {
                throw new \Exception('No slug has been provided');
            }

            $message = 'Environment updated';

            $items->each(fn($environment) => $environment->update(
                ['slug' => $slug]
            ));
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        return $this->handleAsView($request, [
            'change_environment_message' => $message,
        ]);
    }
}
