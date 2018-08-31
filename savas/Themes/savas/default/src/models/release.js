export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'appID', type: 'integer' },
        { name: 'channelID', type: 'integer' },
        { name: 'version', type: 'string' },
        { name: 'description', type: 'string' },
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