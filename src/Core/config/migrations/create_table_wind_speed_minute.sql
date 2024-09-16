use nmea2000;
#drop SEQUENCE wind_speed_minute_seq;
CREATE SEQUENCE wind_speed_minute_seq START WITH 1 INCREMENT BY 1 MINVALUE=1 MAXVALUE=60 CYCLE;

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

create table wind_speed_hour
(
    id               int auto_increment
        primary key,
    date             datetime default current_timestamp() not null,
    avgTwd           double                               not null,
    maxTwd           double                               not null,
    minTwd           double                               not null,
    avgAws           double                               not null,
    maxAws           double                               not null,
    minAws           double                               not null,
    avgAwa           double                               not null,
    maxAwa           double                               not null,
    minAwa           double                               not null,
    avgTws           double                               not null,
    maxTws           double                               not null,
    minTws           double                               not null,
    avgTwa           double                               not null,
    maxTwa           double                               not null,
    minTwa           double                               not null,
    avgCog           double                               not null,
    maxCog           double                               not null,
    minCog           double                               not null,
    avgSog           double                               not null,
    maxSog           double                               not null,
    minSog           double                               not null,
    avgVesselHeading double                               not null,
    maxVesselHeading double                               not null,
    minVesselHeading double                               not null,
    constraint wind_speed_hour_pk
        unique (date)
);
DROP FUNCTION if exists Vavg;
CREATE FUNCTION Vavg (avgSinDir FLOAT,avgCosDir FLOAT, is180Deg BOOL)
RETURNS FLOAT DETERMINISTIC
begin
     IF (avgSinDir = 0 AND avgCosDir = 0) THEN
        return 0.0;
    end if;
    if (is180Deg) THEN
        return mod(degrees(atan2(avgSinDir, avgCosDir)), 180);
    end if;
    if (avgSinDir < 0) then
        return mod(degrees(atan2(avgSinDir, avgCosDir)) + 360, 360);
    else
        return mod(degrees(atan2(avgSinDir, avgCosDir)), 360);
    end if;
end;

DROP TRIGGER if exists generateHourStatistics;
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
                vavg(sin(RADIANS(twd)), cos(RADIANS(twd)), false), max(twd), min(twd),
                avg(aws), max(aws), min(aws),
                vavg(sin(RADIANS(awa)), cos(RADIANS(awa)), true), max(awa), min(awa),
                avg(tws), max(tws), min(tws),
                vavg(sin(RADIANS(twa)), cos(RADIANS(twa)), true), max(twa), min(twa),
                vavg(sin(RADIANS(cog)), cos(RADIANS(cog)), false), max(cog), min(cog),
                avg(sog), max(sog), min(sog),
                vavg(sin(RADIANS(vesselHeading)), cos(RADIANS(vesselHeading)), false), max(vesselHeading), min(vesselHeading)
            from wind_speed_minute;
        END IF;
END;

create table positions
(
    id                  int auto_increment
        primary key,
    fid_wind_speed_hour int                                  not null,
    timestamp           datetime default current_timestamp() not null,
    latitude            double                               not null,
    longitude           double                               not null,
    constraint positions_wind_speed_hour_id_fk
        foreign key (fid_wind_speed_hour) references wind_speed_hour (id)
            on update cascade on delete cascade
);
