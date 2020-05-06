export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'fileID', type: 'integer' },
        { name: 'type', type: 'string', filterable: true },
        { name: 'name', type: 'string', filterable: true },
        { name: 'version', type: 'string', filterable: true },
        { name: 'created', type: 'string' },
        { name: 'changed', type: 'string' }
    ],
    proxy: {
        list: 'frontend/requirement/list',
        detail: 'frontend/requirement/detail',
        save: 'frontend/requirement/save',
        remove: 'frontend/requirement/remove'
    }
}