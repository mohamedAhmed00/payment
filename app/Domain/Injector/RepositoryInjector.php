<?php

namespace App\Domain\Injector;

use App\Providers\AppServiceProvider;

class RepositoryInjector extends AppServiceProvider
{
    public function boot()
    {
        $models = getModels();
        foreach ($models as $model) {
            $this->app->scoped("App\Domain\Repositories\Interfaces\I{$model}Repository", function ($app) use ($model) {
                return $app->makeWith("App\Domain\Repositories\Classes\\".$model.'Repository', ['model' => $app->make('App\Models\\'.$model)]);
            });
        }
    }
}
