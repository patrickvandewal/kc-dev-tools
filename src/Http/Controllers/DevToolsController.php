<?php

declare(strict_types=1);

namespace KingsCode\DevTools\Http\Controllers;

use Dompdf\Exception;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Pushok\Response;
use Symfony\Component\Console\Command\Command;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class DevToolsController extends Controller
{
    private const DIRECTORY = 'App\Console\Commands';

    private const NAME_INCLUSIONS = [
        'optimize:clear',
        'cache:clear',
        'migrate',
        'migrate:fresh',
    ];

    /**
     * Default view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function overview()
    {
        $nameInclusions = collect(self::NAME_INCLUSIONS);

        $commands = collect(resolve(Kernel::class)->all())->filter(function (Command $command) use ($nameInclusions) {
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
        ])->sortBy('name');

        return view('kc-dev-tools::overview', ['commands' => $commands->values()->toArray(),]);
    }

    public function process(Request $request): RedirectResponse
    {
        $commandName = $request->input('command-name');
        $args = $request->input('arguments') ?? [];
        Artisan::call($commandName, $args);

        $parsedMessage = Artisan::output();
        $parsedMessage = collect(explode('.', $parsedMessage))->join('.<br />');

        return redirect()->action(
            [DevToolsController::class, 'overview'],

            array_merge($request->post(),
            [
                'command[output]' => $parsedMessage,
                'command[message' => 'Command executed',
            ]),
        );
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        try {
            $email = $request->input('password-reset.email');
            $password = $request->input('password-reset.password');

            $user = \App\Models\User::query()->where('email', $email)->first();
            if ($user === null) {
                throw new Exception('User with the provided e-mail has not been found');
            }

            $message = 'Password updated';
            $user->update(['password' => bcrypt($password)]);
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        return redirect()->action(
            [DevToolsController::class, 'overview'], ['password-reset[message]' => $message],
        );
    }
}
