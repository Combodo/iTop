var chart = c3.generate({
    data: {
        columns: [
            ['data1', 100, 100],
            ['data2', 50, 150]
        ],
        selection: {
          enabled: true,
          isselectable: function(d) {
              return d.id === 'data1';
          }
        },
        onclick: function(d) {
            console.log('clicked', d.id);
        },
        type: 'bar',
        types: {
            data2: 'line',
        }
    },
    tooltip: {
    grouped: false
    }
});
/*
var chart = c3.generate({
    bindto: '#chart',
    data: {
      type: 'bar',
      columns: [
        ['data1', 3, 2],
        ['data2', 10],
        ['data3', null, 10],
      ],
        groups: [
            ['data1', 'data2', 'data3'],
        ]
    }
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 20, 10, 40, 15, 25]
        ]
    },
    regions: [
        {
            axis: 'y',
            start: 300,
            end: 400,
            class: 'green'
        },
        {
            axis: 'y',
            start: 0,
            end: 100,
            class: 'green'
        }
    ]
});

setTimeout(function(){
    var regions = [
            {
                axis: 'y',
                start: 250,
                end: 350,
                class: 'red'
            },
            {
                axis: 'y',
                start: 25,
                end: 75,
                class: 'red'
            }
        ];
    
   chart.regions(regions);
}, 1000);
*/
/*
setTimeout(function(){
    var regions = [
            {
                axis: 'y',
                start: 250,
                end: 350,
                class: 'red'
            },
            {
                axis: 'y',
                start: 25,
                end: 75,
                class: 'red'
            }
        ];
    
   chart.regions.add(regions);
}, 2000);
*/



/*
var chart = c3.generate({
    data: {
        x: 'date',
        columns: [
            ['date', '2012-12-24', '2012-12-25', '2012-12-26', '2012-12-27', '2012-12-28', '2012-12-29'],
            ['data1', 100, 200, 150, 300, 200],
            ['data2', 0, 500, 250, 700, 300],
//            ['data3', 100, 200, 150, 300, 200],
//            ['data4', 0, 500, 250, 700, 300],
//            ['data6', 100, 200, 150, 300, 200],
//            ['data7', 0, 1500, 1250, 1700, 1300],
        ],
//        labels: true,
        type: 'bar',
        groups: [
            ['data1', 'data2', 'data3', 'data4', 'data5', 'data6', 'data7'],
        ]
    },
    axis: {
        x: {
            type: 'timeseries',
//            height: 200,
        },
        rotated: true,
    },
});

*/
/*
var chart = c3.generate({
    data: {
        pairs: [
            { x: 'HOGEHOGE_0', y: 'HOGEHOGE_0', value: 10000 },
            { x: 'HOGEHOGE_0', y: 'HOGEHOGE_1', value: 20000 },
            { x: 'HOGEHOGE_0', y: 'HOGEHOGE_2', value: 39990 },
//            { x: 'HOGEHOGE_1', y: 'HOGEHOGE_0', value: 50 },
            { x: 'HOGEHOGE_1', y: 'HOGEHOGE_1', value: 6000 },
            { x: 'HOGEHOGE_1', y: 'HOGEHOGE_2', value: 50000 },
            { x: 'HOGEHOGE_2', y: 'HOGEHOGE_0', value: 1000 },
            { x: 'HOGEHOGE_2', y: 'HOGEHOGE_1', value: 2000 },
            { x: 'HOGEHOGE_2', y: 'HOGEHOGE_2', value: 30000 },
        ],
        type: 'bubble'
    },
    grid: {
        x: {
            show: true
        },
        y: {
            show: true,
        }
    }
});

/*
window.grouped = true;
window.toggleGroups = function() {
    if(grouped) {
        chart.groups([]);
        grouped = false;
    } else {
        chart.groups([['series1_y','series2_y']]);   
        grouped = true;
    }
}

window.chart = c3.generate({
    bindto: "#chart",
    data: {
        columns: [

            ['series0_x',2,3,4], //missing item
            ['series0_y',1,2,5], //missing item, breaks
            ['series1_x',0,2,3,4,5], //works
            ['series1_y',0,1,2,3,12], //works
            ['series2_x',1,2,3,4,6],
            ['series2_y',0,10,12,13,12],
        ],
        type: "area",
        xs: {
            series0_y: "series0_x",
            series1_y: "series1_x",
            series2_y: "series2_x"
        },
        groups: [[
            'series0_y',
            'series1_y',
            'series2_y'
        ]]
    }
});
*/


/*
var limit = 8;
var charts = [];
for (var i = 1; i <= limit; i++) {
    var el = document.createElement('div');
    el.id = 'chart' + i;
    $('body').append(el);
}

for (var i = 1; i <= limit; i++) {
    var chart = c3.generate({
        bindto: '#chart' + i,
        data: {
            columns: [
                ['data1', 300, 350, 300, 0, 0, 0],
                ['data2', 130, 100, 140, 200, 150, 50]
            ],
            types: {
                data1: 'area',
                data2: 'area-spline'
            }
        }
    });
    charts.push(chart);
}

function update() {
    for (var i = 0; i < limit; i++) {
        charts[i].load({
            columns: [
            getRandomNum('data1'),
            getRandomNum('data2'), ]
        });
    }
}

function getRandomNum(name) {
    var datapoints = 60;
    var data = [];
    data.push(name)
    for (var i = 0; i < datapoints; i++) {
        data.push((Math.floor(Math.random() * (400 - 0)) + 0));
    };
    return data;
}

setInterval(function () {
    update();
}, 1000);
*/

/*
var generateData = function() {
    var data = [];
    for (var i = 0; i < 5; i++) {
        var datum = [];
        datum.push('dataset ' + i);
        for (var j = 0; j < 20; j++) {
            datum.push(Math.random() * 1000);
        }
        data.push(datum);
    }
    return data;
};

var int, c;

var toggleTest = function() {
    if (!int) {
        int = setInterval(function(){
            if (c) { c.destroy(); }
            c = c3.generate({
                data: {
                    columns: generateData()
                }
            });
        }, 1000);
    } else {
        clearInterval(int);
    }
};
d3.select('#redraw').on('click', toggleTest);
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', -100, -200, -150, -300, -200],
            ['data2', 0, -500, -250, -700, -300], ],
//        labels: true,
//        type: 'bar'
    },
    axis: {
        rotated: true,
        x: {
        }
    }
});
setTimeout(function () {
    chart.load({
        columns: [
            ['data3', -100, -200, -150, -300, -200],
        ],
    });
}, 1000);
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 100, 200, 50, 100, 200, 50, 100, 200, 50, 100, 200, 50],
            ['data2', 300, 400, 100],
        ],
//        type: 'pie',
    },
    pie: {
//        expand: {
//            duration: 300
//        }
    },
    legend: {
        padding: 100,
        item: {
            tile: {
                width: 20,
                height: 2
            }
        }
    },
//    zoom: {
//        enabled: true,
//        x: {
//            min: -10,
//            max: 20
//        }
//    },
    subchart: {
        show: true,
        axis: {
            x: {
                show: false
            }
        }
        
    }
});
*/


/*
var chart = c3.generate({
    padding: {
        left: 200,
    },
    legend: {
        show: false
    },
    data: {
        type: 'bar',
        columns: [
            ['foo', 2621.73, 30260.59, 53622.22, -9.03, 816.62, 0, 58.33, 0, 0, 0, 35106.75]
        ]
    },
    axis: {
        rotated: true,
        x: {
            type: 'category',
            categories: ['Anthoney Schneider', 'Brenton Streich', 'Brigette Stehr',
                         'Caitlyn Gulgowski', 'Dr. Shaniya Little', 'Jax Rath',
                         'Leonor Stanton DDS', 'Miss Hazelle Kunze PhD', 'Mr. Landon Emmerich',
                         'Nicole Gleichner', 'Odelia Kozey']
        }
    }
});
*/
/*
var chart = c3.generate({
    data: {
        x: 'x',
        columns: [
            ['x', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 1, 2, 4, 5],
            ['data1', 30, 200, 100, 400, 150],
            ['data2', 100, 30, 200, 320, 50],
        ]
    },
    axis: {
        rotated: true,
        x: {
            type: 'category',
            tick: {
                format: function () {
                    return 'FORMATTED FORMATTED aaaaaaaaaaaaaaaaaaaaa FORMATTED ';
                }
            }
        },
        y: {
        }
    }
});

setTimeout(function () {
    chart.load({
        columns: [['data1', 300, 230, 400, 520, 230, 250, 330, 280, 250]]
    });
}, 1000);
*/


/*
var args = {
    data: {
        columns: [
            ['data1', -1030, -2200, -2100],
            ['data2', -1150, -2010, -1200],
            ['data3', 1030, 2200, 2100],
            ['data4', 1150, 2010, 1200]
        ],
        type: 'bar',
        labels: true,
        groups: [['data1', 'data2'], ['data3', 'data4']]
    },
    padding: {
//        left: 100
    }
};

function draw() {
    args.bindto = '#chart';
    var chart = c3.generate(args);

args.bindto = '#chart1';
var chart1 = c3.generate(args);

args.bindto = '#chart2';
var chart2 = c3.generate(args);

args.bindto = '#chart3';
var chart3 = c3.generate(args);

args.bindto = '#chart4';
var chart4 = c3.generate(args);

args.bindto = '#chart5';
var chart5 = c3.generate(args);

args.bindto = '#chart6';
var chart6 = c3.generate(args);

args.bindto = '#chart7';
var chart7 = c3.generate(args);

args.bindto = '#chart8';
var chart8 = c3.generate(args);
}
//d3.select('#redraw').on('click', function () {
    draw();
//});
*/


/*
var chart = c3.generate({
    axis: { x: { type: "categorized", } },
    data: {
        type: "bar",
        types: {"ddd": "line"},
        columns: [
            ["aaa", "a", "b", "c"],
            ["bbb", 10,20,30],
            ["ccc", 40,50,60],
            ["ddd", 70,80,90],
        ],
        x: "aaa",
        groups: [["aaa", "bbb", "ccc"]],
    },
});

window.setTimeout(function(){
chart.load({
    type: "bar",
    types: {"ddd": "line"},
    columns: [
            ["aaa", "a", "b", "c"],
            ["bbb", 30,20,30],
            ["ccc", 80,50,60],
            ["ddd", 20,80,90],
    ],
    x: "aaa",
    groups: [["aaa", "bbb", "ccc"]],
});
}, 1000);
*/

/*
var chart = c3.generate({
    data: {
        x: 'x',
        columns: [
            ['x', 'category 1 aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'category 2', 'category 3', 'category 4', 'category 5', 'category 6'],
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 20, 10, 40, 15, 25]
        ]
    },
    axis: {
        x: {
            type: 'category',
            tick: {
//                rotate: 30
            }
        }
    }
});
*/

/*
var chart = c3.generate({
    data: {
        x: 'x',
        columns: [
            ['x', '2012-12-24', '2012-12-25', '2012-12-26', '2012-12-27', '2012-12-28', '2012-12-29', '2012-12-30', '2012-12-31'],
            ['data1', parseInt(Math.random() * 100), parseInt(Math.random() * 100), parseInt(Math.random() * 100), parseInt(Math.random() * 100), parseInt(Math.random() * 100), parseInt(Math.random() * 100), parseInt(Math.random() * 100), parseInt(Math.random() * 100)], ]
    },
    axis: {
        x: {
            type: 'timeseries',
            tick: {
                rotate: 75,
                fit: false
            }
        }
    }
});

var format = d3.time.format("%Y-%m-%d");
var date = new Date(2013, 0, 1);

setTimeout(function () {
    chart.flow({
        columns: [
            ['x', format(date)],
            ['data1', parseInt(Math.random() * 100)]
        ]
    });

    date.setDate(date.getDate() + 1);
}, 1000);
*/


/*
var generateData = function() {
    var data = [];
    for (var i = 0; i < 3; i++) {
        var datum = [];
        datum.push('dataset ' + i);
        for (var j = 0; j < 20; j++) {
            datum.push(Math.random() * 1000);
        }
        data.push(datum);
    }
    return data;
};

var chart = c3.generate({
    bindto: null,
    data: {
//        columns: generateData(),
        columns: [
            ['data1', 100, 200, 50],
            ['data2', 300, 400, 100],
        ],
        labels: true
    }
});
setTimeout(function () {
    $('#chart').append(chart.element);
}, 1000);
*/


/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1'],
            ['data2'],
        ],
        labels: true
    },
    bar: {
        width: 10
    },
    axis: {
        x: {
        },
        y: {
        }
    },
    grid: {
        x: {
            show: true,
            lines: [{value: 3, text:'Label 3'}, {value: 4.5, text: 'Label 4.5'}]
        },
        y: {
            show: true
        }
    },
    regions: [
        {start:2, end:4, class:'region1'},
        {start:100, end:200, axis:'y'},
    ],
});

setTimeout(function () {
    console.log("flow");
    chart.flow({
        rows: [
            ['data1', 'data2', 'data3'],
            [500, 100, 200],
            [200, null, null],
            [100, 50, null] 
        ],
    });
}, 1000);
*/


/*
var chart1 = c3.generate({
    data: {
        columns: [
            ['data1', -1030, -2200, -2100],
            ['data2', -1150, -2010, -1200]
//            ['data1', 1030],
//            ['data2', 1150]
        ],
        type: 'area',
        labels: true,
        groups: [['data1', 'data2']]
    },
    axis: {
    }
});
*/
/*
var chart = c3.generate({
    data: {
        x: 'date',
        columns: [
            ['date', '2014-01-01', '2014-01-10', '2014-01-20', '2014-01-30', '2014-02-01'],
            ['sample', 30, 200, 100, 400, 150, 250]
        ]
    },
    axis: {
        x: {
            type: 'timeseries'
        }
    },
    regions: [
        {start: '2014-01-05', end: '2014-01-10'},
        {start: new Date('2014/01/15'), end: new Date('20 Jan 2014')},
        {start: 1390575600000, end: 1391007600000} // start => 2014-01-25 00:00:00, end => 2014-01-30 00:00:00
    ]
});
*/

/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 20, 10, 40, 15, 25]
        ],
        axes: {
//            data1: 'y',
//            data2: 'y2'
        }
    },
    axis: {
        y: {
            label: 'Y Axis Label'
        },
        y2: {
//            show: true,
            label: 'Y2 Axis Label'
        }
    }
});
*/

/*
setTimeout(function () {
    chart.axis.labels({y2: 'New Y2 Axis Label'});
}, 1000);

setTimeout(function () {
    chart.axis.labels({y: 'New Y Axis Label', y2: 'New Y2 Axis Label Again'});
}, 2000);
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30, -200, -100, 400, 150, 250],
            ['data2', -50, 150, -150, 150, -50, -150],
            ['data3', -100, 100, -40, 100, -150, -50]
        ],
        groups: [
            ['data1', 'data2']
        ],
        type: 'bar',
        labels: {
//            format: function (v, id, i, j) { return "Default Format"; }
            format: {
                y: d3.format('$'),
//                y: function (v, id, i, j) { return "Y Format"; },
//                y2: function (v, id, i, j) { return "Y2 Format"; }
            }
        }
    },
    grid: {
        y: {
            lines: [{value: 0}]
        }
    }
});
*/

/*
var chart = c3.generate({
    legend: {
        show:false
    },
    data: {
        x:'x',
        columns: [
            ['x','aaa','bbbs','abcdefgh','ddd','e','f','gggg12'],
            ['Cost1', 3,0,8,34,6,8,4],
            ['Cost', 3,0,8,34,6,8,4],
        ],
        type: 'bar',
        labels:{
            format: {
                y: function (v,id,i,j){ return '$'+v;}
            }
        },
        
        groups:[]
    },
    axis: {
//        rotated: true,
        x:{
            type:'category',
            label:{
                text: 'Users',
                position: 'outer-middle'
           }
        },
        y:{
            label:{
                text: 'Cost ($)',
                position: 'outer-center',
            },
        },
    },
    legend: {
//        position: 'right'
    }
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250, 50, 100, 250],
            ['data2', 100, 30, 200, 320, 50, 150, 230, 80, 150],
        ],
        selection: {
//            enabled: true
        }
    }
});
*/
/*
setTimeout(function () {
    chart.focus('data1');
}, 1000);
setTimeout(function () {
    chart.revert('data2');
}, 2000);
*/
/*
setTimeout(function () {
    chart.load({
        columns: [['data1', 300, 230, 400, 520, 230, 250, 330, 280, 250]]
    });
}, 1000);
*/
/*
setTimeout(function () {
    chart.flow({
        columns: [
            ['data1', 390, 400, 200, 500]
        ],
        duration: 1000,
    });
}, 1000);
*/


/*
var chart = c3.generate({
    data: {
        url: 'hoge'
//        columns: [
//            ['sample', 30, 200, 100, 400, 150, 250]
//        ]
    },
    axis: {
        rotated: true
    },
    grid: {
        x: {
            lines: [
                {value: 3, text: 'Lable 3', position: 'start'},
                {value: 4.5, text: 'Lable 4.5'},
                {value: 1, text: 'Lable 1', position: 'middle'}
            ]
        },
        y: {
            lines: [
                {value: 30, text: 'Lable 30', position: 'start'},
                {value: 145, text: 'Lable 145'},
                {value: 225, text: 'Lable 225', position: 'middle'}
            ]
        }
    }
});
*/


/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30],
        ],
        type: 'gauge',
    },
    gauge: {
        max: 100
    }
});
setTimeout(function () {
    chart.internal.config.gauge_max = 200;
    chart.flush();
    chart.load({
        columns: [
            ['data1', 30]
        ]
    });
}, 1000);
*/

/*
var chart = c3.generate({
    size:{width:450, height: 250},
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 20, 10, 40, 15, 25]
        ]
    }
});
var nextLoad = 0;
var load = [
    function () {
        chart.load({
            columns: [
                ['data1', 230, 190, 300, 500, 300, 400]
            ]
        });
    },
    function () {
        chart.unload({
            ids: 'data3'
        });
    },
    function () {
        chart.load({
            columns: [
                ['data2', 230, 190, 300, 500, 300, 400]
            ]
        });
    },
    function(){
        chart.load({
            columns: [
                ['data3', 130, 150, 200, 300, 200, 100]
            ]
        });
    },
    function () {
        chart.unload({
            ids: 'data1'
        });
    },
    function(){
        chart.load({
            columns: [
                ['data1', 30, 200, 100, 400, 150, 250],
                ['data2', 50, 20, 10, 40, 15, 25]
            ]
        });
    }
];

setInterval(function(){
    load[nextLoad%load.length]();
    nextLoad++;
}, 500);
*/

/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', null],
            ['data2', null],
            ['data3', null]
        ],
        type: 'pie',
    }
});
setTimeout(function () {
    chart.load({
        columns: [
            ['data1', 10],
            ['data2', 100],
            ['data3', 100],
        ]
    });
}, 1000);
*/

/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30, 20, 50, 40, 60, 50],
            ['data2', 200, 130, 90, 240, 130, 220],
            ['data3', 300, 200, 160, 400, 250, 250]
        ],
        type: 'bar',
        colors: {
            data1: '#ff0000',
            data2: '#00ff00',
            data3: '#0000ff'
        },
        color: function (color, d) {
            // d will be 'id' when called for legends
            return d.id && d.id === 'data3' ? d3.rgb(color).darker(d.value / 150) : color;
        }
    }
});
*/

/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 10, 200],
            ['data2', 20, 300],
            ['data3', 120, 330],
            ['data4', 220, 310],
            ['data5', 320, 350],
            ['data6', 420, 370],
        ],
        names: {
            new_data: 'New Data',
            data1: 'Data 1',
            data2: 'Data 2',
            data3: 'Data 3',
            data4: 'Data 4',
            data5: 'Data 5',
            data6: 'Data 6',
        }
//        type: 'bar'
    },
    axis: {
        x: {
//            type: 'category',
//            min: 0,
//            max: 5,
            tick: {
//                values: [0, 1, 2, 3, 4, 5],
//                fit: false
            }
        }
    }
});
setTimeout(function () {
    chart.unload({
        ids: 'data3'
    });
}, 1000);

setTimeout(function () {
    chart.load({
        columns: [
            ['new_data', 100, 100],
        ]
    });
}, 2000);
*/



