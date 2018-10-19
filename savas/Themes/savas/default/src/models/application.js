export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'label', type: 'string', filterable: true },
        { name: 'description', type: 'string', filterable: true },
        { name: 'visibility', type: 'string', default: 'public' },
        { name: 'publicKey', type: 'string' },
        { name: 'privateKey', type: 'string' },
        { name: 'created', type: 'string' },
        { name: 'changed', type: 'string' },

        // Additional columns
        { name: 'currentVersion', type: 'string', default: '-' },
        { name: 'releaseCount', type: 'integer', default: 0 },
        { name: 'downloadCount', type: 'integer', default: 0 },
        { name: 'feedbackCount', type: 'integer', default: 0 }
    ],
    proxy: {
        list: 'application/list',
        detail: 'application/detail',
        save: 'application/save',
        remove: 'application/remove'
    }
}