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
class Modeloreceita extends BaseController {

    function Modeloreceita() {
        parent::Controller();
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('seguranca/operador_model', 'operador_m');
        $this->load->model('ambulatorio/procedimento_model', 'procedimento');
        $this->load->model('ambulatorio/modeloreceita_model', 'modeloreceita');
        $this->load->model('ambulatorio/laudo_model', 'laudo');
        $this->load->model('ambulatorio/empresa_model', 'empresa');
        $this->load->library('mensagem');
        $this->load->library('utilitario');
        $this->load->library('pagination');
        $this->load->library('validation');
    }

    function index() {
        $this->pesquisar();
    }

    function pesquisar($args = array()) {

        $this->loadView('ambulatorio/modeloreceita-lista', $args);

//            $this->carregarView($data);
    }

    function pesquisar2($args = array()) {

        $this->load->View('ambulatorio/modeloreceita2-lista', $args);

//            $this->carregarView($data);
    }

    function carregarmodeloreceita($exame_modeloreceita_id) {
        $obj_modeloreceita = new modeloreceita_model($exame_modeloreceita_id);
        $data['obj'] = $obj_modeloreceita;
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['procedimentos'] = $this->procedimento->listarprocedimentos();
//        $this->load->View('ambulatorio/modeloreceita-form', $data);
        $this->load->View('ambulatorio/modeloreceita-form', $data);
    }


    function criarnovomodeloreceita($ambulatorio_laudo_id, $medico_id) {
        $data['laudo_id'] = $ambulatorio_laudo_id;
        $data['medico'] = $medico_id;
        $this->load->View('ambulatorio/criarnovomodeloreceita-form', $data);
    }

    function criarnovomodeloreceitaespecial($ambulatorio_laudo_id, $medico_id) {
        $data['laudo_id'] = $ambulatorio_laudo_id;
        $data['medico'] = $medico_id;
        $this->load->View('ambulatorio/criarnovomodeloreceitaespecial-form', $data);
    }

    function criarnovomodelosexames($ambulatorio_laudo_id, $medico_id) {
        $data['laudo_id'] = $ambulatorio_laudo_id;
        $data['medico'] = $medico_id;
        $this->load->View('ambulatorio/criarnovomodelosexames-form', $data);
    }

    function criarnovomodeloterapeutica($ambulatorio_laudo_id, $medico_id) {
        $data['laudo_id'] = $ambulatorio_laudo_id;
        $data['medico'] = $medico_id;
        $this->load->View('ambulatorio/criarnovomodeloterapeutica-form', $data);
    }

    function criarnovomodelorelatorio($ambulatorio_laudo_id, $medico_id) {
        $data['laudo_id'] = $ambulatorio_laudo_id;
        $data['medico'] = $medico_id;
        $this->load->View('ambulatorio/criarnovomodelorelatorio-form', $data);
    }

    function ativarmodeloreceitaautomatico($modeloreceita_id) {
        $this->modeloreceita->ativarmodeloreceitaautomatico($modeloreceita_id);
        redirect(base_url() . "ambulatorio/modeloreceita");
    }

    function desativarmodeloreceitaautomatico($modeloreceita_id) {
        $this->modeloreceita->desativarmodeloreceitaautomatico($modeloreceita_id);
        redirect(base_url() . "ambulatorio/modeloreceita");
    }

    function excluirmodelo($modeloreceita_id){

        if ($this->modeloreceita->excluir($modeloreceita_id)) {
            $mensagem = 'Sucesso ao excluir a Modeloreceita';
        } else {
            $mensagem = 'Erro ao excluir a modeloreceita. Opera&ccedil;&atilde;o cancelada.';
        } 
            $this->session->set_flashdata('message', $mensagem);
            redirect(base_url() . "ambulatorio/modeloreceita");
    }

    function excluir($exame_modeloreceita_id) {
        if ($this->procedimento->excluir($exame_modeloreceita_id)) {
            $mensagem = 'Sucesso ao excluir a Modeloreceita';
        } else {
            $mensagem = 'Erro ao excluir a modeloreceita. Opera&ccedil;&atilde;o cancelada.';
        }

        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/modeloreceita");
    }

