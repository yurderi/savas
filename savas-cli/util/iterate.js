const step = (items) => {
    let data = {}
    let current = 0
    
    let next = (resolve, reject) => {
        let item = items[current]
        
        item(
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
    
    return new Promise((resolve, reject) => {
        next(resolve, reject)
    })
}


/*iterate([
    
    (resolve, reject, data) => {
        data.hello = 1
        resolve()
    },
    
    (resolve, reject, data) => {
        console.log(data)
        data.world = 1
        resolve()
    }
    
])
    .then((data) => {
        console.log(data)
    })*/


module.exports = step