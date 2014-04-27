'use strict';

/* Directives */

angular.module('jrBank.directives', []).
    directive('appVersion', ['version', function (version) {
        return function (scope, elm, attrs) {
            elm.text(version);
        };
    }])
    .directive('jrb-submit', [function () {
        return {
            restrict: 'A',
            controller: ['$scope', function ($scope) {
                var formController = null;
                this.attempted = false;
                this.setAttempted = function () {
                    this.attempted = true;
                }
                this.setFormController = function (controller) {
                    formController = controller;
                };

                this.needsAttention = function (fieldModelController) {
                    console.log(fieldModelController);
                    if (!formController) return false;

                    if (fieldModelController) {
                        return fieldModelController.$invalid && (fieldModelController.$dirty || this.attempted);
                    } else {
                        return formController && formController.$invalid && (formController.$dirty || this.attempted);
                    }
                }
            }],
            compile: function (cElement, cAttributes, transclude) {
                return {
                    pre: function (scope, formElement, attributes, controllers) {

                        var submitController = controllers[0];

                        var formController = (controllers.length > 1) ?
                            controllers[1] : null;
                        submitController.setFormController(formController);

                        scope.jrb = scope.jrb || {};
                        scope.jrb[attributes.name] = submitController;
                    }
                }
            }
        }
    }])
    .directive('autofillHack', function ($timeout) {
        /* Have to use this hack to get around the autofill bug.
         see: http://stackoverflow.com/questions/14965968/angularjs-browser-autofill-workaround-by-using-a-directive#19854565
         Changed it to timeout so I didn't accidentally overwrite something.
         Supposedly this will be fixed in 1.2.3
         */
        return {
            require: "ngModel",
            scope: {},
            link: function (scope, element, attrs, ngModel) {
                var original = element.val();
                $timeout(function () {
                    if (original != element.val()) {
                        ngModel.$setViewValue(element.val());
                    }
                }, 2000);
            }
        }
    })
    .directive('loginlogout', [function () {
        function link(scope, element, attrs) {
            var user = scope.user;
            var loggedIn;
            scope.loginLogout = function () {
                if (loggedIn) {
                    scope.login();
                } else {
                    scope.logout();
                }
            }
            scope.$watch(scope.user, function (val) {
                console.log("User changed");
                console.log(val);
                console.log(scope.user);
            });
            scope.$on('UserLoggedIn', function () {
                console.log("received userloggedin");
                console.log(scope.user);
                console.log(scope.currentUser);
            })
            console.log(user);
//            user.then(function (data) {
//                console.log(data);
//                console.log(data.data.result);
//                if (data.data.result.success == 1) {
//                    scope.display = "Log In"
//                    loggedIn = false;
//                } else {
//                    scope.display = "Welcome " + user.username + " <small>(logout)</small>";
//                    loggedIn = true;
//                }
//
//            });
        }

        return {
            restrict: "A",
            scope: {user: '@', login: '=', logout: '='},
            link: link,
            template: "<a href='#' ng-click='loginLogout()' ng-bind-html='display'></a>",
            replace: true
        }

    }

    ])

    .directive('envelope', [function () {
        function link(scope, element, attrs) {
//            console.log(scope, element, attrs);

            var findPercentages = function (transAmount) {
                if (+transAmount === 0) scope.envelope.val = '';
                if (+transAmount > 0 && scope.envelope.percent) {
                    var newVal = Math.round(transAmount * scope.envelope.percent) / 100;
                    scope.envelope.val = Math.round(newVal * 100) / 100;
                }
                if (+transAmount < 0 && scope.envelope.default_spend == "1") {
                    scope.envelope.val = +transAmount;
                }
            }

            scope.$watch('trans', function (newTrans) {
                findPercentages(newTrans || 0);
            })

            // @todo: There is likely a better way to do this...for now, I'm just going to loop through the parent
            // @todo: Likely, it's
            scope.forceEnvCalc = function () {
                var total = scope.trans;
                var envelopes = scope.$parent.$parent.user.envelopes;
                angular.forEach(envelopes, function (envelope) {
                    if (envelope.val && envelope.id != scope.envelope.id) {
                        total -= envelope.val;
                    }
                });
                scope.envelope.val = Math.round(total * 100) / 100;
            }

        }

        return {
            restrict: "E",
            link: link,
            scope: {
                envelope: '=env',
                trans: '@'
            },
            templateUrl: '/partials/directive-envelope.html',
            replace: true
        }
    }])
    .directive('transactionList', [function () {
        function link(scope, element, attrs) {
//            console.log(scope);
//            console.log(element);
//            console.log(attrs);
            scope.getJsDate = function (theDate) {
                if (typeof theDate === 'string') {
                    var t = theDate.split(/[- :TZ]/);
                    //when t[3], t[4] and t[5] are missing they defaults to zero
                    return new Date(t[0], t[1] - 1, t[2], t[3] || 0, t[4] || 0, t[5] || 0);
                }
            }

            scope.showDetails = false;
        }

        return {
            restrict: "E",
            link: link,
            scope: {
                transaction: '=trans',
                envelopes: '='
            },
            templateUrl: '/partials/directive-transaction-list.html',
            replace: true
        }
    }])
    .directive('accountHolders', ['$location', 'userFactory', function ($location, userFactory) {
        function link(scope, element, attrs) {
            scope.updateUsername = function () {
                if (scope.account.name) {
                    scope.account.username = scope.account.name.toLowerCase().replace(/[^a-z0-9\.\- ]/g, "").replace(/\s+/g, "-");
                }
            }

            scope.editAccount = function () {
                scope.clearAlerts();
                var user = new userFactory(scope.account);
                user.$save().then(function (results) {
                    if (results.success) {
                        scope.account = results.data;
                    } else {
                        var message = results.message.join(" ");
                        scope.addAlert({
                            msg: message,
                            type: "danger"
                        });
                    }
                });
            }
            scope.go = function (id) {
                var thePath = '/accounts/' + id;
                $location.path(thePath);
            }


        }

        return {
            restrict: "E",
            link: link,
            scope: {
                account: '='
            },
            templateUrl: '/partials/directive-accountHolders.html',
            replace: true,
            controller: "mainCtrl"
        }

    }])
    .directive('envelopeList', function() {
        function link(scope,element,attrs) {

        }
        return {
            restrict: "E",
            link: link,
            scope: {
                user: "=",
                alertFn:'&'
            },
            templateUrl: '/partials/directive-envelopeList.html',
            controller: function($scope) {

            }
        }
    })
    .directive('envelopeEdit', [ 'envelopeFactory', '$filter', function (envelopeFactory, $filter) {
        function link(scope, element, attrs, parentCtrl) {
            console.log(scope, element, attrs, parentCtrl);
            if (!scope.envelope.edit) {
                scope.envelope.edit = false;
            }

            scope.checkDefault = function (envelope) {
                console.log(parentCtrl);

                // Check if any of the other envelopes are active, then update them.
                if (envelope.is_active) {
                    if (envelope.default_spend != 1) {
                        angular.forEach(scope.user.envelopes, function (env) {
                            if (env != envelope && env.default_spend == 1) {
                                env.default_spend = 0;
                                scope.saveEnvelope(env);
                            } else if (env == envelope) {
                                env.default_spend = 1;
                                scope.saveEnvelope(env);
                            }
                        });
//                    envelope.default_spend = 1;
//                    scope.saveEnvelope(envelope);
                    }
                }
            };
            scope.checkActive = function (envelope) {
                // Don't deactivate unless the balance is 0
                if (envelope.balance != 0) {
                    var message = "This envelope currently has a balance of " +
                        $filter('currency')(envelope.balance) +
                        ". The envelope balance needs to be at zero before closing it. ";
                    scope.addAlert({
                        msg: message,
                        type: 'warning',
                        delay: 5000
                    });
                    console.log(message);
                } else if (envelope.default_spend == '1') {
                    // Don't deactivate an envelope that is set as the default spending envelope
                    scope.addAlert({
                        msg: "This envelope (" +
                            envelope.name +
                            ") is the current envelope for spending. Change to a different envelope for spending before deactivating the envelope. ",
                        type: 'warning',
                        delay: 5000
                    });
                } else {
                    envelope.is_active = !envelope.is_active;
                    scope.saveEnvelope(envelope);
                }
                console.log(scope);

            }
            scope.saveEnvelope = function (data) {
                console.log(data);
                var envelope = new envelopeFactory(data);
                envelope.$save(function (response, other) {
                    if (response.success) {
                        data.edit = false;
                        parentCtrl.alertFn({
                            msg: data.name + ' saved.',
                            delay: 5000
                        });
                        scope.envelope = response.envelope;
                    }
                });
            }


        }

        return {
            restrict: "E",
            link: link,
            scope: {
                envelope: '=',
                user: '='
            },
            templateUrl: '/partials/directive-envelope-edit.html',
            replace: true,
            require: '^envelopeList'
        }

    }

    ])
    .
    directive('copytrans', ['$filter', function ($filter) {
        function link(scope, element, attrs) {
//            console.log(scope,element,attrs);

            function getLinks() {
                var otherUsers = [];
                angular.forEach(scope.accountHolders, function (user) {
                    if (user.id != scope.current.id) {
                        user['link'] = '#/accounts/' + user.id + '?trans=' + encodeURIComponent(scope.lastTrans); // Don't need to JSON.stringify since it's already a string
                        this.push(user);
                    }
                }, otherUsers);
                return otherUsers;
            }

            attrs.$observe('lastTrans', function () {
                var others = getLinks();
                scope.others = others;
            });

        }

        return {
            restrict: "E",
            link: link,
            scope: {
                users: "=",
                current: "=",
                lastTrans: "@",
                message: "@"
            },
            templateUrl: '/partials/directive-copytrans.html',
            replace: true,
            controller: 'mainCtrl'
        }
    }])
    .directive('menuActive', function ($location) {
        function link(scope, element, attrs) {
//            console.log(scope,element,attrs);
            var checkActive = function () {
                var anchor = element.find('a')[0];
                element.removeClass('active');
                if (anchor.href == $location.absUrl()) {
                    element.addClass('active');
                }
            }
            checkActive();
            scope.$on('$locationChangeSuccess', function () {
                checkActive();
            })
        }

        return {
            restrict: "A",
            link: link,
            scope: true
        }
    })
;
