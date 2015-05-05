'use strict';

var mysql = require('mysql')
  , Q     = require('q');


function Database(host, port, dbName, user, password)
{
    this.client = mysql.createConnection({
        host     : host,
        port     : port,
        database : dbName,
        user     : user,
        password : password
    });

    return this;
}

Database.prototype.addKeychain = function(name, requiredSignatures)
{
    var deferred = Q.defer();

    var keychain = {
        name: name,
        sign_required: requiredSignatures,
        group_withdraws_by_quantity:  1,
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
        created_at:     new Date(),
        updated_at:     new Date()
    };

    this.client.query('INSERT INTO application SET ?', application, function(err, result) {
        if (err) throw err;

        deferred.resolve(result.insertId);
    });

    return deferred.promise;
};

Database.prototype.addPubkeys = function(keychainId, applicationId, name, extendedPubKey, publicKey)
{
    var deferred = Q.defer();
    var self     = this;

    var identity = {
        keychain_id: keychainId,
        name:        name,
        public_key:  publicKey,
        created_at:  new Date(),
        updated_at:  new Date()
    };

    // Create an identity first
    this.client.query('INSERT INTO identity SET ?', identity, function(err, result) {
        if (err) throw err;

        var pubKey = {
            identity_id:    result.insertId,
            application_id: applicationId,
            extended_pub_key: extendedPubKey,
            created_at: new Date(),
            updated_at: new Date()
        };

        // Then create the attached pubKey with the extendedPubKey
        self.client.query('INSERT INTO pub_key SET ?', pubKey, function(err, res) {
            if (err) throw err;

            deferred.resolve(res.insertId);
        });
//        deferred.resolve(result.insertId);
    });

    return deferred.promise;
};

module.exports = Database;
