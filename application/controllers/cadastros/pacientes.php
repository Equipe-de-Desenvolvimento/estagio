<?php

require_once APPPATH . 'controllers/base/BaseController.php';

class pacientes extends BaseController {

    function __construct() {
        parent::__construct();
        $this->load->model('cadastro/paciente_model', 'paciente');
        $this->load->model('ambulatorio/laudo_model', 'laudo');
        $this->load->model('ambulatorio/exametemp_model', 'exametemp');
        $this->load->model('ambulatorio/exame_model', 'exame');
        $this->load->model('seguranca/operador_model', 'operador_m');
        $this->load->model('ambulatorio/procedimento_model', 'procedimento');
        $this->load->model('cadastro/convenio_model', 'convenio');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->library('utilitario');
        $this->load->library('email');
        $this->load->library('mensagem');
        $this->load->library('pagination');
        $this->load->library('validation');
    }

    public function index() {
        $this->pesquisar();
    }

    public function pesquisar($args = array()) {
        $this->loadView('cadastros/pacientes-lista');
    }

    public function pesquisardesativado($args = array()) {
        $this->loadView('cadastros/pacientesdesativados-lista');
    }

    public function pesquisarsubstituir($args = array()) {
        $data['paciente_temp_id'] = $args;
        $this->loadView('cadastros/pacientes-listasubstituir', $data);
    }

    function listarprecadastrosPaciente($args = array()){       
        $this->loadView('cadastros/precadastropaciente-lista',  $args);       
    }
    function novo() {

        $data['idade'] = 0;
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['listaLogradouro'] = $this->paciente->listaTipoLogradouro();
        $data['listaconvenio'] = $this->paciente->listaconvenio();
//        $data['empresaPermissao'] = $this->guia->listarempresapermissoes();
        $this->loadView('cadastros/paciente-ficha', $data);
    }

    function cancelamento($paciente_id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa'] = $this->guia->listarempresa($empresa_id);

        $data['contador'] = $this->paciente->relatoriocancelamentocontador($paciente_id);
        $data['relatorio'] = $this->paciente->relatoriocancelamento($paciente_id);
        $this->load->View('cadastros/impressaorelatoriocancelamentopaciente', $data);
    }

    function substituirambulatoriotemp($paciente_id, $paciente_temp_id) {
        $paciente_id = $this->exametemp->substituirpacientetemp($paciente_id, $paciente_temp_id);
        if ($paciente_id == 0) {
            $data['mensagem'] = 'Paciente gravado com sucesso';
        } else {
            $data['mensagem'] = 'Erro ao gravar paciente';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exametemp");
    }

    function anexarimagem($paciente_id) {

        $this->load->helper('directory');
        if (!is_dir("./upload/paciente")) {
            mkdir("./upload/paciente");
            $destino = "./upload/paciente";
            chmod($destino, 0777);
        }
        if (!is_dir("./upload/paciente/$paciente_id")) {
            mkdir("./upload/paciente/$paciente_id");
            $destino = "./upload/paciente/$paciente_id";
            chmod($destino, 0777);
        }
//        $data['arquivo_pasta'] = directory_map("./upload/paciente/$paciente_id/");
        $data['arquivo_pasta'] = directory_map("./upload/paciente/$paciente_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['paciente_id'] = $paciente_id;
        $this->loadView('ambulatorio/importacao-imagempaciente', $data);
    }

    function importarimagem() {
        $paciente_id = $_POST['paciente_id'];

        for ($i = 0; $i < count($_FILES['arquivos']['name']); $i++) {
            $_FILES['userfile']['name'] = $_FILES['arquivos']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['arquivos']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['arquivos']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['arquivos']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['arquivos']['size'][$i];

            if (!is_dir("./upload/paciente/$paciente_id")) {
                mkdir("./upload/paciente/$paciente_id");
                $destino = "./upload/paciente/$paciente_id";
                chmod($destino, 0777);
            }

            //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
            $config['upload_path'] = "./upload/paciente/" . $paciente_id . "/";
            $config['allowed_types'] = 'gif|jpg|BMP|bmp|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar|xml|txt';
            $config['max_size'] = '0';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload()) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $error = null;
                $data = array('upload_data' => $this->upload->data());
            }
            if($error == null){
                $this->laudo->gravaranexoarquivo(NULL, $paciente_id, 'upload/paciente/'.$paciente_id.'/'. $_FILES['userfile']['name'], $_FILES['userfile']['name']);
            }
        }
        $data['paciente_id'] = $paciente_id;

        redirect(base_url() . "cadastros/pacientes/anexarimagem/$paciente_id");

//        $this->anexarimagem($paciente_id);
    }

    function excluirimagem($paciente_id, $nome) {

        if (!is_dir("./uploadopm/paciente/$paciente_id")) {
            mkdir("./uploadopm/paciente");
            mkdir("./uploadopm/paciente/$paciente_id");
            $destino = "./uploadopm/paciente/$paciente_id";
            chmod($destino, 0777);
        }

        $origem = "./upload/paciente/$paciente_id/$nome";
        $destino = "./uploadopm/paciente/$paciente_id/$nome";
        copy($origem, $destino);
        unlink($origem);

        redirect(base_url() . "cadastros/pacientes/anexarimagem/$paciente_id");

//        $this->anexarimagem($paciente_id);
    }

    function excluirimagemlaudo($paciente_id, $nome) {

        if (!is_dir("./uploadopm/paciente/$paciente_id")) {
            mkdir("./uploadopm/paciente");
            mkdir("./uploadopm/paciente/$paciente_id");
            $destino = "./uploadopm/paciente/$paciente_id";
            chmod($destino, 0777);
        }

        $origem = "./upload/paciente/$paciente_id/$nome";
        $destino = "./uploadopm/paciente/$paciente_id/$nome";
        copy($origem, $destino);
        unlink($origem);

        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");

//        $this->anexarimagem($paciente_id);
    }

    function autorizarambulatoriotemp($paciente_id) {

        $resultadoguia = $this->guia->listarguia($paciente_id);
        $ambulatorio_guia_id = $resultadoguia['ambulatorio_guia_id'];
        if ($ambulatorio_guia_id == NULL) {
            $ambulatorio_guia_id = $this->guia->gravarguia($paciente_id);
        }
        $teste = $this->exametemp->autorizarpacientetemp($paciente_id, $ambulatorio_guia_id);
        if (@$teste["cod"] == -1) {
            if (@$teste['message'] == 'pending') {
                $messagem = "O paciente possui pendência no sistema de fidelidade.";
            } else {
                $messagem = "O paciente não existe no sistema de fidelidade.";
            }
            $this->session->set_flashdata('message', $messagem);
            redirect(base_url() . "cadastros/pacientes/procedimentoautorizarconsulta/$paciente_id");
        } else {
            if ($teste == 0) {
                $data['mensagem'] = 'Paciente gravado com sucesso';
            } else {
                $data['mensagem'] = 'Erro ao gravar paciente';
            }
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/guia/pesquisar/$paciente_id");
        }
    }

    function autorizarambulatoriotempconsulta($paciente_id) {
        $resultadoguia = $this->guia->listarguia($paciente_id);
        $ambulatorio_guia_id = @$resultadoguia['ambulatorio_guia_id'];
        if ($ambulatorio_guia_id == 0) {
            $ambulatorio_guia_id = $this->guia->gravarguia($paciente_id);
        }
        $teste = $this->exametemp->autorizarpacientetempconsulta($paciente_id, $ambulatorio_guia_id);
        if (@$teste["cod"] == -1) {
            if (@$teste['message'] == 'pending') {
                $messagem = "O paciente possui pendência no sistema de fidelidade.";
            } else {
                $messagem = "O paciente não existe no sistema de fidelidade.";
            }
            $this->session->set_flashdata('message', $messagem);
            redirect(base_url() . "cadastros/pacientes/procedimentoautorizarconsulta/$paciente_id");
        } else {
            if ($teste == 0) {
                //            $this->gerardicom($ambulatorio_guia_id);
                $data['mensagem'] = 'Paciente gravado com sucesso';
            } else {
                $data['mensagem'] = 'Erro ao gravar paciente';
            }
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/guia/pesquisar/$paciente_id");
        }
    }

