<?php

namespace Lake\Command;

use Lake\Config;
use Lake\Entity\LakeClass;
use Lake\Entity\Method;
use Lake\Finder\GlobalFinder;
use Lake\Generator\TestGenerator;
use Lake\Printer\Printer;
use Lake\Resolver\NamespaceResolver;
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
    private $printer;

    public function __construct(Config $config)
    {
        parent::__construct();
        $this->config = $config;
        $this->printer = new Printer(new Filesystem);
    }

    protected function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The path + class name of the file.');
        $this->addArgument('method', InputArgument::OPTIONAL, 'The method name of the file.', null);

        $this->addOption('extends', 'e', InputOption::VALUE_OPTIONAL, 'Extends class', null);

        $this->addOption('arguments', 'a', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The Arguments', []);
        $this->addOption('return', 'r', InputOption::VALUE_OPTIONAL, 'Return Type', null);
        $this->addOption('docblock', 'd', InputOption::VALUE_OPTIONAL, 'DocBlock description', null);


        $this->addOption('dry-run', null, InputOption::VALUE_OPTIONAL, 'Screen Mode', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getHelper('question');

        new GlobalFinder($input, $output, $questionHelper, $this->config);

        $method = new Method(
            $input->getArgument('method'),
            $input->getOption('arguments'),
            $input->getOption('return'),
            $input->getOption('docblock')
        );

        $class = new LakeClass($input->getArgument('name'), $input->getOption('extends'));

        if ($method->isEmptyConstruct()) {
            if ($this->addEmptyConstruct($questionHelper, $input, $output)) {
                $class->addMethod($method);
            }
        } else {
            $class->addMethod($method);
        }

        if(!$class->exists()) {
            $class->setNamespace(
                NamespaceResolver::resolve($class->getPath(), $this->config)
            );
        }

        if ($input->getOption('dry-run') !== false) {
            $output->write($class->generate());
            return 0;
        }

        $test = (new TestGenerator($class, $this->config))->getTest();

        $this->printer->print($class, $class->getFullPath());
        $this->printer->print($test, $test->getFullPath());


        $output->writeln(sprintf('code: %s', $class->getFullPath()));
        $output->writeln(sprintf('test: %s', $test->getFullPath()));

        return 0;
    }

    private function addEmptyConstruct($question, $input, $output)
    {
        $response = $question->ask($input, $output, new ChoiceQuestion(
            'Do you want add empty __constructor?',
            [1 => 'Yes', 2 => 'No']   
        ));

        if ($response == 'No') {
            return false;
        }

        return true;
    }
}
