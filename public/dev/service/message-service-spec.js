describe('messageService', function () {

    beforeEach(module('jrbank'));

    it('should have no messages to start', inject(function (messageService) {
        expect(messageService.messages()).toEqual([]);
    }));

    it('should add a message and return an id', inject(function (messageService) {
        var message = {
            message: "You are about to run off the road!",
            type: 'alert',
            persist: true
        };
        expect(messageService.messages().length).toBe(0);
        var id = messageService.addMessage(message);
        expect(id).toMatch(/........-....-4...-....-............/);
        expect(messageService.messages().length).toBe(1);
    }));

    it('should delete a non-persisting message when calling .init', inject(function(messageService) {
        var message = {
            message: "Watch out for the chicken!",
            type: 'alert',
            persist: true
        },
            messageNoPersist = {
                message: "Watch out for the chicken!",
                type: 'alert',
                persist: false
            };
        expect(messageService.messages().length).toBe(0);
        messageService.addMessage(message);
        messageService.addMessage(messageNoPersist);
        expect(messageService.messages().length).toBe(2);
        messageService.init();
        expect(messageService.messages().length).toBe(1);
    }));


});