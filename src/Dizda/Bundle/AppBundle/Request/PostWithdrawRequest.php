<?php

namespace Dizda\Bundle\AppBundle\Request;

use Dizda\Bundle\AppBundle\Request\AbstractRequest;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostWithdrawRequest
 */
class PostWithdrawRequest extends AbstractRequest
{

    public function __construct(array $options = array())
    {
        parent::__construct($options);
    }

    protected function setDefaultOptions(OptionsResolver $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
            'id',
            'json_signed_transaction',
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
            'json_transaction',
            'total_inputs',
            'total_outputs',
            'withdraw_inputs',
            'withdraw_outputs',
            'raw_signed_transaction'
        ));

        $resolver->setAllowedTypes(array(
            'id'    => ['integer'],
            'txid'  => ['string'],
            'raw_signed_transaction' => ['string', 'null'],
            'json_signed_transaction' => ['string'],
            'is_signed'  => ['bool'],
            'signed_by'  => ['string'],
            'signatures' => ['array']
        ));

        $resolver->setDefaults(array(
            'raw_signed_transaction' => null
        ));

        /*$resolver->setAllowedValues(array(
            'duration' => ['newspaper', 'author', 'post'] // different mysql ids ?
        ));*/
    }
}
