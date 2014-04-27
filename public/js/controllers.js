'use strict';

/* Controllers */

angular.module('jrBank.controllers', []).
    controller('listCtrl', ['$scope', '$location', 'userFactory', function ($scope, $location, userFactory) {
        $scope.addAccount = function () {
            var newAccount = {
                active: "1",
                edit: true
            }
            $scope.accountHolders.push(newAccount);
        }


    }])
    .controller('userCtrl', ['$scope', 'userFactory', 'transactionFactory', '$routeParams', 'envelopeFactory', '$filter', '$location',
        function ($scope, userFactory, transactionFactory, $routeParams, envelopeFactory, $filter, $location) {
            $scope.lastTransaction = $scope.lastTransaction || {};
            $scope.accountHolders.$promise.then(function () {
                $scope.user = $scope.getUser($routeParams.userId);
            });

            $scope.transactions = transactionFactory.user({userId: $routeParams.userId});

            // Prefill values if they're on the screen
            var search = $location.search();
            if (search.trans) {
                try {
                    var theTrans = JSON.parse(search.trans);
                    $scope.trans = theTrans;
                } catch (e) {
                    console.error("Parsing error:", e);
                    console.error("The Transaction: ", search.trans);
                }
            }

            $scope.submitTransaction = function () {
                var envDiff = checkEnvelopeDifference($scope.trans.amount, $scope.user.envelopes);

                if (envDiff == 0) {
                    var theEnvelopes = [], envToSave = {};
                    angular.forEach($scope.user.envelopes, function (envelope) {
                        if (envelope.val) {
                            envToSave = {
                                amount: envelope.val,
                                envelope_id: envelope.id
                            };
                            theEnvelopes.push(envToSave);

                        }
                    });

                    var theTransaction = {
                        user_id: $scope.user.id,
                        description: $scope.trans.description,
                        amount: $scope.trans.amount,
                        envelope_transaction: theEnvelopes
                    };
                    var transaction = new transactionFactory(theTransaction);
                    transaction.$save(function (data) {
                        if (data.success) {
                            $scope.lastTransaction = $scope.trans;
                            var alert = $scope.addAlert({
                                msg: 'Transaction Saved.',
                                type: 'success',
                                copy: true,
                                delay: 10000
                            });
                            $scope.$on('$locationChangeSuccess', function () {
                                $scope.removeAlert(alert);
                            });

                            $scope.trans = {}; // Clear form

                            theTransaction.created_at = new Date().toISOString();
                            $scope.transactions.push(theTransaction);
                            $scope.user.balance = +theTransaction.amount + +$scope.user.balance;
                            angular.forEach($scope.user.envelopes, function (envelope) {
                                envelope.balance += envelope.val;
                                delete envelope['val'];
                            });
                        }
                    });
                } else {
                    var filtered = $filter('currency')(Math.abs(envDiff));
                    var warning = "Your envelopes are too ";
                    warning += envDiff > 0 ? "low" : "high";
                    warning += " by " + filtered;
                    warning += ". Try ";
                    warning += envDiff > 0 ? "adding to " : "subtracting from ";
                    warning += "your envelopes ";
                    warning += filtered + " or click on one of the Calc buttons to even everything out for you.";
                    $scope.addAlert({
                        msg: warning,
                        type: 'danger'
                    });
                }
            }


            function checkEnvelopeDifference(total, envelopes) {
                // Check to make sure
                angular.forEach(envelopes, function (envelope) {
                    if (envelope.val) {
                        total -= envelope.val;
                    }
                });
                return Math.round(total * 100) / 100;
            }

        }])
    .controller('envelopesCtrl', ['$scope', '$routeParams', 'envelopeFactory', '$filter', function ($scope, $routeParams, envelopeFactory, $filter) {
        $scope.accountHolders.$promise.then(function () {
            $scope.user = $scope.getUser($routeParams.userId);
        });

        $scope.addEnvelope = function () {
            $scope.user.envelopes.push({
                edit: true,
                is_active: 1,
                balance: 0,
                user_id: +$routeParams.userId
            });
        }

    }])
    .controller('loginCtrl', ['$scope', '$location', 'userFactory', 'Auth', function ($scope, $location, userFactory, Auth) {
        $scope.problem = {user: false, pass: false};
        $scope.error = '';
        $scope.doLogin = function (form) {
            userFactory.login({username: form.nameOrEmail, password: form.password}).$promise.then(function (result) {
                if (result.token) {
                    $location.path('/accounts');
                } else {
                    console.error("Couldn't log in",result);
                }
            });
        }

    }])
    .controller('menuCtrl', ['$scope', '$location', 'userFactory', 'Auth', function ($scope, $location, userFactory, Auth) {
        $scope.currentUser = Auth.getUser();
        $scope.$on('auth:loggedOut', function () {
            $scope.currentUser = Auth.getUser();
        });
        $scope.$on('auth:loggedIn', function() {
            $scope.currentUser = Auth.getUser();
        });
        $scope.login = function () {
            alert('logging in');
        }
        $scope.logout = function () {
            Auth.logout();
        }
    }])
    .controller('mainCtrl', ['$scope', 'userFactory', '$filter', '$timeout', 'Auth', function ($scope, userFactory, $filter, $timeout, Auth) {
        var updateUsers = function () {
            $scope.accountHolders = userFactory.getAll();
        }
        if (Auth.isLoggedIn()) {
            updateUsers();
        }

        $scope.$on('auth:loggedIn', function() {
            updateUsers();
        });
        $scope.getUser = function (id) {
            return $filter('filter')($scope.accountHolders, {id: id})[0];
        }

        $scope.currentUser = function () {
            return $filter('filter')($scope.accountHolders, {id: Auth.getId()})[0];
        }

        $scope.otherUsers = function (id) {
            return $filter('notId')($scope.accountHolders, {id: id});
        }

        $scope.closeAlert = function (index) {
            $scope.alerts.splice(index, 1);
        };
        $scope.clearAlerts = function () {
            $scope.alerts = [];
        }
        $scope.alerts = [];

        $scope.addAlert = function (settings) {
            // Add an alert with an automatic close delay. Format:
            // {msg: theMessage, type: bootstrapcssType (warning|info|etc.), delay: (optional) delay to close in ms}
            settings.guid = $scope.guid();
            if (settings.delay && settings.delay > 0) {
                $timeout(function () {
                    $scope.removeAlert(settings.guid);
                }, settings.delay);
            }
            $scope.alerts.push(settings);
            return settings.guid;
        }

        $scope.removeAlert = function (guid) {
            var alerts = [];
            angular.forEach($scope.alerts, function (alert, index) {
                if (alert.guid && alert.guid == guid) {
                    $scope.alerts.splice(index, 1);
                } else {
                    alerts.push(alert);
                }
            }, alerts);
            $scope.alerts = alerts;
        }

        $scope.guid = function () {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }

    }])
;