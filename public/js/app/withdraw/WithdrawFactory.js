'use strict';

app.factory('Withdraw', ['$resource', function($resource) {
    return $resource('/api/withdraws.json', {id: '@id'});
}]);