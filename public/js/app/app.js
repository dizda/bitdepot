'use strict';

angular.module('app', ['ngResource', 'ngRoute', 'ngSanitize', 'ngAnimate', 'mgcrea.ngStrap', 'LocalStorageModule', 'bgf.paginateAnything']);

/**
 * Constants
 */
angular.module('app').constant('AUTH_EVENTS', {
    loginSuccess:     'auth-login-success',
    loginFailed:      'auth-login-failed',
    logoutSuccess:    'auth-logout-success',
    sessionTimeout:   'auth-session-timeout',
    notAuthenticated: 'auth-not-authenticated',
    notAuthorized:    'auth-not-authorized'
});

/**
 * Routes
 */
angular.module('app').config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
    $routeProvider

        .when('/', {
            templateUrl : '/js/app/home.html',
            controller  : 'HomeCtrl'
        })

        .when('/addresses', {
            templateUrl : '/js/app/address/list.html',
            controller  : 'AddressCtrl'
        })

        .when('/deposits', {
            templateUrl : '/js/app/deposit/list.html',
            controller  : 'DepositCtrl'
        })

        .when('/withdraws', {
            templateUrl : '/js/app/withdraw/list.html',
            controller  : 'WithdrawCtrl'
        })

        .otherwise({redirectTo: '/404'})
    ;

    $locationProvider.html5Mode(true);

}]);

/**
 * Run once the appli loaded
 *
 * @see https://medium.com/opinionated-angularjs/techniques-for-authentication-in-angularjs-applications-7bbf0346acec
 */
angular.module('app').run(['$rootScope', 'AUTH_EVENTS', 'AuthService', function ($rootScope, AUTH_EVENTS, AuthService) {
//    if (!AuthService.isAuthenticated()) {
//        $rootScope.$broadcast(AUTH_EVENTS.notAuthenticated);
//    }
    AuthService.retrieveSession();

    $rootScope.$on('$routeChangeStart', function (event, next) {
//        var authorizedRoles = next.data.authorizedRoles;
        var authorizedRoles = null;
//        if (!AuthService.isAuthorized(authorizedRoles)) {
        if (!AuthService.isAuthorized(authorizedRoles)) {
            event.preventDefault();
            if (AuthService.isAuthenticated()) {
                // user is not allowed
                $rootScope.$broadcast(AUTH_EVENTS.notAuthorized);
            } else {
                // user is not logged in
                $rootScope.$broadcast(AUTH_EVENTS.notAuthenticated);
            }
        }
    });
}]);