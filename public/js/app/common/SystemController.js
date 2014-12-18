'use strict';

app.controller('SystemCtrl', ['$scope', '$http', '$location', 'localStorageService', 'LoginService', function($scope, $http, $location, localStorageService, LoginService) {

    $scope.credentials = {};


//    $scope.$on('event:auth-loginRequired', function () {
//        $location.path('/');
//    });

    $scope.$on('event:auth-login-complete', function () {
        alert('Finally logged!');
    });

    $scope.$on('event:auth-loginConfirmed', function (e, credentials) {
        $scope.credentials = credentials.data;

        $location.path('/surf');
    });

    $scope.$on('event:auth-login-failed', function () {
        alert('failed!');
    });

    $scope.$on('event:auth-logout-complete', function () {
        $scope.credentials = {};
        $location.path('/');
    });

    $scope.$on('$routeChangeSuccess', function(e, currentRoute) {
        $scope.isSurfing = 'SurfCtrl' === currentRoute.$$route.controller;
    });

    /**
     * After successful confirmed visit, the balance is updated
     */
    $scope.$on('balance.updated', function(e, balance)
    {
        $scope.credentials.balance = balance;
    });

    $scope.test = function()
    {
        $http
            .get('/api/surf/visit.json')
            .then(function (response) {
                alert('ok');
            })
        ;
    };

    $scope.login = function()
    {
        LoginService.login({
            _username: 'dizda',
            _password: 'bambou'
        });
    };

    $scope.logout = function()
    {
        LoginService.logout();
    };

}]);