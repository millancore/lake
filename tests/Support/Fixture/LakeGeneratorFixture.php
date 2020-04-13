<?php

namespace Lake\Tests\Support\Fixture;

use Lake\Entity\LakeClass;
use Lake\Entity\Method;

class LakeGeneratorFixture 
{
    public static function fixture()
    {
        $class = new LakeClass('src\Tests\TestClass');
        $class->addMethod(new Method(
            'show',
            [
                'int:id',
                'Type'
            ]
        ));

        return $class;
    }
}