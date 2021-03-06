export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'appID', type: 'integer' },
        { name: 'channelID', type: 'integer' },
        { name: 'active', type: 'boolean' },
        { name: 'version', type: 'string', filterable: true },
        { name: 'description', type: 'string', filterable: true },
        { name: 'created', type: 'string' },
        { name: 'changed', type: 'string' },

        // Additional columns
        { name: 'files', type: 'integer '}
    ],
    proxy: {
        list: 'frontend/release/list',
        detail: 'frontend/release/detail',
        save: 'frontend/release/save',
        remove: 'frontend/release/remove'
    }
}