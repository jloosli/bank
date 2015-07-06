(function () {

    /**
     * @ngInject
     * @constructor
     */
    function BankHeaderController(utilsService, alertsService) {
        var vm = this;

        this.logOut = utilsService.logout;
        this.isLoggedIn = utilsService.isLoggedIn;
        this.navCollapsed = true;

        var updateAlerts = function() {
            vm.alerts = alertsService.get();
        };
        alertsService.registerObserverCallback(updateAlerts);

        this.removeAlert = function(id) {
            alertsService.remove(id);
        };

        updateAlerts();

    }

    angular.module('jrbank').controller('BankHeaderCtrl', BankHeaderController);
})();


