'use strict';

app.controller('WithdrawCtrl', ['$scope', '$location', '$modal', 'Withdraw', function($scope, $location, $modal, Withdraw) {

    $scope.withdraws = Withdraw.query();
    $scope.withdraw  = null;

    $scope.openModalSignature = function(withdraw) {

//        var tx = new bitcoin.Transaction();
//        console.log(tx);
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
     * Sign each inputs with the submitted seed
     *
     * @param seed
     */
    $scope.sign = function(seed)
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
            }


            console.log(privKey.toWIF());

            // Sign the input
            txb.sign(index, privKey, bitcoin.Script.fromHex(input.address.redeem_script));

//            console.log(txb.build());

            // Return the incomplete rawtransaction
            $scope.withdraw.raw_transaction = txb.buildIncomplete().toHex();
            console.log($scope.withdraw.raw_transaction);
        });


    };


}]);