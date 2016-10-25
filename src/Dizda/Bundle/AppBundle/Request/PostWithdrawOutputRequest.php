<?php

namespace Dizda\Bundle\AppBundle\Request;

use Dizda\Bundle\AppBundle\Request\AbstractRequest;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostWithdrawOutputRequest
 */
class PostWithdrawOutputRequest extends AbstractRequest
{

    public function __construct(array $options = array())
    {
        parent::__construct($options);
    }

    protected function setDefaultOptions(OptionsResolver $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
            'application_id',
            'to_address',
            'amount'
        ));

        $resolver->setOptional(array(
            'reference',
            'is_accepted'
        ));

        $resolver->setAllowedTypes(array(
            'application_id' =>  ['integer'],
            'to_address'     =>  ['string'],
            'amount'         =>  ['string'],
            'is_accepted'    =>  ['bool'],
            'reference'      =>  ['string', 'integer', 'null']
        ));

        $resolver->setDefaults(array(
            'is_accepted' => true,
            'reference'   => null
        ));
    }
}
