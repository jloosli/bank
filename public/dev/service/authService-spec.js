describe('authService', function () {

    beforeEach(module('jrbank'));

    it('should set and get the token', inject(function (authService) {

        authService.setToken('bob');
        var token = authService.getToken();
        expect(token).toEqual('bob');

    }));

    it('should reset the token', inject(function (authService) {
        "use strict";
        authService.setToken('bob');
        authService.clearToken();
        var token = authService.getToken();
        expect(token).toBeNull();
    }));

});