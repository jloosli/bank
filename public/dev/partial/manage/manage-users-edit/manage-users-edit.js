(function () {
    'use strict';
    /**
     * @ngInject
     * @param $scope
     * @constructor
     */
    function UserEdit($scope, $state, $stateParams, users, banksService) {
        var self = this;
        var params = $stateParams;

        users.$promise.then(function (result) {
            self.user = _.find(result.users, function (user) {
                if(params.id.toString().indexOf('new-') === 0) {
                    return user.id === params.id;
                }
                return parseInt(user.id) === parseInt(params.id);
            });
            // If they happened to refresh the screen, take them back to a clean representation of the users.
            if(!self.user) {
                $state.go('^');
            }
        });

        this.isNew = function() {
            return self.user && self.user.id.toString().indexOf('new-') === 0;
        };


        $scope.$on('$destroy', function() {
            $scope.$parent.manageUsers.removeUnsavedUsers();
        });

        this.save = function () {

            if (typeof self.user.password !== 'undefined' && !self.user.password) {
                delete(self.user.password);
            } else if (self.user.password) {
                if (self.user.password !== self.user.password_check) {
                    console.log('Passwords don\'t match');
                    //@todo throw up error on form
                    return;
                }
            }

            if (self.user.id.toString().indexOf('new-') === 0) {
                var parent = $scope.$parent; // Grab the parent scope now since by the time the service resolves, $scope will be destroyed
                banksService.users().save(self.user).$promise.then(function (result) {
                    parent.manageUsers.addUser(result.data);
                });
            } else {
                banksService.users(self.user.id).update(self.user).$promise.then(function (result) {
                    console.log(result);
                    if (result.success) {
                    }
                });
            }
            $state.go('^');
        };
    }

    angular.module('jrbank').controller('ManageUsersEditCtrl', UserEdit);
})();