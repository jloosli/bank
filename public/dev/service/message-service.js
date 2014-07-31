(function() {
    /*
     @ngInject
     */
    function messageService($timeout) {
        "use strict";
        var service = {};
        var me = this;

        var messages = [];

        function guid() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
                return v.toString(16);
            });
        }

        service.addMessage = function (theMessage) {
            /*
             Messages format: { id, message, type, duration, persist }
             */
            var id = guid();
            var message = {
                id: id,
                message: theMessage.message || "",
                type: theMessage.type || 'normal',
                persist: !!theMessage.persist || false
            };
            messages.push(message);
            if(theMessage.duration && theMessage.duration > 0) {
                $timeout(function() {
                    me.removeMessage(id);
                }, theMessage.duration);
            }
            return id;
        };

        service.removeMessage = function(id) {
            messages = _.remove(messages, function(msg) {
                return msg.id === id;
            });
        };

        service.messages = function() {
            return messages;
        };

        service.init = function() {
            messages = _.remove(messages, function(msg) {
                return !msg.persist;
            });
        };

        return service;

    }
    angular.module('jrbank').factory('messageService',messageService);
})();

