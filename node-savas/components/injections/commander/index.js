const _ = require('lodash')
const boxen = require('boxen')
const { format } = require('util')
const chalk = require('chalk')
const Configstore = require('configstore')

let app = {
    opts: null,
    config: null,

    /**
     * Injects node-savas into an existing commander instance
     *
     * @param {object} program
     * @param {object} config
     */
    injectCommander: (program, config) => {
        app.opts = _.merge({
            /**
             * The technical app of your application/package (used for configstore)
             *
             * @var {string}
             */
            appName: '',
            /**
             * Where the update files should be extracted to
             *
             * @var {string}
             */
            destination: '',
            /**
             * Whether an warning is logged when update check has failed
             */
            silent: true,
            /**
             * Configuration of the application in savas
             *
             * @var {object}
             */
            appConfig: {
                /**
                 * The host (required)
                 *
                 * @var {string}
                 */
                host: '',
                /**
                 * Enable or disable https (default: false)
                 *
                 * @var {boolean}
                 */
                ssl: false,
                /**
                 * The application id (required)
                 *
                 * @var {string}
                 */
                id: '',
                /**
                 * The desired channel (required)
                 *
                 * @var {string}
                 */
                channel: '',
                /**
                 * The desired platform (required)
                 *
                 * @var {string}
                 */
                platform: '',
                /**
                 * The current application version (required)
                 *
                 * @var {string}
                 */
                version: '',
                /**
                 * The application public key. (required when app is private)
                 *
                 * @var {string|null}
                 */
                token: null
            },
            /**
             * Check for update when this method is called
             *
             * @var {boolean}
             */
            checkVersionOnStartup: true,
            /**
             * A listener which gets called when the version check is finished
             *
             * @var {function}
             */
            onVersionChecked: () => {
            },
            /**
             * The interval to check for updates in milliseconds
             *
             * @var {number}
             */
            interval: 86400000
        }, config)

        /**
         * The config-store is used to remember when we need to check for updates
         */
        app.config = new Configstore(app.opts.appName, {
            lastCheck: null
        })

        program.command('self-upgrade').description('Update to latest version').action(require('./commands/self_upgrade'))

        if (app.opts.checkVersionOnStartup) {
            app.checkForUpdates().then(() => {
                app.opts.onVersionChecked()
            })
        }
    },

    /**
     * Creates a new instance of savas
     *
     * @returns {*|module.UpdateService}
     */
    getInstance() {
        const { instance } = require('../../../index')

        return instance(app.opts.appConfig)
    },

    /**
     * Checks if an update-check is needed
     *
     * @returns {boolean}
     */
    needCheck() {
        return process.env.ENV === 'dev'
            || app.config.get('lastCheck') === null
            || Date.now() - app.config.get('lastCheck') > app.opts.interval
    },

    /**
     * Checks for an update and prints on screen if so
     *
     * @returns {Promise<void>}
     */
    async checkForUpdates() {
        if (app.needCheck() === false) {
            return
        }

        app.config.set('lastCheck', Date.now())

        let savas = app.getInstance()

        try {
            let update = await savas.getUpdate()

            if (update) {
                let installCommand = format('%s self-upgrade', app.opts.appName)
                let message = 'Update available ' + chalk.dim(version) + chalk.reset(' → ') +
                    chalk.green(update.version) + ' \nRun ' + chalk.cyan(installCommand) + ' to update'

                console.log(
                    boxen(
                        message,
                        {
                            padding: 1,
                            margin: 1,
                            align: 'center',
                            borderColor: 'yellow',
                            borderStyle: 'round'
                        }
                    )
                )
            }
        }
        catch (error) {
            if (app.opts.silent !== true) {
                console.log('%s could not check for updates (%s)', chalk.yellow('warning: '), error)
            }
        }
    }
}

module.exports = app