    function gravar() {
        $exame_modeloreceita_id = $this->modeloreceita->gravar();
        if ($exame_modeloreceita_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar a Modeloreceita. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar a Modeloreceita.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/modeloreceita");
    }


    function gravarreceitaatendimento() {
        $modelo_id = NULL;
        if(isset($_POST['btnSalvarModelo'])){
            $exame_modeloreceita_id = $this->modeloreceita->gravarreceitaatendimento();

            if ($exame_modeloreceita_id == "-1") {
                // $data['mensagem'] = 'Erro ao gravar a Receita. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $modelo_id = $exame_modeloreceita_id;
                // $data['mensagem'] = 'Sucesso ao gravar a Receita.';
            }
        }

        if(isset($_POST['r_especial'])){
            $especial = 1;
        }else{
            $especial = NULL;
        }

        $receita_id = $this->laudo->gravarreceituarioatendimento($_POST['laudo_id'], $_POST['receita'], $especial, $modelo_id);

        if($especial == 1){
            $this->laudo->impressaoreceitaespecialsalvar($receita_id, 'true', $_POST['laudo_id']);
        }else{
            $this->laudo->impressaoreceitasalvar($receita_id, $_POST['laudo_id']); 
        }
        
        if($receita_id == "-1"){
            $data['mensagem'] = 'Erro ao gravar a Receita. Opera&ccedil;&atilde;o cancelada.';
        }else{
            $data['mensagem'] = 'Sucesso ao gravar a Receita.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }


    function gravarsexamesatendimento() {
        $modelo_id = NULL;
        if(isset($_POST['btnSalvarModelo'])){
            $exame_modeloreceita_id = $this->modeloreceita->gravarsexamesatendimento();

            if ($exame_modeloreceita_id == "-1") {
                // $data['mensagem'] = 'Erro ao gravar a Receita. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $modelo_id = $exame_modeloreceita_id;
                // $data['mensagem'] = 'Sucesso ao gravar a Receita.';
            }
        }


        $receita_id = $this->laudo->gravarexameatendimento($_POST['laudo_id'], $_POST['receita'], $modelo_id);

        $this->laudo->impressaosolicitarexamesalvar($receita_id, $_POST['laudo_id']); 
  
        
        if($receita_id == "-1"){
            $data['mensagem'] = 'Erro ao gravar a Receita. Opera&ccedil;&atilde;o cancelada.';
        }else{
            $data['mensagem'] = 'Sucesso ao gravar a Receita.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }


    function gravarterapeuticaatendimento() {
        $modelo_id = NULL;
        if(isset($_POST['btnSalvarModelo'])){
            $exame_modeloreceita_id = $this->modeloreceita->gravarterapeuticaatendimento();

            if ($exame_modeloreceita_id == "-1") {
                // $data['mensagem'] = 'Erro ao gravar a Receita. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $modelo_id = $exame_modeloreceita_id;
                // $data['mensagem'] = 'Sucesso ao gravar a Receita.';
            }
        }


        $receita_id = $this->laudo->gravarterapeuticasatendimento($_POST['laudo_id'], $_POST['receita'], $modelo_id);

        $this->laudo->impressaoteraupeticasalvar($receita_id, $_POST['laudo_id']); 
  
        
        if($receita_id == "-1"){
            $data['mensagem'] = 'Erro ao gravar a Receita. Opera&ccedil;&atilde;o cancelada.';
        }else{
            $data['mensagem'] = 'Sucesso ao gravar a Receita.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }


    function gravarrelatorioatendimento() {
        $modelo_id = NULL;
        if(isset($_POST['btnSalvarModelo'])){
            $exame_modeloreceita_id = $this->modeloreceita->gravarrelatorioatendimento();

            if ($exame_modeloreceita_id == "-1") {
                // $data['mensagem'] = 'Erro ao gravar a Receita. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $modelo_id = $exame_modeloreceita_id;
                // $data['mensagem'] = 'Sucesso ao gravar a Receita.';
            }
        }


        $receita_id = $this->laudo->gravarrelatorioatendimento($_POST['laudo_id'], $_POST['receita'], $modelo_id);

        $this->laudo->impressaorelatoriosalvar($receita_id, $_POST['laudo_id']); 
  
        
        if($receita_id == "-1"){
            $data['mensagem'] = 'Erro ao gravar a Receita. Opera&ccedil;&atilde;o cancelada.';
        }else{
            $data['mensagem'] = 'Sucesso ao gravar a Receita.';
        }

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
        }
        $this->load->view('footer');
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
