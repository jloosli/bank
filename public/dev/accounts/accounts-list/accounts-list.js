(function () {
    "use strict";

    /*
    @ngInject
     */
    function AccountsListCtrl ($stateParams) {
        console.log("in accounts list");
        this.accountsList = ['bob','john'];
    }
    angular.module('jrbank').controller('AccountsListCtrl', AccountsListCtrl);
})();
