<?php

class CertificadoAPI extends Controller {

    function TuoTempoAPI() {

        parent::Controller();
        $this->load->model('login_model', 'login_m');
        $this->load->model('tuotempo_model', 'tuotempo');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/laudo_model', 'laudo');
        $this->load->model('seguranca/operador_model', 'operador_m');

        $this->load->library('utilitario');

    }

    function index(){
        echo json_encode('WebService');
    }

    function  autenticar(){
        
    }


}