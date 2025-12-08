<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifikasi;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $count = 0;

            if (Auth::check()) {
                $count = Notifikasi::where('user_id', Auth::id())
                    ->where('dibaca', false)
                    ->count();
            }

            $view->with('unreadNotifCount', $count);
        });
    }
}
