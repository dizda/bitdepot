'use strict';

app.factory('LoginService', ['$rootScope', '$http', 'localStorageService', function ($rootScope, $http, localStorageService) {

    var credentials = localStorageService.get('credentials');

    if (credentials) {
        $http.defaults.headers.common.Authorization = 'Bearer ' + credentials.token;  // Step 1
    }

    return {
        login: function (credentials) {
            $http
                .post('/api/login_check', credentials, { ignoreAuthModule: true })
                .success(function (data, status, headers, config) {
                    localStorageService.set('credentials', data); // save into localStorage
                    $http.defaults.headers.common.Authorization = 'Bearer ' + data.token;  // Step 1
                    // config.headers.Authorization = 'Bearer ' + data.token;
                    $rootScope.$broadcast('login:auth-login-complete');

                })
                .error(function (data, status, headers, config) {
                    $rootScope.$broadcast('login:auth-login-failed', status);
                })
            ;
        },

        logout: function () {
            localStorageService.remove('credentials');
            delete $http.defaults.headers.common.Authorization;
            $rootScope.$broadcast('login:auth-logout-complete');
        },

        /**
         * Recover updated data of the user
         *
         * @param {Function} callback
         */
        ping: function (callback) {
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
        }
    };
}]);