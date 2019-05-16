const { format } = require('util')
const extract = require('extract-zip')
const { opts, getInstance } = require('../index')
const ora = require('ora')

module.exports = () => {
    let start = Date.now()
    let spinner = ora('checking for updates').start()

    getInstance().getUpdate().then(update => {
        spinner.text = 'downloading update'

        let baseDir = opts.destination
        let destination = format(baseDir + '/update_%s.zip', update.version)

        getInstance().download(update, {
            destination,
            resolve({ filename }) {
                spinner.text = 'extracting files'

                extract(filename, { dir: baseDir }, function (err) {
                    if (err) {
                        throw err
                    } else {
                        let end = (Date.now() - start) / 1000

                        spinner.succeed(format('finished in %ds', end))
                    }
                })
            },
            reject(error) {
                throw error
            }
        })
    }).catch(err => {
        if (err) {
            spinner.fail('update failed')
        } else {
            spinner.info('no updates available')
        }
    })

}
