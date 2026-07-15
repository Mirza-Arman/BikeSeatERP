<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

abstract class Controller
{
    /**
     * Render a module placeholder page.
     *
     * @param  array<int, array{label: string, url?: string}>  $breadcrumbs
     */
    protected function moduleView(string $view, string $title, array $breadcrumbs = []): View
    {
        return view($view, [
            'pageTitle' => $title,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}
