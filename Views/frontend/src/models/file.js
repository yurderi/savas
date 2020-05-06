export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'releaseID', type: 'integer' },
        { name: 'platformID', type: 'integer' },
        { name: 'filename', type: 'string', filterable: true },
        { name: 'displayName', type: 'string', filterable: true },
        { name: 'size', type: 'integer' },
        { name: 'extension', type: 'string' },
        { name: 'mimeType', type: 'string' },
        { name: 'created', type: 'string' },
        { name: 'changed', type: 'string' }
    ],
    proxy: {
        list: 'frontend/file/list',
        detail: 'frontend/file/detail',
        save: 'frontend/file/save',
        remove: 'frontend/file/remove'
    }
}