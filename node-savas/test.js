const savas = require('./index')

let app = savas.instance({
    host: 'http://savas.test',
    id: 'development-tool',
    channel: 'production',
    platform: 'windows',
    version: '0.0.0'
})

app.getUpdate()
    .then(update => {

        app.download(update, {
            destination: __dirname + '/picture.jpg',

            progress ({ total, current, percentage }) {
                console.log('%d/%d (%d%%)', current, total, percentage)
            },
            resolve ({ filename }) {
                console.log('Downloaded update file: %s', filename)
            },
            reject (error) {
                console.log('There was an error while downloading the update')
            }
        })

    })
    .catch(error => {
        if (error) {
            console.log('There was an error while checking for updates')
        } else {
            console.log('No update available')
        }
    })