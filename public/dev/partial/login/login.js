(function() {

    /**
     * @ngInject
     * @param $scope
     * @constructor
     */
    function LoginCtrl ($scope) {
        var me = this;
        $scope.$on('event:google-plus-signin-success', function (event, authResult) {
            console.log(event);
            console.log(authResult);

        });
    }
    angular.module('jrbank').controller('LoginCtrl', LoginCtrl);
})();