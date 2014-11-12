'use strict';

var app = angular.module('app', ['ngResource', 'ngRoute', 'ngSanitize']);

app.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
    $routeProvider

        // route for the home page
        .when('/', {
            templateUrl : '/js/app/home.html',
            controller  : 'HomeCtrl'
        })

        //.otherwise({redirectTo: '/phones'})
    ;

    $locationProvider.html5Mode(true);

}]);