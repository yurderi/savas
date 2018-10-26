const program = require('commander')
const _ = require('lodash')
const pkg = require('./package')

program
    .version(pkg.version)

program
    .command('init')
    .description('Init a savas project in the current directory')
    .action(require('./commands/init'))

program
    .command('set-remote <host>')
    .description('Sets the project remote')
    .action(require('./commands/remote/set.js'))

program
    .command('auth')
    .description('Authenticate')
    .action(require('./commands/auth.js'))

program
    .command('list')
    .description('List available releases')
    .action(require('./commands/list.js'))

if(_.isEmpty(program.parse(process.argv).args) && process.argv.length === 2) {
    program.help()
}
