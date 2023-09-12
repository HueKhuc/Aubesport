<?php

namespace App\Tests\Entity;
use App\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase 
{
    public function testValidEntity() 
        {
            $user = (new User())
                ->setEmail('lalalalala@gmail.com');
            $this->assertSame('lalalalala@gmail.com',$user->getEmail());    
        }
}
