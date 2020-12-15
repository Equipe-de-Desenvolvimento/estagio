
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
class Exametemp extends BaseController {

    function Exametemp() {
        parent::Controller();
        $this->load->model('ambulatorio/agenda_model', 'agenda');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/exametemp_model', 'exametemp');
        $this->load->model('ambulatorio/exame_model', 'exame');
        $this->load->model('ambulatorio/laudo_model', 'laudo');
        $this->load->model('ambulatorio/procedimento_model', 'procedimento');
        $this->load->model('ambulatorio/procedimentoplano_model', 'procedimentoplano');
        $this->load->model('cadastro/paciente_model', 'paciente');
        $this->load->model('cadastro/convenio_model', 'convenio');
        $this->load->model('ambulatorio/GExtenso', 'GExtenso');
        $this->load->model('seguranca/operador_model', 'operador_m');
        $this->load->model('ambulatorio/empresa_model', 'empresa');
        $this->load->model('ambulatorio/sala_model', 'sala');


        $this->load->library('googleplus');
        $this->load->model('calendario/googlecalendar_model', 'googlecalendar');
        $this->load->model('calendario/auth_model', 'auth');



        $this->load->library('mensagem');
        $this->load->library('utilitario');
        $this->load->library('pagination');
        $this->load->library('validation');
    }

    function index() {
        $this->pesquisar();
    }

    function pesquisar($args = array()) {

        $this->loadView('ambulatorio/exametemp-lista', $args);

//            $this->carregarView($data);
    }

    function novo() {
        $ambulatorio_pacientetemp_id = $this->exametemp->criar();
        $this->carregarexametemp($ambulatorio_pacientetemp_id);

//            $this->carregarView($data);
    }

    function novopaciente() {
        $data['idade'] = 0;
        $this->loadView('ambulatorio/pacientetemp-form', $data);
    }

    function novopacienteconsulta() {
        $data['idade'] = 0;
        $data['medico'] = $this->operador_m->listarmedicos();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $this->loadView('ambulatorio/pacientetempconsulta-form', $data);
    }

    function novopacientefisioterapia() {
        $data['idade'] = 0;
        $data['medico'] = $this->operador_m->listarmedicos();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $this->loadView('ambulatorio/pacientetempfisioterapia-form', $data);
    }

    function novopacienteconsultaencaixe() {
        $data['idade'] = 0;
        $data['medico'] = $this->operador_m->listarmedicos();
        $data['empresas'] = $this->exame->listarempresas();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $this->loadView('ambulatorio/pacientetempconsultaencaixe-form', $data);
    }

    function novopacienteexameencaixe() {
        $data['idade'] = 0;
        $data['empresas'] = $this->exame->listarempresas();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['salas'] = $this->exame->listartodassalas();
        $data['medico'] = $this->operador_m->listarmedicos();
        $this->loadView('ambulatorio/pacientetempexameencaixe-form', $data);
    }

    function novopacienteencaixegeral() {
        $data['idade'] = 0;
        $data['empresas'] = $this->exame->listarempresas();
        $data['salas'] = $this->exame->listartodassalas();
        $data['medico'] = $this->operador_m->listarmedicos();
        $this->loadView('ambulatorio/pacientetempencaixegeral-form', $data);
    }

    function novopacienteencaixegeralpaciente($paciente_id) {
        $data['paciente_id'] = $paciente_id;
        $data['idade'] = 0;
        $data['empresas'] = $this->exame->listarempresas();
        $data['salas'] = $this->exame->listartodassalas();
        $data['medico'] = $this->operador_m->listarmedicos();
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $this->loadView('ambulatorio/pacientetempencaixegeralSelecionado', $data);
    }

    function novohorarioexameencaixe() {
        $data['idade'] = 0;
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['salas'] = $this->exame->listartodassalas();
        $data['medico'] = $this->operador_m->listarmedicos();
        $this->loadView('ambulatorio/horariotempexameencaixe-form', $data);
    }

    function novohorarioencaixegeral() {
        $data['idade'] = 0;
        $data['salas'] = $this->exame->listartodassalas();
        $data['medico'] = $this->operador_m->listarmedicos();
        $this->loadView('ambulatorio/horariotempencaixegeral-form', $data);
    }

    function novopacientefisioterapiaencaixe() {
        $data['idade'] = 0;
        $data['empresas'] = $this->exame->listarempresas();
        $data['medico'] = $this->operador_m->listarmedicos();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $this->loadView('ambulatorio/pacientetempfisioterapiaencaixe-form', $data);
    }

    function novopacientefisioterapiaencaixemedico() {
        $data['idade'] = 0;
        $data['empresas'] = $this->exame->listarempresas();
        $data['medico'] = $this->operador_m->listarmedicos();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $this->loadView('ambulatorio/pacientetempfisioterapiaencaixemedico-form', $data);
    }

    function pacienteconsultaencaixe($paciente_id) {
        $data['idade'] = 0;
        $data['paciente_id'] = $paciente_id;
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['medico'] = $this->operador_m->listarmedicos();
        $this->loadView('ambulatorio/pacienteconsultaencaixe-form', $data);
    }

    function mostrargraficosexames() {
        $data['relatorio'] = $this->exametemp->gerargraficosexames();
//        var_dump($data);die;
        $this->load->View('ambulatorio/graficosexames', $data);
    }

    function carregarexametemp($ambulatorio_pacientetemp_id) {
        $obj_exametemp = new exametemp_model($ambulatorio_pacientetemp_id);
        $data['obj'] = $obj_exametemp;
        $data['idade'] = 0;
        $data['contador'] = $this->exametemp->contador($ambulatorio_pacientetemp_id);
        if ($data['contador'] > 0) {
            $data['exames'] = $this->exametemp->listaragendas($ambulatorio_pacientetemp_id);
        }
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/exametemp-form', $data);
    }

    function unificar($paciente_id) {
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['paciente_id'] = $paciente_id;
        $this->loadView('ambulatorio/unificar-form', $data);
    }

    function gravarunificar() {
        $this->load->helper('directory');

        $pacientetemp_id = $_POST['paciente_id'];

        if ($_POST['paciente_id'] == $_POST['pacienteid']) {
            $data['mensagem'] = 'Erro ao unificar. Você está tentando unificar ';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/unificar/$pacientetemp_id");
        }

        if ($_POST['txtpaciente'] == '' || $_POST['pacienteid'] == '') {
            $data['mensagem'] = 'Paciente que sera unificado nao informado ou invalido.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/unificar/$pacientetemp_id");
        } else {
            $verifica = $this->exametemp->gravarunificacao();
            if ($verifica == "-1") {
                $data['mensagem'] = 'Erro ao unificar Paciente. Opera&ccedil;&atilde;o cancelada.';
            } else {
                // Unificando arquivos
                $arquivos = directory_map("./upload/paciente/" . $_POST['pacienteid'] . "/");
                if (count($arquivos) > 0) {

                    if (!is_dir("./upload/paciente/" . $_POST['paciente_id'])) {
                        mkdir("./upload/paciente/" . $_POST['paciente_id']);
                        $destino = "./upload/paciente/" . $_POST['paciente_id'];
                        chmod($destino, 0777);
                    }
                    foreach ($arquivos as $arq) {
                        $file = "./upload/paciente/" . $_POST['pacienteid'] . "/" . $arq;
                        $newfile = "./upload/paciente/" . $_POST['paciente_id'] . "/" . $arq;
                        rename($file, $newfile);
                    }
                }

                $data['mensagem'] = 'Sucesso ao unificar Paciente.';
            }
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/unificar/$pacientetemp_id");
        }
    }

    function carregarpacientetempgeral($pacientetemp_id, $faltou = null) {
        if (isset($faltou)) {
            $data['faltou'] = $faltou;
        }

        $obj_paciente = new paciente_model($pacientetemp_id);
        $data['obj'] = $obj_paciente;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['exames'] = $this->exametemp->listaragendatotalpacientegeral($pacientetemp_id);
        $data['consultasanteriores'] = $this->exametemp->listarprocedimentosanterior($pacientetemp_id);
        $data['consultaanteriorcontador'] = $this->exametemp->listarprocedimentoanteriorcontador($pacientetemp_id);


        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/examepacientetempgeral-form', $data);
    }

    function carregarpacientetemp($pacientetemp_id, $faltou = null) {
        if (isset($faltou)) {
            $data['faltou'] = $faltou;
        }

        $obj_paciente = new paciente_model($pacientetemp_id);
        $data['obj'] = $obj_paciente;
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['contador'] = $this->exametemp->contadorpaciente($pacientetemp_id);
        $data['exames'] = $this->exametemp->listaragendatotalpaciente($pacientetemp_id);
//        print_r( $data['exames']);
//        die();
        $data['examesanteriores'] = $this->exametemp->listarexameanterior($pacientetemp_id);
        $data['salas'] = $this->exame->listartodassalasexames();
        $data['grupos'] = $this->procedimento->listargrupos();
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/examepacientetemp-form', $data);
    }

    function carregarpacienteconsultatemp($pacientetemp_id, $faltou = null) {
        if (isset($faltou)) {
            $data['faltou'] = $faltou;
        }

        $obj_paciente = new paciente_model($pacientetemp_id);
        $data['obj'] = $obj_paciente;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['contador'] = $this->exametemp->contadorconsultapaciente($pacientetemp_id);
        $data['exames'] = $this->exametemp->listaragendatotalpacienteconsulta($pacientetemp_id);
        $data['consultasanteriores'] = $this->exametemp->listarconsultaanterior($pacientetemp_id);
        $data['consultaanteriorcontador'] = $this->exametemp->listarconsultaanteriorcontador($pacientetemp_id);

        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/consultapacientetemp-form', $data);
    }

    function carregarpacientefisioterapiatemp($pacientetemp_id, $faltou = null) {
        if (isset($faltou)) {
            $data['faltou'] = $faltou;
        }            
        $obj_paciente = new paciente_model($pacientetemp_id);
        $data['obj'] = $obj_paciente;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['contador'] = $this->exametemp->contadorfisioterapiapaciente($pacientetemp_id);
        $data['exames'] = $this->exametemp->listaragendatotalpacientefisioterapia($pacientetemp_id);
        $data['consultasanteriores'] = $this->exametemp->listarespecialidadeanterior($pacientetemp_id);
        $data['consultaanteriorcontador'] = $this->exametemp->listarconsultaanteriorcontador($pacientetemp_id);

        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/fisioterapiapacientetemp-form', $data);
    }
    
     function carregarpacientefisioterapiatemp2($pacientetemp_id, $faltou = null) {
        if (isset($faltou)) {
            $data['faltou'] = $faltou;
        }        
        $data['reagenda'] = "true";
        $obj_paciente = new paciente_model($pacientetemp_id);
        $data['obj'] = $obj_paciente;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['contador'] = $this->exametemp->contadorfisioterapiapaciente($pacientetemp_id);
        $data['exames'] = $this->exametemp->listaragendatotalpacientefisioterapia($pacientetemp_id);
        $data['consultasanteriores'] = $this->exametemp->listarespecialidadeanterior($pacientetemp_id);
        $data['consultaanteriorcontador'] = $this->exametemp->listarconsultaanteriorcontador($pacientetemp_id);

        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/fisioterapiapacientetemp-form', $data);
    }

    function reangedarfisioterapiatemp($agenda_exames_id, $pacientetemp_id, $medico_consulta_id,$reagenda=NULL) {
        if (isset($reagenda)) {
            $data['reagenda'] = $reagenda;
        }
        $data['pacientetemp_id'] = $pacientetemp_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['medico_consulta_id'] = $medico_consulta_id;
        $obj_paciente = new paciente_model($pacientetemp_id);
        $data['obj'] = $obj_paciente;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['contador'] = $this->exametemp->contadorfisioterapiapaciente($pacientetemp_id);
        $data['exames'] = $this->exametemp->listaragendatotalpacientefisioterapiareangedar($agenda_exames_id);
        $data['consultasanteriores'] = $this->exametemp->listarespecialidadeanterior($pacientetemp_id);
        $data['consultaanteriorcontador'] = $this->exametemp->listarconsultaanteriorcontador($pacientetemp_id);

        //$this->carregarView($data, 'giah/servidor-form');
        
        $this->loadView('ambulatorio/reagendarfisioterapiapacientetemp-form', $data);
    }

    function reangedarexametemp($agenda_exames_id, $pacientetemp_id, $medico_consulta_id) {
//        if (isset($faltou)) {
//            $data['faltou'] = $faltou;
//        }
        $data['pacientetemp_id'] = $pacientetemp_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['medico_consulta_id'] = $medico_consulta_id;
        $obj_paciente = new paciente_model($pacientetemp_id);
        $data['obj'] = $obj_paciente;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['contador'] = $this->exametemp->contadorfisioterapiapaciente($pacientetemp_id);
        $data['exames'] = $this->exametemp->listaragendatotalpacientefisioterapiareangedar($agenda_exames_id);
        $data['consultasanteriores'] = $this->exametemp->listarespecialidadeanterior($pacientetemp_id);
        $data['consultaanteriorcontador'] = $this->exametemp->listarconsultaanteriorcontador($pacientetemp_id);
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/reagendarexamepacientetemp-form', $data);
    }

    function reangedarconsultatemp($agenda_exames_id, $pacientetemp_id, $medico_consulta_id) {
//        if (isset($faltou)) {
//            $data['faltou'] = $faltou;
//        }
        $data['pacientetemp_id'] = $pacientetemp_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['medico_consulta_id'] = $medico_consulta_id;
        $obj_paciente = new paciente_model($pacientetemp_id);
        $data['obj'] = $obj_paciente;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['contador'] = $this->exametemp->contadorfisioterapiapaciente($pacientetemp_id);
        $data['exames'] = $this->exametemp->listaragendatotalpacientefisioterapiareangedar($agenda_exames_id);
        $data['consultasanteriores'] = $this->exametemp->listarespecialidadeanterior($pacientetemp_id);
        $data['consultaanteriorcontador'] = $this->exametemp->listarconsultaanteriorcontador($pacientetemp_id);

        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/reagendarconsultapacientetemp-form', $data);
    }

    function reangedargeraltemp($agenda_exames_id, $pacientetemp_id, $medico_consulta_id) {
//        if (isset($faltou)) {
//            $data['faltou'] = $faltou;
//        }
        $data['pacientetemp_id'] = $pacientetemp_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['medico_consulta_id'] = $medico_consulta_id;
        $obj_paciente = new paciente_model($pacientetemp_id);
        $data['obj'] = $obj_paciente;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['contador'] = $this->exametemp->contadorfisioterapiapaciente($pacientetemp_id);
        $data['exames'] = $this->exametemp->listaragendatotalpacientefisioterapiareangedar($agenda_exames_id);
        $data['consultasanteriores'] = $this->exametemp->listarespecialidadeanterior($pacientetemp_id);
        $data['consultaanteriorcontador'] = $this->exametemp->listarconsultaanteriorcontador($pacientetemp_id);

        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/reagendargeralpacientetemp-form', $data);
    }

