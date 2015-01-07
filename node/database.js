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
        created_at: new Date(),
        updated_at: new Date()
    };

    this.client.query('INSERT INTO keychain SET ?', keychain, function(err, result) {
        if (err) throw err;

        deferred.resolve(result.insertId);
    });

    return deferred.promise;
};

Database.prototype.addApplication = function(keychainId, name)
{
    var deferred = Q.defer();

    var application = {
        keychain_id: keychainId,
        name:        name,
        app_id:      '1313131',         // generated value
        app_secret:  '1313131secret',   // generated value
        callback_endpoint:  'http://test.com',
        confirmations_required:  6,
        group_withdraws_by_quantity:  1,
        created_at:     new Date(),
        updated_at:     new Date()
    };

    this.client.query('INSERT INTO application SET ?', application, function(err, result) {
        if (err) throw err;

        deferred.resolve(result.insertId);
    });

    return deferred.promise;
};

Database.prototype.addPubkeys = function(applicationId, name, extendedPubKey)
{
    var deferred = Q.defer();

    var pubKey = {
        name: name,
        application_id: applicationId,
        extended_pub_key: extendedPubKey,
        created_at: new Date(),
        updated_at: new Date()
    };

    this.client.query('INSERT INTO pub_key SET ?', pubKey, function(err, result) {
        if (err) throw err;

        deferred.resolve(result.insertId);
    });

    return deferred.promise;
};

module.exports = Database;
