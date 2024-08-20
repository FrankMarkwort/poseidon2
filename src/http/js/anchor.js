var host = '192.168.0.101';
(async () => {

    var chartData = await fetch(
       'http://' + host + '/api/anchorJson.php'
    ).then(response => response.json());
    var isSetAnchor = false;
    if(chartData.isSet === undefined) {
        isSetAnchor = false;
    } else {
        isSetAnchor = chartData.isSet;
    }
    var chart = Highcharts.mapChart('container', {
        chart: {
            margin: 0,
            events: {
                load() {
                    const chart = this,
                        ancorMeter = document.getElementById('ancorMeter'),
                        setAncor =  document.getElementById('setAncor'),
                        providerSelect = document.getElementById('provider'),
                        themeSelect = document.getElementById('theme'),
                        apikeyInput = document.getElementById('apikey'),
                        submitAPIkeyBtn = document.getElementById(
                            'submitAPIkey'
                        ),
                        { TilesProviderRegistry } = Highcharts;

                    function updateTWM() {
                        chart.series[0].update({
                            provider: {
                                type: providerSelect.value,
                                theme: themeSelect.value,
                                apiKey: apikeyInput.value
                            }
                        });
                    }
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

                    function loadThemes(key) {
                        const {
                            themes
                        } = new TilesProviderRegistry[key]();
                        Object.keys(themes).forEach(themeKey => {
                            const themeOpt = document.createElement('option');
                            themeOpt.value = themeKey;
                            themeOpt.innerHTML = themeKey;
                            themeSelect.appendChild(themeOpt);
                        });
                    }

                    Object.keys(TilesProviderRegistry).forEach(key => {
                        const providerOpt = document.createElement('option');
                        providerOpt.value = key;
                        providerOpt.innerHTML = key;
                        providerSelect.appendChild(providerOpt);
                    });
                    loadThemes(providerSelect.value);

                    providerSelect.addEventListener('change', function () {
                        apikeyInput.value = '';
                        themeSelect.innerHTML = '';
                        loadThemes(this.value);
                        updateTWM();
                    });
                    setAncor.addEventListener('click', setAncorFu);
                    themeSelect.addEventListener('change', updateTWM);
                    submitAPIkeyBtn.addEventListener('click', updateTWM);
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
                    [chartData.longitude - 0.004, chartData.latitude + 0.004],
                    [chartData.longitude + 0.004, chartData.latitude - 0.004]
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
            name: 'Map',
            provider: {
                type: 'OpenStreetMap',
                theme: 'Standard'
            },
            color: 'rgba(128,128,128,0.3)'
        },
        {
            type: 'mappoint',
            name: 'Boat',
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
                lat: chartData.latitude,
                lon: chartData.longitude
            }]
        }]
    });

    function isSetAnchorFu(data)
    {
        if (data.isSet === undefined) {

            return false;
        }

        return data.isSet;
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

    function updateSeriesColorByName(name, color, index = 0)
    {
        var serie = getSerieByName(name);
        if (serie != null) {
            serie.data[index].color = color;
        }
    }

    function addAnchorPoint(name, latitude, longitude, waterDepth = 22)
    {
        if (! isSerie(name)) {
            chart.addSeries(
                {
                    tooltip: {
                        formatter() {

                            return `
                                x: ${this.lat}, 
                                y: ${this.lon}, 
                                Wassertiefe: ${this.watherDepth}
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
                            lon: longitude,
                            waterDepth: waterDepth
                        }
                    ]
                })
        }
    }

    function addLineFromBotToAnchor(name, alat, along, lat, lon)
    {
         rmSerieByName(name);
         chart.addSeries({
                name: name,
                type: 'map',
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
            type: 'map',
            color: 'rgb(255,0,0)',
            borderColor: 'rgb(255,0,0)',
            states: {
                inactive: {
                    enabled: false
                }
            },
            zIndex: 2,
            data: [
                {
                    name: name,
                    color: 'rgb(255,0,0)',
                    geometry: {
                        type: 'LineString',
                        coordinates: data
                    }
                }]
        })
    }

    function addHeadingLine(name, data)
    {
        rmSerieById('heading');
        chart.addSeries({
            id: 'heading',
            name: name,
            type: 'map',
            color: 'rgb(0,0,0)',
            borderColor: 'rgb(0,0,0)',
            states: {
                inactive: {
                    enabled: false
                }
            },
            zIndex: 2,
            data: [
                {
                    name: name,
                    color: 'rgb(0,0,0)',
                    geometry: {
                        type: 'LineString',
                        coordinates: data
                    }
                }]
        })
    }



    function addBoatPositions(name, dataName, data, force = true)
    {
        if (force) {
            rmSerieByName(name)
        }
        if ((! isSerie(name)) && data.length > 0) {
            chart.addSeries({
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

    function addAnchorCirlesSerie(name, dataName1, dataName2, data1, data2, color1, color2, force= true)
    {
        if (force) {
            rmSerieByName(name)
        }
        if (! isSerie(name)) {
            chart.addSeries({
                name: name,
                type: 'map',
                color: color1,
                states: {inactive: {enabled: false}},
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
            updateSeriesColorByName(name, color1, 0)
            updateSeriesColorByName(name, color2, 1)
        }
    }

    function updateSerieByName(name, index, data)
    {
        serie = getSerieByName(name);
        if (serie != null) {
            serie.data[index].update(data);
        }
    }

    function getChainLength(data)
    {
        if (data.chainLength === undefined) {
            return null;
        }

        return data.chainLength;
    }

    function isNumeric(n)
    {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    setInterval(function() {
        fetch('http://' + host + '/api/anchorJson.php')
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (Object.keys(data).length > 3) {
                    addAnchorCirlesSerie(
                        'AnchorCircle',
                        'AnchorWarnCircle',
                        'AnchorCircle',
                        chartData.anchorWarnCirclePolygon,
                        chartData.anchorCirclePolygon,
                        chartData.anchorColorCirclePolygon,
                        chartData.anchorColorCirclePolygon,
                        false
                    );
                    addLineFromBotToAnchor('chain',data.anchorLatitude, data.anchorLongitude, data.latitude, data.longitude);
                    addAnchorPoint('Anchor', data.anchorLatitude, data.anchorLongitude);
                    addBoatPositions('BoatPositions', 'positions', data.anchorHistory, force = true)
                    updateSerieByName('Boat', 0, {
                        lat: data.latitude,
                        lon: data.longitude,
                        waterDepth: data.waterDepth
                    });
                    addAwaLine(data.awaLabel, data.awaLine);
                    addHeadingLine(data.headingLabel, data.headingLine);
                } else {
                    rmSerieByName('Anchor');
                    rmSerieByName('BoatPositions');
                    rmSerieByName('AnchorCircle');
                    rmSerieByName('chain')
                }

                isSetAnchor = data.isSet;
                if (isSetAnchorFu(data)) {
                    document.getElementById("setAncor").innerText = 'unset Ancor';
                    document.getElementById("setAncor").disabled = false;
                    document.getElementById("ancorMeter").value = getChainLength(data);
                    document.getElementById("ancorMeter").readOnly = true;
                    document.getElementById("ancorMeter").disabled = true;

                } else if(isNumeric(data.chainLength)) {
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
                if(data.hasAlarm) {
                    audio = new Audio('http://192.168.0.101/sound/alertAlarm.wav');
                    audio.play();
                }
            });
    }, 1000 * 60); //1000 means 1 sec, 5000 means 5 seconds
})();

function playSound()
{
    let audio = new Audio('http://192.168.0.101/sound/alertAlarm.wav');
    //audio.muted = true;
    //audio.play();
}
