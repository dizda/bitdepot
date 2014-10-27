var Database = require('./database.js');


function Bootstrap()
{
    this.database = new Database();
}

Bootstrap.prototype.start = function()
{
    this.database.client.connect();
};

Bootstrap.prototype.end = function()
{
    this.database.client.end();
};

module.exports = Bootstrap;
