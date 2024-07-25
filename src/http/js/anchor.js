setInterval(() => {
(async () => {
    const chartData = await fetch(
        'http://192.168.0.101/anchorJson.php'
    ).then(response => response.json());
    // Initialize the chart
    Highcharts.mapChart('container', {
        chart: {
            margin: 0,
            events: {
                load() {
                    const chart = this,
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
            name: 'AnchorCircle',
            type: 'map',
            color: 'rgb(0,0,0)',
             states: {
                inactive: {
                    enabled: false
                }
            },
            data: [
                {
                    name: 'AnchorWarnCircle',
                    color: chartData.anchorColorCirclePolygon,
                    geometry: {
                        type: 'Polygon',
                        coordinates: chartData.anchorWarnCirclePolygon
                    }
                },
                {
                    name: 'AnchorCircle',
                    color: chartData.anchorColorCirclePolygon,
                    geometry: {
                        type: 'Polygon',
                        coordinates: chartData.anchorCirclePolygon
                    }
                }
           ]},{
                name: 'AnchorPosition',
                type: 'map',
                bordercolor :  'rgb(252,244,12)',
                color: 'rgb(251,228,1)',
                states: {
                    inactive: {
                        enabled: false
                    }
                },
                data: [
                    {
                        name: 'AnchorPosition',
                        color:'rgba(255,255,255,0)',
                        geometry: {
                            type: 'Polygon',
                            coordinates: chartData.anchorHistory
                        }
                    }
                ]},
        {
            type: 'mappoint',
            name: 'Boat',
            enableMouseTracking: false,
            states: {
                inactive: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: true
            },
            data: [ {
                name: 'Boat',
                lat: chartData.latitude,
                lon: chartData.longitude
            },
            {
                name: 'Ancor',
                color: 'rgb(255,0,0)',
                lat: chartData.anchorLatitude,
                lon: chartData.anchorLongitude
            }
            ]
        },

           ]
    });
})();
}, 60000);