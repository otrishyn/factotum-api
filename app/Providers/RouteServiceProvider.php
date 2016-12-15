<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class RouteServiceProvider
 *
 * @package App\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
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
        $this->makeRoutes('api/v1', 'routes/api.php', $this->makeDefaultMiddlewares());
        
        $this->makeRoutes('api/v1/auth', 'routes/Routes/auth.php', ['api', 'guest']);
        
        $this->makeRoutes('api/v1/ategories', 'routes/Routes/category.php', $this->makeDefaultMiddlewares());
    }
    
    /**
     * @param string $prefix
     * @param string $filename
     * @param array $middlewares
     */
    protected function makeRoutes($prefix, $filename, array $middlewares = [])
    {
        Route::group(
            [
                'middleware' => $middlewares,
                'namespace' => $this->namespace,
                'prefix' => $prefix,
            ],
            function ($router) use ($filename) {
                require base_path($filename);
            }
        );
    }
    
    /**
     * @param array $middlewares
     * @return array
     */
    protected function makeDefaultMiddlewares(array $middlewares = [])
    {
        return array_merge(['api', 'auth:api', 'user.state'], $middlewares);
    }
}
