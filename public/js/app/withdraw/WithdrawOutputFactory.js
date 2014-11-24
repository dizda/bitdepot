'use strict';

app.factory('WithdrawOutput', ['$resource', function($resource) {
    return $resource('/api/withdraws/:application/outputs/:id.json', {application: '@application', id: '@id'});
}]);