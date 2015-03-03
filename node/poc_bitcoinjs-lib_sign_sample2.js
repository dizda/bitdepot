/**
 * Trying to sign 2 inputs
 */
var bitcoin = require('bitcoinjs-lib');

var privKeys = {
    '98baec1f2a344276ea24437f5a8792f448c849dab2d6e141dd328e9e4e974291': {
        vout: 1,
        privKeys: [
            'L43XZrsKz8h2zyEWJxyx27VZ9Zje7CijgZ8ZjUe6xn7gny86HfE3',
            'L2wxUn3Y9CBSzmcD9KHUkVZ8zPs84HtnRCRJtZiyjKzHxxbPB5Nk'
        ],
        redeemScript: '52210208c207e1a5122e77afacfd2a1eaae48f8be879e4a8beda1a4bc83db8171834d72102180c60b560029109b4ca936435b15e26598a44c5b7bf9bd3017736cfd81404db2103f90d8bc82e05a024e5990429605265ec21c038c5d673584ebaf1362d65aad7da53ae'
    },
    '09e587979d39ae6e6124e530b020d5b1a58339355be21e41a81b7f941e17994c': {
        vout: 1,
        privKeys: [
            'KzAP1PMQvsBYNnFrntBBZ2NeYsfuWpxTq5yQgANs3EPfxk4UvMcp',
            'L3yTikaT5vh3kh3waFQV37v9Qnu4BXK9m4pPFjqjzBYtvpwGaNWt'
        ],
        redeemScript: '522102090dd9ec73b8ddc0cce7826040c56b80b8e33abfab4b5d9ea2a0e444e6c741852102b106f270cc598f47300b7d475337b96b3be7e88b8e360abbad1b5d3a888268bc2103d8610409add92f1d4917a08069ba38bf0b1c241109c1ebe9dc57fe33ff544dd153ae'
    }
};

// Recover transaction from raw_transaction created by bitcoind || or raw_signed_transaction
var tx  = bitcoin.Transaction.fromHex('01000000029142974e9e8e32dd41e1d6b2da49c848f492875a7f4324ea7642342a1fecba980100000000ffffffff4c99171e947f1ba8411ee25b353983a5b1d520b030e524616eae399d9787e5090100000000ffffffff0210270000000000001976a9147afa489a639d3a0687505f046cdb402304143f7a88acd83506000000000017a9147845484a98ace0c5ca2b44561fa71f1f3579a4a08700000000');

// Build it into Transaction Builder
var txb = bitcoin.TransactionBuilder.fromTransaction(tx);

var rawSignedTransaction;

//for (var signPass = 0; signPass <= 1; signPass++) {
    var signPass = 0;
    // Loop on each inputs
    txb.tx.ins.forEach(function (input, i) {

        // get the txid of the input
        var txid = bitcoin.bufferutils.reverse(input.hash).toString('hex');

        // [...]

        // Sign the input
        txb.sign(i, bitcoin.ECKey.fromWIF(privKeys[txid].privKeys[signPass]), bitcoin.Script.fromHex(privKeys[txid].redeemScript));
        //txb.sign(i, bitcoin.ECKey.fromWIF(privKeys[txid].privKeys[1]), bitcoin.Script.fromHex(privKeys[txid].redeemScript));
        //txb.sign(i, bitcoin.ECKey.fromWIF(privKeys[1]), bitcoin.Script.fromHex(redeemScript));
        console.log('[%d] Txid: %s (vout: 1)', i, txid);
        console.log('[%d] Private key: %s', i, privKeys[txid].privKeys[signPass]);

        try {
            rawSignedTransaction = txb.build().toHex();

            console.log('Successfully signed.');
        } catch (e) {
            if ('Transaction is missing signatures' === e.message) {
                // Normal, because every inputs are not signed yet.

                rawSignedTransaction = txb.buildIncomplete().toHex();
            } else if ('Not enough signatures provided' === e.message) {
                console.log('Not enough signatures provided');

                rawSignedTransaction = txb.buildIncomplete().toHex();
            } else {
                console.log(e);
            }
        }
    });
//}

console.log(rawSignedTransaction);