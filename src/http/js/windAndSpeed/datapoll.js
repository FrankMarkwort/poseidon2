var socket = new WebSocket('ws://' + hostPort);

socket.onmessage = function (event) {
    var daten = JSON.parse(event.data);
    //console.log('Received data:', daten);
    if(typeof daten.testmsg != 'undefined' && daten.testmsg != null) {
       // console.log('Received data testmsg:', daten.testmsg);
        var awa = parseInt(daten.testmsg.awa);
        var twa = parseInt(daten.testmsg.twa);
        var twd = parseInt(daten.testmsg.twd);
        var aws = parseInt(360 / 40 * daten.testmsg.aws);
        var tws = parseInt(360 / 40 * daten.testmsg.tws);
        var sog = parseInt(daten.testmsg.sog);
        var vmg = parseInt(daten.testmsg.vmg);
        chart0.series[0].points[0].update(awa);
        chart0.series[1].points[0].update(twa);
        chart0.series[2].points[0].update(twd);
        chart0.series[3].points[0].update(aws);
        chart0.series[4].points[0].update(tws);
        chart1.series[0].points[0].update(sog);
        chart1.series[1].points[0].update(vmg);
        chart3.series[0].points[0].update(awa);
    }
};