<?php

namespace Dizda\Bundle\AppBundle\Tests;

abstract class BasicUnitTest extends \PHPUnit\Framework\TestCase
{
    protected $prophet;

    protected function setup()
    {
        $this->prophet = new \Prophecy\Prophet;
    }

    protected function tearDown()
    {
        $this->prophet->checkPredictions();
    }

//    protected function prophesize($thing)
//    {
//        return $this->prophet->prophesize($thing);
//    }
}