/*
var chart = c3.generate({
  height: 300,
  data: {
    x: 'date',
    xFormat: '%Y%m%d',
    rows: [
      ['date', 'price'],
      ['20140915', 50],
      ['20140916', 20],
      ['20140917', 10],
      ['20140918', 40],
      ['20140919', 15],
      ['20140920', 25]
    ]
  },
  axis: {
    x: {
      type: 'timeseries',
      tick: {
        format: '%Y-%m-%d'
      }
    }
  }
});
*/

/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30],
            ['data2', 120],
        ],
        type : 'donut',
        onclick: function (d, i) { console.log("onclick", d, i); },
        onmouseover: function (d, i) { console.log("onmouseover", d, i); },
        onmouseout: function (d, i) { console.log("onmouseout", d, i); }
    },
    donut: {
        title: "Iris Petal Width"
    },
});

setTimeout(function () {
    chart.load({
        columns: [
            ["setosa", 0.2, 0.2, 0.2, 0.2, 0.2, 0.4, 0.3, 0.2, 0.2, 0.1, 0.2, 0.2, 0.1, 0.1, 0.2, 0.4, 0.4, 0.3, 0.3, 0.3, 0.2, 0.4, 0.2, 0.5, 0.2, 0.2, 0.4, 0.2, 0.2, 0.2, 0.2, 0.4, 0.1, 0.2, 0.2, 0.2, 0.2, 0.1, 0.2, 0.2, 0.3, 0.3, 0.2, 0.6, 0.4, 0.3, 0.2, 0.2, 0.2, 0.2],
            ["versicolor", 1.4, 1.5, 1.5, 1.3, 1.5, 1.3, 1.6, 1.0, 1.3, 1.4, 1.0, 1.5, 1.0, 1.4, 1.3, 1.4, 1.5, 1.0, 1.5, 1.1, 1.8, 1.3, 1.5, 1.2, 1.3, 1.4, 1.4, 1.7, 1.5, 1.0, 1.1, 1.0, 1.2, 1.6, 1.5, 1.6, 1.5, 1.3, 1.3, 1.3, 1.2, 1.4, 1.2, 1.0, 1.3, 1.2, 1.3, 1.3, 1.1, 1.3],
            ["virginica", 2.5, 1.9, 2.1, 1.8, 2.2, 2.1, 1.7, 1.8, 1.8, 2.5, 2.0, 1.9, 2.1, 2.0, 2.4, 2.3, 1.8, 2.2, 2.3, 1.5, 2.3, 2.0, 2.0, 1.8, 2.1, 1.8, 1.8, 1.8, 2.1, 1.6, 1.9, 2.0, 2.2, 1.5, 1.4, 2.3, 2.4, 1.8, 1.8, 2.1, 2.4, 2.3, 1.9, 2.3, 2.5, 2.3, 1.9, 2.0, 2.3, 1.8],
        ]
    });
}, 1500);

setTimeout(function () {
    chart.unload({
        ids: 'data1'
    });
    chart.unload({
        ids: 'data2'
    });
}, 2500);
*/


/*
var generateData = function() {
    var data = [];
    for (var i = 0; i < 5; i++) {
        var datum = [];
        datum.push('dataset ' + i);
        for (var j = 0; j < 20; j++) {
            datum.push(Math.random() * 1000);
        }
        data.push(datum);
    }
    return data;
};

var int, chart;

var toggleTest = function() {
    if (!int) {
        int = setInterval(function(){
            if (chart) {
                chart = chart.destroy();
            }
            chart = c3.generate({
                data: {
                    columns: generateData()
                }
            });
        }, 500);
    } else {
        clearInterval(int);
    }
};
d3.select('#load').on('click', toggleTest);
*/

/*
var cols = [["x","2014-01-01T00:00:00.000Z","2014-01-01T03:37:26.004Z","2014-01-01T07:14:52.008Z","2014-01-01T10:52:19.002Z","2014-01-01T14:29:45.006Z"], ["num1",7.753290000000037,7.753290000000037,7.753290000000037,7.753290000000037,7.753290000000037], ["num2",1.6549999999999927,1.654999999999993,1.6549999999999927,1.654999999999993,1.6549999999999927], "num3",100,10,40,90,90];
             
cols[0] = _.map(cols[0], function(d) {
    if (d !== 'x') {
        var date = d;
        if (!/.*\.\d+$/.test(date)) {
          date += '.0';
        }
        return d3.time.format('%Y-%m-%dT%H:%M:%S.%LZ').parse(d);
    } else {
        return d;
    }
});
var types = {"types":{"num1":"scatter","num2":"area","num3":"line"},"axes":{"num1":"y2","num2":"y2","num3":"y"}};
            
var chart = c3.generate({
    data: {
        x: 'x',
//        types: types.types,
        columns: cols,
        selection: {
//            enabled: false   
        },
        type: 'pie'
    },
    subchart: {
//        show: true
    },
    axis: {
        type: 'timeseries'
    },
    legend: {
//            show: false
    },
    point: {
        r: function(item) {
            var val = item.value;
          console.log("item =>", item);

          if (val === null || val === undefined || val === 0) {
            return 0;
          } else {
            return 4.5;
          }
        }
    },
    tooltip: {
//        grouped: false
    }
});
*/


/*
var chart;
$(function () {
    function redraw() {
        chart = c3.generate({
            title: "As of 2\/4\/2014 @ 1:19 PM CST",
            padding: {
                left: 30
            },
            data: {
                x: 'date',
                x_format: '%b %y',
                columns: [
                    ['date', "2014-02-01", "2014-03-01", "2014-04-01", "2014-05-01", "2014-06-01", "2014-07-01", "2014-08-01", "2014-09-01", "2014-10-01", "2014-11-01", "2014-12-01", "2015-01-01"],
                    ['Value', 0.077500000000000568, 0.084999999999993747, 0.090000000000003411, 0.090000000000003411, 0.094999999999998863, 0.094999999999998863, 0.099999999999994316, 0.10999999999999943, 0.10999999999999943, 0.12000000000000455, 0.12999999999999545, 0.14000000000000057]
                ],
                types: {
                    Value: 'area'
                }
            },
            legend: {
                show: false
            },
            axis: {
                y: {
                    min: 0,
                    max: 0.2,
                    padding: {
                        top: 0,
                        bottom: 0
                    }
                },
                x: {
                    type: 'timeseries',
                    tick: {
                        format: '%m/%y'
                    }
                }
            },
            tooltip : {
                format : {
                    title: d3.time.format('%B %Y'),
                    value: d3.format(".3r")
                }
            },
            grid: {
                y: {
                    show: true
                }
            }
        });
    }
    
    $('#hide').click(function() {
        $('.overlay').show();
        $('.widget').hide();
    });
    $('#show').click(function() {
        $('.overlay').hide();
        $('.widget').show();
    });
    $('#resize').click(function() {
        $('#widget').resize();
    });
    
    $('#redraw').click(function(){
        redraw();
    });
    
    redraw();
});
*/


/*
c3.generate({
  data: {
    x: 'categories',
    columns: [
      ['categories', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',],
      ['data1', 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16],
      ['data2', 4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19],
      ]
  },
  axis: {
    x: {
      type: 'category',
      tick: {
        count: 4
      }
    }
  }
});
*/


/*
c3.chart.internal.fn.xForRotatedTickText = function () {
    return -10;
};
var chart = c3.generate({
    data: {
        x: 'date',
        columns: [
            ['date', '2014-01-01', '2014-01-10', '2014-01-20', '2014-01-30', '2014-02-01'],
            ['sample', 30, 200, 100, 400, 150, 250]
        ],
        type: 'donut'
    },
    donut: {
        title: 'HogeHoge'
    },
    axis: {
        x: {
            type: 'categories',
            tick: {
                rotate: 60
            }
        }
    },
});
*/


/*
var chart = c3.generate({
    data: {
        x: 'date',
        columns: [
            ['date', '2014-01-01', '2014-01-10', '2014-01-20', '2014-01-30', '2014-02-01'],
            ['sample', 30, 200, 100, 400, 150, 250]
        ]
    },
    axis: {
        x: {
            type: 'timeseries'
        }
    },
    regions: [
        {start: '2014-01-05', end: '2014-01-10'},
        {start: new Date('2014-01-15 00:00:00'), end: new Date('2014-01-20 00:00:00')},
        {start: 1390575600000, end: 1391007600000} // start => 2014-01-25 00:00:00, end => 2014-01-30 00:00:00
    ],
    onrendered: function () {
        console.log('rendered');
    }
});
setTimeout(function () {
    chart.load({
        json: {
            data4: [130, 220, 350, 140, 360, 250, 100, 300]
        }
    });
}, 1000);
*/


/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30000, 20000, 10000, 40000, 15000, 250000],
            ['data2', 100, 200, 100, 40, 150, 250]
        ],
        axes: {
            data2: 'y2'
        }
    },
    axis : {
        y : {
            tick: {
                format: d3.format("s")
            }
        },

        y2: {
            show: true,
            tick: {
                format: d3.format("$")
            }
        }

    },
    tooltip: {
        format: {
            title: function (d) { return 'Data ' + d; },
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('$');
                return format(value);
            }
//            value: d3.format(',') // apply this format to both y and y2
        }
    }
});
*/


/*
var categories = [];
var count = [];
for (var i = 0; i < 20; i++) {
    categories.push('ABC-DEFG');
    count.push(5);
} 

var chart = c3.generate({
    data: {
        x: 'x',
        columns: [
            ['x'].concat(categories),
            ['count'].concat(count)
        ],
        groups: [
            ['count']
        ],
        type: 'bar'
    }, 
    axis: {
        x: {
            type: 'category',
            tick: {
                format: function (x) {
                    var category = this.categoryName(x);
                    return category.substr(0, 2) + '..';
                }
            }
        }
    }
});
*/


/*
setTimeout(function () {
    chart.focus('data1');
}, 1000);

setTimeout(function () {
    chart.revert();
}, 2000);

setTimeout(function () {
    chart.load({
        json: {
            data4: [130, 220, 350, 140, 360, 250, 100, 300]
        }
    });
}, 3000);
*/



/*
c3.chart.fn.subchart = function () {};
c3.chart.fn.subchart.show = function () {
    var $$ = this.internal;
    $$.config.subchart_show = true;

    $$.updateSizes();
    $$.updateTargetsForSubchart(chart.internal.data.targets);
    $$.showTargets();

    this.flush();
};
c3.chart.fn.subchart.hide = function () {
    var $$ = this.internal;
    $$.config.subchart_show = false;

    this.flush();
};

var chart = c3.generate({
    bindto: '#chart',
    data: {
        columns: [['1', 1, 2, 2, 1, 4]]
    },
    subchart: {
//        show: true
    },
//    padding: {top: 50}
});
*/

//chart.xgrids.add({value: 3, text: "why so low"});
/*
setTimeout(function () {
    chart.subchart.show();
}, 1000);

setTimeout(function () {
    chart.subchart.hide();
}, 2000);
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 190, 200, 190, null],
            ['data2', 90, 20, -19, 50],
            ['data3', 490, 300, 490, null],
        ],
        type: 'bar'
    },
    axis: {
        y: {
            inverted: true
//            max: -100,
//            min: 600,
        }
    }
});
*/



/*
var values = [];

for (var j=0; j< 40; j++){
    values.push('s'+j)
}

var series = [];
for (var i=0; i < 365; i++){
    var point = {
        "x": (1388620800 + i*86400) * 1000
    };
    
    for (var j=0; j< Math.random()*10; j++){
        point['s'+j] = Math.random()*10
    } 
    
    series.push(point);
    
}



var chart = c3.generate({
    bindto: '#chart',
    data: {
        json: [],
        keys: {
            x: 'x',
            value: values
        },
        type: 'bar',
        groups: [values]
    },
    padding: {
        left: 50
    },
    axis: {
        x: {
            type: 'timeseries',
            padding: {
                left: 0,
                right: 0,
            },
            tick: {
                format: '%Y-%m-%d'
            }
        },
        y: {
            tick: {
                count: 5,
                format: d3.format('.2s')
            }
        }
    },
    subchart: {
        show: true,
        size: {
            height: 20
        }
    },
    size: {
//        height: 650
    },
    zoom: {
        rescale: true
    },
    grid: {
        x: {
            show: true
        },
        y: {
            show: true
        }
    },
    tooltip: {
        format: {
            title: function(d) {
                return '' + d;
            },
            value: function(v) {
                return v
            }
        }
    },
    transition: {
        duration: null
    },
    onrendered: function () {
        console.log('rendered!!', d3.selectAll('.c3-chart-lines g').size());
        
    }
});

//document.getElementById('loading').innerHTML = 'LOADING';

// now leats simulate loading via ajax
//setTimeout(function(){
d3.select('#load').on('click', function(){
    var startDate = performance.now();
    chart.load({
        json: series,
        keys: {
            x: 'x',
            value: values
        }
    });
    var taken = performance.now() - startDate;
    document.getElementById('timeTaken').innerHTML = taken + 'ms';
//},20);
});
*/



/*
var chart = c3.generate({
    data: {
        x : 'Station',
        columns: [
            ["Station","Hydration Station 1","Hydration Station 2","Hydration Station 3","Hydration Station 4","Hydration Station 5","Hydration Station 6","Hydration Station 7","Hydration Station 8","Hydration Station 9","Hydration Station 10",
             "Hydration Station 11","Hydration Station 12","Hydration Station 13","Hydration Station 14","Hydration Station 15","Hydration Station 16","Hydration Station 17","Hydration Station 18","Hydration Station 19","Hydration Station 20"],
            ["Drip Rate",2.72,1.53,1.67,1.22,0.92,0.78,1.13,1.20,2.53,1.16,2.18,1.58,1.96,1.19,1.50,1.59,0.71,1.30,1.42],
            ["Flow Rate",2.39,1.47,0.33,1.14,0.61,0.10,0.97,0.89,2.50,0.73,2.00,1.57,1.85,1.16,1.49,1.31,0.61,1.27,1.33,1.87],
            ["Loss Rate",0.00,0.00,0.00,0.02,0.03,0.03,0.03,0.03,0.04,0.05,0.06,0.06,0.07,0.08,0.10,0.10,0.12,0.12,0.12,0.13]],
        type: 'bar'
    },
    axis: {
        x: {
            type: 'category',
            tick: {
                rotate: 30,
                multiline:false
            },
        }
    }
});
*/

/*
var chart = c3.generate({
    bindto: '#chart1',
    data: {
        columns: [
            ['data1', 190, 200, 190, null],
            ['data2', 90, 20, -19, 50],
            ['data3', 490, 300, 490, null],
        ],
    },
    axis: {
        x: {
            padding: 0
        }
    }
});
*/


/*
var dataa = {"xs":{"yHUM-1111":"xHUM-1111"},
             "types":{"yHUM-1111":"area"},
             "columns":[["xHUM-1111",1421006366616,1421005781613,1421005184171,1421004576704],
                        ["yHUM-1111",31.109,30.804,30.651,30]
                       ]
            };
var chart = c3.generate({
    bindto: "#chart",
    data: dataa,
    axis : {
        x : {
            type : 'timeseries',
            tick: {
                format: '%d-%b %H:%M', 
                culling: { min : 5 },
                fit:false,
                rotate: 35
            }
        },
        y2: {
            show: false
        }
    },
    legend: {
        position: "inset",
        inset: {
            anchor: 'top-right',
            x: 20,
            y: 10,
            step: 1
        }
    },
    zoom: {
        enabled: false
    },
    tooltip: {
        show: true,
        grouped: true
    },
    point: {
        show: false
    }
});
*/


/*
var test_array_JSONs = [
    {"Label 1": 20, "Label 2": 50, "Label 3": 30},
];
var donutChart = c3.generate ({
    data: {
        json: test_array_JSONs,
        keys: {
            value: ['Label 1', 'Label 2', 'Label 3']
        },
        type: 'bar',
        selection: {
            enabled: true,
            multiple: false
        }
    },
    pie: {
        label: {
            show: false
        }
    }
});
*/


/*
var chart = c3.generate({
    bindto: '#chart1',
    data: {
        columns: [
            ['data1', 190, 200, 190, null],
            ['data2', 90, 20, -19, 50],
            ['data3', 490, 300, 490, null],
        ],
    }
});
var chart2 = c3.generate({
    bindto: '#chart2',
    data: {
        columns: [
            ['data1', 190, 200, 190, null],
            ['data2', 90, 20, -19, 50],
            ['data3', 490, 300, 490, null],
        ],
        selection: {
            draggable: true
        }
    }
});
*/


/*
var chart = c3.generate({
   "data":{
      "rows":[
         [ 
            "positive",
            "negative"
         ],
         [
            0.1,
            -0.1
         ],
         [
            0.10000000000000026,
            -0.1
         ]
      ],
      "type":"bar"
   },
   "axis":{
      "x":{
         "type":"category",
         "show":true,
         "categories":[
            "E_Num_customers[2018] (E2)",
            "E_Num_Seats[2018] (E3)"
         ],
         "tick":{
            "multiline":false
         }
      },
      "rotated":true
   },
   "color":{
      "pattern":[
         "#084594",
         "#2171B5",
         "#4292C6",
         "#6BAED6",
         "#9ECAE1",
         "#C6DBEF",
         "#DEEBF7",
         "#F7FBFF"
      ]
   }
});
*/
/*
var chart = c3.generate({
    data: {
        type: 'area-spline',
        x: 'date',
        url: '/data/c3_test_ms.csv'
//        rows: [
//            ["date", "eth0-rx", "eth0-tx", "eth1-rx", "eth1-tx"],
//            [1417622461000, 37, 2, 68, 33],
//            [1417622522000, 39, 2, 57, 23],
//            [1417622581000, 41, 3, 61, 23],
//        ],
    },
    axis: {
        x : {
            type : 'timeseries',
            tick : {
                format : "%m-%d %H:%M"
            }
        }
    },
    zoom: {
//        enabled: true
    }
});
*/
/*
function generateData() {
    var dataName, columns = [], column, i, j;
    for (i = 0; i < 10; i++) {
        dataName = 'data' + i;
        column = [dataName];
        for (j = 0; j < 36; j++) {
            column.push(Math.random() * i * j);
        }
        columns.push(column);
    }
    return columns;
}

var columns = generateData();

var chart;
//d3.select('#generate').on('click', function () {
    chart = c3.generate({
        data: {
            columns: columns,
//            type: 'pie'
        },
        padding: {
            left: 100
        },
        legend: {
//            show: false
        }
    });
//});
setTimeout(function () {
//    chart = chart.destroy();
}, 2000);
*/


/*
*/

/*
var chartValues = [
  ['Category First Type Of Thing', 2],
  ['Category Second Type Of Thing', 6],
  ['Category Third Type Of Thing', 1],
  ['Category Fourth Type Of Thing', 4],
  ['Category Fifth Type Of Thing', 4],
];

var chart = c3.generate({
    bindto: '#chart',
    legend: {
//        position: 'right'
    },
    data: {
        columns: chartValues,
//        type: 'pie'
    }
});
*/


//c3.chart.internal.fn.getAreaBaseValue = function () {
//    return -100;
//};
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 190, 200, 190, null],
            ['data2', 90, 20, -19, 50],
            ['data3', 490, 300, 490, null],
        ],
        labels: {
// format: function (v, id, i, j) {
//                return v;
//            }
            // it's possible to set for each data
            format: {
                data1: function (v, id, i, j) {
                    return v;
                }
            }
        }
//        type: 'pie'
    }
});
*/



/*
var chart = c3.generate({
    data: {
        x: 'x',
        columns: [
            ['x', '2013-01-01', '2013-02-02', '2013-02-10 aaaaaaaaaaaaa aaaaaaa'],
            ['data1', 25, 2, 45],
            ['data2', 25, 25, 22],
            ['data3', 25, 55, 101]
        ],
        type: 'area',
        groups: [
            ['data1', 'data2', 'data3']
        ]
    },
    axis: {
//        rotated: true,
        x: {
            tick: {
                centered: true
            },
            type: 'category'
        }
    }
});
*/




