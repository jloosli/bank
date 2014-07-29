angular.module('jrbank').factory('usersService',function($resource) {

    return $resource('/api/users/:userId', {userId: '@id'}, {
        query: {method: 'GET'},
        login: {method: 'POST', params: {username: '@user', password: '@pass'}, url: '/api/v1/auth'},
        logout: {url: '/api/v1/logout'},
        getAll: {method: 'GET', isArray: true}
    });
});