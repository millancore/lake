<?php

namespace Lake\Command;

use Lake\Generator\LakeGenerator;
use Laminas\Code\Generator\FileGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Filesystem\Filesystem;

class MakeCommand extends Command
{
    const DS = DIRECTORY_SEPARATOR;

    protected static $defaultName = 'make';
    private $filesystem;

    protected function configure()
    {
        $this->filesystem = new Filesystem();

        $this->addArgument('name', InputArgument::REQUIRED, 'The path + class name of the file.');
        $this->addArgument('method', InputArgument::REQUIRED, 'The method name of the file.');
        
        $this->addOption('arguments', 'a', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The Arguments', []);
        $this->addOption('return', 'r', InputOption::VALUE_OPTIONAL , 'Return Type', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
       $args = $input->getOption('arguments');
    
        $nameClass = $input->getArgument('name');

        $classParts = array_map(function($section){
            return ucfirst($section);
        }, explode(':', $nameClass) );
        
        $className = implode(self::DS, $classParts);

        $method = $input->getArgument('method');

        $class = new LakeGenerator;
        $class->addMethod($method, $args);
        $class = $class->generate(end($classParts));

        $class->addUse('Symfony\Component\Console\Command\Command');

        $file = new FileGenerator([
            'classes' => [$class]
        ]);

    
        # Class
        $this->filesystem->dumpFile($className.'.php', $file->generate());

        # Test
        //$this->filesystem->appendToFile('test'.self::DS.$className.'Test.php', 'test!!');

        return 0;
    }
}