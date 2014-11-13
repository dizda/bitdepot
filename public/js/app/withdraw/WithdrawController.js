'use strict';

app.controller('WithdrawCtrl', ['$scope', '$location', 'Withdraw', function($scope, $location, Withdraw) {

    $scope.withdraws = Withdraw.query();

    $scope.openModalSignature = function(withdraw) {

//        var tx = new bitcoin.Transaction();
//        console.log(tx);
        Withdraw.get({id: withdraw.id}, function() {

        });

    };

    $scope.modal = {
        "title": "Title",
        "content": "Hello Modal<br />This is a multiline message!"
    };

}]);