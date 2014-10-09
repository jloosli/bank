/*
 @ngInject
 */
function newTransactionCtrl($scope, banksService, utilsService) {
    var self = this;
    //this.envelopes = $$scope.envelopes;
    this.sumEnvelopes = 0;

    this.onTransChange = _.debounce(function (amount) {
        amount = amount || 0;

        $scope.$apply(function () {
            $scope.envelopes = _.map($scope.envelopes, function (env) {
                if (amount >= 0) {
                    env.amount = Math.round(env.percent * amount) / 100;
                } else {
                    env.amount = parseInt(env.default_spend, 10) === 1 ? amount : 0;
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
        return Math.round(_.reduce($scope.envelopes, function (sum, env) {
            return parseFloat(sum) + parseFloat(env['amount']);
        }, 0) * 100) / 100;
    };

    this.clearEnvelope = function (id) {
        $scope.envelopes = _.map($scope.envelopes, function (env) {
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
            $scope.envelopes = _.map($scope.envelopes, function (env) {
                if (env.id === id) {
                    env.amount = Math.round((env.amount + diff) * 100) / 100;
                }
                return env;
            });
            self.onValueChange();
        }
    };

    this.submitTransaction = function () {
        var transaction = {
            'transaction': {
                description:           $scope.trans.description,
                amount:                $scope.trans.amount,
                envelope_transactions: _.map($scope.envelopes, function (env) {
                    return {amount: env.amount, envelope_id: env.id};
                })
            }
        };
        banksService.transactions($scope.user).save(transaction).$promise.then(function (result) {
            var newTransaction = result.transaction;
            newTransaction.envelope_transactions = transaction.envelope_transactions;
            newTransaction.created = utilsService.relDate(newTransaction.created_at);

            $scope.transactions.push(newTransaction);

            $scope.envelopes = _.map($scope.envelopes, function(env) {
                env.balance += _.find(transaction.transaction.envelope_transactions, function(et) {
                    return parseInt(et.envelope_id) === parseInt(env.id);
                }).amount;
                return env;
            });
            banksService.flush('transactions', newTransaction.user_id);
            $scope.trans = {};
            $scope.addTransaction.$setPristine();
            console.log($scope.addTransaction);
        });
    };
}

angular.module('jrbank').directive('newTransaction', function () {
    return {
        restrict:     'E',
        replace:      true,
        scope:        {
            envelopes:    '=',
            user:         '@',
            transactions: '='
        },
        templateUrl:  'directive/new-transaction/new-transaction.html',
        link:         function (scope, element, attrs, transactionCtrl) {
            scope.$watch('trans.amount', transactionCtrl.onTransChange);
        },
        controller:   newTransactionCtrl,
        controllerAs: 'transactionCtrl'
    };
});

