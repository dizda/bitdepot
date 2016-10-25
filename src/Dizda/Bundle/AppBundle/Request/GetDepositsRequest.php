<?php

namespace Dizda\Bundle\AppBundle\Request;

use Dizda\Bundle\AppBundle\Request\AbstractRequest;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GetDepositsRequest
 */
class GetDepositsRequest extends AbstractRequest
{

    public function __construct(array $options = array())
    {
        parent::__construct($options);
    }

    protected function setDefaultOptions(OptionsResolver $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
        ));

        $resolver->setOptional(array(
            'currentPage',
            'maxPerPage'
        ));

        $resolver->setAllowedTypes(array(
            'currentPage' => ['integer'],
            'maxPerPage'  => ['integer', 'null']
        ));

        $resolver->setDefaults(array(
            'currentPage' => 1,
            'maxPerPage'  => 50
        ));

        $resolver->setAllowedValues(array(

        ));
    }
}
