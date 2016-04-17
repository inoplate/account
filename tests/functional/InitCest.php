<?php

class InitCest
{
    // tests
    public function initAccount(FunctionalTester $I)
    {
        $I->runShellCommand('../../../artisan inoplate:account-init');
    }
}
