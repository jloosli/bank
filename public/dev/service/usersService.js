angular.module('jrbank').factory('usersService', function ($http, $q, API_URL, $stateParams) {

    var submitPasswordReminder = function (email) {
        return $q(function (resolve, reject) {
            $http.post(API_URL + 'password/remind', {email: email})
                .then(function (results) {
                    console.log(results);
                    resolve(results);
                });
        });
    };

    var submitPasswordReset = function (credentials) {
        var payload = {
            username:                credentials.username,
            password:                credentials.password,
            'password_confirmation': credentials.password,
            token:                   $stateParams.token
        };
        return $q(function (resolve, reject) {
            $http.post(API_URL + 'password/reset', payload)
                .then(function (results) {
                    console.log(results);
                    if (results.data.meta.success) {
                        resolve(results);
                    } else {
                        reject(results);
                    }
                })
                .catch(function (result) {
                    reject(result);
                });
        });
    };

    return {
        submitPasswordReminder: submitPasswordReminder,
        submitPasswordReset:    submitPasswordReset
    };


    //return $resource('/api/users/:userId', {userId: '@id'}, {
    //    query: {method: 'GET'},
    //    login: {method: 'POST', params: {username: '@user', password: '@pass'}, url: '/api/v1/auth'},
    //    logout: {url: '/api/v1/logout'},
    //    getAll: {method: 'GET', isArray: true}
    //});
});