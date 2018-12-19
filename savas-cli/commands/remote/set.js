const Config = require('../../components/config')

module.exports = (host) => {
    let config = new Config()

    if (config.isTouched()) {
        config.data.remote = host
        config.write()

        console.log('remote set to "%s"', host)
    } else {
        console.log('savas is not yet initialized')
    }
}