    function listarcredito($paciente_id) {

        $data['paciente_id'] = $paciente_id;
        $data['permissoes'] = $this->guia->listarempresapermissoes();
        $data['valortotal'] = $this->exametemp->listarsaldocreditopaciente($paciente_id);
        $data['gerente_recepcao_top_saude'] = $data['permissoes'][0]->gerente_recepcao_top_saude;
        $this->loadView('ambulatorio/carregarcredito-lista', $data);
    }

    function listarinadimplencia($paciente_id) {

        $data['paciente_id'] = $paciente_id;
        $data['permissoes'] = $this->guia->listarempresapermissoes();
        $data['valortotal'] = $this->exametemp->listarsaldoinadimplenciapaciente($paciente_id);
        $data['gerente_recepcao_top_saude'] = $data['permissoes'][0]->gerente_recepcao_top_saude;

        $this->loadView('ambulatorio/carregarinadimplencia-lista', $data);
    }

    function mostrarpendencias() {

        $data['pendencias'] = $this->exametemp->listarpendenciasoperador();


        $this->load->view('ambulatorio/mostrarpendencias', $data);
    }

    function gerasaldocredito($paciente_id) {
        $data['paciente_id'] = $paciente_id;

        $credito = $this->exametemp->gerasaldocredito($paciente_id);
//        var_dump($credito); die;

        if (@$credito[0]->valor_total == '0.00') {
            $data['extenso'] = 'ZERO';
        } else {
            $valoreditado = str_replace(",", "", str_replace(".", "", @$credito[0]->valor_total));
            $data['extenso'] = GExtenso::moeda($valoreditado);
        }

        $data['credito'] = $credito;
        $data['empresa'] = $this->guia->listarempresa();

//        var_dump($credito); die;
        $this->load->view('ambulatorio/impressaosaldopacientecredito', $data);
    }

    function gerarecibocredito($paciente_credito_id, $paciente_id) {
        $data['paciente_credito_id'] = $paciente_credito_id;

        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes($empresa_id);
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);

        $data['impressaorecibo'] = $this->guia->listarconfiguracaoimpressaorecibo($empresa_id);
        @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;

        $data['paciente'] = $this->paciente->listardados($paciente_id);

        $credito = $this->exametemp->gerarecibocredito($paciente_credito_id);
//        var_dump($credito); die;
        if ($credito[0]->valor == '0,00') {
            $data['extenso'] = 'ZERO';
        } else {
            $valoreditado = str_replace(",", "", str_replace(".", "", $credito[0]->valor));
            $data['extenso'] = GExtenso::moeda($valoreditado);
        }

        $data['credito'] = $credito;

        if ($data['empresapermissoes'][0]->recibo_config == 't') {

            if ($data['impressaorecibo'][0]->cabecalho == 't') {
                if ($data['empresa'][0]->cabecalho_config == 't') { // Cabeçalho Da clinica
                    $cabecalho = "$cabecalho_config";
                } else {
                    $cabecalho = "<table><tr><td><img width='1000px' height='180px' src='img/cabecalho.jpg'></td></tr></table>";
                }
            } else {
                $cabecalho = '';
            }

            if ($data['impressaorecibo'][0]->rodape == 't') { // rodape da empresa
                if ($data['empresa'][0]->rodape_config == 't') {
                    $rodape = $rodape_config;
                } else {
                    $rodape = "";
                }
            } else {
                $rodape = "";
            }
            $data['cabecalho'] = $cabecalho;
            $data['rodape'] = $rodape;

            $this->load->View('ambulatorio/impressaorecibocreditoconfiguravel', $data);
        } else {
            $this->load->view('ambulatorio/reciboprocedimentocredito', $data);
        }
    }

    function enviarpendenteatendimento($exames_id, $sala_id, $agenda_exames_id) {
        $verificar = $this->exametemp->enviarpendenteatendimento($exames_id, $sala_id, $agenda_exames_id);
        if ($verificar == -1) {
            $data['mensagem'] = 'Erro ao finalizar o Exame. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao finalizar o Exame.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function mostrarlembretes() {

        $data['lembretes'] = $this->exametemp->listarlembretesoperador();

        $this->load->view('ambulatorio/mostrarlembretes', $data);
    }

    function carregarcredito($paciente_id) {
        $data['paciente_id'] = $paciente_id;
        $data['convenio'] = $this->convenio->listardados();
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['permissoes'] = $this->guia->listarempresapermissoes();
        $this->loadView('ambulatorio/novocredito-form', $data);
    }

    function gravarcredito() {
        $paciente_id = $_POST['txtpaciente_id'];
        $credito_id = $this->exametemp->gravarcredito();
        redirect(base_url() . "ambulatorio/exametemp/faturarcreditos/$credito_id/$paciente_id");
    }

    function carregarinadimplencia($paciente_id) {
        $data['paciente_id'] = $paciente_id;
        $data['convenio'] = $this->convenio->listardados();
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['permissoes'] = $this->guia->listarempresapermissoes();
        $this->loadView('ambulatorio/novoinadimplencia-form', $data);
    }

    function gravarinadimplencia() {
        $paciente_id = $_POST['txtpaciente_id'];
        $credito_id = $this->exametemp->gravarinadimplencia();
        redirect(base_url() . "ambulatorio/exametemp/listarinadimplencia/$paciente_id");
    }

    function faturarinadimplencia($inadimplencia_id, $paciente_id) {
        $data['forma_pagamento'] = $this->guia->formadepagamentofaturarcredito();
        $data['valor_inadimplencia'] = $this->exametemp->listarinadimplenciafaturar($inadimplencia_id);
        $data['paciente_id'] = $paciente_id;
        $data['inadimplencia_id'] = $inadimplencia_id;
        $data['valor'] = 0.00;
        $permissoes = $this->guia->listarempresapermissoes();

        $this->load->View('ambulatorio/faturarinadimplencia-form', $data);
    }

    function faturarcreditos($credito_id, $paciente_id) {
        $data['forma_pagamento'] = $this->guia->formadepagamentofaturarcredito();
        $data['valor_credito'] = $this->exametemp->listarcreditofaturar($credito_id);
        $data['paciente_id'] = $paciente_id;
        $data['credito_id'] = $credito_id;
        $data['valor'] = 0.00;
        $permissoes = $this->guia->listarempresapermissoes();
        if ($permissoes[0]->ajuste_pagamento_procedimento == 't') {
            $this->load->View('ambulatorio/faturarcreditopersonalizado-form', $data);
        } else {
            $this->load->View('ambulatorio/faturarcredito-form', $data);
        }
    }

    function gravarfaturarcreditopersonalizado() {
        $credito_id = $_POST['credito_id'];
        $paciente_id = $_POST['paciente_teste_id'];

        if ((float) $_POST['valortotal'] == 0) {
            $this->guia->gravarfaturarcreditopersonalizado();
            redirect(base_url() . "ambulatorio/exametemp/listarcredito/$paciente_id");
        } else {
            redirect(base_url() . "ambulatorio/exametemp/faturarcreditos/$credito_id/$paciente_id");
        }
    }

    function gravarfaturarinadimplencia() {
        $inadimplencia_id = $_POST['inadimplencia_id'];
        $paciente_id = $_POST['paciente_teste_id'];
        if ((float) $_POST['valortotal'] == 0) {
            $this->guia->gravarfaturarinadimplencia();
            redirect(base_url() . "ambulatorio/exametemp/listarinadimplencia/$paciente_id");
        } else {
            redirect(base_url() . "ambulatorio/exametemp/faturarinadimplencia/$inadimplencia_id/$paciente_id");
        }
//        $data['agenda_exames_id'] = $agenda_exames_id;
//        $this->load->View('ambulatorio/faturar-form', $data);
    }

    function gravarfaturarcredito() {
        $credito_id = $_POST['credito_id'];
        $paciente_id = $_POST['paciente_teste_id'];
        if ((float) $_POST['valortotal'] == 0) {
            $this->guia->gravarfaturamentocredito();
            redirect(base_url() . "ambulatorio/exametemp/listarcredito/$paciente_id");
        } else {
            redirect(base_url() . "ambulatorio/exametemp/faturarcreditos/$credito_id/$paciente_id");
        }
//        $data['agenda_exames_id'] = $agenda_exames_id;
//        $this->load->View('ambulatorio/faturar-form', $data);
    }

    function trasnferircredito($credito_id, $paciente_id) {
        $verificar = $this->exametemp->listarusocredito($credito_id);
        if (!$verificar) {
            // var_dump($verificar); die;
            $data['mensagem'] = 'Erro ao transferir. Crédito já utilizado.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/listarcredito/$paciente_id");
        }
        $data['credito'] = $this->exametemp->listarcreditotransferir($credito_id);
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['paciente_id'] = $paciente_id;
        $data['credito_id'] = $credito_id;
        $data['permissoes'] = $this->guia->listarempresapermissoes();
        $this->loadView('ambulatorio/transferircredito-form', $data);
    }

    function gravartransferircredito() {
        $paciente_id = $_POST['paciente_id'];
        $paciente_new_id = $_POST['paciente_new_id'];
        $credito_id = $_POST['credito_id'];
        $verificar = $this->exametemp->transferircredito($credito_id, $paciente_id, $paciente_new_id);
        if ($verificar == -1) {
            $data['mensagem'] = 'Erro ao transferir o Crédito. Opera&ccedil;&atilde;o cancelada.';
        } elseif ($verificar == -2) {
            $data['mensagem'] = 'Erro ao transferir. Crédito já utilizado.';
        } else {
            $data['mensagem'] = 'Sucesso ao transferir o Crédito.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exametemp/listarcredito/$paciente_id");
    }

    function excluircredito($credito_id, $paciente_id) {
        $this->exametemp->registrainformacaoestornocredito($credito_id);
        $verificar = $this->exametemp->excluircredito($credito_id);
        if ($verificar == -1) {
            $data['mensagem'] = 'Erro ao estornar o Crédito. Opera&ccedil;&atilde;o cancelada.';
        } elseif ($verificar == -2) {
            $data['mensagem'] = 'Erro ao estornar. Crédito já utilizado.';
        } else {
            $data['mensagem'] = 'Sucesso ao estornar o Crédito.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exametemp/listarcredito/$paciente_id");
    }

    function excluirinadimplencia($inadimplencia_id, $paciente_id) {
        $verificar = $this->exametemp->excluirinadimplencia($inadimplencia_id);
        if ($verificar == -1) {
            $data['mensagem'] = 'Erro ao estornar o Crédito. Opera&ccedil;&atilde;o cancelada.';
        } elseif ($verificar == -2) {
            $data['mensagem'] = 'Erro ao estornar. Crédito já utilizado.';
        } else {
            $data['mensagem'] = 'Sucesso ao estornar o Crédito.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exametemp/listarinadimplencia/$paciente_id");
    }

    function carregaragendamultiempresa3($agenda_exames_id, $externo_id) {
        $data['externo_id'] = $externo_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $externo = $this->exame->listarexterno($externo_id);

        $parametro = array(
            "acao" => "convenio",
            "agenda_exames_id" => $agenda_exames_id,
            "ip" => $externo[0]->ip_externo
        );

        $dados = http_build_query($parametro);

        $contexto = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'content' => $dados,
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                . "Content-Length: " . strlen($dados) . "\r\n",
            )
        ));

        $url = "acao={$parametro['acao']}&ip={$parametro['ip']}&agenda_exames_id={$agenda_exames_id}";
        $resposta = file_get_contents("http://localhost/arquivoRequisicoes.php?{$url}", null, $contexto);

//        $data['convenio'] = json_decode($resposta);

        $array = explode("|", $resposta);
        $convenio = json_decode($array[0]);
        $dadosAgendaExame = json_decode($array[1]);

        $data["convenio"] = $convenio;
        $data["consultas"] = $dadosAgendaExame;
        $data["ip"] = $externo[0]->ip_externo;

        $this->loadView('ambulatorio/consultapacientemultiempresa-form', $data);
    }

    function carregaragendamultiempresa($agenda_exames_id, $medico_id, $externo_id) {
        $data['medico'] = $medico_id;
        $data['externo_id'] = $externo_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $externo = $this->exame->listarexterno($externo_id);

        $parametro = array(
            "acao" => "convenio",
            "agenda_exames_id" => $agenda_exames_id,
            "ip" => $externo[0]->ip_externo
        );

        $dados = http_build_query($parametro);

        $contexto = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'content' => $dados,
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                . "Content-Length: " . strlen($dados) . "\r\n",
            )
        ));

        $url = "acao={$parametro['acao']}&ip={$parametro['ip']}&agenda_exames_id={$agenda_exames_id}";
        $resposta = file_get_contents("http://localhost/arquivoRequisicoes.php?{$url}", null, $contexto);

