export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'releaseID', type: 'integer' },
        { name: 'platformID', type: 'integer' },
        { name: 'filename', type: 'string', filterable: true },
        { name: 'originalFilename', type: 'string', filterable: true },
        { name: 'size', type: 'integer' },
        { name: 'created', type: 'string' },
        { name: 'changed', type: 'string' }
    ],
    proxy: {
        list: 'file/list',
        detail: 'file/detail',
        save: 'file/save',
        remove: 'file/remove'
    }
}