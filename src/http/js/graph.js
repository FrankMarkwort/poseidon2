(async () => {
    const chartData = await fetch(
        'http://192.168.0.101/api/averages.php'
    ).then(response => response.json());
    var chart = Highcharts.chart('container', {
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
            name: 'RangeTWD',
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
            name: chartData['titleWatertemperature'],
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
    });
    /*
    setInterval(function() {
        fetch('http://192.168.0.101/api/averages.php')
            .then(function (response) { return response.json(); })
            .then(function (data) {
                console.info(data.averagesAwa);
                chart.series[0].data[0].update(data.averagesAwa);
                chart.series[1].data[0].update(data.rangesAwa);
                //chart.series[2].data[0].update(positions);
                //chart.series[1].data[0].update(circleColor);
                //chart.series[1].data[1].update(circleColor);
                //if(! data.hasAlarm) {
                //audio.play();
                //}

                // chart.series[2].redraw();
                //chart.series[2].data[0].
                //console.info(positions);
                //console.info(data);
                //console.info(chart.series[2].data[0]);
            });
    }, 1000 * 60 *60); //1000 m
    */
})();

