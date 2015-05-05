var Database = require('./database.js'),
    yaml = require('js-yaml'),
    fs   = require('fs');

/**
 * Bootstrap the database with Symfony2 imported config
 *
 * @constructor
 */
function Bootstrap()
{
    var config;

    try {
        config = yaml.safeLoad(fs.readFileSync(__dirname + '/../app/config/parameters.yml', 'utf8')).parameters;
    } catch (e) {
        console.log(e);
    }

    this.database = new Database(config.database_host, config.database_port, config.database_name, config.database_user, config.database_password);
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
