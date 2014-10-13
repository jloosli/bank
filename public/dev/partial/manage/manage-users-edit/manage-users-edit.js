(function() {
    /**
     * @ngInject
     * @param $scope
     * @constructor
     */
    function UserEdit($scope, $stateParams, currentUser){
        var self = this;
        console.log(currentUser);
        this.params = $stateParams;
        console.log($scope.$parent.manageUsers);
        this.user = _.find($scope.$parent.users, function(user) {
            console.log(user);
            return parseInt(user.id) === parseInt(self.params.id);
        });

    }
    angular.module('jrbank').controller('ManageUsersEditCtrl',UserEdit);
})();