//        $data['convenio'] = json_decode($resposta);

        $array = explode("|", $resposta);
        $convenio = json_decode($array[0]);
        $dadosAgendaExame = json_decode($array[1]);

        $data["convenio"] = $convenio;
        $data["consultas"] = $dadosAgendaExame;
        $data["ip"] = $externo[0]->ip_externo;

        $this->loadView('ambulatorio/consultapacientemultiempresa-form', $data);
    }

    function carregarexamegeral($agenda_exames_id, $medico_id) {
        $data['medico'] = $medico_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['convenio'] = $this->procedimentoplano->listarconveniomedico($medico_id);
        $data['exames'] = $this->exametemp->listaragendasexamepaciente($agenda_exames_id);
        $data['grupos'] = $this->procedimento->listargruposatendimento();
        $data['origem'] = $this->sala->listarorigem();
        $data['setor'] = $this->guia->listarsetores();
        $permissoes = $this->guia->listarempresapermissoes();
        $data['empresapermissoes'] = $permissoes[0]->setores;
        $data['email_obrigatorio'] = $permissoes[0]->email_obrigatorio;
// echo '<pre>';
// print_r($data['empresapermissoes']);
// die; 
//        $horainicio = $data['exames'][0]->inicio;
//        $horafim = $data['exames'][0]->fim;
//
//        $data['exameshorario'] = $this->exametemp->listaragendasexamepacientehorario($horainicio, $horafim, $agenda_exames_id, $medico_id);
//        $data['exameshorariofim'] = $this->exametemp->listaragendasexamepacientehorariofinal($horainicio, $horafim, $agenda_exames_id, $medico_id);
//
//        if (count($data['exameshorario']) > 0 || count($data['exameshorariofim']) > 0) {
//            
//            $data['mensagem'] = 'Esse medico já tem paciente neste horario:';
//            $this->session->set_flashdata('message', $data['mensagem']);
//            redirect(base_url() . "ambulatorio/exametemp/carregarexamegeral2/$agenda_exames_id/$medico_id");
//            
//        } else {
        $this->loadView('ambulatorio/examepacientegeral-form', $data);
//        }
    }

    function carregarexamegeralpacienteSelecionado($agenda_exames_id, $medico_id, $paciente_id) {
        $data['medico'] = $medico_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['paciente_id'] = $paciente_id;
        $data['convenio'] = $this->procedimentoplano->listarconveniomedico($medico_id);
        $data['exames'] = $this->exametemp->listaragendasexamepaciente($agenda_exames_id);
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $this->loadView('ambulatorio/examegeralpacienteSelecionado', $data);
    }

    function carregarexamegeral2($agenda_exames_id, $medico_id) {
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['medico'] = $medico_id;
//        $data['medicos'] = $this->exametemp->listarmedicoconsulta();
        $data['convenio'] = $this->procedimentoplano->listarconveniomedico($medico_id);
        $data['exames'] = $this->exametemp->listaragendasexamepaciente($agenda_exames_id);

//        $horainicio = $data['exames'][0]->inicio;
//        $horafim = $data['exames'][0]->fim;
//
//        $data['exameshorario'] = $this->exametemp->listaragendasexamepacientehorario($horainicio, $horafim, $agenda_exames_id, $medico_id);
//        $data['exameshorariofim'] = $this->exametemp->listaragendasexamepacientehorariofinal($horainicio, $horafim, $agenda_exames_id, $medico_id);
        $this->loadView('ambulatorio/examepacientegeral-form', $data);
    }

    function carregarexamegeral3($agenda_exames_id) {
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['exames'] = $this->exametemp->listaragendasexamepaciente($agenda_exames_id);

//        $horainicio = $data['exames'][0]->inicio;
//        $horafim = $data['exames'][0]->fim;
//
//        $data['exameshorario'] = $this->exametemp->listaragendasexamepacientehorario($horainicio, $horafim, $agenda_exames_id, $medico_id);
//        $data['exameshorariofim'] = $this->exametemp->listaragendasexamepacientehorariofinal($horainicio, $horafim, $agenda_exames_id, $medico_id);
        $this->loadView('ambulatorio/examepacientegeralmedico-form', $data);
    }

    function carregarexame($agenda_exames_id) {
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['exames'] = $this->exametemp->listaragendasexamepaciente($agenda_exames_id);
        $medico = $data['exames'][0]->medico_agenda;
        $datas = $data['exames'][0]->data;
        $data['valor'] = $this->exametemp->listarvalortotalexames($medico, $datas);

        $data['origem'] = $this->sala->listarorigem();
        $data['setor'] = $this->guia->listarsetores();
        $permissoes = $this->guia->listarempresapermissoes();
        $data['empresapermissoes'] = $permissoes[0]->setores;

        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/examepaciente-form', $data);
    }

    function carregarconsultatemp($agenda_exames_id) {
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['consultas'] = $this->exametemp->listaragendasconsultapaciente($agenda_exames_id);

        $data['origem'] = $this->sala->listarorigem();
        $permissoes = $this->guia->listarempresapermissoes();
        $data['empresapermissoes'] = $permissoes[0]->origem_agendamento;
        
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/consultapaciente-form', $data);
    }

    function carregarfisioterapiatemp($agenda_exames_id) {
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['consultas'] = $this->exametemp->listaragendasconsultapaciente($agenda_exames_id);
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/fisioterapiapaciente-form', $data);
    }
    
       
    function carregarfisioterapiatempmedico($agenda_exames_id) {
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['consultas'] = $this->exametemp->listaragendasconsultapaciente($agenda_exames_id);

        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/fisioterapiapacientemedico-form', $data);
    }

    function excluir($agenda_exames_id, $ambulatorio_pacientetemp_id) {
        $this->exametemp->excluir($agenda_exames_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarexametemp/$ambulatorio_pacientetemp_id");
    }

    function excluirprocedimentoguia($agenda_exames_id, $guia_id) {
        $this->exametemp->excluirprocedimentoguia($agenda_exames_id);
        redirect(base_url() . "ambulatorio/exame/guiacirurgicaitens/$guia_id");
    }

    function excluirexametemp($agenda_exames_id, $pacientetemp_id) {
        $this->exametemp->salvaragendamentoexcluido($agenda_exames_id);
        $this->exametemp->excluirexametemp($agenda_exames_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientetemp/$pacientetemp_id");
    }

    function excluirconsultatempagendamento($agenda_exames_id, $paciente_id, $tipo, $encaixe, $googleId) {
        if($encaixe == 'false'){
            $verificar = $this->exametemp->verificarsolicitacaoagendamento($agenda_exames_id);
                if(count($verificar) > 0){
                $mensagem = 'Existe Pacientes na Solicitacao de Agendamento.';
                $this->session->set_userdata('message', $mensagem);
                }
            }

            if($this->session->userdata('google_calendar_access_token')){
                if($googleId != 0){
                $this->googlecalendar->DeletarAgenda('primary', $googleId);
                }

            }

        
        // $this->exametemp->salvaragendamentoexcluidomotivo($agenda_exames_id, $tipo, $encaixe);
        // var_dump($encaixe); die;
        $this->exametemp->excluirexametempagendamento($agenda_exames_id, $encaixe);

        if ($tipo == 'GERAL') {
            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$paciente_id");
        } elseif ($tipo == 'CONSULTA') {
            redirect(base_url() . "ambulatorio/exametemp/carregarpacienteconsultatemp/$paciente_id");
        } elseif ($tipo == 'EXAME') {
            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetemp/$paciente_id");
        } else {
            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$paciente_id");
        }
    }

    function excluirconsultatempgeral($agenda_exames_id, $pacientetemp_id) {
        $this->exametemp->salvaragendamentoexcluido($agenda_exames_id);
        $this->exametemp->excluirexametemp($agenda_exames_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$pacientetemp_id");
    }

    function excluirconsultatemp($agenda_exames_id, $pacientetemp_id) {
        $this->exametemp->salvaragendamentoexcluido($agenda_exames_id);
        $this->exametemp->excluirexametemp($agenda_exames_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacienteconsultatemp/$pacientetemp_id");
    }

    function excluirconsultatempmedico($agenda_exames_id) {
        $this->exametemp->salvaragendamentoexcluido($agenda_exames_id);
        $this->exametemp->excluirexametemp($agenda_exames_id);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function excluirconsultatempencaixe($agenda_exames_id, $pacientetemp_id) {
        $this->exametemp->salvaragendamentoexcluido($agenda_exames_id);
        $this->exametemp->excluirexametempencaixe($agenda_exames_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacienteconsultatemp/$pacientetemp_id");
    }

    function excluirgeraltempencaixe($agenda_exames_id, $pacientetemp_id) {
        $this->exametemp->salvaragendamentoexcluido($agenda_exames_id);
        $this->exametemp->excluirexametempencaixe($agenda_exames_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$pacientetemp_id");
    }

    function excluirexametempencaixeodontologia($agenda_exames_id, $pacientetemp_id) {
        $this->exametemp->salvaragendamentoexcluido($agenda_exames_id);
        $this->exametemp->excluirexametempencaixeodontologia($agenda_exames_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientetemp/$pacientetemp_id");
    }

    function excluirexametempencaixe($agenda_exames_id, $pacientetemp_id) {
        $this->exametemp->salvaragendamentoexcluido($agenda_exames_id);
        $this->exametemp->excluirexametempencaixe($agenda_exames_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientetemp/$pacientetemp_id");
    }

    function examecancelamentoencaixe($agenda_exames_id,$exames_id=NULL) {
        $this->exametemp->salvaragendamentoexcluido($agenda_exames_id);
        $this->exametemp->excluirexametempencaixe($agenda_exames_id,$exames_id);
        redirect(base_url() . "ambulatorio/exame/listarmultifuncaomedicofisioterapia");
    }

    function excluirfisioterapiatempencaixe($agenda_exames_id, $pacientetemp_id) {
        $this->exametemp->salvaragendamentoexcluido($agenda_exames_id);
        $this->exametemp->excluirexametempencaixe($agenda_exames_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientefisioterapiatemp/$pacientetemp_id");
    }

    function excluirfisioterapiatemp($agenda_exames_id, $pacientetemp_id,$reagendar=NULL) {
        $this->exametemp->salvaragendamentoexcluido($agenda_exames_id);
        $this->exametemp->excluirexametemp($agenda_exames_id);
        
        if ($reagendar == "true") {       
          redirect(base_url() . "ambulatorio/exametemp/carregarpacientefisioterapiatemp2/$pacientetemp_id");
        }
       redirect(base_url() . "ambulatorio/exametemp/carregarpacientefisioterapiatemp/$pacientetemp_id");
        
        
    }

    function excluirfisioterapiatempmultifuncaomedico($agenda_exames_id,$exames_id=NULL) {
//        $args = str_replace('!', '=', $url);
//        $args = str_replace('@', '&', $args);
        $this->exametemp->salvaragendamentoexcluido($agenda_exames_id);
        $this->exametemp->excluirexametempmultifuncaomedico($agenda_exames_id,$exames_id);
        redirect(base_url() . "ambulatorio/exame/listarmultifuncaomedicofisioterapia");
    }

    function gravar() {
        $exametemp_tuss_id = $this->exametemp->gravar();
        if ($exametemp_tuss_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar o Procedimento. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar o Procedimento.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exametemp");
    }

    function excluirpaciente($paciente_id) {
        $pacienteexame = $this->exametemp->contadorpacienteexame($paciente_id);
        $pacienteagendaexame = $this->exametemp->contadorpaciente($paciente_id);
        $pacienteguia = $this->exametemp->contadorpacienteguia($paciente_id);
        $pacienteconsulta = $this->exametemp->contadorpacienteconsulta($paciente_id);
        $pacientelaudo = $this->exametemp->contadorpacientelaudo($paciente_id);
        if ($pacienteexame != 0 || $pacienteagendaexame != 0 || $pacienteguia != 0 || $pacienteconsulta != 0 || $pacientelaudo != 0) {
            $data['mensagem'] = 'Erro ao excluir o Paciente. Favor verificar pendencias';
        } else {
            $verifica = $this->exametemp->excluirpaciente($paciente_id);
            if ($verifica == "-1") {
                $data['mensagem'] = 'Erro ao excluir o Paciente. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao excluir o Paciente.';
            }
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/pacientes");
    }

    function gravartemp() {
        $ambulatorio_pacientetemp_id = $_POST['txtpaciente_id'];
        $this->exametemp->gravarexames($ambulatorio_pacientetemp_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarexametemp/$ambulatorio_pacientetemp_id");
    }

    function gravarguiacirurgicaprocedimentos() {
        $guia_id = $_POST['txtguiaid'];
        $procedimento_valor = $this->procedimento->carregavalorprocedimentocirurgico($_POST['procedimento']);
        $_POST['valor'] = $procedimento_valor[0]->valortotal;
//        var_dump($procedimento_valor); die;
        $this->exametemp->gravarguiacirurgicaprocedimentos();
        redirect(base_url() . "ambulatorio/exame/guiacirurgicaitens/$guia_id");
    }

    function enviarWhatsappUnitario($agenda_exames_id){
        $pp['retorno'] = $this->agenda->listarpermissoes();
        if (@$pp['retorno'][0]->servicowhatsapp == 't') {
//       BLOCO ONDE ACONTECE O ENVIO DOS EXAMES DE UM BANCO PARAR OUTRO
            $quantidade['quantidade'] = $this->agenda->listarquantidadeatual(); //quantidade atual de envios da clinica
            $contador_atual = $quantidade['quantidade'][0]->contador;
            $pacote['pacote'] = $this->agenda->listarpacoteempresa(); //o pacote da clinica
            $qtdmax = $pacote['pacote'][0]->pacote;
            $maximo = $qtdmax - $contador_atual; //o maximo de registros que o select() vai buscar na funcao abaixo, ou seja, o total do pacote menos o contador atual da clinica 
            $data = $this->agenda->getAgendaexamesUnity($agenda_exames_id, $maximo);
            $mensagem = $this->exametemp->gravarcadastrowhatsapp($agenda_exames_id);

            $quantoveio = count($data); //essa quantidade é a que veio no select()  acima
            $this->agenda->atualizarcontador($quantoveio, $contador_atual);  //atualiza o contador da clinica que está logada no sistema
            if (!($quantidade['quantidade'][0]->contador >= $pacote['pacote'][0]->pacote)) {             
            
                $qtd = count($data); //isso é pra saber quantas vezes o for() vai rodar de acordo com o total de registro que veio da tb_agenda_exames  
                $endereco_externo['endereco_externo'] = $this->agenda->listarendereco_externo(); //pegando o endereço externo
                $endereco_externo = $endereco_externo['endereco_externo'][0]->endereco_externo;
                for ($i = 0; $i < $qtd; $i++) {
                    $data[$i]->mensagem = $mensagem; // Inserindo uma nova mensagem
                    // var_dump($data);
                    // die;
                    $url = $endereco_externo . "welcome/gravartesteteste"; //essa url é do computar 'BOT' onde vai está rodando o macro.
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_HEADER, 0);
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data[$i]);
                    curl_exec($curl);
                    curl_close($curl);
                }
                return true;
            }
////       BLOCO ONDE ACONTECE O ENVIO DOS EXAMES DE UM BANCO PARAR OUTRO        
        }
    }

    function carregavalorprocedimentocirurgico() {

        if (isset($_GET['procedimento_id']) && isset($_GET['equipe_id'])) {
            $procedimento_valor = $this->procedimento->carregavalorprocedimentocirurgico($_GET['procedimento_id']);
            $equipe = $this->exame->listarquipeoperadores($_GET['equipe_id']);

            $valorProcedimento = ((float) ($procedimento_valor[0]->valor_total));
            $valorCirurgiao = 0;
            $valorAnestesista = 0;

            foreach ($equipe as $value) {
                if ($value->funcao == '00') {//cirurgiao
                } elseif ($value->funcao == '00') {//anestesista
                }
            }
        }
        return $result;
    }

    function gravarpacienteexametemp($agenda_exames_id, $empresa = null) {
        $agenda_id = $_POST['agendaid'];
        if (trim($_POST['convenio1']) == "-1") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio selecionar o convenio.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/carregarexame/$agenda_id");
        } elseif (trim($_POST['procedimento1']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio selecionar o procedimento.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/carregarexame/$agenda_id");
        } else {
            $paciente_id = $this->exametemp->gravarpacienteexames($agenda_exames_id, $empresa);
            $return = $this->exametemp->gravarcadastrowhatsapp($agenda_exames_id);
            if ($paciente_id != 0) {
                redirect(base_url() . "ambulatorio/exametemp/carregarpacientetemp/$paciente_id");
            } else {
                $data['mensagem'] = 'Erro ao marcar exame o horario esta oculpado.';
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "ambulatorio/exame/listarmultifuncao");
            }
        }
    }

    function gravarpacienteexametempgeral($agenda_exames_id, $empresa = null) {
        // echo '<pre>';
        // print_r($_POST);
        // die;
        $medico = $_POST['medico'];

        if (trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/carregarexamegeral/$agenda_exames_id");
        }else {
            $tipo = $this->exametemp->tipomultifuncaogeral($_POST['procedimento1']);
            $paciente_id = $this->exametemp->gravarpacienteexames($agenda_exames_id, $tipo[0]->tipo, $empresa);

            if($paciente_id == -10){
                $mensagem = 'O Paciente já está Marcado no mesmo Horario e Dia';
                $this->session->set_flashdata('message', $mensagem);
                redirect(base_url() . "ambulatorio/exametemp/carregarexamegeral/$agenda_exames_id/$medico");
            }

            $return = $this->exametemp->gravarcadastrowhatsapp($agenda_exames_id);
            if ($paciente_id != 0) {

                if($this->session->userdata('google_calendar_access_token')){
                $dadospaciente = $this->guia->dadospaciente($_POST['txtNomeid']);
                $dadosmedico = $this->guia->dadosmedico($_POST['medico']);
                $dadosprocedimento = $this->guia->dadosprocedimento($_POST['procedimento1']);
                $dadosempresa = $this->guia->listarempresapermissoes();

                $_POST['data'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data_agendamento'])));
                $_POST['description'] = 'Médico: '.$dadosmedico[0]->nome.'<br>Procedimento: '.$dadosprocedimento[0]->nome.'<br>Observação: '.$_POST['observacoes'];
                
                if($dadospaciente[0]->cns != ''){
                    $qtdemail = 2;
                    $event = array(
                        'summary'     => $dadospaciente[0]->nome,
                        'start'       => $_POST['data'].'T'.$_POST['inicio'].'-03:00',
                        'end'         => $_POST['data'].'T'.$_POST['fim'].'-03:00',
                        'description' => $_POST['description'],
                        'email' => $dadosmedico[0]->email,
                        'email2' => $dadospaciente[0]->cns,
                        'colorid' => $dadosmedico[0]->coragenda,
                        'localizacao' => $dadosempresa[0]->logradouro.' '.$dadosempresa[0]->numero.' - '.$dadosempresa[0]->bairro.' - '.$dadosempresa[0]->municipio,
            
                    );

                }else{
                $qtdemail = 1;
                $event = array(
                    'summary'     => $dadospaciente[0]->nome,
                    'start'       => $_POST['data'].'T'.$_POST['inicio'].'-03:00',
                    'end'         => $_POST['data'].'T'.$_POST['fim'].'-03:00',
                    'description' => $_POST['description'],
                    'email' => $dadosmedico[0]->email,
                    'colorid' => $dadosmedico[0]->coragenda,
                    'localizacao' => $dadosempresa[0]->logradouro.' '.$dadosempresa[0]->numero.' - '.$dadosempresa[0]->bairro.' - '.$dadosempresa[0]->municipio,
        
                );
            }
        
                $foo = $this->googlecalendar->addEvent('primary', $event, $qtdemail);
        
                  if ($foo->status == 'confirmed') {
                        $data['message'] = 'Evento Adicionado com Sucesso';
                        $this->session->set_userdata('message', $data['message']);

                        $this->exametemp->confirmaragendagoogle($agenda_exames_id);
                  }
                }

                // if(isset($_POST['btnEnviarArquivo'])){
                //     $procedimento = $this->guia->pegaridprocedimento($_POST['procedimento1']);
                //     $idprocedimento = $procedimento[0]->procedimento_tuss_id;
                //     $empresaInfo = $this->guia->listarempresa();


                //     $this->load->helper('directory');
                //     $arquivo_pasta = directory_map("./upload/arquivoprocedimento/$idprocedimento/");
                    
                //     $horario = date('H:i');
                //     if($horario >= '06:00' && $horario <= '12:00'){
                //         $tempo = 'Bom Dia';
                //     }elseif($horario >= '12:01' && $horario <= '18:00'){
                //         $tempo = 'Boa Tarde';
                //     }else{
                //         $tempo = 'Boa Noite';
                //     }

                //     $mensagememail = $tempo.' Sr(a). '.$_POST['txtNome'].' <br><br>
                //                     Segue abaixo em Anexo os arquivos para a Preparação do Exame';

                //     $this->load->library('email');

                //     $config['protocol'] = 'smtp';
                //     $config['smtp_host'] = 'ssl://smtp.gmail.com';
                //     $config['smtp_port'] = '465';
                //     $config['smtp_user'] = 'stgsaude@gmail.com';
                //     $config['smtp_pass'] = 'saude@stg*1202';
                //     $config['validate'] = TRUE;
                //     $config['mailtype'] = 'html';
                //     $config['charset'] = 'utf-8';
                //     $config['newline'] = "\r\n";

                //     $this->email->initialize($config);
                //     if (@$empresaInfo[0]->email != '') {
                //         $this->email->from($empresaInfo[0]->email, $empresaInfo[0]->nome);
                //     } else {
                //         $this->email->from('stgsaude@gmail.com', $empresaInfo[0]->nome);
                //     }
                //     $this->email->to($_POST['txtEmail']); 
                //     $this->email->subject("Preparo de Exames");
                //     foreach($arquivo_pasta as $value){
                //         $this->email->attach('./upload/arquivoprocedimento/'.$idprocedimento.'/'.$value);
                //     }
                //     $this->email->message($mensagememail);

                //     if ($this->email->send()) {
                //         $data['message'] = 'Email Enviado com Sucesso';
                //     }else{
                //         print($this->email->erro());
                //         die;
                //     }
                // }

                redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$paciente_id");
            } else {
                $data['mensagem'] = 'Erro ao marcar exame o horario esta oculpado.';
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "ambulatorio/exame/listarmultifuncaogeral");
            }
        }
    }

    function gravarexametempgeralSelecionado($agenda_exames_id) {
//        die;
//        $agenda = $_POST['agendaid'];
        $medico = $_POST['medico'];
        if (trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/carregarexamegeral3/$agenda_exames_id");
        } else {
            $tipo = $this->exametemp->tipomultifuncaogeral($_POST['procedimento1']);
            $paciente_id = $this->exametemp->gravarpacienteexamesSelecionado($agenda_exames_id, $tipo[0]->tipo);
            $return = $this->exametemp->gravarcadastrowhatsapp($agenda_exames_id);
            if ($paciente_id != 0) {
                redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$paciente_id");
            } else {
                $data['mensagem'] = 'Erro ao marcar exame o horario esta oculpado.';
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "ambulatorio/exame/listarmultifuncaocalendariopaciente/{$_POST['txtNomeid']}");
            }
        }
    }

    function gravarpacienteagendamultiempresa($agenda_exames_id) {
//        die;
        $tipo = $this->exametemp->tipomultifuncaogeralmultiempresa($_POST['procedimento']);
        $paciente_id = $this->exametemp->gravarpacienteconsultasmultiempresa($agenda_exames_id, $tipo[0]->tipo);

        if ($paciente_id != 0) {
            $data['mensagem'] = 'Consulta agendada com sucesso.';
        } else {
            $data['mensagem'] = 'Erro ao agendar consulta.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function gravarpacienteconsultatemp($agenda_exames_id) {
//        var_dump($_POST); die;
        if (trim($_POST['txtNome']) == "" && trim($_POST['txtNomeid']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/carregarconsultatemp/$agenda_exames_id");
        } elseif (trim($_POST['convenio']) == "0") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio informar o convênio.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/carregarconsultatemp/$agenda_exames_id");
        } elseif (trim($_POST['procedimento']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio informar o procedimento.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/carregarconsultatemp/$agenda_exames_id");
        } else {
            $data['medico'] = $this->exametemp->listarmedicoconsulta();
            $paciente_id = $this->exametemp->gravarpacienteconsultas($agenda_exames_id);
            $return = $this->exametemp->gravarcadastrowhatsapp($agenda_exames_id);
            if ($paciente_id != 0) {
                redirect(base_url() . "ambulatorio/exametemp/carregarpacienteconsultatemp/$paciente_id");
            } else {
                $data['mensagem'] = 'Erro ao marcar consulta o horario esta oculpado.';
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "ambulatorio/exame/listarmultifuncaoconsulta");
            }
        }
    }

    function gravarpacientefisioterapiatemp($agenda_exames_id) {
        if (trim($_POST['txtNome']) == "" && trim($_POST['txtNomeid']) == "") {
            $data['mensagem'] = 'Erro ao marcar especialidade é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacienteconsulta");
        } elseif ($_POST['convenio'] == '0') {
            $data['mensagem'] = 'Erro ao marcar especialidade. Selecione um convenio.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacienteconsulta");
        } elseif ($_POST['procedimento'] == '') {
            $data['mensagem'] = 'Erro ao marcar especialidade. Selecione um procedimento.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacienteconsulta");
        } else {

            $_POST['horarios'] = array_filter($_POST['horarios']);
            $_POST['txtNomeid'] = $this->exametemp->crianovopacienteespecialidade();
            $agrupador = $this->exametemp->agrupadorfisioterapia();
            $agenda_escolhida = array();
            $x = 1;
            foreach ($_POST['horarios'] as $item) {
                $agenda_escolhida [$x] = $item;
                $x++;
            }

            $data['medico'] = $this->exametemp->listarmedicoconsulta();

            if (count($_POST['dia']) > 0 && $_POST['sessao'] > 0) {
                $contador_sessao = $this->exametemp->gravarpacientefisioterapiapersonalizada($_POST['horarios'], $_POST['sessao'], $agrupador);
            }

            $c = 1;
            $semana = 1;

            for ($i = $contador_sessao; $i <= $_POST['sessao']; $i++) {

                $agenda_selecionada = $this->exametemp->listaagendafisioterapiapersonalizada($agenda_escolhida[$c], $semana);

                if ($agenda_selecionada != false) {
                    $this->exametemp->gravarpacientefisioterapiapersonalizadasessao($agenda_selecionada[0]->agenda_exames_id, $_POST['sessao'], $contador_sessao, $agrupador);
                } else {

                    $agenda_inexistente = $this->exametemp->listaagendafisioterapiapersonalizadaerro($agenda_escolhida[$c], $semana);
//                    var_dump($agenda_inexistente); die;
                    $medico = $agenda_inexistente[0]->medico;
                    $hora = $agenda_inexistente[0]->inicio;

                    $data = date("d/m/Y", strtotime($agenda_inexistente[0]->data));
                    $mensagem = "Horário de $medico em $data as $hora não existe ou está ocupado";
//                    echo $mensagem; die;
                    $this->session->set_flashdata('message', $mensagem);

                    foreach ($agenda_escolhida as $item) {
                        $excluir = $this->exametemp->excluirfisioterapiatemp($item);
                    }

                    redirect(base_url() . "ambulatorio/exametemp/carregarfisioterapiatemp/$agenda_escolhida[1]");
                }


                if ($c == count($_POST['horarios'])) {
                    $c = 0;
                    $semana ++;
                }
                $c++;
                $contador_sessao++;
            }
            $return = $this->exametemp->gravarcadastrowhatsapp($agenda_selecionada[0]->agenda_exames_id);

            $paciente_id = $_POST['txtNomeid'];
            $data['mensagem'] = 'Sucesso ao realizar agendamento';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/carregarpacientefisioterapiatemp/$paciente_id");
//            if (count($_POST['dia']) > 0 && $_POST['sessao'] > 0) {                
//            }
            //LOGICA ANTIGA. AQUI ELE BOTA UM POR SEMANA SE NAO MARCAR NADA LÁ NOS CHECKBOXES
//            else {
//
//                if (isset($_POST['sessao'])) {
//                    $data['agenda_selecionada'] = $this->exametemp->listaagendafisioterapia($agenda_exames_id);
//                    $contaHorarios = count($this->exametemp->contadordisponibilidadefisioterapia($data['agenda_selecionada'][0]));
//
//                    //tratando o numero que veio nas sessoes
//                    if ($_POST['sessao'] == '' || $_POST['sessao'] == null || $_POST['sessao'] == 'null' || $_POST['sessao'] == 0) {
//                        $_POST['sessao'] = 1;
//                    }
//                    $_POST['sessao'] = (int) $_POST['sessao'];
//
//                    //pegando os dias da semana disponiveis
//                    $diaSemana = date("Y-m-d", strtotime($data['agenda_selecionada'][0]->data));
//                    $contador = 0;
//
//                    //definindo array que recebera os selects
//                    $horarios_livres = array();
//
//                    //definindo array que tera os valores filtrados de $horarios_livres
//                    $data['horarios_livres'] = array();
//
//                    do {
//                        $horarios_livres = $this->exametemp->listadisponibilidadefisioterapia($data['agenda_selecionada'][0], $diaSemana);
//                        $diaSemana = date("Y-m-d", strtotime("+1 week", strtotime($diaSemana)));
//                        $contador++;
//
//                        //verificando se a busca veio vazia, caso nao, adciona essa busca a $data['horarios_livres']
//                        if (count($horarios_livres) != 0) {
//                            $data['horarios_livres'][] = $horarios_livres[0];
//                        }
//                        if ($contador == $_POST['sessao']) {
//                            break;
//                        }
//                    } while ($contador < $contaHorarios);
////                var_dump($data['horarios_livres']); die;
//                    //limpando o array
//                    $data['horarios_livres'] = array_filter($data['horarios_livres']);
//
//                    //testando se ha disponibilidade de horario para todas as sessoes
//                    $tothorarios = count($data['horarios_livres']);
//
//                    if ($tothorarios < $_POST['sessao']) {
//                        $data['mensagem'] = "Não há horarios suficientes na agenda para o numero de sessoes escolhido";
//                        $this->session->set_flashdata('message', $data['mensagem']);
//                        redirect(base_url() . "ambulatorio/exametemp/novopacienteconsulta");
//                    }
//
//                    $_POST['txtNomeid'] = $this->exametemp->crianovopacienteespecialidade();
//                    $agrupador = $this->exametemp->agrupadorfisioterapia();
//
//                    //marcando sessoes
//                    if ($_POST['sessao'] == 1) {
//                        $paciente_id = $this->exametemp->gravarpacientefisioterapia($data['agenda_selecionada'][0]->agenda_exames_id);
//                    } else {
//                        $contador_sessao = 1;
//                        for ($i = 0; $i < $_POST['sessao']; $i++) {
//                            $paciente_id = $this->exametemp->gravarpacientefisioterapia($data['horarios_livres'][$i]->agenda_exames_id, $_POST['sessao'], $contador_sessao, $agrupador);
//                            $contador_sessao++;
//                        }
//                    }
//
//                    redirect(base_url() . "ambulatorio/exametemp/carregarpacientefisioterapiatemp/$paciente_id");
//                } else {
//                    $paciente_id = $this->exametemp->gravarpacientefisioterapia($agenda_exames_id);
//                    redirect(base_url() . "ambulatorio/exametemp/carregarpacientefisioterapiatemp/$paciente_id");
//                }
//            }
        }
    }

    function reservarexametemp($agenda_exames_id, $paciente_id, $medico_id) {
        // $empresa_id = $this->session->userdata('empresa_id');
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        // var_dump($data['empresapermissoes']); die;
        if ($data['empresapermissoes'][0]->reservar_escolher_proc == 't') {
            $data['agenda_exames_id'] = $agenda_exames_id;
            $data['paciente_id'] = $paciente_id;
            $data['medico_id'] = $medico_id;
            // $data['data'] = $data;

            $obj_paciente = new paciente_model($paciente_id);
            $data['obj'] = $obj_paciente;
            $data['medico'] = $this->exametemp->listarmedicoconsulta();
            $data['convenio'] = $this->procedimentoplano->listarconvenio();
            $data['contador'] = $this->exametemp->contadorfisioterapiapaciente($paciente_id);
            $data['exames'] = $this->exametemp->listaragendasexamepacientereservar($agenda_exames_id);
            $data['grupos'] = $this->procedimento->listargrupos();
            // var_dump($data['exames']); die;
            $this->loadview('ambulatorio/reservarescolherprocedimento', $data);
        } else {
            $paciente_id = $this->exametemp->reservarexametemp($agenda_exames_id, $paciente_id, $medico_id);
            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetemp/$paciente_id");
        }
    }

    function reservarexametempalterarprocedimento($agenda_exames_id, $paciente_id, $medico_id) {
        // $empresa_id = $this->session->userdata('empresa_id');
        // $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        // var_dump($medico_id); die;
        $paciente_id = $this->exametemp->reservarexametempalterarproc($agenda_exames_id, $paciente_id, $medico_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientetemp/$paciente_id");
    }

    function reservarexametempalterarprocedimentogeral($agenda_exames_id, $paciente_id, $medico_id) {
        // $empresa_id = $this->session->userdata('empresa_id');
        // $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        // var_dump($medico_id); die;
        $paciente_id = $this->exametemp->reservarexametempalterarproc($agenda_exames_id, $paciente_id, $medico_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$paciente_id");
    }

    function reservartempgeral($agenda_exames_id, $paciente_id, $medico_id, $data_escolhida) {
        $empresaPermissao = $this->guia->listarempresapermissoes();
        // var_dump($empresaPermissao); die;
        if ($empresaPermissao[0]->reservar_escolher_proc == 't') {
            $data['agenda_exames_id'] = $agenda_exames_id;
            $data['paciente_id'] = $paciente_id;
            $data['medico_id'] = $medico_id;
            // $data['data'] = $data;

            $obj_paciente = new paciente_model($paciente_id);
            $data['obj'] = $obj_paciente;
            $data['medico'] = $this->exametemp->listarmedicoconsulta();
            $data['convenio'] = $this->procedimentoplano->listarconvenio();
            $data['contador'] = $this->exametemp->contadorfisioterapiapaciente($paciente_id);
            $data['exames'] = $this->exametemp->listaragendasexamepacientereservar($agenda_exames_id);
            $data['grupos'] = $this->procedimento->listargrupos();
            // var_dump($data['exames']); die;
            $this->loadview('ambulatorio/reservarescolherprocedimentogeral', $data);
        } else {
            $paciente_id = $this->exametemp->reservarexametemp($agenda_exames_id, $paciente_id, $agenda_exames_nome_id, $data_escolhida);
            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$paciente_id");
        }
    }

    function reservarconsultatemp($agenda_exames_id, $paciente_id, $medico_consulta_id, $data) {
        $paciente_id = $this->exametemp->reservarconsultatemp($agenda_exames_id, $paciente_id, $medico_consulta_id, $data);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacienteconsultatemp/$paciente_id");
    }

    function reservarfisioterapiatemp($agenda_exames_id, $paciente_id, $medico_consulta_id, $data) {
        $paciente_id = $this->exametemp->reservarfisioterapiatemp($agenda_exames_id, $paciente_id, $medico_consulta_id, $data);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientefisioterapiatemp/$paciente_id");
    }

    function gravarpacientetemp() {

        $pacientetemp_id = $_POST['txtpaciente_id'];
        $this->exametemp->gravarexames($pacientetemp_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientetemp/$pacientetemp_id");
    }

    function gravarpacientetempgeral() {

        $pacientetemp_id = $_POST['txtpaciente_id'];

        if ($_POST['data_ficha'] == '' || $_POST['exame'] == '' || $_POST['convenio1'] == '' || $_POST['procedimento1'] == '' || $_POST['horarios'] == '') {
            $data['mensagem'] = 'Insira os campos obrigatorios.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$pacientetemp_id");
        }
//        elseif ($_POST['txtNome'] == '') {
//            $data['mensagem'] = 'Paciente não informado ou inválido.';
//            $this->session->set_flashdata('message', $data['mensagem']);
//            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$pacientetemp_id");
//        } 
        else {
            $this->exametemp->gravarpacienteexistentegeral($pacientetemp_id);
            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$pacientetemp_id");
        }
    }

    function gravarconsultapacientetemp() {

        $pacientetemp_id = $_POST['txtpaciente_id'];
        $this->exametemp->gravarconsultaspacienteexistente($pacientetemp_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacienteconsultatemp/$pacientetemp_id");
    }

    function gravarfisioterapiapacientetemp() {

        $pacientetemp_id = $_POST['txtpaciente_id'];
        $this->exametemp->gravarfisioterapiapacienteexistente($pacientetemp_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientefisioterapiatemp/$pacientetemp_id");
//        $this->carregarpacientefisioterapiatemp($pacientetemp_id);
    }

    function gravarfisioterapiapacientetempreagendar() {

        $pacientetemp_id = $_POST['txtpaciente_id'];
//        $data['exames'] = $this->exametemp->listaragendatotalpacientefisioterapiareangedar();
        $this->exametemp->gravarfisioterapiapacientetempreagendar($pacientetemp_id);
        if ($_POST['reagenda'] == "true") {
              redirect(base_url() . "ambulatorio/exametemp/carregarpacientefisioterapiatemp2/$pacientetemp_id");
        }
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientefisioterapiatemp/$pacientetemp_id");
//        $this->carregarpacientefisioterapiatemp($pacientetemp_id);
    }

    function gravarconsultapacientetempreagendar() {

        $pacientetemp_id = $_POST['txtpaciente_id'];
//        $data['exames'] = $this->exametemp->listaragendatotalpacientefisioterapiareangedar();
        $this->exametemp->gravarconsultapacientetempreagendar($pacientetemp_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacienteconsultatemp/$pacientetemp_id");
//        $this->carregarpacientefisioterapiatemp($pacientetemp_id);
    }

    function gravarexamepacientetempreagendar() {

        $pacientetemp_id = $_POST['txtpaciente_id'];
//        $data['exames'] = $this->exametemp->listaragendatotalpacientefisioterapiareangedar();
        $this->exametemp->gravarexamepacientetempreagendar($pacientetemp_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientetemp/$pacientetemp_id");
//        $this->carregarpacientefisioterapiatemp($pacientetemp_id);
    }

    function gravargeralpacientetempreagendar() {

        $pacientetemp_id = $_POST['txtpaciente_id'];
//        $data['exames'] = $this->exametemp->listaragendatotalpacientefisioterapiareangedar();
        $this->exametemp->gravargeralpacientetempreagendar($pacientetemp_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$pacientetemp_id");
//        $this->carregarpacientefisioterapiatemp($pacientetemp_id);
    }

    function gravarpaciente() {
        if (trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopaciente");
        } else {
            $agenda_exames_id = $_POST['horarios'];
            $paciente_id = $this->exametemp->gravarpacienteexames($agenda_exames_id);
            $return = $this->exametemp->gravarcadastrowhatsapp($agenda_exames_id);
            if ($paciente_id != 0) {
                redirect(base_url() . "ambulatorio/exametemp/carregarpacientetemp/$paciente_id");
            } else {
                $data['mensagem'] = 'Erro ao marcar exame o horario esta oculpado.';
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "ambulatorio/exame/listarmultifuncao");
            }
        }
    }

    function gravarpacienteconsulta() {
        if ((trim($_POST['txtNome']) == "") || (trim($_POST['convenio']) == "0")) {
            $mensagem = 'Erro ao marcar consulta é obrigatorio nome do Paciente e Convenio.';
            $this->session->set_flashdata('message', $mensagem);
            redirect(base_url() . "ambulatorio/exametemp/novopacienteconsulta");
        } else {
            $pacientetemp_id = $this->paciente->gravarpacientetemp();
            $this->exametemp->gravarconsultas($pacientetemp_id);
            redirect(base_url() . "ambulatorio/exametemp/carregarpacienteconsultatemp/$pacientetemp_id");
        }
    }

    function gravarpacientefisioterapia() {
        if (trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacientefisioterapia");
        } else {
            $pacientetemp_id = $this->paciente->gravarpacientetemp();
            $this->exametemp->gravarfisioterapia($pacientetemp_id);
            redirect(base_url() . "ambulatorio/exametemp/carregarpacientefisioterapiatemp/$pacientetemp_id");
        }
    }

    function gravarpacienteconsultaencaixe() {
        if (trim($_POST['procedimento']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio selecionar um procedimento.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacienteconsulta");
        } elseif (trim($_POST['convenio']) == "0") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio selecionar um convenio.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacienteconsulta");
        } elseif (trim($_POST['txtNomeid']) == "" && trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio inserir um Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacienteconsulta");
        } else {
            $pacientetemp_id = $this->exametemp->gravarconsultasencaixe();
            $returnWhats = $this->enviarWhatsappUnitario($pacientetemp_id[1]);
//            enviar email
//            $texto = "Consulta agendada para o dia " . $_POST['data_ficha'] . ", com início às " . $_POST['horarios'] . ".";
//            $email = $this->laudo->email($pacientetemp_id);
//            if (isset($email)) {
//                $this->email($email, $texto);
//            }
//            fim enviar email

            redirect(base_url() . "ambulatorio/exametemp/carregarpacienteconsultatemp/$pacientetemp_id[0]");
        }
    }

    function email($email, $texto) {
        $this->load->library('email');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_user'] = 'equipe2016gcjh@gmail.com';
        $config['smtp_pass'] = 'aramis*123@';
        $config['validate'] = TRUE;
        $config['mailtype'] = 'text';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $this->email->initialize($config);

        $this->email->from('equipe2016gcjh@gmail.com', 'STG Saúde');
        $this->email->to($email);
        $this->email->subject('Consulta Agendada');
        $this->email->message($texto);
        $this->email->send();
//        echo $this->email->print_debugger();
    }

    function gravarpacienteexameencaixe() {
        if (trim($_POST['txtNome']) == "" || $_POST['convenio1'] == "-1") {
            $data['mensagem'] = 'Erro. Obrigatório Convenio e nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
//            redirect(base_url() . "ambulatorio/exametemp/novopaciente");
            redirect(base_url() . "ambulatorio/exametemp/novopacienteexameencaixe");
        } else {
            $pacientetemp_id = $this->exametemp->gravarexameencaixe();
            $returnWhats = $this->enviarWhatsappUnitario($pacientetemp_id[1]);
            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetemp/$pacientetemp_id[0]");
        }
    }

    function gravarpacienteencaixegeral() {
        if (trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacienteencaixegeral");
        } else {
            $pacientetemp_id = $this->exametemp->gravarexameencaixegeral();
            $returnWhats = $this->enviarWhatsappUnitario($pacientetemp_id[1]);
            // var_dump($returnWhats); die;
            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$pacientetemp_id[0]");
        }
    }

    function gravarpacienteencaixegeralpaciente() {
        if (trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacienteencaixegeral");
        } else {
            $pacientetemp_id = $this->exametemp->gravarexameencaixegeralpaciente();
            $returnWhats = $this->enviarWhatsappUnitario($pacientetemp_id[1]);
            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$pacientetemp_id[0]");
        }
    }

    function gravarhorarioexameencaixe() {
        $this->exametemp->gravarhorarioencaixe();
        redirect(base_url() . "ambulatorio/exame/listarmultifuncao");
    }

    function gravarhorarioexameencaixegeral() {
        $this->exametemp->gravarhorarioencaixegeral();
        redirect(base_url() . "ambulatorio/exame/listarmultifuncaogeral");
    }

    function gravarhorarioexameencaixegeral2($agenda_exames_id) {

        $this->exametemp->gravarhorarioencaixegeral2($agenda_exames_id);
        redirect(base_url() . "ambulatorio/exame/listarmultifuncaocalendario2");
    }

    function gravarhorarioexameencaixegeral3($agenda_exames_id) {

        $this->exametemp->gravarhorarioencaixegeral2($agenda_exames_id);
        redirect(base_url() . "ambulatorio/exame/listarmultifuncaocalendario2");
    }

    function gravarpacientefisioterapiaencaixe() {
        if (trim($_POST['txtNomeid']) == "" && trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio inserir um Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacientefisioterapiaencaixe");
        } elseif (trim($_POST['convenio']) == "0") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio inserir o covenio.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacientefisioterapiaencaixe");
        } elseif (trim($_POST['procedimento']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio inserir o procedimento.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacientefisioterapiaencaixe");
        } elseif (trim($_POST['horarios']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta. É obrigatorio inserir o horario.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacientefisioterapiaencaixe");
        } else {
//            $disponibilidade = $this->exametemp->contadorhorariosdisponiveisfisioterapia($_POST['data_ficha'], $_POST['horarios'], $_POST['medico']);
//            if ($disponibilidade == 0) {
//            var_dump($_POST); die;
            $pacientetemp_id = $this->exametemp->gravarfisioterapiaencaixe();
            $returnWhats = $this->enviarWhatsappUnitario($pacientetemp_id[1]);

            redirect(base_url() . "ambulatorio/exametemp/carregarpacientefisioterapiatemp/$pacientetemp_id[0]");
//            } else {
//                $data['mensagem'] = 'Erro ao marcar consulta. Este horário já está agendado.';
//                $this->session->set_flashdata('message', $data['mensagem']);
//                redirect(base_url() . "ambulatorio/exametemp/novopacientefisioterapiaencaixe");
//            }
        }
    }

    function gravarpacientefisioterapiaencaixemedico() {
        if (trim($_POST['txtNomeid']) == "" && trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio inserir um Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacientefisioterapiaencaixe");
        } elseif (trim($_POST['convenio']) == "0") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio inserir o covenio.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacientefisioterapiaencaixe");
        } elseif (trim($_POST['procedimento']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio inserir o procedimento.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacientefisioterapiaencaixe");
        } elseif (trim($_POST['horarios']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta. É obrigatorio inserir o horario.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacientefisioterapiaencaixe");
        } else {
//            $disponibilidade = $this->exametemp->contadorhorariosdisponiveisfisioterapia($_POST['data_ficha'], $_POST['horarios'], $_POST['medico']);
//            if ($disponibilidade == 0) {
            $pacientetemp_id = $this->exametemp->gravarfisioterapiaencaixe();

            redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
//            } else {
//                $data['mensagem'] = 'Erro ao marcar consulta. Este horário já está agendado.';
//                $this->session->set_flashdata('message', $data['mensagem']);
//                redirect(base_url() . "ambulatorio/exametemp/novopacientefisioterapiaencaixe");
//            }
        }
    }

    function gravapacienteconsultaencaixe() {
        $pacientetemp_id = $_POST['txtpaciente_id'];
        $retorno = $this->exametemp->gravaconsultasencaixe($pacientetemp_id);
        $returnWhats = $this->enviarWhatsappUnitario($retorno[1]);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacienteconsultatemp/$pacientetemp_id");
    }

    private
            function carregarView($data = null, $view = null) {
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

    function carregarpacientetempgeralsadt($pacientetemp_id, $solicitacao_sadt_id, $solicitacao_sadt_procedimento_id) {
        $obj_paciente = new paciente_model($pacientetemp_id);
        $data['obj'] = $obj_paciente;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['exames'] = $this->exametemp->listaragendatotalpacientegeral($pacientetemp_id);
        $data['consultasanteriores'] = $this->exametemp->listarprocedimentosanterior($pacientetemp_id);
        $data['consultaanteriorcontador'] = $this->exametemp->listarprocedimentoanteriorcontador($pacientetemp_id);
        $data['procedimentosadt'] = $this->exametemp->listarprocedimentosadt($solicitacao_sadt_procedimento_id);
        $data['convenios'] = $this->exametemp->listarautocompletemedicoconvenio();
        $data['solicitacao_sadt_id'] = $solicitacao_sadt_id;
        $data['solicitacao_sadt_procedimento_id'] = $solicitacao_sadt_procedimento_id;
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/agendarprocedimentosadt-form', $data);
    }

    function gravarpacientetempgeralsadt() {

        $pacientetemp_id = $_POST['txtpaciente_id'];
        $solicitacao_sadt_id = $_POST['solicitacao_sadt_id'];
        $solicitacao_sadt_procedimento_id = $_POST['solicitacao_sadt_procedimento_id'];

        if ($_POST['data_ficha'] == '' || $_POST['exame'] == '' || $_POST['convenio1'] == '' || $_POST['procedimento1'] == '' || $_POST['horarios'] == '') {
            $data['mensagem'] = 'Insira os campos obrigatorios.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeralsadt/$pacientetemp_id/$solicitacao_sadt_id/$solicitacao_sadt_procedimento_id");
        }
//        elseif ($_POST['txtNome'] == '') {
//            $data['mensagem'] = 'Paciente não informado ou inválido.';
//            $this->session->set_flashdata('message', $data['mensagem']);
//            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$pacientetemp_id");
//        } 
        else {
            $this->exametemp->gravarpacienteexistentegeralsadt($pacientetemp_id);

            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeralsadt/$pacientetemp_id/$solicitacao_sadt_id/$solicitacao_sadt_procedimento_id");
        }
    }

    function excluirconsultatempagendamentosadt($agenda_exames_id, $paciente_id, $tipo, $encaixe) {
        // var_dump($encaixe); die;
        $this->exametemp->salvaragendamentoexcluidomotivo($agenda_exames_id, $tipo, $encaixe);
        $this->exametemp->excluirexametempagendamento($agenda_exames_id, $encaixe);
        $solicitacao_sadt_id = $_POST['solicitacao_sadt_id'];
        $solicitacao_sadt_procedimento_id = $_POST['solicitacao_sadt_procedimento_id'];
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeralsadt/$paciente_id/$solicitacao_sadt_id/$solicitacao_sadt_procedimento_id");

//        if ($tipo == 'GERAL') {
//            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$paciente_id");
//        } elseif ($tipo == 'CONSULTA') {
//            redirect(base_url() . "ambulatorio/exametemp/carregarpacienteconsultatemp/$paciente_id");
//        } elseif ($tipo == 'EXAME') {
//            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetemp/$paciente_id");
//        } else {
//            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeral/$paciente_id");
//        }
    }

    function reservartempgeralsadt($agenda_exames_id, $paciente_id, $medico_id, $data_escolhida, $solicitacao_sadt_id, $solicitacao_sadt_procedimento_id) {
        $empresaPermissao = $this->guia->listarempresapermissoes();
        // var_dump($empresaPermissao); die;
        if ($empresaPermissao[0]->reservar_escolher_proc == 't') {
            $data['agenda_exames_id'] = $agenda_exames_id;
            $data['paciente_id'] = $paciente_id;
            $data['medico_id'] = $medico_id;
            // $data['data'] = $data;

            $obj_paciente = new paciente_model($paciente_id);
            $data['obj'] = $obj_paciente;
            $data['medico'] = $this->exametemp->listarmedicoconsulta();
            $data['convenio'] = $this->procedimentoplano->listarconvenio();
            $data['contador'] = $this->exametemp->contadorfisioterapiapaciente($paciente_id);
            $data['exames'] = $this->exametemp->listaragendasexamepacientereservar($agenda_exames_id);
            $data['grupos'] = $this->procedimento->listargrupos();
            // var_dump($data['exames']); die;
            $this->loadview('ambulatorio/reservarescolherprocedimentogeral', $data);
        } else {
            $paciente_id = $this->exametemp->reservarexametemp($agenda_exames_id, $paciente_id, $agenda_exames_nome_id, $data_escolhida);
            redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeralsadt/$paciente_id/$solicitacao_sadt_id/$solicitacao_sadt_procedimento_id");
        }
    }

    function reangedargeraltempsadt($agenda_exames_id, $pacientetemp_id, $medico_consulta_id, $solicitacao_sadt_id, $solicitacao_sadt_procedimento_id) {
//        if (isset($faltou)) {
//            $data['faltou'] = $faltou;
//        }
        $data['pacientetemp_id'] = $pacientetemp_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['medico_consulta_id'] = $medico_consulta_id;
        $data['solicitacao_sadt_id'] = $solicitacao_sadt_id;
        $data['solicitacao_sadt_procedimento_id'] = $solicitacao_sadt_procedimento_id;

        $obj_paciente = new paciente_model($pacientetemp_id);
        $data['obj'] = $obj_paciente;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['contador'] = $this->exametemp->contadorfisioterapiapaciente($pacientetemp_id);
        $data['exames'] = $this->exametemp->listaragendatotalpacientefisioterapiareangedar($agenda_exames_id);
        $data['consultasanteriores'] = $this->exametemp->listarespecialidadeanterior($pacientetemp_id);
        $data['consultaanteriorcontador'] = $this->exametemp->listarconsultaanteriorcontador($pacientetemp_id);
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/reagendargeralpacientetempsadt-form', $data);
    }

    function gravargeralpacientetempreagendarsadt() {

        $pacientetemp_id = $_POST['txtpaciente_id'];
        $solicitacao_sadt_id = $_POST['solicitacao_sadt_id'];
        $solicitacao_sadt_procedimento_id = $_POST['solicitacao_sadt_procedimento_id'];
//        $data['exames'] = $this->exametemp->listaragendatotalpacientefisioterapiareangedar();
        $this->exametemp->gravargeralpacientetempreagendar($pacientetemp_id);
        redirect(base_url() . "ambulatorio/exametemp/carregarpacientetempgeralsadt/$pacientetemp_id/$solicitacao_sadt_id/$solicitacao_sadt_procedimento_id");
//        $this->carregarpacientefisioterapiatemp($pacientetemp_id);
    }

    function listartarefas($paciente_id) {
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['paciente_id'] = $paciente_id;
        $data['permissoes'] = $this->guia->listarempresapermissoes();
        $data['valortotal'] = $this->exametemp->listarsaldocreditopaciente($paciente_id);
        $this->loadView('ambulatorio/carregartarefas-lista', $data);
    }

    function carregartarefa($paciente_id) {
        $data['paciente_id'] = $paciente_id;
        $data['convenio'] = $this->convenio->listardados();
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['permissoes'] = $this->guia->listarempresapermissoes();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $this->loadView('ambulatorio/novatarefa-form', $data);
    }

    function gravartarefa() {
        $paciente_id = $_POST['txtpaciente_id'];
        $this->load->helper('directory');
        $credito_id = $this->exametemp->gravartarefa();
        redirect(base_url() . "ambulatorio/exametemp/listartarefas/$paciente_id");
    }

    function visualizartarefarecep($tarefa_medico_id) {
        $this->load->helper('directory');
        $data['paciente'] = $this->exametemp->listardadostarefa($tarefa_medico_id);
        $this->load->View('ambulatorio/carregardadostarefa', $data);
    }

    function excluirtarefa($tarefa_medico_id, $paciente_id) {

        if ($this->exametemp->excluirtarefa($tarefa_medico_id) != '-1') {
            $data['mensagem'] = 'Sucesso ao excluir tarefa.';
        } else {
            $data['mensagem'] = 'Erro ao excluir tarefa.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);

//        redirect(base_url() . "ambulatorio/exametemp/listartarefas/$paciente_id");
          redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function listartarefasmedico($args = array()) {
        $this->loadView('ambulatorio/tarefamedico-lista', $args);
    }

    function visualizartarefamedic($tarefa_medico_id, $paciente_id = NULL) {
        $this->exametemp->atualizarstatus($tarefa_medico_id);
        $data['listapaciente'] = $this->exametemp->listardadostarefapaciente($paciente_id);
        $data['listatarefa'] = $this->exametemp->listardadostarefa($tarefa_medico_id);
        $data['lista'] = $this->exametemp->listarmodelolaudotarefa($data['listatarefa'][0]->medico_id);
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['operadores'] = $this->operador_m->listarmedicos();
        $this->load->View('ambulatorio/tarefa-form', $data);
    }

    function gravarlaudotarefa() {
        $this->exametemp->gravarlaudotarefa();

        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function impressaotarefa($tarefa_medico_id) {
//        $this->exametemp->gravarlaudotarefa($tarefa_medico_id);
        sleep(1);

        $data['tarefa_medico_id'] = $tarefa_medico_id;

        $data['lista'] = $this->exametemp->listardadostarefa($tarefa_medico_id);


        $this->load->plugin('mpdf');
        $empresa_id = $this->session->userdata('empresa_id');
//        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['laudo'] = $this->exametemp->listardadostarefa($tarefa_medico_id);
 
        // var_dump($data['laudo'][0]->template_obj); die;
//        if($data['laudo'][0]->template_obj != ''){
//            $data['laudo'][0]->texto = $this->templateParaTexto($data['laudo'][0]->template_obj);
//        }
        $texto = $data['laudo'][0]->laudo;
//        $adendo = $data['laudo'][0]->adendo;
        $data['laudo'][0]->laudo = $texto;
        $data['laudo'][0]->laudo = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->laudo);
        $data['laudo'][0]->laudo = str_replace("<head>", '', $data['laudo'][0]->laudo);
        $data['laudo'][0]->laudo = str_replace("</head>", '', $data['laudo'][0]->laudo);
        $data['laudo'][0]->laudo = str_replace("<html>", '', $data['laudo'][0]->laudo);
        $data['laudo'][0]->laudo = str_replace("<body>", '', $data['laudo'][0]->laudo);
        $data['laudo'][0]->laudo = str_replace("</html>", '', $data['laudo'][0]->laudo);
        $data['laudo'][0]->laudo = str_replace("</body>", '', $data['laudo'][0]->laudo);
        $data['laudo'][0]->laudo = str_replace('align="center"', '', $data['laudo'][0]->laudo);
        $data['laudo'][0]->laudo = str_replace('align="left"', '', $data['laudo'][0]->laudo);
        $data['laudo'][0]->laudo = str_replace('align="right"', '', $data['laudo'][0]->laudo);
        $data['laudo'][0]->laudo = str_replace('align="justify"', '', $data['laudo'][0]->laudo);
        // var_dump($data['laudo'][0]->laudo); die;
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_id);
//        var_dump($data['cabecalhomedico']); die;
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
        // $sem_margins = 't';
        @$data['exame_id'] = @$exame_id;
        @$data['ambulatorio_laudo_id'] = @$ambulatorio_laudo_id;
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');



        //////////////////////////////////////////////////////////////////////////////////////////////////
        //LAUDO CONFIGURÁVEL
        if ($data['empresa'][0]->laudo_config == 't') {
//            die('morreu');
            $filename = "laudo.pdf";
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
            } else {
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_id . ".jpg")) { // Logo do Profissional
                    $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_id . '.jpg"/>';
                } else {
                    if ($data['impressaolaudo'][0]->cabecalho == 't') {
                        if ($data['empresa'][0]->cabecalho_config == 't') { // Cabeçalho Da clinica
                            $cabecalho = "$cabecalho_config";
                        } else {
                            $cabecalho = "<table><tr><td><img width='1000px' height='180px' src='img/cabecalho.jpg'></td></tr></table>";
                        }
                    } else {
                        $cabecalho = '';
                    }
                }
            }

            $cabecalho = str_replace("_paciente_", $data['laudo'][0]->paciente, $cabecalho);
            $cabecalho = str_replace("_sexo_", $data['laudo'][0]->sexo, $cabecalho);
            $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($data['laudo'][0]->nascimento)), $cabecalho);
            $cabecalho = str_replace("_convenio_", $data['laudo'][0]->convenio, $cabecalho);
//            $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
            $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
            $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
//            $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
            $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
            $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico_responsavel, $cabecalho);
            $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
//            $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
//            $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
//            $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
//            $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
//            $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
            $operador_id = $this->session->userdata('operador_id');
            $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
            @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
//            @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
            $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
            $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
            $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
            $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);

            $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
            $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']);



            if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_id . ".jpg")) {
                $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_id . ".jpg'>";
                $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_id . ".jpg'>";
            } else {
                $assinatura = "";
                $data['assinatura'] = "";
            }

            if ($data['cabecalhomedico'][0]->rodape != '') { // Rodapé do profissional
                $rodape_config = $data['cabecalhomedico'][0]->rodape;
                $rodape_config = str_replace("_assinatura_", $assinatura, $rodape_config);
                $rodape = $rodape_config;
            } else {
                if ($data['impressaolaudo'][0]->rodape == 't') { // rodape da empresa
                    if ($data['empresa'][0]->rodape_config == 't') {
                        $rodape_config = str_replace("_assinatura_", $assinatura, $rodape_config);

                        $rodape = $rodape_config;
                    } else {
                        $rodape = "";
                    }
                } else {
                    $rodape = "";
                }
            }



            $html = $this->load->view('ambulatorio/impressaolaudoconfiguravel', $data, true);
            // echo '<pre>';
            // echo $html;
            // die;

            if ($data['empresa'][0]->impressao_tipo == 33) {
                // ossi rezaf rop adiv ahnim oiedo uE
                // Isso é pra quando for da vale-imagem, o menor tamanho ficar absurdamente pequeno
                // açneod ?euq roP
                $html = str_replace('xx-small', '5pt', $html);
            }

            $teste_cabecalho = "$cabecalho";

            // $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            // $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
            } else {
                pdf($html, $filename, $cabecalho, $rodape);
            }
        } else { // CASO O LAUDO NÃO CONFIGURÁVEL
            //////////////////////////////////////////////////////////////////////////////////////////////////
            if ($data['empresa'][0]->impressao_laudo == 1) {//HUMANA IMAGEM
                $filename = "laudo.pdf";
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    //                $cabecalho = $cabecalho_config; 
//                    if ($data['empresapermissoes'][0]->alterar_data_emissao == 't') {
//                        $cabecalho = "<table><tr><td>$cabecalho_config</td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//                    } else {
                    $cabecalho = "<table><tr><td>$cabecalho_config</td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->medico_responsavel . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data, 8, 2) . '/' . substr($data['laudo']['0']->data, 5, 2) . '/' . substr($data['laudo']['0']->data, 0, 4) . "</td></tr></table>";
//                    }
                } else {
//                    if ($data['empresapermissoes'][0]->alterar_data_emissao == 't') {
                    $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->medico_responsavel . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//                    } else {
//                        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_agenda_exames, 8, 2) . '/' . substr($data['laudo']['0']->data_agenda_exames, 5, 2) . '/' . substr($data['laudo']['0']->data_agenda_exames, 0, 4) . "</td></tr></table>";
//                    }
                }

                if ($data['empresa'][0]->rodape_config == 't') {
                    $rodape = $rodape_config;
                } else {
                    $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
                }
                $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
                pdf($html, $filename, $cabecalho, $rodape);
                $this->load->View('ambulatorio/impressaolaudo_1', $data);
            }


            if ($data['empresa'][0]->impressao_laudo == 33) { // ValeImagem
                $filename = "laudo.pdf";
                if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                } else {
                    if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_id . ".jpg")) { // Logo do Profissional
                        $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_id . '.jpg"/>';
                    } else {
                        if ($data['impressaolaudo'][0]->cabecalho == 't') {
                            if ($data['empresa'][0]->cabecalho_config == 't') { // Cabeçalho Da clinica
                                $cabecalho = "$cabecalho_config";
                            } else {
                                $cabecalho = "<table><tr><td><img width='1000px' height='180px' src='img/cabecalho.jpg'></td></tr></table>";
                            }
                        } else {
                            $cabecalho = '';
                        }
                    }
                }
                //            if ($data['impressaolaudo'][0]->cabecalho == 't') {
                //                if ($data['empresa'][0]->cabecalho_config == 't') {
                //                    if ($data['cabecalhomedico'][0]->cabecalho != '') {
                //                        $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                //                    } else {
                //                        $cabecalho = "$cabecalho_config";
                //                    }
                //                } else {
                //                    $cabecalho = "<table><tr><td><img width='1000px' height='180px' src='img/cabecalho.jpg'></td></tr></table>";
                //                }
                //            } else {
                //                $cabecalho = '';
                //            }
                $cabecalho = str_replace("_paciente_", $data['laudo'][0]->paciente, $cabecalho);
                $cabecalho = str_replace("_sexo_", $data['laudo'][0]->sexo, $cabecalho);
                $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($data['laudo'][0]->nascimento)), $cabecalho);
                $cabecalho = str_replace("_convenio_", $data['laudo'][0]->convenio, $cabecalho);
//                $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
                $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
//                $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
                $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
                $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico_responsavel, $cabecalho);
                $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
//                $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
//                $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
//                $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
//                $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
//                $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
                $operador_id = $this->session->userdata('operador_id');
                $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
                @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
//                @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
                $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
                $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
                $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
                $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
                $cabecalho = "<table style='width:100%'>
                                <tr>
                                    <td style='width:100%; text-align:center;'>
                                        $cabecalho
                                    </td>
                                </tr>
                             ";

                $cabecalho = $cabecalho . "
                <tr>
                    <td>
                        {$data['impressaolaudo'][0]->adicional_cabecalho}
                    </td>
                </tr>

                </table>";


                $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']);



                if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_id . ".jpg")) {
                    $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_id . ".jpg'>";
                    $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_id . ".jpg'>";
                } else {
                    $assinatura = "";
                    $data['assinatura'] = "";
                }

                if ($data['cabecalhomedico'][0]->rodape != '') { // Rodapé do profissional
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                    $rodape_config = str_replace("_assinatura_", $assinatura, $rodape_config);
                    $rodape = $rodape_config;
                } else {
                    if ($data['impressaolaudo'][0]->rodape == 't') { // rodape da empresa
                        if ($data['empresa'][0]->rodape_config == 't') {
                            //                        if($data['laudo']['0']->situacao == "FINALIZADO"){
                            $rodape_config = str_replace("_assinatura_", $assinatura, $rodape_config);
                            //                        }else{
                            //                            $rodape_config = str_replace("_assinatura_", '', $rodape_config);
                            //                        }

                            $rodape = $rodape_config;
                        } else {
                            $rodape = "";
                        }
                    } else {
                        $rodape = "";
                    }
                }



                $html = $this->load->view('ambulatorio/impressaolaudoconfiguravel', $data, true);
                //    echo '<pre>';
                //    echo $cabecalho;

                if ($data['empresa'][0]->impressao_tipo == 33) {
                    // ossi rezaf rop adiv ahnim oiedo uE
                    // Isso é pra quando for da vale-imagem, o menor tamanho ficar absurdamente pequeno
                    // açneod ?euq roP
                    $html = str_replace('xx-small', '5pt', $html);
                }

                //    $teste_cabecalho = "<table><tr><td>$cabecalho</td><tr></table>";
                //    var_dump($html); 
                //    var_dump($html); 
                //            $margin = "";
                //    echo $cabecalho; 
                //    echo $html; 
                //    echo $rodape; 
                //    die;
                //            $cabecalho = '';
                //            $rodape = '';
                $rodape_t = "<table style='width:100%'>
                            <tr>
                                <td style='width:100%; text-align:center;'>
                                    $rodape
                                </td>
                            </tr>
                            </table>
                        ";
                pdf($html, $filename, $cabecalho, $rodape_t, '', 0);
            }

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////
            elseif ($data['empresa'][0]->impressao_laudo == 10) {//CLINICA MED
                $filename = "laudo.pdf";
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $cabecalho = "<table width=100% border=1><tr> <td>$cabecalho_config</td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->medico_responsavel . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                } else {
                    $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='180px' src='img/cabecalho.jpg'></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->medico_responsavel . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                }

                if ($data['empresa'][0]->rodape_config == 't') {
                    $rodape = $rodape_config;
                } else {
                    $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
                }


                $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
                pdf($html, $filename, $cabecalho, $rodape);
                $this->load->View('ambulatorio/impressaolaudo_1', $data);
            }

            // //////////////////////////////////////////////////////////////////////////////////////////////////////////////       
            elseif ($data['empresa'][0]->impressao_laudo == 11) {//CLINICA MAIS
                $filename = "laudo.pdf";
                //            var_dump( $data['laudo']['0']->carimbo); die;
                $cabecalho = $cabecalho_config;
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $cabecalho = "<table><tr><td>$cabecalho_config</td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
                } else {
                    $cabecalho = "<table><tr><td><img align = 'left'  width='300px' height='90px' src='img/logomais.png'></td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br>EXAME: " . $data['laudo']['0']->cabecalho . "<br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
                }

                if ($data['laudo']['0']->status == "ATENDENDO") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt; text-align:center;'><tr><td>" . $data['laudo']['0']->carimbo . "</td></tr>
                <tr><td><center></td></tr></table><img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
                } elseif ($data['laudo']['0']->status == "FINALIZADO") {
                    //                echo $data['laudo']['0']->carimbo;
                    if ($data['empresa'][0]->rodape_config == 't') {
                        //                $cabecalho = $cabecalho_config;
                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_responsavel . ".jpg'></td></tr></table>$rodape_config<br><br><br>";
                    } else {
                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_responsavel . ".jpg'></td></tr></table><img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'><br><br><br>";
                    }
                }
                $html = $this->load->view('ambulatorio/impressaolaudo_1pacajus', $data, true);
                pdf($html, $filename, $cabecalho, $rodape);
                $this->load->View('ambulatorio/impressaolaudo_1pacajus', $data);
            }

            ////////////////////////////////////////////////////////////////////////////////////////////
            elseif ($data['empresa'][0]->impressao_laudo == 6) {//CLINICA DEZ
                $filename = "laudo.pdf";
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $cabecalho = "<table><tr><td>$cabecalho_config</td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
                } else {
                    $cabecalho = "<table><tr><td><img align = 'left'  width='300px' height='90px' src='img/logomais.png'></td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
                }
                //            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='90px' src='img/clinicadez.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                if ($data['empresa'][0]->rodape_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_responsavel . ".jpg'></td></tr></table><table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
                } else {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_responsavel . ".jpg'></td></tr></table><table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
                }
                $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
                $grupo = 'laboratorial';
                pdf($html, $filename, $cabecalho, $rodape, $grupo);
                $this->load->View('ambulatorio/impressaolaudo_1', $data);
            }

            //   /////////////////////////////////////////////////////////////////////////////////////////////     
            elseif ($data['empresa'][0]->impressao_laudo == 2) {//CLINICA PROIMAGEM
                $filename = "laudo.pdf";
                $cabecalho = "<table>
    <tr>
      <td width='30px'></td><td><img align = 'left'  width='330px' height='100px' src='img/clinicadez.jpg'></td>
    </tr>
    <td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
    <tr>
    <td width='30px'></td><td>" . substr($data['laudo']['0']->sala, 0, 10) . "</td>
    </tr>
    <tr>
    <td width='30px'></td><td width='400px'>Reg.:" . $data['laudo']['0']->paciente_id . "</td><td>Emiss&atilde;o: " . substr($data['laudo']['0']->data, 8, 2) . '/' . substr($data['laudo']['0']->data, 5, 2) . '/' . substr($data['laudo']['0']->data, 0, 4) . "</td>
    </tr>
    <tr>
      <td width='30px'></td><td >Paciente:" . $data['laudo']['0']->paciente . "</td><td>Idade:" . $teste . "</td>
    </tr>
    <tr>
    <td width='30px'></td><td>Solicitante: Dr(a). " . $data['laudo']['0']->medico_responsavel . "</td><td>Sexo:" . $data['laudo']['0']->sexo . "</td>
    </tr>
    </tr>
    </tr><tr><td>&nbsp;</td></tr>
    <tr>
    </table>";
                $rodape = "";
                if ($data['laudo']['0']->status == "FINALIZADO") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td width='400px'></td><td><img align = 'Right'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_id . ".jpg'></td></tr></tr><tr><td>&nbsp;</td></tr></table>";
                }
