alter table wind_speed_minute
    add waterTemperature double default 0 not null;

alter table wind_speed_hour
    add avgWaterTemperature double default 0 not null;

alter table wind_speed_hour
    add minWaterTemperature double default 0 not null;

alter table wind_speed_hour
    add maxWaterTemperature double default 0 not null;

drop trigger generateHourStatistics;

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
                avgVesselHeading, maxVesselHeading, minVesselHeading,
                avgWaterTemperature, maxWaterTemperature, minWaterTemperature
            )
            select
                avg(twd), max(twd), min(twd),
                avg(aws), max(aws), min(aws),
                avg(awa), max(awa), min(awa),
                avg(tws), max(tws), min(tws),
                avg(twa), max(twa), min(twa),
                avg(cog), max(cog), min(cog),
                avg(sog), max(sog), min(sog),
                avg(vesselHeading), max(vesselHeading), min(vesselHeading),
                avg(waterTemperature), max(waterTemperature), min(waterTemperature)
            from wind_speed_minute;
        END IF;
END;


