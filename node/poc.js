var bitcoin = require('bitcoinjs-lib');


var rawTransaction = '010000000155a3dd66c03bd64f6512fc47c9156db1a431946e07536d7ed5321df051d6bfc50100000000ffffffff0210270000000000001976a914d356d4d8079f8556be4c8102ecf00cc63344e75488ac102700000000000017a914dec137068316fe8ddffdf05befb24d786e38adf98700000000';
var redeemScriptHex = '522102a71ef05b31072d778b35f47d6204b80733db964498267f61dec2bdaaca22752121025bcd11b34f89704aba4d8f88d5e4d5db2a65ed1d6aabbc1f335f2eec771ee4e421024da9e2fb260317954c54df92d78051ef230de9a5aafef2592bb3b4f666209bcf53ae';
var privKeys = [
    'L1Dz8SqCiSS9j2GWLkmAytv3U9abVYzYQJLncTEgNoKCUTAJxiUi',
    'KwRo1CDf3LEKJ9RS9QNSZ74SXJqTJ3P76j4QVVqF81NjN2tM8QkM'
].map(function(wif) { return bitcoin.ECKey.fromWIF(wif) });

// Recover transaction from raw_transaction created by bitcoind || or raw_signed_transaction
var tx  = bitcoin.Transaction.fromHex(rawTransaction);

// Build it into Transaction Builder
var txb = bitcoin.TransactionBuilder.fromTransaction(tx);


txb.sign(0, privKeys[0], bitcoin.Script.fromHex(redeemScriptHex));
txb.sign(0, privKeys[1], bitcoin.Script.fromHex(redeemScriptHex));
//txb.sign(0, privKeys[0], redeemScript);
//txb.sign(0, privKeys[1], redeemScript);



//console.log(txb.buildIncomplete().toHex());
console.log(txb.build().toHex());