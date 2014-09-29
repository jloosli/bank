/*
 @ngInject
 */
function newTransactionCtrl($scope) {
    var self = this;
    this.envelopes = [];
    self.sumEnvelopes = 0;

    this.addEnvelope = function (envelope) {
        self.envelopes.push(envelope);
    };

    this.onTransChange = _.debounce(function (amount) {
        amount = amount || 0;

        $scope.$apply(function () {
            self.envelopes = _.map(self.envelopes, function (env) {
                if (amount >= 0) {
                    env.amount = Math.round(env.percent * amount) / 100;
                } else {
                    env.amount = env.default_spend ? amount : 0;
                }
                return env;
            });
            self.onValueChange();
        });
    }, 250);

    this.onValueChange = _.debounce(function () {
        $scope.$apply(function () {
            $scope.balanced = checkDiff() === 0;
        });
    });


    $scope.balanced = true;
    $scope.trans = {amount: 0};

    var checkDiff = function () {
        var amount = $scope.trans.amount || 0;
        return Math.round((amount - getEnvelopeSum()) * 100) / 100;
    };

    var getEnvelopeSum = function () {
        return Math.round(_.reduce(self.envelopes, function (sum, env) {
            return parseFloat(sum) + parseFloat(env['amount']);
        }, 0) * 100) / 100;
    };

    this.clearEnvelope = function (id) {
        self.envelopes = _.map(self.envelopes, function (env) {
            if (env.id === id) {
                env.amount = 0;
            }
            return env;
        });
        self.onValueChange();
    };

    this.calcEnvelope = function (id) {
        var diff = checkDiff();
        if (diff !== 0) {
            self.envelopes = _.map(self.envelopes, function (env) {
                if (env.id === id) {
                    env.amount = Math.round((env.amount + diff) * 100) / 100;
                }
                return env;
            });
            self.onValueChange();
        }
    };
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

        },
        controller:   newTransactionCtrl,
        controllerAs: 'transactionCtrl'
    };
});

