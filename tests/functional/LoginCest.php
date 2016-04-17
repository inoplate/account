<?php

class LoginCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveRecord('roles', [
            'id' => 1,
            'name' =>  'Administrator',
            'slug' => 'administrator',
            'landing' => 'admin/dashboard',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        $I->haveRecord('roles', [
            'id' => 2,
            'name' =>  'Spectator',
            'slug' => 'spectator',
            'landing' => 'admin/profile',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        $I->haveRecord('users', [
            'id' => 1,
            'username' => 'admin',
            'email' => 'admin@site.com',
            'password' => bcrypt('123456'),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        $I->haveRecord('users', [
            'id' => 2,
            'username' => 'spectator',
            'email' => 'spectator@site.com',
            'password' => bcrypt('123456'),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        $I->haveRecord('role_user', [
            'user_id' => 1,
            'role_id' => 1,
        ]);

        $I->haveRecord('role_user', [
            'user_id' => 2,
            'role_id' => 2,
        ]);
    }

    public function trySigninWithUsername(FunctionalTester $I)
    {
        $I->wantTo('Login as user with username as identifier');
        $I->dontSeeAuthentication();
        $I->amOnPage('/login');
        $I->fillField('identifier', 'admin');
        $I->fillField('password', '123456');
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals('/admin/dashboard');
        $I->seeAuthentication();
    }

    public function trySigninWithEmail(FunctionalTester $I)
    {
        $I->wantTo('Login as user with email as identifier');
        $I->dontSeeAuthentication();
        $I->amOnPage('/login');
        $I->fillField('identifier', 'admin@site.com');
        $I->fillField('password', '123456');
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals('/admin/dashboard');
        $I->seeAuthentication();
    }

    public function trySigninWithDifferentRole(FunctionalTester $I)
    {
        $I->wantTo('Login as user with different role');
        $I->dontSeeAuthentication();
        $I->amOnPage('/login');
        $I->fillField('identifier', 'spectator');
        $I->fillField('password', '123456');
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals('/admin/profile');
        $I->seeAuthentication();
    }

    public function trySigninWithIncompleteInput(FunctionalTester $I)
    {
        $I->wantTo('Login as user with incomplete credentials');
        $I->dontSeeAuthentication();
        $I->amOnPage('/login');
        $I->fillField('identifier', 'spectator');
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals('/login');
        $I->dontSeeAuthentication();
    }

    public function trySigninWithInvalidCredentials(FunctionalTester $I)
    {
        $I->wantTo('Login as user with invalid credentials');
        $I->dontSeeAuthentication();
        $I->amOnPage('/login');
        $I->fillField('identifier', 'spectator');
        $I->fillField('password', 'invalid');
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals('/login');
        $I->dontSeeAuthentication();
    }
}
