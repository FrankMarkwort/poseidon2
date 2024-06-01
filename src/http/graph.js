function fetchJSON(url) {
    return fetch(url)
        .then(response => response.json())
        .catch((error) => {
            console.log(error);
        });
}

fetchJSON('http://192.168.0.101/averages.php')
.then((data) => {
    chartData = data;
    Highcharts.chart('container', {
        title: {
            text: 'Apparent wind speed',
            align: 'left'
        },
        xAxis: {
            type: 'datetime',
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
                pointStart: Date.UTC(2022, 6, 1),
                pointIntervalUnit: 'day'
            }
        },
        series: [{
            name: 'Apparent wind Angle',
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
            name: 'Range',
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
        },
        {
            name: 'Aparrent Wind Speed',
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
            name: 'Range',
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
        }
        ],

    }
)});