/*
var chart = c3.generate({
    data: {
        x: 'x',
        columns: [
            ["x", 'a', 'b', 'c', 'd', 'e', 'f'],
            ['sample', 30, 200, 100, 400, 150, 250]
        ]
    },
    axis: {
        x: {
            type: 'category',
  //          categories: ['a', 'b', 'c', 'd', 'e', 'f'],
        }
    },
    grid: {
        x: {
            lines: [{value: 3, text: 'Label 3'}]   // This "works," but probably isn't what you want
//            lines: [{value: 'd', text: 'Label d'}]   // This does not work as of 56d9d450f1
        }
    }
});
*/


/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 190, 200, 190, null],
        ],
        //          type: 'bar',
        labels: {
            format: function (v, id) {
                if (v === null) {
                    return 'Not Applicable';
                }
                return d3.format('$')(v);
            }
        }
    },
    axis: {
        rotated: true
    }
});
*/

/*
var chart = c3.generate({
    data: {
        columns: [
           ['% Women CS', 0.036, 0.095, 0.105, 0.182, 0.121],
//            ['% Women Math', 0.182, 0.077, 0.182, 0.026, 0.097],
//            ['% Women Comp Eng /EE', 0.039, 0.074, 0.032, 0.095, 0.087]
//            ['% Women CS', 36000, 95000, 105000, 182000, 121000],
        ],
        type: 'bar',
        labels: true
    },
    axis : {
        x: {
            type: 'category',
            categories: ['Carleton', 'Alberta', 'Sherbrooke', 'McGill', 'Laval']
        }
    }
});
*/


/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 100, 200, 150, 300, 200, 100, 200, 300, 200, 100],
            ['data2', 400, 500, 250, 700, 300, 400, 100, 800, 600, 200],
        ],
        axes: {
            data1: 'y',
            data2: 'y2'
        }
    },
    axis: {
        y: {
            inner: true
        },
        y2: {
            show: true,
            inner: true,
            label: {
                text: 'Y2 Label',
                position: 'outer-middle'
            }
        }
    }
});
*/


/*
var chart = c3.generate({
    data: {
        x: 'x',
        columns: [
            ['x', new Date()],
            ['data1', 100],
            ['data2', 400]
        ]
    },
    axis: {
        x: {
            type: 'timeseries',
            tick: {
                format: '%Y%m%d'
            }
        }
    }
});
*/

/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 100, 200, 150, 300, 200, 100, 200, 300, 200, 100],
            ['data2', 400, 500, 250, 700, 300, 400, 100, 800, 600, 200],
        ]
    },
    axis: {
      x: {
          extent: [0, 4],
          label: {
              text: 'HogeHoge',
              position: 'outer-center'
          }
      }
    },
//    zoom: {
//      enabled: true,
//    }
    legend: {
        show: false
    }
});
setTimeout(function () {
    chart.focus('data2');
}, 500);
setTimeout(function () {
    chart.revert();
}, 1000);
*/

/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 20, 10, 40, 15, 25],
            ['data3', 150, 120, 110, 140, 115, 125]
        ]
    }
});
setTimeout(function () {
    chart.defocus('data1');
}, 1000);
*/


/*
var chart = c3.generate({
    data: {
        x: 'x',
        columns: [
            [
                'x', 
                '2013-01-01', 
                '2013-01-02', 
                '2013-01-03',
                '2013-01-04', 
                '2013-01-05', 
                '2013-01-06', 
                '2013-01-07', 
                '2013-01-08', 
                '2013-01-09', 
                '2013-01-10', 
            ],
            [
                'data1', 
                230, 
                300, 
                330,
                123,
                350,
                230,
                330,
                123,
                350,
                230,
                350
            ],
        ]
    },
    axis: {
        x: {
            type: 'timeseries',
            tick: {
                format: '%m/%d',
                values: ['2013-01-01', '2013-01-10', '2013-01-20', '2013-02-01', '2013-02-10']
            }
        }
    }
});
 
setTimeout(function () {
    chart.flow({
        columns: [
            [
                'x', 
                '2013-01-11', 
                '2013-01-12', 
                '2013-01-13',
                '2013-01-14', 
                '2013-01-15', 
                '2013-01-16', 
                '2013-01-17', 
                '2013-01-18', 
                '2013-01-19', 
                '2013-01-20', 
                '2013-01-21', 
                '2013-01-22', 
                '2013-01-23',
                '2013-01-24', 
                '2013-01-25', 
                '2013-01-26', 
                '2013-01-27', 
                '2013-01-28', 
                '2013-01-29', 
            ],
            [   
                'data1', 
                230, 
                300, 
                330,
                123,
                350,
                230,
                330,
                123,
                350,
                230,
                230, 
                300, 
                330,
                123,
                350,
                230,
                330,
                123,
                350
            ],
        ],
        duration: 1500,
        to: '2013-01-05'
    });
}, 1000);

setTimeout(function () {
    chart.flow({
        columns: [
            [
                'x', 
                '2013-02-11', 
                '2013-02-12', 
                '2013-02-13',
                '2013-02-14', 
                '2013-02-15', 
                '2013-02-16', 
                '2013-02-17', 
                '2013-02-18', 
                '2013-02-19', 
                '2013-02-20', 
                '2013-02-21', 
                '2013-02-22', 
                '2013-02-23',
                '2013-02-24', 
                '2013-02-25', 
                '2013-02-26', 
                '2013-02-27', 
                '2013-02-28', 
                '2013-02-29', 
            ],
            [   
                'data1', 
                230, 
                300, 
                330,
                123,
                350,
                230,
                330,
                123,
                350,
                230,
                230, 
                300, 
                330,
                123,
                350,
                230,
                330,
                123,
                350
            ],
        ],
        duration: 1500,
        to: '2013-01-15'
    });
}, 4000);
*/



/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30],
            ['data2', 120],
        ],
        type : 'donut',
        onclick: function (d, i) { console.log("onclick", d, i); },
        onmouseover: function (d, i) { console.log("onmouseover", d, i); },
        onmouseout: function (d, i) { console.log("onmouseout", d, i); }
    },
    donut: {
        title: "Iris Petal Width"
    }
});
*/


/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 20, 10, 40, 15, 25]
        ],
        classes: {
            data1: 'additional-data1-class'
        }
    },
    axis: {
        rotated: true,
        x: {
            categories: [
                'some long long long long long category name 1',
                'some long long long long long category name 2'],
            type: 'category'
        }
    },
    interaction: {
        enabled: false
    }
});
*/


/*
var data = [
    ['x', generateRandomAxis(), generateRandomAxis()],
    ['data1', generateRandomNumber(), generateRandomNumber()],
    ['data2', generateRandomNumber(), generateRandomNumber()]
];
console.log(data);
var chart = c3.generate({
    data: {
        x: 'x',
        columns: data,
    },
    axis: {
        x: {
            type: 'category'
        }
    }
});
function generateRandomAxis() {
    var words = ['Rock', 'Paper', 'Scissor'];
    return words[Math.floor(Math.random()*words.length)];
}
function generateRandomNumber() {
    return Math.floor((Math.random() * 10) + 1);
}

setTimeout(function () {
    chart.load({
        columns: [
            ['x', 'new 1', 'long long long long long long long long long long long long long long long long long long long long long long long long long long long long '],
            ['data1', generateRandomNumber(), generateRandomNumber()],
            ['data2', generateRandomNumber(), generateRandomNumber()]
        ]
    });
}, 1000);
setTimeout(function () {
    chart.load({
        columns: [
            ['x', 'HOGE 1', 'HOGE 2'],
            ['data1', generateRandomNumber(), generateRandomNumber()],
            ['data2', generateRandomNumber(), generateRandomNumber()]
        ]
    });
}, 2000);
*/



/*
function generateData(name, n) {
    var column = [name];
    for (var i = 0; i < n; i++) {
        column.push(Math.random() * 500);
    }
    return column;
}
function generateTime(name, n) {
    var column = [name],
        now = new Date();
    for (var i = 0; i < n; i++) {
        column.push(+now + 1000*3600*i);
    }
    return column;
}
var n = 10;
var chart = c3.generate({
    data: {
        oninit: function () {
            this.hideLegend('data2');
        },
        x: 'x',
        columns: [
            generateTime("x", n),
            generateData("data1", n),
            generateData("data2", n),
//            generateData("data3", 2000),
//            generateData("data4", 2000),
//            generateData("data5", 2000)
        ],
//        labels: true,
        ys: {
//            data2: 'y2'
        }
//        hide: 'data2'
    },
    legend: {
        hide: true
    },
    padding: {
//        left: 50,
//        right: 50
    },
    axis: {
        x: {
            type: 'timeseries',
            tick: {
//                multiline: false,
//                format: "%Y%m%d %H:%M:%S",
                format: function () {
                    return "very long tick text on x axis";
                },
//                rotate: 60,
//                width: 30
//                fit: false,
//                count: 3
            }
//            default: [30, 60]
        },
    },
    point: {
        show :false
    },
    transition: {
        duration: 0
    },
//    zoom: {
//        enabled: true,
//        onzoomstart: function (event) {
//            console.log("onzoomstart", event);
//        },
//        onzoomend: function (domain) {
//            console.log("onzoomend", domain);
//        },
//        rescale: true
//    },
//    subchart: {
//        show: true
//    }
});
*/

/*
function generate() {
    var start = new Date().getTime();

for (var i = 0; i < 100; i++) {
    var chart = c3.generate({
        data: {
            json: [{
                date: '2014-01-01',
                upload: 200,
                download: 200,
                total: 400
            }, {
                date: '2014-01-02',
                upload: 100,
                download: 300,
                total: 400
            }, {
                date: '2014-02-01',
                upload: 300,
                download: 200,
                total: 500
            }, {
                date: '2014-02-02',
                upload: 400,
                download: 100,
                total: 500
            }],
            keys: {
                x: 'date',
                value: ['upload', 'download']
            }
        },
        axis: {
            x: {
                type: 'timeseries',
                tick: {
                    format: function (x) {
                        if (x.getDate() === 1) {
                            return x.toLocaleDateString();
                        }
                    }
                }
            }
        },
        padding: {
            left: 100
        },
        size: {
            width: 720,
            height: 480
        }
    });
}
var end = new Date().getTime();
var time = end - start;
console.log(time);
}
*/






/*
setTimeout(function () {
    chart.load({
        columns: [
            generateTime("x", 10),
            generateData("data1", 10),
            generateData("data2", 10),
        ]
    });
}, 1000);
*/
/*
// graph shows some data.
var chart = c3.generate({
    data: {
        json: [
            {
                x: '2013-01-01',
                data1: 30
            }
        ],
        keys: {
            x: 'x',
            value: ["data1"]
        },
        type: 'line',
    },
    bar: {
        width: 2
    },
    subchart: {
        show: true
    },
    zoom: {
        enabled: true,
        rescale: true
    },
    grid: {
        y: {
            show: true,
//            ticks: 6
        }
    },
    point: {
        show: false
    },
    transition: {
        duration: 0
    },
    legend: {
        show: false
    },
    axis: {
        x: {
            type: 'timeseries',
            tick: {
                format: '%Y-%m-%d'
            }
        },
        y: {
            tick: {
//                count: 6
            }
        }
    },
    padding: {
        bottom: 100
    }
});

// load some new data
function update() {
//    chart.internal.config.axis_y_min = 0;
//    chart.internal.config.axis_y_max = 2000;
    chart.internal.config.axis_y_tick_values = [-250, 0, 250, 500, 750, 2000];
    chart.load({ // does a redraw
        json: [{
            x: '2013-01-01',
            data1: 30
        }, {
            x: '2013-01-02',
            data1: 200
        }, {
            x: '2013-01-03',
            data1: 100
        }, {
            x: '2013-01-04',
            data1: 320
        }, {
            x: '2013-01-05',
            data1: 1500
        }, {
            x: '2013-01-06',
            data1: 250
        }, {
            x: '2013-01-07',
            data1: -10
        }, {
            x: '2013-01-08',
            data1: 750
        }],
        keys: {
            x: 'x',
            value: ['data1']
        }
    });
};

setTimeout(function () {
    update();
//    chart.flush();
}, 500);
*/




/*
var chart = c3.generate({
    data: {
        json: [

        ]
//        columns: [
//            ['data1']
//        ]
    },
    padding: {
        top: 10
    }
});
*/






/*
var newNoteLine, chart = c3.generate({
    axis: {
        x: {
//            type: 'timeseries'
        }
    },
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 130, 340, 200, 500, 250, 350]
        ],
        type: 'area',
//        types: {
//            data1: 'area',
//            data2: 'area'
//        },
        groups: [
//            ['data1', 'data2']
        ],
        selection: {
//            enabled: true
        },
        onclick: function (d) {
            if (newNoteLine) {
                chart.xgrids.remove({ value: newNoteLine.value });
            }
            newNoteLine = { value: d.x, class: 'new-note-line' };
            setTimeout(function () {
                chart.xgrids.add(newNoteLine);
            }, 500);
        }
    },
    zoom: {
        enabled: true
    }
});
*/

/*
var chart = c3.generate({
    data: {
        columns: [
            ['sample1', 30, 200, 0, 250, 3150, 250],
            ['sample2', 130, 100, 200, 400, 6500, 250]
        ],
//        type: 'bar'
    },
    zoom: {
//        rescale: true
    },
    subchart: {
//        show: true
    },
    legend: {
        position: 'right'
    },
    axis: {
        x: {
//            extent: [0,2]
        },
        y: {
            min: 0,
            padding: {
                bottom: 0
            }
        }
    },
});
*/




/*
var padding = {}, types = {}, chart, generate = function () { return c3.generate({
    data: {
        columns: [
            ['data1'],
            ['data2'],
        ],
        types: types,
        labels: true
    },
    bar: {
        width: 10
    },
    axis: {
        x: {
            padding: padding
        },
        y: {
        }
    },
    grid: {
        x: {
            show: true,
            lines: [{value: 3, text:'Label 3'}, {value: 4.5, text: 'Label 4.5'}]
        },
        y: {
            show: true
        }
    },
    regions: [
        {start:2, end:4, class:'region1'},
        {start:100, end:200, axis:'y'},
    ],
});
};

chart = generate();

setTimeout(function () {
    // Load only one data
    chart.flow({
        rows: [
            ['data1', 'data2', 'data3'],
            [500, 100, 200],
            [200, null, null],
            [10000, 50, null] 
        ],
        duration: 1500,
    });
}, 1000);
*/

/*
var chart = c3.generate({
    data: {
        columns: [
            ['sample1', 30, 200, 0, 250, 3150, 250],
            ['sample2', 130, 100, 200, 400, 6500, 250]
        ],
//        type: 'bar'
    },
    zoom: {
        rescale: true
    },
    subchart: {
        show: true
    },
    axis: {
        x: {
            extent: [0,2]
        }
    }
});
*/


/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30, 200, null, 400, 150, 250],
            ['data2', 130, 100, 140, 200, 150, 50]
        ],
        type: 'area',
        onclick: function () {
            console.log('onclick from data');
        }
    },
    area: {
        zerobased: false
    },
    zoom: {
//        enabled: true
    },
    onmouseover: function () {
        console.log("mouseover");
    },
    onmouseout: function () {
        console.log("mouseout");
    },
    size: {
        width: 350
    }
});
*/





/*
var chart = c3.generate({
    data: {
        columns: [
            ["times", 60000, 120000, 180000, 240000]
        ]
    },
    axis: {
        y: {
            type : 'timeseries',
            tick: {
                values: null,
                count: undefined,
            },
            time : {
//                value : 'seconds',
//                interval : 60
            }
        }
    }
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['sample1', 30, 200, 0, 250, 150, 250],
            ['sample2', 430, 300, 500, 400, 650, 250]
        ],
        onclick: function (d) {
            alert("id =>" + d.id + ", value =>" + d.value);
        }
//        type: 'bar'
    },
    zoom: {
        rescale: true
    },
    subchart: {
        show: true
    }
});
*/
/*
c3.generate({
    "data":{
        "empty":{
            "label":{
                "text":"No hay datos para mostrar en las fechas seleccionadas"
            }
        },
        "x":"fecha",
        "xFormat":"%Y-%m-%dT%H:%M:%S%Z",
        "type":"bar",
        "labels":true,
        "columns":[
            [
                "fecha",
                "2014-04-10T00:00:00-0500",
                "2014-04-12T00:00:00-0500",
                "2014-05-10T00:00:00-0500"
            ],
            [
                "b85b2dfb-6863-11e4-92a5-1867b083cd22",
                76,
                0,
                0
            ],
            [
                "b85b13c8-6863-11e4-92a5-1867b083cd22",
                25,
                3,
                30
            ]
        ],
        "names":{
            "b85b2dfb-6863-11e4-92a5-1867b083cd22":"Desayunos",
            "b85b13c8-6863-11e4-92a5-1867b083cd22":"Almuerzos"
        }
    },
    bar: {
//        width: { max: 10 }
        width: 10
    },
    "axis":{
        "x":{
            "type":"timeseries",
            "tick":{
                "format":"%d/%m/%Y",
                fit: false
            }
        }
    }
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data', 95.0]
        ],
        type: 'gauge',
    },
    gauge: {
        min: 0,
        max: 100
    },
    color: {
        
    },
    
});
*/

/*
c3.chart.internal.fn.redrawCircle = function () {};
c3.chart.internal.fn.addTransitionForCircle = function () {};
var chart = c3.generate({
    data: {
        columns: [
            ['sample1', 30, 200, 0, 250, 150, 250],
            ['sample2', 430, 300, 500, 400, 650, 250]
        ],
        onclick: function (d) {
            alert("id =>" + d.id + ", value =>" + d.value);
        },
//        type: 'bar'
    },
});
*/

/*
c3.generate({
    bindto: '#chart',
    data: {
//        type: 'bar',
        columns: [
            ["data1", 1251400, 1445000, 1721200, 1406600, 1581600, 1833400, 1220200, 1448400, 1643800, 1436400, 1381800, 1284400],
            ["data2", 161600, 219600, 462000, 323800, 436000, 442000, 142600, 283600, 354600, 71800, 230600, 281400]
        ]
    },
    grid: {
        y: {
            show: true,
        }
    },
    axis: {
        y: {
            tick: {
                count: 5
            }
        }
    }
});
*/
/*
var xaxis= new Array();
for (var i=0;i<14;i++){
    xaxis[i]=2000+i;
}

var chart = c3.generate({
    data: {
        x: 'xaxis',
        json: {
            xaxis: xaxis,
            data2: [200, 130, 90, 240, 130, 220],
            data3: [300, 200, 160, 400, 250, 250],
            data4: [100, 430, 140, 300, 220, 210]
        }
    },
    axis: {
        y: {
            label: 'Y Label'
        }
    }
});
*/

/*
var chart = c3.generate({
    data: {
        x: 'date',
        columns: [
//            ['date', '2014-01-01', '2014-01-10', '2014-01-20', '2014-01-30', '2014-02-01'],
//            ['sample', 30, 200, 100, 400, 150, 250]
            ['date'],
            ['sample']
        ]
    },
    axis: {
//        rotated: true,
        x: {
            type: 'timeseries',
            tick: {
                format: '%Y%m%d %H:%M:%S'
            }
        },
        y2: {
            //            show: true,
        }
    },
    regions: [
//        {start: '2014-01-05', end: '2014-01-10'},
//        {start: new Date('2014-01-10'), end: new Date('2014-01-15')},
//        {start: 1390608000000, end: 1391040000000}
    ]
});

setTimeout(function () {
    chart.load({
        columns: [
            ['date', 1415530121836, 1415530122836, 1415530123836],
            ['sample', 1, 2, 3]
        ]
    });

    chart.regions([
        {start: 1415530121836, end: 1415530122836}
    ]);

//    chart.load({
//        columns: [
//            ['date', +new Date('2014-01-01'), +new Date('2014-01-10'), +new Date('2014-03-01')],
//            ['sample', 1, 2, 3]
//        ]
//    });
//    chart.regions([
//        {start: +new Date('2014-01-10'), end: +new Date('2014-01-15')}
//    ]);

}, 500);
*/


