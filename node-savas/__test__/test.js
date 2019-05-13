const savas = require('./index')

let app = savas.instance({
    host: 'store.yurderi.de',
    ssl: true,

    id: 'pv_savas',
    channel: 'stable',
    platform: 'provallo-plugin',
    version: '1.0.5'
})

app.getUpdate().then(update => {
    app.download(update, {
        destination: __dirname + '/pv.zip',

        progress({ total, current, percentage }) {
            console.log('%d/%d (%d%%)', current, total, percentage)
        },
        resolve({ filename }) {
            console.log('Downloaded update file: %s', filename)
        },
        reject(error) {
            console.log('There was an error while downloading the update')
        }
    })

}).catch(error => {
    if (error) {
        console.log('There was an error while checking for updates')
    } else {
        console.log('No update available')
    }
})