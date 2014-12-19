'use strict';

app.factory('AuthService', ['$rootScope', '$http', 'localStorageService', 'Session', 'AUTH_EVENTS', function ($rootScope, $http, localStorageService, Session, AUTH_EVENTS) {

    var authService = {};
    var credentials = localStorageService.get('credentials');

    if (credentials) {
        $http.defaults.headers.common.Authorization = 'Bearer ' + credentials.token;  // Step 1
    }
    // TODO: Login automatically when refreshing the page


    authService.login = function (credentials) {
        $http
            .post('/api/login_check', credentials, { ignoreAuthModule: true })
            .success(function (response, status, headers, config) {
                localStorageService.set('credentials', response); // save into localStorage
                $http.defaults.headers.common.Authorization = 'Bearer ' + response.token;  // Step 1

                $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);

                Session.create(response.token, response.data.username);

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
        $rootScope.$broadcast('login:auth-logout-complete');
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
                $rootScope.$broadcast('login:auth-login-failed', status);
            })
        ;
    };

    return authService;
}]);