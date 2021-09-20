<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

class SignupCest
{
    protected $formId = '#form-signup';


    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('auth/signup/request');
    }

    public function signupWithEmptyFields(FunctionalTester $I)
    {
        $I->see('Новый пользователь', '.site-signup h2');
        $I->see('Регистрируйтесь');
        $I->submitForm($this->formId, []);
        $I->seeValidationError('Необходимо заполнить «Логин».');
        $I->seeValidationError('Необходимо заполнить «E-mail».');
        $I->seeValidationError('Необходимо заполнить «Пароль».');

    }

    public function signupWithWrongEmail(FunctionalTester $I)
    {
        $I->submitForm(
            $this->formId, [
                'SignupForm[username]' => 'tester',
                'SignupForm[email]' => 'ttttt',
                'SignupForm[password]' => 'tester_password',
            ]
        );
        $I->dontSee('Необходимо заполнить «Логин».', '.help-block');
        $I->dontSee('Необходимо заполнить «Пароль».', '.help-block');
        $I->see('Значение «E-mail» не является правильным email адресом.', '.help-block');
    }

    public function signupSuccessfully(FunctionalTester $I)
    {
        $I->submitForm($this->formId, [
            'SignupForm[username]' => 'tester',
            'SignupForm[email]' => 'tester.email@example.com',
            'SignupForm[password]' => 'tester_password',
            'SignupForm[check]' => 'nospam',
        ]);

        $I->seeRecord('core\entities\user\User', [
            'username' => 'tester',
            'email' => 'tester.email@example.com',
        ]);

        $I->see('Проверьте электронную почту для получения дальнейших инструкций.');
    }
}
