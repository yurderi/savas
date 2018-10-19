const UpdateService = require('./components/update_service')

module.exports = {
    instance (settings) {
        return new UpdateService(settings)
    }
}