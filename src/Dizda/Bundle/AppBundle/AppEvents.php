<?php

namespace Dizda\Bundle\AppBundle;

/**
 * Contains all event related to App that are dispatched.
 */
class AppEvents
{
    /**
     */
    const ADDRESS_TRANSACTION_CREATE   = 'app.address_transaction_create';

    const DEPOSIT_UPDATED   = 'app.deposit_updated';

    /**
     * On withdraw creation
     */
    const WITHDRAW_CREATE   = 'app.withdraw_create';
    const WITHDRAW_SEND     = 'app.withdraw_send';

}
