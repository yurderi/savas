console.reset = function () {
    return process.stdout.write('\033c');
}

const step = (items) => {
    let data = {}
    let current = 0
    let startTime = Date.now()
    
    let next = (resolve, reject) => {
        let item = items[current]

        //console.reset()
        console.log('[%d/%d] %s ...', current + 1, items.length, item.description)
        
        item.handler(
            () => {
                ++current
                
                if (current < items.length) {
                    next(resolve, reject)
                } else {
                    resolve(data)
                }
            },
            (err) => {
                reject(err)
            },
            data
        )
    }
    
    let promise = new Promise((resolve, reject) => {
        next(resolve, reject)
    })
    
    promise.then((data) => {
        let endTime = Date.now()
        let time    = (endTime - startTime) / 1000
        
        console.log('Finished in %ds', time)
        
        return data
    })
    
    return promise
}

/*step([
    
    {
        description: 'Creating files',
        
        handler(resolve, reject, data) {
            data.hello = 1
            resolve()
        }
    },
    {
        description: 'Cleaning up',
        handler(resolve, reject, data) {
            data.world = 1
            resolve()
        }
    }
    
]).then(data => {
    console.log(data)
})*/


module.exports = step