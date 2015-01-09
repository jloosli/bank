(function () {
    /**
     * @ngInject
     */
    function manageUsers($scope, $state, users) {
        'use strict';
        /*jshint validthis: true */
        var self = this,
            clean_user_type;

        users.$promise.then(function (results) {
            self.users = results.users;

            // @todo: This needs to be moved to a function and applied to everything
            switch (results.users.user_type) {
                case 'admin':
                    clean_user_type = 'Administrator (parent)';
                    break;
                case 'super-admin':
                    clean_user_type = 'Super Administrator';
                    break;
                //case 'user':
                default:
                    clean_user_type = 'Account Holder';
                    break;
            }
            self.users['user_type_clean'] = clean_user_type;
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

