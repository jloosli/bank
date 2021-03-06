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
        this.message = '';

        users.$promise.then(function (result) {
            self.user = _.find(result.users, function (user) {
                if (params.id.toString().indexOf('new-') === 0) {
                    return user.id === params.id;
                }
                return parseInt(user.id) === parseInt(params.id);
            });
            // If they happened to refresh the screen, take them back to a clean representation of the users.
            if (!self.user) {
                $state.go('^');
            }
        });

        this.isNew = function () {
            return self.user && self.user.id.toString().indexOf('new-') === 0;
        };

        this.suggestUsername = function (pristine) {
            if (self.isNew() && pristine) {
                self.user.username = self.user.name.replace(/ /g, '_').toLowerCase();
            }
        };


        $scope.$on('$destroy', function () {
            $scope.$parent.manageUsers.removeUnsavedUsers();
        });

        this.save = function () {
            self.message='';
            var promise;

            if (typeof self.user.password !== 'undefined' && !self.user.password) {
                delete(self.user.password);
            } else if (self.user.password) {
                if (self.user.password !== self.user.password_check) {
                    self.message="Passwords don't match";
                    return;
                }
            }
            var parent = $scope.$parent; // Grab the parent scope now since by the time the service resolves, $scope will be destroyed

            if (self.user.id.toString().indexOf('new-') === 0) {
                promise= banksService.users().save(self.user).$promise;
            } else {
                promise = banksService.users(self.user.id).update(self.user).$promise;
            }
            promise.then(function (result) {
                if (self.user.id.toString().indexOf('new-') === 0) {
                    parent.manageUsers.addUser(result.data);
                }
                $state.go('^');
            }).catch(function (result) {
                console.log(result);
                self.message="There was a problems saving the user.<br>";
                _.forEach(result.data.errors, function(item, idx) {
                    self.message += item+"<br>";
                });
            });
        };
    }

    var compareTo = function () {
        return {
            require: "ngModel",
            scope:   {
                otherModelValue: "=compareTo"
            },
            link:    function (scope, element, attributes, ngModel) {
                ngModel.$validators.compareTo = function (modelValue) {
                    return modelValue === scope.otherModelValue;
                };
                scope.$watch("otherModelValue", function () {
                    ngModel.$validate();
                });
            }
        };
    };


    angular.module('jrbank').controller('ManageUsersEditCtrl', UserEdit).directive('compareTo',compareTo);
})();