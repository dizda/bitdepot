'use strict';

app.controller('WithdrawCtrl', ['$scope', '$location', 'Withdraw', function($scope, $location, Withdraw) {

    $scope.withdraws = Withdraw.query();

    $scope.sign = function() {

        var tx = new bitcoin.Transaction();
        console.log(tx);

    };

    $scope.modal = {
        "title": "Title",
        "content": "Hello Modal<br />This is a multiline message!"
    };

}]);