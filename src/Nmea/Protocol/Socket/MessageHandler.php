<?php

namespace Nmea\Protocol\Socket;

use Socket;

readonly class MessageHandler implements HandleInterface
{
    public function __construct(private SocketsCollection $clientSockets)
    {
    }
    public function send(mixed $message):true
    {
		$messageLength = strlen($message);
		foreach($this->clientSockets as $clientSocket) {
			@socket_write($clientSocket, $message, $messageLength);
		}

		return true;
	}

	public function unseal(false|string|array $socketData):false|string|array
    {
		$length = ord($socketData[1]) & 127;
		if($length == 126) {
			$masks = substr($socketData, 4, 4);
			$data = substr($socketData, 8);
		}
		elseif($length == 127) {
			$masks = substr($socketData, 10, 4);
			$data = substr($socketData, 14);
		}
		else {
			$masks = substr($socketData, 2, 4);
			$data = substr($socketData, 6);
		}
		$socketData = "";
		for ($i = 0; $i < strlen($data); ++$i) {
			$socketData .= $data[$i] ^ $masks[$i%4];
		}
		return $socketData;
	}

	public function seal(string $socketData):string
    {
		$b1 = 0x80 | (0x1 & 0x0f);
		$length = strlen($socketData);

		if($length <= 125) $header = pack('CC', $b1, $length);
		elseif($length < 65536) $header = pack('CCn', $b1, 126, $length);
        else $header = pack('CCNN', $b1, 127, $length);

		return $header.$socketData;
	}

	public function doHandshake(string $receivedHeader, Socket $clientSocketResource, string $hostName, int $port):void
    {
		$headers = array();
		$lines = preg_split("/\r\n/", $receivedHeader);
		foreach($lines as $line) {
			$line = chop($line);
			if(preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
				$headers[$matches[1]] = $matches[2];
			}
		}
		$secKey = $headers['Sec-WebSocket-Key'];
		$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
		$buffer  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n"
		    . "Upgrade: websocket\r\n"
		    . "Connection: Upgrade\r\n"
		    . "WebSocket-Origin: $hostName\r\n"
		    . "WebSocket-Location: ws://$hostName:$port/windsocket.php\r\n"
		    . "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
		socket_write($clientSocketResource, $buffer, strlen($buffer));
	}

	public function newConnectionAck(string $clientIpAddress):string
    {
		$message = 'New client ' . $clientIpAddress.' joined';
		$messageArray = array('message'=> $message,'message_type'=>'chat-connection-ack');

        return $this->seal(json_encode($messageArray));
	}

	public function connectionDisconnectAck(string $clientIpAddress):string
    {
		$message = 'Client ' . $clientIpAddress.' disconnected';
		$messageArray = array('message'=>$message,'message_type'=>'chat-connection-ack');

        return $this->seal(json_encode($messageArray));
	}

	public function createMessage(string $jsonMessage):string
    {
        return $this->seal($jsonMessage);
	}
}