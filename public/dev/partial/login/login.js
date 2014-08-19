(function() {

    /**
     * @ngInject
     * @param $scope
     * @constructor
     */
    function LoginCtrl ($scope, $auth) {
        $scope.authenticate = function(provider) {
            $auth.authenticate(provider);
        }
    }
    angular.module('jrbank').controller('LoginCtrl', LoginCtrl);
})();