function () {
    var $ctrl = this;
    $ctrl.teams = [
        {
            name: 'Personal',
            books: [
                {name: 'Test', description: 'Panjang'},
                {name: 'Dua', description: 'Panjang'},
                {name: 'Tiga', description: 'Panjang'},
                {name: 'Empat', description: 'Panjang'},
                {name: 'Lima', description: 'Panjang'},
            ]
        },
        {
            name: 'Mdmunir',
            books: [
                {name: 'Satu', description: 'Panjang'},
                {name: 'Dua', description: 'desc'},
                {name: 'Tiga', description: 'Panjang'},
                {name: 'Empat', description: 'Panjang'},
                {name: 'Lima', description: 'Panjang'},
            ]
        },
    ];

    $ctrl.chunk = function (items, size) {
        var chunks = [];
        if (angular.isArray(items)) {
            if (isNaN(size))
                size = 4;
            for (var i = 0; i < items.length; i += size) {
                chunks.push(items.slice(i, i + size));
            }
        } else {
            console.log("items is not an array: " + angular.toJson(items));
        }
        return chunks;
    }
}