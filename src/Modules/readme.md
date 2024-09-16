[Module](Module)

    Module classes can use External classes
[External](External)

    External classes can use Internal classes
[Internal](Internal)

    Internal classes can use Core classes
These [Cron](Module/Cron) modules are attached to [cron.php](../deamon/cron.php) via [register.php](register.php). 

These [Realtime](Module/Realtime) modules are attached to [RealtimeDistributor.php](Internal/RealtimeDistributor.php) via [register.php](register.php).

Modules must be registered in [register.php](register.php)

Each module is attached via its own Bootstrap class, for example [Bootstrap.php](Module/Cron/AnchorWatch/Bootstrap.php), 
which implements the interface [InterfaceObserverCronWorker.php](Internal/Interfaces/InterfaceObserverCronWorker.php) for [cron.php](../deamon/cron.php) 
and [InterfaceObserverRealtime.php](Internal/Interfaces/InterfaceObserverRealtime.php) 
for [RealtimeDistributor.php](Internal/RealtimeDistributor.php) ([deamon.php](../deamon/deamon.php)). 