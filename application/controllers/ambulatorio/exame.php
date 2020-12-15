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
class Exame extends BaseController {

    function Exame() {
        parent::Controller();
        $this->load->model('ambulatorio/exame_model', 'exame');
        $this->load->model('ambulatorio/procedimentoplano_model', 'procedimentoplano');
//        $this->load->model('ambulatorio/agenda_model', 'agenda');
        $this->load->model('ambulatorio/sala_model', 'sala');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/laudo_model', 'laudo');
        $this->load->model('ambulatorio/empresa_model', 'empresa');
        $this->load->model('login_model', 'login');
        $this->load->model('ambulatorio/tipoconsulta_model', 'tipoconsulta');
        $this->load->model('seguranca/operador_model', 'operador_m');
        $this->load->model('cadastro/paciente_model', 'paciente');
        $this->load->model('ambulatorio/motivocancelamento_model', 'motivocancelamento');
        $this->load->model('ambulatorio/procedimento_model', 'procedimento');
        $this->load->model('centrocirurgico/centrocirurgico_model', 'centrocirurgico_m');
        $this->load->model('internacao/internacao_model', 'internacao_m');
        $this->load->model('ambulatorio/agenda_model', 'agenda');
        $this->load->model('ponto/Competencia_model', 'competencia');
        $this->load->model('cadastro/convenio_model', 'convenio');
        $this->load->model('cadastro/formapagamento_model', 'formapagamento');
        $this->load->model('ambulatorio/exametemp_model', 'exametemp');

        $this->load->library('googleplus');
        $this->load->model('calendario/googlecalendar_model', 'googlecalendar');
        $this->load->model('calendario/auth_model', 'auth');


        $this->load->library('mensagem');
        $this->load->library('utilitario');
        $this->load->library('pagination');
        $this->load->library('validation');
//        if ($this->session->userdata('autenticado') != true) {
//            redirect(base_url() . "login/index/login004", "refresh");
//        }
    }

    function index() {
        $this->pesquisar();
    }

    function pesquisar($limite = 10) {
        $data["limite_paginacao"] = $limite;
        $this->loadView('ambulatorio/exame-lista', $data);
    }

    function gravaraudio($args = array()) {

        $this->loadView('ambulatorio/gravaraudio-form', $args);
    }

    function listarsalaspreparo($args = array()) {

        $this->loadView('ambulatorio/examepreparo-lista', $args);
    }

    function relatoriousosala($args = array()) {
        $this->load->View('ambulatorio/relatoriousosala');
    }

    function listarsalasespera($limite = 25) {
        $data["limite_paginacao"] = $limite;
        $this->loadView('ambulatorio/exameespera-lista', $data);
    }

    function mantertelastoten() {

        $this->loadView('ambulatorio/telatoten-lista');
    }

    function mantersalastoten() {

        $this->loadView('ambulatorio/salatoten-lista');
    }

    function mantertabelastoten() {

        $this->loadView('ambulatorio/tabelastoten-lista');
    }

    function carregarsalatoten($id) {
        $data['empresa'] = $this->empresa->listarempresatoten();
        $endereco = $data['empresa'][0]->endereco_toten;
        $data['endereco'] = $endereco;
        $data['sala'] = $this->exame->carregarsalatoten($endereco, $id);
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/salatoten-form', $data);
    }

    function gravartotentelasetor() {

        $data['empresa'] = $this->empresa->listarempresatoten();
        $endereco = $data['empresa'][0]->endereco_toten;
        $data['endereco'] = $endereco;
        $this->exame->gravartotentelasetor($endereco);
        $mensagem = 'Tela e setor associados com sucesso';
        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/exame/mantertelastoten");
    }

    function gravarsalatoten() {

        $data['empresa'] = $this->empresa->listarempresatoten();
        $endereco = $data['empresa'][0]->endereco_toten;
        $data['endereco'] = $endereco;
        $this->exame->gravarsalatoten($endereco);
        $mensagem = 'Sala criada com sucesso';
        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/exame/mantersalastoten");
    }

    function excluirsalatoten($id) {

        $data['empresa'] = $this->empresa->listarempresatoten();
        $endereco = $data['empresa'][0]->endereco_toten;
        $data['endereco'] = $endereco;
        $this->exame->excluirsalatoten($endereco, $id);
        $mensagem = 'Sala excluida com sucesso';
        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/exame/mantersalastoten");
    }

    function excluirsenhastoten() {

        $data['empresa'] = $this->empresa->listarempresatoten();
        $endereco = $data['empresa'][0]->endereco_toten;
        $data['endereco'] = $endereco;
        $this->exame->excluirsenhastoten($endereco);
        $mensagem = 'Senhas excluidas com sucesso';
        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/exame/mantertabelastoten");
    }

    function excluirchamadototen() {

        $data['empresa'] = $this->empresa->listarempresatoten();
        $endereco = $data['empresa'][0]->endereco_toten;
        $data['endereco'] = $endereco;
        $this->exame->excluirchamadototen($endereco);
        $mensagem = 'Chamadas excluidas com sucesso';
        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/exame/mantertabelastoten");
    }

    function listaresperacaixa($args = array()) {

        $this->loadView('ambulatorio/exameesperacaixa-lista', $args);
    }

    function listaresperasenhas() {
//        echo '<pre>';
        $empresa_id = $this->session->userdata('empresa_id');
        $operador_id = $this->session->userdata('operador_id');
        $data['operadorpoltrona'] = $this->operador_m->listarmedicospoltrona();  
        $data['empresa'] = $this->empresa->listarempresatoten($empresa_id);
        $data['permissao'] = $this->empresa->listarverificacaopermisao2($empresa_id);
        $data['solicitacoes'] = $this->laudo->listarsolicitacoeschamar();
        $endereco = $data['empresa'][0]->endereco_toten;
        $data['endereco'] = $endereco;

        $data['operadorguiche'] = $this->operador_m->listaroperadorguiche2($operador_id);
        $guiche = $data['operadorguiche'][0]->guiche;
        if ($data['operadorguiche'][0]->guiche != 0) {
            $data['guiche'] = $guiche;
        } else {
            $data['guiche'] = "1";
        }
//        var_dump($data['guiche']);die;
        if ($endereco != '') {
            $data['senhas'] = array();
            @$setor_busca = file_get_contents("$endereco/webService/telaAtendimento/setores");
            $data['setores'] = json_decode($setor_busca);
            if (!count($data['setores']) > 0) {
                $data['setores'] = array();
            }
            $setor_string = '';
            foreach ($data['setores'] as $item) {
                if ($setor_string == '') {
                    $setor_string = $setor_string . $item->id;
                } else {
                    $setor_string = $setor_string . "," . $item->id;
                }
                $item->contador = 0;
                $item->contadorTotal = 0;
            }
            $data['setor_string'] = $setor_string;


        //    var_dump($data['setores']); die;
//        var_dump($data['senhas']); die;    
            if (!preg_match('/\:8099/', $endereco)) {
                @$senhas_busca = file_get_contents("$endereco/painel-api/api/painel/senhas");
                $data['senhas'] = json_decode($senhas_busca);
                @$this->operador_m->gravarsenhahorario($data['senhas']);
                $this->loadView('ambulatorio/exameesperasenhasnovo-lista', $data);
            } else {
                @$senhas_busca = file_get_contents("$endereco/webService/fialaDeespera/$setor_string");
                $data['senhas'] = json_decode($senhas_busca);
                $data['senhasTotal'] = $this->operador_m->senhasretiradas();
                foreach ($data['setores'] as $item) {
                    if ($setor_string == '') {
                        $setor_string = $setor_string . $item->id;
                    } else {
                        $setor_string = $setor_string . "," . $item->id;
                    }
                    foreach ($data['senhas'] as $key => $value) {
                        $senhaNome = $value->setor->nome;
                        if($senhaNome == $item->nome){
                            $item->contador++;
                        }
                    }

                    foreach ($data['senhasTotal'] as $key => $value) {
                        $setor = str_replace('-', '', $item->nome);
                        $senha = explode('-', $value->senha);
                        $senhaCon = $this->utilitario->removeCharacter($senha[0]);
                        $setor = $this->utilitario->removeCharacter($setor);
                        // echo '<pre>';
                        // var_dump($data['setores']);
                        // var_dump($data['senhasTotal']);
                        // echo '<br>';
                        // die;
                        if(trim($setor) == trim($senhaCon)){
                            $item->contadorTotal++;
                        }
                    }
                    
                }
                $this->operador_m->gravarsenhahorario($data['senhas']);
               
                // echo '<pre>';
                // var_dump($data['setores']); 
                // die; 
                $this->loadView('ambulatorio/exameesperasenhas-lista', $data);
            }
        } else {
            $mensagem = 'Erro: Não há endereço do Toten cadastrado na empresa';
            $this->session->set_flashdata('message', $mensagem);
            redirect(base_url() . "cadastros/pacientes");
        }
    }

    function listarmultifuncao($args = array()) {

        $this->loadView('ambulatorio/examemultifuncao-lista', $args);
    }

    function listaragendamentomultiempresa($args = array()) {
        $parametro = array(
            'especialidade' => (@$_GET['especialidade'] != '') ? @$_GET['especialidade'] : '',
            'medico' => (@$_GET['medico'] != '') ? @$_GET['medico'] : '',
            'data' => (@$_GET['data'] != '') ? @$_GET['data'] : '',
            'nome' => (@$_GET['nome'] != '') ? @$_GET['nome'] : ''
        );

        $url = "especialidade={$parametro['especialidade']}&medico={$parametro['medico']}&data={$parametro['data']}&nome={$parametro['nome']}";

        $resposta = file_get_contents("http://localhost/arquivoDados.php?{$url}");

        $array = explode("|", $resposta);

        foreach ($array as $item) {
            if (strlen($item) >= 2) {
                $a = explode("$", $item);
                @$args["agenda"][str_replace(" ", '', $a[0])] = json_decode($a[1]);
            }
        }
        $data["dados"] = $args;

        $medicos = file_get_contents("http://localhost/arquivoRequisicoes.php?acao=medico");

        $med = explode("|", $medicos);

        foreach ($med as $item) {
            if (strlen($item) >= 2) {
                $a = explode("$", $item);
                @$data["medicos"][str_replace(" ", '', $a[0])] = json_decode($a[1]);
            }
        }

        $especialidade = file_get_contents("http://localhost/arquivoRequisicoes.php?acao=especialidade");

        $esp = explode("|", $especialidade);

        foreach ($esp as $item) {
            if (strlen($item) >= 2) {
                $a = explode("$", $item);
                @$data["especialidade"][str_replace(" ", '', $a[0])] = json_decode($a[1]);
            }
        }
        /* CASO ESTEJA DANDO ERRO, ANTES DE IR PROCURAR, 
         * OLHA NOS ARQUIVOS QUE ESTÃO LA EM WWW 
         * PARA VER SE ESTÃO COM O NOME DO BANCO CERTO */
//        var_dump(@$data["especialidade"]);die;

        $this->loadView('ambulatorio/agendamentomultiempresa-lista', $data);
    }

    function listarmultifuncaogeral($args = array()) {

        $this->loadView('ambulatorio/examemultifuncaogeral-lista', $args);
    }

    function calendariohorariosagenda($args = array()) {
        // var_dump($_GET); die;
        $this->load->View('ambulatorio/calendariohorariosagenda', $args);
    }

    function listarmultifuncaocalendario2($args = array()) {
        $this->load->View('ambulatorio/calendario2', $args);
    }

    function oauth()
    {
  
        $code = $this->input->get('code', true);
        $this->googlecalendar->login($code);

        redirect(base_url().'ambulatorio/exame/listarmultifuncaocalendario2');
    }

    function importaragendagoogles(){
        $agendas = $this->exame->listaragendasocupadas();
        
        
        // echo '<pre>';
        // print_r($agendas);
        // die;
        
        $i = 0;
        foreach($agendas as $key=>$item){

            if($item->cns == '' || $item->cns == NULL){
                echo 'Paciente '.$item->paciente.' Não possui e-mail cadastrado - Evento não importado';
                echo '<br>';
                continue;
            }else{

                if(filter_var($item->cns, FILTER_VALIDATE_EMAIL)){
                    
                }else{
                    echo 'Paciente '.$item->paciente.' E-mail com Formato Invalido - '.$item->cns.' - Evento não importado';
                    echo '<br>';
                    continue;
                }
            }
            $i++;

            //echo $i;
            //echo '<br>';
            // if($key <= 18){
            //     continue;
            // }

                    $data = date("Y-m-d", strtotime(str_replace('/', '-', $item->data)));
                    $description = 'Médico: '.$item->medico.'<br>Procedimento: '.$item->procedimento.'<br>Observação: '.$item->observacoes;
                    
                    if($item->cns != ''){
                        $qtdemail = 2;
                        $event = array(
                            'summary'     => $item->paciente,
                            'start'       => $data.'T'.$item->inicio.'-03:00',
                            'end'         => $data.'T'.$item->fim.'-03:00',
                            'description' => $description,
                            'email' => $item->email,
                            'email2' => $item->cns,
                            'colorid' => $item->coragenda,
                            'localizacao' => $item->logradouro.' '.$item->numero.' - '.$item->bairro.' - '.$item->municipio,
                
                        );

                    }else{
                    $qtdemail = 1;
                    $event = array(
                        'summary'     => $item->paciente,
                            'start'       => $data.'T'.$item->inicio.'-03:00',
                            'end'         => $data.'T'.$item->fim.'-03:00',
                            'description' => $description,
                            'email' => $item->email,
                            'colorid' => $item->coragenda,
                            'localizacao' => $item->logradouro.' '.$item->numero.' - '.$item->bairro.' - '.$item->municipio,
            
                    );
                }
                  $foo = $this->googlecalendar->addEvent('primary', $event, $qtdemail);

             if ($foo->status == 'confirmed') {
                     $this->exametemp->confirmaragendagoogle($item->agenda_exames_id);
               }
        }
        if(count($agendas) > 0){
            echo '<br> <b>Importação das Agendas Finalizada</b>';
        }else{
            echo '<br> <b>Todas as Agendas de Ontem Foram importadas</b>';
        }
        // echo $i;
        // echo '<br> <b>Importação das Agendas Finalizada</b>';
    }

    function listarmultifuncaocalendariopaciente($paciente_id) {
        $data['paciente_id'] = $paciente_id;
        $this->load->View('ambulatorio/calendariopaciente', $data);
    }

    function listarmultifuncaocalendario($args = array()) {

        $this->load->View('ambulatorio/calendario', $args);
    }

    function listarmultifuncaoexamecalendario($args = array()) { 
        $this->load->View('ambulatorio/calendarioexame', $args);
    }

    function listarmultifuncaoconsultacalendario($args = array()) {

        $this->load->View('ambulatorio/calendarioconsulta', $args);
    }

    function listarmultifuncaoespecialidadecalendario($args = array()) {

        $this->load->View('ambulatorio/calendarioespecialidade', $args);
    }

    function listarmultifuncaoconsulta($args = array()) {

        $this->loadView('ambulatorio/examemultifuncaoconsulta-lista', $args);
    }

    function relatoriomedicoagendaexame() {
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->guia->listarempresas();
        $data['salas'] = $this->exame->listartodassalas();
        $this->loadView('ambulatorio/relatoriomedicoagendaexame', $data);
    }

    function operadorguiche() {

        $data['perfil'] = $this->operador_m->listarPerfil();
        $data['operadores'] = $this->operador_m->listaroperadoreslembrete();
        $data['operador'] = $this->operador_m->listaroperadorguiche();
        $this->loadView('ambulatorio/operadorguiche', $data);
    }

    function gravaroperadorguiche() {
//        var_dump($_POST);die;
        if ($this->exame->gravaroperadorguiche()) {
            $mensagem = 'Sucesso ao gravar!';
        } else {
            $mensagem = 'Erro ao gravar. Opera&ccedil;&atilde;o cancelada.';
        }

        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/exame/operadorguiche");
    }

    function relatoriomedicoagendafaltou() {
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->guia->listarempresas();
        $data['salas'] = $this->exame->listartodassalas();
        $this->loadView('ambulatorio/relatoriomedicoagendaexamefaltou', $data);
    }

    function carregarreagendamento() {
        if (count($_POST) > 0) {

            @$agenda = $this->exame->listarhorariosreagendamento();
            if (count(@$agenda) > 0) {
                $verificao = $this->exame->gravareagendamento($agenda);
                if (count($verificao) == 0) {
                    $data['mensagem'] = 'Sucesso ao reagendar todos Pacientes do dia selecionado.';
                } else {
                    $data['mensagem'] = 'Não foi possivel reagendar alguns pacientes devido a conflitos de horario.';
                }
            } else {
                $data['mensagem'] = 'Não há pacientes agendados para o dia escolhido.';
            }
        }

//        echo "<pre>";
//        var_dump($agenda);die;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/reagendamentogeral");
    }

    function carregarreagendamentoespecialidade() {

        if (count($_POST['reagendar']) > 0) {

            @$agenda = $this->exame->listarhorariosreagendamentoespecialidade();
//            var_dump(@$agenda); die;
            $pacientes = '';
            if (count(@$agenda) > 0) {
                $verificao = $this->exame->gravareagendamentoespecialidade($agenda);
                if (count($verificao) == 0) {
                    $data['mensagem'] = 'Sucesso ao reagendar todos Pacientes do dia selecionado.';
                } else {

                    foreach ($verificao as $item) {
                        $pacientes = $pacientes . ", " . $item;
//                    var_dump($item); 
                    }
//                die;
                    $data['mensagem'] = "Não foi possivel reagendar os seguintes pacientes devido a conflitos de horario. $pacientes";
                }
            } else {
                $data['mensagem'] = 'Não há pacientes agendados para o dia escolhido.';
            }
        } else {
            $data['mensagem'] = 'Não foram escolhidos ou não há pacientes para reagendar.';
        }

//        echo "<pre>";
//        var_dump($agenda);die;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/listarmultifuncaomedicofisioterapia");
    }

    function reagendamentogeral() {
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->guia->listarempresas();
        $data['salas'] = $this->exame->listartodassalas();
        $this->loadView('ambulatorio/relatorioreagendamentogeral', $data);
    }

    function relatorioorcamentos() {
        $data['empresa'] = $this->guia->listarempresas();
        $data["grupos"] = $this->guia->listargruposrelatorioorcamento();
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $this->loadView('ambulatorio/relatorioorcamentos', $data);
    }

    function relatoriodemandagrupo() {
        $data['empresa'] = $this->guia->listarempresas();
        $data['grupos'] = $this->procedimento->listargrupos();
        $this->loadView('ambulatorio/relatoriodemandagrupo', $data);
    }

    function listarusosala() {
        if (count($_GET) > 0 && $_GET['sala'] != '') {
            $empresaFuncionamento = $this->exame->horariofuncionamentoempresa();

            // Pega o tempo total (em minutos) que a empresa vai funcionar durante nos dias da semana
            $datetime1 = new DateTime($empresaFuncionamento[0]->horario_seg_sex_inicio);
            $datetime2 = new DateTime($empresaFuncionamento[0]->horario_seg_sex_fim);
            $intervalo = $datetime1->diff($datetime2);
            $tempoFuncionamentoSemana = ((int) $intervalo->format('%H')) * 60 + ((int) $intervalo->format('%i'));

            // Pega o tempo total (em minutos) que a empresa vai funcionar durante os dias do fds
            $datetime1 = new DateTime($empresaFuncionamento[0]->horario_sab_inicio);
            $datetime2 = new DateTime($empresaFuncionamento[0]->horario_sab_fim);
            $intervalo = $datetime1->diff($datetime2);
            $tempoFuncionamentoSabado = ((int) $intervalo->format('%H')) * 60 + ((int) $intervalo->format('%i'));

            // Busca os horarios que essa sala vai estar em uso (ou agendada)
            $result = $this->exame->listarusosala($_GET['sala']);
            $retornoJSON = array();
            $dias = array();

            for ($i = 0; $i < count($result); $i++) {
                $medico = Array();
                $string_medico = "";
                $json = $result[$i]->agenda_exames_array; 
                $agenda_exames_idStr = str_replace('{', '',str_replace('}', '',   $result[$i]->agenda_exames_array));
                if($agenda_exames_idStr != 'NULL'){
                    $array_agenda_exames_id = explode(',', $agenda_exames_idStr);
                }else{
                    $array_agenda_exames_id = array(); 
                }   
                foreach($array_agenda_exames_id as $item){ 
                    $medicos = $this->exame->listarmedicosagenda($item);
                    foreach($medicos as $value){
                      $medico[$value->operador_id] =  $value->medico;
                    } 
                } 
                foreach($medico as $m){ 
                      $string_medico .= "Médico: ".$m."\n";
                }  
                    
                // Calcula o tempo que essa sala vai estar sendo usada (em minutos)
                $datetime1 = new DateTime($result[$i]->inicio);
                $datetime2 = new DateTime($result[$i]->fim);
                $intervalo = $datetime1->diff($datetime2);
                $tempoOcupacao = ((int) $intervalo->format('%H')) * 60 + ((int) $intervalo->format('%i'));

                if (date("N", strtotime($result[$i]->data)) > 5) { // Caso seja um FDS
                    @$dias[date("Ymd", strtotime($result[$i]->data))]['tempoFuncionamento'] = $tempoFuncionamentoSabado;
                    @$dias[date("Ymd", strtotime($result[$i]->data))]['start'] = date("Y-m-d", strtotime($result[$i]->data)) . "T" . date("H:i:s", strtotime($empresaFuncionamento[0]->horario_sab_inicio));
                    @$dias[date("Ymd", strtotime($result[$i]->data))]['end'] = date("Y-m-d", strtotime($result[$i]->data)) . "T" . date("H:i:s", strtotime($empresaFuncionamento[0]->horario_sab_fim));
                    @$dias[date("Ymd", strtotime($result[$i]->data))]['texto'] = $result[$i]->sala . "\n" . @date("H:i", strtotime($empresaFuncionamento[0]->horario_sab_inicio)) . " as " . @date("H:i", strtotime($empresaFuncionamento[0]->horario_sab_fim));
                } else { // Caso seja um dia normal 
                    @$dias[date("Ymd", strtotime($result[$i]->data))]['tempoFuncionamento'] = $tempoFuncionamentoSemana;
                    @$dias[date("Ymd", strtotime($result[$i]->data))]['start'] = date("Y-m-d", strtotime($result[$i]->data)) . "T" . date("H:i:s", strtotime($empresaFuncionamento[0]->horario_seg_sex_inicio));
                    @$dias[date("Ymd", strtotime($result[$i]->data))]['end'] = date("Y-m-d", strtotime($result[$i]->data)) . "T" . date("H:i:s", strtotime($empresaFuncionamento[0]->horario_seg_sex_fim));
                    @$dias[date("Ymd", strtotime($result[$i]->data))]['texto'] = $result[$i]->sala . "\n" . @date("H:i", strtotime($empresaFuncionamento[0]->horario_seg_sex_inicio)) . " as " . @date("H:i", strtotime($empresaFuncionamento[0]->horario_seg_sex_fim));
                }

                // Incrementa o tempo calculado nesse laço, com o valor dos laços passados
                @$dias[date("Ymd", strtotime($result[$i]->data))]['tempoUso'] += $tempoOcupacao;
                $percentual = (($dias[date("Ymd", strtotime($result[$i]->data))]['tempoUso'] / $dias[date("Ymd", strtotime($result[$i]->data))]['tempoFuncionamento']) * 100);
                // Transforma o valor acima em um percentual
                @$dias[date("Ymd", strtotime($result[$i]->data))]['title'] = number_format($percentual, 2, ",", "") . "%";

                    
                $retorno['id'] = $i;
                $retorno['title'] = $result[$i]->sala;
                $retorno['texto'] = $result[$i]->sala . "\n" . date("H:i", strtotime($result[$i]->inicio)) . " as " . date("H:i:s", strtotime($result[$i]->fim))."\n".$string_medico;
                $retorno['start'] = date("Y-m-d", strtotime($result[$i]->data)) . "T" . date("H:i:s", strtotime($result[$i]->inicio));
                $retorno['end'] = date("Y-m-d", strtotime($result[$i]->data)) . "T" . date("H:i:s", strtotime($result[$i]->fim));
                $retornoJSON[] = $retorno;
            }

            foreach ($dias as $value) { // Esses valores irão aparecer na linha "ALL DAY"
                $i++;
                $value['color'] = '#e74c3c'; // Definindo uma cor
                $value['className'] = 'titulo'; // Definindo um estilo CSS
                $value['allDay'] = 'true'; // Informando que essas informações é para aparecer na linha "ALL DAY"
                $value['id'] = $i;
                $retornoJSON[] = $value;
            }

            echo json_encode($retornoJSON);
        }
    }

    function relatorioteleoperadora() {
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarteleoperadora();
        $data['empresa'] = $this->guia->listarempresas();
        $data['salas'] = $this->exame->listartodassalas();
        $this->loadView('ambulatorio/relatorioteleoperadora', $data);
    }

    function relatoriorecepcaoagenda() {
        $empresa_id = $this->session->userdata('empresa_id');
        $data['operadores'] = $this->operador_m->listartecnicos();
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->guia->listarempresas();
        $data['salas'] = $this->exame->listartodassalas();
        $data['permissaoempresa'] = $this->guia->listarempresasaladepermissao($empresa_id);
        $this->loadView('ambulatorio/relatoriorecepcaoagenda', $data);
    }

    function relatoriomapadecalor() {
        $empresa_id = $this->session->userdata('empresa_id');
        $data['operadores'] = $this->operador_m->listartecnicos();
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->guia->listarempresas();
        $data['salas'] = $this->exame->listartodassalas();
        $data['permissaoempresa'] = $this->guia->listarempresasaladepermissao($empresa_id);
        $this->loadView('ambulatorio/relatoriomapadecalor', $data);
    }

    function relatoriomedicoagendaconsultas() {
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriomedicoagendaconsulta', $data);
    }

    function relatoriomedicoordem() {
        $data['convenio'] = $this->convenio->listardados();
        $data['procedimentos'] = $this->guia->listarprocedimentos();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['salas'] = $this->sala->listarsalas();
        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriomedicoordem', $data);
    }

    function relatoriopacientetelefone() {
        $data['empresa'] = $this->guia->listarempresas();
        $data['convenio'] = $this->convenio->listardados();
        $data['bairros'] = $this->paciente->listarbairros();
        $data['grupos'] = $this->procedimento->listargrupos();
        $data['procedimento'] = $this->procedimento->listarprocedimento2();
        $this->loadView('ambulatorio/relatoriopacientetelefone', $data);
    }

    function relatoriorevisao() {
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data["grupos"] = $this->guia->listargruposrelatorioretorno();
        $this->loadView('ambulatorio/relatoriorevisao', $data);
    }

    function relatorioretorno() {
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data["grupos"] = $this->guia->listargruposrelatorioretorno();
        $this->loadView('ambulatorio/relatorioretorno', $data);
    }

    function relatorioencaminhamento() {
//        $data['convenio'] = $this->convenio->listardados();
//        $data['grupos'] = $this->procedimento->listargrupos();
        $data['medicos'] = $this->operador_m->listarmedicos();
//        $data['procedimento'] = $this->procedimento->listarprocedimento2();
        $this->loadView('ambulatorio/relatorioencaminhamento', $data);
    }

    function gerarelatoriorecepcaoagenda() {
        $empresa_id = $this->session->userdata('empresa_id');
        $medicos = $_POST['medicos'];
        $salas = $_POST['salas'];
        if ($salas > 0) {
            $data['salas'] = $this->sala->listarsala($salas);
        }
        if ($medicos > 0) {
            $data['medico'] = $this->operador_m->listarCada($medicos);
        }
        if ($_POST['convenio'] > 0) {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        } else {
            $data['convenios'] = 0;
        }
        $data['permissaoempresa'] = $this->guia->listarempresasaladepermissao($empresa_id);
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->exame->relatoriorecepcaoagenda();
        $data['operador'] = $this->operador_m->listaroperadorarray(@$_POST['operador']);

        // echo '<pre>';
        // var_dump($data['relatorio']); die;
        
        
        if ($_POST['tipoRelatorio'] == '2') {
            $this->load->View('ambulatorio/impressaorelatoriomedicoagendaexamefaltou', $data);
        } else {            
            if ($_POST['modelorealatorio'] == '0') { 
                $this->load->View('ambulatorio/impressaorelatoriomedicoagendatodos2', $data);
            }else{
                $this->load->View('ambulatorio/impressaorelatoriomedicoagendatodos', $data);
            }            
        }
        
        
        
    }

    function gerarelatoriomapadecalor() {
        $empresa_id = $this->session->userdata('empresa_id');
        $salas = $_POST['salas'];
        if ($salas > 0) {
            // $data['salas'] = $this->sala->listarsala($salas);
        }
        $data['salas'] = array();
        $data['permissaoempresa'] = $this->guia->listarempresasaladepermissao($empresa_id);
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->exame->relatoriomapadecalor();


        // echo '<pre>';
        // var_dump($data['relatorio']); die;
        
        $this->load->View('ambulatorio/impressaorelatoriomapadecalor', $data);
        
    }

    function gerarelatoriodemandagrupo() {
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['empresa_id'] = $_POST['empresa'];
        $data['grupo'] = $_POST['grupo'];
        $data['relatorio'] = $this->exame->gerarelatoriodemandagrupo();
        $data['horarios'] = $this->exame->gerarelatoriodemandagrupohorario();

//        echo "<pre>";
//        var_dump($data['horarios']); die;
        $this->load->View('ambulatorio/impressaorelatoriodemandagrupo', $data);
    }

    function gerarelatorioorcamentos() {
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['empresa_id'] = $_POST['empresa'];
        $data['grupo'] = $_POST['grupo'];
        $data['relatorio'] = $this->exame->gerarelatorioorcamentos();
        $data['relatoriodemanda'] = $this->exame->gerarelatoriodemandagrupoorcamento();
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $this->load->View('ambulatorio/impressaorelatorioorcamentos', $data);
    }

    function gerarelatorioteleoperadora() {
        $medicos = $_POST['medicos'];
        if ($_POST['medicos'] != '') {
            $data['medico'] = $this->operador_m->listarCada($medicos);
        } else {
            $data['medico'] = null;
        }

        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->exame->listaragendamentoteleoperadora();
        $this->load->View('ambulatorio/impressaorelatorioteleoperadora', $data);
    }

    function recusarorcamento($ambulatorio_orcamento_id) {
        $dataSelecionada = null;
        $teste = $this->exame->gravarrecusarorcamento($ambulatorio_orcamento_id, $dataSelecionada);
        //    var_dump($teste);
        //    die;
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function gravarautorizarorcamentorelatorio($ambulatorio_orcamento_id, $dataSelecionada) {
        $dataSelecionada = date("Y-m-d", strtotime(str_replace('/', '-', $dataSelecionada)));

        $teste = $this->exame->testarautorizarorcamentorelatorio($ambulatorio_orcamento_id, $dataSelecionada);
        //    var_dump($teste);
        //    die;

        if ($teste[0]->autorizado == 'f') {
            $paciente_id = $this->exame->gravarautorizacaoorcamentorelatorio($ambulatorio_orcamento_id, $dataSelecionada);
            if ($paciente_id == '-1') {
                $mensagem = 'Erro ao autorizar o Orçamento. Opera&ccedil;&atilde;o cancelada.';
                $this->session->set_flashdata('message', $mensagem);
                redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
            } else {
                redirect(base_url() . "cadastros/pacientes/procedimentoautorizaratendimento/$paciente_id?orcamento=$ambulatorio_orcamento_id");
            }
        } else {
            $paciente_id = $teste[0]->paciente_id;
            redirect(base_url() . "cadastros/pacientes/procedimentoautorizaratendimento/$paciente_id?orcamento=$ambulatorio_orcamento_id");
        }
    }

    function gravarautorizarorcamentonaocadastro($ambulatorio_orcamento_id) {
                    
        $teste = $this->exame->testarautorizarorcamento($ambulatorio_orcamento_id);
//        var_dump($_POST);
//        die;
        if ($_POST['txtNome'] != '') {
            if ($teste[0]->autorizado == 'f') {
                $gravar_paciente = $this->exame->gravarpacienteorcamento($ambulatorio_orcamento_id);
                $paciente_id = $this->exame->gravarautorizacaoorcamento($ambulatorio_orcamento_id);
                if ($paciente_id == '-1') {
                    $mensagem = 'Erro ao autorizar o Orçamento. Opera&ccedil;&atilde;o cancelada.';
                    $this->session->set_flashdata('message', $mensagem);
                    redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
                } else {
                    redirect(base_url() . "cadastros/pacientes/procedimentoautorizaratendimento/$paciente_id");
                }
            } else {
                $paciente_id = $teste[0]->paciente_id;
                redirect(base_url() . "cadastros/pacientes/procedimentoautorizaratendimento/$paciente_id");
            }
        } else {
            $mensagem = 'Erro ao autorizar o orçamento: É necessário informar o paciente';
            $this->session->set_flashdata('message', $mensagem);
            redirect(base_url() . "ambulatorio/exame/autorizarorcamentonaocadastro/$ambulatorio_orcamento_id");
        }
    }

    function gravarautorizarorcamento($ambulatorio_orcamento_id) {

        $teste = $this->exame->testarautorizarorcamentorelatorio($ambulatorio_orcamento_id);
//        var_dump($teste);
//        die;
        if ($teste[0]->autorizado == 'f') {
            $paciente_id = $this->exame->gravarautorizacaoorcamento($ambulatorio_orcamento_id);
            if ($paciente_id == '-1') {
                $mensagem = 'Erro ao autorizar o Orçamento. Opera&ccedil;&atilde;o cancelada.';
                $this->session->set_flashdata('message', $mensagem);
                redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
            } else {
                redirect(base_url() . "cadastros/pacientes/procedimentoautorizaratendimento/$paciente_id?orcamento=$ambulatorio_orcamento_id");
            }
        } else {
            $paciente_id = $teste[0]->paciente_id;
            redirect(base_url() . "cadastros/pacientes/procedimentoautorizaratendimento/$paciente_id?orcamento=$ambulatorio_orcamento_id");
        }
    }

    function autorizarorcamento($ambulatorio_orcamento_id) {
        $data['ambulatorio_orcamento_id'] = $ambulatorio_orcamento_id;
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['salas'] = $this->guia->listarsalas();
        $this->load->View('ambulatorio/autorizarorcamento-form', $data);
    }

    function autorizarorcamentonaocadastro($ambulatorio_orcamento_id) {
        $data['ambulatorio_orcamento_id'] = $ambulatorio_orcamento_id;
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['salas'] = $this->guia->listarsalas();
        $data['exames'] = $this->procedimento->listarorcamentosrecepcao($ambulatorio_orcamento_id);
        $data['responsavel'] = $this->procedimento->listaresponsavelorcamento($ambulatorio_orcamento_id);
        $this->loadView('ambulatorio/autorizarorcamentonaocadastro', $data);
    }

    function autorizarencaminhamento($agenda_exames_id) {
        $paciente_id = $this->exame->gravarencaminhamentoatendimento($agenda_exames_id);
        if ($paciente_id == '-1') {
            $mensagem = 'Erro ao autorizar o Encaminhamento. Opera&ccedil;&atilde;o cancelada.';
            $this->session->set_flashdata('message', $mensagem);
            redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
        } else {
            redirect(base_url() . "cadastros/pacientes/procedimentoautorizaratendimento/$paciente_id");
        }
//        die('Criar o exame/consulta e redirecionar pra tela de "autorizar atendimento');
    }

    function gerarelatoriorevisao() {

        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['relatorio'] = $this->exame->gerarelatoriorevisao();
        $data['grupo'] = $_POST['grupo'];
        $this->load->View('ambulatorio/impressaorelatoriorevisao', $data);
    }

    function gerarelatorioretorno() {

        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['relatorio'] = $this->exame->gerarelatorioretorno();
        $data['grupo'] = $_POST['grupo'];
        $this->load->View('ambulatorio/impressaorelatorioretorno', $data);
    }

    function gerarelatorioencaminhamento() {

        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['relatorio'] = $this->exame->gerarelatorioencaminhamento();
        $this->load->View('ambulatorio/impressaorelatorioencaminhamento', $data);
    }

    function gerarelatoriopacientetelefone() {
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['relatorio'] = $this->exame->gerarelatoriopacientetelefone();
        if ($_POST['gera_planilha'] == 'SIM') {

            $html = $this->load->View('ambulatorio/impressaorelatoriopacientetelefone', $data, true);
            $filename = "Relatorio " . $_POST['txtdata_inicio'] . " a " . $_POST['txtdata_fim'];
            // Configurações header para forçar o download
            header("Content-type: application/x-msexcel; charset=utf-8");
            header("Content-Disposition: attachment; filename=\"{$filename}\"");
            header("Content-Description: PHP Generated Data");
            // Envia o conteúdo do arquivo
            echo $html;
            exit;
        } else {
            $this->load->View('ambulatorio/impressaorelatoriopacientetelefone', $data);
        }
    }

    function gerarelatoriomedicoagendaconsultas() {
        $medicos = $_POST['medicos'];
        if ($_POST['medicos'] != '') {
            $data['medico'] = $this->operador_m->listarCada($medicos);
        } else {
            $data['medico'] = null;
        }

        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->exame->listaragendaconsulta();
        $this->load->View('ambulatorio/impressaorelatoriomedicoagendaconsulta', $data);
    }

    function gerarelatoriomedicoagendaespecialidade() {
        $medicos = $_POST['medicos'];
        if ($_POST['medicos'] != '') {
            $data['medico'] = $this->operador_m->listarCada($medicos);
        } else {
            $data['medico'] = null;
        }
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->exame->listaragendaespecialidade();
        $this->load->View('ambulatorio/impressaorelatoriomedicoagendaespecialidade', $data);
    }

    function gerarelatoriomedicoagendaduplicidade() {
        $medicos = $_POST['medicos'];
        if ($_POST['medicos'] != '') {
            $data['medico'] = $this->operador_m->listarCada($medicos);
        } else {
            $data['medico'] = null;
        }

        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->exame->listaragendaconsulta();
        $this->load->View('ambulatorio/impressaorelatoriomedicoagendaconsulta', $data);
    }

    function excluiragenda($agenda_id) {
        if ($this->exame->excluiragenda($agenda_id)) {
            $mensagem = 'Sucesso ao excluir o Agenda';
        } else {
            $mensagem = 'Erro ao excluir o Agenda. Opera&ccedil;&atilde;o cancelada.';
        }

        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/exame");
    }

    function gerarelatoriomedicoordem() {
        $data['medico'] = $_POST['medicos'];
        if ($data['medico'] != '0') {
            $data['medico'] = $this->operador_m->listarCada($_POST['medicos']);
        }
        $data['procedimentos'] = $_POST['procedimentos'];
        if ($_POST['procedimentos'] != '0') {
            $data['procedimentos'] = $this->guia->selecionarprocedimentos($_POST['procedimentos']);
        }
        $data['salas'] = $_POST['salas'];
        if ($_POST['salas'] != '0') {
            $data['salas'] = $this->sala->listarsala($_POST['salas']);
        }
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->exame->listaragendaordem();
        $data['relatorioprioridade'] = $this->exame->listaragendaordemprioridade();
//        echo '<pre>';
//        var_dump($data['relatorioprioridade']);
//        echo '<pre>';
//        die;
        $this->load->View('ambulatorio/impressaorelatoriomedicoordem', $data);
    }

    function gerarelatoriomedicoagendaexame() {
        $medicos = $_POST['medicos'];
        $salas = $_POST['salas'];
        if ($medicos > 0) {
            $data['medico'] = $this->operador_m->listarCada($medicos);
        }
        if ($salas > 0) {
            $data['salas'] = $this->sala->listarsala($salas);
        }
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->exame->listaragendaexame();
        $this->load->View('ambulatorio/impressaorelatoriomedicoagendaexame', $data);
    }

    function gerarelatoriomedicoagendatodos() {
        $medicos = $_POST['medicos'];
        $salas = $_POST['salas'];
        if ($salas > 0) {
            $data['salas'] = $this->sala->listarsala($salas);
        }
        if ($medicos > 0) {
            $data['medico'] = $this->operador_m->listarCada($medicos);
        }

        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->exame->listaragendaexame();
        $this->load->View('ambulatorio/impressaorelatoriomedicoagendatodos', $data);
    }

    function gerarelatoriomedicoagendaexamefaltou() {
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
//        echo '<pre>'; 
        $data['relatorio'] = $this->exame->gerarelatoriomedicoagendaexamefaltou();
//        var_dump($data['relatorio']); die;
        $this->load->View('ambulatorio/impressaorelatoriomedicoagendaexamefaltou', $data);
    }

    function listarmultifuncaofisioterapia($args = array()) {

        $this->loadView('ambulatorio/examemultifuncaofisioterapia-lista', $args);
    }

    function autorizarsessaofisioterapia($paciente_id) {
        $data['lista'] = $this->exame->autorizarsessaofisioterapia($paciente_id);
//        var_dump($data['lista']); die;
        $data['paciente_id'] = $paciente_id;
        $this->loadView('ambulatorio/autorizarsessaofisioterapia', $data);
    }

    function autorizarsessaopsicologia($paciente_id) {
        $data['lista'] = $this->exame->autorizarsessaopsicologia($paciente_id);
        $data['paciente_id'] = $paciente_id;
        $this->loadView('ambulatorio/autorizarsessaopsicologia', $data);
    }

    function cancelartodosfisioterapia($paciente_id) {
        $lista = $this->exame->autorizarsessaofisioterapia($paciente_id);
        foreach ($lista as $item) {
            $this->exame->cancelartodosfisioterapia($item->agenda_exames_id);
        }
        $data['mensagem'] = 'Sucesso ao cancelar o Exame.';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "emergencia/filaacolhimento/novo/$paciente_id");
    }

    function cancelartodospsicologia($paciente_id) {
        $lista = $this->exame->autorizarsessaopsicologia($paciente_id);
        foreach ($lista as $item) {
            $this->exame->cancelartodospsicologia($item->agenda_exames_id);
        }
        $data['mensagem'] = 'Sucesso ao cancelar o Exame.';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "emergencia/filaacolhimento/novo/$paciente_id");
    }

    function listarmultifuncaomedicopsicologia($args = array()) {

        $this->loadView('ambulatorio/multifuncaomedicopsicologia-lista', $args);
    }

    function listarmultifuncaomedicoodontologia($args = array()) {

        $this->loadView('ambulatorio/multifuncaomedicoodontologia-lista', $args);
    }

    function listarmultifuncaomedicofisioterapia($args = array()) {

        $this->loadView('ambulatorio/multifuncaomedicofisioterapia-lista', $args);
    }

    function listarmultifuncaomedicofisioterapiareagendar($args = array()) {

        $this->loadView('ambulatorio/multifuncaomedicofisioterapiareagendar-lista', $args);
    }

    function listarmultifuncaomedicoconsulta($args = array()) {

        $this->loadView('ambulatorio/multifuncaomedicoconsulta-lista', $args);
    }

    function listarmultifuncaomedicogeral($args = array()) { 
//        $this->loadView('ambulatorio/multifuncaomedicogeral-lista', $args);modelo antigo comentei caso queiram de volta
          $this->load->View('ambulatorio/multifuncaomedicogeralnovo-lista', $args);
    }

//    function multifuncaomedicointegracao() {
//        set_time_limit(7200); // Limite de tempo de execução: 2h. Deixe 0 (zero) para sem limite
//        ignore_user_abort(true); // Não encerra o processamento em caso de perda de conexão 
//
//        $data['integracao'] = $this->laudo->listarlaudosintegracaotodos();
//        if (count($data['integracao']) > 0) {
////            echo count($data['integracao']) . "<hr>";
//            $laudos = $this->laudo->atualizacaolaudosintegracaotodos();
//            
//            foreach($laudos as $item){
//                $dados = $this->laudo->listardadoslaudogravarxml($item);
//            }
//        }
//    }

    function manterdiagnostico($args = array()){
        $this->load->helper('directory');
        $this->loadView('ambulatorio/diagnostico-lista');
    }

    function carregardiagnostico($diagnostico_id){
        $data['procedimentos'] = $this->procedimento->listarprocedimentos();

        $data['diagnostico_id'] = $diagnostico_id;
        $data['diagnostico'] = $this->exame->listardiagnosticoform($diagnostico_id);

    
        $this->loadView('ambulatorio/diagnostico-ficha', $data);
    }

    function excluirdiagnostico($diagnostico_id){
        if ($this->exame->excluirdiagnostico($diagnostico_id)) {
            $mensagem = 'Diagnostico excluido com sucesso';
        } else {
            $mensagem = 'Erro ao Excluir Diagnostico. ';
        } 
        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/exame/manterdiagnostico");
    }

    function gravardiagnostico(){
        if ($this->exame->gravardiagnostico()) {
            $mensagem = 'Sucesso ao gravar o Diagnostico';
        } else {
            $mensagem = 'Erro ao gravar o Diagnostico. Opera&ccedil;&atilde;o cancelada.';
        }
        $this->session->set_flashdata('message', $mensagem);
        // redirect(base_url() . "ambulatorio/empresa/listartemplatesconsulta");
    }

    function procedimentosdiagnostico($diagnostico_id){
        $data['diagnostico_id'] = $diagnostico_id;
        $data['pegarprocedimentos'] = $this->procedimento->pegarprocedimentos($diagnostico_id);
        foreach($data['pegarprocedimentos'] as $item){
            $procedimento_id  = json_decode($item->procedimentos);
         }
        $data['procedimentos'] = $this->procedimento->listarprocedimentosdiagnostico($procedimento_id, 'true');
        $data['listar_procedimentos'] = $this->procedimento->listarprocedimentosdiagnostico($procedimento_id, 'false');

        $this->load->View('ambulatorio/procedimentosdiagnostico-form', $data);
    }

    function excluiprocedimentodiagnostico($procedimento_tuss_id, $diagnostico_id){
        $data['pegarprocedimentos'] = $this->procedimento->pegarprocedimentos($diagnostico_id);
        foreach($data['pegarprocedimentos'] as $item){
            $arrayprocedimento  = json_decode($item->procedimentos);
         }
            // Excluido Procedimento do Array
            $key = array_search($procedimento_tuss_id, $arrayprocedimento);
            if($key!==false){
                unset($arrayprocedimento[$key]);
            }

            // Como o Json Encode tava dando um resultado não esperado
            // Criei essa Logica para salvar no mesmo padrão no Banco de dados
            $arrayprocedimento2 = '[';
            $i = 0;
            foreach($arrayprocedimento as $item){
                $i++;
                $arrayprocedimento2 .= '"'.$item.'"';

                if ($i != count($arrayprocedimento)) {  
                    $arrayprocedimento2 .= ","; 
                }
            }
            $arrayprocedimento2 .= ']';

        $this->exame->procedimentosdiagnostico($arrayprocedimento2, $diagnostico_id);

        redirect(base_url() . "ambulatorio/exame/procedimentosdiagnostico/$diagnostico_id");
    }

    function adicionarprocedimentodiagnostico($diagnostico_id){
        $data['pegarprocedimentos'] = $this->procedimento->pegarprocedimentos($diagnostico_id);
        foreach($data['pegarprocedimentos'] as $item){
            $arrayprocedimento  = json_decode($item->procedimentos);
         }

         $arrayprocedimento = array_merge($arrayprocedimento, $_POST['procedimentos']);

         // Como o Json Encode tava dando um resultado não esperado
            // Criei essa Logica para salvar no mesmo padrão no Banco de dados
            $arrayprocedimento2 = '[';
            $i = 0;
            foreach($arrayprocedimento as $item){
                $i++;
                $arrayprocedimento2 .= '"'.$item.'"';

                if ($i != count($arrayprocedimento)) {  
                    $arrayprocedimento2 .= ","; 
                }
            }
            $arrayprocedimento2 .= ']';

        $this->exame->procedimentosdiagnostico($arrayprocedimento2, $diagnostico_id);
        redirect(base_url() . "ambulatorio/exame/procedimentosdiagnostico/$diagnostico_id");
    }

    function carregardiagnosticojson($diagnostico_id) {
        $data['diagnostico_id'] = $diagnostico_id;
        $data['diagnostico'] = $this->exame->listardiagnosticoform($diagnostico_id);
//        var_dump($data['impressao']); die;
        if(count($data['diagnostico']) > 0){
            echo $data['diagnostico'][0]->diagnostico;
        }else{
            echo json_encode(array());
        }
        
    }


    function reagendarespecialidade() {
//        var_dump($_POST);
//        die;
        set_time_limit(7200); // Limite de tempo de execução: 2h. Deixe 0 (zero) para sem limite
        ignore_user_abort(true); // Não encerra o processamento em caso de perda de conexão 

        $data['integracao'] = $this->laudo->listarlaudosintegracaotodos();
        if (count($data['integracao']) > 0) {
//            echo count($data['integracao']) . "<hr>";
            $this->laudo->atualizacaolaudosintegracaotodos();
        }
    }

    function listarmultifuncaomedico($args = array()) {
//        $data['integracao'] = $this->laudo->listarlaudosintegracaotodos();
//        if (count($data['integracao']) > 0) {
////            echo count($data['integracao']) . "<hr>";
//            $this->laudo->atualizacaolaudosintegracaotodos();
//        }
        /* A integraçao agora e feita por AJAX na view abaixo */
        $this->loadView('ambulatorio/multifuncaomedico-lista', $args);
    }

    function listarmultifuncaomedicolaboratorial($args = array()) {

        $this->loadView('ambulatorio/multifuncaomedicolaboratorial-lista', $args);
    }

    function faturamentoexame() {

        $this->loadView('ambulatorio/faturamentoexame');
    }

    function faturamentomanual($args = array()) {

        $this->loadView('ambulatorio/faturamentomanual', $args);
    }

    function bardeiradestatus($args = array()) {

        $this->loadView('ambulatorio/bardeiradestatus-lista', $args);
    }

    function conveniobardeirasstatus($bardeira_id){
        $data['bardeira_id'] = $bardeira_id;
        $data['convenio'] = $this->convenio->listardados();
        $this->loadView('ambulatorio/conveniobardeirasstatus-lista', $data);
    }

    function novoconveniobardeirastatus($bardeira_id = null ,$convenio_id = null){
        $data['convenio'] = $this->convenio->listardados();
        $data['procedimento'] = $this->procedimentoplano->listarprocedimentobardeira();
        $data['grupo'] = $this->procedimentoplano->listargrupo();
        $data['bardeira'] = $this->exame->listarbardeiras($bardeira_id);
        $data['convenio_id'] = $convenio_id;
        $data['bardeira_id'] = $bardeira_id;
        $this->loadView('ambulatorio/procedimentoconveniobardeirastatus-form', $data);
    }

    function gravarbardeirastatusconvenio(){
        $procedimentoplano_tuss_id = $this->exame->gravarbardeirastatusconvenio();
        if ($procedimentoplano_tuss_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar o Bardeira Status. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar o Bardeira Status.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function procedimentoconveniobardeirastatus($bardeira_id, $convenio_id){
        $data['convenio_id'] = $convenio_id;
        $data['bardeira_id'] = $bardeira_id;
        $data['grupo'] = $this->procedimento->listargrupos();
        $this->loadView('ambulatorio/procedimentoconveniobardeirastatus-lista', $data);
    }

    function excluirbardeirastatus(){
        foreach ($_POST['percentual'] as $key => $value) {
            $procedimento_bardeira_status_convenio_id = $key;
            $this->exame->excluirbardeirastatus($procedimento_bardeira_status_convenio_id);
        }
        foreach ($_POST['percentual2'] as $key => $value) {
            $procedimento_bardeira_status_id = $key;
            $this->exame->excluirbardeirastatusprocedimento($procedimento_bardeira_status_id);
        }

        $mensagem = 'Sucesso ao excluir as Bardeiras de Status';
        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function excluirbardeirastatusconvenio(){
        $bardeira_id = $_POST['bardeira_id'];

        foreach ($_POST['convenio'] as $key => $value) {
            $convenio_id = $key;
            $this->exame->excluirbardeirastatusconvenio($bardeira_id, $convenio_id);
        }

        $mensagem = 'Sucesso ao excluir os Percentuais medicos associados a esse convenio';
        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function gravarguiacirurgica() {
        $ambulatorio_guia = $this->guia->gravarguiacirurgica();

        if ($ambulatorio_guia == "-1") {
            $data['mensagem'] = 'Erro ao gravar Guia. Opera&ccedil;&atilde;o cancelada.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exame/faturamentomanual");
        } else {
            $data['mensagem'] = 'Sucesso ao gravar Guia.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exame/guiacirurgicaitens/$ambulatorio_guia");
        }
    }

    function gravarguiaambulatorial() {
//        var_dump($_POST);
//        die;
        $ambulatorio_guia = $this->guia->gravarguiacirurgica();

        if ($ambulatorio_guia == "-1") {
            $data['mensagem'] = 'Erro ao gravar Guia. Opera&ccedil;&atilde;o cancelada.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exame/faturamentomanual");
        } else {
            $data['mensagem'] = 'Sucesso ao gravar Guia.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exame/guiacirurgicaitens/$ambulatorio_guia");
        }
    }

    function guiacirurgicaitens($guia_id) {
        $data['guia_id'] = $guia_id;
        $data['guia'] = $this->guia->instanciarguia($guia_id);
        $data['procedimentos'] = $this->exame->listarprocedimentocirurgicoconvenio($data['guia'][0]->convenio_id);
        $data['procedimentos_cadastrados'] = $this->exame->listarprocedimentosadcionados($guia_id);
        $this->loadView('ambulatorio/guiacirurgicaitens', $data);
    }

    function carregarguiacirurgica($guia_id = null) {

        $data['guia'] = $this->guia->instanciarguia($guia_id);
        $data['hospitais'] = $this->exame->listarhospitais();
        $data['convenios'] = $this->guia->listarconvenios();
        $this->loadView('ambulatorio/novaguiacirurgica-form', $data);
    }

    function carregarguiaambulatorial($guia_id = null) {

        $data['guia'] = $this->guia->instanciarguia($guia_id);
        $data['hospitais'] = $this->exame->listarhospitais();
        $data['convenios'] = $this->guia->listarconvenios();
        $this->loadView('ambulatorio/novaguiaambulatorio-form', $data);
    }

    function fecharfinanceiro($valor_contrato) {
//        var_dump($_POST);        
//        die;
        $financeiro = $this->exame->fecharfinanceiro($valor_contrato);
        if ($financeiro == "-1") {
            $data['mensagem'] = 'Erro ao fechar financeiro. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao fechar financeiro.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/faturamentoexame", $data);
    }

    function fecharfinanceirointernacao($valor_contrato) {
//        var_dump($valor_contrato);die;        
        $financeiro = $this->exame->fecharfinanceirointernacao($valor_contrato);
        if ($financeiro == "-1") {
            $data['mensagem'] = 'Erro ao fechar financeiro. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao fechar financeiro.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/faturamentoexame", $data);
    }

    function autorizarsessao($agenda_exames_id, $paciente_id, $guia_id) {
//        var_dump($agenda_exames_id); die;
        $home_care = $this->exame->procedimentohomecare($agenda_exames_id);
        $intervalo = $this->exame->verificadiasessaohomecare($agenda_exames_id);
        if ($intervalo == 0) {
            $this->exame->autorizarsessao($agenda_exames_id);
            $data['lista'] = $this->exame->autorizarsessaofisioterapia($paciente_id);
            redirect(base_url() . "ambulatorio/guia/impressaoficha/$paciente_id/$guia_id/$agenda_exames_id");
        } else {
            $data['mensagem'] = 'Essa sessao só poderá ser autorizada amanhã.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exame/autorizarsessaofisioterapia/$paciente_id/");
        }
    }

    function autorizarsessaocadapsicologia($agenda_exames_id, $paciente_id, $guia_id) {
        $this->exame->autorizarsessao($agenda_exames_id);
        $data['lista'] = $this->exame->autorizarsessaopsicologia($paciente_id);
        redirect(base_url() . "ambulatorio/guia/impressaoficha/$paciente_id/$guia_id/$agenda_exames_id");
    }

    function faturamentomanuallista() {
        $data['convenio'] = $_POST['convenio'];
        $data['tipo'] = $_POST['tipo'];

        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        } else {
            $data['convenios'] = 0;
        }

        $data['listarinternacao'] = array();
        if ($_POST['tipo'] == 'AMBULATORIAL') {
            $data['listar'] = $this->exame->listarguiafaturamentomanual();
        } else {
            $data['listar'] = $this->exame->listarguiafaturamentomanualcirurgico();
            $data['listarinternacao'] = $this->internacao_m->listarguiafaturamentomanualinternacaoconvenio();
        }

//        echo "<pre>";
//        var_dump($data['listarinternacao']);
//        die;

        $this->loadView('ambulatorio/faturamentomanual-lista', $data);
    }

    function faturaramentomanualinternacao($internacao_procedimentos_id, $internacao_id) {

//        die('morre');
        $data['internacao'] = $this->exame->listarfaturamentomanualinternacao2($internacao_procedimentos_id);
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data["valor"] = (float) $data['internacao'][0]->valor_total;
        $data['internacao_id'] = $internacao_id;
        $data['internacao_procedimentos_id'] = $internacao_procedimentos_id;
        $this->load->View('ambulatorio/faturaramentomanualinternacao-form', $data);
    }

    function faturaramentomanualinternacaoconvenio($internacao_procedimentos_id, $internacao_id) {

//        die('morre');
        $data['internacao'] = $this->exame->listarfaturamentomanualinternacao2($internacao_procedimentos_id);
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data["valor"] = (float) $data['internacao'][0]->valor_total;
        $data['internacao_id'] = $internacao_id;
        $data['internacao_procedimentos_id'] = $internacao_procedimentos_id;
        $this->load->View('ambulatorio/faturamentomanualinternacaoconvenio-form', $data);
    }

    function gravarfaturaramentomanualinternacao() {

        $_POST['valor1'] = (float) str_replace(',', '.', $_POST['valor1']);
        $_POST['valor2'] = (float) str_replace(',', '.', $_POST['valor2']);
        $_POST['valor3'] = (float) str_replace(',', '.', $_POST['valor3']);
        $_POST['valor4'] = (float) str_replace(',', '.', $_POST['valor4']);
        $resulta = $_POST['valortotal'];
        if ($resulta == "0.00") {
            $ambulatorio_guia_id = $this->exame->gravarfaturaramentomanualinternacao();
            if ($ambulatorio_guia_id == "-1") {
                $data['mensagem'] = 'Erro ao gravar faturamento. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao gravar faturamento.';
            }
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);
    }

    function gravarfaturaramentointernacaoconvenio($internacao_procedimentos_id, $internacao_id) {



        $ambulatorio_guia_id = $this->exame->gravarfaturaramentointernacaoconvenio($internacao_procedimentos_id, $internacao_id);
        if ($ambulatorio_guia_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar faturamento. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar faturamento.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);
    }

    function gravarfaturaramentointernacaoconveniotodos($internacao_id) {


        $ambulatorio_guia_id = $this->exame->gravarfaturaramentointernacaoconveniotodos($internacao_id);
        if ($ambulatorio_guia_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar faturamento. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar faturamento.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);
    }

    function faturamentoexamelista() {
        $data['convenio'] = $_POST['convenio'];
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        } else {
            $data['convenios'] = 0;
        }
        $data['listarinternacao'] = array();
        $data['listar'] = array();
        if ($_POST['tipo'] == 'AMBULATORIAL') {
            $data['listar'] = $this->exame->listarguiafaturamento();
        } else if($_POST['tipo'] == 'CIRURGICO'){
            $data['listarinternacao'] = $this->internacao_m->listarguiafaturamentomanualinternacaoconvenio();
        }else{
            $data['listarcirurgico'] = $this->centrocirurgico_m->listarguiafaturamentomanualcentrocirurgicoconvenio();
           // echo '<pre>'; print_r($data['listarcirurgico']); die;
        }          
        $this->loadView('ambulatorio/faturamentoexame-lista', $data);
    }

    function faturarconveniocirurgico($solicitacao_cirurgia_procedimento_id) {
        $data['cirurgico'] = $this->centrocirurgico_m->listarfaturamentomanualcirurgicoconvenio($solicitacao_cirurgia_procedimento_id);
        $this->load->View('ambulatorio/faturarconveniocirurgico-form', $data);
    }

    function desfazerconveniocirurgico($solicitacao_cirurgia_procedimento_id, $agenda_exames_id, $solicitacao_cirurgia_id) {
        $this->centrocirurgico_m->desfazerfaturamentoconveniocirurgia($solicitacao_cirurgia_procedimento_id, $agenda_exames_id);
        $this->centrocirurgico_m->verificarfaturamentocirurgico($solicitacao_cirurgia_id);
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);
    }

    function gravarfaturadoconveniocirurgia($solicitacao_cirurgia_id) {
        $this->centrocirurgico_m->gravarfaturamentoconveniocirurgia();
        $this->centrocirurgico_m->verificarfaturamentocirurgico($solicitacao_cirurgia_id);
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);
    }

    function faturartodosfaturamentocirurgico() {
        $this->centrocirurgico_m->gravarfaturartodosfaturamentocirurgico();
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function fecharfinanceirocirurgico($valor_contrato) {
           //var_dump($valor_contrato);die;

                $financeiro = $this->centrocirurgico_m->fecharfinanceirocirurgico($valor_contrato);
                if ($financeiro == "-1") {
                    $data['mensagem'] = 'Erro ao fechar financeiro. Opera&ccedil;&atilde;o cancelada.';
                } else {
                    $data['mensagem'] = 'Sucesso ao fechar financeiro.';
                }
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "ambulatorio/exame/faturamentoexame", $data);
            }


    function faturamentoexamexml($args = array()) {

        $this->loadView('ambulatorio/faturamentoexamexml-form', $args);
    }

    function faturamentoexamexmlcirurgico($args = array()) {

        $this->loadView('ambulatorio/faturamentoexamexmlcirurgico-form', $args);
    }

    function listarexamerealizando($limite = 25) {
        $data["limite_paginacao"] = $limite;
        $this->loadView('ambulatorio/examerealizando-lista', $data);
    }

    function listarexamependente($args = array()) {
        $this->loadView('ambulatorio/examependente-lista', $args);
    }

    function painelrecepcao($args = array()) {

        $this->loadView('ambulatorio/painelrecepcao-lista', $args);
    }

    function faturaramentomanualguia($guia_id, $paciente_id) {
        $data['guia_id'] = $guia_id;
        $data['paciente_id'] = $paciente_id;
        $data['guia'] = $this->guia->instanciarguia($guia_id);
        $data['procedimentos'] = $this->centrocirurgico_m->listarprocedimentosguiacirurgica($guia_id);
        $data['equipe'] = $this->centrocirurgico_m->listarequipecirurgicaoperadores($data['guia'][0]->equipe_id);
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $this->loadView('ambulatorio/guiafaturamentomanual-form', $data);
    }

    function faturarguia($guia_id, $paciente_id) { 
        $data['guia_id'] = $guia_id;
        $data['paciente_id'] = $paciente_id;
        $data['convenios'] = $this->convenio->listarconvenionaodinheiro();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->login->listar();
        $data['exames'] = $this->exame->listarexamesguiafaturar($guia_id);
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['empresaPermissao'] = $this->guia->listarempresapermissoes();
        $data['listargrauparticipacao'] = $this->guia->listargrauparticipacao();
        $data['grupos'] = $this->procedimento->listargruposatendimento();
        $data['tipos_cirurgia'] = $this->guia->listartodascirurgia();
        $data['indicacao_acidente'] = $this->guia->listartodasinidicacaoacidente();
        $data['carater'] = $this->guia->listartodoscarater();
        $data['incluir_atendimento'] = $this->guia->listartodosincluir();
        $this->loadView('ambulatorio/guiafaturamento-form', $data);
    }

    function procedimentosinternacao($internacao_id, $paciente_id) {
        $data['internacao_id'] = $internacao_id;
        $data['paciente_id'] = $paciente_id;
        $data['convenios'] = $this->convenio->listarconvenionaodinheiro();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->login->listar();
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['internacao'] = $this->internacao_m->listardadosinternacao($internacao_id);
//        var_dump($data['internacao'] ); die;
        $data['procedimentos'] = $this->exame->listarprocedimentosinternacao($internacao_id);
//        var_dump($data['procedimentos']); die;
        $this->loadView('ambulatorio/procedimentosinternacao-form', $data);
    }

    function gravarprocedimentosinternacao() {

        $internacao_id = $_POST['txtinternacao_id'];
        $paciente_id = $_POST['txtpaciente_id'];
        $this->exame->gravarprocedimentosinternacao();
        redirect(base_url() . "ambulatorio/exame/procedimentosinternacao/$internacao_id/$paciente_id");
    }

    function gravarprocedimentosfaturamentototal() {
        $guia_id = null;

        $data['guia'] = $this->guia->instanciarguia($guia_id);
        $this->guia->gravarfaturamentoprocedimentostotal();
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);
    }

    function faturartodosfaturamento() {
        // var_dump($_POST); die;
        $this->guia->gravarfaturartodosfaturamento();
        // $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function excluirprocedimentointernacao($internacao_procedimentos_id, $internacao_id, $paciente_id) {

        $this->exame->excluirprocedimentointernacao($internacao_procedimentos_id);
        $mensagem = 'Procedimento excluído com sucesso';
        redirect(base_url() . "ambulatorio/exame/procedimentosinternacao/$internacao_id/$paciente_id");
    }

    function faturarguiamatmed($guia_id, $paciente_id) {
        $data['guia_id'] = $guia_id;
        $data['paciente_id'] = $paciente_id;
        $data['convenios'] = $this->convenio->listarconvenionaodinheiro();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->login->listar();
        $data['exames'] = $this->exame->listarexamesguiamatmed($guia_id);
        $data['medicos'] = $this->operador_m->listarmedicos();
//        var_dump($data['exames']); die;
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $this->loadView('ambulatorio/guiafaturamentomatmed-form', $data);
    }

    function faturarguiamanual($paciente_id = null) {
        if ($paciente_id == null) {
            $paciente_id = $_POST['txtpacienteid'];
        }
//        var_dump($_POST); die;

        $data['paciente_id'] = $paciente_id;
        $data['salas'] = $this->guia->listarsalas();
        $data['convenios'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->login->listar();
        $data['exames'] = $this->exame->listarexamesguiamanual($paciente_id);
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $this->loadView('ambulatorio/guiafaturamentoambulatorial-form', $data);
    }

    function gravarprocedimentosfaturamentomanual($paciente_id) {
//        var_dump($_POST); die;

        $resultadoguia = $this->exame->listarguiafaturamentomanualambulatorial($paciente_id);
        if ($resultadoguia == null) {
            $ambulatorio_guia = $this->exame->gravarguiamanual($paciente_id);
        } else {
            $ambulatorio_guia = $resultadoguia[0]->ambulatorio_guia_id;
        }

        $procedimentopercentual = $_POST['procedimento1'];
        $medicopercentual = $_POST['medicoagenda'];
        $percentual = $this->guia->percentualmedicoconvenioexames($procedimentopercentual, $medicopercentual);
        if (count($percentual) == 0) {
            $percentual = $this->guia->percentualmedicoprocedimento($procedimentopercentual, $medicopercentual);
        }

//        var_dump($percentual); die;

        $this->exame->gravarexamesfaturamentomanual($ambulatorio_guia, $percentual);

        redirect(base_url() . "ambulatorio/exame/faturarguiamanual/$paciente_id");
    }

    function estoqueguia($agenda_exames_id) {

        $data['procedimento'] = $this->procedimento->listarprocedimentos();
        $data['agenda_exames_id'] = $agenda_exames_id;
        $this->loadView('ambulatorio/estoqueguia-form', $data);
    }

    function acompanhamentodocumento($filtro = -1, $inicio = 0) {
        $this->loadView('ambulatorio/acompanhamentodocumento-lista');
    }

    function novoacompanhamentodocumento($acompanhamento_quest_id) {

        $data['acompanhamento_quest_id'] = $acompanhamento_quest_id;
        $this->loadView('ambulatorio/acompanhamentoquest-form', $data);
    }

    function gravaracompanhamentoquest($acompanhamento_quest_id) {
        // echo '<pre>';
        // var_dump($_FILES); die;
        $type = explode('.', $_FILES['userfile']['name']);
        $nome = str_replace(' ', '_', $_POST['nome']);
        $nome_caminho = $nome . '.' . $type[1];
        $quest_id = $this->exame->gravaracompanhamentoquest($acompanhamento_quest_id, $nome, $nome_caminho);

        if (!is_dir("./upload/acompquest")) {
            mkdir("./upload/acompquest");
            $destino = "./upload/acompquest";
            chmod($destino, 0777);
        }

        if (!is_dir("./upload/acompquest/$quest_id")) {
            mkdir("./upload/acompquest/$quest_id");
            $destino = "./upload/acompquest/$quest_id";
            chmod($destino, 0777);
        }

        $_FILES['userfile']['name'] = $nome . '.' . $type[1];
        //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
        $config['upload_path'] = "./upload/acompquest/$quest_id/";
        $config['allowed_types'] = 'gif|jpg|png|jpeg|txt|xlsx|xls|docx|pdf|doc|odt|bmp';
        $config['max_size'] = '0';
        $config['overwrite'] = TRUE;
        $config['encrypt_name'] = FALSE;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $error = null;
            $data = array('upload_data' => $this->upload->data());
        }

        $data['mensagem'] = 'Gravado com sucesso';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/acompanhamentodocumento");
    }

    function excluiracompquest($acompanhamento_quest_id) {

        $quest_id = $this->exame->excluiracompquest($acompanhamento_quest_id);
        $data['mensagem'] = 'Excluido com sucesso';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/acompanhamentodocumento");
    }

    function preparosala($paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id) {
        $data['salas'] = $this->exame->listarsalas();
        $data['medico_id'] = $this->exame->listarmedicoagenda($agenda_exames_id);
        $data['agenda_exames_nome_id'] = $this->exame->listarsalaagenda($agenda_exames_id);
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['tecnicos'] = $this->operador_m->listartecnicos();
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['guia_id'] = $guia_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $this->loadView('ambulatorio/examepreparo-form', $data);
    }

    function enviarsalaatendimento($agenda_exames_id) {
        $this->exame->gravarexamepreparo($agenda_exames_id);
        $data['mensagem'] = 'Enviado para a sala de atendimento com sucesso.';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/listarsalaspreparo");
    }

    function examesala($paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa'] = $this->empresa->listarempresatoten($empresa_id);

        $endereco = @$data['empresa'][0]->endereco_toten;
        $data['endereco'] = $endereco;


//        if ($endereco != '') {
//            $senha = $this->exame->listarultimasenhatoten();
//            $idFila = $senha[0]->id;
//            $paciente = $this->exame->listarpacientetoten($paciente_id);
//            $paciente_nome = $paciente[0]->nome;
//            if ($paciente[0]->cpf != '') {
//                $paciente_cpf = $paciente[0]->cpf;
//            } else {
//                $paciente_cpf = 'null';
//            }
//
//            $medico = $this->exame->listarmedicoagendatoten($agenda_exames_id);
//            $nome_medico = $medico[0]->nome;
//            $medico_id = $medico[0]->operador_id;
//            $data['url'] = "$endereco/webService/telaAtendimento/enviarFicha/$idFila/$paciente_nome/$paciente_cpf/$medico_id/$nome_medico/1/true";
//        }else{
        $data['url'] = '';
//        }
//        var_dump($data['url']);
//        die;

        $data['salas'] = $this->exame->listarsalas();
        $data['medico_id'] = $this->exame->listarmedicoagenda($agenda_exames_id);
        $data['agenda_exames_nome_id'] = $this->exame->listarsalaagenda($agenda_exames_id);
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['tecnicos'] = $this->operador_m->listartecnicos();
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['guia_id'] = $guia_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $this->loadView('ambulatorio/exameespera-form', $data);
    }

    function examepacientedetalhes($paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id) {
        $data['guia'] = $this->exame->listarguia($agenda_exames_id);
        $data['exames'] = $this->exame->listarexamesguias($guia_id);
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['guia_id'] = $guia_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $this->load->View('ambulatorio/examepacientedetalhe-form', $data);
    }

    function agendaauditoria($agenda_exames_id) {
        $data['guia'] = $this->exame->listaragendaauditoria($agenda_exames_id);
        $this->load->View('ambulatorio/agendaauditoria-form', $data);
    }

    function agendadoauditoria($agenda_exames_id) {
        $data['exclusao'] = $this->exame->listarexclusaoagendamento($agenda_exames_id);
//        var_dump($data['exclusao']); die;
        $data['salas'] = $this->exame->listarsalas();
        $data['guia'] = $this->exame->listaragendadoauditoria($agenda_exames_id);
        $this->load->View('ambulatorio/agendadoauditoria-form', $data);
    }

    function agendamedicocurriculo($medico_agenda) {
        $data['guia'] = $this->exame->listaragendamedicocurriculo($medico_agenda);
//        var_dump($data['guia']); die;
        $this->load->View('ambulatorio/agendamedicocurriculo-form', $data);
    }

    function trocarmedico($agenda_exames_id) {
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['medico_atual'] = $this->exame->buscarmedicotroca($agenda_exames_id);
        $data['medicos'] = $this->exame->listarmedico();
        $data['tipo'] = 1; // exame ou consulta ou fisio
        $this->load->View('ambulatorio/trocarmedico-form', $data);
    }

    function mantergastos($args = array()){
        $this->loadView('ambulatorio/mantergastos-lista', $args);
    }

    function relatoriomantergastos(){
            $data['operadores'] = $this->exame->listaroperadores();
            $data['empresa'] = $this->guia->listarempresas();
            $data['gerente_relatorio_financeiro'] = $this->guia->gerenterelatoriofinanceiro();
            $this->loadView('ambulatorio/relatoriomantergastos', $data);
        
    }

    function gerarelatoriomantergastos(){
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['operador'] = $this->operador_m->listaroperadorarray(@$_POST['operador']);
        $data['empresa'] = $this->guia->listarempresa(@$_POST['empresa']);

        $data['operadores'] = $this->exame->listaroperadoresrelatoriogasto();

        if ($_POST['gerar'] == "pdf") {
            $this->load->plugin('mpdf');
            $texto = $this->load->View('ambulatorio/impressaorelatoriomantergastos', $data, true);
            $cabecalhopdf = '';
            $rodapepdf = '';
            $nomepdf = "relatoriopdf " . date("d/m/Y H:i:s") . ".pdf";
            downloadpdf($texto, $nomepdf, $cabecalhopdf, $rodapepdf);
        }else if($_POST['gerar'] == "planilha"){
            $html = $this->load->View('ambulatorio/impressaorelatoriomantergastos', $data, true);
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-type: application/x-msexcel");
            header("Content-Disposition: attachment; filename=\"Relatorio.xls\"");
            header("Content-Description: PHP Generated Data");
            // Envia o conteúdo do arquivo
            echo $html;
            exit;
        }else{
            $this->load->View('ambulatorio/impressaorelatoriomantergastos', $data);
        }

    }

    function carregargastooperador($manter_gasto_id, $status){
        $data['obj'] = $this->exame->listargastooperador($manter_gasto_id);
        // echo '<pre>';
        // print_r($data['obj']);
        $data['status'] = $status;
        $this->loadView('ambulatorio/mantergastos-form', $data);
    }

    function gravargastosoperador(){
       $gasto_operador_id = $this->exame->gravargastosoperador();
        if ($gasto_operador_id == "-1") {
            $data['mensagem'] = 'Erro ao cadastrar Gasto. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao Gravar o Gasto.';
            $this->importararquivogastooperador($gasto_operador_id);
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/mantergastos");
    }

    function importararquivogastooperador($gasto_operador_id){


        if (!is_dir("./upload/gastooperador")) {
            mkdir("./upload/gastooperador");
            chmod("./upload/gastooperador", 0777);
        }

        for ($i = 0; $i < count($_FILES['arquivos']['name']); $i++) {
            $_FILES['userfile']['name'] = $_FILES['arquivos']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['arquivos']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['arquivos']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['arquivos']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['arquivos']['size'][$i];

            if (!is_dir("./upload/gastooperador/$gasto_operador_id")) {
                mkdir("./upload/gastooperador/$gasto_operador_id");
                $destino = "./upload/gastooperador/$gasto_operador_id";
                chmod($destino, 0777);
            }

            //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
            $config['upload_path'] = "./upload/gastooperador/" . $gasto_operador_id . "/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar';
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
        }
    }

    function anexararquivogastooperador($manter_gasto_id){
        if (!is_dir("./upload/gastooperador")) {
            mkdir("./upload/gastooperador");
            chmod("./upload/gastooperador", 0777);
        }

        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/gastooperador/$manter_gasto_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['manter_gasto_id'] = $manter_gasto_id;
        $this->loadView('ambulatorio/importacao-gastooperador', $data);
    }

    function importargastooperador() {
        $manter_gasto_id = $_POST['paciente_id'];
//        $data = $_FILES['userfile'];
//        var_dump($data);
//        die;
        if (!is_dir("./upload/gastooperador")) {
            mkdir("./upload/gastooperador");
            chmod("./upload/gastooperador", 0777);
        }

        for ($i = 0; $i < count($_FILES['arquivos']['name']); $i++) {
            $_FILES['userfile']['name'] = $_FILES['arquivos']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['arquivos']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['arquivos']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['arquivos']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['arquivos']['size'][$i];

            if (!is_dir("./upload/gastooperador/$manter_gasto_id")) {
                mkdir("./upload/gastooperador/$manter_gasto_id");
                $destino = "./upload/gastooperador/$manter_gasto_id";
                chmod($destino, 0777);
            }

            //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
            $config['upload_path'] = "./upload/gastooperador/" . $manter_gasto_id . "/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar';
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
        }

        $data['manter_gasto_id'] = $manter_gasto_id;
        redirect(base_url() . "ambulatorio/exame/anexararquivogastooperador/$manter_gasto_id/");
    }

    function ecluirarquivogastooperador($manter_gasto_id, $value){
        unlink("./upload/gastooperador/$manter_gasto_id/$value");
        redirect(base_url() . "ambulatorio/exame/anexararquivogastooperador/$manter_gasto_id");
    }

    function excluirgastooperador($manter_gasto_id){
        $verificar = $this->exame->excluirgastooperador($manter_gasto_id);
         if ($verificar == "-1") {
             $data['mensagem'] = 'Erro ao excluir Gasto. Opera&ccedil;&atilde;o cancelada.';
         } else {
             $data['mensagem'] = 'Sucesso ao Excluir o Gasto.';
         }
 
         $this->session->set_flashdata('message', $data['mensagem']);
         redirect(base_url() . "ambulatorio/exame/mantergastos");
     }

    function guiacirurgicafaturamento($guia) {

        $data['guia_id'] = $guia;
        $data['guia'] = $this->guia->instanciarguia($guia);
        $data['procedimentos'] = $this->centrocirurgico_m->listarprocedimentosguiacirurgica($guia);
        $data['equipe'] = $this->centrocirurgico_m->listarequipecirurgicaoperadores($data['guia'][0]->equipe_id);
        $this->loadView('centrocirurgico/guiacirurgicafaturamento-lista', $data);
    }

    function trocarmedicoconsulta($agenda_exames_id) {
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['medico_atual'] = $this->exame->buscarmedicotrocaconsulta($agenda_exames_id);
        $data['medicos'] = $this->exame->listarmedico();
        $data['tipo'] = 2; // exame ou consulta ou fisio        
        $this->load->View('ambulatorio/trocarmedico-form', $data);
    }

    function gravartrocarmedico() {

        $this->exame->trocarmedico();
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function gravaralterarsala() {

        $this->exame->gravaralterarsala();
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function gravarpacientedetalhes() {

        $paciente_id = $_POST['paciente_id'];
        $procedimento_tuss_id = $_POST['procedimento_tuss_id'];
        $guia_id = $_POST['guia_id'];
        $agenda_exames_id = $_POST['agenda_exames_id'];
        $this->exame->gravarpacientedetalhes();
        $data['mensagem'] = 'Guia atualizada com sucesso.';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/examesala/$paciente_id /$procedimento_tuss_id /$guia_id/$agenda_exames_id");
    }

    function examesalatodos($paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id) {

        $data['salas'] = $this->exame->listarsalas();
        $data['grupo'] = $this->exame->listargrupo($agenda_exames_id);
        $data['medico_id'] = $this->exame->listarmedicoagenda($agenda_exames_id);
        $data['agenda_exames_nome_id'] = $this->exame->listarsalaagenda($agenda_exames_id);
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['tecnicos'] = $this->operador_m->listartecnicos();
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['guia_id'] = $guia_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $this->loadView('ambulatorio/exameesperatodos-form', $data);
    }

    function examesalatodosfiladecaixa($paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id) {

        $data['salas'] = $this->exame->listarsalas();
        $data['grupo'] = $this->exame->listargrupo($agenda_exames_id);
        $data['medico_id'] = $this->exame->listarmedicoagenda($agenda_exames_id);
        $data['agenda_exames_nome_id'] = $this->exame->listarsalaagenda($agenda_exames_id);
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['tecnicos'] = $this->operador_m->listartecnicos();
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['guia_id'] = $guia_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $this->loadView('ambulatorio/exameesperatodosfiladecaixa-form', $data);
    }

    function esperacancelamento($agenda_exames_id, $paciente_id, $procedimento_tuss_id) {
        $data['motivos'] = $this->motivocancelamento->listartodos();
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['pegamedico'] = $this->exame->pegarmedicocancelamento($agenda_exames_id);
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $this->load->View('ambulatorio/esperacancelamento-form', $data);
    }

    function matmedcancelamento($agenda_exames_id, $paciente_id, $procedimento_tuss_id) {
        $data['motivos'] = $this->motivocancelamento->listartodos();
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $this->loadView('ambulatorio/matmedcancelamento-form', $data);
    }

    function pacotecancelamento($sala_id, $guia_id, $paciente_id, $procedimento_agrupador_id) {
        $data['motivos'] = $this->motivocancelamento->listartodos();
        $data['guia_id'] = $guia_id;
        $data['sala_id'] = $sala_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_agrupador_id'] = $procedimento_agrupador_id;
        $this->loadView('ambulatorio/cancelamentopacote-form', $data);
    }

    function esperacancelamentopacote($guia_id, $paciente_id, $procedimento_agrupador_id) {
        $data['motivos'] = $this->motivocancelamento->listartodos();
        $data['guia_id'] = $guia_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_agrupador_id'] = $procedimento_agrupador_id;
        $this->loadView('ambulatorio/esperacancelamentopacote-form', $data);
    }

    function atendimentocancelamentopacote($guia_id, $paciente_id, $procedimento_agrupador_id) {
        $data['motivos'] = $this->motivocancelamento->listartodos();
        $data['guia_id'] = $guia_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_agrupador_id'] = $procedimento_agrupador_id;
        $this->loadView('ambulatorio/atendimentocancelamentopacote-form', $data);
    }

    function guiacancelamento($agenda_exames_id, $paciente_id, $procedimento_tuss_id) {
        $data['motivos'] = $this->motivocancelamento->listartodos();
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $this->loadView('ambulatorio/guiacancelamento-form', $data);
    }

    function examecancelamentoencaixe($exames_id, $sala_id, $agenda_exames_id, $paciente_id, $procedimento_tuss_id) {
        $data['motivos'] = $this->motivocancelamento->listartodos();
        $data['exames_id'] = $exames_id;
        $data['sala_id'] = $sala_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $this->loadView('ambulatorio/examecancelamentoencaixe-form', $data);
    }

    function examecancelamento($exames_id, $sala_id, $agenda_exames_id, $paciente_id, $procedimento_tuss_id, $encaixe = NULL) {
        $data['motivos'] = $this->motivocancelamento->listartodos();
        $data['exames_id'] = $exames_id;
        $data['sala_id'] = $sala_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['pegamedico'] = $this->exame->pegarmedicocancelamento($agenda_exames_id);
        @$data['encaixe'] = @$encaixe;

        // print_r($sala_id);
        // die;
        $this->loadView('ambulatorio/examecancelamento-form', $data);
    }

    function examecancelamentoagendamento($agenda_exames_id, $paciente_id, $tipo, $encaixe) {
        $data['motivos'] = $this->motivocancelamento->listartodos();
        $data['paciente_id'] = $paciente_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['tipo'] = $tipo;
        $data['encaixe'] = $encaixe;

        $detalhesagenda = $this->exame->detalhesagenda($agenda_exames_id);
        // echo '<pre>';
        // print_r($detalhesagenda);
        // die;
        $data['googleId'] = 0;
        if($this->session->userdata('google_calendar_access_token')){

        
            $start = $detalhesagenda[0]->data.' '.$detalhesagenda[0]->inicio;
            $end = $detalhesagenda[0]->data.' '.$detalhesagenda[0]->fim;
            $paciente = $detalhesagenda[0]->paciente;

        $events = $this->googlecalendar->getEvents('primary', $start, $end, 40, $paciente);

        // echo '<pre>';
        // print_r($events);
        // die;


        foreach($events as $item){
            if($item['summary'] == $paciente){
               $data['googleId'] = $item['id'];
            }
        }

        }

        $this->loadView('ambulatorio/examecancelamentoagendamento-form', $data);
    }

    function examecancelamentogeral($exames_id = NULL, $sala_id = NULL, $agenda_exames_id = NULL, $paciente_id = NULL, $procedimento_tuss_id = NULL, $encaixe = NULL) {
        $data['motivos'] = $this->motivocancelamento->listartodos();
        $data['exames_id'] = $exames_id;
        $data['sala_id'] = $sala_id;
        @$data['paciente_id'] = @$paciente_id;
        @$data['procedimento_tuss_id'] = @$procedimento_tuss_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['pegamedico'] = $this->exame->pegarmedicocancelamento($agenda_exames_id);
        @$data['encaixe'] = @$encaixe;
        $this->load->View('ambulatorio/examecancelamentogeral-form', $data);
    }

    function enviarsalaesperamedicolaboratorial($paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id, $medico_id) {
        $total = $this->exame->contadorexamesmedico($agenda_exames_id);
        if ($total == 0) {
            $procedimentopercentual = $procedimento_tuss_id;
            $medicopercentual = $medico_id;
            $percentual = $this->guia->percentualmedicoconvenioexames($procedimentopercentual, $medicopercentual);
            if (count($percentual) == 0) {
                $percentual = $this->guia->percentualmedicoprocedimento($procedimentopercentual, $medicopercentual);
            }

            $laudo_id = $this->exame->enviarsalaesperamedicolaboratorial($percentual, $paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id, $medico_id);
            if ($laudo_id == "-1") {
                $data['mensagem'] = 'Erro ao gravar o Exame. Opera&ccedil;&atilde;o cancelada.';
            } else {
                // $this->gerarcr($agenda_exames_id); //clinica humana
                // $this->gerardicom($laudo_id); //clinica ronaldo
                $empresa_id = $this->session->userdata('empresa_id');
                $empresa = $this->guia->listarempresa($empresa_id);
                if ($empresa[0]->chamar_consulta == 't' && $_POST['txttipo'] == 'CONSULTA') {
                    $this->laudo->chamadaconsulta($laudo_id);
                }
//                $this->laudo->chamada($laudo_id);
            }
        } else {
            $data['mensagem'] = 'Erro ao gravar o Exame. Exame ja cadastrato.';
        }
//        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function enviarsalaesperamedicoodontologia($paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id, $medico_id) {
        $total = $this->exame->contadorexamesmedico($agenda_exames_id);
        if ($total == 0) {
            $procedimentopercentual = $procedimento_tuss_id;
            $medicopercentual = $medico_id;
            $percentual = $this->guia->percentualmedicoconvenioexames($procedimentopercentual, $medicopercentual);
            if (count($percentual) == 0) {
                $percentual = $this->guia->percentualmedicoprocedimento($procedimentopercentual, $medicopercentual);
            }

            $laudo_id = $this->exame->enviarsalaesperamedicoodontologia($percentual, $paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id, $medico_id);
            if ($laudo_id == "-1") {
                $data['mensagem'] = 'Erro ao gravar o Exame. Opera&ccedil;&atilde;o cancelada.';
            } else {
                // $this->gerarcr($agenda_exames_id); //clinica humana
                // $this->gerardicom($laudo_id); //clinica ronaldo
                $empresa_id = $this->session->userdata('empresa_id');
                $empresa = $this->guia->listarempresa($empresa_id);
                if ($empresa[0]->chamar_consulta == 't' && $_POST['txttipo'] == 'CONSULTA') {
                    $this->laudo->chamadaconsulta($laudo_id);
                }
//                $this->laudo->chamada($laudo_id);
            }
        } else {
            $data['mensagem'] = 'Erro ao gravar o Exame. Exame ja cadastrato.';
        }
//        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function enviarsalaesperamedicoespecialidade($paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id, $medico_id) {
        $total = $this->exame->contadorexamesmedico($agenda_exames_id);
        if ($total == 0) {
            $procedimentopercentual = $procedimento_tuss_id;
            $medicopercentual = $medico_id;
            $percentual = $this->guia->percentualmedicoconvenioexames($procedimentopercentual, $medicopercentual);
            if (count($percentual) == 0) {
                $percentual = $this->guia->percentualmedicoprocedimento($procedimentopercentual, $medicopercentual);
            }

            $laudo_id = $this->exame->enviarsalaesperamedicoespecialidade($percentual, $paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id, $medico_id);
            if ($laudo_id == "-1") {
                $data['mensagem'] = 'Erro ao gravar o Exame. Opera&ccedil;&atilde;o cancelada.';
            } else {
                // $this->gerarcr($agenda_exames_id); //clinica humana
                // $this->gerardicom($laudo_id); //clinica ronaldo
                $empresa_id = $this->session->userdata('empresa_id');
                $empresa = $this->guia->listarempresa($empresa_id);
                if ($empresa[0]->chamar_consulta == 't' && $_POST['txttipo'] == 'CONSULTA') {
                    $this->laudo->chamadaconsulta($laudo_id);
                }
//                $this->laudo->chamada($laudo_id);
            }
        } else {
            $data['mensagem'] = 'Erro ao gravar o Exame. Exame ja cadastrato.';
        }
//        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function enviarsalaesperamedicoconsulta($paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id, $medico_id) {
        $total = $this->exame->contadorexamesmedico($agenda_exames_id);
        if ($total == 0) {
            $procedimentopercentual = $procedimento_tuss_id;
            $medicopercentual = $medico_id;
            $percentual = $this->guia->percentualmedicoconvenioexames($procedimentopercentual, $medicopercentual);
            if (count($percentual) == 0) {
                $percentual = $this->guia->percentualmedicoprocedimento($procedimentopercentual, $medicopercentual);
            }

            $laudo_id = $this->exame->enviarsalaesperamedicoconsulta($percentual, $paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id, $medico_id);
            if ($laudo_id == "-1") {
                $data['mensagem'] = 'Erro ao gravar o Exame. Opera&ccedil;&atilde;o cancelada.';
            } else {
                // $this->gerarcr($agenda_exames_id); //clinica humana
                // $this->gerardicom($laudo_id); //clinica ronaldo
                $empresa_id = $this->session->userdata('empresa_id');
                $empresa = $this->guia->listarempresa($empresa_id);
                if ($empresa[0]->chamar_consulta == 't' && $_POST['txttipo'] == 'CONSULTA') {
                    $this->laudo->chamadaconsulta($laudo_id);
                }
//                $this->laudo->chamada($laudo_id);
            }
        } else {
            $data['mensagem'] = 'Erro ao gravar o Exame. Exame ja cadastrato.';
        }
//        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function enviarsalaesperamedicoexame($paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id, $medico_id) {
        $total = $this->exame->contadorexamesmedico($agenda_exames_id);
        if ($total == 0) {
            $procedimentopercentual = $procedimento_tuss_id;
            $medicopercentual = $medico_id;
            $percentual = $this->guia->percentualmedicoconvenioexames($procedimentopercentual, $medicopercentual);
            if (count($percentual) == 0) {
                $percentual = $this->guia->percentualmedicoprocedimento($procedimentopercentual, $medicopercentual);
            }

            $laudo_id = $this->exame->enviarsalaesperamedicoexame($percentual, $paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id, $medico_id);
            if ($laudo_id == "-1") {
                $data['mensagem'] = 'Erro ao gravar o Exame. Opera&ccedil;&atilde;o cancelada.';
            } else {
                // $this->gerarcr($agenda_exames_id); //clinica humana
                // $this->gerardicom($laudo_id); //clinica ronaldo
                $empresa_id = $this->session->userdata('empresa_id');
                $empresa = $this->guia->listarempresa($empresa_id);
                if ($empresa[0]->chamar_consulta == 't' && $_POST['txttipo'] == 'CONSULTA') {
                    $this->laudo->chamadaconsulta($laudo_id);
                }
//                $this->laudo->chamada($laudo_id);
            }
        } else {
            $data['mensagem'] = 'Erro ao gravar o Exame. Exame ja cadastrato.';
        }
//        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function enviarsalaesperamedico($paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id, $medico_id) {
        $total = $this->exame->contadorexamesmedico($agenda_exames_id);
        if ($total == 0) {
            $procedimentopercentual = $procedimento_tuss_id;
            $medicopercentual = $medico_id;
            $percentual = $this->guia->percentualmedicoconvenioexames($procedimentopercentual, $medicopercentual);
            if (count($percentual) == 0) {
                $percentual = $this->guia->percentualmedicoprocedimento($procedimentopercentual, $medicopercentual);
            }

            $laudo_id = $this->exame->enviarsalaesperamedico($percentual, $paciente_id, $procedimento_tuss_id, $guia_id, $agenda_exames_id, $medico_id);
            if ($laudo_id == "-1") {
                $data['mensagem'] = 'Erro ao gravar o Exame. Opera&ccedil;&atilde;o cancelada.';
            } else {
                // $this->gerarcr($agenda_exames_id); //clinica humana
                // $this->gerardicom($laudo_id); //clinica ronaldo
                $empresa_id = $this->session->userdata('empresa_id');
                $empresa = $this->guia->listarempresa($empresa_id);
                if ($empresa[0]->chamar_consulta == 't' && $_POST['txttipo'] == 'CONSULTA') {
                    $this->laudo->chamadaconsulta($laudo_id);
                }
//                $this->laudo->chamada($laudo_id);
            }
        } else {
            $data['mensagem'] = 'Erro ao gravar o Exame. Exame ja cadastrato.';
        }


//        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function gravarexame() {
        $total = $this->exame->contadorexames();
//        var_dump($total);die('morreu');




        if ($total == 0) {
            $preparo = $this->guia->listarprocedimentopreparo();
            $procedimentopercentual = $_POST['txtprocedimento_tuss_id'];
            $medicopercentual = $_POST['txtmedico'];
            $percentual = $this->guia->percentualmedicoconvenioexames($procedimentopercentual, $medicopercentual);
            if (count($percentual) == 0) {
                $percentual = $this->guia->percentualmedicoprocedimento($procedimentopercentual, $medicopercentual);
            }

            $laudo_id = $this->exame->gravarexame($percentual);
            if ($laudo_id == "-1") {
                $data['mensagem'] = 'Erro ao gravar o Exame. Opera&ccedil;&atilde;o cancelada.';
            } else {

                if ($preparo[0]->sala_preparo == 't') {
                    $this->exame->gravarsalapreparo();
                    $data['mensagem'] = 'Sucesso ao enviar para a sala de preparo.';
                } else {
                    $data['mensagem'] = 'Sucesso ao gravar o Exame.';
                }
//                $this->gerarcr($agenda_exames_id); //clinica humana
                //$this->gerardicom($laudo_id); //clinica ronaldo
                $empresa_id = $this->session->userdata('empresa_id');
                $empresa = $this->guia->listarempresa($empresa_id);
                if ($empresa[0]->chamar_consulta == 't' && $_POST['txttipo'] == 'CONSULTA') {
                    $this->laudo->chamadaconsulta($laudo_id);
                }
//                $this->laudo->chamada($laudo_id);
            }
        } else {
            $data['mensagem'] = 'Erro ao gravar o Exame. Exame ja cadastrato.';
        }
//        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function gravarexametodos() {
//        $total = $this->exame->contadorexamestodos();
//        if ($total == 0) {
        $laudo_id = $this->exame->gravarexametodos();

        if (count($laudo_id) == 0) {
            $data['mensagem'] = 'Erro ao gravar o Exame. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar o Exame.';
//                $this->gerarcr($agenda_exames_id); //clinica humana
            foreach ($laudo_id as $value) {
                $this->gerardicom($value); //clinica ronaldo
            }
        }
//        } else {
//            $data['mensagem'] = 'Erro ao gravar o Exame. Exame ja cadastrato.';
//        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/listarsalasespera");
    }

    function gravarexametodosfiladecaixa() {
        $total = $this->exame->contadorexamestodos();
        if ($total == 0) {
            $laudo_id = $this->exame->gravarexametodos();

            if (count($laudo_id) == 0) {
                $data['mensagem'] = 'Erro ao gravar o Exame. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao gravar o Exame.';
//                $this->gerarcr($agenda_exames_id); //clinica humana
                foreach ($laudo_id as $value) {
                    $this->gerardicom($value); //clinica ronaldo
                }
            }
        } else {
            $data['mensagem'] = 'Erro ao gravar o Exame. Exame ja cadastrato.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/listarsalasespera");
    }

    function cancelarexamelancarcredito() {
//        $credito = $this->exame->creditocancelamentoespera();
        $verificar = $this->exame->cancelarexamelancarcredito();
        if ($verificar == "-1") {
            $data['mensagem'] = 'Erro ao cancelar o Exame. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao cancelar o Exame.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/listarsalasespera");
    }

    function cancelarespera() {
        if ($this->session->userdata('perfil_id') != 12) {
            // $credito = $this->exame->creditocancelamentoespera();

            $credito_modelo2 = $this->exame->creditomodelo2cancelamento();
            $tcd = $this->exame->tcdcancelamento();
            $inadimplencia = $this->exame->cancelarinadimplencia();
            $verificar = $this->exame->cancelarespera();
            if ($verificar == "-1") {
                $data['mensagem'] = 'Erro ao cancelar o Exame. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao cancelar o Exame.';
            }
        } else {
            $data['mensagem'] = 'Erro ao cancelar o Exame. Você não possui perfil para realizar essa opera&ccedil;&atilde;o .';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
        // redirect(base_url() . "ambulatorio/exame/listarsalasespera");
    }

    function cancelarmatmed() {
        if ($this->session->userdata('perfil_id') != 12) {
            $credito = $this->exame->creditocancelamento();
            $credito_modelo2 = $this->exame->creditomodelo2cancelamento();
            $inadimplencia = $this->exame->cancelarinadimplencia();
            $verificar = $this->exame->cancelaresperamatmed();
            $gasto = $this->exame->verificargastodesala();
            $gasto_id = $gasto[0]->ambulatorio_gasto_sala_id;
//            var_dump($gasto_id);
//            die;
            if ($gasto_id != '') {
                $this->exame->excluirgastodesalaguia($gasto_id);
            }
            if ($verificar == "-1") {
                $data['mensagem'] = 'Erro ao cancelar o Exame. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao cancelar o Exame.';
            }
        } else {
            $data['mensagem'] = 'Erro ao cancelar o Exame. Você não possui perfil para realizar essa opera&ccedil;&atilde;o .';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function cancelarpacoterealizando() {
        if ($this->session->userdata('perfil_id') != 12) {
            $credito = $this->exame->creditocancelamentopacote();
            $verificar = $this->exame->cancelarpacoterealizando();
            if ($verificar == "-1") {
                $data['mensagem'] = 'Erro ao cancelar o Pacote. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao cancelar o Pacote.';
            }
        } else {
            $data['mensagem'] = 'Erro ao cancelar o Pacote. Você não possui perfil para realizar essa opera&ccedil;&atilde;o .';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function esperacancelarpacote() {
        if ($this->session->userdata('perfil_id') != 12) {
            $credito = $this->exame->creditocancelamentopacote();
            $verificar = $this->exame->esperacancelarpacote();
            if ($verificar == "-1") {
                $data['mensagem'] = 'Erro ao cancelar o Pacote. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao cancelar o Pacote.';
            }
        } else {
            $data['mensagem'] = 'Erro ao cancelar o Pacote. Você não possui perfil para realizar essa opera&ccedil;&atilde;o .';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function cancelarpacote() {
        if ($this->session->userdata('perfil_id') != 12) {
            $credito = $this->exame->creditocancelamentopacote();
            $verificar = $this->exame->cancelarpacote();
            if ($verificar == "-1") {
                $data['mensagem'] = 'Erro ao cancelar o Pacote. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao cancelar o Pacote.';
            }
        } else {
            $data['mensagem'] = 'Erro ao cancelar o Pacote. Você não possui perfil para realizar essa opera&ccedil;&atilde;o .';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function cancelarguia() {
        if ($this->session->userdata('perfil_id') != 12) {
            $credito = $this->exame->creditocancelamentoguia();
            $inadimplencia = $this->exame->cancelarinadimplencia();
            $verificar = $this->exame->cancelarespera();
            $credito_modelo2 = $this->exame->creditomodelo2cancelamento();


            if ($verificar == "-1") {
                $data['mensagem'] = 'Erro ao cancelar o Exame. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao cancelar o Exame.';
            }
        } else {
            $data['mensagem'] = 'Erro ao cancelar o Exame. Você não possui perfil para realizar essa opera&ccedil;&atilde;o .';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function telefonema($agenda_exame_id, $paciente) {
        $data['agenda_exame_id'] = $agenda_exame_id;
        $data['paciente'] = $paciente;
        $this->load->View('ambulatorio/telefonema-form', $data);
    }

    function chegada($agenda_exame_id, $paciente) {
        $data['agenda_exame_id'] = $agenda_exame_id;
        $data['paciente'] = $paciente;
        $this->load->View('ambulatorio/chegada-form', $data);
    }

    function telefonemagravar($agenda_exame_id) {
        $this->exame->telefonema($agenda_exame_id);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function whatsappgravarconfirmacao($agenda_exame_id = NULL, $paciente_id = NULL) {


        $verificar = $this->exame->whatsappgravarconfirmacao($agenda_exame_id, $paciente_id);
        $data['paciente'] = $this->exame->dadospaciente($paciente_id);
        if ($verificar != "-1") {

            $this->load->View('ambulatorio/confirmado_sucesso', $data);
        } else {
            $this->load->View('ambulatorio/confirmado_erro');
        }
    }

    function autenticacaowhatsapp($agenda_exame_id = NULL, $paciente_id = NULL) {
        $data['paciente'] = $this->exame->dadospaciente($paciente_id);
        $data['paciente_id'] = $paciente_id;
        $data['agenda_exame_id'] = $agenda_exame_id;
        $this->load->View('ambulatorio/autenticacaowhatsapp', $data);
    }

    function chegadagravar($agenda_exame_id) {
        $this->exame->chegada($agenda_exame_id);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function observacao($agenda_exame_id, $paciente = '') {
        $data['agenda_exame_id'] = $agenda_exame_id;
        $data['paciente'] = $paciente;
        $data['obs'] = $this->exame->listarobservacoes($agenda_exame_id);
        $this->load->View('ambulatorio/observacao-form', $data);
    }

    function alterarobservacaolaudo($laudo_id) {
        $data['laudo_id'] = $laudo_id;
        $data['observacao'] = $this->exame->listarobservacaolaudo($laudo_id);
        $this->load->View('ambulatorio/alterarobservacaolaudo-form', $data);
    }

    function alterarobservacao($agenda_exame_id) {
        $data['agenda_exame_id'] = $agenda_exame_id;
        $data['observacao'] = $this->exame->listarobservacoes($agenda_exame_id);
        $this->load->View('ambulatorio/alterarobservacao-form', $data);
    }

    function alterardescricao($ambulatorio_orcamento_id, $dataSelecionada) {
        $data['ambulatorio_orcamento_id'] = $ambulatorio_orcamento_id;
        $data['dataselecionada'] = $dataSelecionada;
        $data['observacao'] = $this->exame->listardescricoes($ambulatorio_orcamento_id);
        $this->load->View('ambulatorio/alterardescricao-form', $data);
    }

    function alterarobservacaofaturar($agenda_exame_id) {
        $data['agenda_exame_id'] = $agenda_exame_id;
        $data['observacao'] = $this->exame->listarobservacoesfaturar($agenda_exame_id);
        $this->load->View('ambulatorio/alteracaoobservacaofaturamento-form', $data);
    }

    function alterarobservacaofaturaramentomanual($guia_id) {
        $data['guia_id'] = $guia_id;
        $data['observacao'] = $this->exame->listarobservacoesfaturaramentomanual($guia_id);
//        var_dump($data);die;
        $this->load->View('ambulatorio/alteracaoobservacaofaturamentomanual-form', $data);
    }

    function observacaolaudogravar($laudo_id) {
//        var_dump($agenda_exame_id); die;
        $verificar = $this->exame->observacaolaudogravar($laudo_id);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function observacaogravar($agenda_exame_id) {
//        var_dump($agenda_exame_id); die;
        $verificar = $this->exame->observacao($agenda_exame_id);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function descricaogravar($ambulatorio_orcamento_id, $dataSelecionada) {

        $this->exame->observacaoorcamento($ambulatorio_orcamento_id, $dataSelecionada);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function observacaofaturargravar($agenda_exame_id) {
        $this->exame->observacaofaturamento($agenda_exame_id);
        echo '<script type="text/javascript">window.close();</script>';
    }

    function observacaofaturaramentomanualgravar($guia_id) {
        $this->exame->observacaofaturamentomanual($guia_id);
        echo '<script type="text/javascript">window.close();</script>';
    }

    function desbloquear($agenda_exame_id, $inicio) {
        $data['agenda_exame_id'] = $agenda_exame_id;
        $data['inicio'] = $inicio;
        $this->load->View('ambulatorio/desbloquearagenda-form', $data);
    }

    function bloquear($agenda_exame_id, $inicio) {
        $data['agenda_exame_id'] = $agenda_exame_id;
        $data['inicio'] = $inicio;
        $this->load->View('ambulatorio/bloquearagenda-form', $data);
    }

    function desbloqueargravar($agenda_exame_id) {
        $this->exame->desbloquear($agenda_exame_id);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function bloqueargravar($agenda_exame_id) {
        $this->exame->bloquear($agenda_exame_id);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function cancelarexameencaixe() {
        if ($this->session->userdata('perfil_id') != 12) {
            $verificar = $this->exame->cancelarexameencaixe();
            if ($verificar == "-1") {
                $data['mensagem'] = 'Erro ao cancelar o Exame. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao cancelar o Exame.';
            }
        } else {
            $data['mensagem'] = 'Erro ao cancelar o Exame. Você não possui perfil para realizar essa opera&ccedil;&atilde;o .';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/listarexamerealizando");
    }

    function cancelarexame() {
        if ($this->session->userdata('perfil_id') != 12) {
            $credito = $this->exame->creditocancelamento();
            // die;
            $inadimplencia = $this->exame->cancelarinadimplencia();
            $verificar = $this->exame->cancelarexame();
            if ($verificar == "-1") {
                $data['mensagem'] = 'Erro ao cancelar o Exame. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao cancelar o Exame.';
            }
        } else {
            $data['mensagem'] = 'Erro ao cancelar o Exame. Você não possui perfil para realizar essa opera&ccedil;&atilde;o .';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function cancelarexamegeral() {
//        var_dump($_POST); die;
        if ($this->session->userdata('perfil_id') != 12) {
            $credito = $this->exame->creditocancelamento();
            $tcd = $this->exame->tcdcancelamento();
            $credito_modelo2 = $this->exame->creditomodelo2cancelamento();
            $inadimplencia = $this->exame->cancelarinadimplencia();
            $verificar = $this->exame->cancelarexame();
            if ($verificar == "-1") {
                $data['mensagem'] = 'Erro ao cancelar o Exame. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao cancelar o Exame.';
            }
        } else {
            $data['mensagem'] = 'Erro ao cancelar o Exame. Você não possui perfil para realizar essa opera&ccedil;&atilde;o .';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function voltarexame($exame_id, $sala_id, $agenda_exames_id) {
        $verificar = $this->exame->voltarexame($exame_id, $sala_id, $agenda_exames_id);
        if ($verificar == "-1") {
            $data['mensagem'] = 'Erro ao adiar o Exame. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao adiar o Exame.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/listarexamerealizando");
    }

    function voltarexamependente($exame_id, $sala_id, $agenda_exames_id) {
        $verificar = $this->exame->voltarexame($exame_id, $sala_id, $agenda_exames_id);
        if ($verificar == "-1") {
            $data['mensagem'] = 'Erro ao adiar o Exame. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao adiar o Exame.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/listarexamependente", $data);
    }

    function finalizarexame($exames_id, $sala_id) {
        $verificar = $this->exame->finalizarexame($exames_id, $sala_id);
        if ($verificar == -1) {
            $data['mensagem'] = 'Erro ao finalizar o Exame. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao finalizar o Exame.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/listarexamerealizando", $data);
    }

    function lancarcreditoexamependente($exames_id, $sala_id, $agenda_exames_id) {
        $verificar = $this->exame->lancarcreditoexamependente($exames_id, $sala_id, $agenda_exames_id);
        if ($verificar == -1) {
            $data['mensagem'] = 'Erro ao lançar credito. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao lançar credito.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/listarexamependente", $data);
    }

    function carregarcancelamentoexamecredito($exames_id, $sala_id, $agenda_exames_id) {
        $verificar = $this->exame->carregarcancelamentoexamecredito($exames_id, $sala_id, $agenda_exames_id);
        $data['motivos'] = $this->motivocancelamento->listartodos();
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $this->loadView('ambulatorio/cancelamentoexamecredito-form', $data);
    }

    function finalizarexamependente($exames_id, $sala_id, $agenda_exames_id) {
        $verificar = $this->exame->finalizarexamependente($exames_id, $sala_id, $agenda_exames_id);
        if ($verificar == -1) {
            $data['mensagem'] = 'Erro ao finalizar o Exame. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao finalizar o Exame.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/listarexamependente", $data);
    }

    function finalizarexametodos($sala_id, $guia_id, $grupo) {
        $verificar = $this->exame->finalizarexametodos($sala_id, $guia_id, $grupo);
        if ($verificar == "-1") {
            $data['mensagem'] = 'Erro ao finalizar o Exame. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao finalizar o Exame.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/listarexamerealizando", $data);
    }

    function pendenteexame($exames_id, $sala_id) {
        $verificar = $this->exame->pendenteexame($exames_id, $sala_id);
        if ($verificar == "-1") {
            $data['mensagem'] = 'Erro ao encaminhar o Exame. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao encaminhar o Exame.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/listarexamerealizando");
    }

    function gastosdesala($exames_id, $convenio_id, $sala_id = null, $mensagem = null) {
        $data['convenio_id'] = $convenio_id;
        $data['sala_id'] = $sala_id;
        $data['mensagem'] = $mensagem;

        $data['armazem_id'] = $this->exame->listararmazemsala($sala_id);
        $data['armazem_farmacia_id'] = $this->exame->listararmazemfarmaciasala($sala_id);
        $armazem_id = $data['armazem_id'];
        $armazem_farmacia_id = $data['armazem_farmacia_id'];
        $data['paciente'] = $this->exame->listarpacientegastos($exames_id);
        $data['procedimentoagrupados'] = $this->procedimento->listarprocedimentoagrupadosgastodesala($convenio_id);
        $data['produtos'] = $this->exame->listarprodutossalagastos($convenio_id, $armazem_id);
        $data['produtos_farmacia'] = $this->exame->listarprodutossalagastosfarmacia($convenio_id, $armazem_farmacia_id);
        $data['guia_id'] = $this->exame->listargastodesalaguia($exames_id);
        $data['produtos_gastos'] = $this->exame->listaritensgastosprocedimento($exames_id);
        $data['laudo'] = $this->exame->mostrarlaudogastodesala($exames_id);
        $data['aparelhos'] = $this->exame->listaraparelhos();
        $data['aparelhos_associados'] = $this->exame->listargastoaparelhos($exames_id);
        $empresa_id = $this->session->userdata('empresa_id');
        $empresapermissoes = $this->guia->listarempresapermissoes($empresa_id);
        $data['filaparelho'] = $empresapermissoes[0]->filaaparelho;

        // echo '<pre>'; 
        // var_dump($data['filaparelho']);
        // die;
        $data['exames_id'] = $exames_id;
        $this->load->View('ambulatorio/gastosdesala', $data);
    }

    function gravargastodesala() { 
        $exame_id = $_POST['exame_id'];         
        $sala_id = $_POST['sala_id']; 
                    
//        echo "<pre>";;
        
        if (@$_POST['aparelho_id'] > 0) {
           $res = $this->exame->gravargastoasalaparelho($exame_id);   
        } 
//        echo "<pre>";
//        print_r($exame_id);
//        die; 
        $mensagem = '';
        if ($_POST['pacote_id'] > 0) {
            $procedimento_convenio  = $this->procedimento->listarprocedimentoconveniovalor($_POST['pacote_id']);
            $procedimentos_pacote = $this->procedimento->listarprocedimentoagrupadosgastodesalagravar();
            $agrupador_id = $this->guia->gravaragrupadorpacote($procedimento_convenio[0]->procedimento_convenio_id);                   
            foreach ($procedimentos_pacote as $item) {
                $data['agenda_exames'] = $this->exame->listaagendaexames($exame_id);
                $convenio_id = $data['agenda_exames'][0]->convenio_id;
                $data['procedimento'] = $this->exame->listaprocedimento($item->procedimento_tuss_id, $convenio_id);
                $valor = $data['procedimento'][0]->valortotal;
//        var_dump($data['procedimento']); die;
                $produto_id = $item->estoque_produto_id;
                $quantidade = $item->quantidade_agrupador;
                $gasto_id = $this->exame->gravargastodesalapacote($valor, $produto_id, $quantidade);
                if ($gasto_id > 0) {
//                    if (isset($_POST['faturar'])) {
                    $_POST['medicoagenda'] = $data['agenda_exames'][0]->medico_agenda;
                    $_POST['tipo'] = $data['agenda_exames'][0]->tipo;

//            var_dump($data['procedimento']); die;
                    if (count($data['procedimento']) > 0) {
                    // As taxas do pacote não irão entrar como procedimentos
                        if($data['procedimento'][0] != 'TAXA'){
                            $this->exame->faturargastodesalapacote($data['procedimento'][0], $gasto_id, $quantidade,$agrupador_id);
                        }
                    }
//                    }
                } else {
                    $mensagem = 1;
                }
            } 
            $this->session->set_flashdata('message', $mensagem);
            redirect(base_url() . "ambulatorio/exame/gastosdesala/$exame_id/$convenio_id/$sala_id/$mensagem");
        } elseif ($_POST['produto_id'] > 0 || $_POST['produto_farmacia_id'] > 0) {
            $data['agenda_exames'] = $this->exame->listaagendaexames($exame_id);
            $convenio_id = $data['agenda_exames'][0]->convenio_id;
            $data['procedimento'] = $this->exame->listaprocedimento($_POST['procedimento_id'], $convenio_id);
            $valor = $data['procedimento'][0]->valortotal;
            // var_dump($_POST['produto_farmacia_id']); die;
            if ($_POST['produto_farmacia_id'] > 0) {
                $gasto_id = $this->exame->gravargastodesalafarmacia($valor);
            } else {
                $gasto_id = $this->exame->gravargastodesala($valor);
            }
            if (isset($_POST['faturar'])) {
                $_POST['medicoagenda'] = $data['agenda_exames'][0]->medico_agenda;
                $_POST['tipo'] = $data['agenda_exames'][0]->tipo;
//            var_dump($data['procedimento']); die;
                if (count($data['procedimento']) > 0) {
                    if($data['procedimento'][0] != 'TAXA'){
                        $this->exame->faturargastodesala($data['procedimento'][0], $gasto_id);
                    }
                }
            }
            $data['agenda_exames'] = $this->exame->listaagendaexames($exame_id);
            $convenio_id = $data['agenda_exames'][0]->convenio_id;
            redirect(base_url() . "ambulatorio/exame/gastosdesala/$exame_id/$convenio_id/$sala_id");
        } else {
            $data['agenda_exames'] = $this->exame->listaagendaexames($exame_id);
            $convenio_id = $data['agenda_exames'][0]->convenio_id;
            $this->session->set_flashdata('message', $mensagem);
            redirect(base_url() . "ambulatorio/exame/gastosdesala/$exame_id/$convenio_id/$sala_id");
        }
    }

    function excluirgastodesala($gasto_id, $exame_id, $convenio_id, $sala_id) {
        $this->exame->excluirgastodesala($gasto_id);
        redirect(base_url() . "ambulatorio/exame/gastosdesala/$exame_id/$convenio_id/$sala_id");
//        $this->gastosdesala($exame_id);
    }

    function excluirgastodesalafarmacia($gasto_id, $exame_id, $convenio_id, $sala_id) {
        $this->exame->excluirgastodesalafarmacia($gasto_id);
        redirect(base_url() . "ambulatorio/exame/gastosdesala/$exame_id/$convenio_id/$sala_id");
//        $this->gastosdesala($exame_id);
    }

    function anexarimagem($exame_id, $sala_id) {
//        var_dump($_POST); die;

        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }

        $data['arquivos_deletados'] = directory_map("./uploadopm/$exame_id/");
        $data['agenda_exames'] = $this->exame->listaagendaexames($exame_id);
        $convenio_id = $data['agenda_exames'][0]->convenio_id;
        $ambulatorio_laudo_id = $data['agenda_exames'][0]->ambulatorio_laudo_id;
        $data['arquivo_pasta_pdf'] = directory_map("./upload/consulta/$ambulatorio_laudo_id/");
        if ($data['arquivo_pasta_pdf'] != false) {
            sort($data['arquivo_pasta_pdf']);
        }
//        $data['arquivo_pasta'] = directory_map("/home/hamilton/projetos/clinica/upload/$exame_id/");
        //$data['arquivos_deletados'] = directory_map("/home/hamilton/projetos/clinica/uploadopm/$exame_id/");
        $data['convenio_id'] = $convenio_id;
        $data['exame_id'] = $exame_id;
        $data['sala_id'] = $sala_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->loadView('ambulatorio/importacao-imagem', $data);
    }

    function gravarprocedimentoproducaoduplo() {
        // echo '<pre>';
        // var_dump($_POST); 
        // die;
        $this->procedimento->gravarPercentualImpDuplo();
        $mensagem = 'Procedimentos gravados com sucesso';
        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/exame/procedimentoduploimpProducao");
    }

    function procedimentoduploimpProducao() {
        $data['producao'] = $this->procedimento->listarprocedimentosproducaoduplo();
        $this->loadView('ambulatorio/importarproducaomedicaprocedimentoduplo', $data);
    }

    function importarproducaomedica() {                    
        $this->loadView('ambulatorio/importarproducaomedica');
    }


    function importaragendatoutempo() {                    
        $this->loadView('ambulatorio/importaragendatoutempo');
    }

    function importarproducaomedicaxml() {                    
        $this->loadView('ambulatorio/importarproducaomedicaxml');
    }

    function importararquivoproducaomedicaxml() {
        $arquivo = $_FILES['userfile'];
        $nome_arquivo = $_FILES['userfile']['name'];
        // var_dump($arquivo); 
        // die;

        if (!is_dir("./upload/producaoimpxml")) {
            mkdir("./upload/producaoimpxml");
            $destino = "./upload/producaoimpxml";
            chmod($destino, 0777);
        }
        //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
        $config['upload_path'] = "./upload/producaoimpxml/";
        $config['allowed_types'] = 'xml|.xml';
        $config['max_size'] = '0';
        $config['overwrite'] = TRUE;
        $config['encrypt_name'] = FALSE;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());
            $erro_msg = 'Houve um erro ao importar o arquivo. Verifique o tipo do arquivo e tente novamente';
            // $this->redirectImpProducao($erro_msg);
        } else {
            $error = null;
            $data = array('upload_data' => $this->upload->data());
        }
        $caminho = 'upload/producaoimpxml/' . $nome_arquivo;
        // $caminho = 'upload/ImportXMLTemp.xml';
        // var_dump($error); die;
        $xmlArray = $this->utilitario->convertXMLToArray($caminho);
        
        $arrayAtendimentos = $xmlArray['mensagemTISS']['ans:prestadorParaOperadora']['ans:loteGuias']['ans:guiasTISS']["ans:guiaSP-SADT"];
        if(!isset($arrayAtendimentos[0])){
            $arrayAtendimentos = $xmlArray['mensagemTISS']['ans:prestadorParaOperadora']['ans:loteGuias']['ans:guiasTISS'];
        }
        // echo '<pre>';
        // var_dump($arrayAtendimentos); die;
        foreach ($arrayAtendimentos as $key => $value) {
            $crm = $value['ans:procedimentosExecutados']["ans:procedimentoExecutado"]["ans:equipeSadt"]["ans:numeroConselhoProfissional"];
            $codigPrestador = $value["ans:dadosExecutante"]["ans:contratadoExecutante"]["ans:codigoPrestadorNaOperadora"];
            $data = $value["ans:dadosAutorizacao"]["ans:dataAutorizacao"];
            $codigoProc = $value['ans:procedimentosExecutados']['ans:procedimentoExecutado']['ans:procedimento']['ans:codigoProcedimento'];
            // echo '<pre>';
            // var_dump($crm); die;
            $medico_id = $this->procedimento->listarmedicoXML($crm);
            $convenio = $this->procedimento->listarconvenioXML($codigPrestador);
           
            if(!count($convenio) > 0){
                $this->procedimento->apagarProducaoImpTemp(); 
                $mensagem = "Codigo de prestador $codigPrestador nao encontrado";   
                $this->session->set_flashdata('message', $mensagem);
                redirect(base_url() . "ambulatorio/exame/importarproducaomedicaxml");
            }
            $convenio_id = $convenio[0]->convenio_id;

            $procedimentos = $this->procedimento->listarprocedimentoXML($codigoProc, $convenio_id);
            // echo '<pre>';
            // print_r($medico_id);
            // print_r($procedimentos);
            // print_r(count($procedimentos));
            // echo '<br>';

            if(!count($procedimentos) > 0){
                $this->procedimento->apagarProducaoImpTemp(); 
                $mensagem = "Codigo $codigoProc nao encontrado no convenio {$convenio[0]->nome}";   
                $this->session->set_flashdata('message', $mensagem);
                redirect(base_url() . "ambulatorio/exame/importarproducaomedicaxml");
            }
            // elseif(count($procedimentos) > 1){
            //     $this->procedimento->apagarProducaoImpTemp(); 
            //     $mensagem = "Codigo $codigoProc encontrado em varios procedimentos no {$convenio[0]->nome}";   
            //     $this->session->set_flashdata('message', $mensagem);
            //     redirect(base_url() . "ambulatorio/exame/importarproducaomedicaxml");
            // }

            // $valor = $value['ans:procedimentosExecutados']['ans:procedimentoExecutado']["ans:valorTotal"];
            $valor = $procedimentos[0]->valortotal;
            $paciente = $value["ans:dadosBeneficiario"]["ans:nomeBeneficiario"];
            $pacienteCarteira = $value["ans:dadosBeneficiario"]["ans:numeroCarteira"];
            $array_linha = json_encode($value);
            // echo '<pre>';
            // var_dump($procedimento_convenio_id); die;
            $this->procedimento->gravarPercentualTempXMLImp($medico_id, $convenio_id, $procedimentos, $paciente,$pacienteCarteira, $data, $valor, $array_linha); 
            # code...
        }
        $retorno = $this->procedimento->gravarPercentualImpXML(); 
        $this->procedimento->apagarProducaoImpTemp(); 
        $mensagem = 'Importação efetuada com sucesso';   
        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/exame/importarproducaomedicaxml");
        // echos '<pre>';
        // var_dump($arrayAtendimentos); die;
 
    }

    function listardiagnostico($exame_id){
        $this->load->View('ambulatorio/listardiagnostico-lista', $exame_id);
    }

    function importararquivoagendatoutempo() {
        $arquivo = $_FILES['userfile'];
        $nome_arquivo = $_FILES['userfile']['name'];
        // var_dump($arquivo); 
        // die;

        if (!is_dir("./upload/agendatoutempo")) {
            mkdir("./upload/agendatoutempo");
            $destino = "./upload/agendatoutempo";
            chmod($destino, 0777);
        }
        //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
        $config['upload_path'] = "./upload/agendatoutempo/";
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = '0';
        $config['overwrite'] = TRUE;
        $config['encrypt_name'] = FALSE;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());
            $erro_msg = 'Houve um erro ao importar o arquivo. Verifique o tipo do arquivo e tente novamente';
            $this->redirectImpProducao($erro_msg);
        } else {
            $error = null;
            $data = array('upload_data' => $this->upload->data());
        }
        $nome_arquivo = str_replace(' ', '_', $nome_arquivo);
        $arrayExcel = $this->convertExcelToArrayAgenda($nome_arquivo);                  

        // echo '<pre>';
        // print_r($arrayExcel);
        // die;
        $this->exame->AdicionarAgendaTouTempo($arrayExcel);

            $mensagem = 'Importação Realizada com Sucesso';
           $this->session->set_flashdata('message', $mensagem);
           redirect(base_url() . "ambulatorio/exame/importaragendatoutempo");
                    
        }
        

    function importararquivoproducaomedica() {
        $arquivo = $_FILES['userfile'];
        $nome_arquivo = $_FILES['userfile']['name'];
        // var_dump($arquivo); 
        // die;

        if (!is_dir("./upload/producaoimp")) {
            mkdir("./upload/producaoimp");
            $destino = "./upload/producaoimp";
            chmod($destino, 0777);
        }
        //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
        $config['upload_path'] = "./upload/producaoimp/";
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = '0';
        $config['overwrite'] = TRUE;
        $config['encrypt_name'] = FALSE;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());
            $erro_msg = 'Houve um erro ao importar o arquivo. Verifique o tipo do arquivo e tente novamente';
            $this->redirectImpProducao($erro_msg);
        } else {
            $error = null;
            $data = array('upload_data' => $this->upload->data());
        }
        // var_dump($error); die;
        $arrayExcel = $this->convertExcelToArray($nome_arquivo);
        // echo '<pre>';
        // var_dump($arrayExcel); 
        // die;
        $countErros = 0;                    
        $chave =  $nome_arquivo."~CHAVE~".json_encode($arrayExcel);                   
        // Verificando todas as condições do arquivo.
        $return = '';
        foreach ($arrayExcel as $key => $value) {
            if ($key == 0) {
                continue;
            }                   
            $return .= $this->verificarArrayProducao($value,$chave) ." @# ";                    
        }
                    
        $erros = explode("@#", $return);        
        foreach($erros as $item){                    
        if (str_replace(" ","",str_replace("@#","",$item)) != "" ) {  
//            Caso tenha algum erro no arquivo ele irá mostrar a primeira mensagem
            $return =  $item;                  
            break;            
         }
        }
                    
        $duplo = $this->procedimento->gravarPercentualImp(); 
        $this->procedimento->apagarProducaoImpTemp();        
        $data['operadores_add'] =  $this->procedimento->listaroperadorimportacaoadd($chave);
        if ($duplo == 't') {
            $mensagem = 'Existem mais de um Procedimento cadastrados no convenio com o Mesmo código TUSS, Por favor Selecione os Procedimentos referentes ao que o Dr.(a) realizou';
            $this->session->set_flashdata('message', $mensagem);
            redirect(base_url() . "ambulatorio/exame/procedimentoduploimpProducao", $data);
        } else {
            if (str_replace(" ","",str_replace("@#","",$return )) != "") {
                $erro = explode("@#", $return);
//              $mensagem =  $countErros." Linhas do arquivo apontam erros";
                $mensagem = $erro[0];
            }else{
                 $mensagem = 'Importação efetuada com sucesso';   
            }                  
           
            if (count($data['operadores_add']) > 0) {  
                $this->load->plugin('mpdf');
                $nomepdf = "Cadastrosfeitos-".$nome_arquivo.".pdf";
                $cabecalhopdf = "";      
                $rodapepdf = "";
                $texto = $this->load->View('ambulatorio/operadoresimportacao-lista', $data, true);                    
                downloadpdf($texto, $nomepdf, $cabecalhopdf, $rodapepdf);                       
            }      
            
            
            $nome_arqjson = json_encode($nome_arquivo);
            $this->procedimento->autualizartabelaoperadorimportados($chave,$nome_arqjson);
           
           $this->session->set_flashdata('message', $mensagem);
           redirect(base_url() . "ambulatorio/exame/importarproducaomedica");
                    
        }
        
        
    }

    function verificarArrayProducao($array,$chave) {

        $erro = 0;
        $erro_msg = '';
        $medico = array();
        $convenio = array();
        $procedimento = array();
                    
        if ($array[0] == '' && $erro_msg == '') { // Codigo
            $erro = -1;
            $erro_msg = 'No Arquivo enviado, possui linhas com a coluna CÓDIGO em branco';
        } else {
            $medico = $this->operador_m->verificarCodigoProf($array[0],$array,$chave);
            if (count($medico) == 0) {
                $erro = -2;
                $erro_msg = "Não foi encontrando um médico com o CÓDIGO X {$array[0]}";
            }                    
        }
        
        if ($array[2] == '' && $erro_msg == '') { // CNPJ
            $erro = -3;
            $erro_msg = "No arquivo enviado, possuem linhas com a coluna CNPJ em branco";
        } else {
            $convenio = $this->convenio->verificarCNPJconvenio($array[2]);
            if (count($convenio) == 0) {
                 $erro = -4;
                $erro_msg = "Não foi encontrando um convenio com o CNPJ X {$array[2]}";
            }
        }
                
        if ($array[9] == '' && $erro_msg == '') { // Codigo Procedimento
            $erro = -2;
            $erro_msg = "No arquivo enviado, possui Linhas com a coluna COD. PROCEDIMENTO em branco";
        } elseif($array[9] != '' && $erro_msg == '') {
            $procedimento = $this->procedimento->verificarCodigoProcedimento($array[9], $convenio);
            if (count($procedimento) == 0) {
                $erro = -5;
                $erro_msg = "Não foi encontrando um procedimento que possua o Codigo Tuss X {$array[9]}";
            }
        }
                    
        
//       echo "<pre>";
//       print_r($array);
//       echo $erro_msg;
//       die;
        
        if (!($erro_msg != '')) {
           $this->procedimento->gravarPercentualTempImp($medico, $convenio, $procedimento, $array); 
        } else {
         //$this->redirectImpProducao($erro_msg); 
        }        
                    
        return $erro_msg;
    }

    function convertExcelToArray($nome) {
        require_once('./system/plugins/PHPExcel/PHPExcel/IOFactory.php');
        $phpexcel_filename = './upload/producaoimp/' . $nome;
        $phpexcel_filetype = PHPExcel_IOFactory::identify($phpexcel_filename);
        $phpexcel_objReader = PHPExcel_IOFactory::createReader($phpexcel_filetype);
        $phpexcel_objPHPExcel = $phpexcel_objReader->load($phpexcel_filename);
        // convert one sheet
        $phpexcel_sheet = $phpexcel_objPHPExcel->getSheet(0);
        $phpexcel_array = $phpexcel_sheet->toArray();


        return $phpexcel_array;
    }


    function convertExcelToArrayAgenda($nome) {
        require_once('./system/plugins/PHPExcel/PHPExcel/IOFactory.php');
        $phpexcel_filename = './upload/agendatoutempo/' . $nome;
        $phpexcel_filetype = PHPExcel_IOFactory::identify($phpexcel_filename);
        $phpexcel_objReader = PHPExcel_IOFactory::createReader($phpexcel_filetype);
        $phpexcel_objPHPExcel = $phpexcel_objReader->load($phpexcel_filename);
        // convert one sheet
        $phpexcel_sheet = $phpexcel_objPHPExcel->getSheet(0);
        $phpexcel_array = $phpexcel_sheet->toArray();


        return $phpexcel_array;
    }

    

    function redirectImpProducao($erro) {
        $this->procedimento->apagarProducaoImpTemp();
        $data['mensagem'] = $erro;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/importarproducaomedica", $data);
    }

    function anexarimagemmedico($exame_id = NULL, $sala_id = NULL) {

        header('Cache-Control: no-cache');
        header('Pragma: no-cache');

        $this->load->helper('directory');
        if (!is_dir("./upload/$exame_id")) {
            mkdir("./upload/$exame_id");
            $destino = "./upload/$exame_id";
            chmod($destino, 0777);
        }
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");
//        $data['arquivo_pasta'] = directory_map("/home/hamilton/projetos/clinica/upload/$exame_id/");
        if ($data['arquivo_pasta'] != false) {
            natcasesort($data['arquivo_pasta']);
        }
        $data['arquivos_deletados'] = directory_map("./uploadopm/$exame_id/");
//        $data['arquivos_deletados'] = directory_map("/home/hamilton/projetos/clinica/uploadopm/$exame_id/");
        $data['exame_id'] = $exame_id;
        $data['sala_id'] = $sala_id;
        $this->load->View('ambulatorio/importacao-imagem2', $data);
    }

    function importarimagem() {
        $exame_id = $_POST['exame_id'];
        $sala_id = $_POST['sala_id'];
//        $data = $_FILES['userfile'];
//        var_dump($data);
//        die;
        for ($i = 0; $i < count($_FILES['arquivos']['name']); $i++) {
            $_FILES['userfile']['name'] = $_FILES['arquivos']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['arquivos']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['arquivos']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['arquivos']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['arquivos']['size'][$i];

            if (!is_dir("./upload/$exame_id")) {
                mkdir("./upload/$exame_id");
                $destino = "./upload/$exame_id";
                chmod($destino, 0777);
            }

            //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
            $config['upload_path'] = "./upload/" . $exame_id . "/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
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
        }


        $data['exame_id'] = $exame_id;
        $this->anexarimagem($exame_id, $sala_id);
    }

    function importararquivopdf() {
        $ambulatorio_laudo_id = $_POST['ambulatorio_laudo_id'];

//        var_dump($_FILES);
//        die;

        for ($i = 0; $i < count($_FILES['arquivos']['name']); $i++) {
            $_FILES['userfile']['name'] = $_FILES['arquivos']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['arquivos']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['arquivos']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['arquivos']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['arquivos']['size'][$i];

            if (!is_dir("./upload/consulta/$ambulatorio_laudo_id")) {
                mkdir("./upload/consulta/$ambulatorio_laudo_id");
                $destino = "./upload/consulta/$ambulatorio_laudo_id";
                chmod($destino, 0777);
            }

            //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
            $config['upload_path'] = "./upload/consulta/" . $ambulatorio_laudo_id . "/";
            $config['allowed_types'] = 'gif|jpg|BMP|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar';
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
        }

        $exame_id = $_POST['exame_id'];
        $sala_id = $_POST['sala_id'];
        redirect(base_url() . "ambulatorio/exame/anexarimagem/$exame_id/$sala_id");
    }

    function excluirimagemmedico($exame_id, $nome, $sala_id) {
        $this->load->helper('directory');

        $contador = directory_map("./uploadopm/$exame_id/");
        sort($contador);
//        var_dump(count($contador)); die;
        $numero = 1;
        foreach ($contador as $item) {
            $numero_for = (int) $item;
            if ($numero_for > $numero) {
                $numero = $numero_for;
            }
        }

        if ($contador > 0) {
            $novonome = (int) $numero + 1 . '.jpg';
            ;
        } else {
            $novonome = $nome;
        }

        if (!is_dir("./uploadopm/$exame_id")) {
            mkdir("./uploadopm/$exame_id");
            $pasta = "./uploadopm/$exame_id";
            chmod($pasta, 0777);
        }
        $origem = "./upload/$exame_id/$nome";
        $destino = "./uploadopm/$exame_id/$novonome";
        copy($origem, $destino);
        unlink($origem);
        redirect(base_url() . "ambulatorio/exame/anexarimagemmedico/$exame_id/$sala_id");
    }

    function excluirimagem($exame_id, $nome, $sala_id) {

        if (!is_dir("./uploadopm/$exame_id")) {
            mkdir("./uploadopm/$exame_id");
            $pasta = "./uploadopm/$exame_id";
            chmod($pasta, 0777);
        }
        $origem = "./upload/$exame_id/$nome";
        $destino = "./uploadopm/$exame_id/$nome";
        copy($origem, $destino);
        unlink($origem);
        redirect(base_url() . "ambulatorio/exame/anexarimagem/$exame_id/$sala_id");
    }

    function moverimagens($exame_id, $sala_id) {

        //HUMANA
        $this->load->helper('directory');
        if ($sala_id == 1) {

            //$arquivo_pasta = directory_map("/home/hamilton/teste/");
            $arquivo_pasta = directory_map("./upload/ultrasom1/");
            //$origem = "/home/hamilton/teste";
            $origem = "./upload/ultrasom1";
            foreach ($arquivo_pasta as $value) {
                $nova = substr($value, 11, 6);
                if (!is_dir("./upload/$exame_id")) {
                    mkdir("./upload/$exame_id");
                    $destino = "./upload/$exame_id/$nova";
                    chmod($destino, 0777);
                }
                $destino = "./upload/$exame_id/$nova";
                $local = "$origem/$value";
                copy($local, $destino);
            }
        }
        if ($sala_id == 2) {

            $arquivo_pasta = directory_map("./upload/ultrasom2/");
            $origem = "./upload/ultrasom2";
            foreach ($arquivo_pasta as $value) {
                $nova = substr($value, 11, 6);
                if (!is_dir("./upload/$exame_id")) {
                    mkdir("./upload/$exame_id");
                    $destino = "./upload/$exame_id/$nova";
                    chmod($destino, 0777);
                }
                $destino = "./upload/$exame_id/$nova";
                $local = "$origem/$value";
                copy($local, $destino);
            }
        }
        if ($sala_id == 9) {

            $arquivo_pasta = directory_map("./upload/ultrasom3/");
            $origem = "./upload/ultrasom3";
            foreach ($arquivo_pasta as $value) {
                $nova = substr($value, 8, 6);
                if (!is_dir("./upload/$exame_id")) {
                    mkdir("./upload/$exame_id");
                    $destino = "./upload/$exame_id/$nova";
                    chmod($destino, 0777);
                }
                $destino = "./upload/$exame_id/$nova";
                $local = "$origem/$value";
                copy($local, $destino);
            }
        }

        delete_files($origem);

        redirect(base_url() . "ambulatorio/exame/anexarimagem/$exame_id/$sala_id");

//        CAGE/GASTROSUL
//        
//        $this->load->helper('directory');
//        if ($sala_id == 1) {
//
//            //$arquivo_pasta = directory_map("/home/hamilton/teste/");
//            $arquivo_pasta = directory_map("./upload/ultrasom1/");
//            sort($arquivo_pasta);
//            //$origem = "/home/hamilton/teste";
//            $origem = "./upload/ultrasom1";
//            foreach ($arquivo_pasta as $value) {
//                $i++;
//                $nova = $i . ".jpg";
//                if (!is_dir("./upload/$exame_id")) {
//                    mkdir("./upload/$exame_id");
//                    $destino = "./upload/$exame_id/$nova";
//                    chmod($destino, 0777);
//                }
//                $destino = "./upload/$exame_id/$nova";
//                $local = "$origem/$value";
//                copy($local, $destino);
//            }
//        }
//        if ($sala_id == 2) {
//
//            $arquivo_pasta = directory_map("./upload/ultrasom2/");
//            sort($arquivo_pasta);
//            $origem = "./upload/ultrasom2";
//            foreach ($arquivo_pasta as $value) {
//                $i++;
//                $nova = $i . ".jpg";
//                if (!is_dir("./upload/$exame_id")) {
//                    mkdir("./upload/$exame_id");
//                    $destino = "./upload/$exame_id/$nova";
//                    chmod($destino, 0777);
//                }
//                $destino = "./upload/$exame_id/$nova";
//                $local = "$origem/$value";
//                copy($local, $destino);
//            }
//        }
//        if ($sala_id == 9) {
//
//            $arquivo_pasta = directory_map("./upload/ultrasom3/");
//            sort($arquivo_pasta);
//            $origem = "./upload/ultrasom3";
//            foreach ($arquivo_pasta as $value) {
//                $i++;
//                $nova = $i . ".jpg";
//                if (!is_dir("./upload/$exame_id")) {
//                    mkdir("./upload/$exame_id");
//                    $destino = "./upload/$exame_id/$nova";
//                    chmod($destino, 0777);
//                }
//                $destino = "./upload/$exame_id/$nova";
//                $local = "$origem/$value";
//                copy($local, $destino);
//            }
//        }
//
//        delete_files($origem);
//
//        redirect(base_url() . "ambulatorio/exame/anexarimagem/$exame_id/$sala_id");
    }

    function moverimagensmedico($exame_id, $sala_id) {
        //HUMANA
        //
        
//        
//        $this->load->helper('directory');
//        if ($sala_id == 1) {
//
//            //$arquivo_pasta = directory_map("/home/hamilton/teste/");
//            $arquivo_pasta = directory_map("./upload/ultrasom1/");
//            //$origem = "/home/hamilton/teste";
//            $origem = "./upload/ultrasom1";
//            foreach ($arquivo_pasta as $value) {
//
//                $nova = substr($value, 11, 6);
//
//                if (!is_dir("./upload/$exame_id")) {
//                    mkdir("./upload/$exame_id");
//                    $destino = "./upload/$exame_id/$nova";
//                    chmod($destino, 0777);
//                }
//                $destino = "./upload/$exame_id/$nova";
//                $local = "$origem/$value";
//                copy($local, $destino);
//            }
//        }
//        if ($sala_id == 2) {
//
//            $arquivo_pasta = directory_map("./upload/ultrasom2/");
//            $origem = "./upload/ultrasom2";
//            foreach ($arquivo_pasta as $value) {
//
//                $nova = substr($value, 11, 6);
//
//                if (!is_dir("./upload/$exame_id")) {
//                    mkdir("./upload/$exame_id");
//                    $destino = "./upload/$exame_id/$nova";
//                    chmod($destino, 0777);
//                }
//                $destino = "./upload/$exame_id/$nova";
//                $local = "$origem/$value";
//                copy($local, $destino);
//            }
//        }
//        if ($sala_id == 9) {
//
//            $arquivo_pasta = directory_map("./upload/ultrasom3/");
//            $origem = "./upload/ultrasom3";
//            foreach ($arquivo_pasta as $value) {
//
//                $nova = substr($value, 8, 6);
//
//                if (!is_dir("./upload/$exame_id")) {
//                    mkdir("./upload/$exame_id");
//                    $destino = "./upload/$exame_id/$nova";
//                    chmod($destino, 0777);
//                }
//                $destino = "./upload/$exame_id/$nova";
//                $local = "$origem/$value";
//                copy($local, $destino);
//            }
//        }
//
//        delete_files($origem);
//
//        redirect(base_url() . "ambulatorio/exame/anexarimagemmedico/$exame_id/$sala_id");
//      CAGE/GASTROSUL

        $this->load->helper('directory');
        $contador = directory_map("./upload/$exame_id/");
//        var_dump(count($contador)); die;
        if ($contador > 0) {
            $i = count($contador);
        } else {
            $i = 0;
        }
        if ($sala_id == 1) {

            //$arquivo_pasta = directory_map("/home/hamilton/teste/");
            $arquivo_pasta = directory_map("./upload/ultrasom1/");

            natcasesort($arquivo_pasta);

            $origem = "./upload/ultrasom1";
            foreach ($arquivo_pasta as $value) {
                $i++;
                $nova = $i . ".jpg";

                if (!is_dir("./upload/$exame_id")) {
                    mkdir("./upload/$exame_id");
                    $destino = "./upload/$exame_id/$nova";
                    chmod($destino, 0777);
                }
                $destino = "./upload/$exame_id/$nova";
                $local = "$origem/$value";
                copy($local, $destino);
            }
        }
        if ($sala_id == 2) {


            $arquivo_pasta = directory_map("./upload/ultrasom2/");
            natcasesort($arquivo_pasta);
            $origem = "./upload/ultrasom2";
            foreach ($arquivo_pasta as $value) {

                $i++;
                $nova = $i . ".jpg";

                if (!is_dir("./upload/$exame_id")) {
                    mkdir("./upload/$exame_id");
                    $destino = "./upload/$exame_id/$nova";
                    chmod($destino, 0777);
                }
                $destino = "./upload/$exame_id/$nova";
                $local = "$origem/$value";
                copy($local, $destino);
            }
        }
        if ($sala_id == 9) {

            $arquivo_pasta = directory_map("./upload/ultrasom3/");
            natcasesort($arquivo_pasta);
            $origem = "./upload/ultrasom3";
            foreach ($arquivo_pasta as $value) {

                $i++;
                $nova = $i . ".jpg";

                if (!is_dir("./upload/$exame_id")) {
                    mkdir("./upload/$exame_id");
                    $destino = "./upload/$exame_id/$nova";
                    chmod($destino, 0777);
                }
                $destino = "./upload/$exame_id/$nova";
                $local = "$origem/$value";
                copy($local, $destino);
            }
        }

        delete_files($origem);
        // die;

        redirect(base_url() . "ambulatorio/exame/anexarimagemmedico/$exame_id/$sala_id");
    }

    function restaurarimagem($exame_id, $nome) {



        $origem = "./uploadopm/$exame_id/$nome";
        $destino = "./upload/$exame_id/$nome";
        copy($origem, $destino);
        unlink($origem);
        redirect(base_url() . "ambulatorio/exame/anexarimagem/$exame_id");
    }

    function ordenarimagens($exame_id, $sala_id) {
        $this->load->helper('directory');
        $i = 1;
        $b = 1;
        $imagens = $_POST['teste'];
//        var_dump($imagens); die;
        foreach ($imagens as $value) {

            $origem = "./upload/$exame_id/$value";
            $destino = "./upload/$exame_id/$i-$value";
            copy($origem, $destino);
            unlink($origem);
            $i++;
        }
        $arquivo_pasta = directory_map("./upload/$exame_id");
        natcasesort($arquivo_pasta);
//        var_dump($arquivo_pasta);
//        die;

        foreach ($arquivo_pasta as $value) {
//            var_dump($value); die;
            $nova = $nova = $b . ".jpg";
            ;
            $oldname = "./upload/$exame_id/$value";
            $newname = "./upload/$exame_id/$nova";
            rename($oldname, $newname);
            $b++;
        }

        redirect(base_url() . "ambulatorio/exame/anexarimagemmedico/$exame_id/$sala_id");
    }

    function restaurarimagemmedico($exame_id, $nome, $sala_id) {
        $this->load->helper('directory');

        $contador = directory_map("./upload/$exame_id/");
        sort($contador);
        $teste = (int) end($contador);
        $numero = 1;
        foreach ($contador as $item) {
            $numero_for = (int) $item;
            if ($numero_for > $numero) {
                $numero = $numero_for;
            }
        }

        // var_dump($numero); die;

        if (count($contador > 0)) {
            $novonome = (int) $numero + 1 . '.jpg';
        } else {
            $novonome = $nome;
        }


        //    var_dump($novonome); die;

        $origem = "./uploadopm/$exame_id/$nome";
        $destino = "./upload/$exame_id/$novonome";
        copy($origem, $destino);
        unlink($origem);

        redirect(base_url() . "ambulatorio/exame/anexarimagemmedico/$exame_id/$sala_id");
    }

    function gravarpaciente() {
        $agenda_exame_id = $_POST['txtagenda_exames_id'];
        $verificar = $this->exame->gravarpaciente($agenda_exame_id);
        if ($verificar == "-1") {
            $data['mensagem'] = 'Erro ao marcar o Exame. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao marcar o Exame.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame");
    }

    function listaragendaexame($agenda_exames_nome_id) {

        $dia = date("Y-m-d");
        $data['diainicio'] = $dia;
        $data['agenda_exames_nome_id'] = $agenda_exames_nome_id;
        $data['dia1'] = $this->exame->listarexameagenda($dia, $agenda_exames_nome_id);
        $data['contadia1'] = $this->exame->contador($dia, $agenda_exames_nome_id);
        $data2 = date('d-m-Y', strtotime("+1 days", strtotime($dia)));
        $data['dia2'] = $this->exame->listarexameagenda($data2, $agenda_exames_nome_id);
        $data['contadia2'] = $this->exame->contador($data2, $agenda_exames_nome_id);
        $data3 = date('d-m-Y', strtotime("+2 days", strtotime($dia)));
        $data['dia3'] = $this->exame->listarexameagenda($data3, $agenda_exames_nome_id);
        $data['contadia3'] = $this->exame->contador($data3, $agenda_exames_nome_id);
        $data4 = date('d-m-Y', strtotime("+3 days", strtotime($dia)));
        $data['dia4'] = $this->exame->listarexameagenda($data4, $agenda_exames_nome_id);
        $data['contadia4'] = $this->exame->contador($data4, $agenda_exames_nome_id);
        $data5 = date('d-m-Y', strtotime("+4 days", strtotime($dia)));
        $data['dia5'] = $this->exame->listarexameagenda($data5, $agenda_exames_nome_id);
        $data['contadia5'] = $this->exame->contador($data5, $agenda_exames_nome_id);
        $data6 = date('d-m-Y', strtotime("+5 days", strtotime($dia)));
        $data['dia6'] = $this->exame->listarexameagenda($data6, $agenda_exames_nome_id);
        $data['contadia6'] = $this->exame->contador($data6, $agenda_exames_nome_id);
        $data7 = date('d-m-Y', strtotime("+6 days", strtotime($dia)));
        $data['dia7'] = $this->exame->listarexameagenda($data7, $agenda_exames_nome_id);
        $data['contadia7'] = $this->exame->contador($data7, $agenda_exames_nome_id);
        if ($data['contadia1'] != '0') {
            $data['repetidor'] = $data['dia1'];
        } elseif ($data['contadia2'] != '0') {
            $data['repetidor'] = $data['dia2'];
        } elseif ($data['contadia3'] != '0') {
            $data['repetidor'] = $data['dia3'];
        } elseif ($data['contadia4'] != '0') {
            $data['repetidor'] = $data['dia4'];
        } elseif ($data['contadia5'] != '0') {
            $data['repetidor'] = $data['dia5'];
        } elseif ($data['contadia6'] != '0') {
            $data['repetidor'] = $data['dia6'];
        } elseif ($data['contadia7'] != '0') {
            $data['repetidor'] = $data['dia7'];
        }
        $this->loadView('ambulatorio/exameagenda-lista', $data);

//            $this->carregarView($data);
    }

    function esquerda($dia, $agenda_exames_nome_id) {

        $data['diainicio'] = date('d-m-Y', strtotime("-7 days", strtotime($dia)));
        $dia = date('d-m-Y', strtotime("-7 days", strtotime($dia)));
        $data['agenda_exames_nome_id'] = $agenda_exames_nome_id;
        $data['dia1'] = $this->exame->listarexameagenda($dia, $agenda_exames_nome_id);
        $data['contadia1'] = $this->exame->contador($dia, $agenda_exames_nome_id);
        $data2 = date('d-m-Y', strtotime("+1 days", strtotime($dia)));
        $data['dia2'] = $this->exame->listarexameagenda($data2, $agenda_exames_nome_id);
        $data['contadia2'] = $this->exame->contador($data2, $agenda_exames_nome_id);
        $data3 = date('d-m-Y', strtotime("+2 days", strtotime($dia)));
        $data['dia3'] = $this->exame->listarexameagenda($data3, $agenda_exames_nome_id);
        $data['contadia3'] = $this->exame->contador($data3, $agenda_exames_nome_id);
        $data4 = date('d-m-Y', strtotime("+3 days", strtotime($dia)));
        $data['dia4'] = $this->exame->listarexameagenda($data4, $agenda_exames_nome_id);
        $data['contadia4'] = $this->exame->contador($data4, $agenda_exames_nome_id);
        $data5 = date('d-m-Y', strtotime("+4 days", strtotime($dia)));
        $data['dia5'] = $this->exame->listarexameagenda($data5, $agenda_exames_nome_id);
        $data['contadia5'] = $this->exame->contador($data5, $agenda_exames_nome_id);
        $data6 = date('d-m-Y', strtotime("+5 days", strtotime($dia)));
        $data['dia6'] = $this->exame->listarexameagenda($data6, $agenda_exames_nome_id);
        $data['contadia6'] = $this->exame->contador($data6, $agenda_exames_nome_id);
        $data7 = date('d-m-Y', strtotime("+6 days", strtotime($dia)));
        $data['dia7'] = $this->exame->listarexameagenda($data7, $agenda_exames_nome_id);
        $data['contadia7'] = $this->exame->contador($data7, $agenda_exames_nome_id);
        if ($data['contadia1'] != '0') {
            $data['repetidor'] = $data['dia1'];
        } elseif ($data['contadia2'] != '0') {
            $data['repetidor'] = $data['dia2'];
        } elseif ($data['contadia3'] != '0') {
            $data['repetidor'] = $data['dia3'];
        } elseif ($data['contadia4'] != '0') {
            $data['repetidor'] = $data['dia4'];
        } elseif ($data['contadia5'] != '0') {
            $data['repetidor'] = $data['dia5'];
        } elseif ($data['contadia6'] != '0') {
            $data['repetidor'] = $data['dia6'];
        } elseif ($data['contadia7'] != '0') {
            $data['repetidor'] = $data['dia7'];
        }

        $this->loadView('ambulatorio/exameagenda-lista', $data);
    }

    function direita($dia, $agenda_exames_nome_id) {

        $data['diainicio'] = date('d-m-Y', strtotime("+7 days", strtotime($dia)));
        $dia = date('d-m-Y', strtotime("+7 days", strtotime($dia)));
        $data['agenda_exames_nome_id'] = $agenda_exames_nome_id;
        $data['dia1'] = $this->exame->listarexameagenda($dia, $agenda_exames_nome_id);
        $data['contadia1'] = $this->exame->contador($dia, $agenda_exames_nome_id);
        $data2 = date('d-m-Y', strtotime("+1 days", strtotime($dia)));
        $data['dia2'] = $this->exame->listarexameagenda($data2, $agenda_exames_nome_id);
        $data['contadia2'] = $this->exame->contador($data2, $agenda_exames_nome_id);
        $data3 = date('d-m-Y', strtotime("+2 days", strtotime($dia)));
        $data['dia3'] = $this->exame->listarexameagenda($data3, $agenda_exames_nome_id);
        $data['contadia3'] = $this->exame->contador($data3, $agenda_exames_nome_id);
        $data4 = date('d-m-Y', strtotime("+3 days", strtotime($dia)));
        $data['dia4'] = $this->exame->listarexameagenda($data4, $agenda_exames_nome_id);
        $data['contadia4'] = $this->exame->contador($data4, $agenda_exames_nome_id);
        $data5 = date('d-m-Y', strtotime("+4 days", strtotime($dia)));
        $data['dia5'] = $this->exame->listarexameagenda($data5, $agenda_exames_nome_id);
        $data['contadia5'] = $this->exame->contador($data5, $agenda_exames_nome_id);
        $data6 = date('d-m-Y', strtotime("+5 days", strtotime($dia)));
        $data['dia6'] = $this->exame->listarexameagenda($data6, $agenda_exames_nome_id);
        $data['contadia6'] = $this->exame->contador($data6, $agenda_exames_nome_id);
        $data7 = date('d-m-Y', strtotime("+6 days", strtotime($dia)));
        $data['dia7'] = $this->exame->listarexameagenda($data7, $agenda_exames_nome_id);
        $data['contadia7'] = $this->exame->contador($data7, $agenda_exames_nome_id);
        if ($data['contadia1'] != '0') {
            $data['repetidor'] = $data['dia1'];
        } elseif ($data['contadia2'] != '0') {
            $data['repetidor'] = $data['dia2'];
        } elseif ($data['contadia3'] != '0') {
            $data['repetidor'] = $data['dia3'];
        } elseif ($data['contadia4'] != '0') {
            $data['repetidor'] = $data['dia4'];
        } elseif ($data['contadia5'] != '0') {
            $data['repetidor'] = $data['dia5'];
        } elseif ($data['contadia6'] != '0') {
            $data['repetidor'] = $data['dia6'];
        } elseif ($data['contadia7'] != '0') {
            $data['repetidor'] = $data['dia7'];
        }

        $this->loadView('ambulatorio/exameagenda-lista', $data);
    }

    function carregarprocedimento($procedimento_tuss_id) {
        $obj_procedimento = new procedimento_model($procedimento_tuss_id);
        $data['obj'] = $obj_procedimento;
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/procedimento-form', $data);
    }

    function novoagendageral($agenda_id) {
        $data['agenda_id'] = $agenda_id;
        $data['medico'] = $this->exame->listarmedico();
        $data['agenda'] = $this->agenda->listaratribuiragenda($agenda_id);
        $data['empresaPermissao'] = $this->guia->listarempresapermissoes();
        $data['tipo'] = $this->tipoconsulta->listartodos();
        $data['horarioagenda'] = $this->agenda->listarhorarioagenda($agenda_id);
        $this->loadView('ambulatorio/geral-form', $data);
    }

    function novoagendaexame($agenda_id) {
        $data['agenda_id'] = $agenda_id;
        $data['medico'] = $this->exame->listarmedico();
        $data['agenda'] = $this->agenda->listaratribuiragenda($agenda_id);
        $data['empresaPermissao'] = $this->guia->listarempresapermissoes();
        $data['horarioagenda'] = $this->agenda->listarhorarioagenda($agenda_id);
        $this->loadView('ambulatorio/exame-form', $data);
    }

    function novoagendaconsulta($agenda_id) {
        $data['agenda_id'] = $agenda_id;
        $data['medico'] = $this->exame->listarmedico();
        $data['agenda'] = $this->agenda->listaratribuiragenda($agenda_id);
        $data['horarioagenda'] = $this->agenda->listarhorarioagenda($agenda_id);
        $data['empresaPermissao'] = $this->guia->listarempresapermissoes();
        $data['tipo'] = $this->tipoconsulta->listartodos();
        $this->loadView('ambulatorio/consulta-form', $data);
    }

    function novoagendaespecializacao($agenda_id) {
        $data['agenda_id'] = $agenda_id;
        $data['medico'] = $this->exame->listarmedico();
        $data['agenda'] = $this->agenda->listaratribuiragenda($agenda_id);
        $data['horarioagenda'] = $this->agenda->listarhorarioagenda($agenda_id);
        $data['empresaPermissao'] = $this->guia->listarempresapermissoes();
        $data['tipo'] = $this->agenda->listarespecialidades();
        $this->loadView('ambulatorio/especializacao-form', $data);
    }

    function excluir($procedimento_tuss_id) {
        if ($this->procedimento->excluir($procedimento_tuss_id)) {
            $mensagem = 'Sucesso ao excluir o Procedimento';
        } else {
            $mensagem = 'Erro ao excluir o Procedimento. Opera&ccedil;&atilde;o cancelada.';
        }

        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/procedimento");
    }

    function gravarss() {
        $procedimento_tuss_id = $this->procedimento->gravar();
        if ($procedimento_tuss_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar o Procedimento. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar o Procedimento.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/procedimento");
    }

    function gravarintervalogeralmodelo2() {
        $datainicial = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['txtdatainicial'])));
        $datafinal = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['txtdatafinal'])));
        $intervalo = (int) $_POST['txtintervalo'];

        $qtd_horas_dia = $_POST['qtd_horas_dia'];
        $agenda_id = $_POST['txthorario'];
        $tipo_agenda_id = $_POST['tipo_agenda_id'];
        $medico_id = $_POST['txtmedico'];
        // Deletando os horários para recria-los caso a agenda seja consolidada novamente
        $deletarHorarios = $this->agenda->deletaragendacriacaogeralmodelo2($agenda_id);
        // Gravando as informacoes de data inicial/final
        $gravarAgenda = $this->agenda->gravarAgendaData($agenda_id);
        // echo '<pre>';
        // var_dump($_POST); die;

        if ($intervalo != '' && $intervalo > 0) {

            // Data inicial
            $datainicial_intervalo = $datainicial;

            // Pega a quantidade de dias que faltam até encerrar a semana da data inicial
            $diasAteFimDaSemana = 6 - date("N", strtotime($datainicial));

            // Data equivalente ao ultimo dia da semana da data inicial      
            $datafinal_intervalo = date('Y-m-d', strtotime("+$diasAteFimDaSemana days", strtotime($datainicial)));

            while (strtotime($datafinal_intervalo) <= strtotime($datafinal)) {

                $this->gravargeralmodelo2($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id, $qtd_horas_dia);

                // Avança o marcador inicial para o primeiro dia da semana seguinte
                $datainicial_intervalo = date('Y-m-d', strtotime("+" . $intervalo . " week 1 day", strtotime($datafinal_intervalo)));

                // Avança o marcador final para o ultimo dia da semana seguinte
                $datafinal_intervalo = date('Y-m-d', strtotime("+" . $intervalo + 1 . " week", strtotime($datafinal_intervalo)));

                if (strtotime($datafinal_intervalo) >= strtotime($datafinal)) {
                    // Caso o marcador final fique maior que a data final informada na criaçao da agenda, ele repoe o valor do marcador
                    // Pela data maxima que a agenda pode ser criada (ou seja, a data que o usuario informou)
                    $datafinal_intervalo = $datafinal;

                    // Em seguida ele salva e sai do laço
                    $this->gravargeralmodelo2($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id, $qtd_horas_dia);
                    break; // Se tirar esse trecho, ele irá ficar no loop eternamente
                }
            }
        } else {
            $this->gravargeralmodelo2($datainicial, $datafinal, $agenda_id, $medico_id, $qtd_horas_dia);
        }
        $data['mensagem'] = 'Sucesso ao gravar o Agenda.';

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/agenda/novohorarioagendamodelo2/$agenda_id");
    }

    function gravargeralmodelo2($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id, $qtd_horas_dia) {
        //        var_dump($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $sala_id, $medico_id);
        //        die;
        //        $agenda_id = $_POST['txthorario'];
        //        $sala_id = $_POST['txtsala'];
        //        $medico_id = $_POST['txtmedico'];
        $datainicial = date("Y-m-d", strtotime(str_replace("/", "-", $datainicial_intervalo)));
        $datafinal = date("Y-m-d", strtotime(str_replace("/", "-", $datafinal_intervalo)));
        $nome = $_POST['txtNome'];

        $tipo = $this->agenda->listartiposala();
        $tipo = $tipo[0]->tipo;


        $horarioagenda = $this->agenda->listarhorarioagendacriacaogeralmodelo2($agenda_id, $medico_id, $datainicial, $datafinal, $tipo);
//                 echo '<pre>';
//                 var_dump($horarioagenda); die;
        $id = 0;

        foreach ($horarioagenda as $item) {

            $tempoconsulta = $item->tempoconsulta;
            $qtdeconsulta = $item->qtdeconsulta;
            $qtdeconsulta = (int) $qtdeconsulta;
            $horarioagenda_id = $item->horarioagenda_id;
            $empresa_id = $item->empresa_id;
            $obs = $item->observacoes;
            $sala_id = $item->sala_id;

            if (($qtdeconsulta != 0) && ($item->intervaloinicio == "00:00:00")) {
                $entrada = $item->horaentrada1;
                $saida = $item->horasaida1;
                $hora1 = explode(":", $entrada);
                $hora2 = explode(":", $saida);
                $acumulador1 = ($hora1[0] * 60) + $hora1[1];
                $acumulador2 = ($hora2[0] * 60) + $hora2[1];
                $resultado = $acumulador2 - $acumulador1;
                $tempoconsulta = $resultado / $item->qtdeconsulta;
                $tempoconsulta = (int) $tempoconsulta + 1;
            }
            if (($qtdeconsulta != 0) && ($item->intervaloinicio != "00:00:00")) {
                $entrada = $item->horaentrada1;
                $saida = $item->horasaida1;
                $intervaloinicio = $item->intervaloinicio;
                $intervalofim = $item->intervalofim;
                $hora1 = explode(":", $entrada);
                $hora2 = explode(":", $saida);
                $horainicio = explode(":", $intervaloinicio);
                $horafim = explode(":", $intervalofim);
                $acumulador1 = ($hora1[0] * 60) + $hora1[1];
                $acumulador2 = ($hora2[0] * 60) + $hora2[1];
                $acumulador3 = ($horainicio[0] * 60) + $horainicio[1];
                $acumulador4 = ($horafim[0] * 60) + $horafim[1];
                $resultado = ($acumulador3 - $acumulador1) + ($acumulador2 - $acumulador4);
                $tempoconsulta = $resultado / $item->qtdeconsulta;
                $tempoconsulta = (int) $tempoconsulta + 1;
            }

            for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                $data = strftime("%A", strtotime($index));

                switch ($data) {
                    case"Sunday": $data = "Domingo";
                        break;
                    case"Monday": $data = "Segunda";
                        break;
                    case"Tuesday": $data = "Terça";
                        break;
                    case"Wednesday": $data = "Quarta";
                        break;
                    case"Thursday": $data = "Quinta";
                        break;
                    case"Friday": $data = "Sexta";
                        break;
                    case"Saturday": $data = "Sabado";
                        break;
                }
                $i = 0;
                $horaconsulta = 0;
                $horaverifica = 0;
                $horasaida = 0;
                if ($data == substr($item->dia, 4)) {
                    for ($horaindex = $item->horaentrada1; $horaindex <= $item->horasaida1; $horaindex = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaindex)))) {

                        if ($item->intervaloinicio == "00:00:00") {
                            if ($i == 0) {
                                $horaconsulta = date('H:i:s', strtotime($item->horaentrada1));
                                $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($item->horaentrada1)));
                                $i = 1;
                                if ($id == 0) {
                                    $id = $this->exame->gravarnome($nome);
                                }
                                $this->exame->gravargeralmodelo2($horarioagenda_id, $agenda_id, $horaconsulta, $horaverifica, $nome, $datainicial, $datafinal, $index, $sala_id, $id, $medico_id, $empresa_id, $obs, $tipo, $qtd_horas_dia);
                            }
                            if (( $horaverifica < $item->horasaida1)) {
                                $x = 1;
                                $horaconsulta = $horaverifica;
                                $horasaida = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                                $this->exame->gravargeralmodelo2($horarioagenda_id, $agenda_id, $horaconsulta, $horasaida, $nome, $datainicial, $datafinal, $index, $sala_id, $id, $medico_id, $empresa_id, $obs, $tipo, $qtd_horas_dia);
                            }
                            $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                        } else {
                            if ($i == 0) {
                                $horaconsulta = date('H:i:s', strtotime($item->horaentrada1));
                                $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($item->horaentrada1)));
                                $i = 1;
                                if ($id == 0) {
                                    $id = $this->exame->gravarnome($nome);
                                }
                                $this->exame->gravargeralmodelo2($horarioagenda_id, $agenda_id, $horaconsulta, $horaverifica, $nome, $datainicial, $datafinal, $index, $sala_id, $id, $medico_id, $empresa_id, $obs, $tipo, $qtd_horas_dia);
                            }
                            if ((($horaverifica < $item->intervaloinicio) || ($horaverifica >= $item->intervalofim)) && ( $horaverifica < $item->horasaida1)) {
                                $x = 1;
                                $horaconsulta = $horaverifica;
                                $horasaida = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                                $this->exame->gravargeralmodelo2($horarioagenda_id, $agenda_id, $horaconsulta, $horasaida, $nome, $datainicial, $datafinal, $index, $sala_id, $id, $medico_id, $empresa_id, $obs, $tipo, $qtd_horas_dia);
                            }
                            $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                        }
                    }
                }
            }
        }

        //        $data['mensagem'] = 'Sucesso ao gravar o Agenda.';
        //        $this->session->set_flashdata('message', $data['mensagem']);
        //        redirect(base_url() . "ambulatorio/agenda");
    }

    function gravarintervalogeral() {
        $datainicial = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['txtdatainicial'])));
        $datafinal = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['txtdatafinal'])));
        $intervalo = (int) $_POST['txtintervalo'];

        $agenda_id = $_POST['txthorario'];
//        $sala_id = $_POST['txtsala'];
        $medico_id = $_POST['txtmedico'];

        if ($intervalo != '' && $intervalo > 0) {

            // Data inicial
            $datainicial_intervalo = $datainicial;

            // Pega a quantidade de dias que faltam até encerrar a semana da data inicial
            $diasAteFimDaSemana = 6 - date("N", strtotime($datainicial));

            // Data equivalente ao ultimo dia da semana da data inicial      
            $datafinal_intervalo = date('Y-m-d', strtotime("+$diasAteFimDaSemana days", strtotime($datainicial)));

            while (strtotime($datafinal_intervalo) <= strtotime($datafinal)) {

                $this->gravargeral($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id);

                // Avança o marcador inicial para o primeiro dia da semana seguinte
                $datainicial_intervalo = date('Y-m-d', strtotime("+" . $intervalo . " week 1 day", strtotime($datafinal_intervalo)));

                // Avança o marcador final para o ultimo dia da semana seguinte
                $datafinal_intervalo = date('Y-m-d', strtotime("+" . $intervalo + 1 . " week", strtotime($datafinal_intervalo)));

                if (strtotime($datafinal_intervalo) >= strtotime($datafinal)) {
                    // Caso o marcador final fique maior que a data final informada na criaçao da agenda, ele repoe o valor do marcador
                    // Pela data maxima que a agenda pode ser criada (ou seja, a data que o usuario informou)
                    $datafinal_intervalo = $datafinal;

                    // Em seguida ele salva e sai do laço
                    $this->gravargeral($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id);
                    break; // Se tirar esse trecho, ele irá ficar no loop eternamente
                }
            }
        } else {
            $this->gravargeral($datainicial, $datafinal, $agenda_id, $medico_id);
        }
        $data['mensagem'] = 'Sucesso ao gravar o Agenda.';

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/agenda");
    }

    function gravarintervaloespecialidade() {
        $datainicial = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['txtdatainicial'])));
        $datafinal = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['txtdatafinal'])));
        $intervalo = (int) $_POST['txtintervalo'];

        $agenda_id = $_POST['txthorario'];
        $sala_id = $_POST['txtsala'];
        $medico_id = $_POST['txtmedico'];

        if ($intervalo != '' && $intervalo > 0) {

            // Data inicial
            $datainicial_intervalo = $datainicial;

            // Pega a quantidade de dias que faltam até encerrar a semana da data inicial
            $diasAteFimDaSemana = 6 - date("N", strtotime($datainicial));

            // Data equivalente ao ultimo dia da semana da data inicial      
            $datafinal_intervalo = date('Y-m-d', strtotime("+$diasAteFimDaSemana days", strtotime($datainicial)));

            while (strtotime($datafinal_intervalo) <= strtotime($datafinal)) {

                $this->gravarespecialidade($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id);

                // Avança o marcador inicial para o primeiro dia da semana seguinte
                $datainicial_intervalo = date('Y-m-d', strtotime("+" . $intervalo . " week 1 day", strtotime($datafinal_intervalo)));

                // Avança o marcador final para o ultimo dia da semana seguinte
                $datafinal_intervalo = date('Y-m-d', strtotime("+" . $intervalo + 1 . " week", strtotime($datafinal_intervalo)));

                if (strtotime($datafinal_intervalo) >= strtotime($datafinal)) {
                    // Caso o marcador final fique maior que a data final informada na criaçao da agenda, ele repoe o valor do marcador
                    // Pela data maxima que a agenda pode ser criada (ou seja, a data que o usuario informou)
                    $datafinal_intervalo = $datafinal;

                    // Em seguida ele salva e sai do laço
                    $this->gravarespecialidade($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id);
                    break; // Se tirar esse trecho, ele irá ficar no loop eternamente
                }
            }
        } else {
            $this->gravarespecialidade($datainicial, $datafinal, $agenda_id, $medico_id);
        }
        $data['mensagem'] = 'Sucesso ao gravar o Agenda.';

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/agenda");
    }

    function gravarintervaloconsulta() {
        $datainicial = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['txtdatainicial'])));
        $datafinal = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['txtdatafinal'])));
        $intervalo = (int) $_POST['txtintervalo'];

        $agenda_id = $_POST['txthorario'];
        $sala_id = $_POST['txtsala'];
        $medico_id = $_POST['txtmedico'];

        if ($intervalo != '' && $intervalo > 0) {

            // Data inicial
            $datainicial_intervalo = $datainicial;

            // Pega a quantidade de dias que faltam até encerrar a semana da data inicial
            $diasAteFimDaSemana = 6 - date("N", strtotime($datainicial));

            // Data equivalente ao ultimo dia da semana da data inicial      
            $datafinal_intervalo = date('Y-m-d', strtotime("+$diasAteFimDaSemana days", strtotime($datainicial)));

            while (strtotime($datafinal_intervalo) <= strtotime($datafinal)) {

                $this->gravarconsulta($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id);

                // Avança o marcador inicial para o primeiro dia da semana seguinte
                $datainicial_intervalo = date('Y-m-d', strtotime("+" . $intervalo . " week 1 day", strtotime($datafinal_intervalo)));

                // Avança o marcador final para o ultimo dia da semana seguinte
                $datafinal_intervalo = date('Y-m-d', strtotime("+" . $intervalo + 1 . " week", strtotime($datafinal_intervalo)));

                if (strtotime($datafinal_intervalo) >= strtotime($datafinal)) {
                    // Caso o marcador final fique maior que a data final informada na criaçao da agenda, ele repoe o valor do marcador
                    // Pela data maxima que a agenda pode ser criada (ou seja, a data que o usuario informou)
                    $datafinal_intervalo = $datafinal;

                    // Em seguida ele salva e sai do laço
                    $this->gravarconsulta($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id);
                    break; // Se tirar esse trecho, ele irá ficar no loop eternamente
                }
            }
        } else {
            $this->gravarconsulta($datainicial, $datafinal, $agenda_id, $medico_id);
        }
        $data['mensagem'] = 'Sucesso ao gravar o Agenda.';

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/agenda");
    }

    function gravarintervalo() {
        $datainicial = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['txtdatainicial'])));
        $datafinal = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['txtdatafinal'])));
        $intervalo = (int) $_POST['txtintervalo'];

        $agenda_id = $_POST['txthorario'];
//        $sala_id = $_POST['txtsala'];
        $medico_id = $_POST['txtmedico'];

        if ($intervalo != '' && $intervalo > 0) {

            // Data inicial
            $datainicial_intervalo = $datainicial;

            // Pega a quantidade de dias que faltam até encerrar a semana da data inicial
            $diasAteFimDaSemana = 6 - date("N", strtotime($datainicial));

            // Data equivalente ao ultimo dia da semana da data inicial      
            $datafinal_intervalo = date('Y-m-d', strtotime("+$diasAteFimDaSemana days", strtotime($datainicial)));

            while (strtotime($datafinal_intervalo) <= strtotime($datafinal)) {

                $this->gravar($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id);

                // Avança o marcador inicial para o primeiro dia da semana seguinte
                $datainicial_intervalo = date('Y-m-d', strtotime("+" . $intervalo . " week 1 day", strtotime($datafinal_intervalo)));

                // Avança o marcador final para o ultimo dia da semana seguinte
                $datafinal_intervalo = date('Y-m-d', strtotime("+" . $intervalo + 1 . " week", strtotime($datafinal_intervalo)));

                if (strtotime($datafinal_intervalo) >= strtotime($datafinal)) {
                    // Caso o marcador final fique maior que a data final informada na criaçao da agenda, ele repoe o valor do marcador
                    // Pela data maxima que a agenda pode ser criada (ou seja, a data que o usuario informou)
                    $datafinal_intervalo = $datafinal;

                    // Em seguida ele salva e sai do laço
                    $this->gravar($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id);
                    break; // Se tirar esse trecho, ele irá ficar no loop eternamente
                }
            }
        } else {
            $this->gravar($datainicial, $datafinal, $agenda_id, $medico_id);
        }
        $data['mensagem'] = 'Sucesso ao gravar o Agenda.';

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/agenda");
    }

    function gravar($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id) {

//        $agenda_id = $_POST['txthorario'];
//        $sala_id = $_POST['txtsala'];
//        $medico_id = $_POST['txtmedico'];
        $datainicial = date("Y-m-d", strtotime(str_replace("/", "-", $datainicial_intervalo)));
        $datafinal = date("Y-m-d", strtotime(str_replace("/", "-", $datafinal_intervalo)));
        $nome = $_POST['txtNome'];
        $tipo = 'EXAME';
        $horarioagenda = $this->agenda->listarhorarioagendacriacao($agenda_id, $medico_id, $datainicial, $datafinal, $tipo);
//        var_dump($horarioagenda); die;
        $id = 0;

        foreach ($horarioagenda as $item) {

            $tempoconsulta = $item->tempoconsulta;
            $qtdeconsulta = $item->qtdeconsulta;
            $qtdeconsulta = (int) $qtdeconsulta;
            $horarioagenda_id = $item->horarioagenda_id;
            $empresa_id = $item->empresa_id;
            $sala_id = $item->sala_id;
            $obs = $item->observacoes;

            if (($qtdeconsulta != 0) && ($item->intervaloinicio == "00:00:00")) {
                $entrada = $item->horaentrada1;
                $saida = $item->horasaida1;
                $hora1 = explode(":", $entrada);
                $hora2 = explode(":", $saida);
                $acumulador1 = ($hora1[0] * 60) + $hora1[1];
                $acumulador2 = ($hora2[0] * 60) + $hora2[1];
                $resultado = $acumulador2 - $acumulador1;
                $tempoconsulta = $resultado / $item->qtdeconsulta;
                $tempoconsulta = (int) $tempoconsulta + 1;
            }
            if (($qtdeconsulta != 0) && ($item->intervaloinicio != "00:00:00")) {
                $entrada = $item->horaentrada1;
                $saida = $item->horasaida1;
                $intervaloinicio = $item->intervaloinicio;
                $intervalofim = $item->intervalofim;
                $hora1 = explode(":", $entrada);
                $hora2 = explode(":", $saida);
                $horainicio = explode(":", $intervaloinicio);
                $horafim = explode(":", $intervalofim);
                $acumulador1 = ($hora1[0] * 60) + $hora1[1];
                $acumulador2 = ($hora2[0] * 60) + $hora2[1];
                $acumulador3 = ($horainicio[0] * 60) + $horainicio[1];
                $acumulador4 = ($horafim[0] * 60) + $horafim[1];
                $resultado = ($acumulador3 - $acumulador1) + ($acumulador2 - $acumulador4);
                $tempoconsulta = $resultado / $item->qtdeconsulta;
                $tempoconsulta = (int) $tempoconsulta + 1;
            }

            for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                $data = strftime("%A", strtotime($index));

                switch ($data) {
                    case"Sunday": $data = "Domingo";
                        break;
                    case"Monday": $data = "Segunda";
                        break;
                    case"Tuesday": $data = "Terça";
                        break;
                    case"Wednesday": $data = "Quarta";
                        break;
                    case"Thursday": $data = "Quinta";
                        break;
                    case"Friday": $data = "Sexta";
                        break;
                    case"Saturday": $data = "Sabado";
                        break;
                }
                $i = 0;
                $horaconsulta = 0;
                $horaverifica = 0;
                $horasaida = 0;
                if ($data == substr($item->dia, 4)) {
                    for ($horaindex = $item->horaentrada1; $horaindex <= $item->horasaida1; $horaindex = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaindex)))) {

                        if ($item->intervaloinicio == "00:00:00") {
                            if ($i == 0) {
                                $horaconsulta = date('H:i:s', strtotime($item->horaentrada1));
                                $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($item->horaentrada1)));
                                $i = 1;
                                if ($id == 0) {
                                    $id = $this->exame->gravarnome($nome);
                                }
                                $this->exame->gravar($horarioagenda_id, $agenda_id, $horaconsulta, $horaverifica, $nome, $datainicial, $datafinal, $index, $sala_id, $id, $medico_id, $empresa_id, $obs);
                            }
                            if (( $horaverifica < $item->horasaida1)) {
                                $x = 1;
                                $horaconsulta = $horaverifica;
                                $horasaida = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                                $this->exame->gravar($horarioagenda_id, $agenda_id, $horaconsulta, $horasaida, $nome, $datainicial, $datafinal, $index, $sala_id, $id, $medico_id, $empresa_id, $obs);
                            }
                            $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                        } else {
                            if ($i == 0) {
                                $horaconsulta = date('H:i:s', strtotime($item->horaentrada1));
                                $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($item->horaentrada1)));
                                $i = 1;
                                if ($id == 0) {
                                    $id = $this->exame->gravarnome($nome);
                                }
                                $this->exame->gravar($horarioagenda_id, $agenda_id, $horaconsulta, $horaverifica, $nome, $datainicial, $datafinal, $index, $sala_id, $id, $medico_id, $empresa_id, $obs);
                            }
                            if ((($horaverifica < $item->intervaloinicio) || ($horaverifica >= $item->intervalofim)) && ( $horaverifica < $item->horasaida1)) {
                                $x = 1;
                                $horaconsulta = $horaverifica;
                                $horasaida = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                                $this->exame->gravar($horarioagenda_id, $agenda_id, $horaconsulta, $horasaida, $nome, $datainicial, $datafinal, $index, $sala_id, $id, $medico_id, $empresa_id, $obs);
                            }
                            $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                        }
                    }
                }
            }
        }

//        $data['mensagem'] = 'Sucesso ao gravar o Agenda.';
//        $this->session->set_flashdata('message', $data['mensagem']);
//        redirect(base_url() . "ambulatorio/agenda");
    }

    function gravargeral($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id) {
//        var_dump($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $sala_id, $medico_id);
//        die;
//        $agenda_id = $_POST['txthorario'];
//        $sala_id = $_POST['txtsala'];
//        $medico_id = $_POST['txtmedico'];
        $datainicial = date("Y-m-d", strtotime(str_replace("/", "-", $datainicial_intervalo)));
        $datafinal = date("Y-m-d", strtotime(str_replace("/", "-", $datafinal_intervalo)));
        $nome = $_POST['txtNome'];

        $tipo = $this->agenda->listartiposala();
        $tipo = $tipo[0]->tipo;
        $horarioagenda = $this->agenda->listarhorarioagendacriacaogeral($agenda_id, $medico_id, $datainicial, $datafinal, $tipo);
        $id = 0;

        foreach ($horarioagenda as $item) {

            $tempoconsulta = $item->tempoconsulta;
            $qtdeconsulta = $item->qtdeconsulta;
            $qtdeconsulta = (int) $qtdeconsulta;
            $horarioagenda_id = $item->horarioagenda_id;
            $empresa_id = $item->empresa_id;
            $obs = $item->observacoes;
            $sala_id = $item->sala_id;

            if (($qtdeconsulta != 0) && ($item->intervaloinicio == "00:00:00")) {
                $entrada = $item->horaentrada1;
                $saida = $item->horasaida1;
                $hora1 = explode(":", $entrada);
                $hora2 = explode(":", $saida);
                $acumulador1 = ($hora1[0] * 60) + $hora1[1];
                $acumulador2 = ($hora2[0] * 60) + $hora2[1];
                $resultado = $acumulador2 - $acumulador1;
                $tempoconsulta = $resultado / $item->qtdeconsulta;
                $tempoconsulta = (int) $tempoconsulta + 1;
            }
            if (($qtdeconsulta != 0) && ($item->intervaloinicio != "00:00:00")) {
                $entrada = $item->horaentrada1;
                $saida = $item->horasaida1;
                $intervaloinicio = $item->intervaloinicio;
                $intervalofim = $item->intervalofim;
                $hora1 = explode(":", $entrada);
                $hora2 = explode(":", $saida);
                $horainicio = explode(":", $intervaloinicio);
                $horafim = explode(":", $intervalofim);
                $acumulador1 = ($hora1[0] * 60) + $hora1[1];
                $acumulador2 = ($hora2[0] * 60) + $hora2[1];
                $acumulador3 = ($horainicio[0] * 60) + $horainicio[1];
                $acumulador4 = ($horafim[0] * 60) + $horafim[1];
                $resultado = ($acumulador3 - $acumulador1) + ($acumulador2 - $acumulador4);
                $tempoconsulta = $resultado / $item->qtdeconsulta;
                $tempoconsulta = (int) $tempoconsulta + 1;
            }

            for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                $data = strftime("%A", strtotime($index));

                switch ($data) {
                    case"Sunday": $data = "Domingo";
                        break;
                    case"Monday": $data = "Segunda";
                        break;
                    case"Tuesday": $data = "Terça";
                        break;
                    case"Wednesday": $data = "Quarta";
                        break;
                    case"Thursday": $data = "Quinta";
                        break;
                    case"Friday": $data = "Sexta";
                        break;
                    case"Saturday": $data = "Sabado";
                        break;
                }
                $i = 0;
                $horaconsulta = 0;
                $horaverifica = 0;
                $horasaida = 0;
                if ($data == substr($item->dia, 4)) {
                    for ($horaindex = $item->horaentrada1; $horaindex <= $item->horasaida1; $horaindex = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaindex)))) {

                        if ($item->intervaloinicio == "00:00:00") {
                            if ($i == 0) {
                                $horaconsulta = date('H:i:s', strtotime($item->horaentrada1));
                                $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($item->horaentrada1)));
                                $i = 1;
                                if ($id == 0) {
                                    $id = $this->exame->gravarnome($nome);
                                }
                                $this->exame->gravargeral($horarioagenda_id, $agenda_id, $horaconsulta, $horaverifica, $nome, $datainicial, $datafinal, $index, $sala_id, $id, $medico_id, $empresa_id, $obs, $tipo);
                            }
                            if (( $horaverifica < $item->horasaida1)) {
                                $x = 1;
                                $horaconsulta = $horaverifica;
                                $horasaida = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                                $this->exame->gravargeral($horarioagenda_id, $agenda_id, $horaconsulta, $horasaida, $nome, $datainicial, $datafinal, $index, $sala_id, $id, $medico_id, $empresa_id, $obs, $tipo);
                            }
                            $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                        } else {
                            if ($i == 0) {
                                $horaconsulta = date('H:i:s', strtotime($item->horaentrada1));
                                $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($item->horaentrada1)));
                                $i = 1;
                                if ($id == 0) {
                                    $id = $this->exame->gravarnome($nome);
                                }
                                $this->exame->gravargeral($horarioagenda_id, $agenda_id, $horaconsulta, $horaverifica, $nome, $datainicial, $datafinal, $index, $sala_id, $id, $medico_id, $empresa_id, $obs, $tipo);
                            }
                            if ((($horaverifica < $item->intervaloinicio) || ($horaverifica >= $item->intervalofim)) && ( $horaverifica < $item->horasaida1)) {
                                $x = 1;
                                $horaconsulta = $horaverifica;
                                $horasaida = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                                $this->exame->gravargeral($horarioagenda_id, $agenda_id, $horaconsulta, $horasaida, $nome, $datainicial, $datafinal, $index, $sala_id, $id, $medico_id, $empresa_id, $obs, $tipo);
                            }
                            $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                        }
                    }
                }
            }
        }

//        $data['mensagem'] = 'Sucesso ao gravar o Agenda.';
//        $this->session->set_flashdata('message', $data['mensagem']);
//        redirect(base_url() . "ambulatorio/agenda");
    }

    function gravarconsulta($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id) {
//        $agenda_id = $_POST['txthorario'];
//        $medico_id = $_POST['txtmedico'];
        $datainicial = date("Y-m-d", strtotime(str_replace("/", "-", $datainicial_intervalo)));
        $datafinal = date("Y-m-d", strtotime(str_replace("/", "-", $datafinal_intervalo)));
        $nome = $_POST['txtNome'];
        $tipo = 'CONSULTA';
        $horarioagenda = $this->agenda->listarhorarioagendacriacao($agenda_id, $medico_id, $datainicial, $datafinal, $tipo);
//        var_dump($horarioagenda); die;
        $id = 0;

        foreach ($horarioagenda as $item) {

            $observacoes = $item->observacoes;
            $horarioagenda_id = $item->horarioagenda_id;
            $empresa_id = $item->empresa_id;
            $tempoconsulta = $item->tempoconsulta;
            $qtdeconsulta = $item->qtdeconsulta;
            $qtdeconsulta = (int) $qtdeconsulta;

            if (($qtdeconsulta != 0) && ($item->intervaloinicio == "00:00:00")) {
                $entrada = $item->horaentrada1;
                $saida = $item->horasaida1;
                $hora1 = explode(":", $entrada);
                $hora2 = explode(":", $saida);
                $acumulador1 = ($hora1[0] * 60) + $hora1[1];
                $acumulador2 = ($hora2[0] * 60) + $hora2[1];
                $resultado = $acumulador2 - $acumulador1;
                $tempoconsulta = $resultado / $item->qtdeconsulta;
                $tempoconsulta = (int) $tempoconsulta + 1;
            }
            if (($qtdeconsulta != 0) && ($item->intervaloinicio != "00:00:00")) {
                $entrada = $item->horaentrada1;
                $saida = $item->horasaida1;
                $intervaloinicio = $item->intervaloinicio;
                $intervalofim = $item->intervalofim;
                $hora1 = explode(":", $entrada);
                $hora2 = explode(":", $saida);
                $horainicio = explode(":", $intervaloinicio);
                $horafim = explode(":", $intervalofim);
                $acumulador1 = ($hora1[0] * 60) + $hora1[1];
                $acumulador2 = ($hora2[0] * 60) + $hora2[1];
                $acumulador3 = ($horainicio[0] * 60) + $horainicio[1];
                $acumulador4 = ($horafim[0] * 60) + $horafim[1];
                $resultado = ($acumulador3 - $acumulador1) + ($acumulador2 - $acumulador4);
                $tempoconsulta = $resultado / $item->qtdeconsulta;
                $tempoconsulta = (int) $tempoconsulta + 1;
            }

            for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {
//                var_dump($index); die;
                $data = strftime("%A", strtotime($index));

                switch ($data) {
                    case"Sunday": $data = "Domingo";
                        break;
                    case"Monday": $data = "Segunda";
                        break;
                    case"Tuesday": $data = "Terça";
                        break;
                    case"Wednesday": $data = "Quarta";
                        break;
                    case"Thursday": $data = "Quinta";
                        break;
                    case"Friday": $data = "Sexta";
                        break;
                    case"Saturday": $data = "Sabado";
                        break;
                }
                $i = 0;
                $horaconsulta = 0;
                $horaverifica = 0;
                $horasaida = 0;
                if ($data == substr($item->dia, 4)) {
                    for ($horaindex = $item->horaentrada1; $horaindex <= $item->horasaida1; $horaindex = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaindex)))) {

                        if ($item->intervaloinicio == "00:00:00") {
                            if ($i == 0) {
                                $horaconsulta = date('H:i:s', strtotime($item->horaentrada1));
                                $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($item->horaentrada1)));
                                $i = 1;
                                if ($id == 0) {
                                    $id = $this->exame->gravarnome($nome);
                                }
                                $this->exame->gravarconsulta($horarioagenda_id, $agenda_id, $horaconsulta, $horaverifica, $nome, $datainicial, $datafinal, $index, $medico_id, $id, $observacoes, $empresa_id);
                            }
                            if (( $horaverifica < $item->horasaida1)) {
                                $x = 1;
                                $horaconsulta = $horaverifica;
                                $horasaida = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                                $this->exame->gravarconsulta($horarioagenda_id, $agenda_id, $horaconsulta, $horasaida, $nome, $datainicial, $datafinal, $index, $medico_id, $id, $observacoes, $empresa_id);
                            }
                            $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                        } else {
                            if ($i == 0) {
                                $horaconsulta = date('H:i:s', strtotime($item->horaentrada1));
                                $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($item->horaentrada1)));
                                $i = 1;
                                if ($id == 0) {
                                    $id = $this->exame->gravarnome($nome);
                                }
                                $this->exame->gravarconsulta($horarioagenda_id, $agenda_id, $horaconsulta, $horaverifica, $nome, $datainicial, $datafinal, $index, $medico_id, $id, $observacoes, $empresa_id);
                            }
                            if ((($horaverifica < $item->intervaloinicio) || ($horaverifica >= $item->intervalofim)) && ( $horaverifica < $item->horasaida1)) {
                                $x = 1;
                                $horaconsulta = $horaverifica;
                                $horasaida = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                                $this->exame->gravarconsulta($horarioagenda_id, $agenda_id, $horaconsulta, $horasaida, $nome, $datainicial, $datafinal, $index, $medico_id, $id, $observacoes, $empresa_id);
                            }
                            $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                        }
                    }
                }
            }
        }

//        $data['mensagem'] = 'Sucesso ao gravar o Agenda.';
//        $this->session->set_flashdata('message', $data['mensagem']);
//        redirect(base_url() . "ambulatorio/agenda");
    }

    function gravaralteracaoagendacriada() {
        $dados = $this->exame->listardadosagendacriada();
        if (count($dados) == 0) {

            $data['mensagem'] = 'Erro. Todos os registros associados a essa agenda já foram excluidos. Por favor, crie uma nova agenda!';

            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/agenda");
        }

        $agenda_id = $_GET['agenda_id'];
        $medico_id = $_GET['medico_id'];
        $nome = $_GET['nome_agenda'];
        $tipo_consulta_id = $dados[0]->tipo_consulta_id;
        $sala_id = $dados[0]->agenda_exames_nome_id;
        $datainicial = $dados[0]->data_inicio;
        $datafinal = $dados[0]->data_fim;
        $tipo = $dados[0]->tipo;

        $agenda = $this->agenda->listarnovoshorarioseditaragendacriada();
        $id = 0;

        $data['mensagem'] = 'Sucesso ao criar novos horarios para essa agenda no periodo de ' . date("d/m/Y", strtotime($datainicial)) . " até " . date("d/m/Y", strtotime($datafinal));
        $this->session->set_flashdata('message', $data['mensagem']);

        foreach ($agenda as $item) {
            $this->agenda->consolidandonovoshorarios($item->horarioagenda_editada_id);
            $tempoconsulta = $item->tempoconsulta;
            $qtdeconsulta = $item->qtdeconsulta;
            $qtdeconsulta = (int) $qtdeconsulta;
            $horarioagenda_id = $item->horarioagenda_editada_id;
            $empresa_id = $item->empresa_id;
            $sala_id = $item->sala_id;
            $obs = $item->observacoes;

            if (($qtdeconsulta != 0) && ($item->intervaloinicio == "00:00:00")) {
                $entrada = $item->horaentrada1;
                $saida = $item->horasaida1;
                $hora1 = explode(":", $entrada);
                $hora2 = explode(":", $saida);
                $acumulador1 = ($hora1[0] * 60) + $hora1[1];
                $acumulador2 = ($hora2[0] * 60) + $hora2[1];
                $resultado = $acumulador2 - $acumulador1;
                $tempoconsulta = $resultado / $item->qtdeconsulta;
                $tempoconsulta = (int) $tempoconsulta + 1;
            }
            if (($qtdeconsulta != 0) && ($item->intervaloinicio != "00:00:00")) {
                $entrada = $item->horaentrada1;
                $saida = $item->horasaida1;
                $intervaloinicio = $item->intervaloinicio;
                $intervalofim = $item->intervalofim;
                $hora1 = explode(":", $entrada);
                $hora2 = explode(":", $saida);
                $horainicio = explode(":", $intervaloinicio);
                $horafim = explode(":", $intervalofim);
                $acumulador1 = ($hora1[0] * 60) + $hora1[1];
                $acumulador2 = ($hora2[0] * 60) + $hora2[1];
                $acumulador3 = ($horainicio[0] * 60) + $horainicio[1];
                $acumulador4 = ($horafim[0] * 60) + $horafim[1];
                $resultado = ($acumulador3 - $acumulador1) + ($acumulador2 - $acumulador4);
                $tempoconsulta = $resultado / $item->qtdeconsulta;
                $tempoconsulta = (int) $tempoconsulta + 1;
            }

            for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                $data = strftime("%A", strtotime($index));

                switch ($data) {
                    case"Sunday": $data = "Domingo";
                        break;
                    case"Monday": $data = "Segunda";
                        break;
                    case"Tuesday": $data = "Terça";
                        break;
                    case"Wednesday": $data = "Quarta";
                        break;
                    case"Thursday": $data = "Quinta";
                        break;
                    case"Friday": $data = "Sexta";
                        break;
                    case"Saturday": $data = "Sabado";
                        break;
                }
                $i = 0;
                $horaconsulta = 0;
                $horaverifica = 0;
                $horasaida = 0;
                if ($data == substr($item->dia, 4)) {
//                    die('morreu');
                    for ($horaindex = $item->horaentrada1; $horaindex <= $item->horasaida1; $horaindex = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaindex)))) {

                        if ($item->intervaloinicio == "00:00:00") {
                            if ($i == 0) {
                                $horaconsulta = date('H:i:s', strtotime($item->horaentrada1));
                                $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($item->horaentrada1)));
                                $i = 1;
                                if ($id == 0) {
                                    $id = $this->exame->gravarnome($nome);
                                }
                                $this->exame->gravarhorarioseditadosagendacriada($horarioagenda_id, $agenda_id, $horaconsulta, $horaverifica, $nome, $datainicial, $datafinal, $index, $medico_id, $id, $empresa_id, $obs, $tipo, $sala_id, $tipo_consulta_id);
                            }
                            if (( $horaverifica < $item->horasaida1)) {
                                $x = 1;
                                $horaconsulta = $horaverifica;
                                $horasaida = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                                $this->exame->gravarhorarioseditadosagendacriada($horarioagenda_id, $agenda_id, $horaconsulta, $horasaida, $nome, $datainicial, $datafinal, $index, $medico_id, $id, $empresa_id, $obs, $tipo, $sala_id, $tipo_consulta_id);
                            }
                            $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                        } else {
                            if ($i == 0) {
                                $horaconsulta = date('H:i:s', strtotime($item->horaentrada1));
                                $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($item->horaentrada1)));
                                $i = 1;
                                if ($id == 0) {
                                    $id = $this->exame->gravarnome($nome);
                                }
                                $this->exame->gravarhorarioseditadosagendacriada($horarioagenda_id, $agenda_id, $horaconsulta, $horaverifica, $nome, $datainicial, $datafinal, $index, $medico_id, $id, $empresa_id, $obs, $tipo, $sala_id, $tipo_consulta_id);
                            }
                            if ((($horaverifica < $item->intervaloinicio) || ($horaverifica >= $item->intervalofim)) && ( $horaverifica < $item->horasaida1)) {
                                $x = 1;
                                $horaconsulta = $horaverifica;
                                $horasaida = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                                $this->exame->gravarhorarioseditadosagendacriada($horarioagenda_id, $agenda_id, $horaconsulta, $horasaida, $nome, $datainicial, $datafinal, $index, $medico_id, $id, $empresa_id, $obs, $tipo, $sala_id, $tipo_consulta_id);
                            }
                            $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                        }
                    }
                }
            }
        }

        $this->exame->removendoprocedimentoduplicadoagendaeditada();

        redirect(base_url() . "ambulatorio/agenda");
    }

    function gravarespecialidade($datainicial_intervalo, $datafinal_intervalo, $agenda_id, $medico_id) {
//        $agenda_id = $_POST['txthorario'];
//        $medico_id = $_POST['txtmedico'];
        $datainicial = date("Y-m-d", strtotime(str_replace("/", "-", $datainicial_intervalo)));
        $datafinal = date("Y-m-d", strtotime(str_replace("/", "-", $datafinal_intervalo)));
        $nome = $_POST['txtNome'];
        $tipo = 'ESPECIALIDADE';
        $horarioagenda = $this->agenda->listarhorarioagendacriacaoespecialidade($agenda_id, $medico_id, $datainicial, $datafinal, $tipo);
//        var_dump($horarioagenda); die;
        $id = 0;
//        var_dump($horarioagenda);die;
        foreach ($horarioagenda as $item) {

            $tempoconsulta = $item->tempoconsulta;
            $qtdeconsulta = $item->qtdeconsulta;
            $qtdeconsulta = (int) $qtdeconsulta;
            $horarioagenda_id = $item->horarioagenda_id;
            $empresa_id = $item->empresa_id;
            $obs = $item->observacoes;

            if (($qtdeconsulta != 0) && ($item->intervaloinicio == "00:00:00")) {
                $entrada = $item->horaentrada1;
                $saida = $item->horasaida1;
                $hora1 = explode(":", $entrada);
                $hora2 = explode(":", $saida);
                $acumulador1 = ($hora1[0] * 60) + $hora1[1];
                $acumulador2 = ($hora2[0] * 60) + $hora2[1];
                $resultado = $acumulador2 - $acumulador1;
                $tempoconsulta = $resultado / $item->qtdeconsulta;
                $tempoconsulta = (int) $tempoconsulta + 1;
            }
            if (($qtdeconsulta != 0) && ($item->intervaloinicio != "00:00:00")) {
                $entrada = $item->horaentrada1;
                $saida = $item->horasaida1;
                $intervaloinicio = $item->intervaloinicio;
                $intervalofim = $item->intervalofim;
                $hora1 = explode(":", $entrada);
                $hora2 = explode(":", $saida);
                $horainicio = explode(":", $intervaloinicio);
                $horafim = explode(":", $intervalofim);
                $acumulador1 = ($hora1[0] * 60) + $hora1[1];
                $acumulador2 = ($hora2[0] * 60) + $hora2[1];
                $acumulador3 = ($horainicio[0] * 60) + $horainicio[1];
                $acumulador4 = ($horafim[0] * 60) + $horafim[1];
                $resultado = ($acumulador3 - $acumulador1) + ($acumulador2 - $acumulador4);
                $tempoconsulta = $resultado / $item->qtdeconsulta;
                $tempoconsulta = (int) $tempoconsulta + 1;
            }

            for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                $data = strftime("%A", strtotime($index));

                switch ($data) {
                    case"Sunday": $data = "Domingo";
                        break;
                    case"Monday": $data = "Segunda";
                        break;
                    case"Tuesday": $data = "Terça";
                        break;
                    case"Wednesday": $data = "Quarta";
                        break;
                    case"Thursday": $data = "Quinta";
                        break;
                    case"Friday": $data = "Sexta";
                        break;
                    case"Saturday": $data = "Sabado";
                        break;
                }
                $i = 0;
                $horaconsulta = 0;
                $horaverifica = 0;
                $horasaida = 0;
                if ($data == substr($item->dia, 4)) {
                    for ($horaindex = $item->horaentrada1; $horaindex <= $item->horasaida1; $horaindex = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaindex)))) {

                        if ($item->intervaloinicio == "00:00:00") {
                            if ($i == 0) {
                                $horaconsulta = date('H:i:s', strtotime($item->horaentrada1));
                                $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($item->horaentrada1)));
                                $i = 1;
                                if ($id == 0) {
                                    $id = $this->exame->gravarnome($nome);
                                }
                                $this->exame->gravarespecialidade($horarioagenda_id, $agenda_id, $horaconsulta, $horaverifica, $nome, $datainicial, $datafinal, $index, $medico_id, $id, $empresa_id, $obs);
                            }
                            if (( $horaverifica < $item->horasaida1)) {
                                $x = 1;
                                $horaconsulta = $horaverifica;
                                $horasaida = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                                $this->exame->gravarespecialidade($horarioagenda_id, $agenda_id, $horaconsulta, $horasaida, $nome, $datainicial, $datafinal, $index, $medico_id, $id, $empresa_id, $obs);
                            }
                            $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                        } else {
                            if ($i == 0) {
                                $horaconsulta = date('H:i:s', strtotime($item->horaentrada1));
                                $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($item->horaentrada1)));
                                $i = 1;
                                if ($id == 0) {
                                    $id = $this->exame->gravarnome($nome);
                                }
                                $this->exame->gravarespecialidade($horarioagenda_id, $agenda_id, $horaconsulta, $horaverifica, $nome, $datainicial, $datafinal, $index, $medico_id, $id, $empresa_id, $obs);
                            }
                            if ((($horaverifica < $item->intervaloinicio) || ($horaverifica >= $item->intervalofim)) && ( $horaverifica < $item->horasaida1)) {
                                $x = 1;
                                $horaconsulta = $horaverifica;
                                $horasaida = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                                $this->exame->gravarespecialidade($horarioagenda_id, $agenda_id, $horaconsulta, $horasaida, $nome, $datainicial, $datafinal, $index, $medico_id, $id, $empresa_id, $obs);
                            }
                            $horaverifica = date('H:i:s', strtotime("+ $tempoconsulta minutes", strtotime($horaverifica)));
                        }
                    }
                }
            }
        }

//        $data['mensagem'] = 'Sucesso ao gravar a Agenda.';
//        $this->session->set_flashdata('message', $data['mensagem']);
//        redirect(base_url() . "ambulatorio/agenda");
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

    function gerarcr($agenda_exames_id) {
        $exame = $this->exame->listararquivo($agenda_exames_id);
        $titulo = "                                       " . $agenda_exames_id;
        $comando = "CMD=CREATE";
        $id = "PATID=" . $agenda_exames_id;
        $paciente = "PATNAME=" . $exame[0]->paciente;
        $sexo = "PATSEX=" . $exame[0]->sexo;
        $banco = "PATBD=19480915";
        $acc = "ACCNUM=" . $agenda_exames_id;
        $procedimento = "STDDESC=" . $exame[0]->procedimento;
        $modalidade = "MODALITY=CR";
        $data = "STDDATE=" . str_replace("-", "", date("Y-m-d"));
        $hora = "STDTIME=" . str_replace(":", "", date("H:i:s"));

        if (!is_dir("./cr/")) {
            mkdir("./cr/");
        }
        $nome = "./cr/" . $agenda_exames_id . ".txt";
        $fp = fopen($nome, "w+");
        fwrite($fp, $titulo . "\n");
        fwrite($fp, $comando . "\n");
        fwrite($fp, $id . "\n");
        fwrite($fp, $paciente . "\n");
        fwrite($fp, $sexo . "\n");
        fwrite($fp, $banco . "\n");
        fwrite($fp, $acc . "\n");
        fwrite($fp, $procedimento . "\n");
        fwrite($fp, $modalidade . "\n");
        fwrite($fp, $data . "\n");
        fwrite($fp, $hora . "\n");
        fclose($fp);
    }

    function gerarPDFXML($nome, $arrayPDF){
        $data['convenio'] = $_POST['convenio'];
        $data['procedimentos'] = 0;
        $data['grupo'] = 0;
        $data['datainicio'] = $_POST['datainicio'];
        $data['datafim'] = $_POST['datafim'];
        $_POST['mostrar_medico'] = 'SIM';
        $_POST['aparecervalor'] = '1';
        @$medicos = array($_POST['medico']);
        if (isset($medicos)) {
            if (in_array("0", @$medicos)) {
                $todos = true;
            } else {
                $todos = false;
            }
        } else {
            $todos = false;
        }

        if (count($medicos) != 0 && !$todos) {
            $data['medico'] = $this->operador_m->listarVarios($medicos);
        } else {
            $data['medico'] = 0;
        }
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        if ($_POST['convenio'] != '') {
            $data['convenios'] = $this->guia->listardados($_POST['convenio']);
        }
        
        $data['procedimentos'] = array();
        
        $data['relatorio'] = $arrayPDF;
        $this->load->plugin('mpdf');
        $texto = $this->load->View('ambulatorio/impressaorelatorioconferenciaPDFXML', $data, true);
        $cabecalhopdf = '';
        $rodapepdf = '';
        if (is_dir("./upload/cr")) {
            // chmod("./upload/cr", 0777);
        }
        salvapdf($texto, $nome, $cabecalhopdf, $rodapepdf);
        // var_dump($texto);
        // die;
    }

    function gerarxmlcirurgico() {
        $listarexame = $this->exame->listargxmlfaturamentointernacao();
        if($listarexame){
            $convenio = $listarexame[0]->convenio;
        }

        $empresa = $this->exame->listarcnpj();

        $lote = $this->exame->listarlote();
        $b = $lote[0]->lote;
        $lotee = $b;
        $b++;
        $umavez = 0;
        $zero = '0000000000000000';

        // Criando a Pasta caso não exista
        $pasta_origem = "./upload/cr_cirurgico/";
        if (!is_dir($pasta_origem)) {
            mkdir($pasta_origem);
            chmod($pasta_origem, 0777);
                   }

        $origem = "./upload/cr_cirurgico/" . $convenio . "/";
        if (!is_dir($origem)) {
            mkdir($origem);
            chmod($origem, 0777);
                   }

        if ($_POST['apagar'] == 1) {
            delete_files($origem);
        }

        if($_POST['limite'] == 1){
            if(count($listarexame) > 100){

                $contadorlimite = count($listarexame);
                $ignoralinhas = 0;
                $teste = 0;

                //print_r($contadorlimite);
                //die;

                while($contadorlimite >= 1){
                    $teste++;

        $cabecalho = $this->getCabecalhocirurgico($listarexame, $b);
        $body = $this->getBodycirurgico($listarexame, $contadorlimite, $ignoralinhas); 
        

        $XMLfinal = '';

        // Aplicando ANS: em todas as TAGs do Corpo
        // Por limitações tecnicas não foi possivel adicionar automaticamente
        $body= str_replace('ans', 'ans:', $body);
    
        
        // Unindo XML e completando Tags que falta
        $XMLfinal.=$cabecalho;
        $lotee++;
        $XMLfinal.='<ans:prestadorParaOperadora>
        <ans:loteGuias>
          <ans:numeroLote>'.$lotee.'</ans:numeroLote>
          <ans:guiasTISS>';

        $XMLfinal.=$body;

        $XMLfinal.='</ans:guiasTISS>
        </ans:loteGuias>
      </ans:prestadorParaOperadora>
      <ans:epilogo>
      <ans:hash>1a3fbdf5943c63b1fe08ce2aaf73057b</ans:hash>
      </ans:epilogo>
      </ans:mensagemTISS>';

$nome = "./upload/cr_cirurgico/" . $convenio . "/" . $zero . $b . "_" . $lotee . ".xml";

        $fp = fopen($nome, "w+");
        fwrite($fp, $XMLfinal . "\n");
        fclose($fp);
 
                    $contadorlimite = $contadorlimite - 100;
                    $ignoralinhas = $ignoralinhas + 100;
                }




            }else{
              $umavez = 1; 
            }

        }else{
            $umavez = 1;
        }
        if($umavez == 1){
        $b++;
        $cabecalho = $this->getCabecalhocirurgico($listarexame, $b);
        $body = $this->getBodycirurgico($listarexame, 0, 0); 
        $lotee = $b;


        $XMLfinal = '';

        // Aplicando ANS: em todas as TAGs do Corpo
        // Por limitações tecnicas não foi possivel adicionar automaticamente
        $body= str_replace('ans', 'ans:', $body);
    
        
        // Unindo XML e completando Tags que falta
        $XMLfinal.=$cabecalho;

        $XMLfinal.='<ans:prestadorParaOperadora>
        <ans:loteGuias>
          <ans:numeroLote>'.$lotee.'</ans:numeroLote>
          <ans:guiasTISS>';

        $XMLfinal.=$body;

        $XMLfinal.='</ans:guiasTISS>
        </ans:loteGuias>
      </ans:prestadorParaOperadora>
      <ans:epilogo>
      <ans:hash>1a3fbdf5943c63b1fe08ce2aaf73057b</ans:hash>
      </ans:epilogo>
      </ans:mensagemTISS>';


                   
        $nome = "./upload/cr_cirurgico/" . $convenio . "/" . $zero . $b . "_" . $lotee . ".xml";

        $fp = fopen($nome, "w+");
        fwrite($fp, $XMLfinal . "\n");
        fclose($fp);
    }

    if (count($listarexame) > 0) {
        $this->exame->gravarlote($b);
        $zip = new ZipArchive;
        $this->load->helper('directory');
        $arquivo_pasta = directory_map($origem);


        if ($arquivo_pasta != false) {
            foreach ($arquivo_pasta as $value) {
                $deletar[] = "$origem/$value";
            }
            foreach ($arquivo_pasta as $value) {

                $rest = substr($value, 0, -4);
                $rest = $rest;


                $zip->open("$origem/$value.zip", ZipArchive::CREATE);                   
                $zip->addFile("$origem/$value", "$value");

            }
            $zip->close();
            foreach ($deletar as $arquivonome) {
                unlink($arquivonome);  
            }
        }
    }


        // var_dump('teee'); die;
       $data['mensagem'] = 'Sucesso ao gerar arquivo.';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/faturamentoexamexmlcirurgico", $data);
    }


    function getCabecalhocirurgico($listarexame, $b){

        $horario = date("Y-m-d");
        $hora = date("H:i:s");
        $empresa = $this->exame->listarcnpj();

        $cnpjxml = @$listarexame[0]->codigoidentificador;
        $registroans = @$listarexame[0]->registroans;

        // Padrao
        $sequencialTransacao = $b - 53;
        //         
        $cabecalhoXML = '';
        // Nesse não precisa usar o ANS antes das Tags por causa do SimpleXMLElement
        // Como estou passando o paramentro "<ans:mensagemTISS>" ele automaticamente
        // Colocar o ANS: antes de cada Tag
        // Isso não foi possivel aplicar no corpo
        $arrayCabecalho = [
            'cabecalho' => [
                'identificacaoTransacao' => [
                    'tipoTransacao' => 'ENVIO_LOTE_GUIAS',
                    'sequencialTransacao' => $sequencialTransacao,
                    'dataRegistroTransacao' => $horario,
                    'horaRegistroTransacao' => $hora,
                ],
                'origem' => [
                    'identificacaoPrestador' => [
                        'codigoPrestadorNaOperadora' => $cnpjxml,
                    ]
                ],
                'destino' => [
                    'registroANS' => $registroans,
                ],
                'Padrao' => '3.03.01',
            ]];

            $xml_data = new SimpleXMLElement('<?xml version="1.0" encoding="iso-8859-1"?><ans:mensagemTISS xmlns:ans="http://www.ans.gov.br/padroes/tiss/schemas"></ans:mensagemTISS>');
            $this->utilitario->array_to_xml($arrayCabecalho, $xml_data);  
            $cabecalhoXML.= str_replace('</ans:mensagemTISS>', '', $xml_data->asXML()); 

            return $cabecalhoXML;

    } 


    function getBodycirurgico($listarexame, $contadorlimite, $ignoralinhas){


        $horario = date("Y-m-d");
        $hora = date("H:i:s");
        $empresa = $this->exame->listarcnpj();
        $lote = $this->exame->listarlote();
        $carater_xml = 1;

        $codigoUF = $this->utilitario->codigo_uf($empresa[0]->codigo_ibge, 'codigo');

        $razao_socialxml = @$empresa[0]->razao_socialxml;
        $cpfxml = $empresa[0]->cpfxml;
        $cnpj = $empresa[0]->cnpj;
        $cnes = $empresa[0]->cnes;
        $convenio = @$listarexame[0]->convenio;
        $versao = $_POST['xml'];
        $modelo = $_POST['modelo'];

        if($contadorlimite > 0){
            $contadorpaciente = 0;
        }
        if($ignoralinhas == 0){

        }else{
        $contadorpacientetotal = $ignoralinhas;
        }


        $string = '';

        foreach ($listarexame as $key => $paciente) {

            if($ignoralinhas > 0){
                if($contadorpacientetotal > 0){
                    $contadorpacientetotal = $contadorpacientetotal - 1;
                  continue;
                    }  
            }
            if(@$contadorpaciente >= 0 && @$contadorpaciente <= 100){
                @$contadorpaciente++;
            }

            $dataautorizacao = substr($paciente->data_internacao, 0, 10);
            if($paciente->carater_internacao == 'Eletiva'){
                $caracteratendimento = '1';
            }else if($paciente->carater_internacao == 'Normal'){
                $caracteratendimento = '1';
            }else{
                $caracteratendimento = '2';
            }

            $data_faturamento = substr($paciente->data_faturamento, 0, 10);
            $hora_faturamento = substr($paciente->data_faturamento, 11, 19);

            if ($paciente->convenionumero == '') {
                $numerodacarteira = '0000000';
            } else {
                $numerodacarteira = $paciente->convenionumero;
            }

            $string.= '<ansguiaResumoInternacao>';
            if($_POST['autorizacao'] == 'SIM'){
                $Arraycorpo = array(
                    'anscabecalhoGuia' => array(
                                            'ansregistroANS' => $paciente->registroans,       
                                            'ansnumeroGuiaPrestador' => $paciente->internacao_id,       
                                        ),
                    'ansnumeroGuiaSolicitacaoInternacao' => $paciente->senha,
                    'ansdadosAutorizacao' => array(
                                            'ansnumeroGuiaOperadora' => $paciente->senha,       
                                            'ansdataAutorizacao' => $dataautorizacao,       
                                            'anssenha' => $paciente->senha,   
                                        ),
                    'ansdadosBeneficiario' => array(
                        'ansnumeroCarteira' => $numerodacarteira,
                        'ansatendimentoRN' => 'N',
                        'ansnomeBeneficiario' => $paciente->paciente,
                                        ),
                    'ansdadosExecutante' => array(
                        'anscontratadoExecutante' => array(
                            'anscodigoPrestadorNaOperadora' => $paciente->codigoidentificador,
                            'ansnomeContratado' => $razao_socialxml,
                                        ),
                        'ansCNES' => $cnes,
                                        ),
                    'ansdadosInternacao' => array(
                        'anscaraterAtendimento' => $caracteratendimento,
                        'anstipoFaturamento' => '4',
                        'ansdataInicioFaturamento' => $data_faturamento,
                        'anshoraInicioFaturamento' => $hora_faturamento,
                        'ansdataFinalFaturamento' => $data_faturamento,
                        'anshoraFinalFaturamento' => $hora_faturamento,
                        'anstipoInternacao' => '2',
                        'ansregimeInternacao' => '1',
                                        ),
                    'ansdadosSaidaInternacao' => array(
                        'ansdiagnostico' => $paciente->diagnostico,
                        'ansindicadorAcidente' => '9',
                        'ansmotivoEncerramento' => '12',
                                        ),    
                );
            }else{
                $Arraycorpo = array(
                    'anscabecalhoGuia' => array(
                                            'ansregistroANS' => $paciente->registroans,       
                                            'ansnumeroGuiaPrestador' => $paciente->internacao_id,       
                                        ),
                    'ansnumeroGuiaSolicitacaoInternacao' => $paciente->senha,
                    'ansdadosAutorizacao' => array(
                                            'ansnumeroGuiaOperadora' => $paciente->senha,       
                                            'ansdataAutorizacao' => $dataautorizacao,  
                                        ),
                    'ansdadosBeneficiario' => array(
                        'ansnumeroCarteira' => $numerodacarteira,
                        'ansatendimentoRN' => 'N',
                        'ansnomeBeneficiario' => $paciente->paciente,
                                        ),
                    'ansdadosExecutante' => array(
                        'anscontratadoExecutante' => array(
                            'anscodigoPrestadorNaOperadora' => $paciente->codigoidentificador,
                            'ansnomeContratado' => $razao_socialxml,
                                        ),
                        'ansCNES' => $cnes,
                                        ),
                    'ansdadosInternacao' => array(
                        'anscaraterAtendimento' => $caracteratendimento,
                        'anstipoFaturamento' => '4',
                        'ansdataInicioFaturamento' => $data_faturamento,
                        'anshoraInicioFaturamento' => $hora_faturamento,
                        'ansdataFinalFaturamento' => $data_faturamento,
                        'anshoraFinalFaturamento' => $hora_faturamento,
                        'anstipoInternacao' => '2',
                        'ansregimeInternacao' => '1',
                                        ),
                    'ansdadosSaidaInternacao' => array(
                        'ansdiagnostico' => $paciente->diagnostico,
                        'ansindicadorAcidente' => '9',
                        'ansmotivoEncerramento' => '12',
                                        ),    
                );
            }

            $xml_data = new SimpleXMLElement('<branco></branco>');
            $this->utilitario->array_to_xml($Arraycorpo, $xml_data);  
            $string.= str_replace(['<?xml version="1.0"?>','<branco>','</branco>'], '', $xml_data->asXML()); 
            $string.='<ansprocedimentosExecutados>';

            $Procedimentos = $this->exame->listarprocedimentoxmlfaturamentointernacao($paciente->internacao_id);
            $totalprocedimento = 0;
            foreach($Procedimentos as $key => $procedimento){
                $data_execucao = substr($procedimento->data_cadastro, 0, 10);
                $valor_unitario = $procedimento->valor_total / $procedimento->quantidade;
                $valor_unitario = number_format($valor_unitario, 2, '.', '') ;
                $totalprocedimento = $totalprocedimento + $procedimento->valor_total;
                $totalprocedimento = number_format($totalprocedimento, 2, '.', '') ;
                $Arrayprocedimento = array(
                    'ansdataExecucao' => $data_execucao,
                    'ansprocedimento' => array(
                        'anscodigoTabela' => '00',
                        'anscodigoProcedimento' => $procedimento->codigo,
                        'ansdescricaoProcedimento' => $procedimento->procedimento,
                                    ),
                    'ansquantidadeExecutada' => $procedimento->quantidade,
                    'ansreducaoAcrescimo' => '1.00',
                    'ansvalorUnitario' => $valor_unitario,
                    'ansvalorTotal' => $procedimento->valor_total,
                );

                $xml_data = new SimpleXMLElement('<ansprocedimentoExecutado></ansprocedimentoExecutado>');
                $this->utilitario->array_to_xml($Arrayprocedimento, $xml_data);  
                $string.= str_replace('<?xml version="1.0"?>', '', $xml_data->asXML()); 
            }
            $string.='</ansprocedimentosExecutados>';
            $despesas = $this->exame->listaroutrasdespesasxmlinternacao($paciente->internacao_id);
            if($despesas == 0){
                $total_geral = $totalprocedimento;
                $valor_total_taxa = '0.00';
            }else{
              $total_geral = $totalprocedimento + $despesas[0]->valor_total_taxa;
              $valor_total_taxa =  $despesas[0]->valor_total_taxa;
            }
            $total_geral = number_format($total_geral, 2, '.', '') ;
            $Arraycorpo_2 = array(
                'ansvalorTotal' => array(
                    'ansvalorProcedimentos' => $totalprocedimento,
                    'ansvalorTaxasAlugueis' => $valor_total_taxa,
                    'ansvalorTotalGeral' => $total_geral,
                                ),
            );

            $xml_data = new SimpleXMLElement('<branco></branco>');
            $this->utilitario->array_to_xml($Arraycorpo_2, $xml_data);  
            $string.= str_replace(['<?xml version="1.0"?>','<branco>','</branco>'], '', $xml_data->asXML()); 
            // Verificando se possui OutrasDespesas para o paciente
            if($despesas == 0){

            }else{
            $string.='<ansoutrasDespesas>';


            foreach($despesas as $key => $despesa){
                if($despesa->grupo == 'TAXA'){
                    $anscodigoDespesa = '07';
                    $anscodigoTabela = '18';
                }else if($despesa->grupo == 'MATERIAL'){
                    $anscodigoDespesa = '03';
                    $anscodigoTabela = '19';
                }else{
                    $anscodigoDespesa = '00';
                    $anscodigoTabela = '00';
                }
                if(strlen($despesa->unidade_medida) != 3){
                    if(strlen($despesa->unidade_medida) == 2){
                        $ansunidadeMedida = '0'.$despesa->unidade_medida;
                    }else if(strlen($despesa->unidade_medida) == 1){
                        $ansunidadeMedida = '00'.$despesa->unidade_medida;
                    }else{
                        $ansunidadeMedida = '000';
                    }
                }else{
                    $ansunidadeMedida = $despesa->unidade_medida;
                }
                $ansdataExecucao = substr($despesa->data_cadastro, 0, 10);
                $Arraydespesas = array(
                    'anscodigoDespesa' => $anscodigoDespesa,
                    'ansservicosExecutados' => array(
                        'ansdataExecucao' => $ansdataExecucao,
                        'anscodigoTabela' => $anscodigoTabela,
                        'anscodigoProcedimento' => $despesa->codigo,
                        'ansquantidadeExecutada' => $despesa->quantidade,
                        'ansunidadeMedida' => $ansunidadeMedida,
                        'ansreducaoAcrescimo' => '1.00',
                        'ansvalorUnitario' => $despesa->valor_u,
                        'ansvalorTotal' => $despesa->valor_total,
                        'ansdescricaoProcedimento' => $despesa->taxa,
                                    ),
                );

                $xml_data = new SimpleXMLElement('<ansdespesa></ansdespesa>');
                $this->utilitario->array_to_xml($Arraydespesas, $xml_data);  
                $string.= str_replace('<?xml version="1.0"?>', '', $xml_data->asXML());
            }
            $string.='</ansoutrasDespesas>';
        } // Fechamento do IF que verifica se o paciente possui Despesas
            $string.= '</ansguiaResumoInternacao>';

            if($contadorpaciente == 100){
                 break;
            }
        }

       // print_r($string);
       // print_r($xml);
       // die;
   
        return $string;
    }



    function gerarxml() {
        $this->load->plugin('mpdf');
        $total = 0;

        $listarpacienete = $this->exame->listarpacientesxmlfaturamento();
        // print_r($listarpacienete);
        // die;
        $listarexame = $this->exame->listargxmlfaturamento();
        $listarexames = $this->exame->listarxmlfaturamentoexames();
        // $data['internacao'] = $this->internacao_m->relatoriointernacaosituacao();
//         echo'<pre>';
//         var_dump($listarexames);
//         var_dump($listarexames);
//         die;

        $horario = date("Y-m-d");
        $hora = date("H:i:s");
        $empresa = $this->exame->listarcnpj();
        $lote = $this->exame->listarlote();
        $carater_xml = 1;
        // Só inicializando a variável pra caso alguém esqueça de adicionar em um ponto do código
        // Daí não dá erro.

        $codigoUF = $this->utilitario->codigo_uf($empresa[0]->codigo_ibge, 'codigo');

        $cnpjxml = @$listarexame[0]->codigoidentificador;
        $razao_socialxml = @$empresa[0]->razao_socialxml;
        $registroans = @$listarexame[0]->registroans;
        $cpfxml = $empresa[0]->cpfxml;
        $cnpj = $empresa[0]->cnpj;
        $cnes = $empresa[0]->cnes;
        $convenio = @$listarexame[0]->convenio;
        $versao = $_POST['xml'];
        $modelo = $_POST['modelo'];
        $sequencialItem = 0;
        $arrayPDF = array();
//        echo '<pre>';
//        var_dump($listarexame);
//        die;
                    

        $limite = ($_POST['limite'] == '0') ? false : true;

        if ($_POST['tipo'] != 0) {
            $classificacao = @$listarexame[0]->classificacao;
        } else {
            $classificacao = 'TODOS';
        }
        $_POST['datainicio'] = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['datainicio'])));
        $_POST['datafim'] = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['datafim'])));
        $datainicio = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['datainicio'])));
        $datafim = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['datafim'])));
        $nomearquivo = '035753bf836c231bedbc68a08daf4668';
        $nomearquivoconsulta = 'e2eadfe09fd6750a184902545aa41771';
        $origem = "./upload/cr/" . $convenio;

        if (!is_dir("./upload/cr/" . $convenio)) {
            mkdir("./upload/cr/" . $convenio);
            chmod($origem, 0777);
        }
        if ($_POST['apagar'] == 1) {
            delete_files($origem);
        }
        $i = 0;
        $totExames = 0;
        $b = $lote[0]->lote;
        $j = $b - 53;
        $zero = '0000000000000000';
        $corpo = "";
        $cnpjPrestador = $_POST['identificacaoPrestador'];
        // ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
        if ($_POST['unir_exame_consulta'] == 'SIM') {
            $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
                            <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
                            <ans:cabecalho>
                              <ans:identificacaoTransacao>
                                 <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
                                 <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
                                 <ans:dataRegistroTransacao>" . substr($listarexame[0]->data_autorizacao, 0, 10) . "</ans:dataRegistroTransacao>
                                 <ans:horaRegistroTransacao>" . $hora . "</ans:horaRegistroTransacao>
                              </ans:identificacaoTransacao>
                              <ans:origem>
                                 <ans:identificacaoPrestador>" .
                    ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                    "</ans:identificacaoPrestador>
                              </ans:origem>
                              <ans:destino>
                                 <ans:registroANS>" . $registroans . "</ans:registroANS>
                              </ans:destino>
                              <ans:Padrao>" . $versao . "</ans:Padrao>
                            </ans:cabecalho>
                            <ans:prestadorParaOperadora>
                              <ans:loteGuias>
                                 <ans:numeroLote>" . $b . "</ans:numeroLote>
                                    <ans:guiasTISS>";

            $contador = 0;
            foreach ($listarpacienete as $pac) {
                $contador += $pac->contador;
            }

            foreach ($listarpacienete as $value) {
                            
                if ($value->convenionumero == '') {
                    $numerodacarteira = '0000000';
                } else {
                    $numerodacarteira = $value->convenionumero;
                }

                foreach ($listarexames as $item) {
                    if ($item->guiaconvenio == '') {
                        $guianumero = '0000000';
                    } else {
                        $guianumero = $item->guiaconvenio;
                    }
                   

                    if ($value->paciente_id == $item->paciente_id && $value->ambulatorio_guia_id == $item->ambulatorio_guia_id  && $item->exames_id == $value->exames_id) {
                        $tabela = '22';
                        array_push($arrayPDF, $item);

                        $valorProcedimento = $item->valor_total;
                        $valorMaterial = 0.00;
                        $valorMedicamento = 0.00;

                        if ($item->grupo == "MATERIAL") { //caso seja material
                            $tabela = '19';
                            $codDespesa = '03';
                            $valorMaterial = $item->valor_total;
                            $valorProcedimento = 0.00;
                        } elseif ($item->grupo == "MEDICAMENTO") { //caso seja medicamento
                            $tabela = '20';
                            $codDespesa = '02';
                            $valorMedicamento = $item->valor_total;
                            $valorProcedimento = 0.00;
                        }

                        $i++;
                        $totExames++;
                        $data_autorizacao = $this->exame->listarxmldataautorizacao($value->agenda_exames_id);
                        $dataautorizacao = substr($data_autorizacao[0]->data_cadastro, 0, 10);
                        $dataValidadeSenha = date('Y-m-d', strtotime("+30 days", strtotime($dataautorizacao)));
                        $carater_xml = $item->carater_xml;
                        if ($item->medico == '') {
                            $medico = 'ADMINISTRADOR';
                        } else {
                            $medico = $item->medico;
                        }
                        if ($item->conselho == '') {
                            $conselho = '0000000';
                        } else {
                            $conselho = $item->conselho;
                        }
                        if ($item->cbo_ocupacao_id == '') {
                            $cbo_medico = '999999';
                        } else {
                            $cbo_medico = $item->cbo_ocupacao_id;
                        }
                        if ($item->medicosolicitante == '') {
                            $medicosolicitante = $item->medico;
                        } else {
                            $medicosolicitante = $item->medicosolicitante;
                        }
                        if ($item->conselhosolicitante == '') {
                            $conselhosolicitante = $item->conselho;
                        } else {
                            $conselhosolicitante = $item->conselhosolicitante;
                        }

                        if ($_POST['autorizacao'] == 'SIM') {
                            $corpo = $corpo . "<ans:guiaSP-SADT>
                 <ans:cabecalhoGuia>
                    <ans:registroANS>" . $registroans . "</ans:registroANS>
                 <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f' ? $value->ambulatorio_guia_id : $item->agenda_exames_id)) . "</ans:numeroGuiaPrestador>
                 <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
              </ans:cabecalhoGuia>
              <ans:dadosAutorizacao>
              <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
              <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
              <ans:senha>" . $item->autorizacao . "</ans:senha>
              <ans:dataValidadeSenha>" . $dataValidadeSenha . "</ans:dataValidadeSenha> 
              </ans:dadosAutorizacao>
              <ans:dadosBeneficiario>
                 <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                     <ans:atendimentoRN>S</ans:atendimentoRN>
                 <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
              </ans:dadosBeneficiario>
              <ans:dadosSolicitante>
                 <ans:contratadoSolicitante>" .
                                    ( ($modelo == 'cpf') ? ("<ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>") : ("<ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>")) .
                                    "<ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                 </ans:contratadoSolicitante>
                 <ans:profissionalSolicitante>
                    <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                    <ans:conselhoProfissional>06</ans:conselhoProfissional>
                    <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                        <ans:UF>" . $codigoUF . "</ans:UF>
                    <ans:CBOS>$cbo_medico</ans:CBOS>
                 </ans:profissionalSolicitante>
              </ans:dadosSolicitante>
              <ans:dadosSolicitacao>
                 <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                 <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                 <ans:indicacaoClinica>I</ans:indicacaoClinica>
              </ans:dadosSolicitacao>
              <ans:dadosExecutante>
                 <ans:contratadoExecutante>" .
                                    ( ($modelo == 'cpf') ? ("<ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>") : ("<ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>")) .
                                    "<ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                    <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                </ans:contratadoExecutante>
                 <ans:CNES>" . $cnes . "</ans:CNES>
              </ans:dadosExecutante>
              <ans:dadosAtendimento>
              <ans:tipoAtendimento>04</ans:tipoAtendimento>
              <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
              <ans:tipoConsulta>1</ans:tipoConsulta>

              </ans:dadosAtendimento>
              " . (
                      ($item->grupo != "MATERIAL" && $item->grupo != "MEDICAMENTO") ? "<ans:procedimentosExecutados>
                     <ans:procedimentoExecutado>
                            <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                            <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                            <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                            <ans:procedimento>
                            <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                           <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                           <ans:descricaoProcedimento >" . substr($item->procedimento, 0, 60) . "</ans:descricaoProcedimento >
                           </ans:procedimento>                        
                    <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                        <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                        <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                        <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                        <ans:equipeSadt>
                            <ans:grauPart>12</ans:grauPart>
                            <ans:codProfissional>
                                <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                            </ans:codProfissional>
                            <ans:nomeProf>" . $medico . "</ans:nomeProf>
                            <ans:conselho>06</ans:conselho>
                            <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                            <ans:UF>" . $codigoUF . "</ans:UF>
                            <ans:CBOS>$cbo_medico</ans:CBOS>
                        </ans:equipeSadt>
                  </ans:procedimentoExecutado>
              </ans:procedimentosExecutados>" : "<ans:outrasDespesas>
                     <ans:despesa>
                        <ans:codigoDespesa>" . $codDespesa . "</ans:codigoDespesa>
                        <ans:servicosExecutados>
                            <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                            <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                            <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                            <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                            <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                            <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                            <ans:unidadeMedida>036</ans:unidadeMedida>
                            <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                            <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                            <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                            <ans:descricaoProcedimento >" . substr($item->procedimento, 0, 60) . "</ans:descricaoProcedimento >
                        </ans:servicosExecutados>
                    </ans:despesa>
              </ans:outrasDespesas>
              " ) . "

              <ans:observacao>III</ans:observacao>
                 <ans:valorTotal >
                 <ans:valorProcedimentos >" . number_format($valorProcedimento, 2, '.', '') . "</ans:valorProcedimentos >
                 <ans:valorDiarias>0.00</ans:valorDiarias>
                 <ans:valorTaxasAlugueis>0.00</ans:valorTaxasAlugueis>
                 <ans:valorMateriais>" . number_format($valorMaterial, 2, '.', '') . "</ans:valorMateriais>
                 <ans:valorMedicamentos>" . number_format($valorMedicamento, 2, '.', '') . "</ans:valorMedicamentos>
                 <ans:valorOPME>0.00</ans:valorOPME>
                 <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                 <ans:valorTotalGeral>" . $item->valor_total . "</ans:valorTotalGeral>
              </ans:valorTotal>
              </ans:guiaSP-SADT>";
                        } else {
                            $corpo = $corpo . "
                                                  <ans:guiaSP-SADT>
                  <ans:cabecalhoGuia>
                    <ans:registroANS>" . $registroans . "</ans:registroANS>
                 <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f' ? $value->ambulatorio_guia_id : $item->agenda_exames_id)) . "</ans:numeroGuiaPrestador>
                 <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
              </ans:cabecalhoGuia>
              <ans:dadosAutorizacao>
              <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
              <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
              </ans:dadosAutorizacao>
              <ans:dadosBeneficiario>
                 <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                     <ans:atendimentoRN>S</ans:atendimentoRN>
                 <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
              </ans:dadosBeneficiario>
                                              <ans:dadosSolicitante>
                 <ans:contratadoSolicitante>" .
                                    ( ($modelo == 'cpf') ? ("<ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>") : ("<ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>")) .
                                    "<ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                 </ans:contratadoSolicitante>
                 <ans:profissionalSolicitante>
                    <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                    <ans:conselhoProfissional>06</ans:conselhoProfissional>
                    <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                        <ans:UF>" . $codigoUF . "</ans:UF>
                    <ans:CBOS>$cbo_medico</ans:CBOS>
                 </ans:profissionalSolicitante>
              </ans:dadosSolicitante>
              <ans:dadosSolicitacao>
                 <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                 <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                 <ans:indicacaoClinica>I</ans:indicacaoClinica>
              </ans:dadosSolicitacao>
              <ans:dadosExecutante>
                       
                    <ans:contratadoExecutante>" .
                                    ( ($modelo == 'cpf') ? ("<ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>") : ("<ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>")) .
                                    "<ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                        <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>     
                    </ans:contratadoExecutante>
                 <ans:CNES>" . $cnes . "</ans:CNES>
              </ans:dadosExecutante>
              <ans:dadosAtendimento>
              <ans:tipoAtendimento>04</ans:tipoAtendimento>
              <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
              <ans:tipoConsulta>1</ans:tipoConsulta>

              </ans:dadosAtendimento>" . (
                                    ($item->grupo != "MATERIAL" && $item->grupo != "MEDICAMENTO") ? "<ans:procedimentosExecutados>
                     <ans:procedimentoExecutado>
                            <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                            <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                            <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                            <ans:procedimento>
                            <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                           <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                           <ans:descricaoProcedimento >" . substr($item->procedimento, 0, 60) . "</ans:descricaoProcedimento >
                           </ans:procedimento>                        
                    <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                        <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                        <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                        <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                        <ans:equipeSadt>
                            <ans:grauPart>12</ans:grauPart>
                            <ans:codProfissional>
                            <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                            </ans:codProfissional>
                            <ans:nomeProf>" . $medico . "</ans:nomeProf>
                            <ans:conselho>06</ans:conselho>
                            <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                            <ans:UF>" . $codigoUF . "</ans:UF>
                            <ans:CBOS>$cbo_medico</ans:CBOS>
                        </ans:equipeSadt>
                  </ans:procedimentoExecutado>
              </ans:procedimentosExecutados>" : "<ans:outrasDespesas>
                     <ans:despesa>
                        <ans:codigoDespesa>" . $codDespesa . "</ans:codigoDespesa>
                        <ans:servicosExecutados>
                            <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                            <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                            <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                            <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                            <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                            <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                            <ans:unidadeMedida>036</ans:unidadeMedida>
                            <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                            <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                            <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                            <ans:descricaoProcedimento >" . substr($item->procedimento, 0, 60) . "</ans:descricaoProcedimento >
                        </ans:servicosExecutados>
                    </ans:despesa>
              </ans:outrasDespesas>
              " ) . "

              <ans:observacao>III</ans:observacao>
                 <ans:valorTotal >
                 <ans:valorProcedimentos >" . number_format($valorProcedimento, 2, '.', '') . "</ans:valorProcedimentos >
                 <ans:valorDiarias>0.00</ans:valorDiarias>
                 <ans:valorTaxasAlugueis>0.00</ans:valorTaxasAlugueis>
                 <ans:valorMateriais>" . number_format($valorMaterial, 2, '.', '') . "</ans:valorMateriais>
                 <ans:valorMedicamentos>" . number_format($valorMedicamento, 2, '.', '') . "</ans:valorMedicamentos>
                 <ans:valorOPME>0.00</ans:valorOPME>
                 <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                 <ans:valorTotalGeral>" . $item->valor_total . "</ans:valorTotalGeral>
              </ans:valorTotal>
              </ans:guiaSP-SADT>";
                        }
                        if (!$limite) {
                            if ($totExames == count($listarexames)) {
                                $contador = $contador - $i;
                                $b++;
                                $i = 0;
                                $rodape = "</ans:guiasTISS>
</ans:loteGuias>
</ans:prestadorParaOperadora>
<ans:epilogo>
<ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
</ans:epilogo>
</ans:mensagemTISS>";

                                $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                $xml = $cabecalho . $corpo . $rodape;
                                $fp = fopen($nome, "w+");
                                fwrite($fp, $xml . "\n");
                                fclose($fp);
                                $corpo = "";
                                $rodape = "";
                                if ($_POST['espelho_conferencia'] == "SIM") {
                                   $this->gerarPDFXML($nomePDF, $arrayPDF);
                                   $arrayPDF = array();
                                } 
                                
                            }
                        } else {

                            if ($i == 100) {
                                $contador = $contador - $i;

                                $i = 0;
                                $rodape = "   </ans:guiasTISS>

  </ans:loteGuias>
</ans:prestadorParaOperadora>
<ans:epilogo>
  <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
</ans:epilogo>
</ans:mensagemTISS>
";

                                $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                $xml = $cabecalho . $corpo . $rodape;
                                $fp = fopen($nome, "w+");
                                fwrite($fp, $xml . "\n");
                                fclose($fp);
                                $b++;
                                $corpo = "";
                                $rodape = "";
                                if ($_POST['espelho_conferencia'] == "SIM") {
                                    $this->gerarPDFXML($nomePDF, $arrayPDF);
                                    $arrayPDF = array();
                                 } 
                            }

                            if ($contador < 100 && $contador == $i) {
                                $i = 0;
                                $rodape = "   </ans:guiasTISS>

  </ans:loteGuias>
</ans:prestadorParaOperadora>
<ans:epilogo>
  <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
</ans:epilogo>
</ans:mensagemTISS>
";
                                $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                $xml = $cabecalho . $corpo . $rodape;
                                $fp = fopen($nome, "w+");
                                fwrite($fp, $xml . "\n");
                                fclose($fp);
                                $b++;
                                $corpo = "";
                                $rodape = "";
                                if ($_POST['espelho_conferencia'] == "SIM") {
                                    $this->gerarPDFXML($nomePDF, $arrayPDF);
                                    $arrayPDF = array();
                                 }
                                
                            }
                        }
                    }
                }
            }
        } else {


            if ($versao == '3.03.01' || $versao == '3.03.02' || $versao == '3.03.03') {

                if ($modelo == 'cpf') {                    
                    if ($_POST['layoutarq'] == 'sadt' && count($listarexame) > 0) {
                        $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
        <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
           <ans:cabecalho>
              <ans:identificacaoTransacao>
                 <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
                 <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
                 <ans:dataRegistroTransacao>" . $horario . "</ans:dataRegistroTransacao>
                 <ans:horaRegistroTransacao>" . $hora . "</ans:horaRegistroTransacao>
              </ans:identificacaoTransacao>
              <ans:origem>
              <ans:identificacaoPrestador>" .
                                ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                                "</ans:identificacaoPrestador>
              </ans:origem>
              <ans:destino>
                 <ans:registroANS>" . $registroans . "</ans:registroANS>
              </ans:destino>
              <ans:Padrao>" . $versao . "</ans:Padrao>
           </ans:cabecalho>

           <ans:prestadorParaOperadora>
              <ans:loteGuias>
                 <ans:numeroLote>" . $b . "</ans:numeroLote>
                    <ans:guiasTISS>";
                        $contador = 0;
                        foreach ($listarpacienete as $pac) {
                            $contador += $pac->contador;
                        }
                                    $valorMaterial = 0.00;
                                    $valorMedicamento = 0.00;
                                    $valorTaxa= 0.00;
                                    $totalgeral=0.00;
                        foreach ($listarpacienete as $value) {                            
                            $produtos_gastos = $this->exame->listaritensgastosprocedimento($value->exames_id); 
                            $totalgastos = 0;
                            $totalgastostaxa= 0;
                            $totalgastosmaterial = 0;
                            $totalgastosmedicamento= 0;
                            
                             if (count($produtos_gastos) > 0) {
                                foreach($produtos_gastos as $v){                                    
                                    $totalgastos += $v->valor;
                                    if ($v->tipo = "MATERIAL") {
                                        $totalgastosmaterial += $v->valor;
                                    } elseif ($v->tipo = "TAXA") {
                                        $totalgastostaxa += $v->valor;
                                    } elseif ($v->tipo = "MEDICAMENTO") {
                                        $totalgastosmedicamento += $v->valor;
                                    } else {
                                        
                                    }
                                }                                
                            }
                              
                            if ($value->convenionumero == '') {
                                $numerodacarteira = '0000000';
                            } else {
                                $numerodacarteira = $value->convenionumero;
                            }

                                   
                            foreach ($listarexames as $item) {
                    
                                if ($item->guiaconvenio == '') {
                                    $guianumero = '0000000';
                                } else {
                                    $guianumero = $item->guiaconvenio;
                                }
                    
                                if ($value->paciente_id == $item->paciente_id && $value->ambulatorio_guia_id == $item->ambulatorio_guia_id  && $item->exames_id == $value->exames_id) {
                                    $tabela = '22';
                                    //                                die('morreu');
                                    array_push($arrayPDF, $item);
                                    $valorProcedimento = $item->valor_total;
                                

                                    if ($item->grupo == "MATERIAL") { //caso seja material
                                        $tabela = '19';
                                        $codDespesa = '03';
                                        $valorMaterial = $item->valor_total + $totalgastosmaterial;
                                        $valorProcedimento = 0.00;
                                    } elseif ($item->grupo == "MEDICAMENTO") { //caso seja medicamento
                                        $tabela = '20';
                                        $codDespesa = '02';
                                        $valorMedicamento = $item->valor_total + $totalgastosmedicamento;
                                        $valorProcedimento = 0.00;
                                    }
                                    
                                     if ($item->grupo == "TAXA") {
                                        $tabela = '18';
                                        $valorTaxa = $item->valor_total + $totalgastostaxa;
                                        $valorProcedimento = 0.00;
                                    }
                                    
                                    $totalgeral = $item->valor_total + $totalgastos;

                                    $i++;
                                    $totExames++;
                                    $data_autorizacao = $this->exame->listarxmldataautorizacao($value->agenda_exames_id);
                                    $dataautorizacao = substr($data_autorizacao[0]->data_cadastro, 0, 10);
                                    $dataValidadeSenha = date('Y-m-d', strtotime("+30 days", strtotime($dataautorizacao)));
                                    $carater_xml = $item->carater_xml;
                                    if ($item->medico == '') {
                                        $medico = 'ADMINISTRADOR';
                                    } else {
                                        $medico = $item->medico;
                                    }
                                    if ($item->conselho == '') {
                                        $conselho = '0000000';
                                    } else {
                                        $conselho = $item->conselho;
                                    }
                                    if ($item->cbo_ocupacao_id == '') {
                                        $cbo_medico = '999999';
                                    } else {
                                        $cbo_medico = $item->cbo_ocupacao_id;
                                    }
                                    if ($item->medicosolicitante == '') {
                                        $medicosolicitante = $item->medico;
                                    } else {
                                        $medicosolicitante = $item->medicosolicitante;
                                    }
                                    if ($item->conselhosolicitante == '') {
                                        $conselhosolicitante = $item->conselho;
                                    } else {
                                        $conselhosolicitante = $item->conselhosolicitante;
                                    }

                                    if ($_POST['autorizacao'] == 'SIM') {
                                        $corpo = $corpo . "
                        <ans:guiaSP-SADT>
                          <ans:cabecalhoGuia>
                             <ans:registroANS>" . $registroans . "</ans:registroANS>
                             <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f') ? $value->ambulatorio_guia_id : $item->agenda_exames_id) . "</ans:numeroGuiaPrestador>
                             <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                          </ans:cabecalhoGuia>

                          <ans:dadosAutorizacao>
                            <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                            <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                            <ans:senha>" . $item->autorizacao . "</ans:senha>
                            <ans:dataValidadeSenha>" . $dataValidadeSenha . "</ans:dataValidadeSenha> 
                          </ans:dadosAutorizacao>

                          <ans:dadosBeneficiario>
                             <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                             <ans:atendimentoRN>S</ans:atendimentoRN>
                             <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                          </ans:dadosBeneficiario>

                          <ans:dadosSolicitante>
                            <ans:contratadoSolicitante>
                                <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                            </ans:contratadoSolicitante>

                            <ans:profissionalSolicitante>
                                <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>06</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                            </ans:profissionalSolicitante>

                          </ans:dadosSolicitante>

                          <ans:dadosSolicitacao>
                             <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                             <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                             <ans:indicacaoClinica>I</ans:indicacaoClinica>
                          </ans:dadosSolicitacao>

                          <ans:dadosExecutante>
        
                             <ans:contratadoExecutante>
                                 <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                 <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                                 <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoExecutante>
                             <ans:CNES>" . $cnes . "</ans:CNES>
                          </ans:dadosExecutante>

                          <ans:dadosAtendimento>
                            <ans:tipoAtendimento>04</ans:tipoAtendimento>
                            <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                            <ans:tipoConsulta>1</ans:tipoConsulta>

                          </ans:dadosAtendimento>";                                               
                              if($item->tipo != "MATERIAL" && $item->tipo != "MEDICAMENTO" && $item->tipo != "TAXA"){
                                     $corpo .="<ans:procedimentosExecutados>
                             <ans:procedimentoExecutado>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                    <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                    <ans:procedimento>
                                       <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                       <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                                       <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                                   </ans:procedimento>                        
                                   <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                                    <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                                    <ans:equipeSadt>
                                        <ans:grauPart>12</ans:grauPart>
                                        <ans:codProfissional>
                                            <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                        </ans:codProfissional>
                                        <ans:nomeProf>" . $medico . "</ans:nomeProf>
                                        <ans:conselho>06</ans:conselho>
                                        <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                                        <ans:UF>" . $codigoUF . "</ans:UF>
                                        <ans:CBOS>$cbo_medico</ans:CBOS>
                                    </ans:equipeSadt>
                          </ans:procedimentoExecutado>
                                    </ans:procedimentosExecutados>"; 
                             if (count($produtos_gastos) > 0) { 
                                 $corpo .= "<ans:outrasDespesas>"; 
                               foreach($produtos_gastos as $gastos){
                                if($gastos->tipo == "MATERIAL" ){
                                      $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>03</ans:codigoDespesa>
                                    <ans:servicosExecutados
                                    ><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>19</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "TAXA" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>07</ans:codigoDespesa>
                                    <ans:servicosExecutados><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>18</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "MEDICAMENTO"){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>02</ans:codigoDespesa>
                                    <ans:servicosExecutados>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>20</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>001</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }else{
                                      $corpo .="";
                                }
                               } 
                               $corpo .= "</ans:outrasDespesas>";
                              }
                           } 
                    
                                        $corpo .="
                          <ans:observacao>III</ans:observacao>
                             <ans:valorTotal >
                             <ans:valorProcedimentos >" . number_format($valorProcedimento, 2, '.', '') . "</ans:valorProcedimentos >
                             <ans:valorDiarias>0.00</ans:valorDiarias>
                             <ans:valorTaxasAlugueis>" . number_format($totalgastostaxa, 2, '.', '') . "</ans:valorTaxasAlugueis>
                             <ans:valorMateriais>" . number_format($totalgastosmaterial, 2, '.', '') . "</ans:valorMateriais>
                             <ans:valorMedicamentos>" . number_format($totalgastosmedicamento, 2, '.', '') . "</ans:valorMedicamentos>
                             <ans:valorOPME>0.00</ans:valorOPME>
                             <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                             <ans:valorTotalGeral>" . $totalgeral . "</ans:valorTotalGeral>
                          </ans:valorTotal>
                          </ans:guiaSP-SADT>";
                                    } else {

                                        //                                    die('morreu02');
                                        $corpo = $corpo . "
                    <ans:guiaSP-SADT>
                              <ans:cabecalhoGuia>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                             <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f') ? $value->ambulatorio_guia_id : $item->agenda_exames_id) . "</ans:numeroGuiaPrestador>
                             <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                          </ans:cabecalhoGuia>
                          <ans:dadosAutorizacao>
                          <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                          <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                          </ans:dadosAutorizacao>
                          <ans:dadosBeneficiario>
                             <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                 <ans:atendimentoRN>S</ans:atendimentoRN>
                             <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                          </ans:dadosBeneficiario>

                          <ans:dadosSolicitante>
                             <ans:contratadoSolicitante>
                                   <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                                   <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoSolicitante>
                             <ans:profissionalSolicitante>
                                <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>06</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                             </ans:profissionalSolicitante>
                          </ans:dadosSolicitante>

                          <ans:dadosSolicitacao>
                             <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                             <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                             <ans:indicacaoClinica>I</ans:indicacaoClinica>
                          </ans:dadosSolicitacao>

                          <ans:dadosExecutante>
                            <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoExecutante>
                             <ans:CNES>" . $cnes . "</ans:CNES>
                          </ans:dadosExecutante>

                          <ans:dadosAtendimento>
                            <ans:tipoAtendimento>04</ans:tipoAtendimento>
                            <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                            <ans:tipoConsulta>1</ans:tipoConsulta>

                          </ans:dadosAtendimento>";
                                    if($item->tipo != "MATERIAL" && $item->tipo != "MEDICAMENTO"  && $item->tipo != "TAXA") {
                                       $corpo .="<ans:procedimentosExecutados>
                             <ans:procedimentoExecutado>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                    <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                    <ans:procedimento>
                                        <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                        <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                                        <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                                    </ans:procedimento>                        
                                    <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                                    <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                                    <ans:equipeSadt>
                                        <ans:grauPart>12</ans:grauPart>
                                        <ans:codProfissional>
                                            <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                        </ans:codProfissional>
                                        <ans:nomeProf>" . $medico . "</ans:nomeProf>
                                        <ans:conselho>06</ans:conselho>
                                        <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                                        <ans:UF>" . $codigoUF . "</ans:UF>
                                        <ans:CBOS>$cbo_medico</ans:CBOS>
                                    </ans:equipeSadt>
                          </ans:procedimentoExecutado>
                        </ans:procedimentosExecutados>" ;
                    
                              if (count($produtos_gastos) > 0) {  
                                  $corpo .= "<ans:outrasDespesas>"; 
                            foreach($produtos_gastos as $gastos){
                                if($gastos->tipo == "MATERIAL"){
                                      $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>03</ans:codigoDespesa>
                                    <ans:servicosExecutados
                                    ><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>19</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "TAXA" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>07</ans:codigoDespesa>
                                    <ans:servicosExecutados><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>18</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "MEDICAMENTO" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>02</ans:codigoDespesa>
                                    <ans:servicosExecutados>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>20</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>001</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }else{
                                      $corpo .="";
                                   }
                                 }
                               $corpo .= "</ans:outrasDespesas>";
                              }
                           }
                    
                                        $corpo .="
                          <ans:observacao>III</ans:observacao>
                             <ans:valorTotal >
                             <ans:valorProcedimentos >" . number_format($valorProcedimento, 2, '.', '') . "</ans:valorProcedimentos >
                             <ans:valorDiarias>0.00</ans:valorDiarias>
                             <ans:valorTaxasAlugueis>" . number_format($totalgastostaxa, 2, '.', '') . "</ans:valorTaxasAlugueis>
                             <ans:valorMateriais>" . number_format($totalgastosmaterial, 2, '.', '') . "</ans:valorMateriais>
                             <ans:valorMedicamentos>" . number_format($totalgastosmedicamento, 2, '.', '') . "</ans:valorMedicamentos>
                             <ans:valorOPME>0.00</ans:valorOPME>
                             <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                             <ans:valorTotalGeral>" . $totalgeral . "</ans:valorTotalGeral>
                          </ans:valorTotal>
                          </ans:guiaSP-SADT>";
                                        
                                    }

                                    if (!$limite) {
                                        if ($totExames == count($listarexames)) {
                                            $contador = $contador - $i;

                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>
              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>";

                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                            }
                                            
                                        }
                                    } else {
                                        if ($i == 100) {
                                            $contador = $contador - $i;

                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>
              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>";

                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                            
                                        }
                                        if ($contador < 100 && $contador == $i) {
                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>
              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>";
                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                            
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if (count($listarexame) > 0) {
                            $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
            <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
               <ans:cabecalho>
                  <ans:identificacaoTransacao>
                     <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
                     <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
                     <ans:dataRegistroTransacao>" . substr($listarexame[0]->data_autorizacao, 0, 10) . "</ans:dataRegistroTransacao>
                     <ans:horaRegistroTransacao>" . $hora . "</ans:horaRegistroTransacao>
                  </ans:identificacaoTransacao>
                  <ans:origem>
                    <ans:identificacaoPrestador>" .
                                    ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                                    "</ans:identificacaoPrestador>
                  </ans:origem>
                  <ans:destino>
                     <ans:registroANS>" . $registroans . "</ans:registroANS>
                  </ans:destino>
                  <ans:Padrao>" . $versao . "</ans:Padrao>
               </ans:cabecalho>
               <ans:prestadorParaOperadora>
                  <ans:loteGuias>
                     <ans:numeroLote>" . $b . "</ans:numeroLote>
                        <ans:guiasTISS>";
                            $contador = count($listarexame);
                            foreach ($listarexame as $value) {

                                $tabela = '22';
                                //                        $valorProcedimento = $value->valor;
                                //                        $valorMaterial = 0.00;
                                //                        $valorMedicamento = 0.00;

                                if ($value->grupo == "MATERIAL") { //caso seja material
                                    $tabela = '19';
                                    $codDespesa = '03';
                                    //                            $valorMaterial = $value->valor;
                                    //                            $valorProcedimento = 0.00;
                                } elseif ($value->grupo == "MEDICAMENTO") { //caso seja medicamento
                                    $tabela = '20';
                                    $codDespesa = '02';
                                    //                            $valorMedicamento = $value->valor;
                                    //                            $valorProcedimento = 0.00;
                                }

                                if ($value->convenionumero == '') {
                                    $numerodacarteira = '0000000';
                                } else {
                                    $numerodacarteira = $value->convenionumero;
                                }
                                if ($value->medico == '') {
                                    $medico = 'ADMINISTRADOR';
                                } else {
                                    $medico = $value->medico;
                                }
                                if ($value->cbo_ocupacao_id == '') {
                                    $cbo_medico = '999999';
                                } else {
                                    $cbo_medico = $value->cbo_ocupacao_id;
                                }
                                if ($value->conselho == '') {
                                    $conselho = '0000000';
                                } else {
                                    $conselho = $value->conselho;
                                }
                                if ($value->guiaconvenio == '') {
                                    $guianumero = '0000000';
                                } else {
                                    $guianumero = $value->guiaconvenio;
                                }
                                $corpo = $corpo . "
                        <ans:guiaConsulta>
                            <ans:cabecalhoConsulta>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                                <ans:numeroGuiaPrestador>" . (($value->guia_prestador_unico == 'f' ? $value->ambulatorio_guia_id : $value->agenda_exames_id)) . "</ans:numeroGuiaPrestador>
                            </ans:cabecalhoConsulta>
                            <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                            <ans:dadosBeneficiario>
                                <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                <ans:atendimentoRN>N</ans:atendimentoRN>
                                <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                            </ans:dadosBeneficiario>
                            <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cpfxml . "</ans:codigoPrestadorNaOperadora>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                                <ans:CNES>" . $cnes . "</ans:CNES>
                            </ans:contratadoExecutante>
                            <ans:profissionalExecutante>
                                <ans:nomeProfissional>" . $medico . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>06</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional>" . $conselho . "</ans:numeroConselhoProfissional>
                                <ans:UF>15</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                            </ans:profissionalExecutante>
                            <ans:indicacaoAcidente>9</ans:indicacaoAcidente>
                            <ans:dadosAtendimento>
                                <ans:dataAtendimento>" . substr($value->data_autorizacao, 0, 10) . "</ans:dataAtendimento>
                                <ans:tipoConsulta>1</ans:tipoConsulta>
                                <ans:procedimento>
                                    <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $value->codigo . "</ans:codigoProcedimento>
                                    <ans:valorProcedimento>" . $value->valor . "</ans:valorProcedimento>
                                </ans:procedimento>
                            </ans:dadosAtendimento>
                        </ans:guiaConsulta>";
                                if (!$limite) {
                                    if ($totExames == count($listarexames)) {
                                        $contador = $contador - $i;
                                        $b++;
                                        $i = 0;
                                        $rodape = "</ans:guiasTISS>
                </ans:loteGuias>
            </ans:prestadorParaOperadora>
            <ans:epilogo>
            <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
            </ans:epilogo>
            </ans:mensagemTISS>";

                                        $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                        $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                        $xml = $cabecalho . $corpo . $rodape;
                                        $fp = fopen($nome, "w+");
                                        fwrite($fp, $xml . "\n");
                                        fclose($fp);
                                        $corpo = "";
                                        $rodape = "";
                                        if ($_POST['espelho_conferencia'] == "SIM") {
                                            $this->gerarPDFXML($nomePDF, $arrayPDF);
                                            $arrayPDF = array();
                                         }
                                    }
                                } else {
                                    if ($i == 100) {
                                        $contador = $contador - $i;
                                        $b++;
                                        $i = 0;
                                        $rodape = "</ans:guiasTISS>
                </ans:loteGuias>
            </ans:prestadorParaOperadora>
            <ans:epilogo>
            <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
            </ans:epilogo>
            </ans:mensagemTISS>";

                                        $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                        $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                        $xml = $cabecalho . $corpo . $rodape;
                                        $fp = fopen($nome, "w+");
                                        fwrite($fp, $xml . "\n");
                                        fclose($fp);
                                        $corpo = "";
                                        $rodape = "";
                                        if ($_POST['espelho_conferencia'] == "SIM") {
                                            $this->gerarPDFXML($nomePDF, $arrayPDF);
                                            $arrayPDF = array();
                                         }
                                    }
                                    if ($contador < 100 && $contador == $i) {

                                        $i = 0;
                                        $rodape = "   </ans:guiasTISS>


                </ans:loteGuias>
            </ans:prestadorParaOperadora>
            <ans:epilogo>
            <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
            </ans:epilogo>
            </ans:mensagemTISS>";
                                        $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                        $xml = $cabecalho . $corpo . $rodape;
                                        $fp = fopen($nome, "w+");
                                        fwrite($fp, $xml . "\n");
                                        fclose($fp);
                                        $b++;
                                        $corpo = "";
                                        $rodape = "";
                                        if ($_POST['espelho_conferencia'] == "SIM") {
                                            $this->gerarPDFXML($nomePDF, $arrayPDF);
                                            $arrayPDF = array();
                                         }
                                    }
                                }
                            }
                        }
                    }
                } else {

                    if ($_POST['layoutarq'] == 'sadt' && count($listarexame) > 0) {

                        // echo 'its here'; die;
                        $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
        <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
           <ans:cabecalho>
              <ans:identificacaoTransacao>
                 <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
                 <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
                 <ans:dataRegistroTransacao>" . substr($listarexame[0]->data_autorizacao, 0, 10) . "</ans:dataRegistroTransacao>
                 <ans:horaRegistroTransacao>" . $hora . "</ans:horaRegistroTransacao>
              </ans:identificacaoTransacao>
              <ans:origem>
                <ans:identificacaoPrestador>" .
                                ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                                "</ans:identificacaoPrestador>
              </ans:origem>
              <ans:destino>
                 <ans:registroANS>" . $registroans . "</ans:registroANS>
              </ans:destino>
              <ans:Padrao>" . $versao . "</ans:Padrao>
           </ans:cabecalho>
           <ans:prestadorParaOperadora>
              <ans:loteGuias>
                 <ans:numeroLote>" . $b . "</ans:numeroLote>
                    <ans:guiasTISS>";
                        $contador = 0;
                        foreach ($listarpacienete as $pac) {
                            $contador += $pac->contador;
                        }
                        foreach ($listarpacienete as $value) {
                            $totalgastos = 0;
                            $totalgastostaxa= 0;
                            $totalgastosmaterial = 0;
                            $totalgastosmedicamento= 0;                            
                            $produtos_gastos = $this->exame->listaritensgastosprocedimento($value->exames_id);
                            if (count($produtos_gastos) > 0) {
                                foreach($produtos_gastos as $v){                                    
                                    $totalgastos += $v->valor;
                                    if ($v->tipo = "MATERIAL") {
                                        $totalgastosmaterial += $v->valor;
                                    } elseif ($v->tipo = "TAXA") {
                                        $totalgastostaxa += $v->valor;
                                    } elseif ($v->tipo = "MEDICAMENTO") {
                                        $totalgastosmedicamento += $v->valor;
                                    } else {
                                        
                                    }
                                }  
                              
                            }

                            if ($value->convenionumero == '') {
                                $numerodacarteira = '0000000';
                            } else {
                                $numerodacarteira = $value->convenionumero;
                            }
                                    $valorMaterial = 0.00;
                                    $valorMedicamento = 0.00;
                                    $valorTaxa = 0.00;
                                    
                            foreach ($listarexames as $item) {
                                if ($item->guiaconvenio == '') {
                                    $guianumero = '0000000';
                                } else {
                                    $guianumero = $item->guiaconvenio;
                                }
                                

                                if ($item->cbo_ocupacao_id == '') {
                                    $cbo_medico = '999999';
                                } else {
                                    $cbo_medico = $item->cbo_ocupacao_id;
                                }

                                if ($value->paciente_id == $item->paciente_id && $value->ambulatorio_guia_id == $item->ambulatorio_guia_id  && $item->exames_id == $value->exames_id) {
                                    $tabela = '22';
                                    array_push($arrayPDF, $item);
                                    $valorProcedimento = $item->valor_total;
                                  

                                    if ($item->grupo == "MATERIAL") { //caso seja material
                                        $tabela = '19';
                                        $codDespesa = '03';                                        
                                        $valorMaterial = $item->valor_total  + $totalgastosmaterial;
                                        $valorProcedimento = 0.00;
                                    } elseif ($item->grupo == "MEDICAMENTO") { //caso seja medicamento
                                        $tabela = '20';
                                        $codDespesa = '02';
                                        $valorMedicamento = $item->valor_total + $totalgastosmedicamento;
                                        $valorProcedimento = 0.00;
                                    }
                                    if ($item->grupo == "TAXA") {
                                        $valorTaxa = $item->valor_total + $totalgastostaxa;
                                        $valorProcedimento = 0.00;
                                    }
                                    
                                    $totalgeral = $item->valor_total + $totalgastos;
                                    
                                    $i++;

                                    $totExames++;
                                    // echo "<span style='color:red'>$totExames</span>";
                                    $data_autorizacao = $this->exame->listarxmldataautorizacao($value->agenda_exames_id);
                                    $dataautorizacao = substr($data_autorizacao[0]->data_cadastro, 0, 10);
                                    $dataValidadeSenha = date('Y-m-d', strtotime("+30 days", strtotime($dataautorizacao)));
                                    $carater_xml = $item->carater_xml;
                                    if ($item->medico == '') {
                                        $medico = 'ADMINISTRADOR';
                                    } else {
                                        $medico = $item->medico;
                                    }
                                    if ($item->conselho == '') {
                                        $conselho = '0000000';
                                    } else {
                                        $conselho = $item->conselho;
                                    }
                                    if ($item->medicosolicitante == '') {
                                        $medicosolicitante = $item->medico;
                                    } else {
                                        $medicosolicitante = $item->medicosolicitante;
                                    }
                                    if ($item->conselhosolicitante == '') {
                                        $conselhosolicitante = $item->conselho;
                                    } else {
                                        $conselhosolicitante = $item->conselhosolicitante;
                                    }

                                    if ($_POST['autorizacao'] == 'SIM') {
                                        $corpo = $corpo . "
                                                              <ans:guiaSP-SADT>
                              <ans:cabecalhoGuia>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                             <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f' ? $value->ambulatorio_guia_id : $item->agenda_exames_id)) . "</ans:numeroGuiaPrestador>
                             <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                          </ans:cabecalhoGuia>
                          <ans:dadosAutorizacao>
                          <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                          <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                          <ans:senha>" . $item->autorizacao . "</ans:senha>
                          <ans:dataValidadeSenha>" . $dataValidadeSenha . "</ans:dataValidadeSenha> 
                          </ans:dadosAutorizacao>
                          <ans:dadosBeneficiario>
                             <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                 <ans:atendimentoRN>S</ans:atendimentoRN>
                             <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                          </ans:dadosBeneficiario>
                                                          <ans:dadosSolicitante>
                             <ans:contratadoSolicitante>
                                   <ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoSolicitante>
                             <ans:profissionalSolicitante>
                                <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>06</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                    <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                             </ans:profissionalSolicitante>
                          </ans:dadosSolicitante>
                          <ans:dadosSolicitacao>
                             <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                             <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                             <ans:indicacaoClinica>I</ans:indicacaoClinica>
                          </ans:dadosSolicitacao>
                          <ans:dadosExecutante>
                                <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>                               
                             <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoExecutante>
                             <ans:CNES>" . $cnes . "</ans:CNES>
                          </ans:dadosExecutante>
                          <ans:dadosAtendimento>
                          <ans:tipoAtendimento>04</ans:tipoAtendimento>
                          <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                          <ans:tipoConsulta>1</ans:tipoConsulta>

                          </ans:dadosAtendimento>
                          ";
                            if($item->tipo != "MATERIAL" && $item->tipo != "MEDICAMENTO"  && $item->tipo != "TAXA") {
                                        $corpo .= "<ans:procedimentosExecutados>
                                 <ans:procedimentoExecutado>
                                        <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                        <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                        <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                        <ans:procedimento>
                                        <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                       <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                                       <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                                       </ans:procedimento>                        
                                <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                                    <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                                    <ans:equipeSadt>
                                        <ans:grauPart>12</ans:grauPart>
                                        <ans:codProfissional>
                                        <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                        </ans:codProfissional>
                                        <ans:nomeProf>" . $medico . "</ans:nomeProf>
                                        <ans:conselho>06</ans:conselho>
                                        <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                                        <ans:UF>" . $codigoUF . "</ans:UF>
                                        <ans:CBOS>$cbo_medico</ans:CBOS>
                                    </ans:equipeSadt>
                              </ans:procedimentoExecutado>
                                        </ans:procedimentosExecutados>";  
                        if (count($produtos_gastos) > 0) {
                             $corpo .= "<ans:outrasDespesas>";
                               foreach($produtos_gastos as $gastos){
                                if($gastos->tipo == "MATERIAL" ){
                                      $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>03</ans:codigoDespesa>
                                    <ans:servicosExecutados
                                    ><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>19</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "TAXA" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>07</ans:codigoDespesa>
                                    <ans:servicosExecutados><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>18</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "MEDICAMENTO"){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>02</ans:codigoDespesa>
                                    <ans:servicosExecutados>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>20</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>001</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }else{
                                      $corpo .="";
                                }
                               } 
                                $corpo .= "</ans:outrasDespesas>";
                              }
                         
                          }      
                    
                              $corpo .="<ans:observacao>III</ans:observacao>
                             <ans:valorTotal >
                             <ans:valorProcedimentos >" . number_format($valorProcedimento, 2, '.', '') . "</ans:valorProcedimentos >
                             <ans:valorDiarias>0.00</ans:valorDiarias>
                             <ans:valorTaxasAlugueis>" . number_format($totalgastostaxa, 2, '.', '') . "</ans:valorTaxasAlugueis>
                             <ans:valorMateriais>" . number_format($totalgastosmaterial, 2, '.', '') . "</ans:valorMateriais>
                             <ans:valorMedicamentos>" . number_format($totalgastosmedicamento, 2, '.', '') . "</ans:valorMedicamentos>
                             <ans:valorOPME>0.00</ans:valorOPME>
                             <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                             <ans:valorTotalGeral>" . $totalgeral . "</ans:valorTotalGeral>
                          </ans:valorTotal>
                          </ans:guiaSP-SADT>";
                                    } else {
                                        $corpo = $corpo . "
                                                              <ans:guiaSP-SADT>
                              <ans:cabecalhoGuia>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                             <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f' ? $value->ambulatorio_guia_id : $item->agenda_exames_id)) . "</ans:numeroGuiaPrestador>
                             <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                          </ans:cabecalhoGuia>
                          <ans:dadosAutorizacao>
                          <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                          <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                          </ans:dadosAutorizacao>
                          <ans:dadosBeneficiario>
                             <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                 <ans:atendimentoRN>S</ans:atendimentoRN>
                             <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                          </ans:dadosBeneficiario>
                                                          <ans:dadosSolicitante>
                             <ans:contratadoSolicitante>
                                   <ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoSolicitante>
                             <ans:profissionalSolicitante>
                                <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>06</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                    <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                             </ans:profissionalSolicitante>
                          </ans:dadosSolicitante>
                          <ans:dadosSolicitacao>
                             <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                             <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                             <ans:indicacaoClinica>I</ans:indicacaoClinica>
                          </ans:dadosSolicitacao>
                          <ans:dadosExecutante>                                
                                <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>                             
                             <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoExecutante>
                             <ans:CNES>" . $cnes . "</ans:CNES>
                          </ans:dadosExecutante>
                          <ans:dadosAtendimento>
                          <ans:tipoAtendimento>04</ans:tipoAtendimento>
                          <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                          <ans:tipoConsulta>1</ans:tipoConsulta>

                          </ans:dadosAtendimento>";
                                if($item->tipo != "MATERIAL" && $item->tipo != "MEDICAMENTO"  && $item->tipo != "TAXA"){  
                                  $corpo .= "<ans:procedimentosExecutados>
                                 <ans:procedimentoExecutado>
                                        <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                        <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                        <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                        <ans:procedimento>
                                        <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                       <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                                       <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                                       </ans:procedimento>                        
                                <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                                    <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                                    <ans:equipeSadt>
                                        <ans:grauPart>12</ans:grauPart>
                                        <ans:codProfissional>
                                        <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                        </ans:codProfissional>
                                        <ans:nomeProf>" . $medico . "</ans:nomeProf>
                                        <ans:conselho>06</ans:conselho>
                                        <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                                        <ans:UF>" . $codigoUF . "</ans:UF>
                                        <ans:CBOS>$cbo_medico</ans:CBOS>
                                    </ans:equipeSadt>
                              </ans:procedimentoExecutado>
                                </ans:procedimentosExecutados>";  
                             if (count($produtos_gastos) > 0) {
                                 $corpo .= "<ans:outrasDespesas>";
                               foreach($produtos_gastos as $gastos){
                                if($gastos->tipo == "MATERIAL" ){
                                      $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>03</ans:codigoDespesa>
                                    <ans:servicosExecutados
                                    ><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>19</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "TAXA" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>07</ans:codigoDespesa>
                                    <ans:servicosExecutados><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>18</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "MEDICAMENTO" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>02</ans:codigoDespesa>
                                    <ans:servicosExecutados>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>20</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>001</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }else{
                                      $corpo .="";
                                  }
                                } 
                                 $corpo .= "</ans:outrasDespesas>"; 
                              }
                           } 
                    
                                        $corpo .="
                          <ans:observacao>III</ans:observacao>
                             <ans:valorTotal >
                             <ans:valorProcedimentos >" . number_format($valorProcedimento, 2, '.', '') . "</ans:valorProcedimentos >
                             <ans:valorDiarias>0.00</ans:valorDiarias>
                             <ans:valorTaxasAlugueis>" . number_format($totalgastostaxa, 2, '.', '') . "</ans:valorTaxasAlugueis>
                             <ans:valorMateriais>" . number_format($totalgastosmaterial, 2, '.', '') . "</ans:valorMateriais>
                             <ans:valorMedicamentos>" . number_format($totalgastosmedicamento, 2, '.', '') . "</ans:valorMedicamentos>
                             <ans:valorOPME>0.00</ans:valorOPME>
                             <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                             <ans:valorTotalGeral>" . $totalgeral . "</ans:valorTotalGeral>
                          </ans:valorTotal>
                          </ans:guiaSP-SADT>";
                                    }

                                    if (!$limite) {

                                        if ($totExames == count($listarexames)) {
                                            $contador = $contador - $i;
                                            $b++;
                                            $i = 0;
                                            $rodape = "</ans:guiasTISS>
            </ans:loteGuias>
        </ans:prestadorParaOperadora>
        <ans:epilogo>
        <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
        </ans:epilogo>
        </ans:mensagemTISS>";

                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";

                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");

                                            $html = "<table border=2><tr><td>teste</td></tr></table>";


                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
//                                            salvapdf($html, $nomepdf, "", "");


                                            fclose($fp);
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                            //    echo 'gravou o xml';
                                        }
                                        $total_certao = count($listarexames);
                                        // echo '<pre>';
                                        // var_dump($item); 
                                        // echo '<br> <hr>';
                                        // echo "$totExames , {$total_certao} <br>";
                                    } else {

                                        if ($i == 100) {
                                            $contador = $contador - $i;

                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>

              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>
        ";

                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                        }

                                        if ($contador < 100 && $contador == $i) {
                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>

              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>
        ";
                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array(); 
                                             }
                                            // echo 'aushd';
                                        }
                                    }
                                }
                            }
                        }
                        // die;
                    } else {
                        if (count($listarexame) > 0) {
                            $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
            <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
               <ans:cabecalho>
                  <ans:identificacaoTransacao>
                     <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
                     <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
                     <ans:dataRegistroTransacao>" . substr($listarexame[0]->data_autorizacao, 0, 10) . "</ans:dataRegistroTransacao>
                     <ans:horaRegistroTransacao>" . $hora . "</ans:horaRegistroTransacao>
                  </ans:identificacaoTransacao>
                  <ans:origem>
                    <ans:identificacaoPrestador>" .
                                    ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                                    "</ans:identificacaoPrestador>
                  </ans:origem>
                  <ans:destino>
                     <ans:registroANS>" . $registroans . "</ans:registroANS>
                  </ans:destino>
                  <ans:Padrao>" . $versao . "</ans:Padrao>
               </ans:cabecalho>
               <ans:prestadorParaOperadora>
                  <ans:loteGuias>
                     <ans:numeroLote>" . $b . "</ans:numeroLote>
                        <ans:guiasTISS>";
                            $contador = count($listarexame);

                            foreach ($listarexame as $value) {
                                $tabela = '22';
                                //                        $valorProcedimento = $value->valor;
                                //                        $valorMaterial = 0.00;
                                //                        $valorMedicamento = 0.00;

                                if ($value->grupo == "MATERIAL") { //caso seja material
                                    $tabela = '19';
                                    $codDespesa = '03';
                                    //                            $valorMaterial = $value->valor;
                                    //                            $valorProcedimento = 0.00;
                                } elseif ($value->grupo == "MEDICAMENTO") { //caso seja medicamento
                                    $tabela = '20';
                                    $codDespesa = '02';
                                    //                            $valorMedicamento = $value->valor;
                                    //                            $valorProcedimento = 0.00;
                                }
                                $i++;
                                $totExames++;
                                if ($value->convenionumero == '') {
                                    $numerodacarteira = '0000000';
                                } else {
                                    $numerodacarteira = $value->convenionumero;
                                }
                                if ($value->medico == '') {
                                    $medico = 'ADMINISTRADOR';
                                } else {
                                    $medico = $value->medico;
                                }
                                if ($value->conselho == '') {
                                    $conselho = '0000000';
                                } else {
                                    $conselho = $value->conselho;
                                }
                                if ($value->guiaconvenio == '') {
                                    $guianumero = '0000000';
                                } else {
                                    $guianumero = $value->guiaconvenio;
                                }
                                if ($value->cbo_ocupacao_id == '') {
                                    $cbo_medico = '999999';
                                } else {
                                    $cbo_medico = $value->cbo_ocupacao_id;
                                }
                                $corpo = $corpo . "
                        <ans:guiaConsulta>
                            <ans:cabecalhoConsulta>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                                <ans:numeroGuiaPrestador>" . (($value->guia_prestador_unico == 'f' ? $value->ambulatorio_guia_id : $value->agenda_exames_id)) . "</ans:numeroGuiaPrestador>
                            </ans:cabecalhoConsulta>
                            <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                            <ans:dadosBeneficiario>
                                <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                <ans:atendimentoRN>N</ans:atendimentoRN>
                                <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                            </ans:dadosBeneficiario>
                            <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                                <ans:CNES>" . $cnes . "</ans:CNES>
                            </ans:contratadoExecutante>
                            <ans:profissionalExecutante>
                                <ans:nomeProfissional>" . $medico . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>06</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional>" . $conselho . "</ans:numeroConselhoProfissional>
                                <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                            </ans:profissionalExecutante>
                            <ans:indicacaoAcidente>9</ans:indicacaoAcidente>
                            <ans:dadosAtendimento>
                                <ans:dataAtendimento>" . substr($value->data_autorizacao, 0, 10) . "</ans:dataAtendimento>
                                <ans:tipoConsulta>1</ans:tipoConsulta>
                                <ans:procedimento>
                                    <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $value->codigo . "</ans:codigoProcedimento>
                                    <ans:valorProcedimento>" . $value->valor . "</ans:valorProcedimento>
                                </ans:procedimento>
                            </ans:dadosAtendimento>
                        </ans:guiaConsulta>";
                                if (!$limite) {
                                    if ($totExames == count($listarexames)) {
                                        $contador = $contador - $i;
                                        $b++;
                                        $i = 0;
                                        $rodape = "</ans:guiasTISS>
                </ans:loteGuias>
            </ans:prestadorParaOperadora>
            <ans:epilogo>
            <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
            </ans:epilogo>
            </ans:mensagemTISS>";

                                        $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                        $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                        $xml = $cabecalho . $corpo . $rodape;
                                        $fp = fopen($nome, "w+");
                                        fwrite($fp, $xml . "\n");
                                        fclose($fp);
                                        //                                var_dump($xml);die;
                                        $corpo = "";
                                        $rodape = "";
                                        if ($_POST['espelho_conferencia'] == "SIM") {
                                            $this->gerarPDFXML($nomePDF, $arrayPDF);
                                            $arrayPDF = array();
                                         }
                                    }
                                } else {
                                    if ($i == 100) {
                                        $contador = $contador - $i;
                                        $i = 0;
                                        $rodape = "</ans:guiasTISS>
                </ans:loteGuias>
            </ans:prestadorParaOperadora>
            <ans:epilogo>
            <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
            </ans:epilogo>
            </ans:mensagemTISS>
            ";

                                        $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                        $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                        $xml = $cabecalho . $corpo . $rodape;
                                        $fp = fopen($nome, "w+");
                                        fwrite($fp, $xml . "\n");
                                        fclose($fp);
                                        $b++;
                                        $corpo = "";
                                        $rodape = "";
                                        if ($_POST['espelho_conferencia'] == "SIM") {
                                            $this->gerarPDFXML($nomePDF, $arrayPDF);
                                            $arrayPDF = array();
                                         }
                                    }
                                    if ($contador < 100 && $contador == $i) {
                                        $i = 0;
                                        $rodape = "   </ans:guiasTISS>


                </ans:loteGuias>
            </ans:prestadorParaOperadora>
            <ans:epilogo>
            <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
            </ans:epilogo>
            </ans:mensagemTISS>
            ";
                                        $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                        $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                        $xml = $cabecalho . $corpo . $rodape;
                                        $fp = fopen($nome, "w+");
                                        fwrite($fp, $xml . "\n");
                                        fclose($fp);
                                        $b++;
                                        $corpo = "";
                                        $rodape = "";
                                        if ($_POST['espelho_conferencia'] == "SIM") {
                                            $this->gerarPDFXML($nomePDF, $arrayPDF);
                                            $arrayPDF = array();
                                         }
                                    }
                                }
                            }
                        }
                    }
                }
            } else if ($versao == '3.04.00' || $versao == '3.04.01') {
                if ($modelo == 'cpf') {    
                    if ($_POST['layoutarq'] == 'sadt' && count($listarexame) > 0) {
                        $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
        <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
           <ans:cabecalho>
              <ans:identificacaoTransacao>
                 <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
                 <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
                 <ans:dataRegistroTransacao>" . $horario . "</ans:dataRegistroTransacao>
                 <ans:horaRegistroTransacao>" . $hora . "</ans:horaRegistroTransacao>
              </ans:identificacaoTransacao>
              <ans:origem>
              <ans:identificacaoPrestador>" .
                                ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                                "</ans:identificacaoPrestador>
              </ans:origem>
              <ans:destino>
                 <ans:registroANS>" . $registroans . "</ans:registroANS>
              </ans:destino>
              <ans:Padrao>" . $versao . "</ans:Padrao>
           </ans:cabecalho>

           <ans:prestadorParaOperadora>
              <ans:loteGuias>
                 <ans:numeroLote>" . $b . "</ans:numeroLote>
                    <ans:guiasTISS>";
                        $contador = 0;
                        foreach ($listarpacienete as $pac) {
                            $contador += $pac->contador;
                        }
                                    $valorMaterial = 0.00;
                                    $valorMedicamento = 0.00;
                                    $valorTaxa= 0.00;
                                    $totalgeral=0.00;
                        foreach ($listarpacienete as $value) {                            
                            $produtos_gastos = $this->exame->listaritensgastosprocedimento($value->exames_id); 
                            $totalgastos = 0;
                            $totalgastostaxa= 0;
                            $totalgastosmaterial = 0;
                            $totalgastosmedicamento= 0;
                            
                             if (count($produtos_gastos) > 0) {
                                foreach($produtos_gastos as $v){                                    
                                    $totalgastos += $v->valor;
                                    if ($v->tipo = "MATERIAL") {
                                        $totalgastosmaterial += $v->valor;
                                    } elseif ($v->tipo = "TAXA") {
                                        $totalgastostaxa += $v->valor;
                                    } elseif ($v->tipo = "MEDICAMENTO") {
                                        $totalgastosmedicamento += $v->valor;
                                    } else {
                                        
                                    }
                                }                                
                            }
                              
                            if ($value->convenionumero == '') {
                                $numerodacarteira = '0000000';
                            } else {
                                $numerodacarteira = $value->convenionumero;
                            }

                                   
                            foreach ($listarexames as $item) {
                    
                                if ($item->guiaconvenio == '') {
                                    $guianumero = '0000000';
                                } else {
                                    $guianumero = $item->guiaconvenio;
                                }
                    
                                if ($value->paciente_id == $item->paciente_id && $value->ambulatorio_guia_id == $item->ambulatorio_guia_id  && $item->exames_id == $value->exames_id) {
                                    $tabela = '22';
                                    //                                die('morreu');
                                    array_push($arrayPDF, $item);
                                    $valorProcedimento = $item->valor_total;
                                

                                    if ($item->grupo == "MATERIAL") { //caso seja material
                                        $tabela = '19';
                                        $codDespesa = '03';
                                        $valorMaterial = $item->valor_total + $totalgastosmaterial;
                                        $valorProcedimento = 0.00;
                                    } elseif ($item->grupo == "MEDICAMENTO") { //caso seja medicamento
                                        $tabela = '20';
                                        $codDespesa = '02';
                                        $valorMedicamento = $item->valor_total + $totalgastosmedicamento;
                                        $valorProcedimento = 0.00;
                                    }
                                    
                                     if ($item->grupo == "TAXA") {
                                        $tabela = '18';
                                        $valorTaxa = $item->valor_total + $totalgastostaxa;
                                        $valorProcedimento = 0.00;
                                    }
                                    
                                    $totalgeral = $item->valor_total + $totalgastos;

                                    $i++;
                                    $totExames++;
                                    $data_autorizacao = $this->exame->listarxmldataautorizacao($value->agenda_exames_id);
                                    $dataautorizacao = substr($data_autorizacao[0]->data_cadastro, 0, 10);
                                    $dataValidadeSenha = date('Y-m-d', strtotime("+30 days", strtotime($dataautorizacao)));
                                    $carater_xml = $item->carater_xml;
                                    if ($item->medico == '') {
                                        $medico = 'ADMINISTRADOR';
                                    } else {
                                        $medico = $item->medico;
                                    }
                                    if ($item->conselho == '') {
                                        $conselho = '0000000';
                                    } else {
                                        $conselho = $item->conselho;
                                    }
                                    if ($item->cbo_ocupacao_id == '') {
                                        $cbo_medico = '999999';
                                    } else {
                                        $cbo_medico = $item->cbo_ocupacao_id;
                                    }
                                    if ($item->medicosolicitante == '') {
                                        $medicosolicitante = $item->medico;
                                    } else {
                                        $medicosolicitante = $item->medicosolicitante;
                                    }
                                    if ($item->conselhosolicitante == '') {
                                        $conselhosolicitante = $item->conselho;
                                    } else {
                                        $conselhosolicitante = $item->conselhosolicitante;
                                    }

                                    if ($_POST['autorizacao'] == 'SIM') {
                                        $corpo = $corpo . "
                        <ans:guiaSP-SADT>
                          <ans:cabecalhoGuia>
                             <ans:registroANS>" . $registroans . "</ans:registroANS>
                             <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f') ? $value->ambulatorio_guia_id : $item->agenda_exames_id) . "</ans:numeroGuiaPrestador>
                             <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                          </ans:cabecalhoGuia>

                          <ans:dadosAutorizacao>
                            <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                            <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                            <ans:senha>" . $item->autorizacao . "</ans:senha>
                            <ans:dataValidadeSenha>" . $dataValidadeSenha . "</ans:dataValidadeSenha> 
                          </ans:dadosAutorizacao>

                          <ans:dadosBeneficiario>
                             <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                             <ans:atendimentoRN>S</ans:atendimentoRN>
                             <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                          </ans:dadosBeneficiario>

                          <ans:dadosSolicitante>
                            <ans:contratadoSolicitante>
                                <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                            </ans:contratadoSolicitante>

                            <ans:profissionalSolicitante>
                                <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>06</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                            </ans:profissionalSolicitante>

                          </ans:dadosSolicitante>

                          <ans:dadosSolicitacao>
                             <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                             <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                             <ans:indicacaoClinica>I</ans:indicacaoClinica>
                          </ans:dadosSolicitacao>

                          <ans:dadosExecutante>
        
                             <ans:contratadoExecutante>
                                 <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                 <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                                 <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoExecutante>
                             <ans:CNES>" . $cnes . "</ans:CNES>
                          </ans:dadosExecutante>

                          <ans:dadosAtendimento>
                            <ans:tipoAtendimento>04</ans:tipoAtendimento>
                            <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                            <ans:tipoConsulta>1</ans:tipoConsulta>

                          </ans:dadosAtendimento>";                                               
                              if($item->tipo != "MATERIAL" && $item->tipo != "MEDICAMENTO" && $item->tipo != "TAXA"){
                                     $corpo .="<ans:procedimentosExecutados>
                             <ans:procedimentoExecutado>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                    <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                    <ans:procedimento>
                                       <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                       <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                                       <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                                   </ans:procedimento>                        
                                   <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                                    <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                                    <ans:equipeSadt>
                                        <ans:grauPart>12</ans:grauPart>
                                        <ans:codProfissional>
                                            <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                        </ans:codProfissional>
                                        <ans:nomeProf>" . $medico . "</ans:nomeProf>
                                        <ans:conselho>06</ans:conselho>
                                        <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                                        <ans:UF>" . $codigoUF . "</ans:UF>
                                        <ans:CBOS>$cbo_medico</ans:CBOS>
                                    </ans:equipeSadt>
                          </ans:procedimentoExecutado>
                                    </ans:procedimentosExecutados>"; 
                             if (count($produtos_gastos) > 0) { 
                                 $corpo .= "<ans:outrasDespesas>"; 
                               foreach($produtos_gastos as $gastos){
                                if($gastos->tipo == "MATERIAL" ){
                                      $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>03</ans:codigoDespesa>
                                    <ans:servicosExecutados
                                    ><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>19</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "TAXA" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>07</ans:codigoDespesa>
                                    <ans:servicosExecutados><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>18</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "MEDICAMENTO"){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>02</ans:codigoDespesa>
                                    <ans:servicosExecutados>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>20</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>001</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }else{
                                      $corpo .="";
                                }
                               } 
                               $corpo .= "</ans:outrasDespesas>";
                              }
                           } 
                    
                                        $corpo .="
                          <ans:observacao>III</ans:observacao>
                             <ans:valorTotal >
                             <ans:valorProcedimentos >" . number_format($valorProcedimento, 2, '.', '') . "</ans:valorProcedimentos >
                             <ans:valorDiarias>0.00</ans:valorDiarias>
                             <ans:valorTaxasAlugueis>" . number_format($totalgastostaxa, 2, '.', '') . "</ans:valorTaxasAlugueis>
                             <ans:valorMateriais>" . number_format($totalgastosmaterial, 2, '.', '') . "</ans:valorMateriais>
                             <ans:valorMedicamentos>" . number_format($totalgastosmedicamento, 2, '.', '') . "</ans:valorMedicamentos>
                             <ans:valorOPME>0.00</ans:valorOPME>
                             <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                             <ans:valorTotalGeral>" . $totalgeral . "</ans:valorTotalGeral>
                          </ans:valorTotal>
                          </ans:guiaSP-SADT>";
                                    } else {

                                        //                                    die('morreu02');
                                        $corpo = $corpo . "
                    <ans:guiaSP-SADT>
                              <ans:cabecalhoGuia>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                             <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f') ? $value->ambulatorio_guia_id : $item->agenda_exames_id) . "</ans:numeroGuiaPrestador>
                             <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                          </ans:cabecalhoGuia>
                          <ans:dadosAutorizacao>
                          <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                          <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                          </ans:dadosAutorizacao>
                          <ans:dadosBeneficiario>
                             <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                 <ans:atendimentoRN>S</ans:atendimentoRN>
                             <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                          </ans:dadosBeneficiario>

                          <ans:dadosSolicitante>
                             <ans:contratadoSolicitante>
                                   <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                                   <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoSolicitante>
                             <ans:profissionalSolicitante>
                                <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>06</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                             </ans:profissionalSolicitante>
                          </ans:dadosSolicitante>

                          <ans:dadosSolicitacao>
                             <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                             <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                             <ans:indicacaoClinica>I</ans:indicacaoClinica>
                          </ans:dadosSolicitacao>

                          <ans:dadosExecutante>
                            <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoExecutante>
                             <ans:CNES>" . $cnes . "</ans:CNES>
                          </ans:dadosExecutante>

                          <ans:dadosAtendimento>
                            <ans:tipoAtendimento>04</ans:tipoAtendimento>
                            <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                            <ans:tipoConsulta>1</ans:tipoConsulta>

                          </ans:dadosAtendimento>";
                                    if($item->tipo != "MATERIAL" && $item->tipo != "MEDICAMENTO"  && $item->tipo != "TAXA") {
                                        $sequencialItem++;
                                       $corpo .="<ans:procedimentosExecutados>
                             <ans:procedimentoExecutado>
                                    <ans:sequencialItem>".$sequencialItem."</ans:sequencialItem>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                    <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                    <ans:procedimento>
                                        <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                        <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                                        <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                                    </ans:procedimento>                        
                                    <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                                    <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                                    <ans:equipeSadt>
                                        <ans:grauPart>12</ans:grauPart>
                                        <ans:codProfissional>
                                            <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                        </ans:codProfissional>
                                        <ans:nomeProf>" . $medico . "</ans:nomeProf>
                                        <ans:conselho>06</ans:conselho>
                                        <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                                        <ans:UF>" . $codigoUF . "</ans:UF>
                                        <ans:CBOS>$cbo_medico</ans:CBOS>
                                    </ans:equipeSadt>
                          </ans:procedimentoExecutado>
                        </ans:procedimentosExecutados>" ;
                    
                              if (count($produtos_gastos) > 0) {  
                                  $corpo .= "<ans:outrasDespesas>"; 
                            foreach($produtos_gastos as $gastos){
                                if($gastos->tipo == "MATERIAL"){
                                      $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>03</ans:codigoDespesa>
                                    <ans:servicosExecutados
                                    ><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>19</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "TAXA" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>07</ans:codigoDespesa>
                                    <ans:servicosExecutados><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>18</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "MEDICAMENTO" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>02</ans:codigoDespesa>
                                    <ans:servicosExecutados>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>20</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>001</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }else{
                                      $corpo .="";
                                   }
                                 }
                               $corpo .= "</ans:outrasDespesas>";
                              }
                           }
                    
                                        $corpo .="
                          <ans:observacao>III</ans:observacao>
                             <ans:valorTotal >
                             <ans:valorProcedimentos >" . number_format($valorProcedimento, 2, '.', '') . "</ans:valorProcedimentos >
                             <ans:valorDiarias>0.00</ans:valorDiarias>
                             <ans:valorTaxasAlugueis>" . number_format($totalgastostaxa, 2, '.', '') . "</ans:valorTaxasAlugueis>
                             <ans:valorMateriais>" . number_format($totalgastosmaterial, 2, '.', '') . "</ans:valorMateriais>
                             <ans:valorMedicamentos>" . number_format($totalgastosmedicamento, 2, '.', '') . "</ans:valorMedicamentos>
                             <ans:valorOPME>0.00</ans:valorOPME>
                             <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                             <ans:valorTotalGeral>" . $totalgeral . "</ans:valorTotalGeral>
                          </ans:valorTotal>
                          </ans:guiaSP-SADT>";
                                        
                                    }

                                    if (!$limite) {
                                        if ($totExames == count($listarexames)) {
                                            $contador = $contador - $i;

                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>
              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>";

                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                            }
                                            
                                        }
                                    } else {
                                        if ($i == 100) {
                                            $contador = $contador - $i;

                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>
              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>";

                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                            
                                        }
                                        if ($contador < 100 && $contador == $i) {
                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>
              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>";
                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                            
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if (count($listarexame) > 0) {
                            $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
            <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
               <ans:cabecalho>
                  <ans:identificacaoTransacao>
                     <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
                     <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
                     <ans:dataRegistroTransacao>" . substr($listarexame[0]->data_autorizacao, 0, 10) . "</ans:dataRegistroTransacao>
                     <ans:horaRegistroTransacao>" . $hora . "</ans:horaRegistroTransacao>
                  </ans:identificacaoTransacao>
                  <ans:origem>
                    <ans:identificacaoPrestador>" .
                                    ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                                    "</ans:identificacaoPrestador>
                  </ans:origem>
                  <ans:destino>
                     <ans:registroANS>" . $registroans . "</ans:registroANS>
                  </ans:destino>
                  <ans:Padrao>" . $versao . "</ans:Padrao>
               </ans:cabecalho>
               <ans:prestadorParaOperadora>
                  <ans:loteGuias>
                     <ans:numeroLote>" . $b . "</ans:numeroLote>
                        <ans:guiasTISS>";
                            $contador = count($listarexame);
                            foreach ($listarexame as $value) {

                                $tabela = '22';
                                //                        $valorProcedimento = $value->valor;
                                //                        $valorMaterial = 0.00;
                                //                        $valorMedicamento = 0.00;

                                if ($value->grupo == "MATERIAL") { //caso seja material
                                    $tabela = '19';
                                    $codDespesa = '03';
                                    //                            $valorMaterial = $value->valor;
                                    //                            $valorProcedimento = 0.00;
                                } elseif ($value->grupo == "MEDICAMENTO") { //caso seja medicamento
                                    $tabela = '20';
                                    $codDespesa = '02';
                                    //                            $valorMedicamento = $value->valor;
                                    //                            $valorProcedimento = 0.00;
                                }

                                if ($value->convenionumero == '') {
                                    $numerodacarteira = '0000000';
                                } else {
                                    $numerodacarteira = $value->convenionumero;
                                }
                                if ($value->medico == '') {
                                    $medico = 'ADMINISTRADOR';
                                } else {
                                    $medico = $value->medico;
                                }
                                if ($value->cbo_ocupacao_id == '') {
                                    $cbo_medico = '999999';
                                } else {
                                    $cbo_medico = $value->cbo_ocupacao_id;
                                }
                                if ($value->conselho == '') {
                                    $conselho = '0000000';
                                } else {
                                    $conselho = $value->conselho;
                                }
                                if ($value->guiaconvenio == '') {
                                    $guianumero = '0000000';
                                } else {
                                    $guianumero = $value->guiaconvenio;
                                }
                                $corpo = $corpo . "
                        <ans:guiaConsulta>
                            <ans:cabecalhoConsulta>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                                <ans:numeroGuiaPrestador>" . (($value->guia_prestador_unico == 'f' ? $value->ambulatorio_guia_id : $value->agenda_exames_id)) . "</ans:numeroGuiaPrestador>
                            </ans:cabecalhoConsulta>
                            <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                            <ans:dadosBeneficiario>
                                <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                <ans:atendimentoRN>N</ans:atendimentoRN>
                                <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                            </ans:dadosBeneficiario>
                            <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cpfxml . "</ans:codigoPrestadorNaOperadora>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                                <ans:CNES>" . $cnes . "</ans:CNES>
                            </ans:contratadoExecutante>
                            <ans:profissionalExecutante>
                                <ans:nomeProfissional>" . $medico . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>06</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional>" . $conselho . "</ans:numeroConselhoProfissional>
                                <ans:UF>15</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                            </ans:profissionalExecutante>
                            <ans:indicacaoAcidente>9</ans:indicacaoAcidente>
                            <ans:dadosAtendimento>
                                <ans:dataAtendimento>" . substr($value->data_autorizacao, 0, 10) . "</ans:dataAtendimento>
                                <ans:tipoConsulta>1</ans:tipoConsulta>
                                <ans:procedimento>
                                    <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $value->codigo . "</ans:codigoProcedimento>
                                    <ans:valorProcedimento>" . $value->valor . "</ans:valorProcedimento>
                                </ans:procedimento>
                            </ans:dadosAtendimento>
                        </ans:guiaConsulta>";
                                if (!$limite) {
                                    if ($totExames == count($listarexames)) {
                                        $contador = $contador - $i;
                                        $b++;
                                        $i = 0;
                                        $rodape = "</ans:guiasTISS>
                </ans:loteGuias>
            </ans:prestadorParaOperadora>
            <ans:epilogo>
            <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
            </ans:epilogo>
            </ans:mensagemTISS>";

                                        $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                        $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                        $xml = $cabecalho . $corpo . $rodape;
                                        $fp = fopen($nome, "w+");
                                        fwrite($fp, $xml . "\n");
                                        fclose($fp);
                                        $corpo = "";
                                        $rodape = "";
                                        if ($_POST['espelho_conferencia'] == "SIM") {
                                            $this->gerarPDFXML($nomePDF, $arrayPDF);
                                            $arrayPDF = array();
                                         }
                                    }
                                } else {
                                    if ($i == 100) {
                                        $contador = $contador - $i;
                                        $b++;
                                        $i = 0;
                                        $rodape = "</ans:guiasTISS>
                </ans:loteGuias>
            </ans:prestadorParaOperadora>
            <ans:epilogo>
            <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
            </ans:epilogo>
            </ans:mensagemTISS>";

                                        $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                        $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                        $xml = $cabecalho . $corpo . $rodape;
                                        $fp = fopen($nome, "w+");
                                        fwrite($fp, $xml . "\n");
                                        fclose($fp);
                                        $corpo = "";
                                        $rodape = "";
                                        if ($_POST['espelho_conferencia'] == "SIM") {
                                            $this->gerarPDFXML($nomePDF, $arrayPDF);
                                            $arrayPDF = array();
                                         }
                                    }
                                    if ($contador < 100 && $contador == $i) {

                                        $i = 0;
                                        $rodape = "   </ans:guiasTISS>


                </ans:loteGuias>
            </ans:prestadorParaOperadora>
            <ans:epilogo>
            <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
            </ans:epilogo>
            </ans:mensagemTISS>";
                                        $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                        $xml = $cabecalho . $corpo . $rodape;
                                        $fp = fopen($nome, "w+");
                                        fwrite($fp, $xml . "\n");
                                        fclose($fp);
                                        $b++;
                                        $corpo = "";
                                        $rodape = "";
                                        if ($_POST['espelho_conferencia'] == "SIM") {
                                            $this->gerarPDFXML($nomePDF, $arrayPDF);
                                            $arrayPDF = array();
                                         }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if ($_POST['layoutarq'] == 'sadt' && count($listarexame) > 0) {
                        // echo 'its here'; die;
                        $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
        <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
           <ans:cabecalho>
              <ans:identificacaoTransacao>
                 <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
                 <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
                 <ans:dataRegistroTransacao>" . substr($listarexame[0]->data_autorizacao, 0, 10) . "</ans:dataRegistroTransacao>
                 <ans:horaRegistroTransacao>" . $hora . "</ans:horaRegistroTransacao>
              </ans:identificacaoTransacao>
              <ans:origem>
                <ans:identificacaoPrestador>" .
                                ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                                "</ans:identificacaoPrestador>
              </ans:origem>
              <ans:destino>
                 <ans:registroANS>" . $registroans . "</ans:registroANS>
              </ans:destino>
              <ans:Padrao>" . $versao . "</ans:Padrao>
           </ans:cabecalho>
           <ans:prestadorParaOperadora>
              <ans:loteGuias>
                 <ans:numeroLote>" . $b . "</ans:numeroLote>
                    <ans:guiasTISS>";

                        $contador = 0;
                        foreach ($listarpacienete as $pac) {
                            $contador += $pac->contador;
                        }
                        foreach ($listarpacienete as $value) {
                            $totalgastos = 0;
                            $totalgastostaxa= 0;
                            $totalgastosmaterial = 0;
                            $totalgastosmedicamento= 0;                            
                            $produtos_gastos = $this->exame->listaritensgastosprocedimento($value->exames_id);
                            if (count($produtos_gastos) > 0) {
                                foreach($produtos_gastos as $v){                                    
                                    $totalgastos += $v->valor;
                                    if ($v->tipo = "MATERIAL") {
                                        $totalgastosmaterial += $v->valor;
                                    } elseif ($v->tipo = "TAXA") {
                                        $totalgastostaxa += $v->valor;
                                    } elseif ($v->tipo = "MEDICAMENTO") {
                                        $totalgastosmedicamento += $v->valor;
                                    } else {
                                        
                                    }
                                }  
                              
                            }

                            if ($value->convenionumero == '') {
                                $numerodacarteira = '0000000';
                            } else {
                                $numerodacarteira = $value->convenionumero;
                            }
                                    $valorMaterial = 0.00;
                                    $valorMedicamento = 0.00;
                                    $valorTaxa = 0.00;
                                    
                            foreach ($listarexames as $item) {
                                if ($item->guiaconvenio == '') {
                                    $guianumero = '0000000';
                                } else {
                                    $guianumero = $item->guiaconvenio;
                                }
                                

                                if ($item->cbo_ocupacao_id == '') {
                                    $cbo_medico = '999999';
                                } else {
                                    $cbo_medico = $item->cbo_ocupacao_id;
                                }

                                if ($value->paciente_id == $item->paciente_id && $value->ambulatorio_guia_id == $item->ambulatorio_guia_id  && $item->exames_id == $value->exames_id) {
                                    $tabela = '22';
                                    array_push($arrayPDF, $item);
                                    $valorProcedimento = $item->valor_total;
                                  

                                    if ($item->grupo == "MATERIAL") { //caso seja material
                                        $tabela = '19';
                                        $codDespesa = '03';                                        
                                        $valorMaterial = $item->valor_total  + $totalgastosmaterial;
                                        $valorProcedimento = 0.00;
                                    } elseif ($item->grupo == "MEDICAMENTO") { //caso seja medicamento
                                        $tabela = '20';
                                        $codDespesa = '02';
                                        $valorMedicamento = $item->valor_total + $totalgastosmedicamento;
                                        $valorProcedimento = 0.00;
                                    }
                                    if ($item->grupo == "TAXA") {
                                        $valorTaxa = $item->valor_total + $totalgastostaxa;
                                        $valorProcedimento = 0.00;
                                    }
                                    
                                    $totalgeral = $item->valor_total + $totalgastos;
                                    
                                    $i++;

                                    $totExames++;
                                    // echo "<span style='color:red'>$totExames</span>";
                                    $data_autorizacao = $this->exame->listarxmldataautorizacao($value->agenda_exames_id);
                                    $dataautorizacao = substr($data_autorizacao[0]->data_cadastro, 0, 10);
                                    $dataValidadeSenha = date('Y-m-d', strtotime("+30 days", strtotime($dataautorizacao)));
                                    $carater_xml = $item->carater_xml;
                                    if ($item->medico == '') {
                                        $medico = 'ADMINISTRADOR';
                                    } else {
                                        $medico = $item->medico;
                                    }
                                    if ($item->conselho == '') {
                                        $conselho = '0000000';
                                    } else {
                                        $conselho = $item->conselho;
                                    }
                                    if ($item->medicosolicitante == '') {
                                        $medicosolicitante = $item->medico;
                                    } else {
                                        $medicosolicitante = $item->medicosolicitante;
                                    }
                                    if ($item->conselhosolicitante == '') {
                                        $conselhosolicitante = $item->conselho;
                                    } else {
                                        $conselhosolicitante = $item->conselhosolicitante;
                                    }

                                    if ($_POST['autorizacao'] == 'SIM') {
                                        $corpo = $corpo . "
                                                              <ans:guiaSP-SADT>
                              <ans:cabecalhoGuia>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                             <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f' ? $value->ambulatorio_guia_id : $item->agenda_exames_id)) . "</ans:numeroGuiaPrestador>
                             <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                          </ans:cabecalhoGuia>
                          <ans:dadosAutorizacao>
                          <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                          <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                          <ans:senha>" . $item->autorizacao . "</ans:senha>
                          <ans:dataValidadeSenha>" . $dataValidadeSenha . "</ans:dataValidadeSenha> 
                          </ans:dadosAutorizacao>
                          <ans:dadosBeneficiario>
                             <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                 <ans:atendimentoRN>S</ans:atendimentoRN>
                             <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                          </ans:dadosBeneficiario>
                                                          <ans:dadosSolicitante>
                             <ans:contratadoSolicitante>
                                   <ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoSolicitante>
                             <ans:profissionalSolicitante>
                                <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>06</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                    <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                             </ans:profissionalSolicitante>
                          </ans:dadosSolicitante>
                          <ans:dadosSolicitacao>
                             <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                             <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                             <ans:indicacaoClinica>I</ans:indicacaoClinica>
                          </ans:dadosSolicitacao>
                          <ans:dadosExecutante>
                                <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>                               
                             <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoExecutante>
                             <ans:CNES>" . $cnes . "</ans:CNES>
                          </ans:dadosExecutante>
                          <ans:dadosAtendimento>
                          <ans:tipoAtendimento>04</ans:tipoAtendimento>
                          <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                          <ans:tipoConsulta>1</ans:tipoConsulta>

                          </ans:dadosAtendimento>
                          ";
                            if($item->tipo != "MATERIAL" && $item->tipo != "MEDICAMENTO"  && $item->tipo != "TAXA") {
                                        $sequencialItem++;
                                        $corpo .= "<ans:procedimentosExecutados>
                                 <ans:procedimentoExecutado>
                                        <ans:sequencialItem>".$sequencialItem."</ans:sequencialItem>
                                        <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                        <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                        <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                        <ans:procedimento>
                                        <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                       <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                                       <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                                       </ans:procedimento>                        
                                <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                                    <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                                    <ans:equipeSadt>
                                        <ans:grauPart>12</ans:grauPart>
                                        <ans:codProfissional>
                                        <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                        </ans:codProfissional>
                                        <ans:nomeProf>" . $medico . "</ans:nomeProf>
                                        <ans:conselho>06</ans:conselho>
                                        <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                                        <ans:UF>" . $codigoUF . "</ans:UF>
                                        <ans:CBOS>$cbo_medico</ans:CBOS>
                                    </ans:equipeSadt>
                              </ans:procedimentoExecutado>
                                        </ans:procedimentosExecutados>";
                        if (count($produtos_gastos) > 0) {
                             $corpo .= "<ans:outrasDespesas>";
                               foreach($produtos_gastos as $gastos){
                                if($gastos->tipo == "MATERIAL" ){
                                      $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>03</ans:codigoDespesa>
                                    <ans:servicosExecutados
                                    ><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>19</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "TAXA" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>07</ans:codigoDespesa>
                                    <ans:servicosExecutados><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>18</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "MEDICAMENTO"){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>02</ans:codigoDespesa>
                                    <ans:servicosExecutados>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>20</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>001</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }else{
                                      $corpo .="";
                                }
                               } 
                                $corpo .= "</ans:outrasDespesas>";
                              }
                         
                          }      
                    
                              $corpo .="<ans:observacao>III</ans:observacao>
                             <ans:valorTotal >
                             <ans:valorProcedimentos >" . number_format($valorProcedimento, 2, '.', '') . "</ans:valorProcedimentos >
                             <ans:valorDiarias>0.00</ans:valorDiarias>
                             <ans:valorTaxasAlugueis>" . number_format($totalgastostaxa, 2, '.', '') . "</ans:valorTaxasAlugueis>
                             <ans:valorMateriais>" . number_format($totalgastosmaterial, 2, '.', '') . "</ans:valorMateriais>
                             <ans:valorMedicamentos>" . number_format($totalgastosmedicamento, 2, '.', '') . "</ans:valorMedicamentos>
                             <ans:valorOPME>0.00</ans:valorOPME>
                             <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                             <ans:valorTotalGeral>" . $totalgeral . "</ans:valorTotalGeral>
                          </ans:valorTotal>
                          </ans:guiaSP-SADT>";
                                    } else {
                                        $corpo = $corpo . "
                                                              <ans:guiaSP-SADT>
                              <ans:cabecalhoGuia>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                             <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f' ? $value->ambulatorio_guia_id : $item->agenda_exames_id)) . "</ans:numeroGuiaPrestador>
                             <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                          </ans:cabecalhoGuia>
                          <ans:dadosAutorizacao>
                          <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                          <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                          </ans:dadosAutorizacao>
                          <ans:dadosBeneficiario>
                             <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                 <ans:atendimentoRN>S</ans:atendimentoRN>
                             <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                          </ans:dadosBeneficiario>
                                                          <ans:dadosSolicitante>
                             <ans:contratadoSolicitante>
                                   <ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoSolicitante>
                             <ans:profissionalSolicitante>
                                <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>06</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                    <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                             </ans:profissionalSolicitante>
                          </ans:dadosSolicitante>
                          <ans:dadosSolicitacao>
                             <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                             <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                             <ans:indicacaoClinica>I</ans:indicacaoClinica>
                          </ans:dadosSolicitacao>
                          <ans:dadosExecutante>                                
                                <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>                             
                             <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoExecutante>
                             <ans:CNES>" . $cnes . "</ans:CNES>
                          </ans:dadosExecutante>
                          <ans:dadosAtendimento>
                          <ans:tipoAtendimento>04</ans:tipoAtendimento>
                          <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                          <ans:tipoConsulta>1</ans:tipoConsulta>

                          </ans:dadosAtendimento>";
                                if($item->tipo != "MATERIAL" && $item->tipo != "MEDICAMENTO"  && $item->tipo != "TAXA"){  
                                    $sequencialItem++;
                                    $corpo .= "<ans:procedimentosExecutados>
                                 <ans:procedimentoExecutado>
                                        <ans:sequencialItem>".$sequencialItem."</ans:sequencialItem>
                                        <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                        <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                        <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                        <ans:procedimento>
                                        <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                       <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                                       <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                                       </ans:procedimento>                        
                                <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                                    <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                                    <ans:equipeSadt>
                                        <ans:grauPart>12</ans:grauPart>
                                        <ans:codProfissional>
                                        <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                        </ans:codProfissional>
                                        <ans:nomeProf>" . $medico . "</ans:nomeProf>
                                        <ans:conselho>06</ans:conselho>
                                        <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                                        <ans:UF>" . $codigoUF . "</ans:UF>
                                        <ans:CBOS>$cbo_medico</ans:CBOS>
                                    </ans:equipeSadt>
                              </ans:procedimentoExecutado>
                                </ans:procedimentosExecutados>";  
                             if (count($produtos_gastos) > 0) {
                                 $corpo .= "<ans:outrasDespesas>";
                               foreach($produtos_gastos as $gastos){
                                if($gastos->tipo == "MATERIAL" ){
                                      $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>03</ans:codigoDespesa>
                                    <ans:servicosExecutados
                                    ><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>19</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "TAXA" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>07</ans:codigoDespesa>
                                    <ans:servicosExecutados><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>18</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "MEDICAMENTO" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>02</ans:codigoDespesa>
                                    <ans:servicosExecutados>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>20</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>001</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }else{
                                      $corpo .="";
                                  }
                                } 
                                 $corpo .= "</ans:outrasDespesas>"; 
                              }
                           } 
                    
                                        $corpo .="
                          <ans:observacao>III</ans:observacao>
                             <ans:valorTotal >
                             <ans:valorProcedimentos >" . number_format($valorProcedimento, 2, '.', '') . "</ans:valorProcedimentos >
                             <ans:valorDiarias>0.00</ans:valorDiarias>
                             <ans:valorTaxasAlugueis>" . number_format($totalgastostaxa, 2, '.', '') . "</ans:valorTaxasAlugueis>
                             <ans:valorMateriais>" . number_format($totalgastosmaterial, 2, '.', '') . "</ans:valorMateriais>
                             <ans:valorMedicamentos>" . number_format($totalgastosmedicamento, 2, '.', '') . "</ans:valorMedicamentos>
                             <ans:valorOPME>0.00</ans:valorOPME>
                             <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                             <ans:valorTotalGeral>" . $totalgeral . "</ans:valorTotalGeral>
                          </ans:valorTotal>
                          </ans:guiaSP-SADT>";
                                    }

                                    if (!$limite) {

                                        if ($totExames == count($listarexames)) {
                                            $contador = $contador - $i;
                                            $b++;
                                            $i = 0;
                                            $rodape = "</ans:guiasTISS>
            </ans:loteGuias>
        </ans:prestadorParaOperadora>
        <ans:epilogo>
        <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
        </ans:epilogo>
        </ans:mensagemTISS>";

                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";

                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");

                                            $html = "<table border=2><tr><td>teste</td></tr></table>";


                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
//                                            salvapdf($html, $nomepdf, "", "");


                                            fclose($fp);
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                            //    echo 'gravou o xml';
                                        }
                                        $total_certao = count($listarexames);
                                        // echo '<pre>';
                                        // var_dump($item); 
                                        // echo '<br> <hr>';
                                        // echo "$totExames , {$total_certao} <br>";
                                    } else {

                                        if ($i == 100) {
                                            $contador = $contador - $i;

                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>

              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>
        ";

                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                        }

                                        if ($contador < 100 && $contador == $i) {
                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>

              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>
        ";
                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array(); 
                                             }
                                            // echo 'aushd';
                                        }
                                    }
                                }
                            }
                        }
                        // die;
                    } else {
                        if (count($listarexame) > 0) {
                            $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
            <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
               <ans:cabecalho>
                  <ans:identificacaoTransacao>
                     <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
                     <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
                     <ans:dataRegistroTransacao>" . substr($listarexame[0]->data_autorizacao, 0, 10) . "</ans:dataRegistroTransacao>
                     <ans:horaRegistroTransacao>" . $hora . "</ans:horaRegistroTransacao>
                  </ans:identificacaoTransacao>
                  <ans:origem>
                    <ans:identificacaoPrestador>" .
                                    ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                                    "</ans:identificacaoPrestador>
                  </ans:origem>
                  <ans:destino>
                     <ans:registroANS>" . $registroans . "</ans:registroANS>
                  </ans:destino>
                  <ans:Padrao>" . $versao . "</ans:Padrao>
               </ans:cabecalho>
               <ans:prestadorParaOperadora>
                  <ans:loteGuias>
                     <ans:numeroLote>" . $b . "</ans:numeroLote>
                        <ans:guiasTISS>";
                            $contador = count($listarexame);

                            foreach ($listarexame as $value) {
                                $tabela = '22';
                                //                        $valorProcedimento = $value->valor;
                                //                        $valorMaterial = 0.00;
                                //                        $valorMedicamento = 0.00;

                                if ($value->grupo == "MATERIAL") { //caso seja material
                                    $tabela = '19';
                                    $codDespesa = '03';
                                    //                            $valorMaterial = $value->valor;
                                    //                            $valorProcedimento = 0.00;
                                } elseif ($value->grupo == "MEDICAMENTO") { //caso seja medicamento
                                    $tabela = '20';
                                    $codDespesa = '02';
                                    //                            $valorMedicamento = $value->valor;
                                    //                            $valorProcedimento = 0.00;
                                }
                                $i++;
                                $totExames++;
                                if ($value->convenionumero == '') {
                                    $numerodacarteira = '0000000';
                                } else {
                                    $numerodacarteira = $value->convenionumero;
                                }
                                if ($value->medico == '') {
                                    $medico = 'ADMINISTRADOR';
                                } else {
                                    $medico = $value->medico;
                                }
                                if ($value->conselho == '') {
                                    $conselho = '0000000';
                                } else {
                                    $conselho = $value->conselho;
                                }
                                if ($value->guiaconvenio == '') {
                                    $guianumero = '0000000';
                                } else {
                                    $guianumero = $value->guiaconvenio;
                                }
                                if ($value->cbo_ocupacao_id == '') {
                                    $cbo_medico = '999999';
                                } else {
                                    $cbo_medico = $value->cbo_ocupacao_id;
                                }
                                $corpo = $corpo . "
                        <ans:guiaConsulta>
                            <ans:cabecalhoConsulta>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                                <ans:numeroGuiaPrestador>" . (($value->guia_prestador_unico == 'f' ? $value->ambulatorio_guia_id : $value->agenda_exames_id)) . "</ans:numeroGuiaPrestador>
                            </ans:cabecalhoConsulta>
                            <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                            <ans:dadosBeneficiario>
                                <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                <ans:atendimentoRN>N</ans:atendimentoRN>
                                <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                            </ans:dadosBeneficiario>
                            <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                                <ans:CNES>" . $cnes . "</ans:CNES>
                            </ans:contratadoExecutante>
                            <ans:profissionalExecutante>
                                <ans:nomeProfissional>" . $medico . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>06</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional>" . $conselho . "</ans:numeroConselhoProfissional>
                                <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                            </ans:profissionalExecutante>
                            <ans:indicacaoAcidente>9</ans:indicacaoAcidente>
                            <ans:dadosAtendimento>
                                <ans:dataAtendimento>" . substr($value->data_autorizacao, 0, 10) . "</ans:dataAtendimento>
                                <ans:tipoConsulta>1</ans:tipoConsulta>
                                <ans:procedimento>
                                    <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $value->codigo . "</ans:codigoProcedimento>
                                    <ans:valorProcedimento>" . $value->valor . "</ans:valorProcedimento>
                                </ans:procedimento>
                            </ans:dadosAtendimento>
                        </ans:guiaConsulta>";
                                if (!$limite) {
                                    if ($totExames == count($listarexames)) {
                                        $contador = $contador - $i;
                                        $b++;
                                        $i = 0;
                                        $rodape = "</ans:guiasTISS>
                </ans:loteGuias>
            </ans:prestadorParaOperadora>
            <ans:epilogo>
            <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
            </ans:epilogo>
            </ans:mensagemTISS>";

                                        $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                        $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                        $xml = $cabecalho . $corpo . $rodape;
                                        $fp = fopen($nome, "w+");
                                        fwrite($fp, $xml . "\n");
                                        fclose($fp);
                                        //                                var_dump($xml);die;
                                        $corpo = "";
                                        $rodape = "";
                                        if ($_POST['espelho_conferencia'] == "SIM") {
                                            $this->gerarPDFXML($nomePDF, $arrayPDF);
                                            $arrayPDF = array();
                                         }
                                    }
                                } else {
                                    if ($i == 100) {
                                        $contador = $contador - $i;
                                        $i = 0;
                                        $rodape = "</ans:guiasTISS>
                </ans:loteGuias>
            </ans:prestadorParaOperadora>
            <ans:epilogo>
            <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
            </ans:epilogo>
            </ans:mensagemTISS>
            ";

                                        $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                        $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                        $xml = $cabecalho . $corpo . $rodape;
                                        $fp = fopen($nome, "w+");
                                        fwrite($fp, $xml . "\n");
                                        fclose($fp);
                                        $b++;
                                        $corpo = "";
                                        $rodape = "";
                                        if ($_POST['espelho_conferencia'] == "SIM") {
                                            $this->gerarPDFXML($nomePDF, $arrayPDF);
                                            $arrayPDF = array();
                                         }
                                    }
                                    if ($contador < 100 && $contador == $i) {
                                        $i = 0;
                                        $rodape = "   </ans:guiasTISS>


                </ans:loteGuias>
            </ans:prestadorParaOperadora>
            <ans:epilogo>
            <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
            </ans:epilogo>
            </ans:mensagemTISS>
            ";
                                        $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                        $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                        $xml = $cabecalho . $corpo . $rodape;
                                        $fp = fopen($nome, "w+");
                                        fwrite($fp, $xml . "\n");
                                        fclose($fp);
                                        $b++;
                                        $corpo = "";
                                        $rodape = "";
                                        if ($_POST['espelho_conferencia'] == "SIM") {
                                            $this->gerarPDFXML($nomePDF, $arrayPDF);
                                            $arrayPDF = array();
                                         }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            //VERSÃO ANTIGA DO XML
            else {
                if ($modelo == 'cpf') {




                    if ($_POST['layoutarq'] == 'sadt' && count($listarexame) > 0) {
                        $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
        <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
           <ans:cabecalho>
              <ans:identificacaoTransacao>
                 <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
                 <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
                 <ans:dataRegistroTransacao>" . $horario . "</ans:dataRegistroTransacao>
                 <ans:horaRegistroTransacao>" . $hora . "</ans:horaRegistroTransacao>
              </ans:identificacaoTransacao>
              <ans:origem>
              <ans:identificacaoPrestador>" .
                                ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                                "</ans:identificacaoPrestador>
              </ans:origem>
              <ans:destino>
                 <ans:registroANS>" . $registroans . "</ans:registroANS>
              </ans:destino>
              <ans:versaoPadrao>" . $versao . "</ans:versaoPadrao>
           </ans:cabecalho>

           <ans:prestadorParaOperadora>
              <ans:loteGuias>
                 <ans:numeroLote>" . $b . "</ans:numeroLote>
                    <ans:guiasTISS>";
                        $contador = 0;
                        foreach ($listarpacienete as $pac) {
                            $contador += $pac->contador;
                        }

                        foreach ($listarpacienete as $value) {                            
                            $produtos_gastos = $this->exame->listaritensgastosprocedimento($value->exames_id);
                            $totalgastos = 0;
                            $totalgastostaxa= 0;
                            $totalgastosmaterial = 0;
                            $totalgastosmedicamento= 0;
                            if (count($produtos_gastos) > 0) {
                                foreach($produtos_gastos as $v){                                    
                                    $totalgastos += $v->valor;
                                    if ($v->tipo = "MATERIAL") {
                                        $totalgastosmaterial += $v->valor;
                                    } elseif ($v->tipo = "TAXA") {
                                        $totalgastostaxa += $v->valor;
                                    } elseif ($v->tipo = "MEDICAMENTO") {
                                        $totalgastosmedicamento += $v->valor;
                                    } else {
                                        
                                    }
                                }  
                              
                            }
                            
                            if ($value->convenionumero == '') {
                                $numerodacarteira = '0000000';
                            } else {
                                $numerodacarteira = $value->convenionumero;
                            }

                            foreach ($listarexames as $item) {

                                if ($item->guiaconvenio == '') {
                                    $guianumero = '0000000';
                                } else {
                                    $guianumero = $item->guiaconvenio;
                                }
                                

                                if ($value->paciente_id == $item->paciente_id && $value->ambulatorio_guia_id == $item->ambulatorio_guia_id  && $item->exames_id == $value->exames_id) {
                                    $tabela = '22';
                                    //                                die('morreu');
                                    $valorProcedimento = $item->valor_total;
                                    $valorMaterial = 0.00;
                                    $valorMedicamento = 0.00;
                                    $valorTaxa= 0.00;
                                    array_push($arrayPDF, $item);
                                     if ($item->grupo == "MATERIAL") { //caso seja material
                                        $tabela = '19';
                                        $codDespesa = '03';                                        
                                        $valorMaterial = $item->valor_total  + $totalgastosmaterial;
                                        $valorProcedimento = 0.00;
                                    } elseif ($item->grupo == "MEDICAMENTO") { //caso seja medicamento
                                        $tabela = '20';
                                        $codDespesa = '02';
                                        $valorMedicamento = $item->valor_total + $totalgastosmedicamento;
                                        $valorProcedimento = 0.00;
                                    }
                                    if ($item->grupo == "TAXA") {
                                        $valorTaxa = $item->valor_total + $totalgastostaxa;
                                        $valorProcedimento = 0.00;
                                    }
                                    
                                   $totalgeral = $item->valor_total + $totalgastos;
                                   
                                    $i++;
                                    $totExames++;
                                    $data_autorizacao = $this->exame->listarxmldataautorizacao($value->agenda_exames_id);
                                    $dataautorizacao = substr($data_autorizacao[0]->data_cadastro, 0, 10);
                                    $dataValidadeSenha = date('Y-m-d', strtotime("+30 days", strtotime($dataautorizacao)));
                                    $carater_xml = $item->carater_xml;
                                    if ($item->medico == '') {
                                        $medico = 'ADMINISTRADOR';
                                    } else {
                                        $medico = $item->medico;
                                    }
                                    if ($item->conselho == '') {
                                        $conselho = '0000000';
                                    } else {
                                        $conselho = $item->conselho;
                                    }
                                    if ($item->cbo_ocupacao_id == '') {
                                        $cbo_medico = '999999';
                                    } else {
                                        $cbo_medico = $item->cbo_ocupacao_id;
                                    }
                                    if ($item->medicosolicitante == '') {
                                        $medicosolicitante = $item->medico;
                                    } else {
                                        $medicosolicitante = $item->medicosolicitante;
                                    }
                                    if ($item->conselhosolicitante == '') {
                                        $conselhosolicitante = $item->conselho;
                                    } else {
                                        $conselhosolicitante = $item->conselhosolicitante;
                                    }

                            if ($_POST['autorizacao'] == 'SIM') {
                                        $corpo = $corpo . "
                        <ans:guiaSP-SADT>
                          <ans:cabecalhoGuia>
                             <ans:registroANS>" . $registroans . "</ans:registroANS>
                             <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f') ? $value->ambulatorio_guia_id : $item->agenda_exames_id) . "</ans:numeroGuiaPrestador>
                             <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                          </ans:cabecalhoGuia>

                          <ans:dadosAutorizacao>
                            <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                            <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                            <ans:senha>" . $item->autorizacao . "</ans:senha>
                            <ans:dataValidadeSenha>" . $dataValidadeSenha . "</ans:dataValidadeSenha> 
                          </ans:dadosAutorizacao>

                          <ans:dadosBeneficiario>
                             <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                             <ans:atendimentoRN>S</ans:atendimentoRN>
                             <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                          </ans:dadosBeneficiario>

                          <ans:dadosSolicitante>
                            <ans:contratadoSolicitante>
                                <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                            </ans:contratadoSolicitante>

                            <ans:profissionalSolicitante>
                                <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>6</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                            </ans:profissionalSolicitante>

                          </ans:dadosSolicitante>

                          <ans:dadosSolicitacao>
                             <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                             <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                             <ans:indicacaoClinica>I</ans:indicacaoClinica>
                          </ans:dadosSolicitacao>

                          <ans:dadosExecutante>
                             
                             <ans:contratadoExecutante>
                                 <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                 <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                                 <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoExecutante>
                             <ans:CNES>" . $cnes . "</ans:CNES>
                          </ans:dadosExecutante>

                          <ans:dadosAtendimento>
                            <ans:tipoAtendimento>04</ans:tipoAtendimento>
                            <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                            <ans:tipoConsulta>1</ans:tipoConsulta>

                          </ans:dadosAtendimento>"; 
                                  if($item->tipo != "MATERIAL" && $item->tipo != "MEDICAMENTO"  && $item->tipo != "TAXA") {
                                     $corpo .= "<ans:procedimentosExecutados>
                             <ans:procedimentoExecutado>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                    <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                    <ans:procedimento>
                                       <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                       <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                                       <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                                   </ans:procedimento>                        
                                   <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                                    <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                                    <ans:equipeSadt>
                                        <ans:grauPart>12</ans:grauPart>
                                        <ans:codProfissional>
                                            <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                        </ans:codProfissional>
                                        <ans:nomeProf>" . $medico . "</ans:nomeProf>
                                        <ans:conselho>06</ans:conselho>
                                        <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                                        <ans:UF>" . $codigoUF . "</ans:UF>
                                        <ans:CBOS>$cbo_medico</ans:CBOS>
                                    </ans:equipeSadt>
                          </ans:procedimentoExecutado>
                                  </ans:procedimentosExecutados>"; 
                             if (count($produtos_gastos) > 0) {
                                   $corpo .= "<ans:outrasDespesas>"; 
                               foreach($produtos_gastos as $gastos){
                                if($gastos->tipo == "MATERIAL"){
                                      $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>03</ans:codigoDespesa>
                                    <ans:servicosExecutados
                                    ><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>19</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "TAXA" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>07</ans:codigoDespesa>
                                    <ans:servicosExecutados><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>18</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "MEDICAMENTO" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>02</ans:codigoDespesa>
                                    <ans:servicosExecutados>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>20</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>001</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }else{
                                      $corpo .="";
                                }
                               } 
                                $corpo .= "</ans:outrasDespesas>"; 
                              }
                          } 
                    
                              $corpo .="
                          <ans:observacao>III</ans:observacao>
                             <ans:valorTotal >
                             <ans:valorProcedimentos >" . number_format($valorProcedimento, 2, '.', '') . "</ans:valorProcedimentos >
                             <ans:valorDiarias>0.00</ans:valorDiarias>
                             <ans:valorTaxasAlugueis>" . number_format($totalgastostaxa, 2, '.', '') . "</ans:valorTaxasAlugueis>
                             <ans:valorMateriais>" . number_format($totalgastosmaterial, 2, '.', '') . "</ans:valorMateriais>
                             <ans:valorMedicamentos>" . number_format($totalgastosmedicamento, 2, '.', '') . "</ans:valorMedicamentos>
                             <ans:valorOPME>0.00</ans:valorOPME>
                             <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                             <ans:valorTotalGeral>" . $totalgeral . "</ans:valorTotalGeral>
                          </ans:valorTotal>
                          </ans:guiaSP-SADT>";
                                    } else {
                    
                                        //    die('morreu02');
                                        $corpo = $corpo . "
                    <ans:guiaSP-SADT>
                              <ans:cabecalhoGuia>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                             <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f') ? $value->ambulatorio_guia_id : $item->agenda_exames_id) . "</ans:numeroGuiaPrestador>
                             <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                          </ans:cabecalhoGuia>
                          <ans:dadosAutorizacao>
                          <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                          <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                          </ans:dadosAutorizacao>
                          <ans:dadosBeneficiario>
                             <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                 <ans:atendimentoRN>S</ans:atendimentoRN>
                             <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                          </ans:dadosBeneficiario>

                          <ans:dadosSolicitante>
                             <ans:contratadoSolicitante>
                                   <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                                   <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoSolicitante>
                             <ans:profissionalSolicitante>
                                <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>6</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                             </ans:profissionalSolicitante>
                          </ans:dadosSolicitante>

                          <ans:dadosSolicitacao>
                             <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                             <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                             <ans:indicacaoClinica>I</ans:indicacaoClinica>
                          </ans:dadosSolicitacao>

                          <ans:dadosExecutante>
                            <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoExecutante>
                             <ans:CNES>" . $cnes . "</ans:CNES>
                          </ans:dadosExecutante>

                          <ans:dadosAtendimento>
                            <ans:tipoAtendimento>04</ans:tipoAtendimento>
                            <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                            <ans:tipoConsulta>1</ans:tipoConsulta>

                          </ans:dadosAtendimento>";
                             
                                 if($item->tipo != "MATERIAL" && $item->tipo != "MEDICAMENTO"  && $item->tipo != "TAXA") {
                                       $corpo .= "<ans:procedimentosExecutados>
                             <ans:procedimentoExecutado>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                    <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                    <ans:procedimento>
                                        <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                        <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                                        <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                                    </ans:procedimento>                        
                                    <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                                    <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                                    <ans:equipeSadt>
                                        <ans:grauPart>12</ans:grauPart>
                                        <ans:codProfissional>
                                            <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                        </ans:codProfissional>
                                        <ans:nomeProf>" . $medico . "</ans:nomeProf>
                                        <ans:conselho>06</ans:conselho>
                                        <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                                        <ans:UF>" . $codigoUF . "</ans:UF>
                                        <ans:CBOS>$cbo_medico</ans:CBOS>
                                    </ans:equipeSadt>
                          </ans:procedimentoExecutado>
                                 </ans:procedimentosExecutados>";  
                             if (count($produtos_gastos) > 0) { 
                             $corpo .= "<ans:outrasDespesas>"; 
                               foreach($produtos_gastos as $gastos){
                                if($gastos->tipo == "MATERIAL" ){
                                      $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>03</ans:codigoDespesa>
                                    <ans:servicosExecutados
                                    ><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>19</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "TAXA" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>07</ans:codigoDespesa>
                                    <ans:servicosExecutados><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>18</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "MEDICAMENTO" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>02</ans:codigoDespesa>
                                    <ans:servicosExecutados>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>20</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>001</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }else{
                                      $corpo .="";
                                }
                               }
                               $corpo .= "</ans:outrasDespesas>";
                              }
                          }                  
                            $corpo .="
                          <ans:observacao>III</ans:observacao>
                             <ans:valorTotal >
                             <ans:valorProcedimentos >" . number_format($valorProcedimento, 2, '.', '') . "</ans:valorProcedimentos >
                             <ans:valorDiarias>0.00</ans:valorDiarias>
                             <ans:valorTaxasAlugueis>" . number_format($totalgastostaxa, 2, '.', '') . "</ans:valorTaxasAlugueis>
                             <ans:valorMateriais>" . number_format($totalgastosmaterial, 2, '.', '') . "</ans:valorMateriais>
                             <ans:valorMedicamentos>" . number_format($totalgastosmedicamento, 2, '.', '') . "</ans:valorMedicamentos>
                             <ans:valorOPME>0.00</ans:valorOPME>
                             <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                             <ans:valorTotalGeral>" . $totalgeral . "</ans:valorTotalGeral>
                          </ans:valorTotal>
                          </ans:guiaSP-SADT>";
                                    }

                                    if (!$limite) {
                                        if ($totExames == count($listarexames)) {
                                            $contador = $contador - $i;

                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>
              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>";

                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                        }
                                    } else {
                                        if ($i == 100) {
                                            $contador = $contador - $i;

                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>
              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>";

                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                        }
                                        if ($contador < 100 && $contador == $i) {
                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>
              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>";
                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                        }
                                    }
                                }
                            }
                        }
                    } else {

                        if ($listarexame[0]->grupo != 'CONSULTA') {

                            $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
        <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
           <ans:cabecalho>
              <ans:identificacaoTransacao>
                 <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
                 <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
                 <ans:dataRegistroTransacao>" . $horario . "</ans:dataRegistroTransacao>
                 <ans:horaRegistroTransacao>18:40:50</ans:horaRegistroTransacao>
              </ans:identificacaoTransacao>
              <ans:origem>
                <ans:identificacaoPrestador>" .
                                    ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                                    "</ans:identificacaoPrestador>
              </ans:origem>
              <ans:destino>
                 <ans:registroANS>" . $registroans . "</ans:registroANS>
              </ans:destino>
              <ans:versaoPadrao>" . $versao . "</ans:versaoPadrao>
           </ans:cabecalho>
           <ans:prestadorParaOperadora>
              <ans:loteGuias>
                 <ans:numeroLote>" . $b . "</ans:numeroLote>
                    <ans:guiasTISS>";

                            $contador = 0;
                            foreach ($listarpacienete as $pac) {
                                $contador += $pac->contador;
                            }

                            foreach ($listarpacienete as $value) {
                                //
                                //                        if ($value->guiaconvenio == '') {
                                //                            $guianumero = '0000000';
                                //                        } else {
                                //                            $guianumero = $value->guiaconvenio;
                                //                        }
                                if ($value->convenionumero == '') {
                                    $numerodacarteira = '0000000';
                                } else {
                                    $numerodacarteira = $value->convenionumero;
                                }

                                foreach ($listarexames as $item) {

                                    if ($item->guiaconvenio == '') {
                                        $guianumero = '0000000';
                                    } else {
                                        $guianumero = $item->guiaconvenio;
                                    }
                                    

                                    if ($value->paciente_id == $item->paciente_id && $value->ambulatorio_guia_id == $item->ambulatorio_guia_id  && $item->exames_id == $value->exames_id) {
                                        $i++;
                                        $data_autorizacao = $this->exame->listarxmldataautorizacao($value->agenda_exames_id);
                                        $dataautorizacao = substr($data_autorizacao[0]->data_cadastro, 0, 10);
                                        $dataValidadeSenha = date('Y-m-d', strtotime("+30 days", strtotime($dataautorizacao)));
                                        $carater_xml = $item->carater_xml;
                                        array_push($arrayPDF, $item);
                                        if ($item->medico == '') {
                                            $medico = 'ADMINISTRADOR';
                                        } else {
                                            $medico = $item->medico;
                                        }
                                        if ($item->conselho == '') {
                                            $conselho = '0000000';
                                        } else {
                                            $conselho = $item->conselho;
                                        }
                                        if ($item->medicosolicitante == '') {
                                            $medicosolicitante = $item->medico;
                                        } else {
                                            $medicosolicitante = $item->medicosolicitante;
                                        }
                                        if ($item->cbo_ocupacao_id == '') {
                                            $cbo_medico = '999999';
                                        } else {
                                            $cbo_medico = $item->cbo_ocupacao_id;
                                        }

                                        if ($item->conselhosolicitante == '') {
                                            $conselhosolicitante = $item->conselho;
                                        } else {
                                            $conselhosolicitante = $item->conselhosolicitante;
                                        }


                                        if ($_POST['autorizacao'] == 'SIM') {
                                            $corpo = $corpo . "
                                                              <ans:guiaSP-SADT>
                              <ans:cabecalhoGuia>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                             <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f') ? $value->ambulatorio_guia_id : $item->agenda_exames_id) . "</ans:numeroGuiaPrestador>
                             <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                          </ans:cabecalhoGuia>
                          <ans:dadosAutorizacao>
                          <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                          <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                          <ans:senha>" . $item->autorizacao . "</ans:senha>
                          <ans:dataValidadeSenha>" . $dataValidadeSenha . "</ans:dataValidadeSenha> 
                          </ans:dadosAutorizacao>
                          <ans:dadosBeneficiario>
                             <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                 <ans:atendimentoRN>S</ans:atendimentoRN>
                             <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                          </ans:dadosBeneficiario>
                                                          <ans:dadosSolicitante>
                             <ans:contratadoSolicitante>
                                   <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoSolicitante>
                             <ans:profissionalSolicitante>
                                <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>6</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                    <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                             </ans:profissionalSolicitante>
                          </ans:dadosSolicitante>
                          <ans:dadosSolicitacao>
                             <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                             <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                             <ans:indicacaoClinica>I</ans:indicacaoClinica>
                          </ans:dadosSolicitacao>
                          <ans:dadosExecutante>
                                <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                             <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoExecutante>
                             <ans:CNES>" . $cnes . "</ans:CNES>
                          </ans:dadosExecutante>
                          <ans:dadosAtendimento>
                          <ans:tipoAtendimento>04</ans:tipoAtendimento>
                          <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                          <ans:tipoConsulta>1</ans:tipoConsulta>

                          </ans:dadosAtendimento>
                          <ans:procedimentosExecutados>
                             <ans:procedimentoExecutado>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                    <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                    <ans:procedimento>
                                    <ans:codigoTabela>22</ans:codigoTabela>
                                   <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                                   <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                                   </ans:procedimento>                        
                            <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                                <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                                <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                                <ans:equipeSadt>
                                    <ans:grauPart>12</ans:grauPart>
                                    <ans:codProfissional>
                                    <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                    </ans:codProfissional>
                                    <ans:nomeProf>" . $medico . "</ans:nomeProf>
                                    <ans:conselho>06</ans:conselho>
                                    <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                                    <ans:UF>" . $codigoUF . "</ans:UF>
                                    <ans:CBOS>$cbo_medico</ans:CBOS>
                                </ans:equipeSadt>
                          </ans:procedimentoExecutado>
                          </ans:procedimentosExecutados>
                          <ans:observacao>III</ans:observacao>
                             <ans:valorTotal >
                             <ans:valorProcedimentos >" . $item->valor_total . "</ans:valorProcedimentos >
                             <ans:valorDiarias>0.00</ans:valorDiarias>
                             <ans:valorTaxasAlugueis>0.00</ans:valorTaxasAlugueis>
                             <ans:valorMateriais>0.00</ans:valorMateriais>
                             <ans:valorMedicamentos>0.00</ans:valorMedicamentos>
                             <ans:valorOPME>0.00</ans:valorOPME>
                             <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                             <ans:valorTotalGeral>" . $item->valor_total . "</ans:valorTotalGeral>
                          </ans:valorTotal>
                          </ans:guiaSP-SADT>";
                                        } else {
                                            $corpo = $corpo . "
                                                              <ans:guiaSP-SADT>
                              <ans:cabecalhoGuia>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                             <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f') ? $value->ambulatorio_guia_id : $item->agenda_exames_id) . "</ans:numeroGuiaPrestador>
                             <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                          </ans:cabecalhoGuia>
                          <ans:dadosAutorizacao>
                          <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                          <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                          </ans:dadosAutorizacao>
                          <ans:dadosBeneficiario>
                             <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                 <ans:atendimentoRN>S</ans:atendimentoRN>
                             <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                          </ans:dadosBeneficiario>
                                                          <ans:dadosSolicitante>
                             <ans:contratadoSolicitante>
                                   <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoSolicitante>
                             <ans:profissionalSolicitante>
                                <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>6</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                    <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                             </ans:profissionalSolicitante>
                          </ans:dadosSolicitante>
                          <ans:dadosSolicitacao>
                             <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                             <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                             <ans:indicacaoClinica>I</ans:indicacaoClinica>
                          </ans:dadosSolicitacao>
                          <ans:dadosExecutante>
                                <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                <ans:cpfContratado>" . $cpfxml . "</ans:cpfContratado>
                             <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoExecutante>
                             <ans:CNES>" . $cnes . "</ans:CNES>
                          </ans:dadosExecutante>
                          <ans:dadosAtendimento>
                          <ans:tipoAtendimento>04</ans:tipoAtendimento>
                          <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                          <ans:tipoConsulta>1</ans:tipoConsulta>

                          </ans:dadosAtendimento>
                          <ans:procedimentosExecutados>
                             <ans:procedimentoExecutado>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                    <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                    <ans:procedimento>
                                    <ans:codigoTabela>22</ans:codigoTabela>
                                   <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                                   <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                                   </ans:procedimento>                        
                            <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                                <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                                <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                                <ans:equipeSadt>
                                    <ans:grauPart>12</ans:grauPart>
                                    <ans:codProfissional>
                                    <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                    </ans:codProfissional>
                                    <ans:nomeProf>" . $medico . "</ans:nomeProf>
                                    <ans:conselho>06</ans:conselho>
                                    <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                                    <ans:UF>" . $codigoUF . "</ans:UF>
                                    <ans:CBOS>$cbo_medico</ans:CBOS>
                                </ans:equipeSadt>
                          </ans:procedimentoExecutado>
                          </ans:procedimentosExecutados>
                          <ans:observacao>III</ans:observacao>
                             <ans:valorTotal >
                             <ans:valorProcedimentos >" . $item->valor_total . "</ans:valorProcedimentos >
                             <ans:valorDiarias>0.00</ans:valorDiarias>
                             <ans:valorTaxasAlugueis>0.00</ans:valorTaxasAlugueis>
                             <ans:valorMateriais>0.00</ans:valorMateriais>
                             <ans:valorMedicamentos>0.00</ans:valorMedicamentos>
                             <ans:valorOPME>0.00</ans:valorOPME>
                             <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                             <ans:valorTotalGeral>" . $item->valor_total . "</ans:valorTotalGeral>
                          </ans:valorTotal>
                          </ans:guiaSP-SADT>";
                                        }

                                        if ($i == 100) {
                                            $contador = $contador - $i;

                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>
              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>";

                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                        }
                                        if ($contador < 100 && $contador == $i) {
                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>
              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>";
                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                        }
                                    }
                                }
                            }
                        } else {


                            $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
        <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
           <ans:cabecalho>
              <ans:identificacaoTransacao>
                 <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
                 <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
                 <ans:dataRegistroTransacao>" . substr($listarexame[0]->data_autorizacao, 0, 10) . "</ans:dataRegistroTransacao>
                 <ans:horaRegistroTransacao>18:40:50</ans:horaRegistroTransacao>
              </ans:identificacaoTransacao>
              <ans:origem>
                <ans:identificacaoPrestador>" .
                                    ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                                    "</ans:identificacaoPrestador>
              </ans:origem>
              <ans:destino>
                 <ans:registroANS>" . $registroans . "</ans:registroANS>
              </ans:destino>
              <ans:versaoPadrao>" . $versao . "</ans:versaoPadrao>
           </ans:cabecalho>
           <ans:prestadorParaOperadora>
              <ans:loteGuias>
                 <ans:numeroLote>" . $b . "</ans:numeroLote>
                    <ans:guiasTISS>";
                            $contador = count($listarexame);
                            foreach ($listarexame as $value) {

                                if ($value->convenionumero == '') {
                                    $numerodacarteira = '0000000';
                                } else {
                                    $numerodacarteira = $value->convenionumero;
                                }
                                if ($value->medico == '') {
                                    $medico = 'ADMINISTRADOR';
                                } else {
                                    $medico = $value->medico;
                                }
                                if ($value->conselho == '') {
                                    $conselho = '0000000';
                                } else {
                                    $conselho = $value->conselho;
                                }
                                if ($value->guiaconvenio == '') {
                                    $guianumero = '0000000';
                                } else {
                                    $guianumero = $value->guiaconvenio;
                                }
                                if ($value->cbo_ocupacao_id == '') {
                                    $cbo_medico = '999999';
                                } else {
                                    $cbo_medico = $value->cbo_ocupacao_id;
                                }
                                $corpo = $corpo . "
                    <ans:guiaConsulta>
                        <ans:cabecalhoConsulta>
                            <ans:registroANS>" . $registroans . "</ans:registroANS>
                            <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f') ? $value->ambulatorio_guia_id : $item->agenda_exames_id) . "</ans:numeroGuiaPrestador>
                        </ans:cabecalhoConsulta>
                        <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                        <ans:dadosBeneficiario>
                            <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                            <ans:atendimentoRN>N</ans:atendimentoRN>
                            <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                        </ans:dadosBeneficiario>
                        <ans:contratadoExecutante>
                            <ans:codigoPrestadorNaOperadora>" . $cpfxml . "</ans:codigoPrestadorNaOperadora>
                            <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                            <ans:CNES>" . $cnes . "</ans:CNES>
                        </ans:contratadoExecutante>
                        <ans:profissionalExecutante>
                            <ans:nomeProfissional>" . $medico . "</ans:nomeProfissional>
                            <ans:conselhoProfissional>6</ans:conselhoProfissional>
                            <ans:numeroConselhoProfissional>" . $conselho . "</ans:numeroConselhoProfissional>
                            <ans:UF>$codigoUF</ans:UF>
                            <ans:CBOS>$cbo_medico</ans:CBOS>
                        </ans:profissionalExecutante>
                        <ans:indicacaoAcidente>9</ans:indicacaoAcidente>
                        <ans:dadosAtendimento>
                            <ans:dataAtendimento>" . substr($value->data_autorizacao, 0, 10) . "</ans:dataAtendimento>
                            <ans:tipoConsulta>1</ans:tipoConsulta>
                            <ans:procedimento>
                                <ans:codigoTabela>22</ans:codigoTabela>
                                <ans:codigoProcedimento>" . $value->codigo . "</ans:codigoProcedimento>
                                <ans:valorProcedimento>" . $value->valor . "</ans:valorProcedimento>
                            </ans:procedimento>
                        </ans:dadosAtendimento>
                    </ans:guiaConsulta>";
                                if ($i == 100) {
                                    $contador = $contador - $i;
                                    $b++;
                                    $i = 0;
                                    $rodape = "</ans:guiasTISS>
            </ans:loteGuias>
        </ans:prestadorParaOperadora>
        <ans:epilogo>
        <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
        </ans:epilogo>
        </ans:mensagemTISS>";

                                    $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                    $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                    $xml = $cabecalho . $corpo . $rodape;
                                    $fp = fopen($nome, "w+");
                                    fwrite($fp, $xml . "\n");
                                    fclose($fp);
                                    $corpo = "";
                                    $rodape = "";
                                    if ($_POST['espelho_conferencia'] == "SIM") {
                                        $this->gerarPDFXML($nomePDF, $arrayPDF);
                                        $arrayPDF = array();
                                     }
                                }
                                if ($contador < 100 && $contador == $i) {

                                    $i = 0;
                                    $rodape = "   </ans:guiasTISS>


            </ans:loteGuias>
        </ans:prestadorParaOperadora>
        <ans:epilogo>
        <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
        </ans:epilogo>
        </ans:mensagemTISS>";
                                    $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                    $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                    $xml = $cabecalho . $corpo . $rodape;
                                    $fp = fopen($nome, "w+");
                                    fwrite($fp, $xml . "\n");
                                    fclose($fp);
                                    $b++;
                                    $corpo = "";
                                    $rodape = "";
                                    if ($_POST['espelho_conferencia'] == "SIM") {
                                        $this->gerarPDFXML($nomePDF, $arrayPDF);
                                        $arrayPDF = array();
                                     }
                                }
                            }
                        }
                    }
                } else {


                    if ($_POST['layoutarq'] == 'sadt' && count($listarexame) > 0) {
                        // echo 'its here'; die;
                        $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
        <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
           <ans:cabecalho>
              <ans:identificacaoTransacao>
                 <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
                 <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
                 <ans:dataRegistroTransacao>" . substr($listarexame[0]->data_autorizacao, 0, 10) . "</ans:dataRegistroTransacao>
                 <ans:horaRegistroTransacao>" . $hora . "</ans:horaRegistroTransacao>
              </ans:identificacaoTransacao>
              <ans:origem>
                <ans:identificacaoPrestador>" .
                                ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                                "</ans:identificacaoPrestador>
              </ans:origem>
              <ans:destino>
                 <ans:registroANS>" . $registroans . "</ans:registroANS>
              </ans:destino>
              <ans:versaoPadrao>" . $versao . "</ans:versaoPadrao>
           </ans:cabecalho>
           <ans:prestadorParaOperadora>
              <ans:loteGuias>
                 <ans:numeroLote>" . $b . "</ans:numeroLote>
                    <ans:guiasTISS>";
                        $contador = 0;
                        foreach ($listarpacienete as $pac) {
                            $contador += $pac->contador;
                        }
                        foreach ($listarpacienete as $value) {                            
                            $produtos_gastos = $this->exame->listaritensgastosprocedimento($value->exames_id);
                            $totalgastos = 0;
                            $totalgastostaxa= 0;
                            $totalgastosmaterial = 0;
                            $totalgastosmedicamento= 0;
                            if (count($produtos_gastos) > 0) {
                                foreach($produtos_gastos as $v){                                    
                                    $totalgastos += $v->valor;
                                    if ($v->tipo = "MATERIAL") {
                                        $totalgastosmaterial += $v->valor;
                                    } elseif ($v->tipo = "TAXA") {
                                        $totalgastostaxa += $v->valor;
                                    } elseif ($v->tipo = "MEDICAMENTO") {
                                        $totalgastosmedicamento += $v->valor;
                                    } else {
                                        
                                    }
                                }                                
                            }
                            if ($value->convenionumero == '') {
                                $numerodacarteira = '0000000';
                            } else {
                                $numerodacarteira = $value->convenionumero;
                            }

                            foreach ($listarexames as $item) {
                                if ($item->guiaconvenio == '') {
                                    $guianumero = '0000000';
                                } else {
                                    $guianumero = $item->guiaconvenio;
                                }
                                

                                if ($item->cbo_ocupacao_id == '') {
                                    $cbo_medico = '999999';
                                } else {
                                    $cbo_medico = $item->cbo_ocupacao_id;
                                }

                                if ($value->paciente_id == $item->paciente_id && $value->ambulatorio_guia_id == $item->ambulatorio_guia_id  && $item->exames_id == $value->exames_id) {
                                    $tabela = '22';
                                    array_push($arrayPDF, $item);

                                    $valorProcedimento = $item->valor_total;
                                    $valorMaterial = 0.00;
                                    $valorMedicamento = 0.00;
                                    $valorTaxa = 0.00;

                                   if ($item->grupo == "MATERIAL") { //caso seja material
                                        $tabela = '19';
                                        $codDespesa = '03';                                        
                                        $valorMaterial = $item->valor_total  + $totalgastosmaterial;
                                        $valorProcedimento = 0.00;
                                    } elseif ($item->grupo == "MEDICAMENTO") { //caso seja medicamento
                                        $tabela = '20';
                                        $codDespesa = '02';
                                        $valorMedicamento = $item->valor_total + $totalgastosmedicamento;
                                        $valorProcedimento = 0.00;
                                    }
                                    if ($item->grupo == "TAXA") {
                                        $valorTaxa = $item->valor_total + $totalgastostaxa;
                                        $valorProcedimento = 0.00;
                                    }

                                $totalgeral = $item->valor_total + $totalgastos;

                                    $i++;

                                    $totExames++;
                                    // echo "<span style='color:red'>$totExames</span>";
                                    $data_autorizacao = $this->exame->listarxmldataautorizacao($value->agenda_exames_id);
                                    $dataautorizacao = substr($data_autorizacao[0]->data_cadastro, 0, 10);
                                    $dataValidadeSenha = date('Y-m-d', strtotime("+30 days", strtotime($dataautorizacao)));
                                    $carater_xml = $item->carater_xml;
                                    if ($item->medico == '') {
                                        $medico = 'ADMINISTRADOR';
                                    } else {
                                        $medico = $item->medico;
                                    }
                                    if ($item->conselho == '') {
                                        $conselho = '0000000';
                                    } else {
                                        $conselho = $item->conselho;
                                    }
                                    if ($item->medicosolicitante == '') {
                                        $medicosolicitante = $item->medico;
                                    } else {
                                        $medicosolicitante = $item->medicosolicitante;
                                    }
                                    if ($item->conselhosolicitante == '') {
                                        $conselhosolicitante = $item->conselho;
                                    } else {
                                        $conselhosolicitante = $item->conselhosolicitante;
                                    }

                                    if ($_POST['autorizacao'] == 'SIM') {
                                        
                                        $corpo = $corpo . "
                                                              <ans:guiaSP-SADT>
                              <ans:cabecalhoGuia>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                             <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f' ? $value->ambulatorio_guia_id : $item->agenda_exames_id)) . "</ans:numeroGuiaPrestador>
                             <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                          </ans:cabecalhoGuia>
                          <ans:dadosAutorizacao>
                          <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                          <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                          <ans:senha>" . $item->autorizacao . "</ans:senha>
                          <ans:dataValidadeSenha>" . $dataValidadeSenha . "</ans:dataValidadeSenha> 
                          </ans:dadosAutorizacao>
                          <ans:dadosBeneficiario>
                             <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                 <ans:atendimentoRN>S</ans:atendimentoRN>
                             <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                          </ans:dadosBeneficiario>
                                                          <ans:dadosSolicitante>
                             <ans:contratadoSolicitante>
                                   <ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoSolicitante>
                             <ans:profissionalSolicitante>
                                <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>6</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                    <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                             </ans:profissionalSolicitante>
                          </ans:dadosSolicitante>
                          <ans:dadosSolicitacao>
                             <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                             <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                             <ans:indicacaoClinica>I</ans:indicacaoClinica>
                          </ans:dadosSolicitacao>
                          <ans:dadosExecutante>
                             <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                <ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoExecutante>
                             <ans:CNES>" . $cnes . "</ans:CNES>
                          </ans:dadosExecutante>
                          <ans:dadosAtendimento>
                          <ans:tipoAtendimento>04</ans:tipoAtendimento>
                          <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                          <ans:tipoConsulta>1</ans:tipoConsulta>

                          </ans:dadosAtendimento>
                          ";
                                            
                              if($item->tipo != "MATERIAL" && $item->tipo != "MEDICAMENTO"  && $item->tipo != "TAXA") { 
                                   $corpo .="<ans:procedimentosExecutados>
                                 <ans:procedimentoExecutado>
                                        <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                        <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                        <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                        <ans:procedimento>
                                        <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                       <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                                       <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                                       </ans:procedimento>                        
                                <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                                    <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                                    <ans:equipeSadt>
                                        <ans:grauPart>12</ans:grauPart>
                                        <ans:codProfissional>
                                            <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                        </ans:codProfissional>
                                        <ans:nomeProf>" . $medico . "</ans:nomeProf>
                                        <ans:conselho>06</ans:conselho>
                                        <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                                        <ans:UF>" . $codigoUF . "</ans:UF>
                                        <ans:CBOS>$cbo_medico</ans:CBOS>
                                    </ans:equipeSadt>
                              </ans:procedimentoExecutado>
                                    </ans:procedimentosExecutados>";  
                             if (count($produtos_gastos) > 0) {
                                  $corpo .= "<ans:outrasDespesas>"; 
                               foreach($produtos_gastos as $gastos){
                                if($gastos->tipo == "MATERIAL" ){
                                      $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>03</ans:codigoDespesa>
                                    <ans:servicosExecutados
                                    ><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>19</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "TAXA" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>07</ans:codigoDespesa>
                                    <ans:servicosExecutados><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>18</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "MEDICAMENTO" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>02</ans:codigoDespesa>
                                    <ans:servicosExecutados>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>20</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>001</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }else{
                                      $corpo .="";
                                }
                               } 
                               $corpo .= "</ans:outrasDespesas>"; 
                              }
                          } 
                    
                             $corpo .="
                          <ans:observacao>III</ans:observacao>
                             <ans:valorTotal >
                             <ans:valorProcedimentos >" . number_format($valorProcedimento, 2, '.', '') . "</ans:valorProcedimentos >
                             <ans:valorDiarias>0.00</ans:valorDiarias>
                             <ans:valorTaxasAlugueis>" . number_format($totalgastostaxa, 2, '.', '') . "</ans:valorTaxasAlugueis>
                             <ans:valorMateriais>" . number_format($totalgastosmaterial, 2, '.', '') . "</ans:valorMateriais>
                             <ans:valorMedicamentos>" . number_format($totalgastosmedicamento, 2, '.', '') . "</ans:valorMedicamentos>
                             <ans:valorOPME>0.00</ans:valorOPME>
                             <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                             <ans:valorTotalGeral>" .  $totalgeral. "</ans:valorTotalGeral>
                          </ans:valorTotal>
                          </ans:guiaSP-SADT>";
                                    } else {
                                        $corpo = $corpo . "
                                                              <ans:guiaSP-SADT>
                              <ans:cabecalhoGuia>
                                <ans:registroANS>" . $registroans . "</ans:registroANS>
                             <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f' ? $value->ambulatorio_guia_id : $item->agenda_exames_id)) . "</ans:numeroGuiaPrestador>
                             <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                          </ans:cabecalhoGuia>
                          <ans:dadosAutorizacao>
                          <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                          <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                          </ans:dadosAutorizacao>
                          <ans:dadosBeneficiario>
                             <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                                 <ans:atendimentoRN>S</ans:atendimentoRN>
                             <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                          </ans:dadosBeneficiario>
                                                          <ans:dadosSolicitante>
                             <ans:contratadoSolicitante>
                                   <ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>
                                <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoSolicitante>
                             <ans:profissionalSolicitante>
                                <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                                <ans:conselhoProfissional>6</ans:conselhoProfissional>
                                <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                    <ans:UF>" . $codigoUF . "</ans:UF>
                                <ans:CBOS>$cbo_medico</ans:CBOS>
                             </ans:profissionalSolicitante>
                          </ans:dadosSolicitante>
                          <ans:dadosSolicitacao>
                             <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                             <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                             <ans:indicacaoClinica>I</ans:indicacaoClinica>
                          </ans:dadosSolicitacao>
                          <ans:dadosExecutante>
                                <ans:contratadoExecutante>
                                <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                <ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>
                             <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                             </ans:contratadoExecutante>
                             <ans:CNES>" . $cnes . "</ans:CNES>
                          </ans:dadosExecutante>
                          <ans:dadosAtendimento>
                          <ans:tipoAtendimento>04</ans:tipoAtendimento>
                          <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                          <ans:tipoConsulta>1</ans:tipoConsulta>

                          </ans:dadosAtendimento>";
                                       if($item->tipo != "MATERIAL" && $item->tipo != "MEDICAMENTO"  && $item->tipo != "TAXA") { 
                                        $corpo .="<ans:procedimentosExecutados>
                                 <ans:procedimentoExecutado>
                                        <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                        <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                        <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                        <ans:procedimento>
                                        <ans:codigoTabela>" . $tabela . "</ans:codigoTabela>
                                       <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                                       <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                                       </ans:procedimento>                        
                                <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                                    <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                                    <ans:equipeSadt>
                                        <ans:grauPart>12</ans:grauPart>
                                        <ans:codProfissional>
                                        <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                                        </ans:codProfissional>
                                        <ans:nomeProf>" . $medico . "</ans:nomeProf>
                                        <ans:conselho>06</ans:conselho>
                                        <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                                        <ans:UF>" . $codigoUF . "</ans:UF>
                                        <ans:CBOS>$cbo_medico</ans:CBOS>
                                    </ans:equipeSadt>
                              </ans:procedimentoExecutado>
                                       </ans:procedimentosExecutados>";                  
                             if (count($produtos_gastos) > 0) {
                                  $corpo .= "<ans:outrasDespesas>"; 
                               foreach($produtos_gastos as $gastos){
                                if($gastos->tipo == "MATERIAL" ){
                                      $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>03</ans:codigoDespesa>
                                    <ans:servicosExecutados
                                    ><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>19</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "TAXA" ){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>07</ans:codigoDespesa>
                                    <ans:servicosExecutados><ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>18</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>036</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }elseif($gastos->tipo == "MEDICAMENTO"){
                                     $corpo .="<ans:despesa>
                                    <ans:codigoDespesa>02</ans:codigoDespesa>
                                    <ans:servicosExecutados>
                                    <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                    <ans:codigoTabela>20</ans:codigoTabela>
                                    <ans:codigoProcedimento>" . $gastos->codigo . "</ans:codigoProcedimento>
                                    <ans:quantidadeExecutada>".$gastos->quantidade."</ans:quantidadeExecutada>
                                    <ans:unidadeMedida>001</ans:unidadeMedida>
                                    <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                                    <ans:valorUnitario>" . ($gastos->valor / $gastos->quantidade) . "</ans:valorUnitario>
                                    <ans:valorTotal>" . ($gastos->valor) . "</ans:valorTotal>
                                    <ans:descricaoProcedimento>" . substr(($gastos->procedimento), 0, 60) . "</ans:descricaoProcedimento>
                                    </ans:servicosExecutados>
                                    </ans:despesa>";
                                }else{
                                      $corpo .="";
                                   }
                                 }
                               $corpo .= "</ans:outrasDespesas>";  
                               }            
                             }                                       
                    
                             $corpo .="
                          <ans:observacao>III</ans:observacao>
                             <ans:valorTotal >
                             <ans:valorProcedimentos >" . number_format($valorProcedimento, 2, '.', '') . "</ans:valorProcedimentos >
                             <ans:valorDiarias>0.00</ans:valorDiarias>
                             <ans:valorTaxasAlugueis>" . number_format($totalgastostaxa, 2, '.', '') . "</ans:valorTaxasAlugueis>
                             <ans:valorMateriais>" . number_format($totalgastosmaterial, 2, '.', '') . "</ans:valorMateriais>
                             <ans:valorMedicamentos>" . number_format($totalgastosmedicamento, 2, '.', '') . "</ans:valorMedicamentos>
                             <ans:valorOPME>0.00</ans:valorOPME>
                             <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                             <ans:valorTotalGeral>" .  $totalgeral . "</ans:valorTotalGeral>
                          </ans:valorTotal>
                          </ans:guiaSP-SADT>";
                                    }
                                    if (!$limite) {
                                        if ($totExames == count($listarexames)) {
                                            $contador = $contador - $i;
                                            $b++;
                                            $i = 0;
                                            $rodape = "</ans:guiasTISS>
            </ans:loteGuias>
        </ans:prestadorParaOperadora>
        <ans:epilogo>
        <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
        </ans:epilogo>
        </ans:mensagemTISS>";

                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                            //    echo 'gravou o xml';
                                        }
                                        $total_certao = count($listarexames);
                                        // echo '<pre>';
                                        // var_dump($item); 
                                        // echo '<br> <hr>';
                                        // echo "$totExames , {$total_certao} <br>";
                                    } else {

                                        if ($i == 100) {
                                            $contador = $contador - $i;

                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>

              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>
        ";

                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                        }

                                        if ($contador < 100 && $contador == $i) {
                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>

              </ans:loteGuias>
           </ans:prestadorParaOperadora>
           <ans:epilogo>
              <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
           </ans:epilogo>
        </ans:mensagemTISS>
        ";
                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                            // echo 'aushd';
                                        }
                                    }
                                }
                            }
                        }
                        // die;
                    } else {

                        if ($listarexame[0]->grupo != 'CONSULTA') {
                            $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
    <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
       <ans:cabecalho>
          <ans:identificacaoTransacao>
             <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
             <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
             <ans:dataRegistroTransacao>" . substr($listarexame[0]->data_autorizacao, 0, 10) . "</ans:dataRegistroTransacao>
             <ans:horaRegistroTransacao>18:40:50</ans:horaRegistroTransacao>
          </ans:identificacaoTransacao>
          <ans:origem>
            <ans:identificacaoPrestador>" .
                                    ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                                    "</ans:identificacaoPrestador>
          </ans:origem>
          <ans:destino>
             <ans:registroANS>" . $registroans . "</ans:registroANS>
          </ans:destino>
          <ans:versaoPadrao>" . $versao . "</ans:versaoPadrao>
       </ans:cabecalho>
       <ans:prestadorParaOperadora>
          <ans:loteGuias>
             <ans:numeroLote>" . $b . "</ans:numeroLote>
                <ans:guiasTISS>";
                            $contador = 0;
                            foreach ($listarpacienete as $pac) {
                                $contador += $pac->contador;
                            }

                            foreach ($listarpacienete as $value) {
                                if ($value->convenionumero == '') {
                                    $numerodacarteira = '0000000';
                                } else {
                                    $numerodacarteira = $value->convenionumero;
                                }

                                foreach ($listarexames as $item) {

                                    if ($item->guiaconvenio == '') {
                                        $guianumero = '0000000';
                                    } else {
                                        $guianumero = $item->guiaconvenio;
                                    }
                                    

                                    if ($value->paciente_id == $item->paciente_id && $value->ambulatorio_guia_id == $item->ambulatorio_guia_id  && $item->exames_id == $value->exames_id) {
                                        $i++;
                                        $data_autorizacao = $this->exame->listarxmldataautorizacao($value->agenda_exames_id);
                                        $dataautorizacao = substr($data_autorizacao[0]->data_cadastro, 0, 10);
                                        $dataValidadeSenha = date('Y-m-d', strtotime("+30 days", strtotime($dataautorizacao)));
                                        $carater_xml = $item->carater_xml;
                                        array_push($arrayPDF, $item);
                                        if ($item->medico == '') {
                                            $medico = 'ADMINISTRADOR';
                                        } else {
                                            $medico = $item->medico;
                                        }
                                        if ($item->conselho == '') {
                                            $conselho = '0000000';
                                        } else {
                                            $conselho = $item->conselho;
                                        }
                                        if ($item->medicosolicitante == '') {
                                            $medicosolicitante = $item->medico;
                                        } else {
                                            $medicosolicitante = $item->medicosolicitante;
                                        }
                                        if ($item->conselhosolicitante == '') {
                                            $conselhosolicitante = $item->conselho;
                                        } else {
                                            $conselhosolicitante = $item->conselhosolicitante;
                                        }
                                        if ($item->cbo_ocupacao_id == '') {
                                            $cbo_medico = '999999';
                                        } else {
                                            $cbo_medico = $item->cbo_ocupacao_id;
                                        }

                                        if ($_POST['autorizacao'] == 'SIM') {
                                            $corpo = $corpo . "
                                                          <ans:guiaSP-SADT>
                          <ans:cabecalhoGuia>
                            <ans:registroANS>" . $registroans . "</ans:registroANS>
                         <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f') ? $value->ambulatorio_guia_id : $item->agenda_exames_id) . "</ans:numeroGuiaPrestador>
                         <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                      </ans:cabecalhoGuia>
                      <ans:dadosAutorizacao>
                      <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                      <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                      <ans:senha>" . $item->autorizacao . "</ans:senha>
                      <ans:dataValidadeSenha>" . $dataValidadeSenha . "</ans:dataValidadeSenha> 
                      </ans:dadosAutorizacao>
                      <ans:dadosBeneficiario>
                         <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                             <ans:atendimentoRN>S</ans:atendimentoRN>
                         <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                      </ans:dadosBeneficiario>
                                                      <ans:dadosSolicitante>
                         <ans:contratadoSolicitante>
                               <ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>
                            <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                         </ans:contratadoSolicitante>
                         <ans:profissionalSolicitante>
                            <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                            <ans:conselhoProfissional>6</ans:conselhoProfissional>
                            <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                <ans:UF>" . $codigoUF . "</ans:UF>
                            <ans:CBOS>$cbo_medico</ans:CBOS>
                         </ans:profissionalSolicitante>
                      </ans:dadosSolicitante>
                      <ans:dadosSolicitacao>
                         <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                         <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                         <ans:indicacaoClinica>I</ans:indicacaoClinica>
                      </ans:dadosSolicitacao>
                      <ans:dadosExecutante>
                            <ans:contratadoExecutante>
                            <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                            <ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>
                         <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                         </ans:contratadoExecutante>
                         <ans:CNES>" . $cnes . "</ans:CNES>
                      </ans:dadosExecutante>
                      <ans:dadosAtendimento>
                      <ans:tipoAtendimento>04</ans:tipoAtendimento>
                      <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                      <ans:tipoConsulta>1</ans:tipoConsulta>

                      </ans:dadosAtendimento>
                      <ans:procedimentosExecutados>
                         <ans:procedimentoExecutado>
                                <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                <ans:procedimento>
                                <ans:codigoTabela>22</ans:codigoTabela>
                               <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                               <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                               </ans:procedimento>                        
                        <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                            <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                            <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                            <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                            <ans:equipeSadt>
                            <ans:codProfissional>
                            <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                            </ans:codProfissional>
                            <ans:nomeProf>" . $medico . "</ans:nomeProf>
                            <ans:conselho>06</ans:conselho>
                            <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                            <ans:UF>" . $codigoUF . "</ans:UF>
                            <ans:CBOS>$cbo_medico</ans:CBOS>
                            </ans:equipeSadt>
                      </ans:procedimentoExecutado>
                      </ans:procedimentosExecutados>
                      <ans:observacao>III</ans:observacao>
                         <ans:valorTotal >
                         <ans:valorProcedimentos >" . $item->valor_total . "</ans:valorProcedimentos >
                         <ans:valorDiarias>0.00</ans:valorDiarias>
                         <ans:valorTaxasAlugueis>0.00</ans:valorTaxasAlugueis>
                         <ans:valorMateriais>0.00</ans:valorMateriais>
                         <ans:valorMedicamentos>0.00</ans:valorMedicamentos>
                         <ans:valorOPME>0.00</ans:valorOPME>
                         <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                         <ans:valorTotalGeral>" . $item->valor_total . "</ans:valorTotalGeral>
                      </ans:valorTotal>
                      </ans:guiaSP-SADT>";
                                        } else {
                                            $corpo = $corpo . "
                                                          <ans:guiaSP-SADT>
                          <ans:cabecalhoGuia>
                            <ans:registroANS>" . $registroans . "</ans:registroANS>
                         <ans:numeroGuiaPrestador>" . (($item->guia_prestador_unico == 'f') ? $value->ambulatorio_guia_id : $item->agenda_exames_id) . "</ans:numeroGuiaPrestador>
                         <ans:guiaPrincipal>" . $guianumero . "</ans:guiaPrincipal>
                      </ans:cabecalhoGuia>
                      <ans:dadosAutorizacao>
                      <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                      <ans:dataAutorizacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataAutorizacao>
                      </ans:dadosAutorizacao>
                      <ans:dadosBeneficiario>
                         <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                             <ans:atendimentoRN>S</ans:atendimentoRN>
                         <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                      </ans:dadosBeneficiario>
                                                      <ans:dadosSolicitante>
                         <ans:contratadoSolicitante>
                               <ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>
                            <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                         </ans:contratadoSolicitante>
                         <ans:profissionalSolicitante>
                            <ans:nomeProfissional>" . $medicosolicitante . "</ans:nomeProfissional>
                            <ans:conselhoProfissional>6</ans:conselhoProfissional>
                            <ans:numeroConselhoProfissional >" . $conselhosolicitante . "</ans:numeroConselhoProfissional >
                                <ans:UF>" . $codigoUF . "</ans:UF>
                            <ans:CBOS>$cbo_medico</ans:CBOS>
                         </ans:profissionalSolicitante>
                      </ans:dadosSolicitante>
                      <ans:dadosSolicitacao>
                         <ans:dataSolicitacao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataSolicitacao>
                         <ans:caraterAtendimento>$carater_xml</ans:caraterAtendimento>
                         <ans:indicacaoClinica>I</ans:indicacaoClinica>
                      </ans:dadosSolicitacao>
                      <ans:dadosExecutante>
                            <ans:contratadoExecutante>
                            <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                            <ans:cnpjContratado>" . $cnpj . "</ans:cnpjContratado>
                         <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                         </ans:contratadoExecutante>
                         <ans:CNES>" . $cnes . "</ans:CNES>
                      </ans:dadosExecutante>
                      <ans:dadosAtendimento>
                      <ans:tipoAtendimento>04</ans:tipoAtendimento>
                      <ans:indicacaoAcidente>0</ans:indicacaoAcidente>
                      <ans:tipoConsulta>1</ans:tipoConsulta>

                      </ans:dadosAtendimento>
                      <ans:procedimentosExecutados>
                         <ans:procedimentoExecutado>
                                <ans:dataExecucao>" . substr($data_autorizacao[0]->data_cadastro, 0, 10) . "</ans:dataExecucao>
                                <ans:horaInicial>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaInicial>
                                <ans:horaFinal>" . substr($data_autorizacao[0]->data_cadastro, 11, 8) . "</ans:horaFinal>
                                <ans:procedimento>
                                <ans:codigoTabela>22</ans:codigoTabela>
                               <ans:codigoProcedimento>" . $item->codigo . "</ans:codigoProcedimento>
                               <ans:descricaoProcedimento >" . substr(($item->procedimento), 0, 60) . "</ans:descricaoProcedimento >
                               </ans:procedimento>                        
                        <ans:quantidadeExecutada>" . $item->quantidade . "</ans:quantidadeExecutada>
                            <ans:reducaoAcrescimo>1.00</ans:reducaoAcrescimo>
                            <ans:valorUnitario >" . $item->valor . "</ans:valorUnitario >
                            <ans:valorTotal>" . $item->valor_total . "</ans:valorTotal>
                            <ans:equipeSadt>
                            <ans:codProfissional>
                            <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                            </ans:codProfissional>
                            <ans:nomeProf>" . $medico . "</ans:nomeProf>
                            <ans:conselho>06</ans:conselho>
                            <ans:numeroConselhoProfissional>$conselho</ans:numeroConselhoProfissional>
                            <ans:UF>" . $codigoUF . "</ans:UF>
                            <ans:CBOS>$cbo_medico</ans:CBOS>
                            </ans:equipeSadt>
                      </ans:procedimentoExecutado>
                      </ans:procedimentosExecutados>
                      <ans:observacao>III</ans:observacao>
                         <ans:valorTotal >
                         <ans:valorProcedimentos >" . $item->valor_total . "</ans:valorProcedimentos >
                         <ans:valorDiarias>0.00</ans:valorDiarias>
                         <ans:valorTaxasAlugueis>0.00</ans:valorTaxasAlugueis>
                         <ans:valorMateriais>0.00</ans:valorMateriais>
                         <ans:valorMedicamentos>0.00</ans:valorMedicamentos>
                         <ans:valorOPME>0.00</ans:valorOPME>
                         <ans:valorGasesMedicinais>0.00</ans:valorGasesMedicinais>
                         <ans:valorTotalGeral>" . $item->valor_total . "</ans:valorTotalGeral>
                      </ans:valorTotal>
                      </ans:guiaSP-SADT>";
                                        }

                                        if ($i == 100) {
                                            $contador = $contador - $i;

                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>

          </ans:loteGuias>
       </ans:prestadorParaOperadora>
       <ans:epilogo>
          <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
       </ans:epilogo>
    </ans:mensagemTISS>
    ";

                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                        }

                                        if ($contador < 100 && $contador == $i) {

                                            $i = 0;
                                            $rodape = "   </ans:guiasTISS>

          </ans:loteGuias>
       </ans:prestadorParaOperadora>
       <ans:epilogo>
          <ans:hash>035753bf836c231bedbc68a08daf4668</ans:hash>
       </ans:epilogo>
    </ans:mensagemTISS>
    ";
                                            $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".xml";
                                            $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivo . ".pdf";
                                            $xml = $cabecalho . $corpo . $rodape;
                                            $fp = fopen($nome, "w+");
                                            fwrite($fp, $xml . "\n");
                                            fclose($fp);
                                            $b++;
                                            $corpo = "";
                                            $rodape = "";
                                            if ($_POST['espelho_conferencia'] == "SIM") {
                                                $this->gerarPDFXML($nomePDF, $arrayPDF);
                                                $arrayPDF = array();
                                             }
                                        }
                                    }
                                }
                            }
                        } else {

                            $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
    <ans:mensagemTISS xmlns='http://www.w3.org/2001/XMLSchema' xmlns:ans='http://www.ans.gov.br/padroes/tiss/schemas'>
       <ans:cabecalho>
          <ans:identificacaoTransacao>
             <ans:tipoTransacao>ENVIO_LOTE_GUIAS</ans:tipoTransacao>
             <ans:sequencialTransacao>" . $j . "</ans:sequencialTransacao>
             <ans:dataRegistroTransacao>" . substr($listarexame[0]->data_autorizacao, 0, 10) . "</ans:dataRegistroTransacao>
             <ans:horaRegistroTransacao>18:40:50</ans:horaRegistroTransacao>
          </ans:identificacaoTransacao>
          <ans:origem>
            <ans:identificacaoPrestador>" .
                                    ( ($cnpjPrestador == 'SIM') ? ("<ans:CNPJ>" . $cnpj . "</ans:CNPJ>") : ("<ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>")) .
                                    "</ans:identificacaoPrestador>
          </ans:origem>
          <ans:destino>
             <ans:registroANS>" . $registroans . "</ans:registroANS>
          </ans:destino>
          <ans:versaoPadrao>" . $versao . "</ans:versaoPadrao>
       </ans:cabecalho>
       <ans:prestadorParaOperadora>
          <ans:loteGuias>
             <ans:numeroLote>" . $b . "</ans:numeroLote>
                <ans:guiasTISS>";
                            $contador = count($listarexame);

                            foreach ($listarexame as $value) {
                                $i++;
                                if ($value->convenionumero == '') {
                                    $numerodacarteira = '0000000';
                                } else {
                                    $numerodacarteira = $value->convenionumero;
                                }
                                if ($value->medico == '') {
                                    $medico = 'ADMINISTRADOR';
                                } else {
                                    $medico = $value->medico;
                                }
                                if ($value->conselho == '') {
                                    $conselho = '0000000';
                                } else {
                                    $conselho = $value->conselho;
                                }
                                if ($value->guiaconvenio == '') {
                                    $guianumero = '0000000';
                                } else {
                                    $guianumero = $value->guiaconvenio;
                                }
                                if ($value->cbo_ocupacao_id == '') {
                                    $cbo_medico = '999999';
                                } else {
                                    $cbo_medico = $value->cbo_ocupacao_id;
                                }
                                $corpo = $corpo . "
                <ans:guiaConsulta>
                    <ans:cabecalhoConsulta>
                        <ans:registroANS>" . $registroans . "</ans:registroANS>
                        <ans:numeroGuiaPrestador>" . (($value->guia_prestador_unico == 'f' ? $value->ambulatorio_guia_id : $value->agenda_exames_id)) . "</ans:numeroGuiaPrestador>
                    </ans:cabecalhoConsulta>
                    <ans:numeroGuiaOperadora>" . $guianumero . "</ans:numeroGuiaOperadora>
                    <ans:dadosBeneficiario>
                        <ans:numeroCarteira>" . $numerodacarteira . "</ans:numeroCarteira>
                        <ans:atendimentoRN>N</ans:atendimentoRN>
                        <ans:nomeBeneficiario>" . $value->paciente . "</ans:nomeBeneficiario>
                    </ans:dadosBeneficiario>
                    <ans:contratadoExecutante>
                        <ans:codigoPrestadorNaOperadora>" . $cnpjxml . "</ans:codigoPrestadorNaOperadora>
                        <ans:nomeContratado>" . $razao_socialxml . "</ans:nomeContratado>
                        <ans:CNES>" . $cnes . "</ans:CNES>
                    </ans:contratadoExecutante>
                    <ans:profissionalExecutante>
                        <ans:nomeProfissional>" . $medico . "</ans:nomeProfissional>
                        <ans:conselhoProfissional>6</ans:conselhoProfissional>
                        <ans:numeroConselhoProfissional>" . $conselho . "</ans:numeroConselhoProfissional>
                        <ans:UF>15</ans:UF>
                        <ans:CBOS>$cbo_medico</ans:CBOS>
                    </ans:profissionalExecutante>
                    <ans:indicacaoAcidente>9</ans:indicacaoAcidente>
                    <ans:dadosAtendimento>
                        <ans:dataAtendimento>" . substr($value->data_autorizacao, 0, 10) . "</ans:dataAtendimento>
                        <ans:tipoConsulta>1</ans:tipoConsulta>
                        <ans:procedimento>
                            <ans:codigoTabela>22</ans:codigoTabela>
                            <ans:codigoProcedimento>" . $value->codigo . "</ans:codigoProcedimento>
                            <ans:valorProcedimento>" . $value->valor . "</ans:valorProcedimento>
                        </ans:procedimento>
                    </ans:dadosAtendimento>
                </ans:guiaConsulta>";
                                if ($i == 100) {
                                    $contador = $contador - $i;
                                    $i = 0;
                                    $rodape = "</ans:guiasTISS>
        </ans:loteGuias>
    </ans:prestadorParaOperadora>
    <ans:epilogo>
    <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
    </ans:epilogo>
    </ans:mensagemTISS>
    ";

                                    $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                    $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                    $xml = $cabecalho . $corpo . $rodape;
                                    $fp = fopen($nome, "w+");
                                    fwrite($fp, $xml . "\n");
                                    fclose($fp);
                                    $b++;
                                    $corpo = "";
                                    $rodape = "";
                                    if ($_POST['espelho_conferencia'] == "SIM") {
                                        $this->gerarPDFXML($nomePDF, $arrayPDF);
                                        $arrayPDF = array();
                                     }
                                }
                                if ($contador < 100 && $contador == $i) {
                                    $i = 0;
                                    $rodape = "   </ans:guiasTISS>


        </ans:loteGuias>
    </ans:prestadorParaOperadora>
    <ans:epilogo>
    <ans:hash>e2eadfe09fd6750a184902545aa41771</ans:hash>
    </ans:epilogo>
    </ans:mensagemTISS>
    ";
                                    $nome = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".xml";
                                    $nomePDF = "./upload/cr/" . $convenio . "/" . $zero . $b . "_" . $nomearquivoconsulta . ".pdf";
                                    $xml = $cabecalho . $corpo . $rodape;
                                    $fp = fopen($nome, "w+");
                                    fwrite($fp, $xml . "\n");
                                    fclose($fp);
                                    $b++;
                                    $corpo = "";
                                    $rodape = "";
                                    if ($_POST['espelho_conferencia'] == "SIM") {
                                        $this->gerarPDFXML($nomePDF, $arrayPDF);
                                        $arrayPDF = array();
                                     }
                                }
                            }
                        }
                    }
                }
            }
        }

        if (count($listarexame) > 0) {
            $this->exame->gravarlote($b);
            $zip = new ZipArchive;
            $this->load->helper('directory');
            $arquivo_pasta = directory_map("./upload/cr/$convenio/");


            if ($arquivo_pasta != false) {
                foreach ($arquivo_pasta as $value) {
                    $deletar[] = "./upload/cr/$convenio/$value";
                }
                foreach ($arquivo_pasta as $value) {

                    $rest = substr($value, 0, -4);
                    $rest = $rest;

//                    $origem_con = "./upload/cr/$convenio/$rest";
//                    if (!is_dir("./upload/cr/$convenio/$rest")) {
//                        mkdir("./upload/cr/$convenio/$rest");
//                        chmod($origem_con, 0777);
//                    }

                    $zip->open("./upload/cr/$convenio/$value.zip", ZipArchive::CREATE);                   
                    $zip->addFile("./upload/cr/$convenio/$value", "$value");
                    
                    //           $deletarxml = "./upload/cr/$convenio/$value";
                    //           unlink($deletarxml);
                }
                $zip->close();
                foreach ($deletar as $arquivonome) {
                    unlink($arquivonome);  
                }
            }
        }
        // var_dump('teee'); die;
        $data['mensagem'] = 'Sucesso ao gerar arquivo.';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exame/faturamentoexamexml", $data);
    }

    function gerardicom($laudo_id) {
        $exame = $this->exame->listardicom($laudo_id);

        $grupo = $exame[0]->grupo;
        if ($grupo == 'RX' || $grupo == 'MAMOGRAFIA') {
            $grupo = 'CR';
        }
        if ($grupo == 'RM') {
            $grupo = 'MR';
        }
        if ($grupo == 'TOMOGRAFIA') {
            $grupo = 'CT';
        }
        if ($grupo == 'DENSITOMETRIA') {
            $grupo = 'DX';
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
        $data['identificador_id'] = $exame[0]->guia_id;
        $data['pedido_id'] = $exame[0]->guia_id;
        $data['solicitante'] = $exame[0]->convenio;
        $data['referencia'] = "";
        $data['paciente_id'] = $exame[0]->paciente_id;
        $data['paciente'] = $exame[0]->paciente;
        $data['nascimento'] = str_replace("-", "", $exame[0]->nascimento);
        $data['sexo'] = $exame[0]->sexo;
        $data['exame_id'] = $laudo_id;
        $this->exame->gravardicom($data);
    }

    function pagartodosprocedimentos() {


        $teste = $this->exame->pagartodosprocedimentos();

        if ($teste == "-1") {
            $data['mensagem'] = 'Erro ao pagar Procedimentos. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao pagar Procedimentos.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);


        redirect(base_url() . "ambulatorio/exame/faturamentoexame", $data);
    }

    function acoesagendamento($agenda_exames_id) {


        $data['lista'] = $this->exame->listaracoesagendamento($agenda_exames_id);

        $this->load->View('ambulatorio/acoesagendamento-lista', $data);
    }

    function alterarsalalaudo($ambulatorio_laudo_id = NULL, $exame_id = NULL, $paciente_id = NULL, $procedimento_tuss_id = NULL, $sala_id = NULL) {


        $data['sala_id'] = $sala_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['exame_id'] = $exame_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['lista'] = $this->exame->listarsalas();
        $this->load->View('ambulatorio/alterarsalalaudo-form', $data);
    }

    function gerarCorpoBPA(
    $padarao, $cnes, $data_realizacao, $cbo_ocupacao_id, $prd_seq, $ambulatorio_guia_id, $nascimento, $prd_qt, $prd_fim) {

        if ($data_realizacao == "") {
            $data_realizacao2 = "";
        } else {
            $partes = explode("-", $data_realizacao);
            $ano = $partes[0];
            $mes = $partes[1];
            $data_realizacao2 = $ano . $mes;
        }

        $dataFuturo2 = date("Y-m-d");  //ISSO 
        $dataAtual2 = $nascimento; // TUDO
        $date_time2 = new DateTime($dataAtual2); // CALCULA 
        $diff2 = $date_time2->diff(new DateTime($dataFuturo2)); //A
        $idade = $diff2->format('%Y'); // IDADE
//        $prd_seq++;        
        $prd_org = "BPA";
        $prd_fim = "";
        $prd_flh = "";
        $camp_prd_ident = $this->utilitario->preencherEsquerda($padarao, 2, "0");
        $camp_prd_cnes = $this->utilitario->preencherEsquerda($cnes, 7, "0");
        $camp_prd_cmp = $this->utilitario->preencherEsquerda($data_realizacao2, 6, " ");
        $camp_prd_cbo = $this->utilitario->preencherEsquerda($cbo_ocupacao_id, 6, " ");
        $camp_prd_flh = $this->utilitario->preencherEsquerda($prd_flh, 3, "0");
        $camp_prd_seq = $this->utilitario->preencherEsquerda($prd_seq, 2, "0");
        $camp_prd_pa = $this->utilitario->preencherEsquerda($ambulatorio_guia_id, 10, "0");
        $camp_prd_idade = $this->utilitario->preencherEsquerda($idade, 3, "0");
        $camp_prd_qt = $this->utilitario->preencherEsquerda($prd_qt, 6, "0");
        $camp_org = $this->utilitario->preencherDireita($prd_org, 3, " ");
        $camp_fim = $this->utilitario->preencherDireita($prd_fim, 2, " ");
        return $camp_prd_ident . $camp_prd_cnes . $camp_prd_cmp . $camp_prd_cbo . $camp_prd_flh . $camp_prd_seq .
                $camp_prd_pa . $camp_prd_idade . $camp_prd_qt . $camp_org .
                $camp_fim;
    }

    function gerarCabecalhobpa($totalregistros, $q_controle, $nome) {

        $data = date('Ym');
        $tipo_registro = "01";
        $nome_empresa_sigla = "";
        $cpf_empresa = "";
        $orgao_saude_empresa = "";
        $contador_pagina = "";
        $orgao_indicador_destinatario = "M";
//                     *(PREENCHER DIREITA) É UMA FUNCAO QUE VC PASSA O VALOR, DIZ QUANTOS CARACTERES VAI PEGAR DESSE VALOR 
//                         E O QUE QUER PREENCHER A DIREITA CASO NÃO TENHA O NUMERO DE CARACTERES SUFICIENTE.
//                     *(PREENCHER ESQUERDA) TEM A MESMA LOGICA, SÓ QUE VAI PRA ESQUEDA. É USADO MUITO QUANTO SE TRATA DE NUMEROS.
        $BPA1 = $this->utilitario->preencherDireita("01", 2, " ");
        $BPA = $this->utilitario->preencherDireita("#BPA#", 5, " ");
        $ano_mes = $this->utilitario->preencherEsquerda($data, 6, " ");
        $total = $this->utilitario->preencherEsquerda($totalregistros, 6, "0");
        $q_paginas = $this->utilitario->preencherEsquerda($contador_pagina, 6, "0");
        $cbc_smt_vrf = $this->utilitario->preencherEsquerda($q_controle, 4, "0");
        $camp_cbc_rsp = $this->utilitario->preencherDireita($nome, 30, " ");
        $camp_sigla_empresa = $this->utilitario->preencherDireita($nome_empresa_sigla, 6, " ");
        $camp_cpf_empresa = $this->utilitario->preencherEsquerda($cpf_empresa, 14, "0");
        $camp_orgao_saude = $this->utilitario->preencherDireita($orgao_saude_empresa, 40, " ");
        $camp_indi_orgao = $this->utilitario->preencherDireita($orgao_indicador_destinatario, 1, " ");
        $camp_versao_sis = $this->utilitario->preencherDireita("", 10, " ");
        $camp_fim_cabecalho = $this->utilitario->preencherDireita("", 2, " ");


        return $BPA1 . $BPA . $ano_mes . $total . $q_paginas . $cbc_smt_vrf .
                $camp_cbc_rsp . $camp_sigla_empresa . $camp_cpf_empresa . $camp_orgao_saude .
                $camp_indi_orgao . $camp_versao_sis . $camp_fim_cabecalho;
    }

    function gerarCorpoBPAINDIVI(
    $prd_seq, $prd_ident, $cnes_i, $data_realizacao_i, $cbo_ocupacao_id_i, $data_atendimento_i, $ambulatorio_guia_id_i, $sexo_i, $codigo_ibge_i, $nascimento_i, $paciente_i, $raca_cor_i, $cnpjempresa_i, $ceppaciente_i, $endereco_i, $complemento_i, $numero_i, $bairro_i, $telefone_i, $email_i
    ) {


        $nascimento = str_replace('-', "", $nascimento_i);
        $ceppaciente = str_replace("-", "", $ceppaciente_i);
        $telefone = str_replace(" ", "", $telefone_i);
        if ($data_realizacao_i == "") {
            $data_realizacao = "";
        } else {
            $partes = explode("-", $data_realizacao_i);
            $ano = $partes[0];
            $mes = $partes[1];
            $data_realizacao = $ano . $mes;
        }
        if ($data_atendimento_i == "") {
            $data_atendimento = "";
        } else {
            $partes = explode("-", $data_atendimento_i);
            $ano = $partes[0];
            $mes = $partes[1];
            $dia = $partes[2];
            $data_atendimento = $ano . $mes . $dia;
        }

        $dataFuturo2 = date("Y-m-d");  // ISSO 
        $dataAtual2 = $nascimento_i; // TUDO
        $date_time2 = new DateTime($dataAtual2); // CALCULA 
        $diff2 = $date_time2->diff(new DateTime($dataFuturo2)); //A
        $idade = $diff2->format('%Y'); // IDADE
        $prd_cnsmed = "";
//        $prd_seq = "";
        $prd_cnspac = "";
        $prd_cid = "";
        $prd_qt = "";
        $prd_caten = "";
        $prd_naut = "";
        $prd_org = "BPA";
        $prd_etnia = "";
        $prd_nac = "";
        $prd_srv = "";
        $prd_clf = "";
        $equipe_Seq = "";
        $equipe_Area = "";
        $prd_ine = "";
        $prd_flh = "";
        $lograd_pcnte = "";

        if ($raca_cor_i == "0" || $raca_cor_i == "") {
            $prd_raca = "99";
        } elseif ($raca_cor_i == "1") {
            $prd_raca = "01";
        } elseif ($raca_cor_i == "3") {
            $prd_raca = "02";
        } elseif ($raca_cor_i == "4") {
            $prd_raca = "03";
        } elseif ($raca_cor_i == "2") {
            $prd_raca = "04";
        } elseif ($raca_cor_i == "5") {
            $prd_raca = "05";
        } else {
            $prd_raca = "99";
        }
        $camp_prd_ident = $this->utilitario->preencherEsquerda($prd_ident, 2, "0");
        $camp_prd_cnes = $this->utilitario->preencherEsquerda($cnes_i, 7, "0");
        $camp_prd_cmp = $this->utilitario->preencherEsquerda($data_realizacao, 6, " ");
        if ($prd_cnsmed == "") {
            $camp_prd_cnsmed = $this->utilitario->preencherEsquerda($prd_cnsmed, 15, " ");
        } else {
            $camp_prd_cnsmed = $this->utilitario->preencherEsquerda($prd_cnsmed, 15, "0");
        }
        if ($cbo_ocupacao_id_i == "") {
            $camp_prd_cbo = $this->utilitario->preencherEsquerda("", 6, " ");
        } else {
            $camp_prd_cbo = $this->utilitario->preencherEsquerda($cbo_ocupacao_id_i, 6, "0");
        }
        $camp_data_atendimento = $this->utilitario->preencherEsquerda($data_atendimento, 8, "0");
        $camp_prd_flh = $this->utilitario->preencherEsquerda($prd_flh, 3, "0");
        $camp_prd_seq = $this->utilitario->preencherEsquerda($prd_seq, 2, "0");
        $camp_prd_pa = $this->utilitario->preencherEsquerda($ambulatorio_guia_id_i, 10, "0");
        if ($prd_cnspac == "") {
            $camp_prd_cnspac = $this->utilitario->preencherEsquerda($prd_cnspac, 15, " ");
        } else {
            $camp_prd_cnspac = $this->utilitario->preencherEsquerda($prd_cnspac, 15, "0");
        }
        $camp_prd_sexo = $this->utilitario->preencherDireita($sexo_i, 1, "0");
        if ($codigo_ibge_i == "") {
            $camp_prd_ibge = $this->utilitario->preencherDireita($codigo_ibge_i, 6, " ");
        } else {
            $camp_prd_ibge = $this->utilitario->preencherDireita($codigo_ibge_i, 6, "0");
        }
        $camp_prd_cid = $this->utilitario->preencherDireita($prd_cid, 4, " ");
        $camp_prd_idade = $this->utilitario->preencherEsquerda($idade, 3, " ");

        $camp_prd_qt = $this->utilitario->preencherEsquerda($prd_qt, 6, "0");
        if ($prd_caten == "") {
            $camp_prd_caten = $this->utilitario->preencherEsquerda($prd_caten, 2, " ");
        } else {
            $camp_prd_caten = $this->utilitario->preencherEsquerda($prd_caten, 2, "0");
        }
        if ($prd_naut == "") {
            $camp_prd_naut = $this->utilitario->preencherEsquerda($prd_naut, 13, " ");
        } else {
            $camp_prd_naut = $this->utilitario->preencherEsquerda($prd_naut, 13, "0");
        }
        $camp_prd_org = $this->utilitario->preencherEsquerda($prd_org, 3, " ");
        $camp_prd_nmpac = $this->utilitario->preencherDireita($paciente_i, 30, " ");
        $camp_prd_dtnasc = $this->utilitario->preencherDireita($nascimento, 8, " ");
        $camp_prd_raca = $this->utilitario->preencherEsquerda($prd_raca, 2, "0");
        $camp_prd_etnia = $this->utilitario->preencherEsquerda($prd_etnia, 4, " ");
        if ($prd_nac == "") {
            $camp_prd_nac = $this->utilitario->preencherEsquerda($prd_nac, 3, " ");
        } else {
            $camp_prd_nac = $this->utilitario->preencherEsquerda($prd_nac, 3, "0");
        }
        if ($prd_srv == "") {
            $camp_prd_srv = $this->utilitario->preencherEsquerda($prd_srv, 3, " ");
        } else {
            $camp_prd_srv = $this->utilitario->preencherEsquerda($prd_srv, 3, "0");
        }
        if ($prd_clf == "") {
            $camp_prd_clf = $this->utilitario->preencherEsquerda($prd_clf, 3, " ");
        } else {
            $camp_prd_clf = $this->utilitario->preencherEsquerda($prd_clf, 3, "0");
        }
        if ($equipe_Seq == "") {
            $camp_equipe_Seq = $this->utilitario->preencherEsquerda($equipe_Seq, 8, " ");
        } else {
            $camp_equipe_Seq = $this->utilitario->preencherEsquerda($equipe_Seq, 8, "0");
        }
        if ($equipe_Area == "") {
            $camp_equipe_Area = $this->utilitario->preencherEsquerda($equipe_Area, 4, " ");
        } else {
            $camp_equipe_Area = $this->utilitario->preencherEsquerda($equipe_Area, 4, "0");
        }
        if ($cnpjempresa_i == "") {
            $camp_prd_cnpj = $this->utilitario->preencherEsquerda($cnpjempresa_i, 14, " ");
        } else {
            $camp_prd_cnpj = $this->utilitario->preencherEsquerda($cnpjempresa_i, 14, "0");
        }
        if ($ceppaciente == "") {
            $camp_prd_cep_pcnte = $this->utilitario->preencherEsquerda($ceppaciente, 8, " ");
        } else {
            $camp_prd_cep_pcnte = $this->utilitario->preencherEsquerda($ceppaciente, 8, "0");
        }
        if ($endereco_i == "") {
            $camp_prd_end_pcnte = $this->utilitario->preencherDireita("", 30, " ");
        } else {
            $camp_prd_end_pcnte = $this->utilitario->preencherDireita($endereco_i, 30, " ");
        }
        if ($complemento_i == "") {
            $camp_prd_compl_pcnte = $this->utilitario->preencherDireita("", 30, " ");
        } else {
            $camp_prd_compl_pcnte = $this->utilitario->preencherDireita($complemento_i, 30, " ");
        }
        $camp_prd_num_pcnte = $this->utilitario->preencherDireita($numero_i, 5, " ");
        $camp_prd_bairro_pcnte = $this->utilitario->preencherDireita($bairro_i, 30, " ");
        $camp_prd_ddtel_pcnte = $this->utilitario->preencherDireita($telefone, 11, " ");
        if ($email_i == "") {
            $camp_prd_email_pcnte = $this->utilitario->preencherDireita($email_i, 40, " ");
        } else {
            $camp_prd_email_pcnte = $this->utilitario->preencherDireita($email_i, 40, "0");
        }
        if ($prd_ine == "") {
            $camp_prd_ine = $this->utilitario->preencherDireita($prd_ine, 10, " ");
        } else {
            $camp_prd_ine = $this->utilitario->preencherEsquerda($prd_ine, 10, "0");
        }



        return $camp_prd_ident . $camp_prd_cnes . $camp_prd_cmp . $camp_prd_cnsmed . $camp_prd_cbo . $camp_data_atendimento .
                $camp_prd_flh . $camp_prd_seq . $camp_prd_pa . $camp_prd_cnspac .
                $camp_prd_sexo . $camp_prd_ibge . $camp_prd_cid . $camp_prd_idade
                . $camp_prd_qt . $camp_prd_caten . $camp_prd_naut . $camp_prd_org
                . $camp_prd_nmpac . $camp_prd_dtnasc . $camp_prd_raca . $camp_prd_etnia
                . $camp_prd_nac . $camp_prd_srv . $camp_prd_clf . $camp_equipe_Area
                . $camp_prd_cnpj . $camp_prd_cep_pcnte . $lograd_pcnte . $camp_prd_end_pcnte . $camp_prd_compl_pcnte
                . $camp_prd_num_pcnte . $camp_prd_bairro_pcnte . $camp_prd_ddtel_pcnte
                . $camp_prd_email_pcnte . $camp_prd_ine;
    }

    function faturamentobpa($args = array()) {

        $this->loadView('ambulatorio/faturamentobpa-form', $args);
    }

    function gerarbpa() {
        $this->db->select('');
        $this->db->from('tb_empresa_permissoes');
        $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
        $permissao = $this->db->get()->result();


        $rs['listabpa'] = $this->exame->gerarbpa();
        $rs['dadosempresa'] = $this->exame->listardadosempresa();
        $rsempresatxt = $this->exame->listardadosempresa();
        $rstxt = $this->exame->gerarbpa();
        $registros_total = count($rstxt);
        $somar = 0;
                    
//UMA PARA TODOS calculando o controle que fica no cabeçalho de acordo com o arquivo pdf
        if (count($rstxt) > 0) {
            foreach ($rstxt as $item) {
                @$somar = $somar + $item->procedimento_tuss_id;
                @$resto = $somar % 111;
                @$q_controle = $resto + 111;
            }
        } else {
            $q_controle = "";
        }




        $prd_ident = 1;
        $prd_seq = 0;
        $prd_ident1 = 2;

        $data['datainicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['datainicio'])));
        $data['datafim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['datafim'])));
                    
        $mes_inicial = date('m', strtotime($data['datainicio']));

        $mes_final = date('m', strtotime($data['datafim']));


        for ($i = $mes_inicial; $i <= $mes_final; $i++) {
            switch ($i) {
                case "01": $mes = "JAN";
                    break;
                case "02": $mes = "FEV";
                    break;
                case "03": $mes = "MAR";
                    break;
                case "04": $mes = "ABR";
                    break;
                case "05": $mes = "MEIO";
                    break;
                case "06": $mes = "JUN";
                    break;
                case "07": $mes = "JUL";
                    break;
                case "08": $mes = "AGO";
                    break;
                case "09": $mes = "SET";
                    break;
                case "10": $mes = "OUT";
                    break;
                case "11": $mes = "NOV";
                    break;
                case "12": $mes = "DEZ";
                    break;
            }

            @$extensao = $mes;
        }


//       criando a pasta bpa caso não exista

        $origem_raiz = "./upload/bpa/";
        if (!is_dir("./upload/bpa/")) {
            mkdir("./upload/bpa/");
            chmod($origem_raiz, 0777);
        }

//        criando a pasta consolidado caso não exista
        $origem_con = "./upload/bpa/consolidado/";
        if (!is_dir("./upload/bpa/consolidado/")) {
            mkdir("./upload/bpa/consolidado/");
            chmod($origem_con, 0777);
        }

//        criando a pasata individualizada caso não exista
        $origem_ind = "./upload/bpa/individualizada/";
        if (!is_dir("./upload/bpa/individualizada/")) {
            mkdir("./upload/bpa/individualizada/");
            chmod($origem_ind, 0777);
        }
//delete_files serve para a apagar todos os arquivos da pasta selecionada
        if (@$_POST['bpa'] == "bpa-c") {
            if ($_POST['apagar'] == 1) {
                delete_files($origem_con);
            }
        } else {
            if ($_POST['apagar'] == 1) {
                delete_files($origem_ind);
            }
        }


        if (@$_POST['paciente'] != "") {

            @$nomepaciente = $_POST['paciente'];
        } else {



            $mes_i = date('m', strtotime($data['datainicio']));

            $mes_f = date('m', strtotime($data['datafim']));
            $nomepadrao = '';
            for ($i = $mes_i; $i <= $mes_f; $i++) {
                switch ($i) {
                    case "01": $mes = "JAN";
                        break;
                    case "02": $mes = "FEV";
                        break;
                    case "03": $mes = "MAR";
                        break;
                    case "04": $mes = "ABR";
                        break;
                    case "05": $mes = "MEIO";
                        break;
                    case "06": $mes = "JUN";
                        break;
                    case "07": $mes = "JUL";
                        break;
                    case "08": $mes = "AGO";
                        break;
                    case "09": $mes = "SET";
                        break;
                    case "10": $mes = "OUT";
                        break;
                    case "11": $mes = "NOV";
                        break;
                    case "12": $mes = "DEZ";
                        break;
                }

                @$nomepadrao .= "-" . $mes;
            }


            @$nomepaciente = "PAFUN--" . $nomepadrao;
        }


        if ($_POST['bpa'] == "bpa-c") {
//PARA GERAR O BPA CONSOLIDADO
////             GERANDO ARQUIVO .TXT DO CONSOLIADO
//            $nomearquivo = "relatoriotext" . "" . $nomepaciente . date("d-m-Y") . date("H:i:s");
//            foreach ($rsempresatxt as $item) {
//                $contador_pagina = "";
//                $cabecalho = $this->gerarCabecalhobpa(
//                        $registros_total, $q_controle, $item->nome
//                );
//            }
////            isso serve para mandar os parametros para a funcao gerarCorpoBPA, que retorna o 'Texto'
//            foreach ($rstxt as $item) {
//                @$prd_ident++;
//                $padarao = "02";
//                @$prd_flh = "";
//                @$prd_org = "BPA";
//                @$prd_fim = "";
//                $prd_seq++;
//                if ($prd_seq == 21) {
//                    $prd_seq = 1;
//                }
//                @$lancamento[] = $this->gerarCorpoBPA(
//                        $padarao, $item->cnes, $item->data_realizacao, $item->cbo_ocupacao_id, $prd_seq, $item->codigo, $item->nascimento, $item->quantidade, $prd_fim
//                );
//            }
//            $nome = "./upload/bpa/consolidado/" . $nomearquivo . ".txt";
//            $fp = fopen($nome, "w+");
//            fwrite($fp, $cabecalho . "\n");
//            if (count(@$lancamento) > 0) {
//                foreach ($lancamento as $value) {
//                    fwrite($fp, $value . "\n");
//                }
////              fwrite($fp, $fim . "\n");        
//            }
//            fclose($fp);
//            $zip = new ZipArchive;
//            $this->load->helper('directory');
//            $arquivo_pasta = directory_map("./upload/bpa/consolidado/");
//            if ($arquivo_pasta != false) {
//                foreach ($arquivo_pasta as $value) {
//                    $deletar[] = "./upload/bpa/consolidado/$value"; //pega todos os arquivos para depois deletar
//                }
//                foreach ($arquivo_pasta as $value) {
//                    $zip->open("./upload/bpa/consolidado/$value.zip", ZipArchive::CREATE);
//                    $zip->addFile("./upload/bpa/consolidado/$value", "$value"); // fazendo com que todos os arquivos da pasta vire .zip, até os proprios zipados
//                }
//                $zip->close();
//                foreach ($deletar as $arquivonome) {
//                    unlink($arquivonome);
//                }
//            }
////FIM DA GERAÇÃO DO .TXT
            if ($_POST['gerararq'] == "gerarpdfs") {
                $this->load->plugin('mpdf');
                $texto = $this->load->View('ambulatorio/impressaobpaconsolidado', $rs, true);
                $cabecalhopdf = '';
                $rodapepdf = '';
                $nomepdf = $nomepaciente . ".pdf";
//                echo "<pre>";
//        print_r($rstxt); 

                downloadpdf($texto, $nomepdf, $cabecalhopdf, $rodapepdf);

                redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
            } elseif ($_POST['gerararq'] == "arqtexto") {

                //             GERANDO ARQUIVO .TXT DO CONSOLIADO
                $nomearquivo = $nomepaciente;
                foreach ($rsempresatxt as $item) {
                    $contador_pagina = "";
                    $cabecalho = $this->gerarCabecalhobpa(
                            $registros_total, $q_controle, $item->nome
                    );
                }
//            isso serve para mandar os parametros para a funcao gerarCorpoBPA, que retorna o 'Texto'
                foreach ($rstxt as $item) {
                    @$prd_ident++;
                    $padarao = "02";
                    @$prd_flh = "";
                    @$prd_org = "BPA";
                    @$prd_fim = "";
                    $prd_seq++;
                    if ($prd_seq == 21) {
                        $prd_seq = 1;
                    }
                    @$lancamento[] = $this->gerarCorpoBPA(
                            $padarao, $item->cnes, $item->data_realizacao, $item->cbo_ocupacao_id, $prd_seq, $item->codigo, $item->nascimento, $item->quantidade, $prd_fim
                    );
                }
                $nome = "./upload/bpa/consolidado/" . $nomearquivo . "." . $extensao;
                $fp = fopen($nome, "w+");
                fwrite($fp, $cabecalho . "\n");
                if (count(@$lancamento) > 0) {
                    foreach ($lancamento as $value) {
                        fwrite($fp, $value . "\n");
                    }
//              fwrite($fp, $fim . "\n");        
                }
                fclose($fp);
                $zip = new ZipArchive;
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/bpa/consolidado/");
                if ($arquivo_pasta != false) {
                    foreach ($arquivo_pasta as $value) {
                        $deletar[] = "./upload/bpa/consolidado/$value"; //pega todos os arquivos para depois deletar
                    }
                    foreach ($arquivo_pasta as $value) {
                        $zip->open("./upload/bpa/consolidado/$value.zip", ZipArchive::CREATE);
                        $zip->addFile("./upload/bpa/consolidado/$value", "$value"); // fazendo com que todos os arquivos da pasta vire .zip, até os proprios zipados
                    }
                    $zip->close();
                    foreach ($deletar as $arquivonome) {
                        unlink($arquivonome);
                    }
                }
//FIM DA GERAÇÃO DO .TXT
                redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
            } else {
                $html = $this->load->View('ambulatorio/impressaobpaconsolidado', $rs, true);
                $filename = $nomepaciente;
                // Configurações header para forçar o download
                header("Content-type: application/x-msexcel; charset=utf-8");
                header("Content-Disposition: attachment; filename=\"{$filename}\"");
                header("Content-Description: PHP Generated Data");
                // Envia o conteúdo do arquivo
                echo $html;
                exit;
            }


//            FIM CONSOLIDADO      
        } else {


            if ($permissao[0]->tabela_bpa == 't') {

// /////////////////////////////////////////////////////////////////////                       
// /////////////////////////////Criando arquivo de texto                       
                $nomearquivo = "" . "" . $nomepaciente;
                foreach ($rsempresatxt as $item) {
                    $contador_pagina = "";
                    $cabecalho = $this->gerarCabecalhobpa(
                            $registros_total, $q_controle, $item->nome
                    );
                }


                $teste = "";
//                  echo "<pre>";
//                print_r($rstxt);
//                die;

                foreach ($rstxt as $item) {
//                    @$prd_seq++;
                    @$contprd_seq{$item->medico} ++;
                    $padrao_03 = "03";
                    $padrao_230440 = "230440";
                    $padrao_4posicao = "";
                    $padrao_000001 = "000001";
                    $padrao_01 = "01";
                    $padrao_13 = "";
                    $padrao_BPA = "BPA";
                    $padrao_6posica = "";
                    $padrao_010 = "010";
                    $padrao_26posicao = "";
                    $padrao_81 = "81";                    
                    @$numero_medico{$item->medico} ++;
                   
                    if ($numero_medico{$item->medico} == 1 || $contprd_seq{$item->medico} == 100) {
                        @$numero_medicoM{$item->medico}++;
                        @$numero_por_medico = $numero_medicoM{$item->medico};
                    }
                    
                    if ($contprd_seq{$item->medico} == 100) {
                        $contprd_seq{$item->medico} = 1;
                    }  
                    $prd_seq2 = $contprd_seq{$item->medico};

                    @$lancamento2[] = $this->gerarCorpoBPAINDIVImodelo2($padrao_03, $item->cod_cnes, $item->data_atendimento, $item->cod_cnes_prof, $item->cbo_ocupacao_id, $item->data_atendimento, $numero_por_medico, $prd_seq2, $item->codigo, $item->convenionumero, $item->sexo, $padrao_230440, $padrao_4posicao, $item->nascimento, $padrao_000001, $padrao_01, $padrao_13, $padrao_BPA, $item->paciente, $item->nascimento, $padrao_6posica, $padrao_010, $item->cod_servico, $item->cod_classificacao
                            , $padrao_26posicao, $item->ceppaciente, $padrao_81, $item->logradouro, $item->complemento, $item->numero, $item->bairro, $item->telefone, $item->celular
                    );
                }
                

                if ($_POST['gerararq'] == "gerarpdfs") {

                    foreach ($rsempresatxt as $item) {
                        $contador_pagina = "";
                        $cabecalho = $this->gerarCabecalhobpa(
                                $registros_total, $q_controle, $item->nome
                        );
                    }

                    if (count(@$lancamento2) > 0) {
                        foreach ($lancamento2 as $value) {
                            $texto .= $value . "<br>";
                        }
                    }


                    $this->load->plugin('mpdf');
                    $cabecalhopdf = '';
                    $rodapepdf = '';
                    $nomepdf = $nomepaciente . ".pdf";

                    downloadpdf($texto, $nomepdf, $cabecalho, $rodapepdf);
                } elseif ($_POST['gerararq'] == "arqtexto") {



                    $nome = "./upload/bpa/individualizada/" . $nomearquivo . "." . $extensao;
                    $fp = fopen($nome, "w+");
                    fwrite($fp, $cabecalho . "\n");
                    if (count(@$lancamento2) > 0) {
                        foreach ($lancamento2 as $value) {
                            fwrite($fp, $value . "\n");
                        }
                    }
                    fclose($fp);
                    $zip = new ZipArchive;
                    $this->load->helper('directory');
                    $arquivo_pasta = directory_map("./upload/bpa/individualizada/");
                    if ($arquivo_pasta != false) {
                        foreach ($arquivo_pasta as $value) {
                            $deletar[] = "./upload/bpa/individualizada/$value";
                        }
                        foreach ($arquivo_pasta as $value) {
                            $zip->open("./upload/bpa/individualizada/$value.zip", ZipArchive::CREATE);
                            $zip->addFile("./upload/bpa/individualizada/$value", "$value");
                        }
                        $zip->close();
                        foreach ($deletar as $arquivonome) {
                            unlink($arquivonome);
                        }
                    }
                } else {

                    foreach ($rsempresatxt as $item) {
                        $contador_pagina = "";
                        $cabecalho = $this->gerarCabecalhobpa(
                                $registros_total, $q_controle, $item->nome
                        );
                    }

                    if (count(@$lancamento2) > 0) {
                        foreach ($lancamento2 as $value) {
                            @$html .= $value . "<br>";
                        }
                    }

                    $filename = $nomepaciente;
                    // Configurações header para forçar o download
                    header("Content-type: application/x-msexcel; charset=utf-8");
                    header("Content-Disposition: attachment; filename=\"{$filename}\"");
                    header("Content-Description: PHP Generated Data");
                    // Envia o conteúdo do arquivo
                    echo $cabecalho . "<br>" . $html;
                    exit;
                }




                redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
            } else {


////FIM DA GERAÇÃO DO ARQUIVO TXT 


                if ($_POST['gerararq'] == "gerarpdfs") {
                    $this->load->plugin('mpdf');
                    $texto = $this->load->View('ambulatorio/impressaobpaindividualizada', $rs, true);
                    $cabecalhopdf = '';
                    $rodapepdf = '';
                    $nomepdf = $nomepaciente . ".pdf";

                    downloadpdf($texto, $nomepdf, $cabecalhopdf, $rodapepdf);
                } elseif ($_POST['gerararq'] == "arqtexto") {
//      GERANDO AQUIVO TXT DO INDIVIDUALIZADA
                    $nomearquivo = "" . "" . $nomepaciente;
                    foreach ($rsempresatxt as $item) {
                        $contador_pagina = "";

                        $cabecalho = $this->gerarCabecalhobpa(
                                $registros_total, $q_controle, $item->nome
                        );
                    }
                    foreach ($rstxt as $item) {
                        @$prd_seq++;
                        $prd_ident1 = 03;
                        @$prd_flh = "";

                        @$prd_qt = "";
                        @$prd_org = "";
                        @$prd_fim = "";
                        if ($prd_seq == 21) {
                            $prd_seq = 1;
                        }
                        @$lancamento2[] = $this->gerarCorpoBPAINDIVI(
                                $prd_seq, $prd_ident1, $item->cnes, $item->data_realizacao, $item->cbo_ocupacao_id, $item->data_atendimento, $item->codigo, $item->sexo, $item->codigo_ibge, $item->nascimento, $item->paciente, $item->raca_cor, $item->cnpjempresa, $item->ceppaciente, $item->logradouro, $item->complemento, $item->numero, $item->bairro, $item->telefone, $item->email
                        );
                    }
                    $nome = "./upload/bpa/individualizada/" . $nomearquivo . "." . $extensao;
                    $fp = fopen($nome, "w+");

                    fwrite($fp, $cabecalho . "\n");

                    if (count(@$lancamento2) > 0) {


                        foreach ($lancamento2 as $value) {
                            fwrite($fp, $value . "\n");
                        }
//              fwrite($fp, $fim . "\n");
                    }
                    fclose($fp);
                    $zip = new ZipArchive;
                    $this->load->helper('directory');
                    $arquivo_pasta = directory_map("./upload/bpa/individualizada/");
                    if ($arquivo_pasta != false) {
                        foreach ($arquivo_pasta as $value) {
                            $deletar[] = "./upload/bpa/individualizada/$value";
                        }
                        foreach ($arquivo_pasta as $value) {
                            $zip->open("./upload/bpa/individualizada/$value.zip", ZipArchive::CREATE);
                            $zip->addFile("./upload/bpa/individualizada/$value", "$value");
                        }
                        $zip->close();
                        foreach ($deletar as $arquivonome) {
                            unlink($arquivonome);
                        }
                    }
                    redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
//FIM DA GERAÇÃO DO ARQUIVO TXT 
                } else {
                    $html = $this->load->View('ambulatorio/impressaobpaindividualizada', $rs, true);
                    $filename = $nomepaciente;
                    // Configurações header para forçar o download
                    header("Content-type: application/x-msexcel; charset=utf-8");
                    header("Content-Disposition: attachment; filename=\"{$filename}\"");
                    header("Content-Description: PHP Generated Data");
                    // Envia o conteúdo do arquivo
                    echo $html;
                    exit;
                }
//            FIM INDIVIDUALIZADA
            }
        }
    }

    function enviartodosparaatendimento() {

        $atendimento = $_POST['atendimentos'];
        foreach ($atendimento as $item) {

            $consulta_exames = $this->exame->listardadosexamesenviados($item);

            @$data['agenda_exames_nome_id'] = $this->exame->listarsalaagenda($consulta_exames[0]->agenda_exames_id);
            @$total = $this->exame->contadorexames($consulta_exames[0]->agenda_exames_id);
//               echo '<pre>';
//                print_r($data['agenda_exames_nome_id']);
//                die; 
            if ($total == 0) {

                $preparo = $this->guia->listarprocedimentopreparo($consulta_exames[0]->procedimento_tuss_id);

                $procedimentopercentual = $consulta_exames[0]->procedimento_tuss_id;
                $medicopercentual = $consulta_exames[0]->medico_id;
                $percentual = $this->guia->percentualmedicoconvenioexames($procedimentopercentual, $medicopercentual);
                if (count($percentual) == 0) {
                    $percentual = $this->guia->percentualmedicoprocedimento($procedimentopercentual, $medicopercentual);
                }
//
                $laudo_id = $this->exame->gravarexameenviatodos(
                        @$consulta_exames[0]->agenda_exames_id, @$consulta_exames[0]->paciente_id, @$consulta_exames[0]->medico_id, @$consulta_exames[0]->procedimento_tuss_id, @$consulta_exames[0]->guia_id, @$data['agenda_exames_nome_id']->tipo, @$consulta_exames[0]->exame_sala_id, @$data['agenda_exames_nome_id']->indicacao);



                if ($laudo_id == "-1") {
                    $data['mensagem'] = 'Erro ao gravar o Exame. Opera&ccedil;&atilde;o cancelada.';
                } else {

                    if ($preparo[0]->sala_preparo == 't') {
                        $this->exame->gravarsalapreparo($consulta_exames[0]->agenda_exames_id);
                        $data['mensagem'] = 'Sucesso ao enviar para a sala de preparo.';
                    } else {
                        $data['mensagem'] = 'Sucesso ao gravar o Exame.';
                    }
////                $this->gerarcr($agenda_exames_id); //clinica humana
                    //$this->gerardicom($laudo_id); //clinica ronaldo
                    $empresa_id = $this->session->userdata('empresa_id');
                    $empresa = $this->guia->listarempresa($empresa_id);



                    if ($empresa[0]->chamar_consulta == 't' && $data['agenda_exames_nome_id'][0]->indicacao == 'CONSULTA') {
                        $this->laudo->chamadaconsulta($laudo_id);
                    }
////                $this->laudo->chamada($laudo_id);
                }
            } else {
                $data['mensagem'] = 'Erro ao gravar o Exame. Exame ja cadastrato.';
            }
        }
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function examecancelamentogeral2($agenda_exames_id = NULL, $paciente_id = NULL, $procedimento_tuss_id = NULL, $encaixe = NULL) {
        $exames_id = null;
        $sala_id = null;
        $data['motivos'] = $this->motivocancelamento->listartodos();
        @$data['exames_id'] = @$exames_id;
        @$data['sala_id'] = @$sala_id;
        @$data['paciente_id'] = @$paciente_id;
        @$data['procedimento_tuss_id'] = @$procedimento_tuss_id;
        $data['agenda_exames_id'] = @$agenda_exames_id;
        @$data['encaixe'] = @$encaixe;
        $this->loadView('ambulatorio/examecancelamentogeral-form', $data);
    }

    function examecancelamentogeral3($exame_id = NULL, $agenda_exames_id = NULL, $paciente_id = NULL, $procedimento_tuss_id = NULL, $encaixe = NULL) {
        $exames_id = null;
        $sala_id = null;
        $data['motivos'] = $this->motivocancelamento->listartodos();
        @$data['exames_id'] = @$exames_id;
        @$data['sala_id'] = @$sala_id;
        @$data['paciente_id'] = @$paciente_id;
        @$data['procedimento_tuss_id'] = @$procedimento_tuss_id;
        $data['agenda_exames_id'] = @$agenda_exames_id;
        @$data['encaixe'] = @$encaixe;
        $this->loadView('ambulatorio/examecancelamentogeral-form', $data);
    }

    function gerarCorpoBPAINDIVImodelo2(
    $padrao_03, $cnes_i, $data_atendimento_i1, $cod_cnes_prof, $cbo_prof, $data_atendimento_i, $numero_por_medico, $prd_seq, $codigo_tuss_pro, $numero_carteira, $sexo_i, $padrao_230440, $padrao_4posicao, $nascimento_i, $padrao_000001, $padra_01, $padra_13posicao, $padra_BPA, $paciente_i, $nascimento_i1, $padrao_6posicao, $padrao_010, $cod_servico, $cod_classif, $padra_26posicao, $cep_paciente, $padra_81
    , $endereco, $complemento, $numero, $bairro, $telefone, $celular
    ) {



        $nascimento = str_replace('-', "", $nascimento_i1);
        $ceppaciente = str_replace("-", "", $ceppaciente_i);
        $telefone1 = str_replace("(", "", str_replace(")", "", $telefone));
        $telefone_u = str_replace(" ", "", $telefone1);

        $celular1 = str_replace("(", "", str_replace(")", "", $celular));
        $celular_u = str_replace(" ", "", $celular1);


        if ($telefone_u != "") {
            $telefone = $telefone_u;
        } elseif ($celular_u != "") {
            $telefone = $celular_u;
        } else {
            $telefone = "";
        }



        if ($data_atendimento_i1 == "") {
            $data_atendimento_i1 = "";
        } else {
            $partes = explode("-", $data_atendimento_i1);
            $ano = $partes[0];
            $mes = $partes[1];
            $data_atendimento_i1 = $ano . $mes;
        }



        if ($data_atendimento_i == "") {
            $data_atendimento = "";
        } else {

            $data_at = substr($data_atendimento_i, 0, 4) . substr($data_atendimento_i, 5, 2) . substr($data_atendimento_i, 8, 2);
        }

        $dataFuturo2 = date("Y-m-d");  // ISSO 
        $dataAtual2 = $nascimento_i; // TUDO
        $date_time2 = new DateTime($dataAtual2); // CALCULA 
        $diff2 = $date_time2->diff(new DateTime($dataFuturo2)); //A
        $idade = $diff2->format('%Y'); // IDADE



        if ($padrao_03 == "") {
            $padrao_03 = $this->utilitario->preencherDireita($padrao_03, 2, " ");
        } else {
            $padrao_03 = $this->utilitario->preencherDireita($padrao_03, 2, " ");
        }

        if ($cnes_i == "") {
            $cnes_i = $this->utilitario->preencherEsquerda($cnes_i, 7, " ");
        } else {
            $cnes_i = $this->utilitario->preencherEsquerda($cnes_i, 7, " ");
        }


        if ($data_atendimento_i1 == "") {
            $data_atendimento_i1 = $this->utilitario->preencherEsquerda($data_atendimento_i1, 6, " ");
        } else {
            $data_atendimento_i1 = $this->utilitario->preencherEsquerda($data_atendimento_i1, 6, " ");
        }



        if ($cod_cnes_prof == "") {
            $cod_cnes_prof = $this->utilitario->preencherEsquerda($cod_cnes_prof, 15, " ");
        } else {
            $cod_cnes_prof = $this->utilitario->preencherEsquerda($cod_cnes_prof, 15, " ");
        }



        if ($cbo_prof == "") {
            $cbo_prof = $this->utilitario->preencherEsquerda($cbo_prof, 6, " ");
        } else {
            $cbo_prof = $this->utilitario->preencherEsquerda($cbo_prof, 6, " ");
        }




        if ($data_at == "") {
            $data_at = $this->utilitario->preencherEsquerda($data_at, 8, " ");
        } else {
            $data_at = $this->utilitario->preencherEsquerda($data_at, 8, " ");
        }




        if ($numero_por_medico == "") {
            $numero_por_medico = $this->utilitario->preencherEsquerda($numero_por_medico, 3, "0");
        } else {
            $numero_por_medico = $this->utilitario->preencherEsquerda($numero_por_medico, 3, "0");
        }



        if ($prd_seq == "") {
            $prd_seq = $this->utilitario->preencherEsquerda($prd_seq, 2, "0");
        } else {
            $prd_seq = $this->utilitario->preencherEsquerda($prd_seq, 2, "0");
        }


        if ($codigo_tuss_pro == "") {
            $codigo_tuss_pro = $this->utilitario->preencherEsquerda($codigo_tuss_pro, 10, "0");
        } else {
            $codigo_tuss_pro = $this->utilitario->preencherEsquerda($codigo_tuss_pro, 10, "0");
        }


        if ($numero_carteira == "") {
            $numero_carteira = $this->utilitario->preencherEsquerda($numero_carteira, 15, " ");
        } else {
            $numero_carteira = $this->utilitario->preencherEsquerda($numero_carteira, 15, "0");
        }

        if ($sexo_i == "") {
            $sexo_i = $this->utilitario->preencherEsquerda($sexo_i, 1, " ");
        } else {
            $sexo_i = $this->utilitario->preencherDireita($sexo_i, 1, " ");
        }

        if ($padrao_230440 == "") {
            $padrao_230440 = $this->utilitario->preencherEsquerda($padrao_230440, 6, "0");
        } else {
            $padrao_230440 = $this->utilitario->preencherDireita($padrao_230440, 6, " ");
        }

        if ($padrao_4posicao == "") {
            $padrao_4posicao = $this->utilitario->preencherDireita($padrao_4posicao, 4, " ");
        } else {
            $padrao_4posicao = $this->utilitario->preencherDireita($padrao_4posicao, 4, " ");
        }


        if ($idade == "") {
            $idade = $this->utilitario->preencherEsquerda($idade, 3, "0");
        } else {
            $idade = $this->utilitario->preencherEsquerda($idade, 3, "0");
        }


        if ($padrao_000001 == "") {
            $padrao_000001 = $this->utilitario->preencherEsquerda($padrao_000001, 6, "0");
        } else {
            $padrao_000001 = $this->utilitario->preencherEsquerda($padrao_000001, 6, "0");
        }


        if ($padra_01 == "") {
            $padra_01 = $this->utilitario->preencherEsquerda($padra_01, 2, "0");
        } else {
            $padra_01 = $this->utilitario->preencherEsquerda($padra_01, 2, "0");
        }



        if ($padra_13posicao == "") {
            $padra_13posicao = $this->utilitario->preencherEsquerda($padra_13posicao, 13, " ");
        } else {
            $padra_13posicao = $this->utilitario->preencherEsquerda($padra_13posicao, 13, " ");
        }

        if ($padra_BPA == "") {
            $padra_BPA = $this->utilitario->preencherEsquerda($padra_BPA, 3, " ");
        } else {
            $padra_BPA = $this->utilitario->preencherEsquerda($padra_BPA, 3, " ");
        }

        if ($paciente_i == "") {
            $paciente_i = $this->utilitario->preencherDireita($paciente_i, 30, " ");
        } else {
            $paciente_i = $this->utilitario->preencherDireita($paciente_i, 30, " ");
        }

        if ($nascimento == "") {
            $nascimento = $this->utilitario->preencherDireita($nascimento, 8, " ");
        } else {
            $nascimento = $this->utilitario->preencherDireita($nascimento, 8, " ");
        }
        if ($padrao_6posicao == "") {
            $padrao_6posicao = "99";
            $padrao_6posicao = $this->utilitario->preencherDireita($padrao_6posicao, 6, " ");
        } else {
            $padrao_6posicao = "99";
            $padrao_6posicao = $this->utilitario->preencherDireita($padrao_6posicao, 6, " ");
        }



        if ($padrao_010 == "") {
            $padrao_010 = $this->utilitario->preencherDireita($padrao_010, 3, " ");
        } else {
            $padrao_010 = $this->utilitario->preencherDireita($padrao_010, 3, " ");
        }


        if ($cod_servico == "") {
            $cod_servico = $this->utilitario->preencherDireita($cod_servico, 3, " ");
        } else {
            $cod_servico = $this->utilitario->preencherDireita($cod_servico, 3, " ");
        }



        if ($cod_classif == "") {
            $cod_classif = $this->utilitario->preencherDireita($cod_classif, 3, " ");
        } else {
            $cod_classif = $this->utilitario->preencherDireita($cod_classif, 3, " ");
        }



        if ($padra_26posicao == "") {
            $padra_26posicao = $this->utilitario->preencherDireita($padra_26posicao, 26, " ");
        } else {
            $padra_26posicao = $this->utilitario->preencherDireita($padra_26posicao, 26, " ");
        }


        $cep_paciente = str_replace("-", "", $cep_paciente);

        if ($cep_paciente == "") {
            $cep_paciente = $this->utilitario->preencherDireita($cep_paciente, 8, " ");
        } else {
            $cep_paciente = $this->utilitario->preencherDireita($cep_paciente, 8, " ");
        }


        if ($padra_81 == "") {
            $padra_81 = $this->utilitario->preencherEsquerda($padra_81, 3, "0");
        } else {
            $padra_81 = $this->utilitario->preencherEsquerda($padra_81, 3, "0");
        }




        if ($endereco == "") {
            $endereco = $this->utilitario->preencherDireita($endereco, 30, " ");
        } else {
            $endereco = $this->utilitario->preencherDireita($endereco, 30, " ");
        }




        if ($complemento == "") {
            $complemento = $this->utilitario->preencherDireita($complemento, 10, " ");
        } else {
            $complemento = $this->utilitario->preencherDireita($complemento, 10, " ");
        }


        if ($numero == "") {
            $numero = $this->utilitario->preencherDireita($numero, 5, " ");
        } else {
            $numero = $this->utilitario->preencherDireita($numero, 5, " ");
        }

        if ($bairro == "") {
            $bairro = $this->utilitario->preencherDireita($bairro, 30, " ");
        } else {
            $bairro = $this->utilitario->preencherDireita($bairro, 30, " ");
        }

        if ($telefone == "") {
            $telefone = $this->utilitario->preencherDireita($telefone, 11, " ");
        } else {
            $telefone = $this->utilitario->preencherDireita($telefone, 11, " ");
        }


        return $padrao_03 . $cnes_i . $data_atendimento_i1 . $cod_cnes_prof . $cbo_prof . $data_at . $numero_por_medico . $prd_seq . $codigo_tuss_pro . $numero_carteira . $sexo_i . $padrao_230440 . $padrao_4posicao . $idade
                . $padrao_000001 . $padra_01 . $padra_13posicao . $padra_BPA . $paciente_i
                . $nascimento . $padrao_6posicao . $padrao_010 . $cod_servico . $cod_classif
                . $padra_26posicao . $cep_paciente . $padra_81 . $endereco . $complemento . $numero . $bairro . $telefone;
    }

    function dashboardfinanceiro() {
        $empresa_id = $this->session->userdata('empresa_id');
        $data_inicio_get = (@$_GET['data_inicio'] != '') ? @$_GET['data_inicio'] : date("01/m/Y");
        $data_fim_get = (@$_GET['data_fim'] != '') ? @$_GET['data_fim'] : date("t/m/Y");
        $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', $data_inicio_get)));
        $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', $data_fim_get)));
        $data['permissaoempresa'] = $this->exame->listartodaspermissoes($empresa_id);
//        echo "<pre>";
//        print_r($data['permissaoempresa']);
//        die;
        $data['convenios'] = $this->exame->listarconveniodashboard($data_inicio, $data_fim);
        $data['solicitantes'] = $this->exame->listarsolicitantesdashboard($data_inicio, $data_fim);
        $data['solicitantesExt'] = $this->exame->listarsolicitantesextdashboard($data_inicio, $data_fim);
        $data['mensal'] = $this->exame->listarmensaldashboard($data_inicio, $data_fim);
        $data['atendentes'] = $this->exame->listaratendentesdashboard($data_inicio, $data_fim);
        $data['grupos'] = $this->exame->listargruposdashboard($data_inicio, $data_fim);
        $data['subgruposAgen'] = $this->exame->listarsubgruposdashboard($data_inicio, $data_fim);
        $data['AgenConf'] = $this->exame->listarconfdashboard($data_inicio, $data_fim);
        $data['pacienteRec'] = $this->exame->listarconfpacientedashboard($data_inicio, $data_fim);
//        // $data['atrasos'] = $this->exame->listarconfatrasosdashboard($data_inicio, $data_fim);
        $data['paciente'] = $this->exame->listarpacientedashboard($data_inicio, $data_fim);
        $data['medicos'] = $this->exame->listarmedicodashboard($data_inicio, $data_fim);
        $data['indicacao'] = $this->exame->listarindicacaodashboard($data_inicio, $data_fim);
        $data['cancelamentos'] = $this->exame->listarcancelamentosdashboard($data_inicio, $data_fim);

        $data['totalconvenio'] = $this->exame->listartotalconveniodashboard($data_inicio, $data_fim);

        if ($data['permissaoempresa'][0]->faturamento_novo == "t") {
            $data['pagamento'] = $this->exame->listarformasdashboardnovo($data_inicio, $data_fim);
        } else {
            $data['pagamento'] = $this->exame->listarformasdashboard($data_inicio, $data_fim);
        }
        $data['empresas'] = $this->guia->listarempresas();
        // echo '<pre>';
        // print_r($data['mensal']);          
        // die; 

        $this->load->View('ambulatorio/dashboardfinanceiro', $data);
    }

    function dashboardrecepcao(){
        $empresa_id = $this->session->userdata('empresa_id');
        $data_inicio_get = (@$_GET['data_inicio'] != '') ? @$_GET['data_inicio'] : date("01/m/Y");
        $data_fim_get = (@$_GET['data_fim'] != '') ? @$_GET['data_fim'] : date("t/m/Y");
        $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', $data_inicio_get)));
        $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', $data_fim_get)));

        $data['permissaoempresa'] = $this->exame->listartodaspermissoes($empresa_id);
        $data['empresas'] = $this->guia->listarempresas();
        $data['pessoas'] = $this->exame->listarqtdepessoasporsexo($data_inicio, $data_fim);
        $data['faixaidade'] = $this->exame->listaridadepessoa($data_inicio, $data_fim);
        $data['atendimento'] = $this->exame->listarqtdeatendimento($data_inicio, $data_fim);
        $data['agendamento'] = $this->exame->listarqtdeagendamento($data_inicio, $data_fim);
        $data['agendamentopormedico'] = $this->exame->listarqtdeagendamentopormedico($data_inicio, $data_fim);
        $data['subgruposAgen'] = $this->exame->listarsubgruposdashboard($data_inicio, $data_fim);

        if(count($data['atendimento']) == 0){
            @$data['atendimento'][0]->particular = 0;
            @$data['atendimento'][0]->convenio = 0;
            @$data['atendimento'][0]->total_atendimento = 0;
        }
        // echo '<pre>';
        // print_r($data['agendamentopormedico']);          
        // die; 
        $this->load->View('ambulatorio/dashboardrecepcao', $data);
    }

    function gerarplanilhadashboard() {
        $data['valor'] = array();
        $data['nome_array'] = array();
        $data['titulo'] = '';
        if (isset($_POST)) {
            $data['valor'] = json_decode($_POST['valor_form']);
            $data['nome_array'] = json_decode($_POST['nome_form']);
            $data['titulo'] = $_POST['titulo_form'];
        }
        $data['total'] = 0;
        // var_dump($data['valor']); 
        $percentual = true;
        foreach ($data['valor'] as $value) {
            if ($value > 0 && is_float($value)) {
                $percentual = false;
            }
            $data['total'] += $value;
        }
        $data['percentualShow'] = $percentual;
        // var_dump($data['percentualShow']); 

        $html = $this->load->View('ambulatorio/dashboardtabelaexcel', $data, true);

        // var_dump($html);
        // die;
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/x-msexcel");
        header("Content-Disposition: attachment; filename=\"{$data['titulo']}.xls\"");
        header("Content-Description: PHP Generated Data");
        // Envia o conteúdo do arquivo
        echo $html;
        exit;
    }

    function dashboardpagamentoparcela() {
        $empresa_id = $this->session->userdata('empresa_id');
        $data['permissaoempresa'] = $this->exame->listartodaspermissoes($empresa_id);
        $data_inicio_get = (@$_GET['data_inicio'] != '') ? @$_GET['data_inicio'] : date("01/m/Y");
        $data_fim_get = (@$_GET['data_fim'] != '') ? @$_GET['data_fim'] : date("t/m/Y");
        $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', $data_inicio_get)));
        $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', $data_fim_get)));
        $forma = @$_GET['forma'];
        $data['forma'] = $forma;

        if ($data['permissaoempresa'][0]->faturamento_novo == "t") {
            $data['pagamento'] = $this->exame->listarformasparceladashboardnovo($data_inicio, $data_fim, $forma);
        } else {
            $data['pagamento'] = $this->exame->listarformasparceladashboard($data_inicio, $data_fim, $forma);
        }

        // echo '<pre>';
        // var_dump($data['pagamento']); die;
        $this->load->View('ambulatorio/dashboardpagamentoparcela', $data);
    }

    function dashboardgruposubgrupo() {
        $data_inicio_get = (@$_GET['data_inicio'] != '') ? @$_GET['data_inicio'] : date("01/m/Y");
        $data_fim_get = (@$_GET['data_fim'] != '') ? @$_GET['data_fim'] : date("t/m/Y");
        $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', $data_inicio_get)));
        $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', $data_fim_get)));
        $grupo = @$_GET['grupo'];
        $data['grupo'] = $grupo;
        $data['grupos'] = $this->exame->listarmedicogruposubgrupodashboard($data_inicio, $data_fim, $grupo);
        // echo '<pre>';
        // var_dump($data['grupos']); die; 
        $this->load->View('ambulatorio/dashboardgruposubgrupo', $data);
    }

    function examecancelamentoagendamentosadt($agenda_exames_id, $paciente_id, $tipo, $encaixe, $solicitacao_sadt_id, $solicitacao_sadt_procedimento_id) {
        $data['motivos'] = $this->motivocancelamento->listartodos();
        $data['paciente_id'] = $paciente_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['tipo'] = $tipo;
        $data['encaixe'] = $encaixe;
        $data['cancelandopelosadt'] = "cance@landopelosadt";
        $data['solicitacao_sadt_id'] = $solicitacao_sadt_id;
        $data['solicitacao_sadt_procedimento_id'] = @$solicitacao_sadt_procedimento_id;
        $this->loadView('ambulatorio/examecancelamentoagendamentosadt-form', $data);
    }
                 

    function excluirimportacoesmedicas() {                    
        $this->loadView('ambulatorio/excluirimportacoesmedicas');
    }
    
    function visualizarimportacao($procedimento_importacao_arquivo_id){
        $data['lista'] = $this->exame->listarprocedimentoimportadosarquivo($procedimento_importacao_arquivo_id);
        $data['procedimento_importacao_arquivo_id'] = $procedimento_importacao_arquivo_id;
        $this->load->View('ambulatorio/procedimentoimportados-lista', $data);   
    }

    function visualizarimportacaodupla($procedimento_importacao_arquivo_id){
        $data['lista'] = $this->exame->listarprocedimentoimportadosarquivo($procedimento_importacao_arquivo_id);
        $data['procedimento_importacao_arquivo_id'] = $procedimento_importacao_arquivo_id;
        $this->load->View('ambulatorio/procedimentoimportadosduplo-lista', $data);   
    }
    
    function excluirprocedimentoimportado(){  
        $procedimento = $_POST['procedimento'];
        if (count($_POST['procedimento']) > 0) {                    
            $this->exame->excluirprocedimentoimportado($procedimento);  
        }  
        $procedimento_importacao_arquivo_id = $_POST['procedimento_importacao_arquivo_id'];
        redirect(base_url()."ambulatorio/exame/visualizarimportacao/".$procedimento_importacao_arquivo_id);
    }

    function adicionarprocedimentoimportadoduplo(){  
        $procedimento = $_POST['procedimento'];
        $arquivo_id = $_POST['arquivo_id'];
        if (count($_POST['procedimento']) > 0) {                    
             $this->exame->adicionarprocedimentoimportadoduplo($procedimento, $arquivo_id);  
        }  
        $procedimento_importacao_arquivo_id = $_POST['procedimento_importacao_arquivo_id'];
        redirect(base_url()."ambulatorio/exame/visualizarimportacaodupla/".$procedimento_importacao_arquivo_id);
    }
                    
    function excluirprocedimentoimportadogeral($procedimento_importacao_arquivo_id){        
         $lista = $this->exame->listarprocedimentoimportadosarquivo($procedimento_importacao_arquivo_id);                    
         $procedimento = json_decode($lista[0]->arquivo);
         $this->exame->excluirprocedimentoimportado($procedimento);  
        $verificar =  $this->exame->excluirimportacao($procedimento_importacao_arquivo_id);            
        if ($verificar != '-1') {
            $mensagem = 'Procedimentos excluidos com sucesso';  
        }else{
            $mensagem = 'Erro ao excluir importação';     
        }
       
         $this->session->set_flashdata('message', $mensagem);
         redirect(base_url()."ambulatorio/exame/excluirimportacoesmedicas");         
    }
    
    function listarfilaaparelho($limite = 25) {
        $data["limite_paginacao"] = $limite;
        $this->loadView('ambulatorio/filaaparelhos-lista', $data);
    }
    
    
    function carregaraparelho($fila_aparelhos_id){
          $data['obj']  =  $this->exame->listaraparelho($fila_aparelhos_id);
          $this->loadView('ambulatorio/filaaparelhos-ficha',$data);
    }
    
    function gravaraparelho(){  
        $res =  $this->exame->gravaraparelho();
        if ($res != '-1') {
           $data['mensagem'] = 'Gravado com sucesso';
        }else{
           $data['mensagem'] = 'Erro ao gravar aparelho'; 
        } 
        $this->session->set_flashdata('message', $data['mensagem']);   
        redirect(base_url()."ambulatorio/exame/listarfilaaparelho",$data);         
        
    }
    
    function finalizaraparelho($aparelho_gasto_sala_id){ 
         if($this->exame->finalizaraparelho($aparelho_gasto_sala_id) != "-1"){
            $mensagem = 'Finalizado com sucesso';   
         }else{
            $mensagem = 'Erro ao finalizar aparelho';  
         }
        $this->session->set_flashdata('message', $mensagem);
        
       redirect(base_url() . "ambulatorio/exame/listaraparelhosassociados");
        
    }
    
    
    function trocarparelho($aparelho_gasto_sala_id,$fila_aparelhos_id){ 
        $data['lista'] = $this->exame->listaraparelhos($fila_aparelhos_id);
        $data['aparelho_gasto_sala_id'] = $aparelho_gasto_sala_id;
        $data['fila_aparelhos_id'] = $fila_aparelhos_id;
        $this->load->View('ambulatorio/trocaraparelho',$data);
    }
    
    
    function descricaoaparelho($fila_aparelhos_id){
       $data['obj']  =  $this->exame->listaraparelho($fila_aparelhos_id);
       $this->load->View('ambulatorio/descricaoaparelho',$data); 
    }             
    
    function alterarobsaparelho(){
        $this->exame->alterarobsaparelho();   
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao"); 
    }
    
    function historicoaparelho($fila_aparelhos_id){ 
       $data['historico']  =  $this->exame->listarhistoricoaparelho($fila_aparelhos_id);             
       $this->load->View('ambulatorio/historicoaparelho',$data);
    }
    
    
    function adicionarocorrencia($exames_id,$sala_id){ 
        $data['template'] = $this->paciente->listarocorrencia();   
        $data['exames_id'] = $exames_id;
        $data['sala_id'] = $sala_id;
        $this->load->View('ambulatorio/adicionarocorrencia-form',$data);
    }
    
    function gravarocorrencia(){
      $atendimento_ocorrencia_id =  $this->exame->gravarocorrencia();  
        
        $type = explode('.', $_FILES['userfile']['name']);
        $nome = str_replace(' ', '_', $_FILES['userfile']['name']);
        $nome_caminho = $nome . '.' . $type[1];  
                    
        if (!is_dir("./upload/ocorrencias")) {
            mkdir("./upload/ocorrencias");
            $destino = "./upload/ocorrencias";
            chmod($destino, 0777);
        }

        if (!is_dir("./upload/ocorrencias/$atendimento_ocorrencia_id")) {
            mkdir("./upload/ocorrencias/$atendimento_ocorrencia_id");
            $destino = "./upload/ocorrencias/$atendimento_ocorrencia_id";
            chmod($destino, 0777);
        }

        $_FILES['userfile']['name'] = $nome . '.' . $type[1];
        //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
        $config['upload_path'] = "./upload/ocorrencias/$atendimento_ocorrencia_id/";
        $config['allowed_types'] = 'gif|jpg|png|jpeg|txt|xlsx|xls|docx|pdf|doc|odt|bmp';
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
        
    }             
    
    
    function excluiraparelho($fila_aparelho_id){
        $this->exame->excluiraparelho($fila_aparelho_id);  
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");     
    }
    
    
   function listaraparelhosassociados($limite = 25) {
        $data["limite_paginacao"] = $limite;
        $this->loadView('ambulatorio/filaaparelhosassociados-lista', $data);
    }
    
    
    
    function gravartrocaaparelho(){
        $this->exame->gravartrocaaparelho();
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");  
    }
                  
    function historicoaparelhogastosala($aparelho_gasto_sala_id){ 
       $data['historico']  =  $this->exame->listarhistoricoaparelhogatosala($aparelho_gasto_sala_id);             
       $this->load->View('ambulatorio/historicoaparelho',$data);
    }
    
    
     function trocarprocedimentoconsulta($agenda_exames_id) {
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['consulta'] = $this->exame->listarsalaagenda($agenda_exames_id); 
        $data['convenios'] = $this->guia->listarconvenios();    
        $data['grupos'] = $this->procedimento->listargrupos();
        $this->load->View('ambulatorio/trocarprocedimento-form', $data);
    }
                    
    function gravartrocarprocedimento(){
      $verificar =  $this->exame->gravartrocarprocedimento();  

       if($verificar != "-1"){
           $mensagem = "Sucesso ao trocar procedimento";
       }else{
           $mensagem = "Erro ao trocar procedimento";
       }
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
                    
    
    function relatoriopacientecnh() {
        $empresa_id = $this->session->userdata('empresa_id');
        $data['operadores'] = $this->operador_m->listartecnicos();
        $data['convenio'] = $this->convenio->listardados();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['empresa'] = $this->guia->listarempresas();
        $data['salas'] = $this->exame->listartodassalas();
        $data['permissaoempresa'] = $this->guia->listarempresasaladepermissao($empresa_id);
        $this->loadView('ambulatorio/relatoriopacientecnh', $data);
    }
    
     function gerarelatoriopacientecnh() {
        $empresa_id = $this->session->userdata('empresa_id'); 
        $data['empresa'] = $this->guia->listarempresas();
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))); 
        $data['relatorio'] = $this->exame->relatoriopacientecnh(); 
        $data['empresa'] = $this->guia->listarempresa(@$_POST['empresa']);
        if (isset($_POST['mala_direta'])) {
            $data['mala_direta'] = true;
        } else {
            $data['mala_direta'] = false;
        }         
        $this->load->View('ambulatorio/impressaorelatoriopacientecnh', $data);
        
    }
    
    
}

/* End of file welcome.php */
    /* Location: ./system/application/controllers/welcome.php */
