<?php

namespace core\tests\unit\entities\user;

use Codeception\Test\Unit;
use core\entities\user\User;

class SignupTest extends Unit
{
    public function testSuccess()
    {
        $user = User::requestSignup(
            $username = 'username',
            $email = 'email@site.com',
            $password = 'password'
        );

        $this->assertEquals($username, $user->username);
        $this->assertEquals($email, $user->email);
        $this->assertNotEmpty($user->password_hash);
        $this->assertNotEquals($password, $user->password_hash);
        $this->assertNotEmpty($user->created_at);
        $this->assertNotEmpty($user->auth_key);
        $this->assertFalse($user->isActive());
        $this->assertTrue($user->isWait());
    }
}
