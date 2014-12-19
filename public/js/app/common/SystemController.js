'use strict';

app.controller('SystemCtrl', ['$scope', '$http', '$location', 'localStorageService', 'AuthService', function($scope, $http, $location, localStorageService, AuthService) {

    $scope.credentials     = {};
    $scope.isAuthenticated = AuthService.isAuthenticated;


    $scope.login = function()
    {
        AuthService.login({
            _username: $scope.credentials.username,
            _password: $scope.credentials.password
        });
    };

    $scope.logout = function()
    {
        AuthService.logout();
    };

}]);