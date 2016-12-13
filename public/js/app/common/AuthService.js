'use strict';

angular.module('app').factory('AuthService', ['$rootScope', '$http', 'localStorageService', 'Session', 'AUTH_EVENTS', function ($rootScope, $http, localStorageService, Session, AUTH_EVENTS) {

    var authService = {};

    authService.getCredentials = function() {
        return localStorageService.get('credentials');
    };

    authService.retrieveSession = function() {
        var credentials = authService.getCredentials();

        if (credentials) {
            $http.defaults.headers.common.Authorization = 'Bearer ' + credentials.token;  // Step 1

            this.ping(function(credentials) {
                // Recreate session
                Session.create(credentials.token, credentials.data.username);

                // Dispatch event to all services
                $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
            });
        }
    };

    authService.login = function (credentials) {
        var self = this;

        $http
            .post('/api/login_check', credentials, { ignoreAuthModule: true })
            .success(function (response, status, headers, config) {

                // Create session
                self.createSession(response);

                // Dispatch event to all services
                $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);

            })
            .error(function (data, status, headers, config) {
                $rootScope.$broadcast('login:auth-login-failed', status);
            })
        ;
    };

    authService.createSession = function(response) {
        localStorageService.set('credentials', response); // save into localStorage
        $http.defaults.headers.common.Authorization = 'Bearer ' + response.token;  // Step 1

        // Create session
        Session.create(response.token, response.data.username);
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
                var credentials = authService.getCredentials();
                credentials.data = {}; // clean old data
                angular.extend(credentials.data, response);
                localStorageService.set('credentials', credentials);

                // Return username, balance, etc.
                callback(credentials);
            })
            .error(function (data, status) {
                // If we got 401 Unauthorized, so the token is invalid
                if (status === 401) {
                    localStorageService.remove('credentials');
                }

                $rootScope.$broadcast(AUTH_EVENTS.loginFailed);
            })
        ;
    };

    return authService;
}]);