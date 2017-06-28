<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapCmsRoutes();
        $this->mapBackendRoutes();
        $this->mapClientRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    protected function mapCmsRoutes()
    {
        Route::prefix('cms/api/v1')
             ->middleware('cms')
             ->namespace($this->namespace)
             ->group(base_path('routes/cms.php'));
    }
    protected function mapBackendRoutes()
    {
        Route::prefix('backend/api/v1')
             ->middleware('backend')
             ->namespace($this->namespace)
             ->group(base_path('routes/backend.php'));
    }
    protected function mapClientRoutes()
    {
        Route::prefix('client/api/v1')
             ->middleware('client')
             ->namespace($this->namespace)
             ->group(base_path('routes/client.php'));
    }
}
