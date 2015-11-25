var bitcore = require('bitcore-lib')
  , bitcoin = require('bitcoinjs-lib')
  , Hash = bitcore.crypto.Hash;

var tx = '0100000001e102cfe0091fb9c825d36e6f6cc0b8660babdc75da767c90c6fec2f1eec435910100000000ffffffff02102700000000000017a914450f21f2ec4c64491eeb95555e175579193d1d9887102700000000000017a914f87c86c1f30117ac4869684fa7e9f9809c86eede8700000000';
//var transaction = new bitcore.Transaction(tx);
//var input = transaction.inputs[0];

//input.countSignatures().should.equal(0);
//
//transaction.sign(privateKey1);
//input.countSignatures().should.equal(1);
//input.countMissingSignatures().should.equal(1);
//input.isFullySigned().should.equal(false);

//var hdPrivateKey = bitcore.HDPrivateKey.fromSeed(bitcoin.crypto.sha256('coucou'), bitcore.Networks.livenet);
//transaction.sign(hdPrivateKey.derive("m/44'/0'/1'/0/17").privateKey);

// ## Get a public key of a derivation
//var hdPrivateKey = bitcore.HDPrivateKey.fromSeed(bitcoin.crypto.sha256('bambou1'), bitcore.Networks.livenet);
//console.log(hdPrivateKey.derive("m/44'/0'/1'/0/17").publicKey);

// Create manually the transaction
var pub1 = new bitcore.PublicKey('02ca3bf5f14b2adf2b5365db10a794b0d1bed963727757559c7c3e168d77d9ca6e');
var pub2 = new bitcore.PublicKey('036753048d216ea94a60ce8c6fe6ec01677708f7cd7a7d7528bf8d9e2b6c5f9dc0');
var pub3 = new bitcore.PublicKey('038269dda81f554e8d17016328bc709f8b1900fdbb7961d22fe5c02b4989a2c9b4');
var output = {
    address: '3AH75iWPWvZ8cN8kMGdDuG7GjSEJzTVJiK',
    txId: '9135c4eef1c2fec6907c76da75dcab0b66b8c06c6f6ed325c8b91f09e0cf02e1',
    outputIndex: 1,
    script: new bitcore.Script(new bitcore.Address('3AH75iWPWvZ8cN8kMGdDuG7GjSEJzTVJiK')),
    satoshis: 30000
};

//var transaction2 = new bitcore.Transaction()
//    .from(output, [pub1, pub2, pub3], 2)
////    .from(output, [pub1, pub2, pub3], 2)
//    .fee(10000)
//    .to(new bitcore.Address('31vPyhmN6oTzTTXSSAF12o9Sc8v3QMcu4S'), 10000)
//    .to(new bitcore.Address('3AH75iWPWvZ8cN8kMGdDuG7GjSEJzTVJiK'), 30000)
////    .change(new bitcore.Address('3AH75iWPWvZ8cN8kMGdDuG7GjSEJzTVJiK'))
//;
//
//console.log(transaction2);

var gotOneSignature = '0100000001e102cfe0091fb9c825d36e6f6cc0b8660babdc75da767c90c6fec2f1eec4359101000000b500483045022100bbbaaa09ace25f191dd8190446f3f6cbe0099688f81f2bd0686f40a83c73626a02207a78356bdf376a40cd6980be58de8edd89a24f88bdabb66742637410c2ab27c9014c69522102ca3bf5f14b2adf2b5365db10a794b0d1bed963727757559c7c3e168d77d9ca6e21036753048d216ea94a60ce8c6fe6ec01677708f7cd7a7d7528bf8d9e2b6c5f9dc021038269dda81f554e8d17016328bc709f8b1900fdbb7961d22fe5c02b4989a2c9b453aeffffffff0000000000';
// Just try to sign a serialized transaction
var transaction = new bitcore.Transaction(gotOneSignature);
// reset the inputs, to add them again but with PublicKeys & multisig infos
//transaction.inputs = [];

transaction.from(output, [pub1, pub2, pub3], 2);
//transaction.fee(10000);
//transaction.to(new bitcore.Address('37zAgDGt7ZNg9734rPuNr7kAdV6Kraesnw'), 10000);
//transaction.to(new bitcore.Address('3QLtgN2sHvrFNsWoEMdsZigaDAVSHeVQ7n'), 10000);

var hdPrivateKey1 = bitcore.HDPrivateKey.fromSeed(bitcoin.crypto.sha256('bambou1'), bitcore.Networks.livenet);
var hdPrivateKey2 = bitcore.HDPrivateKey.fromSeed(bitcoin.crypto.sha256('bambou2'), bitcore.Networks.livenet);
console.log(hdPrivateKey1.derive("m/44'/0'/1'/1/4").privateKey.toWIF());
console.log(hdPrivateKey2.derive("m/44'/0'/1'/1/4").privateKey.toWIF()); // Then sign with bitcoinjs-lib
//transaction.sign(hdPrivateKey1.derive("m/44'/0'/1'/1/4").privateKey);
transaction.sign(hdPrivateKey2.derive("m/44'/0'/1'/1/4").privateKey);
console.log(transaction.serialize());

console.log('Count signatures: %d', transaction.inputs[0].countSignatures());
console.log('Count missing signatures: %d', transaction.inputs[0].countMissingSignatures());
console.log('Is fully signed ? %s', transaction.inputs[0].isFullySigned());

// TODO: FUCK IT! Ca marche pas... La solution temporaire serait d'obtenir la clée privée via la derivation grâce à bitcore
// TODO: Puis de signer avec bitcoinjs-lib.