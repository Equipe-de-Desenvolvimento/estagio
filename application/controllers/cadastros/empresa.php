<?php

require_once APPPATH . 'controllers/base/BaseController.php';

/**
 * Esta classe é o controler de Servidor. Responsável por chamar as funções e views, efetuando as chamadas de models
 * @author Equipe de desenvolvimento APH
 * @version 1.0
 * @copyright Prefeitura de Fortaleza
 * @access public
 * @package Model
 * @subpackage GIAH
 */
class Empresa extends BaseController {

    function Empresa(){
        parent::Controller();
        $this->load->model('cadastro/empresa_model', 'empresa');
    }

    function gerecianet() {
        $obj_gerecianet = new empresa_model(1);
        $data['obj'] = $obj_gerecianet;
        $this->loadView('cadastros/gerecianet-form', $data);
    }

    function gravargerencianet(){
        $empresa = 1;
        $this->empresa->gravargerencianet($empresa);
        $this->session->set_flashdata('message', 'Dados gravados com Sucesso');
        redirect(base_url() . "cadastros/empresa/gerecianet");
    }
    

}