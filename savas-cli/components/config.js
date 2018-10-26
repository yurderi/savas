const fs = require('fs-extra')
const os = require('os')
const path = require('path')
const yaml = require('yaml')

module.exports = class {
    constructor () {
        let me = this

        me.directory = path.join(process.cwd(), '.savas')
        me.filename  = path.join(me.directory, 'config.yml')
        me.data = me.read() || {
            remote: '',
            auth: null
        }
    }

    touch () {
        let me = this

        fs.ensureDirSync(me.directory)
        fs.writeFileSync(me.filename, '')
    }

    isTouched () {
        let me = this

        return fs.existsSync(me.filename)
    }

    isAuthenticated() {
        let me = this

        return typeof me.data.auth === 'object'
            && 'appID' in me.data.auth
            && 'token' in me.data.auth
    }

    write () {
        let me = this

        fs.writeFileSync(me.filename, yaml.stringify(me.data))
    }

    read () {
        let me = this

        if (me.isTouched()) {
            let data = fs.readFileSync(me.filename).toString()

            if (data.length > 0) {
                return yaml.parse(data)
            }
        }

        return null
    }
}