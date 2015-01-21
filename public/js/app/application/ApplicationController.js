'use strict';

app.controller('ApplicationCtrl', ['$scope', '$location', 'Application', function($scope, $location, Application) {

    $scope.applications = Application.query();

}]);