    function autorizarambulatoriotempfisioterapia($paciente_id) {

        $resultadoguia = $this->guia->listarguia($paciente_id);
        $ambulatorio_guia_id = $resultadoguia['ambulatorio_guia_id'];
        if ($ambulatorio_guia_id == 0) {
            $ambulatorio_guia_id = $this->guia->gravarguia($paciente_id);
        }
        $teste = $this->exametemp->autorizarpacientetempfisioterapia($paciente_id, $ambulatorio_guia_id);
        if (@$teste["cod"] == -1) {
            if (@$teste['message'] == 'pending') {
                $messagem = "O paciente possui pendência no sistema de fidelidade.";
            } else {
                $messagem = "O paciente não existe no sistema de fidelidade.";
            }
            $this->session->set_flashdata('message', $messagem);
            redirect(base_url() . "cadastros/pacientes/procedimentoautorizarfisioterapia/$paciente_id");
        } else {
            if ($teste == 0) {
                //            $this->gerardicom($ambulatorio_guia_id);
                $data['mensagem'] = 'Paciente gravado com sucesso';
            } else {
                $data['mensagem'] = 'Erro ao gravar paciente';
            }
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/guia/pesquisar/$paciente_id");
        }
    }

    function autorizarambulatoriotempgeral($paciente_id) {
//        var_dump(date("Y-m-d", -370126800)); die;

            if(isset($_POST['sem_atendimento'])){

                $data['mensagem'] = 'Paciente sem Procedimentos agendado para hoje';
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "ambulatorio/guia/pesquisar/$paciente_id");
            }


        $resultadoguia = $this->guia->listarguia($paciente_id);
        @$ambulatorio_guia_id = $resultadoguia['ambulatorio_guia_id'];
        if ($ambulatorio_guia_id == 0) {
            $ambulatorio_guia_id = $this->guia->gravarguia($paciente_id);
        }
         
        if(isset($_POST['ambulatorio_orcamento_id']) && $_POST['ambulatorio_orcamento_id'] != ""){
          $this->exametemp->finalizarautoriazacaoorcamento($_POST['ambulatorio_orcamento_id']);
        }
        $teste = $this->exametemp->autorizarpacientetempgeral($paciente_id, $ambulatorio_guia_id);
 

        if (@$teste["cod"] == -1) {
            if (@$teste['message'] == 'pending') {
                $messagem = "O paciente possui pendência no sistema de fidelidade.";
            } else {
                $messagem = "O paciente não existe no sistema de fidelidade.";
            }
            // die;
            $this->session->set_flashdata('message', $messagem);
            redirect(base_url() . "cadastros/pacientes/procedimentoautorizaratendimento/$paciente_id");
        } else {
            if ($teste == 0) {
                //            $this->gerardicom($ambulatorio_guia_id);
                $data['mensagem'] = 'Paciente gravado com sucesso';
            } elseif ($teste == -1) {
                $data['mensagem'] = 'Erro ao gravar paciente';
            } elseif ($teste == 2) {
                $data['mensagem'] = 'ERRO: Obrigatório preencher solicitante.';
                // die;
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "cadastros/pacientes/procedimentoautorizaratendimento/$paciente_id");
            }
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/guia/pesquisar/$paciente_id");
    }

    function procedimentosubstituir($paciente_id, $paciente_temp_id) {
        $data['paciente_id'] = $paciente_id;
        $data['convenio'] = $this->convenio->listardados();
        $data['paciente_temp_id'] = $paciente_temp_id;
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['paciente'] = $this->paciente->listardados($data['paciente_id']);
        $data['procedimento'] = $this->procedimento->listarprocedimentos();
        $data['exames'] = $this->exametemp->listaragendas($paciente_temp_id);
        $this->loadView('ambulatorio/procedimentosubstituir-form', $data);
    }

    function procedimentoautorizar($paciente_id) {
        $data['paciente_id'] = $paciente_id;
        $data['convenio'] = $this->convenio->listardados();
        $data['setor'] = $this->guia->listarsetores();
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['paciente'] = $this->paciente->listardados($data['paciente_id']);

        if ($data['paciente'][0]->ativo == 'f') {
            $data['mensagem'] = 'Paciente excluído';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "cadastros/pacientes");
        }
        $data['procedimento'] = $this->procedimento->listarprocedimentos();
        $data['exames'] = $this->exametemp->listaragendaspaciente($paciente_id);
        $data['grupos'] = $this->procedimento->listargruposexame();
        $this->loadView('ambulatorio/procedimentoautorizar-form', $data);
    }

    function procedimentoautorizarconsulta($paciente_id) {
        $data['paciente_id'] = $paciente_id;
        $data['salas'] = $this->exame->listarsalastotal();
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['paciente'] = $this->paciente->listardados($data['paciente_id']);
        if ($data['paciente'][0]->ativo == 'f') {
            $data['mensagem'] = 'Paciente excluído';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "cadastros/pacientes");
        }
        $data['consultasanteriores'] = $this->exametemp->listarconsultaanterior($paciente_id);
        $data['procedimento'] = $this->procedimento->listarprocedimentos();
        $data['exames'] = $this->exametemp->listaragendaspacienteconsulta($paciente_id);
        $this->loadView('ambulatorio/procedimentoautorizarconsulta-form', $data);
    }

    function procedimentoautorizarfisioterapia($paciente_id) {
//        $lista = $this->exame->autorizarsessaofisioterapia($paciente_id);
//        if (count($lista) == 0) {
        $data['paciente_id'] = $paciente_id;
        $data['salas'] = $this->exame->listarsalastotal();
        $data['convenio'] = $this->convenio->listardados();
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['paciente'] = $this->paciente->listardados($data['paciente_id']);
        if ($data['paciente'][0]->ativo == 'f') {
            $data['mensagem'] = 'Paciente excluído';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "cadastros/pacientes");
        }
        $data['consultasanteriores'] = $this->exametemp->listarfisioterapiaanterior($paciente_id);
        $data['procedimento'] = $this->procedimento->listarprocedimentos();
        $data['exames'] = $this->exametemp->listaragendaspacientefisioterapia($paciente_id);
        $data['grupos'] = $this->procedimento->listargruposespecialidade();
//        var_dump($data['grupos']);die;
        $this->loadView('ambulatorio/procedimentoautorizarfisioterapia-form', $data);
