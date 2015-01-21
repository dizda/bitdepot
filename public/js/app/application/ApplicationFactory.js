'use strict';

app.factory('Application', ['$resource', function($resource) {
    return $resource('/api/applications/:id/:operation.json', {id: '@id'});
}]);