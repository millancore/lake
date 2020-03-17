<?php

namespace Lake;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Config
{
    private $options;

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        
        $this->options = $resolver->resolve($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $composer = $this->loadComposerFile(getcwd());

        $resolver->setDefined(['exepath', 'mode', 'src', 'test']);

        $resolver->setDefaults([
            'exepath' => getcwd(),
            'mode' => 'loose',
            'src' => array_flip($composer['autoload']['psr-4']),
        ]);

        $resolver->setDefault('test', function (OptionsResolver $spoolResolver) {
            $spoolResolver->setDefaults([
                'dir' => 'tests',
                'extends' => 'PHPUnit\Framework\TestCase',
                'style' => 'camel'
            ]);
        
        });

    }

    public function loadComposerFile(string $executePath)
    {
        $expectComposerFile = $executePath.DS.'composer.json';

        if (!file_exists($expectComposerFile)) {
            throw new FileNotFoundException(sprintf('composer.json not found in %s', $executePath));
        }

        $composer = json_decode(file_get_contents($expectComposerFile), true);
        
        return $composer;
    }

    public function __get($name)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }

        return null;
    }


    public function getOptions()
    {
        return $this->options;
    }
}
