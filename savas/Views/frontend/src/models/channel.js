export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'userID', type: 'integer' },
        { name: 'label', type: 'string', filterable: true },
        { name: 'short', type: 'string', filterable: true },
        { name: 'main', type: 'boolean' },
    ],
    proxy: {
        list: 'frontend/channel/list',
        detail: 'frontend/channel/detail',
        save: 'frontend/channel/save',
        remove: 'frontend/channel/remove'
    }
}