<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

/* @var $scenario \Codeception\Scenario */
class ContactCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage(['contact/index']);
    }

    public function checkContact(FunctionalTester $I)
    {
        $I->see('Связаться с нами', 'h1');
    }

    public function checkContactSubmitNoData(FunctionalTester $I)
    {
        $I->submitForm('#contact-form', []);
        $I->see('Связаться с нами', 'h1');
        $I->seeValidationError('Необходимо заполнить «Ваше имя».');
        $I->seeValidationError('Необходимо заполнить «E-mail».');
        $I->seeValidationError('Необходимо заполнить «Тема».');
        $I->seeValidationError('Необходимо заполнить «Сообщение».');
        $I->seeValidationError('Неправильный проверочный код.');
    }

    public function checkContactSubmitNotCorrectEmail(FunctionalTester $I)
    {
        $I->submitForm('#contact-form', [
            'ContactForm[name]' => 'tester',
            'ContactForm[email]' => 'tester.email',
            'ContactForm[subject]' => 'test subject',
            'ContactForm[body]' => 'test content',
            'ContactForm[verifyCode]' => 'testme',
        ]);
        $I->seeValidationError('Значение «E-mail» не является правильным email адресом.');
        $I->dontSeeValidationError('Name cannot be blank');
        $I->dontSeeValidationError('Subject cannot be blank');
        $I->dontSeeValidationError('Body cannot be blank');
        $I->dontSeeValidationError('The verification code is incorrect');
    }

    public function checkContactSubmitCorrectData(FunctionalTester $I)
    {
        $I->submitForm('#contact-form', [
            'ContactForm[name]' => 'tester',
            'ContactForm[email]' => 'tester@example.com',
            'ContactForm[subject]' => 'test subject',
            'ContactForm[body]' => 'test content',
            'ContactForm[verifyCode]' => 'testme',
            'ContactForm[check]' => 'nospam',
        ]);
        $I->seeEmailIsSent();
        $I->see('Ваше сообщение успешно отправлено');
    }
}
