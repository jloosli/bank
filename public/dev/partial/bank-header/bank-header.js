(function() {

    /**
     * @ngInject
     * @constructor
     */
    function BankHeaderController(utilsService) {

        this.logOut = utilsService.logout;
        this.isLoggedIn = utilsService.isLoggedIn;

    }
    angular.module('jrbank').controller('BankHeaderCtrl',BankHeaderController);

})();


