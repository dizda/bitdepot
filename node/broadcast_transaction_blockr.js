#!/usr/bin/env node

'use strict';

/**
 * Since we got trouble with Insight to broadcast transactions, I prefer to switch to blockr
 *
 */
var util         = require('util'),
    yaml         = require('js-yaml'),
    serializedTx = process.argv[2],
    http         = require('http');

var body = JSON.stringify({
    hex: serializedTx
});

var request = new http.ClientRequest({
    hostname: 'btc.blockr.io',
    port: 80,
    path: '/api/v1/tx/push',
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Content-Length': Buffer.byteLength(body)
    }
});

request.on('response', function (response) {
    response.setEncoding('utf8');

    response.on('data', function (chunk) {
        var json = JSON.parse(chunk);

        if (response.statusCode !== 200 || json.code !== 200) {
            throw new Error(util.format('Unable to broadcast %s: %s', serializedTx, json.data));
        }

        // Mark the transaction as broadcasted
        console.log(json.data);
    });
});

request.end(body);

