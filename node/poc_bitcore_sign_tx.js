var bitcore = require('bitcore')
  , bitcoin = require('bitcoinjs-lib')
  , Hash = bitcore.crypto.Hash;

var tx = '01000000016c9519f0b930e1c7ab7e066ec6a4d866343531d7097c917d0d288bbe1443c9fe0100000000ffffffff02102700000000000017a9140287d151f7d330e9dba6aaee6a571a2eacc7735487307500000000000017a9145e33746c50ec05676a9310e491f31780b3b568508700000000';
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
var pub1 = new bitcore.PublicKey('02801af44effb78ad51d3a8fa4081e09fb0d3b900d24d465e11217ac591721ef27');
var pub2 = new bitcore.PublicKey('0294040d31a1b4801ec6148ac9c00fbd69994a2db0804fbcbdadc6bb582a7f33fa');
var pub3 = new bitcore.PublicKey('029e0aae6bb5b1fe585f9df649776753ee22284e703c276c5e53320c3a0e0705e7');
var output = {
    address: '3LFeyAJvfeZeZdntipQEMksm3nb2h8Knve',
    txId: 'fec94314be8b280d7d917c09d731353466d8a4c66e067eabc7e130b9f019956c',
    outputIndex: 1,
    script: new bitcore.Script(new bitcore.Address('3LFeyAJvfeZeZdntipQEMksm3nb2h8Knve')),
    satoshis: 50000
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

var gotOneSignature = '01000000016c9519f0b930e1c7ab7e066ec6a4d866343531d7097c917d0d288bbe1443c9fe01000000b500483045022100a9feae5a5982d348a96149ca435c40848b850b887a20e0504b99f154bf00df1b02204dc822310a8e013f2470fbd1e7563087b87707d037f17e3641ec22ea37421f9d014c69522102801af44effb78ad51d3a8fa4081e09fb0d3b900d24d465e11217ac591721ef27210294040d31a1b4801ec6148ac9c00fbd69994a2db0804fbcbdadc6bb582a7f33fa21029e0aae6bb5b1fe585f9df649776753ee22284e703c276c5e53320c3a0e0705e753aeffffffff02102700000000000017a9140287d151f7d330e9dba6aaee6a571a2eacc7735487307500000000000017a9145e33746c50ec05676a9310e491f31780b3b568508700000000';
// Just try to sign a serialized transaction
var transaction = new bitcore.Transaction(gotOneSignature);
// reset the inputs, to add them again but with PublicKeys & multisig infos
//transaction.inputs = [];

transaction.from(output, [pub1, pub2, pub3], 2);

var hdPrivateKey1 = bitcore.HDPrivateKey.fromSeed(bitcoin.crypto.sha256('bambou1'), bitcore.Networks.livenet);
var hdPrivateKey2 = bitcore.HDPrivateKey.fromSeed(bitcoin.crypto.sha256('bambou2'), bitcore.Networks.livenet);
//transaction.sign(hdPrivateKey1.derive("m/44'/0'/1'/0/17").privateKey);
transaction.sign(hdPrivateKey2.derive("m/44'/0'/1'/0/17").privateKey);
console.log(transaction.serialize());

console.log('Count signatures: %d', transaction.inputs[0].countSignatures());
console.log('Count missing signatures: %d', transaction.inputs[0].countMissingSignatures());
console.log('Is fully signed ? %s', transaction.inputs[0].isFullySigned());