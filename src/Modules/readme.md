[External](External)

    External classes can use Internal classes
[Internal](Internal)

    Internal classes can use Core classes
[Module](Module)

    Module classes can use External classes
[Cron](Module/Cron)
These modules are attached to [cron.php](../deamon/cron.php). 

[Realtime](Module/Realtime)
These modules are attached to [RealtimeDistributor.php](Internal/RealtimeDistributor.php).

Modules are registered in [register.php](register.php)

Each module is attached via its own Bootstrap class, for example [Bootstrap.php](Module/Cron/AnchorWatch/Bootstrap.php), 
which implements the interface [InterfaceObserverCronWorker.php](Internal/Interfaces/InterfaceObserverCronWorker.php) for [cron.php](../deamon/cron.php) 
and [InterfaceObserverRealtime.php](Internal/Interfaces/InterfaceObserverRealtime.php) 
for [RealtimeDistributor.php](Internal/RealtimeDistributor.php) ([deamon.php](../deamon/deamon.php)). 