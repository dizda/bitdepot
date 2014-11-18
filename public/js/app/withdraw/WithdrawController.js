'use strict';

app.controller('WithdrawCtrl', ['$scope', '$location', '$modal', 'Withdraw', function($scope, $location, $modal, Withdraw) {

    $scope.withdraws = Withdraw.query();
    $scope.withdraw  = null;
    $scope.signing   = false;
    $scope.signState  = {
        label: 'Enter your private key',
        signed_by: null,
        error: false,
        success: false
    };

    $scope.openModalSignature = function(withdraw) {

        Withdraw.get({id: withdraw.id}, function(withdraw) {

            $scope.withdraw = withdraw;

            $modal({
                title:    'Withdraw detail',
                template: 'js/app/withdraw/modal.html',
                animation:'am-fade-and-scale',
                placement:'center',
                show:     true,
                scope:    $scope
            });


        });

    };

    /**
     * Verify the submitted seed before signing
     *
     * @param seed
     */
    $scope.verify = function(seed)
    {
        $scope.initSignature();

        // Create a wallet from the seed submitted
        var wallet = new bitcoin.Wallet(bitcoin.crypto.sha256(seed), bitcoin.networks.bitcoin);

        var accountPubKey = wallet.getExternalAccount().derive(0).pubKey.toHex();

        var identity = getIdentity(accountPubKey, $scope.withdraw.keychain.pub_keys);

        if (!identity) {
            $scope.signState.label = 'Unknown private key.';
            $scope.signState.error = true;
            $scope.signing         = false;

            return;
        }

        $scope.signState.signed_by = identity.name;
        $scope.signState.label = 'Signing with ' + identity.name + '...';

        $scope.withdraw.signed_by = identity.value;

        sign(seed);
    };

    /**
     * Sign each inputs with the submitted seed
     *
     * @param seed
     */
    function sign(seed)
    {
        // Recover transaction from raw_transaction created by bitcoind || or raw_signed_transaction
        var tx  = bitcoin.Transaction.fromHex(getRawTransaction($scope.withdraw));

        // Build it into Transaction Builder
        var txb = bitcoin.TransactionBuilder.fromTransaction(tx);

        // Create a wallet from the seed submitted
        var wallet = new bitcoin.Wallet(bitcoin.crypto.sha256(seed), bitcoin.networks.bitcoin);

        // We sign the transaction for each inputs
        angular.forEach($scope.withdraw.withdraw_inputs, function(input, index) {
            var privKey;

            // Finding the good private key according to the derivation
            if (input.address.is_external === true) {
                privKey = wallet.getExternalAccount().derive(input.address.derivation).privKey;
            } else {
                privKey = wallet.getInternalAccount().derive(input.address.derivation).privKey;
            }

            console.log(privKey.toWIF());

            // Sign the input
            txb.sign(index, privKey, bitcoin.Script.fromHex(input.address.redeem_script));


            try {
                $scope.withdraw.raw_signed_transaction = txb.build().toHex();
                $scope.withdraw.is_signed = true;
                console.log('Successfully signed.');
            } catch (e) {
                if ('Transaction is missing signatures' === e.message) {
                    // Normal, because every inputs not signed yet.

                    $scope.withdraw.raw_signed_transaction = txb.buildIncomplete().toHex();
                } else if ('Not enough signatures provided' === e.message) {
                    console.log('Not enough signatures provided');

                    $scope.withdraw.raw_signed_transaction = txb.buildIncomplete().toHex();
                }
            }

            // send the signed transaction there
            console.log($scope.withdraw.raw_signed_transaction);

        });


        $scope.withdraw.$save(function() {
            $scope.signState.label   = 'Signed by ' + $scope.signState.signed_by + '.';
            $scope.signState.success = true;
        }, function() {
            $scope.signState.label = 'Error, probably because you\'re already signed the transaction.';
            $scope.signState.error = true;
        });

        $scope.signing    = false;
    }

    $scope.initSignature = function() {
        $scope.signing    = true;
        $scope.signState  = {
            label: 'Enter your private key',
            signed_by: null,
            error: false,
            success: false
        };
    };

    /**
     * @param seed    The public key submitted by the user
     * @param pubKeys Public keys that can sign the withdraw
     *
     * @returns {boolean|Object}
     */
    function getIdentity(seed, pubKeys)
    {
        var identity = false;

        pubKeys.forEach(function(pubKey) {
            if (pubKey.value === seed) {
                identity = pubKey;
            }
        });

        return identity;
    }

    /**
     * @param {Withdraw} withdraw
     *
     * @returns {String}
     */
    function getRawTransaction(withdraw)
    {
        if (withdraw.raw_signed_transaction) {
            return withdraw.raw_signed_transaction;
        }

        return withdraw.raw_transaction;
    }


}]);