'use strict';

angular.module('app').controller('SignModalCtrl', ['$scope', 'Withdraw', function($scope, Withdraw) {

    var bitcore = require('bitcore-lib');
    var Buffer = bitcore.deps.Buffer;

    $scope.seed      = null;
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
     * @param {Object} e    event
     */
    $scope.verify = function(e)
    {
        if (e && e.which !== 13) {
            return;
        }

        $scope.initSignature();

        // Create a wallet from the seed submitted
        var wallet = bitcore.HDPrivateKey.fromSeed(
            bitcore.crypto.Hash.sha256(new Buffer($scope.seed)).toString('hex'),
            bitcore.Networks.livenet
        );

//        wallet.derive("m/44'/0'/0'/0/0")
        var accountPubKey = wallet
            .derive(44, true) // BIP44 constant
            .derive(0, true)  // bitcoin
            .derive(0, true)  // # application
            .derive(0)        // chain
            .derive(0)        // address
        .publicKey.toString();

        console.log('Account pubKey: %s', accountPubKey);
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

        sign($scope.seed);
    };

    /**
     * Sign each inputs with the submitted seed
     *
     * @param seed
     */
    function sign(seed)
    {
        console.log($scope.withdraw.json_transaction);
        console.log($scope.withdraw.json_signed_transaction);
        // Recover transaction from json_transaction created by bitcore || or json_signed_transaction
        var transaction = bitcore.Transaction(JSON.parse($scope.withdraw.json_signed_transaction || $scope.withdraw.json_transaction));

        // Create a wallet from the seed submitted
        var wallet = bitcore.HDPrivateKey.fromSeed(bitcore.crypto.Hash.sha256(new Buffer(seed)).toString('hex'), bitcore.Networks.livenet);
        var privKeys = []; // Just being sure to not sign the same address twice, otherwise it'll throw an error

        // Sign all inputs
        $scope.withdraw.withdraw_inputs.forEach(function(input, i) {

            // Get the private key according to the derivation
            var privKey = getPrivateKey(wallet, input.address.application.id, input.address.is_external, input.address.derivation);

            console.log('[%d] Txid: %s (vout: %d)', i, input.txid, input.index);
            console.log('[%d] Private key: %s', i, privKey.toWIF());


            // Be sure the address has not been already signed
            if (privKeys.indexOf(privKey.toWIF()) !== -1) {

                console.warn('This address\'s derivation "'+input.address.derivation+'" has already been signed.');
            } else {
                // Sign the input
                transaction.sign(privKey);

                // Don't sign this address twice for the next loop
                privKeys.push(privKey.toWIF());
            }
        });

        privKeys = null; // freeing private keys from the memory

        // save the signed transaction
        $scope.withdraw.json_signed_transaction = JSON.stringify(transaction);

        if (transaction.isFullySigned()) {
            $scope.withdraw.raw_signed_transaction = transaction.serialize(); // then send this to bitcoind
            $scope.withdraw.is_signed = true; // Setting the signed flag
            console.log('Successfully signed.');
        } else {
            console.log('Not successfully signed yet.');
        }

        // print the signed transaction there
        console.log($scope.withdraw.raw_signed_transaction);

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
     * @param {Wallet}  wallet
     * @param {Number}  application Application id
     * @param {Boolean} isExternal
     * @param {Number}  address
     *
     * @returns {HDNode.privKey|*|.HDNode.privKey}
     */
    function getPrivateKey(wallet, application, isExternal, address)
    {
        var privateKey = wallet
            .derive(44, true) // BIP44 constant
            .derive(0, true)  // bitcoin
            .derive(application, true)  // # application
            .derive(isExternal ? 0 : 1)        // chain
            .derive(address)        // address
        ;

        return privateKey.privateKey;
    }

}]);