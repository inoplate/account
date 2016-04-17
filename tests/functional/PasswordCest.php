<?php

class PasswordCest
{
    public function _before(FunctionalTester $I)
    {
        $dt = new DateTime();

        $I->haveRecord('roles', [
            'id' => 1,
            'name' =>  'Administrator',
            'slug' => 'administrator',
            'landing' => 'admin/dashboard',
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
            'username' => 'admin2',
            'email' => 'admin2@site.com',
            'password' => bcrypt('123456'),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        $I->haveRecord('password_resets', [
            'email' => 'admin@site.com',
            'token' => 'reset-token',
            'created_at' => new DateTime(),
        ]);

        $I->haveRecord('password_resets', [
            'email' => 'admin2@site.com',
            'token' => 'reset-token2',
            'created_at' => $dt->modify('-2 hour'),
        ]);

        $I->haveRecord('role_user', [
            'user_id' => 1,
            'role_id' => 1,
        ]);

        $I->haveRecord('role_user', [
            'user_id' => 2,
            'role_id' => 1,
        ]);
    }

    public function tryToResetWithIncompleteInput(FunctionalTester $I)
    {
        $I->wantTo('Reset my password with incomplete input');
        $I->amOnPage('/password/email');
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals('/password/email');
    }

    public function tryToResetWithInvalidEmail(FunctionalTester $I)
    {
        $I->wantTo('Reset my password with invalid email');
        $I->amOnPage('/password/email');
        $I->fillField('email', 'not-exist@not.com');
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals('/password/email');
    }

    public function tryToResetWithValidEmail(FunctionalTester $I)
    {
        $I->wantTo('Reset my password with valid email');
        $I->amOnPage('/password/email');
        $I->fillField('email', 'admin@site.com');
        $I->click('button[type=submit]');
        $I->seeRecord('password_resets', ['email' => 'admin@site.com']);
        $I->seeCurrentUrlEquals('/password/email');
    }

    public function tryToResetPasswordWithInvalidToken(FunctionalTester $I)
    {
        $I->wantTo('Reset my password invalid token');
        $I->amOnPage('/password/reset/asdasd');
        $I->fillField('email', 'admin@site.com');
        $I->fillField('password', '123456');
        $I->fillField('password_confirmation', '123456');
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals('/password/reset/asdasd');
    }

    public function tryToResetPasswordWithExpiredToken(FunctionalTester $I)
    {
        $I->wantTo('Reset my password expired token');
        $I->amOnPage('/password/reset/reset-token2');
        $I->fillField('email', 'admin2@site.com');
        $I->fillField('password', '123456');
        $I->fillField('password_confirmation', '123456');
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals('/password/reset/reset-token2');
    }

    public function tryToResetPasswordWithTokenUnmatchEmail(FunctionalTester $I)
    {
        $I->wantTo('Reset my password token that unmatch email');
        $I->amOnPage('/password/reset/reset-token');
        $I->fillField('email', 'admin2@site.com');
        $I->fillField('password', '123456');
        $I->fillField('password_confirmation', '123456');
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals('/password/reset/reset-token');
    }

    public function tryToResetPasswordWithValidToken(FunctionalTester $I)
    {
        $I->seeRecord('password_resets', ['email' => 'admin@site.com']);
        $I->wantTo('Reset my password invalid token');
        $I->amOnPage('/password/reset/reset-token');
        $I->fillField('email', 'admin@site.com');
        $I->fillField('password', '123456');
        $I->fillField('password_confirmation', '123456');
        $I->click('button[type=submit]');
        $I->dontSeeRecord('password_resets', ['email' => 'admin@site.com']);
        $I->seeAuthentication();
        $I->seeCurrentUrlEquals('/admin/dashboard');
    }
}