export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'userID', type: 'integer' },
        { name: 'label', type: 'string', filterable: true },
    ],
    proxy: {
        list: 'frontend/platform/list',
        detail: 'frontend/platform/detail',
        save: 'frontend/platform/save',
        remove: 'frontend/platform/remove'
    }
}