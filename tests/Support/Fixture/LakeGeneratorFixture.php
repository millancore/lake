<?php

namespace Lake\Tests\Support\Fixture;

use Lake\Generator\LakeGenerator;

class LakeGeneratorFixture 
{
    public static function fixture()
    {
        $generator = new LakeGenerator(
            'TestClass',
            'Lake\Tests\Name',
            'show',
            [
                ['int', 'id'],
                ['Request', 'request']
            ],
            ['Lake\Uses\Request']
        );

        return $generator;
    }
}