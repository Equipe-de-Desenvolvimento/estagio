<?php

class LabAPI extends Controller {

    function LabAPI() {

        parent::Controller();
        $this->load->model('login_model', 'login_m');
        $this->load->model('app_model', 'app');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/procedimento_model', 'procedimento');

    }

    function index(){
        echo json_encode('WebService');
    }

    function enviar_valor() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $convenio = $json_post->convenio;
        $codigo = $json_post->codigo;
        $valor = $json_post->valor;
        $origemLis = $json_post->origemLis;
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $resposta = $this->procedimento->gravarValorLabAPI($convenio, $codigo, $valor, $origemLis);

        $obj = new stdClass();
        if($resposta == ''){
            $obj->status = 'success';
            $obj->mensagem = 'Sucesso ao alterar valor';
        }else{
            $obj->status = 'error';
            $obj->mensagem = $resposta;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }
     
}
