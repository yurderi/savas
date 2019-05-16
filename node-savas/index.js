const UpdateService = require('./components/update_service')

module.exports = {
    instance (settings) {
        return new UpdateService(settings)
    },

    injectCommander: require('./components/injections/commander').injectCommander
}