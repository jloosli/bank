(function () {
    'use strict';
    /**
     * @ngInject
     * @param $scope
     * @constructor
     */
    function UserEdit($state, $stateParams, users, banksService) {
        var self = this;
        var params = $stateParams;
        users.$promise.then(function (result) {
            self.user = _.find(result.users, function (user) {
                return parseInt(user.id) === parseInt(params.id);
            });
        });

        this.save = function() {

            if (typeof self.user.password !== 'undefined' && !self.user.password) {
                delete(self.user.password);
            } else if (self.user.password) {
                if(self.user.password !== self.user.password_check ) {
                    console.log('Passwords don\'t match');
                    //@todo throw up error on form
                    return;
                }
            }
            banksService.users(self.user.id).update(self.user).$promise.then(function(result) {
                console.log(result);
                if(result.success) {
                    $state.go('^');
                }
            });

        };
    }

    angular.module('jrbank').controller('ManageUsersEditCtrl', UserEdit);
})();