//                elseif ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer2 != "") {
////                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td width='30px'></td><td><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_id . ".jpg'></td><td width='30px'></td><td><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_id . ".jpg'></td></tr></tr><tr><td>&nbsp;</td></tr></table>";
//                }
                $grupo = 'laboratorial';
                $html = $this->load->view('ambulatorio/impressaolaudo_5', $data, true);
                pdf($html, $filename, $cabecalho, $rodape, $grupo);
                $this->load->View('ambulatorio/impressaolaudo_5', $data);
            }

            /////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($data['empresa'][0]->impressao_laudo == 12) {//PRONTOMEDICA
                $filename = "laudo.pdf";
                $cabecalho = "<table>
    <tr>
      <td width='30px'></td><td><img align = 'left'  width='330px' height='100px' src='img/clinicadez.jpg'></td>
    </tr>
    <tr>
    <td width='30px'></td><td width='400px'>Numero do exame: " . $ambulatorio_laudo_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
    </tr>
    <tr>
      <td width='30px'></td><td >Paciente: " . strtoupper($data['laudo']['0']->paciente) . "</td><td>Idade: " . $teste . "</td>
    </tr>
    <tr>
    <td width='30px'></td><td>Solicitante: Dr(a). " . strtoupper($data['laudo']['0']->medico_responsavel) . "</td><td>Data de Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td>
    </tr>
    <tr>
    <td width='30px'></td><td>Covenio: " . $data['laudo']['0']->convenio . "</td>
    </tr>
    </tr>
    </tr><tr><td>&nbsp;</td></tr>
    <tr>
    </table>";
                $rodape = "";
                if ($data['laudo']['0']->status == "FINALIZADO") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_id . ".jpg'></td></tr></table>";
                } else {
                    if ($data['laudo']['0']->medico_id == 929) {

                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico_responsavel . "</td></tr>
                <tr><td><center>Ultrassonografista</td></tr>
                <tr><td><center>CRM" . $data['laudo']['0']->conselho . "/CBR01701</td></tr></table>";
                    } else {
                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico_responsavel . "</td></tr>
                <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                    }
                }
                $grupo = 'laboratorial';
                $html = $this->load->view('ambulatorio/impressaolaudo_8', $data, true);
                pdf($html, $filename, $cabecalho, $rodape, $grupo);
                $this->load->View('ambulatorio/impressaolaudo_8', $data);
            }
            //////////////////////////////////////////////////////////////////////////////////////////////
            if ($data['empresa'][0]->impressao_laudo == 19) {//OLÁ CLINICA
                $filename = "laudo.pdf";
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $cabecalho = "<table>
    <tr>
      <td width='30px'></td><td>$cabecalho_config</td>
    </tr>
    <tr>
    <td width='30px'></td><td width='400px'>Numero do exame: " . $ambulatorio_laudo_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
    </tr>
    <tr>
      <td width='30px'></td><td >Paciente: " . strtoupper($data['laudo']['0']->paciente) . "</td><td>Idade: " . $teste . "</td>
    </tr>
    <tr>
    <td width='30px'></td><td>Solicitante: Dr(a). " . strtoupper($data['laudo']['0']->medico_responsavel) . "</td><td>Data de Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td>
    </tr>
    <tr>
    <td width='30px'></td><td>Covenio: " . $data['laudo']['0']->convenio . "</td>
    </tr>
    </tr>
    </tr><tr><td>&nbsp;</td></tr>
    <tr>
    </table>";
                } else {
                    $cabecalho = "<table>
    <tr>
      <td width='30px'></td><td><img align = 'left'  width='330px' height='100px' src='img/cabecalho.jpg'></td>
    </tr>
    <tr>
    <td width='30px'></td><td width='400px'>Numero do exame: " . $ambulatorio_laudo_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
    </tr>
    <tr>
      <td width='30px'></td><td >Paciente: " . strtoupper($data['laudo']['0']->paciente) . "</td><td>Idade: " . $teste . "</td>
    </tr>
    <tr>
    <td width='30px'></td><td>Solicitante: Dr(a). " . strtoupper($data['laudo']['0']->medico_responsavel) . "</td><td>Data de Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td>
    </tr>
    <tr>
    <td width='30px'></td><td>Covenio: " . $data['laudo']['0']->convenio . "</td>
    </tr>
    </tr>
    </tr><tr><td>&nbsp;</td></tr>
    <tr>
    </table>";
                }

                $rodape = "";

                if ($data['laudo']['0']->status == "FINALIZADO") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_id . ".jpg'></td></tr></table>";
                } else {
                    if ($data['laudo']['0']->medico_id == 929) {
                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico_responsavel . "</td></tr>
                <tr><td><center>Ultrassonografista</td></tr>
                <tr><td><center>CRM" . $data['laudo']['0']->conselho . "/CBR01701</td></tr></table>";
                    } else {
                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico_responsavel . "</td></tr>
                <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                    }
                }
                if ($data['empresa'][0]->rodape_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $rodape = $rodape . '<br>' . $rodape_config;
                } else {
                    $rodape = $rodape . '<br>' . "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='330px' height='100px' src='img/rodape.jpg'></td></tr></table>";
                }
                $grupo = 'laboratorial';
                $html = $this->load->view('ambulatorio/impressaolaudo_8', $data, true);
                pdf($html, $filename, $cabecalho, $rodape, $grupo);
                $this->load->View('ambulatorio/impressaolaudo_8', $data);
            }

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_laudo == 15) {//INSTITUTO VASCULAR
                $filename = "laudo.pdf";
                $cabecalho = "<table>
    <tr>
      <td width='300px'></td><td width='180px'></td><td><img align = 'right'  width='180px' height='90px' src='img/clinicadez.jpg'></td>
    </tr>

    <tr>
      <td >PACIENTE: " . $data['laudo']['0']->paciente . "</td><td>IDADE: " . $teste . "</td>
    </tr>
    <tr>
    <td>COVENIO: " . $data['laudo']['0']->convenio . "</td><td>NASCIMENTO: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td>
    </tr>
    <tr>
    <td>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
    </tr>

    </tr>
    </tr><tr><td>&nbsp;</td></tr>
    <tr>
    </table>";
                $rodape = "";
                if ($data['laudo']['0']->status == "FINALIZADO") {
                    $rodape = "<table  width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr>"
                            . "<tr><td><img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'></td></tr>"
                            . "</table> ";
                } else {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico_responsavel . "</td></tr>
                <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr>"
                            . "<tr><td><img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'></td></tr>"
                            . "</table> ";
                }
                $grupo = 'laboratorial';

                $html = $this->load->view('ambulatorio/impressaolaudo_5', $data, true);

                pdf($html, $filename, $cabecalho, $rodape, $grupo);
                $this->load->View('ambulatorio/impressaolaudo_5', $data);
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_laudo == 13) {// CLINICA CAGE
                if ($data['laudo']['0']->sexo == "F") {
                    $SEXO = 'FEMININO';
                } elseif ($data['laudo']['0']->sexo == "M") {
                    $SEXO = 'MASCULINO';
                } else {
                    $SEXO = 'OUTROS';
                }

                $filename = "laudo.pdf";
                $cabecalho = "<table>
            <tr>
              <td><img align = 'left'  width='330px' height='100px' src='img/cage.jpg'></td>
            </tr>
            <tr><td></td></tr>

            <tr><td>&nbsp;</td></tr>
            <tr>
            <td width='430px'>Nome.:" . $data['laudo']['0']->paciente . "</td><td>Idade:" . substr($teste, 0, 2) . "</td>
            </tr>
            <tr>
              <td >Sexo:" . $SEXO . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
            </tr>
            <tr>
            <td>Solicitante: Dr(a). " . $data['laudo']['0']->medico_responsavel . "</td><td></td>
            </tr>

            <tr>
            <td colspan='2'><b></b></td>
            </tr>
            </table>";
                $rodape = "";

                $grupo = 'laboratorial';
                $html = $this->load->view('ambulatorio/impressaolaudo_6', $data, true);
                pdf($html, $filename, $cabecalho, $rodape, $grupo);
                $this->load->View('ambulatorio/impressaolaudo_6', $data);
            }

            ///////////////////////////////////////////////////////////////////////////////////////////
            elseif ($data['empresa'][0]->impressao_laudo == 8) {//RONALDO BARREIRA
                $medicoparecer = $data['laudo']['0']->medico_id;
                //            echo "<pre>"; var_dump($data['laudo']['0']);die;
                $cabecalho = "<table><tr><td><center><img align = 'left'  width='1000px' height='90px' src='img/cabecalho.jpg'></center></td></tr>

                        <tr><td >Exame de: " . $data['laudo']['0']->paciente . "</td>----<td >RG : " . $data['laudo']['0']->rg . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "----Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->tarefa_medico_id . "----Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "----Solicitante: " . $data['laudo']['0']->medico_responsavel . "</td></tr>
                        </table>";
                if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                    $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>           
                        <tr><td >Exame de: " . $data['laudo']['0']->paciente . "</td>----<td >RG : " . $data['laudo']['0']->rg . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->tarefa_medico_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->medico_responsavel, 0, 15) . "</td></tr>
                        </table>";
                }
                if ($data['laudo']['0']->medico_id != 929) {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico_responsavel . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                }
                if ($data['laudo']['0']->medico_id == 929 && $data['laudo']['0']->status != "FINALIZADO") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico_responsavel . "</td></tr>
                        <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                }
                if ($data['laudo']['0']->status == "FINALIZADO" && $data['laudo']['0']->medico_id == 929) {
                    $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  width='180px' height='65px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
                }
                if ($data['laudo']['0']->status == "FINALIZADO" && $data['laudo']['0']->medico_id == 930) {
                    $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <img  width='180px' height='65px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
                }
                if ($data['laudo']['0']->status == "FINALIZADO" && $data['laudo']['0']->medico_id == 2483) {
                    $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <img  width='180px' height='65px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
                }
                $grupo = "";
                $filename = "laudo.pdf";
                $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
                pdf($html, $filename, $cabecalho, $rodape, $grupo, 9, $data['empresa'][0]->impressao_laudo);
                $this->load->View('ambulatorio/impressaolaudo_2', $data);
            }
            ///////////////////////////////////////////////////////////////////////////////////////////
            elseif ($data['empresa'][0]->impressao_laudo == 9) {//RONALDO BARREIRA FILIAL
                $medicoparecer = $data['laudo']['0']->medico_id;
                //            echo "<pre>"; var_dump($data['laudo']['0']);die;
                $cabecalho = "<table><tr><td><center><img align = 'left'  width='1000px' height='90px' src='img/cabecalho.jpg'></center></td></tr>

                        <tr><td colspan='2'>Exame de: " . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "----Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->tarefa_medico_id . "----Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "----Solicitante: " . $data['laudo']['0']->medico_responsavel . "<br></td></tr>
                        </table>";
                if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                    $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>           
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->tarefa_medico_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->medico_responsavel, 0, 15) . "<br></td></tr>
                        </table>";
                }

                if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_id . ".jpg")) {
                    $assinatura = "<img   width='200px' height='100px' src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_id . ".jpg'>";
                    $data['assinatura'] = "<img   width='200px' height='100px' src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_id . ".jpg'>";
                } else {
                    $assinatura = "";
                    $data['assinatura'] = "";
                }

                if ($data['cabecalhomedico'][0]->rodape != '' && $data['laudo']['0']->status == "FINALIZADO") {
                    $rodape = $data['cabecalhomedico'][0]->rodape;
                    $rodape = str_replace("_assinatura_", $assinatura, $rodape);
                } else {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico_responsavel . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                }


                $grupo = "";
                $filename = "laudo.pdf";
                $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
                pdf($html, $filename, $cabecalho, $rodape, $grupo);
                $this->load->View('ambulatorio/impressaolaudo_2', $data);
            }
            //////////////////////////////////////////////////////////////////////////////       
            else {//GERAL       //este item fica sempre por último
                $filename = "laudo.pdf";
                if ($data['cabecalhomedico'][0]->cabecalho != '') {
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho . "<table><tr><td></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->medico_responsavel . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                } else {
                    if ($data['empresa'][0]->cabecalho_config == 't') {
                        $cabecalho = "$cabecalho_config<table><tr><td></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->medico_responsavel . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                    } else {
                        if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_id . ".jpg")) {
                            $img = '<img style="width: 100%; height: 40%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_id . '.jpg"/>';
                        } else {
                            $img = "<img align = 'left'style='width: 100%; height: 40%;'  src='img/cabecalho.jpg'>";
                        }
                        $cabecalho = "<table><tr><td>" . $img . "</td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->medico_responsavel . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                    }
                }
                //            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='90px' src='img/clinicadez.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                } else {
                    if ($data['empresa'][0]->rodape_config == 't') {
                        //                $cabecalho = $cabecalho_config;
                        $rodape = $rodape_config;
                    } else {
                        if (!file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_id . ".jpg")) {
                            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
                        }
                    }
                }


                $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
                if ($sem_margins == 't') {
                    pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
                } else {
                    pdf($html, $filename, $cabecalho, $rodape);
                }
                $this->load->View('ambulatorio/impressaolaudo_1', $data);
            }
        }
 

