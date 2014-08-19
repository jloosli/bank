(function() {

    /**
     * @ngInject
     * @param $scope
     * @constructor
     */
    function LoginCtrl ($scope, $http) {
        var me = this;
        $scope.$on('event:google-plus-signin-success', function (event, authResult) {
            console.log(event);
            console.log(authResult);

            $http({
                method: 'GET',
                url: '/oauth/google',
                params: {
                    code: authResult.code
                }
            });

        });
    }
    angular.module('jrbank').controller('LoginCtrl', LoginCtrl);
})();