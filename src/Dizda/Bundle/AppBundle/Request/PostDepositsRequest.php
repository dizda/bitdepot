<?php

namespace Dizda\Bundle\AppBundle\Request;

use Dizda\Bundle\AppBundle\Request\AbstractRequest;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class PostDepositsRequest
 */
class PostDepositsRequest extends AbstractRequest
{

    public function __construct(array $options = array())
    {
        parent::__construct($options);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
            'application_id',
            'type'
        ));

        $resolver->setOptional(array(
            'amount_expected',
            'reference'
        ));

        $resolver->setAllowedTypes(array(
            'application_id' =>  ['integer'],
            'type'           =>  ['integer'],
            'amount_expected' => ['string'],
            'reference'       => ['string', 'null']
        ));

        $resolver->setDefaults(array(
            'reference' => null
        ));
    }
}
