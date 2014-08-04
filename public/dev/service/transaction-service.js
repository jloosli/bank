(function () {

    function transactionService($resource, API_URL) {
        'use strict';
        var svc = {},
            defaultPageSize = 100;

        svc.resource = function (bank_id, user_id, from_id, pageSize) {
            return $resource(
                API_URL + 'banks/' + bank_id + '/users/' + user_id + '/transactions/:trans_id',
                {
                    from_id: from_id || 0,
                    page_size: pageSize || defaultPageSize
                }

            );
        };

        return svc;
    }

    angular.module('jrbank').factory('transactionService', transactionService);
})();

