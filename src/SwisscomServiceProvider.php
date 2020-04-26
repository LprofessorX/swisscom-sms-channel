<?php

namespace NotificationChannels\Swisscom;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class SwisscomServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(SwisscomClient::class, function () {
            return new SwisscomClient(
                config('services.swisscom.key'),
                config('services.swisscom.from')
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [SwisscomClient::class];
    }
}
