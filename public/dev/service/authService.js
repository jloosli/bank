(function () {
    /*
     @ngInject
     */
    function authService($http, $q, $auth, API_URL, ACCESS_LEVELS) {

        var svc = {};

        function accessLevel() {
            var payload = $auth.getPayload();

            if(!payload || !payload.user) {
                return 0;
            } else  {
                switch (payload.user.user_type) {
                    case 'user':
                        return 1;
                    case 'admin':
                    case 'parent':
                        return 2;
                    case 'super-admin':
                        return 3;
                    default:
                        return 0;
                }
            }
        }

        svc.checkAccess = function (routeAccess) {
            return accessLevel() >= routeAccess;
        };

        svc.init = function () {
            if(!!this.getToken()) {
                $http.defaults.headers.common.Authorization = this.getAuthString();
            } else {
                delete $http.defaults.headers.common.Authorization;
            }
        };

        svc.getToken = function () {
            "use strict";
            return localStorage.getItem('auth_token');
        };

        svc.setToken = function (newToken) {
            "use strict";
            localStorage.setItem('auth_token', newToken);
            this.init();
        };

        svc.clearToken = function () {
            "use strict";
            localStorage.removeItem('auth_token');
            localStorage.removeItem('current_user');
            this.init();
        };

        svc.getAuthString = function () {
            "use strict";
            var theToken = this.getToken();
            if (!!theToken) {
                return 'Basic ' + btoa(theToken + ":");
            }
            return null;
        };

        svc.getCurrentUser = function() {
            "use strict";
            var payload = $auth.getPayload();
            return payload.user || false;
        };
        return svc;
    }


    angular.module('jrbank').factory('authService', authService);
})();