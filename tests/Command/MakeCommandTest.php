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
            'name' => 'app/Http/Request',
            'method' =>  'list',
            '--arguments' => ['Request', 'Int:id'],
            '--dry-run' => true
        ]);
        $output = $commandTester->getDisplay();

        $this->assertEquals(file_get_contents(__DIR__.DS.'makeCommand.txt'), $output);
    }
}