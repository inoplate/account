<?php

use Inoplate\Account\Domain\Exceptions\UserMustHaveAtLeastOneRole;

class RegisterCest
{
    // tests
    public function tryRegisterWithIncompleteInput(FunctionalTester $I)
    {
        $I->wantTo('Register a user with incomplete input');
        $I->amOnPage('/register');
        $I->fillField('username', 'usertest');
        $I->fillField('email', 'usertest@email.com');
        $I->fillField('password', '123456');
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals('/register');
        $I->dontSeeAuthentication();
    }

    public function tryRegisterDesiredInputWithoutDefaultRole(FunctionalTester $I)
    {
        try {
            $I->wantTo('Register a user with desired input');
            $I->amOnPage('/register');
            $I->fillField('username', 'usertest');
            $I->fillField('email', 'usertest@email.com');
            $I->fillField('password', '123456');
            $I->fillField('password_confirmation', '123456');
            $I->click('button[type=submit]');
        } catch(UserMustHaveAtLeastOneRole $ignored){}
    }

    public function tryRegisterDesiredInputWithDefaultRole(FunctionalTester $I)
    {
        $I->haveRecord('roles', [
            'id' => 2,
            'name' =>  'Spectator',
            'slug' => 'spectator',
            'landing' => 'admin/profile',
            'is_default' => true,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        $I->wantTo('Register a user with desired input');
        $I->amOnPage('/register');
        $I->fillField('username', 'usertest');
        $I->fillField('email', 'usertest@email.com');
        $I->fillField('password', '123456');
        $I->fillField('password_confirmation', '123456');
        $I->click('button[type=submit]');
        $I->seeRecord('users', ['username' => 'usertest']);
        $I->seeCurrentUrlEquals('/login');
    }
}