//        } else {
//            $data['mensagem'] = 'Paciente com sessões pendentes.';
//            $this->session->set_flashdata('message', $data['mensagem']);
//            redirect(base_url() . "emergencia/filaacolhimento/novo/$paciente_id");
//        }
    }

    function procedimentoautorizaratendimento($paciente_id) {
        $lista = $this->exame->autorizarsessaofisioterapia($paciente_id);
        $data['paciente_id'] = $paciente_id;
        $data['salas'] = $this->exame->listarsalasativas();
        $data['setor'] = $this->guia->listarsetores();
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['paciente'] = $this->paciente->listardados($data['paciente_id']);
        if ($data['paciente'][0]->ativo == 'f') {
            $data['mensagem'] = 'Paciente excluído';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "cadastros/pacientes");
        }
        $data['consultasanteriores'] = $this->exametemp->listarconsultaanterior($paciente_id);
        $data['procedimento'] = $this->procedimento->listarprocedimentos();
        $data['exames'] = $this->exametemp->listaragendaspacienteatendimento($paciente_id);
        $data['grupos'] = $this->procedimento->listargruposatendimento();
        $data['tcd'] = $this->exametemp->listartcd($paciente_id)->get()->result();
        $data['valortotal'] = $this->exametemp->listarsaldocreditopaciente($paciente_id);
        if(isset($_GET['orcamento']) && $_GET['orcamento'] != ""){
            $data['ambulatorio_orcamento_id'] = $_GET['orcamento']; 
        }
        $this->loadView('ambulatorio/procedimentoautorizaratendimento-form', $data);
    }

    function novosubstituir() {

        $data['idade'] = 0;
        $data['listaLogradouro'] = $this->paciente->listaTipoLogradouro();
        $data['listaconvenio'] = $this->paciente->listaconvenio();
        $this->loadView('cadastros/paciente-fichasubstituir', $data);
    }

    function contatosite() {
        var_dump($_POST);
        die;

//        $data['idade'] = 0;
//        $data['listaLogradouro'] = $this->paciente->listaTipoLogradouro();
//        $data['listaconvenio'] = $this->paciente->listaconvenio();
//        $this->loadView('cadastros/paciente-fichasubstituir', $data);
    }

    function carregar($paciente_id, $agendado = NULL) {
        //essa variavel agendado serve para verificar se esta vindo da multifunção
        $obj_paciente = new paciente_model($paciente_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['ocupacao_mae'] = $data['empresapermissoes'][0]->ocupacao_mae;
        $data['obj'] = $obj_paciente;
        $data['idade'] = 1;
        $data['agendado'] = $agendado;
        $this->loadView('cadastros/paciente-ficha', $data);
    }

    function carregarcirurgico($paciente_id, $agendado = NULL) {
        //essa variavel agendado serve para verificar se esta vindo da multifunção
        $obj_paciente = new paciente_model($paciente_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['obj'] = $obj_paciente;
        $data['idade'] = 1;
        $data['agendado'] = $agendado;
        $this->loadView('cadastros/pacientecirurgico-ficha', $data);
    }

    function carregarmobile($paciente_id, $agendado = NULL, $empresa_id = 1) {
        //essa variavel agendado serve para verificar se esta vindo da multifunção
        $obj_paciente = new paciente_model($paciente_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoesweb($empresa_id);
        $data['obj'] = $obj_paciente;
        $data['idade'] = 1;
        $data['agendado'] = $agendado;
        $this->load->View('cadastros/pacienteeditarweb-ficha', $data);
    }

    function visualizarcarregar($paciente_id) {
        $obj_paciente = new paciente_model($paciente_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['obj'] = $obj_paciente;
        $data['idade'] = 1;
        $this->loadView('cadastros/pacientevisualizar-ficha', $data);
    }

    function carregarinternacaoprecadastro($paciente_id, $internacao_ficha_id) {
        $obj_paciente = new paciente_model($paciente_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['obj'] = $obj_paciente;
        $data['internacao_ficha_id'] = $internacao_ficha_id;
        $data['idade'] = 1;
        $this->loadView('cadastros/pacienteinternacaoprecadastro-ficha', $data);
    }

    function carregarmedico($paciente_id) {
        $obj_paciente = new paciente_model($paciente_id);
        $data['obj'] = $obj_paciente;
        $data['idade'] = 1;
        $this->loadView('cadastros/pacientemedico-ficha', $data);
    }

    function gravar() {
        if(isset($_POST['idade2'])){
            $_POST['idade2'] = str_replace(' ano(s)', '', $_POST['idade2']);
        }else{
            $_POST['idade2'] = 0; 
        }
        // echo '<pre>';
        // print_r($_POST); 
        // die;
        $perfil_id = $this->session->userdata('perfil_id');
        $empresa_id = $this->session->userdata('empresa_id');
        $empresapermissoes = $this->guia->listarempresapermissoes($empresa_id);

        if ($_POST['nascimento'] != '') {
            $nascimento = str_replace('/', '-', $_POST['nascimento']);
 
            $data_valida = $this->utilitario->validateDate($nascimento);
            if (!$data_valida) {
                $_POST['nascimento'] = '';
            }
 
        }

        if (!is_dir("./upload/webcam")) {
            mkdir("./upload/webcam");
            $destino = "./upload/webcam";
            chmod($destino, 0777);
        }
        if (!is_dir("./upload/webcam/pacientes")) {
            mkdir("./upload/webcam/pacientes");
            $destino = "./upload/webcam/pacientes";
            chmod($destino, 0777);
        }

        $contador = $this->paciente->contador();

        $_POST['nascimento'] = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['nascimento'])));

        if ($_POST['cpf'] != "" && $_POST['cpf'] != "000.000.000-00") {
            if ($this->utilitario->validaCPF($_POST['cpf'])) {
                $contadorcpf = $this->paciente->contadorcpf2();
                if ($_POST['cpf_responsavel'] == 'on') {
                    $contadorcpf = 0;
                }// Caso esteja marcado como CPF responsável, ele deixa cadastrar.

                if ($contadorcpf > 0) {
                    $data['mensagem'] = 'CPF do paciente já cadastrado';
                    $this->session->set_flashdata('message', $data['mensagem']);
                    if(isset($_POST['desativado']) && $_POST['desativado'] == "true"){
                      redirect(base_url() . "cadastros/pacientes/pesquisardesativado", $data);
                    }
                    redirect(base_url() . "cadastros/pacientes", $data);
                }
            } else {
                $data['mensagem'] = 'Erro ao gravar paciente. CPF inválido';
                $this->session->set_flashdata('message', $data['mensagem']);
                if(isset($_POST['desativado']) && $_POST['desativado'] == "true"){
                      redirect(base_url() . "cadastros/pacientes/pesquisardesativado", $data);
                }
                redirect(base_url() . "cadastros/pacientes", $data);
            }
//            var_dump($contadorcpf); die;
        } else {
            $contadorcpf = 0;
        }
        
         if ($this->utilitario->validaCPF($_POST['cpf']) || $contadorcpf == 0) {
            if ($contador == 0 && $contadorcpf == 0) {
                if ($paciente_id = $this->paciente->gravar()) {
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'Erro ao gravar paciente';
                }
 
                if ($paciente_id != false && $_POST['mydata'] != '') {
                    $encoded_data = $_POST['mydata'];
                    $binary_data = base64_decode($encoded_data);
                    $result = file_put_contents("upload/webcam/pacientes/$paciente_id.jpg", $binary_data);
                }
                $this->session->set_flashdata('message', $data['mensagem']);
                if(isset($_POST['desativado']) && $_POST['desativado'] == "true"){
                    redirect(base_url() . "cadastros/pacientes/pesquisardesativado", $data);
                }
                if ($empresapermissoes[0]->convenio_padrao == 't'  ) {
                    redirect(base_url() . "ambulatorio/guia/novoatendimentogrupopadrao/$paciente_id");
                } else {
                    redirect(base_url() . "emergencia/filaacolhimento/novo/$paciente_id", $data);
                }
            } elseif ($contador > 0 && $_POST['paciente_id'] != "") {
                //Atualiza cadastro
                if ($paciente_id = $this->paciente->gravar()) {
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'Erro ao gravar paciente';
                }
            } elseif ($contador == 0 && $contadorcpf == 1 && $_POST['paciente_id'] != "") {

                if ($paciente_id = $this->paciente->gravar()) {
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'Erro ao gravar paciente';
                }
            } elseif ($contador == 0 && $contadorcpf == 1 && $_POST['paciente_id'] == "") {

                if ($_POST['cpf'] == "000.000.000-00") {
                    $paciente_id = $this->paciente->gravar();
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'CPF do paciente já cadastrado';
                    $this->session->set_flashdata('message', $data['mensagem']);
                    if(isset($_POST['desativado']) && $_POST['desativado'] == "true"){
                      redirect(base_url() . "cadastros/pacientes/pesquisardesativado", $data);
                    }
                    redirect(base_url() . "cadastros/pacientes", $data);
                }
            } else {
                if ($_POST['cpf'] == "000.000.000-00") {
                    $paciente_id = $this->paciente->gravar();
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'Paciente ja cadastrado';
                    $this->session->set_flashdata('message', $data['mensagem']);
                    if(isset($_POST['desativado']) && $_POST['desativado'] == "true"){
                      redirect(base_url() . "cadastros/pacientes/pesquisardesativado", $data);
                    }
                    redirect(base_url() . "cadastros/pacientes", $data);
                }
            }
        } else {
            $data['mensagem'] = 'CPF inválido';
            $this->session->set_flashdata('message', $data['mensagem']);
            if(isset($_POST['desativado']) && $_POST['desativado'] == "true"){
              redirect(base_url() . "cadastros/pacientes/pesquisardesativado", $data);
            }
            redirect(base_url() . "cadastros/pacientes", $data);
        } 
        if ($paciente_id != false && $_POST['mydata'] != '') {
            $encoded_data = $_POST['mydata'];
            $binary_data = base64_decode($encoded_data);
            $result = file_put_contents("upload/webcam/pacientes/$paciente_id.jpg", $binary_data);
        } 
        $this->session->set_flashdata('message', $data['mensagem']);
        if(isset($_POST['desativado']) && $_POST['desativado'] == "true"){
          redirect(base_url() . "cadastros/pacientes/pesquisardesativado", $data);
        }
        if ($empresapermissoes[0]->convenio_padrao == 't' && $_POST['agendado'] != '1'  ) {
            redirect(base_url() . "ambulatorio/guia/novoatendimentogrupopadrao/$paciente_id");
        } else {
            redirect(base_url() . "emergencia/filaacolhimento/novo/$paciente_id", $data);
        }
        
    }


    function gravarcirurgico() {
         
        $perfil_id = $this->session->userdata('perfil_id');
        $empresa_id = $this->session->userdata('empresa_id');
        $empresapermissoes = $this->guia->listarempresapermissoes($empresa_id);

        if ($_POST['nascimento'] != '') {
            $nascimento = str_replace('/', '-', $_POST['nascimento']);
 
            $data_valida = $this->utilitario->validateDate($nascimento);
            if (!$data_valida) {
                $_POST['nascimento'] = '';
            }
 
        }

        if (!is_dir("./upload/webcam")) {
            mkdir("./upload/webcam");
            $destino = "./upload/webcam";
            chmod($destino, 0777);
        }
        if (!is_dir("./upload/webcam/pacientes")) {
            mkdir("./upload/webcam/pacientes");
            $destino = "./upload/webcam/pacientes";
            chmod($destino, 0777);
        }

        $contador = $this->paciente->contador();

        $_POST['nascimento'] = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['nascimento'])));

        if ($_POST['cpf'] != "" && $_POST['cpf'] != "000.000.000-00") {
            if ($this->utilitario->validaCPF($_POST['cpf'])) {
                $contadorcpf = $this->paciente->contadorcpf2();
                if ($_POST['cpf_responsavel'] == 'on') {
                    $contadorcpf = 0;
                }// Caso esteja marcado como CPF responsável, ele deixa cadastrar.

                if ($contadorcpf > 0) {
                    $data['mensagem'] = 'CPF do paciente já cadastrado';
                    $this->session->set_flashdata('message', $data['mensagem']);
                    redirect(base_url() . "cadastros/pacientes", $data);
                }
            } else {
                $data['mensagem'] = 'Erro ao gravar paciente. CPF inválido';
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "cadastros/pacientes", $data);
            }
