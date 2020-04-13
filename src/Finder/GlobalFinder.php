<?php

namespace Lake\Finder;

use Lake\Config;
use Lake\Exception\LakeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class GlobalFinder
{
    private $input;
    private $output;
    private $helper;
    private $config;

    private static $instance;

    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        $helper,
        Config $config
    )
    {
        $this->input = $input;
        $this->output = $output;
        $this->helper = $helper;
        $this->config = $config;

        self::$instance = $this;
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
           throw new LakeException('There is no instance of the finder');
        }

        return static::$instance;
    }

    public function getInput()
    {
        return $this->input;  
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getHelper()
    {
        return $this->helper;
    }

    public static function findUse($name)
    {
        $finder = self::getInstance();
        $uses = Finder::findClassByName($name, $finder->getConfig());

        if (count($uses) == 1) {
            return current($uses);
        }

        if (count($uses) > 1) {
            $question = new ChoiceQuestion(
                sprintf('Please select a class for the %s.', $name),
                $uses
            );

            return $finder->getHelper()->ask(
                $finder->getInput(),
                $finder->getOutput(),
                $question
            );
        }
    }

}