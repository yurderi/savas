export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'appID', type: 'integer' },
        { name: 'channelID', type: 'integer' },
        { name: 'version', type: 'string', filterable: true },
        { name: 'description', type: 'string', filterable: true },
        { name: 'created', type: 'string' },
        { name: 'changed', type: 'string' },
    ],
    proxy: {
        list: 'release/list',
        detail: 'release/detail',
        save: 'release/save',
        remove: 'release/remove'
    }
}