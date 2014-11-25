'use strict';

app.factory('WithdrawOutput', ['$resource', function($resource) {
    return $resource('/api/withdraws/:application_id/outputs/:id.json', {application_id: '@application_id', id: '@id'});
}]);