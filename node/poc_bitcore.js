var bitcore = require('bitcore');


//var rawTransaction = '010000000155a3dd66c03bd64f6512fc47c9156db1a431946e07536d7ed5321df051d6bfc50100000000ffffffff0210270000000000001976a914d356d4d8079f8556be4c8102ecf00cc63344e75488ac102700000000000017a914dec137068316fe8ddffdf05befb24d786e38adf98700000000';
//
//var transaction = new bitcore.Transaction(rawTransaction);
//
//console.log(transaction.toObject());

// TODO: https://github.com/bitcoin/bips/blob/master/bip-0044.mediawiki

var hdPrivateKey = new bitcore.HDPrivateKey('xprv9s21ZrQH143K2JF8RafpqtKiTbsbaxEeUaMnNHsm5o6wCW3z8ySyH4UxFVSfZ8n7ESu7fgir8imbZKLYVBxFPND1pniTZ81vKfd45EHKX73');
console.log(hdPrivateKey);
//var retrieved = new HDPrivateKey('xpriv...');
//var derived = hdPrivateKey.derive("m/0'");
//var derived = hdPrivateKey.derive(1).derive(2, true);
//var derived = hdPrivateKey.derive("m/1'/0");
//var derived = hdPrivateKey.derive("m/1'/0/1");

// BIP32 External Account & keypair :
//var derived = hdPrivateKey.derive(1 /* account */, true).derive(0 /* external account */).derive(1 /* keypair */);
//var derived = hdPrivateKey.derive(1 /* account */, true);

// BIP44 :
var derived = hdPrivateKey.derive(44 /* BIP44, constant */, true).derive(0 /* cointype: bitcoin */, true).derive(0 /* account */, true); // Extended privatekey
console.log(derived);

var extendedPublicKey = derived.hdPublicKey; // TODO: Add this to BDD

console.log('---');
console.log(extendedPublicKey);
console.log('---');
var hdPubAddress = new bitcore.Address(extendedPublicKey.derive(0 /* internal account */).derive(0 /* address index */).publicKey, bitcore.Networks.livenet);

console.log(hdPubAddress);

var pubkey1 = extendedPublicKey.derive(0).derive(0).publicKey;
var pubkey2 = extendedPublicKey.derive(0).derive(1).publicKey;
var pubkey3 = extendedPublicKey.derive(0).derive(2).publicKey;

var pubkeys = [
    pubkey1,
    pubkey2,
    pubkey3
];
var redeemScript = bitcore.Script.buildMultisigOut(pubkeys, 2);
var script = redeemScript.toScriptHashOut();
console.log(redeemScript.toBuffer().toString('hex')); // serialize to Hex format the redeemScript

var multisigAddress = new bitcore.Address(redeemScript, bitcore.Networks.livenet);
console.log(multisigAddress);

// TODO: add 'extended_public_key' field to 'Pubkey' entity