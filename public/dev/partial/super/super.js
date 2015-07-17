/* @ngInject */
var SuperCtrl = function (banksService) {
    'use strict';
    var self = this;
    this.banks = [];
    this.bank = {};

    banksService.getBanks()
        .then(function (data) {
            self.banks = data;
        });

    this.addBank = function () {
        console.log(self.bank);

        var info = self.bank,
            newBank = {
                bank:  {
                    name:     info.bankname,
                    interest: parseInt(info.interest)
                },
                users: [{
                    username:    info.username,
                    password:    info.password,
                    name:        info.name,
                    email:       info.email,
                    'user_type': 'admin'
                }]
            };
        console.log(newBank);

        banksService.createBank(newBank)
            .then(function (data) {
                self.banks.push(data.bank);
                self.clearAddBank();
            })
            .catch(function () {
                console.log('Couldn\'t add bank');
            });
    };

    this.clearAddBank = function () {
        self.bank = {};
    };
};
angular.module('jrbank').controller('SuperCtrl', SuperCtrl);


