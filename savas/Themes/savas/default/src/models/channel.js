export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'userID', type: 'integer' },
        { name: 'label', type: 'string', filterable: true },
        { name: 'description', type: 'string', filterable: true },
    ],
    proxy: {
        list: 'platform/list',
        detail: 'platform/detail',
        save: 'platform/save',
        remove: 'platform/remove'
    }
}