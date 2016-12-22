<?php

namespace App\Providers;


use App\Repositories\JsapiTicketRepository;
use App\Repositories\TokenRepository;
use App\Repositories\UserRepository;
use App\Repositories\MenuRepository;
use App\Repositories\RoleRepository;
use App\Repositories\ActionRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\WxMessageRepository;
use App\Repositories\WxTemplateRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // 合并自定义配置文件
        $configuration = realpath(__DIR__ . '/../../config/repository.php');
        $this->mergeConfigFrom($configuration, 'repository');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerJsapiTicketRepository();
        $this->registerTokenRepository();
        $this->registerMenuRepository();
        $this->registerUserRepository();
        $this->registerRoleRepository();
        $this->registerActionRepository();
        $this->registerPermissionRepository();
        $this->registerWechatRepository();
    }

    public function registerWechatRepository()
    {
        $this->app->singleton('wxmessagerepository', function ($app) {
            $model = config('repository.models.wxmessage');
            $message = new $model();
            $validator = $app['validator'];

            return new WxMessageRepository($message, $validator);
        });
        $this->app->singleton('wxtemplaterepository', function ($app) {
            $model = config('repository.models.wxtemplate');
            $message = new $model();
            $validator = $app['validator'];

            return new WxTemplateRepository($message, $validator);
        });
    }

    public function registerJsapiTicketRepository()
    {
        $this->app->singleton('jsapiticketrepository', function ($app) {
            $model = config('repository.models.jsapiTicket');
            $ticket = new $model();
            $validator = $app['validator'];

            return new JsapiTicketRepository($ticket, $validator);
        });
    }
    
    public function registerTokenRepository()
    {
        $this->app->singleton('tokenrepository', function ($app) {
            $model = config('repository.models.token');
            $token = new $model();
            $validator = $app['validator'];

            return new TokenRepository($token, $validator);
        });
    }

    /**
     * Register the Menu Repository
     *
     * @return mixed
     */
    public function registerMenuRepository()
    {
        $this->app->singleton('menurepository', function ($app) {
            $model = config('repository.models.menu');
            $menu = new $model();
            $validator = $app['validator'];

            return new MenuRepository($menu, $validator);
        });
    }

    public function registerUserRepository()
    {
        $this->app->singleton('userrepository', function ($app) {
            $model = config('repository.models.user');
            $user = new $model();
            $validator = $app['validator'];

            return new UserRepository($user, $validator);
        });
    }

    public function registerRoleRepository()
    {
        $this->app->singleton('rolerepository', function ($app) {
            $model = config('repository.models.role');
            $role = new $model();
            $validator = $app['validator'];

            return new RoleRepository($role, $validator);
        });
    }

    public function registerActionRepository()
    {
        $this->app->singleton('actionrepository', function ($app) {
            $model = config('repository.models.action');
            $action = new $model();
            $validator = $app['validator'];

            return new ActionRepository($action, $validator);
        });
    }

    public function registerPermissionRepository()
    {
        $this->app->singleton('permissionrepository', function ($app) {
            $model = config('repository.models.permission');
            $permission = new $model();
            $validator = $app['validator'];

            return new PermissionRepository($permission, $validator);
        });
    }
}
