<?php

namespace common\tests\unit\forms;

use common\forms\auth\ResetPasswordForm;

class ResetPasswordFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;

    public function testCorrectToken()
    {
        $form = new ResetPasswordForm();
        $form->password = 'new-password';
        expect_that($form->validate());
    }
}

