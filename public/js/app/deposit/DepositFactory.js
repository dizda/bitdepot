'use strict';

app.factory('Deposit', ['$resource', function($resource) {
    return $resource('/api/deposits/:id/:operation.json', {id: '@id'});
}]);