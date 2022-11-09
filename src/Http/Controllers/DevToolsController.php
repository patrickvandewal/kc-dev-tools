<?php

namespace KingsCode\DevTools\Http\Controllers;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Console\Command\Command;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DevToolsController extends Controller
{
    private const DIRECTORY = 'App\Console\Commands';

    /**
     * Default view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function overview()
    {
        $commands = collect(resolve(Kernel::class)->all())->filter(function (Command $command) {
            return str_contains(get_class($command), self::DIRECTORY);
        })->map(function (Command $command) {
            return [
                'name'  => $command->getName(),
                'value' => $command,
            ];
        })->push([
            'name'  => '--- Select a command ---',
            'value' => null,
        ])->sortBy('name');

        return view('kc-dev-tools::overview', [
            'commands' => $commands->values()->toArray(),
        ]);
    }

    public function process(Request $request)
    {
        $commandName = $request->input('command-name');
        $args = $request->input('arguments') ?? [];
        Artisan::call($commandName, $args);

        return Redirect::to($request->session()->previousUrl());
    }
}
