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
class Modeloreceitaespecial extends BaseController {

    function Modeloreceitaespecial() {
        parent::Controller();
        $this->load->model('ambulatorio/modeloreceitaespecial_model', 'modeloreceitaespecial');
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

        $this->loadView('ambulatorio/modeloreceitaespecial-lista', $args);

//            $this->carregarView($data);
    }

    function pesquisar2($args = array()) {

        $this->load->View('ambulatorio/modeloreceitaespecial2-lista', $args);

//            $this->carregarView($data);
    }

    function carregarmodeloreceitaespecial($exame_modeloreceitaespecial_id) {
        $obj_modeloreceitaespecial = new modeloreceitaespecial_model($exame_modeloreceitaespecial_id);
        $data['obj'] = $obj_modeloreceitaespecial;
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['procedimentos'] = $this->procedimento->listarprocedimentos();
//        $this->load->View('ambulatorio/modeloatestado-form', $data);
        $this->load->View('ambulatorio/modeloreceitaespecial-form', $data);
    }

    function excluirmodelo($exame_modeloatestado_id) {
        if ($this->modeloreceitaespecial->excluir($exame_modeloatestado_id)) {
            $mensagem = 'Sucesso ao excluir o Modelo receita especial';
        } else {
            $mensagem = 'Erro ao excluir a modelo receita especial. Opera&ccedil;&atilde;o cancelada.';
        }

        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/modeloreceitaespecial");
    }

    function gravar() {
        $exame_modeloreceitaespecial_id = $this->modeloreceitaespecial->gravar();
        if ($exame_modeloreceitaespecial_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar a Modeloreceitaespecial. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar a Modeloreceitaespecial.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/modeloreceitaespecial");
    }

    private function carregarView($data = null, $view = null) {
        if (!isset($data)) {
            $data['mensagem'] = '';
        }

        if ($this->utilitario->autorizar(2, $this->session->userdata('modulo')) == true) {
            $this->load->view('header', $data);
            if ($view != null) {
                $this->load->view($view, $data);
            } else {
                $this->load->view('giah/servidor-lista', $data);
            }
        } else {
            $data['mensagem'] = $this->mensagem->getMensagem('login005');
            $this->load->view('header', $data);
            $this->load->view('home');
        }
        $this->load->view('footer');
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
