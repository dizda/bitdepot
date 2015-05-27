'use strict';

angular.module('app').factory('Address', ['$resource', function($resource) {
    return $resource('/api/addresses/:id/:operation.json', {id: '@id'});
}]);