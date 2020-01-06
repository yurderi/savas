const Config = require('../components/config')

module.exports = () => {
    let config = new Config()

    if (config.isTouched()) {
        console.log('savas were already initialized')
    } else {
        config.touch()

        console.log('savas were successfully initialized')
    }
}