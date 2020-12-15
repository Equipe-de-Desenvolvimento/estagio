<?php
require_once("./iugu/lib/Iugu.php");
require_once('./gerencianet/vendor/autoload.php');

use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;

class AppPacienteAPI extends Controller {

    function AppPacienteAPI() {

        parent::Controller();
        $this->load->model('login_model', 'login_m');
        $this->load->model('app_model', 'app');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/laudo_model', 'laudo');
        $this->load->model('ambulatorio/empresa_model', 'empresa');
        $this->load->model('cadastro/paciente_model', 'paciente');
        $this->load->model('seguranca/operador_model', 'operador_m');
        

    }

    function index(){
        echo json_encode('WebService');
    }

    function redefinir_senha(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $empresa = $this->guia->listarempresa(1);
        $resposta = $this->app->emailPaciente($json_post);
        if($resposta[0] == ''){
            $obj = new stdClass();
            $obj->status = 200;
            $obj->mensagem = 'Email não encontrado';
            
            echo json_encode($obj); 
            die;
        }
        $link = base_url() . "appPacienteAPI/reset_senha/{$resposta[0]}";
       
        $mensagem_email = "Para redefinir sua senha é necessário acessar o link a seguir:  <br>
                          LinK: $link";
        // $mensagem_email = "Entre no link a seguir para redefinir sua senha! $json_post->link_senha";
        if(count($empresa) > 0){
            $empresa_nome = $empresa[0]->razao_social;
            if($empresa[0]->email != ''){
                $empresa_email = $empresa[0]->email;
            }else{
                $empresa_email = 'contato@stgsaude.com.br';
            }
            
        }else{
            $empresa_email = 'STG Saúde APP';
            $empresa_email = 'contato@stgsaude.com.br';
        }

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
        $this->email->from($empresa_email, $empresa_nome);
        $this->email->to($resposta[1]);
        $this->email->subject('Redefinição de senha Parte 1');
        $this->email->message($mensagem_email);
        if ($this->email->send()) {
            $mensagem = "Email enviado com sucesso.";
        } else {
            $mensagem = "Envio de Email malsucedido.";
        }

        $obj = new stdClass();
        $obj->status = 200;
        $obj->mensagem = $mensagem;
        
        echo json_encode($obj); 
        die;

    }
    
    function reset_senha($paciente_id){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $empresa = $this->guia->listarempresa(1);
        $resposta = $this->app->resetarSenhaPaciente($paciente_id);
        $mensagem_email = "A sua nova senha é: $resposta[1] <br> Não se esqueça de redefinir sua senha ao entrar no aplicativo novamente";
        // $mensagem_email = "Entre no link a seguir para redefinir sua senha! $json_post->link_senha";
        if(count($empresa) > 0){
            $empresa_nome = $empresa[0]->razao_social;
            if($empresa[0]->email != ''){
                $empresa_email = $empresa[0]->email;
            }else{
                $empresa_email = 'contato@stgsaude.com.br';
            }
            
        }else{
            $empresa_email = 'STG Saúde APP';
            $empresa_email = 'contato@stgsaude.com.br';
        }

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
        $this->email->from($empresa_email, $empresa_nome);
        $this->email->to($resposta[2]);
        $this->email->subject('Redefinição de senha Parte 2');
        $this->email->message($mensagem_email);
        if ($this->email->send()) {
            $mensagem = "Email enviado com sucesso.";
        } else {
            $mensagem = "Envio de Email malsucedido.";
        }

        $obj = new stdClass();
        $obj->status = 200;
        $obj->nome = $mensagem;
         
        echo "<html>
            <meta charset='UTF-8'>
        <script type='text/javascript'>
        alert('Sua nova senha foi enviada para seu email!');
        
            </script>
            </html>";

    }
    
    function editar_paciente(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        // $usuario = $json_post->user;
        // $senha = $json_post->password;
        $empresa = 1;
        if(!isset($json_post->nome)){
            $obj = new stdClass();
            $obj->message = 'Campo nome em branco';
            echo json_encode($obj); 
            die;
        }
        $resposta = $this->app->editarCadastroPaciente($json_post);
        $obj = new stdClass();
        if($resposta > 0){
            $obj->status = 200;
            $obj->paciente_id = $resposta[0];
            $obj->nome = $resposta[1];
            
        }else{
            $obj->status = 404;
            $obj->paciente_id = 0;
        }
        echo json_encode($obj); 

    }

    function editar_senha_paciente(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        // $usuario = $json_post->user;
        // $senha = $json_post->password;
        $resposta = $this->app->editarSenhaPaciente($json_post);
        $obj = new stdClass();
        $obj->status = 200;
        $obj->mensagem = 'Senha atualizada';
        $obj->paciente_id = $json_post->paciente_id;
        
        echo json_encode($obj); 

    }

    function buscar_paciente(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        //var_dump($_GET); 
        //die;
        $resposta = $this->app->buscarpaciente($json_post);        
        // echo '<pre>';
        // var_dump($resposta); 
        // die;

        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->data = $resposta;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }
    
    function gravar_precadastro(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        // $usuario = $json_post->user;
        // $senha = $json_post->password;
        $empresa = 1;
        if(!isset($json_post->nome)){
            $obj = new stdClass();
            $obj->message = 'Campo nome em branco';
            echo json_encode($obj); 
            die;
        }
        $resposta = $this->app->gravarPrecadastro($json_post);
        $obj = new stdClass();
        if($resposta > 0){
            $obj->status = 200;
            $obj->paciente_id = $resposta[0];
            $obj->nome = $resposta[1];
            
        }else{
            $obj->status = 404;
            $obj->paciente_id = 0;
        }
        echo json_encode($obj); 

    }
    
