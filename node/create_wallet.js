#!/usr/bin/env node

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
var network, requiredSignatures, keychainName, application;

function askNetwork()
{
    var question = [
        {
            type: 'list',
            name: 'network',
            message: 'Choose your network',
            choices: [ 'Livenet', 'Testnet' ],
            filter: function( val ) { return val.toLowerCase(); }
        }
    ];

    inquirer.prompt(question, function(answer) {
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

        askGenerateApplication();

    });
}

function askGenerateApplication()
{
    inquirer.prompt({
        type: "input",
        name: "application",
        message: 'Please type a name for your application :'
    }, function(answer) {

        application = answer.application;

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
            console.error('Creation aborted.');
            process.exit();
        }

        process();
    });
}

function process()
{
    var deferred = Q.defer();
    var keychain;

    database.addKeychain(keychainName, requiredSignatures)
    .then(function(keychainId) {
        keychain = keychainId;

        return database.addApplication(keychainId, application);
    })
    .then(function(applicationId) {

        var promises = [];

        for (var i=0; i < seeds.length; i++) {

            var walletCreated = wallet.create(seeds[i], network);

            promises.push(
                database.addPubkeys(
                    keychain,
                    applicationId,
                    '',
                    walletCreated
                        .derive(44 /* BIP44, constant */, true)
                        .derive(0 /* cointype: bitcoin */, true)
                        .derive(applicationId /* account */, true).hdPublicKey, // extendedPubKey
                    walletCreated
                        .derive(44, true) // BIP44 constant
                        .derive(0, true)  // bitcoin
                        .derive(0, true)  // # application
                        .derive(0)        // chain
                        .derive(0)        // address
                        .publicKey
                    .toString()                                                 // publicKey
                )
            );
        }

        return Q.all(promises);
    })
    .then(function() {

        console.log('\n\nFinished!');

        bootstrap.end();

    });


    return deferred.promise;
}

// start
askNetwork();