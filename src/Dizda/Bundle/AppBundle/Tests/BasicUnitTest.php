<?php

namespace AppBundle\Tests;

abstract class BasicUnitTest extends \PHPUnit_Framework_TestCase
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