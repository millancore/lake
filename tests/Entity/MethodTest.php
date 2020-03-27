<?php

use Lake\Entity\Method;
use PHPUnit\Framework\TestCase;

class MethodTest extends TestCase
{

    public function testIsEmptyConstruct()
    {
        $method = new Method();

        $this->assertTrue($method->isEmptyConstruct());
    }


}

