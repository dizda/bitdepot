<?php

namespace Dizda\Bundle\AppBundle\Request;

use Dizda\Bundle\AppBundle\Request\AbstractRequest;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class PostWithdrawRequest
 */
class PostWithdrawRequest extends AbstractRequest
{

    public function __construct(array $options = array())
    {
        parent::__construct($options);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
            'id',
            'is_signed',
            'keychain',
            'raw_transaction',
            'total_inputs',
            'total_outputs',
            'withdraw_inputs',
            'withdraw_outputs'
        ));

        $resolver->setOptional(array(
            'raw_signed_transaction',
            'txid',
            'created_at',
            'updated_at',
            'signed_by',
            'signatures'
        ));

        $resolver->setAllowedTypes(array(
            'id'    => ['integer'],
            'txid'  => ['string'],
            'raw_signed_transaction' => ['string'],
            'signed_by' => ['string'],
            'signatures' => ['array'],
        ));

        /*$resolver->setDefaults(array(
            'typeOfContentSelected' => 'newspaper',
            'network'=> 'all',
        ));*/

        /*$resolver->setAllowedValues(array(
            'duration' => ['newspaper', 'author', 'post'] // different mysql ids ?
        ));*/
    }
}
