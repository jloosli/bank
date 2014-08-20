(function () {

    /**
     * @ngInject
     * @param $scope
     * @constructor
     */
    function LoginCtrl($auth, authService) {
        'use strict';
        this.authenticate = function (provider) {
            $auth.authenticate(provider);
        };
        this.storeToken = function (token) {
            console.log(token);
            authService.setToken(token);
        };
    }

    angular.module('jrbank').controller('LoginCtrl', LoginCtrl);
})();