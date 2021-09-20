<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

class HomeCest
{
    public function checkOpen(FunctionalTester $I)
    {
        $I->amOnPage('site/index');
        $I->see('test.ru');
        $I->seeLink('О компании');
        $I->click('О компании');
        $I->see('О нашей компании');
    }
}
