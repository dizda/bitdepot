'use strict';

app.controller('WithdrawCtrl', ['$scope', '$location', '$modal', 'Withdraw', function($scope, $location, $modal, Withdraw) {

    $scope.withdraws = Withdraw.query();
    $scope.withdraw  = null;

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

    $scope.verify = function(seed)
    {
        // Create a wallet from the seed submitted
        var wallet = new bitcoin.Wallet(bitcoin.crypto.sha256(seed), bitcoin.networks.bitcoin);

        var accountPubKey = wallet.getExternalAccount().derive(0).pubKey.toHex();

        var identity = getIdentity(accountPubKey, $scope.withdraw.keychain.pub_keys);

        if (!identity) {
            return;
        }

        console.log(identity.name);

        sign(seed);
    };

    /**
     * Sign each inputs with the submitted seed
     *
     * @param seed
     */
    function sign(seed)
    {
        // Recover transaction from rawtransaction created by bitcoind
        var tx  = bitcoin.Transaction.fromHex($scope.withdraw.raw_transaction);

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

//            console.log(txb.build());

            // Return the incomplete rawtransaction
            $scope.withdraw.raw_transaction = txb.buildIncomplete().toHex();
            console.log($scope.withdraw.raw_transaction);
        });


    }

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


}]);