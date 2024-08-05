(async () => {
    const chartData = await fetch(
        'http://192.168.0.101/api/averages.php'
    ).then(response => response.json());
    Highcharts.chart('container', {
        chart: {
            zooming: {
                type: 'x'
            }
        },
        rangeSelector: {
            selected: 2
        },
        title: {
            text: 'Wind-Data',
            align: 'left'
        },
        xAxis: {
            type: 'datetime',
            min: chartData['startTime'],
			max: chartData['endTime'],
            accessibility: {
                rangeDescription: 'Range: Jul 2st 2022 to Jul 10st 2022.'
            }
        },
        yAxis: {
            title: {
                text: null
            }
        },
        plotOptions: {
            series: {
                pointStart: chartData['startTime'],
                pointInterval: chartData['pointInterval'],
            }
        },
        data: {
            enablePolling: true,
            dataRefreshRate: 3600,
        },
        series: [
            // #####################
            {
            name: chartData['titleAwa'],
            data: chartData['averagesAwa'],
            zIndex: 1,
            marker: {
                fillColor: 'white',
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[0]
            },
            tooltip: {
                crosshairs: true,
                shared: true,
                valueSuffix: ' °'
            },
        }, {
            name: 'RangeAwa',
            data: chartData['rangesAwa'],
            type: 'arearange',
            lineWidth: 0,
            linkedTo: ':previous',
            color: Highcharts.getOptions().colors[0],
            fillOpacity: 0.3,
            zIndex: 0,
            marker: {
                enabled: false
            }
        },// #####################
        {
            name:  chartData['titleAws'],
            data: chartData['averagesAws'],
            zIndex: 1,
            marker: {
                fillColor: 'grey',
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[0]
            },
            tooltip: {
                crosshairs: true,
                shared: true,
                valueSuffix: ' Kn'
            },
        }, {
            name: 'RangeAws',
            data: chartData['rangesAws'],
            type: 'arearange',
            lineWidth: 0,
            linkedTo: ':previous',
            color: Highcharts.getOptions().colors[0],
            fillOpacity: 0.3,
            zIndex: 0,
            marker: {
                enabled: false
            }
        },// #####################
          {
            name:  chartData['titleTws'],
            data: chartData['averagesTws'],
            zIndex: 1,
            marker: {
                fillColor: 'grey',
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[0]
            },
            tooltip: {
                crosshairs: true,
                shared: true,
                valueSuffix: ' Kn'
            },
        }, {
            name: 'RangeTws',
            data: chartData['rangesTws'],
            type: 'arearange',
            lineWidth: 0,
            linkedTo: ':previous',
            color: Highcharts.getOptions().colors[0],
            fillOpacity: 0.3,
            zIndex: 0,
            marker: {
                enabled: false
            }
        },// #####################
          {
            name:  chartData['titleTwa'],
            data: chartData['averagesTwa'],
            zIndex: 1,
            marker: {
                fillColor: 'grey',
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[0]
            },
            tooltip: {
                crosshairs: true,
                shared: true,
                valueSuffix: ' °'
            },
        }, {
            name: 'RangeTwa',
            data: chartData['rangesTwa'],
            type: 'arearange',
            lineWidth: 0,
            linkedTo: ':previous',
            color: Highcharts.getOptions().colors[0],
            fillOpacity: 0.3,
            zIndex: 0,
            marker: {
                enabled: false
            }
        }, // #####################
         {
            name:  chartData['titleTwd'],
            data: chartData['averagesTwd'],
            zIndex: 1,
            marker: {
                fillColor: 'grey',
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[0]
            },
            tooltip: {
                crosshairs: true,
                shared: true,
                valueSuffix: ' °'
            },
        }, {
            name: 'RangeTwd',
            data: chartData['rangesTwd'],
            type: 'arearange',
            lineWidth: 0,
            linkedTo: ':previous',
            color: Highcharts.getOptions().colors[0],
            fillOpacity: 0.3,
            zIndex: 0,
            marker: {
                enabled: false
            }
        }, // #####################
          {
            name:  chartData['titleWatertemperature'],
            data: chartData['averagesWatertemperature'],
            zIndex: 1,
            marker: {
                fillColor: 'grey',
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[0]
            },
            tooltip: {
                crosshairs: true,
                shared: true,
                valueSuffix: ' °'
            },
        }, {
            name: 'RangeWaterTemperature',
            data: chartData['rangesWatertemperature'],
            type: 'arearange',
            lineWidth: 0,
            linkedTo: ':previous',
            color: Highcharts.getOptions().colors[0],
            fillOpacity: 0.3,
            zIndex: 0,
            marker: {
                enabled: false
            }
        } // #####################
        ],
    }
)})();

