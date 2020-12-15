<?php

class AppAPI extends Controller {

    function AppAPI() {

        parent::Controller();
        $this->load->model('login_model', 'login_m');
        $this->load->model('app_model', 'app');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/laudo_model', 'laudo');
        $this->load->model('seguranca/operador_model', 'operador_m');
    }

    function index(){
        echo json_encode('WebService');
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

        $resposta = $this->login_m->autenticarweb($usuario, $senha, $empresa);
        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->id = $resposta[0]->operador_id;
        }else{
            $obj->status = 404;
            $obj->id = 0;
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

    function listar_procedimentos(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        //$json_post = json_decode(file_get_contents("php://input"));
        //var_dump($_GET); 
        //die;
        $resposta = $this->app->listarprocedimentos();        
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

    function listar_agente(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        //$json_post = json_decode(file_get_contents("php://input"));
        //var_dump($_GET); 
        //die;
        $resposta = $this->app->listargrupoconvenios();        
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

    function paciente_historico(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $agenda_exames_id = $_GET['id'];
        $paciente_id = $_GET['paciente_id'];
        $medico_id = $_GET['medico_id'];
        $resposta = $this->app->listarhistoricoAPP($agenda_exames_id, $paciente_id, $medico_id);        
        // echo '<pre>';
        
        foreach ($resposta as $key => $value) {
            $resposta[$key]->descricao = str_replace('&nbsp;', '', trim(strip_tags($value->descricao)));
        }
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

    function adicionar_adendo(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $laudo_id = $json_post->laudo_id;
        $descricao = $json_post->descricao;
        $image = $json_post->image;
        $medico_id = $json_post->medico_id;
        
        $base64 = $image;
        $imagem_img = "<img src='$base64'>";
        $texto_add = $descricao . ' ' . $imagem_img;
            
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $resposta = $this->app->adicionaradendo($laudo_id, $texto_add, $medico_id);    

        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
	die;
    }

    function solicitar_ajuste(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        
        $medico_id = $_GET['medico_id'];
        $data_ajustada = $_GET['data_solicitada'];
        $grupo_solicitado = $_GET['grupo_solicitado'];
        $medico_solicitado = $_GET['medico_solicitado'];
        $hora_inicio = $_GET['hora_inicio_solicitada'];
        $hora_fim = $_GET['hora_fim_solicitada'];
        $agenda_exames_id = $_GET['agenda_exames_id'];
        
       
        $resposta = $this->app->solicitarAjuste($medico_id, $data_ajustada, $hora_inicio, $hora_fim, $medico_solicitado, $grupo_solicitado, $agenda_exames_id);    
        
        $obj = new stdClass();
        
        if(count($resposta) > 0 && $resposta != false){
            $obj->status = 200;
            if($medico_solicitado > 0){
                $tipo = 1;
                $medico_id = $medico_solicitado;
            }else{
                $tipo = 0;
                $medico_id = null;
            }
            
            $this->enviarNotificacao($medico_id, $tipo, 'Há uma nova solicitação de ajuste. Acesse o app para ver os detalhes', $grupo_solicitado);
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function confirmar_ajuste(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        
        $solicitacao_id = $_GET['ajuste_id'];
        $medico_id = $_GET['medico_id'];
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $resposta = $this->app->confirmarAjuste($solicitacao_id, $medico_id);    
        
        $obj = new stdClass();
        if(count($resposta) > 0 && $resposta != false){
            $obj->status = 200;
            $this->enviarNotificacao($resposta[0]->medico_solicitante_id, 1, 'Seu ajuste foi confirmado! Acesse o app para ver os detalhes');
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function gerarArrayRecebimento($relatorio, $array = array()){
        $dados = $array;
        $perc = 0;
        $percpromotor = 0;
        $valor_total = 0;
        $valor_credito = 0;
        $descontoTotal = 0;
        $empresa_permissao = $this->app->listarempresapermissoes(1);
        foreach ($relatorio as $key => $item) {
            $obj_medico = new stdClass();
            $descontoAtual = 0;
                        
            if ($empresa_permissao[0]->faturamento_novo == 't') {
                $descontoForma = $this->guia->listardescontoTotal($item->agenda_exames_id);
                // var_dump($descontoForma);
                if (count($descontoForma) > 0) {
                    $descontoTotal += $descontoForma[0]->desconto;
                    $descontoAtual = $descontoForma[0]->desconto;
                }
            }
            if ($empresa_permissao[0]->faturamento_novo == 't') {
                //                            $valor_total = $item->valor_pago;
                $valor_total = ($item->valor * $item->quantidade) - @$descontoAtual;                             
            } else {
                $valor_total = $item->valor_total;
            }
         
            if ($item->percentual_promotor == "t") {
                $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

                $percpromotor = $valor_total * ($valorpercentualpromotor / 100);
            } else {
                $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;
                $percpromotor = $valorpercentualpromotor * $item->quantidade;
            }

            if ($item->percentual_medico == "t") {
                $valorpercentualmedico = $item->valor_medico/* - ((float) $item->valor_medico * ((float) $item->taxa_administracao / 100)) */;

                $perc = $valor_total * ($valorpercentualmedico / 100);
            } else {
                $valorpercentualmedico = $item->valor_medico/* - ((float) $item->valor_medico * ((float) $item->taxa_administracao / 100)) */;
                $perc = $valorpercentualmedico * $item->quantidade;
            }
            if ($item->valor_promotor != null && $empresa_permissao[0]->promotor_medico == 't') {
                $perc = $perc - $percpromotor;
            }

            if ($perc > 0) {
                $obj_medico->convenio = $item->convenio;
                $obj_medico->procedimento = $item->procedimento;
                $obj_medico->grupo = $item->grupo;
                $obj_medico->paciente = $item->paciente;
                $obj_medico->quantidade = $item->quantidade;
                $obj_medico->valor_medico = $perc;
                $obj_medico->data_atendimento = $item->data;
                $obj_medico->data_recebimento = $item->data_producao;

                array_push($dados, $obj_medico);
            }

        }
        return $dados;
    }

    function listar_recebimentos(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        
        
        $relatoriogeral = $this->app->relatorioRecebimentos($json_post);
        $relatorioImp = $this->app->relatorioRecebimentosImp($json_post);
        // echo '<pre>';
        // var_dump($relatoriogeral); 
        // die;
        $dados = $this->gerarArrayRecebimento($relatoriogeral);
        $dadosImp = $this->gerarArrayRecebimento($relatorioImp, $dados);

        $obj = new stdClass();
        if(count($dadosImp) > 0 && $dadosImp != false){
            $obj->status = 200;
            $obj->data = $dadosImp;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function listar_recebimentos_grupo(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        
        
        $relatoriogeral = $this->app->relatorioRecebimentos($json_post);
        $relatorioImp = $this->app->relatorioRecebimentosImp($json_post);
        // echo '<pre>';
        // var_dump($relatoriogeral); 
        // die;
        $dados = $this->gerarArrayRecebimento($relatoriogeral);
        $dadosImp = $this->gerarArrayRecebimento($relatorioImp, $dados);

        $arrayFinal = array();
        $dataAtual = '';
        $convenioAtual = '';
        foreach ($dadosImp as $key => $value) {
            if(!isset($arrayFinal[$value->grupo . $value->convenio . $value->data_atendimento])){
                $arrayFinal[$value->grupo . $value->convenio . $value->data_atendimento] = new stdClass();
                $arrayFinal[$value->grupo . $value->convenio . $value->data_atendimento]->quantidade = 0;
                $arrayFinal[$value->grupo . $value->convenio . $value->data_atendimento]->valor_medico = 0;

            }
            $objAtual = $arrayFinal[$value->grupo . $value->convenio . $value->data_atendimento];
            $objAtual->convenio = $value->convenio;
            $objAtual->grupo = $value->grupo;
            $objAtual->quantidade += $value->quantidade;
            $objAtual->valor_medico += $value->valor_medico;
            $objAtual->data_atendimento = $value->data_atendimento;
            $objAtual->data_recebimento = $value->data_recebimento;

            # code...
        }
        // echo '<pre>';
        // var_dump($arrayFinal); die;
       
        $obj = new stdClass();
        if(count($arrayFinal) > 0 && $arrayFinal != false){
            $obj->status = 200;
            $obj->data = $arrayFinal;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }
     
    function gravarmensagemchat(){
            $medico_id = $_POST['para_id'];
           if ($_POST['grupos'][0] != "") { 
                $GRUPO = $_POST['grupos'][0];               
                $res =  $this->app->buscarOperadores();                   
                foreach($res as $value){
                         if (@$value->grupo_agenda != '') {
                             $gruposExi = json_decode(@$value->grupo_agenda);
                         } else {
                             $gruposExi = array();
                         } 
                    if(@in_array($GRUPO, $gruposExi)){                          
                         $this->enviarNotificacao($value->operador_id, 1, 'Chegou uma nova mensagem');
                         $this->app->gravarmensagemchat($value->operador_id);

                    }  
                } 
               }else{
                   
                  $this->app->gravarmensagemchat($medico_id);  
                  $this->enviarNotificacao($medico_id, 1, 'Chegou uma nova mensagem');
                   
               }
            
    }

    function enviarNotificacao($medico_id, $tipo , $mensagem, $grupo = ''){
        $grupo = '';
        $resposta = $this->app->buscarHashDispositivo($medico_id, $tipo, $grupo);    
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
        
        $medico_id = $_GET['ID'];
        $hash = $_GET['indentificacao_dispositivo'];
        
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $resposta = $this->app->registrarDispositivo($medico_id, $hash);    

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

    function listar_empresas(){
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

    function listar_convenios(){
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

    function listar_flags(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $empresa_id = $_GET['empresa_id'];
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $resposta = $this->app->listarFlags($empresa_id);    

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
        $empresa_id = $_GET['empresa_id'];
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $resposta = $this->app->listarBotoes($empresa_id);    

        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->status = 200;
            $obj->data = $resposta[0];
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function listar_medicos(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $grupo = $_GET['grupo'];
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

    function listar_solicitacoes_ajustes(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        $medico_id = $_GET['medico_id'];
        // echo '<pre>';
        // var_dump($texto_add); 
        // die;
        $resposta = $this->app->listarSolicitacoesAjustes($medico_id);    

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

    function conta(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        
        $obj = new stdClass();
        // if(count($resposta) > 0){
        //     $obj->status = 200;
        // }else{
        $obj->status = 404;
        // }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }

    function upload_conta(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        // $json_post = json_decode(file_get_contents("php://input"));
        
        $_FILES['userfile'] = $_FILES['file'];
        $file = $_FILES['file'];
        $nome = $_FILES['file']['name'];
        $descricao = $_POST['descricao'];
        $foto = $_POST['foto']; // Base 64
        $medico_id = $_POST['medico_id']; // Base 64
        if (!is_dir("./upload/uploadconta")) {
            mkdir("./upload/uploadconta");
            $destino = "./upload/uploadconta";
            chmod($destino, 0777);
        }

        if (!is_dir("./upload/uploadconta/$medico_id")) {
            mkdir("./upload/uploadconta/$medico_id");
            $destino = "./upload/uploadconta/$medico_id";
            chmod($destino, 0777);
        }

        if(count($file) > 0){
            $config['upload_path'] = "./upload/uploadconta/$medico_id";
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar';
            $config['max_size'] = '0';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = FALSE;
            $config['name'] = FALSE;
            $this->load->library('upload', $config);
    
            if (!$this->upload->do_upload()) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $error = null;
                $data = array('upload_data' => $this->upload->data());
                $data['mensagem'] = 'Sucesso ao adcionar Logo.';
            }
        }
        else{
            $error = null;
        }
        // var_dump($data); 
        // die;
        
        $obj = new stdClass();
        if(!count($error) > 0){
            $obj->status = 200;
            $obj->errors = array();
        }
        else{
            $obj->status = 404;
            $obj->errors = $error;
        }
        
        echo json_encode($obj); 
        die;
    }

    function salva_precadastro(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $_FILES['userfile'] = $_FILES['file'];
        $file = $_FILES['file'];
        $nome_arquivo = $_FILES['file']['name'];

        $medico_id = $this->app->gravarmedicoprecadastro();   
        // var_dump($file); 
        // die;

        if (!is_dir("./upload/curriculumprecadastro")) {
            mkdir("./upload/curriculumprecadastro");
            $destino = "./upload/curriculumprecadastro";
            chmod($destino, 0777);
        }

        if (!is_dir("./upload/curriculumprecadastro/$medico_id")) {
            mkdir("./upload/curriculumprecadastro/$medico_id");
            $destino = "./upload/curriculumprecadastro/$medico_id";
            chmod($destino, 0777);
        }

        if(count($file) > 0){
            $config['upload_path'] = "./upload/curriculumprecadastro/$medico_id";
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar';
            $config['max_size'] = '0';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = FALSE;
            $config['name'] = FALSE;
            $this->load->library('upload', $config);
    
            if (!$this->upload->do_upload()) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $error = null;
                $data = array('upload_data' => $this->upload->data());
                $data['mensagem'] = 'Sucesso ao fazer upload.';
            }
        }else{
            $error = null;
        }
        
        $obj = new stdClass();
        if(!count($error) > 0){
            $obj->status = 200;
            $obj->errors = array();
        }else{
            $obj->status = 404;
            $obj->errors = $error;
        }
        // echo '<pre>';
        // var_dump($error); 
        // die;

        echo json_encode($obj); 
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

    function impressao_laudo($ambulatorio_laudo_id) {
        $this->load->plugin('mpdf');
        $empresa_id = 1;
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $exame_id = $data['laudo'][0]->exame_id;
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
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes(1);
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        // var_dump($data['cabecalho']); die;
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes(1);
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
        // $sem_margins = 't';
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');
        // var_dump($data['empresa']); die;
         
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
//            print_r($cabecalho);
//              die('morreu'); 
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
    
    
    function envia_mensagem(){
        header('Access-Control-Allow-Origin: *');      
        header("Access-Control-Allow-Headers: content-type"); 
        $result =  $this->app->envia_mensagem();
    }
       
    function listar_mensagens_chat(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $result =  $this->app->listar_mensagens_chat();
    }
    
    function listarecebiveis(){
        $data_inicio = "2020-09-01";
        $data_inicio = "2020-09-30";
        
       $res =  $this->app->listarrelatoriomedico(); 
       
       $recebiveis = Array();
       $recebidos = Array();
       foreach($res as $item){ 
           if($item->producao_paga == "t"){
             $recebidos[] =  $item; 
           }  
            $recebiveis[] = $item;
       }
       
       echo "<pre>";
       print_r($recebiveis);
    
    }
    
    function listar_recebiveis(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die;
        
        
        $relatoriogeral = $this->app->relatorioRecebimentos($json_post);
        $relatorioImp = $this->app->relatorioRecebimentosImp($json_post);
        // echo '<pre>';
        // var_dump($relatoriogeral); 
        // die;
        $dados = $this->gerarArrayRecebimento($relatoriogeral);
        $dadosImp = $this->gerarArrayRecebimento($relatorioImp, $dados);

        $obj = new stdClass();
        if(count($dadosImp) > 0 && $dadosImp != false){
            $obj->status = 200;
            $obj->data = $dadosImp;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }
    
    
    function listar_recebidos(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post);  
        
        
        $relatoriogeral = $this->app->relatorioRecebimentos2($json_post);
        $relatorioImp = $this->app->relatorioRecebimentosImp($json_post);
        // echo '<pre>';
        // var_dump($relatoriogeral); 
        // die;
        $dados = $this->gerarArrayRecebimento($relatoriogeral);
        $dadosImp = $this->gerarArrayRecebimento($relatorioImp, $dados);

        $obj = new stdClass();
        if(count($dadosImp) > 0 && $dadosImp != false){
            $obj->status = 200;
            $obj->data = $dadosImp;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }
    
    
    
     function listar_glosados(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // var_dump($json_post); 
        // die; 
        
        $relatoriogeral = $this->app->relatorioRecebimentos3($json_post);
        $relatorioImp = Array();
        // echo '<pre>';
        // var_dump($relatoriogeral); 
        // die;
        $dados = $this->gerarArrayRecebimento($relatoriogeral);
        $dadosImp = $this->gerarArrayRecebimento($relatorioImp, $dados);

        $obj = new stdClass();
        if(count($dadosImp) > 0 && $dadosImp != false){
            $obj->status = 200;
            $obj->data = $dadosImp;
        }else{
            $obj->status = 404;
        }
        // echo '<pre>';
        // var_dump($obj); 
        // die;

        echo json_encode($obj); 
    }
     
   
}
