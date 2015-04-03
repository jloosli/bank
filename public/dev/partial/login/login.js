(function () {

    /**
     * @ngInject
     * @constructor
     */
    function LoginCtrl($auth, $state) {
        'use strict';
        var self = this;
        this.authenticate = function (provider) {
            self.loginMessage = "Checking with your provider...";
            $auth.authenticate(provider).then(function () {
                console.log('authenticated!');
                self.loginMessage = "Authenticated!";
                $state.go('root.accounts');
            })
                .catch(function (response) {
                    console.log(response.data);
                    self.error = response.data.error;
                    self.loginMessage = "";
                });
        };
        this.login = function () {
            var self = this;
            $auth.login({email: self.email, password: self.password})
                .then(function (response) {
                    $state.go('root.accounts');
                })
                .catch(function (response) {
                    console.log(response);
                });
        };
    }

    angular.module('jrbank').controller('LoginCtrl', LoginCtrl);
})();