var chart1 = Highcharts.chart('speedcontainer', {

    chart: {
        type: 'gauge',
     //   styledMode: true,
        alignTicks: false,
        plotBackgroundColor: null,
        plotBackgroundImage: null,
        plotBorderWidth: 0,
        plotShadow: false,
        height: '100%',
        spacing: [0, 0, 0, 0],
        margin: [0, 0, 0, 0],
        //animation: { duration: 0, easing: 'swing' },
        backgroundColor: 'rgba(45,44,44,0)',
        boost: {
            useGPUTranslations: true,
            seriesThreshold: 3
        },
    },

    title: {
        text: 'Speed'
    },

    pane: [
        {
        startAngle: 0,
        endAngle: 360,
        center: ['42%', '50%'],
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

    }],

    // the value axis
    yAxis: [{
        min: 0,
        max: 12,
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
                to: 2,
                color: 'rgba(45,44,44,0.3)', // green
                thickness: 5,
                borderRadius: '50%'
            }, {
             from: 2,
                to: 5,
                color: 'rgb(158,242,158)', // green
                thickness: 10,
                borderRadius: '50%'
            }, {
                from: 5,
                to: 7,
                color: 'rgb(0,255,0)', // green
                thickness: 5,
                borderRadius: '50%'
            },
            {
                from: 7,
                to: 8,
                color: 'rgb(255,173,0)', // red
                thickness: 5,
                borderRadius: '50%'
            }, {
                from: 8,
                to: 12,
                color: '#ff0101', // yellow
                thickness: 5,
                borderRadius: '50%'
            }]
        }
    ],

    series: [
        {
            name: 'sog',
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
                format: 'SOG {y} Kn',
                align: 'right',
                backgroundColor:  'rgba(14,44,238,0.45)',
                color: (
                    Highcharts.defaultOptions.title &&
                    Highcharts.defaultOptions.title.style &&
                    Highcharts.defaultOptions.title.style.color
                ) || '#0e2cee',
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
        },{
            name: 'vmg',
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
                align: 'left',
                format: 'VMG {y} Kn',
                color: (
                    Highcharts.defaultOptions.title &&
                    Highcharts.defaultOptions.title.style &&
                    Highcharts.defaultOptions.title.style.color
                ) || '#333333',
                enabled: true,
            },
            dial: {
                radius: '100%',
                backgroundColor: '#000000',
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
