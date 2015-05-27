'use strict';

angular.module('app').factory('Withdraw', ['$resource', function($resource) {
    return $resource('/api/withdraws/:id/:operation.json', {id: '@id'});
}]);