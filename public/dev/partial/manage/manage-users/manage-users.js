(function() {

    /**
     * @ngInject
     */
    function manageUsers(users){
        var self = this;
        //console.log(users);
        users.$promise.then(function(results) {
            self.users = results.users;
        });
    }

    angular.module('jrbank').controller('ManageUsersCtrl',manageUsers);
})();

