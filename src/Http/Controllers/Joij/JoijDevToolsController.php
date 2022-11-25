<?php

declare(strict_types=1);

namespace KingsCode\DevTools\Http\Controllers\Joij;

use Google\Auth\CredentialsLoader;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\View;
use KingsCode\DevTools\Http\Controllers\BaseDevToolsController;
use KingsCode\DevTools\Notifications\TestNotification;
use Kreait\Firebase\Util;
use Symfony\Component\Console\Command\Command;

final class JoijDevToolsController extends BaseDevToolsController
{
    public function showOverview(Request $request): View
    {
        return match ($request->input('type')) {
            'change-environment' => $this->handleAsEnvironmentChange($request),
            'notification' => $this->handleAsNotification($request),
            'logout' => parent::logout($request),
            default => $this->handleAsView($request),
        };
    }

    private function handleAsView(Request $request, array $data = []): View
    {
        return view('kc-dev-tools::joij-overview',
            array_merge($request->post(), [
                'change_environment_message' => data_get($data, 'change_environment_message', null),
                'notification_candidates'    => $this->getEligibleNotificationCandidates(),
                'notification_message'       => data_get($data, 'notification_message', null),
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

    private function handleAsNotification(Request $request): View
    {
        try {
            $candidate = $request->input('notification.candidate');

            $title = $request->input('notification.title');
            $message = $request->input('notification.message');
            $data = $request->input('notification.data');

            $candidate = \App\Models\Candidate::query()->find((int) $candidate);
            if ($candidate === null) {
                throw new \Exception('No candidate found');
            }

            $testNotification = new TestNotification($title, $message, $data);
            $testNotification->via($candidate);

            /** @var  \Illuminate\Contracts\Notifications\Dispatcher $dispatcher */
            $dispatcher = app()->make(\Illuminate\Contracts\Notifications\Dispatcher::class);
            $dispatcher->send($candidate, $testNotification);

            $message = 'Dispatched notification';
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        return $this->handleAsView($request, [
            'notification_message' => $message,
        ]);
    }

    private function getEligibleNotificationCandidates(): array
    {
        $candidates = \App\Models\Candidate::all()->where(function (\App\Models\Candidate $candidate) {
            return $candidate->tokens()->whereIn('push_type', ['android', 'ios'])->exists();
        });

        return $candidates->map(function (\App\Models\Candidate $candidate) {
            return [
                'name'  => $candidate->getFullName(),
                'value' => $candidate,
            ];
        })->push([
            'name'  => '--- Select a candidate ---',
            'value' => null,
        ])->sortBy('name')->values()->toArray();
    }
}