    function login(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $usuario = $json_post->user;
        $senha = $json_post->password;
        $empresa = 1;

        $resposta = $this->login_m->autenticarpacienteweb($usuario, $senha, $empresa);
        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->id = $resposta[0]->paciente_id;
            $obj->data = new stdClass();
            $obj->data->nome = $resposta[0]->nome;
            $obj->data->cpf = $resposta[0]->cpf;
            $obj->data->email = $resposta[0]->cns;
            $obj->data->plano = '';
            $obj->data->sistema = 'C';
        }else{
            $obj->status = 404;
            $obj->id = 0;
        }
        echo json_encode($obj); 

// {						
//     "status": 200,      /* 200 = Requisição Ok. */
//     "id": 48665684      /* id do paciente no sistema STG. */,
//     "data": {
//         "nome": "Johnny Alves",     /* Nome do paciente. */
//         "cpf": "84684684654",       /* CPF do paciente. */
//     }
// }
    }

    function email_verificacao(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $usuario = $json_post->email;
        $empresa = 1;

        $resposta = $this->login_m->email_verificacao($usuario);
        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->usado = true;
            
        }else{
            $obj->status = 200;
            $obj->usado = false;
        }
        echo json_encode($obj); 

    }

    function cancelar_agendamento($agenda_exames_id){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;

        $resposta = $this->app->cancelar_agendamento($agenda_exames_id);
        $obj = new stdClass();
        $obj->status = 200;
        
        echo json_encode($obj); 

    }

    function risco_cirurgico(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        
        $paciente_id = $json_post->paciente_id;
        $questionario = $json_post->questionario;
        // var_dump($questionario); 
        // die;
        $resposta = $this->app->gravarRiscoCirurgico($paciente_id, $questionario);
        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
        }else{
            $obj->status = 404;
        }
        echo json_encode($obj); 

    }

    function alerta_medico() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        
        $paciente_id = $json_post->paciente_id;
        $medico_id = $json_post->medico_id;
        $mensagem = $json_post->mensagem;
        if($mensagem == ''){
            $paciente_inf = $this->paciente->listardados($paciente_id);
            if(count($paciente_inf) > 0){
                $mensagem = "O(a) paciente {$paciente_inf[0]->nome} se conectou ao Zoom!";
            }else{
                $mensagem = "Um paciente se conectou ao Zoom!";
            }
            
        }
        // var_dump($mensagem); 
        // die;
        $resposta = $this->app->gravaralertamedico($medico_id, $mensagem);
        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
        }else{
            $obj->status = 404;
        }
        echo json_encode($obj); 

    }

    function listar_grupos(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $medico_id = $_GET['medico_id'];
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $resposta = $this->app->listargrupos($medico_id);    
        if($medico_id > 0 && count($resposta) > 0){
            if(json_decode($resposta[0]->nome) != null){
                $array = json_decode($resposta[0]->nome);
            }else{
                $array = array();
            }
            foreach ($array as $key => $value) {
                $resposta[$key] = new stdClass();
                $resposta[$key]->nome = $value;
            }
        }
        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->data = $resposta;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function listar_botoes(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $empresa_id = @$_GET['empresa_id'];
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $resposta = $this->app->listarBotoes($empresa_id);    

        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->data = $resposta[0];
        }
        else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function buscar_procedimento_convenio() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        $convenio_id = $json_post->convenio_id;
        
        $grupo = @$json_post->grupo;
        if (empty($grupo)) {
            $grupo = 'CONSULTA';
        }

        if (isset($convenio_id)) {
            $result = $this->app->listarautocompleteprocedimentosatendimentonovo($convenio_id, @$grupo);
        } 
// var_dump($result); die;
        if(count($result) > 0){
            foreach ($result as $key => $value) {
                $result[$key]->valortotal = number_format($value->valortotal, 2 , ',', '.');
                # code...
            }

        }
        $obj = new stdClass();
        if(count($result) > 0){
            $obj->status = 200;
            $obj->data = $result;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function buscar_clinicas() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
       
        $result = $this->app->listarclinicaprocedimento();
        
        $obj = new stdClass();
        if(count($result) > 0){
            $obj->status = 200;
            $obj->data = $result;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function listar_agendado() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));

        $paciente_id = $_GET['paciente_id']; 
       
        $result = $this->app->listaragendatotalpacientegeral($paciente_id);
        
        $obj = new stdClass();
        if(count($result) > 0){
            $obj->status = 200;
            $obj->data = $result;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function listar_solicitar_agenda() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));

        $paciente_id = $_GET['paciente_id'];
       
        $result = $this->app->listarsolicitacaoagendamento($paciente_id);
        
        $obj = new stdClass();
        if(count($result) > 0){
            $obj->status = 200;
            $obj->data = $result;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function enviarNotificacao($mensagem){
        $resposta = $this->app->buscarHashDispositivoPaciente();    
        $headers = array();
        // echo '<pre>';
        // var_dump($resposta); 
        // die;
        if(count($resposta) > 0){
            $hash = '';
            $hash_array = array();
            foreach ($resposta as $key => $value) {
                $hash_array[] = $value->hash;
            }
            $hash = json_encode($hash_array);
            
            $url = 'https://onesignal.com/api/v1/notifications';
            $headers[] = 'Content-Type: application/json; charset=utf-8';
            $headers[] = 'Authorization: Basic ZTVmZTU2NjEtZDU1My00NzQzLTllZTYtMzFkMjJlMmEzZWZi';
            $ch = curl_init();
            $body = '{
                "app_id": "13964cbb-2421-4e58-b040-0ad8f2b2e9fa",
                "include_player_ids": '. $hash .',
                "data": {"foo": "bar"},
                "contents": {"en": "'. "$mensagem" .'"}
            }';
            // var_dump($body);
            // die;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

            $result = curl_exec($ch);
            // var_dump($result); die;
            curl_close($ch);
            
            return true;
        }else{
            return false;
        }

    }

    function registrar_dispositivo(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        
        $paciente_id = $_GET['ID'];
        $hash = $_GET['indentificacao_dispositivo'];
        
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $resposta = $this->app->registrarDispositivoPaciente($paciente_id, $hash);    

        $obj = new stdClass();
        if(count($resposta) > 0 && $resposta != false){
            $obj->status = 200;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function buscar_datas() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        $result = array();
        // var_dump($_GET); die;
        if (isset($_GET['procedimento_id']) && isset($_GET['empresa_id'])) {
            $result = $this->app->horariosdisponiveisclinica($_GET['procedimento_id'], $_GET['empresa_id']);
        }
        
        $obj = new stdClass();
        if(count($result) > 0){
            $obj->status = 200;
            $obj->data = $result;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        echo json_encode($obj); 
    }

    function listar_tipoagenda() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        $result = array();
        // var_dump($_GET); die;
        $result = $this->app->listartipoagenda();
        
        
        $obj = new stdClass();
        if(count($result) > 0){
            $obj->status = 200;
            $obj->data = $result;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        echo json_encode($obj); 
    }

    function buscar_datas_unicas() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        $result = array();
        // var_dump($_GET); die;
        $result = $this->app->horariosdisponiveisclinicaunicas();
        //  echo '<pre>';
        //  var_dump($result); 
        //  die;
        $obj = new stdClass();
        if(count($result) > 0){
            $obj->status = 200;
            $obj->data = $result;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        echo json_encode($obj); 
    }

    function listar_horarios_medicos() {
        $result = array();
        // var_dump($_GET); die;
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        $procedimento_id = $json_post->procedimento_id;
        $empresa_id = $json_post->empresa_id;
        $data = $json_post->data;
        
        $result = $this->app->listarhorariosmedicos($procedimento_id, $empresa_id, $data);
        
        $objetoCerto = array();
        $medico_id = 0;
        $contador = -1;
        // echo '<pre>';
        // var_dump($result); 
        // die;
        foreach ($result as $key => $value) {
            $horario = date("H:i",strtotime($value->inicio));
            $agenda_exames_id = $value->agenda_exames_id;
            
            if($value->medico_agenda != $medico_id){
                $contador++;
                $objetoCerto[$contador] = new stdClass();
                $objetoCerto[$contador]->medico_id = $value->medico_agenda;
                $objetoCerto[$contador]->nome = $value->medico;
                if($value->sigla != ''){
                    $objetoCerto[$contador]->sigla = $value->sigla;
                }else{
                    $objetoCerto[$contador]->sigla = "CRM";
                }
                
                $objetoCerto[$contador]->conselho = $value->conselho;
                $objetoCerto[$contador]->horario = array();
                $objetoCerto[$contador]->agenda_exames_id = array();
                array_push($objetoCerto[$contador]->horario, $horario);
                array_push($objetoCerto[$contador]->agenda_exames_id, $agenda_exames_id); 
            }else{
                // var_dump($contador);
                array_push($objetoCerto[$contador]->horario, $horario);
                array_push($objetoCerto[$contador]->agenda_exames_id, $agenda_exames_id);  
            }
            
            $medico_id = $value->medico_agenda;
   
        }
        // echo '<pre>';
        // var_dump($objetoCerto); 
        // die;
        
        $obj = new stdClass();
        if(isset($objetoCerto)){
            $obj->status = 200;
            $obj->data = $objetoCerto;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function listar_horarios_medicos_unicos() {
        $result = array();
        // var_dump($_GET); die;
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        $data = $json_post->data;
        
        $result = $this->app->listarhorariosmedicosunicos($data);
        // $contador = -1;
        // echo '<pre>';
        // var_dump($result); 
        // die;
        $objetoCerto = array();
        $medico_id = 0;
        $contador = -1;
        foreach ($result as $key => $value) {
            $horario = date("H:i",strtotime($value->inicio));
            $agenda_exames_id = $value->agenda_exames_id;
            
            if($value->medico_agenda != $medico_id){
                $contador++;
                $objetoCerto[$contador] = new stdClass();
                $objetoCerto[$contador]->medico_id = $value->medico_agenda;
                $objetoCerto[$contador]->nome = $value->medico;
                if($value->sigla != ''){
                    $objetoCerto[$contador]->sigla = $value->sigla;
                }else{
                    $objetoCerto[$contador]->sigla = "CRM";
                }
                
                $objetoCerto[$contador]->conselho = $value->conselho;
                $objetoCerto[$contador]->horario = array();
                $objetoCerto[$contador]->agenda_exames_id = array();
                array_push($objetoCerto[$contador]->horario, $horario);
                array_push($objetoCerto[$contador]->agenda_exames_id, $agenda_exames_id); 
            }else{
                // var_dump($contador);
                array_push($objetoCerto[$contador]->horario, $horario);
                array_push($objetoCerto[$contador]->agenda_exames_id, $agenda_exames_id);  
            }
            
            $medico_id = $value->medico_agenda;
   
        }
        // echo '<pre>';
        // var_dump($result); 
        // die;
        
        $obj = new stdClass();
        if(isset($result)){
            $obj->status = 200;
            $obj->data = $objetoCerto;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }


    function gravar_agendamento(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        
        $paciente_id = $json_post->paciente_id;
        $agenda_exames_id = $json_post->agenda_exames_id;
        $procedimento_id = $json_post->procedimento_id;
        $empresa_id = $json_post->empresa_id;

        $retorno = $this->app->gravarAgendamento($empresa_id, $paciente_id, $agenda_exames_id, $procedimento_id);
        $obj = new stdClass();
        if($retorno != false){
            $obj->status = 200;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;
        echo json_encode($obj); 
    }

    function gravar_agendamento_unico(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        
        $paciente_id = $json_post->paciente_id;
        $agenda_exames_id = $json_post->agenda_exames_id;
        $procedimento_id = $this->app->listarprocedimentoconsultaparticular();
        $empresa_id = 1;
        $retorno = $this->app->gravarAgendamento($empresa_id, $paciente_id, $agenda_exames_id, $procedimento_id);
        $obj = new stdClass();
        if($retorno != false){
            $obj->status = 200;
        }else{
            $obj->status = 404;
        }
        $reotrnoFunc = $this->criar_pagamentoGerenciaNet($paciente_id, $agenda_exames_id);
        // echo '<pre>';
        // var_dump($obj); 
        // die;
        echo json_encode($obj); 
    }

    function gravar_solicitar_agendamento(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        
        $paciente_id = $json_post->paciente_id;
        $data = $json_post->data;
        $hora = $json_post->hora;
        $procedimento_id = $json_post->procedimento_id;
        $procedimento_text = $json_post->procedimento_text;
        $convenio_text = $json_post->convenio_text;
        if(!$paciente_id > 0){
            $obj = new stdClass();
            $obj->status = 404;
            echo json_encode($obj); 
            die;
        }
        $retorno = $this->app->gravarSolicitarAgendamento($paciente_id, $data, $hora, $procedimento_id, $procedimento_text, $convenio_text);
        $obj = new stdClass();
        if($retorno != false){
            $obj->status = 200;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;
        echo json_encode($obj); 
    }

    function pesquisa_satisfacao(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        
        $paciente_id = $json_post->paciente_id;
        $questionario = $json_post->questionario;
        // var_dump($questionario); 
        // die;
        $resposta = $this->app->gravarPesquisaSatisfacao($paciente_id, $questionario);
        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
        }else{
            $obj->status = 404;
        }
        echo json_encode($obj); 

    }

    function agenda_calendario(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $medico_id = $_GET['medico_id'];
        $data = $_GET['data'];
        $empresa_id = $_GET['empresa_id'];
        $data_array = array();
        $resposta = $this->app->listarhorarioscalendarioAPP($medico_id, $data, $empresa_id); 
	//echo "<pre>";
        $i = 0;
        foreach ($resposta as $item) {
	    $retorno = new stdClass();
            $i++;
            // $retorno['id'] = $i;
            if ($item->situacao == 'LIVRE') {
                $retorno->title = $item->contagem . ' Horários Livres';
            } else {
                $retorno->title = $item->contagem . ' Pacientes';
            }

            $retorno->start = $item->data;
            if ($item->situacao == 'LIVRE') {
                $retorno->backgroundColor = '#00e676';
                $retorno->borderColor = '#00e676';
                $retorno->textColor = '#000000';
            } else {
                $retorno->backgroundColor = '#b71c1c';
                $retorno->borderColor = '#b71c1c';
                $retorno->textColor = '#ffffff';
            }
           
            $data_array[] = $retorno;
            //var_dump($retorno);
            //var_dump($data_array);
            //echo '<hr>';
        }  
        // echo '<pre>';
        // var_dump($data_array); 
        // die;

        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->data = $data_array;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function agenda(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        //$json_post = json_decode(file_get_contents("php://input"));
        //var_dump($_GET); 
        //die;
        $medico_id = $_GET["medico_id"];
        $data = $_GET["data"];
        $empresa_id = $_GET["empresa_id"];
        $resposta = $this->app->listarhorariosAPP($medico_id, $data, $empresa_id);        
        // echo '<pre>';
        // var_dump($resposta); 
        // die;

        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->data = $resposta;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function historico_exame(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $paciente_id = $_GET['paciente_id'];

        $resposta = $this->app->listarhistoricoAPPEspec($paciente_id, 'EXAME');        
        // echo '<pre>';
        // var_dump($resposta); 
        // die;

        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->data = $resposta;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function busca_mensalidades() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // echo '<pre>';
        // var_dump($json_post); 
        // die;
        $paciente_id = $json_post->paciente_id;

        // verificarcarenciaweb
        $flags = $this->guia->listarempresapermissoes(1);
        $paciente_inf = $this->guia->pacienteAntigoId($paciente_id);
        // var_dump($paciente_inf); die;
        $convenio_retorno = $this->guia->enderecoSistemaFidelidadeExis();
        $endereco = $convenio_retorno[0]->fidelidade_endereco_ip;
        $paciente_antigo_id = (int) $paciente_inf[0]->prontuario_antigo;
        $cpf = $paciente_inf[0]->cpf;
        $obj = new stdClass();
        if ($endereco != '') {
            if ($flags[0]->fidelidade_paciente_antigo == 'f') {
                $paciente_antigo_id = 0;
            }
            // Dessa forma, se a flag estiver desativada o sistema manda zero pro fidelidade
            // daí o fidelidade não pesquisa pelo ID e sim pelo CPF.
            //    echo "<pre>";
            $return = file_get_contents("http://{$endereco}/autocomplete/mensalidadesAPI?paciente_antigo_id=$paciente_antigo_id&cpf=$cpf");
            $resposta = json_decode($return);
            // var_dump($resposta);
            // die;
            // echo json_encode($resposta);
            
            if($resposta != NULL){
                $obj->status = 200;
                $obj->data = $resposta;
            }else{
                $obj->status = 404;
                $obj->data = [];
            }
           
        } else {
            $obj->status = 404;
            $obj->mensagem = 'Erro ao configurar Fidelidade e Clinicas';
        }
        echo json_encode($obj); 
    }

    function busca_carterinha_virtual() {
        // $json_post = json_decode(file_get_contents("php://input"));
        // echo '<pre>';
        // var_dump($json_post); 
        // die;
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $paciente_id = $_GET['paciente_id'];

        // verificarcarenciaweb
        $flags = $this->guia->listarempresapermissoes(1);
        $paciente_inf = $this->guia->pacienteAntigoId($paciente_id);
        // var_dump($paciente_inf); die;
        $convenio_retorno = $this->guia->enderecoSistemaFidelidadeExis();
        $endereco = $convenio_retorno[0]->fidelidade_endereco_ip;
        $paciente_antigo_id = (int) $paciente_inf[0]->prontuario_antigo;
        $cpf = $paciente_inf[0]->cpf;
        $obj = new stdClass();
        if ($endereco != '') {
            if ($flags[0]->fidelidade_paciente_antigo == 'f') {
                $paciente_antigo_id = 0;
            }
            // Dessa forma, se a flag estiver desativada o sistema manda zero pro fidelidade
            // daí o fidelidade não pesquisa pelo ID e sim pelo CPF.
            //    echo "<pre>";
            $return = file_get_contents("http://{$endereco}/autocomplete/impressaoCarteiraWeb?paciente_id=$paciente_antigo_id&cpf=$cpf");
            $resposta = $return;
            // var_dump($resposta);
            // die;
            // echo json_encode($resposta);
            
            if($resposta != NULL){
                $obj->status = 200;
                $obj->data = $resposta;
            }else{
                $obj->status = 404;
                $obj->data = [];
            }
           
        } else {
            $obj->status = 404;
            $obj->mensagem = 'Erro ao configurar Fidelidade e Clinicas';
        }
        echo json_encode($obj); 
    }

    function historico_consulta(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $paciente_id = $_GET['paciente_id'];

        $resposta = $this->app->listarhistoricoAPPEspec($paciente_id, 'CONSULTA');        
        // echo '<pre>';
        // var_dump($resposta); 
        // die;

        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->data = $resposta;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function posts_blog(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        // $medico_id = $json_post->medico_id;
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $resposta = $this->app->listarPostsBlog();    

        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->data = $resposta;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function busca_empresas(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        // $medico_id = $json_post->medico_id;
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $resposta = $this->app->listarEmpresas();    

        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->data = $resposta;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function busca_convenios(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        // $medico_id = $json_post->medico_id;
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $resposta = $this->app->listarConvenios();    
        foreach ($resposta as $key => $value) {
            $resposta[$key]->image = '';
            if(file_exists('./' . $value->caminho_logo) && $value->caminho_logo != ''){
                // $resposta[$key]->image = 1; 
                $resposta[$key]->image = base64_encode(file_get_contents(base_url() . $value->caminho_logo));
            }
        }

        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->data = $resposta;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function busca_medicos(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $grupo = '';
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $resposta = $this->app->listarmedicos($grupo);    
        foreach ($resposta as $key => $value) {
            $resposta[$key]->grupos = json_decode($value->grupos);
        }
        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->data = $resposta;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function busca_especialidade(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
       
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $array = array();
        $resposta = $this->app->listargrupos(0);    
        if(count($resposta) > 0){
         
            foreach ($resposta as $key => $value) {
                $array[$key] = $value->nome;
            }
        }
        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->data = $array;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function criar_pagamentoGerenciaNet($paciente_id = null, $agenda_exames_id = null){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        if(!$paciente_id > 0 && !$agenda_exames_id > 0){
            $obj = new stdClass();
            $obj->status = 404;
            $obj->message = 'Erro ao gerar cobrança, parametros ausentes';
            echo json_encode($obj); 
            die;
        }
        $transacao_id = $this->criarTransacaoGerenciaNet();
        $link_pagamento = $this->criarLinkGerenciaNet($transacao_id);
        if($link_pagamento == 'Error' || $transacao_id == 'Error'){
            $obj = new stdClass();
            $obj->status = 404;
            $obj->message = 'Erro ao gerar cobrança, tente novamente mais tarde';
            echo json_encode($obj); 
            die;
        }else{
            $paciente_retorno = $this->app->gravarPacienteLink($transacao_id, $paciente_id, $link_pagamento, $agenda_exames_id);
            $obj = new stdClass();
            $obj->status = 200;
            $obj->data = new stdClass();
            $obj->data->charge_id = $transacao_id;
            $obj->data->link = $link_pagamento;
            echo json_encode($obj); 
            die;
        }
    }


    function criarTransacaoGerenciaNet(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $empresa = $this->app->listarEmpresaGerenciaNet(1);  

        $clientId = $empresa[0]->client_id; // insira seu Client_Id, conforme o ambiente (Des ou Prod)
        $clientSecret = $empresa[0]->client_secret; // insira seu Client_Secret, conforme o ambiente (Des ou Prod)
        $valor = $empresa[0]->valor_consulta_app * 100; // insira seu Client_Secret, conforme o ambiente (Des ou Prod)
        if($empresa[0]->client_sandbox == 'f'){
            $sandbox = false;
        }else{
            $sandbox = true;
        }
        
        $options = [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'sandbox' => $sandbox // altere conforme o ambiente (true = desenvolvimento e false = producao)
        ];
        
        $item_1 = [
            'name' => 'Pagamento Atendimento Online', // nome do item, produto ou serviço
            'amount' => 1, // quantidade
            'value' => $valor // valor (1000 = R$ 10,00) (Obs: É possível a criação de itens com valores negativos. Porém, o valor total da fatura deve ser superior ao valor mínimo para geração de transações.)
        ];
        $items =  [
            $item_1
        ];

        $body  =  [
            'items' => $items
        ];

        try {
            $api = new Gerencianet($options);
            $charge = $api->createCharge([], $body);
        
            // print_r($charge);
            // var_dump($charge['data']['charge_id']); die;
            return $charge['data']['charge_id'];
        } catch (GerencianetException $e) {
            // print_r($e->code);
            // print_r($e->error);
            // print_r($e->errorDescription);
            return 'Error';
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    function confirmarpagamentoautomaticogerencianet() {
        //        $invoice_id = $_POST["data"]['id'];
        //        $status = $_POST["data"]['status'];
        set_time_limit(0); // Limite de tempo de execução: 2h. Deixe 0 (zero) para sem limite
        ignore_user_abort(true); // Não encerra o processamento em caso de perda de conexão
        $pagamento = $this->app->listarPagamentoAgendaExames();
    

        $empresa = $this->app->listarEmpresaGerenciaNet(1);
        // echo "<pre>";
        // var_dump($pagamento);
        // die;

        $client_id = $empresa[0]->client_id;
        $client_secret = $empresa[0]->client_secret;
        if($empresa[0]->client_sandbox == 'f'){
            $sandbox = false;
        }else{
            $sandbox = true;
        }
        
        $options = [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'sandbox' => $sandbox // altere conforme o ambiente (true = desenvolvimento e false = producao)
        ];

        if ($client_id != "" && $client_secret != "") {
            foreach ($pagamento as $item) {
                $params = [
                    'id' => $item->charge_id  // $charge_id refere-se ao ID da transação ("charge_id")
                ];
                try {
                    $api = new Gerencianet($options);
                    $charge = $api->detailCharge($params, []);
                    // echo "<pre>";
                    // print_r($charge);
                    // die;
//                    print_r($charge);
                } catch (GerencianetException $e) {
                    print_r($e->code);
                    print_r($e->error);
                    print_r($e->errorDescription);
                } catch (Exception $e) {
                    // print_r($e->getMessage());
                }
                // die;
                if ($charge['data']['status'] == "settled" || $charge['data']['status'] == "paid") {
                    $this->app->autorizarPacienteGerenciaNet($item->agenda_exames_id);
                }
                // die;
            }
            echo 'true';
        } else {
            echo 'false';
        }
    }

    function autorizarPacienteGerenciaNet($agenda_exames_id){

        $autorizacao = $this->app->autorizarPacienteGerenciaNet($agenda_exames_id); 

    }


    function criarLinkGerenciaNet($charge_id){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // var_dump($charge_id); die;
        $empresa = $this->app->listarEmpresaGerenciaNet(1);  
        $clientId = $empresa[0]->client_id; // insira seu Client_Id, conforme o ambiente (Des ou Prod)
        $clientSecret = $empresa[0]->client_secret; // insira seu Client_Secret, conforme o ambiente (Des ou Prod)
        $valor = $empresa[0]->valor_consulta_app * 100; // insira seu Client_Secret, conforme o ambiente (Des ou Prod)
        if($empresa[0]->client_sandbox == 'f'){
            $sandbox = false;
        }else{
            $sandbox = true;
        }
        
        $options = [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'sandbox' => $sandbox // altere conforme o ambiente (true = desenvolvimento e false = producao)
        ];
        
        // $charge_id refere-se ao ID da transação gerada anteriormente
        $params = [
        'id' => $charge_id
        ];
        
        $body = [
            'message' => '', // mensagem para o pagador com até 80 caracteres
            'expire_at' => date('Y-m-d', strtotime("+7 day", strtotime(date("Y-m-d")))), // data de vencimento da tela de pagamento e do próprio boleto
            'request_delivery_address' => false, // solicitar endereço de entrega do comprador?
            'payment_method' => 'all' // formas de pagamento disponíveis
        ];
        
        try {
            $api = new Gerencianet($options);
            $response = $api->linkCharge($params, $body);
            // print_r($response);
            return $response['data']['payment_url'];
        } catch (GerencianetException $e) {
            // print_r($e->code);
            // print_r($e->error);
            // print_r($e->errorDescription);
            return 'Error';
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    function paciente_charge_id($paciente_id){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");

        $paciente_charge = $this->app->paciente_charge_id($paciente_id);  
        $obj = new stdClass();
        $obj->status = 200;
        if(count($paciente_charge) > 0){
            $obj->data = new stdClass();
            $obj->data->charge_id = $paciente_charge[0]->gerencianet_id;
            $obj->data->link = $paciente_charge[0]->gerencianet_link;
        }else{
            $obj->message = 'Paciente não encontrado';
        }
        echo json_encode($obj);
        die;
        

    }

    function agenda_charge_id($agenda_exames_id){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");

        $paciente_charge = $this->app->agenda_charge_id($agenda_exames_id);  
        $obj = new stdClass();
        $obj->status = 200;
        if(count($paciente_charge) > 0){
            $obj->data = new stdClass();
            $obj->data->charge_id = $paciente_charge[0]->gerencianet_id;
            $obj->data->link = $paciente_charge[0]->gerencianet_link;
        }else{
            $obj->message = 'Paciente não encontrado';
        }
        echo json_encode($obj);
        die;
    }

    function verificar_transacaoGerencianet($charge_id){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // var_dump($charge_id); die;
        $empresa = $this->app->listarEmpresaGerenciaNet(1);  
        $clientId = $empresa[0]->client_id; // insira seu Client_Id, conforme o ambiente (Des ou Prod)
        $clientSecret = $empresa[0]->client_secret; // insira seu Client_Secret, conforme o ambiente (Des ou Prod)
        $valor = $empresa[0]->valor_consulta_app * 100; // insira seu Client_Secret, conforme o ambiente (Des ou Prod)
        if($empresa[0]->client_sandbox == 'f'){
            $sandbox = false;
        }else{
            $sandbox = true;
        }
        
        $options = [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'sandbox' => $sandbox // altere conforme o ambiente (true = desenvolvimento e false = producao)
        ];
        
        $params = [
            'id' => $charge_id // $charge_id refere-se ao ID da transação ("charge_id")
        ];
        
        try {
            $api = new Gerencianet($options);
            $charge = $api->detailCharge($params, []);
            // echo '<pre>';
            // print_r($charge);
            echo json_encode($charge);
            die;
        } catch (GerencianetException $e) {
            print_r($e->code);
            print_r($e->error);
            print_r($e->errorDescription);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    function gerarAgendaZoom(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.zoom.us/v2/users/me/meetings",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"topic\":\"Teste\",\"type\":2,\"start_time\":\"2020-03-29T16:54:14Z\",\"duration\":30,\"timezone\":\"America/Fortaleza\",\"password\":\"\",\"agenda\":\"\",\"settings\":{\"host_video\":true,\"participant_video\":true,\"join_before_host\":\"true\",\"mute_upon_entry\":false,\"watermark\":false,\"use_pmi\":\"false\",\"approval_type\":0,\"registration_type\":3,\"audio\":\"\",\"auto_recording\":\"\",\"enforce_login\":false,\"enforce_login_domains\":\"\",\"alternative_hosts\":\"\",\"registrants_email_notification\":false}}",
        CURLOPT_HTTPHEADER => array(
            "authorization: Bearer eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiI1NmQ4NmY1YS0wYTQxLTRiMDAtOGM5Zi1mNGRiOTlkM2I2YjcifQ.eyJ2ZXIiOiI2IiwiY2xpZW50SWQiOiJWR0FtY1BGTlJZZWs2d1FOcDNsVlEiLCJjb2RlIjoibTJSS25CMjlzTF9ydHBwbEVxc1NaU21pMEdSdUtzZkV3IiwiaXNzIjoidXJuOnpvb206Y29ubmVjdDpjbGllbnRpZDpWR0FtY1BGTlJZZWs2d1FOcDNsVlEiLCJhdXRoZW50aWNhdGlvbklkIjoiNDJhOWQ5YzhiMTdhZDJhNmZlNGMzMWE2YmY1YWUyYTMiLCJ1c2VySWQiOiJydHBwbEVxc1NaU21pMEdSdUtzZkV3IiwiZ3JvdXBOdW1iZXIiOjAsImF1ZCI6Imh0dHBzOi8vb2F1dGguem9vbS51cyIsImFjY291bnRJZCI6IkNWeG9Oa01NVFd5UDdsTTRhWTRhMEEiLCJuYmYiOjE1ODUzMDk5NzMsImV4cCI6MTU4NTMxMzU3MywidG9rZW5UeXBlIjoiYWNjZXNzX3Rva2VuIiwiaWF0IjoxNTg1MzA5OTczLCJqdGkiOiJhODkyNmFmNy0xMmZmLTQ2ZmEtOWE3Zi1mMmNhNjYwOThkMDEiLCJ0b2xlcmFuY2VJZCI6MH0.yWRBzL30Ji1kHoSs7yYKeh35yltYPoq1mD7R4AEOt4ckgyfvuPTz0E5x8lXAVcPfn_7veC28WhNynJ2UwNZbWg",
            "content-type: application/json"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
        echo $response;
        }
    }

    function listar_solicitarexames($ambulatorio_laudo_id){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");

        $solicitar = $this->app->listarsolicitarexameimpressao($ambulatorio_laudo_id);  
        // var_dump($solicitar); die;
        $obj = new stdClass();
        $obj->status = 200;
        if(count($solicitar) > 0){
            $obj->data = new stdClass();
            $obj->data->data = $solicitar[0]->data_cadastro;
            $obj->data->data = $solicitar[0]->ambulatorio_exame_id;
            $obj->data->link = "/impressaosolicitarexame/" . $solicitar[0]->ambulatorio_exame_id;
        }else{
            $obj->message = 'Sem Solicitações nesse atendimento';
        }
        echo json_encode($obj);
        die;
    }

    function listar_receituario($paciente_id){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");

        $solicitar = $this->app->listarreceitalaudo($paciente_id);  
       
        $obj = new stdClass();
        $obj->status = 200;
        if(count($solicitar) > 0){
            $array = array();
            foreach ($solicitar as $key => $value) {
                # code...
                $obj_array = new stdClass();
                $obj_array->data = $value->data_cadastro;
                $obj_array->medico = $value->medico;
                $obj_array->id = $value->ambulatorio_receituario_id;
                $obj_array->grupo = $value->grupo;
                if($value->especial == 't'){
                    $obj_array->link = "/impressaoreceitaespecial/" . $value->ambulatorio_receituario_id . '/true';
                }else{
                    $obj_array->link = "/impressaoreceita/" . $value->ambulatorio_receituario_id;
                }
                $array[] = $obj_array;
            }
            // var_dump($array); die;
            $obj->data = $array;
           
        }else{
            $obj->message = 'Sem Solicitações nesse atendimento';
        }
        echo json_encode($obj);
        die;
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
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['empresa_m'] = $this->empresa->listarempresamunicipio($empresa_id);
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
            $data['empresapermissoes'] = $this->guia->listarempresapermissoes($empresa_id);
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

            $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'></p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
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

    function impressaoreceitaespecial($ambulatorio_laudo_id,$receituario=NULL) {
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
        $rodape =  $this->load->view('ambulatorio/rodaperelatorioespecial', $data, true);
        
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa_id);
       
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
        
        if ($permissao[0]->a4_receituario_especial == "t") { 
           $html = $this->load->View('ambulatorio/impressaoreceituarioespecialA4', $data,true);
           pdf($html, 'ReceituarioEspecial.pdf', $cabecalho, $rodape, '', 0, 0, 0);  
        }else{ 
           $html = $this->load->View('ambulatorio/impressaoreceituarioespecial', $data,true);
            pdfrespecial($html, 'ReceituarioEspecial.pdf', $cabecalho, $rodape, '', 5, 0, 4,5 ); 
        }
        
       
    }

    function impressaoreceita($ambulatorio_laudo_id) {
        $empresa_id = $this->session->userdata('empresa_id');
        if(!$empresa_id > 0){
            $empresa_id = 1;
        }
        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarreceitaimpressao($ambulatorio_laudo_id);
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes($empresa_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        // var_dump($data['empresa'][0]); die;
        $data['empresa_m'] = $this->empresa->listarempresamunicipio($empresa_id);
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa_id);
        
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
 
         
        $cabecalho = "";
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
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('ambulatorio/impressaoreceituario', $data);
        }
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
        $operador_id = 1;
        $operador_atual = 1;
        @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
        @$cabecalho = str_replace("_usuario_salvar_", $laudo['laudo'][0]->usuario_salvar, $cabecalho);
        $cabecalho = str_replace("_telefone1_", $laudo[0]->telefone, $cabecalho);
        $cabecalho = str_replace("_telefone2_", $laudo[0]->celular, $cabecalho);
        $cabecalho = str_replace("_whatsapp_", $laudo[0]->whatsapp, $cabecalho);
        $cabecalho = str_replace("_prontuario_antigo_", $laudo[0]->prontuario_antigo, $cabecalho);
        $cabecalho = str_replace("_prontuario_", $laudo[0]->paciente_id, $cabecalho);
        $cabecalho = str_replace("_nome_mae_", $laudo[0]->nome_mae, $cabecalho);
        $cabecalho = str_replace("_especialidade_", $laudo[0]->grupo, $cabecalho);
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
     
   function busca_datashistorico($paciente_id) {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        
        $array = array();  
        $hitoricototal = $this->app->listardatashistorico($paciente_id);
//        $hitoricototalantigo = $this->app->listardatashistoricoantigo($paciente_id); 
 
//            foreach($hitoricototal as $item2){   
//                    $hitoricototal[] = json_decode(json_encode(Array('data_cadastro'=>$item2->data_cadastro)));
//                  
//            }  
//        if(count($hitoricototal) > 0){ 
//            foreach ($hitoricototal as $key => $value) {
//                 $array[$key]['data'] = $value->data_cadastro;
//             }
//        }
         
        $obj = new stdClass();
        if(count($hitoricototal) > 0){
            $obj->status = 200;
            $obj->data = $hitoricototal;
        }else{
            $obj->status = 404;
        } 

        echo json_encode($obj); 
    }
    
      function historicoconsulta(){
      header('Access-Control-Allow-Origin: *');
      header("Access-Control-Allow-Headers: content-type");
         
      $_GET['dataescolhida'] = date("Y-m-d", strtotime(str_replace("/", "-", $_GET['dataescolhida'])));
      $data['historico'] = $this->app->listarconsultahistoricodiferenciado($_GET['paciente'],$_GET['dataescolhida']);   
//      $result_antigo = $this->app->listardatashistoricodiaantigo($_GET['paciente'], $_GET['dataescolhida']);  
//        foreach($result_antigo as $item){
//             $existe = false;
//            foreach($data['historico'] as $item2){ 
//                $data['historico'][] =  $item2; 
//            }
//            if(!$existe){
//                $data['historico'][] =  $item2; 
//            }  
//             
//        }   
        $obj = new stdClass();
        if(count($data['historico']) > 0){  
              $array = array(); 
            foreach($data['historico'] as $key => $value){  
                  $data['receita'] = $this->app->listarreceitaprocedimentos($value->ambulatorio_laudo_id);  
                 // $data['prescricaos'] = $this->historicoprescricaoMemed($value->ambulatorio_laudo_id);  
                  
                  $array[$key]['ambulatorio_laudo_id']  = $value->ambulatorio_laudo_id;
                  $array[$key]['data_cadastro']  = $value->data_cadastro;
                  $array[$key]['situacao']  = $value->situacao;
                  $array[$key]['medico']  = $value->medico;
                  $array[$key]['texto']  = $value->texto;
                  $array[$key]['link']  = base_url() .'appPacienteAPI/impressaolaudo/'.$value->ambulatorio_laudo_id;
                  //$array[$key]['telemedicina']  = 'https://meet.jit.si/'.md5(md5($value->ambulatorio_laudo_id, PASSWORD_DEFAULT)."STGSAUDEAPITELEMEDICINA123".md5($value->paciente_id));
                  $array[$key]['procedimento_tuss_id']  = $value->procedimento_tuss_id;
                  $array[$key]['procedimento']  = $value->procedimento;
                  $array[$key]['medico_parecer1']  = $value->medico_parecer1;
                  $array[$key]['agenda_exames_id']  = $value->agenda_exames_id;
                  $array[$key]['convenio']  = $value->convenio;
                  $array[$key]['agenda_exames_nome_id']  = $value->agenda_exames_nome_id;
                  $array[$key]['paciente']  = $value->paciente;
                  $array[$key]['template_obj']  = $value->template_obj;
                  $array[$key]['exames_id']  = $value->exames_id;
                  $array[$key]['nascimento']  = $value->nascimento; 
                  
                  if(count($data['receita']) > 0){
                           foreach($data['receita'] as $item){ 
                               if($item->especial == 't'){
                                    $array[$key]['receituario'][]   =  Array('link' =>   base_url() ."appPacienteAPI/impressaoreceitaespecial/" . $item->ambulatorio_receituario_id . '/true');
                                 }else{
                                    $array[$key]['receituario'][]   =   Array('link' =>  base_url() ."appPacienteAPI/impressaoreceitaapp/" . $item->ambulatorio_receituario_id); 
                                 }
                           }
                  }else{
                          $array[$key]['receituario'][]   =   Array('mensagem' => "Nenhum receituário encontrada"); 
                  }
                  
                   
                     
            }  
            $obj->status = 200;
            $obj->data = $array;
        }else{
            $obj->status = 404;
        }  
           
        echo json_encode($obj); 
     
    }
  
    
     function historicoexames(){
      header('Access-Control-Allow-Origin: *');
      header("Access-Control-Allow-Headers: content-type");
      
      $_GET['dataescolhida'] = date("Y-m-d", strtotime(str_replace("/", "-", $_GET['dataescolhida'])));
      $data['historicoexame'] = $this->app->listarexamehistoricodiferenciado($_GET['paciente'], $_GET['dataescolhida']);
//      $result_antigo = $this->app->listardatashistoricodiaantigo($_GET['paciente'], $_GET['dataescolhida'],'EXAME');  
      
//     foreach($data['historicoexame'] as $item2){ 
//                    $data['historicoexame'][] =  $item2; 
//      } 
       
        $obj = new stdClass();
        if(count($data['historicoexame']) > 0){  
              $array = array(); 
            foreach($data['historicoexame'] as $key => $value){  
                  $data['receita'] = $this->app->listarreceitaprocedimentos($value->ambulatorio_laudo_id);  
//                  $data['prescricaos'] = $this->historicoprescricaoMemed($value->ambulatorio_laudo_id);  
                  
                  $array[$key]['ambulatorio_laudo_id']  = $value->ambulatorio_laudo_id;
                  $array[$key]['data_cadastro']  = $value->data_cadastro;
                  $array[$key]['situacao']  = $value->situacao;
                  $array[$key]['medico']  = $value->medico;
                  $array[$key]['texto']  = $value->texto;
                  $array[$key]['link']  = base_url() .'appPacienteAPI/impressaolaudo/'.$value->ambulatorio_laudo_id;
                 // $array[$key]['telemedicina']  = 'https://meet.jit.si/'.md5(md5($value->ambulatorio_laudo_id, PASSWORD_DEFAULT)."STGSAUDEAPITELEMEDICINA123".md5($value->paciente_id));
                  $array[$key]['procedimento_tuss_id']  = $value->procedimento_tuss_id;
                  $array[$key]['procedimento']  = $value->procedimento;
                  $array[$key]['medico_parecer1']  = $value->medico_parecer1;
                  $array[$key]['agenda_exames_id']  = $value->agenda_exames_id;
//                  $array[$key]['convenio']  = $value->convenio;
                  $array[$key]['agenda_exames_nome_id']  = $value->agenda_exames_nome_id;
//                  $array[$key]['paciente']  = $value->paciente; 
                  $array[$key]['exames_id']  = $value->exames_id; 
                  
                     if(count($data['receita']) > 0){
                        foreach($data['receita'] as $item){ 
                           if($item->especial == 't'){
                                $array[$key]['receituario'][]   =  Array('link' =>   base_url() ."appPacienteAPI/impressaoreceitaespecial/" . $item->ambulatorio_receituario_id . '/true');
                             }else{
                                $array[$key]['receituario'][]   =   Array('link' =>  base_url() ."appPacienteAPI/impressaoreceitaapp/" . $item->ambulatorio_receituario_id); 
                             }
                        }
                     }else{ 
                         $array[$key]['receituario'][]   =   Array('mensagem' => "Nenhum receituário encontrada"); 
                     }  
//                   if(count($data['prescricaos']) > 0){
//                    foreach($data['prescricaos'] as $item){  
//                          if(isset($item['errors']) && count($item['errors']) > 0){
//                              continue;
//                          }  
//                          $array[$key]['prescricoes'][]   =  Array('link' =>  $item['data'][0]['attributes']['link']); 
//                    }  
//                  }else{
//                        $array[$key]['prescricoes'][]   =  Array('mensagem' =>  "Nenhuma prescrição encontrada");  
//                  } 
                  
            }
            
            $obj->status = 200;
            $obj->data = $array;
        }else{
            $obj->status = 404;
        }  
        echo json_encode($obj); 
    }
    
    
      function historicoespecialidades(){
       header('Access-Control-Allow-Origin: *');
       header("Access-Control-Allow-Headers: content-type");
        
      $_GET['dataescolhida'] = date("Y-m-d", strtotime(str_replace("/", "-", $_GET['dataescolhida'])));
      $data['historicoespecialidade'] = $this->app->listarespecialidadehistoricodiferenciado($_GET['paciente'],$_GET['dataescolhida']);    
//      $result_antigo = $this->app->listardatashistoricodiaantigo($_GET['paciente'], $_GET['dataescolhida'],'ESPECIALIDADE');  
//        foreach($result_antigo as $item){
//             $existe = false;
//            foreach($data['historicoespecialidade'] as $item2){ 
//                  if($item->ambulatorio_laudo_antigo_id == $item2->ambulatorio_laudo_id){
//                    $existe = true;
//                  }
//            }
//            if(!$existe){
//                $data['historicoespecialidade'][] =  $item; 
//            }       
//        }  
        $obj = new stdClass();
        if(count($data['historicoespecialidade']) > 0){
            
            
               $array = array(); 
            foreach($data['historicoespecialidade'] as $key => $value){  
                  $data['receita'] = $this->app->listarreceitaprocedimentos($value->ambulatorio_laudo_id);
               //   $data['prescricaos'] = $this->historicoprescricaoMemed($value->ambulatorio_laudo_id);  
                  
                  $array[$key]['ambulatorio_laudo_id']  = $value->ambulatorio_laudo_id;
                  $array[$key]['data_cadastro']  = $value->data_cadastro;
                  $array[$key]['situacao']  = $value->situacao;
                  $array[$key]['medico']  = $value->medico;
                  $array[$key]['texto']  = $value->texto;
                  $array[$key]['link']  = base_url() .'appPacienteAPI/impressaolaudo/'.$value->ambulatorio_laudo_id;
                  $array[$key]['telemedicina']  = 'https://meet.jit.si/'.md5(md5($value->ambulatorio_laudo_id, PASSWORD_DEFAULT)."STGSAUDEAPITELEMEDICINA123".md5($value->paciente_id));
                  $array[$key]['procedimento_tuss_id']  = $value->procedimento_tuss_id;
                  $array[$key]['procedimento']  = $value->procedimento;
                  $array[$key]['medico_parecer1']  = $value->medico_parecer1;
                  $array[$key]['agenda_exames_id']  = $value->agenda_exames_id;
//                  $array[$key]['convenio']  = $value->convenio;
                  $array[$key]['agenda_exames_nome_id']  = $value->agenda_exames_nome_id;
//                  $array[$key]['paciente']  = $value->paciente; 
                  $array[$key]['exames_id']  = $value->exames_id; 
                    if(count($data['receita']) > 0){
                        foreach($data['receita'] as $item){ 
                           if($item->especial == 't'){
                                $array[$key]['receituario'][]   =  Array('link' =>   base_url() ."appPacienteAPI/impressaoreceitaatendimento/" . $item->ambulatorio_receituario_id . '/true');
                             }else{
                                $array[$key]['receituario'][]   =   Array('link' =>  base_url() ."appPacienteAPI/impressaoreceitaapp/" . $item->ambulatorio_receituario_id); 
                             }
                       }
                    }else{
                         $array[$key]['receituario'][]   =   Array('mensagem' => "Nenhum receituário encontrado");  
                    }
  
            }  
            $obj->status = 200;
            $obj->data = $array;
        }else{
            $obj->status = 404;
        }  
        echo json_encode($obj); 
    }
    
      function historicoreceituario(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        
        $_GET['dataescolhida'] = date("Y-m-d", strtotime(str_replace("/", "-", $_GET['dataescolhida'])));
        $data['receita'] = $this->app->listarreceita($_GET['paciente'],$_GET['dataescolhida']); 
        $obj = new stdClass(); 
        $obj->status = 200;
        if(count($data['receita']) > 0){
            $array = array();
            foreach ($data['receita'] as $key => $value) {
                # code...
                $obj_array = new stdClass();
                $obj_array->data_cadastro = $value->data_cadastro;
                $obj_array->medico = $value->medico;
                $obj_array->ambulatorio_receituario_id = $value->ambulatorio_receituario_id;
                $obj_array->grupo = $value->grupo;
                if($value->especial == 't'){
                     base_url() . "appPacienteAPI/reset_senha/{$resposta[0]}";
                    $obj_array->link =  base_url() ."appPacienteAPI/impressaoreceitaespecial/" . $value->ambulatorio_receituario_id . '/true';
                }else{
                    $obj_array->link = base_url() ."appPacienteAPI/impressaoreceita/" . $value->ambulatorio_receituario_id;
                }
                $array[] = $obj_array;
            }
            // var_dump($array); die;
            $obj->status = 200;
            $obj->data = $array; 
        }else{
            $obj->message = 'Sem Solicitações nesse atendimento';
        } 
            
        echo json_encode($obj); 
    }
    
     function busca_carterinha_virtual2() {
        // $json_post = json_decode(file_get_contents("php://input"));
        // echo '<pre>';
        // var_dump($json_post); 
        // die;
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $paciente_id = $_GET['paciente_id'];

        // verificarcarenciaweb
        $flags = $this->guia->listarempresapermissoes(1);
        $paciente_inf = $this->guia->pacienteAntigoId2($paciente_id);
        // var_dump($paciente_inf); die;
        $convenio_retorno = $this->guia->enderecoSistemaFidelidadeExis();
        $endereco = $convenio_retorno[0]->fidelidade_endereco_ip;
        $paciente_antigo_id = (int) $paciente_inf[0]->prontuario_antigo;
        $cpf = $paciente_inf[0]->cpf;
        $obj = new stdClass();
        if ($endereco != '') {
            if ($flags[0]->fidelidade_paciente_antigo == 'f') {
                $paciente_antigo_id = 0;
            }
            // Dessa forma, se a flag estiver desativada o sistema manda zero pro fidelidade
            // daí o fidelidade não pesquisa pelo ID e sim pelo CPF.
            //    echo "<pre>";
            $return = file_get_contents("http://{$endereco}/autocomplete/impressaoCarteiraWeb?paciente_id=$paciente_antigo_id&cpf=$cpf");
            $resposta = $return;
            // var_dump($resposta);
            // die;
            // echo json_encode($resposta);
            
            if($resposta != NULL){
                $obj->status = 200;
                $obj->data = $resposta;
            }else{
                $obj->status = 404;
                $obj->data = [];
            }
           
        } else {
            $obj->status = 404;
            $obj->mensagem = 'Erro ao configurar Fidelidade e Clinicas';
        }
        echo json_encode($obj); 
    }
     
    
    
     function impressaolaudo($ambulatorio_laudo_id, $exame_id=null) {
        $this->load->plugin('mpdf');
        $empresa_id = 1;

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
        @$data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        @$sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
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

            
            if(@$empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

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

                
                if (@$data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
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
 function impressaoreceitaapp($ambulatorio_laudo_id) {
        $this->load->plugin('mpdf');  
      
        $data['laudo'] = $this->laudo->listarreceitaimpressao($ambulatorio_laudo_id);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao(1);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo(1);
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


//        if ($data['laudo'][0]->assinatura == 't') {
//            if (isset($data['laudo'][0]->medico_parecer1)) {
//                $this->load->helper('directory');
//                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
//                foreach ($arquivo_pasta as $value) {
//                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
//                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
//                    }
//                }
//            }
//        } elseif ($data['laudo'][0]->carimbo == 't') {
//            $carimbo = $data['laudo'][0]->medico_carimbo;
//        } else {
//            $carimbo = "";
//        }
//        $data['assinatura'] = $carimbo;
//
////        var_dump($carimbo);die;
//        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");
//
//        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
//        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
//        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

//        $nomemes = $meses[$mes];



//        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;

  
//            if ($data['empresa'][0]->cabecalho_config == 't') {
//                if ($data['cabecalhomedico'][0]->cabecalho != '') {
//                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
//                } else {
//                    $cabecalho = "$cabecalho_config";
//                }
////                $cabecalho = $cabecalho_config;
//            } else {
//                $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
//            }
//
//            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
////                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
//            }
 $cabecalho = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
//            if ($data['empresa'][0]->rodape_config == 't') {
//                if ($data['cabecalhomedico'][0]->rodape != '') {
//                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
//                }
//                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
//                $rodape = $texto_rodape . $rodape_config;
//            } else {
//                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
//            }
      $rodape = "<img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'>";

            $filename = "laudo.pdf";
//            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
//            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            if (!preg_match('/\_paciente_/', $data['laudo'][0]->texto)) {
                $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            } else {
                $html = $this->load->view('ambulatorio/impressaoreceituarioconfiguravel', $data, true);
            } 
            
            pdf($html, $filename, $cabecalho, $rodape);
       
 
  
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
     
    
    function listar_agendado2() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));

         $paciente_antigo = $_GET['paciente_id'];  
         $paciente_inf = $this->guia->pacienteAntigoId2($paciente_antigo);
         $paciente_id = (int) $paciente_inf[0]->paciente_id;
         
        $result = $this->app->listaragendatotalpacientegeral($paciente_id);
        
        $obj = new stdClass();
        if(count($result) > 0){
            $obj->status = 200;
            $obj->data = $result;
        }else{
            $obj->status = 404;
            $obj->data = [];
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }
    
    
     function gravar_agendamento2(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        
        $paciente_antigo = $json_post->paciente_id;
        $agenda_exames_id = $json_post->agenda_exames_id;
        $procedimento_id = $json_post->procedimento_id;
        $empresa_id = $json_post->empresa_id;
        
        $paciente_inf = $this->guia->pacienteAntigoId2($paciente_antigo);
        $paciente_id = (int) $paciente_inf[0]->paciente_id;

        $retorno = $this->app->gravarAgendamento($empresa_id, $paciente_id, $agenda_exames_id, $procedimento_id);
        $obj = new stdClass();
        if($retorno != false){
            $obj->status = 200;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;
        echo json_encode($obj); 
    }

    
    
}
