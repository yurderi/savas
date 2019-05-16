process.on('unhandledRejection', error => {
    throw error
})

const { version } = require('./package.json')
const program = require('commander')
const _ = require('lodash')

const { injectCommander } = require('../index')

injectCommander(program, {
    appName: 'node_savas_test2',
    destination: __dirname,
    appConfig: {
        host: 'store.yurderi.de',
        ssl: true,

        id: 'git-merge-tool',
        channel: 'stable',
        platform: 'nodejs',
        version: '0.2.0',

        token: '1c71358c7305f28a1b34ae59fe74bdbf'
    },
    checkVersionOnStartup: true,
    onVersionChecked () {
        const cmd = program.parse(process.argv)

        if (_.isEmpty(cmd.args) && process.argv.length === 2) {
            program.help()
        }
    }
})

program.name('node_savas_test2')
program.version(version)