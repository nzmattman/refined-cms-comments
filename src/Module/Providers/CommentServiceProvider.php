<?php

namespace RefinedDigital\Comments\Module\Providers;

use Illuminate\Support\ServiceProvider;
use RefinedDigital\Comments\Commands\Install;
use RefinedDigital\CMS\Modules\Core\Models\PackageAggregate;
use RefinedDigital\CMS\Modules\Core\Models\ModuleAggregate;
use RefinedDigital\CMS\Modules\Core\Models\RouteAggregate;

class CommentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->addNamespace('comments', [
            __DIR__.'/../Resources/views',
            base_path().'/resources/views'
        ]);

        if ($this->app->runningInConsole()) {
            if (\DB::connection()->getDatabaseName() && !\Schema::hasTable('comments')) {
                $this->commands([
                    Install::class
                ]);
            }
        }

        $this->publishes([
            __DIR__.'/../../../config/comments.php' => config_path('comments.php'),
        ], 'comments');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        app(RouteAggregate::class)
            ->addRouteFile('comments', __DIR__.'/../Http/routes.php');

        $menuConfig = [
            'order' => 210,
            'name' => 'Comments',
            'icon' => 'fas fa-comments',
            'route' => 'comments',
            'activeFor' => ['comments']
        ];

        app(ModuleAggregate::class)
            ->addMenuItem($menuConfig);

        app(PackageAggregate::class)
            ->addPackage('Comments', [
                'repository' => \RefinedDigital\Comments\Module\Http\Repositories\CommentRepository::class,
                'model' => '\\RefinedDigital\\Comments\\Module\\Models\\Comment',
            ]);
    }
}
