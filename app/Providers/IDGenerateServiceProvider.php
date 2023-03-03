<?php

namespace App\Providers;

use App\Services\Implementation\IDGenerate\IDGenerateService;
use App\Services\Implementation\IDGenerate\IDGeneratorQueryService;
use App\Services\Implementation\IDGenerate\IDGeneratorService;
use App\Services\Interfaces\IDGenerate\IDGenerateServiceInterface;
use App\Services\Interfaces\IDGenerate\IDGeneratorQueryServiceInterface;
use App\Services\Interfaces\IDGenerate\IDGeneratorServiceInterface;
use Illuminate\Support\ServiceProvider;

class IDGenerateServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */

    public $singletons = [
        IDGenerateServiceInterface::class => IDGenerateService::class,
        IDGeneratorServiceInterface::class => IDGenerateService::class,
        IDGeneratorQueryServiceInterface::class => IDGeneratorQueryService::class,
    ];


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IDGenerateService::class, function ($app){
            $queryService = new IDGeneratorQueryService();
            $generatorService = new IDGeneratorService($queryService);

            return new IDGenerateService($generatorService, $queryService);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
