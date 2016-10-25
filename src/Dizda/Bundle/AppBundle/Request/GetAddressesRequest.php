<?php

namespace Dizda\Bundle\AppBundle\Request;

use Dizda\Bundle\AppBundle\Request\AbstractRequest;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GetAddressesRequest
 */
class GetAddressesRequest extends AbstractRequest
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
            'show',
            'currentPage',
            'maxPerPage'
        ));

        $resolver->setAllowedTypes(array(
            'show'        => ['string'],
            'currentPage' => ['integer'],
            'maxPerPage'  => ['integer', 'null']
        ));

        $resolver->setDefaults(array(
            'show' => 'all',
            'currentPage' => 1,
            'maxPerPage'  => 50
        ));

        $resolver->setAllowedValues(array(
            'show' => ['all', 'only_used', 'positive_balance']
        ));
    }
}
