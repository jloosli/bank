(function () {

    /**
     * @ngInject
     * @constructor
     */
    function BankHeaderController(utilsService, alertsService, authService, ACCESS_LEVELS, banksService) {
        var vm = this;

        this.logOut = utilsService.logout;
        this.isLoggedIn = utilsService.isLoggedIn;
        this.navCollapsed = true;
        this.notSuper = false;
        this.banks = [];


        if (authService.checkAccess(ACCESS_LEVELS['super-admin'])) {

            banksService.getBanks()
                .then(function (results) {
                    vm.banks = results;
                    console.log(results);
                });
        }

        var updateAlerts = function () {
            vm.alerts = alertsService.get();
        };
        alertsService.registerObserverCallback(updateAlerts);

        this.removeAlert = function (id) {
            alertsService.remove(id);
        };

        this.setBank = function (id) {
            banksService.setBank(id);
        };

        this.notSuper = !authService.checkAccess(ACCESS_LEVELS['super-admin']);

    }

    angular.module('jrbank').controller('BankHeaderCtrl', BankHeaderController);
})();


