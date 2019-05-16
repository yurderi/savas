const { getSavas } = require('../components/updater')
const path = require('path')
const { format } = require('util')
const extract = require('extract-zip')

module.exports = () => {
    let start = Date.now()

    getSavas().getUpdate().then(update => {
        console.log('Installing update... (Using version %s)', update.version)

        let baseDir = path.join(__dirname, '..')
        let destination = format(baseDir + '/update_%s.zip', update.version)

        getSavas().download(update, {
            destination,
            progress: () => {},
            resolve({ filename }) {
                extract(filename, { dir: baseDir }, function (err) {
                    if (err) {
                        throw err
                    } else {
                        let end = (Date.now() - start) / 1000

                        console.log('Finished in %ds', end)
                    }
                })
            },
            reject(error) {
                throw error
            }
        })
    }).catch(err => {
        if (err) {
            console.log('Update failed.')
        } else {
            console.log('No updates available.')
        }
    })

}
