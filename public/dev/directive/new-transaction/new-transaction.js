/*
 @ngInject
 */
function newTransactionCtrl($scope) {
    var self = this;
    this.envelopes = [];

    this.addEnvelope = function (envelope) {
        self.envelopes.push(envelope);
    };

    this.onTransChange = _.debounce(function (amount) {
        amount = amount || 0;

        $scope.$apply(function() {
            self.envelopes = _.map(self.envelopes, function (env) {
                if(amount >= 0 ) {
                    env.amount = Math.round(env.percent * amount) / 100;
                } else {
                        env.amount = env.default_spend ? amount : 0;
                }
                return env;
            });
        });
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




