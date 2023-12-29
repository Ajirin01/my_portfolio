<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $message = "from: ". $data->name. "<br>";
        $message .= "replyto: ". $data->email. "<br>";
        $message .= $data->message;

        // echo json_encode($message);
        // exit;

        if(mail("mubarakolagoke@gmail.com", $data->subject, $message)){
        // if(true){
            echo json_encode(["message"=> "Message successfully sent. We shall get back to you soon. Thank you!"]);
        }else{
            echo json_encode(["message"=> "Error occured! Please try again."]);
        }
    }else{
        echo json_encode(["message"=> "POST method is required"]);
    }
    

    

    
