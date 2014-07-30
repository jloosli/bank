(function () {
    /*
     @ngInject
     */
    function authService($http, $q, API_URL) {

        var authSvc = {};

        authSvc.init = function () {
            if(!!this.getToken()) {
                $http.defaults.headers.common.Authorization = this.getAuthString();
            } else {
                delete $http.defaults.headers.common.Authorization;
            }
        };

        authSvc.getToken = function () {
            "use strict";
            return localStorage.getItem('auth_token');
        };

        authSvc.setToken = function (newToken) {
            "use strict";
            localStorage.setItem('auth_token', newToken);
            this.init();
        };

        authSvc.clearToken = function () {
            "use strict";
            localStorage.removeItem('auth_token');
            this.init();
        };

        authSvc.getAuthString = function () {
            "use strict";
            var theToken = this.getToken();
            if (!!theToken) {
                return 'Basic ' + btoa(theToken + ":");
            }
            return null;
        };

        authSvc.getCurrentUser = function() {
            "use strict";
            var user = localStorage.getItem('current_user');
            if(!!user) {
                return $q.when(user);
            } else {
                return $http({
                    method: 'GET',
                    url: API_URL+ 'users/me'
                }).then(function (user) {
                    /*
                    Response format:
                     {"user":{"id":1,"username":"first_user","name":"First User","email":"first@example.com","slug":"first_user","bank_id":1,"user_type":"user","balance":0,"created_at":"2014-07-30 21:23:32","updated_at":"2014-07-30 21:23:32"}}
                     */
                    localStorage.setItem('current_user', user.user);
                    return this.getCurrentUser;
                });
            }
        };
        return authSvc;
    }


    angular.module('jrbank').factory('authService', authService);
})();