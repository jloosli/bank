(function () {
    /*
     @ngInject
     */
    function authService($http) {

        var authSvc = {};

        authSvc.init = function () {
            if(!!this.getToken()) {
                $http.defaults.headers.common.Authorization = this.getAuthString();
            }
        };

        authSvc.getToken = function () {
            "use strict";
            return localStorage.getItem('auth_token');
        };

        authSvc.setToken = function (newToken) {
            "use strict";
            localStorage.setItem('auth_token', newToken);
        };

        authSvc.clearToken = function () {
            "use strict";
            localStorage.removeItem('auth_token');

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
            return localStorage.getItem('current_user');
        };
        return authSvc;
    }


    angular.module('jrbank').factory('authService', authService);
})();