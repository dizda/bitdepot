var bitcore = require('bitcore-lib')
  , bitcoin = require('bitcoinjs-lib')
  , Hash = bitcore.crypto.Hash;


//var rawTransaction = '010000000155a3dd66c03bd64f6512fc47c9156db1a431946e07536d7ed5321df051d6bfc50100000000ffffffff0210270000000000001976a914d356d4d8079f8556be4c8102ecf00cc63344e75488ac102700000000000017a914dec137068316fe8ddffdf05befb24d786e38adf98700000000';
//
//var transaction = new bitcore.Transaction(rawTransaction);
//
//console.log(transaction.toObject());

// TODO: https://github.com/bitcoin/bips/blob/master/bip-0044.mediawiki
//console.log(Hash.sha256('coucou'));
var hdPrivateKey = new bitcore.HDPrivateKey('xprv9s21ZrQH143K2JF8RafpqtKiTbsbaxEeUaMnNHsm5o6wCW3z8ySyH4UxFVSfZ8n7ESu7fgir8imbZKLYVBxFPND1pniTZ81vKfd45EHKX73');
console.log(hdPrivateKey);

var hdPrivateKey = bitcore.HDPrivateKey.fromSeed(bitcoin.crypto.sha256('coucou'), bitcore.Networks.livenet);
console.log(hdPrivateKey);

var wallet = new bitcoin.Wallet(bitcoin.crypto.sha256('coucou'), bitcoin.networks.testnet);
console.log(wallet.getMasterKey().toBase58());
console.log('end');

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

var signer1 = extendedPublicKey.derive(0).derive(0).publicKey;
var signer2 = extendedPublicKey.derive(0).derive(1).publicKey;
var signer3 = extendedPublicKey.derive(0).derive(2).publicKey;

var pubkeys = [
    signer1,
    signer2,
    signer3
];
var redeemScript = bitcore.Script.buildMultisigOut(pubkeys, 2);
var script = redeemScript.toScriptHashOut();

// New method
var address = bitcore.Address(pubkeys, 2);

console.log(redeemScript.toBuffer().toString('hex')); // serialize to Hex format the redeemScript

var multisigAddress = new bitcore.Address(redeemScript, bitcore.Networks.livenet);
console.log(multisigAddress);
console.log('script ', address.toScriptHashOut());
// TODO: add 'extended_public_key' field to 'Pubkey' entity






console.log('\nGenerate an address from Public Extended Key:');

var pubkey = new bitcore.HDPublicKey('xpub6CceUL4HSiPa2ms86Ay94Pw4YETv4xAyfRyt7vk9CuQ71rZo5hmNmm9uSx7vVxVG4SUeADNBSUhoRNGU2CbPEsGxNtBJ7uBSQQUdxtSNeT2');

var hdPubAddress = new bitcore.Address(pubkey.derive(0 /* internal account */).derive(0 /* address index */).publicKey, bitcore.Networks.livenet);
console.log(hdPubAddress);