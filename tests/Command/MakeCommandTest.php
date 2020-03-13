<?php

use Lake\Console\Application;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class MakeCommandTest extends TestCase
{
    public function testExecuteMakeCommand()
    {
        $application = new Application(getcwd());

        $command = $application->find('make');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'name' => 'src/App/Http/Request',
            'method' =>  'list',
            '--arguments' => ['Request', 'Int:id']
        ]);
        $output = $commandTester->getDisplay();


        $this->assertContains('create', $output);
    }
}