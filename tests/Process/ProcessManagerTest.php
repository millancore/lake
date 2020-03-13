<?php

use Lake\Process\ProcessManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class ProcessManagerTest extends TestCase
{
    /** @var ProcessManager */
    private $processManager;

    public function setUp() : void
    {
        $this->processManager = new ProcessManager(new Filesystem);
    }

    public function testRegisterNewCommand()
    {
        $this->processManager->register('ls', ['ls']);

        $this->assertEquals(['ls'], $this->processManager->getCommand('ls'));
    }

    public function testRunRegisterdCommand()
    {
        $this->processManager->register('ls', ['ls']);
    
        $process = $this->processManager->ls();
        
        $this->assertInstanceOf(Process::class, $process);
    }

    public function testRunNotRegisterCommand()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The command "invalid" has not been registered');

        $this->processManager->invalid();
    }

    public function testAddPhpCommand()
    {
        $this->processManager->addPhpCommand('phpVersion', ['--version']);

        $command = $this->processManager->getCommand('phpVersion');

        $this->assertContains('php', $command[0]);
        $this->assertEquals('--version', $command[1]);
    }

    public function testAddComposerCommand()
    {
        $this->processManager->addComposerCommand('composerDump', ['dump-autoload']);

        $command = $this->processManager->getCommand('composerDump');

        $this->assertContains('composer', $command[0]);
        $this->assertEquals('dump-autoload', $command[1]);
    }

    public function testGetWorkingPath()
    {
        $this->assertNull($this->processManager->getWorkingPath());
    }


    public function testSetWorkingPath()
    {
        $this->processManager->setWorkingPath(__DIR__);

        $this->assertEquals(__DIR__, $this->processManager->getWorkingPath());
    }
}