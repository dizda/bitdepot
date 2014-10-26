var bitcoin = require('bitcoinjs-lib');
var crypto  = bitcoin.crypto;
var inquirer = require("inquirer");


var Bootstrap = require('./bootstrap.js');
var bootstrap = new Bootstrap();

bootstrap.start();

/*bootstrap.mysql.query('SELECT * from keychain', function(err, rows, fields) {
    if (err) throw err;

    console.log('The solution is: ', rows[0]);
});*/

//bootstrap.end();
var seeds = [];

var network = [
    {
        type: 'list',
        name: 'network',
        message: 'Choose your network',
        choices: [ 'Bitcoin', 'Testnet' ],
        filter: function( val ) { return val.toLowerCase(); }
    }
];

inquirer.prompt(network, function(answers) {
    // Use user feedback for... whatever!!
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

    function ask() {
        inquirer.prompt( seedsQuestions, function( answers ) {
            seeds.push( answers.seed );
            if (answers.askAgain) {
                ask();

                return;
            }

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

                inquirer.prompt({
                    type: 'input',
                    name: 'name',
                    message: 'Please enter a name for your keychain'
                }, function(keychain) {

                    console.log('\n');
                    console.log('You\'re gonna to generate a HD Wallet for a ' + signatures.required + '-of-' + seeds.length + ' multisig. This is your seeds :\n');
                    console.log(seeds.join(', '));
                    console.log('\n');
                    console.log('Your keychain will be saved as '+keychain.name);
                    console.log('\n');

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

                        var wallets = [];

                        for (var i=0; i < seeds.length; i++) {
                            var wallet = new bitcoin.Wallet(crypto.sha256(seeds[i]), bitcoin.networks.bitcoin);
                            wallets.push(wallet);
                            console.log(wallet.getExternalAccount().derive(0).pubKey.toHex());
//                            console.log(wallet.getExternalAccount().derive(0).pubKey.toHex()); // store it in DB & ECDSA public key too


                        }

                        // derive keys, start from #1
                        for (var j=1; j < 1000; j++) {
                            var pubKeys = [];

                            for (var w=0; w < wallets.length; w++) {
                                pubKeys.push(wallets[w].getExternalAccount().derive(j).pubKey)
                            }

                            console.log(pubKeys.map(function(item) {
                                return item.toHex();
                            }));
                            var redeemScript = bitcoin.scripts.multisigOutput(signatures.required, pubKeys);
                            var scriptPubKey = bitcoin.scripts.scriptHashOutput(redeemScript.getHash());

                            var multisigAddress = bitcoin.Address.fromOutputScript(scriptPubKey).toString();
                            console.log('#'+j+' '+multisigAddress);
                        }

                    });

                });





            })
        });
    }

    ask();
});