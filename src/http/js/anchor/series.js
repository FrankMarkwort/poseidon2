export function isSerie(name)
{
    var result = false;
    chart.series.forEach((serie) => {
        if (serie.getName() === name) {

            result = true;
        }
    });

    return result
}

export function getSerieByName(name)
{
    var result = false;
    chart.series.forEach((serie) => {
        if (serie.getName() === name) {
            result = serie;
        }
    });

    return result
}

export function rmSerieByName(name)
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

export function updateSeriesColorByName(name, color, index = 0)
{
    var serie = getSerieByName(name);
    if (serie != null) {
        serie.data[index].color = color;
    }
}

export function addAnchorOrUpdate(name, latitude, longitude)
{
    if (! isSerie(name)) {
        chart.addSeries(
            {
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
    } else {
        updateSerieByName(name, 0, { lat: latitude, lon: longitude })
    }
}

export function addBoatPositions(name, dataName1, data1, force = false)
{
    if (force) {
        rmSerieByName(name)
    }
    if (! isSerie(name)) {
        chart.addSeries({
            name: name,
            type: 'map',
            bordercolor: 'rgb(0,34,255)',
            color: 'rgb(251,228,1)',
            states: {
                inactive: {
                    enabled: false
                }
            },
            zIndex: 2,
            data: [
                {
                    name: dataName1,
                    color: 'rgba(0,34,255,0)',
                    geometry: {
                        type: 'Polygon',
                        coordinates: data1
                    }
                }]
        })
    }
}

export function addAnchorCirlesSerie(name, dataName1, dataName2, data1, data2, color1, color2, force= true)
{
    if (force) {
        rmSerieByName(name)
    }
    if (! isSerie(name)) {
        chart.addSeries({
            name: name,
            type: 'map',
            color: 'rgb(0,0,0)',
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

export function updateSerieByName(name, index, data)
{
    var serie = getSerieByName(name);
    if (serie != null) {
        serie.data[index].update(data);
    }
}
