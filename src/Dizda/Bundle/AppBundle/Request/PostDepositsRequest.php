<?php

namespace Dizda\Bundle\AppBundle\Request;

use Dizda\Bundle\AppBundle\Request\AbstractRequest;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostDepositsRequest
 */
class PostDepositsRequest extends AbstractRequest
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
            'type'
        ));

        $resolver->setDefined(array(
            'amount_expected',
            'amount_expected_fiat',
            'reference'
        ));

        $resolver->setAllowedTypes(array(
            'application_id' =>  ['integer'],
            'type'           =>  ['integer'],
            'amount_expected' => ['string'],
            'amount_expected_fiat' => ['array'],
            'reference'       => ['string', 'null']
        ));

        $resolver->setDefaults(array(
            'reference' => null
        ));

        $resolver->setAllowedValues('amount_expected_fiat', function ($value)  {
            if (isset($value['currency']) && isset($value['amount'])) {
                return true;
            }

            return false;
        });
    }
}
