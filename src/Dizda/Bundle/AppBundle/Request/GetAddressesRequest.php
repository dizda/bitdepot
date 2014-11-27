<?php

namespace Dizda\Bundle\AppBundle\Request;

use Dizda\Bundle\AppBundle\Request\AbstractRequest;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class GetAddressesRequest
 */
class GetAddressesRequest extends AbstractRequest
{

    public function __construct(array $options = array())
    {
        parent::__construct($options);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
        ));

        $resolver->setOptional(array(
            'show'
        ));

        $resolver->setAllowedTypes(array(
            'show' =>  ['string']
        ));

        $resolver->setDefaults(array(
            'show' => 'all',
        ));

        $resolver->setAllowedValues(array(
            'show' => ['all', 'only_used', 'positive_balance']
        ));
    }
}