/*
var data = {"x":["x","(No store found for this ID)","Preston","Moorabbin","Smith Street","Croydon","Head Office","Brisbane","Essendon","Spencer","Jindalee","Torquay","Canberra","(No store found for this ID)","Spare Store2","Gold Coast HT","South Wharf","Adelaide HT","Melbourne Central","Highpoint","Prahran","Prahran Reserve"],"y":["total",405,131,203,157,139,129,90,74,59,86,68,67,69,67,62,50,43,50,32,20,7]}; 

window.chart = c3.generate({
    size: {
        height: 500
    },
    data: {
        x: "x",
        columns: [
            data.x,
            data.y
        ],
        type: 'bar'
    },
    axis: {
        x: {
            type: 'category',
            tick: {
                rotate: 60,
                multiline: false
            },
//            height: 200
        }
    },
    grid: {
        y: {
            show: true
        }
    },
    legend: {
        show: false
    }
});
*/
/*
c3.generate({
    bindto: '#chart',
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 20, 10, 40, 15, 25]
        ]
    },
    grid: {
        y: {
            show: true,
//            ticks: 5
        }
    },
    axis: {
        y: {
            tick: {
//                count: 3
                values: [100, 200]
            }
        },
        x: {
            tick: {
                count: 3
            }
        }
    }
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 130, 100, 140, 200, 150, 50]
        ],
        type: 'line'
    },
    zoom: {
        enabled: true
    }
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 300, 350, 300, 0, 0, 120],
            ['data2', 130, 100, 140, 200, 150, 50],
            ['data3', 130, 100, 140, 200, 150, 50]
        ],
        types: {
            data1: 'area',
            data2: 'area',
            data3: 'area'
            // 'line', 'spline', 'step', 'area', 'area-step' are also available to stack
        },
        groups: [['data1', 'data2', 'data3']],
//        order: 'asc'
//        order: function (t1, t2) { return t1.id < t2.id; }
    },
    legend: {
//        position: 'inset'
    },
    axis: {
        x: {
            tick: {
                format: function () {
//                    return ["aaaaa", "1111"]
                    return "aaaaaaaaaaabbbbbbbccccccccc"
                }
            }
        }
    }
});
setTimeout(function () {
    chart.load({
        json: {
            data4: [130, 220, 350, 140, 360, 250, 100, 300]
        }
    });
}, 500);

setTimeout(function () {
    chart.unload('data2');
}, 1000);
*/
/*
c3.generate({
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 130, 100, 140, 200, 150, 50]
        ],
        type: 'bar'
    },
    axis: {
        y: {
            tick: {
                count: 3
            }
        }
    }
});
*/
/*
var chart1 = c3.generate({
    data: {
//        x: 'x',
        columns: [
//            ['x', '0123456789 abcdefghijk 99999 999 9999 abbbabbccc', '1e-2', '1', 'hgoehoge', 'a', 'b', 'd', 'c', 'aa', 'b', 'aa', '3000000000000000'],
            ['data1', 30, 200, 100, 400, 150, 250, 50, 100, 250, 100, 20, 10],
            ['data2', 3, 20, 10, 40, 15, 25, 5, 10, 25, 10, 2, 1]
        ]
    },
    axis: {
//       rotated: true,
        x: {
//            type: 'categorized',
            tick: {
                format: function () {
                    return "aaaaaaaaaaaaaaaaaaaaaaaa";
                }
//                width: null,
//                rotate: 60
            }
        },
        y2: {
            show: true
        }
    },
    padding: {
//        bottom: 50 
    },
    size: {
//        height: 800
    }
});
*/

/*
var chart = c3.generate({
    bindto: null,
    data: {
        columns: [
            ['data1 hoge', 1030, 1200, 1100, 1400, 1150, 1250],
            ['data2_aaaa', 2130, 2100, 2140, 2200, 2150, 1850],
            ["data3", 2130, 21000, 2140, 22000, 2150, 18500]
        ],
        onclick: function (d) {
            console.log("onclick", d);
        },
        onmouseover: function (d) {
            console.log("onmouseover", d);
        },
        onmouseout: function (d) {
            console.log("onmouseout", d);
        }
    },
    point: {
        show: false
    }
});
*/
/*
var chart1 = c3.generate({
    bindto: '#chart1',
    data: {
        xs: {'data1':'x1','data2':'x2', 'data3':'x3', 'data4':'x4'},
        columns: [
            ['x1', 1, 2, 3, 4, 5, 6],
            ['x2', 1, 2, 3, 4.5, 5, 7],
            ['x3', 1, 2, 3, 4, 5, 6],
            ['x4', 1, 2, 3.5, 4, 5.5, 6],
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 90, 40, 140, 150, 55],
            ['data3', 50, 90, 40, 140, 150, 55],
            ['data4', 50, 90, 40, 140, 150, 55]
        ],
        groups: [['data1','data3']],
        onclick: function (d) { 
            console.log('onclick', d);  ///////// SHOULD WORK, BUT DOESN'T..
        },
        onmouseover: function (d) {
            console.log('onmouseover', d);
        },
        onmouseout: function (d) {
            console.log('onmouseout', d);
        },
        type: "bar",
        types: {
            data4: 'line'
        }
    }
});
*/
/*
var chart2 = c3.generate({
    bindto: '#chart2',
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 90, 40, 140, 150, 55]
        ],
        groups: [['data1','data2']],
        onclick: function (d) { 
            console.log('click! ', d);  ///////// SHOULD WORK...
        },
        types: {"data1":"bar","data2":"bar"}
    }
});
*/
/*
// 662
var data = [
    [ "watch", 5 ],
    [ "def", 4 ],
    [ "ghi", 4 ],
    [ "jkl", 3 ],
    [ "mno", 3 ]
];
var chart = c3.generate({
    bindto: '#chart',
    data: {
        columns: data,
        type: 'donut'
    },
    color: {
        pattern: ["#a2d47f", "#ffb377", "#ff7676", "#99e0fc", "#e7acdc"]
    },
    transition: {
        duration: 500
    },
    legend: {
        show: true
    }
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 3000, 20000, 10000, 40000, -15000, 2500],
        ],
        type: 'bar'
    },
    axis: {
        y: {
            max: 100000,
            min: 10000
        }
    },
    bar: {
        zerobased: false
    },
    zoom: {
//        enabled: true
    },
    subchart: {
        show: true
    }
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['x1', 10 ,40, 100, 110, 200, 320],
            ['x2', 10 ,40, 100, 210, 400, 1320],
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 20, 10, 40, 15, 25]
        ],
        xs: {
            data1: 'x1',
            data2: 'x2'
        }
    },
    tooltip: {
        grouped: false
    },
    point: {
        show: false
    }
});
*/
/*
function generateData(n) {
    var column = ['sample'];
    for (var i = 0; i < n; i++) {
//        column.push(Math.random() * 500);
        column.push(i * 500);
    }
    return column;
}
var chart1 = c3.generate({
    data: {
        columns: [
            generateData(100)
        ]
    },
    axis: {
        x: {
            default: [30, 60]
        }
    },
    zoom: {
        enabled: true,
//        rescale: true,
        onzoomstart: function (event) {
            console.log("onzoomstart", event);
        },
        onzoom: function (domain) {
            console.log("onzoom", domain);
        },
        onzoomend: function (domain) {
            console.log("onzoomend", domain);
        },
    },
    subchart: { show: true }
});
*/

/*
var chart1 = c3.generate({
    bindto: '.' + $('#chart').attr('class'),
    data: {
        x: 'x',
        columns: [
            ['x', '1e-3', '1e-2', '1'],
            ['data1', 50, 200, 100, 400, 150, 250, 50, 100, 250],
            ['data2', 50, 200, 100, 400, 150, 250, 50, 100, 250]
        ],
//        type: 'bar',
        groups: [['data1', 'data2']],
        onclick: function (d) {
            console.log("onclick", d);
        },
        onmouseout: function (d) {
            console.log('onmouseout', d);
        }
    },
    axis: {
        x: {
            type: 'categorized'
        }
    }
});
*/
/*
setTimeout(function () {
    chart1.destroy();
}, 1000);
*/
/*
var chart = c3.generate({
    bindto: '#chart1',
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250]
        ],
        type: 'step'
    },
    grid: {
        x: {
            show: true,
            lines: [{
                value: 2,
                text: 'Label 2',
                class: 'lineFor2'
            }]
        },
        y: {
            show: true,
        }
    }
});
*/
/*
      var option = {
        padding: {
          top: 50,
          right: 200,
          bottom: 50,
          left: 200,
        },
        data: {
          columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 130, 100, 200, 100, 150, 150]
          ],
          axes: {
            data2: 'y2'
          },
        },
        axis: {
          y: {
            label: {
              text: 'Y Label', 
              position: 'outer-center'
            }
          },
          y2: {
            show: true,
            label: {
              text: 'Y2 Label',
              position: 'outer-center'
            }
          }
        },
        legend: {
          position: 'bottom'
        },
        subchart: {
          show: false
        },
        grid: {
          x: {
            show: true,
          },
          y: {
            show: true,
          }
        }
      };
      var chart1 = c3.generate(option);
option.bindto = '#chart1';
option.data.columns[0][1] = 20000;
      var chart2 = c3.generate(option);
*/

/*
var chart = c3.generate({
    data: {
        columns: [
            [ 'data', 91.4 ]
        ],
        type: 'gauge',
        onmouseover: function (d, i) { console.log("onmouseover", d, i, this); },
        onmouseout: function (d, i) { console.log("onmouseout", d, i, this); },
        onclick: function (d, i) { console.log("onclick", d, i, this); },
    },
    gauge: {
        label: {
//            format: function(value, ratio) {
//              return value;
//            },
//          show: false // to turn off the min/max labels.
        },
//          min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
//          max: 100, // 100 is default
//          units: ' %',
//          width: 39 // for adjusting arc thickness
    },
    color: {
            pattern: ['#FF0000', '#F6C600', '#60B044'], // the three color levels for the percentage values.
        threshold: {
//            unit: 'value', // percentage is default
//            max: 200, // 100 is default
            values: [30, 60, 90] // alternate first value is 'value'
        }
    }
});

var ticks = d3.select('.c3-chart-arcs')
        .append('g')
      .selectAll("line")
        .data([0,25,50,75,100]).enter()
      .append("line")
        .attr("x1", 0)
        .attr("x2", 0)
        .attr("y1", -chart.internal.radius+8)
        .attr("y2", -chart.internal.radius)
        .attr("transform", function (d) {
            var min = chart.internal.config.gauge_min,
                max = chart.internal.config.gauge_max,
                ratio = d / (max - min),
                rotate = (d - 2) * 45;
            return "rotate(" + rotate + ")";
        });
*/
/*
var date = ["Data e Ora","2014-05-20 17:25:00.123", "2014-05-20 17:30:00.345", "2014-05-20 17:35:00.456"]; // SORTED

var valori = ["Inverter","4883","5079","5321"];

var chart = c3.generate({
    data: {
        x: 'Data e Ora',
        xFormat: '%Y-%m-%d %H:%M:%S.%L', // ADDED
        columns: [
            date,
            valori,
        ],              
        type: 'spline'
    },  
    axis: {
        x: {
            type: 'timeseries',
            tick: {
                format: '%H:%M:%S.%L'
            },
        },
    },
    transition: {
        duration: 1000
    }
});
*/
/*
var columns = [
    ['data1', 100, 200, 150, 300, 1200],
    ['data2', 400, 500, 250, 700, 300]
];
var chart = c3.generate({
    data: {
        columns: columns,
        type: 'bar',
        color: function (color, d) {
            if (typeof d.index === 'undefined') { return color; }
            return columns[0][d.index + 1] > columns[1][d.index + 1] ? '#F00' : '#00F';
        }
    }
});
*/
/*
var chart = c3.generate({
    data: {
        columns:[
            ['data1', 30, 20, 50, 40, 60, 50, 100, 200],
            ['data2', 230, 220, 250, 240, 260, 250, 300, 400],
            ['data3', 130, 220, 350, 140, 360, 250, 100, 300]
        ],
        labels: true,
        axes: {
            data1: 'y',
            data2: 'y',
            data3: 'y2'
        }
    },
    axis: {
        y: {

        },
        y2: {
            show: true
        }
    }
});

setTimeout(function () {
    chart.load({
        json: {
            data4: [123, 32, 43, 654, 123, 65, 87, 7560]
        },
        axes: {
            data4: 'y2'
        }
    });
}, 1000);
*/
/*
var chart = c3.generate({
    bindto: '#chart',
    data: {
        x: 'x',
        columns: [
            ['x', 0, 1000, 3000, 10000],
            ['data', 10, 10, 10, 10]
//            ['x', 0, 10000],
//            ['data', 10, 10]
        ],
        type: 'bar'
    },
    bar: {
        width: {
            ratio: 0.1 
        }
    }
});
*/
/*
var data = {"hourly":{"x":['x', "2014-09-24T16:00:00.000Z","2014-09-24T17:00:00.000Z","2014-09-24T18:00:00.000Z","2014-09-24T19:00:00.000Z","2014-09-24T20:00:00.000Z","2014-09-24T21:00:00.000Z","2014-09-24T22:00:00.000Z","2014-09-24T23:00:00.000Z","2014-09-25T00:00:00.000Z","2014-09-25T01:00:00.000Z","2014-09-25T02:00:00.000Z","2014-09-25T03:00:00.000Z","2014-09-25T04:00:00.000Z","2014-09-25T05:00:00.000Z","2014-09-25T06:00:00.000Z","2014-09-25T07:00:00.000Z","2014-09-25T08:00:00.000Z","2014-09-25T09:00:00.000Z","2014-09-25T10:00:00.000Z","2014-09-25T11:00:00.000Z","2014-09-25T12:00:00.000Z","2014-09-25T13:00:00.000Z","2014-09-25T14:00:00.000Z","2014-09-25T15:00:00.000Z"],"sold":['Sold Tickets', 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],"revenue":['Revenue', 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]},"daily":{"x":['x', "2014-08-27T00:00:00.000Z","2014-08-28T00:00:00.000Z","2014-08-29T00:00:00.000Z","2014-08-30T00:00:00.000Z","2014-08-31T00:00:00.000Z","2014-09-01T00:00:00.000Z","2014-09-02T00:00:00.000Z","2014-09-03T00:00:00.000Z","2014-09-04T00:00:00.000Z","2014-09-05T00:00:00.000Z","2014-09-06T00:00:00.000Z","2014-09-07T00:00:00.000Z","2014-09-08T00:00:00.000Z","2014-09-09T00:00:00.000Z","2014-09-10T00:00:00.000Z","2014-09-11T00:00:00.000Z","2014-09-12T00:00:00.000Z","2014-09-13T00:00:00.000Z","2014-09-14T00:00:00.000Z","2014-09-15T00:00:00.000Z","2014-09-16T00:00:00.000Z","2014-09-17T00:00:00.000Z","2014-09-18T00:00:00.000Z","2014-09-19T00:00:00.000Z","2014-09-20T00:00:00.000Z","2014-09-21T00:00:00.000Z","2014-09-22T00:00:00.000Z","2014-09-23T00:00:00.000Z","2014-09-24T00:00:00.000Z","2014-09-25T00:00:00.000Z"],"sold":['Sold Tickets', 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],"revenue":['Revenue', 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]},"weekly":{"x":['x', "2014-04-14T00:00:00.000Z","2014-04-21T00:00:00.000Z","2014-04-28T00:00:00.000Z","2014-05-05T00:00:00.000Z","2014-05-12T00:00:00.000Z","2014-05-19T00:00:00.000Z","2014-05-26T00:00:00.000Z","2014-06-02T00:00:00.000Z","2014-06-09T00:00:00.000Z","2014-06-16T00:00:00.000Z","2014-06-23T00:00:00.000Z","2014-06-30T00:00:00.000Z","2014-07-07T00:00:00.000Z","2014-07-14T00:00:00.000Z","2014-07-21T00:00:00.000Z","2014-07-28T00:00:00.000Z","2014-08-04T00:00:00.000Z","2014-08-11T00:00:00.000Z","2014-08-18T00:00:00.000Z","2014-08-25T00:00:00.000Z","2014-09-01T00:00:00.000Z","2014-09-08T00:00:00.000Z","2014-09-15T00:00:00.000Z","2014-09-22T00:00:00.000Z"],"sold":['Sold Tickets', 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0],"revenue":['Revenue', 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,"42.42",0,0,0,0,0,0,0]},"monthly":{"x":['x', "2013-10-01T00:00:00.000Z","2013-11-01T00:00:00.000Z","2013-12-01T00:00:00.000Z","2014-01-01T00:00:00.000Z","2014-02-01T00:00:00.000Z","2014-03-01T00:00:00.000Z","2014-04-01T00:00:00.000Z","2014-05-01T00:00:00.000Z","2014-06-01T00:00:00.000Z","2014-07-01T00:00:00.000Z","2014-08-01T00:00:00.000Z","2014-09-01T00:00:00.000Z"],"sold":['Sold Tickets', 0,0,0,0,0,0,0,0,0,0,1,0],"revenue":['Revenue', 0,0,0,0,0,0,0,0,0,0,"42.42",0]}};

var chart = c3.generate({
  axis: {
    x: {
      type: 'timeseries',
      tick: {
        format: function(x) { return d3.time.format('%b %e')(x); }
      }
    },
    y: {tick: {format: d3.format('d')}},
    y2: {show: true}
  },
  data: {
    x: 'x',
    xFormat: '%Y-%m-%dT%H:%M:%S.%LZ',
    columns: [],
    types: {'Sold Tickets': 'bar', 'Revenue': 'area-spline'},
    axes: {'Revenue': 'y2'},
    colors: {'Sold Tickets': '#a2e1d4', 'Revenue': '#464f88'}
  },
  point: {show: false},
  legend: {
    position: 'inset',
    inset: {step: 2}
  },
  grid: {y: {show: true}}
});
setTimeout(function () {
  chart.load({columns: [data.monthly.x, data.monthly.sold, data.monthly.revenue]});
}, 1000);
setTimeout(function () {
  chart.load({columns: [data.weekly.x, data.weekly.sold, data.weekly.revenue]});
}, 2000);
setTimeout(function () {
  chart.load({columns: [data.daily.x, data.daily.sold, data.daily.revenue]});
}, 3000);
setTimeout(function () {
  chart.load({columns: [data.monthly.x, data.monthly.sold, data.monthly.revenue]});
}, 4000);
*/
/*
// 590
c3.generate({
    bindto: '#chart1',
    data: {
        columns: [
            ['data1', 1, 3, 2, 3, 6, 1],
            ['data2', 1, 4, 4, 3, 2, 5]
        ],
        type: 'bar'
    },
    axis : {
        y : {
            tick : {
                format: function(d) {
                     return "xx xxxxx xxxxxxxx";   
                }
            },
            label: {
                text: "test",
                position: "outer-middle"
            }
        }
    }
});
c3.generate({
    bindto: '#chart2',
    data: {
        columns: [
            ['data3', 2, 4, 1, 1, 0, 1],
            ['data4', 4, 2, 3, 1, 5, 5]
        ],
        type: 'bar'
    },
    axis : {
        y : {
            tick : {
                format: function(d) {
                     return d;   
                }
            },
            label: {
                text: "test",
                position: "outer-middle"
            }
        }
    }
});
c3.generate({
    bindto: '#chart3',
    data: {
        columns: [
            ['data3', 1, 1,7, 8, 8, 2],
            ['data4', 4, 4, 2, 3,1, 8]
        ],
        type: 'bar'
    },
    axis : {
        y : {
            tick : {
                format: function(d) {
                     return "xxxxxxxxxx";   
                }
            },
            label: {
                text: "test",
                position: "outer-middle"
            }
        }
    } 
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['sample', 30, 200, 100, 400, 150, 250]
        ],
        type: 'scatter'
    },
    subchart: {
        show: true
    }
});
*/
/*
var chart = c3.generate({
//    size: { width: 300, height: 300 },
    data: {
        columns: [
            ['data1', 100, 300],
            ['data2', 200, 500]
        ],
        type: 'area'
    },
    axis: {
        x: {
//            show: false
        }
    },
    legend: {
        position: "inset",
        inset: {
            anchor: "top-left",
            x: 10,
            y: 10,
            step: 1
        }
    }
});
*/
/*
// 557
var jsonfile = [{
      "xvariable": "100",
      "valuevariable": "500"
    }, {
      "xvariable": "200",
      "valuevariable": "1500"
    }, {
      "xvariable": "300",
      "valuevariable": "3500"
    }];

var differentjsonfile = [{
      "xvariable": "200",
      "valuevariable": "3500"
    }, {
      "xvariable": "300",
      "valuevariable": "4500"
    }, {
      "xvariable": "400",
      "valuevariable": "5500"
    }];

var chart_scatterplot = c3.generate({
    data: {
        json: jsonfile,
        keys: {
            x: 'xvariable',
            value: ['valuevariable'],
        },
        type: 'scatter',
    },
});

setTimeout(function () {
    chart_scatterplot.load({
        json: jsonfile.concat(differentjsonfile),
        keys: {
            x: 'xvariable',
            value: ['valuevariable'],
        },
    });
}, 1000);
*/
/*
var chart = c3.generate({
    data: {
        xs: {
            setosa: 'setosa_x',
            versicolor: 'versicolor_x',
            virginica: 'virginica_x'
        },
        columns: [
            ["setosa_x", 3.5, 3.0, 3.2, 3.1, 3.6, 3.9, 3.4, 3.4, 2.9, 3.1, 3.7, 3.4, 3.0, 3.0, 4.0, 4.4, 3.9, 3.5, 3.8, 3.8, 3.4, 3.7, 3.6, 3.3, 3.4, 3.0, 3.4, 3.5, 3.4, 3.2, 3.1, 3.4, 4.1, 4.2, 3.1, 3.2, 3.5, 3.6, 3.0, 3.4, 3.5, 2.3, 3.2, 3.5, 3.8, 3.0, 3.8, 3.2, 3.7, 3.3],
            ["versicolor_x", 3.2, 3.2, 3.1, 2.3, 2.8, 2.8, 3.3, 2.4, 2.9, 2.7, 2.0, 3.0, 2.2, 2.9, 2.9, 3.1, 3.0, 2.7, 2.2, 2.5, 3.2, 2.8, 2.5, 2.8, 2.9, 3.0, 2.8, 3.0, 2.9, 2.6, 2.4, 2.4, 2.7, 2.7, 3.0, 3.4, 3.1, 2.3, 3.0, 2.5, 2.6, 3.0, 2.6, 2.3, 2.7, 3.0, 2.9, 2.9, 2.5, 2.8],
            ["virginica_x", 3.3, 2.7, 3.0, 2.9, 3.0, 3.0, 2.5, 2.9, 2.5, 3.6, 3.2, 2.7, 3.0, 2.5, 2.8, 3.2, 3.0, 3.8, 2.6, 2.2, 3.2, 2.8, 2.8, 2.7, 3.3, 3.2, 2.8, 3.0, 2.8, 3.0, 2.8, 3.8, 2.8, 2.8, 2.6, 3.0, 3.4, 3.1, 3.0, 3.1, 3.1, 3.1, 2.7, 3.2, 3.3, 3.0, 2.5, 3.0, 3.4, 3.0],
            ["setosa", 0.2, 0.2, 0.2, 0.2, 0.2, 0.4, 0.3, 0.2, 0.2, 0.1, 0.2, 0.2, 0.1, 0.1, 0.2, 0.4, 0.4, 0.3, 0.3, 0.3, 0.2, 0.4, 0.2, 0.5, 0.2, 0.2, 0.4, 0.2, 0.2, 0.2, 0.2, 0.4, 0.1, 0.2, 0.2, 0.2, 0.2, 0.1, 0.2, 0.2, 0.3, 0.3, 0.2, 0.6, 0.4, 0.3, 0.2, 0.2, 0.2, 0.2],
            ["versicolor", 1.4, 1.5, 1.5, 1.3, 1.5, 1.3, 1.6, 1.0, 1.3, 1.4, 1.0, 1.5, 1.0, 1.4, 1.3, 1.4, 1.5, 1.0, 1.5, 1.1, 1.8, 1.3, 1.5, 1.2, 1.3, 1.4, 1.4, 1.7, 1.5, 1.0, 1.1, 1.0, 1.2, 1.6, 1.5, 1.6, 1.5, 1.3, 1.3, 1.3, 1.2, 1.4, 1.2, 1.0, 1.3, 1.2, 1.3, 1.3, 1.1, 1.3],
            ["virginica", 2.5, 1.9, 2.1, 1.8, 2.2, 2.1, 1.7, 1.8, 1.8, 2.5, 2.0, 1.9, 2.1, 2.0, 2.4, 2.3, 1.8, 2.2, 2.3, 1.5, 2.3, 2.0, 2.0, 1.8, 2.1, 1.8, 1.8, 1.8, 2.1, 1.6, 1.9, 2.0, 2.2, 1.5, 1.4, 2.3, 2.4, 1.8, 1.8, 2.1, 2.4, 2.3, 1.9, 2.3, 2.5, 2.3, 1.9, 2.0, 2.3, 1.8]
        ],
        type : 'scatter',
    },
    point: {
        r: function (d) { 
            console.log(d);
            return d.value * 10;
        }
    },
    axis: {
        x: {
            label: 'Sepal.Width',
            tick: {
                fit: false
            }
        },
        y: {
            label: 'Petal.Width'
        }
    }
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1',1,2,3],
            ['data2',2,3,4]
        ],
        types: {
            data1: 'area-spline',
            data2: 'area-spline'
        },
        onclick: function (d, i) { console.log("onclick", d, i); },
        onmouseover: function (d, i) { console.log("onmouseover", d, i); },
        onmouseout: function (d, i) { console.log("onmouseout", d, i); }
    },
//    point: {show: false}
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 1],
            ['data2', 2],
            ['data3', 3],
            ['data4', 4],
            ['data5', 5],
            ['data40', 40]
        ],
        type: 'bar',
        onclick: function (d, element) {
            console.log("onclick", d, element);
        },
    },
    tooltip: {
        grouped : false
    }
});
*/
/*
// want to get name in callback
var chart = c3.generate({
    data: {
        json: [
            { id: 1, name: 'abc', visits: 1200 },
            { id: 2, name: 'efg', visits: 900 },
            { id: 3, name: 'pqr', visits: 1150 },
            { id: 4, name: 'xyz', visits: 1020 }
        ],
        keys: {
            x: 'id',
            value: ['visits']
        }
    }
});
*/
/*
var chart = c3.generate({
    bindto: '#chart',
    data: {
      x : 'x',
      columns: [
            ['x', '2013-01-01', '2013-01-02', '2013-01-03', '2013-01-04', '2013-01-05', '2013-01-06'],
            ['sample', 30, 200, 100, 400, 150, 250],
            ['sample2', 130, 300, 200, 450, 250, 350]
      ]
    },
    axis : {
//      rotated: true,
      x : {
        type : 'timeseries',
        tick : {
            format : "%e %b %y 01234567890123456789"
        }
      }
    },
    padding: {
      right: 100,
      left: 100
    }
});
*/
//d3.select('.c3-axis.c3-axis-x').attr('clip-path', "")
/*
chart = c3.generate({
    data: {
        x: 'date',
        xFormat: '%Y-%m-%d %H:%M:%S',
        json: [{
            "date": "2014-06-03 12:00:00",
            "data1": "3000",
            "data2": "500"
        }, {
            "date": "2014-06-04 12:00:00",
            "data1": "1000"
        }, {
            "date": "2014-06-05 12:00:00",
            "data1": "5000",
            "data2": "1000"
        }],
        keys: {
            x: 'date',
            value: [ "data1", "data2" ]
        },
        types: {
            data1: 'step',
            data2: 'bar'
        }
    },
    axis: {
        x: {
            type: "category"
        }
    }
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 20, 10, 40, 15, 25]
        ]
    },
    axis: {
        y: {
            min:500
//            max: 0
        }
    }
});
*/
/*
var drdata = [
    ["x", "John", "Fred", "Mary", "You", "Me", "Tom", "Jim"],
    ["Promises not kept", -50, 63, 90, 55, 60, 63, 52]
];

var chart = c3.generate({
    bindto: '#chart',
    data: {
        x: 'x',
        columns: drdata,
        type: 'bar',
        onclick: function (d, i) {
            console.log(d, i, d.index);
        },
    },

    axis: {
        rotated: true,
        x: {
            type: 'category' // this needed to load string x value
        },
        y: {
            max: 100,
            min: -100,
        }
    },
    bar: {
        width: {
            ratio: 0.8 // this makes bar width 50% of length between ticks
        }
        // or
        //width: 100 // this makes bar width 100px
    },
    color: {
        pattern: ['orangered', 'royalblue']
    },
});
*/

