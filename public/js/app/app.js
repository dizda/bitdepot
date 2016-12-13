'use strict';

angular.module('app', ['ngResource', 'ngRoute', 'ngSanitize', 'ngAnimate', 'mgcrea.ngStrap.modal', 'LocalStorageModule', 'bgf.paginateAnything']);

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
angular.module('app').config(['$routeProvider', '$locationProvider', '$httpProvider', function($routeProvider, $locationProvider, $httpProvider) {
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
    $httpProvider.interceptors.push('authInterceptor');
}]);

angular.module('app').factory('authInterceptor', ['$rootScope', '$q', 'AUTH_EVENTS', function($rootScope, $q, AUTH_EVENTS) {
    return {
        // Intercept 401s and redirect you to login
        responseError: function(response) {
            if(response.status === 401 || response.status === 403) {
                // Remove the localStorage and redirect!
                $rootScope.$broadcast(AUTH_EVENTS.notAuthorized);

                // remove any stale tokens
                return $q.reject(response);
            }
            else {
                return $q.reject(response);
            }
        }
    };
}]);

/**
 * Run once the appli loaded
 *
 * @see https://medium.com/opinionated-angularjs/techniques-for-authentication-in-angularjs-applications-7bbf0346acec
 */
angular.module('app').run(['AuthService', function (AuthService) {

    AuthService.retrieveSession();

}]);