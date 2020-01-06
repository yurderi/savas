const path = require('path')
const fs = require('fs-extra')
const yaml = require('yaml')
const Config = require('../components/config')
const inquirer = require('inquirer')
const axios = require('axios')
const iterate = require('../util/iterate')

module.exports = () => {
    let config = new Config()

    iterate([
        // Check if savas is initialized
        (resolve, reject, data) => {
            if (config.isTouched()) {
                resolve()
            } else {
                reject('savas is not yet initialized')
            }
        },
        // Ask for api token
        (resolve, reject, data) => {
            inquirer
                .prompt([
                    {
                        type: 'input',
                        name: 'api_token',
                        message: 'Enter api token'
                    }
                ])
                .then(({ api_token}) => {
                    data.api_token = api_token
                    resolve()
                })
                .catch(reject)
        },
        // Validate entered api token
        (resolve, reject, data) => {
            console.log('checking token...')

            let url = config.data.remote + 'api/v1/auth'

            axios.get(url, { params: { api_token: data.api_token } })
                .then(response => response.data)
                .then(response => {
                    if (response.success) {
                        data.apps = response.apps
                        resolve()
                    } else {
                        reject('invalid access token')
                    }
                })
        },
        // Ask for application to use
        (resolve, reject, data) => {
            inquirer.prompt([
                {
                    type: 'list',
                    name: 'appID',
                    message: 'Select your app to use',
                    choices: data.apps.map(app => ({ name: app.label, value: app.id }))
                }
            ])
                .then(({ appID }) => {
                    data.appID = appID
                    resolve()
                })
        },
        // Save data
        (resolve, reject, { api_token, appID }) => {
            config.data.auth = {
                token: api_token,
                appID
            }

            config.write()

            console.log('authentication successful')
        }
    ])
        .catch(error => {
            console.log(error)
        })
}