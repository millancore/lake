<?php

use Lake\Console\Application;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class DumpConmmandTest extends TestCase
{
    public function testExecuteCommand()
    {
        $application = new Application(getcwd());

        $command = $application->find('dump');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);
        $output = $commandTester->getDisplay();


        $this->assertContains('Successful!', $output);

    }
}