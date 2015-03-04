/**
 * Work !!
 */

var bitcore = require('bitcore')
  , Hash = bitcore.crypto.Hash;

var pubkey1 = [
    new bitcore.PublicKey('0208c207e1a5122e77afacfd2a1eaae48f8be879e4a8beda1a4bc83db8171834d7'),
    new bitcore.PublicKey('02180c60b560029109b4ca936435b15e26598a44c5b7bf9bd3017736cfd81404db'),
    new bitcore.PublicKey('03f90d8bc82e05a024e5990429605265ec21c038c5d673584ebaf1362d65aad7da')
];
var pubkey2 = [
    new bitcore.PublicKey('0208c207e1a5122e77afacfd2a1eaae48f8be879e4a8beda1a4bc83db8171834d7'),
    new bitcore.PublicKey('02180c60b560029109b4ca936435b15e26598a44c5b7bf9bd3017736cfd81404db'),
    new bitcore.PublicKey('03f90d8bc82e05a024e5990429605265ec21c038c5d673584ebaf1362d65aad7da')
];


var address1 = new bitcore.Address(pubkey1, 2);
var address2 = new bitcore.Address(pubkey2, 2);

var transaction = new bitcore.Transaction('{"version":1,"inputs":[{"prevTxId":"98baec1f2a344276ea24437f5a8792f448c849dab2d6e141dd328e9e4e974291","outputIndex":1,"sequenceNumber":4294967295,"script":"","output":{"satoshis":10000,"script":"OP_HASH160 20 0x7b74efa3de73be37a882e19393eaaf3df6e31a92 OP_EQUAL"},"threshold":2,"publicKeys":["0208c207e1a5122e77afacfd2a1eaae48f8be879e4a8beda1a4bc83db8171834d7","02180c60b560029109b4ca936435b15e26598a44c5b7bf9bd3017736cfd81404db","03f90d8bc82e05a024e5990429605265ec21c038c5d673584ebaf1362d65aad7da"],"signatures":[null,null,null]},{"prevTxId":"09e587979d39ae6e6124e530b020d5b1a58339355be21e41a81b7f941e17994c","outputIndex":1,"sequenceNumber":4294967295,"script":"","output":{"satoshis":417000,"script":"OP_HASH160 20 0x7b74efa3de73be37a882e19393eaaf3df6e31a92 OP_EQUAL"},"threshold":2,"publicKeys":["0208c207e1a5122e77afacfd2a1eaae48f8be879e4a8beda1a4bc83db8171834d7","02180c60b560029109b4ca936435b15e26598a44c5b7bf9bd3017736cfd81404db","03f90d8bc82e05a024e5990429605265ec21c038c5d673584ebaf1362d65aad7da"],"signatures":[null,null,null]}],"outputs":[{"satoshis":10000,"script":"OP_DUP OP_HASH160 20 0x7afa489a639d3a0687505f046cdb402304143f7a OP_EQUALVERIFY OP_CHECKSIG"},{"satoshis":407000,"script":"OP_HASH160 20 0x7845484a98ace0c5ca2b44561fa71f1f3579a4a0 OP_EQUAL"}],"nLockTime":0,"fee":10000}')
    .from({
        address: '3Cwo8129ehXhwDSn8rnkYHfuHYgjjC7MDt',
        txid: '98baec1f2a344276ea24437f5a8792f448c849dab2d6e141dd328e9e4e974291',
        outputIndex:1,
        script: new bitcore.Script(address1).toHex(),
        amount:0.0001
    },
        pubkey1,
        2
    )
    .from({
        address: '3M9eF6A2VTNYTpGqkMcRL6mWhioaNUPJTm',
        txid: '09e587979d39ae6e6124e530b020d5b1a58339355be21e41a81b7f941e17994c',
        outputIndex:1,
        script: new bitcore.Script(address2).toHex(),
        amount: 0.00417
    },
        pubkey2,
        2
    )
    //.fee(10000)
    .to('1CDFGQxhWFzghkXzv4uf9yCWxDPSvWudiH', 10000)
    .to('3Cex1PTvqPzwm989zq8Q3xuqS2rTCnHFBC', 407000)
//    .change(new bitcore.Address('3AH75iWPWvZ8cN8kMGdDuG7GjSEJzTVJiK'))
    .sign('L43XZrsKz8h2zyEWJxyx27VZ9Zje7CijgZ8ZjUe6xn7gny86HfE3')
    .sign('L2wxUn3Y9CBSzmcD9KHUkVZ8zPs84HtnRCRJtZiyjKzHxxbPB5Nk')
    //.sign('KzAP1PMQvsBYNnFrntBBZ2NeYsfuWpxTq5yQgANs3EPfxk4UvMcp')
    //.sign('L3yTikaT5vh3kh3waFQV37v9Qnu4BXK9m4pPFjqjzBYtvpwGaNWt')
;

console.log(transaction);

//console.log(transaction.toObject());
//console.log(transaction.toJSON());
//console.log(transaction.serialize());

//console.log('Count signatures: %d', transaction.countSignatures());
//console.log('Count missing signatures: %d', transaction.countMissingSignatures());
//console.log('Is fully signed ? %s', transaction.inputs[0].isFullySigned());
console.log('Is fully signed ? %s', transaction.isFullySigned());
