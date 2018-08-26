export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'label', type: 'string' },
        { name: 'description', type: 'string' },
        { name: 'publicKey', type: 'string' },
        { name: 'privateKey', type: 'string' },
        { name: 'created', type: 'string' },
        { name: 'changed', type: 'string' }
    ],
    proxy: {
        list: 'application/list',
        detail: 'application/detail',
        save: 'application/save',
        remove: 'application/remove'
    }
}