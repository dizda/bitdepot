#!/usr/bin/env node

'use strict';

var Insight      = require('bitcore-explorers').Insight,
    util         = require('util'),
    yaml         = require('js-yaml'),
    serializedTx = process.argv[2],
    config       = null;

// Load de Symfony config
/** @deprecated **/
//try {
//
//    config = yaml.safeLoad(require('fs').readFileSync(__dirname + '/../app/config/parameters.yml', 'utf8')).parameters;
//} catch (e) {
//
//    throw new Error(e);
//}


//var endpoint = config.api_endpoint.replace('/api/', '');

var insight = new Insight();

insight.broadcast(serializedTx, function(err, returnedTxId) {

    if (err) {

        // Handle errors...
        throw new Error(util.format('Unable to broadcast %s: %s', serializedTx, err));
    } else {

        // Mark the transaction as broadcasted
        console.log(returnedTxId);
    }
});
