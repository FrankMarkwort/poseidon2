/** \
@author Frank Markwort\
@version 0.0.1\
@email frank.markwort@gmail.com\
*/\
The decoder works on 32 bit and 64 bit systems\
\
The deamon assembles the data packets and saves them ready for decoding in the memcached server. The key is the pgn.\
Data format from usb device\
06:26:23.548 R 09FD0270 00 0C 02 11 AA FA FF FF\
\
**Deamon**\
_Configuration_\
 src/Deamon/deamon.php\
 &nbsp;&nbsp;$bootstrap = new Bootstrap(new Serial( '_/dev/ttyACM0_'), (new Memcached(127.0.0.1, 11211))->clear());\

**Display of the decoded data.**\
The data is currently displayed in the browser as HTML or JSON.\
The Apache directory is _src/http/_ \

**.htaccess**\
SetEnv MEMCACHED_HOST 172.17.0.1
