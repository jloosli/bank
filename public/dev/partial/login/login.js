(function () {

    /**
     * @ngInject
     * @constructor
     */
    function LoginCtrl($auth, $state, alertsService) {
        'use strict';
        var self = this;

        this.authenticate = function (provider) {
            self.msg = alertsService.add({
                text: 'Checking with ' + provider + '...'
            });
            $auth.authenticate(provider).then(function () {
                alertsService.add({
                    text: 'Authenticated with ' + provider
                });
                console.log('authenticated!');
                $state.go('root.accounts');
            })
                .catch(function (response) {
                    alertsService.add({
                        text: 'Could not authenticate with ' + provider,
                        type: 'danger'
                    });
                    console.log(response.data);
                    self.error = response.data.error;
                })
                .finally(function () {
                    alertsService.remove(self.msg);
                });
        };
        this.login = function () {
            var self = this;
            alertsService.removeAll();
            $auth.login({email: self.email, password: self.password})
                .then(function (response) {
                    $state.go('root.accounts');
                })
                .catch(function (response) {
                    alertsService.add({
                        text: 'Invalid login credentials. <a class="alert-link" href="#/user/login/reminder">Need a reset? Click here.</a>',
                        raw:  true,
                        type: 'danger'
                    });
                    console.log(response);
                });
        };
    }

    angular.module('jrbank').controller('LoginCtrl', LoginCtrl);
})();