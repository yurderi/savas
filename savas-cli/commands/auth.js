const path = require('path')
const fs = require('fs-extra')
const yaml = require('yaml')
const Config = require('../components/config')
const inquirer = require('inquirer')
const axios = require('axios')

module.exports = () => {
    let config = new Config()

    if (config.isTouched()) {
        inquirer
            .prompt([
                {
                    type: 'input',
                    name: 'access_token',
                    message: 'Enter access token'
                }
            ])
            .then(({ access_token }) => {
                console.log('checking credentials...')

                let url = config.data.remote + '/savas/api/auth'

                axios.get(url, { params: { access_token } })
                    .then(response => response.data)
                    .then(response => {
                        if (response.success) {
                            config.data.auth = {
                                appID: response.id,
                                token: access_token
                            }
                            config.write()

                            console.log('successfully authenticated for application "%s"', response.label)
                        } else {
                            console.log('invalid access token')
                        }
                    })
            })
    } else {
        console.log('savas is not yet initialized')
    }
}