//            var_dump($contadorcpf); die;
        } else {
            $contadorcpf = 0;
        }
        
         if ($this->utilitario->validaCPF($_POST['cpf']) || $contadorcpf == 0) {
            if ($contador == 0 && $contadorcpf == 0) {
                if ($paciente_id = $this->paciente->gravar()) {
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'Erro ao gravar paciente';
                }
 
                if ($paciente_id != false && $_POST['mydata'] != '') {
                    $encoded_data = $_POST['mydata'];
                    $binary_data = base64_decode($encoded_data);
                    $result = file_put_contents("upload/webcam/pacientes/$paciente_id.jpg", $binary_data);
                }
                $this->session->set_flashdata('message', $data['mensagem']);

                redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
                
            } elseif ($contador > 0 && $_POST['paciente_id'] != "") {
                //Atualiza cadastro
                if ($paciente_id = $this->paciente->gravar()) {
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'Erro ao gravar paciente';
                }
            } elseif ($contador == 0 && $contadorcpf == 1 && $_POST['paciente_id'] != "") {

                if ($paciente_id = $this->paciente->gravar()) {
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'Erro ao gravar paciente';
                }
            } elseif ($contador == 0 && $contadorcpf == 1 && $_POST['paciente_id'] == "") {

                if ($_POST['cpf'] == "000.000.000-00") {
                    $paciente_id = $this->paciente->gravar();
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'CPF do paciente já cadastrado';
                    $this->session->set_flashdata('message', $data['mensagem']);
                    redirect(base_url() . "cadastros/pacientes", $data);
                }
            } else {
                if ($_POST['cpf'] == "000.000.000-00") {
                    $paciente_id = $this->paciente->gravar();
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'Paciente ja cadastrado';
                    $this->session->set_flashdata('message', $data['mensagem']);
                    redirect(base_url() . "cadastros/pacientes", $data);
                }
            }
        } else {
            $data['mensagem'] = 'CPF inválido';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "cadastros/pacientes", $data);
        }

        if ($paciente_id != false && $_POST['mydata'] != '') {
            $encoded_data = $_POST['mydata'];
            $binary_data = base64_decode($encoded_data);
            $result = file_put_contents("upload/webcam/pacientes/$paciente_id.jpg", $binary_data);
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
       
        
    }

    function gravarweb() {
        //    echo'<pre>';
        //    var_dump($_POST);die;
        $empresa_id = $this->session->userdata('empresa_id');
        $empresapermissoes = $this->guia->listarempresapermissoes($empresa_id);

        if ($_POST['nascimento'] != '') {
            $nascimento = str_replace('/', '-', $_POST['nascimento']);
//            var_dump($nascimento); die;
            $data_valida = $this->utilitario->validateDate($nascimento);
            if (!$data_valida) {
                $_POST['nascimento'] = '';
            }
//            die;
        }

        if (!is_dir("./upload/webcam")) {
            mkdir("./upload/webcam");
            $destino = "./upload/webcam";
            chmod($destino, 0777);
        }
        if (!is_dir("./upload/webcam/pacientes")) {
            mkdir("./upload/webcam/pacientes");
            $destino = "./upload/webcam/pacientes";
            chmod($destino, 0777);
        }

        $contador = $this->paciente->contador();

        $_POST['nascimento'] = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['nascimento'])));

        if ($_POST['cpf'] != "" && $_POST['cpf'] != "000.000.000-00") {
            if ($this->utilitario->validaCPF($_POST['cpf'])) {
                $contadorcpf = $this->paciente->contadorcpf2();
                if ($_POST['cpf_responsavel'] == 'on') {
                    $contadorcpf = 0;
                }// Caso esteja marcado como CPF responsável, ele deixa cadastrar.

                if ($contadorcpf > 0) {
                    $data['mensagem'] = 'CPF do paciente já cadastrado';
                    $this->session->set_flashdata('message', $data['mensagem']);
                    redirect(base_url() . "cadastros/pacientes/carregarmobile/{$_POST['paciente_id']}", $data);
                }
            } else {
                $data['mensagem'] = 'Erro ao gravar paciente. CPF inválido';
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "cadastros/pacientes/carregarmobile/{$_POST['paciente_id']}", $data);
            }
