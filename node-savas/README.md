# node-savas

A simple wrapper to communicate with savas

### Installation
```
yarn add node-savas
```

### Usage
```javascript
const savas = require('node-savas')

let app = savas.instance({
    // The host, with protocol and no ending slash
    host: 'http://savas.test',
    // The label of your application
    id: 'development-tool',
    // The release channel (available values based on your configuration)
    channel: 'production',
    // The platform (available values based on your configuration)
    platform: 'windows',
    // The current installed version (can be null to receive the latest version)
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
```