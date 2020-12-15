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
class Modeloprotocolo extends BaseController {

    function Modeloprotocolo() {
        parent::Controller();
        $this->load->model('ambulatorio/modeloprotocolo_model', 'modeloprotocolo');
        $this->load->model('seguranca/operador_model', 'operador_m');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/procedimento_model', 'procedimento');
        $this->load->model('ambulatorio/exametemp_model', 'exametemp');
        $this->load->library('mensagem');
        $this->load->library('utilitario');
        $this->load->library('pagination');
        $this->load->library('validation');
    }

    function index() {
        $this->pesquisar();
    }

    function pesquisar($args = array()) {

        $this->loadView('ambulatorio/modeloprotocolo-lista', $args);

    }

    function pesquisar2($args = array()) {

        $this->load->View('ambulatorio/modeloprotocolo-lista2', $args);

    }

    function carregarmodeloprotocolo($ambulatorio_modelo_protocolo_id) {
        $obj_modeloprotocolo = new modeloprotocolo_model($ambulatorio_modelo_protocolo_id);
        $data['obj'] = $obj_modeloprotocolo;

        $data['listareceita'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['listareceitaespecial'] = $this->exametemp->listarautocompletemodelosreceitaespecial();
        $data['listaexames'] = $this->exametemp->listarautocompletemodelossolicitarexames();
        $data['listaterapeuticas'] = $this->exametemp->listarmodelosterapeuticas();
        $data['listarelatorios'] = $this->exametemp->listarmodelosrelatorio();

        $this->load->View('ambulatorio/modeloprotocolo-form', $data);
    }

    function excluirmodelo($ambulatorio_modelo_protocolo_id) {
        if ($this->modeloprotocolo->excluir($ambulatorio_modelo_protocolo_id)) {
            $mensagem = 'Sucesso ao excluir a Modelo Protocolo';
        } else {
            $mensagem = 'Erro ao excluir a Modelo Protocolo. Opera&ccedil;&atilde;o cancelada.';
        }

        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/modeloprotocolo");
    }



    function gravar() {
        $ambulatorio_modelo_protocolo_id = $this->modeloprotocolo->gravar();
        if ($ambulatorio_modelo_protocolo_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar a Modelo de Protocolo. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar o Modelo de Protocolo.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/modeloprotocolo");
    }


}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
