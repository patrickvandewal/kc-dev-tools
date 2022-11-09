<?php

declare(strict_types=1);

namespace KingsCode\DevTools\Http\Controllers;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Console\Command\Command;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DevToolsController extends Controller
{
    private const DIRECTORY = 'App\Console\Commands';

    private const NAME_INCLUSIONS = [
        'optimize:clear',
        'cache:clear'
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

    public
    function process(
        Request $request
    ) {
        $commandName = $request->input('command-name');
        $args = $request->input('arguments') ?? [];
        Artisan::call($commandName, $args);

        return Redirect::to($request->session()->previousUrl());
    }
}
