describe('authService', function () {

    beforeEach(module('jrbank'));

    it('should set and get the token', inject(function ($http, authService) {
        authService.setToken('bob');
        var token = authService.getToken();
        expect(token).toEqual('bob');

    }));

    it('should reset the token', inject(function ($http, authService) {
        "use strict";
        authService.setToken('bob');
        authService.clearToken();
        var token = authService.getToken();
        expect(token).toBeNull();
    }));

    it('should format the basic auth string correctly', inject(function ($http, authService) {
        "use strict";
        authService.setToken('bob');
        var authString = authService.getAuthString().split(' ');
        expect(authString[0] + ' ' + atob(authString[1])).toEqual("Basic bob:");
    }));

    it('should set the authentication string correctly', inject(function ($http, authService) {
        "use strict";
        authService.setToken('bob');
        authService.init();
        expect($http.defaults.headers.common.Authorization).toEqual("Basic Ym9iOg==");
    }));

    it('shouldn\'t have an authentication string if no token is set.', inject(function ($http, authService) {
        "use strict";
        authService.clearToken();
        authService.init();
        expect($http.defaults.headers.common.Authorization).toBeUndefined();
    }));

    it('should return stored user', inject(function (authService) {
        var theUser = {id: 1, name: 'bob'};
        localStorage.setItem('current_user', JSON.stringify(theUser));
        var currentUser = authService.getCurrentUser();
        currentUser.then(function(user) {
            expect(theUser).toEqual(user);
        });
    }));

    it('should try to look up user', inject(function ($httpBackend, authService, API_URL) {
        localStorage.removeItem('current_user');
        var apiCall = {
            "user": {
                "id":         1,
                "username":   "first_user",
                "name":       "First User",
                "email":      "first@example.com",
                "slug":       "first_user",
                "bank_id":    1,
                "user_type":  "user",
                "balance":    0,
                "created_at": "2014-07-30 21:23:32",
                "updated_at": "2014-07-30 21:23:32"
            }
        };
        $httpBackend.expectGET(API_URL + 'users/me').respond(200, JSON.stringify(apiCall));
        var currentUser = authService.getCurrentUser();
        currentUser.then(function(user) {
            expect(user).toEqual(apiCall.user);
        });
        $httpBackend.flush();

    }));

    it('should handle an unauthorized user request', inject(function ($httpBackend, authService, API_URL) {
        authService.setToken('The Token');
        localStorage.removeItem('current_user');
        var forbiddenResponse = {"message":"403 Forbidden"};
        $httpBackend.expectGET(API_URL + 'users/me').respond(403, JSON.stringify(forbiddenResponse));
        var currentUser = authService.getCurrentUser();
        currentUser.then(function(user) {
            expect(user).toBeNull();
            expect(localStorage.getItem('current_user')).toBeNull();
            expect(localStorage.getItem('auth_token')).toBeNull();
        });
        $httpBackend.flush();
    }));

    it('shouldn\'t clear the token if a non 403 error received', inject(function ($httpBackend, authService, API_URL) {
        authService.setToken('The Token');
        localStorage.removeItem('current_user');
        var forbiddenResponse = {"message":"404 Forbidden"};
        $httpBackend.expectGET(API_URL + 'users/me').respond(404, JSON.stringify(forbiddenResponse));
        var currentUser = authService.getCurrentUser();
        currentUser.then(function(user) {
            expect(user).toBeNull();
            expect(localStorage.getItem('current_user')).toBeNull();
            expect(localStorage.getItem('auth_token')).toEqual("The Token");
        });
        $httpBackend.flush();
    }));

});