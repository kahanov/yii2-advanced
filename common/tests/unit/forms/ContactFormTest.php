<?php

namespace common\tests\unit\forms;

use frontend\forms\ContactForm;

class ContactFormTest extends \Codeception\Test\Unit
{
    public function testSuccess()
    {
        $form = new ContactForm();

        $form->attributes = [
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'subject' => 'very important letter subject',
            'body' => 'body of current message',
        ];

        expect_that($form->validate(['name', 'email', 'subject', 'body']));
    }
}

