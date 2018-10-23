const program = require('commander')
const _ = require('lodash')
const pkg = require('./package')
const config = require('./components/config')

config.register()

program
    .version(pkg.version)

program
    .command('init')
    .description('Init a savas project in the current directory')
    .action(require('./commands/init'))

if(_.isEmpty(program.parse(process.argv).args) && process.argv.length === 2) {
    program.help()
}
