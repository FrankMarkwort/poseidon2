(async () => {
    var chartData = await fetch(
       'http://' + host + '/api/anchorJson.php'
    ).then(response => response.json());
    var isSetAnchor = false;
    if(chartData.base.isSet === undefined) {
        isSetAnchor = false;
    } else {
        isSetAnchor = chartData.base.isSet;
    }
    var chart = Highcharts.mapChart('container', {
        chart: {
            margin: 0,
            events: {
                load() {
                    //chart = this,
                    ancorMeter = document.getElementById('ancorMeter'),
                    setAncor =  document.getElementById('setAncor'),
                    { TilesProviderRegistry } = Highcharts;

                    function setAncorFu() {
                        var meter = parseInt(ancorMeter.value);
                        if (isSetAnchor) {
                            sendRequest('http://' + host + '/api/setAnchor.php?set=false');
                            isSetAnchor = false;
                            setAncor.innerText = 'setAncor';
                            ancorMeter.readOnly = false;
                            ancorMeter.disabled = false;
                            return;
                        }
                        if (!isNaN(meter)) {
                            setAncor.innerText = 'unset Ancor';
                            sendRequest('http://' + host + '/api/setAnchor.php?set=true&meter=' + Math.abs(meter));
                            ancorMeter.readOnly = true;
                            ancorMeter.disabled = true;
                        }
                    }

                    function sendRequest(url) {
                        const http = new XMLHttpRequest();
                        http.open("GET", url);
                        http.send();

                        http.onreadystatechange = (e) => {
                            console.log('done')
                        }
                    }
                    setAncor.addEventListener('click', setAncorFu);
                }
            }
        },
        title: {
            text: null
        },

        mapNavigation: {
            enabled: true,
            buttonOptions: {
                alignTo: 'spacingBox'
            }
        },

        mapView: {
            fitToGeometry: {
                type: 'MultiPoint',
                coordinates: [
                    [chartData.base.longitude - 0.004, chartData.base.latitude + 0.004],
                    [chartData.base.longitude + 0.004, chartData.base.latitude - 0.004]
                ]
            }
        },

        legend: {
            backgroundColor: 'rgba(255,255,255,0.85)',
            align: 'left',
            layout: 'vertical',
            symbolRadius: 0,
            borderRadius: 2,
            itemHiddenStyle: {
                color: 'rgba(128,128,128,0.3)'
            },
            reversed: true
        },
        series: [{
            type: 'tiledwebmap',
            name: '&#128506;',
            provider: {
                type: 'OpenStreetMap',
                theme: 'Standard'
            },
            color: 'rgba(128,128,128,0.3)'
        },
        {
            id: 'boat',
            type: 'mappoint',
            name: '&#9973;',
            states: {
                inactive: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: true
            },
            zIndex: 10,
            data: [ {
                name: 'Boat',
                lat: chartData.base.latitude,
                lon: chartData.base.longitude
            }]
        }]
    });

    function isSetAnchorFu(data)
    {
        if (data.base.isSet === undefined) {

            return false;
        }

        return data.base.isSet;
    }

    function isSerie(name)
    {
        result = false;
        chart.series.forEach((serie) => {
            if (serie.getName() === name) {

                result = true;
            }
        });

        return result
    }

    function getSerieByName(name)
    {
        var result = false;
        chart.series.forEach((serie) => {
            if (serie.getName() === name) {
                result = serie;
            }
        });

        return result
    }

    function getSerieById(id)
    {
        var result = false;
        serie = chart.get(id);
        if (serie) {
            result = serie;
        }

        return result
    }

    function rmSerieByName(name)
    {
        var result = false;
        chart.series.forEach((serie) => {
            if (serie.getName() === name) {
                serie.remove();

                result = true;
            }
        });

        return result
    }

    function rmSerieById(id)
    {
        var serie = chart.get(id);
        if (serie !== undefined) {
            serie.remove();
        }
    }

    function updateSeriesColorById(id, name, color, index = 0)
    {
        var serie = getSerieById(id);
        if (serie != null) {
            serie.data[index].color = color;
        }
    }


    function addAnchorPoint(name, latitude, longitude, waterDepth = 22)
    {
        rmSerieById('anchor');
        if (! isSerie(name)) {
            chart.addSeries(
                {
                    id: 'anchor',
                    tooltip: {
                        formatter()
                        {
                            return `
                                x: ${this.lat}, 
                                y: ${this.lon} 
                            `
                        }
                    },
                    type: 'mappoint',
                    name: name,
                    states: {
                        inactive: {
                            enabled: false
                        }
                    },
                    dataLabels: {
                        enabled: true
                    },
                    zIndex: 10,
                    data: [
                        {
                            name: 'Ancor',
                            color: 'rgb(255,0,0)',
                            lat: latitude,
                            lon: longitude

                        }
                    ]
                })
        }
    }

    function addChaineLine(name, alat, along, lat, lon)
    {
         rmSerieById('chain');
         chart.addSeries({
                id: 'chain',
                name: name,
                type: 'mapline',
                //legendSymbol: 'lineMarker',
                color: 'rgba(127,124,124,0.3)',
                borderColor: 'rgba(127,124,124,0.3)',
                states: {
                    inactive: {
                        enabled: false
                    }
                },
                zIndex: 2,
                data: [
                    {
                        name: name,
                        color: 'rgba(127,124,124,0.3)',
                        geometry: {
                            type: 'LineString',
                            coordinates: [[lon, lat],[along, alat]]
                        }
                    }]
            })
    }

    function addAwaLine(name, data)
    {
        rmSerieById('awaline');
        chart.addSeries({
            id: 'awaline',
            name: name,
            type: 'mapline',
            //legendSymbol: 'lineMarker',
            color: 'rgb(255,0,0)',
            borderColor: 'rgb(255,0,0)',
            states: {
                inactive: {
                    enabled: false
                }
            },
            zIndex: 3,
            data: [
                {
                    name: name,
                    color: 'rgb(255,0,0)',
                    geometry: {
                        type: 'LineString',
                        coordinates: data
                    },
                     lineWidth: 2,
                }]
        })
    }

    function addHeadingLine(name, data)
    {
        rmSerieById('heading');
        chart.addSeries({
            id: 'heading',
            name: name,
            type: 'mapline',
            color: 'rgb(0,34,255)',
            //legendSymbol: 'lineMarker',
            borderColor: 'rgb(0,34,255)',
            states: {
                inactive: {
                    enabled: false
                }
            },
            zIndex: 2,
            data: [
                {
                    name: name,
                    color: 'rgb(0,34,255)',
                    geometry: {
                        type: 'LineString',
                        coordinates: data
                    },
                    lineWidth: 4,
                }]
        })
    }

    function addBoatPositions(name, dataName, data, force = true)
    {
        if (force) {
            rmSerieById('positions');
        }
        if ((! isSerie(name)) && data.length > 0) {
            chart.addSeries({
                id: 'positions',
                name: name,
                type: 'mapline',
                color: 'rgba(225,0,0,0.3)',
                states: {
                    inactive: {
                        enabled: false
                    }
                },
                zIndex: 2,
                data: [
                    {
                        name: dataName,
                        color: 'rgba(255,0,0,0.3)',
                        geometry: {
                            type: 'MultiLineString',
                            coordinates: data
                        }
                    }]
            })
        }
    }

    function addAnchorCirlesSerie(id, name, dataName1, dataName2, data1, data2, color1, color2, force= true)
    {
        if (force) {
            rmSerieById(id)
        }
        if (! chart.get(id)) {
            chart.addSeries({
                id: id,
                name: name,
                type: 'map',
                color: color1,
                states: {inactive: {enabled: false}, hover:{enabled:false}},
                zIndex: 1,
                data: [
                    {
                        name: dataName1,
                        color: color1,
                        geometry: {
                            type: 'Polygon',
                            coordinates: data1
                        }
                    },
                    {
                        name: dataName2,
                        color: color2,
                        geometry: {
                            type: 'Polygon',
                            coordinates: data2
                        }
                    }
                ]
            });
        } else {
            updateSeriesColorById(id, name, color1, 0)
            updateSeriesColorById(id, name, color2, 1)
        }
    }

    function updateSerieById(id, label, index, data)
    {
        serie = getSerieById(id);
        if (serie !== false) {
            serie.update({name: label}, false)
            serie.data[index].update(data);
            //chart.redraw();
        }
    }

    function getChainLength(data)
    {
        if (data.base.chainLength === undefined) {
            return 0;
        }

        return data.base.chainLength;
    }

    function isNumeric(n)
    {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    setInterval(function() {
        fetch('http://' + host + '/api/anchorJson.php')
            .then(function (response) { return response.json(); })
            .then(function (data) {
                updateSerieById('boat', data.base.boatLabel, 0, {
                    lat: data.base.latitude,
                    lon: data.base.longitude
                });
                addAwaLine(data.base.awaLabel, data.base.awaLine);
                addHeadingLine(data.base.headingLabel, data.base.headingLine);
                if (data.ext !== false) {
                    addAnchorCirlesSerie(
                        'AnchorCircle',
                        data.ext.anchorCirclePolygonLabel,
                        'AnchorCircle',
                        'AnchorWarnCircle',
                        chartData.ext.anchorWarnCirclePolygon,
                        chartData.ext.anchorCirclePolygon,
                        chartData.ext.anchorColorCirclePolygon,
                        chartData.ext.anchorColorCirclePolygon,
                        false
                    );
                    addChaineLine(data.ext.chainLabel, data.ext.anchorLatitude, data.ext.anchorLongitude, data.base.latitude, data.base.longitude);
                    addAnchorPoint(data.ext.anchorLabel, data.ext.anchorLatitude, data.ext.anchorLongitude);
                    addBoatPositions('positions', 'positions', data.ext.positionsHistory, force = true)
                } else {
                    rmSerieById('anchor');
                    rmSerieByName('positions');
                    rmSerieById('AnchorCircle');
                    rmSerieById('chain')
                }

                isSetAnchor = data.base.isSet;
                if (isSetAnchorFu(data)) {
                    document.getElementById("setAncor").innerText = 'unset Ancor';
                    document.getElementById("setAncor").disabled = false;
                    document.getElementById("ancorMeter").value = getChainLength(data);
                    document.getElementById("ancorMeter").readOnly = true;
                    document.getElementById("ancorMeter").disabled = true;

                } else if(isNumeric(data.base.chainLength) && data.base.chainLength > 2) {
                    document.getElementById("setAncor").innerText = 'TyToSet';
                    document.getElementById("setAncor").disabled = true;
                    document.getElementById("ancorMeter").value = getChainLength(data);
                    document.getElementById("ancorMeter").readOnly = true;
                    document.getElementById("ancorMeter").disabled = true;
                } else {
                    document.getElementById("setAncor").innerText = 'setAncor';
                    document.getElementById("setAncor").disabled = false;
                    document.getElementById("ancorMeter").readOnly = false;
                    document.getElementById("ancorMeter").disabled = false;
                }
                //document.getElementById('alarmSound').play();
            });
    }, 1000 * 60);
})();
