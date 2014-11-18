'use strict';

var app = angular.module('app', ['ngResource', 'ngRoute', 'ngSanitize', 'ngAnimate', 'mgcrea.ngStrap']);

app.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
    $routeProvider

        // route for the home page
        .when('/', {
            templateUrl : '/js/app/home.html',
            controller  : 'HomeCtrl'
        })

        .when('/withdraw', {
            templateUrl : '/js/app/withdraw/list.html',
            controller  : 'WithdrawCtrl'
        })

        .otherwise({redirectTo: '/404'})
    ;

    $locationProvider.html5Mode(true);

}]);