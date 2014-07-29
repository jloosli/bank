(function () {
    /*
     @ngInject
     */
    function authService($http) {

        var authSvc = {},
            token,
            currentUser;

        authSvc.getToken = function () {
            "use strict";
            if (!!token) {
                token = localStorage.getItem('auth_token');
            }
            return token;
        };

        authSvc.setToken = function (newToken) {
            "use strict";
            localStorage.setItem('auth_token', newToken);
            token = newToken;
        };

        authSvc.clearToken = function () {
            "use strict";
            token = null;
            localStorage.removeItem('auth_token');

        };

        authSvc.getAuthString = function () {
            "use strict";
            var theToken = this.getToken();
            if (!!theToken) {
                return 'Basic ' + btoa(theToken + ":");
            }
        };

        authSvc.getCurrentUser = function() {
            "use strict";
            if(!!currentUser ) {

            }
            return currentUser;
        };
        return authSvc;
    }


    angular.module('jrbank').factory('authService', authService);
})();