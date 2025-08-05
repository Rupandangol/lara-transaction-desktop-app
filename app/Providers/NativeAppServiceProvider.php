<?php

namespace App\Providers;

use Native\Laravel\Contracts\ProvidesPhpIni;
use Native\Laravel\Facades\Menu;
use Native\Laravel\Facades\MenuBar;
use Native\Laravel\Facades\Window;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Window::open()
            ->width(1050)
            ->height(1050)
            ->minWidth(1050)
            ->minHeight(1050);
        Menu::create(
            Menu::file(),
            Menu::edit(),
            Menu::view(),
            Menu::window(),
            Menu::make(
                Menu::link(route('transaction.index'), 'Transaction List'),
            )->label('Transaction')
        );
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [];
    }
}
