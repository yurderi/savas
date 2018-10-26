const path = require('path')
const fs = require('fs-extra')
const yaml = require('yaml')
const Config = require('../components/config')
const inquirer = require('inquirer')
const axios = require('axios')

module.exports = () => {
    let config = new Config()

    if (config.isTouched()) {
        if (config.isAuthenticated()) {
            let url = config.data.remote + '/savas/release/list?applicationID=' + config.data.auth.appID
            let axios_config = {
                headers: {
                    'X-Access-Token': config.data.auth.token
                }
            }

            axios.get(url, axios_config)
                .then(response => response.data)
                .then(response => {
                    console.log(response)
                })
        } else {
            console.log('you are not authenticated yet')
        }
    } else {
        console.log('savas is not yet initialized')
    }
}