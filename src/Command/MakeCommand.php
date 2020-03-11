<?php

namespace Lake\Command;

use Lake\Finder\Finder;
use Lake\Generator\LakeGenerator;
use Lake\Printer\ClassPrinter;
use Lake\Validation\ClassValidation;
use Lake\Validation\ParameterValidation;
use Lake\Validation\TypeValidation;
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
    protected static $defaultName = 'make';
   
    /** @var Filesystem */
    private $filesystem;
    private $config;
    private $classPrinter;

    public function __construct(array $config)
    {
        parent::__construct();
        $this->config = $config;
        $this->classPrinter = new ClassPrinter(new FileGenerator, new Filesystem);
    }

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
        $classPath = $input->getArgument('name');
        $methodName = $input->getArgument('method');

        $arguments = $input->getOption('arguments');

        $helper = $this->getHelper('question');


        $selectedUses = [];
        $parameters = [];

        foreach ($arguments as $argument) {

            list($type, $varName) = ParameterValidation::validate($argument);

            if (!TypeValidation::isPhpType($type)) {
                $uses = Finder::findClassByName($type);

                if (count($uses) == 1) {
                    $selectedUses[] = current($uses);
                }

                if (count($uses) > 1) {
                    $question = new ChoiceQuestion(
                        sprintf('Please select a class for the "%s" parameter.', $type),
                        $uses
                    );

                    $selectedUses[] = $helper->ask($input, $output, $question);
                }
            }

            $parameters[] = [$type, $varName]; 
        }

        $classPath = ClassValidation::validate($classPath);

        $lake = new LakeGenerator(
            $classPath,
            dirname(str_replace('.'.DS.$this->config['src']['dir'], $this->config['src']['namespace'], $classPath)),
            $methodName,
            $parameters,
            $selectedUses
        );
    
        $this->classPrinter->printFile($lake->getClass(), $classPath);
        $this->classPrinter->printFile(
            $lake->getTest(),
            str_replace('src', 'test', $classPath),
            basename($classPath).'Test'
        );

        return 0;
    }
}