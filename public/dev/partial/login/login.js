(function () {

    /**
     * @ngInject
     * @constructor
     */
    function LoginCtrl($auth, authService) {
        'use strict';
        var self = this;
        this.authenticate = function (provider) {
            $auth.authenticate(provider).then(function () {
                console.log('authenticated!');
            })
                .catch(function (response) {
                    console.log(response.data);
                    self.error = response.data.error;
                    //$alert({
                    //    title:     'Error!',
                    //    content:   response.data.message,
                    //    animation: 'fadeZoomFadeDown',
                    //    type:      'material',
                    //    duration:  3
                    //});
                });
        };
        this.login = function () {
            var self = this;
            $auth.login({email: self.email, password: self.password})
                .catch(function(response) {
                    //$alert({
                    //    title: 'Error!',
                    //    content: response.data.message,
                    //    animation: 'fadeZoomFadeDown',
                    //    type: 'material',
                    //    duration: 3
                    //});
                });
        };
    }

    angular.module('jrbank').controller('LoginCtrl', LoginCtrl);
})();