/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 1, 2, 1, 3, 4],
//            ['data3', 40000, 50000, 25000, 70010, 30000],
            ['data2', 4000000000, 5000000000, 2500000000, 7001000000, 3000000000]
        ]
    }
});
*/
/*
// X axis in a scatterplot ignores range minimum of zero #457
var chart = c3.generate({
    data: {
        xs: {
            'x1': 'data1',
        },
        columns: [
            ["data1", 5],
            ["x1", 7],
        ],
        type: "scatter",
    },
    point: { 
         r: 10
    },
    axis: {          
        x: {
            min: 0,
        },
        y: {
            min: 0,
        }
    }
});
*/
/*
var chart1 = c3.generate({
    bindto: "#chart1",
    data: {
        columns: [
            ['data1', 100, 200, 150, 300, 200],
            ['data2', 400, 500, 250, 700, 300], ]
    }
});

var chart2 = c3.generate({
    bindto: "#chart2",
    data: {
        columns: [
            ['data1', 100, 200, 150, 300, 200],
            ['data2', 400, 500, 250, 700, 300],
        ],
        labels: true
    }
});
*/

/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 20, 10, 40, 15, 25],
            ['data3', 350, 320, 310, 340, 315, 325],
            ['data4', 550, 520, 510, 540, 515, 525]
        ]
    }
});
*/
/*
setTimeout(function () {
    chart.hide();
}, 1000);

setTimeout(function () {
    chart.show();
}, 2000);
*/

/*
// Scatter chart with only one point #548
var chart = c3.generate({
    data: {
        xs: {
            setosa: 'setosa_x'
        },
        // iris data from R
        columns: [
            ["setosa_x", 3.5],
            ["setosa", 0.2]
        ],
//        type: 'scatter'
    },
    axis: {
        x: {
            label: 'Sepal.Width',
            tick: {
                fit: false
            }
        },
        y: {
            label: 'Petal.Width'
        }
    }
});
*/
/*
//Animate left-to-right on x-axis #502
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 100, 200, 150, 300, 200],
            ['data2', 400, 500, 250, 700, 300]
        ],
        type: 'area'
    },
    grid: {
        x: {
            show: true
        }
    },
    oninit: function () {
        this.clipChart
            .attr('width', 0)
            .attr('height', this.height)
          .transition().duration(1000)
            .attr('width', this.width);
    }
});
*/
/*
// x.tick.centered when axis are rotated #539
var chart2 = c3.generate({
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 20, 10, 40, 15, 25]
        ],
        types: {
            data1: 'bar',
        }
    },
    axis: {
        rotated: true,
        x: {
            type: "category",
            label: {
                text: 'X Label',
                position: 'outer-middle'
            },
            tick: {
//                centered: true
            }
        },
        y: {
            label: {
                text: 'Y Label',
                position: 'outer-center'
            },
            tick: {
                centered: true
            }
        }
    }
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 100, 200, 150, 300, 200],
            ['data2', 400, 500, 250, 700, 300]
        ]
    },
    axis: {
        y: {
            min: 100,
            max: 700
        }
    }
});
*/

/*
window.chart = c3.generate({
    bindto: '#chart',
    data: {
        x: 'x',
        xFormat: '%Y-%m-%d %H:%M:%S', // 'xFormat' can be used as custom format of 'x'
        columns: [
            ['x', '2013-01-01 12:00:00', '2013-01-02 12:00:00', '2013-01-03 12:00:00', '2013-01-04 12:00:00', '2013-01-05 12:00:00', '2013-01-10 12:00:00'],
//            ['x', '20130101', '20130102', '20130103', '20130104', '20130105', '20130106'],
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 130, 340, 200, 500, 250, 350],
            ['data3', 230, 400, 500, 600, 450, 950],
            ['data4', 1000, 1200, 1100, 1400, 1150, 1250]
        ],
        type: 'pie'
    },
    axis: {
        x: {
            type: 'timeseries',
            tick: {
                format: '%Y-%m-%d %H:%M:%S'
            }
        }
    }
});

var targetIds = ['data2', 'data3'];
setTimeout(function () {
    chart.focus(targetIds);
}, 1000);

setTimeout(function () {
    window.chart.load({
        columns: [
            ['x', '2013-01-01 12:00:00', '2013-01-02 12:00:00', '2013-01-03 12:00:00', '2013-01-04 12:00:00', '2013-01-05 12:00:00', '2013-01-10 12:00:00', '2013-01-15 12:00:00', '2013-02-15 12:00:00'],
            ['data3', 400, 500, 450, 700, 600, 500, 200, 900]
        ]
    });
}, 2000);
*/
/*
var chart = c3.generate({
//    bindto : '#ch',
    data : {
        x : 'x',
//        xSort: false,
        rows : [
            [ 'x', 'data1', 'data2' ],
            [ 11, 120, 300 ],
            [ 22, 160, 240 ],
            [ -33, 200, 290 ], // <----- note shuffled x point
            [ 44, 160, 230 ],
            [ 55, 130, 300 ],
        ],
        onclick: function (d) { console.log("clicked =>", d); }
    }
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 30],
            ['data2', 120],
        ],
        type : 'donut',
        selection: {
          enabled: true,
          grouped: true,
          multiple: true
        },
        onselected: function(d, i) { 
            console.log('selected', d, i);
        }
    },
    donut: {
        title: "Iris Petal Width"
    }
});
*/
/*
var chart = c3.generate({
    data: {
        x: 'setosa_x',
//        xs: {
//            setosa: 'setosa_x',
//            versicolor: 'versicolor_x'
//        },
        // iris data from R
    columns: [
             ["setosa", 3.0, 3.0, 3.2, 3.1, 3.6, 3.9, 3.4, 3.4, 2.9, 3.1, 3.7, 3.4, 3.0, 3.0, 4.0, 4.4, 3.9, 3.5, 3.8, 3.8, 3.4, 3.7, 3.6, 3.3, 3.4, 3.0, 3.4, 3.5, 3.4, 3.2, 3.1, 3.4, 4.1, 4.2, 3.1, 3.2, 3.5, 3.6, 3.0, 3.4, 3.5, 2.3, 3.2, 3.5, 3.8, 3.0, 3.8, 3.2, 3.7, 3.3],
            ["setosa_x", 3.2, 3.2, 3.1, 2.3, 2.8, 2.8, 3.3, 2.4, 2.9, 2.7, 2.0, 3.0, 2.2, 2.9, 2.9, 3.1, 3.0, 2.7, 2.2, 2.5, 3.2, 2.8, 2.5, 2.8, 2.9, 3.0, 2.8, 3.0, 2.9, 2.6, 2.4, 2.4, 2.7, 2.7, 3.0, 3.4, 3.1, 2.3, 3.0, 2.5, 2.6, 3.0, 2.6, 2.3, 2.7, 3.0, 2.9, 2.9, 2.5, 2.8]
        ],
        type: 'scatter'
    },
    axis: {
        x: {
            label: 'Sepal.Width',
            tick: {
                fit: false
            }
        },
        y: {
            label: 'Petal.Width'
        }
    },

});
*/
/*
function generateData(n) {
    var column = ['sample'];
    for (var i = 0; i < n; i++) {
        column.push(Math.random() * 500);
    }
    return column;
}

function load() {
    chart1.load({
        columns: [
            generateData(Math.random() * 1000)
        ]
    });
}

var chart = c3.generate({
    data: {
        columns: [
            generateData(100)
        ],
        selection: {
            enabled: true
        }
    },
    axis: {
        x: {
//            type: 'category',
            default: [30, 60]
        }
    },
    zoom: { enabled: true },
    subchart: { show: true }
});
*/
/*
var chart = c3.generate({
    data: {
        xs: {
            A: 'x1',
            B: 'x2',
            C: 'x2',
            D: 'x2',
            E: 'x2'
        },
        xFormat: '%Y-%m-%d %H:%M:%S',
        json: {
            x1: ['2013-11-10 12:27:08', '2013-11-10 12:37:08', '2013-11-10 12:47:08', '2013-11-10 12:57:08'],
            x2: ['2013-11-10 12:27:08', '2013-11-10 13:27:08', '2013-11-10 14:27:08', '2013-11-10 15:27:08', '2013-11-10 16:27:08'],
            A: [0, 10, 20, 50],
            B: [0, 0, 0, 0, 0],
            C: [10, 10, 10, 5, 5],
            D: [20, 20, 20, 10, 10],
            E: [40, 40, 40, 30, 30]
        },
        type: 'step',
        types: {
            A: 'scatter'
        }
    },
    axis: {
        x: {
            type: 'timeseries',
            tick: {
                format: '%H:%M:%S'
            }
        }
    }
});
*/

/*
var chart = c3.generate({
    data: {
        x: "x",
        columns: [
            ["x", "one", "two", "three", "four", "five"],
            ["Random", 1, 2, 3, 4, 5]
        ],
        type: "area"
    },
    axis: {
        x: {
            type: "categorized",
            tick: {
                rotate: 90
            },
            height: 100
        }
    },
    oninit: function () {
        this.main.append('rect')
            .style('fill', 'white')
            .attr('x', 0.5)
            .attr('y', -0.5)
            .attr('width', this.width)
            .attr('height', this.height)
          .transition().duration(1000)
            .attr('x', this.width)
            .attr('width', 0)
          .remove();
    }
});
*/
/*
var chart = c3.generate({
    data: {
        x: 'x',
        columns: [
            ['x', '2010-01-01'],
            ['sample', 30]

        ]
    },
    axis : {
        x : {
            type : 'timeseries',
            tick: {
                format: function (x) { return x.getFullYear(); }
              //format: '%Y' // format string is also available for timeseries data
            },
//            default: [new Date('2012-01-01'), new Date('2013-01-01')]
            default: ['2012-01-01', '2013-01-01']
        }
    },
    subchart: {
        show: true
    }
});
*/
/*
var data1 = ['data1'];
generateData(data1, 100);
var data2 = ['data2'];
generateData(data2, 100);

var chart = c3.generate({
    data: {
        columns: [data1, data2],
        type: 'bar'
    },
    subchart: {
        show: true
    }
});

function generateData(array, numberOfPoints) {
    for (var i = 0; i < numberOfPoints; i++) {
        array.push(Math.random() * i + i);
    }
}
*/
/*
var data1 = ['data1'];
generateData(data1, 500);
var data2 = ['data2'];
generateData(data2, 500);

var chart = c3.generate({
    data: {
        columns: [data1, data2],
    },
    subchart: {
        show: true
    }
});

function generateData(array, numberOfPoints) {
    for (var i = 0; i < numberOfPoints; i++) {
        array.push(Math.random() * i + i);
    }
}
*/
/*
var chart = c3.generate({
    data: {
        columns:[
            ['data1', 30, 20, 50, 40, 60, 50, 100, 200],
            ['data2', 230, 220, 250, 240, 260, 250, 300, 400]
        ],
        labels: true,
        axes: {
            data1: 'y',
            data2: 'y',
            data3: 'y2'
        }
    },
    axis: {
        y: {

        },
        y2: {
            show: true
        }
    },
    legend: {
        item: {
            onclick: function () {},
        }
    }
});

setTimeout(function () {
    chart.load({
        json: {
            data3: [130, 220, 350, 140, 360, 250, 100, 300]
        }
    });
}, 1000);
*/

