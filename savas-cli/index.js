const _ = require('lodash')
const ctable = require('console.table')
const yargs = require('yargs')

let argv = yargs
    .command('init', 'Init savas in currenty directory', {}, require('./commands/init'))

    .command('set-remote [host]', 'Defines the remote', {}, require('./commands/remote/set'))

    .command('auth', 'Authenticate on current remote', {}, require('./commands/auth'))

    .command('list', 'Lists available releases', {}, require('./commands/list'))

    .command('create-release', 'Lists available releases', {
        version: {
            string: true
        }
    }, require('./commands/create-release'))

    .argv