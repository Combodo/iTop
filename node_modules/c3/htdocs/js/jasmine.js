document.body.style.margin = '0px';

var div = document.createElement('div');
div.id = 'chart';
div.style.width = '640px';
div.style.height = '480px';
document.body.appendChild(div);

var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 20, 10, 40, 15, 25],
            ['data3', 150, 120, 110, 140, 115, 125]
        ]
    }
});
