<?php

namespace Lake\Tests\Support\Fixture;

use Lake\Generator\LakeGenerator;

class LakeGeneratorFixture 
{
    public static function fixture()
    {
        $generator = new LakeGenerator(
            false,
            'app\Tests\TestClass',
            ['app' => 'App']
        );

        $generator->addMethod('show', [
            ['int', 'id'],
            ['Request', 'request']
        ]);

        $generator->addUses(['Lake\Uses\Request']);

        return $generator;
    }
}