'use strict';

angular.module('app').factory('Deposit', ['$resource', function($resource) {
    return $resource('/api/deposits/:id/:operation.json', {id: '@id'});
}]);