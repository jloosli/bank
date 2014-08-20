(function () {

    /**
     * @ngInject
     * @param $scope
     * @constructor
     */
    function LoginCtrl($auth, authService) {
        'use strict';
        this.authenticate = function (provider) {
            $auth.authenticate(provider).then(function() {
                console.log('authenticated!');
            })
                .catch(function(response) {
                    console.log(response.data);
                });
        };
        this.login = function() {
            var self = this;
            //$auth.login({email: self.email, password: self.password})
            //    .catch(function(response) {
            //        $alert({
            //            title: 'Error!',
            //            content: response.data.message,
            //            animation: 'fadeZoomFadeDown',
            //            type: 'material',
            //            duration: 3
            //        });
            //    });
        };
        this.storeToken = function (token) {
            console.log($auth.isAuthenticated());
            console.log(token);
            authService.setToken(token);
            console.log(authService.getCurrentUser());
        };
    }

    angular.module('jrbank').controller('LoginCtrl', LoginCtrl);
})();