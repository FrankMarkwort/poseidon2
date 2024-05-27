/** \
@author Frank Markwort\
@version 0.0.1\
@email frank.markwort@gmail.com\
*/\
**Deamon**\
_Configuration_\
 src/Deamon/deamon.php\
    $bootstrap = new Bootstrap(new Serial( '_/dev/ttyACM0_'), (new Memcached())->clear());\
    