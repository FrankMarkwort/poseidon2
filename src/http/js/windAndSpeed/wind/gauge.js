var chart0 = Highcharts.chart('windcontainer', {

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
        text: 'Wind'
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

    }],

    // the value axis
    yAxis: [{
        min: -180,
        max: 180,
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
            to: 35,
            color: 'rgba(255,255,255,0)', // green
            thickness: 10,
            borderRadius: '0'
        }, {
            from: 35,
            to: 45,
            color: 'rgb(255,0,0)', // green
            thickness: 10,
            borderRadius: '0'
        }, {
            from: 45,
            to: 65,
            color: 'rgb(0,255,0)', // green
            thickness: 10,
            borderRadius: '0'
        }, {
            from: 65,
            to: 135,
            color: 'rgb(191,255,0)', // green
            thickness: 10,
            borderRadius: '0'
        }, {
            from: 135,
            to:  155,
            color: 'rgb(0,255,0)', // green
            thickness: 10,
            borderRadius: '0'
        },{
            from: 155,
            to: 180,
            color: 'rgb(221,223,13)',
            thickness: 10,
            borderRadius: '0'
        }, {
            from: 0,
            to: -35,
            color: 'rgba(255,255,255,0)', // green
            thickness: 10,
            borderRadius: '0'
        }, {
            from: -35,
            to: -45,
            color: 'rgb(255,0,0)', // green
            thickness: 10,
            borderRadius: '0'
        }, {
            from: -45,
            to: -65,
            color: 'rgb(0,255,0)', // green
            thickness: 10,
            borderRadius: '0'
        }, {
            from: -65,
            to: -135,
            color: 'rgb(191,255,0)', // green
            thickness: 10,
            borderRadius: '0'
        },{
            from: -135,
            to: -155,
            color: 'rgb(0,255,0)', // green
            thickness: 10,
            borderRadius: '0'
        },{
            from: -155,
            to: -180,
            color: 'rgb(221,223,13)',
            thickness: 10,
            borderRadius: '0'
        }

        ]
    },{
        min: 0,
        max: 360,
        //angle: ,
         angle: 180,
        reversed: false,
        tickPixelInterval: 30,
        tickPosition: 'outside',
        tickWidth: 2,
        offset: -50,
        minorTickPosition: 'outside',
        minorTickInterval: null,
        lineColor: '#933',
        tickColor: '#933',
        minorTickColor: '#933',
        tickLength: 5,
        minorTickLength: 5,
        labels: {
            formatter: function() {
                return (this.value + 180) % 360;
            },
            distance: 12,
            style: {
                fontSize: '14px'
            }
        },
        lineWidth: 0,
    },{
        min: 0,
        max: 40,
        //angle: ,
        angle: 100,
        reversed: false,
        tickPixelInterval: 30,
        tickPosition: 'outside',
        tickWidth: 2,
        offset: -100,
        minorTickPosition: 'outside',
        minorTickInterval: null,
        lineColor: '#933',
        tickColor: '#933',
        minorTickColor: '#933',
        tickLength: 5,
        minorTickLength: 5,
        labels: {
            formatter: function() {
                return (this.value + 20) % 40;
            },
            distance: 12,
            style: {
                fontSize: '14px'
            }
        },
        lineWidth: 0,
    },

    ],
    series: [
        {
            name: 'awa',
            data: [0],
            boostThreshold: 1,
            turboThreshold: 1,
            tooltip: {
                valueSuffix: ' °'
            },
            label: {
                rotation: 'auto'
            },
            dataLabels: {
                format: '{y} °',
                borderWidth: 0,
                color: (
                    Highcharts.defaultOptions.title &&
                    Highcharts.defaultOptions.title.style &&
                    Highcharts.defaultOptions.title.style.color
                ) || '#0e2cee',
                style: {
                    fontSize: '16px'
                },
                enabled: false,
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
            name: 'twa',
             boostThreshold: 1,
      	    turboThreshold: 1,
            data: [0],
            tooltip: {
                valueSuffix: ' °'
            },
            label: {
                formatter() {return Math.abs(this.y)},
                rotation: 'auto'
            },
            dataLabels: {
                formatter() {return Math.abs(this.y)},
                format: '{y} °',
                borderWidth: 0,
                color: (
                    Highcharts.defaultOptions.title &&
                    Highcharts.defaultOptions.title.style &&
                    Highcharts.defaultOptions.title.style.color
                ) || '#333333',
                style: {
                    fontSize: '16px'
                },
                enabled: false,
            },
            dial: {
                radius: '100%',
                backgroundColor: '#ff0000',
                baseWidth: 12,
                baseLength: '0%',
                rearLength: '0%'
            },
            pivot: {
                backgroundColor: 'gray',
                radius: 6
            }
        },{
            name: 'twd',
            boostThreshold: 1,
      	    turboThreshold: 1,
            data: [0],
            tooltip: {
                valueSuffix: ' °'
            },
            label: {
                formatter() {return Math.abs(this.y)},
                rotation: 'auto'
            },
            dataLabels: {
                formatter() {return Math.abs(this.y)},
                format: '{y} °',
                borderWidth: 0,
                color: (
                    Highcharts.defaultOptions.title &&
                    Highcharts.defaultOptions.title.style &&
                    Highcharts.defaultOptions.title.style.color
                ) || '#333333',
                style: {
                    fontSize: '16px'
                },
                 enabled: false,
            },
            dial: {
                radius: '80%',
                backgroundColor: "#f4f700",
                baseWidth: 12,
                baseLength: '0%',
                rearLength: '0%'
            },
            pivot: {
                backgroundColor: 'gray',
                radius: 6
            }
        }, {
            name: 'aws',
            boostThreshold: 1,
            turboThreshold: 1,
            data: [0],
            dial: {
                radius: '40%',
                backgroundColor: '#0022ff',
                baseWidth: 12,
                baseLength: '0%',
                rearLength: '0%'
            },
             dataLabels: {
                 enabled: false
             },
             label: {
               // formatter: function () {return this.value + 60 },
                rotation: 'auto'
            },
        }, {
            name: 'tws',
            boostThreshold: 1,
            turboThreshold: 1,
            data: [0],
            dial: {
                radius: '40%',
                backgroundColor: '#fff700',
                baseWidth: 12,
                baseLength: '0%',
                rearLength: '0%'
            },
            dataLabels: {
                 enabled: false
             },
            label: {
                // formatter: function () {return this.value + 60 },
                rotation: 'auto'
            },
        }
    ]

});
