<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class ComposerServiceProvider extends ServiceProvider
{
    private $main, $menu, $user, $role, $permission;

    public function __construct()
    {
        $this->main = [
            'backend.layout.sidebar',
            'backend.layout.breadcrumbs',
			'backend.index.index',
            'backend.layout.frame',
            'backend.layout.window',
            //'backend.layout.main',
        ];

        $this->userInfo = [
            'backend.layout.sidebar',
            'backend.layout.header',
            'backend.index.index'
        ];
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer($this->userInfo, function ($view) {
            $userInfo = \Auth::user();
            $userRoles = $userInfo->roles->toArray();
            $userRoleDisplayNames = array_map(function ($value) {
                return $value['display_name'];
            }, $userRoles);
            //var_dump($userRoleDisplayNames);exit;
            $view->with(compact('userInfo','userRoleDisplayNames'));
        });

        view()->composer($this->main, 'App\Http\ViewComposers\MainComposer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
