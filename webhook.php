<?php
    const TOKEN_HAROM = "HAROMDEVPHPAPIMETAS";
    const WEBHOOK_URL = "https://apibotzito.haromdev.com/webhook.php";

    require_once("config/conexion.php");
    require_once("models/Registro.php");
    require_once("models/Usuario.php");

    function verificarToken($req,$res){
        try{
            $token = $req['hub_verify_token'];
            $challenge = $req['hub_challenge'];

            if (isset($challenge) && isset($token) && $token == TOKEN_HAROM){
                $res->send($challenge);
            }else{
                $res ->status(400)->send();
            }

        }catch(Exception $e){
            $res ->status(400)->send();
        }
    }

    function recibirMensajes($req,$res){
        try{
            $entry = $req['entry'][0];
            $changes = $entry['changes'][0];
            $value = $changes['value'];
            $objetomensaje = $value['messages'];

            if ($objetomensaje){
                $messages  = $objetomensaje[0];

                if(array_key_exists("type",$messages)){
                    $tipo = $messages["type"];

                    if($tipo == "interactive"){
                        $tipo_interactivo = $messages["interactive"]["type"];

                        if($tipo_interactivo == "button_reply"){

                            $comentario = $messages["interactive"]["button_reply"]["id"];
                            $numero = $messages['from'];

                            EnviarMensajeWhastapp($comentario,$numero);

                            $registro = new Registro();
                            $registro->insert_registro($numero,$comentario);

                        }else if($tipo_interactivo == "list_reply"){

                            $comentario = $messages["interactive"]["list_reply"]["id"];
                            $numero = $messages['from'];

                            EnviarMensajeWhastapp($comentario,$numero);

                            $registro = new Registro();
                            $registro->insert_registro($numero,$comentario);

                        }

                    }

                    if (array_key_exists("text",$messages)){
                        $comentario = $messages['text']['body'];
                        $numero = $messages['from'];

                        EnviarMensajeWhastapp($comentario,$numero);

                        $registro = new Registro();
                        $registro->insert_registro($numero,$comentario);
                    }

                }
            }

            echo json_encode(['message' => 'EVENT_RECEIVED']);
            exit;
        }catch(Exception $e){
            echo json_encode(['message' => 'EVENT_RECEIVED']);
            exit;
        }
    }

    function EnviarMensajeWhastapp($comentario,$numero){
        $comentario = strtolower($comentario);

        if (strpos($comentario, 'hola') !== false || strpos($comentario, 'holi') !== false || strpos($comentario, 'a') !== false || strpos($comentario, 'amigo') !== false || strpos($comentario, 'xd') !== false) {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "Hola Bienvenido andercon-bastidas.com"
                ]
            ]);
        }else if ($comentario=='1') {
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
                ]
            ]);
        }else if ($comentario=='2') {
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "location",
                "location"=> [
                    "latitude" => "-12.067158831865067",
                    "longitude" => "-77.03377940839486",
                    "name" => "Estadio Nacional del Perú",
                    "address" => "Cercado de Lima"
                ]
            ]);
        }else if ($comentario=='3') {
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "document",
                "document"=> [
                    "link" => "http://s29.q4cdn.com/175625835/files/doc_downloads/test.pdf",
                    "caption" => "Temario del Curso #001"
                ]
            ]);
        }else if ($comentario=='4') {
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "audio",
                "audio"=> [
                    "link" => "https://filesamples.com/samples/audio/mp3/sample1.mp3",
                ]
            ]);
        }else if ($comentario=='5') {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "to" => $numero,
                "text" => array(
                    "preview_url" => true,
                    "body" => "Introducción al curso! https://youtu.be/6ULOE2tGlBM"
                )
            ]);
        }else if ($comentario=='6') {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => "🤝 En breve me pondré en contacto contigo. 🤓"
                )
            ]);
        }else if ($comentario=='7') {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => "📅 Horario de Atención: Lunes a Viernes. \n🕜 Horario: 9:00 a.m. a 5:00 p.m. 🤓"
                )
            ]);
        }else if (strpos($comentario,'gracias') !== false) {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => "Gracias a ti por contactarme. 🤩"
                )
            ]);
        }else if (strpos($comentario,'adios') !== false || strpos($comentario,'bye') !== false || strpos($comentario,'nos vemos') !== false || strpos($comentario,'adiós') !== false){
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => "Hasta luego. 🌟"
                )
            ]);
        }else if (strpos($comentario,'boton') !== false){
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "interactive",
                "interactive" => [
                    "type" => "button",
                    "body" => [
                        "text" => "¿Confirmas tu registro?"
                    ],
                    "footer" => [
                        "text" => "Selecciona una de las opciones"
                    ],
                    "action" => [
                        "buttons" => [
                            [
                                "type" => "reply",
                                "reply" => [
                                    "id" => "btnsi",
                                    "title" => "Si"
                                ]
                            ],[
                                "type" => "reply",
                                "reply" => [
                                    "id" => "btnno",
                                    "title" => "No"
                                ]
                            ],[
                                "type" => "reply",
                                "reply" => [
                                    "id" => "btntalvez",
                                    "title" => "Tal Vez"
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        }else if (strpos($comentario,'btnsi') !== false){
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "Muchas gracias por Aceptar."
                ]
            ]);
        }else if (strpos($comentario,'btnno') !== false){
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "Es una lastima."
                ]
            ]);
        }else if (strpos($comentario,'btntalvez') !== false){
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "Estare a la espera."
                ]
            ]);
        }else if (strpos($comentario,'lista') !== false){
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "to" => $numero,
                "type" => "interactive",
                "interactive" => [
                    "type" => "list",
                    "body" => [
                        "text" => "Seleccionar alguna opcion"
                    ],
                    "footer" => [
                        "text" => "Seleciona una de las opciones para poder ayudarte"
                    ],
                    "action" => [
                        "button" => "Ver Opciones",
                        "sections" => [
                            [
                                "title" => "Compra y Venta",
                                "rows" => [
                                    [
                                    "id" => "btncompra",
                                    "title" => "Comprar",
                                    "description" => "Compra los mejores articulos de tecnologia"
                                    ],[
                                        "id" => "btnvender",
                                        "title" => "Vender",
                                        "description" => "Vende lo que ya no estes usando"
                                    ]
                                ]
                            ],[
                                "title" => "Distribución y entrega",
                                "rows" => [
                                    [
                                        "id" => "btndireccion",
                                        "title" => "Local",
                                        "description" => "Puedes Visitar nuestro local"
                                    ],[
                                        "id" => "btnentrega",
                                        "title" => "Entrega",
                                        "description" => "La entrega se realiza todos los dias"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        }else if (strpos($comentario,'btncompra') !== false){
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "Muchas gracias por Comprar."
                ]
            ]);
        }else if (strpos($comentario,'btnvender') !== false){
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "Muchas gracias por Vender."
                ]
            ]);
        }else if (strpos($comentario,'btndireccion') !== false){
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "La direccion esta en su factura."
                ]
            ]);
        }else if (strpos($comentario,'btnentrega') !== false){
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "La entrega se realizara durante el dia."
                ]
            ]);
        }else if (strpos($comentario,'dni:')!== false){
            $texto_sin_dni = str_replace("dni: ", "", $comentario);

            $usuario = new Usuario();
            $datos = $usuario->get_usuario($texto_sin_dni);

            if(is_array($datos)==true and count($datos)>0){
                $data = json_encode([
                    "messaging_product" => "whatsapp",
                    "recipient_type"=> "individual",
                    "to" => $numero,
                    "type" => "text",
                    "text"=> [
                        "preview_url" => false,
                        "body"=> "El Dni solicitado es de: ".$datos[0]["usu_nom"]." ".$datos[0]["usu_ape"].""
                    ]
                ]);
            }else{
                $data = json_encode([
                    "messaging_product" => "whatsapp",
                    "recipient_type"=> "individual",
                    "to" => $numero,
                    "type" => "text",
                    "text"=> [
                        "preview_url" => false,
                        "body"=> "El nro de Dni Ingresado no existe."
                    ]
                ]);
            }

        }else{
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "🚀 Hola, visita mi web anderson-bastidas.com para más información.\n \n📌Por favor, ingresa un número #️⃣ para recibir información.\n \n1️⃣. Información del Curso. ❔\n2️⃣. Ubicación del local. 📍\n3️⃣. Enviar temario en pdf. 📄\n4️⃣. Audio explicando curso. 🎧\n5️⃣. Video de Introducción. ⏯️\n6️⃣. Hablar con AnderCode. 🙋‍♂️\n7️⃣. Horario de Atención. 🕜"
                ]
            ]);
        }

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/json\r\nAuthorization: Bearer EAAFcDgOwnOoBO6ZAdNTDyRdDMhpkpssdTGWAy1VDJISgw1aBsKfGXsaI2FEvZAyUirwrZCheVfZAFKoiAL4O6wY6CK24jwpRMQaCeBla8BEZCgoy16wXqxsuP8jquXRroSiwMRHMhjdbDmt5FjwBdZCWQBKUcglZBoD0KFxT5BTNL27DJFGm7eUVTNdmZBb1pzKxgA4CTbCJKVev88USnqiw\r\n",
                'content' => $data,
                'ignore_errors' => true
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents('https://graph.facebook.com/v20.0/397911870072585/messages', false, $context);

        if ($response === false) {
            echo "Error al enviar el mensaje\n";
        } else {
            echo "Mensaje enviado correctamente\n";
        }
    }

    if ($_SERVER['REQUEST_METHOD']==='POST'){
        $input = file_get_contents('php://input');
        $data = json_decode($input,true);

        recibirMensajes($data,http_response_code());
    }else if($_SERVER['REQUEST_METHOD']==='GET'){
        if(isset($_GET['hub_mode']) && isset($_GET['hub_verify_token']) && isset($_GET['hub_challenge']) && $_GET['hub_mode'] === 'subscribe' && $_GET['hub_verify_token'] === TOKEN_HAROM){
            echo $_GET['hub_challenge'];
        }else{
            http_response_code(403);
        }
    }
?>