<?php

namespace Dizda\Bundle\AppBundle\Request;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractRequest
 *
 * Perform a custom validation of a Request
 *
 * @package Bambou\Bundle\AdvertisingBundle\Request
 */
abstract class AbstractRequest
{
    public $options;

    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function setDefaultOptions(OptionsResolver $resolver)
    {

    }
}
