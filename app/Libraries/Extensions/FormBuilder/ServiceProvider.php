<?php

namespace App\Libraries\Extensions\FormBuilder;

use App\Libraries\Extensions\FormBuilder\Converter\JqueryValidation\Converter;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('formbuilder', function ($app) {
            $converter = new Converter();

            $form = new FormBuilder($app['html'], $app['url'], $app['view'], $app['session.store']->getToken(), $converter);

            return $form->setSessionStore($app['session.store']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('formbuilder');
    }

}