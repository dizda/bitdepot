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
            'raw_signed_transaction',
            'signed_by',
            'is_signed'
        ));

        $resolver->setOptional(array(
            'txid',
            'created_at',
            'updated_at',
            'signed_by',
            'signatures',
            'fees',
            'withdrawed_at',
            'change_address',
            'is_signed',
            'keychain',
            'raw_transaction',
            'total_inputs',
            'total_outputs',
            'withdraw_inputs',
            'withdraw_outputs',
            'raw_signed_transaction'
        ));

        $resolver->setAllowedTypes(array(
            'id'    => ['integer'],
            'txid'  => ['string'],
            'raw_signed_transaction' => ['string'],
            'is_signed'  => ['bool'],
            'signed_by'  => ['string'],
            'signatures' => ['array']
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
