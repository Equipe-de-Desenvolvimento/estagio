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
class Modelorelatorio extends BaseController {

    function Modelorelatorio() {
        parent::Controller();
        $this->load->model('ambulatorio/modelorelatorio_model', 'modelorelatorio');
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

        $this->loadView('ambulatorio/modelorelatorio-lista', $args);

    }

    function pesquisar2($args = array()) {

        $this->load->View('ambulatorio/modelorelatorio-lista2', $args);

    }

    function carregarmodelorelatorio($ambulatorio_modelo_relatorio_id) {
        $obj_modelorelatorio = new modelorelatorio_model($ambulatorio_modelo_relatorio_id);
        $data['obj'] = $obj_modelorelatorio;

        $this->load->View('ambulatorio/modeloterelatorio-form', $data);
    }

    function excluirmodelo($ambulatorio_modelo_relatorio_id) {
        if ($this->modelorelatorio->excluir($ambulatorio_modelo_relatorio_id)) {
            $mensagem = 'Sucesso ao excluir a Modelo Relatorio';
        } else {
            $mensagem = 'Erro ao excluir a Modelo Relatorio. Opera&ccedil;&atilde;o cancelada.';
        }

        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/modelorelatorio");
    }



    function gravar() {
        $ambulatorio_modelo_terapeuticas_id = $this->modelorelatorio->gravar();
        if ($ambulatorio_modelo_terapeuticas_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar a Modelosolicitarexames. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar a Modelosolicitarexames.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/modelorelatorio");
    }


}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
