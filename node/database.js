'use strict';

var mysql = require('mysql')
  , Q     = require('q');


function Database()
{
    this.client = mysql.createConnection({
        host     : 'localhost',
        user     : 'root',
        password : '',
        database : 'bitwallet'
    });

    return this;
}

Database.prototype.addKeychain = function(name, requiredSignatures)
{
    var deferred = Q.defer();

    var keychain = {
        name: name,
        sign_required: requiredSignatures,
        created_at: new Date()
    };

    this.client.query('INSERT INTO keychain SET ?', keychain, function(err, result) {
        if (err) throw err;

        deferred.resolve(result.insertId);
    });

    return deferred.promise;
};

Database.prototype.addPubkeys = function(keychainId, name, value)
{
    var deferred = Q.defer();

    var pubkey = {
        keychain_id: keychainId,
        name: name,
        value: value,
        created_at: new Date()
    };

    this.client.query('INSERT INTO pubkey SET ?', pubkey, function(err, result) {
        if (err) throw err;

        deferred.resolve(result.insertId);
    });

    return deferred.promise;
};

Database.prototype.addAddress = function(keychainId, value, isExternal, derivation)
{
    var deferred = Q.defer();

    var address = {
        keychain_id: keychainId,
        value: value,
        is_external: isExternal,
        derivation: derivation,
        balance: 0,
        created_at: new Date()
    };

    this.client.query('INSERT INTO address SET ?', address, function(err, result) {
        if (err) throw err;

        deferred.resolve(result.insertId);
    });

    return deferred.promise;
};

module.exports = Database;
