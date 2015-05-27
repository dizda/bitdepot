'use strict';

angular.module('app').factory('AuthService', ['$rootScope', '$http', 'localStorageService', 'Session', 'AUTH_EVENTS', function ($rootScope, $http, localStorageService, Session, AUTH_EVENTS) {

    var authService = {};

    authService.retrieveSession = function() {
        var credentials = localStorageService.get('credentials');

        if (credentials) {
            $http.defaults.headers.common.Authorization = 'Bearer ' + credentials.token;  // Step 1
            Session.create(credentials.token, credentials.data.username);
            $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
        }
    };

    authService.login = function (credentials) {
        $http
            .post('/api/login_check', credentials, { ignoreAuthModule: true })
            .success(function (response, status, headers, config) {
                localStorageService.set('credentials', response); // save into localStorage
                $http.defaults.headers.common.Authorization = 'Bearer ' + response.token;  // Step 1

                // Create session
                Session.create(response.token, response.data.username);

                // Dispatch event to all services
                $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);

            })
            .error(function (data, status, headers, config) {
                $rootScope.$broadcast('login:auth-login-failed', status);
            })
        ;
    };

    authService.isAuthenticated = function () {
        return !!Session.username;
    };

    authService.isAuthorized = function (authorizedRoles) {
//        if (!angular.isArray(authorizedRoles)) {
//            authorizedRoles = [authorizedRoles];
//        }
//        return (authService.isAuthenticated() &&
//            authorizedRoles.indexOf(Session.userRole) !== -1);

        return authService.isAuthenticated();
    };

    authService.logout = function () {
        localStorageService.remove('credentials');
        delete $http.defaults.headers.common.Authorization;
        Session.destroy();

        $rootScope.$broadcast(AUTH_EVENTS.logoutSuccess);
    };

    /**
     * Recover updated data of the user
     *
     * @param {Function} callback
     */
    authService.ping = function (callback) {
        $http
            .get('/api/ping', {})
            .success(function (response) {
                angular.extend(credentials.data, response);
                localStorageService.set('credentials', credentials);

                // Return username, balance, etc.
                callback(credentials.data);
            })
            .error(function (data, status) {
                $rootScope.$broadcast(AUTH_EVENTS.loginFailed);
            })
        ;
    };

    return authService;
}]);