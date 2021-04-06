<?php
namespace frontend\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use frontend\modules\walmart\components\Data;

/**
* ChatServer controller
*/
class ChatServerController extends Controller 
{
  public $host = 'localhost';
  public $port = '9002';
  public $socket = '';
  public $clients = [];
  public $data = ['user_type'=>'merchant','id'=>false];
  public $liveMembers = [];
  public $resourcesMap = [];
  public function actionIndex($data=false) 
  {
  		echo 'Start'.PHP_EOL;
  		/*if(!$data){
  			$merchant_id=Yii::$app->user->identity->id;
  			$this->data['id'] = $merchant_id;
  		}*/
       //Create TCP/IP sream socket
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		//reuseable port
		socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);

		//bind socket to specified host
		socket_bind($this->socket, 0, $this->port);
		echo 'socket Bind'.PHP_EOL;
		//listen to port
		socket_listen($this->socket);
		echo 'socket listen'.PHP_EOL;
		//create & add listning socket to the list
		$this->clients = array($this->getResourceId($this->socket)=>$this->socket);
		//start endless loop, so that our script doesn't stop
		while (true) {

			//manage multipal connections
			$changed = $this->clients;
			//returns the socket resources in $changed array
			socket_select($changed, $null, $null, 0, 10);
			
			//check for new socket
			if (in_array($this->socket, $changed)) {

				print_r($_GET);
				$socket_new = socket_accept($this->socket); //accpet new socket
				echo 'Accept new socket'.PHP_EOL;
				$this->clients[$this->getResourceId($socket_new)] = $socket_new; //add socket to client array
				var_dump($this->getResourceId($socket_new));
				echo 'Reading new socket'.PHP_EOL;
				$header = socket_read($socket_new, 1024); //read data sent by the socket
				$this->performHandshaking($header, $socket_new, $this->host, $this->port); //perform websocket handshake
				socket_getpeername($socket_new, $ip); //get ip address of connected socket
				
				
				
				//make room for new socket
				$found_socket = array_search($this->socket, $changed);
				unset($changed[$found_socket]);
			}
			
			//loop through all connected sockets
			foreach ($changed as $changed_socket) {	
				echo 'looping sockets';
				//check for any incomming data
				while(socket_recv($changed_socket, $buf, 1024, 0) >= 1)
				{

					echo 'Updated Resource :'.$this->getResourceId($changed_socket).PHP_EOL;
					$resourceId = $this->getResourceId($changed_socket);;
					echo 'recieveMessageFromClient'.PHP_EOL;
					$received_text = $this->unMask($buf); //unmask data
					$tst_msg = json_decode($received_text,true); //json decode 
					if(isset($tst_msg['hash'])){
						if($data = $this->getHashData($tst_msg['hash'])){

							
							$data['resource_id'] = $resourceId;
							if($data['sender_type']=='group'){
								echo 'Adding Member To Group'.PHP_EOL;
								$this->liveMembers[$data['sender_type']][$data['group_id']][/*$data['sender_ref']*/] = $data;
							}
							elseif($data['sender_type']=='customer'){
								$this->liveMembers[$data['sender_type']][$data['sender_ref']] = $data;
								$msgData = array('message_type'=>'status', 'message'=>'connected','user_id'=>$data['sender_ref']);
								//notify to shopify walmart team that client is connected
								$this->sendMessageToGroupMembers('shopify_walmart_team',$msgData);
							}
							$this->resourcesMap[$resourceId] = $data;

							
							print_r($this->resourcesMap);
						}
						else{
							echo 'Hash Not Found'.PHP_EOL;
						}
						echo 'recieved Hash:'.$tst_msg['hash'];
					}else
					{

						if(isset($tst_msg['message'])){
							echo 'Got Message'.PHP_EOL;

							if(isset($this->resourcesMap[$resourceId])){
								echo 'Sending Message'.PHP_EOL;
								$this->sendMessage($this->resourcesMap[$resourceId],$tst_msg);
							}
							else
							{
								print_r($this->resourcesMap);
								echo 'Resource '.$resourceId.' Not Found'.PHP_EOL;
							}
						}
					}
					
					break 2; //exist this loop
				}
				
				$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
				if ($buf === false) { // check disconnected client
					// remove client for $this->clients array

					$this->clientDisconnected($this->getResourceId($changed_socket));
					
					
					//notify all users about disconnected connection
					/*$response = $this->mask(json_encode(array('type'=>'system', 'message'=>$ip.' disconnected')));
					$this->sendMessage($response);*/
				}
			}
		      
		}
		// close the listening socket
		socket_close($this->socket);
	}

	public function clientDisconnected($resourceId){

		$data = $this->resourcesMap[$resourceId];
		echo 'disconnected'.$data['sender_ref'].PHP_EOL;
		if($data['sender_type']=='customer' ){
			$response = array('message_type'=>'status', 'message'=>'disconnected','user_id'=>$data['sender_ref']);
			//notify to shopify walmart team that client is connected
			
			if(isset($this->liveMembers['customer'][$data['sender_ref']]) && $this->liveMembers['customer'][$data['sender_ref']]['resource_id']==$resourceId){
				$this->sendMessageToGroupMembers('shopify_walmart_team',$response);
				$this->setInactive($data['sender_ref']);
				unset($this->liveMembers['customer'][$data['sender_ref']]);
			}
			unset($this->resourcesMap[$resourceId]);
		}
		else
		{
			unset($this->resourcesMap[$resourceId]);
		}
		unset($this->clients[$resourceId]);
	}
	public function getHashData($hash){
		$directory = \Yii::getAlias('@webroot').'/frontend/view/chat/hash/';
		if(file_exists($directory.'/'.$hash)){
			return json_decode(file_get_contents($directory.'/'.$hash),true);
		}
		return false;
	}
	public function getResourceId($resource){
		$idString = (string)$resource;
		return str_replace('Resource id #', '', $idString);
	}

	public function sendMessage($resourceMappingData,$messageData)
	{
		if($resourceMappingData['sender_type']=='customer'){
			$msgData = array('message_type'=>'message', 'user_id'=>$resourceMappingData['sender_ref'],'sender_name'=>$resourceMappingData['sender_name'], 'message'=>$messageData['message']);
			echo 'Sending to client '.PHP_EOL;
			$this->sendMessageTo($msgData,$resourceMappingData['resource_id']);
			if($resourceMappingData['reciever_type']=='group'){
				echo 'Sending to group '.PHP_EOL;
				$this->sendMessageToGroupMembers($resourceMappingData['group_id'],$msgData);
			}
		}
		elseif($resourceMappingData['sender_type']=='group'){
			if(isset($this->liveMembers['customer']) && isset($this->liveMembers['customer'][$resourceMappingData['reciever_ref']]) ){
				$msgData = array('message_type'=>'message', 'user_id'=>$resourceMappingData['group_id'], 'sender_name'=>$resourceMappingData['sender_name'], 'message'=>$messageData['message']);
				$customerResourceId = $this->liveMembers['customer'][$messageData['id']]['resource_id'];
				$this->sendMessageTo($msgData,$customerResourceId);
				echo 'Sending to group '.PHP_EOL;
				$this->sendMessageToGroupMembers($resourceMappingData['group_id'],$msgData);
			}
			else
			{
				$msgData = array('message_type'=>'message', 'user_id'=>$resourceMappingData['group_id'], 'sender_name'=>$resourceMappingData['sender_name'], 'message'=>$messageData['message']);
				echo 'Customer is not active....'.PHP_EOL;
				echo 'Sending to group '.PHP_EOL;
				$this->sendMessageToGroupMembers($resourceMappingData['group_id'],$msgData);
				
			}
		}
		
		return true;
	}

	public function sendMessageToGroupMembers($groupId,$msgData){
		$msg = $this->mask(json_encode($msgData));
		if(isset($this->liveMembers['group'])){
			echo 'group members :';
			print_r($this->liveMembers['group']);
			foreach($this->liveMembers['group'][$groupId] as $data){
				@socket_write($this->clients[$data['resource_id']],$msg,strlen($msg));
			}
		}
	}
	public function sendMessageTo($msgData,$customerResourceId)
	{
		if($msgData['message_type']=='message'){
			$this->saveMessage($msgData,$this->resourcesMap[$customerResourceId]);
		}
		$msg = $this->mask(json_encode($msgData));
		$recieverSocket = $this->clients[$customerResourceId];
		@socket_write($recieverSocket,$msg,strlen($msg));
		
		return true;
	}


	public function setInactive($merchantId){
		Data::sqlRecords('UPDATE client_chat SET active=0 WHERE merchant_id='.$merchantId,null,'update');
	}
	public function saveMessage($messageData,$resourceData){
		
		$data = Data::sqlRecords('select conversation from client_chat where merchant_id='.$resourceData['sender_ref'],null,'one');
		if($data){
			print_r($data);
			$conversation = json_decode($data[0]['conversation'],true);
			$conversation[] = $messageData;
			$conversation = addslashes(json_encode($conversation));
			Data::sqlRecords("update client_chat set conversation='{$conversation}' where merchant_id={$resourceData['sender_ref']}",null,'update');
		}
		else
		{
			$conversation = [$messageData];
			$conversation = addslashes(json_encode($conversation));
			Data::sqlRecords("insert into client_chat (merchant_id,conversation,active) values({$resourceData['sender_ref']},'{$conversation}','1')",null,'insert');

		}
		
		
	}
	public function broadcastMessage($msg){
		foreach($this->clients as $cient){
			@socket_write($cient,$msg,strlen($msg));
		}
	}

	//Unmask incoming framed message
	public function unMask($text) {
		$length = ord($text[1]) & 127;
		if($length == 126) {
			$masks = substr($text, 4, 4);
			$data = substr($text, 8);
		}
		elseif($length == 127) {
			$masks = substr($text, 10, 4);
			$data = substr($text, 14);
		}
		else {
			$masks = substr($text, 2, 4);
			$data = substr($text, 6);
		}
		$text = "";
		for ($i = 0; $i < strlen($data); ++$i) {
			$text .= $data[$i] ^ $masks[$i%4];
		}
		return $text;
	}

	//Encode message for transfer to client.
	public function mask($text)
	{
		$b1 = 0x80 | (0x1 & 0x0f);
		$length = strlen($text);
		
		if($length <= 125)
			$header = pack('CC', $b1, $length);
		elseif($length > 125 && $length < 65536)
			$header = pack('CCn', $b1, 126, $length);
		elseif($length >= 65536)
			$header = pack('CCNN', $b1, 127, $length);
		return $header.$text;
	}

	//handshake new client.
	public function performHandshaking($receved_header,$client_conn, $host, $port)
	{
		$headers = array();
		$lines = preg_split("/\r\n/", $receved_header);
		foreach($lines as $line)
		{
			$line = chop($line);
			if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
			{
				$headers[$matches[1]] = $matches[2];
			}
		}

		$secKey = $headers['Sec-WebSocket-Key'];
		$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
		//hand shaking header
		$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
		"Upgrade: websocket\r\n" .
		"Connection: Upgrade\r\n" .
		"WebSocket-Origin: {$this->host}\r\n" .
		"WebSocket-Location: ws://{$this->host}:{$this->port}/demo/shout.php\r\n".
		"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
		socket_write($client_conn,$upgrade,strlen($upgrade));
	}
}