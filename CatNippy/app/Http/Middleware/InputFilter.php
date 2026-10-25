<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InputFilter
{
    public function handle(Request $request, Closure $next): Response
    {
        // Filter query-string input.
        $request->query->replace(
            $this->filterArray($request->query->all())
        );

        // Filter ordinary form input.
        $request->request->replace(
            $this->filterArray($request->request->all())
        );

        return $next($request);
    }

    private function filterArray(array $values): array
    {
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $values[$key] = $this->filterArray($value);
            } elseif (is_string($value)) {
                // Path input filter.
                $values[$key] = str_replace('../', '', $value);
            }
        }

        return $values;
    }
}