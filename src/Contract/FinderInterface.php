<?php

namespace Lake\Contract;

interface FinderInterface
{
    public function findClass(string $className ) : array ;
}
