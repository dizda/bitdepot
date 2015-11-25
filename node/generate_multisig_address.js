#!/usr/bin/env node

'use strict';

var bitcore = require('bitcore-lib');

var params   = JSON.parse(process.argv[2]);
var pubKeys  = [];
var external = (params.isExternal === 'external') ? 0 : 1;

params.extendedPubKeys.forEach(function(extendedPubKeyBase58) {

    var extendedPubKey = new bitcore.HDPublicKey(extendedPubKeyBase58);
    pubKeys.push(extendedPubKey.derive(external).derive(params.derivation).publicKey);

});


var redeemScript = bitcore.Script.buildMultisigOut(pubKeys, params.signRequired);
var script = redeemScript.toScriptHashOut();

var multisigAddress = new bitcore.Address(script, bitcore.Networks.livenet);

var stdout = {
    redeemScript: redeemScript.toBuffer().toString('hex'), // serialize to Hex format the redeemScript
    address:      multisigAddress.toString(),
    pubKeys:      pubKeys.map(function(pubkey) { return pubkey.toString(); }).sort() // get pubKeys and sort them alphabetically
};

if (bitcore.Address.isValid(multisigAddress.toString(), bitcore.Networks.livenet, bitcore.Address.PayToScriptHash)) {

    // stdout
    console.log(JSON.stringify(stdout));

} else {

    throw new Error('Address generated is invalid (not a PayToScriptHash).');

}