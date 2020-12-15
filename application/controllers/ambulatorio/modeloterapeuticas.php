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
class Modeloterapeuticas extends BaseController {

    function Modeloterapeuticas() {
        parent::Controller();
        $this->load->model('ambulatorio/modeloterapeuticas_model', 'modeloterapeuticas');
        $this->load->model('seguranca/operador_model', 'operador_m');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/procedimento_model', 'procedimento');
        $this->load->library('mensagem');
        $this->load->library('utilitario');
        $this->load->library('pagination');
        $this->load->library('validation');
    }

    function index() {
        $this->pesquisar();
    }

    function pesquisar($args = array()) {

        $this->loadView('ambulatorio/modeloterapeuticas-lista', $args);

    }

    function pesquisar2($args = array()) {

        $this->load->View('ambulatorio/modeloterapeuticas-lista2', $args);

    }

    function carregarmodeloterapeuticas($ambulatorio_modelo_terapeuticas_id) {
        $obj_modeloteraupeticas = new modeloterapeuticas_model($ambulatorio_modelo_terapeuticas_id);
        $data['obj'] = $obj_modeloteraupeticas;

        $this->load->View('ambulatorio/modeloterapeuticas-form', $data);
    }

    function excluirmodelo($ambulatorio_modelo_terapeuticas_id) {
        if ($this->modeloterapeuticas->excluir($ambulatorio_modelo_terapeuticas_id)) {
            $mensagem = 'Sucesso ao excluir a Modelo Terapeutica';
        } else {
            $mensagem = 'Erro ao excluir a Modelo Terapeutica. Opera&ccedil;&atilde;o cancelada.';
        }

        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/modeloterapeuticas");
    }



    function gravar() {
        $ambulatorio_modelo_terapeuticas_id = $this->modeloterapeuticas->gravar();
        if ($ambulatorio_modelo_terapeuticas_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar a Modelosolicitarexames. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar a Modelosolicitarexames.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/modeloterapeuticas");
    }


}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
