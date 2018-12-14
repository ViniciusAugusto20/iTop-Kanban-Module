<?php

$estado = $_POST['estado'];
$id = $_POST['id'];
$auto_solved = "Resolvido automaticamente pelo Kanban";

    if($estado == "Aguardando atendimento")
    {
       $payload = array(
			"operation" => "core/apply_stimulus",
			"comment" => "Synchronization from blah...",
			"class" => "WorkOrder",
			"key" => $id,
			"stimulus" => "ev_aguardando",
			"output_fields" => "id",
			'fields' => array(
			),
		);
    }

    else if($estado == "Em atendimeto")
    {
       $payload = array(
			"operation" => "core/apply_stimulus",
			"comment" => "Synchronization from blah...",
			"class" => "WorkOrder",
			"key" => $id,
			"stimulus" => "ev_atendido",
			"output_fields" => "id",
			'fields' => array(
				'tipoworkorder_id' => $tarefa,
				'agent_id' => "1",
			),
		);	
    }

    else if($estado == "Resolvido")
    {
            $payload = array(
			"operation" => "core/apply_stimulus",
			"comment" => "Synchronization from blah...",
			"class" => "WorkOrder",
			"key" => $id,
			"stimulus" => "ev_reseolved",
			"output_fields" => "id",
			'fields' => array(
				'resolution' => $auto_solved,
			),
		);
    }


	$ITOP_URL = 'http://127.0.0.1/www';
    $ITOP_USER = 'admin';
    $ITOP_PWD = 'admin';
    $TICKET_CLASS = 'WorkOrder';
    $TITLE = 'Service Down on %1$s';
    $DESCRIPTION = "Solicitação gerada automaticamente.";
    $COMMENT = 'Atendido do Kanban da mudança';
    $url = $ITOP_URL . '/webservices/rest.php?version=1.3';
    $tarefa = '203';
	
	$data = array(
        'auth_user' => $ITOP_USER,
        'auth_pwd' => $ITOP_PWD,
        'json_data' => json_encode($payload)
    );
	
    $options = array(
        CURLOPT_POST => count($data),
        CURLOPT_POSTFIELDS => http_build_query($data),
        // Various options...
        CURLOPT_RETURNTRANSFER => true,     // return the content of the request
        CURLOPT_HEADER => false,    // don't return the headers in the output
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING => "",       // handle all encodings
        CURLOPT_AUTOREFERER => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT => 120,      // timeout on response
        // Disabling SSL verification
        CURLOPT_SSL_VERIFYPEER => false,    // Disable SSL certificate validation
        CURLOPT_SSL_VERIFYHOST => false,     // Disable host vs certificate validation
    );

    $handle = curl_init($url);
    curl_setopt_array($handle, $options);
    $response = curl_exec($handle);
    $errno = curl_errno($handle);
    $error_message = curl_error($handle);
    curl_close($handle);

?>