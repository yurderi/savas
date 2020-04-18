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
        list: 'frontend/token/list',
        detail: 'frontend/token/detail',
        save: 'frontend/token/save',
        remove: 'frontend/token/remove'
    }
}