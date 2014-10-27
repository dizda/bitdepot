'use strict';

var inquirer  = require('inquirer')
  , Q         = require('q')
  , Bootstrap = require('./bootstrap.js')
  , Wallet    = require('./wallet.js');

var bootstrap = new Bootstrap();
var database  = bootstrap.database;

bootstrap.start();

//bootstrap.end();
var wallet = new Wallet();
var seeds = [];
var network, requiredSignatures, numberToGenerate, keychainName;

function askNetwork()
{
    var network = [
        {
            type: 'list',
            name: 'network',
            message: 'Choose your network',
            choices: [ 'Bitcoin', 'Testnet' ],
            filter: function( val ) { return val.toLowerCase(); }
        }
    ];

    inquirer.prompt(network, function(answer) {
        network = answer.network;

        askSeeds();
    });
}

function askSeeds()
{
    var seedsQuestions = [
        {
            type: 'input',
            name: 'seed',
            message: 'Enter a seed that will generate a private key'
        },
        {
            type: 'confirm',
            name: 'askAgain',
            message: 'Do you want to add another key (just hit enter for YES)?',
            default: true
        }
    ];

    inquirer.prompt( seedsQuestions, function( answers ) {

        seeds.push(answers.seed);

        if (answers.askAgain) {
            askSeeds();

            return;

        }

        askSignaturesRequired();

    });

}


function askSignaturesRequired()
{
    inquirer.prompt({
        type: "input",
        name: "required",
        message: 'How many signatures do you require to valid a transaction ('+seeds.length+' signatures max)?',
        validate: function( value ) {
            var valid = !isNaN(parseInt(value));

            if (valid && parseInt(value) > seeds.length) {
                return 'Your number cannot be higher than number of keys you got';
            }

            return valid || "Please enter a number";
        },
        filter: Number
    }, function(signatures) {

        requiredSignatures = signatures.required;

        askNumberToGenerate();
    });
}

function askNumberToGenerate()
{
    inquirer.prompt({
        type: "input",
        name: "count",
        message: 'How many address do you want to generate?',
        default: 1000,
        validate: function( value ) {
            var valid = !isNaN(parseInt(value));

            return valid || "Please enter a number";
        },
        filter: Number
    }, function(answer) {

        numberToGenerate = answer.count;

        askKeychainName();

    });
}

function askKeychainName()
{
    inquirer.prompt({
        type: 'input',
        name: 'name',
        message: 'Please enter a name for your keychain'
    }, function(keychain) {

        console.log('\n');
        console.log('You\'re gonna generate a HD Wallet for a ' + requiredSignatures + '-of-' + seeds.length + ' multisig. This is your seeds :\n');
        console.log(seeds.map(function(seed) {
            return ' - ' + seed;
        }).join('\n'));
        console.log('\n');
        console.log('Please, save them into different secure devices.');
        console.log('Your keychain will be saved as ' + keychain.name);
        console.log('\n');

        keychainName = keychain.name;

        askConfirm();
    });
}

function askConfirm()
{
    inquirer.prompt({
        type: "confirm",
        name: "isConfirmed",
        message: "Confirm generation ? Your database will be updated.",
        default: false
    }, function(generation) {
        if (!generation.isConfirmed) {
            console.error('Creation aborted.')
            process.exit();
        }

        process();
    });
}

function process()
{
    var wallets  = [];
    var deferred = Q.defer();
    var keychain;

    database.addKeychain(keychainName, requiredSignatures)
    .then(function(keychainId) {

        var promises = [];

        keychain = keychainId;

        for (var i=0; i < seeds.length; i++) {
            var walletTemp = wallet.create(seeds[i]);
            wallets.push(walletTemp);

            promises.push(
                database.addPubkeys(keychainId, '', walletTemp.getExternalAccount().derive(0).pubKey.toHex())
            );
        }

        return Q.all(promises);
    })
    .then(function() {

        // derive keys, start from #1
        for (var derivation=1; derivation <= numberToGenerate; derivation++) {
            var externalPubKeys = [];
            var internalPubKeys = [];
            var externalPromises = [];
            var internalPromises = [];

            for (var w=0; w < wallets.length; w++) {
                externalPubKeys.push(wallets[w].getExternalAccount().derive(derivation).pubKey);
                internalPubKeys.push(wallets[w].getInternalAccount().derive(derivation).pubKey);
            }

            /*console.log(externalPubKeys.map(function(item) {
                return item.toHex();
            }));*/


            var externalMulti = wallet.getMultisigAddress(2, externalPubKeys);
            var internalMulti = wallet.getMultisigAddress(2, internalPubKeys);

            console.log('#'+derivation+'.ext ' + externalMulti);
            externalPromises.push(database.addAddress(keychain, externalMulti, 1, derivation));

            console.log('#'+derivation+'.int ' + internalMulti);
            internalPromises.push(database.addAddress(keychain, internalMulti, 0, derivation));

        }

        return Q.all(externalPromises) && Q.all(internalPromises);
    })
    .then(function() {

        console.log('\n\nFinish!');

        bootstrap.end();

    });


    return deferred.promise;
}

// start
askNetwork();