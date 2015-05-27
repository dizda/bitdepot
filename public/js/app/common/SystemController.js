'use strict';

angular.module('app').controller('SystemCtrl', ['$scope', '$location', 'AuthService', 'Session', 'AUTH_EVENTS', function($scope, $location, AuthService, Session, AUTH_EVENTS) {

    $scope.credentials     = {
        username: '',
        password: ''
    };
    $scope.isAuthenticated = AuthService.isAuthenticated;
    $scope.currentUser     = Session;

    /**
     * Redirect after login
     */
    $scope.$on(AUTH_EVENTS.loginSuccess, function() {
        $location.path('/');
    });

    /**
     * Redirect after logout
     */
    $scope.$on(AUTH_EVENTS.logoutSuccess, function() {
        $location.path('/');
    });

    /**
     * Trigger a login
     */
    $scope.login = function()
    {
        AuthService.login({
            _username: $scope.credentials.username,
            _password: $scope.credentials.password
        });
    };

    /**
     * Logout
     */
    $scope.logout = function()
    {
        AuthService.logout();
    };

}]);