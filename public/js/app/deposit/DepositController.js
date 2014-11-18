'use strict';

app.controller('DepositCtrl', ['$scope', '$location', '$modal', 'Deposit', function($scope, $location, $modal, Deposit) {

    $scope.deposits = Deposit.query();

    $scope.openModalAddDeposit = function() {

        $modal({
            title:    'New deposit',
            template: 'js/app/deposit/modal_add.html',
            animation:'am-fade-and-scale',
            placement:'center',
            show:     true
        });

    };

}]);
