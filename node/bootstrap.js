var mysql      = require('mysql');


function Bootstrap()
{
    this.mysql = mysql.createConnection({
        host     : 'localhost',
        user     : 'root',
        password : '',
        database : 'bitwallet'
    });
}

Bootstrap.prototype.start = function()
{
    this.mysql.connect();
};

Bootstrap.prototype.end = function()
{
    this.mysql.end();
};

module.exports = Bootstrap;
