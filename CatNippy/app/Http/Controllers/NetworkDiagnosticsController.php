<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class NetworkDiagnosticsController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.tools.connectivity', [
            'host' => '',
            'output' => null,
        ]);
    }

    public function check(Request $request): View
    {
        $validated = $request->validate([
            'host' => ['required', 'string', 'max:200'],
        ]);

        $host = $validated['host'];

        // user input is concatenated directly into a shell command.
        $output = shell_exec(
            'getent hosts ' . $host . ' 2>&1'
        );

        return view('admin.tools.connectivity', [
            'host' => $host,
            'output' => $output ?? 'No output returned.',
        ]);
    }
}