/*
data_test_original = ['data3', 0,0,0,0,0,0,1,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,10,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,1,3,2,0,0,0,0,0,0,0,1,0,0,0,0,0,0,1,0,0,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,7,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,15,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,12,0,41,0,4,0,5,0,29,0,0,0,0,0,0,0,0,0,0,0,11,0,19,23,87,0,83,0,17,13,36,0,10,1,6,0,2,0,1,3,0,0,9,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,7,0,0,0,0,3,0,0,0,1,0,0,1,0,0,0,0,0,2,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,0,1,0,0,1,0,0,0,2,0,0,0,43,9,10,9,2,0,0,0,0,1,4,0,0,2,0,9,2,0,0,0,0,0,1,0,0,3,0,0,0,0,0,0,0,1,0,0,0,0,0,2,0,4,0,0,0,6,0,8,0,0,0,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,2,30,50,100]
data_logscale_tweet = ['data3'];
for(var i=1; i<data_test_original.length; i++){
    if (data_test_original[i] === 0){
        data_logscale_tweet[i] = null;
    }
    else {
        data_logscale_tweet[i] = Math.log(data_test_original[i]) / Math.LN10; 
    }
    
}
var chart = c3.generate({
    bindto: '#chart',
    point: {
        show: false
    },
    bar: {
        width: {
            ratio: 1 // this makes bar width 50% of length between ticks
        }
        // or
        //width: 100 // this makes bar width 100px
    },
    data: {
        hide:['data3'],
        //labels: true,
        x: 'x',
        columns: [
            ['x', "2012-06-26","2012-06-27","2012-06-28","2012-06-29","2012-06-30","2012-07-01","2012-07-02","2012-07-03","2012-07-04","2012-07-05","2012-07-06","2012-07-07","2012-07-08","2012-07-09","2012-07-10","2012-07-11","2012-07-12","2012-07-13","2012-07-14","2012-07-15","2012-07-16","2012-07-17","2012-07-18","2012-07-19","2012-07-20","2012-07-21","2012-07-22","2012-07-23","2012-07-24","2012-07-25","2012-07-26","2012-07-27","2012-07-28","2012-07-29","2012-07-30","2012-07-31","2012-08-01","2012-08-02","2012-08-03","2012-08-04","2012-08-05","2012-08-06","2012-08-07","2012-08-08","2012-08-09","2012-08-10","2012-08-11","2012-08-12","2012-08-13","2012-08-14","2012-08-15","2012-08-16","2012-08-17","2012-08-18","2012-08-19","2012-08-20","2012-08-21","2012-08-22","2012-08-23","2012-08-24","2012-08-25","2012-08-26","2012-08-27","2012-08-28","2012-08-29","2012-08-30","2012-08-31","2012-09-01","2012-09-02","2012-09-03","2012-09-04","2012-09-05","2012-09-06","2012-09-07","2012-09-08","2012-09-09","2012-09-10","2012-09-11","2012-09-12","2012-09-13","2012-09-14","2012-09-15","2012-09-16","2012-09-17","2012-09-18","2012-09-19","2012-09-20","2012-09-21","2012-09-22","2012-09-23","2012-09-24","2012-09-25","2012-09-26","2012-09-27","2012-09-28","2012-09-29","2012-09-30","2012-10-01","2012-10-02","2012-10-03","2012-10-04","2012-10-05","2012-10-06","2012-10-07","2012-10-08","2012-10-09","2012-10-10","2012-10-11","2012-10-12","2012-10-13","2012-10-14","2012-10-15","2012-10-16","2012-10-17","2012-10-18","2012-10-19","2012-10-20","2012-10-21","2012-10-22","2012-10-23","2012-10-24","2012-10-25","2012-10-26","2012-10-27","2012-10-28","2012-10-29","2012-10-30","2012-10-31","2012-11-01","2012-11-02","2012-11-03","2012-11-04","2012-11-05","2012-11-06","2012-11-07","2012-11-08","2012-11-09","2012-11-10","2012-11-11","2012-11-12","2012-11-13","2012-11-14","2012-11-15","2012-11-16","2012-11-17","2012-11-18","2012-11-19","2012-11-20","2012-11-21","2012-11-22","2012-11-23","2012-11-24","2012-11-25","2012-11-26","2012-11-27","2012-11-28","2012-11-29","2012-11-30","2012-12-01","2012-12-02","2012-12-03","2012-12-04","2012-12-05","2012-12-06","2012-12-07","2012-12-08","2012-12-09","2012-12-10","2012-12-11","2012-12-12","2012-12-13","2012-12-14","2012-12-15","2012-12-16","2012-12-17","2012-12-18","2012-12-19","2012-12-20","2012-12-21","2012-12-22","2012-12-23","2012-12-24","2012-12-25","2012-12-26","2012-12-27","2012-12-28","2012-12-29","2012-12-30","2012-12-31","2013-01-01","2013-01-02","2013-01-03","2013-01-04","2013-01-05","2013-01-06","2013-01-07","2013-01-08","2013-01-09","2013-01-10","2013-01-11","2013-01-12","2013-01-13","2013-01-14","2013-01-15","2013-01-16","2013-01-17","2013-01-18","2013-01-19","2013-01-20","2013-01-21","2013-01-22","2013-01-23","2013-01-24","2013-01-25","2013-01-26","2013-01-27","2013-01-28","2013-01-29","2013-01-30","2013-01-31","2013-02-01","2013-02-02","2013-02-03","2013-02-04","2013-02-05","2013-02-06","2013-02-07","2013-02-08","2013-02-09","2013-02-10","2013-02-11","2013-02-12","2013-02-13","2013-02-14","2013-02-15","2013-02-16","2013-02-17","2013-02-18","2013-02-19","2013-02-20","2013-02-21","2013-02-22","2013-02-23","2013-02-24","2013-02-25","2013-02-26","2013-02-27","2013-02-28","2013-03-01","2013-03-02","2013-03-03","2013-03-04","2013-03-05","2013-03-06","2013-03-07","2013-03-08","2013-03-09","2013-03-10","2013-03-11","2013-03-12","2013-03-13","2013-03-14","2013-03-15","2013-03-16","2013-03-17","2013-03-18","2013-03-19","2013-03-20","2013-03-21","2013-03-22","2013-03-23","2013-03-24","2013-03-25","2013-03-26","2013-03-27","2013-03-28","2013-03-29","2013-03-30","2013-03-31","2013-04-01","2013-04-02","2013-04-03","2013-04-04","2013-04-05","2013-04-06","2013-04-07","2013-04-08","2013-04-09","2013-04-10","2013-04-11","2013-04-12","2013-04-13","2013-04-14","2013-04-15","2013-04-16","2013-04-17","2013-04-18","2013-04-19","2013-04-20","2013-04-21","2013-04-22","2013-04-23","2013-04-24","2013-04-25","2013-04-26","2013-04-27","2013-04-28","2013-04-29","2013-04-30","2013-05-01","2013-05-02","2013-05-03","2013-05-04","2013-05-05","2013-05-06","2013-05-07","2013-05-08","2013-05-09","2013-05-10","2013-05-11","2013-05-12","2013-05-13","2013-05-14","2013-05-15","2013-05-16","2013-05-17","2013-05-18","2013-05-19","2013-05-20","2013-05-21","2013-05-22","2013-05-23","2013-05-24","2013-05-25","2013-05-26","2013-05-27","2013-05-28","2013-05-29","2013-05-30","2013-05-31","2013-06-01","2013-06-02","2013-06-03","2013-06-04","2013-06-05","2013-06-06","2013-06-07","2013-06-08","2013-06-09","2013-06-10","2013-06-11","2013-06-12","2013-06-13","2013-06-14","2013-06-15","2013-06-16","2013-06-17","2013-06-18","2013-06-19","2013-06-20","2013-06-21","2013-06-22","2013-06-23","2013-06-24","2013-06-25","2013-06-26","2013-06-27","2013-06-28","2013-06-29","2013-06-30","2013-07-01","2013-07-02","2013-07-03","2013-07-04","2013-07-05","2013-07-06","2013-07-07","2013-07-08","2013-07-09","2013-07-10","2013-07-11","2013-07-12","2013-07-13","2013-07-14","2013-07-15","2013-07-16","2013-07-17","2013-07-18","2013-07-19","2013-07-20","2013-07-21","2013-07-22","2013-07-23","2013-07-24","2013-07-25","2013-07-26","2013-07-27","2013-07-28","2013-07-29","2013-07-30","2013-07-31","2013-08-01","2013-08-02","2013-08-03","2013-08-04","2013-08-05","2013-08-06","2013-08-07","2013-08-08","2013-08-09","2013-08-10","2013-08-11","2013-08-12","2013-08-13","2013-08-14","2013-08-15","2013-08-16","2013-08-17","2013-08-18","2013-08-19","2013-08-20","2013-08-21","2013-08-22","2013-08-23","2013-08-24","2013-08-25","2013-08-26","2013-08-27","2013-08-28","2013-08-29","2013-08-30","2013-08-31","2013-09-01","2013-09-02","2013-09-03","2013-09-04","2013-09-05","2013-09-06","2013-09-07","2013-09-08","2013-09-09","2013-09-10","2013-09-11","2013-09-12","2013-09-13","2013-09-14","2013-09-15","2013-09-16","2013-09-17","2013-09-18","2013-09-19","2013-09-20","2013-09-21","2013-09-22","2013-09-23","2013-09-24","2013-09-25","2013-09-26","2013-09-27"],
            ['data1', 15000,15000,15000,15000,15000,15000,15927.245117077,15927.245117077,15927.245117077,15927.245117077,15927.245117077,15755.288415648,15755.288415648,15755.288415648,15755.288415648,15755.288415648,15755.288415648,15755.288415648,15755.288415648,15755.288415648,15755.288415648,15755.288415648,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15769.738173005,15669.99213179,15669.99213179,15669.99213179,17400.232979407,17400.232979407,17400.232979407,17511.459187653,19854.440712839,21218.860418753,21218.860418753,21218.860418753,21218.860418753,21218.860418753,21218.860418753,21218.860418753,21218.860418753,20807.374921,20807.374921,20807.374921,20807.374921,20807.374921,20807.374921,20807.374921,20657.648030371,20657.648030371,20657.648030371,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20483.263112791,20094.490800289,20094.490800289,20094.490800289,20094.490800289,20094.490800289,20094.490800289,20094.490800289,20094.490800289,20565.55836209,20565.55836209,20367.951604448,20367.951604448,20367.951604448,20367.951604448,20367.951604448,19812.800279277,19812.800279277,19812.800279277,19812.800279277,19812.800279277,19812.800279277,19812.800279277,19812.800279277,19812.800279277,20332.710507924,20332.710507924,22125.499903303,22125.499903303,22125.499903303,22125.499903303,22125.499903303,22125.499903303,22125.499903303,22125.499903303,22125.499903303,22125.499903303,22125.499903303,22125.499903303,20647.420199985,20647.420199985,20063.73419762,20063.73419762,20063.73419762,20063.73419762,20063.73419762,20063.73419762,20063.73419762,20063.73419762,20063.73419762,20063.73419762,20063.73419762,20063.73419762,17914.660445699,17914.660445699,18301.384629534,18301.384629534,18301.384629534,18301.384629534,18301.384629534,18301.384629534,18301.384629534,18301.384629534,18301.384629534,18301.384629534,18301.384629534,18301.384629534,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20085.867765553,20078.815689465,20078.815689465,20078.815689465,20078.815689465,20078.815689465,20078.815689465,20078.815689465,20078.815689465,20078.815689465,20078.815689465,19737.701653826,19737.701653826,19737.701653826,19737.701653826,19737.701653826,19737.701653826,19737.701653826,19737.701653826,22715.863780784,22715.863780784,22715.863780784,22715.863780784,22715.863780784,22715.863780784,22715.863780784,22715.863780784,22715.863780784,22715.863780784,22715.863780784,22715.863780784,22715.863780784,22715.863780784,22715.863780784,22715.863780784,22715.863780784,22715.863780784,20708.303533476,20708.303533476,20708.303533476,20708.303533476,21504.194451988,21504.194451988,21504.194451988,21504.194451988,21504.194451988,21504.194451988,21504.194451988,21504.194451988,21504.194451988,21504.194451988,25172.350703437,25172.350703437,25202.004805018,25202.004805018,26404.971853459,26404.971853459,27170.177165329,27170.177165329,28663.382933737,28663.382933737,28663.382933737,28663.382933737,28663.382933737,28663.382933737,28663.382933737,28663.382933737,28663.382933737,28663.382933737,28663.382933737,28663.382933737,26817.322565229,26817.322565229,34541.980229008,33571.083717099,41577.825873725,41577.825873725,46894.928502596,46894.928502596,51581.870532529,52848.306461127,57077.922813436,57077.922813436,51890.761676227,55249.102238167,49303.048004743,49303.048004743,47842.58415498,47842.58415498,48242.67481654,50356.876830157,50356.876830157,50356.876830157,46900.891797973,46900.891797973,46900.891797973,46900.891797973,46900.891797973,46900.891797973,46900.891797973,46900.891797973,46900.891797973,46900.891797973,46900.891797973,46900.891797973,46900.891797973,46900.891797973,49330.108621652,49330.108621652,49330.108621652,49330.108621652,49330.108621652,49330.108621652,52236.074674791,52236.074674791,52236.074674791,52236.074674791,52236.074674791,52236.074674791,52236.074674791,52236.074674791,43178.901841529,43178.901841529,43178.901841529,43178.901841529,43178.901841529,43178.901841529,43178.901841529,43178.901841529,43178.901841529,43178.901841529,43178.901841529,43917.952150513,43917.952150513,43917.952150513,43917.952150513,43917.952150513,39406.927056817,39406.927056817,39406.927056817,39406.927056817,38589.666737867,38589.666737867,38589.666737867,42560.192132327,42560.192132327,42560.192132327,42560.192132327,42560.192132327,42560.192132327,38169.016903728,38169.016903728,38169.016903728,38169.016903728,38169.016903728,38169.016903728,38169.016903728,38169.016903728,38169.016903728,38169.016903728,42217.551399129,42217.551399129,40879.06354071,40879.06354071,40879.06354071,40879.06354071,40879.06354071,40879.06354071,40879.06354071,40879.06354071,40879.06354071,40879.06354071,40716.042065566,40716.042065566,37801.517412552,37801.517412552,37801.517412552,37801.517412552,37801.517412552,35983.835580405,35983.835580405,35983.835580405,33575.995951833,33575.995951833,33575.995951833,33575.995951833,39256.881482749,39256.881482749,39256.881482749,39256.881482749,39572.042341289,39985.864580795,43052.811003259,46265.984891392,44879.409967854,44879.409967854,44879.409967854,44879.409967854,44879.409967854,47683.50862278,51942.811813877,51942.811813877,51942.811813877,51467.452928262,51467.452928262,59624.450559155,59467.863783826,59467.863783826,59467.863783826,59467.863783826,59467.863783826,59467.863783826,59566.547925439,59566.547925439,59566.547925439,65959.676026915,65959.676026915,65959.676026915,65959.676026915,65959.676026915,65959.676026915,65959.676026915,65959.676026915,51810.581817449,51810.581817449,51810.581817449,51810.581817449,51810.581817449,51810.581817449,48678.834679632,48678.834679632,54323.038812315,54323.038812315,54323.038812315,54323.038812315,68234.490305818,68234.490305818,72351.20171588,72351.20171588,72351.20171588,72351.20171588,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,73055.760187597,66135.685586341,66135.685586341,66135.685586341,66135.685586341,66135.685586341,66135.685586341,66135.685586341], //model
            ['data2', 17276,17235,17147,17013,16943.666666667,16874.333333333,16805,16414,16294,16086,16042,15950.666666667,15859.333333333,15768,15776,15682.5,15589,15608,15532.666666667,15457.333333333,15382,15252,15210,15139,14969,14872.666666667,14776.333333333,14680,14528,14376,14341,14250,14199,14148,14097,13719,13845,13686,13617,13582.666666667,13548.333333333,13514,13633,13491,13504,13426,13504.666666667,13583.333333333,13662,13619,13297,13154,13159.857142857,13165.714285714,13171.571428571,13177.428571429,13183.285714286,13189.142857143,13195,13160,13195.333333333,13230.666666667,13266,13401,13331,13356,13382,13303,13224,13145,13131,12847,12923,12996,12991.333333333,12986.666666667,12982,12864,12855,12841,12981,12948.333333333,12915.666666667,12883,12718,12644,12614,12584,12618.333333333,12652.666666667,12687,12680,12530,12234,12343,12315.666666667,12288.333333333,12261,12183,11927,11930,11925,11947,11969,11991,11892,11949,12020,12013,12051.333333333,12089.666666667,12128,12151,11978,11927,12038,12041.666666667,12045.333333333,12049,12184,12162,12611,12646.75,12682.5,12718.25,12754,12750,12607,12679,12558,12642,12726,12810,13021,13115,13290,13368,13511.333333333,13654.666666667,13798,14045,14147.5,14250,14352.5,14455,14557.5,14660,14985,15039,15051,15069.5,15088,15106.5,15125,15246,15564,15762,15993,16047.333333333,16101.666666667,16156,16214,16244,16266,16345,16337.333333333,16329.666666667,16322,16304,16474,16535,16503,16515,16527,16539,16838,16815,16745,16811,16788.2,16765.4,16742.6,16719.8,16697,16736,16584,16683.666666667,16783.333333333,16883,16982.666666667,17082.333333333,17182,17524,18404,18450,18496,18542,18685,19007,18953,19119,19285,19451,19932,20385,20902,21053,21410.333333333,21767.666666667,22125,22438,22569,22692.5,22816,22712,22608,22504,22547,22089,22365,22238,22206.333333333,22174.666666667,22143,22271,22442,22519,22273,22347,22421,22495,22307,22404,22365,22209,22236.666666667,22264.333333333,22292,22064,22374,22383,22419,22583.666666667,22748.333333333,22913,23620,23817,24235,24758,24904.666666667,25051.333333333,25198,25560,25415,25884,26600,28335.333333333,30070.666666667,31806,34139.5,36473,41987,41987,42836.666666667,43686.333333333,44536,43943,43341,41864,40549,40727.333333333,40905.666666667,41084,42273,42251,42288,42748.5,43209,43669.5,44130,44630,45191,44876,44661,44748,44835,44922,44231,43915,44579,44376,43953,43530,43107,42479,42060,41926,41178,40431,39684,38937,38714,37402,36855,36812,36245,35678,35111,34111,33370,33093,32690,32496.666666667,32303.333333333,32110,32195,31816,31723,31630,31547,31464,31381,31267,31348,31339,30931,30909.333333333,30887.666666667,30866,30730,30569,30581,30461,30636.666666667,30812.333333333,30988,30830,30756,30465,30538,30354,30170,29986,29585,29398,29333.5,29269,29045,28821,28597,28279,28193,28617,28461,28305,28149,27993,28111,28331,28563,28839,29333.666666667,29828.333333333,30323,30844,31512,31865,32641,33456,34271,35086,36804,38014,39712,40017,41426.333333333,42835.666666667,44245,46576,45289,47067,48880,49730.666666667,50581.333333333,51432,52133,53193,53870,53392,53837,54282,54727,55241,55959,55474,55603,55902,56201,56500,56310,55986,57024,57785.272727273,58546.545454545,59307.818181818,60069.090909091,60830.363636364,61591.636363636,62352.909090909,63114.181818182,63875.454545455,64636.727272727,65398,66407,67855,67935,67522,67109,66696,66283,63970,63108,62062,59657,58545,57433,56321,53984,53351,51422,49996,48351.333333333,46706.666666667,45062,43805,41883,40434,39195,38342.666666667,37490.333333333,36638,35503,35047,33850,33469.8,33089.6,32709.4,32329.2,31949,31823,31138,30988,30489.333333333,29990.666666667,29492,29212,28434,27551,27276], //official
            data_logscale_tweet //number of tweet
        ],
        types: {
            data3: 'bar' // ADD
        },
        names: {
            data1: 'Model',
            data2: 'Official',
            data3: 'Number of Tweets'
        },
        axes: {
            data3: 'y2'
        },
        colors: {
            data1: '#0000FF',
            data2: '#FF0000',
            data3: '#808080'
        }
    },
    zoom: {
        enabled: true
    },
    legend: {
        position: 'right'
    },
    axis: {
        x: {
            type: 'timeseries',
            tick: {
                format: "%Y-%m",
                //                    count: 4
//                values: ['2013-06-01'],
                count: 3
            },
            height: 80,
            label: {
                text: 'X Label',
                position: 'outer-center'
            }
        },
        y: {
            min: 0,
            tick: {
                format: function (d) { 
                    return  Math.round(d/1000) + 'K'; 
                }
            },
            padding: {top: 100, bottom: 100},
            label: { // ADD
                text: 'Price (IDR)',
                position: 'outer-middle'
            }
        },
        y2: {
            //max: 100,
            //min: 0
            show: true,
            tick: {
                format: function (d) { return Math.pow(10,d).toFixed(0); }
            },
            //padding: {top: 0, bottom: 10},
            //show: true,
            label: { // ADD
                text: 'Number Of Tweets',
                position: 'outer-middle'
            }
        }
    }
});
      function log10(val) {
        return parseFloat(Math.log(val) / Math.LN10).toFixed(2);
      }
      //for add title of this chart
      d3.select('#chart svg').append('text')
        .attr('x', d3.select('#chart svg').node().getBoundingClientRect().width / 2)
        .attr('y', 16)
        .attr('text-anchor', 'middle')
        .style('font-size', '1.4em')
        .text('Title of this chart');
        
*/

