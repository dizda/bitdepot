'use strict';

app.factory('Withdraw', ['$resource', function($resource) {
    return $resource('/api/withdraws/:id.json', {id: '@id'});
}]);