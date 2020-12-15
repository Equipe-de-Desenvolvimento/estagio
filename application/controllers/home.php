<?php

require_once APPPATH . 'controllers/base/BaseController.php';

class Home extends BaseController {

    function Home() {
        parent::Controller();
        $this->load->library('mensagem');
        $this->load->model('ambulatorio/agenda_model', 'agenda');
        $this->load->model('ambulatorio/agenda_model', 'agenda');
    }

    function index($mensagem = null) {
        if ($mensagem != null) {
            $data['mensagem'] = $this->mensagem->getMensagem($mensagem);
        } else {
            $data['mensagem'] = null;
        }
//  ENVIAR MENSAGENS PARA WHATSAPP    
//    listando as permissoes 
        // Tem uma outra função que manda apenas um agenda exames em todo encaixa em: exametemp.php (enviarWhatsappUnitario)
        $pp['retorno'] = $this->agenda->listarpermissoes();
        if (@$pp['retorno'][0]->servicowhatsapp == 't') {
//       BLOCO ONDE ACONTECE O ENVIO DOS EXAMES DE UM BANCO PARAR OUTRO
            $quantidade['quantidade'] = $this->agenda->listarquantidadeatual(); //quantidade atual de envios da clinica
            $contador_atual = $quantidade['quantidade'][0]->contador;
            $pacote['pacote'] = $this->agenda->listarpacoteempresa(); //o pacote da clinica
            $qtdmax = $pacote['pacote'][0]->pacote;
            $maximo = $qtdmax - $contador_atual; //o maximo de registros que o select() vai buscar na funcao abaixo, ou seja, o total do pacote menos o contador atual da clinica 
            $data = $this->agenda->getAgendaexames($maximo);
//            print_r($data);
            $quantoveio = count($data); //essa quantidade é a que veio no select()  acima
            $this->agenda->atualizarcontador($quantoveio, $contador_atual);  //atualiza o contador da clinica que está logada no sistema
            if (!($quantidade['quantidade'][0]->contador >= $pacote['pacote'][0]->pacote)) {
                 $qtd = count($data); //isso é pra saber quantas vezes o for() vai rodar de acordo com o total de registro que veio da tb_agenda_exames  
                $endereco_externo['endereco_externo'] = $this->agenda->listarendereco_externo(); //pegando o endereço externo
                $endereco_externo = $endereco_externo['endereco_externo'][0]->endereco_externo;
                for ($i = 0; $i < $qtd; $i++) {
                    $url = $endereco_externo . "welcome/gravartesteteste"; //essa url é do computar 'BOT' onde vai está rodando o macro.
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_HEADER, 0);
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data[$i]);
                    curl_exec($curl);
                    curl_close($curl);
                }
            }
////       BLOCO ONDE ACONTECE O ENVIO DOS EXAMES DE UM BANCO PARA OUTRO        
        } else {
         
        }

        //  -----------------------------------------

        // $data = array(
        //     "Username" => "wrcneuro@gmail.com",
        //     "Password" => "215687@w#e",
        // );

        //  $data_string = json_encode($data);
        // // print_r($data_string);
        // // die;

        // $url = 'http://llapi.leadlovers.com/webapi/Token?token=273FFCDFA47A4D39BDFA3DDEFCCC9F36';
        // $curl = curl_init();
        // curl_setopt_array($curl, [
        //     CURLOPT_RETURNTRANSFER => 1,
        //     CURLOPT_URL => $url,
        //     CURLOPT_POST => 1,
        //     CURLOPT_ENCODING => '', 
        //     CURLOPT_MAXREDIRS => 10, 
        //     CURLOPT_TIMEOUT => 30, 
        //     CURLOPT_POSTFIELDS => $data_string,
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json',
        //         'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1bmlxdWVfbmFtZSI6IldlYkFwaSIsInN1YiI6IldlYkFwaSIsInJvbGUiOlsicmVhZCIsIndyaXRlIl0sImlzcyI6Imh0dHA6Ly93ZWJhcGlsbC5henVyZXdlYnNpdGVzLm5ldCIsImF1ZCI6IjFhOTE4YzA3NmE1YjQwN2Q5MmJkMjQ0YTUyYjZmYjc0IiwiZXhwIjoxNjA1NDQxMzM4LCJuYmYiOjE0NzU4NDEzMzh9.YIIpOycEAVr_xrJPLlEgZ4628pLt8hvWTCtjqPTaWMs')

        // ]);

        // $response = curl_exec($curl);

        // curl_close($curl);
        // // print_r(json_decode($response));



        // $url = 'http://llapi.leadlovers.com/webapi'; 
        // $curl = curl_init(); 

        // $data = array(
        //     "Username" => "wrcneuro@gmail.com",
        //     "Password" => "215687@w#e",
        // );

        //  $data_string = json_encode($data);
        //  print_r($data_string);
        //  die;
        
        // curl_setopt_array($curl, array( 
        // CURLOPT_URL => $url . '/machines' . '?token=' . '273FFCDFA47A4D39BDFA3DDEFCCC9F36', 
        // CURLOPT_RETURNTRANSFER => true, 
        // CURLOPT_ENCODING => '', 
        // CURLOPT_MAXREDIRS => 10, 
        // CURLOPT_TIMEOUT => 30, 
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, 
        // CURLOPT_CUSTOMREQUEST => 'GET', 
        // CURLOPT_HTTPHEADER => array( 
        //     'accept: application/json', 
        //     'authorization: bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1bmlxdWVfbmFtZSI6IldlYkFwaSIsInN1YiI6IldlYkFwaSIsInJvbGUiOlsicmVhZCIsIndyaXRlIl0sImlzcyI6Imh0dHA6Ly93ZWJhcGlsbC5henVyZXdlYnNpdGVzLm5ldCIsImF1ZCI6IjFhOTE4YzA3NmE1YjQwN2Q5MmJkMjQ0YTUyYjZmYjc0IiwiZXhwIjoxNjA1NDQxMzM4LCJuYmYiOjE0NzU4NDEzMzh9.YIIpOycEAVr_xrJPLlEgZ4628pLt8hvWTCtjqPTaWMs' 
        // ), 
        // )); 
        
        // $response = curl_exec($curl); 
        // $err = curl_error($curl); 
        
        // curl_close($curl);
        // echo '<pre>';
        // print_r(json_decode($response));
        // die;




        // $url = 'http://llapi.leadlovers.com/webapi'; 
        // $curl = curl_init(); 

        // $data = array(
        //     "MachineCode" => 318218,
        //     "Phone" => "5585988375530",
        //     "Message" => "Teste Integração LeadLovers com STG saúde"
        // );

        //  $data_string = json_encode($data);
        // //  print_r($data_string);
        // //  die;
        
        // curl_setopt_array($curl, array( 
        // CURLOPT_URL => $url . '/whatslovers' . '?token=' . '273FFCDFA47A4D39BDFA3DDEFCCC9F36', 
        // CURLOPT_RETURNTRANSFER => 1,
        // CURLOPT_POST => 1,
        // CURLOPT_ENCODING => '', 
        // CURLOPT_MAXREDIRS => 10, 
        // CURLOPT_TIMEOUT => 30, 
        // CURLOPT_POSTFIELDS => $data_string,
        // CURLOPT_HTTPHEADER => array( 
        //     'Content-Type: application/json', 
        //     'authorization: bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1bmlxdWVfbmFtZSI6IldlYkFwaSIsInN1YiI6IldlYkFwaSIsInJvbGUiOlsicmVhZCIsIndyaXRlIl0sImlzcyI6Imh0dHA6Ly93ZWJhcGlsbC5henVyZXdlYnNpdGVzLm5ldCIsImF1ZCI6IjFhOTE4YzA3NmE1YjQwN2Q5MmJkMjQ0YTUyYjZmYjc0IiwiZXhwIjoxNjA1NDQxMzM4LCJuYmYiOjE0NzU4NDEzMzh9.YIIpOycEAVr_xrJPLlEgZ4628pLt8hvWTCtjqPTaWMs' 
        // )
        // )); 
        
        // $response = curl_exec($curl); 
        // $err = curl_error($curl); 


        // curl_setopt_array($curl, array( 
        // CURLOPT_URL => $url . '/whatslovers/accounts/status' . '?token=' . '273FFCDFA47A4D39BDFA3DDEFCCC9F36', 
        // CURLOPT_RETURNTRANSFER => true, 
        // CURLOPT_ENCODING => '', 
        // CURLOPT_MAXREDIRS => 10, 
        // CURLOPT_TIMEOUT => 30, 
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, 
        // CURLOPT_CUSTOMREQUEST => 'GET', 
        // CURLOPT_HTTPHEADER => array( 
        //     'accept: application/json', 
        //     'authorization: bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1bmlxdWVfbmFtZSI6IldlYkFwaSIsInN1YiI6IldlYkFwaSIsInJvbGUiOlsicmVhZCIsIndyaXRlIl0sImlzcyI6Imh0dHA6Ly93ZWJhcGlsbC5henVyZXdlYnNpdGVzLm5ldCIsImF1ZCI6IjFhOTE4YzA3NmE1YjQwN2Q5MmJkMjQ0YTUyYjZmYjc0IiwiZXhwIjoxNjA1NDQxMzM4LCJuYmYiOjE0NzU4NDEzMzh9.YIIpOycEAVr_xrJPLlEgZ4628pLt8hvWTCtjqPTaWMs' 
        // ), 
        // )); 
        
        // $response = curl_exec($curl); 
        // $err = curl_error($curl);

        // curl_close($curl);
        // echo '<pre>';
        // print_r(json_decode($response));
        // die;


        // $url = 'https://api.bot2zap.com/webapi/'; 
        // $curl = curl_init(); 

        // $data = array(
        //     "MachineCode" => 318218,
        //     "Phone" => "55 85 988375530",
        //     "Message" => "Teste Integração LeadLovers com STG saúde"
        // );

        //  $data_string = json_encode($data);
        //  print_r($data_string);
        //  die;
        
        // curl_setopt_array($curl, array( 
        // CURLOPT_URL => $url . 'Bot2zap' . '?token=' . '273FFCDFA47A4D39BDFA3DDEFCCC9F36', 
        // CURLOPT_RETURNTRANSFER => 1,
        // CURLOPT_POST => 1,
        // CURLOPT_ENCODING => '', 
        // CURLOPT_MAXREDIRS => 10, 
        // CURLOPT_TIMEOUT => 30, 
        // CURLOPT_POSTFIELDS => $data_string,
        // CURLOPT_HTTPHEADER => array( 
        //     'Content-Type: application/json', 
        //     'authorization: bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1bmlxdWVfbmFtZSI6IldlYkFwaSIsInN1YiI6IldlYkFwaSIsInJvbGUiOlsicmVhZCIsIndyaXRlIl0sImlzcyI6Imh0dHA6Ly93ZWJhcGlsbC5henVyZXdlYnNpdGVzLm5ldCIsImF1ZCI6IjFhOTE4YzA3NmE1YjQwN2Q5MmJkMjQ0YTUyYjZmYjc0IiwiZXhwIjoxNjA1NDQxMzM4LCJuYmYiOjE0NzU4NDEzMzh9.YIIpOycEAVr_xrJPLlEgZ4628pLt8hvWTCtjqPTaWMs' 
        // )
        // )); 
        
        // $response = curl_exec($curl); 
        // $err = curl_error($curl);

        // print_r($response);
        // die;

        //  -----------------------------------------

        $this->load->view('header', $data);
        $this->load->view('home');
//        $this->load->view('footer');
        
        if ($this->session->userdata('perfil_id') == 22) {
            
            $medico_id = $this->session->userdata('operador_id');
            redirect(base_url() . "ambulatorio/laudo/preenchersalasmedico/$medico_id");
        }
        
        
        
    }



}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
