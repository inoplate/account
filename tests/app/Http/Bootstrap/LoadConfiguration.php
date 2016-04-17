<?php

namespace Inoplate\Account\Tests\Http\Bootstrap;

use Illuminate\Foundation\Bootstrap\LoadConfiguration as IlluminateLoadConfiguration;
use Illuminate\Contracts\Foundation\Application;

class LoadConfiguration extends IlluminateLoadConfiguration
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        parent::bootstrap($app);
        $app['config']->push('app.providers', \Inoplate\Captcha\CaptchaServiceProvider::class);
        $app['config']->push('app.providers', \Inoplate\Foundation\Providers\InoplateServiceProvider::class);
        $app['config']->push('app.providers', \Inoplate\Account\Providers\AccountServiceProvider::class);

        $app['config']->set('app.aliases.Captcha', \Inoplate\Captcha\Facades\Captcha::class);
        $app['config']->set('captcha.challenge', 'image');
        $app['config']->set('inoplate.account.allow_register', true);
        $app['config']->set('inoplate.account.enable_captcha', false);
    }
}