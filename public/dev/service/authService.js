(function () {
    /*
     @ngInject
     */
    function authService($resource) {

        var authSvc = {},
            token;

        function getTokenFromLS() {
            "use strict";
            return localStorage.getItem('auth_token');
        }

        authSvc.getToken = function () {
            "use strict";
            if (token === undefined) {
                token = localStorage.getItem('token');
            }
            return token;
        };

        authSvc.setToken = function (token) {
            "use strict";
            localStorage.setItem('token', token);
        };

        authSvc.clearToken = function () {
            "use strict";
            token = null;

        };
        return authSvc;
    }


    angular.module('jrbank').factory('authService', authService);
})();