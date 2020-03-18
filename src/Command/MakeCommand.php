<?php

namespace Lake\Command;

use Lake\Config;
use Lake\Finder\Finder;
use Lake\Generator\LakeGenerator;
use Lake\Generator\TestGenerator;
use Lake\Printer\ClassPrinter;
use Lake\Validation\ClassValidation;
use Lake\Validation\ParameterValidation;
use Lake\Validation\TypeValidation;
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

    private $config;
    private $classPrinter;

    public function __construct(Config $config)
    {
        parent::__construct();
        $this->config = $config;
        $this->classPrinter = new ClassPrinter(new Filesystem);
    }

    protected function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The path + class name of the file.');
        $this->addArgument('method', InputArgument::OPTIONAL, 'The method name of the file.', '__construct');

        $this->addOption('extends', 'e', InputOption::VALUE_OPTIONAL, 'Extends class', null);

        $this->addOption('arguments', 'a', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The Arguments', []);
        $this->addOption('return', 'r', InputOption::VALUE_OPTIONAL, 'Return Type', 'void');
        $this->addOption('docblock', 'd', InputOption::VALUE_OPTIONAL, 'DocBlock description', null);


        $this->addOption('dry-run', null, InputOption::VALUE_OPTIONAL, 'Screen Mode', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $classPath = $input->getArgument('name');
        $methodName = $input->getArgument('method');

        $extends = $input->getOption('extends');
        $arguments = $input->getOption('arguments');
        $docblock = $input->getOption('docblock');
        $return = $input->getOption('return');


        $selectedUses = [];
        $parameters = [];

        foreach ($arguments as $argument) {

            list($type, $varName) = ParameterValidation::validate($argument);

            if (!TypeValidation::isPhpType($type)) {
               $selectedUses[] = $this->findUse($type, 'parameter', $input, $output);
            }

            $parameters[] = [$type, $varName];
        }

        if (!is_null($extends)) {
            $extends = $selectedUses[] = $this->findUse($extends, 'extends', $input, $output);
        }

        if (!TypeValidation::isPhpType($return)) {
            $selectedUses[] = $this->findUse($return, 'return', $input, $output);
        }


        list($exists, $classPath) = ClassValidation::validate($classPath);

        $lake = new LakeGenerator($exists, $classPath, $this->config->src);
        $test = new TestGenerator($classPath, $methodName, $this->config);

        $lake->addUses($selectedUses);
        $lake->addMethod($methodName, $parameters, $return, $docblock);
        $lake->setExtends($extends);

        if ($input->getOption('dry-run') !== false) {
            $output->write($lake->getFile()->generate());
            return 0;
        }

        $this->classPrinter->printFile($lake->getFile(), $classPath);
        $this->classPrinter->printFile($test->getFile(), $test->getPath());


        $output->writeln(sprintf('code: %s.php', $classPath));
        $output->writeln(sprintf('test: %s.php', $test->getPath()));

        return 0;
    }


    private function findUse($name, $type, $input, $output)
    {
        $helper = $this->getHelper('question');
        $uses = Finder::findClassByName($name, $this->config);

        if (count($uses) == 1) {
            return current($uses);
        }

        if (count($uses) > 1) {
            $question = new ChoiceQuestion(
                sprintf('Please select a class for the "%s" %s.', $name, $type),
                $uses
            );

            return $helper->ask($input, $output, $question);
        }
    }
}
