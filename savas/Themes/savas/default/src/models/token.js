export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'userID', type: 'integer' },
        { name: 'enabled', type: 'boolean' },
        { name: 'label', type: 'string', filterable: true },
        { name: 'token', type: 'string' },
        { name: 'created', type: 'string' },
        { name: 'changed', type: 'string' },
    ],
    proxy: {
        list: 'token/list',
        detail: 'token/detail',
        save: 'token/save',
        remove: 'token/remove'
    }
}