/*
var myjson = [{"value":569,"timestamp":"2014-01-01 08:27:36"},
{"value":499,"timestamp":"2014-01-01 10:25:37"},
{"value":639,"timestamp":"2014-01-01 20:00:44"},
{"value":569,"timestamp":"2014-01-02 06:36:32"},
{"value":559,"timestamp":"2014-01-02 06:58:33"},
{"value":539,"timestamp":"2014-01-02 08:13:33"}
];


//http://c3js.org/samples/data_json.html
//https://github.com/masayuki0812/c3/issues/364
chart_value = c3.generate({
    data: {
        json: myjson,
        keys: {
            value: ['value']
            },
        type: 'step',
        onclick: function(d) { console.log(d); }
        }
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1',null,null],
            ['data2',null,null]
//            ['data1'],
//            ['data2']
        ]
    }
});
*/

/*
var chart1 = c3.generate({
    bindto: "#chart1",
    data: {
        columns: [
            ['data1', 100, 200, 150, 300, 200],
            ['data2', 400, 500, 250, 700, 300], ]
    }
});

var chart2 = c3.generate({
    bindto: "#chart2",
    data: {
        columns: [
            ['data1', 100, 200, 150, 300, 200],
            ['data2', 400, 500, 250, 700, 300], ]
    }
});
*/


/**
 * innerHTML property for SVGElement
 * Copyright(c) 2010, Jeff Schiller
 *
 * Licensed under the Apache License, Version 2
 *
 * Works in a SVG document in Chrome 6+, Safari 5+, Firefox 4+ and IE9+.
 * Works in a HTML5 document in Chrome 7+, Firefox 4+ and IE9+.
 * Does not work in Opera since it doesn't support the SVGElement interface yet.
 *
 * I haven't decided on the best name for this property - thus the duplication.
 */
/*
(function() {
var serializeXML = function(node, output) {
  var nodeType = node.nodeType;
  if (nodeType == 3) { // TEXT nodes.
    // Replace special XML characters with their entities.
    output.push(node.textContent.replace(/&/, '&amp;').replace(/</, '&lt;').replace('>', '&gt;'));
  } else if (nodeType == 1) { // ELEMENT nodes.
    // Serialize Element nodes.
    output.push('<', node.tagName);
    if (node.hasAttributes()) {
      var attrMap = node.attributes;
      for (var i = 0, len = attrMap.length; i < len; ++i) {
        var attrNode = attrMap.item(i);
        output.push(' ', attrNode.name, '=\'', attrNode.value, '\'');
      }
    }
    if (node.hasChildNodes()) {
      output.push('>');
      var childNodes = node.childNodes;
      for (var i = 0, len = childNodes.length; i < len; ++i) {
        serializeXML(childNodes.item(i), output);
      }
      output.push('</', node.tagName, '>');
    } else {
      output.push('/>');
    }
  } else if (nodeType == 8) {
    // TODO(codedread): Replace special characters with XML entities?
    output.push('<!--', node.nodeValue, '-->');
  } else {
    // TODO: Handle CDATA nodes.
    // TODO: Handle ENTITY nodes.
    // TODO: Handle DOCUMENT nodes.
    throw 'Error serializing XML. Unhandled node of type: ' + nodeType;
  }
}
// The innerHTML DOM property for SVGElement.
Object.defineProperty(SVGElement.prototype, 'innerHTML', {
  get: function() {
    var output = [];
    var childNode = this.firstChild;
    while (childNode) {
      serializeXML(childNode, output);
      childNode = childNode.nextSibling;
    }
    return output.join('');
  },
  set: function(markupText) {
    // Wipe out the current contents of the element.
    while (this.firstChild) {
      this.removeChild(this.firstChild);
    }

    try {
      // Parse the markup into valid nodes.
      var dXML = new DOMParser();
      dXML.async = false;
      // Wrap the markup into a SVG node to ensure parsing works.
      sXML = '<svg xmlns=\'http://www.w3.org/2000/svg\'>' + markupText + '</svg>';
      var svgDocElement = dXML.parseFromString(sXML, 'text/xml').documentElement;

      // Now take each node, import it and append to this element.
      var childNode = svgDocElement.firstChild;
      while(childNode) {
        this.appendChild(this.ownerDocument.importNode(childNode, true));
        childNode = childNode.nextSibling;
      }
    } catch(e) {
      throw new Error('Error parsing XML string');
    };
  }
});

// The innerSVG DOM property for SVGElement.
Object.defineProperty(SVGElement.prototype, 'innerSVG', {
  get: function() {
    return this.innerHTML;
  },
  set: function(markupText) {
    this.innerHTML = markupText;
  }
});

})();
d3.selection.prototype.text = d3.selection.prototype.html;

var chart = c3.generate({
    data: {
        xs: {
            'x1': 'data1',
            'x2': 'data2',
            'x3': 'data3',
        },
        columns: [
            ["data1", 5],
            ["x1", 7],
            ["data2", 15],
            ["x2", 17],
            ["data3", 25],
            ["x3", 27],
        ],
        type: "scatter",
    },
    point: { 
       r: 15 
    },
    axis: {          
        x: {
            tick: {
                values: [0, 10, 20, 30, 40],
                format: function(d) {
                    var date = '2014-01-01';
                    var weekday = d;
                    return '<tspan x="0" dy="1.2em">' + date + '</tspan><tspan x="0" dy="1.2em">' + weekday + '</tspan>';
                }
            },
            min: 0,
            max: 50
        },
        y: {
            min: 0,
        }
    }
});
*/
/*
var chart = c3.generate({
    data: {
        xs: {
            'x1': 'data1',
        },
        columns: [
            ["data1", 5],
            ["x1", 7],
        ],
        type: "scatter",
    },
    point: { 
         r: 10
    },
    axis: {          
        x: {
            min: 0,
            max: 10
        },
        y: {
            min: 0,
        }
    }
});
*/
/*
function A(id) {
    this.id = id;

    this.config = {};
    this.config.value = id;


    bindThis(fn, this, this);

    function bindThis(fn, target, argThis) {
        console.log(fn ,target);
        for (var key in fn) {
            if (typeof fn[key] === 'object') {
                target[key] = {};
                bindThis(fn[key], target[key], argThis);
            } else {
                target[key] = fn[key].bind(argThis);
            }
        }
    };
}
var fn = A.prototype = {};

fn.func = {};
fn.func.hoge = function () {
    console.log("func.hoge =>", this.id);
};
fn.func.cat1 = {};
fn.func.cat1.hoge = function () {
    console.log("func.cat1.hoge =>", this.id);
};

//fn.config = {};
//fn.config.value = undefined;

fn.api = function () {
    console.log("api", this);
};

var a1 = new A(1), a2 = new A(2);
console.log(a1.id, a1.config.value);
console.log(a2.id, a2.config.value);

a1.func.hoge();
a2.func.hoge();

a1.func.cat1.hoge();
a2.func.cat1.hoge();
*/


/*
var chart = c3.generate({
    data: {
        url: '/data/test_1.json',
        mimeType: 'json',
        keys: {
            x: 'fecha_hora',
            value: ['lgcom', 'fravegacom', 'mercadolibrecomar']
        },
    },
    axis : {
        x : {
            type : 'timeseries',
            tick : {
                format : "%Y-%m-%d %H:%M:%S"
            }
        }
    }
});
*/
/*
var chart = c3.generate({
    data: {
        x: 'x',
        x_format: '%Y-%m-%dT%H:%M:%S',
        url: "/data/timeseries.json",
        mimeType: 'json'
    },
    axis: {
        x: {
            type: 'timeseries',
            tick: {
                format: "%Y-%m-%d"
            }
        }
    },
});
*/
/*
var chart = c3.generate({
    data: {
        x: 'x',
        columns: [
            ['x', 10, 20, 30],
            ['data2', 400, 500, 250]
        ]
    }
});
            
setTimeout(function(){
    chart.flow({
        columns: [
            ['x', 40, 50],
            ['data2', 700, 300]
        ]
    });
}, 1000);
*/
/*
var chart = c3.generate({
    "data": {
        "x_format": "%Y-%m-%dT%H:%M:%S.%LZ",
        "xs": {
            "xxx #0": "x504s0",
            "yyy #0": "x15575s0",
            "zzz #0": "x17521s0"
        },
        "columns": [
            [
                "x504s0",
                "2014-06-20T00:16:52.000Z",
                "2014-06-24T15:38:54.000Z",
                "2014-06-24T15:39:30.000Z",
                "2014-06-24T15:40:00.000Z",
                "2014-06-27T06:39:21.000Z",
                "2014-06-27T06:39:53.000Z"
            ],
            [
                "xxx #0",
                1,
                1,
                1,
                1
            ],
            [
                "x15575s0",
                "2014-06-21T16:35:17.000Z"
            ],
            [
                "yyy #0",
                2
            ],
            [
                "x17521s0",
                "2014-06-21T18:14:34.000Z",
                "2014-06-21T20:05:42.000Z",
                "2014-06-22T05:34:16.000Z",
                "2014-06-22T21:48:58.000Z",
                "2014-06-24T11:42:07.000Z",
                "2014-06-24T13:11:48.000Z",
                "2014-06-24T14:01:13.000Z"
            ],
            [
                "zzz #0",
                3,
                3,
                3,
                3
            ]
        ]
    },
    "axis": {
        "x": {
            "type": "timeseries",
            "tick": {
                "rotate": 45,
//                "count": 20,
                fit: false,
                "format": "%m-%d",
                values: [new Date("2014-06-20"), new Date("2014-06-22")]
            }
        }
    }
});
*/
/*
var start = moment("2013-01-01", "YYYY-MM-DD"),
    end   = moment("2014-01-01", "YYYY-MM-DD"),
    range = moment().range(start, end);

var dates = ['x'];
var sample = ['sample'];
var sample2 = ['sample2'];
range.by('days', function(moment) {
    dates.push(moment.toDate());
    sample.push(Math.floor(Math.random() * 250) + 1);
    sample2.push(Math.floor(Math.random() * 250) + 1);
});

var chart = c3.generate({
    bindto: '#chart',
    data: {
        x : 'x',
        x_format : '%Y%m%d',
        columns: [
            dates, sample, sample2
        ]
    },
    axis : {
        x : {
            type : 'timeseries',
            tick : {
                format : "%e %b %y" // https://github.com/mbostock/d3/wiki/Time-Formatting#wiki-format
            }
        }
    }
});
*/
/*
var chart = c3.generate({
    size: {
        height: 500,
        width: 1100,
    },
    data: {
        x: 'Company',
        columns: [
            ['Company', 'AAA', 'BBB', 'CCC', 'DDD'],
            ['c1', 100, 200, 100, 150],
            ['c2', 200, 100, 100, 150],
            ['c3', 300, 200, 200, 250],
        ],
        type: 'scatter',
    },
    grid: {
        x: {
//            show: true
            lines: [
                {value:0, class:'dotted'},
                {value:1, class:'dotted'},
                {value:2, class:'dotted'},
                {value:3, class:'dotted'},
            ]
        }
    },
    axis: {
        x: {
            type: 'categories',
            tick: {
                rotate: 75,
                centered: true
            },
            height: 200
        },
        y: {
            tick: {
                format: d3.format("$,")
            }
        }
    }
});
*/
/*
var countyVals = ['Population',1737046,1755487,1777514,1788082,1800783,1814999,1845209,1871098,1891125,1909205,1931249,1942600,1957000,1981900,2169381];
var yearCats = ['2000','2001','2002','2003','2004','2005','2006','2007','2008','2009','2010','2011','2012','2013'];

var usPopChart = c3.generate({
    padding: {
        top: 0,
        right: 15,
        bottom: 0,
        left: 15,
    },
    size: {
        height: 105,
        width: 285
    },
    data: {
        columns: [
            countyVals
        ],
        type: 'area'
    },
    area : {
        zerobased: false,
    },
    axis : {
        x : {
            type: 'categorized',
            categories: yearCats,
            tick: {
                count: 4,
                format: function(d) { return yearCats[d.toFixed(0)]; }
            }
        },
        y : {
            min: 1700000,
            max: 2000000,
            show: false
        }
    },
    legend: {
        show: false
    },
    tooltip: {
        format: {
            value: d3.format(',')
        }
    }
});
*/

/*    
time = ["x",
      "2013-01-01T12:00:00", 
      "2013-01-02T12:00:00", 
      "2013-01-03T12:00:00", 
      "2013-01-04T12:00:00", 
      "2013-01-05T12:00:00", 
      "2013-01-06T12:00:00"];
data = ['data1', 30, 200, 100, 400, 150, 250];


var chart = c3.generate({
    data: {
        x: 'x',
        x_format: '%Y-%m-%dT%H:%M:%S',
        columns: [
            time,
            data
        ]
    },
    axis: {
        x: {
            type: 'timeseries',
            tick: {
                format: '%Y-%m-%dT%H:%M:%S'
            }
        }
    }
});
*/
/*
var chart1 = c3.generate({
    bindto: "#weight",
    data: {
      columns: [
        ["weight", 10, 4, 5, 3, 2, 5]
      ],
      colors: {
        weight: "#6ab153"
      },
      types: {
        weight: "area"
      }
    }
  });

  var chart2 = c3.generate({
    bindto: "#bodyfat",
    data: {
      columns: [
        ["bodyfat", 1,2,3,4,5,10 ]
      ],
      colors: {
        bodyfat: "#018aa1"
      },
      types: {
        bodyfat: "area"
      }
    }
  });

  var chart3 = c3.generate({
    bindto: "#bloodpressure",
    data: {
      columns: [
        ["systolic", 1, 3.5, 4.5, 2, 3.5],
        ["diastolic", 0.5, 2, 3, 0.5, 2.5]
      ],
      colors: {
        systolic: "#6ab153",
        diastolic: "#018aa1"
      },
      types: {
        systolic: "line",
        diastolic: "area"
      }
    }
  });

  var chart4 = c3.generate({
    bindto: "#glucose",
    data: {
      columns: [
        ["glucose", 80, 80, 90, 100, 40]
      ],
      colors: {
        glucose: "#6ab153"
      },
      types: {
        glucose: "area"
      }
    }
  });
*/
/*
var chart = c3.generate({
    bindto: '#chart',
    data: {
        x: 'x',
        x_format: '%Y-%m-%d %H:%M:%S',
        columns: [
            ['x', '2013-01-01 23:10:12', '2013-01-02 23:10:12', '2013-01-03 23:10:12', '2013-01-04 23:10:12', '2013-01-05 23:10:12', '2013-01-06 23:10:12'],
            ['data1', 300, 300, 600, 400, 350, 250]
        ],
        types : { data1: 'area'} , colors : {data1:'#000000'}
    },
    axis: {
        x: {
            show : false,
            type: 'timeseries',
            tick: {
                format: '%Y-%m-%d %H:%M:%S'
            }
        },
        y: {
            min: 150 //CAN SUCCESSFULLY SET TO BELOW 0, BUT NUMBERS ABOVE ZERO DON'T WORK
        }
    },
    axes: {
        data1: 'y'
    },
    point : {
        show: false
    },
    grid: {
        x: {
            show: true
        },
        y : {
            show: true
        }
    }
});
*/

/*
function generate(){
    var __tickFormat = "%H:%M";
    var __datas = [["date","12:05","12:06","12:07"],["ages",32,32,34]];
    var __tickCount = 3;
    var __chartSeriesColors = ["#3aa4c4"];
    var __chartSeries = ["ages"];
    var __chartTypes = ["donut"];
    var chart = c3.generate({
        data : {
            x : 'date',
            x_format : __tickFormat,
            columns : __datas
        },
        type : 'timeseries',
        axis: {
            x: {
                type: 'timeseries',
                tick: {
                    count : __tickCount,
                    format: __tickFormat
                }
            }
        },
        color: {
            pattern: __chartSeriesColors
        }
    });
    for(var i = 0;i < __chartSeries.length; i++){
        var dataName = __chartSeries[i];
        var chartType = __chartTypes[i];
        chart.transform(chartType);
    }
}
generate();
*/

/*
function makeChart(){
  return c3.generate({
      data: {
          columns: [
              ['AA', 30, 50, 80]
          ],
          type: 'bar',
          labels: true,

      },
      axis: {
          x: {
              type: "category",
              categories: ["cat1", "cat2", "cat3"]
          }
      }
  });
};

var chart = makeChart();

var data = {
    columns: [
        ['BB', 100, 20, 40, 30, 10]
    ],
    categories: ["cat1", "cat2", "cat3", "cat4", "cat5"]
};
setTimeout(function () {
    chart.load(data);
}, 1000);
setTimeout(function(){
    //$("#chart").children().remove();
    chart.unload(['BB'])

    chart.destroy();
    $("#caption").html("Restarting...")
    $("#data").html("");
    chart = makeChart();

}, 2000);
*/


/*
c3.generate({
    size: {
        height: 130,
    },
    data: {
        x: 'x',
        json: {
            x:['2013-01-01', '2013-02-01', '2013-03-01', '2013-04-01', '2013-05-01', '2013-06-01', '2013-07-01', '2013-08-01', '2013-09-01', '2013-10-01', '2013-11-01', '2013-12-01'],
            data: [25000, 18000, 19500, 20250, 22000, 30000, 24500, 22000, 24500, 25000, 21500, 19000]
        },
        names: {
            data: "bounty"
        },
        types: {
            data: 'area'
        },
        colors: {
            data: '#6a8da7'
        }
    },
    tooltip: {
        show: true
    },
    grid: {
        y: {
            lines: [
                {value: 60}
            ]
        }
    },
    axis: {
        y: {
            show: false,
            tick: {
                format: function (d) {
                    return d + " " + "kWh"
                }
            }
        },
        x: {
            show: false,
            type: 'timeseries',
            tick: {
                format: '%b'
            }
        }
    },
    legend: {
        show: false
    }
});
*/


/*
var chart = c3.generate({
    data: {
        columns: [
            ["Cost", 70, 130 , 160, 140, 140, 130],
            ["Company_Impact", 20, 50, 60 , 30, 40, 90, 0 , 0 , 0 , 0 , 0 , 0],
            ["Suppliers", 30, 60, 80 , 90, 80, 20, 0 , 0 , 0 , 0 , 0 , 0]
        ],
        axes: {
            Cost: 'y2'
        },
        groups: [
            ["Company_Impact", "Suppliers"]
        ],
        names: {
            Company_Impact: "Company Impact"
        },
        type: "bar",
        colors: {
            Company_Impact: "#e5603b",
            Suppliers: "#B8C2BB",
            Cost: "#6a8da7"
        },
        types: {
            Cost: "line"
        }
    },
    axis: {
        x: {
            type: 'categorized',
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        y: {
            label: {
                text: 'lbs CO2e / unit',
                position: 'outer-middle'
            }
        },
        y2: {
            min: 0,
            show: true,
            label: {
                text: 'Cost',
                position: 'outer-middle'
            },
            tick: {
                format: function (d) {
                    return "$ " + d
                }
            }
        }
    },
    grid: {
        x: {
            show: true
        },
        y: {
            show: true
        }
    },
    bar: {
        width: 10
    },
    tooltip: {
        show: true,
        format: {
            value: function (value, ratio, id) {
                var format = id === 'Cost' ? d3.format('$') : function (d) {
                    return d + " lbs CO2e / unit"
                };
                return format(value);
            }
        }
    }
});
$('#chart1').append(chart.element);
*/


