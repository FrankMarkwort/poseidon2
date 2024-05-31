use nmea2000;
drop SEQUENCE wind_speed_minute_seq;
CREATE SEQUENCE wind_speed_minute_seq START WITH 1 INCREMENT BY 1 MINVALUE=1 MAXVALUE=60 CYCLE;

drop TABLE wind_speed_minute;

create table wind_speed_minute
(
    id_minute     int default nextval(`nmea2000`.`wind_speed_minute_seq`) not null
        primary key,
    timestamp     time                                                    not null,
    twd           double                                                  not null,
    aws           double                                                  not null,
    awa           double                                                  not null,
    tws           double                                                  not null,
    twa           double                                                  not null,
    cog           double                                                  not null,
    sog           double                                                  not null,
    vesselHeading double                                                  not null
);

drop table wind_speed_hour;
create table wind_speed_hour
(
    date   datetime default current_timestamp() not null primary key,
    avgTwd double                               not null,
    maxTwd double                               not null,
    minTwd double                               not null,
    avgAws double                               not null,
    maxAws double                               not null,
    minAws double                               not null,
    avgAwa double                               not null,
    maxAwa double                               not null,
    minAwa double                               not null,
    avgTws double                               not null,
    maxTws double                               not null,
    minTws double                               not null,
    avgTwa double                               not null,
    maxTwa double                               not null,
    minTwa double                               not null,
    avgCog       double                               not null,
    maxCog       double                               not null,
    minCog       double                               not null,
    avgSog       double                               not null,
    maxSog       double                               not null,
    minSog       double                               not null,
    avgVesselHeading      double                               not null,
    maxVesselHeading       double                               not null,
    minVesselHeading       double                               not null
);

create definer = nmea2000@`%` trigger generateHourStatistics
    after insert
    on wind_speed_minute
    for each row
BEGIN
    IF NEW.id_minute >= 60 THEN
            insert into wind_speed_hour(
                avgTwd, maxTwd, minTwd,
                avgAws, maxAws, minAws,
                avgAwa, maxAwa, minAwa,
                avgTws, maxTws, minTws,
                avgTwa, maxTwa, minTwa,
                avgCog, maxCog, minCog,
                avgSog, maxSog, minSog,
                avgVesselHeading, maxVesselHeading, minVesselHeading
            )
            select
                avg(twd), max(twd), min(twd),
                avg(aws), max(aws), min(aws),
                avg(awa), max(awa), min(awa),
                avg(tws), max(tws), min(tws),
                avg(twa), max(twa), min(twa),
                avg(cog), max(cog), min(cog),
                avg(sog), max(sog), min(sog),
                avg(vesselHeading), max(vesselHeading), min(vesselHeading)
            from wind_speed_minute;
        END IF;
END;
