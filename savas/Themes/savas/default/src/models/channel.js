export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'userID', type: 'integer' },
        { name: 'label', type: 'string', filterable: true },
        { name: 'short', type: 'string', filterable: true },
        { name: 'main', type: 'boolean' },
    ],
    proxy: {
        list: 'channel/list',
        detail: 'channel/detail',
        save: 'channel/save',
        remove: 'channel/remove'
    }
}