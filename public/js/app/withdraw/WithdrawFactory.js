'use strict';

app.factory('Withdraw', ['$resource', function($resource) {
    return $resource('/api/withdraws/:id/:operation.json', {id: '@id'});
}]);