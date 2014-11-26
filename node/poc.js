var bitcoin = require('bitcoinjs-lib');


var rawTransaction = '01000000014c80a5129d03d0ebe28c2626fb2b35962c438f548ff399cc85b462109338550b0100000000ffffffff0210270000000000001976a914d356d4d8079f8556be4c8102ecf00cc63344e75488ac307500000000000017a9145010eb90f22f4865766e969fc5e07c1e2445f81a8700000000';
var redeemScriptHex = '522102a6074afbed684715760e4eebe9878fea1ea3862828e687674a841d7e03db07c7210253468fb6d90c4d9bf6d02dde920dc7cfa214b3103fc0f59b7c52a4163df6bbaf210210ec8ae465d9d6a686a8718e9051b581277ee05889fc7776a2925a111c7fc77f53ae';
var redeemScriptASM = '0 3045022100f68727afbd82d8b0d3a6c2161db8d3e049c1627307a994c18b33b65dc3c9814502207bb4998766fe0c5fcbdf260b2175bd70895c30d8d51419678c2a7c2e1c21f87701 30440220305c2da7a189f3213b282c486af680a20ef1bb39860ec7bbbe034ee5837e25690220375ebed5ef0a9c6c556b9e535b58567d86b179103eedd887c62155ef2682e48d01 522103b367219a55e4d9a74f508f05ad376535fe653a60453545d828bdd095c1f11eea210283f6c706be826c63b3e1f8d4de345e4eab581015544507025cc2772627d4da00210379a6f8d1e4d376dc00eeb96fc02219ac0d7811773c2a9050889e346836cb435c53ae';
var privKeys = [
    'KwLPiuHXJGkuyMJkjybUH8M2nQ9A64FYXMtT9RFJLxgtT5wZBAey',
    'KxjVtJZscdLZ1GZR8cHhhDo8bXMmQNjST3f4FW2AVhCFS7h4k8q2'
].map(function(wif) { return bitcoin.ECKey.fromWIF(wif) });

// Recover transaction from raw_transaction created by bitcoind || or raw_signed_transaction
var tx  = bitcoin.Transaction.fromHex(rawTransaction);

// Build it into Transaction Builder
var txb = bitcoin.TransactionBuilder.fromTransaction(tx);
//var txb = new bitcoin.TransactionBuilder();
//txb.addInput('35f383c45b94f0307e03777c855f1d9b5eeba13c1f8eca2ba275ea2b7fba3631', 1);
//txb.addOutput('1LGTbdVSEbD9C37qXcpvVJ1egdBu8jYSeV', 10000);
//txb.addOutput('3MQmgtgNgvPQQpfFc7ENbN2hJmi3iaoVWm', 50000);

// Create a wallet from the seed submitted
//var walletDIZDA = new bitcoin.Wallet(bitcoin.crypto.sha256('bambou1'), bitcoin.networks.bitcoin);
//var walletZENOU = new bitcoin.Wallet(bitcoin.crypto.sha256('bambou2'), bitcoin.networks.bitcoin);
//var walletSERVER = new bitcoin.Wallet(bitcoin.crypto.sha256('bambou3'), bitcoin.networks.bitcoin);
//
//var pubkeys = [];
//pubkeys.push(walletDIZDA.getInternalAccount().derive(12).pubKey);
//pubkeys.push(walletZENOU.getInternalAccount().derive(12).pubKey);
//pubkeys.push(walletSERVER.getInternalAccount().derive(12).pubKey);


//console.log(privKeys[0].pub);
//var redeemScript = bitcoin.scripts.multisigOutput(2, pubkeys);
//console.log(redeemScript.toHex());

txb.sign(0, privKeys[0], bitcoin.Script.fromHex(redeemScriptHex));
txb.sign(0, privKeys[1], bitcoin.Script.fromHex(redeemScriptHex));
//txb.sign(0, privKeys[0], redeemScript);
//txb.sign(0, privKeys[1], redeemScript);



//console.log(txb.buildIncomplete().toHex());
console.log(txb.build().toHex());