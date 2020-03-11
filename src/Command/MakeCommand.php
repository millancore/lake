<?php

namespace Lake\Command;

use Lake\Finder\Finder;
use Lake\Generator\LakeGenerator;
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
   
    private $filesystem;
    private $config;

    public function __construct(array $config)
    {
        parent::__construct();
        $this->config = $config;
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
        $className = $input->getArgument('name');
        $methodName = $input->getArgument('method');

        $arguments = $input->getOption('arguments');

        $helper = $this->getHelper('question');


        $selectedUses = [];
        $parameters = [];

        foreach ($arguments as $argument) {

            list($type, $varName) = ParameterValidation::validate($argument);

            if (!TypeValidation::isPhpType($type)) {
                $uses = Finder::findClassByName($type);

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

        if (!strpos($className, ':') === false) {
            $classParts = array_map(function($section){
                return ucfirst($section);
            }, explode(':', $className) );
            
            $className = implode(DIRECTORY_SEPARATOR, $classParts);
        }

        $class = new LakeGenerator;
        $class->addMethod($methodName, $arguments);
        $class = $class->generate(basename($className));

        $class->addUse('Lake\\Finder\\SourceFinder');

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