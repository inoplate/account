<?php

class AuthCest
{
    protected $app;

    public function _before(FunctionalTester $I)
    {
        $this->app = $I->getApplication();
        $this->app->register('Inoplate\Account\Providers\AccountServiceProvider');
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function checkAuthEndpoint(FunctionalTester $I)
    {
        $I->wantTo('Visit login endpoint');
        $I->amOnPage('login');
        $I->seeResponseCodeIs(200);

        $I->wantTo('Visit register endpoint');
        $I->amOnPage('register');
        $I->seeResponseCodeIs(200);
    }
}
