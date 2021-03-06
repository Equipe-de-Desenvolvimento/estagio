<?php

require_once APPPATH . 'controllers/base/BaseController.php';

/**
 * Esta fornecedor é o controler de Servidor. Responsável por chamar as funções e views, efetuando as chamadas de models
 * @author Equipe de desenvolvimento APH
 * @version 1.0
 * @copyright Prefeitura de Fortaleza
 * @access public
 * @package Model
 * @subpackage GIAH
 */
class Fornecedor extends BaseController {

    function Fornecedor() {
        parent::Controller();
        $this->load->model('cadastro/fornecedor_model', 'fornecedor');
        $this->load->library('mensagem');
        $this->load->library('utilitario');
        $this->load->library('pagination');
        $this->load->library('validation');
    }

    function index() {
        $this->pesquisar();
    }

    function pesquisar($limite = 10) {
        $data["limite_paginacao"] = $limite;

        $this->loadView('cadastros/fornecedor-lista', $data);

//            $this->carregarView($data);
    }
    
     function carregarfornecedor() {
        $obj_fornecedor = new fornecedor_model();
        $data['obj'] = $obj_fornecedor;
        $data['tipo'] = $this->fornecedor->listartipo();
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('cadastros/fornecedor-form', $data);
    }

    function unificarcredordevedor($financeiro_credor_devedor_id) {
        $obj_fornecedor = new fornecedor_model($financeiro_credor_devedor_id);
        $data['credordevedor'] = $this->fornecedor->listarcredordevedor($financeiro_credor_devedor_id);
        $data['obj'] = $obj_fornecedor;
        $data['tipo'] = $this->fornecedor->listartipo();
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('cadastros/unificarfornecedor-form', $data);
    }

    function reativar($financeiro_credor_devedor_id) {
        $valida = $this->fornecedor->reativar($financeiro_credor_devedor_id);
        if ($valida == 0) {
            $data['mensagem'] = 'Sucesso ao reativar o Fornecedor';
        } else {
            $data['mensagem'] = 'Erro ao reativar o fornecedor. Opera&ccedil;&atilde;o cancelada.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/fornecedor");
    }

//     function 

//         $valida = $this->fornecedor->excluir($financeiro_credor_devedor_id);
//         if ($valida) {
//             $data['mensagem'] = 'Sucesso ao excluir o Fornecedor';
//         } else {
//             $data['mensagem'] = 'Erro ao fornecedor. Opera&ccedil;&atilde;o cancelada.';
//         }
// //         $this->session->set_flashdata('message', $data['mensagem']);
//         redirect(base_url() . "cadastros/fornecedor/fornecedor");
//     }

    function excluir($financeiro_credor_devedor_id) {
        // die('fim');
        $teste = $this->fornecedor->excluir($financeiro_credor_devedor_id);
        if ($teste) {
            $data['mensagem'] = 'Erro ao confirmar Cadastro';
        } else {
            $data['mensagem'] = 'Cadastro confirmado com sucesso';
        }
        redirect(base_url() . "cadastros/fornecedor/fornecedor");
    }
    
    function verificadependenciasexclusao($financeiro_credor_devedor_id) {
        $valida = $this->fornecedor->verificadependenciasexclusao($financeiro_credor_devedor_id);
        echo json_encode($valida);
    }

    function gravar() {
        $mensagem_existencia = $this->fornecedor->listarcredoexistentecpfcnpj();        
       
        if($mensagem_existencia != null){
            $data['mensagem'] = $mensagem_existencia;
        }else{
            $exame_fornecedor_id = $this->fornecedor->gravar();
            if ($exame_fornecedor_id == "-1") {
                $data['mensagem'] = 'Erro ao gravar a Fornecedor. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao gravar a Fornecedor.';
            }
        }
       
       
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/fornecedor");
    }

    function gravarunificarcredor() {
       
        $exame_fornecedor_id = $this->fornecedor->gravarunificarcredor();

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
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
            $this->load->view('cpf');
            $this->load->view('telefone');
        }
        $this->load->view('footer');
    }

    function gravarfornecedor(){
            
        $data['listaLogradouro'] = $this->fornecedor->gravarfornecedor();

    //    print_r($_POST);
        redirect(base_url() . "cadastros/fornecedor/carregarfornecedor/");
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
