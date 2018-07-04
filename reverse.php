<?php
/** API de Estornar Pedidos - Exemplo de requisição em PHP **/
 
	/*Capturando informações para requisição*/ 
	 
	$Reference = 12060; 
	$Type = 1; 
	$Value = 0; 
	$AdditionalInfo = utf8_encode(''); 
	
	/*Montando o objeto de requisição */

	$transmitObject = array(
	'Reference' => $Reference, //Número do pedido
	'Type' => $Type, //Tipo do estorno: 1 = Total / 2 = Parcial
	'Value' => $Value, //Valor do estorno Parcial(TYPE = 2)
	'AdditionalInfo' => $AdditionalInfo //Motivo do estorno
	);
		
	/*Montando o objeto JSON a partir do objeto de requisição criado($transmitObject)*/
 
	//Criando um JSON
	$jsonObject = json_encode($transmitObject);

	//Definindo a URL de requisição para o ambiente de teste
	//$url = "https://api-rest.koin.com.br/Transaction/reverse";
	$url = "http://api-rest.qa.koin.in/Transaction/reverse";
	
	//Chaves de autenticação - Teste
	//Solicite suas chaves de teste para a Equipe de Integração (integracao@koin.com.br)
	$consumerKey = "1BFCF567A63E4B6FB38F6A22FFA21FFE";
	$secretKey = "50856FDA556747A7860C3295C25FEA26";

	//convertendo o formato do timezone para UTC 
	date_default_timezone_set("UTC");
	
	//Obtendo a hora do servidor
	$time = time();
    
	//Criando o hash de autenticação
	$binaryHash = hash_hmac('sha512', $url.$time, $secretKey, true);
	
	//Convertendo para Base64
	$hash = base64_encode($binaryHash);
	
	//Enviando a requisição
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonObject);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json; 
	charset=utf-8",  "Content-Length:".strlen($jsonObject), 
	"Authorization: {$consumerKey},{$hash},{$time}"));
	
	
	//Recebendo resposta.
	try {
		$response = curl_exec($ch);
		curl_close ($ch);
		//echo $response;
		var_dump($response);
	    } 
	catch (Exception $e) {echo "Exceção: ",  $e->getMessage(), "\n";}
 	
	print $jsonObject;
	
?>
