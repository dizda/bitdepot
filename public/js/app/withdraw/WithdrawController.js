'use strict';

app.controller('WithdrawCtrl', ['$scope', '$location', '$modal', 'Withdraw', 'WithdrawOutput', function($scope, $location, $modal, Withdraw, WithdrawOutput) {

    $scope.withdraws = Withdraw.query();
    $scope.withdrawOutputs = WithdrawOutput.query({application_id: 10});

    $scope.openModalSignature = function(withdraw) {

        Withdraw.get({id: withdraw.id}, function(withdraw) {

            $scope.withdraw = withdraw;

            $modal({
                title:    'Withdraw detail',
                template: 'js/app/withdraw/modal_sign.html',
                animation:'am-fade-and-scale',
                placement:'center',
                show:     true,
                scope:    $scope
            });

        });

    };

    /**
     * Update the withdraw in the list when an update is received.
     */
    $scope.$on('withdraw:update', function(e, withdraw) {
        var index = _.findIndex($scope.withdraws, {id: withdraw.id});

        $scope.withdraws[index] = withdraw;
    });


    $scope.openModalAddWithdrawOutput = function()
    {
        $modal({
            title:    'New withdraw output',
            template: 'js/app/withdraw/modal_output_add.html',
            animation:'am-fade-and-scale',
            placement:'center',
            show:     true,
            scope:    $scope
        });
    };

    /**
     * When a withdraw output is created, add it to the top of the list
     */
    $scope.$on('withdraw_output:create', function(e, withdrawOutput) {
        $scope.withdrawOutputs.unshift(withdrawOutput);
    });

    /**
     * @param {Object} withdraw
     */
    $scope.deleteWithdraw = function(withdraw)
    {
        withdraw.$delete(function() {
            // success
            $scope.withdraws.splice($scope.withdraws.indexOf(withdraw), 1);
        });
    };

}]);