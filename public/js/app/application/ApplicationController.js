'use strict';

angular.module('app').controller('ApplicationCtrl', ['$scope', '$location', 'Application', function($scope, $location, Application) {

    $scope.applications = Application.query();

}]);
