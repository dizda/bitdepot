'use strict';

var bitcore = require('bitcore-lib');


function Wallet()
{
    // null byte-o-cul
}

/**
 *
 * @param {String} seed    The passphrase
 * @param {String} network Could be 'livenet' or 'testnet' for example
 *
 * @returns {bitcore.HDPrivateKey}
 */
Wallet.prototype.create = function(seed, network)
{

    return bitcore.HDPrivateKey.fromSeed(bitcore.crypto.Hash.sha256(bitcore.deps.Buffer(seed)), bitcore.Networks[network]);
};

//Wallet.prototype.getMultisigAddress = function(requiredSignatures, pubkeys)
//{
//    var redeemScript = bitcoin.scripts.multisigOutput(requiredSignatures, pubkeys);
//    var scriptPubKey = bitcoin.scripts.scriptHashOutput(redeemScript.getHash());
//
//    //return bitcoin.Address.fromOutputScript(scriptPubKey).toString();
//    return {
//        address: bitcoin.Address.fromOutputScript(scriptPubKey).toString(),
//        redeemScript: redeemScript,
//        scriptPubKey: scriptPubKey
//    };
//};

module.exports = Wallet;
