var bitcoin = require('bitcoinjs-lib');


var rawTransaction = '0100000001e102cfe0091fb9c825d36e6f6cc0b8660babdc75da767c90c6fec2f1eec435910100000000ffffffff02102700000000000017a914450f21f2ec4c64491eeb95555e175579193d1d9887102700000000000017a914f87c86c1f30117ac4869684fa7e9f9809c86eede8700000000';
var redeemScriptHex = '522102ca3bf5f14b2adf2b5365db10a794b0d1bed963727757559c7c3e168d77d9ca6e21036753048d216ea94a60ce8c6fe6ec01677708f7cd7a7d7528bf8d9e2b6c5f9dc021038269dda81f554e8d17016328bc709f8b1900fdbb7961d22fe5c02b4989a2c9b453ae';
var privKeys = [
    'L5ZP8JyEKFbwgkfYgAvEq6NyAx5JpLpaay2AcZUDyqsaR66Ka8sh',
    'L5E68vTYc7npNKbR8xRunGzYjSLdJziBYk8vq8MxGkdTx2iXHij9'
].map(function(wif) { return bitcoin.ECKey.fromWIF(wif) });

// Recover transaction from raw_transaction created by bitcoind || or raw_signed_transaction
var tx  = bitcoin.Transaction.fromHex(rawTransaction);

// Build it into Transaction Builder
var txb = bitcoin.TransactionBuilder.fromTransaction(tx);


txb.sign(0, privKeys[1], bitcoin.Script.fromHex(redeemScriptHex));
txb.sign(0, privKeys[0], bitcoin.Script.fromHex(redeemScriptHex));
//txb.sign(0, privKeys[0], redeemScript);
//txb.sign(0, privKeys[1], redeemScript);



//console.log(txb.buildIncomplete().toHex());
console.log(txb.build().toHex());