//            var_dump($contadorcpf); die;
        } else {
            $contadorcpf = 0;
        }

        if ($this->utilitario->validaCPF($_POST['cpf']) || $contadorcpf == 0) {
            if ($contador == 0 && $contadorcpf == 0) {
                if ($paciente_id = $this->paciente->gravar()) {
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'Erro ao gravar paciente';
                }


                if ($paciente_id != false && $_POST['mydata'] != '') {
                    $encoded_data = $_POST['mydata'];
                    $binary_data = base64_decode($encoded_data);
                    $result = file_put_contents("upload/webcam/pacientes/$paciente_id.jpg", $binary_data);
                }
                $this->session->set_flashdata('message', $data['mensagem']);
                if ($empresapermissoes[0]->convenio_padrao == 't') {
                    redirect(base_url() . "ambulatorio/guia/novoatendimentogrupopadrao/$paciente_id");
                } else {
                    redirect(base_url() . "emergencia/filaacolhimento/novo/$paciente_id", $data);
                }
            } elseif ($contador > 0 && $_POST['paciente_id'] != "") {
                //Atualiza cadastro
                if ($paciente_id = $this->paciente->gravar()) {
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'Erro ao gravar paciente';
                }
            } elseif ($contador == 0 && $contadorcpf == 1 && $_POST['paciente_id'] != "") {

                if ($paciente_id = $this->paciente->gravar()) {
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'Erro ao gravar paciente';
                }
            } elseif ($contador == 0 && $contadorcpf == 1 && $_POST['paciente_id'] == "") {

                if ($_POST['cpf'] == "000.000.000-00") {
                    $paciente_id = $this->paciente->gravar();
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'CPF do paciente já cadastrado';
                    $this->session->set_flashdata('message', $data['mensagem']);
                    redirect(base_url() . "cadastros/pacientes/carregarmobile/{$_POST['paciente_id']}", $data);
                }
            } else {
                if ($_POST['cpf'] == "000.000.000-00") {
                    $paciente_id = $this->paciente->gravar();
                    $data['mensagem'] = 'Paciente gravado com sucesso';
                } else {
                    $data['mensagem'] = 'Paciente ja cadastrado';
                    $this->session->set_flashdata('message', $data['mensagem']);
                    redirect(base_url() . "cadastros/pacientes/carregarmobile/{$_POST['paciente_id']}", $data);
                }
            }
        } else {
            $data['mensagem'] = 'CPF inválido';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "cadastros/pacientes/carregarmobile/{$_POST['paciente_id']}", $data);
        }
        // Em caso de atualização de cadastro
        // Encodando o raw da imagem em base64, transformando em jpg e salvando

        if ($paciente_id != false && $_POST['mydata'] != '') {
            $encoded_data = $_POST['mydata'];
            $binary_data = base64_decode($encoded_data);
            $result = file_put_contents("upload/webcam/pacientes/$paciente_id.jpg", $binary_data);
        }
        $base_url = base_url();

        $this->session->set_flashdata('message', $data['mensagem']);
        // die;
        echo "<html>
            <meta charset='UTF-8'>
            <script type='text/javascript'>
                alert('Dados alterados com sucesso!');
                window.location.href = '{$base_url}'; 
            </script>
            </html>";   
        // redirect(base_url());
    }

    function gravarpacienteprecadastro($internacao_ficha_id) {

        if ($_POST['nascimento'] != '') {
            $nascimento = str_replace('/', '-', $_POST['nascimento']);
//            var_dump($nascimento); die;
            $data_valida = $this->utilitario->validateDate($nascimento);
            if (!$data_valida) {
                $_POST['nascimento'] = '';
            }
//            die;
        }
//         var_dump($_POST['nascimento']); die;

        if (!is_dir("./upload/webcam")) {
            mkdir("./upload/webcam");
            $destino = "./upload/webcam";
            chmod($destino, 0777);
        }
        if (!is_dir("./upload/webcam/pacientes")) {
            mkdir("./upload/webcam/pacientes");
            $destino = "./upload/webcam/pacientes";
            chmod($destino, 0777);
        }

        $contador = $this->paciente->contador();

        if ($_POST['cpf'] != "") {
            $contadorcpf = $this->paciente->contadorcpf();
        } else {
            $contadorcpf = 0;
        }

        if ($contador == 0 && $contadorcpf == 0) {
            if ($paciente_id = $this->paciente->gravar()) {
                $data['mensagem'] = 'Paciente gravado com sucesso';
            } else {
                $data['mensagem'] = 'Erro ao gravar paciente';
            }
            //Em caso de paciente novo
            // Encodando o raw da imagem em base64, transformando em jpg e salvando

            if ($paciente_id != false && $_POST['mydata'] != '') {
                $encoded_data = $_POST['mydata'];
                $binary_data = base64_decode($encoded_data);
                $result = file_put_contents("upload/webcam/pacientes/$paciente_id.jpg", $binary_data);
            }
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "internacao/internacao/novointernacao/$paciente_id/$internacao_ficha_id", $data);
        } elseif ($contador > 0 && $_POST['paciente_id'] != "") {
//Atualiza cadastro
            if ($paciente_id = $this->paciente->gravar()) {
                $data['mensagem'] = 'Paciente gravado com sucesso';
            } else {
                $data['mensagem'] = 'Erro ao gravar paciente';
            }
        } elseif ($contador == 0 && $contadorcpf == 1 && $_POST['paciente_id'] != "") {

            if ($paciente_id = $this->paciente->gravar()) {
                $data['mensagem'] = 'Paciente gravado com sucesso';
            } else {
                $data['mensagem'] = 'Erro ao gravar paciente';
            }
        } elseif ($contador == 0 && $contadorcpf == 1 && $_POST['paciente_id'] == "") {

            $data['mensagem'] = 'CPF do paciente já cadastrado';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "internacao/internacao/manterfichaquestionario", $data);
        } else {
            $data['mensagem'] = 'Paciente ja cadastrado';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "internacao/internacao/manterfichaquestionario", $data);
        }
        // Em caso de atualização de cadastro
        // Encodando o raw da imagem em base64, transformando em jpg e salvando

        if ($paciente_id != false && $_POST['mydata'] != '') {
            $encoded_data = $_POST['mydata'];
            $binary_data = base64_decode($encoded_data);
            $result = file_put_contents("upload/webcam/pacientes/$paciente_id.jpg", $binary_data);
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "internacao/internacao/novointernacao/$paciente_id/$internacao_ficha_id");
    }

    function gravarmedico() {
        if ($_POST['nascimento'] != '') {
            $nascimento = str_replace('/', '-', $_POST['nascimento']);
//            var_dump($nascimento); die;
            $data_valida = $this->utilitario->validateDate($nascimento);
            if (!$data_valida) {
                $_POST['nascimento'] = '';
            }
//            die;
        }


        if (!is_dir("./upload/webcam")) {
            mkdir("./upload/webcam");
            $destino = "./upload/webcam";
            chmod($destino, 0777);
        }
        if (!is_dir("./upload/webcam/pacientes")) {
            mkdir("./upload/webcam/pacientes");
            $destino = "./upload/webcam/pacientes";
            chmod($destino, 0777);
        }

        $contador = $this->paciente->contador();

        $_POST['nascimento'] = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['nascimento'])));

        if ($_POST['cpf'] != "") {
            $contadorcpf = $this->paciente->contadorcpf();
        } else {
            $contadorcpf = 0;
        }

        if ($contador == 0 && $contadorcpf == 0) {
            if ($paciente_id = $this->paciente->gravar()) {
                $data['mensagem'] = 'Paciente gravado com sucesso';
            } else {
                $data['mensagem'] = 'Erro ao gravar paciente';
            }
            //Em caso de paciente novo
            // Encodando o raw da imagem em base64, transformando em jpg e salvando

            if ($paciente_id != false && $_POST['mydata'] != '') {
                $encoded_data = $_POST['mydata'];
                $binary_data = base64_decode($encoded_data);
                $result = file_put_contents("upload/webcam/pacientes/$paciente_id.jpg", $binary_data);
            }
            $this->session->set_flashdata('message', $data['mensagem']);
//            redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
        } elseif ($contador > 0 && $_POST['paciente_id'] != "") {
//Atualiza cadastro
            if ($paciente_id = $this->paciente->gravar()) {
                $data['mensagem'] = 'Paciente gravado com sucesso';
            } else {
                $data['mensagem'] = 'Erro ao gravar paciente';
            }
        } elseif ($contador == 0 && $contadorcpf == 1 && $_POST['paciente_id'] != "") {

            if ($paciente_id = $this->paciente->gravar()) {
                $data['mensagem'] = 'Paciente gravado com sucesso';
            } else {
                $data['mensagem'] = 'Erro ao gravar paciente';
            }
        } elseif ($contador == 0 && $contadorcpf == 1 && $_POST['paciente_id'] == "") {

            $data['mensagem'] = 'CPF do paciente já cadastrado';
            $this->session->set_flashdata('message', $data['mensagem']);
//            redirect(base_url() . "cadastros/pacientes", $data);
        } else {
            $data['mensagem'] = 'Paciente ja cadastrado';
            $this->session->set_flashdata('message', $data['mensagem']);
//            redircect(base_url() . "cadastros/pacientes", $data);
        }
        // Em caso de atualização de cadastro
        // Encodando o raw da imagem em base64, transformando em jpg e salvando

        if ($paciente_id != false && $_POST['mydata'] != '') {
            $encoded_data = $_POST['mydata'];
            $binary_data = base64_decode($encoded_data);
            $result = file_put_contents("upload/webcam/pacientes/$paciente_id.jpg", $binary_data);
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        $mensagem = $data['mensagem'];
        echo "<html>
                    <meta charset='UTF-8'>
        <script type='text/javascript'>
        
        alert('$mensagem');
        window.onunload = fechaEstaAtualizaAntiga;
        function fechaEstaAtualizaAntiga() {
            window.opener.location.reload();
            }
        window.close();
            </script>
            </html>";
    }

    public function pesquisarprocedimento($args = array()) {
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $this->loadView('cadastros/procedimento-lista', $data);
    }

    public function pesquisarpacientecenso($args = array()) {
        $this->loadView('cadastros/censoprocedimento-lista');
    }

    public function listarpacientecenso($args = array()) {
        $operador = $this->session->userdata('operador_id');

        if ($operador == 100) {
            $data['paciente'] = $this->paciente->relatoriopacientecensosuper();
            $data['demanda'] = $this->paciente->relatoriodemandadiretoria();
            $data['data'] = date("Ydm");
            $this->load->View('cadastros/relatoriodirecao-lista', $data);
        } else {
            $data['paciente'] = $this->paciente->relatoriopacientecenso($operador);
            $data['demanda'] = $this->paciente->relatoriodemandadiretoria();
            $data['data'] = date("Ydm");
            $this->load->View('cadastros/relatoriodirecao-lista', $data);
        }
    }

    function pesquisarbe($args = array()) {
        $this->loadView('cadastros/pacientesconsulta-be');
    }

    function pesquisarbectq($args = array()) {
        $this->loadView('cadastros/pacientesconsulta-bectq');
    }

    function pesquisarbegiah($args = array()) {
        $this->loadView('cadastros/pacientesconsulta-begiah');
    }

    function pesquisarbeapac($args = array()) {
        $this->loadView('cadastros/pacientesconsulta-beapac');
    }

    function pesquisarbeacolhimento($args = array()) {
        $this->loadView('cadastros/pacientesconsulta-beacolhimento');
    }

    function formulariobeacolhimento($args = array()) {
        $this->loadView('cadastros/pacientesformulario-beacolhimento');
    }

    function pesquisarfaturamentohospub($args = array()) {
        $this->loadView('cadastros/faturamentohospub');
    }

    function pesquisarfaturamentohospubinternado($args = array()) {
        $this->loadView('cadastros/faturamentohospubinternado');
    }

    function pesquisarfaturamentohospubetiqueta($args = array()) {
        $this->loadView('cadastros/faturamentohospubetiqueta');
    }

    function pesquisarsamecomparecimento($args = array()) {
        $this->loadView('cadastros/pacientesconsulta-samecomparecimento');
    }

    function pesquisarfaturamentomensal($args = array()) {
        $this->loadView('cadastros/consulta-faturamentomensal');
    }

    function pesquisarcensohospub($args = array()) {
        if ($this->utilitario->autorizar(23, $this->session->userdata('modulo')) == true) {
            $data['clinicas'] = $this->paciente->listarclinicashospub();
            $this->loadView('cadastros/censohospub', $data);
        } else {
            $data['mensagem'] = 'Usuario sem permissao';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "cadastros/pacientes/pesquisarbe", $data);
        }
    }

    function pesquisarcensohospubstatus($args = array()) {

        if ($this->utilitario->autorizar(21, $this->session->userdata('modulo')) == true) {
            $data['clinicas'] = $this->paciente->listarclinicashospub();
            $this->loadView('cadastros/censohospub_status', $data);
        } else {
            $data['mensagem'] = 'Usuario sem permissao';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "cadastros/pacientes/pesquisarbe", $data);
        }
    }

    function consultacpf($args = array()) {
        $data['cpf'] = $this->paciente->consultacpf();
        $competencia = str_replace("/", "", $_POST['txtcompetencia']);
        $valida = $this->paciente->verificaproducaomedica($competencia);
        if ($valida == 0) {
            foreach ($data['cpf'] as $value) {
                $cpf = substr($value['IC0CPF'], 1, 12);
                $nome = $value['IC0NOMGUE'];
                $crm = $value['IC0ICR'];
                $this->paciente->consultaprocedimento($cpf, $nome, $competencia, $crm);
            }
        }
        $this->gerarelatoriofaturamento($competencia);
    }

    function consultapacientes() {
        $municipio = '2304400';
        $this->paciente->listapacientes($municipio);
    }

    function gerarelatoriofaturamento($competencia) {
        $data['ponto'] = $this->paciente->listarProcedimentosPontos($competencia);
        $data['lista'] = $this->paciente->listarfaturamentomensal($competencia);
//                echo "<pre>";
//                var_dump($data['lista']);
//                echo "</pre>";
//                die;
        $this->load->View('cadastros/producaomedica', $data);
    }

    function samelistacomparecimento() {
        $data['lista'] = $this->paciente->samelistahospub();
        $this->loadView('cadastros/paciente-samelistacomparecimento', $data);
    }

    function samecomparecimento($registro, $datainternacao) {
        $data['paciente'] = $this->paciente->samehospub($registro, $datainternacao);
        $data['data'] = date("d/m/Y");
        $data['hora'] = date('H:i:s');
        $this->loadView('cadastros/paciente-samecomparecimento', $data);
    }

    function pesquisabecircunstanciado($args = array()) {
        $this->loadView('cadastros/pacientesconsulta-becircunstaciado');
    }

    function listarcircunstanciado($args = array()) {
        $this->loadView('cadastros/pacientes-relatoriocircunstanciadolista');
    }

    public function impressaocircunstanciado() {
        $data['paciente'] = $this->paciente->conection();
        $this->loadview('cadastros/paciente-becircunstaciado', $data);
    }

    function relatoriocircunstanciado() {
        $id = $this->paciente->gravarcircunstanciado();
        $data['paciente'] = $this->paciente->relatoriobecircunstanciado($id);
        $this->load->view('cadastros/paciente-becircunstaciadoimpressao', $data);
    }

    function impressaorelatoriocircunstanciado($id) {
        $data['paciente'] = $this->paciente->relatoriobecircunstanciado($id);
        $this->load->view('cadastros/paciente-becircunstaciadoimpressao_1', $data);
    }

    function samecomparecimentoimpressao($registro, $datainternacao) {
        $data['paciente'] = $this->paciente->samehospubimpressao($registro, $datainternacao);
        $data['data'] = date("d/m/Y");
        $data['hora'] = date('H:i:s');
        $this->load->view('cadastros/paciente-samecomparecimentoimpressao', $data);
    }

    function faturamentohospubetiqueta() {
        $data['paciente'] = $this->paciente->faturamentohospub();
        $data['data'] = date("d/m/Y");
        $data['hora'] = date('H:i:s');
        $this->load->view('cadastros/paciente-faturamentohospubetiqueta', $data);
    }

    function faturamentohospub() {
        $data['paciente'] = $this->paciente->faturamentohospub();
        $data['data'] = date("d/m/Y");
        $data['hora'] = date('H:i:s');
        $this->load->view('cadastros/paciente-faturamentohospub', $data);
    }

    function faturamentohospubinternado() {
        $data['paciente'] = $this->paciente->faturamentohospubinternado();
        $data['data'] = date("d/m/Y");
        $data['hora'] = date('H:i:s');
        $this->load->view('cadastros/paciente-faturamentohospubinternado', $data);
    }

    function formularioacolhimento() {
        $data['paciente'] = $this->paciente->conection();
        $this->load->view('cadastros/paciente-formularioacolhimento', $data);
    }

    function atualizacao() {
        $data = $this->paciente->listaAtualizar();

        foreach ($data as $value) {
            $this->paciente->atualizar($value->be);
        }
        $this->loadView('cadastros/pacientesconsulta-be');
    }

    public function impressaobe() {
        $data['paciente'] = $this->paciente->conection();
        $this->load->view('cadastros/paciente-impressaobe', $data);
    }

    public function impressaobectq() {
        $data['paciente'] = $this->paciente->conectionctq();
        $this->load->view('cadastros/paciente-formularioacolhimentoctq', $data);
    }

    public function impressaoacolhimento() {
        $data['paciente'] = $this->paciente->conection();
        $this->load->view('cadastros/paciente-impressaoacolhimento', $data);
    }

    public function impressaobegiah() {
        $data['paciente'] = $this->paciente->conection();
        $this->load->view('cadastros/paciente-impressaobegiah', $data);
    }

    public function impressaoabeapac() {
        $data['paciente'] = $this->paciente->apac();
        $this->load->view('cadastros/paciente-impressaobeapac', $data);
    }

    public function impressaocensohospub() {
        if ($this->utilitario->autorizar(21, $this->session->userdata('modulo')) == true) {
            $clinica = $_POST['txtclinica'];
            $data['paciente'] = $this->paciente->censohospub($clinica);
            $data['leitos'] = $this->paciente->listarleitoshospub($clinica);
            $clinicadescricao = $data['paciente']["0"]["C14NOMEC"];
            $this->paciente->deletarclinicas($clinicadescricao);
            foreach ($data['paciente'] as $value) {
                $this->paciente->gravarcensoclinicas($value);
            }
            $data['procedimentos'] = $this->paciente->listarProcedimentos();
            $data['risco1'] = $this->paciente->listarpacienterisco1();
            $data['risco2'] = $this->paciente->listarpacienterisco2();
            $data['corredor'] = $this->paciente->listarpacientecorredor();
            $capitalfortaleza = $this->paciente->listarpacientemunicipio();
            $data['capitalfortaleza'] = $capitalfortaleza['0']->count;
            $data['procedimentopaciente'] = $this->paciente->listarpacientecenso();
            $data['data'] = date("Ydm");

            $this->load->view('cadastros/impressao-censo', $data);
        } else {
            $data['mensagem'] = 'Usuario sem permissao';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "cadastros/pacientes/pesquisarbe", $data);
        }
    }

    public function impressaocensohospubstatus() {
        if ($this->utilitario->autorizar(21, $this->session->userdata('modulo')) == true) {
            $clinica = $_POST['txtclinica'];
            $data['paciente'] = $this->paciente->censohospub($clinica);
            $data['leitos'] = $this->paciente->listarleitoshospub($clinica);
            $data['procedimentos'] = $this->paciente->listarProcedimentos();
            $data['procedimentopaciente'] = $this->paciente->listarpacientecenso();
            $clinicadescricao = $data['paciente']["0"]["C14NOMEC"];
            $pacienteativos = $this->paciente->listarpacienteporclinicas($clinicadescricao);
            foreach ($pacienteativos as $value) {
                $verificador = 0;
                foreach ($data['paciente'] as $item) {

                    if ($value->prontuario == trim($item['IB6REGIST'])) {
                        $verificador = 1;
                    }
                }
                if ($verificador == 0) {
                    $this->paciente->atualizarpacienteporclinicas($value->prontuario);
                }
            }
            $data['data'] = date("Ydm");
            $this->load->view('cadastros/impressao-censostatus', $data);
        } else {
            $data['mensagem'] = 'Usuario sem permissao';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "cadastros/pacientes/pesquisarbe", $data);
        }
    }

    function novademanda() {
        $this->loadView('cadastros/demandasdiretorias-ficha');
    }

    function gravardemanda() {

        if ($this->paciente->gravardemanda()) {
            $data['mensagem'] = 'Demanda gravado com sucesso';
        } else {
            $data['mensagem'] = 'Erro ao gravar demanda';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/pacientes/listarpacientecenso");
    }

    function atualizardemanda($demanda_id) {

        $this->paciente->atualizardemanda($demanda_id);
        redirect(base_url() . "cadastros/pacientes/listarpacientecenso");
    }

    function gravarpacientecenso() {
        if ($this->paciente->gravarpacientecenso()) {
            $data['mensagem'] = 'Paciente gravado com sucesso';
        } else {
            $data['mensagem'] = 'Erro ao gravar paciente';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/pacientes/pesquisarpacientecenso");
    }

    function carregarprocedimento($procedimento) {
        $data['procedimento'] = $this->paciente->instanciarprocedimento($procedimento);
        $this->loadView('cadastros/procedimento-ficha', $data);
    }

    function carregarpacientecenso($prontuario = null, $nome = null, $procedimento = null, $procedimentodescricao = null, $unidade = null) {
        $data['prontuario'] = $prontuario;
        $data['nome'] = $nome;
        $data['procedimento'] = $procedimento;
        $data['procedimentodescricao'] = $procedimentodescricao;
        $data['status'] = null;
        $data['unidade'] = $unidade;
        $dados = $this->paciente->instanciarpacientecenso($prontuario);
        if ($dados != null) {
            $data['prontuario'] = $dados['prontuario'];
            $data['nome'] = $dados['nome'];
            $data['procedimento'] = $dados['procedimento'];
            $data['procedimentodescricao'] = $dados['descricao_resumida'];
            $data['status'] = $dados['status'];
        }
        $this->loadView('cadastros/censoprocedimento-ficha', $data);
    }

    function carregarpacientecensostatus($prontuario, $nome = null, $procedimento = null, $procedimentodescricao = null, $unidade = null) {
        $data['prontuario'] = $prontuario;
        $data['nome'] = $nome;
        $data['procedimento'] = $procedimento;
        $data['procedimentodescricao'] = $procedimentodescricao;
        $data['status'] = null;
        $data['unidade'] = $unidade;
        $dados = $this->paciente->instanciarpacientecenso($prontuario);
        if ($dados != null) {
            $data['prontuario'] = $dados['prontuario'];
            $data['nome'] = $dados['nome'];
            $data['procedimento'] = $dados['procedimento'];
            $data['procedimentodescricao'] = $dados['descricao_resumida'];
            $data['status'] = $dados['status'];
        }
        $this->loadView('cadastros/censoprocedimento-fichastatus', $data);
    }

    function atualizaprocedimento() {

        if ($this->paciente->atualizaProcedimentos()) {
            $data['mensagem'] = 'Erro ao gravar procedimento';
        } else {
            $data['mensagem'] = 'Procedimento gravado com sucesso';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/pacientes/pesquisarprocedimento");
    }

    function gerardicom($guia_id) {
        $exame = $this->exame->listardicom($guia_id);

        $grupo = $exame[0]->grupo;
        if ($grupo == 'RX' || $grupo == 'MAMOGRAFIA') {
            $grupo = 'CR';
        }
        if ($grupo == 'RM') {
            $grupo = 'MR';
        }
        $data['titulo'] = "AETITLE";
        $data['data'] = str_replace("-", "", date("Y-m-d"));
        $data['hora'] = str_replace(":", "", date("H:i:s"));
        $data['tipo'] = $grupo;
        $data['tecnico'] = $exame[0]->tecnico;
        $data['procedimento'] = $exame[0]->procedimento;
        $data['procedimento_tuss_id'] = $exame[0]->codigo;
        $data['procedimento_tuss_id_solicitado'] = $exame[0]->codigo;
        $data['procedimento_solicitado'] = $exame[0]->procedimento;
        $data['identificador_id'] = $guia_id;
        $data['pedido_id'] = $guia_id;
        $data['solicitante'] = $exame[0]->convenio;
        $data['referencia'] = "";
        $data['paciente_id'] = $exame[0]->paciente_id;
        $data['paciente'] = $exame[0]->paciente;
        $data['nascimento'] = str_replace("-", "", $exame[0]->nascimento);
        $data['sexo'] = $exame[0]->sexo;
        $this->exame->gravardicom($data);
    }

    function precadastropaciente($args = array()) {
          
        $this->load->View('cadastros/precadastro-ficha');
    }

    function gravarprecadastro() {
        if ($this->paciente->gravarprecadastro()) {           
           $data['mensagem'] = 'Aguarde um retorno, pelo email, do Gerente Administrativo';
           $mensagem = "<h3 id='sucesso_precadastro'>Pré-Cadastro gravado com sucesso<h3>";
        } else {
          $data['mensagem'] = 'Erro ao gravar Pré-Cadastro';
          $mensagem = "";
         }
         $this->session->set_flashdata('message', $data['mensagem']);
         $this->session->set_userdata('precadsatro',$mensagem);
        redirect(base_url() . "cadastros/pacientes/precadastropaciente");    
    }

    function listarprecadastros($args = array()) {
        $this->load->helper('directory');
        $this->loadView('cadastros/precadastro-lista');
    }

    function precadastroinfo($pacientes_precadastro_id) {
        $this->load->helper('directory');
        $data['listas'] = $this->paciente->listarprecadastroinfo($pacientes_precadastro_id);
        $data['pacientes_precadastro_id'] = $pacientes_precadastro_id;
        $this->load->View("cadastros/precadastroinfo", $data);
    }

    function confirmarprecadastro($pacientes_precadastro_id) {
        $teste = $this->paciente->confirmarprecadastro($pacientes_precadastro_id);
        if ($teste) {
            $data['mensagem'] = 'Erro ao confirmar Pré-Cadastro';
        } else {
            $data['mensagem'] = 'Pré-Cadastro confirmado com sucesso';
        }

        redirect(base_url() . "cadastros/pacientes/listarprecadastros", $data);
    }

    function emaildeconfirmacao($pacientes_precadastro_id) {
        $empresa = $this->guia->listarempresa();
        $this->paciente->emailprecadastro($pacientes_precadastro_id);
        $cadastro = $this->paciente->listarprecadastroinfo($pacientes_precadastro_id);

        $mensagem = "Olá, {$cadastro[0]->nome}, continue no link a seguir para completar o cadastro em {$empresa[0]->nome}
        <br>
        {$empresa[0]->endereco_externo_base}seguranca/operador/confirmarprecadastro/{$cadastro[0]->pacientes_precadastro_id}

        <br><br><br><br><br> 
         
         <span>Obs: Não responda esse email. Email automático</span>";
        //  echo '<pre>';
        //  var_dump($mensagem); die;  
        $medico_email = $cadastro[0]->email;        
        $this->load->library('email');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_user'] = 'equipe2016gcjh@gmail.com';
        $config['smtp_pass'] = 'aramis*123@';
        $config['validate'] = TRUE;
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";

        $this->email->initialize($config);
        if (@$empresa[0]->email != '') {
            $this->email->from($empresa[0]->email, $empresa[0]->nome);
        } else {
            $this->email->from('equipe2016gcjh@gmail.com', $empresa[0]->nome);
        }

        $this->email->to($medico_email);
        $this->email->subject("Confirmar cadastro em {$empresa[0]->nome}");
        $this->email->message($mensagem);
 
        if ($this->email->send()) {
            $data['mensagem'] = "Email enviado com sucesso.";
        } else {
            $data['mensagem'] = "Envio de Email malsucedido.";
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/pacientes/listarprecadastros", $data);
    }

    function desativarpaciente($paciente_id) {
        $teste = $this->paciente->desativarpaciente($paciente_id);
        if ($teste) {
            $data['mensagem'] = 'Erro ao desativar paciente';
        } else {
            $data['mensagem'] = 'Paciente desativado com sucesso';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/pacientes", $data); 
    }

    function excluirprecadastroPaciente($pacientes_precadastro_id) {
        $teste = $this->paciente->excluirprecadastroPaciente($pacientes_precadastro_id);
        if ($teste) {
            $data['mensagem'] = 'Erro ao excluir Pré-Cadastro';
        } else {
            $data['mensagem'] = 'Pré-Cadastro excluido com sucesso';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/pacientes/listarprecadastrosPaciente", $data); 
    }

    function confirmarprecadastroPaciente($pacientes_precadastro_id) {
        $teste = $this->paciente->confirmarprecadastroPaciente($pacientes_precadastro_id);
        if (!$teste) {
            $data['mensagem'] = 'Erro ao confirmar Pré-Cadastro ';
        } else {
            $data['mensagem'] = "Pré-Cadastro confirmado com sucesso $teste";
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/pacientes/listarprecadastrosPaciente", $data); 
    }

    function excluirprecadastro($pacientes_precadastro_id) {
        $teste = $this->paciente->excluirprecadastro($pacientes_precadastro_id);
        if ($teste) {
            $data['mensagem'] = 'Erro ao confirmar Pré-Cadastro';
        } else {
            $data['mensagem'] = 'Pré-Cadastro confirmado com sucesso';
        }
        redirect(base_url() . "cadastros/pacientes/listarprecadastros", $data); 
    }
    
    
    function impressaoescolaridade($pacientes_precadastro_id){
        $this->load->helper('directory');
        $this->load->plugin('mpdf');
        $data['lista'] = $this->paciente->listarprecadastroinfo($pacientes_precadastro_id);
        $data['pacientes_precadastro_id'] =$pacientes_precadastro_id;
        $html = $this->load->View('cadastros/impressaoescolaridadeprecadastro',$data, true);
        $filename = "PreCadastro".date('d-m-Y');
        $rodape = "";
        downloadpdf($html, $filename, "", $rodape); 
    }
    
   function listarocorrencia($args = array()) {
        $this->load->helper('directory');
        $this->loadView('cadastros/ocorrencia-lista');
    }
    
    function carregarcampo($ocorrencia_id){
        $data['template_ocorrencia_id'] = $ocorrencia_id;
        $data['template'] = $this->paciente->listartemplateocorrenciaform($ocorrencia_id); 
    
        $this->loadView('cadastros/ocorrencia-ficha',$data);
    }
    
    function gravartemplateocorrencia() { 
        if ($this->paciente->gravartemplateocorrencia()) {
            $mensagem = 'Sucesso ao gravar template';
        } else {
            $mensagem = 'Erro ao gravar o template. Opera&ccedil;&atilde;o cancelada.';
        }
        $this->session->set_flashdata('message', $mensagem);
        // redirect(base_url() . "ambulatorio/empresa/listartemplatesconsulta");
    }
    
    function excluirtemplateocorrencia($template_id) {
        if ($this->paciente->excluirtemplateocorrencia($template_id)) {
            $mensagem = 'Ocorrência desativada com sucesso';
        } else {
            $mensagem = 'Erro ao reativar Ocorrência. ';
        } 
        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "cadastros/pacientes/listarocorrencia");
    }
    
    function reativartemplateocorrencia($template_id) {
        if ($this->paciente->reativartemplateocorrencia($template_id)) {
            $mensagem = 'Ocorrência ativada com sucesso';
        } else {
            $mensagem = 'Erro ao reativar template. ';
        } 
        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "cadastros/pacientes/listarocorrencia");
    }
    
    function carregartemplatejson($template_id) {
        $data['template_ocorrencia_id'] = $template_id;
        $data['template'] = $this->paciente->listartemplateocorrenciaform($template_id);
//        var_dump($data['impressao']); die;
        if(count($data['template']) > 0){
            echo $data['template'][0]->template;
        }else{
            echo json_encode(array());
        }
        
    }
    
    function carregarocorrenciasjson() {
        $data['template'] = $this->paciente->listarocorrencia();
//        var_dump($data['impressao']); die;
        if(count($data['template']) > 0){
            echo json_encode($data['template']);
        // echo $data['template'][0]->template;
        }else{
            echo json_encode(array());
        }
        
    }

    function carregardesativado($paciente_id, $agendado = NULL) {
        //essa variavel agendado serve para verificar se esta vindo da multifunção
        $obj_paciente = new paciente_model($paciente_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['ocupacao_mae'] = $data['empresapermissoes'][0]->ocupacao_mae;
        $data['obj'] = $obj_paciente;
        $data['idade'] = 1;
        $data['agendado'] = $agendado;
        $data['desativado'] = "true";
        
        $this->loadView('cadastros/paciente-ficha', $data);
    }

    
}

?>
