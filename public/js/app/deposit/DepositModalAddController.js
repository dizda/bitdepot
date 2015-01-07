'use strict';

app.controller('DepositModalAddCtrl', ['$scope', '$modal','Deposit', function($scope, $modal, Deposit) {

    $scope.typeOptions = [
        { value: 1, text: 'Expected Amount' },
        { value: 2, text: 'Topup (unknown amount)' }
    ];

    $scope.deposit = {
        application_id: 1,
        type: 1
    };

    $scope.create = function()
    {
        Deposit.save($scope.deposit, function(response) {
            $scope.$emit('deposit:create', response);
            $scope.$hide();
        });
    };

}]);