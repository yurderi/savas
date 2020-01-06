const program = require('commander')
const _ = require('lodash')
const pkg = require('./package')
const updateNotifier = require('update-notifier');

updateNotifier({pkg}).notify();

program
    .version(pkg.version)

program.command('init')
    .description('Init a savas project in the current directory')
    .action(require('./commands/init'))
    
program.command('set-remote <host>')
    .description('Defines the remote')
    .action(require('./commands/remote/set'))

program.command('auth')
    .description('Authenticate on the current remote')
    .action(require('./commands/auth'))

program.command('list')
    .description('List available releases')
    .action(require('./commands/list'))

program.command('create-release <version>')
    .description('Creates a new release')
    .option('--channel [channel]', 'Define the release channel')
    .option('--description [description]', 'Define the release notes')
    .option('--enable', 'Enables the release after creating')
    .action(require('./commands/create-release'))

program.command('upload <filename> <version>')
    .description('Uploads a file to an existing release')
    .option('--channel [channel]', 'A part to define the release')
    .option('--platform [platform]', 'The target platform')
    .action(require('./commands/upload'))

if(_.isEmpty(program.parse(process.argv).args) && process.argv.length === 2) {
    program.help()
}
