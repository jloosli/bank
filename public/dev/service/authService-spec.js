describe('authService', function () {

    beforeEach(module('jrbank'));

    it('should set and get the token', inject(function (authService) {

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

    it('should format the basic auth string correctly', inject(function (authService) {
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
        expect($http.defaults.headers.common.Authorization).toEqual("");
    }));

});