//        $this->load->View('ambulatorio/impressaotarefamedico', $data);
//        
//        
    }

    function adicionalcabecalho($cabecalho, $laudo) {

//        $cabecalho = $impressaolaudo[0]->texto;
        $cabecalho = str_replace("_paciente_", $laudo['0']->paciente, $cabecalho);
        $cabecalho = str_replace("_sexo_", $laudo['0']->sexo, $cabecalho);
        $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($laudo['0']->nascimento)), $cabecalho);
        $cabecalho = str_replace("_convenio_", $laudo['0']->convenio, $cabecalho);
//        $cabecalho = str_replace("_sala_", $laudo['0']->sala, $cabecalho);
        $cabecalho = str_replace("_CPF_", $laudo['0']->cpf, $cabecalho);
        $cabecalho = str_replace("_RG_", $laudo['0']->rg, $cabecalho);
        $cabecalho = str_replace("_solicitante_", $laudo['0']->medico_responsavel, $cabecalho);
        $cabecalho = str_replace("_data_", substr($laudo['0']->data, 8, 2) . '/' . substr($laudo['0']->data, 5, 2) . '/' . substr($laudo['0']->data, 0, 4), $cabecalho);
        $cabecalho = str_replace("_medico_", $laudo['0']->medico_responsavel, $cabecalho);
        $cabecalho = str_replace("_revisor_", $laudo['0']->medicorevisor, $cabecalho);
