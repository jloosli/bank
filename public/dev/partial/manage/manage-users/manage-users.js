(function() {

    /**
     * @ngInject
     */
    function manageUsers($scope, banksService){
        var self = this;
        banksService.users().get().$promise.then(function(result) {
            self.users = result.users;
        })

    }

    angular.module('jrbank').controller('ManageUsersCtrl',manageUsers);
})();

