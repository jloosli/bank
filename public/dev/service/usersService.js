angular.module('jrbank').factory('usersService', function ($http, $q, API_URL) {

    var submitPasswordReminder = function (email) {
        return $q(function (resolve, reject) {
            $http.post(API_URL + 'password/remind', {email: email})
                .then(function (results) {
                    console.log(results);
                    resolve(results);
                });
        });
    };

    return {
        submitPasswordReminder: submitPasswordReminder
    };


    //return $resource('/api/users/:userId', {userId: '@id'}, {
    //    query: {method: 'GET'},
    //    login: {method: 'POST', params: {username: '@user', password: '@pass'}, url: '/api/v1/auth'},
    //    logout: {url: '/api/v1/logout'},
    //    getAll: {method: 'GET', isArray: true}
    //});
});