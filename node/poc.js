var bitcoin = require('bitcoinjs-lib');


var rawTransaction = '0100000002490148e331b17b540e4e50e99537d30058a13c070771f4a572d10ee49f23f32a0100000000ffffffff2f8f8eebc1425458217d57f710459aef4b672fe63d969646a0b7ac8c178252be0000000000ffffffff02204e0000000000001976a914d356d4d8079f8556be4c8102ecf00cc63344e75488ac307500000000000017a9146bacd88c6c3cd4d2bc5d5e85ca39cc246aac945f8700000000';
var redeemScriptHex = '522102a71ef05b31072d778b35f47d6204b80733db964498267f61dec2bdaaca22752121025bcd11b34f89704aba4d8f88d5e4d5db2a65ed1d6aabbc1f335f2eec771ee4e421024da9e2fb260317954c54df92d78051ef230de9a5aafef2592bb3b4f666209bcf53ae';
var privKeysSignature0 = [
    'KxpeZuPLQhLHk7LMiEcGaoZMCJP6pJrocyVbjurYsNmB3KdXDABD', // input:0
    'KzUvPH8vGidQuEnjdBxSrKM687o5aRAarLaYhjTX3SZXymAMJVv9'  // input:1
].map(function(wif) { return bitcoin.ECKey.fromWIF(wif) });

var privKeysSignature1 = [
    'L2pVbMKhRvk3RSTJtj4jXVqThrh2E9AXrJ5Prc6THG391gVbP9om', // input:0
    'KyNJyUHi6h88nLydvgrnxbg1JCgSYvFgj1mPwxbeL35XJSqzKToa'  // input:1
].map(function(wif) { return bitcoin.ECKey.fromWIF(wif) });

var tx  = bitcoin.Transaction.fromHex(rawTransaction);

// Build it into Transaction Builder
var txb = bitcoin.TransactionBuilder.fromTransaction(tx);


txb.sign(0, privKeys[0], bitcoin.Script.fromHex(redeemScriptHex));
txb.sign(0, privKeys[1], bitcoin.Script.fromHex(redeemScriptHex));
//txb.sign(0, privKeys[0], redeemScript);
//txb.sign(0, privKeys[1], redeemScript);



//console.log(txb.buildIncomplete().toHex());
console.log(txb.build().toHex());