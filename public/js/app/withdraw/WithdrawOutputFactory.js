'use strict';

app.factory('WithdrawOutput', ['$resource', function($resource) {
    return $resource('/api/withdraw/outputs/:id.json', {id: '@id'});
}]);