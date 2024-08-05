var chart3 = Highcharts.chart('windlupencontainer', {

    chart: {
        type: 'gauge',
     //   styledMode: true,
        alignTicks: false,
        plotBackgroundColor: null,
        plotBackgroundImage: null,
        plotBorderWidth: 0,
        plotShadow: false,
        height: '100%',
        backgroundColor: 'rgba(45,44,44,0)',
        boost: {
            useGPUTranslations: true,
            seriesThreshold: 3
        },
    },

    title: {
        text: 'Wind Lupe'
    },

    pane: [
        {
        startAngle: -180,
        endAngle: 180,
        center: ['50%', '50%'],
        size: '90%',
        background: [{
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#FFF'],
                        [1, '#333']
                    ]
                },
                borderWidth: 0,
                outerRadius: '100%'
            }, {
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#333'],
                        [1, '#FFF']
                    ]
                },
                borderWidth: 1,
                outerRadius: '113%'
            }, {
                // default background
            }, {
                backgroundColor: '#DDD',
                borderWidth: 0,
                outerRadius: '75%',
                innerRadius: '74%'
            }]

    },
    {
        startAngle: 0,
        endAngle: 360,
        center: ['50%', '50%'],
        size: '40%',
        background: [{
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#FFF'],
                        [1, '#333']
                    ]
                },
                borderWidth: 0,
                outerRadius: '41%'
            }, {
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#333'],
                        [1, '#FFF']
                    ]
                },
                borderWidth: 1,
                outerRadius: '42%'
            }, {
                // default background
            }, {
                backgroundColor: '#DDD',
                borderWidth: 0,
                outerRadius: '41%',
                innerRadius: '40%'
            }]

    }

    ],

    // the value axis
    yAxis: [{
        min: -90,
        max: 90,
        breaks: [{
                from: 0,
                to: 20,
                breakSize: 20
            },{
                from: 0,
                to: -20,
                breakSize: 20
            }
        ],
        tickPixelInterval: 30,
       // tickPosition: 'outside',
        //tickColor: Highcharts.defaultOptions.chart.backgroundColor || '#FFFFFF',
        lineColor: '#339',
        tickColor: '#339',
        minorTickColor: '#339',
        tickLength: 20,
        tickWidth: 2,
        minorTickInterval: null,
        offset: 20,
        labels: {
             formatter: function() {
                return Math.abs(this.value);
            },
            distance: 20,
            style: {
                fontSize: '14px'
            }
        },
        lineWidth: 0,
        plotBands: [{
                from: 0,
                to: 20,
                color: 'rgba(255,0,0,0)', // green
                thickness: 10,
                borderRadius: '0'
            }, {
                from: 20,
                to: 35,
                color: 'rgb(255,0,0)', // green
                thickness: 10,
                borderRadius: '0'
            }, {
                from: 35,
                to: 45,
                color: 'rgb(247,159,0)', // green
                thickness: 10,
                borderRadius: '0'
            },{
                from: 45,
                to: 65,
                color: 'rgb(0,255,0)', // green
                thickness: 10,
                borderRadius: '0'
            },
            {
                from: 65,
                to: 90,
                color: 'rgba(39,252,1,0.3)', // green
                thickness: 10,
                borderRadius: '0'
            },{
                from: 0,
                to: -20,
                color: 'rgba(255,0,0,0)', // green
                thickness: 10,
                borderRadius: '0'
            },{
                from: -20,
                to: -35,
                color: 'rgb(255,0,0)', // green
                thickness: 10,
                borderRadius: '0'
            }, {
                from: -35,
                to: -45,
                color: 'rgb(247,159,0)', // green
                thickness: 10,
                borderRadius: '0'
            },{
                from: -45,
                to: -65,
                color: 'rgb(0,255,0)', // green
                thickness: 10,
                borderRadius: '0'
            },
            {
                from: -65,
                to: -90,
                color: 'rgba(39,252,1,0.3)', // green
                thickness: 10,
                borderRadius: '0'
            },
        ]
    },{
        min: 0,
        max: 12,
        boostThreshold: 1,
      	turboThreshold: 1,
        tickPixelInterval: 120,
       // tickPosition: 'outside',
        //tickColor: Highcharts.defaultOptions.chart.backgroundColor || '#FFFFFF',
        lineColor: '#339',
        tickColor: '#339',
        minorTickColor: '#339',
        tickLength: 20,
        tickWidth: 2,
        minorTickInterval: null,
        offset: 10,
        labels: {
            distance: -60,
            style: {
                fontSize: '14px'
            }
        },
        lineWidth: 0,
    }

    ],
    series: [
        {
            name: 'awa',
            boostThreshold: 1,
      	    turboThreshold: 1,
            data: [0],
            tooltip: {
                valueSuffix: ' °'
            },
            label: {
                rotation: 'auto'
            },
            dataLabels: {
                formatter: function() {
                    return Math.abs(this.y);
                },
                align: 'left',
                format: 'AWA {y} Grad',
                color: (
                    Highcharts.defaultOptions.title &&
                    Highcharts.defaultOptions.title.style &&
                    Highcharts.defaultOptions.title.style.color
                ) || '#0e2cee',
                enabled: true,
            },
            dial: {
                radius: '115%',
                backgroundColor: '#0e2cee',
                baseWidth: 12,
                baseLength: '0%',
                rearLength: '0%'
            },
            pivot: {
                backgroundColor: 'gray',
                radius: 6
            }
        },
         {
            name: 'VMG',
            boostThreshold: 1,
      	    turboThreshold: 1,
            data: [0],
            tooltip: {
                valueSuffix: ' Kn'
            },
            label: {
                rotation: 'auto'
            },
            dataLabels: {
                align: 'right',
                formatter: function() {
                    return Math.abs(this.y);
                },
                format: 'VMG {y} Kn',
                color: (
                    Highcharts.defaultOptions.title &&
                    Highcharts.defaultOptions.title.style &&
                    Highcharts.defaultOptions.title.style.color
                ) || '#0e2cee',
                enabled: true,
            },
            dial: {
                radius: '70%',
                backgroundColor: '#00f708',
                baseWidth: 12,
                baseLength: '0%',
                rearLength: '0%'
            },
            pivot: {
                backgroundColor: 'gray',
                radius: 6
            }
        }
    ]

});
