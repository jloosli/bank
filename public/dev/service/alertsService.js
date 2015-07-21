/* @ngInject */
var alertsService = function ($timeout, $sce) {
    'use strict';

    var alerts = [],
        observerCallbacks = [];

    var registerObserverCallback = function (callback) {
        observerCallbacks.push(callback);
    };

    var notifyObservers = function () {
        angular.forEach(observerCallbacks, function (callback) {
            callback();
        });
    };

    var get = function () {
        return alerts;
    };

    var add = function (alert) {
        if (typeof alert !== 'object') {
            alert = {
                text: alert
            };
        }
        alert.id = _.uniqueId('alert_');
        alert.type = 'alert-' + (alert.type || 'success');
        if (!!alert.raw) {
            alert.text = $sce.trustAsHtml(alert.text);
        }
        alerts.push(alert);
        if (alert.duration && parseInt(alert.duration) > 0) {
            var duration = parseInt(alert.duration) * 1000;
            $timeout(function () {
                remove(alert.id);
            }, duration);
        }
        notifyObservers();
        return alert.id;
    };

    var remove = function (id) {
        console.log("Removing", id);
        _.remove(alerts, function (alert) {
            return alert.id === id;
        });
        notifyObservers();
    };

    var removeAll = function () {
        alerts = [];
        notifyObservers();
    };

    var removeNonEnduring = function () {
        _.remove(alerts, function (alert) {
            return !alert.endure;
        });
    };

    return {
        get:               get,
        add:               add,
        remove:            remove,
        removeAll:         removeAll,
        registerObserverCallback: registerObserverCallback,
        removeNonEnduring: removeNonEnduring
    };
};
angular.module('jrbank').factory('alertsService', alertsService);
