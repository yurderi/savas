const fs = require('fs-extra')
const os = require('os')
const path = require('path')

module.exports = {
    data: {

    },

    register () {
        let me = this
        let directory = path.join(os.homedir(), '.savas')

        return fs.ensureDir(directory)
    },
    write () {
        let me = this


    },
    read () {
        let me = this


    }
}