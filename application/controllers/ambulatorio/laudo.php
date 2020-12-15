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
class Laudo extends BaseController {

    function Laudo() {
        parent::Controller();
        $this->load->model('ambulatorio/laudo_model', 'laudo');
        $this->load->model('login_model', 'login');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/laudooit_model', 'laudooit');
        $this->load->model('seguranca/operador_model', 'operador_m');
        $this->load->model('ambulatorio/procedimento_model', 'procedimento');
        $this->load->model('ambulatorio/procedimentoplano_model', 'procedimentoplano');
        $this->load->model('ambulatorio/exametemp_model', 'exametemp');
        $this->load->model('ambulatorio/exame_model', 'exame');
        $this->load->model('ambulatorio/empresa_model', 'empresa');
        $this->load->model('cadastro/convenio_model', 'convenio');
        $this->load->model('ambulatorio/odontograma_model', 'odontograma');
        $this->load->model('cadastro/paciente_model', 'paciente');
        $this->load->model('certificadoapi_model', 'certificadoAPI');
        $this->load->model('ambulatorio/modelomedicamento_model', 'modelomedicamento');
        $this->load->model('ambulatorio/modeloreceita_model', 'modeloreceita');
        $this->load->library('mensagem');
        $this->load->library('utilitario');
        $this->load->library('pagination');
        $this->load->library('validation');
        if ($this->session->userdata('autenticado') != true) {
            redirect(base_url() . "login/index/login004", "refresh");
        }
    }

    function index() {
        $this->pesquisar();
    }

    function pesquisar($args = array()) {
        $this->loadView('ambulatorio/laudo-lista', $args);
    }

    function pesquisarconsulta($args = array()) {
        $this->loadView('ambulatorio/laudoconsulta-lista', $args);

//            $this->carregarView($data);
    }

    function pesquisarconsultaantigo($args = array()) {
        $this->loadView('ambulatorio/laudoconsultaantigo-lista', $args);

//            $this->carregarView($data);
    }

    function pesquisardigitador($args = array()) {
        $this->loadView('ambulatorio/laudodigitador-lista', $args);
    }

    function pesquisarlaudoantigo($args = array()) {
        $this->loadView('ambulatorio/laudoantigo-lista', $args);

//            $this->carregarView($data);
    }

    function pesquisarrevisor($args = array()) {
        $this->loadView('ambulatorio/revisor-lista', $args);

//            $this->carregarView($data);
    }

    function calculadora($args = array()) {
        $data['valor1'] = '';
        $data['valor2'] = '';
        $data['valor3'] = '';
        $data['resultado'] = '';
        $this->load->View('ambulatorio/calculadora-form', $data);
    }

    function encaminharatendimento($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['medicos'] = $this->operador_m->listarmedicos();
        $this->load->View('ambulatorio/encaminharatendimento-form', $data);
    }

    function impressaohistoricoescolhermedico($ambulatorio_laudo_id, $paciente_id, $procedimento_tuss_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['medicos'] = $this->operador_m->listarmedicos();
        $this->load->View('ambulatorio/historicoescolhermedico-form', $data);
    }

    function gravarencaminhamentoatendimento() {
        $empresapermissao = $this->guia->listarempresapermissoes();
        $laudo_id = $_POST["ambulatorio_laudo_id"];
        $medico_id = $_POST["medico_id"];
        $email_ativado = $empresapermissao[0]->encaminhamento_email;
//        var_dump($_POST);
//        die;
//        $this->laudo->gravarencaminhamentoatendimento();
        if ($email_ativado == 't') {
            $this->enviaremailencaminhamento($medico_id, $laudo_id);
        }
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function enviaremailencaminhamento($medico_id, $laudo_id) {
        $empresa = $this->guia->listarempresa();

        $resposta = $this->laudo->listarlaudoemailencaminhamento($laudo_id);
        $resposta2 = $this->laudo->listarmedicoenviarencaminhamento($medico_id);

        $medico1 = $resposta[0]->medico;
        $medico_encaminhar = $resposta2[0]->medico;
        $medico_email = $resposta2[0]->email;
//            $senha = $resposta[0]->agenda_exames_id;
        $mensagem = "Dr(a). $medico1 indicou você para um paciente. Continue a corrente e sempre que possível, indique outro procedimento da " . $empresa[0]->nome . " para fazer a clinica ainda mais forte <br><br><br><br> <span>Obs: Não responda esse email. Email automático</span>";
//            echo '<pre>';
//            var_dump($empresa); die;           
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
        $this->email->subject("Encaminhamento de Paciente");
        $this->email->message($mensagem);


        if ($this->email->send()) {
            $data['mensagem'] = "Email enviado com sucesso.";
        } else {
            $data['mensagem'] = "Envio de Email malsucedido.";
        }
    }

    function limparnomes($exame_id) {
        $data['exame_id'] = $exame_id;
        $this->load->View('ambulatorio/limparnomeimagem-form', $data);
    }

    function gravarlimparnomes($exame_id) {
        $this->laudo->deletarnomesimagens($exame_id);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function chamarpacientesalaespera($ambulatorio_laudo_id) {
        $this->laudo->chamarpacientesalaespera($ambulatorio_laudo_id);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function chamarpaciente($ambulatorio_laudo_id) {
        $this->laudo->chamada($ambulatorio_laudo_id);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function chamarpaciente2($ambulatorio_laudo_id) {
        $this->laudo->chamada($ambulatorio_laudo_id);
        redirect(base_url() . "ambulatorio/exame/listarexamerealizando");
    }

    function calcularvolume($args = array()) {
        (int)
                $valor1 = str_replace(",", ".", $_POST['valor1']);
        $valor2 = str_replace(",", ".", $_POST['valor2']);
        $valor3 = str_replace(",", ".", $_POST['valor3']);
        $resultado = 0.5233 * $valor1 * $valor2 * $valor3;
        $data['valor1'] = $valor1;
        $data['valor2'] = $valor2;
        $data['valor3'] = $valor3;
        $data['resultado'] = $resultado;
        $this->load->View('ambulatorio/calculadora-form', $data);
    }

    function alterardata($ambulatorio_laudo_id) {
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/alterardatalaudo-form', $data);
    }

    function gravaralterardata($ambulatorio_laudo_id) {
        $this->laudo->gravaralterardata($ambulatorio_laudo_id);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function email($texto, $email) {

//        $this->load->library('email');
//
//        //SMTP
////        stgsaude
////        teste123
//        $config['protocol'] = 'smtp';
//        $config['smtp_host'] = 'ssl://smtp.gmail.com';
//        $config['smtp_port'] = '465';
//        $config['smtp_user'] = 'equipe2016gcjh@gmail.com';
//        $config['smtp_pass'] = 'aramis*123@';
//        $config['validate']  = TRUE;
//        $config['mailtype']  = 'text';
//        $config['charset'] = 'utf-8';
//        $config['newline'] = "\r\n";
//        $this->email->initialize($config);
//
//        $this->email->from('equipe2016gcjh@gmail.com', 'STG Saúde');
//        $this->email->to($email_paciente);
//        $this->email->subject('Laudo Médico');
//        $this->email->message($texto);
//        $this->email->send();
//        echo $this->email->print_debugger();     

        $this->load->library('My_phpmailer');

        $mail = new PHPMailer;

        $mail->setLanguage('br');                             // Habilita as saídas de erro em Português
        $mail->CharSet = 'UTF-8';                             // Habilita o envio do email como 'UTF-8'
        //$mail->SMTPDebug = 3;                               // Habilita a saída do tipo "verbose"
        $mail->isSMTP();                                      // Configura o disparo como SMTP
        $mail->Host = 'smtp.gmail.com';                       // Especifica o enderço do servidor SMTP da Locaweb
        $mail->SMTPAuth = true;                               // Habilita a autenticação SMTP
        $mail->Username = 'equipe2016gcjh@gmail.com';         // Usuário do SMTP
        $mail->Password = 'aramis*123@';                   // Senha do SMTP
        $mail->SMTPSecure = 'ssl';                            // Habilita criptografia TLS | 'ssl' também é possível
        $mail->Port = 465;                                    // Porta TCP para a conexão
        $mail->From = 'equipe2016gcjh@gmail.com';             // Endereço previamente verificado no painel do SMTP
        $mail->FromName = 'STG Saúde';                        // Nome no remetente
        $mail->addAddress($email);                            // Acrescente um destinatário
        $mail->isHTML(true);                                  // Configura o formato do email como HTML
        $mail->Subject = 'Laudo Médico';
        $mail->Body = $texto;
        $mail->send();
    }

        function emailcomarquivo($ambulatorio_laudo_id, $exame, $emailpaciente) {

   
        $this->load->library('email');
        $this->load->helper('directory');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_user'] = 'equipe2016gcjh@gmail.com';
        $config['smtp_pass'] = 'aramis*123@';
        $config['validate'] = TRUE;
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";

        $atendimento = $this->laudo->listaratendimento($ambulatorio_laudo_id);
        $horario = date('H:i');
        $data = date('d/m/Y', strtotime($atendimento[0]->data));

        if($horario >= '06:00' && $horario <= '12:00'){
            $tempo = 'Bom Dia';
        }elseif($horario >= '12:01' && $horario <= '18:00'){
            $tempo = 'Boa Tarde';
        }else{
            $tempo = 'Boa Noite';
        }

        $mensagem = $tempo.' Sr(a). '.$atendimento[0]->paciente.' <br><br> 
        Segue abaixo em anexo o Laudo referente ao atendimento "'.$atendimento[0]->procedimento.'" realizado na clinica '.$empresa[0]->nome.', 
        com o Dr(a). '.$atendimento[0]->medico.', na data de '.$data.'. <br><br><br>

        A '.$empresa[0]->nome.' agradece a sua visita e contamos sempre com sua presença
        <br><br><br><br> <span><u>Obs: Não responda esse email. Email automático</u></span>';

        $arquivo_pasta = directory_map("./upload/laudopdf/$ambulatorio_laudo_id/");
        // $arquivo_imagens = directory_map("./upload/$exame/");

        $empresa = $this->guia->listarempresa();
        $this->email->initialize($config);

        if (@$empresa[0]->email != '') {
            $this->email->from($empresa[0]->email, $empresa[0]->nome);
        } else {
            $this->email->from('equipe2016gcjh@gmail.com', $empresa[0]->nome);
        }
        $this->email->to($emailpaciente);
        $this->email->subject("Laudo Médico");
        foreach($arquivo_pasta as $value){
            $this->email->attach('./upload/laudopdf/'.$ambulatorio_laudo_id.'/'.$value);
        }
        $this->email->message($mensagem);
        $this->email->send();


    }

    function carregarlaudo($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
//        $arquivo_pasta = directory_map( base_url() . "dicom/");
        $this->load->helper('directory');
        $agenda_exames_id = $obj_laudo->_agenda_exames_id;
        $arquivo_pasta = directory_map("./cr/$agenda_exames_id/");
        $origem = "./cr/$agenda_exames_id";
        $this->laudo->auditoriaLaudo($ambulatorio_laudo_id, 'Entrou no Laudo');
//        if (count($arquivo_pasta) > 0) {
//
//            foreach ($arquivo_pasta as $nome1 => $item) {
//                foreach ($item as $nome2 => $valor) {
//
//                  
//                        $nova = $valor;
//                        if (!is_dir("./upload/$exame_id")) {
//                            mkdir("./upload/$exame_id");
//                            $destino = "./upload/$exame_id/$nova";
//                            chmod($destino, 0777);
//                        }
//                        $destino = "./upload/$exame_id/$nova";
//                        $local = "$origem/$nome1/$nova";
//                        $deletar = "$origem/$nome1/$nome2";
//                        copy($local, $destino);
//                }
//            }
//        }    

        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa'] = $this->empresa->listarempresatoten($empresa_id);
        $endereco = $data['empresa'][0]->endereco_toten;
        $data['endereco'] = $endereco;

        $data['integracao'] = $this->laudo->listarlaudosintegracao($agenda_exames_id);
        if (count($data['integracao']) > 0) {
            $this->laudo->atualizacaolaudosintegracao($agenda_exames_id);
        }
        $data['lista'] = $this->exametemp->listarmodeloslaudo($procedimento_tuss_id, @$obj_laudo->_medico_parecer1);
        $data['linha'] = $this->exametemp->listarmodeloslinha($procedimento_tuss_id);
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);
        // $data['historico'] = $this->laudo->listarconsultahistorico($paciente_id);
        $data['historicoantigo'] = $this->laudo->listarconsultahistoricoantigo($paciente_id);
        // $data['historicoexame'] = $this->laudo->listarexamehistorico($paciente_id);
        $data['historicoantigo'] = $this->laudo->listarconsultahistoricoantigo($paciente_id);
        $data['historicowebcon'] = $this->laudo->listarconsultahistoricoweb($paciente_id);
//        var_dump($data['historicowebcon']); die;
        $data['historicowebexa'] = $this->laudo->listarexamehistoricoweb($paciente_id);
        $data['historicoexame'] = $this->laudo->listarexamehistoricodiferenciado($paciente_id, $ambulatorio_laudo_id);
        $data['historicoespecialidade'] = $this->laudo->listarespecialidadehistoricodiferenciado($paciente_id, $ambulatorio_laudo_id);
        $data['historico'] = $this->laudo->listarconsultahistoricodiferenciado($paciente_id, $ambulatorio_laudo_id);
//        var_dump($data['historicoexame']); die;
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['mensagem'] = $messagem;
        $this->load->helper('directory');
        $data['arquivos_anexados'] = directory_map("./upload/consulta/$ambulatorio_laudo_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivos_anexados'] != false) {
            sort($data['arquivos_anexados']);
        }
        $data['arquivos_paciente'] = directory_map("./upload/paciente/$paciente_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivos_paciente'] != false) {
            sort($data['arquivos_paciente']);
        }
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
        if ($data['arquivo_pasta'] != false) {
            natcasesort($data['arquivo_pasta']);
        }
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresapermissao'] = $this->guia->listarempresasaladepermissao($empresa_id);
        $data['obj'] = $obj_laudo;
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        if($data['empresapermissao'][0]->diagnostico_medico == 't'){
            $procedimentod = $this->guia->pegaridprocedimento($procedimento_tuss_id);
            $id_procedimento = $procedimentod[0]->procedimento_tuss_id; 
            $diagnostico_id = 0; 
            $diagnosticosprocedimentos = $this->guia->listadiagnosticosprocedimentos();
            foreach($diagnosticosprocedimentos as $item){
                $arrayprocedimento  = json_decode($item->procedimentos);
                    foreach ($arrayprocedimento as $value){
                        if($id_procedimento == $value){
                            $diagnostico_id = $item->diagnostico_id;
                        }
                    }
            }

            if($diagnostico_id != 0){
                $opcoes = $this->guia->listaropcoesdiagnostico($diagnostico_id);
            
                foreach($opcoes as $item){
                    $arrayopcoes  = json_decode($item->opcoes);
                }
    
                $data['opcoes'] = $arrayopcoes;
                $data['diagnostico_id'] = $diagnostico_id;
            }else{
                $data['opcoes'] = '';
                $data['diagnostico_id'] = '';
            }
        }

        $operador_id = $this->session->userdata('operador_id');
        $operador = $this->operador_m->listaroperadoratendimento($operador_id);
        $atendimento_dif = @$operador[0]->atendimento_medico;

        $data['salvarcomopdf'] = 0;

        if($data['empresapermissao'][0]->atendimento_medico_3 == 't'){
            $data['salvarcomopdf'] = 1;
        }

        $data['laudo_sigiloso'] = $data['empresapermissao'][0]->laudo_sigiloso;
        $data['integracaosollis'] = $data['empresapermissao'][0]->integracaosollis;
        if ($atendimento_dif == 't') {
            $this->load->View('ambulatorio/laudoimagemdif-form', $data);
        } else {
            if ($data['empresapermissao'][0]->atendimento_medico == "f") {
                $this->load->View('ambulatorio/laudo-form_1', $data);
            } else {
                $this->load->View('ambulatorio/laudo-form_2', $data);
            }
        }
//        $this->load->View('ambulatorio/laudo-form', $data);
    }

    function carregaruploadcliente() {
        $this->load->View('ambulatorio/uploadimagens');
    }

    function carregarmodelolaudoselecionado($modelo_id = null) {
//        var_dump($modelo_id);
//        die;
        if ($modelo_id != null) {
            $modelolaudo = $this->exametemp->listarmodeloslaudovisualizar($modelo_id);
            if (count($modelolaudo) > 0) {
                echo $modelolaudo[0]->texto;
            }
        }
    }

    function redirecionauploadcliente() {
        $caminho = $_POST['caminho'];
        $arquivo = (isset($_POST['arquivo'])) ? $_POST['arquivo'] : '';
        $todos = (isset($_POST['todos'])) ? 'true' : 'false';
        header("Location: http://localhost/conexao.php?caminho={$caminho}&arquivo={$arquivo}&todos={$todos}");
    }

    function carregarlaudolaboratorial($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarmodeloslaudo($procedimento_tuss_id, @$obj_laudo->_medico_parecer1);
        $data['linha'] = $this->exametemp->listarmodeloslinha($procedimento_tuss_id);
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);
        $this->laudo->auditoriaLaudo($ambulatorio_laudo_id, 'Entrou no Laudo');
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['mensagem'] = $messagem;
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");

        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['obj'] = $obj_laudo;
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/laudolaboratorial-form', $data);
    }

    function carregarlaudoeco($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarmodeloslaudo($procedimento_tuss_id, @$obj_laudo->_medico_parecer1);
        $data['linha'] = $this->exametemp->listarmodeloslinha($procedimento_tuss_id);
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);

        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['mensagem'] = $messagem;
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $this->laudo->auditoriaLaudo($ambulatorio_laudo_id, 'Entrou no Laudo');
        $data['obj'] = $obj_laudo;
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresapermissao'] = $this->guia->listarempresasaladepermissao($empresa_id);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/laudoeco-form', $data);
    }

    function solicitarparecer($paciente_id, $ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $guia_id = @$obj_laudo->_guia_id;
        $data['especialidade'] = $this->laudo->listarespecialidadeparecer();
        $data['parecer_lista'] = $this->laudo->listarsolicitacaoparecer($paciente_id);
//        var_dump($data['formulario']); die;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['guia_id'] = $guia_id;

        $this->load->View('ambulatorio/soliciparparecer', $data);
    }

    function carregarformulario($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['formulario'] = $this->laudo->preencherformulario($paciente_id, $guia_id);
//        var_dump($data['formulario']); die;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $this->load->View('ambulatorio/formulario_ficha', $data);
    }

    function carregarcirurgia($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['cirurgia'] = $this->laudo->preenchercirurgia($paciente_id, $guia_id);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $this->load->View('ambulatorio/cirurgia_ficha', $data);
    }

    function carregarexameslab($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['exameslab'] = $this->laudo->preencherexameslab($paciente_id, $guia_id);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $this->load->View('ambulatorio/exames-laboratoriais-ficha', $data);
    }

    function carregarecocardio($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['ecocardio'] = $this->laudo->preencherecocardio($paciente_id, $guia_id);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $this->load->View('ambulatorio/ecocardiograma-ficha', $data);
    }

    function carregarecostress($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['ecostress'] = $this->laudo->preencherecostress($paciente_id, $guia_id);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $this->load->View('ambulatorio/ecostress-ficha', $data);
    }

    function carregarcate($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['cate'] = $this->laudo->preenchercate($paciente_id, $guia_id);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $this->load->View('ambulatorio/cateterismo-ficha', $data);
    }

    function carregarholter($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['holter'] = $this->laudo->preencherholter($paciente_id, $guia_id);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $this->load->View('ambulatorio/holter-ficha', $data);
    }

    function carregarcintilografia($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['cintil'] = $this->laudo->preenchercintilografia($paciente_id, $guia_id);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $this->load->View('ambulatorio/cintilografia-ficha', $data);
    }

    function carregarmapa($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['mapa'] = $this->laudo->preenchermapa($paciente_id, $guia_id);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $this->load->View('ambulatorio/mapa-ficha', $data);
    }

    function carregartergometrico($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['tergometrico'] = $this->laudo->preenchertergometrico($paciente_id, $guia_id);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $this->load->View('ambulatorio/teste-ergometrico-ficha', $data);
    }

    function carregarparecer($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['parecer'] = $this->laudo->preencherparecer($paciente_id, $guia_id);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $this->load->View('ambulatorio/parecer-cirurgia-pediatrica-form', $data);
    }

    function carregaravaliacao($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['avaliacao'] = $this->laudo->preencheravaliacao($paciente_id, $guia_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/avaliacao_ficha', $data);
    }

    function carregarlaudohistorico($paciente_id) {
        $data['paciente_id'] = $paciente_id;
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $this->load->View('ambulatorio/laudoconsultahistorico-form', $data);
    }

    function vozemtexto($ambulatorio_laudo_id, $operador_id) {
        $data['laudo_id'] = $ambulatorio_laudo_id;
        $data['operador_id'] = $operador_id;
        $this->load->View('ambulatorio/voz', $data);
    }

    function gravartextoconvertido() {
        $this->exametemp->gravartextoconvertido();

        //O google Chrome não permite fechar a janela pelo javascript a menos que ela tenha sido aberta com javascript
        echo "<script>window.location.href = 'https://www.google.com';</script>";
    }

    function auditorialaudo($ambulatorio_laudo_id = null) {

        $data['auditoria'] = $this->laudo->listarlaudoauditoria($ambulatorio_laudo_id);
        $this->load->View('ambulatorio/laudoauditoria-form', $data);
    }

    function caregarodontograma($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;

        $data['primeiroQuadrante'] = $this->odontograma->instanciarprimeiroquadrantepacienteodontograma($ambulatorio_laudo_id);
        $data['segundoQuadrante'] = $this->odontograma->instanciarsegundoquadrantepacienteodontograma($ambulatorio_laudo_id);
        $data['terceiroQuadrante'] = $this->odontograma->instanciarterceiroquadrantepacienteodontograma($ambulatorio_laudo_id);
        $data['quartoQuadrante'] = $this->odontograma->instanciarquartoquadrantepacienteodontograma($ambulatorio_laudo_id);

        $data['procedimentos'] = $this->convenio->listarprocedimentoconvenioodontograma(@$data['obj']->_convenio_id);

        $this->load->View('ambulatorio/odontograma-form', $data);
    }

    function carregaranamineseodontologia($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $situacaolaudo = @$obj_laudo->_situacaolaudo;
        $agenda_exames_id = @$obj_laudo->_agenda_exames_id;
        $atendimento = @$obj_laudo->_atendimento;
        if ($atendimento != 't') {
            $this->exame->atendimentohora($agenda_exames_id);
        }
        if ($situacaolaudo != 'FINALIZADO') {
            $this->exame->atenderpacienteconsulta($exame_id);
        }
        $this->load->helper('directory');
        $data['arquivos_anexados'] = directory_map("./upload/consulta/$ambulatorio_laudo_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivos_anexados'] != false) {
            sort($data['arquivos_anexados']);
        }
        $data['arquivos_paciente'] = directory_map("./upload/paciente/$paciente_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivos_paciente'] != false) {
            sort($data['arquivos_paciente']);
        }
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresapermissao'] = $this->guia->listarempresasaladepermissao($empresa_id);
        $data['listarades'] = $this->laudo->listarades();
        $data['listaradcl'] = $this->laudo->listaradcl();
        $data['listarodes'] = $this->laudo->listarodes();
        $data['listarodcl'] = $this->laudo->listarodcl();
        $data['listarodeixo'] = $this->laudo->listarodeixo();
        $data['listarodav'] = $this->laudo->listarodav();
        $data['listaroees'] = $this->laudo->listaroees();
        $data['listaroecl'] = $this->laudo->listaroecl();
        $data['listaroeeixo'] = $this->laudo->listaroeeixo();
//        var_dump($data['listaroeeixo']); die;
        $data['listaroeav'] = $this->laudo->listaroeav();
        $data['listaracuidadeod'] = $this->laudo->listaracuidadeod();
        $data['listaracuidadeoe'] = $this->laudo->listaracuidadeoe();

        $data['lista'] = $this->exametemp->listarmodeloslaudo($procedimento_tuss_id, @$obj_laudo->_medico_parecer1);
        $data['linha'] = $this->exametemp->listarmodeloslinha($procedimento_tuss_id);
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);
        $data['laudo_peso'] = $this->laudo->listarlaudospesoaltura($paciente_id, $ambulatorio_laudo_id);
//        echo '<pre>';
//        var_dump($data['laudo_peso']); die;
        $data['historico'] = $this->laudo->listarconsultahistorico($paciente_id);
        $data['historicoantigo'] = $this->laudo->listarconsultahistoricoantigo($paciente_id);
        $data['historicoexame'] = $this->laudo->listarexamehistorico($paciente_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['mensagem'] = $messagem;
        $data['paciente_id'] = $paciente_id;
        $data['exame_id'] = $exame_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/laudoodontologia-form', $data);
    }

    function listareceituarioslaudo($ambulatorio_laudo_id){
        $data['receita'] = $this->laudo->listareceituarioslaudo($ambulatorio_laudo_id);
        // echo '<pre>';
        // print_r($data['receita']);
        
        $this->load->View('ambulatorio/listareceituarioslaudo-form', $data);
    }

    function carregaranaminese($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $messagem = null, $prontuario_adendo = FALSE) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $situacaolaudo = @$obj_laudo->_situacaolaudo;
        $agenda_exames_id = @$obj_laudo->_agenda_exames_id;
        $atendimento = @$obj_laudo->_atendimento;
        if ($atendimento != 't') {
            $this->exame->atendimentohora($agenda_exames_id);
        }
        if ($situacaolaudo != 'FINALIZADO') {
//            echo 'teste';
            $this->exame->atenderpacienteconsulta($exame_id);
        }

        $this->laudo->auditoriaLaudo($ambulatorio_laudo_id, 'Entrou no Laudo');

        $this->load->helper('directory');
        $data['arquivos_anexados'] = directory_map("./upload/consulta/$ambulatorio_laudo_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivos_anexados'] != false) {
            sort($data['arquivos_anexados']);
        }
        $data['arquivos_paciente'] = directory_map("./upload/paciente/$paciente_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivos_paciente'] != false) {
            sort($data['arquivos_paciente']);
        }

        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa'] = $this->empresa->listarempresatoten($empresa_id);

        @$endereco = $data['empresa'][0]->endereco_toten;
        $data['endereco'] = $endereco;

//        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresapermissao'] = $this->guia->listarempresasaladepermissao($empresa_id);
        // echo '<pre>';
        // print_r($data['empresapermissao']);
        // die;
        //    var_dump($data['empresapermissao']); die;
        $data['listarades'] = $this->laudo->listarades();
        $data['listaradcl'] = $this->laudo->listaradcl();
        $data['listarodes'] = $this->laudo->listarodes();
        $data['listarodcl'] = $this->laudo->listarodcl();
        $data['listarodeixo'] = $this->laudo->listarodeixo();
        $data['listarodav'] = $this->laudo->listarodav();
        $data['listaroees'] = $this->laudo->listaroees();
        $data['listaroecl'] = $this->laudo->listaroecl();
        $data['listaroeeixo'] = $this->laudo->listaroeeixo();
//        var_dump($data['listaroeeixo']); die;
        $data['listaroeav'] = $this->laudo->listaroeav();
        $data['listaracuidadeod'] = $this->laudo->listaracuidadeod();
        $data['listaracuidadeoe'] = $this->laudo->listaracuidadeoe();

        $data['lista'] = $this->exametemp->listarmodeloslaudo($procedimento_tuss_id, @$obj_laudo->_medico_parecer1);
        $data['linha'] = $this->exametemp->listarmodeloslinha($procedimento_tuss_id);
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);
        $data['laudo_peso'] = $this->laudo->listarlaudospesoaltura($paciente_id, $ambulatorio_laudo_id);

        $data['historicoexame'] = $this->laudo->listarexamehistoricodiferenciado($paciente_id, $ambulatorio_laudo_id);
        $data['historicoespecialidade'] = $this->laudo->listarespecialidadehistoricodiferenciado($paciente_id, $ambulatorio_laudo_id);
        $data['historico'] = $this->laudo->listarconsultahistoricodiferenciado($paciente_id, $ambulatorio_laudo_id, $prontuario_adendo);

        $data['historicoantigo'] = $this->laudo->listarconsultahistoricoantigo($paciente_id);
        $data['historicowebcon'] = $this->laudo->listarconsultahistoricoweb($paciente_id);
//        var_dump($data['historicowebcon']); die;
        $data['historicowebexa'] = $this->laudo->listarexamehistoricoweb($paciente_id);
        $data['historicowebesp'] = $this->laudo->listarespecialidadehistoricoweb($paciente_id);

        $data['receita'] = $this->laudo->listarreceita($obj_laudo->_paciente_id);

        $data['receita_laudo'] = $this->laudo->listarreceitaconsulta($ambulatorio_laudo_id, $prontuario_adendo);
        $data['receita_especial_laudo'] = $this->laudo->listarreceitaespecialconsulta($ambulatorio_laudo_id, $prontuario_adendo);
        $data['exames_laudo'] = $this->laudo->listarexamesconsulta($ambulatorio_laudo_id, $prontuario_adendo);
        $data['terapeuticas_laudo'] = $this->laudo->listarterapeuticasconsulta($ambulatorio_laudo_id, $prontuario_adendo);
        $data['relatorio_laudo'] = $this->laudo->listarrelatorioconsulta($ambulatorio_laudo_id, $prontuario_adendo);
        $data['nomesimpressoes'] = $this->laudo->listarnomesimpressoes($ambulatorio_laudo_id);


        $data_receita = $this->laudo->datalistarreceitaconsultaantigo($ambulatorio_laudo_id, $paciente_id);
        $data_receitaespecial = $this->laudo->datalistarreceitaespecialconsultaantigo($ambulatorio_laudo_id, $paciente_id);
        $data_exame = $this->laudo->datalistarexamesconsultaantigo($ambulatorio_laudo_id, $paciente_id);
        $data_terapeutica = $this->laudo->datalistarterapeuticasconsultaantigo($ambulatorio_laudo_id, $paciente_id);
        $data_relatorio = $this->laudo->datalistarrelatorioconsultaantigo($ambulatorio_laudo_id, $paciente_id);
        // print($data_receita);
        // die;
        $data['receita_antigo'] = $this->laudo->listarreceitaconsultaantigo($ambulatorio_laudo_id, $paciente_id, $data_receita, $prontuario_adendo);
        $data['receita_especial_antigo'] = $this->laudo->listarreceitaespecialconsultaantigo($ambulatorio_laudo_id, $paciente_id, $data_receitaespecial, $prontuario_adendo);
        $data['exames_antigo'] = $this->laudo->listarexamesconsultaantigo($ambulatorio_laudo_id, $paciente_id, $data_exame, $prontuario_adendo);
        $data['terapeuticas_antigo'] = $this->laudo->listarterapeuticasconsultaantigo($ambulatorio_laudo_id, $paciente_id, $data_terapeutica, $prontuario_adendo);
        $data['relatorio_antigo'] = $this->laudo->listarrelatorioconsultaantigo($ambulatorio_laudo_id, $paciente_id, $data_relatorio, $prontuario_adendo);


        $data['impressao_receitas'] = $this->laudo->listarreceitasimpressoes($ambulatorio_laudo_id, $prontuario_adendo);
        $data['impressao_exames'] = $this->laudo->listarexamesimpressoes($ambulatorio_laudo_id, $prontuario_adendo);
        $data['impressao_terapeuticas'] = $this->laudo->listarterapeuticasimpressoes($ambulatorio_laudo_id, $prontuario_adendo);
        $data['impressao_relatorio'] = $this->laudo->listarrelatorioimpressoes($ambulatorio_laudo_id, $prontuario_adendo);

        $data['resultadoexames'] = $this->laudo->listarresultadosexames($paciente_id);
        // echo '<pre>';
        // print_r($data['resultadoexames']);
        // die;

        $data['anotacaoprivada'] = $this->laudo->listaranotacaoprivada($ambulatorio_laudo_id, @$obj_laudo->_medico_parecer1);
        // $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['modelo'] = array();
        // Nao deixei ativado a questao do modelo automático porque pode dar problema e ficar salvando
        // um receituario toda vez que for salvo o laudo.
        // Pois toda a página só tem um unico form.
        $data['listareceita'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['listareceitaespecial'] = $this->exametemp->listarautocompletemodelosreceitaespecial();
        $data['rotina'] = $this->laudo->listarrotina($paciente_id);
        $data['listaexames'] = $this->exametemp->listarautocompletemodelossolicitarexames();        
//        $data['contador'] = $this->laudo->contadorlistarreceita($ambulatorio_laudo_id);
        $data['solicitarexames'] = $this->laudo->listarexame($ambulatorio_laudo_id);
        
        $data['listaterapeuticas'] = $this->exametemp->listarmodelosterapeuticas();
        $data['listarelatorios'] = $this->exametemp->listarmodelosrelatorio();
        $data['listaprotocolos'] = $this->exametemp->listarmodelosprotocolos();

        // echo '<pre>';
        //  $pos1 = stripos($data['listaprotocolos'][0]->texto, '<p>');
        //  $pos2 = strripos($data['listaprotocolos'][0]->texto, "</p>");
        //  $posD = $pos2 - $pos1;
        //  $teste_texto = substr($data['listaprotocolos'][0]->texto, $pos1, $posD); 

        // echo $teste_texto;
        // var_dump($data['listaprotocolos'][0]->texto);
        // print_r($data['listaprotocolos']);
        // die;

        $data['terapeuticas'] = $this->laudo->listarterapeuticas($ambulatorio_laudo_id);
        $data['relatorios'] = $this->laudo->listarrelatorios($ambulatorio_laudo_id);


        $data['listareceitalaudo'] = $this->laudo->listarreceitalaudo($ambulatorio_laudo_id);
        $data['modelo_aut'] = array();
        // $data['modelo_aut'] = $this->exametemp->listarmodelosexameautomatico();
        $data['procedimentosadt'] = $this->convenio->listarconveniosgrupopadrao(@$obj_laudo->_convenio_id);
        $data['guiahistorico'] = $this->guia->listarsolicitacaosadthistorico($paciente_id);
        $data['listatemplates'] = $this->empresa->listartemplateanamneseatendimento($obj_laudo->_grupo);
        $data['medicamentos'] = $this->modelomedicamento->listarMedicamentos();
        // var_dump($data['procedimento']); die;
        // $data['historicoexame'] = $this->laudo->listarexamehistorico($paciente_id);
        // $data['historicoespecialidade'] = $this->laudo->listarespecialidadehistorico($paciente_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['mensagem'] = $messagem;
        $data['paciente_id'] = $paciente_id;
        $data['exame_id'] = $exame_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $operador_id = $this->session->userdata('operador_id');
        $operador = $this->operador_m->listaroperadoratendimento($operador_id);
        $atendimento_dif = @$operador[0]->atendimento_medico;
        $atendimento_medico = @$data['empresapermissao'][0]->atendimento_medico;
        $atendimento_medico_3 = @$data['empresapermissao'][0]->atendimento_medico_3;
        
        $data['empresa_id'] = $empresa_id;
        $data['arquivo_pasta_logo'] = directory_map("./upload/logomarca/$empresa_id/");
       
        $data['laudo_sigiloso'] = $data['empresapermissao'][0]->laudo_sigiloso;
        $data['integracaosollis'] = $data['empresapermissao'][0]->integracaosollis;

        if($prontuario_adendo){
            // array_map('unlink', glob("./upload/novoatendimento/$ambulatorio_laudo_id/*"));
            $this->load->View('ambulatorio/laudoconsulta-form-novo_2_adendo', $data);
        }else{
            if ($atendimento_dif == 't') {
                $this->load->View('ambulatorio/laudoconsultadif-form', $data);
            } else {
                if($atendimento_medico_3 == 't'){
                    $this->load->View('ambulatorio/laudoconsulta-form-novo_2', $data);
                }else if ($atendimento_medico == "t") {
                    $this->load->View('ambulatorio/laudoconsulta-form-novo', $data);
                } else {
                    $this->load->View('ambulatorio/laudoconsulta-form', $data);
                }
            }
        }
    }


    function excluirmodelos($modelo, $id){

        if($modelo == 'Receita'){
            $this->laudo->excluirmodeloreceita($id);
        }elseif($modelo == 'Exame'){
            $this->laudo->excluirmodeloexames($id);
        }elseif($modelo == 'Relatorio'){
            $this->laudo->excluirmodelorelatorio($id);
        }elseif($modelo == 'Terapeutica'){
            $this->laudo->excluirmodeloterapeutica($id);
        }
        
        echo json_encode(true);
    }



    function carregaranaminesenovo($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['rotina'] = $this->laudo->listarrotina($paciente_id);
        $data['receita'] = $this->laudo->listarreceitasespeciais($ambulatorio_laudo_id);
        $situacaolaudo = @$obj_laudo->_situacaolaudo;
        $agenda_exames_id = @$obj_laudo->_agenda_exames_id;
        $atendimento = @$obj_laudo->_atendimento;
        if ($atendimento != 't') {
            $this->exame->atendimentohora($agenda_exames_id);
        }
        if ($situacaolaudo != 'FINALIZADO') {
//            echo 'teste';
            $this->exame->atenderpacienteconsulta($exame_id);
        }
        $this->load->helper('directory');
        $data['arquivos_anexados'] = directory_map("./upload/consulta/$ambulatorio_laudo_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivos_anexados'] != false) {
            sort($data['arquivos_anexados']);
        }
        $data['arquivos_paciente'] = directory_map("./upload/paciente/$paciente_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivos_paciente'] != false) {
            sort($data['arquivos_paciente']);
        }

        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa'] = $this->empresa->listarempresatoten($empresa_id);
        @$endereco = $data['empresa'][0]->endereco_toten;
        $data['endereco'] = $endereco;

//        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresapermissao'] = $this->guia->listarempresasaladepermissao($empresa_id);
//        var_dump($data['empresapermissao']); die;
        $data['listarades'] = $this->laudo->listarades();
        $data['listaradcl'] = $this->laudo->listaradcl();
        $data['listarodes'] = $this->laudo->listarodes();
        $data['listarodcl'] = $this->laudo->listarodcl();
        $data['listarodeixo'] = $this->laudo->listarodeixo();
        $data['listarodav'] = $this->laudo->listarodav();
        $data['listaroees'] = $this->laudo->listaroees();
        $data['listaroecl'] = $this->laudo->listaroecl();
        $data['listaroeeixo'] = $this->laudo->listaroeeixo();
//        var_dump($data['listaroeeixo']); die;
        $data['listaroeav'] = $this->laudo->listaroeav();
        $data['listaracuidadeod'] = $this->laudo->listaracuidadeod();
        $data['listaracuidadeoe'] = $this->laudo->listaracuidadeoe();
        $data['guiahistorico'] = $this->guia->listarsolicitacaosadthistorico($paciente_id);
        $data['procedimentosadt'] = $this->convenio->listarconveniosgrupopadrao(@$obj_laudo->_convenio_id);
        $data['lista'] = $this->exametemp->listarmodeloslaudo($procedimento_tuss_id, @$obj_laudo->_medico_parecer1);
        $data['linha'] = $this->exametemp->listarmodeloslinha($procedimento_tuss_id);
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);
        $data['laudo_peso'] = $this->laudo->listarlaudospesoaltura($paciente_id, $ambulatorio_laudo_id);
//        echo '<pre>';
//        var_dump($data['laudo_peso']); die;
        $data['historicoexame'] = $this->laudo->listarexamehistoricodiferenciado($paciente_id, $ambulatorio_laudo_id);
        $data['historicoespecialidade'] = $this->laudo->listarespecialidadehistoricodiferenciado($paciente_id, $ambulatorio_laudo_id);
        $data['historico'] = $this->laudo->listarconsultahistoricodiferenciado($paciente_id, $ambulatorio_laudo_id);
        $data['historicoantigo'] = $this->laudo->listarconsultahistoricoantigo($paciente_id);
        $data['historicowebcon'] = $this->laudo->listarconsultahistoricoweb($paciente_id);
//        var_dump($data['historicowebcon']); die;
        $data['historicowebexa'] = $this->laudo->listarexamehistoricoweb($paciente_id);
        $data['historicowebesp'] = $this->laudo->listarespecialidadehistoricoweb($paciente_id);
        $data['receita'] = $this->laudo->listarreceita($obj_laudo->_paciente_id);

        // $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['modelo'] = array();
        // Nao deixei ativado a questao do modelo automático porque pode dar problema e ficar salvando
        // um receituario toda vez que for salvo o laudo.
        // Pois toda a página só tem um unico form.
        $data['listareceita'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['rotina'] = $this->laudo->listarrotina($paciente_id);
        $data['listaexames'] = $this->exametemp->listarautocompletemodelossolicitarexames();
//        $data['contador'] = $this->laudo->contadorlistarreceita($ambulatorio_laudo_id);
        $data['solicitarexames'] = $this->laudo->listarexame($ambulatorio_laudo_id);
        $data['modelo_aut'] = array();

        // $data['modelo_aut'] = $this->exametemp->listarmodelosexameautomatico();
        // $data['historicoexame'] = $this->laudo->listarexamehistorico($paciente_id);
        // $data['historicoespecialidade'] = $this->laudo->listarespecialidadehistorico($paciente_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['mensagem'] = $messagem;
        $data['paciente_id'] = $paciente_id;
        $data['exame_id'] = $exame_id;
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $operador_id = $this->session->userdata('operador_id');
        $operador = $this->operador_m->listaroperadoratendimento($operador_id);
        $atendimento_dif = @$operador[0]->atendimento_medico;
        if ($atendimento_dif == 't') {
            $this->load->View('ambulatorio/laudoconsultadif-form', $data);
        } else {
            $this->load->View('ambulatorio/laudoconsulta-form-novo', $data);
        }
    }

    function preencherformulario($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
//        var_dump($data['formulario']);die;
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/formulario_ficha', $data);
    }

    function preenchercirurgia($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;

        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;


        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['operadores'] = $this->operador_m->listarmedicos();

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/cirurgia_ficha', $data);
    }

    function preencherexameslab($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;

        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;


        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['operadores'] = $this->operador_m->listarmedicos();

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/exames-laboratoriais-ficha', $data);
    }

    function preencherecocardio($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;

        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;


        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['operadores'] = $this->operador_m->listarmedicos();

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/ecocardiograma-ficha', $data);
    }

    function preencherecostress($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;

        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;


        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['operadores'] = $this->operador_m->listarmedicos();

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/ecostress-ficha', $data);
    }

    function preenchercate($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;

        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;


        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['operadores'] = $this->operador_m->listarmedicos();

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/cateterismo-ficha', $data);
    }

    function preencherholter($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;

        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;


        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/holter-ficha', $data);
    }

    function preenchersalas($ambulatorio_laudo_id = NULL, $medico_id = NULL) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;

        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;

//        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
//        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
//        $data['operadores'] = $this->operador_m->listarmedicos($medico_id);
//        $data['sala_id'] = $sala_id;
        $data['salas'] = $this->exame->listarsalaspoltronas();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
//        $data['horas_sala'] = $this->laudo->listarhorasala($sala_id);
//        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['listarmedico'] = $this->laudo->listarmedico($medico_id);
        $this->load->View('ambulatorio/preenchersalas-ficha', $data);
    }

    function preenchercintilografia($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;

        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;


        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['operadores'] = $this->operador_m->listarmedicos();

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/cintilografia-ficha', $data);
    }

    function preenchermapa($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;

        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;


        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['operadores'] = $this->operador_m->listarmedicos();

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/mapa-ficha', $data);
    }

    function preenchertergometrico($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;

        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;


        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['operadores'] = $this->operador_m->listarmedicos();

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/teste-ergometrico-ficha', $data);
    }

    function preencherparecer($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;

        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;


        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['operadores'] = $this->operador_m->listarmedicos();

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/parecer-cirurgia-pediatrica-form', $data);
    }

    function preencherlaudoapendicite($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;

        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;


        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['operadores'] = $this->operador_m->listarmedicos();

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/laudoapendicite-form', $data);
    }

    function preencheravaliacao($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['operadores'] = $this->operador_m->listarmedicos();

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/avaliacao_ficha', $data);
    }


    function impressaoreceitaoculos($ambulatorio_laudo_id) {
        $this->load->plugin('mpdf');
//        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
//        $data['laudo'] = $obj_laudo;
        $data['laudo'] = $this->laudo->listarreceitaoculosimpressao($ambulatorio_laudo_id);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
//        var_dump($data['laudo']); die;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }

        $base_url = base_url();


        if ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } elseif (isset($data['laudo'][0]->medico_parecer1)) {
            $this->load->helper('directory');
            $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
            foreach ($arquivo_pasta as $value) {
                if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                    $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                }
            }
        } else {
            $carimbo = "";
        }


//        echo '<pre>';
        $data['assinatura'] = $carimbo;
//        var_dump($data['laudo']);
//        die;

        $filename = "laudo.pdf";
        if ($data['empresa'][0]->cabecalho_config == 't') {
//                $cabecalho = $cabecalho_config;
            $cabecalho = "<table style='width:100%'><tr><td>$cabecalho_config</td></tr><tr><td></td></tr></table><table style='width:100%;text-align:center;'><tr><td><b>Receita de Óculos</b></td></tr></table>";
        } else {
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cabecalho.jpg'></td></tr><tr><td>Receita de Óculos</td></tr></table>";
        }
        if ($data['empresa'][0]->rodape_config == 't') {
            $rodape = $rodape_config;
        } else {
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
        }
        $html = $this->load->view('ambulatorio/impressaoreceitaoculos', $data, true);
        pdf($html, $filename, $cabecalho, $rodape);
        $this->load->View('ambulatorio/impressaoreceitaoculos', $data);
    }

    function pendenteexamemultifuncao($exames_id) {
//        $sala_id = $exames_id;
        $verificar = $this->exame->pendenteexamemultifuncao($exames_id);
        if ($verificar == "-1") {
            $data['mensagem'] = 'Erro ao encaminhar para sala de pendentes. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao encaminhar para sala de pendentes.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function pendenteodontologia($exames_id) {
//        $sala_id = $exames_id;
        $verificar = $this->exame->pendenteodontologia($exames_id);
        if ($verificar == "-1") {
            $data['mensagem'] = 'Erro ao encaminhar para sala de pendentes. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao encaminhar para sala de pendentes.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function pendenteespecialidade($exames_id) {
//        $sala_id = $exames_id;
        $verificar = $this->exame->pendenteespecialidade($exames_id);
        if ($verificar == "-1") {
            $data['mensagem'] = 'Erro ao encaminhar para sala de pendentes. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao encaminhar para sala de pendentes.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function carregaranaminesehistoricogeral($paciente_id) {
        $this->load->helper('directory');

        $data['arquivos_paciente'] = directory_map("./upload/paciente/$paciente_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivos_paciente'] != false) {
            sort($data['arquivos_paciente']);
        }
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['paciente_id'] = $paciente_id;
        $data['historico'] = $this->laudo->listarconsultahistorico($paciente_id);
        $data['historicoantigo'] = $this->laudo->listarconsultahistoricoantigo($paciente_id);
        $data['historicoexame'] = $this->laudo->listarexamehistorico($paciente_id);
        $data['historicowebcon'] = $this->laudo->listarconsultahistoricoweb($paciente_id);
//        var_dump($data['historicowebcon']); die;
        $data['historicowebexa'] = $this->laudo->listarexamehistoricoweb($paciente_id);
        $data['historicowebesp'] = $this->laudo->listarespecialidadehistoricoweb($paciente_id);

        $this->load->View('ambulatorio/laudoconsultahistoricogeral-form', $data);
    }

    function carregaranamineseantigo($paciente_id) {

        $this->load->library('RtfReader');
        $result = $this->exametemp->testandoConversaoArquivosRTFHistorico($paciente_id);
        if(count($result) > 0){
            foreach ($result as $key => $value) {
                if (strlen($value->laudo) > 10) {
                    $reader = new RtfReader();
                    $rtf = str_replace(';', '', $value->laudo);
                    $reader->Parse($rtf);
                    $formatter = new RtfHtml();
                    $html = $formatter->Format($reader->root);

                    $this->exametemp->convertendoArquivoRtfHTMLHistorico($value->laudoantigo_id, $html);
                }
            }

        }

        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['historicoantigo'] = $this->laudo->listarconsultahistoricoantigo($paciente_id);
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['historicoexame'] = $this->laudo->listarexamehistorico($paciente_id);
        // echo '<pre>';
        // var_dump($data['historicoantigo']); 
        // die;

        $this->load->View('ambulatorio/laudoconsultaantigo-form', $data);
    }

    function editaranaminesehistorico() {

        $this->laudo->editaranaminesehistorico();
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function carregaranaminesehistorico($laudoantigo_id) {

        $data['historicoantigo'] = $this->laudo->listarconsultahistoricoantigoeditar($laudoantigo_id);
        $this->load->View('ambulatorio/editarhistoricoconsulta-form', $data);
    }

    function carregarreceituario($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['receita'] = $this->laudo->listarreceita($obj_laudo->_paciente_id);
        $data['operadores'] = $this->operador_m->listarmedicos();

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/receituarioconsulta-form', $data);
    }

    function carregarrotinas($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarmodeloslaudorotinas();
        $data['modelo'] = $this->exametemp->listarmodelosrotinaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['rotina'] = $this->laudo->listarrotina($obj_laudo->_paciente_id);
        $data['operadores'] = $this->operador_m->listarmedicos();

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;


        $operador_id = $this->session->userdata('operador_id');
        $operador = $this->operador_m->listaroperadoratendimento($operador_id);
        $atendimento_dif = @$operador[0]->atendimento_medico;
        if ($atendimento_dif == 't') {
            $this->load->View('ambulatorio/rotinasdif-form', $data);
        } else {
            $this->load->View('ambulatorio/rotinas-form', $data);
        }
    }

    function carregarreceituariosollis($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['receita'] = $this->laudo->listarreceita($obj_laudo->_paciente_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['prescricao'] = $this->laudo->listarprescricao($obj_laudo->_paciente_id, $ambulatorio_laudo_id);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/receituariosollis-form', $data);
    }

    function carregarprescricao($ambulatorio_laudo_id) {
//        var_dump($obj_laudo->_paciente_id);die;
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['receita'] = $this->laudo->listarreceita($obj_laudo->_paciente_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['prescricao'] = $this->laudo->listarprescricoes($obj_laudo->_paciente_id);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/prescricao-form', $data);
    }

    function editarprescricao($prescricao_id, $ambulatorio_laudo_id, $paciente_id) {
//        var_dump($paciente_id);die;
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();
        $data['receita'] = $this->laudo->listarreceita($paciente_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['prescricao'] = $this->laudo->listarprescricoes($paciente_id);
        $data['paciente'] = $this->laudo->listarprescricoespaciente($paciente_id);
        $data['prescricaosollis'] = $this->laudo->listarprescricao($obj_laudo->_paciente_id, $ambulatorio_laudo_id);

        $data['prescricao_id'] = $prescricao_id;
        $data['paciente_id'] = $paciente_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/receituariosollis-form', $data);
    }

    function excluirprescricao($prescricao_id, $ambulatorio_laudo_id, $paciente_id) {


        $valida = $this->laudo->excluirprescricao($prescricao_id);
        if ($valida == 0) {
            $data['mensagem'] = 'Sucesso ao excluir Prescricao';
        } else {
            $data['mensagem'] = 'Erro ao excluir Prescricao. Opera&ccedil;&atilde;o cancelada.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarprescricao/$ambulatorio_laudo_id/$paciente_id");
    }

    function excluirmedicamento($receituario_sollis_id, $prescricao_id, $ambulatorio_laudo_id, $paciente_id) {


        $valida = $this->laudo->excluirmedicamento($receituario_sollis_id);
        if ($valida == 0) {
            $data['mensagem'] = 'Sucesso ao excluir o item';
        } else {
            $data['mensagem'] = 'Erro ao excluir o item. Opera&ccedil;&atilde;o cancelada.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/editarprescricao/$prescricao_id/$ambulatorio_laudo_id/$paciente_id");
    }

    function gravarprescricao($ambulatorio_laudo_id, $paciente_id) {


        $prescricao_id = $this->laudo->gravarprescricao($ambulatorio_laudo_id);
        if ($prescricao_id == 'banana') {
//            $data['mensagem'] = 'Erro ao gravar setor. Setor já cadastrado.';
        } else {
//            $data['mensagem'] = 'Sucesso ao gravar setor.';
        }
//        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarprescricao/$ambulatorio_laudo_id/$paciente_id");
    }

    function carregaratestado($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['lista'] = $this->exametemp->listarautocompletemodelosatestado();
//        $data['contador'] = $this->laudo->contadorlistarreceita($ambulatorio_laudo_id);
        $data['receita'] = $this->laudo->listaratestado($ambulatorio_laudo_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/atestadoconsulta-form', $data);
    }

    function carregarexames($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['lista'] = $this->exametemp->listarautocompletemodelossolicitarexames();
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
//        $data['contador'] = $this->laudo->contadorlistarreceita($ambulatorio_laudo_id);
        $data['receita'] = $this->laudo->listarexame($ambulatorio_laudo_id);
        $data['modelo_aut'] = $this->exametemp->listarmodelosexameautomatico();
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
            // $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
            $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
        } else {
            // $assinatura = "";
            $data['assinatura'] = "";
        }
        // var_dump($data['assinatura']); die;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/solicitarexame-form', $data);
    }

    function imprimirmodeloaih($ambulatorio_laudo_id) {
        $this->load->helper('directory');
        $empresa_id = $this->session->userdata('empresa_id');
        $operador = $this->session->userdata('operador_id');
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresapermissoes($empresa_id);
        $data['operadores'] = $this->operador_m->listaroperador($operador);
//        $data['texto'] = $texto;
        $this->load->View('internacao/impressaoaih', $data);
    }

    function impressaosolicitacaoexames($guia_id) {
        $data['exames'] = $this->guia->listarexamesguia($guia_id);
        $this->load->View('ambulatorio/impressaosolicitacaoexames', $data);
    }

    function alterarnomeimagem($exame_id, $imagem_id) {
        $data['listaendoscopia'] = $this->laudo->listarnomeendoscopia();
        $data['exame_id'] = $exame_id;
        $data['imagem_id'] = $imagem_id;
        // var_dump($imagem_id); die;
        $data['contador'] = $this->laudo->contadorimagem2($exame_id, $imagem_id);
        $this->load->View('ambulatorio/alterarnomeimagem-form', $data);
    }

    function renomearimagem($exame_id) {

        $imagem_id = trim($_POST['imagem_id']);
        $sequencia = $_POST['sequencia'];
        $deletar_nome = $sequencia . ".jpg";
//        if ($imagem_id == 7){
//            $this->laudo->deletarregistroimagem($exame_id, $imagem_id);
//        }
//        var_dump($sequencia); die;
        $contador = $this->laudo->contadorimagem($exame_id, $deletar_nome);
//        var_dump($imagem_id);
        //    echo '-------------';
        // var_dump($sequencia);
        // die;
//        $imagem_id = '111';
        $oldname = "./upload/$exame_id/$imagem_id";
        // var_dump($sequencia);
        // die;
        $nome = $_POST['nome'];
        $complemento = $_POST['complemento'];
        $novonome = 'Foto ' . $sequencia;




        if ($nome != '') {
            $novonome = 'Foto ' . $sequencia . " - " . $nome;
        } elseif ($complemento != '') {
            $novonome = 'Foto ' . $sequencia . " - " . $complemento;
        }
        if ($sequencia == 11) {
            $this->laudo->deletarregistroimagem($exame_id, $imagem_id);
            redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
        } else {

            if (count($contador) == 0) {
                $this->laudo->deletarregistroimagem($exame_id, $deletar_nome);
                $this->laudo->deletarregistroimagem($exame_id, $imagem_id);
                $sequencia = $sequencia . ".jpg";
                $this->laudo->gravarnome($exame_id, $sequencia, $novonome, $sequencia);
                $nometemp = "./upload/$exame_id/9999999.jpg";
                $newname = "./upload/$exame_id/$sequencia";

                // var_dump($sequencia);
                // die;
                rename($newname, $nometemp);
                rename($oldname, $newname);
                rename($nometemp, $oldname);
                redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
            } elseif ($sequencia == trim($_POST['imagem_id'])) {
                $this->laudo->deletarregistroimagem($exame_id, $deletar_nome);
                $sequencia = $sequencia . ".jpg";
                $this->laudo->alterarnome($exame_id, $imagem_id, $novonome, $sequencia);
                $nometemp = "./upload/$exame_id/9999999.jpg";
                $newname = "./upload/$exame_id/$sequencia";
                rename($newname, $nometemp);
                rename($oldname, $newname);
                rename($nometemp, $oldname);
                redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
            } else {
                $mensagem = "Imagem Foto " . $sequencia . " já existe";
                $this->session->set_flashdata('message', $mensagem);
                redirect(base_url() . "ambulatorio/laudo/alterarnomeimagem/$exame_id/$imagem_id");
            }
            redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
        }
    }

    function salvarnomeimagem($exame_id, $imagem_id) {
        $data['listaendoscopia'] = $this->laudo->listarnomeendoscopia();
        $data['exame_id'] = $exame_id;
        $data['imagem_id'] = $imagem_id;
        $this->load->View('ambulatorio/alterarnomeimagem-form', $data);
    }

    function faturamentolaudoxml($args = array()) {
        $this->loadView('ambulatorio/faturamentolaudoxml-form', $args);
    }

    function carregarreceituarioespecial($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceitaespecial();
        $data['receita'] = $this->laudo->listarreceitasespeciaispaciente($ambulatorio_laudo_id, $obj_laudo->_paciente_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/receituarioespecialconsulta-form', $data);
        
    }

    function editarcarregarreceituarioespecial($ambulatorio_laudo_id, $ambulatorio_receituario_especial_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['receita'] = $this->laudo->listarreceitaespecial($ambulatorio_receituario_especial_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/editarreceituarioespecialconsulta-form', $data);
    }

    function editarcarregarreceituario($ambulatorio_laudo_id, $ambulatorio_receituario_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['receita'] = $this->laudo->listareditarreceita($ambulatorio_receituario_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
//        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/editarreceituarioconsulta-form', $data);
    }

    function editarcarregarrotina($ambulatorio_laudo_id, $ambulatorio_rotinas_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['rotina'] = $this->laudo->listareditarrotina($ambulatorio_rotinas_id);
        $data['operadores'] = $this->operador_m->listarmedicos();


        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/editarrotinaconsulta-form', $data);
    }

    function repetircarregarreceituario($ambulatorio_laudo_id, $ambulatorio_receituario_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['receita'] = $this->laudo->listarrepetirreceita($ambulatorio_receituario_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/repetireceituarioconsulta-form', $data);
    }

    function editarcarregaratestado($ambulatorio_laudo_id, $ambulatorio_receituario_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['receita'] = $this->laudo->listareditaratestado($ambulatorio_receituario_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
//        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/editaratestado-form', $data);
    }

    function editarexame($ambulatorio_laudo_id, $ambulatorio_receituario_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['receita'] = $this->laudo->listareditarexame($ambulatorio_receituario_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
//        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/editarsolicitarexame-form', $data);
    }

    function carregarlaudodigitador($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarmodeloslaudo($procedimento_tuss_id, @$obj_laudo->_medico_parecer1);
        $data['linha'] = $this->exametemp->listarmodeloslinha($procedimento_tuss_id);
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);
        $data['padrao'] = $this->laudo->listarlaudopadrao($procedimento_tuss_id);
        $this->laudo->auditoriaLaudo($ambulatorio_laudo_id, 'Entrou no Laudo');
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['mensagem'] = $messagem;
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['empresa'] = $this->guia->listarempresa();
        $data['obj'] = $obj_laudo;
        $data['exame_id'] = $exame_id;
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresapermissao'] = $this->guia->listarempresasaladepermissao($empresa_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        //$this->carregarView($data, 'giah/servidor-form');

        $this->load->View('ambulatorio/laudodigitador-form_1', $data);
    }

    function todoslaudo($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $guia_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['lista'] = $this->exametemp->listarmodeloslaudo($procedimento_tuss_id, @$obj_laudo->_medico_parecer1);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $grupo = @$obj_laudo->_grupo;
        $procedimento = $this->laudo->listarprocedimentos($guia_id, $grupo);
        $data['grupo'] = $grupo;
        $data['mensagem'] = $messagem;
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $uniaoprocedimento = "";
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresapermissao'] = $this->guia->listarempresasaladepermissao($empresa_id);

        foreach ($procedimento as $value) {
            $procedimentos = $value->procedimento_tuss_id;
            $contador = $this->laudo->contadorlistarlaudopadrao($procedimentos);
            $item = $this->laudo->listarlaudopadrao($procedimentos);
            if ($contador > 0) {
                $uniaoprocedimento = $uniaoprocedimento . '<br><u><b>' . $item['0']->procedimento . '</u></b>';
                $uniaoprocedimento = $uniaoprocedimento . '<br>' . $item['0']->texto;
            } else {
                $uniaoprocedimento = $uniaoprocedimento . '<br><u><b>' . $value->nome . '</u></b><br>';
            }
        }
        $data['padrao'] = $uniaoprocedimento;
        $this->load->View('ambulatorio/laudodigitadortotal-form_1', $data);
    }

    function carregarlaudoanterior($paciente_id, $ambulatorio_laudo_id) {
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);
        $data['total'] = $this->laudo->listarlaudoscontador($paciente_id, $ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $ambulatorio_laudo_id;

        $this->load->View('ambulatorio/laudoanterior-lista', $data);
    }

    function listarxml($convenio, $paciente_id) {
        $data['convenio'] = $convenio;
        $data['paciente_id'] = $paciente_id;
        $this->loadView('ambulatorio/xml-lista', $data);
    }

    function carregarlaudoantigo($id) {
        $data['id'] = $id;
        $data['laudo'] = $this->laudo->listarlaudoantigoimpressao($id);
        $this->load->View('ambulatorio/laudoantigo-form', $data);
    }

    function adicionalcabecalho($cabecalho, $laudo) {
        $ano = 0;
        $mes = 0;
//        $cabecalho = $impressaolaudo[0]->texto;
        $cabecalho = str_replace("_paciente_", $laudo['0']->paciente, $cabecalho);
        $cabecalho = str_replace("_sexo_", $laudo['0']->sexo, $cabecalho);
        $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($laudo['0']->nascimento)), $cabecalho);
        $cabecalho = str_replace("_convenio_", $laudo['0']->convenio, $cabecalho);
        $cabecalho = str_replace("_sala_", $laudo['0']->sala, $cabecalho);
        $cabecalho = str_replace("_CPF_", $laudo['0']->cpf, $cabecalho);
        $cabecalho = str_replace("_RG_", $laudo['0']->rg, $cabecalho);
        $cabecalho = str_replace("_solicitante_", $laudo['0']->solicitante, $cabecalho);
        $cabecalho = str_replace("_data_", substr($laudo['0']->data, 8, 2) . '/' . substr($laudo['0']->data, 5, 2) . '/' . substr($laudo['0']->data, 0, 4), $cabecalho);
        $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($laudo[0]->data_cadastro)), $cabecalho);
        $cabecalho = str_replace("_medico_", $laudo['0']->medico, $cabecalho);
        $cabecalho = str_replace("_revisor_", $laudo['0']->medicorevisor, $cabecalho);
        $cabecalho = str_replace("_procedimento_", $laudo['0']->procedimento, $cabecalho);
        $cabecalho = str_replace("_laudo_", $laudo['0']->texto, $cabecalho);
        $cabecalho = str_replace("_nomedolaudo_", $laudo['0']->cabecalho, $cabecalho);
        $cabecalho = str_replace("_queixa_", $laudo['0']->cabecalho, $cabecalho);
        $cabecalho = str_replace("_peso_", $laudo['0']->peso, $cabecalho);
        $cabecalho = str_replace("_altura_", $laudo['0']->altura, $cabecalho);
        $cabecalho = str_replace("_cid1_", $laudo['0']->cid1, $cabecalho);
        $cabecalho = str_replace("_cid2_", $laudo['0']->cid2, $cabecalho);
        $cabecalho = str_replace("_guia_", $laudo[0]->guia_id, $cabecalho);
        $operador_id = $this->session->userdata('operador_id');
        $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
        @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
        @$cabecalho = str_replace("_usuario_salvar_", $laudo['laudo'][0]->usuario_salvar, $cabecalho);
        $cabecalho = str_replace("_telefone1_", $laudo[0]->telefone, $cabecalho);
        $cabecalho = str_replace("_telefone2_", $laudo[0]->celular, $cabecalho);
        $cabecalho = str_replace("_whatsapp_", $laudo[0]->whatsapp, $cabecalho);
        $cabecalho = str_replace("_prontuario_antigo_", $laudo[0]->prontuario_antigo, $cabecalho);
        $cabecalho = str_replace("_prontuario_", $laudo[0]->paciente_id, $cabecalho);
        $cabecalho = str_replace("_nome_mae_", $laudo[0]->nome_mae, $cabecalho);
        $cabecalho = str_replace("_especialidade_", $laudo[0]->grupo, $cabecalho);
        $cabecalho = str_replace("_endereco_", @$laudo[0]->logradouro." - ". @$laudo[0]->numero. ", ".@$laudo[0]->bairro.", ".@$laudo[0]->cidade, $cabecalho);
        
        
        $dataFuturo2 = date("Y-m-d");
        $dataAtual2 = $laudo[0]->nascimento;
        $date_time2 = new DateTime($dataAtual2);
        $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
        $idade = $diff2->format('%Y anos'); 
     
        $ano = $diff2->format('%Y'); 
        $mes = $diff2->format('%m');  
        
        if ($ano > 1) {
           $s = "s"; 
        }else{
           $s = ""; 
        }
        
        if ($ano == 0) { 
             if ($mes > 1) {
                    $sm = "es";
                }else{
                    $sm = "";
                } 
            $ano_formado = $mes." mes".$sm;   
        }else{
            if ($mes > 0) { 
                if ($mes > 1) {
                    $sm = " meses";
                }else{
                    $sm = " mês";
                }
                  $ano_formado = $ano." ano".$s." e ".$mes.$sm; 
            }else{
                  $ano_formado = $ano." ano".$s;  
            }
        } 
        
        $cabecalho = str_replace("_idade_", $ano_formado, $cabecalho);
          
        return $cabecalho;
    }

    function templateParaTexto($json_obj) {
        $array_obj = json_decode($json_obj);
        $string = '';
        // echo '<pre>';
        // var_dump($array_obj); die;
        foreach ($array_obj as $key => $value) {
            if ($value->tipo == 'checkbox') {
                if ($value->value != '') {
                    $string_value = 'Verdadeiro';
                } else {
                    $string_value = 'Falso';
                }
            } elseif ($value->tipo == 'multiplo') {
                $string_array = '';
                foreach ($value->value as $key => $item) {
                    $string_array .= " $item,";
                }
                $string_value = $string_array;
            } else {
                $string_value = $value->value;
            }
            $string .= "<p style='font-weight: bold'>{$value->nome}</p>
            <p>{$string_value}</p>";
        }
        // echo '<pre>';
        // var_dump($string); die;
        return $string;
    }

    function impressaolaudosalvar($ambulatorio_laudo_id, $exame_id, $paciente_id) {
        $this->load->plugin('mpdf');
        $empresa_id = $this->session->userdata('empresa_id');
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        // var_dump($data['laudo'][0]->template_obj); die;
        $this->laudo->auditoriaLaudo($ambulatorio_laudo_id, 'Imprimiu Laudo');
        if($data['laudo'][0]->template_obj != ''){
            $data['laudo'][0]->texto = $this->templateParaTexto($data['laudo'][0]->template_obj);
        }
        $texto = $data['laudo'][0]->texto;
        
        $adendo = $data['laudo'][0]->adendo;
        $data['laudo'][0]->texto = $texto . '<br>' . $adendo;
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
        // var_dump($data['laudo'][0]->texto); die;
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
//        var_dump($data['cabecalhomedico']); die;
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
        // $sem_margins = 't';
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $certificado_medico = $this->guia->certificadomedico($data['laudo'][0]->medico_parecer1);
        $empresapermissao = $this->guia->listarempresasaladepermissao();

         
        //////////////////////////////////////////////////////////////////////////////////////////////////
        //LAUDO CONFIGURÁVEL
        if ($data['empresa'][0]->laudo_config == 't') { 
//            die('morreu');
            $filename = "laudo.pdf";
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
            } else {
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                    $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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

            $diagnosticonivel = '';
            if($data['laudo'][0]->opcoes_diagnostico != ''){
                $opcoes = str_replace("_", ' ', $data['laudo'][0]->opcoes_diagnostico);
                $diagnosticonivel .= '<b>'.$opcoes.'</b>';
            
                    if($data['laudo'][0]->nivel1_diagnostico != ''){
                        $nivel1 = str_replace("_", ' ', $data['laudo'][0]->nivel1_diagnostico);
                        $diagnosticonivel .= '<br><b> Nivel 1 -</b> '.$nivel1;
            
                        if($data['laudo'][0]->nivel2_diagnostico != ''){
                            $nivel2 = str_replace("_", ' ', $data['laudo'][0]->nivel2_diagnostico);
                            $diagnosticonivel .= '<br><b> Nivel 2 -</b> '.$nivel2;
            
                                if($data['laudo'][0]->nivel3_diagnostico != ''){
                                    $nivel3 = str_replace("_", ' ', $data['laudo'][0]->nivel3_diagnostico);
                                    $diagnosticonivel .= '<br><b> Nivel 3 -</b> '.$nivel3;
                                }
                        }
                    }
            }

          

            $cabecalho = str_replace("_paciente_", $data['laudo'][0]->paciente, $cabecalho);
            $cabecalho = str_replace("_sexo_", $data['laudo'][0]->sexo, $cabecalho);
            $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($data['laudo'][0]->nascimento)), $cabecalho);
            $cabecalho = str_replace("_convenio_", $data['laudo'][0]->convenio, $cabecalho);
            $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
            $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
            $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
            $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
            $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
            $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($data['laudo'][0]->data_cadastro)), $cabecalho);
            $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
            $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
            $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
            $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
            $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
            $operador_id = $this->session->userdata('operador_id');
            $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
            @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
            @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
            $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
            $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
            $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
            $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
            $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
            $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
            $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);



             
            $dataFuturo2 = date("Y-m-d");
            $dataAtual2 = $data['laudo'][0]->nascimento;
            $date_time2 = new DateTime($dataAtual2);
            $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
            $idade = $diff2->format('%Y anos');
            $cabecalho = str_replace("_idade_", $idade, $cabecalho);

            $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
            $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']); 

            $cabecalho = str_replace("_diagnostico_", $diagnosticonivel, $cabecalho);
            $cabecalho = str_replace("_setor_", $data['laudo'][0]->setor, $cabecalho);
            $cabecalho = str_replace("_observacao_", $data['laudo'][0]->observacoes, $cabecalho);

        //    print_r($cabecalho);
        //      die('morreu'); 
            if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
                $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
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

            $nome = str_replace(' ','_',$data['laudo'][0]->procedimento);
            $nome = str_replace('/','_',$data['laudo'][0]->procedimento);
            $filename = $nome.'_'.substr($data['laudo'][0]->data, 8, 2) . '_' . substr($data['laudo'][0]->data, 5, 2) . '_' . substr($data['laudo'][0]->data, 0, 4).'.pdf';

            $this->laudo->salvararquivolaudo($paciente_id, $filename, $ambulatorio_laudo_id, $data['laudo'][0]->medico_parecer1);
                 
                if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                    $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                    $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
                    pdfsalvarlaudo($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $ambulatorio_laudo_id); 
                }else{

                if ($sem_margins == 't') {
                    pdfsalvarlaudo($html, $filename, $cabecalho, $rodape, '', 0, 0, 0,$ambulatorio_laudo_id);
                } else {
                    pdfsalvarlaudo($html, $filename, $cabecalho, $rodape, '', 9,0,15, $ambulatorio_laudo_id);
                }

                }
        }
    }

    function impressaolaudo($ambulatorio_laudo_id, $exame_id) {
        $this->load->plugin('mpdf');
        $empresa_id = $this->session->userdata('empresa_id');
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        // var_dump($data['laudo'][0]->template_obj); die;
        $this->laudo->auditoriaLaudo($ambulatorio_laudo_id, 'Imprimiu Laudo');
        if($data['laudo'][0]->template_obj != ''){
            $data['laudo'][0]->texto = $this->templateParaTexto($data['laudo'][0]->template_obj);
        }
        $texto = $data['laudo'][0]->texto;
        
        $adendo = $data['laudo'][0]->adendo;
        $data['laudo'][0]->texto = $texto . '<br>' . $adendo;
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
        // var_dump($data['laudo'][0]->texto); die;
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
//        var_dump($data['cabecalhomedico']); die;
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
        // $sem_margins = 't';
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $certificado_medico = $this->guia->certificadomedico($data['laudo'][0]->medico_parecer1);
        $empresapermissao = $this->guia->listarempresasaladepermissao();

         
        //////////////////////////////////////////////////////////////////////////////////////////////////
        //LAUDO CONFIGURÁVEL
        if ($data['empresa'][0]->laudo_config == 't') { 
//            die('morreu');
            $filename = "laudo.pdf";
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
            } else {
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                    $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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

            $diagnosticonivel = '';
            if($data['laudo'][0]->opcoes_diagnostico != ''){
                $opcoes = str_replace("_", ' ', $data['laudo'][0]->opcoes_diagnostico);
                $diagnosticonivel .= '<b>'.$opcoes.'</b>';
            
                    if($data['laudo'][0]->nivel1_diagnostico != ''){
                        $nivel1 = str_replace("_", ' ', $data['laudo'][0]->nivel1_diagnostico);
                        $diagnosticonivel .= '<br><b> Nivel 1 -</b> '.$nivel1;
            
                        if($data['laudo'][0]->nivel2_diagnostico != ''){
                            $nivel2 = str_replace("_", ' ', $data['laudo'][0]->nivel2_diagnostico);
                            $diagnosticonivel .= '<br><b> Nivel 2 -</b> '.$nivel2;
            
                                if($data['laudo'][0]->nivel3_diagnostico != ''){
                                    $nivel3 = str_replace("_", ' ', $data['laudo'][0]->nivel3_diagnostico);
                                    $diagnosticonivel .= '<br><b> Nivel 3 -</b> '.$nivel3;
                                }
                        }
                    }
            }

          

            $cabecalho = str_replace("_paciente_", $data['laudo'][0]->paciente, $cabecalho);
            $cabecalho = str_replace("_sexo_", $data['laudo'][0]->sexo, $cabecalho);
            $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($data['laudo'][0]->nascimento)), $cabecalho);
            $cabecalho = str_replace("_convenio_", $data['laudo'][0]->convenio, $cabecalho);
            $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
            $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
            $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
            $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
            $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
            $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($data['laudo'][0]->data_cadastro)), $cabecalho);
            $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
            $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
            $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
            $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
            $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
            $operador_id = $this->session->userdata('operador_id');
            $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
            @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
            @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
            $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
            $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
            $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
            $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
            $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
            $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
            $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);



             
            $dataFuturo2 = date("Y-m-d");
            $dataAtual2 = $data['laudo'][0]->nascimento;
            $date_time2 = new DateTime($dataAtual2);
            $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
            $idade = $diff2->format('%Y anos');
            $cabecalho = str_replace("_idade_", $idade, $cabecalho);

            $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
            $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']); 

            $cabecalho = str_replace("_diagnostico_", $diagnosticonivel, $cabecalho);
            $cabecalho = str_replace("_setor_", $data['laudo'][0]->setor, $cabecalho);
            $cabecalho = str_replace("_observacao_", $data['laudo'][0]->observacoes, $cabecalho);

        //    print_r($cabecalho);
        //      die('morreu'); 
            if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
                $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
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

            
            if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);

                if(isset($json_post->access_token)){

                    $assinatura_service = $this->certificadoAPI->signature($json_post->access_token);

                    if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                        $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                        $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
                        pdfcertificado($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $ambulatorio_laudo_id); 
                    }else{
        
                        if ($sem_margins == 't') {
                            pdfcertificado($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $ambulatorio_laudo_id);
                        } else {
                            pdfcertificado($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $ambulatorio_laudo_id);
                        }

                    }

                       $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                       $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);

                        $this->db->select('link_certificado');
                        $this->db->from('tb_empresa');
                        $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                        $query = $this->db->get();
                        $return = $query->result();
                        $link = $return[0]->link_certificado;
                    
                        $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
        
                        sleep(5);
                        $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                        unlink($local_salvamento.'/laudo.pdf');

                        redirect($url);

                }else{
                    if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                        $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                        $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
                        pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0); 
                    }else{
    
                    if ($sem_margins == 't') {
                        pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
                    } else {
                        pdf($html, $filename, $cabecalho, $rodape);
                    }
    
                    }
                }

            }else{

                 
                if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                    $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                    $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
                    pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0); 
                }else{

                if ($sem_margins == 't') {
                    pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
                } else {
                    pdf($html, $filename, $cabecalho, $rodape);
                }

                }

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
                    $cabecalho = "<table><tr><td>$cabecalho_config</td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_agenda_exames, 8, 2) . '/' . substr($data['laudo']['0']->data_agenda_exames, 5, 2) . '/' . substr($data['laudo']['0']->data_agenda_exames, 0, 4) . "</td></tr></table>";
//                    }
                } else {
//                    if ($data['empresapermissoes'][0]->alterar_data_emissao == 't') {
                    $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//                    } else {
//                        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_agenda_exames, 8, 2) . '/' . substr($data['laudo']['0']->data_agenda_exames, 5, 2) . '/' . substr($data['laudo']['0']->data_agenda_exames, 0, 4) . "</td></tr></table>";
//                    }
                }


                if ($data['empresa'][0]->rodape_config == 't') {
                    $rodape = $rodape_config;
                } else {
                    $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
                }

                if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                    $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
    
                    if(isset($json_post->access_token)){
                            pdfcertificado($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $ambulatorio_laudo_id);


                           $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                           $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
    
                            $this->db->select('link_certificado');
                            $this->db->from('tb_empresa');
                            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                            $query = $this->db->get();
                            $return = $query->result();
                            $link = $return[0]->link_certificado;
                        
                            $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
            
                            sleep(5);
                            $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                            unlink($local_salvamento.'/laudo.pdf');
    
                            redirect($url);
    
                    }else{
                        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
                        pdf($html, $filename, $cabecalho, $rodape);
                        $this->load->View('ambulatorio/impressaolaudo_1', $data);
                    }
    
                }else{
    
                    $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
                    pdf($html, $filename, $cabecalho, $rodape);
                    $this->load->View('ambulatorio/impressaolaudo_1', $data);
    
                }

            }


            if ($data['empresa'][0]->impressao_laudo == 33) { // ValeImagem
                $filename = "laudo.pdf";
                if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                } else {
                    if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                        $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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
                $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
                $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
                $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
                $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
                $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
                $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
                $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
                $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
                $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
                $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
                $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
                $operador_id = $this->session->userdata('operador_id');
                $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
                @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
                @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
                $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
                $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
                $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
                $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
                $cabecalho = str_replace("_diagnostico_", $diagnosticonivel, $cabecalho);
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



                if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                    $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
                    $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
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

                
                        if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                            $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
            
                            if(isset($json_post->access_token)){

                                pdfcertificado($html, $filename, $cabecalho, $rodape_t, '', 9, 0, 15, $ambulatorio_laudo_id);
                                
                                   $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                                   $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
            
                                    $this->db->select('link_certificado');
                                    $this->db->from('tb_empresa');
                                    $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                                    $query = $this->db->get();
                                    $return = $query->result();
                                    $link = $return[0]->link_certificado;
                                
                                    $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
                    
                                    sleep(5);
                                    $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                                    unlink($local_salvamento.'/laudo.pdf');
            
                                    redirect($url);
            
                            }else{
                                pdf($html, $filename, $cabecalho, $rodape_t, '', 0);
                            }
            
                        }else{
            
                            pdf($html, $filename, $cabecalho, $rodape_t, '', 0);
            
                        }
            }

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////
            elseif ($data['empresa'][0]->impressao_laudo == 10) {//CLINICA MED
                $filename = "laudo.pdf";
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $cabecalho = "<table width=100% border=1><tr> <td>$cabecalho_config</td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                } else {
                    $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='180px' src='img/cabecalho.jpg'></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                }

                if ($data['empresa'][0]->rodape_config == 't') {
                    $rodape = $rodape_config;
                } else {
                    $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
                }

                if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                    $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
    
                    if(isset($json_post->access_token)){

                        pdfcertificado($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $ambulatorio_laudo_id);
                        
                           $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                           $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
    
                            $this->db->select('link_certificado');
                            $this->db->from('tb_empresa');
                            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                            $query = $this->db->get();
                            $return = $query->result();
                            $link = $return[0]->link_certificado;
                        
                            $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
            
                            sleep(5);
                            $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                            unlink($local_salvamento.'/laudo.pdf');
    
                            redirect($url);
    
                    }else{
                        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
                        pdf($html, $filename, $cabecalho, $rodape);
                        $this->load->View('ambulatorio/impressaolaudo_1', $data);
                    }
    
                }else{
    
                        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
                        pdf($html, $filename, $cabecalho, $rodape);
                        $this->load->View('ambulatorio/impressaolaudo_1', $data);
    
                }

            }

            // //////////////////////////////////////////////////////////////////////////////////////////////////////////////       
            elseif ($data['empresa'][0]->impressao_laudo == 11) {//CLINICA MAIS
                $filename = "laudo.pdf";
                //            var_dump( $data['laudo']['0']->carimbo); die;
                $cabecalho = $cabecalho_config;
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $cabecalho = "<table><tr><td>$cabecalho_config</td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br>EXAME: " . $data['laudo']['0']->cabecalho . "<br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
                } else {
                    $cabecalho = "<table><tr><td><img align = 'left'  width='300px' height='90px' src='img/logomais.png'></td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br>EXAME: " . $data['laudo']['0']->cabecalho . "<br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
                }

                if ($data['laudo']['0']->situacao == "DIGITANDO") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt; text-align:center;'><tr><td>" . $data['laudo']['0']->carimbo . "</td></tr>
                <tr><td><center></td></tr></table><img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
                } elseif ($data['laudo']['0']->situacao == "FINALIZADO") {
                    //                echo $data['laudo']['0']->carimbo;
                    if ($data['empresa'][0]->rodape_config == 't') {
                        //                $cabecalho = $cabecalho_config;
                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table>$rodape_config<br><br><br>";
                    } else {
                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table><img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'><br><br><br>";
                    }
                }

                if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                    $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
    
                    if(isset($json_post->access_token)){

                        pdfcertificado($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $ambulatorio_laudo_id);
                        
                           $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                           $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
    
                            $this->db->select('link_certificado');
                            $this->db->from('tb_empresa');
                            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                            $query = $this->db->get();
                            $return = $query->result();
                            $link = $return[0]->link_certificado;
                        
                            $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
            
                            sleep(5);
                            $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                            unlink($local_salvamento.'/laudo.pdf');
    
                            redirect($url);
    
                    }else{
                        $html = $this->load->view('ambulatorio/impressaolaudo_1pacajus', $data, true);
                        pdf($html, $filename, $cabecalho, $rodape);
                        $this->load->View('ambulatorio/impressaolaudo_1pacajus', $data);
                    }
    
                }else{
    
                    $html = $this->load->view('ambulatorio/impressaolaudo_1pacajus', $data, true);
                    pdf($html, $filename, $cabecalho, $rodape);
                    $this->load->View('ambulatorio/impressaolaudo_1pacajus', $data);
    
                }

            }

            ////////////////////////////////////////////////////////////////////////////////////////////
            elseif ($data['empresa'][0]->impressao_laudo == 6) {//CLINICA DEZ
                $filename = "laudo.pdf";
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $cabecalho = "<table><tr><td>$cabecalho_config</td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br>EXAME: " . $data['laudo']['0']->cabecalho . "<br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
                } else {
                    $cabecalho = "<table><tr><td><img align = 'left'  width='300px' height='90px' src='img/logomais.png'></td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br>EXAME: " . $data['laudo']['0']->cabecalho . "<br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
                }
                //            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='90px' src='img/clinicadez.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                if ($data['empresa'][0]->rodape_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table><table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
                } else {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table><table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
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
    <td width='30px'></td><td>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "</td><td>Sexo:" . $data['laudo']['0']->sexo . "</td>
    </tr>
    </tr>
    </tr><tr><td>&nbsp;</td></tr>
    <tr>
    </table>";
                $rodape = "";
                if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer2 == "") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td width='400px'></td><td><img align = 'Right'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></tr><tr><td>&nbsp;</td></tr></table>";
                } elseif ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer2 != "") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td width='30px'></td><td><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td><td width='30px'></td><td><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer2 . ".jpg'></td></tr></tr><tr><td>&nbsp;</td></tr></table>";
                }
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
    <td width='30px'></td><td>Solicitante: Dr(a). " . strtoupper($data['laudo']['0']->solicitante) . "</td><td>Data de Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td>
    </tr>
    <tr>
    <td width='30px'></td><td>Covenio: " . $data['laudo']['0']->convenio . "</td>
    </tr>
    </tr>
    </tr><tr><td>&nbsp;</td></tr>
    <tr>
    </table>";
                $rodape = "";
                if ($data['laudo']['0']->situacao == "FINALIZADO") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table>";
                } else {
                    if ($data['laudo']['0']->medico_parecer1 == 929) {

                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                <tr><td><center>Ultrassonografista</td></tr>
                <tr><td><center>CRM" . $data['laudo']['0']->conselho . "/CBR01701</td></tr></table>";
                    } else {
                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
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
    <td width='30px'></td><td>Solicitante: Dr(a). " . strtoupper($data['laudo']['0']->solicitante) . "</td><td>Data de Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td>
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
    <td width='30px'></td><td>Solicitante: Dr(a). " . strtoupper($data['laudo']['0']->solicitante) . "</td><td>Data de Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td>
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

                if ($data['laudo']['0']->situacao == "FINALIZADO") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table>";
                } else {
                    if ($data['laudo']['0']->medico_parecer1 == 929) {

                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                <tr><td><center>Ultrassonografista</td></tr>
                <tr><td><center>CRM" . $data['laudo']['0']->conselho . "/CBR01701</td></tr></table>";
                    } else {
                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
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
    <td>INDICA&Ccedil;&Atilde;O: " . $data['laudo']['0']->indicacao . "</td><td>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
    </tr>

    </tr>
    </tr><tr><td>&nbsp;</td></tr>
    <tr>
    </table>";
                $rodape = "";
                if ($data['laudo']['0']->situacao == "FINALIZADO") {
                    $rodape = "<table  width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr>"
                            . "<tr><td><img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'></td></tr>"
                            . "</table> ";
                } else {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr>"
                            . "<tr><td><img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'></td></tr>"
                            . "</table> ";
                }
                $grupo = 'laboratorial';

                $html = $this->load->view('ambulatorio/impressaolaudo_5', $data, true);


                if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                    $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
    
                    if(isset($json_post->access_token)){

                        pdfcertificado($html, $filename, $cabecalho, $rodape, $grupo, 9, 0, 15, $ambulatorio_laudo_id);
                        
                           $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                           $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
    
                            $this->db->select('link_certificado');
                            $this->db->from('tb_empresa');
                            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                            $query = $this->db->get();
                            $return = $query->result();
                            $link = $return[0]->link_certificado;
                        
                            $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
            
                            sleep(5);
                            $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                            unlink($local_salvamento.'/laudo.pdf');
    
                            redirect($url);
    
                    }else{
                        
                            pdf($html, $filename, $cabecalho, $rodape, $grupo);
                            $this->load->View('ambulatorio/impressaolaudo_5', $data);
                    }
    
                }else{
                    
                pdf($html, $filename, $cabecalho, $rodape, $grupo);
                $this->load->View('ambulatorio/impressaolaudo_5', $data);
    
                }
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
            <td>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "</td><td></td>
            </tr>

            <tr>
            <td colspan='2'><b><center>" . $data['laudo']['0']->cabecalho . "</center></b></td>
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
                $medicoparecer = $data['laudo']['0']->medico_parecer1;
                //            echo "<pre>"; var_dump($data['laudo']['0']);die;
                $cabecalho = "<table><tr><td><center><img align = 'left'  width='1000px' height='90px' src='img/cabecalho.jpg'></center></td></tr>

                        <tr><td >Exame de: " . $data['laudo']['0']->paciente . "</td>----<td >RG : " . $data['laudo']['0']->rg . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "----Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "----Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "----Solicitante: " . $data['laudo']['0']->solicitante . "</td></tr>
                        </table>";
                if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                    $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>           
                        <tr><td >Exame de: " . $data['laudo']['0']->paciente . "</td>----<td >RG : " . $data['laudo']['0']->rg . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "</td></tr>
                        </table>";
                }
                if ($data['laudo']['0']->medico_parecer1 != 929) {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                }
                if ($data['laudo']['0']->medico_parecer1 == 929 && $data['laudo']['0']->situacao != "FINALIZADO") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                }
                if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 929) {
                    $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  width='180px' height='65px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
                }
                if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 930) {
                    $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <img  width='180px' height='65px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
                }
                if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 2483) {
                    $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <img  width='180px' height='65px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
                }
                $grupo = $data['laudo']['0']->grupo;
                $filename = "laudo.pdf";
                $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);


                if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                    $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
    
                    if(isset($json_post->access_token)){

                        pdfcertificado($html, $filename, $cabecalho, $rodape, $grupo, 9, $data['empresa'][0]->impressao_laudo, 15, $ambulatorio_laudo_id);
                        
                           $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                           $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
    
                            $this->db->select('link_certificado');
                            $this->db->from('tb_empresa');
                            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                            $query = $this->db->get();
                            $return = $query->result();
                            $link = $return[0]->link_certificado;
                        
                            $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
            
                            sleep(5);
                            $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                            unlink($local_salvamento.'/laudo.pdf');
    
                            redirect($url);
    
                    }else{
                        
                        pdf($html, $filename, $cabecalho, $rodape, $grupo, 9, $data['empresa'][0]->impressao_laudo);
                        $this->load->View('ambulatorio/impressaolaudo_2', $data);
                    }
    
                }else{
    
                    
                    pdf($html, $filename, $cabecalho, $rodape, $grupo, 9, $data['empresa'][0]->impressao_laudo);
                    $this->load->View('ambulatorio/impressaolaudo_2', $data);
    
                }
            }
            ///////////////////////////////////////////////////////////////////////////////////////////
            elseif ($data['empresa'][0]->impressao_laudo == 9) {//RONALDO BARREIRA FILIAL
                $medicoparecer = $data['laudo']['0']->medico_parecer1;
                //            echo "<pre>"; var_dump($data['laudo']['0']);die;
                $cabecalho = "<table><tr><td><center><img align = 'left'  width='1000px' height='90px' src='img/cabecalho.jpg'></center></td></tr>

                        <tr><td colspan='2'>Exame de: " . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "----Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "----Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "----Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                        </table>";
                if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                    $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>           
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                        </table>";
                }

                if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                    $assinatura = "<img   width='200px' height='100px' src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
                    $data['assinatura'] = "<img   width='200px' height='100px' src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
                } else {
                    $assinatura = "";
                    $data['assinatura'] = "";
                }

                if ($data['cabecalhomedico'][0]->rodape != '' && $data['laudo']['0']->situacao == "FINALIZADO") {
                    $rodape = $data['cabecalhomedico'][0]->rodape;
                    $rodape = str_replace("_assinatura_", $assinatura, $rodape);
                } else {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                }



                $grupo = $data['laudo']['0']->grupo;
                $filename = "laudo.pdf";
                $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);


                if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                    $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
    
                    if(isset($json_post->access_token)){

                        pdfcertificado($html, $filename, $cabecalho, $rodape, $grupo, 9, 0, 15, $ambulatorio_laudo_id);
                        
                           $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                           $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
    
                            $this->db->select('link_certificado');
                            $this->db->from('tb_empresa');
                            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                            $query = $this->db->get();
                            $return = $query->result();
                            $link = $return[0]->link_certificado;
                        
                            $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
            
                            sleep(5);
                            $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                            unlink($local_salvamento.'/laudo.pdf');
    
                            redirect($url);
    
                    }else{
                        
                        pdf($html, $filename, $cabecalho, $rodape, $grupo);
                        $this->load->View('ambulatorio/impressaolaudo_2', $data);
                    }
    
                }else{
    
                    
                    pdf($html, $filename, $cabecalho, $rodape, $grupo);
                    $this->load->View('ambulatorio/impressaolaudo_2', $data);
    
                }
            }
            //////////////////////////////////////////////////////////////////////////////       
            else {//GERAL       //este item fica sempre por último
                $filename = "laudo.pdf";
                if ($data['cabecalhomedico'][0]->cabecalho != '') {
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho . "<table><tr><td></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                } else {
                    if ($data['empresa'][0]->cabecalho_config == 't') {
                        $cabecalho = "$cabecalho_config<table><tr><td></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                    } else {
                        if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                            $img = '<img style="width: 100%; height: 40%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
                        } else {
                            $img = "<img align = 'left'style='width: 100%; height: 40%;'  src='img/cabecalho.jpg'>";
                        }
                        $cabecalho = "<table><tr><td>" . $img . "</td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
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
                        if (!file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
                        }
                    }
                }


                $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);


                if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                    $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
    
                    if(isset($json_post->access_token)){

                        if ($sem_margins == 't') {
                            pdfcertificado($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $ambulatorio_laudo_id);
                        } else {
                            pdfcertificado($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $ambulatorio_laudo_id);
                        }
                        
                           $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                           $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
    
                            $this->db->select('link_certificado');
                            $this->db->from('tb_empresa');
                            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                            $query = $this->db->get();
                            $return = $query->result();
                            $link = $return[0]->link_certificado;
                        
                            $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
            
                            sleep(5);
                            $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                            unlink($local_salvamento.'/laudo.pdf');
    
                            redirect($url);
    
                    }else{
                        
                        if ($sem_margins == 't') {
                            pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
                        } else {
                            pdf($html, $filename, $cabecalho, $rodape);
                        }
                        $this->load->View('ambulatorio/impressaolaudo_1', $data);
                    }
    
                }else{
    
                    
                    if ($sem_margins == 't') {
                        pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
                    } else {
                        pdf($html, $filename, $cabecalho, $rodape);
                    }
                    $this->load->View('ambulatorio/impressaolaudo_1', $data);
    
                }
            }
        }
    }

    function impressaolaudo2($ambulatorio_laudo_id, $exame_id) {
    //     $empresapermissao = $this->guia->listarempresasaladepermissao();
       
    //    if($empresapermissao[0]->certificado_digital == 't'){
    //         $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
    //         $assinatura_service = $this->certificadoAPI->signature($json_post->access_token);
    //         $pdf = file_get_contents('http://localhost/clinicas/ambulatorio/laudo/impressaolaudo2/164407/174405');
    //         $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $pdf);
    //    }

    //     echo '<pre>';
    //     print_r($resposta_pdf);
    //     die;

       $this->load->plugin('mpdf');
       $empresa_id = $this->session->userdata('empresa_id');
       $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
       // var_dump($data['laudo'][0]->template_obj); die;
       $this->laudo->auditoriaLaudo($ambulatorio_laudo_id, 'Imprimiu Laudo');
       if($data['laudo'][0]->template_obj != ''){
           $data['laudo'][0]->texto = $this->templateParaTexto($data['laudo'][0]->template_obj);
       }
       $texto = $data['laudo'][0]->texto;
       
       $adendo = $data['laudo'][0]->adendo;
       $data['laudo'][0]->texto = $texto . '<br>' . $adendo;
       $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
       $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
       $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
       $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
       $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
       $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
       $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
       $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
       $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
       $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
       $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
       // var_dump($data['laudo'][0]->texto); die;
       $data['empresa'] = $this->guia->listarempresa($empresa_id);
       $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
       $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
       $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
//        var_dump($data['cabecalhomedico']); die;
       $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
       @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
       @$rodape_config = $data['cabecalho'][0]->rodape;
       $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
       $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
       // $sem_margins = 't';
       $data['exame_id'] = $exame_id;
       $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
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
               if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                   $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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

           $diagnosticonivel = '';
           if($data['laudo'][0]->opcoes_diagnostico != ''){
               $opcoes = str_replace("_", ' ', $data['laudo'][0]->opcoes_diagnostico);
               $diagnosticonivel .= '<b>'.$opcoes.'</b>';
           
                   if($data['laudo'][0]->nivel1_diagnostico != ''){
                       $nivel1 = str_replace("_", ' ', $data['laudo'][0]->nivel1_diagnostico);
                       $diagnosticonivel .= '<br><b> Nivel 1 -</b> '.$nivel1;
           
                       if($data['laudo'][0]->nivel2_diagnostico != ''){
                           $nivel2 = str_replace("_", ' ', $data['laudo'][0]->nivel2_diagnostico);
                           $diagnosticonivel .= '<br><b> Nivel 2 -</b> '.$nivel2;
           
                               if($data['laudo'][0]->nivel3_diagnostico != ''){
                                   $nivel3 = str_replace("_", ' ', $data['laudo'][0]->nivel3_diagnostico);
                                   $diagnosticonivel .= '<br><b> Nivel 3 -</b> '.$nivel3;
                               }
                       }
                   }
           }

         

           $cabecalho = str_replace("_paciente_", $data['laudo'][0]->paciente, $cabecalho);
           $cabecalho = str_replace("_sexo_", $data['laudo'][0]->sexo, $cabecalho);
           $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($data['laudo'][0]->nascimento)), $cabecalho);
           $cabecalho = str_replace("_convenio_", $data['laudo'][0]->convenio, $cabecalho);
           $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
           $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
           $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
           $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
           $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
           $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($data['laudo'][0]->data_cadastro)), $cabecalho);
           $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
           $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
           $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
           $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
           $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
           $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
           $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
           $operador_id = $this->session->userdata('operador_id');
           $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
           @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
           @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
           $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
           $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
           $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
           $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
           $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
           $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
           $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);



            
           $dataFuturo2 = date("Y-m-d");
           $dataAtual2 = $data['laudo'][0]->nascimento;
           $date_time2 = new DateTime($dataAtual2);
           $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
           $idade = $diff2->format('%Y anos');
           $cabecalho = str_replace("_idade_", $idade, $cabecalho);

           $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
           $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']); 

           $cabecalho = str_replace("_diagnostico_", $diagnosticonivel, $cabecalho);
           $cabecalho = str_replace("_setor_", $data['laudo'][0]->setor, $cabecalho);
           $cabecalho = str_replace("_observacao_", $data['laudo'][0]->observacoes, $cabecalho);

       //    print_r($cabecalho);
       //      die('morreu'); 
           if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
               $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
               $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
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


           if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
               $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
               $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
               pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0); 
           }else{

               if ($sem_margins == 't') {
                   pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
               } else {
                   pdf($html, $filename, $cabecalho, $rodape);
               }

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
                   $cabecalho = "<table><tr><td>$cabecalho_config</td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_agenda_exames, 8, 2) . '/' . substr($data['laudo']['0']->data_agenda_exames, 5, 2) . '/' . substr($data['laudo']['0']->data_agenda_exames, 0, 4) . "</td></tr></table>";
//                    }
               } else {
//                    if ($data['empresapermissoes'][0]->alterar_data_emissao == 't') {
                   $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
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
                   if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                       $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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
               $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
               $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
               $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
               $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
               $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
               $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
               $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
               $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
               $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
               $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
               $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
               $operador_id = $this->session->userdata('operador_id');
               $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
               @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
               @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
               $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
               $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
               $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
               $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
               $cabecalho = str_replace("_diagnostico_", $diagnosticonivel, $cabecalho);
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



               if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                   $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
                   $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
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
                   $cabecalho = "<table width=100% border=1><tr> <td>$cabecalho_config</td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
               } else {
                   $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='180px' src='img/cabecalho.jpg'></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
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
                   $cabecalho = "<table><tr><td>$cabecalho_config</td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br>EXAME: " . $data['laudo']['0']->cabecalho . "<br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
               } else {
                   $cabecalho = "<table><tr><td><img align = 'left'  width='300px' height='90px' src='img/logomais.png'></td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br>EXAME: " . $data['laudo']['0']->cabecalho . "<br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
               }

               if ($data['laudo']['0']->situacao == "DIGITANDO") {
                   $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt; text-align:center;'><tr><td>" . $data['laudo']['0']->carimbo . "</td></tr>
               <tr><td><center></td></tr></table><img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
               } elseif ($data['laudo']['0']->situacao == "FINALIZADO") {
                   //                echo $data['laudo']['0']->carimbo;
                   if ($data['empresa'][0]->rodape_config == 't') {
                       //                $cabecalho = $cabecalho_config;
                       $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table>$rodape_config<br><br><br>";
                   } else {
                       $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table><img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'><br><br><br>";
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
                   $cabecalho = "<table><tr><td>$cabecalho_config</td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br>EXAME: " . $data['laudo']['0']->cabecalho . "<br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
               } else {
                   $cabecalho = "<table><tr><td><img align = 'left'  width='300px' height='90px' src='img/logomais.png'></td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br>EXAME: " . $data['laudo']['0']->cabecalho . "<br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
               }
               //            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='90px' src='img/clinicadez.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
               if ($data['empresa'][0]->rodape_config == 't') {
                   //                $cabecalho = $cabecalho_config;
                   $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table><table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
               } else {
                   $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table><table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
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
   <td width='30px'></td><td>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "</td><td>Sexo:" . $data['laudo']['0']->sexo . "</td>
   </tr>
   </tr>
   </tr><tr><td>&nbsp;</td></tr>
   <tr>
   </table>";
               $rodape = "";
               if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer2 == "") {
                   $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td width='400px'></td><td><img align = 'Right'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></tr><tr><td>&nbsp;</td></tr></table>";
               } elseif ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer2 != "") {
                   $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td width='30px'></td><td><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td><td width='30px'></td><td><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer2 . ".jpg'></td></tr></tr><tr><td>&nbsp;</td></tr></table>";
               }
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
   <td width='30px'></td><td>Solicitante: Dr(a). " . strtoupper($data['laudo']['0']->solicitante) . "</td><td>Data de Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td>
   </tr>
   <tr>
   <td width='30px'></td><td>Covenio: " . $data['laudo']['0']->convenio . "</td>
   </tr>
   </tr>
   </tr><tr><td>&nbsp;</td></tr>
   <tr>
   </table>";
               $rodape = "";
               if ($data['laudo']['0']->situacao == "FINALIZADO") {
                   $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table>";
               } else {
                   if ($data['laudo']['0']->medico_parecer1 == 929) {

                       $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
               <tr><td><center>Ultrassonografista</td></tr>
               <tr><td><center>CRM" . $data['laudo']['0']->conselho . "/CBR01701</td></tr></table>";
                   } else {
                       $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
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
   <td width='30px'></td><td>Solicitante: Dr(a). " . strtoupper($data['laudo']['0']->solicitante) . "</td><td>Data de Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td>
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
   <td width='30px'></td><td>Solicitante: Dr(a). " . strtoupper($data['laudo']['0']->solicitante) . "</td><td>Data de Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td>
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

               if ($data['laudo']['0']->situacao == "FINALIZADO") {
                   $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table>";
               } else {
                   if ($data['laudo']['0']->medico_parecer1 == 929) {

                       $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
               <tr><td><center>Ultrassonografista</td></tr>
               <tr><td><center>CRM" . $data['laudo']['0']->conselho . "/CBR01701</td></tr></table>";
                   } else {
                       $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
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
   <td>INDICA&Ccedil;&Atilde;O: " . $data['laudo']['0']->indicacao . "</td><td>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
   </tr>

   </tr>
   </tr><tr><td>&nbsp;</td></tr>
   <tr>
   </table>";
               $rodape = "";
               if ($data['laudo']['0']->situacao == "FINALIZADO") {
                   $rodape = "<table  width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr>"
                           . "<tr><td><img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'></td></tr>"
                           . "</table> ";
               } else {
                   $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
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
           <td>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "</td><td></td>
           </tr>

           <tr>
           <td colspan='2'><b><center>" . $data['laudo']['0']->cabecalho . "</center></b></td>
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
               $medicoparecer = $data['laudo']['0']->medico_parecer1;
               //            echo "<pre>"; var_dump($data['laudo']['0']);die;
               $cabecalho = "<table><tr><td><center><img align = 'left'  width='1000px' height='90px' src='img/cabecalho.jpg'></center></td></tr>

                       <tr><td >Exame de: " . $data['laudo']['0']->paciente . "</td>----<td >RG : " . $data['laudo']['0']->rg . "</td></tr>
                       <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "----Idade: " . $teste . "</td></tr>
                       <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "----Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                       <tr><td>Convenio: " . $data['laudo']['0']->convenio . "----Solicitante: " . $data['laudo']['0']->solicitante . "</td></tr>
                       </table>";
               if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                   $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                       <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                       <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>           
                       <tr><td >Exame de: " . $data['laudo']['0']->paciente . "</td>----<td >RG : " . $data['laudo']['0']->rg . "</td></tr>
                       <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                       <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                       <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "</td></tr>
                       </table>";
               }
               if ($data['laudo']['0']->medico_parecer1 != 929) {
                   $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                       <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
               }
               if ($data['laudo']['0']->medico_parecer1 == 929 && $data['laudo']['0']->situacao != "FINALIZADO") {
                   $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                       <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                       <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
               }
               if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 929) {
                   $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  width='180px' height='65px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
               }
               if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 930) {
                   $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           <img  width='180px' height='65px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
               }
               if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 2483) {
                   $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           <img  width='180px' height='65px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
               }
               $grupo = $data['laudo']['0']->grupo;
               $filename = "laudo.pdf";
               $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
               pdf($html, $filename, $cabecalho, $rodape, $grupo, 9, $data['empresa'][0]->impressao_laudo);
               $this->load->View('ambulatorio/impressaolaudo_2', $data);
           }
           ///////////////////////////////////////////////////////////////////////////////////////////
           elseif ($data['empresa'][0]->impressao_laudo == 9) {//RONALDO BARREIRA FILIAL
               $medicoparecer = $data['laudo']['0']->medico_parecer1;
               //            echo "<pre>"; var_dump($data['laudo']['0']);die;
               $cabecalho = "<table><tr><td><center><img align = 'left'  width='1000px' height='90px' src='img/cabecalho.jpg'></center></td></tr>

                       <tr><td colspan='2'>Exame de: " . $data['laudo']['0']->paciente . "</td></tr>
                       <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "----Idade: " . $teste . "</td></tr>
                       <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "----Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                       <tr><td>Convenio: " . $data['laudo']['0']->convenio . "----Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                       </table>";
               if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                   $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                       <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                       <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>           
                       <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                       <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                       <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                       <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                       </table>";
               }

               if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                   $assinatura = "<img   width='200px' height='100px' src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
                   $data['assinatura'] = "<img   width='200px' height='100px' src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
               } else {
                   $assinatura = "";
                   $data['assinatura'] = "";
               }

               if ($data['cabecalhomedico'][0]->rodape != '' && $data['laudo']['0']->situacao == "FINALIZADO") {
                   $rodape = $data['cabecalhomedico'][0]->rodape;
                   $rodape = str_replace("_assinatura_", $assinatura, $rodape);
               } else {
                   $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                       <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
               }



               $grupo = $data['laudo']['0']->grupo;
               $filename = "laudo.pdf";
               $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
               pdf($html, $filename, $cabecalho, $rodape, $grupo);
               $this->load->View('ambulatorio/impressaolaudo_2', $data);
           }
           //////////////////////////////////////////////////////////////////////////////       
           else {//GERAL       //este item fica sempre por último
               $filename = "laudo.pdf";
               if ($data['cabecalhomedico'][0]->cabecalho != '') {
                   $cabecalho = $data['cabecalhomedico'][0]->cabecalho . "<table><tr><td></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
               } else {
                   if ($data['empresa'][0]->cabecalho_config == 't') {
                       $cabecalho = "$cabecalho_config<table><tr><td></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                   } else {
                       if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                           $img = '<img style="width: 100%; height: 40%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
                       } else {
                           $img = "<img align = 'left'style='width: 100%; height: 40%;'  src='img/cabecalho.jpg'>";
                       }
                       $cabecalho = "<table><tr><td>" . $img . "</td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
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
                       if (!file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
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
   }
    
   function imprimirnovoatendimento($ambulatorio_laudo_id){
        // echo '<pre>';
        // print_r($_POST);
        // die;
        // $url = base_url();
        // if(isset($_POST['relatorios']) || isset($_POST['relatorios']) || isset($_POST['solicitacaoexames'])){
        //     if(isset($_POST['receitaespecial']) && isset($_POST['receitas'])){
        //         echo "
        //             <script type='text/javascript'>
        //             window.open('$url ambulatorio/laudo/impressaolaudotudonovo/$ambulatorio_laudo_id');
        //             window.open('$url ambulatorio/laudo/impressaoreceitatodosnovo/$ambulatorio_laudo_id');
        //             window.open('$url ambulatorio/laudo/imprimirReceitaEspecialTodosnovo/$ambulatorio_laudo_id');
        //         </script>";
        //     }else if(isset($_POST['receitaespecial'])){
        //         echo "<script>
        //             window.open('".base_url()."ambulatorio/laudo/impressaolaudotudonovo/$ambulatorio_laudo_id');
        //             window.open('".base_url()."ambulatorio/laudo/imprimirReceitaEspecialTodosnovo/$ambulatorio_laudo_id');
        //         </script>";
        //     }else if(isset($_POST['receitas'])){
        //         echo "<script>
        //             window.open('".base_url()."ambulatorio/laudo/impressaolaudotudonovo/$ambulatorio_laudo_id');
        //             window.open('".base_url()."ambulatorio/laudo/impressaoreceitatodosnovo/$ambulatorio_laudo_id');
        //         </script>";
        //     }else{
        //         echo "<script>
        //             window.open('".base_url()."ambulatorio/laudo/impressaolaudotudonovo/$ambulatorio_laudo_id');
        //         </script>";
        //     }
            

        // }else{
        //     if(isset($_POST['receitaespecial']) && isset($_POST['receitas'])){
        //         echo "<script>
        //             window.open('".base_url()."ambulatorio/laudo/impressaoreceitatodosnovo/$ambulatorio_laudo_id');
        //             window.open('".base_url()."ambulatorio/laudo/imprimirReceitaEspecialTodosnovo/$ambulatorio_laudo_id');
        //         </script>";
        //     }else if(isset($_POST['receitaespecial'])){
        //         echo "<script>
        //             window.open('".base_url()."ambulatorio/laudo/impressaoreceitatodosnovo/$ambulatorio_laudo_id');
        //         </script>";
        //     }else if(isset($_POST['receitas'])){
        //         echo "<script>
        //             window.open('".base_url()."ambulatorio/laudo/imprimirReceitaEspecialTodosnovo/$ambulatorio_laudo_id');
        //         </script>";
        //     }
        // }


       return true;
    }

    function assinareenviaronline($ambulatorio_laudo_id, $medico, $impressoesGerais, $email, $email2, $certificado_birdID, $textoadicional = null){


        $this->load->helper('directory');
        // $_POST['Imprimir_Assinar'] = explode(",", $impressoesGerais);
        $Imprimir_Assinar = directory_map("./upload/novoatendimento/$ambulatorio_laudo_id/");
        $_POST['Imprimir_Assinar'] = $Imprimir_Assinar;
        $totalImpressoes = count($Imprimir_Assinar);

        $certificado_medico = $this->guia->certificadomedico($medico);
        $empresapermissao = $this->guia->listarempresasaladepermissao();
        $endereco_upload = $empresapermissao[0]->endereco_upload;

        $medico_id = $this->guia->medicoidimpressao($ambulatorio_laudo_id, 'ID');
        $cert_password = $this->guia->medicoidimpressao($ambulatorio_laudo_id, 'SENHA');


        if (!is_dir("./upload/certificadomedico")) {
            mkdir("./upload/certificadomedico");
            $destino = "./upload/certificadomedico";
            chmod($destino, 0777);
        }

        // Verificando se o Certificado Manual existe no Sistema
        $cert_medico = "./upload/certificadomedico/$medico_id.crt";
        if(file_exists($cert_medico)){
            $existe = true;
        }else{
            $existe = false;
        }


        if (!is_dir("./upload/pdfassinados")) {
            mkdir("./upload/pdfassinados");
            $destino = "./upload/pdfassinados";
            chmod($destino, 0777);
        }

        if (!is_dir("./upload/pdfassinados/$ambulatorio_laudo_id")) {
            mkdir("./upload/pdfassinados/$ambulatorio_laudo_id");
            $destino = "./upload/pdfassinados/$ambulatorio_laudo_id";
            chmod($destino, 0777);
        }

        $empresa = $this->guia->listarempresa();
        $atendimento = $this->laudo->listaratendimento($ambulatorio_laudo_id);
        $this->laudo->atualizaremail($atendimento[0]->paciente_id, $email, $email2);
        $atendimento = $this->laudo->listaratendimento($ambulatorio_laudo_id);
        $horario = date('H:i');
        $data = date('d/m/Y', strtotime($atendimento[0]->data));

        if($horario >= '06:00' && $horario <= '12:00'){
            $tempo = 'Bom Dia';
        }elseif($horario >= '12:01' && $horario <= '18:00'){
            $tempo = 'Boa Tarde';
        }else{
            $tempo = 'Boa Noite';
        }

        $extra = 'Essas são receitas eletrônicas, assinadas digitalmente. 
        Não é necessário imprimi-las. Basta encaminhar o arquivo para o farmacêutico, 
        da farmácia onde irá comprar. O farmacêutico tem que abrir o arquivo em um leitor de PDF 
        e ele também tem que assina-la digitalmente. Uma vez assinada digitalmente pela farmácia, 
        ele envia o arquivo para o site https://assinaturadigital.iti.gov.br/ e valida a mesma.';

        if($textoadicional == null && $textoadicional != ''){
            $extra = $textoadicional;
        }

        $mensagem = $tempo.' Sr(a). '.$atendimento[0]->paciente.' <br><br> 
        Segue abaixo em anexo os arquivos referente ao atendimento "'.$atendimento[0]->procedimento.'" realizado na clinica '.$empresa[0]->razao_social.', 
        com o Dr(a). '.$atendimento[0]->medico.', na data de '.$data.'. <br><br><br>

        '.$extra.'
        <br><br><br>

        A '.$empresa[0]->nome.' agradece a sua visita e contamos sempre com sua presença
        <br><br><br><br> <span><u>Obs: Não responda esse email. Email automático</u></span>';
        

        $this->load->library('email');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_user'] = 'stgsaude@gmail.com';
        $config['smtp_pass'] = 'saude@stg*1202';
        $config['validate'] = TRUE;
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";

        $arquivo_pasta = directory_map("./upload/pdfassinados/$ambulatorio_laudo_id/");

        // echo '<pre>';
        // print_r($_POST);
        // die;
        if(count($arquivo_pasta) == 0){

            if($empresapermissao[0]->certificado_digital_manual == 't' && $existe && $cert_password != '' && $endereco_upload != ''){
                $this->load->plugin('tcpdf');

                $pdfscomnome = directory_map("./upload/novoatendimento/$ambulatorio_laudo_id/");
                foreach($pdfscomnome as $nome){
                    Salvarpdfassinado($medico_id, $cert_password, $nome, $ambulatorio_laudo_id, $endereco_upload);
                }


                $arquivo_pasta = directory_map("./upload/pdfassinados/$ambulatorio_laudo_id/");

                $this->email->initialize($config);
                if (@$empresa[0]->email != '') {
                    $this->email->from($empresa[0]->email, $empresa[0]->nome);
                } else {
                    $this->email->from('stgsaude@gmail.com', $empresa[0]->nome);
                }
                
                
                $this->email->to($atendimento[0]->email); 
                
                
                if($atendimento[0]->email2 != '' || $atendimento[0]->email2 != NULL){
                    $this->email->cc($atendimento[0]->email2);
                    }


                // $this->email->to($atendimento[0]->email);
                $this->email->subject("Atendimento Médico");
    
                foreach($arquivo_pasta as $value){
                    $this->email->attach('./upload/pdfassinados/'.$ambulatorio_laudo_id.'/'.$value);
                }
    
                $this->email->message($mensagem);
    
    
                if ($this->email->send()) {
                    $this->laudo->auditoriaenviaronline('Sucesso', $atendimento[0]->paciente_id, $medico);
                    //$info = "Email enviado com sucesso.";
                    echo "<script>alert('PDFs Assinados e Enviados com Sucesso!');</script>";
                } else {
                    $this->laudo->auditoriaenviaronline('Envio de Email malsucedido', $atendimento[0]->paciente_id, $medico);
                    $info = "Envio de Email malsucedido.";
                    echo "<script>alert('PDFs Assinados, Envio de Email malsucedido!');</script>";
                }
                     echo "<script>window.close();</script>";  


            }elseif($empresapermissao[0]->certificado_digital == 't' && $certificado_birdID != 0){

            $this->db->select('link_certificado');
            $this->db->from('tb_empresa');
            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
            $query = $this->db->get();
            $return = $query->result();
            $link = $return[0]->link_certificado;

            
            $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id, $certificado_birdID);


            if(isset($json_post->access_token)){

                $assinatura_service = $this->certificadoAPI->signature($json_post->access_token);


                   $resposta_pdf = $this->certificadoAPI->filetopdf_2($assinatura_service->tcn, $ambulatorio_laudo_id);
                   
                    sleep(5);

                    $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);

                    $a = 0;
                    foreach($_POST['Imprimir_Assinar'] as $nome){
                        $url = $link.'file-transfer/'.$assinatura_service->tcn.'/'.$a;

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        $fp = fopen('./upload/pdfassinados/'.$ambulatorio_laudo_id.'/'.$nome, 'w+');
                        curl_setopt($ch, CURLOPT_FILE, $fp);
                        curl_exec ($ch);
                        curl_close ($ch);
                        fclose($fp);

                        $a++;
                    }
                
                    
                    $arquivo_pasta = directory_map("./upload/pdfassinados/$ambulatorio_laudo_id/");

                    $this->email->initialize($config);
                    if (@$empresa[0]->email != '') {
                        $this->email->from($empresa[0]->email, $empresa[0]->nome);
                    } else {
                        $this->email->from('stgsaude@gmail.com', $empresa[0]->nome);
                    }
        
                   
                        $this->email->to($atendimento[0]->email); 
                        
                        if($atendimento[0]->email2 != '' || $atendimento[0]->email2 != NULL){
                            $this->email->cc($atendimento[0]->email2);
                            }

                    $this->email->subject("Atendimento Médico");
        
                    foreach($arquivo_pasta as $value){
                        $this->email->attach('./upload/pdfassinados/'.$ambulatorio_laudo_id.'/'.$value);
                    }
        
                    $this->email->message($mensagem);
        
        
                    if ($this->email->send()) {
                        $this->laudo->auditoriaenviaronline('Sucesso', $atendimento[0]->paciente_id, $medico);
                        //$info = "Email enviado com sucesso.";
                        echo "<script>alert('PDFs Assinados e Enviados com Sucesso!');</script>";
                    } else {
                        $this->laudo->auditoriaenviaronline('Envio de Email malsucedido', $atendimento[0]->paciente_id, $medico);
                        $info = "Envio de Email malsucedido.";
                        echo "<script>alert('PDFs Assinados, Envio de Email malsucedido!');</script>";
                    }
                         echo "<script>window.close();</script>";  

            }else{
                $this->laudo->auditoriaenviaronline('Falha ao Assinar', $atendimento[0]->paciente_id, $medico);
                echo "<script>alert('Falha ao Assinar, Verique se não ah problemas com a sua Assinatura');</script>";
                echo "<script>window.close();</script>";     
            }

        }else{
                $this->laudo->auditoriaenviaronline('Falha ao Assinar', $atendimento[0]->paciente_id, $medico);
                echo "<script>alert('Falha ao Assinar, Verique se não ah problemas com a sua Assinatura');</script>";
                echo "<script>window.close();</script>"; 
        }



    }else{
        $arquivo_pasta = directory_map("./upload/pdfassinados/$ambulatorio_laudo_id/");

        $this->email->initialize($config);
        if (@$empresa[0]->email != '') {
            $this->email->from($empresa[0]->email, $empresa[0]->nome);
        } else {
            $this->email->from('stgsaude@gmail.com', $empresa[0]->nome);
        }

        $this->email->to($atendimento[0]->email);

        if($atendimento[0]->email2 != '' || $atendimento[0]->email2 != NULL){
        $this->email->cc($atendimento[0]->email2);
        }

        $this->email->subject("Atendimento Médico");

        foreach($arquivo_pasta as $value){
            $this->email->attach('./upload/pdfassinados/'.$ambulatorio_laudo_id.'/'.$value);
        }

        $this->email->message($mensagem);


        if ($this->email->send()) {
            $this->laudo->auditoriaenviaronline('Sucesso', $atendimento[0]->paciente_id, $medico);
            //$info = "Email enviado com sucesso.";
            echo "<script>alert('PDFs Assinados e Enviados com Sucesso!');</script>";
        } else {
            $this->laudo->auditoriaenviaronline('Envio de Email malsucedido', $atendimento[0]->paciente_id, $medico);
            $info = "Envio de Email malsucedido.";
            echo "<script>alert('PDFs Assinados, Envio de Email malsucedido!');</script>";
        }
             echo "<script>window.close();</script>"; 
    }


    }

    function impressaolaudotudonovo_imprimir($ambulatorio_laudo_id){

        $impressao_exame = $this->laudo->impressaonovoatendimento_botaoimprimir($ambulatorio_laudo_id, 'EXAMES');
        $impressao_terapeutica = $this->laudo->impressaonovoatendimento_botaoimprimir($ambulatorio_laudo_id, 'TERAPEUTICAS');
        $impressao_relatorio = $this->laudo->impressaonovoatendimento_botaoimprimir($ambulatorio_laudo_id, 'RELATORIOS');

        if(@$impressao_exame == 0 && @$impressao_terapeutica == 0 && @$impressao_relatorio == 0){
            echo "<script>window.close();</script>";      
         die;
         }



         $_POST['impressao_exame'] = $impressao_exame;
         $_POST['impressao_terapeutica'] = $impressao_terapeutica;
         $_POST['impressao_relatorio'] = $impressao_relatorio;

         $this->load->plugin('mpdf');
         $empresa_id = $this->session->userdata('empresa_id');
         $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
 
         $data['empresa'] = $this->guia->listarempresa($empresa_id);
         $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
         $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
         $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
 
         $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
         @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
         @$rodape_config = $data['cabecalho'][0]->rodape;
         $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
         $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
 
         $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
         $dataFuturo = date("Y-m-d");
         $dataAtual = $data['laudo']['0']->nascimento;
         $date_time = new DateTime($dataAtual);
         $diff = $date_time->diff(new DateTime($dataFuturo));
         $teste = $diff->format('%Ya %mm %dd');
 
         //////////////////////////////////////////////////////////////////////////////////////////////////
         //LAUDO CONFIGURÁVEL
 //            die('morreu');
         $filename = "ImpressaoTodos.pdf";
         if ($data['cabecalhomedico'][0]->cabecalho2 != '') { // Cabeçalho do Profissional 2
             $cabecalho = $data['cabecalhomedico'][0]->cabecalho2;
         } elseif($data['cabecalhomedico'][0]->cabecalho != ''){ // Cabeçalho do Profissional
            $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
        }else {
             if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                 $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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
         $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
         $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
         $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
         $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
         $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
         $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
         $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
         $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
         $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
         $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
         $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
         $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
         $operador_id = $this->session->userdata('operador_id');
         $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
         @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
         @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
         $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
         $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
         $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
         $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
         $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
         $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
         $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);
         $dataFuturo2 = date("Y-m-d");
         $dataAtual2 = $data['laudo'][0]->nascimento;
         $date_time2 = new DateTime($dataAtual2);
         $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
         $idade = $diff2->format('%Y anos');
         $cabecalho = str_replace("_idade_", $idade, $cabecalho);
 
         $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
         $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']);
 
 
 
         if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
             $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
             $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
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


        $html = '';
        if(count($impressao_exame) > 0){
            $solicitacaoExames = $this->imprimirsolicitacaoExamesTodosnovo_imprimir($ambulatorio_laudo_id);
            $html .= $solicitacaoExames;
        }

        if(count($impressao_relatorio) > 0){
             $relatorioExames = $this->imprimirrelatorioExamesTodosnovo_imprimir($ambulatorio_laudo_id);
             $html .= $relatorioExames;
        }

        if(count($impressao_terapeutica) > 0){
             $terapeuticasExames = $this->imprimirterapeuticasExamesTodosnovo_imprimir($ambulatorio_laudo_id);
             $html .= $terapeuticasExames;
        }
        
        
        // $html .= $solicitacaoExames;
        // $html .= $terapeuticasExames;
        // $html .= $relatorioExames;

        if ($data['empresa'][0]->impressao_tipo == 33) {
            // ossi rezaf rop adiv ahnim oiedo uE
            // Isso é pra quando for da vale-imagem, o menor tamanho ficar absurdamente pequeno
            // açneod ?euq roP
            $html = str_replace('xx-small', '5pt', $html);
        }

        // $teste_cabecalho = "$cabecalho";
        if ($sem_margins == 't') {
            pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
        } else {
            pdf($html, $filename, $cabecalho, $rodape);
        }
    }

    function impressaolaudotudonovo($ambulatorio_laudo_id, $solicitar_exames = 0, $relatorios = 0, $terapeuticas = 0, $sem_data = null){
        

        // print_r($relatorios);
        //  die;

        if($solicitar_exames == 0 && $relatorios == 0 && $terapeuticas == 0){
            echo "<script>window.close();</script>";      
         die;
         }


             $_POST['sem_data_e'] = $sem_data;


        // $_POST['repetir_impressoes'] = explode(",", $impressao_array);
        //  echo '<pre>';
        //  print_r($_POST['repetir_impressoes']);
        //  die;
        $this->load->plugin('mpdf');
        $empresa_id = $this->session->userdata('empresa_id');
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);

        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);

        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        //////////////////////////////////////////////////////////////////////////////////////////////////
        //LAUDO CONFIGURÁVEL
//            die('morreu');
        $filename = "ImpressaoTodos.pdf";
        if ($data['cabecalhomedico'][0]->cabecalho2 != '') { // Cabeçalho do Profissional 2
            $cabecalho = $data['cabecalhomedico'][0]->cabecalho2;
        } elseif($data['cabecalhomedico'][0]->cabecalho != ''){ // Cabeçalho do Profissional
           $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
       } else {
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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
        $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
        $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
        $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
        $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
        $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
        $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
        $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
        $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
        $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
        $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
        $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
        $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
        $operador_id = $this->session->userdata('operador_id');
        $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
        @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
        @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
        $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
        $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
        $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
        $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
        $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
        $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
        $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);
        $dataFuturo2 = date("Y-m-d");
        $dataAtual2 = $data['laudo'][0]->nascimento;
        $date_time2 = new DateTime($dataAtual2);
        $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
        $idade = $diff2->format('%Y anos');
        $cabecalho = str_replace("_idade_", $idade, $cabecalho);

        $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
        $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']);



        if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
            $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
            $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
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

        // $html = $this->load->view('ambulatorio/impressaolaudoconfiguravel', $data, true);
        $html = '';
        if($solicitar_exames != 0){
            $solicitacaoExames = $this->imprimirsolicitacaoExamesTodosnovo($ambulatorio_laudo_id, $solicitar_exames);
            $html .= $solicitacaoExames;
         }

        if($relatorios != 0){
            $relatorioExames = $this->imprimirrelatorioExamesTodosnovo($ambulatorio_laudo_id, $relatorios, $solicitar_exames);
            $html .= $relatorioExames;
        }

        if($terapeuticas != 0){
            $terapeuticasExames = $this->imprimirterapeuticasExamesTodosnovo($ambulatorio_laudo_id, $terapeuticas, $solicitar_exames, $relatorios);
            $html .= $terapeuticasExames;
        }
        
        // print_r($solicitacaoExames);
        // die;
        // $html .= $solicitacaoExames;
        // $html .= $terapeuticasExames;
        // $html .= $relatorioExames;
 

        if ($data['empresa'][0]->impressao_tipo == 33) {
            // ossi rezaf rop adiv ahnim oiedo uE
            // Isso é pra quando for da vale-imagem, o menor tamanho ficar absurdamente pequeno
            // açneod ?euq roP
            $html = str_replace('xx-small', '5pt', $html);
        }

                $validarcert ='<div style="border: groove; width:180px; text-align: center; margin: 0pt 0pt 0pt 350pt;">Validar Certificado: <br> <a target="_blank" href="https://assinaturadigital.iti.gov.br/">validator.docusign.com</a></div>';
        
        $validarcert .= $cabecalho;

        $cabecalho = $validarcert;
        // $teste_cabecalho = "$cabecalho";
        if ($sem_margins == 't') {
            pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
        } else {
            pdf($html, $filename, $cabecalho, $rodape);
        }
    }


    function impressaolaudotudo($ambulatorio_laudo_id, $exame_id) {
        $this->load->plugin('mpdf');
        $empresa_id = $this->session->userdata('empresa_id');
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        // var_dump($data['laudo'][0]->template_obj); die;
        if ($data['laudo'][0]->template_obj != '') {
            $data['laudo'][0]->texto = $this->templateParaTexto($data['laudo'][0]->template_obj);
        }
        $texto = $data['laudo'][0]->texto;
        $adendo = $data['laudo'][0]->adendo;
        $data['laudo'][0]->texto = $texto . '<br>' . $adendo;
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
        // var_dump($data['laudo'][0]->texto); die;
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
//        var_dump($data['cabecalhomedico']); die;
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
        // $sem_margins = 't';
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        //////////////////////////////////////////////////////////////////////////////////////////////////
        //LAUDO CONFIGURÁVEL
//            die('morreu');
        $filename = "laudo.pdf";
        if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
            $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
        } else {
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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
        $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
        $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
        $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
        $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
        $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
        $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
        $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
        $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
        $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
        $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
        $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
        $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
        $operador_id = $this->session->userdata('operador_id');
        $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
        @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
        @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
        $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
        $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
        $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
        $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
        $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
        $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
        $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);
        $dataFuturo2 = date("Y-m-d");
        $dataAtual2 = $data['laudo'][0]->nascimento;
        $date_time2 = new DateTime($dataAtual2);
        $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
        $idade = $diff2->format('%Y anos');
        $cabecalho = str_replace("_idade_", $idade, $cabecalho);

        $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
        $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']);



        if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
            $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
            $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
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
        $solicitacaoExames = $this->imprimirsolicitacaoExamesTodos($ambulatorio_laudo_id);
        $relatorioExames = $this->imprimirrelatorioExamesTodos($ambulatorio_laudo_id);
        $terapeuticasExames = $this->imprimirterapeuticasExamesTodos($ambulatorio_laudo_id);
        $receita = $this->imprimirReceitaTodos($ambulatorio_laudo_id);
        $html .= $solicitacaoExames;
        $html .= $relatorioExames;
        $html .= $terapeuticasExames;
        // var_dump($html); die;

        if ($data['empresa'][0]->impressao_tipo == 33) {
            // ossi rezaf rop adiv ahnim oiedo uE
            // Isso é pra quando for da vale-imagem, o menor tamanho ficar absurdamente pequeno
            // açneod ?euq roP
            $html = str_replace('xx-small', '5pt', $html);
        }

        $teste_cabecalho = "$cabecalho";
        if ($sem_margins == 't') {
            pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
        } else {
            pdf($html, $filename, $cabecalho, $rodape);
        }
    }

    function imprimirsolicitacaoExamesTodos($ambulatorio_laudo_id) {

        $this->load->plugin('mpdf');
        $data['solicitacao'] = $this->laudo->listarexameimpressaotodos($ambulatorio_laudo_id);
        // var_dump($ambulatorio_laudo_id);
        if (count($data['solicitacao']) == 0) {
            return '';
        }
        // var_dump($data['laudo']); die;
        $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;

        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
        $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
        $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
        $nomemes = $meses[$mes];
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        $string = '';
        if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
            $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
        } else {
            $assinatura = "";
            $data['assinatura'] = "";
        }


        foreach ($data['solicitacao'] as $key => $value) {
            $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto) . '<br><br>' . $texto_rodape;

            // Por causa do Tinymce Novo.
            $data['laudo'] = array($value);
            $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);

            $html2 = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
            $string .= '<pagebreak>' . $html2;
        }

        // var_dump($string); die;


        return $string;
    }

    function imprimirsolicitacaoExamesTodosnovo($ambulatorio_laudo_id, $solicitar_exames) {
        $this->load->plugin('mpdf');
        $string = '';
            $sol_exames = explode(",", $solicitar_exames);
            $this->load->plugin('mpdf');
            $data['solicitacao'] = $this->laudo->listarexameimpressaotodosnovo($sol_exames);
            // var_dump($ambulatorio_laudo_id);
            if (count($data['solicitacao']) == 0) {
                return '';
            }
            // var_dump($data['laudo']); die;
            $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);
    
            $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
            $data['empresa'] = $this->guia->listarempresa();
            $data['empresa_m'] = $this->empresa->listarempresamunicipio();
            @$municipio_empresa = @$data['empresa_m'][0]->municipio;
            $data['receituario'] = true;
    
            $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");
    
            $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
            $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
            $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
            $nomemes = $meses[$mes];

            if($_POST['sem_data_e'] == 'on'){
                $texto_rodape = '';
            }else{
                $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
            }
            

            if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
                $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
                $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            } else {
                $assinatura = "";
                $data['assinatura'] = "";
            }
    
            $i = 0;
            foreach ($data['solicitacao'] as $key => $value) {


                // foreach($_POST['repetir_impressoes'] as $chave => $item){
                //     if($item == 'solicitacaoexames_'.$value->ambulatorio_exame_id){
                //         $posicao_repetir = $chave + 1;
                        
                //     }else{
                //         continue;
                //     }
                // }

                //$reperti_impressao = $_POST['repetir_impressoes'][$posicao_repetir];
                $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto) . '<br><br>' . $texto_rodape;

                //for($a = 1; $a <= $reperti_impressao; $a++){
                $i++;
    
                // Por causa do Tinymce Novo.
                $data['laudo'] = array($value);
                $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
                 //if($a == 1){
                $data['laudo'][0]->texto = '<b><font size="7">Solicitações de Exames </font></b><br>'. $data['laudo'][0]->texto;
                // }
                $html2 = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
                
                if($i == 1){
                    $string .= $html2;
                }else{
                    $string .= '<pagebreak>' . $html2; 
                }
           // }
        }

        return $string;
    }

    function imprimirsolicitacaoExamesTodosnovo_imprimir($ambulatorio_laudo_id) {
        $this->load->plugin('mpdf');
        $string = '';

            $this->load->plugin('mpdf');
            $data['solicitacao'] = $this->laudo->listarexameimpressaotodosnovo_imprimir();
            // var_dump($ambulatorio_laudo_id);
            if (count($data['solicitacao']) == 0) {
                return '';
            }
            // var_dump($data['laudo']); die;
            $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);
    
            $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
            $data['empresa'] = $this->guia->listarempresa();
            $data['empresa_m'] = $this->empresa->listarempresamunicipio();
            @$municipio_empresa = @$data['empresa_m'][0]->municipio;
            $data['receituario'] = true;
    
            $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");
    
            $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
            $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
            $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
            $nomemes = $meses[$mes];
            $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;

            if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
                $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
                $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            } else {
                $assinatura = "";
                $data['assinatura'] = "";
            }
    
            $i = 0;
            foreach ($data['solicitacao'] as $key => $value) {

                $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto) . '<br><br>' . $texto_rodape;

                $i++;
    
                // Por causa do Tinymce Novo.
                $data['laudo'] = array($value);
                $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
                // if($i == 1){
                $data['laudo'][0]->texto = '<b><font size="7">Solicitações de Exames </font></b><br>'. $data['laudo'][0]->texto;
                // }
                $html2 = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
                
                if($i == 1){
                    $string .= $html2;
                }else{
                    $string .= '<pagebreak>' . $html2; 
                }
        }

        return $string;
    }

    function imprimirrelatorioExamesTodos($ambulatorio_laudo_id) {
        
        $this->load->plugin('mpdf');
        $data['solicitacao'] = $this->laudo->listarrelatorioimpressaotodos($ambulatorio_laudo_id);
        // var_dump($ambulatorio_laudo_id);
        if (count($data['solicitacao']) == 0) {
            return '';
        }
        // var_dump($data['laudo']); die;
        $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;

        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
        $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
        $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
        $nomemes = $meses[$mes];
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        $string = '';
        if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
            $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
        } else {
            $assinatura = "";
            $data['assinatura'] = "";
        }


        foreach ($data['solicitacao'] as $key => $value) {
            $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto) . '<br><br>' . $texto_rodape;

            // Por causa do Tinymce Novo.
            $data['laudo'] = array($value);
            $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);

            $html2 = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
            $string .= '<pagebreak>' . $html2;
        }

        // var_dump($string); die;


        return $string;
    }

    function imprimirrelatorioExamesTodosnovo_imprimir($ambulatorio_laudo_id) {
        $this->load->plugin('mpdf');
        $string = '';



            $data['solicitacao'] = $this->laudo->listarrelatorioimpressaotodosnovo_imprimir();
            // var_dump($ambulatorio_laudo_id);
            if (count($data['solicitacao']) == 0) {
                return '';
            }
            // var_dump($data['laudo']); die;
            $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);
    
            $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
            $data['empresa'] = $this->guia->listarempresa();
            $data['empresa_m'] = $this->empresa->listarempresamunicipio();
            @$municipio_empresa = @$data['empresa_m'][0]->municipio;
            $data['receituario'] = true;
    
            $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");
    
            $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
            $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
            $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
            $nomemes = $meses[$mes];
            $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
    
            if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
                $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
                $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            } else {
                $assinatura = "";
                $data['assinatura'] = "";
            }
    
            $i = 0;
            foreach ($data['solicitacao'] as $key => $value) {


                $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto) . '<br><br>' . $texto_rodape;
                $i++;
    
                // Por causa do Tinymce Novo.
                $data['laudo'] = array($value);
                $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
                
                // if($i == 1){
                $data['laudo'][0]->texto = '<b><font size="7">Relatório </font></b><br>'. $data['laudo'][0]->texto;
                // }

                $html2 = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
                
                if($i == 1){
                    if($_POST['impressao_exame'] == 0){
                        $string .= $html2;
                    }else{
                        $string .= '<pagebreak>' . $html2;
                    }
                }else{
                    $string .= '<pagebreak>' . $html2;
                }
        }

        return $string;
    }

    function imprimirrelatorioExamesTodosnovo($ambulatorio_laudo_id, $relatorio, $solicitar_exames) {
        $this->load->plugin('mpdf');
        $string = '';


            $rela = explode(",", $relatorio);

            $data['solicitacao'] = $this->laudo->listarrelatorioimpressaotodosnovo($rela);
            // var_dump($ambulatorio_laudo_id);
            if (count($data['solicitacao']) == 0) {
                return '';
            }
            // var_dump($data['laudo']); die;
            $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);
    
            $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
            $data['empresa'] = $this->guia->listarempresa();
            $data['empresa_m'] = $this->empresa->listarempresamunicipio();
            @$municipio_empresa = @$data['empresa_m'][0]->municipio;
            $data['receituario'] = true;
    
            $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");
    
            $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
            $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
            $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
            $nomemes = $meses[$mes];

            if($_POST['sem_data_e'] == 'on'){
                $texto_rodape = '';
            }else{
                $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
            }

           
    
            if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
                $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
                $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            } else {
                $assinatura = "";
                $data['assinatura'] = "";
            }
    
            $i = 0;
            foreach ($data['solicitacao'] as $key => $value) {

                // foreach($_POST['repetir_impressoes'] as $chave => $item){
                //     if($item == 'relatorios_'.$value->ambulatorio_relatorio_id){
                //         $posicao_repetir = $chave + 1;
                        
                //     }else{
                //         continue;
                //     }
                // }

                //$reperti_impressao = $_POST['repetir_impressoes'][$posicao_repetir];

                $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto) . '<br><br>' . $texto_rodape;
                //for($a = 1; $a <= $reperti_impressao; $a++){
                $i++;
    
                // Por causa do Tinymce Novo.
                $data['laudo'] = array($value);
                $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
                
                if($a == 1){
                $data['laudo'][0]->texto = '<b><font size="7">Relatório </font></b><br>'. $data['laudo'][0]->texto;
                }

                $html2 = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
                
                if($i == 1){
                    if($solicitar_exames == 0){
                        $string .= $html2;
                    }else{
                        $string .= '<pagebreak>' . $html2;
                    }
                }else{
                    $string .= '<pagebreak>' . $html2;
                }
           // }
        }

        return $string;
    }

    function imprimirterapeuticasExamesTodos($ambulatorio_laudo_id) {
        
        $this->load->plugin('mpdf');
        $data['solicitacao'] = $this->laudo->listarterapeuticaimpressaotodos($ambulatorio_laudo_id);
        // var_dump($ambulatorio_laudo_id);
        if (count($data['solicitacao']) == 0) {
            return '';
        }
        // var_dump($data['laudo']); die;
        $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;

        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
        $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
        $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
        $nomemes = $meses[$mes];
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        $string = '';
        if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
            $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
        } else {
            $assinatura = "";
            $data['assinatura'] = "";
        }


        foreach ($data['solicitacao'] as $key => $value) {
            $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto) . '<br><br>' . $texto_rodape;

            // Por causa do Tinymce Novo.
            $data['laudo'] = array($value);
            $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);

            $html2 = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
            $string .= '<pagebreak>' . $html2;
        }

        // var_dump($string); die;


        return $string;
    }

    function imprimirterapeuticasExamesTodosnovo_imprimir($ambulatorio_laudo_id) {
        
        $this->load->plugin('mpdf');

        $string = '';

            $data['solicitacao'] = $this->laudo->listarterapeuticaimpressaotodosnovo_imprimir();
            // var_dump($ambulatorio_laudo_id);
            if (count($data['solicitacao']) == 0) {
                return '';
            }
            // var_dump($data['laudo']); die;
            $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);
    
            $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
            $data['empresa'] = $this->guia->listarempresa();
            $data['empresa_m'] = $this->empresa->listarempresamunicipio();
            @$municipio_empresa = @$data['empresa_m'][0]->municipio;
            $data['receituario'] = true;
    
            $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");
    
            $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
            $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
            $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
            $nomemes = $meses[$mes];
            $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
            
            if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
                $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
                $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            } else {
                $assinatura = "";
                $data['assinatura'] = "";
            }
    
            $i = 0;
            foreach ($data['solicitacao'] as $key => $value) {


                $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto) . '<br><br>' . $texto_rodape;

                $i++;
    
                // Por causa do Tinymce Novo.
                $data['laudo'] = array($value);
                $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
                // if($i == 1){
                $data['laudo'][0]->texto = '<b><font size="7">Terapeuticas </font></b><br>'. $data['laudo'][0]->texto;
                // }
                $html2 = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);

            if($i == 1){
                if($_POST['impressao_exame'] == 0 && $_POST['impressao_relatorio'] == 0){
                    $string .= $html2;
                }else{
                    $string .= '<pagebreak>' . $html2;
                }
            }else{
                $string .= '<pagebreak>' . $html2;
            }

        }
        

        return $string;
    }

    function imprimirterapeuticasExamesTodosnovo($ambulatorio_laudo_id, $terapeuticas, $solicitar_exames, $relatorios) {
        
        $this->load->plugin('mpdf');

        $string = '';

        $tera = explode(",", $terapeuticas);
            $data['solicitacao'] = $this->laudo->listarterapeuticaimpressaotodosnovo($tera);
            // var_dump($ambulatorio_laudo_id);
            if (count($data['solicitacao']) == 0) {
                return '';
            }
            // var_dump($data['laudo']); die;
            $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);
    
            $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
            $data['empresa'] = $this->guia->listarempresa();
            $data['empresa_m'] = $this->empresa->listarempresamunicipio();
            @$municipio_empresa = @$data['empresa_m'][0]->municipio;
            $data['receituario'] = true;
    
            $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");
    
            $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
            $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
            $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
            $nomemes = $meses[$mes];
            if($_POST['sem_data_e'] == 'on'){
                $texto_rodape = '';
            }else{
                $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
            }
            
            if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
                $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
                $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            } else {
                $assinatura = "";
                $data['assinatura'] = "";
            }
    
            $i = 0;
            foreach ($data['solicitacao'] as $key => $value) {

                // foreach($_POST['repetir_impressoes'] as $chave => $item){
                //     if($item == 'terapeuticas_'.$value->ambulatorio_terapeutica_id){
                //         $posicao_repetir = $chave + 1;
                //     }else{
                //         continue;
                //     }
                // }

                //$reperti_impressao = $_POST['repetir_impressoes'][$posicao_repetir];
                $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto) . '<br><br>' . $texto_rodape;

                //for($a = 1; $a <= $reperti_impressao; $a++){
                $i++;
    
                // Por causa do Tinymce Novo.
                $data['laudo'] = array($value);
                $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
                $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
               // if($a == 1){
                $data['laudo'][0]->texto = '<b><font size="7">Terapeuticas </font></b><br>'. $data['laudo'][0]->texto;
              //  }
                $html2 = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);

            if($i == 1){
                if($solicitar_exames == 0 && $relatorios == 0){
                    $string .= $html2;
                }else{
                    $string .= '<pagebreak>' . $html2;
                }
            }else{
                $string .= '<pagebreak>' . $html2;
            }

           // }
        }
        

        return $string;
    }

    function imprimirReceitaTodos($ambulatorio_laudo_id) {
        $this->load->plugin('mpdf');
        $data['solicitacao'] = $this->laudo->listarreceitaimpressaotodos($ambulatorio_laudo_id);
        // var_dump($ambulatorio_laudo_id);
        // var_dump($data['laudo']); die;
        if (count($data['solicitacao']) == 0) {
            return '';
        }
        $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;

        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
        $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
        $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
        $nomemes = $meses[$mes];
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        $string = '';
        if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
            $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
        } else {
            $assinatura = "";
            $data['assinatura'] = "";
        }


        foreach ($data['solicitacao'] as $key => $value) {
            $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto);

            // Por causa do Tinymce Novo.
            $data['laudo'] = array($value);
            $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);

            $html2 = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);

            $string .= '<pagebreak>' . $html2;
        }

        // var_dump($string); die;


        return $string;
    }
    
    function imprimirReceitaTodos2($ambulatorio_laudo_id) {
        $this->load->plugin('mpdf'); 

        $empresa_id = $this->session->userdata('empresa_id');
        $i = 0;
        $data['solicitacao'] = $this->laudo->listarreceitaimpressaotodosnovo($ambulatorio_laudo_id);
        $string = "";
        $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
      
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['solicitacao'][0]->medico_parecer1);
        $data['cabecalho'][0]->cabecalho = str_replace("_minicurriculum_", $data['cabecalhomedico'][0]->curriculo,$data['cabecalho'][0]->cabecalho);          
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
      
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudoreceituario($empresa_id);
          $i = 0;
          foreach ($data['solicitacao'] as $key => $value) {
              $i++;
            $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto);
            // Por causa do Tinymce Novo.
            $data['laudo'] = array($value);
            $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
          if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];

        $data['texto_rodape'] = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
           
        if ($data['empresa'][0]->ficha_config == 't') { 
            $filename = "laudo.pdf";  
            $html = $this->load->view('ambulatorio/impressaoreceituarioconfiguravel', $data, true);  
        }else{
            
       
        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
            $filename = "laudo.pdf";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
           
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
           
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);


/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        
        }
/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
      
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
        
///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo
            
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } else {
                $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
            }

           $html =  $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        }
      }
            
            if ($i == count($data['solicitacao'])) {
                 $string .=  $html;
            }else{
                 $string .=  $html . '<pagebreak>';
            }
//              print_r($string);
           
        }
        
           
        return $string;

    }
    

    function imprimirReceitaTodos2novo_imprimir($ambulatorio_laudo_id) {

        
        $this->load->plugin('mpdf'); 


        $impressao_receita = $this->laudo->impressaonovoatendimento_botaoimprimir($ambulatorio_laudo_id, 'RECEITAS');

        $_POST['impressao_receita'] = $impressao_receita;

        $empresa_id = $this->session->userdata('empresa_id');
        $i = 0;
        $data['solicitacao'] = $this->laudo->listarreceitaimpressaotodosnovo_imprimir();

        // echo '<pre>';
        // print_r($data['solicitacao']);
        // die; 

        $string = "";
        $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
      
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['solicitacao'][0]->medico_parecer1);
        $data['cabecalho'][0]->cabecalho = str_replace("_minicurriculum_", $data['cabecalhomedico'][0]->curriculo,$data['cabecalho'][0]->cabecalho);          
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
      
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudoreceituario($empresa_id);
          $i = 0;
          $totalimpress = 0;
          foreach ($data['solicitacao'] as $key => $value) {

                $i++;
            $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto);
            // Por causa do Tinymce Novo.
            $data['laudo'] = array($value);
            $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
          if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];

        $data['texto_rodape'] = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;

        if ($data['empresa'][0]->ficha_config == 't') { 
            $filename = "laudo.pdf";  
            $html = $this->load->view('ambulatorio/impressaoreceituarioconfiguravel', $data, true);  
        
        }else{
            
       
        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
            $filename = "laudo.pdf";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
           
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
           
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);


/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        
        }
/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
      
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
        
///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo
            
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } else {
                $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
            }

           $html =  $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        }
      }
            
            if ($i == 1) {
                $string .=  $html;
            }else{
                 $string .=  '<pagebreak>'. $html;
            }
//              print_r($string);

    }
        
           
        return $string;

    }


    function imprimirReceitaTodos2novo($ambulatorio_laudo_id, $receitas) {

        // echo '<pre>';
        // print_r($_POST['repetir_impressoes_receituario']);
        // // print_r($receitas);
        // die;
        $this->load->plugin('mpdf'); 


        $receita = explode(",", $receitas);
        // print_r($receita);
        // die;

        $empresa_id = $this->session->userdata('empresa_id');
        $i = 0;
        $data['solicitacao'] = $this->laudo->listarreceitaimpressaotodosnovo($receita);
        $string = "";
        $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
      
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['solicitacao'][0]->medico_parecer1);
        $data['cabecalho'][0]->cabecalho = str_replace("_minicurriculum_", $data['cabecalhomedico'][0]->curriculo,$data['cabecalho'][0]->cabecalho);          
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
      
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudoreceituario($empresa_id);
          $i = 0;
          $totalimpress = 0;
          foreach ($data['solicitacao'] as $key => $value) {
                // foreach($_POST['repetir_impressoes_receituario'] as $chave => $item){
                //     if($item == 'receita_'.$value->ambulatorio_receituario_id){
                //         $posicao_repetir = $chave + 1;

                //         if($i == 0){
                //             $totalimpress = $totalimpress + $_POST['repetir_impressoes_receituario'][$posicao_repetir] + 1;
                //         }else{
                //             $totalimpress = $totalimpress + $_POST['repetir_impressoes_receituario'][$posicao_repetir] - 1;
                //         }
                        
                //     }else{
                //         continue;
                //     }
                // }

                //$reperti_impressao = $_POST['repetir_impressoes_receituario'][$posicao_repetir];
                
            //for($a = 1; $a <= $reperti_impressao; $a++){
                $i++;
            $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto);
            // Por causa do Tinymce Novo.
            $data['laudo'] = array($value);
            $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
          if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];

        if($_POST['sem_data_r'] == 'on'){
            $data['texto_rodape'] = '';
        }else{
            $data['texto_rodape'] = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        }

           
        if ($data['empresa'][0]->ficha_config == 't') { 
            $filename = "laudo.pdf";  
             $html = $this->load->view('ambulatorio/impressaoreceituarioconfiguravel', $data, true); 
            //  print_r($html); 
            //  die; 
        }else{
            
       
        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
            $filename = "laudo.pdf";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
           
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
           
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);


/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        
        }
/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
      
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
        
///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo
            
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } else {
                $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
            }

           $html =  $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        }
      }
            
            if ($i == 1) {
                $string .=  $html;
            }else{
                 $string .=  '<pagebreak>'. $html;
            }
//              print_r($string);
           
        //}
    }
        
           
        return $string;

    }


    function imprimirReceitaEspecialTodos($ambulatorio_laudo_id) {
        $empresa = $this->session->userdata('empresa_id');
        $this->load->plugin('mpdf');
        $data['solicitacao'] = $this->laudo->listarreceitaespecialimpressaotodos($ambulatorio_laudo_id);
        // var_dump($ambulatorio_laudo_id);
        // var_dump($data['laudo']); die;
        if (count($data['solicitacao']) == 0) {
            return '';
        }
        $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);
        $data['permissao'] = $this->empresa->listarverificacaopermisao2($empresa);
        
     
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;

        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
        $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
        $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
        $nomemes = $meses[$mes];
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        $string = '';
        if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
            $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
        } else {
            $assinatura = "";
            $data['assinatura'] = "";
        }

        if ($data['solicitacao'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['solicitacao'][0]->medico_parecer1;
        }

        $this->load->helper('directory');
        $arquivos = directory_map("./upload/operadorLOGO/");
        $data['arquivo_existe'] = false;
//        var_dump($arquivos); die;
//        var_dump($arquivos);die;
        if (@$arquivos != false) {
            foreach (@$arquivos as $value) {
                if (@$value == @$data['solicitacao'][0]->medico_parecer1 . ".jpg") {
                    @$data['arquivo_existe'] = true;
                    @$data['medico_parecer1'] = @$data['solicitacao'][0]->medico_parecer1;
                    break;
                }
            }
        }


        foreach ($data['solicitacao'] as $key => $value) {
            $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto);

            // Por causa do Tinymce Novo.
            $data['laudo'] = array($value);
            $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);

            if ($data['permissao'][0]->a4_receituario_especial == "t") { 
               $html2 = $this->load->view('ambulatorio/impressaoreceituarioespecialA4', $data,true); 
            }else{
//               $html2 = $this->load->view('ambulatorio/impressaoreceituariotodosespecial', $data, true); 
                $html2 = $this->load->view('ambulatorio/impressaoreceituarioespecial', $data, true);
            } 
            if ($key > 0 && $key < (count($data['solicitacao']) - 1)) {
                $string .= $html2 . '<pagebreak>'; 
            } else {
                $string .= $html2; 
            }
        }
      
        
        
        $cabecalho =  $this->load->view('ambulatorio/cabecalhorelatorioespecial', $data, true);
        $rodape =  $this->load->view('ambulatorio/rodaperelatorioespecial', $data, true); 
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa);
       
        if ($data['cabecalho'][0]->receituario_especial == "t") {
            $cabecalho_congfig = $data['cabecalho'][0]->cabecalho;
                 if ($data['permissao'][0]->a4_receituario_especial == "t") { 
                   $cabecalho = "<table border='0'  width=100%><tr><td>".$cabecalho_congfig."</td></tr></table>";      
                 }else{
                   $cabecalho = "<table border='0'  width=100%><tr><td>".$cabecalho_congfig."</td><td>".$cabecalho_congfig."</td></tr></table>";      
                 }  
        }else{ 
           if ($data['permissao'][0]->a4_receituario_especial == "t") { 
              $cabecalho =  $this->load->view('ambulatorio/cabecalhorelatorioespecialA4', $data, true);
            }   
        }
         
//        $c = '<table><tr text-rotate="90"><th >CABEÇALHO</th></tr></table> ';
//        $r = '<table style="margin-left:90%;margin-top:-50%;"><tr text-rotate="90"><th >RODAPE</th></tr></table> ';
         
          if ($data['permissao'][0]->a4_receituario_especial == "t") { 
             pdf($string, 'ReceituarioEspecial.pdf', $cabecalho, $rodape, '', 5, 0, 4);  
           }else{
             pdfrespecial($string, 'ReceituarioEspecial.pdf', $cabecalho, $rodape, '', 5, 0, 4 );   
          }
 
    }


    function imprimirReceitaEspecialTodosnovo_imprimir($ambulatorio_laudo_id) {
        $empresa = $this->session->userdata('empresa_id');
        $this->load->plugin('mpdf');
        

        $impressao_receita_especial = $this->laudo->impressaonovoatendimento_botaoimprimir($ambulatorio_laudo_id, 'RECEITAS');
        $_POST['impressao_receita_especial'] = $impressao_receita_especial; 

        $data['solicitacao'] = $this->laudo->listarreceitaespecialimpressaotodosnovo_imprimir();

        if(count($data['solicitacao']) == 0){
            echo "<script>window.close();</script>";

            die;
         }


        $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);
        $data['permissao'] = $this->empresa->listarverificacaopermisao2($empresa);
        
     
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;

        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
        $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
        $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
        $nomemes = $meses[$mes];
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        $string = '';
        if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
            $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
        } else {
            $assinatura = "";
            $data['assinatura'] = "";
        }

        if ($data['solicitacao'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['solicitacao'][0]->medico_parecer1;
        }

        $this->load->helper('directory');
        $arquivos = directory_map("./upload/operadorLOGO/");
        $data['arquivo_existe'] = false;
//        var_dump($arquivos); die;
//        var_dump($arquivos);die;
        if (@$arquivos != false) {
            foreach (@$arquivos as $value) {
                if (@$value == @$data['solicitacao'][0]->medico_parecer1 . ".jpg") {
                    @$data['arquivo_existe'] = true;
                    @$data['medico_parecer1'] = @$data['solicitacao'][0]->medico_parecer1;
                    break;
                }
            }
        }

        $i = 0;
        $totalimpress = 0;
        foreach ($data['solicitacao'] as $key => $value) {

            $i++;
            $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto);

            // Por causa do Tinymce Novo.
            $data['laudo'] = array($value);
            $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);

            if ($data['permissao'][0]->a4_receituario_especial == "t") { 
               $html2 = $this->load->view('ambulatorio/impressaoreceituarioespecialA4', $data,true); 
            }else{
//               $html2 = $this->load->view('ambulatorio/impressaoreceituariotodosespecial', $data, true); 
                $html2 = $this->load->view('ambulatorio/impressaoreceituarioespecial', $data, true);
            } 

            $html2 .= "<br><br><br><br><br><br><font size='1'>Data: ".date('d/m/Y', strtotime($data['laudo'][0]->data_cadastro)). "</font>";
            $data['semdata'] = true;
            
            if($i == 1){
                $string .= $html2;
            }else{
                $string .= '<pagebreak>'. $html2; 
            }

    }
        
        // print_r($string);
        // die;
      
        
        
        $cabecalho =  $this->load->view('ambulatorio/cabecalhorelatorioespecial', $data, true);
        $rodape =  $this->load->view('ambulatorio/rodaperelatorioespecial', $data, true); 
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa);
       
        if ($data['cabecalho'][0]->receituario_especial == "t") {
            $cabecalho_congfig = $data['cabecalho'][0]->cabecalho;
                 if ($data['permissao'][0]->a4_receituario_especial == "t") { 
                   $cabecalho = "<table border='0'  width=100%><tr><td>".$cabecalho_congfig."</td></tr></table>";      
                 }else{
                   $cabecalho = "<table border='0'  width=100%><tr><td>".$cabecalho_congfig."</td><td>".$cabecalho_congfig."</td></tr></table>";      
                 }  
        }else{ 
           if ($data['permissao'][0]->a4_receituario_especial == "t") { 
              $cabecalho =  $this->load->view('ambulatorio/cabecalhorelatorioespecialA4', $data, true);
            }   
        }

        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['solicitacao'][0]->medico_parecer1);
        if ($data['permissao'][0]->a4_receituario_especial == "t") { 
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                $cabecalho .=  $this->load->view('ambulatorio/cabecalhorelatorioespecialA4', $data, true);
            }
        }
         
//        $c = '<table><tr text-rotate="90"><th >CABEÇALHO</th></tr></table> ';
//        $r = '<table style="margin-left:90%;margin-top:-50%;"><tr text-rotate="90"><th >RODAPE</th></tr></table> ';
         
          if ($data['permissao'][0]->a4_receituario_especial == "t") { 
             pdf($string, 'ReceituarioEspecial.pdf', $cabecalho, $rodape);  
           }else{
             pdfrespecial($string, 'ReceituarioEspecial.pdf', $cabecalho, $rodape, '', 5, 0, 4 );   
          }
 
    }


    function imprimirReceitaEspecialTodosnovo($ambulatorio_laudo_id, $receita_especial = array(), $sem_data = null) {
        $empresa = $this->session->userdata('empresa_id');
        $this->load->plugin('mpdf');
        // print_r($receita_especial);
        // die;
        if(count($receita_especial) > 0){
            $receitas_esp = explode(",", $receita_especial); 
        }else{
            $receitas_esp = ["0"];
        }
        // $receitas_esp = explode(",", $receita_especial);
        // print_r($receitas_esp);
        // die;

        $data['solicitacao'] = $this->laudo->listarreceitaespecialimpressaotodosnovo($receitas_esp);

        if(count($data['solicitacao']) == 0){
            echo "<script>window.close();</script>";

            die;
         }


            $_POST['sem_data_r_especial'] = $sem_data;


        // $_POST['repetir_impressoes_receituario_especial'] = explode(",", $impressao_array);

        //  echo '<pre>';
        //  print_r($_POST['repetir_impressoes_receituario_especial']);
        //  die;

        $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);
        $data['permissao'] = $this->empresa->listarverificacaopermisao2($empresa);
        
     
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;

        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
        $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
        $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
        $nomemes = $meses[$mes];
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        $string = '';
        if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
            $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
        } else {
            $assinatura = "";
            $data['assinatura'] = "";
        }

        if ($data['solicitacao'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['solicitacao'][0]->medico_parecer1;
        }

        $this->load->helper('directory');
        $arquivos = directory_map("./upload/operadorLOGO/");
        $data['arquivo_existe'] = false;
//        var_dump($arquivos); die;
//        var_dump($arquivos);die;
        if (@$arquivos != false) {
            foreach (@$arquivos as $value) {
                if (@$value == @$data['solicitacao'][0]->medico_parecer1 . ".jpg") {
                    @$data['arquivo_existe'] = true;
                    @$data['medico_parecer1'] = @$data['solicitacao'][0]->medico_parecer1;
                    break;
                }
            }
        }

        $i = 0;
        $totalimpress = 0;
        foreach ($data['solicitacao'] as $key => $value) {


            // foreach($_POST['repetir_impressoes_receituario_especial'] as $chave => $item){
            //     if($item == 'receituarioEspecial_'.$value->ambulatorio_receituario_id){
            //         $posicao_repetir = $chave + 1;

            //     if(count($data['solicitacao']) > 1){
            //         if($i == 0){
            //             $totalimpress = $totalimpress + $_POST['repetir_impressoes_receituario_especial'][$posicao_repetir] + 1;
            //         }else{
            //             $totalimpress = $totalimpress + $_POST['repetir_impressoes_receituario_especial'][$posicao_repetir] - 1;
            //         }
            //     }else{
            //         $totalimpress = $totalimpress + $_POST['repetir_impressoes_receituario_especial'][$posicao_repetir];
            //     }
                    
            //     }else{
            //         continue;
            //     }
            // }

            //$reperti_impressao = $_POST['repetir_impressoes_receituario_especial'][$posicao_repetir];

            //for($a = 1; $a <= $reperti_impressao; $a++){
            $i++;
            $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto);

            // Por causa do Tinymce Novo.
            $data['laudo'] = array($value);
            $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);

            if ($data['permissao'][0]->a4_receituario_especial == "t") { 
               $html2 = $this->load->view('ambulatorio/impressaoreceituarioespecialA4', $data,true); 
            }else{
//               $html2 = $this->load->view('ambulatorio/impressaoreceituariotodosespecial', $data, true); 
                $html2 = $this->load->view('ambulatorio/impressaoreceituarioespecial', $data, true);
            } 

            if($_POST['sem_data_r_especial'] == 'on'){

            }else{
                $html2 .= "<br><br><br><font size='1'>Data: ".date('d/m/Y', strtotime($data['laudo'][0]->data_cadastro)). "</font>";
            }

            $data['semdata'] = true;
            if($i == 1){
                $string .= $html2;
            }else{
                $string .= '<pagebreak>'. $html2; 
            }

       // }
    }
        
        // print_r($string);
        // die;
      
        
        
        $cabecalho =  $this->load->view('ambulatorio/cabecalhorelatorioespecial', $data, true);
        $rodape =  $this->load->view('ambulatorio/rodaperelatorioespecial', $data, true); 
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa);
       
        if ($data['cabecalho'][0]->receituario_especial == "t") {
            $cabecalho_congfig = $data['cabecalho'][0]->cabecalho;
                 if ($data['permissao'][0]->a4_receituario_especial == "t") { 
                   $cabecalho = "<table border='0'  width=100%><tr><td>".$cabecalho_congfig."</td></tr></table>";      
                 }else{
                   $cabecalho = "<table border='0'  width=100%><tr><td>".$cabecalho_congfig."</td><td>".$cabecalho_congfig."</td></tr></table>";      
                 }  
        }else{ 
           if ($data['permissao'][0]->a4_receituario_especial == "t") { 
              $cabecalho =  $this->load->view('ambulatorio/cabecalhorelatorioespecialA4', $data, true);
            }   
        }

        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['solicitacao'][0]->medico_parecer1);
        if ($data['permissao'][0]->a4_receituario_especial == "t") { 
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                $cabecalho .=  $this->load->view('ambulatorio/cabecalhorelatorioespecialA4', $data, true);
            }
        }

//        $c = '<table><tr text-rotate="90"><th >CABEÇALHO</th></tr></table> ';
//        $r = '<table style="margin-left:90%;margin-top:-50%;"><tr text-rotate="90"><th >RODAPE</th></tr></table> ';
         
          if ($data['permissao'][0]->a4_receituario_especial == "t") { 
             pdf($string, 'ReceituarioEspecial.pdf', $cabecalho, $rodape);
           }else{
             pdfrespecial($string, 'ReceituarioEspecial.pdf', $cabecalho, $rodape, '', 5, 0, 4 );   
          }
 
    }

    function impressaohistorico($ambulatorio_laudo_id, $paciente_id, $procedimento_tuss_id) {


        $this->load->plugin('mpdf');
        $empresa_id = $this->session->userdata('empresa_id');
        $data['laudo'] = $this->laudo->listarlaudohistorico($ambulatorio_laudo_id, $paciente_id);
        if (count($data['laudo']) > 0) {

            $data['empresa'] = $this->guia->listarempresa($empresa_id);
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
            $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
            $texto_historico = '';
            foreach ($data['laudo'] as $item) {
                $concatenar = "Data: 25/05/2017
                           <br>
                           Medico: $item->medico
                           <br>
                           Procedimento: $item->procedimento
                           <br>
                           Queixa principal: $item->texto";


                $texto_historico = $texto_historico . "<br>" . $concatenar;
            }

            $data['laudo'][0]->texto = $texto_historico;
            $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
            @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
            @$rodape_config = $data['cabecalho'][0]->rodape;
            $exame_id = $paciente_id;
            $data['exame_id'] = $exame_id;
            $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;


            $dataFuturo = date("Y-m-d");
            $dataAtual = $data['laudo']['0']->nascimento;
            $date_time = new DateTime($dataAtual);
            $diff = $date_time->diff(new DateTime($dataFuturo));
            $teste = $diff->format('%Ya %mm %dd');

            $data['integracao'] = $this->laudo->listarlaudosintegracao(@$agenda_exames_id);
            if (count($data['integracao']) > 0) {
                $this->laudo->atualizacaolaudosintegracao($agenda_exames_id);
            }
            //////////////////////////////////////////////////////////////////////////////////////////////////
            //LAUDO CONFIGURÁVEL
            if ($data['empresa'][0]->laudo_config == 't') {

                $filename = "laudo.pdf";
                if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                } else {
                    if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                        $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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
                $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
                $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
                $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
                $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
                $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
                $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
                $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
                $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
                $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
                $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
                $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
                $operador_id = $this->session->userdata('operador_id');
                $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
                @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
                $cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
                $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
                $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
                $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
                $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);

                $cabecalho = $cabecalho . "<br> {$data['impressaolaudo'][0]->adicional_cabecalho}";
                $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']);
                // var_dump($operador_atual);
                // die;


                if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                    $assinatura = "<img   width='200px' height='100px' src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
                    $data['assinatura'] = "<img   width='200px' height='100px' src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
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


                $html = $this->load->view('ambulatorio/impressaohistoricoconfiguravel', $data, true);

                pdf($html, $filename, $cabecalho, $rodape);

                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                } else {
                    if ($data['empresa'][0]->rodape_config == 't') {
                        //                $cabecalho = $cabecalho_config;
                        $rodape = $rodape_config;
                    } else {
                        if (!file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
                        }
                    }
                }


                $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
                pdf($html, $filename, $cabecalho, $rodape);
                $this->load->View('ambulatorio/impressaolaudo_1', $data);
            }
        }
    }

    function impressaoformulario($ambulatorio_laudo_id) {

        //$this->load->plugin('mpdf');
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['formulario'] = $this->laudo->preencherformulario($paciente_id, $guia_id);

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $this->load->View('ambulatorio/impressaoformulario', $data);
    }

    function impressaoparecer($ambulatorio_laudo_id) {

        //$this->load->plugin('mpdf');
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['parecer'] = $this->laudo->preencherparecer($paciente_id, $guia_id);

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $this->load->View('ambulatorio/impressao_parecer_cirurgia_pediatrica', $data);
    }

    function impressaoapendicite($ambulatorio_laudo_id) {

        //$this->load->plugin('mpdf');
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['parecer'] = $this->laudo->preencherparecer($paciente_id, $guia_id);

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $this->load->View('ambulatorio/impressao_laudo_apendicite', $data);
    }

    function impressaocirurgia($ambulatorio_laudo_id) {

        //$this->load->plugin('mpdf');
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['cirurgia'] = $this->laudo->preenchercirurgia($paciente_id, $guia_id);

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $this->load->View('ambulatorio/impressao_cirurgias', $data);
    }

    function impressaoexameslab($ambulatorio_laudo_id) {


        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['exameslab'] = $this->laudo->preencherexameslab($paciente_id, $guia_id);

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $this->load->View('ambulatorio/impressao-exameslab', $data);
    }

    function impressaoecocardio($ambulatorio_laudo_id) {


        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['ecocardio'] = $this->laudo->preencherecocardio($paciente_id, $guia_id);

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $this->load->View('ambulatorio/impressaoecocardio', $data);
    }

    function impressaoecostress($ambulatorio_laudo_id) {


        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['ecostress'] = $this->laudo->preencherecostress($paciente_id, $guia_id);

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $this->load->View('ambulatorio/impressaoecostress', $data);
    }

    function impressaocate($ambulatorio_laudo_id) {


        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['cate'] = $this->laudo->preenchercate($paciente_id, $guia_id);

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $this->load->View('ambulatorio/impressaocate', $data);
    }

    function impressaoholter($ambulatorio_laudo_id) {


        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['holter'] = $this->laudo->preencherholter($paciente_id, $guia_id);

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $this->load->View('ambulatorio/impressaoholter', $data);
    }

    function impressaocintil($ambulatorio_laudo_id) {


        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['cintil'] = $this->laudo->preenchercintilografia($paciente_id, $guia_id);

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $this->load->View('ambulatorio/impressaocintilografia', $data);
    }

    function impressaomapa($ambulatorio_laudo_id) {


        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['mapa'] = $this->laudo->preenchermapa($paciente_id, $guia_id);

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $this->load->View('ambulatorio/impressaomapa', $data);
    }

    function impressaotergometrico($ambulatorio_laudo_id) {


        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['tergometrico'] = $this->laudo->preenchertergometrico($paciente_id, $guia_id);

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $this->load->View('ambulatorio/impressaotesteergometrico', $data);
    }

    function impressaoavaliacao($ambulatorio_laudo_id) {

        //$this->load->plugin('mpdf');
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['obj'] = $obj_laudo;
        $paciente_id = @$obj_laudo->_paciente_id;
        $guia_id = @$obj_laudo->_guia_id;
        $data['avaliacao'] = $this->laudo->preencheravaliacao($paciente_id, $guia_id);

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $this->load->View('ambulatorio/impressaoavaliacao', $data);
    }

    function impressaolaudolaboratorial($ambulatorio_laudo_id, $exame_id) {


        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');


        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA IMAGEM
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaolaudo_1', $data);
        }

////////////////////////////////////////////////////////////////////////////////////
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaolaudo_1', $data);
        }

////////////////////////////////////////////////////////////////////////////////////
        elseif ($data['empresa'][0]->impressao_tipo == 11) {//CLINICA MAIS
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='110px' src='img/logomais.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='900px' height='100px' src='img/rodapemais.png'>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaolaudo_1', $data);
        }

//////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='90px' src='img/clinicadez.jpg'></td><td><center>CLÍNICA DEZ <br> LABORATÓRIO DE ANÁLISES CLÍNICAS</center></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "  </td><td> Data da Coleta: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . " </td></tr><tr><td> Medico:" . $data['laudo']['0']->medicosolicitante . "   </td><td>  RG: " . $data['laudo']['0']->rg . "</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table>";
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table><table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            $grupo = 'laboratorial';
            pdf($html, $filename, $cabecalho, $rodape, $grupo);
            $this->load->View('ambulatorio/impressaolaudo_1', $data);
        }

/////////////////////////////////////////////////////////////////////////////////
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $medicoparecer = $data['laudo']['0']->medico_parecer1;
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                    <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                    <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                    <tr><td></td><td></td></tr>
                    <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                    <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                    <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                    <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                    </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                    <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                    <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                    <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                    <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                    <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                    <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                    </table>";
            }
            if ($data['laudo']['0']->medico_parecer1 != 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                    <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            if ($data['laudo']['0']->medico_parecer1 == 929 && $data['laudo']['0']->situacao != "FINALIZADO") {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                    <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                    <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 929) {
                $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  width='200px' height='130px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
            }
            if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 930) {
                $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <img  width='120px' height='80px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
            }
            if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 2483) {
                $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <img  width='120px' height='80px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdf($html, $filename, $cabecalho, $rodape, $grupo);
            $this->load->View('ambulatorio/impressaolaudo_2', $data);
        }

////////////////////////////////////////////////////////////////////////////////////////        
        else {//GERAL   // este item fica sempre por último
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/CABECALHO.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaolaudo_1', $data);
        }
    }

    function impressaolaudoeco($ambulatorio_laudo_id, $exame_id) {


        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');
    }

    function impressaolaudo2via($ambulatorio_laudo_id, $exame_id) {


        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

//                GERAL
//                $filename = "laudo.pdf";
//                $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/CABECALHO.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
//                $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
//                pdf($html, $filename, $cabecalho, $rodape);
//                $this->load->View('ambulatorio/impressaolaudo_1', $data);
        //PROIMAGEM
//        $filename = "laudo.pdf";
//        $cabecalho = "<table>
//        <tr>
//          <td width='30px'></td><td><img align = 'left'  width='330px' height='100px' src='img/clinicadez.jpg'></td>
//        </tr>
//        <td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
//        <tr>
//        <td width='30px'></td><td>" . substr($data['laudo']['0']->sala, 0, 10) . "</td>
//        </tr>
//        <tr>
//        <td width='30px'></td><td width='400px'>Reg.:" . $data['laudo']['0']->paciente_id . "</td><td>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
//        </tr>
//        <tr>
//          <td width='30px'></td><td >Paciente:" . $data['laudo']['0']->paciente . "</td><td>Idade:" . $teste . "</td>
//        </tr>
//        <tr>
//        <td width='30px'></td><td>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "</td><td>Sexo:" . $data['laudo']['0']->sexo . "</td>
//        </tr>
//        </tr>
//        </tr><tr><td>&nbsp;</td></tr>
//        <tr>
//        </table>";
//        $rodape = "";
//        if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer2 == "") {
//            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td width='400px'></td><td><img align = 'Right'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></tr><tr><td>&nbsp;</td></tr></table>";
//        } elseif ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer2 != "") {
//            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td width='30px'></td><td><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td><td width='30px'></td><td><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer2 . ".jpg'></td></tr></tr><tr><td>&nbsp;</td></tr></table>";
//        }
//        $grupo = 'laboratorial';
//        $html = $this->load->view('ambulatorio/impressaolaudo_5', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape, $grupo);
//        $this->load->View('ambulatorio/impressaolaudo_5', $data);
        //CAGE
        //                if ($data['laudo']['0']->sexo == "F"){
        //            $SEXO= 'FEMININO';
        //        }else{
        //            $SEXO= 'MASCULINO';
        //        }
        //        
        //        $filename = "laudo.pdf";
        //        $cabecalho = "<table>
        //<tr>
        //  <td width='30px'></td><td><img align = 'left'  width='330px' height='100px' src='img/cage.jpg'></td>
        //</tr>
        //<tr><td>&nbsp;</td></tr>
        //<tr>
        //<td width='30px'></td><td colspan='2'><b><center>" . $data['laudo']['0']->cabecalho . "</center></b></td>
        //</tr>
        //<tr><td>&nbsp;</td></tr>
        //<tr>
        //<td width='30px'></td><td width='350px'>Reg.:" . $data['laudo']['0']->paciente . "</td><td>Idade:" . $teste . "</td>
        //</tr>
        //<tr>
        //  <td width='30px'></td><td >Sexo:" . $SEXO . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
        //</tr>
        //<tr>
        //<td width='30px'></td><td>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "</td><td></td>
        //</tr>
        //
                                                                                                                                                                                                        //<tr>
        //<td width='30px'></td><td><b>VIDEOENDOSCOPIO:</b> Olympus Exera CV 145</td><td><b>MONITORIZA&Ccedil;&Atilde;O:</b> Oximetria de pulso</td>
        //</tr>
        //</table>";
        //        $rodape = "";
        //
                                                                                                                                                                                                        //        $grupo = 'laboratorial';
        //        $html = $this->load->view('ambulatorio/impressaolaudo_6', $data, true);
        //        pdf($html, $filename, $cabecalho, $rodape, $grupo);
        //        $this->load->View('ambulatorio/impressaolaudo_6', $data);
        //CDC
        //        $filename = "laudo.pdf";
        //        $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        //        $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
        //        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
        //        pdf($html, $filename, $cabecalho, $rodape);
        //        $this->load->View('ambulatorio/impressaolaudo_1', $data);
        //CLINICA MAIS
        //        $filename = "laudo.pdf";
        //        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='110px' src='img/logomais.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        //        $rodape = "<img align = 'left'  width='900px' height='100px' src='img/rodapemais.png'>";
        //        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
        //        pdf($html, $filename, $cabecalho, $rodape);
        //        $this->load->View('ambulatorio/impressaolaudo_1', $data);




        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaolaudo_1', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
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
          <td width='30px'></td><td><img align = 'left'  width='330px' height='100px' src='img/cage.jpg'></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
        <td width='30px'></td><td colspan='2'><b><center>" . $data['laudo']['0']->cabecalho . "</center></b></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
        <td width='30px'></td><td width='350px'>Reg.:" . $data['laudo']['0']->paciente . "</td><td>Idade:" . $teste . "</td>
        </tr>
        <tr>
          <td width='30px'></td><td >Sexo:" . $SEXO . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
        </tr>
        <tr>
        <td width='30px'></td><td>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "</td><td></td>
        </tr>
        
                                                                                                                                                                                                        <tr>
        <td width='30px'></td><td><b>VIDEOENDOSCOPIO:</b> Olympus Exera CV 145</td><td><b>MONITORIZA&Ccedil;&Atilde;O:</b> Oximetria de pulso</td>
        </tr>
        </table>";
            $rodape = "";

            $grupo = 'laboratorial';
            $html = $this->load->view('ambulatorio/impressaolaudo_6', $data, true);
            pdf($html, $filename, $cabecalho, $rodape, $grupo);
            $this->load->View('ambulatorio/impressaolaudo_6', $data);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaolaudo_1', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='90px' src='img/clinicadez.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaolaudo_1', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $medicoparecer = $data['laudo']['0']->medico_parecer1;
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                    <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                    <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                    <tr><td></td><td></td></tr>
                    <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                    <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                    <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                    <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                    </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                    <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                    <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                    <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                    <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                    <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                    <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                    </table>";
            }
            if ($data['laudo']['0']->medico_parecer1 != 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                    <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            if ($data['laudo']['0']->medico_parecer1 == 929 && $data['laudo']['0']->situacao != "FINALIZADO") {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                    <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                    <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 929) {
                $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  width='200px' height='130px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
            }
            if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 930) {
                $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <img  width='120px' height='80px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdf($html, $filename, $cabecalho, $rodape, $grupo);
            $this->load->View('ambulatorio/impressaolaudo_2', $data);

///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/CABECALHO.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaolaudo_1', $data);
        }
    }

    function impressaoreceita($ambulatorio_laudo_id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarreceitaimpressao($ambulatorio_laudo_id);
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa_id);
        if(!$empresa_id > 0){
            $empresa_id = 1;
        }
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);

        $data['cabecalho'][0]->cabecalho = str_replace("_minicurriculum_", $data['cabecalhomedico'][0]->curriculo,$data['cabecalho'][0]->cabecalho);         
       
        
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudoreceituario($empresa_id); 
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;  
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }
         
        $base_url = base_url();

                                
        
        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;

        //    var_dump($carimbo);die;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];



        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        $data['texto_rodape'] = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        $endereco_paciente = $data['laudo'][0]->logradouro." ".$data['laudo'][0]->numero.", ".$data['laudo'][0]->bairro.", ".$data['laudo'][0]->cidade ;
 
         
        $cabecalho = "";
                                
        if ($data['empresa'][0]->ficha_config == 't') {
          
            
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
            } else {
                
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                    $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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
//      print_r($cabecalho);
//            die('s');
            
            $cabecalho = str_replace("_paciente_", $data['laudo'][0]->paciente, $cabecalho);
            $cabecalho = str_replace("_sexo_", $data['laudo'][0]->sexo, $cabecalho);
            $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($data['laudo'][0]->nascimento)), $cabecalho);
            $cabecalho = str_replace("_convenio_", $data['laudo'][0]->convenio, $cabecalho);
            $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
            $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
            $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
            $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
            $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
            $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($data['laudo'][0]->data_cadastro)), $cabecalho);
            $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
            $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
            $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
            $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
            $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
            $operador_id = $this->session->userdata('operador_id');
            $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
            @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
            @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
            $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
            $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
            $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
            $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
            $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
            $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
            $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);
            $cabecalho = str_replace("_municipiodata_",$texto_rodape, $cabecalho);
            $cabecalho = str_replace("_crm_", $data['laudo'][0]->cbo_ocupacao_id,$cabecalho);
            $cabecalho = str_replace("_ocupacao_", $data['laudo'][0]->descricao, $cabecalho);
            $data['impressaolaudo'][0]->adicional_cabecalho = str_replace("_municipiodata_",$texto_rodape, $data['impressaolaudo'][0]->adicional_cabecalho);
          
            
            $dataFuturo2 = date("Y-m-d");
            $dataAtual2 = $data['laudo'][0]->nascimento;
            $date_time2 = new DateTime($dataAtual2);
            $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
            $idade = $diff2->format('%Y anos');
            $cabecalho = str_replace("_idade_", $idade, $cabecalho); 
            $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
            $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']); 
              
                                
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } 
              
            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape_config = str_replace("_municipiodata_",$texto_rodape, $rodape_config);
                $rodape = $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            } 
             
            $filename = "laudo.pdf"; 
            
            $html = $this->load->view('ambulatorio/impressaoreceituarioconfiguravel', $data, true);
            
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            $certificado_medico = $this->guia->certificadomedico($data['laudo'][0]->medico_parecer1);
            $empresapermissao = $this->guia->listarempresasaladepermissao();


            if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);

                if(isset($json_post->access_token)){

                    if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                        $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                        $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
                        pdfcertificado($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $ambulatorio_laudo_id); 
                    }else{ 
                        if ($sem_margins == 't') {
                            pdfcertificado($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $ambulatorio_laudo_id);
                        } else {
                            pdfcertificado($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $ambulatorio_laudo_id);
                        }
                    }


                       $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                       $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);

                        $this->db->select('link_certificado');
                        $this->db->from('tb_empresa');
                        $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                        $query = $this->db->get();
                        $return = $query->result();
                        $link = $return[0]->link_certificado;
                    
                        $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
        
                        sleep(5);
                        $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                        unlink($local_salvamento.'/laudo.pdf');

                        redirect($url);

                }else{
                    if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                        $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                        $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
                        pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0); 
                    }else{ 
                        if ($sem_margins == 't') {
                            pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
                        } else {
                            pdf($html, $filename, $cabecalho, $rodape);
                        }
                    } 
                }

            }else{

                if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                    $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                    $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
                    pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0); 
                }else{ 
                    if ($sem_margins == 't') {
                        pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
                    } else {
                        pdf($html, $filename, $cabecalho, $rodape);
                    }
                } 

            }
            
        }

        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA  
            
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE 
            
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                        <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                        <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                        </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                        </table>";
            }
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            if ($data['laudo']['0']->medico_parecer1 == 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdf($html, $filename, $cabecalho, $rodape, $grupo);

///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo
            $filename = "laudo.pdf";

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } else {
                $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
            }

            $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }
    }

    function impressaoreceitasalvar($ambulatorio_laudo_id, $laudo_id, $sem_data) {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarreceitaimpressao($ambulatorio_laudo_id, 'simples');
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa_id);
        if(!$empresa_id > 0){
            $empresa_id = 1;
        }
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);

        $data['cabecalho'][0]->cabecalho = str_replace("_minicurriculum_", $data['cabecalhomedico'][0]->curriculo,$data['cabecalho'][0]->cabecalho);         
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudoreceituario($empresa_id); 
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;  
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }
        $base_url = base_url();



        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;

        //    var_dump($carimbo);die;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];

        if($data['laudo'][0]->receita_id > 0){
            $filename = str_replace(' ', '_', $data['laudo'][0]->modelo).'_'.substr($data['laudo']['0']->data_cadastro, 8, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 0, 4) .'.pdf';
        }else{
            $filename = 'ReceitaComum'.substr($data['laudo']['0']->data_cadastro, 8, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 0, 4) .'.pdf';
        }

        // $filename = 'receita_'.$ambulatorio_laudo_id.'.pdf';  

        if($sem_data == null){
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        $data['texto_rodape'] = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        }else{
            $texto_rodape = '';
            $data['texto_rodape'] = '';
        }
         
        $cabecalho = "";
        if ($data['empresa'][0]->ficha_config == 't') {


            
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
            } else {
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                    $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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
            $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
            $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
            $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
            $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
            $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
            $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($data['laudo'][0]->data_cadastro)), $cabecalho);
            $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
            $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
            $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
            $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
            $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
            $operador_id = $this->session->userdata('operador_id');
            $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
            @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
            @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
            $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
            $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
            $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
            $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
            $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
            $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
            $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);
            $cabecalho = str_replace("_municipiodata_",$texto_rodape, $cabecalho);
            $data['impressaolaudo'][0]->adicional_cabecalho = str_replace("_municipiodata_",$texto_rodape, $data['impressaolaudo'][0]->adicional_cabecalho);
             
            $dataFuturo2 = date("Y-m-d");
            $dataAtual2 = $data['laudo'][0]->nascimento;
            $date_time2 = new DateTime($dataAtual2);
            $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
            $idade = $diff2->format('%Y anos');
            $cabecalho = str_replace("_idade_", $idade, $cabecalho); 
            $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
            $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']); 
              
               
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } 
             
            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape_config = str_replace("_municipiodata_",$texto_rodape, $rodape_config);
                $rodape = $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            } 
            
            
            $html = $this->load->view('ambulatorio/impressaoreceituarioconfiguravel', $data, true);
             
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

                $validarcert ='<div style="border: groove; width:180px; text-align: center; margin: 0pt 0pt 0pt 350pt;">Validar Certificado: <br> <a target="_blank" href="https://assinaturadigital.iti.gov.br/">validator.docusign.com</a></div>';
                $validarcert .= $cabecalho;
                $cabecalho = $validarcert;

                if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                    $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                    $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
                    pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id); 
                }else{ 
                    if ($sem_margins == 't') {
                        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id);
                    } else {
                        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
                    }
                } 
     
        }else{
        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA  
            
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE 
            
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            

            $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                        <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                        <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                        </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                        </table>";
            }
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            if ($data['laudo']['0']->medico_parecer1 == 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);

///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo


            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } else {
                $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
            }

            $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }
    }

    $this->laudo->informacaoimpressaosalvar($laudo_id, 'receita_simples', $ambulatorio_laudo_id);
}

    function impressaoreceitaatendimento($ambulatorio_laudo_id) {

        $this->load->plugin('mpdf');
        $empresa_id = $this->session->userdata('empresa_id');
        $data['laudo'] = $this->laudo->listarreceitaimpressaoatendimento($ambulatorio_laudo_id);
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }

        $this->load->helper('directory');
        $arquivos = directory_map("./upload/operadorLOGO/");
        $data['arquivo_existe'] = false;
//        var_dump($arquivos); die;
//        var_dump($arquivos);die;
        if (@$arquivos != false) {
            foreach (@$arquivos as $value) {
                if (@$value == @$data['laudo'][0]->medico_parecer1 . ".jpg") {
                    @$data['arquivo_existe'] = true;
                    @$data['medico_parecer1'] = @$data['laudo'][0]->medico_parecer1;
                    break;
                }
            }
        }

        $base_url = base_url();


        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;

        //    var_dump($carimbo);die;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];
 
// var_dump()
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        if ($data['laudo'][0]->especial == 't') {
            $this->load->View('ambulatorio/impressaoreceituarioespecial', $data);
        } else {
            if ($data['empresa'][0]->ficha_config == 't') {
                // die;
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    if ($data['cabecalhomedico'][0]->cabecalho != '') {
                        $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                    } else {
                        $cabecalho = "$cabecalho_config";
                    }
                    //                $cabecalho = $cabecalho_config;
                } else {
                    $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
                }

                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                    $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
                }

                if ($data['empresa'][0]->rodape_config == 't') {
                    if ($data['cabecalhomedico'][0]->rodape != '') {
                        $rodape_config = $data['cabecalhomedico'][0]->rodape;
                    }
                    $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                    $rodape = $texto_rodape . $rodape_config;
                } else {
                    $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
                }

                $filename = "laudo.pdf";
                //            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
                //            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
                if (false) {
                    // die;
                    $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
                } else {
                    $html = $this->load->view('ambulatorio/impressaoreceituarioconfiguravel', $data, true);
                }
                
                if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                    $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                    $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
                    pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0); 
                }else{
                    if ($sem_margins == 't') {
                         pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
                     } else {
                         pdf($html, $filename, $cabecalho, $rodape);
                     }
                }
            }

            if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
                $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
                pdf($html, $filename, $cabecalho, $rodape);
                $this->load->View('ambulatorio/impressaoreceituario', $data);
            }

            /////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
                $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
                pdf($html, $filename, $cabecalho, $rodape);
                $this->load->View('ambulatorio/impressaoreceituario', $data);
            }

            ///////////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
                $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
                pdf($html, $filename, $cabecalho, $rodape);
                $this->load->View('ambulatorio/impressaoreceituario', $data);

                /////////////////////////////////////////////////////////////////////////////////////////////////            
            } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
                $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
                pdf($html, $filename, $cabecalho, $rodape);
                $this->load->View('ambulatorio/impressaoreceituario', $data);
            }

            /////////////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                            <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                            <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                            <tr><td></td><td></td></tr>
                            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                            </table>";
                if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                    $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                            <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                            <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                            </table>";
                }
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                if ($data['laudo']['0']->medico_parecer1 == 929) {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                            <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                }
                $grupo = $data['laudo']['0']->grupo;
                $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
                pdf($html, $filename, $cabecalho, $rodape, $grupo);

                ///////////////////////////////////////////////////////////////////////////////////////            
            } else {//GERAL        //  este item fica sempre por ultimo
                $filename = "laudo.pdf";

                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                    $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
                } else {
                    $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
                }

                $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
                $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
                // pdf($html, $filename, $cabecalho, $rodape);
                $this->load->View('ambulatorio/impressaoreceituario', $data);
            }
        }
    }

    function impressaorotina($ambulatorio_laudo_id) {

        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarrotinaimpressao($ambulatorio_laudo_id);
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['rotina'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }

        $base_url = base_url();


        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;

//        var_dump($carimbo);die;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];



        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;

        if ($data['empresa'][0]->ficha_config == 't') {
            if ($data['empresa'][0]->cabecalho_config == 't') {
                if ($data['cabecalhomedico'][0]->cabecalho != '') {
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                } else {
                    $cabecalho = "$cabecalho_config";
                }
//                $cabecalho = $cabecalho_config;
            } else {
                $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
            }

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            }

            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape = $texto_rodape . $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            }

            $filename = "laudo.pdf";
//            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
//            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            if (false) {
                $html = $this->load->view('ambulatorio/impressaorotina', $data, true);
            } else {
                $html = $this->load->view('ambulatorio/impressaorotinaconfiguravel', $data, true);
            }
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
            } else {
                pdf($html, $filename, $cabecalho, $rodape);
            }
            // pdf($html, $filename, $cabecalho, $rodape);
        }

        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
            $html = $this->load->view('ambulatorio/impressaorotina', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaorotina', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
            $html = $this->load->view('ambulatorio/impressaorotina', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaorotina', $data);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaorotina', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaorotina', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaorotina', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                        <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                        <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                        </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                        </table>";
            }
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            if ($data['laudo']['0']->medico_parecer1 == 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdf($html, $filename, $cabecalho, $rodape, $grupo);

///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo
            $filename = "laudo.pdf";

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } else {
                $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
            }

            $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Rotinas Médica</p></b></center></td></tr></table>";
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaorotina', $data, true);
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
            } else {
                pdf($html, $filename, $cabecalho, $rodape);
            }
            $this->load->View('ambulatorio/impressaorotina', $data);
        }
    }

    function impressaoTomada($ambulatorio_laudo_id) {
       
        $this->load->plugin('mpdf');
        $empresa_id = $this->session->userdata('empresa_id');
        $data['laudo'] = $this->laudo->listarTomadaimpressao($ambulatorio_laudo_id);

        // echo '<pre>';
        // print_r($data['laudo']);
        // die;

        $laudo_horario = $this->laudo->listarTomadaimpressaohorario($ambulatorio_laudo_id);

        // echo '<pre>';
        // print_r($laudo_horario);
        //  die;

        foreach($laudo_horario as $itens){
            $medicamentos_linha = $this->laudo->listarTomadaimpressaomedicamento($ambulatorio_laudo_id, $itens->horario, $itens->horario_texto, $itens->texto);
            
            // echo '<pre>';
            // print_r($medicamentos_linha);

            $linhas = '';
            $qtdtotal = count($medicamentos_linha);
            $i=0;
            foreach($medicamentos_linha as $linha){
                $i++;
                $linha->qtd = str_replace('.00', '', $linha->qtd);
                if($qtdtotal == $i){
                    if($linha->qtd == 1.00){
                        $linhas .= $linha->qtd .' '.$linha->medicamento;
                    }else{
                        $linhas .= $linha->qtd .' '.$linha->medicamento;
                    }
                }else{
                    if($linha->qtd == 1.00){
                        $linhas .= $linha->qtd .' '.$linha->medicamento.' + ';
                    }else{
                        $linhas .= $linha->qtd .' '.$linha->medicamento.' + ';
                    }
                }
                
            }
            $horario_texto = '';
            if($itens->texto != null){
                $horario_texto = $itens->texto;
            }else{
                if($itens->horario_texto == '1'){
                    $horario_texto = "Após Café da Manhã";
                }else if($itens->horario_texto == '2'){
                    $horario_texto = "Antes do Almoço";
                }else if($itens->horario_texto == '3'){
                    $horario_texto = "Antes do Jantar";
                }else if($itens->horario_texto == '4'){
                    $horario_texto = "Ao Deitar";
                }
            }

            $horario = '';

            if($itens->horario_texto == ''){
                $horario = $itens->horario .'h';
            }else if($itens->horario == ''){
                $horario = '';
            }else if($itens->horario_texto != '' && $itens->horario != ''){
                $horario = '('.$itens->horario.'h)';
            }
            
            $linha_laudo[$horario_texto.''.$horario] = $linhas;
        }
        // echo '<pre>';
        // print_r($linha_laudo);
        //  die;
        
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['rotina'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }

        $base_url = base_url();
        $texto = '
        <b>Paciente: </b> '.$data['laudo'][0]->paciente. 
        '<br><br>
        Prescrição:
        <br><br>';


        // echo '<pre>';
        // print_r($data['laudo']); 
        // die;
        $a = 0;
        foreach($linha_laudo as $key => $value){
            if($a == 0){
                $texto .= '<u>'.$key.'</u> <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$value;
            }else{
                $texto .= '<br><br><u>'.$key.'</u> <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$value;
            }
            $a++;
        }

        // echo $texto;
        // die;
        
        // foreach ($data['laudo'] as $key => $value) {
        //     $quantidade = number_format($value->quantidade_med, 2, ',', '.');
        //     // $texto .= " * $value->medicamento - $value->posologia - Quantidade: $value->quantidade_med <br>";
        //     $texto .= ""
        // }


        // echo '<pre>';
        // print_r($data['laudo']); 
        // die;

        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->medico_carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;

//        var_dump($carimbo);die;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];





        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        $texto .= "<br><br> ".$texto_rodape;
        $data['laudo'][0]->texto = $texto;

        if ($data['empresa'][0]->ficha_config == 't') {
            if ($data['empresa'][0]->cabecalho_config == 't') {
                if ($data['cabecalhomedico'][0]->cabecalho != '') {
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                } else {
                    $cabecalho = "$cabecalho_config";
                }
//                $cabecalho = $cabecalho_config;
            } else {
                $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
            }

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            }

            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape = $rodape_config;
            } 
            // else {
            //     $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            // }

            $filename = "laudo.pdf";
//            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
//            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            
            $html = $this->load->view('ambulatorio/impressaoTomadaConfiguravel', $data, true);
            
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;


            if ($sem_margins == 't') {
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
            } else {
                pdf($html, $filename, $cabecalho, $rodape);
            }
            // pdf($html, $filename, $cabecalho, $rodape);
        }
    }

    function impressaosolicitarexame($ambulatorio_laudo_id) {

        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarexameimpressao($ambulatorio_laudo_id);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        $empresa_id = $this->session->userdata('empresa_id');
        if(!@$empresa_id > 0){
            $empresa_id = 1;
        }
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }

        $base_url = base_url();


        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
      
                                
        $data['assinatura'] = $carimbo;

//        var_dump($carimbo);die;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];



        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;

        if ($data['empresa'][0]->ficha_config == 't') {
            if ($data['empresa'][0]->cabecalho_config == 't') {
                if ($data['cabecalhomedico'][0]->cabecalho != '') {
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                } else {
                    $cabecalho = "$cabecalho_config";
                }
//                $cabecalho = $cabecalho_config;
            } else {
                $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
            }

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            }

            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape = $texto_rodape . $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            }

            $filename = "laudo.pdf";

            // var_dump($data['laudo'][0]->texto); die;
//            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
//            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
            // Por causa do Tinymce Novo.
            if (false) {
                // echo 'asduha'; die;
                $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesreceita', $data, true);
            } else {
                $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
            }
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
            } else {
                pdf($html, $filename, $cabecalho, $rodape);
            }
        }

        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                        <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                        <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                        </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                        </table>";
            }
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            if ($data['laudo']['0']->medico_parecer1 == 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdf($html, $filename, $cabecalho, $rodape, $grupo);

///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo
            $filename = "laudo.pdf";

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } else {
                $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
            }

            $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'></p></b></center></td></tr><tr><td><b>Para:" . $data['laudo']['0']->paciente . "<br></b></td></tr></table>";
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
            } else {
                pdf($html, $filename, $cabecalho, $rodape);
            }
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }
    }
    function impressaosolicitarexamesalvar($ambulatorio_laudo_id, $laudo_id, $sem_data) {

        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarexameimpressao($ambulatorio_laudo_id);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        $empresa_id = $this->session->userdata('empresa_id');
        if(!@$empresa_id > 0){
            $empresa_id = 1;
        }
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }

        $base_url = base_url();

        if($data['laudo'][0]->exame_id > 0){
            $filename = str_replace(' ', '_', $data['laudo'][0]->modelo).'_'.substr($data['laudo']['0']->data_cadastro, 8, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 0, 4) .'.pdf';
        }else{
            $filename = 'Solicitacaoexames_'.substr($data['laudo']['0']->data_cadastro, 8, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 0, 4) .'.pdf';
        }

        // $filename = "solicitacaoexames_".$ambulatorio_laudo_id.".pdf";

        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;

//        var_dump($carimbo);die;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];



        if($sem_data == null){
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        }else{
            $texto_rodape = '';
        }

        if ($data['empresa'][0]->ficha_config == 't') {
            if ($data['empresa'][0]->cabecalho_config == 't') {          
                if ($data['cabecalhomedico'][0]->cabecalho2 != '') { // Cabeçalho do Profissional 2
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho2;
                } elseif($data['cabecalhomedico'][0]->cabecalho != ''){ // Cabeçalho do Profissional
                   $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
               } else {
                    $cabecalho = "$cabecalho_config";
                }
//                $cabecalho = $cabecalho_config;
            } else {
                $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
            }

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            }

            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape = $texto_rodape . $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            }
            // var_dump($data['laudo'][0]->texto); die;
//            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
//            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
            // Por causa do Tinymce Novo.

            // $data['extra'] = 'Terapeuticas';
            $data['laudo'][0]->texto = '<b><font size="7">Solicitações de Exames </font></b><br><br>'. $data['laudo'][0]->texto;
            // $data['laudo'][0]->texto = '1';
            // print_r($data['laudo'][0]->texto);
            // die;
            if (false) {
                // echo 'asduha'; die;
                $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesreceita', $data, true);
            } else {
                $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
            }
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
            
            $validarcert ='<div style="border: groove; width:180px; text-align: center; margin: 0pt 0pt 0pt 350pt;">Validar Certificado: <br> <a target="_blank" href="https://assinaturadigital.iti.gov.br/">validator.docusign.com</a></div>';
            $validarcert .= $cabecalho;
            $cabecalho = $validarcert;

            if ($sem_margins == 't') {
                pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id);
            } else {
                pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '',9,0,15, $laudo_id);
            }
        }else{

        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
            // $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '',9,0,15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
            // $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '',9,0,15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            // $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '',9,0,15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            // $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '',9,0,15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                        <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                        <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                        </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                        </table>";
            }
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            if ($data['laudo']['0']->medico_parecer1 == 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, $grupo,9,0,15, $laudo_id);

///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo
            // $filename = "laudo.pdf";

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } else {
                $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
            }

            $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'></p></b></center></td></tr><tr><td><b>Para:" . $data['laudo']['0']->paciente . "<br></b></td></tr></table>";
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id);
            } else {
                pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '',9,0,15, $laudo_id);
            }
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }
    }
        $this->laudo->informacaoimpressaosalvar($laudo_id, 'solicitacao_exames', $ambulatorio_laudo_id);
}

    function impressaoteraupetica($ambulatorio_laudo_id) {

        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarteraupeticaimpressao($ambulatorio_laudo_id);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        $empresa_id = $this->session->userdata('empresa_id');
        if(!@$empresa_id > 0){
            $empresa_id = 1;
        }
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }

        $base_url = base_url();


        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;

//        var_dump($carimbo);die;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];



        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;

        if ($data['empresa'][0]->ficha_config == 't') {
            if ($data['empresa'][0]->cabecalho_config == 't') {
                if ($data['cabecalhomedico'][0]->cabecalho != '') {
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                } else {
                    $cabecalho = "$cabecalho_config";
                }
//                $cabecalho = $cabecalho_config;
            } else {
                $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
            }

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            }

            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape = $texto_rodape . $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            }

            $filename = "laudo.pdf";

            // var_dump($data['laudo'][0]->texto); die;
//            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
//            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
            // Por causa do Tinymce Novo.
            if (false) {
                // echo 'asduha'; die;
                $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesreceita', $data, true);
            } else {
                $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
            }
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
            } else {
                pdf($html, $filename, $cabecalho, $rodape);
            }
        }

        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                        <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                        <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                        </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                        </table>";
            }
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            if ($data['laudo']['0']->medico_parecer1 == 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdf($html, $filename, $cabecalho, $rodape, $grupo);

///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo
            $filename = "laudo.pdf";

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } else {
                $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
            }

            $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'></p></b></center></td></tr><tr><td><b>Para:" . $data['laudo']['0']->paciente . "<br></b></td></tr></table>";
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
            } else {
                pdf($html, $filename, $cabecalho, $rodape);
            }
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }
    }

    function impressaoteraupeticasalvar($ambulatorio_laudo_id, $laudo_id, $sem_data) {

        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarteraupeticaimpressao($ambulatorio_laudo_id);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        $empresa_id = $this->session->userdata('empresa_id');
        if(!@$empresa_id > 0){
            $empresa_id = 1;
        }
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }

        $base_url = base_url();

        if($data['laudo'][0]->terapeuticas_id > 0){
		    $filename = str_replace(' ', '_', $data['laudo'][0]->modelo).'_'.substr($data['laudo']['0']->data_cadastro, 8, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 0, 4) .'.pdf';
		}else{
		    $filename = 'Terapeuticas_'.substr($data['laudo']['0']->data_cadastro, 8, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 0, 4) .'.pdf';
        }
        
        // $filename = "terapeuticas_".$ambulatorio_laudo_id.".pdf";


        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;

//        var_dump($carimbo);die;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];


        if($sem_data == null){
            $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        }else{
            $texto_rodape = '';
        }
        

        if ($data['empresa'][0]->ficha_config == 't') {
            if ($data['empresa'][0]->cabecalho_config == 't') {
                if ($data['cabecalhomedico'][0]->cabecalho2 != '') { // Cabeçalho do Profissional 2
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho2;
                } elseif($data['cabecalhomedico'][0]->cabecalho != ''){ // Cabeçalho do Profissional
                   $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
               } else {
                    $cabecalho = "$cabecalho_config";
                }
//                $cabecalho = $cabecalho_config;
            } else {
                $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
            }

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            }

            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape = $texto_rodape . $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            }
            $data['extra'] = "<font size='7'><b>Terapeuticas</b></font><br><br>";
            // var_dump($data['laudo'][0]->texto); die;
//            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
//            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
            // Por causa do Tinymce Novo.
            if (false) {
                // echo 'asduha'; die;
                $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesreceita', $data, true);
            } else {
                $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
            }
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            $validarcert ='<div style="border: groove; width:180px; text-align: center; margin: 0pt 0pt 0pt 350pt;">Validar Certificado: <br> <a target="_blank" href="https://assinaturadigital.iti.gov.br/">validator.docusign.com</a></div>';
            $validarcert .= $cabecalho;
            $cabecalho = $validarcert;

            if ($sem_margins == 't') {
                pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id);
            } else {
                pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            }
        }else{

        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
            // $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
            // $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            // $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            // $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                        <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                        <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                        </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                        </table>";
            }
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            if ($data['laudo']['0']->medico_parecer1 == 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, $grupo, 9, 0, 15, $laudo_id);

///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo
            // $filename = "laudo.pdf";

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } else {
                $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
            }

            $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'></p></b></center></td></tr><tr><td><b>Para:" . $data['laudo']['0']->paciente . "<br></b></td></tr></table>";
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id);
            } else {
                pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            }
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }
    }

    $this->laudo->informacaoimpressaosalvar($laudo_id, 'terapeuticas', $ambulatorio_laudo_id);
}
    function impressaorelatorio($ambulatorio_laudo_id) {

        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarrelatorioimpressao($ambulatorio_laudo_id);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        $empresa_id = $this->session->userdata('empresa_id');
        if(!@$empresa_id > 0){
            $empresa_id = 1;
        }
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }

        $base_url = base_url();


        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;

//        var_dump($carimbo);die;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];



        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;

        if ($data['empresa'][0]->ficha_config == 't') {
            if ($data['empresa'][0]->cabecalho_config == 't') {
                if ($data['cabecalhomedico'][0]->cabecalho != '') {
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                } else {
                    $cabecalho = "$cabecalho_config";
                }
//                $cabecalho = $cabecalho_config;
            } else {
                $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
            }

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            }

            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape = $texto_rodape . $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            }

            $filename = "laudo.pdf";

            // var_dump($data['laudo'][0]->texto); die;
//            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
//            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
            // Por causa do Tinymce Novo.
            if (false) {
                // echo 'asduha'; die;
                $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesreceita', $data, true);
            } else {
                $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
            }
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
            } else {
                pdf($html, $filename, $cabecalho, $rodape);
            }
        }

        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                        <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                        <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                        </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                        </table>";
            }
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            if ($data['laudo']['0']->medico_parecer1 == 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdf($html, $filename, $cabecalho, $rodape, $grupo);

///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo
            $filename = "laudo.pdf";

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } else {
                $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
            }

            $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'></p></b></center></td></tr><tr><td><b>Para:" . $data['laudo']['0']->paciente . "<br></b></td></tr></table>";
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
            } else {
                pdf($html, $filename, $cabecalho, $rodape);
            }
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }
    }

    function impressaorelatoriosalvar($ambulatorio_laudo_id, $laudo_id, $sem_data) {

        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarrelatorioimpressao($ambulatorio_laudo_id);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        $empresa_id = $this->session->userdata('empresa_id');
        if(!@$empresa_id > 0){
            $empresa_id = 1;
        }
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }

        $base_url = base_url();

        if($data['laudo'][0]->relatorio_id > 0){
		    $filename = str_replace(' ', '_', $data['laudo'][0]->modelo).'_'.substr($data['laudo']['0']->data_cadastro, 8, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 0, 4) .'.pdf';
		}else{
		    $filename = 'Relatorios_'.substr($data['laudo']['0']->data_cadastro, 8, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 0, 4) .'.pdf';
        }
        
        // $filename = "relatorios_".$ambulatorio_laudo_id.".pdf";


        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;

//        var_dump($carimbo);die;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];


        if($sem_data == null){
            $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        }else{
            $texto_rodape = '';
        }
        

        if ($data['empresa'][0]->ficha_config == 't') {
            if ($data['empresa'][0]->cabecalho_config == 't') {
                if ($data['cabecalhomedico'][0]->cabecalho2 != '') { // Cabeçalho do Profissional 2
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho2;
                } elseif($data['cabecalhomedico'][0]->cabecalho != ''){ // Cabeçalho do Profissional
                   $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
               } else {
                    $cabecalho = "$cabecalho_config";
                }
//                $cabecalho = $cabecalho_config;
            } else {
                $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
            }

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            }

            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape = $texto_rodape . $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            }
            $data['extra'] = "<font size='7'><b>Relatório</b></font><br><br>";
            // var_dump($data['laudo'][0]->texto); die;
//            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
//            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
            // Por causa do Tinymce Novo.
            if (false) {
                // echo 'asduha'; die;
                $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesreceita', $data, true);
            } else {
                $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
            }
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            $validarcert ='<div style="border: groove; width:180px; text-align: center; margin: 0pt 0pt 0pt 350pt;">Validar Certificado: <br> <a target="_blank" href="https://assinaturadigital.iti.gov.br/">validator.docusign.com</a></div>';
            $validarcert .= $cabecalho;
            $cabecalho = $validarcert;
            
            if ($sem_margins == 't') {
                pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id);
            } else {
                pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            }
        } else{

        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
            // $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
            // $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            // $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            // $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                        <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                        <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                        </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                        </table>";
            }
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            if ($data['laudo']['0']->medico_parecer1 == 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, $grupo, 9, 0, 15, $laudo_id);

///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo
            // $filename = "laudo.pdf";

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } else {
                $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
            }

            $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'></p></b></center></td></tr><tr><td><b>Para:" . $data['laudo']['0']->paciente . "<br></b></td></tr></table>";
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id);
            } else {
                pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            }
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }
    }
    $this->laudo->informacaoimpressaosalvar($laudo_id, 'relatorios', $ambulatorio_laudo_id);
}
    function impressaoatestado($ambulatorio_laudo_id) {

        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listaratestadoimpressao($ambulatorio_laudo_id);
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        if(!@$empresa > 0){
            $empresa = 1;
        }
        if(!@$empresa_id > 0){
            $empresa_id = 1;
        }
        $data['atestado'] = true;
        $data['imprimircid'] = $data['laudo']['0']->imprimir_cid;
        $data['co_cid'] = $data['laudo']['0']->cid1;
        $data['co_cid2'] = $data['laudo']['0']->cid2;
//        var_dump($data); die;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;

        if (isset($data['co_cid'])) {
            $data['cid'] = $this->laudo->listarcid($data['co_cid']);
        }

        if (isset($data['co_cid'])) {
            $data['cid2'] = $this->laudo->listarcid($data['co_cid2']);
        }

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }

        $base_url = base_url();

        $this->load->helper('directory');
        $arquivos = directory_map("./upload/operadorLOGO/");
        $arquivo_existe = false;
        foreach ($arquivos as $value) {
            if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                $arquivo_existe = true;
                $data['medico_parecer1'] = $data['laudo'][0]->medico_parecer1;
                break;
            }
        }
//        var_dump($data['laudo'][0]->medico_parecer1);die;

        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value'/>";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;
//        var_dump($carimbo); die;

        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data, 8, 2);
        $mes = substr($data['laudo'][0]->data, 5, 2);
        $ano = substr($data['laudo'][0]->data, 0, 4);

        $nomemes = $meses[$mes];


        $texto_rodape = @$data['empresa'][0]->municipio . ", " . $dia . " de " . $nomemes . " de " . $ano;


        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['empresa'][0]->atestado_config == 't') {
            if ($arquivo_existe) {
                $src = base_url() . "upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . '.jpg';
            } else {
                $src = 'img/cabecalho.jpg';
            }
            $filename = "Atestado.pdf";
            if ($data['empresa'][0]->cabecalho_config == 't') {
                $cabecalho = $cabecalho_config;
            } else {
                $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='{$src}'></td></tr></table>";
            }
            $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
            if ($data['empresa'][0]->atestado_config == 't') {
                $rodape = $texto_rodape . $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            }

            $html = $this->load->view('ambulatorio/impressaoatestadoconfiguravel', $data, true);
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
            } else {
                pdf($html, $filename, $cabecalho, $rodape);
            }
//            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }


        if ($data['empresa'][0]->impressao_tipo == 14) {//MEDLAB
            if ($arquivo_existe) {
                $src = base_url() . "upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . '.jpg';
            } else {
                $src = 'img/medlab.jpg';
            }
            $filename = "laudo.pdf";

            if ($data['empresa'][0]->cabecalho_config == 't') {
                $cabecalho = "$cabecalho_config";
            } else {
                $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='{$src}'></td></tr></table>";
            }

//        $rodape = "<img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituariomedlab', $data, true);
            pdf($html, $filename, $cabecalho);
            $this->load->View('ambulatorio/impressaoreceituariomedlab', $data);
        }

//////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA   
            if ($arquivo_existe) {
                $src = base_url() . "upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . '.jpg';
            } else {
                $src = 'img/humana.jpg';
            }
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='{$src}'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data, 8, 2) . '/' . substr($data['laudo']['0']->data, 5, 2) . '/' . substr($data['laudo']['0']->data, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

//////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE      
            if ($arquivo_existe) {
                $src = base_url() . "upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . '.jpg';
            } else {
                $src = 'img/cage.jpg';
            }
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='{$src}'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data, 8, 2) . '/' . substr($data['laudo']['0']->data, 5, 2) . '/' . substr($data['laudo']['0']->data, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

//////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            if ($arquivo_existe) {
                $src = base_url() . "upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . '.jpg';
            } else {
                $src = 'img/logo2.png';
            }
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='{$src}'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data, 8, 2) . '/' . substr($data['laudo']['0']->data, 5, 2) . '/' . substr($data['laudo']['0']->data, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ    
            if ($arquivo_existe) {
                $src = base_url() . "upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . '.jpg';
            } else {
                $src = 'img/cabecalho.jpg';
            }
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  src='{$src}'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data, 8, 2) . '/' . substr($data['laudo']['0']->data, 5, 2) . '/' . substr($data['laudo']['0']->data, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td><center><img align = 'left' src='img/rodape.jpg'></center></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                    <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                    <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                    <tr><td></td><td></td></tr>
                    <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                    <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                    <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                    <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                    </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                    <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                    <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                    <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                    <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                    <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                    <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                    </table>";
            }
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                    <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            if ($data['laudo']['0']->medico_parecer1 == 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                    <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                    <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdf($html, $filename, $cabecalho, $rodape, $grupo);
        } else { //GERAL        //este item fica sempre por útimo
            if ($arquivo_existe) {
                $src = base_url() . "upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . '.jpg';
            } else {
                $src = 'img/cabecalho.jpg';
            }
            $filename = "laudo.pdf";
            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='{$src}'></td></tr><tr><td><center><b>ATESTADO MÉDICO</b></center><br/><br/><br/></td></tr><tr><td><b>Para:" . $data['laudo']['0']->paciente . "<br></b></td></tr></table>";
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
            } else {
                pdf($html, $filename, $cabecalho, $rodape);
            }
//            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }
    }

    function impressaoreceitaespecial($ambulatorio_laudo_id,$receituario=NULL) {
        @$certificado_medico = $this->guia->certificadomedico($data['laudo'][0]->medico_parecer1);
        $empresapermissao = $this->guia->listarempresasaladepermissao();

        $empresa_id = $this->session->userdata('empresa_id');
        $this->load->plugin('mpdf');
        $empresa = $this->session->userdata('empresa_id');
        if(!$empresa > 0){
            $empresa = 1;
        }
        if(!$empresa_id > 0){
            $empresa_id = 1;
        }
        $data['permissao'] = $this->empresa->listarverificacaopermisao2($empresa);
       
        if ($receituario== "true") {
          $data['laudo'] = $this->laudo->listarreceitaimpressao($ambulatorio_laudo_id);   
        }else{
           $data['laudo'] = $this->laudo->listarreceitaespecialimpressao($ambulatorio_laudo_id);
        }                     
         
        $permissao = $this->empresa->listarverificacaopermisao2($empresa);
         
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresamunicipio();
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo'][0]->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }

        $this->load->helper('directory');
        $arquivos = directory_map("./upload/operadorLOGO/");
        $data['assinatura'] = directory_map("./upload/1ASSINATURAS/");
        $data['arquivo_existe'] = false;
//        var_dump($arquivos); die;
//        var_dump($arquivos);die;
        if (@$arquivos != false) {
            foreach (@$arquivos as $value) {
                if (@$value == @$data['laudo'][0]->medico_parecer1 . ".jpg") {
                    @$data['arquivo_existe'] = true;
                    @$data['medico_parecer1'] = @$data['laudo'][0]->medico_parecer1;
                    break;
                }
            }
        } 
        
        $cabecalho =  $this->load->view('ambulatorio/cabecalhorelatorioespecial', $data, true);
        // print_r($cabecalho);
        // die;

        // print_r($cabecalho);
        // die;
        $rodape =  $this->load->view('ambulatorio/rodaperelatorioespecial', $data, true);
        
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        @$data['cabecalho'][0]->cabecalho = str_replace("_minicurriculum_", $data['cabecalhomedico'][0]->curriculo,$data['cabecalho'][0]->cabecalho);
       
        if (@$data['cabecalho'][0]->receituario_especial == "t") {
            $cabecalho_config = $data['cabecalho'][0]->cabecalho;
            if ($data['permissao'][0]->a4_receituario_especial == "t") { 
                 $cabecalho = "<table border='0'  width=100%><tr><td>".$cabecalho_config."</td></tr></table>";      
            }else{
                 $cabecalho = "<table border='0'  width=100%><tr><td>".$cabecalho_config."</td><td>".$cabecalho_config."</td></tr></table>";      
            }  

        }else{ 

           if ($data['permissao'][0]->a4_receituario_especial == "t") { 
              $cabecalho =  $this->load->view('ambulatorio/cabecalhorelatorioespecialA4', $data, true);
            }   
        } 
        $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
        
        if ($permissao[0]->a4_receituario_especial == "t") { 
           $html = $this->load->View('ambulatorio/impressaoreceituarioespecialA4', $data,true);
          
           if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

            $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);

            if(isset($json_post->access_token)){
                    pdfcertificado($html, 'ReceituarioEspecial.pdf', $cabecalho, $rodape, '', 5, 0, 4, $ambulatorio_laudo_id);

                   $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                   $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);

                    $this->db->select('link_certificado');
                    $this->db->from('tb_empresa');
                    $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                    $query = $this->db->get();
                    $return = $query->result();
                    $link = $return[0]->link_certificado;
                
                    $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
    
                    sleep(5);
                    $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                    unlink($local_salvamento.'/laudo.pdf');

                    redirect($url);

            }else{
                pdf($html, 'ReceituarioEspecial.pdf', $cabecalho, $rodape, '', 0, 0, 0); 
            }

        }else{

            pdf($html, 'ReceituarioEspecial.pdf', $cabecalho, $rodape, '', 0, 0, 0); 

        }
           
        }else{ 
            $html = $this->load->View('ambulatorio/impressaoreceituarioespecial', $data,true);
            // print_r($cabecalho);
            // die;
            pdfrespecial($html, 'ReceituarioEspecial.pdf', $cabecalho, $rodape, '', 5, 0, 4,5 ); 
        }
        
       
    }

    function impressaoreceitaespecialsalvar($ambulatorio_laudo_id,$receituario=NULL, $laudo_id, $sem_data = null) {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->load->plugin('mpdf');
        $empresa = $this->session->userdata('empresa_id');
        if(!$empresa > 0){
            $empresa = 1;
        }
        if(!$empresa_id > 0){
            $empresa_id = 1;
        }
        $data['permissao'] = $this->empresa->listarverificacaopermisao2($empresa);
       
        if ($receituario== "true") {
          $data['laudo'] = $this->laudo->listarreceitaimpressao($ambulatorio_laudo_id, 'especial');   
        }else{
           $data['laudo'] = $this->laudo->listarreceitaespecialimpressao($ambulatorio_laudo_id);
        }

        //     echo '<pre>';
        //  print_r($data['laudo']);
        //  die;
        $permissao = $this->empresa->listarverificacaopermisao2($empresa);
        
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresamunicipio();
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo'][0]->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }
       
        $this->load->helper('directory');
        $arquivos = directory_map("./upload/operadorLOGO/");
        $data['assinatura'] = directory_map("./upload/1ASSINATURAS/");
        $data['arquivo_existe'] = false;
//        var_dump($arquivos); die;
//        var_dump($arquivos);die;
        if (@$arquivos != false) {
            foreach (@$arquivos as $value) {
                if (@$value == @$data['laudo'][0]->medico_parecer1 . ".jpg") {
                    @$data['arquivo_existe'] = true;
                    @$data['medico_parecer1'] = @$data['laudo'][0]->medico_parecer1;
                    break;
                }
            }
        } 

        if($sem_data != null){
            $data['semdata'] = true;
        }
        
        $cabecalho =  $this->load->view('ambulatorio/cabecalhorelatorioespecial', $data, true);
        $rodape =  $this->load->view('ambulatorio/rodaperelatorioespecial', $data, true);
        
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        @$data['cabecalho'][0]->cabecalho = str_replace("_minicurriculum_", $data['cabecalhomedico'][0]->curriculo,$data['cabecalho'][0]->cabecalho);
        
        if ($data['cabecalho'][0]->receituario_especial == "t") {
            $cabecalho_config = $data['cabecalho'][0]->cabecalho;
            if ($data['permissao'][0]->a4_receituario_especial == "t") { 
                 $cabecalho = "<table border='0'  width=100%><tr><td>".$cabecalho_config."</td></tr></table>";      
            }else{
                 $cabecalho = "<table border='0'  width=100%><tr><td>".$cabecalho_config."</td><td>".$cabecalho_config."</td></tr></table>";      
            }  
        }else{ 
           if ($data['permissao'][0]->a4_receituario_especial == "t") { 
              $cabecalho =  $this->load->view('ambulatorio/cabecalhorelatorioespecialA4', $data, true);
            }   
        } 
        
        $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
        
// $filename = 'receituarioEspecial_'.$ambulatorio_laudo_id.'.pdf';
        if($data['laudo'][0]->receita_id > 0){
            $filename = str_replace(' ', '_', $data['laudo'][0]->modelo).'_'.substr($data['laudo']['0']->data_cadastro, 8, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 0, 4) .'.pdf';
        }else{
            $filename = 'ReceitaEspecialComum'.substr($data['laudo']['0']->data_cadastro, 8, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '_' . substr($data['laudo']['0']->data_cadastro, 0, 4) .'.pdf';
        }

// echo $laudo_id;
// die;

        if ($data['permissao'][0]->a4_receituario_especial == "t") { 
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                $cabecalho .=  $this->load->view('ambulatorio/cabecalhorelatorioespecialA4', $data, true);
            }
        }

        if ($permissao[0]->a4_receituario_especial == "t") { 
           $html = $this->load->View('ambulatorio/impressaoreceituarioespecialA4', $data,true);
           pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 5, 0, 4, $laudo_id); 


        }else{ 
           $html = $this->load->View('ambulatorio/impressaoreceituarioespecial', $data,true);
           pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 5, 0, 4, $laudo_id); 
        }
        
       $this->laudo->informacaoimpressaosalvar($laudo_id, 'receita_especial', $ambulatorio_laudo_id);
    }

    function impressaolaudoantigo($id) {
        $data['laudo'] = $this->laudo->listarlaudoantigoimpressao($id);

        //$this->carregarView($data, 'giah/servidor-form');
        $this->load->View('ambulatorio/impressaolaudoantigo', $data);
    }

    function impressaolaudoimagemsalvar($ambulatorio_laudo_id, $exame_id) {
        $this->load->plugin('mpdf');
        $sort = $this->laudo->listarnomeimagem($exame_id);

        $sort_array = array();
        for ($i = 0; $i < count($sort); $i++) {
            if (substr($sort[$i]->nome, 0, 7) == 'Foto 10') {
                $c = $i;
                continue;
            }
            $sort_array[] = $sort[$i]->nome;
        }
        if (isset($c)) {
            $sort_array[] = $sort[$c]->nome;
        }

        $data['nomeimagem'] = $sort_array;


        $data['empresa'] = $this->guia->listarempresa();

        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($data['empresa'][0]->empresa_id);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($data['empresa'][0]->empresa_id);
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;
        $verificador = $data['laudo']['0']->imagens;
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');
//        var_dump($data['empresa'][0]->impressao_tipo); die;

        if ($data['empresa'][0]->laudo_config == 't') {
            if ($data['impressaolaudo'][0]->cabecalho == 't') {
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    $cabecalho = "$cabecalho_config . <table><tr><td></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                } else {
                    $cabecalho = "<table><tr><td></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                }
            }
            $filename = "laudo.pdf";

            if ($verificador == 1) {
                $html = $this->load->view('ambulatorio/impressaoimagem1configuravel', $data, true);
            }
            if ($verificador == 2) {
                $html = $this->load->view('ambulatorio/impressaoimagem2configuravel', $data, true);
            }
            if ($verificador == 3) {
                $html = $this->load->view('ambulatorio/impressaoimagem3configuravel', $data, true);
            }
            if ($verificador == 4) {
                $html = $this->load->view('ambulatorio/impressaoimagem4configuravel', $data, true);
            }
            if ($verificador == 5) {
                $html = $this->load->view('ambulatorio/impressaoimagem5configuravel', $data, true);
            }
            if ($verificador == 6 || $verificador == "") {

                $html = $this->load->view('ambulatorio/impressaoimagem6configuravel', $data, true);
            }

            if ($data['empresa'][0]->rodape_config == 't') {
                $rodape = "$rodape_config";
            } else {
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
            }
        } else {

            if ($data['empresa'][0]->impressao_tipo == 1) {//humana
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";

                if ($verificador == 1) {
                    $html = $this->load->view('ambulatorio/impressaoimagem1', $data, true);
                }
                if ($verificador == 2) {
                    $html = $this->load->view('ambulatorio/impressaoimagem2', $data, true);
                }
                if ($verificador == 3) {
                    $html = $this->load->view('ambulatorio/impressaoimagem3', $data, true);
                }
                if ($verificador == 4) {
                    $html = $this->load->view('ambulatorio/impressaoimagem4', $data, true);
                }
                if ($verificador == 5) {
                    $html = $this->load->view('ambulatorio/impressaoimagem5', $data, true);
                }
                if ($verificador == 6 || $verificador == "") {

                    $html = $this->load->view('ambulatorio/impressaoimagem6', $data, true);
                }
            }

/////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE
                $filename = "laudo.pdf";
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
        </td><td width='430px'>Nome.:" . $data['laudo']['0']->paciente . "</td><td></td>
        </tr>
        <tr>
          </td><td >Sexo:" . $SEXO . " Idade:" . substr($teste, 0, 2) . "</td><td></td>
        </tr>
        
                                                                                                                                                                                                        </table>";
                $rodape = "";
                $html = $this->load->view('ambulatorio/impressaoimagem6cage', $data, true);
            }

////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 16) {//GASTROSUL
                $filename = "laudo.pdf";
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
            </td><td width='100px'></td><td width='430px'>Nome.:" . $data['laudo']['0']->paciente . "</td><td></td>
            </tr>
            <tr>
              </td><td width='100px'></td><td >Sexo:" . $SEXO . " Idade:" . substr($teste, 0, 2) . "</td><td></td>
            </tr>
            
                                                                                                                                                                                                        </table>";
                $rodape = "";
//                var_dump($html); die;
                $html = $this->load->view('ambulatorio/impressaoimagem6gastrosul', $data, true);
            }

////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC      
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            }

////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 11) {//clinica MAIS      
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame: Dr(a). " . $data['laudo']['0']->procedimento . "</td></tr></table>";
                $rodape = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr></table>";
            }

////////////////////////////////////////////////////////////////////////////////        
            else {//GERAL  // este item deve ficar sempre por último
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
                if ($verificador == 1) {
                    $html = $this->load->view('ambulatorio/impressaoimagem1', $data, true);
                }
                if ($verificador == 2) {
                    $html = $this->load->view('ambulatorio/impressaoimagem2', $data, true);
                }
                if ($verificador == 3) {
                    $html = $this->load->view('ambulatorio/impressaoimagem3', $data, true);
                }
                if ($verificador == 4) {
                    $html = $this->load->view('ambulatorio/impressaoimagem4', $data, true);
                }
                if ($verificador == 5) {
                    $html = $this->load->view('ambulatorio/impressaoimagem5', $data, true);
                }
                if ($verificador == 6 || $verificador == "") {

                    $html = $this->load->view('ambulatorio/impressaoimagem6', $data, true);
                }
            }
        }


        $grupo = $data['laudo']['0']->grupo;

        if ($data['arquivo_pasta'] != false) {
            $nome = str_replace(' ','_',$data['laudo'][0]->procedimento);
            $nome = str_replace('/','_',$data['laudo'][0]->procedimento);
            $filename = 'imagens_'.$nome.'_'.substr($data['laudo'][0]->data, 8, 2) . '_' . substr($data['laudo'][0]->data, 5, 2) . '_' . substr($data['laudo'][0]->data, 0, 4).'.pdf';
            $this->laudo->salvararquivolaudo($data['laudo'][0]->paciente_id, $filename, $ambulatorio_laudo_id, $data['laudo'][0]->medico_parecer1);
            pdfsalvarlaudo($html, $filename, $cabecalho, $rodape, $grupo, 9,0,15, $ambulatorio_laudo_id);
        }


    }

    function impressaoimagem($ambulatorio_laudo_id, $exame_id) {
        $this->load->plugin('mpdf');
        $sort = $this->laudo->listarnomeimagem($exame_id);
       
        $sort_array = array();
        for ($i = 0; $i < count($sort); $i++) {
            if (substr($sort[$i]->nome, 0, 7) == 'Foto 10') {
                $c = $i;
                continue;
            }
            $sort_array[] = $sort[$i]->nome;
        }
        if (isset($c)) {
            $sort_array[] = $sort[$c]->nome;
        }

        $data['nomeimagem'] = $sort_array;


        $data['empresa'] = $this->guia->listarempresa();
        
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($data['empresa'][0]->empresa_id);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($data['empresa'][0]->empresa_id);
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;
        $verificador = $data['laudo']['0']->imagens;
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");

        // print_r($data['arquivo_pasta']);
        // die;
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');
//        var_dump($data['empresa'][0]->impressao_tipo); die;

        if ($data['empresa'][0]->laudo_config == 't') {
            if ($data['impressaolaudo'][0]->cabecalho == 't') {
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    $cabecalho = "$cabecalho_config . <table><tr><td></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                } else {
                    $cabecalho = "<table><tr><td></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                }
            }
            $filename = "laudo.pdf";

            if ($verificador == 1) {
                $html = $this->load->view('ambulatorio/impressaoimagem1configuravel', $data, true);
            }
            if ($verificador == 2) {
                $html = $this->load->view('ambulatorio/impressaoimagem2configuravel', $data, true);
            }
            if ($verificador == 3) {
                $html = $this->load->view('ambulatorio/impressaoimagem3configuravel', $data, true);
            }
            if ($verificador == 4) {
                $html = $this->load->view('ambulatorio/impressaoimagem4configuravel', $data, true);
            }
            if ($verificador == 5) {
                $html = $this->load->view('ambulatorio/impressaoimagem5configuravel', $data, true);
            }
            if ($verificador == 6 || $verificador == "") {

                $html = $this->load->view('ambulatorio/impressaoimagem6configuravel', $data, true);
            }

            if ($data['empresa'][0]->rodape_config == 't') {
                $rodape = "$rodape_config";
            } else {
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
            }
        } else {

            if ($data['empresa'][0]->impressao_tipo == 1) {//humana
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";

                if ($verificador == 1) {
                    $html = $this->load->view('ambulatorio/impressaoimagem1', $data, true);
                }
                if ($verificador == 2) {
                    $html = $this->load->view('ambulatorio/impressaoimagem2', $data, true);
                }
                if ($verificador == 3) {
                    $html = $this->load->view('ambulatorio/impressaoimagem3', $data, true);
                }
                if ($verificador == 4) {
                    $html = $this->load->view('ambulatorio/impressaoimagem4', $data, true);
                }
                if ($verificador == 5) {
                    $html = $this->load->view('ambulatorio/impressaoimagem5', $data, true);
                }
                if ($verificador == 6 || $verificador == "") {

                    $html = $this->load->view('ambulatorio/impressaoimagem6', $data, true);
                }
            }

/////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE
                $filename = "laudo.pdf";
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
        </td><td width='430px'>Nome.:" . $data['laudo']['0']->paciente . "</td><td></td>
        </tr>
        <tr>
          </td><td >Sexo:" . $SEXO . " Idade:" . substr($teste, 0, 2) . "</td><td></td>
        </tr>
        
                                                                                                                                                                                                        </table>";
                $rodape = "";
                $html = $this->load->view('ambulatorio/impressaoimagem6cage', $data, true);
            }

////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 16) {//GASTROSUL
                $filename = "laudo.pdf";
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
            </td><td width='100px'></td><td width='430px'>Nome.:" . $data['laudo']['0']->paciente . "</td><td></td>
            </tr>
            <tr>
              </td><td width='100px'></td><td >Sexo:" . $SEXO . " Idade:" . substr($teste, 0, 2) . "</td><td></td>
            </tr>
            
                                                                                                                                                                                                        </table>";
                $rodape = "";
//                var_dump($html); die;
                $html = $this->load->view('ambulatorio/impressaoimagem6gastrosul', $data, true);
            }

////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC      
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            }

////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 11) {//clinica MAIS      
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame: Dr(a). " . $data['laudo']['0']->procedimento . "</td></tr></table>";
                $rodape = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr></table>";
            }

////////////////////////////////////////////////////////////////////////////////        
            else {//GERAL  // este item deve ficar sempre por último
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
                if ($verificador == 1) {
                    $html = $this->load->view('ambulatorio/impressaoimagem1', $data, true);
                }
                if ($verificador == 2) {
                    $html = $this->load->view('ambulatorio/impressaoimagem2', $data, true);
                }
                if ($verificador == 3) {
                    $html = $this->load->view('ambulatorio/impressaoimagem3', $data, true);
                }
                if ($verificador == 4) {
                    $html = $this->load->view('ambulatorio/impressaoimagem4', $data, true);
                }
                if ($verificador == 5) {
                    $html = $this->load->view('ambulatorio/impressaoimagem5', $data, true);
                }
                if ($verificador == 6 || $verificador == "") {

                    $html = $this->load->view('ambulatorio/impressaoimagem6', $data, true);
                }
            }
        }


        $grupo = $data['laudo']['0']->grupo;

        pdf($html, $filename, $cabecalho, $rodape, $grupo);

//                pdf($html, $filename, $cabecalho, $rodape);
    }

    function gerarxml() {

        $this->load->plugin('mpdf');

//        $listarexame = $this->laudo->listarxmllaudo();
//        echo '<pre>';
//        var_dump($listarexame);
//        die;
        //        if ($_POST['convenio'] !== "") {
        $listarexame = $this->laudo->listarxmllaudo();
        $empresa = $this->exame->listarcnpj();
//        $cnpjxml = $listarexame[0]->codigoidentificador;
//        $razao_socialxml = $empresa[0]->razao_socialxml;
//        $registroans = $listarexame[0]->registroans;
//        $cpfxml = $empresa[0]->cpfxml;
//        $cnpj = $empresa[0]->cnpj;

        $convenio = $listarexame[0]->convenio;
//        $datainicio = str_replace("/", "", $_POST['datainicio']);
//        $datafim = str_replace("/", "", $_POST['datafim']);
//        $paciente = $listarexame[0]->paciente;
//        $nomearquivo = $convenio . "-" . $paciente . "-" . $datainicio . "-" . $datafim;
        $origem = "./upload/laudo";

        if (!is_dir($origem)) {
            mkdir($origem);
            chmod($origem, 0777);
        }
        if (!is_dir($origem . '/' . $convenio)) {
            mkdir($origem . '/' . $convenio);
            chmod($origem . '/' . $convenio, 0777);
        }

        $tipo_xml = $this->laudo->listarempresatipoxml(); //verifica qual tipo de xml que a empresa usa.
        if ($tipo_xml[0]->nome == 'TIPO 1') { // se o tipo xml for SLINE (início).
            $corpo = "";
            $paciente_dif = "";
            foreach ($listarexame as $item) {

                if ($_POST['apagar'] == 1) {
                    delete_files($origem . '/' . $convenio . '/' . $item->paciente_id);
                }

                if ($item->paciente_id !== $paciente_dif) {
                    $sl_cod_doc = $item->ambulatorio_laudo_id;
                    $texto = "";
                    if (!is_dir($origem . '/' . $convenio . '/' . $item->paciente_id)) {
                        mkdir($origem . '/' . $convenio . '/' . $item->paciente_id);
                        chmod($origem . '/' . $convenio . '/' . $item->paciente_id, 0777);
                    }
                    //cria código para TAG <SL_COD_DOC>
//                $dataatual = date("Y-m-d");
//                $horarioatual = date("H-i");
//                $data_cod = str_replace("-", "", $dataatual);
//                $horario_cod = str_replace("-", "", $horarioatual);
//                $num_aleatorio = mt_rand(1000, 100000000);
//                $sl_cod_doc = $num_aleatorio . $data_cod . $horario_cod;
                    //NUMERO DA CARTEIRA
                    if ($item->convenionumero == '') {
                        $numerodacarteira = '0000000';
                    } else {
                        $numerodacarteira = $item->convenionumero;
                    }

                    //NUMERO GUIA CONVENIO  
                    if ($item->guiaconvenio == '') {
                        $numeroguia = '0000000';
                    } else {
                        $numeroguia = $item->guiaconvenio;
                    }

                    $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
                      <S_LINE>
                      <SL_TIPO>RP</SL_TIPO>
                      <SL_TITULO>" . $item->paciente . "-" . $item->nascimento . "</SL_TITULO>
                      <SL_SN>O</SL_SN>
                      <SL_CHAVE></SL_CHAVE>
                      <SL_SENHA></SL_SENHA>                      
                      <SL_COD_DOC>" . $sl_cod_doc . "</SL_COD_DOC>                      
                      <SL_FORMATO>PDF</SL_FORMATO>
                      <SL_DATA_REALIZACAO>" . substr($item->data_realizacao, 0, 10) . "T" . substr($item->data_realizacao, 10, 18) . " </SL_DATA_REALIZACAO>
                        <SL_OPER>
                           <OPER_REGANS>384577</OPER_REGANS>
                           <OPER_NUMCARTEIRA>" . $numerodacarteira . "</OPER_NUMCARTEIRA>
                           <OPER_NUMGUIA>" . $numeroguia . "
                                ";
                    //este foreach irá inserir todos os códigos dos exames relacionados ao numeroguia 
                    foreach ($listarexame as $value) {
                        $corpo = $corpo . "<OPER_EXAME>" . $value->codigo . "</OPER_EXAME>";
                        $texto = $texto . $value->texto_laudo;
                    }


                    $fim_numguia = "</OPER_NUMGUIA>";

                    $rodape = "</SL_OPER>
                       <SL_TEXTO></SL_TEXTO>
                    </S_LINE>";

                    $nome = "./upload/laudo/" . $convenio . "/" . $item->paciente_id . "/" . $sl_cod_doc . ".xml";
                    $xml = $cabecalho . $corpo . $fim_numguia . $rodape;
                    $fp = fopen($nome, "w+");
                    fwrite($fp, $xml . "\n");
                    fclose($fp);

                    $nomepdf = "./upload/laudo/" . $convenio . "/" . $item->paciente_id . "/" . $sl_cod_doc . ".pdf";
                    $cabecalhopdf = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td>Nome:" . $item->paciente . " <br>Emiss&atilde;o: </td></tr></table>";
                    $rodapepdf = "<img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'>";
                    salvapdf($texto, $nomepdf, $cabecalhopdf, $rodapepdf);


                    $zip = new ZipArchive;
                    $this->load->helper('directory');
                    $arquivo_pasta = directory_map("./upload/laudo/$convenio/$item->paciente_id/");
                    $pasta = $item->paciente_id;
                    if ($arquivo_pasta != false) {
                        foreach ($arquivo_pasta as $value) {
                            $zip->open("./upload/laudo/$convenio/$pasta/$sl_cod_doc.zip", ZipArchive::CREATE);
                            $zip->addFile("./upload/laudo/$convenio/$pasta/$value", "$sl_cod_doc.xml");
                            $zip->addFile("./upload/laudo/$convenio/$pasta/$value", "$sl_cod_doc.pdf");
                            $zip->close();
                        }
                        $arquivoxml = "./upload/laudo/$convenio/$pasta/$sl_cod_doc.xml";
                        $arquivopdf = "./upload/laudo/$convenio/$pasta/$sl_cod_doc.pdf";
                        unlink($arquivoxml);
                        unlink($arquivopdf);
                    }

                    $paciente_dif = $item->paciente_id;
                }
            }
        } // se o tipo xml for SLINE (fim).
        else { // se o tipo xml for NAJA (início).
            $texto = "";
            $corpo = "";
            foreach ($listarexame as $item) {

                if (!is_dir($origem . '/' . $convenio)) {
                    mkdir($origem . '/' . $convenio);
                    chmod($origem . '/' . $convenio, 0777);
                }

                $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
                            <NAJA>
                                <NJ_CodPaciente>" . $item->paciente_id . "</NJ_CodPaciente>
                                <NJ_NomePaciente>" . $item->paciente . "</NJ_NomePaciente>
                                <NJ_Laudo>" . $item->ambulatorio_laudo_id . "</NJ_Laudo>
                                <NJ_LocalLaudo>./upload/laudo/" . $convenio . "</NJ_LocalLaudo>
                                <NJ_FormatoLaudo>RTF</NJ_FormatoLaudo>
                                <NJ_NomeMedicoLaudante>" . $item->medicosolicitante . "</NJ_NomeMedicoLaudante>
                                <NJ_Detalhes>";

                $corpo = $corpo . "<NJ_Exame>
                                            <NJ_Accessionnumber>" . $item->wkl_accnumber . "</NJ_Accessionnumber>                            
                                            <NJ_NomeExame>" . $item->wkl_procstep_descr . "</NJ_NomeExame>
                                        </NJ_Exame>";
                $texto = $texto . $value->texto_laudo;

                $rodape = "</NJ_Detalhes>
                       </NAJA>";

                $nome = "./upload/laudo/" . $convenio . "/" . $item->paciente_id . ".xml";
                $xml = $cabecalho . $corpo . $rodape;
                $fp = fopen($nome, "w+");
                fwrite($fp, $xml . "\n");
                fclose($fp);

                $nome = "./upload/laudo/" . $convenio . "/" . $item->paciente_id . ".rtf";
                $rtf = $texto;
                $fp = fopen($nome, "w+");
                fwrite($fp, $rtf . "\n");
                fclose($fp);
            }
        }// se o tipo xml for NAJA (fim).



        $data['mensagem'] = 'Sucesso ao gerar arquivo.';

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/faturamentolaudoxml", $data);
    }

    function gerarxmlsalvar($ambulatorio_laudo_id, $exame_id, $sala_id) {
        $this->load->plugin('mpdf');
//        var_dump($ambulatorio_laudo_id, $exame_id, $sala_id); die;
        $listarexame = $this->laudo->listarxmlsalvar($ambulatorio_laudo_id, $exame_id, $sala_id);
//        var_dump($listarexame); die;
        if (count($listarexame) > 0) {
            $empresa = $this->exame->listarcnpj();

            $convenio = $listarexame[0]->convenio;
            $pasta = $listarexame[0]->convenio_pasta;
            if ($pasta == '') {
                $pasta = $convenio;
            }

            $origem = "./upload/laudo";

            if (!is_dir($origem)) {
                mkdir($origem);
                chmod($origem, 0777);
            }
            if (!is_dir($origem . '/' . $pasta)) {
                mkdir($origem . '/' . $pasta);
                chmod($origem . '/' . $pasta, 0777);
            }


            $tipo_xml = $this->laudo->listarempresatipoxml(); //verifica qual tipo de xml que a empresa usa.   
            $impressao_tipo = $tipo_xml[0]->impressao_tipo;
            if ($tipo_xml[0]->nome == 'TIPO 1') {

                $corpo = "";
                $paciente_dif = "";

                if ($impressao_tipo == 2) {
                    foreach ($listarexame as $item) {


                        if ($item->paciente_id !== $paciente_dif) {

                            $data_atual = date('Y-m-d');
                            $data1 = new DateTime($data_atual);
                            $data2 = new DateTime($item->nascimento);
                            $intervalo = $data1->diff($data2);
                            $teste = $intervalo->y;
                            if ($pasta == 'UNIMED MAIS') {
                                $pasta = 'UNIMED UBERLANDIA';
                            }

                            $sl_cod_doc = $item->ambulatorio_laudo_id;
                            if (!is_dir($origem . '/' . $pasta)) {
                                mkdir($origem . '/' . $pasta);
                                chmod($origem . '/' . $pasta, 0777);
                            }
//                            if (!is_dir($origem . '/' . $pasta . '/' . $sl_cod_doc)) {
//                                mkdir($origem . '/' . $pasta . '/' . $sl_cod_doc);
//                                chmod($origem . '/' . $pasta . '/' . $sl_cod_doc, 0777);
//                            }
                            $texto = "";

                            //NUMERO DA CARTEIRA
                            if ($item->convenionumero == '') {
                                $numerodacarteira = '0000000';
                            } else {
                                $numerodacarteira = $item->convenionumero;
                            }

                            //NUMERO GUIA CONVENIO  
                            if ($item->guiaconvenio == '') {
                                $numeroguia = '0000000';
                            } else {
                                $numeroguia = $item->guiaconvenio;
                            }

                            $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
                      <S_LINE>
                      <SL_TIPO>RP</SL_TIPO>
                      <SL_TITULO>" . $item->paciente . "-" . $item->nascimento . "</SL_TITULO>
                      <SL_SN>O</SL_SN>
                      <SL_CHAVE></SL_CHAVE>
                      <SL_SENHA></SL_SENHA>                      
                      <SL_COD_DOC>" . $sl_cod_doc . "</SL_COD_DOC>                      
                      <SL_FORMATO>PDF</SL_FORMATO>
                      <SL_DATA_REALIZACAO>" . substr($item->data_realizacao, 0, 10) . "T" . substr($item->data_realizacao, 10, 18) . " </SL_DATA_REALIZACAO>
                        <SL_OPER>
                           <OPER_REGANS>$item->registroans</OPER_REGANS>
                           <OPER_NUMCARTEIRA>" . $numerodacarteira . "</OPER_NUMCARTEIRA>
                           <OPER_NUMGUIA>" . $numeroguia . "
                                ";
                            //   este foreach irá inserir todos os códigos dos exames relacionados ao numeroguia 
                            foreach ($listarexame as $value) {
                                $corpo = $corpo . "<OPER_EXAME>" . $value->codigo . "</OPER_EXAME>";
                                $texto = $texto . $value->texto;
                            }
                            // matriz de entrada
                            $what = array('&Aacute;', '&Eacute;', '&Iacute;', '&Oacute;', '&Uacute;', '&aacute;', '&eacute;', '&iacute;', '&oacute;', '&uacute;', '&Acirc;');

                            // matriz de saída
                            $by = array('A', '', '');

                            $saida = strip_tags($texto);
                            $saida = str_replace($what, $by, $saida);
//                            $saida = ($saida);
//                            echo $saida;
//                            die;
                            $fim_numguia = "</OPER_NUMGUIA>";

                            $rodape = "</SL_OPER>
                       <SL_TEXTO></SL_TEXTO>
                    </S_LINE>";
                            $nome = "./upload/laudo/" . $pasta . "/" . $sl_cod_doc . ".xml";
                            $xml = $cabecalho . $corpo . $fim_numguia . $rodape;
                            $fp = fopen($nome, "w+");
                            fwrite($fp, $xml . "\n");
                            fclose($fp);


                            $cabecalho = "<table>
    <tr>
      <td width='30px'></td><td><img align = 'left'  width='330px' height='100px' src='img/clinicadez.jpg'></td>
    </tr>
    <td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
    <tr>
    <td width='30px'></td><td>" . substr($item->sala, 0, 10) . "</td>
    </tr>
    <tr>
    <td width='30px'></td><td width='400px'>Reg.:" . $item->paciente_id . "</td><td>Emiss&atilde;o: " . substr($item->data, 8, 2) . '/' . substr($item->data, 5, 2) . '/' . substr($item->data, 0, 4) . "</td>
    </tr>
    <tr>
      <td width='30px'></td><td >Paciente:" . $item->paciente . "</td><td>Idade:" . $teste . "</td>
    </tr>
    <tr>
    <td width='30px'></td><td>Solicitante: Dr(a). " . $item->medicosolicitante . "</td><td>Sexo:" . $item->sexo . "</td>
    </tr>
    </tr>
    </tr><tr><td>&nbsp;</td></tr>
    <tr>
    </table>";
                            $rodape = "";
                            if ($item->situacao == "FINALIZADO" && $item->medico_parecer2 == "") {
                                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td width='400px'></td><td><img align = 'Right'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $item->medico_parecer1 . ".jpg'></td></tr></tr><tr><td>&nbsp;</td></tr></table>";
                            } elseif ($item->situacao == "FINALIZADO" && $item->medico_parecer2 != "") {
                                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td width='30px'></td><td><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $item->medico_parecer1 . ".jpg'></td><td width='30px'></td><td><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $item->medico_parecer2 . ".jpg'></td></tr></tr><tr><td>&nbsp;</td></tr></table>";
                            }

                            $nomepdf = "./upload/laudo/" . $pasta . "/" . $sl_cod_doc . ".pdf";
                            $cabecalhopdf = $cabecalho;
                            $rodapepdf = $rodape;
//                            $cabecalhopdf = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td>Nome:" . $item->paciente . " <br>Emiss&atilde;o: </td></tr></table>";
//                            $rodapepdf = "<img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'>";
                            salvapdf($texto, $nomepdf, $cabecalhopdf, $rodapepdf);


//                        $zip = new ZipArchive;
//                        $this->load->helper('directory');
//                        $arquivo_pasta = directory_map("./upload/laudo/$pasta/");
//                        $pasta = $item->paciente_id;
//                        if ($arquivo_pasta != false) {
//                            foreach ($arquivo_pasta as $value) {
//                                $zip->open("./upload/laudo/$pasta/$sl_cod_doc.zip", ZipArchive::CREATE);
//                                $zip->addFile("./upload/laudo/$pasta/$value", "$sl_cod_doc.xml");
//                                $zip->addFile("./upload/laudo/$pasta/$value", "$sl_cod_doc.pdf");
//                                $zip->close();
//                            }
//                            $arquivoxml = "./upload/laudo/$pasta/$sl_cod_doc.xml";
//                            $arquivopdf = "./upload/laudo/$pasta/$sl_cod_doc.pdf";
//                            unlink($arquivoxml);
//                            unlink($arquivopdf);
//                        }

                            $paciente_dif = $item->paciente_id;
                        }
                    }
                } else {
                    foreach ($listarexame as $item) {

//                    if ($_POST['apagar'] == 1) {
//                        delete_files($origem . '/' . $pasta . '/' . $item->paciente_id);
//                    }

                        if ($item->paciente_id !== $paciente_dif) {
                            $sl_cod_doc = $item->ambulatorio_laudo_id;
                            $texto = "";
                            if (!is_dir($origem . '/' . $pasta)) {
                                mkdir($origem . '/' . $pasta);
                                chmod($origem . '/' . $pasta, 0777);
                            }

                            //NUMERO DA CARTEIRA
                            if ($item->convenionumero == '') {
                                $numerodacarteira = '0000000';
                            } else {
                                $numerodacarteira = $item->convenionumero;
                            }

                            //NUMERO GUIA CONVENIO  
                            if ($item->guiaconvenio == '') {
                                $numeroguia = '0000000';
                            } else {
                                $numeroguia = $item->guiaconvenio;
                            }

                            $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
                      <S_LINE>
                      <SL_TIPO>RP</SL_TIPO>
                      <SL_TITULO>" . $item->paciente . "-" . $item->nascimento . "</SL_TITULO>
                      <SL_SN>O</SL_SN>
                      <SL_CHAVE></SL_CHAVE>
                      <SL_SENHA></SL_SENHA>                      
                      <SL_COD_DOC>" . $sl_cod_doc . "</SL_COD_DOC>                      
                      <SL_FORMATO>PDF</SL_FORMATO>
                      <SL_DATA_REALIZACAO>" . substr($item->data_realizacao, 0, 10) . "T" . substr($item->data_realizacao, 10, 18) . " </SL_DATA_REALIZACAO>
                        <SL_OPER>
                           <OPER_REGANS>384577</OPER_REGANS>
                           <OPER_NUMCARTEIRA>" . $numerodacarteira . "</OPER_NUMCARTEIRA>
                           <OPER_NUMGUIA>" . $numeroguia . "
                                ";
                            //este foreach irá inserir todos os códigos dos exames relacionados ao numeroguia 
                            foreach ($listarexame as $value) {
                                $corpo = $corpo . "<OPER_EXAME>" . $value->codigo . "</OPER_EXAME>";
                                $texto = $texto . $value->texto_laudo;
                            }


                            $fim_numguia = "</OPER_NUMGUIA>";

                            $rodape = "</SL_OPER>
                       <SL_TEXTO></SL_TEXTO>
                    </S_LINE>";

                            $nome = "./upload/laudo/" . $pasta . "/" . $sl_cod_doc . ".xml";
                            $xml = $cabecalho . $corpo . $fim_numguia . $rodape;
                            $fp = fopen($nome, "w+");
                            fwrite($fp, $xml . "\n");
                            fclose($fp);

                            $nomepdf = "./upload/laudo/" . $pasta . "/" . $sl_cod_doc . ".pdf";
                            $cabecalhopdf = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td>Nome:" . $item->paciente . " <br>Emiss&atilde;o: </td></tr></table>";
                            $rodapepdf = "<img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'>";
                            salvapdf($texto, $nomepdf, $cabecalhopdf, $rodapepdf);


                            $zip = new ZipArchive;
                            $this->load->helper('directory');
                            $arquivo_pasta = directory_map("./upload/laudo/$pasta/");
                            $pasta = $item->paciente_id;
                            if ($arquivo_pasta != false) {
                                foreach ($arquivo_pasta as $value) {
                                    $zip->open("./upload/laudo/$pasta/$sl_cod_doc.zip", ZipArchive::CREATE);
                                    $zip->addFile("./upload/laudo/$pasta/$value", "$sl_cod_doc.xml");
                                    $zip->addFile("./upload/laudo/$pasta/$value", "$sl_cod_doc.pdf");
                                    $zip->close();
                                }
                                $arquivoxml = "./upload/laudo/$pasta/$sl_cod_doc.xml";
                                $arquivopdf = "./upload/laudo/$pasta/$sl_cod_doc.pdf";
                                unlink($arquivoxml);
                                unlink($arquivopdf);
                            }

                            $paciente_dif = $item->paciente_id;
                        }
                    }
                }
            } else {

                if (!is_dir($origem . '/' . $pasta . '/' . $listarexame[0]->paciente_id)) {
                    mkdir($origem . '/' . $pasta . '/' . $listarexame[0]->paciente_id);
                    chmod($origem . '/' . $pasta . '/' . $listarexame[0]->paciente_id, 0777);
                }

                $cabecalho = "<?xml version='1.0' encoding='iso-8859-1'?>
                            <NAJA>
                                <NJ_CodPaciente>" . $listarexame[0]->paciente_id . "</NJ_CodPaciente>
                                <NJ_NomePaciente>" . $listarexame[0]->paciente . "</NJ_NomePaciente>
                                <NJ_Laudo>" . $listarexame[0]->ambulatorio_laudo_id . "</NJ_Laudo>
                                <NJ_LocalLaudo>./upload/laudo" . $pasta . "/" . $listarexame[0]->paciente_id . "</NJ_LocalLaudo>
                                <NJ_FormatoLaudo>RTF</NJ_FormatoLaudo>
                                <NJ_NomeMedicoLaudante>" . $listarexame[0]->medicosolicitante . "</NJ_NomeMedicoLaudante>
                               <NJ_Detalhes>";

                $corpo = "<NJ_Exame>
                            <NJ_Accessionnumber>" . $listarexame[0]->wkl_accnumber . "</NJ_Accessionnumber>                            
                            <NJ_NomeExame>" . $listarexame[0]->wkl_procstep_descr . "</NJ_NomeExame>
                       </NJ_Exame>";

                $texto = $listarexame[0]->texto_laudo;

                $rodape = "</NJ_Detalhes>
                       </NAJA>";

                $nome = "./upload/laudo/" . $pasta . "/" . $listarexame[0]->paciente_id . "/" . $listarexame[0]->paciente_id . ".xml";
                $xml = $cabecalho . $corpo . $rodape;
                $fp = fopen($nome, "w+");
                fwrite($fp, $xml . "\n");
                fclose($fp);

                $nome = "./upload/laudo/" . $pasta . "/" . $listarexame[0]->paciente_id . "/" . $listarexame[0]->paciente_id . ".rtf";
                $rtf = $texto;
                $fp = fopen($nome, "w+");
                fwrite($fp, $rtf . "\n");
                fclose($fp);
            }
            //        $data['mensagem'] = 'Sucesso ao gerar arquivo.';
//
//        $this->session->set_flashdata('message', $data['mensagem']);
//        redirect(base_url() . "ambulatorio/laudo", $data);
        }
    }

    function carregarrevisao($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelos();
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $this->load->helper('directory');
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresapermissao'] = $this->guia->listarempresasaladepermissao($empresa_id);
        $data['mensagem'] = $messagem;
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['obj'] = $obj_laudo;
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        //$this->carregarView($data, 'giah/servidor-form');
        $this->load->View('ambulatorio/laudorevisao-form', $data);
    }

    function oit($ambulatorio_laudo_id) {
        $verifica = $this->laudooit->contadorlaudo($ambulatorio_laudo_id);
        if ($verifica == 0) {
            $ambulatorio_laudooit_id = $this->laudooit->inserirlaudo($ambulatorio_laudo_id);
            $obj_laudo = new laudooit_model($ambulatorio_laudooit_id);
        } else {
            $resultado = $this->laudooit->consultalaudo($ambulatorio_laudo_id);
            $ambulatorio_laudooit_id = $resultado[0]->ambulatorio_laudooit_id;
            $obj_laudo = new laudooit_model($ambulatorio_laudooit_id);
            //        $obj_laudo = new laudooit_model($ambulatorio_laudooit_id);
            //        $data['lista'] = $this->exametemp->listarautocompletemodelos();
            //        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);
            //        $data['operadores'] = $this->operador_m->listarmedicos();
        }
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['obj'] = $obj_laudo;
        $data['medico_atendimento'] = $this->laudooit->medicoresponsavel($ambulatorio_laudo_id);
        //$this->carregarView($data, 'giah/servidor-form');
        // echo '<pre>';
        // print_r($data['obj']->_rx_digital);
        // die;
        $this->loadView('ambulatorio/laudooit-form-novo', $data);
    }

    function imagenspacs($accession_number) {
//        $verifica = $this->empresa->listarpacs();

        $pacs = $this->empresa->listarpacs();
        if (count($pacs) > 0) {

//        var_dump($agenda_exames_id);
//        die;
// $AN- variavel, com o accession number( numero do exame), obtida do sistema gestor da clinica;
            $AN = $accession_number;
            $ipPACS_LAN = $pacs[0]->ip_local; //Ip atribuido ao PACS, na LAN do cliente;
            $IPpublico = $pacs[0]->ip_externo; // IP, OU URL( dyndns, no-ip, etc) PARA ACESSO EXTERNO AO PACS;
//login que depende da clinica;
            $login = $pacs[0]->login;
            $password = $pacs[0]->senha;

// url de requisicao(GET),composta pelo IP publico da clinica  ou dns dinamico , considerando, que o seu webserver vai estar fora da clinica, se ele estiver na clinica, aqui deve ser substituido por $ipPACS_LAN ;

            $url = "http://{$IPpublico}/createlink?AccessionNumber={$AN}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
            $resultado = curl_exec($ch);
            curl_close($ch);
// A variavel $resultado, comtem o link, com o IP da rede local do pacs, que deve ser substituido pelo 
// endereco de acesso externo;
//$linkImagem, variável com o link a ser exportado para o site, para o cliente acessar as imagens;

            $linkImagem = str_replace("$ipPACS_LAN", "$IPpublico", "$resultado");

            echo $url, '<br>';
            echo $resultado, '<br>';
            echo $linkImagem, '<br>';


//        if ($verifica == 0) {
//            $ambulatorio_laudooit_id = $this->laudooit->inserirlaudo($ambulatorio_laudo_id);
//            $obj_laudo = new laudooit_model($ambulatorio_laudooit_id);
//        } 
        } else {
            echo '<script>window.close();</script>';
        }
    }

    function impressaooit($ambulatorio_laudo_id) {
        $verifica = $this->laudooit->contadorlaudo($ambulatorio_laudo_id);
        if ($verifica == 0) {
            $ambulatorio_laudooit_id = $this->laudooit->inserirlaudo($ambulatorio_laudo_id);
            $obj_laudo = new laudooit_model($ambulatorio_laudooit_id);
        } else {
            $data['obj'] = $this->laudooit->consultalaudooit($ambulatorio_laudo_id);
        }
          $this->load->View('ambulatorio/impressaooit-novo', $data);
    }

    function gravaroit() {
        // echo '<pre>';
        // print_r($_POST);
        // die;
        $this->laudo->gravaroit();
        $ambulatorio_laudo_id = $_POST['laudo_id'];
        $mensagem = 'Sucesso ao gravar OIT';
        $data['exame_id'] = $exame_id;
        $this->session->set_flashdata('message', $data['mensagem']);

        if($_POST['Enviar'] == 'EnviarImp'){
        redirect(base_url() . "ambulatorio/laudo/impressaooit/$ambulatorio_laudo_id");
        }else{
        redirect(base_url() . "ambulatorio/laudo/oit/$ambulatorio_laudo_id");
        }
    }

    function gravarhistorico($paciente_id) {

        $this->laudo->gravarhistorico($paciente_id);
        $mensagem = 'Sucesso ao gravar historico';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "emergencia/filaacolhimento/novo/$paciente_id");
    }

    function excluir($exame_sala_id) {
        if ($this->procedimento->excluir($exame_sala_id)) {
            $mensagem = 'Sucesso ao excluir a Sala';
        } else {
            $mensagem = 'Erro ao excluir a sala. Opera&ccedil;&atilde;o cancelada.';
        }

        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/sala");
    }

    function gravar($paciente_id) {
        $ambulatorio_laudo_id = $this->laudo->gravar($paciente_id);
        $data['paciente_id'] = $paciente_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['procedimento'] = $this->procedimento->listarprocedimentos();
        $this->novo($data);
    }

    function multifuncaomedicointegracao() {
        set_time_limit(7200); // Limite de tempo de execução: 2h. Deixe 0 (zero) para sem limite
        ignore_user_abort(true); // Não encerra o processamento em caso de perda de conexão 

        $data['integracao'] = $this->laudo->listarlaudosintegracaotodos();
        if (count($data['integracao']) > 0) {
            $laudos = $this->laudo->atualizacaolaudosintegracaotodos();

            foreach ($laudos as $item) {
                $dados = $this->laudo->listardadoslaudogravarxml($item);

                $this->gerarxmlsalvar($dados[0]->ambulatorio_laudo_id, $dados[0]->exame_id, $dados[0]->sala_id);
                sleep(2);
            }
        }
    }

    function listalaudoantigos(){

        $lista = $this->laudo->listalaudosantigos();

        foreach($lista as $item){
            echo $item->ambulatorio_laudo_id;
            echo '<br>';
            $this->impressaolaudosalvar($item->ambulatorio_laudo_id, $item->exame_id, $item->paciente_id);
            $this->impressaolaudoimagemsalvar($item->ambulatorio_laudo_id, $item->exame_id, $item->paciente_id);
        }
        echo 'finalizado';

    }

    function gravarlaudo($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $sala_id, $salvacomopdf = 0) {
        if(@$_POST['opcoes'] != ''){

            if(@$_POST['nivel2_campolivre'] != ''){
                $_POST['nivel2'] = $_POST['nivel2_campolivre'];
            }

            if($_POST['nivel2'] == ''){
                $_POST['nivel2'] = '';
            }

            if(@$_POST['nivel3_campolivre'] != ''){
                $_POST['nivel3'] = $_POST['nivel3_campolivre'];
            }
            
            if($_POST['nivel3'] == ''){
                $_POST['nivel3'] = '';
            }
        }
        
        if ($_POST['situacao'] == 'FINALIZADO') {
            $validar = $this->laudo->validar();
            $empresa_id = $this->session->userdata('empresa_id');
            $empresapermissao = $this->guia->listarempresasaladepermissao($empresa_id);

            if ($validar == '1' || $empresapermissao[0]->senha_finalizar_laudo == 'f' || $_POST['adendo'] != '') {
                $gravar = $this->laudo->gravarlaudo($ambulatorio_laudo_id, $exame_id, $sala_id, $procedimento_tuss_id);
                if ($gravar == 0) {
                    $this->gerarxmlsalvar($ambulatorio_laudo_id, $exame_id, $sala_id);
                }
                $messagem = 2;

                // $email_paciente = $this->laudo->email($paciente_id);
                // if ((isset($email_paciente)) && $email_paciente !== "") {
                //     $this->email($_POST['laudo'], $email_paciente);
                // }
            } else {
                $this->laudo->gravarlaudodigitando($ambulatorio_laudo_id, $exame_id, $procedimento_tuss_id);
                $messagem = 1;
            }
        } else {
//            die('rmekrl');
            $this->laudo->gravarlaudodigitando($ambulatorio_laudo_id, $exame_id, $procedimento_tuss_id);
        }

        if($salvacomopdf  > 0){
            $this->impressaolaudosalvar($ambulatorio_laudo_id, $exame_id, $paciente_id);
            $this->impressaolaudoimagemsalvar($ambulatorio_laudo_id, $exame_id, $paciente_id);
            if($_POST['situacao'] == 'FINALIZADO'){
                $email_paciente = $this->laudo->email($paciente_id);
                if ((isset($email_paciente)) && $email_paciente !== "") {

                    $this->emailcomarquivo($ambulatorio_laudo_id, $exame_id, $email_paciente);
                }
            }

        }
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        if(isset($_POST['btnFinalizar'])){
            redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
        }else{
            redirect(base_url() . "ambulatorio/laudo/carregarlaudo/$ambulatorio_laudo_id/$exame_id/$paciente_id/$procedimento_tuss_id/$messagem");
        }
    }

    function gravarformulario($ambulatorio_laudo_id) {

        $this->laudo->gravarformulario($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['mensagem'] = 'Formulário gravado com sucesso';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarformulario/$ambulatorio_laudo_id");
    }

    function gravarsolicitarparecer($ambulatorio_laudo_id) {

        $centrocicurgico_parecer_id = $this->laudo->gravarsolicitarparecer($ambulatorio_laudo_id);
        $subrotina_id = $_POST['sub_rotina'];
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $paciente_id = $_POST['paciente_id'];
        $data['paciente_id'] = $paciente_id;
        $data['mensagem'] = 'Solicitado com sucesso';


        if ($subrotina_id > 0) {

            $tela = $this->laudo->especialidadeparecersubrotinatela($subrotina_id);

            // var_dump($tela); die;
            if ($tela == 'parecercirurgia') {
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "ambulatorio/laudo/preencherparecer/$ambulatorio_laudo_id");
            } elseif ($tela == 'laudoapendicite') {
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "ambulatorio/laudo/preencherlaudoapendicite/$ambulatorio_laudo_id");
            } else {
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "ambulatorio/laudo/solicitarparecer/$paciente_id/$ambulatorio_laudo_id");
            }
        } else {
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/laudo/solicitarparecer/$paciente_id/$ambulatorio_laudo_id");
        }
    }

    function gravarcirurgia($ambulatorio_laudo_id) {

        $this->laudo->gravarcirurgia($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['mensagem'] = 'Gravado com sucesso!';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarcirurgia/$ambulatorio_laudo_id");
    }

    function gravarexameslab($ambulatorio_laudo_id) {

        $this->laudo->gravarexameslab($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['mensagem'] = 'Gravado com sucesso!';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarexameslab/$ambulatorio_laudo_id");
    }

    function gravarecocardio($ambulatorio_laudo_id) {

        $this->laudo->gravarecocardio($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['mensagem'] = 'Gravado com sucesso!';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarecocardio/$ambulatorio_laudo_id");
    }

    function gravarecostress($ambulatorio_laudo_id) {

        $this->laudo->gravarecostress($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['mensagem'] = 'Gravado com sucesso!';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarecostress/$ambulatorio_laudo_id");
    }

    function gravarcate($ambulatorio_laudo_id) {

        $this->laudo->gravarcate($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['mensagem'] = 'Gravado com sucesso!';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarcate/$ambulatorio_laudo_id");
    }

    function gravarholter($ambulatorio_laudo_id) {

        $this->laudo->gravarholter($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['mensagem'] = 'Gravado com sucesso!';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarholter/$ambulatorio_laudo_id");
    }

    function gravarcintilografia($ambulatorio_laudo_id) {

        $this->laudo->gravarcintilografia($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['mensagem'] = 'Gravado com sucesso!';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarcintilografia/$ambulatorio_laudo_id");
    }

    function gravarmapa($ambulatorio_laudo_id) {

        $this->laudo->gravarmapa($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['mensagem'] = 'Gravado com sucesso!';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarmapa/$ambulatorio_laudo_id");
    }

    function gravartergometrico($ambulatorio_laudo_id) {

        $this->laudo->gravartergometrico($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['mensagem'] = 'Gravado com sucesso!';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregartergometrico/$ambulatorio_laudo_id");
    }

    function gravarparecer($ambulatorio_laudo_id) {

        $this->laudo->gravarparecer($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['mensagem'] = 'Parecer gravado com sucesso';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarparecer/$ambulatorio_laudo_id");
    }

    function gravarlaudoapendicite($ambulatorio_laudo_id) {

        $this->laudo->gravarlaudoapendicite($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['mensagem'] = 'Laudo Apendicite gravado com sucesso';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/preencherlaudoapendicite/$ambulatorio_laudo_id");
    }

    function gravaravaliacao($ambulatorio_laudo_id) {
        $this->laudo->gravaravaliacao($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['mensagem'] = 'Avaliação gravada com sucesso';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregaravaliacao/$ambulatorio_laudo_id");
    }

    function gravarlaudolaboratorial($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id) {

        if ($_POST['situacao'] == 'FINALIZADO') {
            $validar = $this->laudo->validar();
            if ($validar == '1') {
                $this->laudo->gravarlaudolaboratorial($ambulatorio_laudo_id, $exame_id);
                $messagem = 2;
            } else {
                $this->laudo->gravarlaudodigitandolaboratorial($ambulatorio_laudo_id, $exame_id);
                $messagem = 1;
            }
        } else {
            $this->laudo->gravarlaudodigitandolaboratorial($ambulatorio_laudo_id, $exame_id);
        }
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;


        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarlaudolaboratorial/$ambulatorio_laudo_id/$exame_id/$paciente_id/$procedimento_tuss_id/$messagem");
    }

    function gravarlaudoeco($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id) {

        if ($_POST['situacao'] == 'FINALIZADO') {
            $validar = $this->laudo->validar();
            if ($validar == '1') {
                $this->laudo->gravarlaudoeco($ambulatorio_laudo_id);
                $messagem = 2;
            } else {
                $this->laudo->gravarlaudodigitandoeco($ambulatorio_laudo_id);
                $messagem = 1;
            }
        } else {
            $this->laudo->gravarlaudodigitandoeco($ambulatorio_laudo_id);
        }
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarlaudoeco/$ambulatorio_laudo_id/$exame_id/$paciente_id/$procedimento_tuss_id/$messagem");
    }

    function gravaranamineseodontologia($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id) {

        $this->laudo->gravaranamineseodontologia($ambulatorio_laudo_id, $exame_id, $procedimento_tuss_id);

        $servicoemail = $this->session->userdata('servicoemail');
        if ($servicoemail == 't') {

            $dados = $this->laudo->listardadoservicoemail($ambulatorio_laudo_id, $exame_id);
            if ($dados['enviado'] != 't') {
                $this->load->library('My_phpmailer');
                $mail = new PHPMailer(true);

                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'ssl://smtp.gmail.com';
                $config['smtp_port'] = '465';
                $config['smtp_user'] = 'stgsaude@gmail.com';
                $config['smtp_pass'] = 'saude123';
                $config['validate'] = TRUE;
                $config['mailtype'] = 'html';
                $config['charset'] = 'utf-8';
                $config['newline'] = "\r\n";

                $mail->setLanguage('br');                             // Habilita as saídas de erro em Português
                $mail->CharSet = 'UTF-8';                             // Habilita o envio do email como 'UTF-8'
                $mail->SMTPDebug = 3;                               // Habilita a saída do tipo "verbose"
                $mail->isSMTP();                                      // Configura o disparo como SMTP
                $mail->Host = 'smtp.gmail.com';                       // Especifica o enderço do servidor SMTP da Locaweb
                $mail->SMTPAuth = true;                               // Habilita a autenticação SMTP
                $mail->Username = 'stgsaude@gmail.com';                    // Usuário do SMTP
                $mail->Password = 'saude123';                   // Senha do SMTP
                $mail->SMTPSecure = 'ssl';                            // Habilita criptografia TLS | 'ssl' também é possível
                $mail->Port = 465;                                    // Porta TCP para a conexão
                $mail->From = $dados['empresaEmail'];             // Endereço previamente verificado no painel do SMTP
                $mail->FromName = $dados['razaoSocial'];                        // Nome no remetente
                $mail->addAddress($dados['pacienteEmail']);                            // Acrescente um destinatário
                $mail->isHTML(true);                                  // Configura o formato do email como HTML
                $mail->Subject = $dados['razaoSocial'] . " agradece sua presença.";
                $mail->Body = $dados['mensagem'];

                //                $mail->AddAttachment("./upload/nfe/$solicitacao_cliente_id/validada/" . $notafiscal[0]->chave_nfe . '-danfe.pdf', $notafiscal[0]->chave_nfe . '-danfe.pdf');

                if (!$mail->Send()) {
                    $mensagem = "Erro: " . $mail->ErrorInfo;
                } else {
                    $mensagem = "Email enviado com sucesso!";
                }

                $this->laudo->setaemailparaenviado($ambulatorio_laudo_id);
            }
        }

        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function tirarStringTinymce($string) {

        $string = str_replace("<head>", '', $string);
        $string = str_replace("</head>", '', $string);
        $string = str_replace("<html>", '', $string);
        $string = str_replace("<body>", '', $string);
        $string = str_replace("</html>", '', $string);
        $string = str_replace("</body>", '', $string);
        $string = str_replace("<!DOCTYPE html>", '', $string);
        $string = str_replace(" ", '', $string);
        return $string;
    }


    function importararrastaresoltar($ambulatorio_laudo_id, $paciente_id){
        if (!is_dir("./upload/consulta")) {
            mkdir("./upload/consulta");
            $destino = "./upload/consulta";
            chmod($destino, 0777);
        }

        if (!is_dir("./upload/consulta/$ambulatorio_laudo_id")) {
            mkdir("./upload/consulta/$ambulatorio_laudo_id");
            $destino = "./upload/consulta/$ambulatorio_laudo_id";
            chmod($destino, 0777);
        }
        $file = $_FILES['file'];

        $ret = [];

        if(move_uploaded_file($file['tmp_name'],'./upload/consulta/'.$ambulatorio_laudo_id.'/'. $file['name'])){
            $ret["status"] = "success";
            $ret["path"] = base_url().'upload/consulta/'.$ambulatorio_laudo_id.'/'. $file['name'];
            $ret["name"] = $file['name'];
        }else{
            $ret["status"] = "error";
            $ret["name"] = $file['name'];
        }

        if($ret["status"] == "success"){
            $this->laudo->gravaranexoarquivo($ambulatorio_laudo_id, $paciente_id, 'upload/consulta/'.$ambulatorio_laudo_id.'/'. $file['name'], $ret["name"]);
        }

        echo json_encode($ret, JSON_PRETTY_PRINT);
    }

    function importarimagematendimento($ambulatorio_laudo_id) {

        if (!is_dir("./upload/consulta")) {
            mkdir("./upload/consulta");
            $destino = "./upload/consulta";
            chmod($destino, 0777);
        }
        // var_dump($_FILES['arquivos']);
        // die;
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
        }
//        var_dump($error); die;


        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        return $error;
    }

    function gravaranaminese($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $adendo = false) {

        // echo '<pre>';
        // print_r($_POST);
        // die;

        if(isset($_POST['btnAtivarprecriscao'])){
            if (!is_dir("./upload/novoatendimento")) {
                mkdir("./upload/novoatendimento");
                $destino = "./upload/novoatendimento";
                chmod($destino, 0777);
            }

            if (!is_dir("./upload/novoatendimento/$ambulatorio_laudo_id")) {
                mkdir("./upload/novoatendimento/$ambulatorio_laudo_id");
                $destino = "./upload/novoatendimento/$ambulatorio_laudo_id";
                chmod($destino, 0777);
            }

            array_map('unlink', glob("./upload/novoatendimento/$ambulatorio_laudo_id/*"));

            $this->laudo->apagarimpressoeslaudo($ambulatorio_laudo_id);

            if(isset($_POST['receita_imprimir'])){

               $this->laudo->gravarimpressoes($_POST['receita_imprimir'], 'RECEITAS', $ambulatorio_laudo_id, $adendo);
                 foreach($_POST['receita_imprimir'] as $imprimir){
                     $especial = $this->laudo->verificarreceitaespecial($imprimir);
                     if($especial == 't'){
                        $this->impressaoreceitaespecialsalvar($imprimir, 'true', $ambulatorio_laudo_id, @$_POST['sem_data_r_especial']);
                        $meses = $_POST['receita_imprimir_por_mes_'.$imprimir];
                            if($meses != 0 && $meses != 1){
                                for($i = 2; $i <= $meses; $i++){
                                    $a = $i - 1;
                                    $receita_id_novo = $this->laudo->repetirreceituariopormes_2($imprimir, $a);
                                    $this->impressaoreceitaespecialsalvar($receita_id_novo, 'true', $ambulatorio_laudo_id, @$_POST['sem_data_r_especial']);
                                    $this->laudo->marcarnovareceita($ambulatorio_laudo_id, 'RECEITAS', $receita_id_novo);
                                }
                            }
                     }else{
                         $this->impressaoreceitasalvar($imprimir, $ambulatorio_laudo_id, @$_POST['sem_data_r']);
                         $meses = $_POST['receita_imprimir_por_mes_'.$imprimir];
                            if($meses != 0){
                                for($i = 2; $i <= $meses; $i++){
                                    $a = $i - 1;
                                    $receita_id_novo = $this->laudo->repetirreceituariopormes_2($imprimir, $a);
                                    $this->impressaoreceitasalvar($receita_id_novo, $ambulatorio_laudo_id, @$_POST['sem_data_r']);
                                    $this->laudo->marcarnovareceita($ambulatorio_laudo_id, 'RECEITAS', $receita_id_novo);
                                }
                            }
                     }
                 }
            }

            if(isset($_POST['s_exames_imprimir'])){
                $this->laudo->gravarimpressoes($_POST['s_exames_imprimir'], 'EXAMES', $ambulatorio_laudo_id, $adendo);
                
                foreach($_POST['s_exames_imprimir'] as $imprimir){
                       $this->impressaosolicitarexamesalvar($imprimir, $ambulatorio_laudo_id, @$_POST['sem_data_e']);
                }
            }

            if(isset($_POST['terapeuticas_imprimir'])){
                $this->laudo->gravarimpressoes($_POST['terapeuticas_imprimir'], 'TERAPEUTICAS', $ambulatorio_laudo_id, $adendo);
                
                foreach($_POST['terapeuticas_imprimir'] as $imprimir){
                    $this->impressaoteraupeticasalvar($imprimir, $ambulatorio_laudo_id, @$_POST['sem_data_e']);
                }
            }

            if(isset($_POST['relatorio_imprimir'])){
                $this->laudo->gravarimpressoes($_POST['relatorio_imprimir'], 'RELATORIOS', $ambulatorio_laudo_id, $adendo);
                
                foreach($_POST['relatorio_imprimir'] as $imprimir){
                    $this->impressaorelatoriosalvar($imprimir, $ambulatorio_laudo_id, @$_POST['sem_data_e']);
                }
            }

            if(isset($_POST['receita_antiga_imprimir'])){
                 $arrayreceita = [];
                foreach($_POST['receita_antiga_imprimir'] as $imprimir){

                     $receita_nova = $this->laudo->replicarreceitaatual($imprimir);
                     $id_receita = $this->laudo->gravarreceituarioatendimentoantigo($ambulatorio_laudo_id, $receita_nova, $adendo);


                     if($receita_nova[0]->especial == 't'){
                        $this->impressaoreceitaespecialsalvar($id_receita, 'true', $ambulatorio_laudo_id);
                     }else{
                         $this->impressaoreceitasalvar($id_receita, $ambulatorio_laudo_id);
                     }
                     $arrayreceita[] = $id_receita;
                 }
                 $this->laudo->gravarimpressoes_antigo($arrayreceita, 'RECEITAS', $ambulatorio_laudo_id, $adendo);
                //  die('morreu');
            }

            if(isset($_POST['s_exames_antiga_imprimir'])){
                $arrayreceita = [];
                foreach($_POST['s_exames_antiga_imprimir'] as $imprimir){
                        $exames_nova = $this->laudo->replicarexameatual($imprimir);
                        $id_exames = $this->laudo->gravarexamesatendimentoantigo($ambulatorio_laudo_id, $exames_nova, $adendo);

                       $this->impressaosolicitarexamesalvar($id_exames, $ambulatorio_laudo_id);
                    $arrayreceita[] = $id_exames;
                }

                $this->laudo->gravarimpressoes_antigo($arrayreceita, 'EXAMES', $ambulatorio_laudo_id, $adendo);
            }

            if(isset($_POST['terapeuticas_antiga_imprimir'])){
                $arrayreceita = [];
                foreach($_POST['terapeuticas_antiga_imprimir'] as $imprimir){
                    $terapeuticas_nova = $this->laudo->replicarterapeuticaatual($imprimir);
                    $id_terapeuticas = $this->laudo->gravarterapeuticasatendimentoantigo($ambulatorio_laudo_id, $terapeuticas_nova, $adendo);

                    $this->impressaoteraupeticasalvar($id_terapeuticas, $ambulatorio_laudo_id);

                    $arrayreceita[] = $id_terapeuticas;
                }

                $this->laudo->gravarimpressoes_antigo($arrayreceita, 'TERAPEUTICAS', $ambulatorio_laudo_id, $adendo);
            }

            if(isset($_POST['relatorio_antiga_imprimir'])){
                $arrayreceita = [];
                
                foreach($_POST['relatorio_antiga_imprimir'] as $imprimir){
                    $relatorios_nova = $this->laudo->replicarrelatorioatual($imprimir);
                    $id_relatorio = $this->laudo->gravarrelatorioatendimentoantigo($ambulatorio_laudo_id, $relatorios_nova, $adendo);

                    $this->impressaorelatoriosalvar($id_relatorio, $ambulatorio_laudo_id);
                    $arrayreceita[] = $id_relatorio;
                }

                $this->laudo->gravarimpressoes_antigo($arrayreceita, 'RELATORIOS', $ambulatorio_laudo_id, $adendo);
            }
        }
        
        $evolucao = '';
        // var_dump($_FILES['arquivos']);
        // die;
        if(isset($_FILES['arquivos']['name'][0]) && $_FILES['arquivos']['name'][0] != ''){
            $this->importarimagematendimento($ambulatorio_laudo_id);
        }
        if(isset($_POST['Peso'])){
            $evolucao .= 'Peso - '.$_POST['Peso'].'Kg/ ';
        }
        if(isset($_POST['Altura'])){
            $evolucao .= 'Altura - '.$_POST['Altura'].'cm/ ';
        }
        if(isset($_POST['imc'])){
            $evolucao .= 'IMC - '.$_POST['imc'].'/ ';
        }
        if(isset($_POST['medicacao'])){
            $evolucao .= 'Medicacao - '.$_POST['medicacao'].'/ ';
        }
        if(isset($_POST['pulso'])){
            $evolucao .= 'Pulso - '.$_POST['pulso'].'Bpm/ ';
        }
        if(isset($_POST['temperatura'])){
            $evolucao .= 'Temperatura - '.$_POST['temperatura'].'ºC/ ';
        }
        if(isset($_POST['pressao_arterial'])){
            $evolucao .= 'Pressao Arterial - '.$_POST['pressao_arterial'].'mm_Hg/ ';
        }
        if(isset($_POST['f_respiratoria'])){
            $evolucao .= 'F.respiratoria - '.$_POST['f_respiratoria'].'Rpm/ ';
        }
        if(isset($_POST['spo2'])){
            $evolucao .= 'SPO2 - '.$_POST['spo2'].'% ';
        }

        // São no minimo 15 caracteres pela quantidade que o Tinymce coloca por si só.
        // echo '<pre>';
        // var_dump($_POST); 
        // die;

        // Receitas HotKey = '#';
        // Receitas Especial HotKey = '&';
        // Solicitar Exames HotKey = '@';
        // Terapeutica HotKey = '$';
        // Relatorio HotKey = '%';
        // Procolos HotKey = '!';

        // O SISTEMA APENAS SALVARÁ A HOTKEY QUANDO CLICAR NO BOTÃO "SALVAR" OU "LIBERAR"
        if(isset($_POST['btnSalvar']) || isset($_POST['btnFinalizar'])){


        $verificar_receita = strpos($_POST['laudo'], '*');

        if ($verificar_receita === false) {

        }else{
            $modeloreceita = $this->exametemp->listarautocompletemodelosreceita();

            foreach($modeloreceita as $item){
                $salvar_receita = strpos($_POST['laudo'], '*'.$item->nome.'*');
                if ($salvar_receita === false) {

                }else{
                    $this->laudo->gravarreceituarioatendimento($ambulatorio_laudo_id, $item->texto, NULL, $item->ambulatorio_modelo_receita_id, $adendo);
                     $_POST['laudo'] = str_replace('*'.$item->nome.'*', $item->nome, $_POST['laudo']);
                }
            }
        }

        $verificar_receita_especial = strpos($_POST['laudo'], '#');

        if ($verificar_receita_especial === false) {

        }else{
            $modeloreceitaespecial = $this->exametemp->listarautocompletemodelosreceitaespecial();
            foreach($modeloreceitaespecial as $especial){
                $salvar_receita_especial = strpos($_POST['laudo'], '#'.$especial->nome.'#');
                if ($salvar_receita_especial === false) {

                }else{
                     $this->laudo->gravarreceituarioatendimento($ambulatorio_laudo_id, $especial->texto, 'TRUE', $especial->ambulatorio_modelo_receita_especial_id, $adendo);
                     $_POST['laudo'] = str_replace('#'.$especial->nome.'#', $especial->nome, $_POST['laudo']);
                }
            }
        }


        $verificar_s_exames = strpos($_POST['laudo'], '@');

        if ($verificar_s_exames === false) {

        }else{
            $modelo_s_exames = $this->exametemp->listarautocompletemodelossolicitarexames();       
 
            foreach($modelo_s_exames as $exames){
                $salvar_s_exames = strpos($_POST['laudo'], '@'.$exames->nome.'@');
                if ($salvar_s_exames === false) {

                }else{
                    $this->laudo->gravarexameatendimento($ambulatorio_laudo_id, $exames->texto, $exames->ambulatorio_modelo_solicitar_exames_id, $adendo);
                     $_POST['laudo'] = str_replace('@'.$exames->nome.'@', $exames->nome, $_POST['laudo']);
                }
            }
        }


        $verificar_terapeuticas = strpos($_POST['laudo'], '$');

        if ($verificar_terapeuticas === false) {

        }else{
            $modelo_terapeuticas = $this->exametemp->listarmodelosterapeuticas();     
 
            foreach($modelo_terapeuticas as $terapeutica){
                $salvar_terapeuticas = strpos($_POST['laudo'], '$'.$terapeutica->nome.'$');
                if ($salvar_terapeuticas === false) {

                }else{
                    $this->laudo->gravarterapeuticasatendimento($ambulatorio_laudo_id, $terapeutica->texto, $terapeutica->ambulatorio_modelo_terapeuticas_id, $adendo);
                     $_POST['laudo'] = str_replace('$'.$terapeutica->nome.'$', $terapeutica->nome, $_POST['laudo']);
                }
            }
        }


        $verificar_relatorio = strpos($_POST['laudo'], '%');

        if ($verificar_relatorio === false) {

        }else{
            $modelo_relatorio = $this->exametemp->listarmodelosrelatorio(); 
     
 
            foreach($modelo_relatorio as $relatorio){
                $salvar_relatorio = strpos($_POST['laudo'], '%'.$relatorio->nome.'%');
                if ($salvar_relatorio === false) {

                }else{
                    $this->laudo->gravarrelatorioatendimento($ambulatorio_laudo_id, $relatorio->texto, $relatorio->ambulatorio_modelo_relatorio_id, $adendo);
                     $_POST['laudo'] = str_replace('%'.$relatorio->nome.'%', $relatorio->nome, $_POST['laudo']);
                }
            }
        }

        $verificar_protocolo = strpos($_POST['laudo'], '!');

        if ($verificar_protocolo === false) {

        }else{
            $modelo_protocolo = $this->exametemp->listarmodelosprotocolos(); 
     
            foreach($modelo_protocolo as $protocolo){
                $salvar_protocolo = strpos($_POST['laudo'], '!'.$protocolo->nome.'!');

                if ($salvar_protocolo === false) {

                }else{
                    $v_receita = strpos($protocolo->texto, '*');
                    $v_receitaespecial = strpos($protocolo->texto, '#');
                    $v_exames = strpos($protocolo->texto, '@');
                    $v_terapeutica = strpos($protocolo->texto, '$');
                    $v_relatorio = strpos($protocolo->texto, '%');

                    if($v_receita === false){

                    }else{
                        $modeloreceita = $this->exametemp->listarautocompletemodelosreceita();

                        foreach($modeloreceita as $item){
                            $salvar_receita = strpos($protocolo->texto, '*'.$item->nome.'*');
                            if ($salvar_receita === false) {
            
                            }else{
                                $this->laudo->gravarreceituarioatendimento($ambulatorio_laudo_id, $item->texto, NULL, $item->ambulatorio_modelo_receita_id, $adendo);
                            }
                        }
                    } // FIM RECEITAS

                    if ($v_receitaespecial === false) {

                    }else{
                        $modeloreceitaespecial = $this->exametemp->listarautocompletemodelosreceitaespecial();
            
                        foreach($modeloreceitaespecial as $item){
                            $salvar_receita = strpos($protocolo->texto, '#'.$item->nome.'#');
                            if ($salvar_receita === false) {
            
                            }else{
                                $this->laudo->gravarreceituarioatendimento($ambulatorio_laudo_id, $item->texto, 'TRUE', $item->ambulatorio_modelo_receita_especial_id, $adendo);
                            }
                        }
                    }

                    if($v_exames === false){

                    }else{
                        $modelo_s_exames = $this->exametemp->listarautocompletemodelossolicitarexames();       
 
                        foreach($modelo_s_exames as $exames){
                            $salvar_s_exames = strpos($protocolo->texto, '@'.$exames->nome.'@');
                            if ($salvar_s_exames === false) {
            
                            }else{
                                $this->laudo->gravarexameatendimento($ambulatorio_laudo_id, $exames->texto, $exames->ambulatorio_modelo_solicitar_exames_id, $adendo);
                            }
                        }
                    } // FIM SOLICITAR EXAMES

                    if($v_terapeutica === false){

                    }else{
                        $modelo_terapeuticas = $this->exametemp->listarmodelosterapeuticas();     
 
                        foreach($modelo_terapeuticas as $terapeutica){
                            $salvar_terapeuticas = strpos($protocolo->texto, '$'.$terapeutica->nome.'$');
                            if ($salvar_terapeuticas === false) {
            
                            }else{
                                $this->laudo->gravarterapeuticasatendimento($ambulatorio_laudo_id, $terapeutica->texto, $terapeutica->ambulatorio_modelo_terapeuticas_id, $adendo);
                            }
                        }
                    } // FIM TERAPEUTICA

                    if($v_relatorio === false){

                    }else{
                        $modelo_relatorio = $this->exametemp->listarmodelosrelatorio(); 
     
 
                        foreach($modelo_relatorio as $relatorio){
                            $salvar_relatorio = strpos($protocolo->texto, '%'.$relatorio->nome.'%');
                            if ($salvar_relatorio === false) {
            
                            }else{
                                $this->laudo->gravarrelatorioatendimento($ambulatorio_laudo_id, $relatorio->texto, $relatorio->ambulatorio_modelo_relatorio_id, $adendo);
                            }
                        }
                    } // FIM RELATORIO

                }
                 $_POST['laudo'] = str_replace('!'.$protocolo->nome.'!', $protocolo->nome, $_POST['laudo']);
            } // FIM PROTOCOLOS
        }
    }


    if(isset($_POST['btnmedicamentostomadas'])){
        $tomadas = $this->laudo->buscartomadaslancadas($ambulatorio_laudo_id);
        $medicamento = '';
        foreach($tomadas as $simples){
            $total_mes = $simples->qtd * 30;
            $medicamento .= $simples->medicamento.' ---------------------- '.$total_mes.' cp <br><br>';
        }


        if($medicamento != ''){
            $medicamento .= "Tomar os medicamentos conforme escrito na folha de Tomadas. <br><br>";
            $this->laudo->gravarreceituarioatendimento($ambulatorio_laudo_id, $medicamento, NULL, -10);
        }

        $tomadas_especial = $this->laudo->buscartomadaslancadasespecial($ambulatorio_laudo_id);
        $medicamento_especial = '';
        if(count($tomadas_especial) > 0 ){
            // $medicamento_especial .= 'Uso oral <br>';
        }

        foreach($tomadas_especial as $especial){
            $total_mes = $especial->qtd * 30;
            $medicamento_especial = '';
            $medicamento_especial .= 'Uso oral <br>';
            $medicamento_especial .= '1) '.$especial->medicamento.' ---------------------- '.ceil($total_mes).' cp <br> Tomar '. ceil($especial->qtd).' cp por dia. Uso contínuo <br><br><br>';
            $medicamento_especial .= "Tomar os medicamentos conforme escrito na folha de Tomadas. <br><br>";

            $this->laudo->gravarreceituarioatendimento($ambulatorio_laudo_id, $medicamento_especial, 'TRUE', -10);
        }

        // if($medicamento_especial != ''){
        //     $this->laudo->gravarreceituarioatendimento($ambulatorio_laudo_id, $medicamento_especial, 'TRUE');
        // }

        if($medicamento != '' || $medicamento_especial != ''){
            $this->laudo->tomadasnaorepetir($ambulatorio_laudo_id);
        }
    }


        if (isset($_POST['receituario']) && strlen($this->tirarStringTinymce($_POST['receituario'])) > 14) {
            $this->laudo->gravarreceituarioatendimento($ambulatorio_laudo_id);
            // die;
        } 
        
        if (isset($_POST['receituario_simples']) && strlen($this->tirarStringTinymce($_POST['receituario_simples'])) > 14) { 
            $this->laudo->gravarreceituarioatendimentosimples($ambulatorio_laudo_id); 
        }
        
        
        if (isset($_POST['receituario_especial']) && strlen($this->tirarStringTinymce($_POST['receituario_especial'])) > 14) {  
            $this->laudo->gravarreceituarioatendimentoespecial($ambulatorio_laudo_id);  
        }
        
        if (isset($_POST['anotacao_privada']) && strlen($this->tirarStringTinymce($_POST['anotacao_privada'])) > 14) {
            $this->laudo->gravaranotacaoprivada($ambulatorio_laudo_id);
            // die;
        }

        if (isset($_POST['solicitar_exames']) && strlen($this->tirarStringTinymce($_POST['solicitar_exames'])) > 14) {
            $this->laudo->gravarexameatendimento($ambulatorio_laudo_id);
            // die;
        }
        
        
        if (isset($_POST['receita']) && strlen($this->tirarStringTinymce($_POST['receita'])) > 14) { 
                                
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
                                
        }
                                
        if($adendo){ 
            $this->laudo->gravaranaminese_adendo($ambulatorio_laudo_id, $exame_id, $procedimento_tuss_id, $evolucao);
        }else{                  
            $this->laudo->gravaranaminese($ambulatorio_laudo_id, $exame_id, $procedimento_tuss_id, $evolucao);
        }
                                
        
        $empresaPermissoes = $this->login->listarEmpresa();
        $servicoemail = $empresaPermissoes[0]->servicoemail;

        $servicoemail = $this->session->userdata('servicoemail');
        if ($servicoemail == 't') {

            $dados = $this->laudo->listardadoservicoemail($ambulatorio_laudo_id, $exame_id);
//            var_dump($dados); die;
            if ($dados['enviado'] != 't' && $dados['pacienteEmail'] != '' && $dados['mensagem'] != '') {
                $this->load->library('My_phpmailer');
                $mail = new PHPMailer(true);

                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'ssl://smtp.gmail.com';
                $config['smtp_port'] = '465';
                $config['smtp_user'] = 'stgsaude@gmail.com';
                $config['smtp_pass'] = 'saude123';
                $config['validate'] = TRUE;
                $config['mailtype'] = 'html';
                $config['charset'] = 'utf-8';
                $config['newline'] = "\r\n";

                $mail->setLanguage('br');                             // Habilita as saídas de erro em Português
                $mail->CharSet = 'UTF-8';                             // Habilita o envio do email como 'UTF-8'
                $mail->SMTPDebug = 3;                               // Habilita a saída do tipo "verbose"
                $mail->isSMTP();                                      // Configura o disparo como SMTP
                $mail->Host = 'smtp.gmail.com';                       // Especifica o enderço do servidor SMTP da Locaweb
                $mail->SMTPAuth = true;                               // Habilita a autenticação SMTP
                $mail->Username = 'stgsaude@gmail.com';                    // Usuário do SMTP
                $mail->Password = 'saude123';                   // Senha do SMTP
                $mail->SMTPSecure = 'ssl';                            // Habilita criptografia TLS | 'ssl' também é possível
                $mail->Port = 465;                                    // Porta TCP para a conexão
                $mail->From = $dados['empresaEmail'];             // Endereço previamente verificado no painel do SMTP
                $mail->FromName = $dados['razaoSocial'];                        // Nome no remetente
                $mail->addAddress($dados['pacienteEmail']);                            // Acrescente um destinatário
                $mail->isHTML(true);                                  // Configura o formato do email como HTML
                $mail->Subject = $dados['razaoSocial'] . " agradece sua presença.";
                $mail->Body = $dados['mensagem'];

                //                $mail->AddAttachment("./upload/nfe/$solicitacao_cliente_id/validada/" . $notafiscal[0]->chave_nfe . '-danfe.pdf', $notafiscal[0]->chave_nfe . '-danfe.pdf');

                if (!$mail->Send()) {
                    $mensagem = "Erro: " . $mail->ErrorInfo;
                } else {
                    $mensagem = "Email enviado com sucesso!";
                }

                $this->laudo->setaemailparaenviado($ambulatorio_laudo_id);
            }
        }
        $medico_inf = $this->operador_m->medicoenderecoweb($_POST['medico']);

//        echo '<pre>';
//        var_dump($medico_inf);
//        die;
        if (@$medico_inf[0]->endereco_sistema != '') {

            $endereco = @$medico_inf[0]->endereco_sistema;


            @$teste = file_get_contents("{$endereco}/autocomplete/testarconexaointegracaolaudo");
            @$decode_teste = json_decode(@$teste);
//            var_dump(@$decode_teste); die;
            if (@$decode_teste == 'true') {

                $paciente_inf = $this->laudo->listartudopaciente($paciente_id);
                $laudo_inf = $this->laudo->listarlaudointegracaoweb($ambulatorio_laudo_id);
                $paciente_json = json_encode($paciente_inf);
                $laudo_json = json_encode($laudo_inf);
//                 var_dump(@$decode_teste); die;

                $url = "{$endereco}/autocomplete/gravaratendimentointegracaoweb";
                $postdata = http_build_query(
                        array(
                            'paciente_json' => $paciente_json,
                            'laudo_json' => $laudo_json
                        )
                );
                $opts = array('http' =>
                    array(
                        'method' => 'POST',
                        'header' => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postdata
                    )
                );

                $context = stream_context_create($opts);
                $result = file_get_contents($url, false, $context);
                $retorno_result = json_decode($result);
//            var_dump($retorno_result);
//            die;
            }
        }

        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;

        $this->session->set_flashdata('message', $data['mensagem']);
        if($empresaPermissoes[0]->atendimento_medico == 'f' || isset($_POST['btnFinalizar']) || isset($_POST['btnFechar'])){
            redirect(base_url() . "ambulatorio/exame/listarmultifuncaomedicogeral");
                                
        }else{
            if($adendo){
                redirect(base_url() . "ambulatorio/laudo/carregaranaminese/$ambulatorio_laudo_id/$exame_id/$paciente_id/$procedimento_tuss_id/NULL/TRUE");
            }else{
                redirect(base_url() . "ambulatorio/laudo/carregaranaminese/$ambulatorio_laudo_id/$exame_id/$paciente_id/$procedimento_tuss_id");
            }
        }
    }


    function anexarimagem($ambulatorio_laudo_id) {

        $this->load->helper('directory');

        if (!is_dir("./upload/consulta")) {
            mkdir("./upload/consulta");
            $destino = "./upload/consulta";
            chmod($destino, 0777);
        }


        $data['arquivo_pasta'] = directory_map("./upload/consulta/$ambulatorio_laudo_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $paciente_id = $this->laudo->pegarpacienteimagem($ambulatorio_laudo_id);
        $data['paciente_id'] = $paciente_id;
        $this->loadView('ambulatorio/importacao-imagemconsulta', $data);
    }

    function importarimagem() {
        // print_r($_FILES);
        // die;
        $ambulatorio_laudo_id = $_POST['paciente_id'];
        $paciente_id = $_POST['paciente_id_real'];
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
            $config['allowed_types'] = 'gif|jpg|BMP|bmp|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar|xml|txt';
            $config['max_size'] = '0';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload', $config);
            // print_r($_FILES['userfile']['type']);
            // echo '<br>';
            // print_r($config['allowed_types']);
            // die;
            if (!$this->upload->do_upload()) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $error = null;
                $data = array('upload_data' => $this->upload->data());
            }
            if($error == null){
                $this->laudo->gravaranexoarquivo($ambulatorio_laudo_id, $paciente_id, 'upload/consulta/'.$ambulatorio_laudo_id.'/'. $_FILES['userfile']['name'], $_FILES['userfile']['name']);
            }
        }


        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->anexarimagem($ambulatorio_laudo_id);
    }

    function excluirimagem($ambulatorio_laudo_id, $nome) {
        $nome = html_entity_decode($nome);
        if (!is_dir("./uploadopm/consulta/$ambulatorio_laudo_id")) {
            if (!is_dir("./uploadopm/consulta")) {
                mkdir("./uploadopm/consulta");
            }
            mkdir("./uploadopm/consulta/$ambulatorio_laudo_id");
            $destino = "./uploadopm/consulta/$ambulatorio_laudo_id";
            chmod($destino, 0777);
        }

        $origem = "./upload/consulta/$ambulatorio_laudo_id/$nome";
        $destino = "./uploadopm/consulta/$ambulatorio_laudo_id/$nome";
        copy($origem, $destino);
        unlink($origem);
        $this->anexarimagem($ambulatorio_laudo_id);
    }

    function excluirimagemnovoatendimento($laudo_id, $nome) {

        $origem = "./upload/consulta/$laudo_id/$nome";
        $caminho = "upload/consulta/$laudo_id/$nome";
         unlink($origem);

         $this->laudo->excluiranexoarquivo($caminho);
        // echo '<script>window.location.reload(true);</script>';
         redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function excluirimagemlaudo($ambulatorio_laudo_id, $nome) {

        if (!is_dir("./uploadopm/consulta/$ambulatorio_laudo_id")) {
            if (!is_dir("./uploadopm/consulta")) {
                mkdir("./uploadopm/consulta");
            }
            mkdir("./uploadopm/consulta/$ambulatorio_laudo_id");
            $destino = "./uploadopm/consulta/$ambulatorio_laudo_id";
            chmod($destino, 0777);
        }

        $origem = "./upload/consulta/$ambulatorio_laudo_id/$nome";
        $destino = "./uploadopm/consulta/$ambulatorio_laudo_id/$nome";
        copy($origem, $destino);
        unlink($origem);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function gravarreceituario($ambulatorio_laudo_id) {
        // var_dump($_POST); die;

        $this->laudo->gravarreceituario($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarreceituario/$ambulatorio_laudo_id");
    }

    function gravarrotinas($ambulatorio_laudo_id) {

        $this->laudo->gravarrotinas($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarrotinas/$ambulatorio_laudo_id");
    }

    function gravarreceituariosollis($ambulatorio_laudo_id, $paciente_id, $prescricao_id) {

//        var_dump($paciente_id);die;
        $this->laudo->gravarreceituariosollis($ambulatorio_laudo_id, $prescricao_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/editarprescricao/$prescricao_id/$ambulatorio_laudo_id/$paciente_id");
    }

    function gravaratestado($ambulatorio_laudo_id) {

        $this->laudo->gravaratestado($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
 
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregaratestado/$ambulatorio_laudo_id");
    }

    function gravarexame($ambulatorio_laudo_id) {

        $this->laudo->gravarexame($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;


        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarexames/$ambulatorio_laudo_id");
    }

    function gravarreceituarioespecial($ambulatorio_laudo_id) {

        $this->laudo->gravarreceituarioespecial($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarreceituarioespecial/$ambulatorio_laudo_id");
    }

    function editarreceituarioespecial($ambulatorio_laudo_id) {

        $this->laudo->editarreceituarioespecial($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function editarreceituario($ambulatorio_laudo_id) {

        $this->laudo->editarreceituario($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function editarrotina($ambulatorio_laudo_id) {

        $this->laudo->editarrotina($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function repetirreceituario($ambulatorio_laudo_id) {

        $this->laudo->repetirreceituario($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function editaratestado($ambulatorio_laudo_id) {

        $this->laudo->editaratestado($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function editarexame2($ambulatorio_laudo_id) {

        $this->laudo->editarsolicitarexame($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function gravarlaudodigitador($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id) {

        if ($_POST['situacao'] == 'FINALIZADO') {
            $validar = $this->laudo->validar();
            if ($validar == '1') {
                $this->laudo->gravarlaudo($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id);
                $messagem = 2;
            } else {
                $this->laudo->gravarlaudodigitando($ambulatorio_laudo_id, $exame_id, $procedimento_tuss_id);
                $messagem = 1;
            }
        } else {
//            die;
            $this->laudo->gravarlaudodigitando($ambulatorio_laudo_id, $exame_id, $procedimento_tuss_id);
        }
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;

        $this->session->set_flashdata('message', $data['mensagem']);
        if(isset($_POST['btnFinalizar'])){
            redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
        }else{
            redirect(base_url() . "ambulatorio/laudo/carregarlaudodigitador/$ambulatorio_laudo_id/$exame_id/$paciente_id/$procedimento_tuss_id/$messagem");
        }


    }

    function gravarlaudodigitadortotal($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id) {

        if ($_POST['situacao'] == 'FINALIZADO') {
            $validar = $this->laudo->validar();
            if ($validar == '1') {
                $this->laudo->gravarlaudotodos($ambulatorio_laudo_id);
                $messagem = 2;
            } else {
                $this->laudo->gravarlaudodigitandotodos($ambulatorio_laudo_id);
                $messagem = 1;
            }
        } else {
            $this->laudo->gravarlaudodigitandotodos($ambulatorio_laudo_id);
        }
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;


        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarlaudodigitador/$ambulatorio_laudo_id/$exame_id/$paciente_id/$procedimento_tuss_id/$messagem");
    }

    function gravarrevisao($ambulatorio_laudo_id) {

        if ($_POST['situacao'] == 'FINALIZADO') {
            $validar = $this->laudo->validarrevisor();
            if ($validar == '1') {
                $this->laudo->gravarrevisao($ambulatorio_laudo_id);
                $messagem = 2;
            } else {
                $this->laudo->gravarrevisaodigitando($ambulatorio_laudo_id);
                $messagem = 1;
            }
        } else {
            $this->laudo->gravarrevisaodigitando($ambulatorio_laudo_id);
        }
        if ($ambulatorio_laudo_id == "1") {
            $data['mensagem'] = 'Sucesso ao gravar a Laudo.';
        } else {
            $data['mensagem'] = 'Erro ao gravar a Laudo. Opera&ccedil;&atilde;o cancelada.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/pesquisarrevisor", $data);
    }

    function gravarprocedimentos() {
        $agenda_exames_id = $this->laudo->gravarexames();
        if ($agenda_exames_id == "-1") {
            $data['mensagem'] = 'Erro ao agendar Exame. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao agendar Exame.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/pacientes", $data);
    }

    function novo($data) {
        $data['paciente'] = $this->paciente->listardados($data['paciente_id']);
        $data['convenio'] = $this->convenio->listardados();
        $this->loadView('ambulatorio/laudo-form', $data);
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

    function alteraremail1($paciente_id) {
        $data['paciente'] = $this->laudo->pacienteemails($paciente_id);
        $this->load->View('ambulatorio/alterapacienteemail1', $data);
    }

    function salvaralteracaoemail($paciente_id) {

        $this->laudo->salvaralteracaoemail($paciente_id);

        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function listarholter($laudo_id = NULL) {

        $data['laudo_id'] = $laudo_id;
        $data['lista'] = $this->laudo->listarholter();
        $data['exames'] = $this->laudo->listarexames();
        $this->load->View('ambulatorio/holter-lista', $data);
    }

    function listarecostress($laudo_id = NULL) {

        $data['laudo_id'] = $laudo_id;
        $data['lista'] = $this->laudo->listarecostress();
        $data['exames'] = $this->laudo->listarexamesecostress();
        $this->load->View('ambulatorio/ecostress-lista', $data);
    }

    function listarcateterismo($laudo_id = NULL) {

        $data['laudo_id'] = $laudo_id;
        $data['lista'] = $this->laudo->listarcateterismo();
        $data['exames'] = $this->laudo->listarexamescateterismo();
        $this->load->View('ambulatorio/cateterismo-lista', $data);
    }

    function listarcintil($laudo_id = NULL) {

        $data['laudo_id'] = $laudo_id;
        $data['lista'] = $this->laudo->listarcintil();
        $data['exames'] = $this->laudo->listarexamescintil();
        $this->load->View('ambulatorio/cintil-lista', $data);
    }

    function listarmapa($laudo_id = NULL) {

        $data['laudo_id'] = $laudo_id;
        $data['lista'] = $this->laudo->listarmapa();
        $data['exames'] = $this->laudo->listarexamesmapa();
        $this->load->View('ambulatorio/mapa-lista', $data);
    }

    function listarecocardiograma($laudo_id = NULL) {

        $data['laudo_id'] = $laudo_id;
        $data['lista'] = $this->laudo->listarecocardiograma();
        $data['exames'] = $this->laudo->listarexamesecocardiograma();
        $this->load->View('ambulatorio/ecocardiograma-lista', $data);
    }

    function gravarhora_agendamento($ambulatorio_laudo_id = NULL, $sala_id = NULL) {
        $medico_id = $_POST['medico_id'];

        $teste = $this->laudo->gravarhora_agendamento($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        if ($teste == -2) {
            $data['mensagem'] = 'Erro ao gravar, não há horário restante disponível no Medico Nesse dia';
        } elseif ($teste == -3) {
            $data['mensagem'] = 'Erro ao gravar, não há horário restante no Período da Manhã';
        } elseif ($teste == -4) {
            $data['mensagem'] = 'Erro ao gravar, não há horário restante no Período da Tarde';
        } elseif ($teste == -5) {
            $data['mensagem'] = 'Erro ao gravar,Turno Não selecionado!';
        } else {
            // $data['mensagem'] = 'Gravado com sucesso!';
        }

        if (isset($data['mensagem']) && $data['mensagem'] != '') {
            $this->session->set_flashdata('message', $data['mensagem']);
        }
        redirect(base_url() . "ambulatorio/laudo/preenchersalas/$ambulatorio_laudo_id/$medico_id");
    }

    function listaragendaatendimentos($sala_id = NULL) {


        $data['lista'] = $this->laudo->listaragendaatendimentos($sala_id);
//       $data['exames'] = $this->laudo->listarexames();
        $this->load->View('ambulatorio/agendeatendimentos-lista', $data);
    }

    function desativarhoraagenda($hora_agendamento_id = NULL, $sala_id = NULL) {

        $this->laudo->desativarhoraagenda($hora_agendamento_id);

        redirect(base_url() . "ambulatorio/guia/pesquisarponltrona");
    }

    function confirmarhoraagenda($hora_agendamento_id) {

        $this->laudo->confirmarhoraagenda($hora_agendamento_id);

        redirect(base_url() . "ambulatorio/guia/pesquisarponltrona");
    }

    function atualizarsala() {

        $ambulatorio_laudo_id = $_POST['ambulatorio_laudo_id'];
        $exame_id = $_POST['exame_id'];
        $paciente_id = $_POST['paciente_id'];
        @$sala_id = $_POST['sala_id'];
        $procedimento_tuss_id = $_POST['procedimento_tuss_id'];
        $verificar = $this->laudo->atualizarsala();


        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function listaragendamentosdatapoltrona($data_pol = NULL) {

        $data['data2'] = $data_pol;
        $data['teste'] = $data_pol . "eeae";
        $data['teste3'] = $_POST['data'];
        $this->load->View('ambulatorio/agendamentosdatapoltrona-lista', $data);
    }

    function preenchersalasmedico($medico_id = NULL) {
        $empresa_id = $this->session->userdata('empresa_id');
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();
        $data['empresapermissao'] = $this->guia->listarempresapermissoes(); 
        $data['salas'] = $this->exame->listarsalaspoltronas(); 
        // echo '<pre>';
        // print_r($data['salas']);
        // die;
        $data['listarmedico'] = $this->laudo->listarmedico($medico_id); 
        $data['empresa'] = $this->empresa->listarempresatoten($empresa_id); 
        $this->load->View('ambulatorio/preenchersalas-ficha2', $data);
    }

    function gravarhorapoltronamedico() { 
        $medico_id = $_POST['medico_id']; 
        $teste = $this->laudo->gravarhorapoltronamedico(); 
       
        if ($teste == -2) {
            $data['mensagem'] = 'Erro ao gravar, não há horário restante disponível no Medico Nesse dia';
        } elseif ($teste == -3) {
            $data['mensagem'] = 'Erro ao gravar, não há horário restante no Período da Manhã';
        } elseif ($teste == -4) {
            $data['mensagem'] = 'Erro ao gravar, não há horário restante no Período da Tarde';
        } elseif ($teste == -5) {
            $data['mensagem'] = 'Erro ao gravar,Turno Não selecionado!';
        } elseif ($teste == -6) {
            $data['mensagem'] = 'Erro ao gravar, Prontuario já existente!';
        } else {
//            $data['mensagem'] = 'Gravado com sucesso!';
        }


        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/preenchersalasmedico/$medico_id");
    }

    function gerarpdfaih($paciente_id) {

        $this->load->plugin('mpdf');
        $data['paciente_id'] = $paciente_id;
        $data['dadospaciente'] = $this->paciente->listardados($paciente_id);
        $html = $this->load->view('ambulatorio/impressaoaihpdf', $data, true);
        pdf($html, $filename, $cabecalho, $rodape);
    }
    
     function carregaratestadotarefa($tarefa_medico_id) {
      
        $data['lista'] = $this->exametemp->listarautocompletemodelosatestado();
        $data['obj'] = $this->exametemp->listardadostarefa($tarefa_medico_id);
        $data['receita'] = $this->laudo->listaratestadotarefa($tarefa_medico_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['tarefa_medico_id'] = $tarefa_medico_id;
    
        $this->load->View('ambulatorio/atestadoconsultatarefa-form', $data);
        
    }
    
    
    
    function gravaratestadotarefa($tarefa_medico_id) {
        $this->laudo->gravaratestadotarefa($tarefa_medico_id);
        $data['tarefa_medico_id'] = $tarefa_medico_id;
 
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregaratestadotarefa/$tarefa_medico_id");
    }
    
    
   function editarcarregaratestadotarefa($tarefa_medico_id, $tarefa_atestado_id) {
     
        $data['obj'] = $this->exametemp->listardadostarefa($tarefa_medico_id);
        $data['receita'] = $this->laudo->listareditaratestadotarefa($tarefa_atestado_id);
        $data['operadores'] = $this->operador_m->listarmedicos(); 
        $data['tarefa_atestado_id'] = $tarefa_atestado_id;
        $data['tarefa_medico_id'] = $tarefa_medico_id;
        $this->load->View('ambulatorio/editaratestadotarefa-form', $data);
        
    }
    
    
    function editaratestadotarefa($tarefa_atestado_id) {

        $this->laudo->editaratestadotarefa($tarefa_atestado_id);
        $data['tarefa_atestado_id'] = $tarefa_atestado_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }
    
    
      function impressaoatestadotarefa($tarefa_atestado_id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listaratestadoimpressaotarefa($tarefa_atestado_id);
         
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
          
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_responsavel);
         
        $data['tarefa_atestado_id'] = $tarefa_atestado_id;
     
        $data['empresa'] = $this->guia->listarempresa();
         
        $data['atestado'] = true;
        $data['imprimircid'] = $data['laudo']['0']->imprimir_cid;
        $data['co_cid'] = $data['laudo']['0']->cid1;
        $data['co_cid2'] = $data['laudo']['0']->cid2;
   
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
     
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
         
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;
 
        if (isset($data['co_cid'])) {
            $data['cid'] = $this->laudo->listarcid($data['co_cid']);
        }

        if (isset($data['co_cid'])) {
            $data['cid2'] = $this->laudo->listarcid($data['co_cid2']);
        }

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_responsavel;
        }
//        echo "<pre>";
//        print_r($data['operador_assinatura']);
//        die; 
      
        $base_url = base_url();

        $this->load->helper('directory');
        $arquivos = directory_map("./upload/operadorLOGO/");
        $arquivo_existe = false;
        foreach ($arquivos as $value) {
            if ($value == $data['laudo'][0]->medico_responsavel . ".jpg") {
                $arquivo_existe = true;
                $data['medico_responsavel'] = $data['laudo'][0]->medico_responsavel;
                break;
            }
        }
        
          
//        var_dump($data['laudo'][0]->medico_responsavel);die;

        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_responsavel)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_responsavel . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value'/>";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        
        
            
        $data['assinatura'] = $carimbo;
//        var_dump($carimbo); die;
     
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

            
        $dia = substr($data['laudo'][0]->data, 8, 2);
        $mes = substr($data['laudo'][0]->data, 5, 2);
        $ano = substr($data['laudo'][0]->data, 0, 4);

        $nomemes = $meses[$mes];

         
        $texto_rodape = @$data['empresa'][0]->municipio . ", " . $dia . " de " . $nomemes . " de " . $ano;

         
          
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

       
        if ($data['empresa'][0]->ficha_config == 't') {
             
            if ($arquivo_existe) {
                $src = base_url() . "upload/operadorLOGO/" . $data['laudo'][0]->medico_responsavel . '.jpg';
            } else {
                $src = 'img/cabecalho.jpg';
            } 
           
            $filename = "Atestado.pdf";
            if ($data['empresa'][0]->cabecalho_config == 't') {
                $cabecalho = $cabecalho_config;
            } else {
                $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='{$src}'></td></tr></table>";
            }
//            
//           echo "<pre>";
//           print_r($cabecalho);    
//           die;
            $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
            if ($data['empresa'][0]->atestado_config == 't') {
                $rodape = $texto_rodape . $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            }

            $html = $this->load->view('ambulatorio/impressaoatestadoconfiguravel', $data, true);
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

            if ($sem_margins == 't') {
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
            } else {
                pdf($html, $filename, $cabecalho, $rodape);
            }
//            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }
        
      


        if ($data['empresa'][0]->impressao_tipo == 14) {//MEDLAB
             
            if ($arquivo_existe) {
                $src = base_url() . "upload/operadorLOGO/" . $data['laudo'][0]->medico_responsavel . '.jpg';
            } else {
                $src = 'img/medlab.jpg';
            }
            $filename = "laudo.pdf";

            if ($data['empresa'][0]->cabecalho_config == 't') {
                $cabecalho = "$cabecalho_config";
            } else {
                $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='{$src}'></td></tr></table>";
            }

//        $rodape = "<img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituariomedlab', $data, true);
            pdf($html, $filename, $cabecalho);
            $this->load->View('ambulatorio/impressaoreceituariomedlab', $data);
            
        } 

//////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA   
            if ($arquivo_existe) {
                $src = base_url() . "upload/operadorLOGO/" . $data['laudo'][0]->medico_responsavel . '.jpg';
            } else {
                $src = 'img/humana.jpg';
            }
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='{$src}'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data, 8, 2) . '/' . substr($data['laudo']['0']->data, 5, 2) . '/' . substr($data['laudo']['0']->data, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
             
        }

//////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE      
            if ($arquivo_existe) {
                $src = base_url() . "upload/operadorLOGO/" . $data['laudo'][0]->medico_responsavel . '.jpg';
            } else {
                $src = 'img/cage.jpg';
            }
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='{$src}'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data, 8, 2) . '/' . substr($data['laudo']['0']->data, 5, 2) . '/' . substr($data['laudo']['0']->data, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

//////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            if ($arquivo_existe) {
                $src = base_url() . "upload/operadorLOGO/" . $data['laudo'][0]->medico_responsavel . '.jpg';
            } else {
                $src = 'img/logo2.png';
            }
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='{$src}'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data, 8, 2) . '/' . substr($data['laudo']['0']->data, 5, 2) . '/' . substr($data['laudo']['0']->data, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ    
            if ($arquivo_existe) {
                $src = base_url() . "upload/operadorLOGO/" . $data['laudo'][0]->medico_responsavel . '.jpg';
            } else {
                $src = 'img/cabecalho.jpg';
            }
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  src='{$src}'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data, 8, 2) . '/' . substr($data['laudo']['0']->data, 5, 2) . '/' . substr($data['laudo']['0']->data, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td><center><img align = 'left' src='img/rodape.jpg'></center></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                    <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                    <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                    <tr><td></td><td></td></tr>
                    <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                    <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                    <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                    <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                    </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                    <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                    <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                    <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                    <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                    <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                    <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                    </table>";
            }
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                    <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            if ($data['laudo']['0']->medico_responsavel == 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                    <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                    <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdf($html, $filename, $cabecalho, $rodape, $grupo);
        } else { //GERAL        //este item fica sempre por útimo
           
            if ($arquivo_existe) {
                $src = base_url() . "upload/operadorLOGO/" . $data['laudo'][0]->medico_responsavel . '.jpg';
            } else {
                $src = 'img/cabecalho.jpg';
            }
            $filename = "laudo.pdf";
            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='{$src}'></td></tr><tr><td><center><b>ATESTADO MÉDICO</b></center><br/><br/><br/></td></tr><tr><td><b>Para:" . $data['laudo']['0']->paciente . "<br></b></td></tr></table>";
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
            if ($sem_margins == 't') {
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
            } else {
                pdf($html, $filename, $cabecalho, $rodape);
            }
//            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }
    }

    
     function carregarreceituariotarefa($tarefa_medico_id) {
       
        $data['obj'] = $this->exametemp->listardadostarefa($tarefa_medico_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceita();            
        $data['modelo'] = $this->exametemp->listarmodelosreceitaautomatico();      
        $data['empresapermissao'] = $this->guia->listarempresapermissoes();          
        $data['receita'] = $this->laudo->listarreceitatarefa($data['obj'][0]->paciente_id);      
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['tarefa_medico_id'] = $tarefa_medico_id;
         
        $this->load->View('ambulatorio/receituarioconsultatarefa-form', $data);
    }
    
     function gravarreceituariotarefa($tarefa_medico_id) {
    
        $this->laudo->gravarreceituariotarefa($tarefa_medico_id);
        $data['tarefa_medico_id'] = $tarefa_medico_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarreceituariotarefa/$tarefa_medico_id");
    }
    
      function editarcarregarreceituariotarefa($tarefa_medico_id, $tarefa_receituario_id) {
      
        $data['obj'] = $this->exametemp->listardadostarefa($tarefa_medico_id); 
        $data['receita'] = $this->laudo->listareditarreceitatarefa($tarefa_receituario_id); 
        $data['operadores'] = $this->operador_m->listarmedicos(); 
        $data['tarefa_medico_id'] = $tarefa_medico_id; 
         
        $this->load->View('ambulatorio/editarreceituarioconsultatarefa-form', $data);
    }

    function editarreceituariotarefa($tarefa_receituario_id) {

        $this->laudo->editarreceituariotarefa($tarefa_receituario_id);
        $data['tarefa_receituario_id'] = $tarefa_receituario_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }
    
    function impressaoreceitatarefa($tarefa_receituario_id) {
        $empresa_id = $this->session->userdata('empresa_id');

        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarreceitaimpressaotarefa($tarefa_receituario_id);        
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);         
        $data['tarefa_receituario_id'] = $tarefa_receituario_id;        
        $data['empresa'] = $this->guia->listarempresa();      
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();         
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);         
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);        
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
         
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;
      
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }

        $base_url = base_url();
        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;
        //    var_dump($carimbo);die;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        if ($data['empresa'][0]->ficha_config == 't') {
            // die;
            if ($data['empresa'][0]->cabecalho_config == 't') {
                if ($data['cabecalhomedico'][0]->cabecalho != '') {
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                } else {
                    $cabecalho = "$cabecalho_config";
                }
//                $cabecalho = $cabecalho_config;
            } else {
                $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
            }

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } 
           
            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape = $texto_rodape . $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            }

            $filename = "laudo.pdf";
//            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
//            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            if (false) {
                // die;
                $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            } else {
                $html = $this->load->view('ambulatorio/impressaoreceituarioconfiguravel', $data, true);
            }
            pdf($html, $filename, $cabecalho, $rodape);
        }

        
          
        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA   
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            $filename = "laudo.pdf";
            $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                        <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                        <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                        </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                        </table>";
            }
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            if ($data['laudo']['0']->medico_parecer1 == 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdf($html, $filename, $cabecalho, $rodape, $grupo);

///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo
            $filename = "laudo.pdf";

            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } else {
                $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
            } 
            $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }
    }
    
     function carregarreceituarioespecialtarefa($tarefa_medico_id) { 
        $data['obj'] = $this->exametemp->listardadostarefa($tarefa_medico_id); 
        $data['lista'] = $this->exametemp->listarautocompletemodelosreceitaespecial(); 
        $data['receita'] = $this->laudo->listarreceitasespeciaispacientetarefa( $data['obj'][0]->paciente_id);  
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['tarefa_medico_id'] = $tarefa_medico_id; 
        $this->load->View('ambulatorio/receituarioespecialconsultatarefa-form', $data);
    }
    
    
    
    
    function gravarreceituarioespecialtarefa($tarefa_medico_id) {

        $this->laudo->gravarreceituarioespecialtarefa($tarefa_medico_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarreceituarioespecialtarefa/$tarefa_medico_id");
    }
    
     function editarcarregarreceituarioespecialtarefa($tarefa_medico_id, $tarefa_receituario_especial_id) {
        $data['obj'] = $this->exametemp->listardadostarefa($tarefa_medico_id); 
        $data['receita'] = $this->laudo->listarreceitaespecialtarefa($tarefa_receituario_especial_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['tarefa_medico_id'] = $tarefa_medico_id; 
        $this->load->View('ambulatorio/editarreceituarioespecialconsultatarefa-form', $data);
        
    }
    
    function editarreceituarioespecialtarefa($tarefa_receituario_especial_id) { 
        $this->laudo->editarreceituarioespecialtarefa($tarefa_receituario_especial_id);
        $data['tarefa_receituario_especial_id'] = tarefa_receituario_especial_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }
    
     function impressaoreceitaespecialtarefa($tarefa_receituario_especial_id) {   
        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarreceitaespecialimpressaotarefa($tarefa_receituario_especial_id); 
        $data['tarefa_receituario_especial_id'] = $tarefa_receituario_especial_id;
        $data['empresa'] = $this->guia->listarempresamunicipio(); 
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo'][0]->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd'); 
        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        } 
        $this->load->helper('directory');
        $arquivos = directory_map("./upload/operadorLOGO/");
        $data['arquivo_existe'] = false;
//        var_dump($arquivos); die;
//        var_dump($arquivos);die;
        if (@$arquivos != false) {
            foreach (@$arquivos as $value) {
                if (@$value == @$data['laudo'][0]->medico_parecer1 . ".jpg") {
                    @$data['arquivo_existe'] = true;
                    @$data['medico_parecer1'] = @$data['laudo'][0]->medico_parecer1;
                    break;
                }
            }
        } 
        $this->load->View('ambulatorio/impressaoreceituarioespecial', $data); 
    }
    
    function carregarhistorico($paciente_id,$tarefa_medico_id){
         $data['historicowebcon'] = $this->laudo->listarconsultahistoricoweb($paciente_id);
         $data['historicowebexa'] = $this->laudo->listarexamehistoricoweb($paciente_id);
         $data['historicowebesp'] = $this->laudo->listarespecialidadehistoricoweb($paciente_id);
         $data['historico'] = $this->laudo->listarconsultahistoricodiferenciado($paciente_id);        
         $data['historicoexame'] = $this->laudo->listarexamehistoricodiferenciado($paciente_id);
         $data['historicoespecialidade'] = $this->laudo->listarespecialidadehistoricodiferenciado($paciente_id);
         $data['historiotarefa'] = $this->laudo->listartarefashistorico($paciente_id,$tarefa_medico_id);
         $this->load->View('ambulatorio/impressaohistoricotarefa', $data);
        
    }
    
    function impressaoreceitatodos($ambulatorio_laudo_id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->load->plugin('mpdf');
      
        $data['laudo'] = $this->laudo->listarreceitaimpressaotodos($ambulatorio_laudo_id);
        if(count($data['laudo']) == 0){
           echo "<script>window.close();</script>";
        }
     
        $string = "";
           
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
      
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        @$data['cabecalho'][0]->cabecalho = str_replace("_minicurriculum_", $data['cabecalhomedico'][0]->curriculo,$data['cabecalho'][0]->cabecalho);          
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
       
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudoreceituario($empresa_id);
        
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');
        
        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;

 
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;

    if ($data['empresa'][0]->ficha_config == 't') {
            // die;
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
            } else {
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                    $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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
            $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
            $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
            $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
            $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
            $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
            $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($data['laudo'][0]->data_cadastro)), $cabecalho);
            $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
            $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
            $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
            $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
            $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
            $operador_id = $this->session->userdata('operador_id');
            $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
            @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
            @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
            $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
            $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
            $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
            $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
            $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
            $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
            $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);
            $cabecalho = str_replace("_municipiodata_",$texto_rodape, $cabecalho);
            $data['impressaolaudo'][0]->adicional_cabecalho = str_replace("_municipiodata_",$texto_rodape, $data['impressaolaudo'][0]->adicional_cabecalho);
             
            $dataFuturo2 = date("Y-m-d");
            $dataAtual2 = $data['laudo'][0]->nascimento;
            $date_time2 = new DateTime($dataAtual2);
            $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
            $idade = $diff2->format('%Y anos');
            $cabecalho = str_replace("_idade_", $idade, $cabecalho); 
            $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
            $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']); 
              
               
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            }  
            
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            }

            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape_config = str_replace("_municipiodata_",$texto_rodape, $rodape_config);
                $rodape = $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            }
            
           

            $filename = "laudo.pdf";
            
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
             
            if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
               
            }
           
    }else{

            if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";         
            } 
    /////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";

            }

    ///////////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";

    /////////////////////////////////////////////////////////////////////////////////////////////////            
            } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";       
            } 
    /////////////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                            <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                            <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                            <tr><td></td><td></td></tr>
                            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                            </table>";
                if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                    $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                            <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                            <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                            </table>";
                }
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                if ($data['laudo']['0']->medico_parecer1 == 929) {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                            <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                }
                $grupo = $data['laudo']['0']->grupo;

    ///////////////////////////////////////////////////////////////////////////////////////            
            } else {//GERAL        //  este item fica sempre por ultimo
                $filename = "laudo.pdf";
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                    $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
                } else {
                    $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
                }
                $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";  
            }
     
    }
        
        $receita = $this->imprimirReceitaTodos2($ambulatorio_laudo_id);
        $html .= $receita;
         
        if ($sem_margins == 't') {
             pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0); 
        } else {
            pdf($html, $filename, $cabecalho, $rodape);
        }
                 
    }
    

    function impressaoreceitatodosnovo_imprimir($ambulatorio_laudo_id) {


        
        $empresa_id = $this->session->userdata('empresa_id');
        $this->load->plugin('mpdf');

        $impressao_receita = $this->laudo->impressaonovoatendimento_botaoimprimir($ambulatorio_laudo_id, 'RECEITAS');
        
        $data['laudo'] = $this->laudo->listarreceitaimpressaotodosnovo($impressao_receita);
        if(count($data['laudo']) == 0){
           echo "<script>window.close();</script>";

           die;
        }

        
        $string = "";
           
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
      
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        @$data['cabecalho'][0]->cabecalho = str_replace("_minicurriculum_", $data['cabecalhomedico'][0]->curriculo,$data['cabecalho'][0]->cabecalho);          
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
       
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudoreceituario($empresa_id);
        
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');
        
        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;

 
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;

    if ($data['empresa'][0]->ficha_config == 't') {
            // die;
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
            } else {
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                    $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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
            $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
            $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
            $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
            $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
            $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
            $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($data['laudo'][0]->data_cadastro)), $cabecalho);
            $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
            $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
            $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
            $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
            $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
            $operador_id = $this->session->userdata('operador_id');
            $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
            @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
            @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
            $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
            $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
            $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
            $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
            $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
            $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
            $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);
            $cabecalho = str_replace("_municipiodata_",$texto_rodape, $cabecalho);
            $data['impressaolaudo'][0]->adicional_cabecalho = str_replace("_municipiodata_",$texto_rodape, $data['impressaolaudo'][0]->adicional_cabecalho);
             
            $dataFuturo2 = date("Y-m-d");
            $dataAtual2 = $data['laudo'][0]->nascimento;
            $date_time2 = new DateTime($dataAtual2);
            $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
            $idade = $diff2->format('%Y anos');
            $cabecalho = str_replace("_idade_", $idade, $cabecalho); 
            $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
            $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']); 
              
               
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            }  
            
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            }

            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape_config = str_replace("_municipiodata_",$texto_rodape, $rodape_config);
                $rodape = $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            }
            
           

            $filename = "laudo.pdf";
            
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
             
            if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
               
            }
           
    }else{

            if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";         
            } 
    /////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";

            }

    ///////////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";

    /////////////////////////////////////////////////////////////////////////////////////////////////            
            } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";       
            } 
    /////////////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                            <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                            <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                            <tr><td></td><td></td></tr>
                            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                            </table>";
                if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                    $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                            <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                            <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                            </table>";
                }
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                if ($data['laudo']['0']->medico_parecer1 == 929) {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                            <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                }
                $grupo = $data['laudo']['0']->grupo;

    ///////////////////////////////////////////////////////////////////////////////////////            
            } else {//GERAL        //  este item fica sempre por ultimo
                $filename = "laudo.pdf";
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                    $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
                } else {
                    $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
                }
                $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";  
            }
     
    }
        
        $receita = $this->imprimirReceitaTodos2novo_imprimir($ambulatorio_laudo_id);
        $html .= $receita;
         
        if ($sem_margins == 't') {
             pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0); 
        } else {
            pdf($html, $filename, $cabecalho, $rodape);
        }
                 
    }


    function impressaoreceitatodosnovo($ambulatorio_laudo_id, $receitas = array(), $sem_data = null) {


        $empresa_id = $this->session->userdata('empresa_id');
        $this->load->plugin('mpdf');

        $receitass = explode(",", $receitas);
        

        $data['laudo'] = $this->laudo->listarreceitaimpressaotodosnovo($receitass);
        if(count($data['laudo']) == 0){
           echo "<script>window.close();</script>";

           die;
        }


            $_POST['sem_data_r'] = $sem_data;



        //$_POST['repetir_impressoes_receituario'] = explode(",", $impressao_array);
        // echo '<pre>';
        // print_r($_POST['repetir_impressoes_receituario']);
        // die;

        $string = "";
           
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
      
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        @$data['cabecalho'][0]->cabecalho = str_replace("_minicurriculum_", $data['cabecalhomedico'][0]->curriculo,$data['cabecalho'][0]->cabecalho);          
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
       
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudoreceituario($empresa_id);
        
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');
        
        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;

 
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];

        if($_POST['sem_data_r'] == 'on'){
            $texto_rodape = '';
        }else{
            $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        }

    if ($data['empresa'][0]->ficha_config == 't') {
            // die;
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
            } else {
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                    $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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
            $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
            $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
            $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
            $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
            $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
            $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($data['laudo'][0]->data_cadastro)), $cabecalho);
            $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
            $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
            $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
            $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
            $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
            $operador_id = $this->session->userdata('operador_id');
            $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
            @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
            @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
            $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
            $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
            $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
            $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
            $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
            $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
            $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);
            $cabecalho = str_replace("_municipiodata_",$texto_rodape, $cabecalho);
            $data['impressaolaudo'][0]->adicional_cabecalho = str_replace("_municipiodata_",$texto_rodape, $data['impressaolaudo'][0]->adicional_cabecalho);
             
            $dataFuturo2 = date("Y-m-d");
            $dataAtual2 = $data['laudo'][0]->nascimento;
            $date_time2 = new DateTime($dataAtual2);
            $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
            $idade = $diff2->format('%Y anos');
            $cabecalho = str_replace("_idade_", $idade, $cabecalho); 
            $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
            $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']); 
              
               
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            }  
            
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            }

            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape_config = str_replace("_municipiodata_",$texto_rodape, $rodape_config);
                $rodape = $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            }
            
           

            $filename = "laudo.pdf";
            
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
             
            if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
               
            }
           
    }else{

            if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";         
            } 
    /////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";

            }

    ///////////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";

    /////////////////////////////////////////////////////////////////////////////////////////////////            
            } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";       
            } 
    /////////////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                            <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                            <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                            <tr><td></td><td></td></tr>
                            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                            </table>";
                if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                    $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                            <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                            <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                            </table>";
                }
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                if ($data['laudo']['0']->medico_parecer1 == 929) {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                            <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                }
                $grupo = $data['laudo']['0']->grupo;

    ///////////////////////////////////////////////////////////////////////////////////////            
            } else {//GERAL        //  este item fica sempre por ultimo
                $filename = "laudo.pdf";
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                    $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
                } else {
                    $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
                }
                $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";  
            }
     
    }
        $html = '';
        $receita = $this->imprimirReceitaTodos2novo($ambulatorio_laudo_id, $receitas);
        $html .= $receita;

        $validarcert ='<div style="border: groove; width:180px; text-align: center; margin: 0pt 0pt 0pt 350pt;">Validar Certificado: <br> <a target="_blank" href="https://assinaturadigital.iti.gov.br/">validator.docusign.com</a></div>';
        
        $validarcert .= $cabecalho;

        $cabecalho = $validarcert;
        //print_r($validarcert);
        // print_r($cabecalho);
        //die;
        if ($sem_margins == 't') {
             pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0); 
        } else {
            pdf($html, $filename, $cabecalho, $rodape);
        }
                 
    }
    
    function carregarocorrencias($paciente_id){
        $data['ocorrencias'] = $this->guia->listarocorrenciaspaciente($paciente_id);  
        $this->load->View('ambulatorio/ocorrenciapaciente-lista',$data);  
    }
    
    function responderocorrencia($atendimento_ocorrencia_id){ 
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/ocorrencias/$atendimento_ocorrencia_id/"); 
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
         
       $data['ocorrencia'] = $this->guia->listarocorrencia($atendimento_ocorrencia_id);
       $data['atendimento_ocorrencia_id'] = $atendimento_ocorrencia_id;
       $data['operador'] = $this->operador_m->listaroperador($this->session->userdata('operador_id'));
       $data['respostas'] = $this->guia->listarrespostas($atendimento_ocorrencia_id);
       
       
       $this->load->View('ambulatorio/responderocorrencia-form',$data);  
    }
    
    function gravarespostaocorrencia(){
        $this->guia->gravarespostaocorrencia(); 
    }
    
    
    function excluirocorrencia($atendimento_ocorrencia_id){
        $this->guia->excluirocorrencia($atendimento_ocorrencia_id);  
       redirect(base_url() . "seguranca/operador/pesquisarrecepcao");  
         
    }
    
    
    
    
    
    function descricaoocorrencia($atendimento_ocorrencia_id){
      $data['ocorrencia'] = $this->guia->listarocorrencia($atendimento_ocorrencia_id);
      $data['atendimento_ocorrencia_id'] = $atendimento_ocorrencia_id;         
      $this->load->View('ambulatorio/finalizarocorrencia-form',$data);  
    }
    
    
    function finalizarocorrencia(){
        $this->guia->finalizarocorrencia();
         redirect(base_url() . "seguranca/operador/pesquisarrecepcao");   
    }
    
    
    function cancelarpoltrona($hora_agendamento_id){ 
        $this->laudo->cancelarpoltrona($hora_agendamento_id); 
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");   
    }
    
    function excluirsolicitacaochamar($solicitacao_exame_chamar_id){
         $this->laudo->excluirsolicitacaochamar($solicitacao_exame_chamar_id); 
         redirect(base_url() . "seguranca/operador/pesquisarrecepcao");   
    }
    
   function gravarimpressaoatestado(){ 
       $impressao_atestado_id = $this->laudo->gravarimpressaoatestado(); 
      echo $impressao_atestado_id;
   } 
   
   function impressaoatestadomedico($impressao_atestado_id){ 
        $this->load->plugin('mpdf');
        $this->load->helper('directory');
        $data['impressao_atestado_id'] = $impressao_atestado_id;
        $data['lista'] = $this->laudo->listarimpressaoatestado($impressao_atestado_id);

        $data['empresa_id'] = $data['lista'][0]->empresa_id;
        $empresa_id = $data['lista'][0]->empresa_id;
        $data['empresapermissao'] = $this->guia->listarempresasaladepermissao($empresa_id);
         $array = json_decode($data['lista'][0]->texto,true); 
        
        $cid = $array['txtCICPrimarioAtestado'];
        if($cid != ""){
          $data['cid'] = $this->guia->listarcidporcodigo($cid);
        }
        $data['arquivo_pasta_logo'] = directory_map("./upload/logomarca/$empresa_id/");
        
          switch (date("m")) {
                                case "01":    $mes = 'Janeiro';     break;
                                case "02":    $mes = 'Fevereiro';   break;
                                case "03":    $mes = 'Março';       break;
                                case "04":    $mes = 'Abril';       break;
                                case "05":    $mes = 'Maio';        break;
                                case "06":    $mes = 'Junho';       break;
                                case "07":    $mes = 'Julho';       break;
                                case "08":    $mes = 'Agosto';      break;
                                case "09":    $mes = 'Setembro';    break;
                                case "10":    $mes = 'Outubro';     break;
                                case "11":    $mes = 'Novembro';    break;
                                case "12":    $mes = 'Dezembro';    break; 
                         }
        
        
                                
        $municipio = $data['empresapermissao'][0]->municipio;
        $dia = date('d');
        $ano = date('Y');
                                
        $html =   $this->load->View('ambulatorio/impressaoatestadomedico',$data,true);
        $filename = "Atestado";  
        $cabecalho = "";
        $rodape = "<table width='100%' ><tr><td style='text-align:right;'><b>$municipio, <u> $dia</u> de <u>$mes</u> , <u>$ano</u></b></td><tr></table>";
        pdf($html, $filename, $cabecalho, $rodape);
    
   }
   
   
   
}

/* End of file welcome.php */
    /* Location: ./system/application/controllers/welcome.php */