/*
var chart = c3.generate({
    data: {
        x: 'x',
        columns: [
            ['x', '2014-01-01', '2014-02-01', '2014-03-01'],
            ['data1', 190, 200, 190],
        ],
        type: 'bar',
    },
    axis: {
        x: {
            type: 'categorized'
        },
        rotated:true   
    },
});


setTimeout(function () {
    chart.load({
        columns: [['data1', 300, 350, 100]],
        categories:['2014-01-01 10:10:10', '2014-02-01 12:30:00', '2014-03-01 16:30:00']
    });
    setTimeout(function () {
        chart.load({
            columns: [['data1', 50, 100, 150]],
            categories:['2014', '2015', '2016']
        });
    }, 2000);
}, 2000);
*/

/*
var chart = c3.generate({
//    "bindto": "#chart",
    "axis": {
        "x": {
            "type": "timeseries",
            "min": 1401879600000,
            "max": 1401969600000,
        }
    },
    "grid": {
        "x": {
            "lines": [
                { "value": 1401880524000, "text": "a", "color": "#f00" },
                { "value": 1401880566000, "text": "b", "color": "#f00" }
            ]
        }
    },
    "data": {
        "type": "line",
        "columns": [
            ["epoch", 1401879600000, 1401883200000, 1401886800000],
            ["y", 1955, 2419, 2262]
        ],
        "xs": {
            "y": "epoch"
        }
    }
});
*/
/*
c3.generate({
    padding: {
        top: 100,
        right: 0,
        bottom: 100,
        left: 40
    },
    size: {
        height: 400
    },
    data: {
        columns: [
            ['sample', 1000, 2000, 1500, 3000, 2500, 1500],
        ]
    }
})
*/

/*
var xAxisData = ['EMP1','EMP2','EMP3','EMP4','EMP5','EMP6'];
var chart = c3.generate({
    data: {
        columns: [
            ['sample', 1000, 2000, 1500, 3000, 2500, 1500],
            ['sample1', 1562, 3654, 1478, 3654, 987, 78],
            ['sample2', 59, 2364, 2785, 369, 698, 6987],
            ['sample3', 654, 6987, 4112, 6210, 412, 202],
            ['sample4', 22, 252, 275, 3136, 159, 2636],
        ],
        type : 'bar',
        labels: {
            format: {
                y: d3.format('')
            }
        },
        groups: [
            ['sample', 'sample1','sample2','sample3','sample4']
        ]
    },
    axis: {
        x: {
            type: 'categorized',
            categories: xAxisData,
            label: {
                text: 'Employee name',
                position: 'outer-center'
            }
        },
        y: {
            label: {
                text: ' Count',
                position: 'outer-middle'
            }
        },
        rotated : true
    }
});
*/

/*
var config_fuelecon = {
    data: {
      x: 'x',
      x_format: '%Y-%m-%d %I:%M:%S %p',
      columns: [
        [ 'x','2014-06-01 21:44:15 PM','2014-06-01 21:44:45 PM','2014-06-01 21:45:15 PM','2014-06-01 21:45:45 PM','2014-06-01 21:46:15 PM','2014-06-01 21:46:45 PM','2014-06-01 21:47:16 PM','2014-06-01 21:47:45 PM','2014-06-01 21:48:15 PM','2014-06-01 21:48:45 PM','2014-06-01 21:48:56 PM','2014-06-01 21:49:15 PM','2014-06-01 21:49:17 PM','2014-06-01 21:49:19 PM','2014-06-01 21:49:38 PM','2014-06-01 21:49:50 PM','2014-06-01 21:49:52 PM','2014-06-01 21:49:54 PM','2014-06-01 21:50:15 PM','2014-06-01 21:50:22 PM','2014-06-01 21:50:45 PM','2014-06-01 21:51:22 PM','2014-06-01 21:51:44 PM','2014-06-01 21:51:46 PM','2014-06-01 21:52:15 PM','2014-06-01 21:52:47 PM','2014-06-01 21:53:15 PM','2014-06-01 21:53:45 PM','2014-06-01 21:54:15 PM','2014-06-01 21:54:45 PM','2014-06-01 21:55:15 PM','2014-06-01 21:55:45 PM','2014-06-01 21:56:15 PM','2014-06-01 21:56:45 PM','2014-06-01 21:57:15 PM','2014-06-01 21:57:45 PM','2014-06-01 21:58:58 PM','2014-06-01 21:58:59 PM','2014-06-01 21:59:15 PM','2014-06-01 21:59:45 PM','2014-06-01 22:00:22 PM','2014-06-01 22:00:53 PM','2014-06-01 22:01:15 PM','2014-06-01 22:01:45 PM','2014-06-01 22:02:15 PM','2014-06-01 22:02:45 PM','2014-06-01 22:03:15 PM','2014-06-01 22:03:47 PM','2014-06-01 22:04:15 PM','2014-06-01 22:04:45 PM','2014-06-01 22:05:15 PM','2014-06-01 22:05:45 PM','2014-06-01 22:06:15 PM','2014-06-01 22:06:30 PM','2014-06-01 22:06:45 PM','2014-06-01 22:07:15 PM','2014-06-01 22:07:52 PM','2014-06-01 22:08:14 PM','2014-06-01 22:08:16 PM','2014-06-01 22:08:52 PM','2014-06-01 22:09:15 PM','2014-06-01 22:09:42 PM','2014-06-01 22:10:15 PM','2014-06-01 22:10:17 PM','2014-06-01 22:10:45 PM','2014-06-01 22:10:47 PM','2014-06-01 22:10:49 PM','2014-06-01 22:11:15 PM','2014-06-01 22:11:32 PM','2014-06-01 22:11:34 PM','2014-06-01 22:11:45 PM','2014-06-01 22:12:15 PM','2014-06-01 22:12:45 PM','2014-06-01 22:13:15 PM','2014-06-01 22:13:17 PM','2014-06-01 22:13:45 PM','2014-06-01 22:14:15 PM','2014-06-01 22:14:45 PM','2014-06-01 22:15:03 PM','2014-06-02 00:05:16 AM','2014-06-02 00:05:46 AM','2014-06-02 00:05:48 AM','2014-06-02 00:06:02 AM','2014-06-02 00:06:15 AM','2014-06-02 00:06:17 AM','2014-06-02 00:06:46 AM','2014-06-02 00:07:15 AM','2014-06-02 00:07:17 AM','2014-06-02 00:07:46 AM','2014-06-02 00:07:48 AM','2014-06-02 00:08:20 AM','2014-06-02 00:08:31 AM','2014-06-02 00:08:33 AM','2014-06-02 00:08:46 AM','2014-06-02 00:09:16 AM','2014-06-02 00:09:46 AM','2014-06-02 00:10:16 AM','2014-06-02 00:10:46 AM','2014-06-02 00:11:16 AM','2014-06-02 00:11:49 AM','2014-06-02 00:11:57 AM','2014-06-02 00:12:16 AM','2014-06-02 00:12:46 AM','2014-06-02 00:13:16 AM','2014-06-02 00:13:46 AM','2014-06-02 00:14:16 AM','2014-06-02 00:14:46 AM','2014-06-02 00:15:26 AM','2014-06-02 00:15:49 AM','2014-06-02 00:16:16 AM','2014-06-02 00:16:46 AM','2014-06-02 00:17:16 AM','2014-06-02 00:17:46 AM','2014-06-02 00:18:21 AM','2014-06-02 00:18:50 AM','2014-06-02 00:19:16 AM','2014-06-02 00:19:46 AM','2014-06-02 00:20:16 AM','2014-06-02 00:20:47 AM','2014-06-02 00:21:59 AM','2014-06-02 00:22:01 AM','2014-06-02 00:22:17 AM','2014-06-02 00:22:46 AM','2014-06-02 00:23:16 AM','2014-06-02 00:23:46 AM','2014-06-02 00:24:16 AM','2014-06-02 00:24:46 AM','2014-06-02 00:25:16 AM','2014-06-02 00:25:46 AM','2014-06-02 00:26:16 AM','2014-06-02 00:26:46 AM','2014-06-02 00:27:16 AM','2014-06-02 00:27:25 AM','2014-06-02 00:27:46 AM','2014-06-02 00:27:48 AM','2014-06-02 00:28:16 AM','2014-06-02 00:28:18 AM','2014-06-02 00:28:20 AM','2014-06-02 00:28:46 AM','2014-06-02 00:29:17 AM','2014-06-02 00:29:46 AM','2014-06-02 00:30:16 AM','2014-06-02 00:30:46 AM','2014-06-02 00:31:16 AM','2014-06-02 00:31:18 AM','2014-06-02 00:31:46 AM','2014-06-02 00:32:16 AM','2014-06-02 00:32:46 AM','2014-06-02 00:33:16 AM','2014-06-02 00:33:46 AM','2014-06-02 00:34:12 AM','2014-06-02 00:34:47 AM','2014-06-02 00:34:50 AM','2014-06-02 00:34:58 AM','2014-06-02 00:35:16 AM','2014-06-02 00:35:46 AM','2014-06-02 00:36:08 AM','2014-06-02 00:36:30 AM','2014-06-02 00:36:31 AM','2014-06-02 00:36:31 AM','2014-06-02 00:36:49 AM','2014-06-02 00:36:50 AM','2014-06-02 00:37:16 AM','2014-06-02 00:37:49 AM','2014-06-02 00:37:59 AM','2014-06-02 00:38:16 AM','2014-06-02 00:38:46 AM','2014-06-02 00:39:52 AM','2014-06-02 00:47:29 AM','2014-06-02 00:47:50 AM','2014-06-02 00:47:51 AM','2014-06-02 00:48:16 AM','2014-06-02 00:48:26 AM','2014-06-02 00:48:44 AM','2014-06-02 00:48:46 AM','2014-06-02 00:49:16 AM','2014-06-02 00:49:38 AM','2014-06-02 00:49:46 AM','2014-06-02 00:50:12 AM','2014-06-02 00:50:47 AM','2014-06-02 00:50:49 AM','2014-06-02 00:51:09 AM','2014-06-02 00:51:35 AM','2014-06-02 00:51:37 AM','2014-06-02 00:51:46 AM','2014-06-02 00:52:16 AM','2014-06-02 00:52:46 AM','2014-06-02 00:53:10 AM','2014-06-02 00:53:52 AM','2014-06-02 00:53:52 AM','2014-06-02 00:53:52 AM','2014-06-02 00:54:10 AM','2014-06-02 00:54:46 AM','2014-06-02 00:54:48 AM','2014-06-02 00:55:16 AM','2014-06-02 00:55:46 AM','2014-06-02 00:56:08 AM','2014-06-02 00:56:16 AM','2014-06-02 00:56:46 AM','2014-06-02 00:56:55 AM' ],
        [ 'MPG',11,10,9,10,11,13,12,12,13,13,14,13,14,14,14,14,14,14,14,14,14,15,15,15,16,16,17,17,17,18,18,18,18,18,18,18,18,19,19,19,19,19,19,19,19,19,19,19,19,19,20,20,20,19,19,19,19,19,19,20,19,19,19,20,20,19,19,19,19,19,19,0,0,0,0,0,0,0,19,0,2,2,4,6,6,12,15,15,13,12,15,15,14,14,14,15,16,17,17,17,18,18,18,18,18,19,19,19,19,20,20,20,20,20,20,20,20,20,20,21,21,21,21,21,21,21,21,21,21,21,21,21,22,22,22,22,22,21,22,22,22,22,22,22,22,22,22,22,22,22,22,21,22,22,21,21,21,21,21,21,21,21,21,0,0,0,0,21,0,2,4,5,7,11,11,11,12,13,12,12,14,15,14,15,15,18,17,17,16,17,17,17,16,17,16,0,0,0,0,16 ]
      ],
      empty: {
        label: {
          text: "No Data"
        }
      },
      type: 'area-step'
    },
    axis: {
      x: {
        type: 'timeseries',
        tick: {
          count: 5,
          fit: false,
          format: function (x) { if (x !== undefined) { var parts = x.toString().split(' '); return parts[1]+parts[2]+' '+parts[4].slice(0,8); } }
        },
        padding: { left: 100000, right: 100000 }
      },
      y: {
        max: 22,                ticks: 3,
        padding: { top: 0, bottom: 0 }
      }
    },
    legend: {
      show: false
    },
    color: {
      pattern: ['#758eab']
    }
  };
 var graph_fuelecon = c3.generate(config_fuelecon);
*/


/*
var chart = c3.generate({
    data: {
        x: 'x',
        columns: [
            ['x', '2014-01-01', '2014-02-01', '2014-03-01', '2014-04-01'],
            ['data1', 190, 200, 190, null],
//            ['data1', -190, -200, -190, null],
        ],
        type: 'bar',
//        labels: true
        labels: {
            format: function (v, id) {
                if (v === null) {
                    return 'Not Applicable';
                }
                return d3.format('$')(v);
            }
        }

    },
    axis: {
        x: {
            type: 'categorized'
        },
        rotated: true
    },
});
*/
/*
var chart = c3.generate({
    data: {
        x : 'date',
//        x_format: '%Y-%m-%d',
        columns: [
            ['date', '2013-01-01', '2013-11-02', '2013-12-01', '2014-01-01'],
            ['sample', 30, -200, 100, 400],
            ['sample2', -130, 300, 200, 450]
        ],
        type: 'bar',
    },
    bar: {
        width: 10,
    },
    axis : {
        x : {
            type : 'timeseries',
            tick : {
                format : "%Y-%m-%d %H:%M:%S"
            }
        }
    }
});
*/

/*
function generateBarChart() {
    return c3.generate({
        data: {
            columns: [
                ['data1', 30, 300, 140, 190, 50, 90],
                ['data2', 130, 200, 90, 300, 250, 150],
                ['data3', 90, 100, 180, 190, 150, 250]
            ],
            type: 'bar',
            groups: [['data1', 'data2', 'data3']],
            onclick: function (d, element) {
                var data = {};
                chart.data.targets.forEach(function (t) {
                    data[t.id] = t.values.filter(function (v) {
                        return v.index === d.index;
                    }).map(function (v) {
                        return v.value;
                    });
                });
                chart = generatePieChart(data);
            }
        }
    });
}

function generatePieChart(data) {
    return c3.generate({
        data: {
            json: data,
            type: 'pie',
            onclick: function () {
                chart = generateBarChart();
            }
        },
    });
}

var chart = generateBarChart();
*/

/*
var popYears = ['x','2000-01-02','2001-01-02','2002-01-02','2003-01-02','2004-01-02','2005-01-02','2006-01-02','2007-01-02','2008-01-02','2009-01-02','2010-01-02','2011-01-02','2012-01-02','2013-01-02','2020-01-02'];
var countyVals = ['Population',1737046,1755487,1777514,1788082,1800783,1814999,1845209,1871098,1891125,1909205,1931249,1942600,1957000,1981900,2169381];
var popChart = c3.generate({
    padding: {
        top: 0,
        right: 10,
        bottom: 0,
        left: 10,
    },
    size: {
//        height: 50,
//        width: 185
    },
    data: {
        x: 'x',
        columns: [
            popYears,
            countyVals
        ],
        regions: {
            'Population': [{'start':"2013-01-02",'end':"2020-01-02",'style':'dashed'}]
        }
    },
    axis : {
        x : {
            type : 'timeseries',
            tick: {
                count: 4,
                fit: true,
                format: "%Y"
            }
        },
        y: {
            show:false
        }
    },
    legend: {
        show: false
    },
    tooltip: {
        format: {
            value: d3.format(',')
        }
    }
});
*/
/*
var chart = c3.generate({
    data: {
        columns: [
            ['data1', 100, 200, 150, 400],
        ],
        type: 'bar'
    },
    onresized: updateErrorBars
});

var errors = [50, 20, 100, 150];

var errorBars = d3.select('#chart svg .c3-chart').append('g');

errorBars.selectAll('circle')
  .data(errors)
    .enter().append('circle')
    .attr('class', function (d, i) { return 'error-circle-' + i; })
    .attr('r', 5);

errorBars.selectAll('path')
  .data(errors)
    .enter().append('path')
    .attr('class', function (d, i) { return 'error-line-' + i; });

function updateErrorBars() {

    d3.selectAll('.c3-bar').each(function (d, i) {
        var segList = this.pathSegList,
            yPos = segList.getItem(1).y,
            xPos = (segList.getItem(2).x + segList.getItem(0).x) / 2;

        errorBars.select('.error-circle-' + i)
            .attr('cx', xPos)
            .attr('cy', yPos);

        errorBars.select('.error-line-' + i)
            .attr('d', function (d) {
                return 'M' + xPos + ',' + (yPos + d/2) + ' ' +
                    'L' + xPos + ',' + (yPos - d/2) + ' ' +
                    'M' + (xPos - 5) + ',' + (yPos + d/2) + ' ' +
                    'L' + (xPos + 5) + ',' + (yPos + d/2) + ' ' +
                    'M' + (xPos - 5) + ',' + (yPos - d/2) + ' ' +
                    'L' + (xPos + 5) + ',' + (yPos - d/2) + ' ' +
                    'z';
            });

    });
};

setTimeout(updateErrorBars, 500);
*/

/*
var x_data = ["x","Apr22","Apr23","Apr24","Apr26","Apr27","Apr28","Apr29","Apr30","May01","May02","May03","May04","May05","May06","May07","May08","May09","May10","May11","May12","May13","May14","May15","May16","May17","May18","May19","May20","May21"];
var connect_data = ["Connected",6,6,6,6,5,5,6,6,6,5,5,5,5,5,4,4,5,6,7,7,7,7,6,6,6,12,12,13,13];
var disconnect_data = ["Disconnected",8,8,8,11,12,12,11,11,11,12,12,12,12,12,13,13,12,11,10,10,10,10,11,11,11,1,1,1,1];

for (var i = 1; i < x_data.length; i++) {
    x_data[i] = x_data[i] + '1930';
}

c3.generate({
    data: {
        x: 'x',
        x_format: '%b%d%Y',
        columns: [
            x_data,
            connect_data,
            disconnect_data
        ],
        type: 'area',
        groups: [
            ["Connected","Disconnected"]
        ]
    },
    axis: {
//        rotated: true,
        x: {
            type: 'timeseries',
            tick: {
                count: 29,
                fit: false,
                format: function (x) { var parts = x.toString().split(' '); return parts[1]+parts[2]; }
            },
            padding: { left: 0, right: 0}
        },
        y: {
            ticks: 10,
            padding: { bottom: 0 },
            max: 17,
            min: 1
        }
    },
    legend: {
        show: false
    },
    color: {
        pattern: ['#F6C600','#00253C'],
//        opacity: 0.3
    },
    zoom: {
//        enabled: true
    }
});
*/

/*
var chart = c3.generate({
    bindto:"#chart",
    data: {
//        x: "x",
        columns: [
//            ["x", "A", "B", "C"],
            ['data1', 130, 120, 150],
            ['data4', 30, 20, 50]
        ],
//        type: 'bar'
    },
    axis: {
        x: {
//            type: 'categorized'
        }
    },
    zoom: {
        enabled: true
    }
});
*/
/*
setTimeout(function () {
    chart.load({
        columns: [
            ["x", "A", "B", "C", "D", "E", "F", "G", "H"],
            ['data1', 130, 120, 150, 140, 160, 150, 200, 300],
            ['data4', 30, 20, 50, 40, 60, 50, 100, 120]
        ]
    });
}, 1000);
*/

/*
var chart = c3.generate({
    size: { width: 300, height: 300 },
    data: {
        columns: [
        ],
        type : 'donut',
    },
    donut: {
        title: "Owner"
    }
});
*/
/*
var chart = c3.generate({
    bindto: '#chart',
    data: {
        x: 'date',
        x_format : '%d/%m/%Y',
        columns: [
            ['date',"17/03/2014","07/04/2014","08/04/2014","14/04/2014","25/04/2014","26/04/2014","08/05/2014"],
            ["Dilma Roussef", 42, 41, 40, 39, 38, 37, 36],
            ["Aécio Neves", 15, 15, 15, 15, 16, 17, 18],
            ["Eduardo Campos", 7, 7, 8, 7, 9, 8, 9],
            ["Pastor Everaldo", 2, 2, 2, 2, 2, 2, 2],
            ["Outros", 1, 2, 2, 2, 3, 2, 3]
        ],
    },
    axis : {
        x : {
            type : 'timeseries',
            tick : {
//              format : "%m/%d" // https://github.com/mbostock/d3/wiki/Time-Formatting#wiki-format
                format : "%e %b %y" // https://github.com/mbostock/d3/wiki/Time-Formatting#wiki-format
            }
        }
    }
});
*/
