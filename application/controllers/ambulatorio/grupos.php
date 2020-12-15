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
class Grupos extends BaseController {


    function Grupos() {
        parent::Controller();
        $this->load->model('ambulatorio/grupos_model', 'grupos');
       // $this->load->model('estoque/cliente_model', 'cliente');
        $this->load->library('mensagem');
        $this->load->library('utilitario');
        $this->load->library('pagination');
        $this->load->library('validation');
    }

    function index() {
        $this->pesquisar();
    }

    function pesquisar($args = array()) {
        $this->loadView('ambulatorio/grupo-lista', $args);
    }

    function carregargrupos($ambulatorio_grupo_id) {
        $obj_menu = new grupos_model($ambulatorio_grupo_id);
        $data['obj'] = $obj_menu;
        $this->loadView('ambulatorio/grupo-form', $data);
    }

    function gravar() {
        $grupo_id = $this->grupos->gravar();
        if ($grupo_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar o Grupo. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar o Grupo.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/grupos");
    }

    function excluir($ambulatorio_grupo_id, $nome_grupo) {
        $valida = $this->grupos->excluir($ambulatorio_grupo_id, $nome_grupo);
        if ($valida == 0) {
            $data['mensagem'] = 'Sucesso ao excluir o Grupo';
        } else if ($valida == -2) {
            $data['mensagem'] = 'Erro ao excluir o Grupo. O grupo possui procedimentos associados ao mesmo!';
        }else{
            $data['mensagem'] = 'Erro ao excluir o Grupo. Opera&ccedil;&atilde;o cancelada.'; 
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/grupos");
    }


}
/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
