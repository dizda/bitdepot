var rawTransaction = '0100000001e102cfe0091fb9c825d36e6f6cc0b8660babdc75da767c90c6fec2f1eec435910100000000ffffffff02102700000000000017a914450f21f2ec4c64491eeb95555e175579193d1d9887102700000000000017a914f87c86c1f30117ac4869684fa7e9f9809c86eede8700000000';
//var redeemScriptHex = '522102a71ef05b31072d778b35f47d6204b80733db964498267f61dec2bdaaca22752121025bcd11b34f89704aba4d8f88d5e4d5db2a65ed1d6aabbc1f335f2eec771ee4e421024da9e2fb260317954c54df92d78051ef230de9a5aafef2592bb3b4f666209bcf53ae';
//var privKeys = [
//    'L1Dz8SqCiSS9j2GWLkmAytv3U9abVYzYQJLncTEgNoKCUTAJxiUi',
//    'KwRo1CDf3LEKJ9RS9QNSZ74SXJqTJ3P76j4QVVqF81NjN2tM8QkM'
//].map(function(wif) { return bitcoin.ECKey.fromWIF(wif) });

// Recover transaction from raw_transaction created by bitcoind || or raw_signed_transaction
var tx  = bitcoin.Transaction.fromHex(rawTransaction);

// Build it into Transaction Builder
var txb = bitcoin.TransactionBuilder.fromTransaction(tx);


txb.sign(0, privKeys[0], bitcoin.Script.fromHex(redeemScriptHex));
txb.sign(0, privKeys[1], bitcoin.Script.fromHex(redeemScriptHex));