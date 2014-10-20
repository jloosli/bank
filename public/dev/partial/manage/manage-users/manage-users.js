(function () {
    /**
     * @ngInject
     */
    function manageUsers($scope, $state, users) {
        'use strict';
        /*jshint validthis: true */
        var self = this;

        users.$promise.then(function (results) {
            this.users = results.users;
        });

        this.addNew = function () {
            var newUser = {
                username:    'new_user',
                name:        'New Account Holder',
                id:          _.uniqueId('new-'),
                "user_type": 'user'
            };
            self.users.push(newUser);
            $state.go('root.manage.manage-users.manage-users-edit', {id: newUser.id});
        };

        this.removeUnsavedUsers = function () {
            _.remove(self.users, function (user) {
                return user.id.toString().indexOf('new-') === 0;
            });
        };

        this.addUser = function(user) {
            self.users.push(user);
        };

    }

    angular.module('jrbank').controller('ManageUsersCtrl', manageUsers);
})();