//        $cabecalho = str_replace("_procedimento_", $laudo['0']->procedimento, $cabecalho);
        $cabecalho = str_replace("_laudo_", $laudo['0']->texto, $cabecalho);
//        $cabecalho = str_replace("_nomedolaudo_", $laudo['0']->cabecalho, $cabecalho);
//        $cabecalho = str_replace("_queixa_", $laudo['0']->cabecalho, $cabecalho);
//        $cabecalho = str_replace("_peso_", $laudo['0']->peso, $cabecalho);
//        $cabecalho = str_replace("_altura_", $laudo['0']->altura, $cabecalho);
//        $cabecalho = str_replace("_cid1_", $laudo['0']->cid1, $cabecalho);
//        $cabecalho = str_replace("_cid2_", $laudo['0']->cid2, $cabecalho);
//        $cabecalho = str_replace("_guia_", $laudo[0]->guia_id, $cabecalho);
        $operador_id = $this->session->userdata('operador_id');
        $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
        @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
//        @$cabecalho = str_replace("_usuario_salvar_", $laudo['laudo'][0]->usuario_salvar, $cabecalho);
        $cabecalho = str_replace("_prontuario_", $laudo[0]->paciente_id, $cabecalho);
        $cabecalho = str_replace("_telefone1_", $laudo[0]->telefone, $cabecalho);
        $cabecalho = str_replace("_telefone2_", $laudo[0]->celular, $cabecalho);
        $cabecalho = str_replace("_whatsapp_", $laudo[0]->whatsapp, $cabecalho);

        return $cabecalho;
    }
    
    function listartcd($paciente_id){ 
        $data['paciente_id'] = $paciente_id;
        $data['permissoes'] = $this->guia->listarempresapermissoes();  
        $this->loadView('ambulatorio/carregartcd-lista', $data); 
    }
    
    function imprimirtcd($orcamento_id, $paciente_id,$paciente_tcd_id){
        $this->load->plugin('mpdf'); 
        $filename = "Serviço RPS";
        $cabecalho = "";
        $rodape = ""; 
        $data['empresa'] = $this->guia->listarempresapermissoes();
        $data['paciente'] = $this->guia->listarpaciente($paciente_id);
        $data['total'] = $this->exametemp->somaprocedimentosorcamentotcd($orcamento_id); 
        $data['procedimentos'] = $this->exametemp->listarprocedimentosorcamentotcd($orcamento_id); 
        $data['numeronota'] = $this->guia->numeronotarps();
        
 
        // echo '<pre>';
        // print_r($data['procedimentos']);
        // die;

        $html =  $this->load->View('ambulatorio/servicorps',$data,true);
        
        // print_r($html);
        // die;
        pdf($html, $filename, $cabecalho, $rodape, '', 0,0,5);

        // $data['procedimentos'] = $this->exametemp->listarprocedimentosorcamentotcd($orcamento_id); 
        //  $this->load->View('ambulatorio/impressaotcd',$data);
            
    }
    
     function excluirtcd($paciente_tcd_id, $paciente_id) {
         $this->exametemp->registrainformacaoestornotcd($paciente_tcd_id);
        $verificar = $this->exametemp->excluirtcd($paciente_tcd_id);
        if ($verificar == -1) {
            $data['mensagem'] = 'Erro ao estornar o TCD. Opera&ccedil;&atilde;o cancelada.';
        } elseif ($verificar == -2) {
            $data['mensagem'] = 'Erro ao estornar. TCD já utilizado.';
        } else {
            $data['mensagem'] = 'Sucesso ao estornar o TCD.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exametemp/listartcd/$paciente_id");
    }

    function confirmartcd($paciente_tcd_id,$paciente_id){
       $verificar = $this->exametemp->confirmartcd($paciente_tcd_id);
        
         if ($verificar == -1) {
            $data['mensagem'] = 'Erro. Opera&ccedil;&atilde;o cancelada.';
        }  else {
            $data['mensagem'] = 'Sucesso ao confirmar TCD';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exametemp/listartcd/$paciente_id");
    }
    
    function imprimirtermotcd($paciente_id,$guia_id,$paciente_tcd_id){
       $this->load->plugin('mpdf'); 
       $filename = "TermoTCD";
       $cabecalho = "";
       $rodape = ""; 
       $procedimentos = $this->exametemp->somaprocedimentosorcamentotcd($guia_id); 
       $data['procedimentos'] = $this->exametemp->listarprocedimentosorcamentotcd($guia_id); 
       $data['tcd'] = $this->exametemp->listardadostcd($paciente_tcd_id);
       $data['valor_total'] = $procedimentos[0]->total;

       if ($procedimentos[0]->total <= 0) {
            $data['extenso'] = 'ZERO';
       } else {
            $valoreditado = str_replace(",", "", str_replace(".", "", $procedimentos[0]->total));
            $data['extenso'] = GExtenso::moeda($valoreditado);
       }  
       $data['empresas'] = $this->guia->listarempresapermissoes();
       $data['pacientes'] = $this->guia->listarpaciente($paciente_id);


    //    echo '<pre>';
    //    print_r($data['pacientes']);
    //    die;
       $html =  $this->load->View('ambulatorio/impressaotermotcd',$data,true);
        
       pdf($html, $filename, $cabecalho, $rodape, ''); 
    }
    
    
    function adicionarlimiteprocedimento(){
        $empresa_id = $_POST['empresa_id'];
        $medico_id = $_POST['medico_id'];
        $procedimento_tuss_id = $_POST['procedimento_tuss_id'];
        $quantidade = $_POST['quantidade'];
       
        $contador = $this->exametemp->verificarlimiteprocedimento($procedimento_tuss_id,$medico_id,$empresa_id);
        $mensagem = "";
           
       if(count($contador) > 0){
         $mensagem = "Erro, Procedimento já cadastrado para o profissional";  
       } else{ 
         $verificar =  $this->exametemp->adicionarlimiteprocedimento($procedimento_tuss_id,$medico_id,$quantidade,$empresa_id);
         if($verificar > 0){
            $mensagem = "Procedimento cadastado com sucesso";   
         }else{
             $mensagem = "Erro, tente novamente"; 
         }
       }  
        echo  $mensagem;
 
    }
    
    
    function excluirlimiteprocedimento($limite_procedimento_id){
        $this->exametemp->excluirlimiteprocedimento($limite_procedimento_id);
         redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }
    
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
