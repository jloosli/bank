/*
 @ngInject
 */
function newTransactionCtrl() {
    var self = this;
    this.envelopes = [];

    this.addEnvelope = function (envelope) {
        self.envelopes.push(envelope);
    };

    this.onTransChange = _.debounce(function (amount) {
        console.log(amount);
        amount = amount || 0;
        self.envelopes = _.map(self.envelopes, function (env) {
            env.amount = Math.round(env.percent * amount ) / 100;
            return env;
        });
        console.log(self.envelopes);
    }, 150);
}

angular.module('jrbank').directive('newTransaction', function () {
    return {
        restrict:     'E',
        replace:      true,
        scope:        {
            envelopes: '='
        },
        templateUrl:  'directive/new-transaction/new-transaction.html',
        link:         function (scope, element, attrs, transactionCtrl) {
            scope.$watch('trans.amount', transactionCtrl.onTransChange);
            scope.transaction.amount = scope.transaction.amount || 0;
        },
        controller:   newTransactionCtrl,
        controllerAs: 'transaction'
    };
});

angular.module('jrbank').service('newTransactionSvc', function () {
    'use strict';
    var svc = {},
        envelopes = [];

    svc.calc = function () {

    };


    return svc;
});




