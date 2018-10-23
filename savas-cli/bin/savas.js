#! /usr/bin/env node

process.on('unhandledRejection', error => {
    throw error
})

require('../index.js')