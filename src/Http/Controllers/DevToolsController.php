<?php

declare(strict_types=1);

namespace KingsCode\DevTools\Http\Controllers;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Console\Command\Command;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Illuminate\Contracts\View\View;

final class DevToolsController extends Controller
{
    private const MAX_EXECUTION_TIME = 60 * 5; // 5 minutes

    private const DIRECTORY = 'App\Console\Commands';

    private const NAME_INCLUSIONS = [
        'optimize:clear',
        'cache:clear',
        'migrate',
        'migrate:fresh',
    ];

    public function overview(Request $request): View
    {
        set_time_limit(self::MAX_EXECUTION_TIME);

        return match ($request->input('type')) {
            'password-reset' => $this->handleAsPasswordReset($request),
            'process-command' => $this->handleAsCommand($request),
            default => $this->handleAsView($request),
        };
    }

    private function handleAsView(Request $request, array $data = []): View
    {
        return view('kc-dev-tools::overview',
            array_merge($request->post(),
                [
                    'commands'                   => $this->getCommands(),
                    'command_output'             => data_get($data, 'command_output', null),
                    'command_message'            => data_get($data, 'command_message', null),
                    'password_reset_message'     => data_get($data, 'password_reset_message', null),
                ]));
    }

    private function handleAsCommand(Request $request): View
    {
        $commandName = $request->input('command_name');
        $args = $request->input('arguments') ?? [];

        $message = '';
        $output = '';

        if ($commandName !== null) {
            Artisan::call($commandName, $args);

            $output = Artisan::output();
            if (! empty ($output)) {
                $output = collect(explode('.', $output))->join('.<br />');
            }
            $message = sprintf('%s has been executed', $commandName);
        }

        return $this->handleAsView($request, [
            'command_output'  => $output,
            'command_message' => $message,
        ]);
    }

    private function handleAsPasswordReset(Request $request): View
    {
        try {
            $email = $request->input('password-reset.email');
            $password = $request->input('password-reset.password');

            $user = \App\Models\User::query()->where('email', $email)->first();
            if ($user === null) {
                throw new \Exception('User with the provided e-mail has not been found');
            }

            if (empty ($password)) {
                throw new \Exception('Password cannot be empty');
            }

            $message = 'Password updated';
            $user->update(['password' => bcrypt($password)]);
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        return $this->handleAsView($request, [
            'password_reset_message' => $message,
        ]);
    }

    private function getCommands(): array
    {
        $nameInclusions = collect(self::NAME_INCLUSIONS);

        return collect(resolve(Kernel::class)->all())->filter(function (Command $command) use ($nameInclusions) {
            if ($nameInclusions->contains($command->getName()) ||
                str_contains(get_class($command), self::DIRECTORY)) {
                return true;
            }

            return false;
        })->map(function (Command $command) {
            return [
                'name'  => $command->getName(),
                'value' => $command,
            ];
        })->push([
            'name'  => '--- Select a command ---',
            'value' => null,
        ])->sortBy('name')->values()->toArray();
    }
}
