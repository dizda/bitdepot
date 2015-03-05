/**
 * Work !!
 */

var bitcore = require('bitcore')
  , Hash = bitcore.crypto.Hash;


var pubkeys1 = [
    new bitcore.PublicKey('0208c207e1a5122e77afacfd2a1eaae48f8be879e4a8beda1a4bc83db8171834d7'),
    new bitcore.PublicKey('02180c60b560029109b4ca936435b15e26598a44c5b7bf9bd3017736cfd81404db'),
    new bitcore.PublicKey('03f90d8bc82e05a024e5990429605265ec21c038c5d673584ebaf1362d65aad7da')
];
var pubkeys2 = [
    new bitcore.PublicKey('02090dd9ec73b8ddc0cce7826040c56b80b8e33abfab4b5d9ea2a0e444e6c74185'),
    new bitcore.PublicKey('02b106f270cc598f47300b7d475337b96b3be7e88b8e360abbad1b5d3a888268bc'),
    new bitcore.PublicKey('03d8610409add92f1d4917a08069ba38bf0b1c241109c1ebe9dc57fe33ff544dd1')
];

var address1 = new bitcore.Address(pubkeys1, 2);
var address2 = new bitcore.Address(pubkeys2, 2);

var transaction = new bitcore.Transaction()
    .from({
        address: '3Cwo8129ehXhwDSn8rnkYHfuHYgjjC7MDt',
        txid: '98baec1f2a344276ea24437f5a8792f448c849dab2d6e141dd328e9e4e974291',
        outputIndex:1,
        script: new bitcore.Script(address1).toHex(),
        amount:0.0001
    },
        pubkeys1,
        2
    )
    .from({
        address: '3M9eF6A2VTNYTpGqkMcRL6mWhioaNUPJTm',
        txid: '09e587979d39ae6e6124e530b020d5b1a58339355be21e41a81b7f941e17994c',
        outputIndex:1,
        script: new bitcore.Script(address2).toHex(),
        amount: 0.00417
    },
        pubkeys2,
        2
    )
    //.fee(10000)
    .to('1CDFGQxhWFzghkXzv4uf9yCWxDPSvWudiH', 10000)
    .to('3Cex1PTvqPzwm989zq8Q3xuqS2rTCnHFBC', 407000)
//    .change(new bitcore.Address('3AH75iWPWvZ8cN8kMGdDuG7GjSEJzTVJiK'))
//    .sign('L43XZrsKz8h2zyEWJxyx27VZ9Zje7CijgZ8ZjUe6xn7gny86HfE3')
//    .sign('L2wxUn3Y9CBSzmcD9KHUkVZ8zPs84HtnRCRJtZiyjKzHxxbPB5Nk')
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
