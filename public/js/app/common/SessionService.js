'use strict';

app.factory('Session', [function () {

    this.create = function (tokenId, username) {
        this.token = tokenId;
        this.username = username;
//        this.userRole = userRole;
    };

    this.destroy = function () {
        this.token = null;
        this.username = null;
//        this.userRole = null;
    };

    return this;

}]);