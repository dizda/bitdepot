'use strict';

app.controller('SignModalCtrl', ['$scope', 'Withdraw', function($scope, Withdraw) {

    $scope.signing   = false;
    $scope.signState = {
        label: 'Enter your private key',
        signed_by: null,
        error: false,
        success: false
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
//        var wallet = new bitcoin.Wallet(bitcoin.crypto.sha256(seed), bitcoin.networks.bitcoin);
        var wallet = bitcore.HDPrivateKey.fromSeed(bitcoin.crypto.sha256(seed), bitcore.Networks.livenet);

//        wallet.derive("m/44'/0'/0'/0/0")
        var accountPubKey = wallet
            .derive(44, true) // BIP44 constant
            .derive(0, true)  // bitcoin
            .derive(0, true)  // # application
            .derive(0)        // chain
            .derive(0)        // address
        .publicKey.toString();

        console.log(accountPubKey);
//        var accountPubKey = wallet.getExternalAccount().derive(0).pubKey.toHex();

        // search identity according to the public key submitted
        var identity = _.find($scope.withdraw.keychain.identities, {public_key: accountPubKey});

        if (!identity) {
            $scope.signState.label = 'Unknown public key.';
            $scope.signState.error = true;
            $scope.signing         = false;

            return;
        }

        $scope.signState.signed_by = identity.name;
        $scope.signState.label     = 'Signing with ' + identity.name + '...';

        $scope.withdraw.signed_by = identity.public_key;

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
        var wallet = bitcore.HDPrivateKey.fromSeed(bitcoin.crypto.sha256(seed), bitcore.Networks.livenet);

        // Loop on each inputs
        txb.tx.ins.forEach(function(input, i) {

            // get the txid of the input
            var txid   = bitcoin.bufferutils.reverse(input.hash).toString('hex');
            var wInput = _.find($scope.withdraw.withdraw_inputs, {
                txid: txid,
                index: input.index // match the correct input with <txid & index>
            });

            // Finding the good private key according to the derivation
            var privKey = getPrivateKey(wallet, wInput.address.application.id, wInput.address.is_external, wInput.address.derivation);

            console.log(privKey.toWIF());

            // Sign the input
            txb.sign(i, privKey, bitcoin.Script.fromHex(wInput.address.redeem_script));

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
                } else {
                    console.log(e);
                }
            }

            // print the signed transaction there
            console.log($scope.withdraw.raw_signed_transaction);
        });


        $scope.withdraw.$save(function(response) {
            $scope.signState.label   = 'Signed by ' + $scope.signState.signed_by + '.';
            $scope.signState.success = true;

            $scope.$emit('withdraw:update', response);
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
     * @param {Withdraw} withdraw
     *
     * @returns {String}
     */
    function getRawTransaction(withdraw)
    {
        return withdraw.raw_signed_transaction || withdraw.raw_transaction;
    }

    /**
     * @param {Wallet}  wallet
     * @param {Number}  application Application id
     * @param {Boolean} isExternal
     * @param {Number}  address
     *
     * @returns {HDNode.privKey|*|.HDNode.privKey}
     */
    function getPrivateKey(wallet, application, isExternal, address)
    {

        console.log(application);
        console.log(isExternal);
        console.log(address);
        var privateKey = wallet
            .derive(44, true) // BIP44 constant
            .derive(0, true)  // bitcoin
            .derive(application, true)  // # application
            .derive(isExternal ? 0 : 1)        // chain
            .derive(address)        // address
        ;

        // Convert bitcore privateKey to bitcoinjs-lib format
        return bitcoin.ECKey.fromWIF(privateKey.privateKey.toWIF());
    }

}]);