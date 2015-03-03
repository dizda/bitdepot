var bitcoin = require('bitcoinjs-lib');

var pubKeys = [
    '0227fbadd0c4a2c8c89ab7996e5f5489dfcc3dfe7924fedd0167046166f1613eaa', // #1
    '034162edc518f6fc83ab826db81d50a8002b814b9f0b739a69560e14d3773296ee', // #2
    '0296a44a6f2ed29bec93d4a6afac0fbc243c9ea1599caad6f515a140e7f4ee61e7'  // #3
];

var privKeys = [
    'L2rVPg54JmbpUYBXbMCFHFFdZByXKT1UdaAN9zvYbgFGWoHTM4A3', // #1
    'L5h7uvxNbUwht99ewKxRedEZq7wii4RDwnVSVP327UCwWLYprysM', // #2
    'KzgkfPwEHKJzoQc8CyrdVLAB8dec861zj54BEYNaP6vALGum4K8C'  // #3
];

var redeemScript = '52210227fbadd0c4a2c8c89ab7996e5f5489dfcc3dfe7924fedd0167046166f1613eaa210296a44a6f2ed29bec93d4a6afac0fbc243c9ea1599caad6f515a140e7f4ee61e721034162edc518f6fc83ab826db81d50a8002b814b9f0b739a69560e14d3773296ee53ae';


// Recover transaction from raw_transaction created by bitcoind || or raw_signed_transaction
var tx  = bitcoin.Transaction.fromHex('0100000001a9c345321fa0866212a1852a48e32876816a1a059e03e8bfd34a2acdb8ac38170100000000ffffffff02204e0000000000001976a9140b167a9a9040e46e9c87d8c8291bbefae77c6bfe88ac503403000000000017a9147a82193d183b15125285cd88aac0b8fa9e322ac18700000000');

// Build it into Transaction Builder
var txb = bitcoin.TransactionBuilder.fromTransaction(tx);

var rawSignedTransaction;

// Loop on each inputs
txb.tx.ins.forEach(function(input, i) {

    // get the txid of the input
    var txid   = bitcoin.bufferutils.reverse(input.hash).toString('hex');

    // [...]

    // Sign the input
    txb.sign(i, bitcoin.ECKey.fromWIF(privKeys[0]), bitcoin.Script.fromHex(redeemScript));
//    txb.sign(i, bitcoin.ECKey.fromWIF(privKeys[1]), bitcoin.Script.fromHex(redeemScript));

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

console.log(rawSignedTransaction);