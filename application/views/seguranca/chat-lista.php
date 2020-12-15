<?php
 $session = mt_rand(1, 999);
 $json = json_encode($lista);
?>
 
<!DOCTYPE html>
<html>
    <head>  
        <title>Chat</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <script src="<?php echo base_url()?>js/jquery.js" type="text/javascript"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
        <style type="text/css">
            html{
                background-color:#c0c0c0;
                
            }

            #box {
                background-color:#c0c0c0;
                border-color:#000;
            }
            
/*            #box {position:relative;margin:auto; height:100%;border-color:#000;}*/
            
            #chat_output {
                left:0;
                padding:0px;
                width:100%;
                height:700px;
                min-width: 700px;
                border-color:#000000;
                overflow-y:auto;
                font-size: 2px
              
             }
            #chat_input {
                overflow:auto;
                bottom:0;
                left:0;
                padding:5px;
                width:100%;
                height:40px;
                border:box;
                border-color:#000;
                display: inline ;  
            }
            .entrou{
                border-color:#000;
                color:green;
            }
            .sistema{
                border-color:#000;
                color: #341f97;
            }
            .windownew{
                border-color:#000;
                border:box;
                font-size: 15px;
                font-weight: normal;
                background-color: #666;
                color: #fff;
            }
            .minha{
                font-size: 13px;
            }.privada{
                font-size: 13px;
            }#nome_user{
                color: red;
                font-size: 14px;
            }
            .bloco_msg{
                /*max-box-size: 150px;*/
                border:2px solid black;
                padding: 2%;
                margin-top: 1%;

            }
            #minha{
                margin-left: 50%;
                border:2px solid silver; 
                border-radius: 50px;
                border-bottom-color: #fff;;
                background-color: #666;
                font-size: 2px;
            }
            #minhagrupo{
               
                border:2px solid silver; 
                border-radius: 50px;
                border-bottom-color: #fff;
                
            }
            .minhagrupo {      
                    word-wrap: break-word; 
                    max-width:5px;
                    
                }
            #oposto{
                 border:2px solid silver; 
                border-radius: 50px;
                border-bottom-color: #fff;
                background-color: #666;
                 width: 50%;   
            }
             #undefined{
                 border:2px solid silver;
                 width: 50%;    
                 border-radius: 10px;
                 background-color: #666;
                  
            }
            #header{
                border:1px solid blue;
                width: 100%;
                padding: 10px;
                background-color: blue;
            }
            .quebra {
            word-wrap: break-word;
            width: 10px;
            border-color:#000;
             }​
        </style>
    </head>

    <body>

        <?php
        if ($this->session->userdata('operador_id') == "") {
            redirect(base_url());
        }
      //var_dump($this->session->userdata('name'));
      //  var_dump($this->session->userdata('meu_id'));

        $teste =  $this->session->userdata('name');

//        $teste2 = $this->session->userdata('meu_id');
 
        ?>
<!--<div id="header">-->
     <!--<a href="<?php echo base_url() ?>o/sair" style="color:white; text-decoration: none;">SAIR</a>-->
 
<!--</div>-->
        <!-- <div id='acesso_usuario'  > -->
        <!-- <form id='login'> -->
        <input type='text' placeholder='Insira seu apelido' name='apelido' value="<?php echo $teste; ?>" id='apelido'  hidden/>
        <input type='text' placeholder='' name='meu_id' value="<?= $this->session->userdata('operador_id'); ?>" id='meu_id'   hidden/>

        <input type='text' name='select_id_c'  id='select_id_c'  hidden />
        <input type='text' name='select_grupo'  id='select_grupo'   hidden/>

 <!-- <input type='submit' value='Entrar' /> -->
        <!-- </form> -->
        <!-- </div> -->
 <div id="box"  > 

     <table  border=1 cellspacing=0 cellpadding=2  >
        
            <tr> 
                <td rowspan="2" style="vertical-align: top;">
                     <select multiple="multiple" id='grupos'  style="height:650px;width:180px; color: #000;  border: none; overflow: hidden; margin: 5px 5px;"  > 
                         <?php 
                        foreach($grupos as $item){                                            
                        ?>
                         <option value="<?php echo $item->nome; ?>" id="<?= $item->nome; ?>"  > <?php echo $item->nome; ?>  </option>
                        <?
                          }
                       ?>
                     </select>
                </td>
                <td rowspan="2" style="vertical-align: top;" >
                    <label style="font-size:25px;font-weight: bold; font-family: Andale Mono, monospace; border:#0001">Médicos:    </label>
                    <select multiple="multiple" id='contatos'  style="height:650px;width:400px;color: #000; margin-top: 5px; border: none; overflow: hidden;"   > 

                        <?php 
                        foreach($usuarios as $item){
                            $res = $this->operador_m->listarmensagensnaovistas($item->operador_id);                            
                        ?>
                        <option value="<?php echo $item->operador_id; ?> " id="<?php echo $item->operador_id; ?>"> <?php echo $item->nome; ?>  </option>
                        <?
                              }
                       ?>
               </select>

                </td>
                <td>
                    <div id="chat_output" style="width:100px; position: static;top: 0; left:0"></div>
                </td>

            </tr>
            <tr>                
                <td>
                    <table width="100%" style=" bottom: 0">
                    <tr>
                        <td>
                              <textarea id="chat_input" placeholder="Digite aqui.." ></textarea>           
                        </td>
                        <td style="width:80px;">
                             <button class="btn btn-primary" onclick=(enviarmensagem())>Enviar</button> 
                        </td>
                    </tr>
                </table>    
 
                </td>
            </tr>
        </table>
 
            <script type="text/javascript">
  
         $(window).load(function () { 
             
                   setInterval(function(){ 
                   var medico_id = $("#select_id_c").val(); 
                   //alert(medico_id) ; 
                     if ($("#select_grupo").val() == "") {     
                         $("#chat_output").load("listar_mensagens?medico_id="+medico_id+"");
                     }                                                              
                            }, 500);
                            
                            
                               setInterval(function(){ 
                               var medico_id = $("#select_id_c").val(); 
                               //alert(medico_id); 
                                           $.getJSON('<?= base_url() ?>seguranca/operador/buscartodasmensagem', {meu_id: "teste"}, function (j) { 
                                                
                                                    for (var c = 0; c < j.length; c++) {                                                               
                                                                var combo = document.getElementById("contatos");

                                                                for (var i = 0; i < combo.options.length; i++)
                                                                {
//                                                                       console.log(j[c].de +" - "+combo.options[i].value);
                                                                        
                                                                                        $('#contatos option').each(function() {
                                                                                            // se localizar o id
                                                                                            if($(this).val() == parseInt(j[c].de)) {
                                                                                                var texto_antigo = $(this).text();
//                                                                                                 alert(texto_antigo);  || medico_id == parseInt(j[c].de)
                                                                                                    if (texto_antigo.substr(-1) == ')' ) { 
                                                                                                         $.getJSON('<?= base_url() ?>seguranca/operador/atualizarstatusmsg', {para_id: medico_id}, function (j) { });                                                 
                                                                                                    }else{
                                                                                                       $(this).text(texto_antigo+" ( 1)");  
                                                                                                    }                                                                                                  
                                                                                                  
                                                                                               
                                                                                            }
                                                                                          });
                                                                                 
                                                                                
                                                                }
                                                    } 
                                                
                                           });
                                 
                            }, 1000);



         })


function enviarmensagem(){
                                    var chat_msg = $("#chat_input").val();
                                    var nome = $("#apelido").val();
                                    var contato = $("#contatos").val();
                                    var meu_id = $("#meu_id").val();
                                    var x  = "";
                                    var y  = "";
                                    var select_id = "";
                                    var grupos = $("#grupos").val();
                                    
                     if (document.getElementById("contatos").selectedIndex != -1) {
                          x = document.getElementById("contatos").selectedIndex;
                          y = document.getElementById("contatos").options;
                                 // alert(y[x].id);
                                 select_id = y[x].id;
                         }
                         
                           $.ajax({
                                        type: "POST",
                                        url: "<?php echo base_url()?>appAPI/gravarmensagemchat",
                    data: {'mensagem': chat_msg, 'meu_id': meu_id,'para_id':select_id,'grupos':grupos},
                                        success: function( data )
                                             {
                                              //alert( data );
                                            var i =0;
                                            var numOfCharacters = 40;
                                            var msgArray = chat_msg.split(''); //Transforma em array
                                            chat_msg = '';
                                            for(i = 0; i < msgArray.length; i++){ //Percorre array
                                                chat_msg += msgArray[i];//Reescreve a string
                                                if((i+1)%numOfCharacters == 0){ //Se for divisivél por 40 insere  quebra de linha
                                                  chat_msg += '<br>';
                                                };
                                            };

                                            var mensagem_formatada = $("<div class='bloco_msg' id='minhagrupo'   ><b class='windownew minhagrupo' >"+chat_msg+"</b><div>");
                                            $('#chat_output').append(mensagem_formatada);  
                                            $('#chat_output').scrollTop($('#chat_output')[0].scrollHeight);
                                              
                                      }
                        });

                                    // websocket_server.send(
                                    //         JSON.stringify({
                                    //                 'type':'chat',
                                    //                 'user_id':<?php echo $session; ?>,
                                    //                 'chat_msg':chat_msg,
                                    //                 'user':nome,
                                    //                 'contato':contato
                                    //         })
                                    // );
                                    $("#chat_input").val('');
                                    $('#chat_output').scrollTop($('#chat_output')[0].scrollHeight);
}

            jQuery(document).ready(function($){

                     var cont = 0;
                     var conttot = 0;
  
                    // Events
                    $('#chat_input').on('keyup',function(e){
                            if(e.keyCode==13 && !e.shiftKey)
                            {                                
                                    var chat_msg = $(this).val();
                                    var nome = $("#apelido").val();
                                    var contato = $("#contatos").val();
                                    var meu_id = $("#meu_id").val();
                                    var x  = "";
                                    var y  = "";
                                    var select_id = "";
                                    var grupos = $("#grupos").val();

                         if (document.getElementById("contatos").selectedIndex != -1) {
                          x = document.getElementById("contatos").selectedIndex;
                          y = document.getElementById("contatos").options;
                                 // alert(y[x].id);
                                 select_id = y[x].id;
                         }
                                    $.ajax({
                                        type: "POST",
                                        url: "<?php echo base_url()?>appAPI/gravarmensagemchat",
                    data: {'mensagem': chat_msg, 'meu_id': meu_id,'para_id':select_id,'grupos':grupos},
                                        success: function( data )
                                        {
                                            // alert( data );
                                            var i =0;
                                            var numOfCharacters = 40;
                                            var msgArray = chat_msg.split(''); //Transforma em array
                                            chat_msg = '';
                                            for(i = 0; i < msgArray.length; i++){ //Percorre array
                                                chat_msg += msgArray[i];//Reescreve a string
                                                if((i+1)%numOfCharacters == 0){ //Se for divisivél por 40 insere  quebra de linha
                                                  chat_msg += '<br>';
                                                };
                                            };

                                            var mensagem_formatada = $("<div class='bloco_msg' id='minhagrupo'   ><b class='windownew minhagrupo' >"+chat_msg+"</b><div>");
                                            $('#chat_output').append(mensagem_formatada);
                                            $('#chat_output').scrollTop($('#chat_output')[0].scrollHeight);
                                        }
                                        
                                    });

                                    
                                    $(this).val('');
                                    $('#chat_output').scrollTop($('#chat_output')[0].scrollHeight);


                            }
                    });

 

document.querySelector('#contatos').addEventListener('change', function(){
 
                                            var nome = $("#apelido").val();
                                            var contato = $("#contatos").val();
                                            var meu_id = $("#meu_id").val();
                                            var select_id = this.options[this.selectedIndex].id;   
                                            
                                            
                                            $("#select_id_c").val(select_id);
                                            if ($("#select_grupo").val() != "") {
                                            document.getElementById($("#select_grupo").val()).selected = false;
                                            }
                                            $("#select_grupo").val("");
                                    
                                            if (select_id=='') {
 
                                            }else{
                                                
                                                $.getJSON('<?= base_url() ?>seguranca/operador/atualizarmensagem', {meu_id: meu_id, para_id: select_id}, function (y) { 
                                                
                                                });
                                                
                                                $('#contatos option').each(function() {
                                                                 // se localizar o id
                                                                  if($(this).val() == parseInt(select_id)) {                                                                      
                                                                         $.getJSON('<?= base_url() ?>seguranca/operador/buscarmedico', {medico_id: select_id}, function (y) { 
                                                                            
                                                                                      $('#contatos option').each(function() {
                                                                                            // se localizar o id
                                                                                       if($(this).val() == parseInt(y[0].operador_id)) {                                                                     
                                                                                                $(this).text(y[0].nome);                                                                                                                
                                                                                           }
                                                                                       });        
                                                                       });
                                                                                                  
                                                                                               
                                                                    }
                                                      });
                                                 
                                                
                                  $.getJSON('<?= base_url() ?>seguranca/operador/buscarmensagem', {meu_id: meu_id, para_id: select_id}, function (j) { 

                                                 for (var c = 0; c < j.length; c++) {                                                    
                                                    if (j[c].de == meu_id ) {
                                                        var mensage_minha = "minha";
                                                    }else{
                                                         var mensage_minha = "oposto";
                                                    }
           var mensagem_formatada = $("<div class='bloco_msg' id="+mensage_minha+"><b class='windownew' >"+j[c].mensagem+"</b><div>");

                                                       // alert(j[c].mensagem);   
                                                        $('#chat_output').prepend(mensagem_formatada);   
                                                

                                           }  
                                        if (c == j.length) {                                                        
                                                  $('#chat_output').scrollTop($('#chat_output')[0].scrollHeight);


                                           }else{
                                           }

                                                                                                     
                                            });                                 
                                          }
                                    
 	
}); 

document.querySelector('#grupos').addEventListener('change', function(){
      var select_id = this.options[this.selectedIndex].id;                                             
        $("#select_grupo").val(select_id);
        if ($("#select_id_c").val() != "") {
           document.getElementById($("#select_id_c").val()).selected = false;
        }
      $("#select_id_c").val("");       
      $("#chat_output").empty();
              $.getJSON('<?= base_url() ?>seguranca/operador/buscarmensagemgrupo', {grupo: select_id}, function (j) { 
              for (var c = 0; c < j.length; c++) {
                                            var i =0;
                                            var numOfCharacters = 80;
                                            var msgArray = j[c].mensagem.split(''); //Transforma em array
                                            j[c].mensagem = '';
                                            for(i = 0; i < msgArray.length; i++){ //Percorre array
                                                j[c].mensagem += msgArray[i];//Reescreve a string
                                                if((i+1)%numOfCharacters == 0){ //Se for divisivél por 40 insere  quebra de linha
                                                  j[c].mensagem += '<br>';
                                                };
                                            };
            var mensagem_formatada = $("<div class='bloco_msg' id='minhagrupo'   ><b class='windownew' >"+j[c].mensagem+"</b><div>");

            $('#chat_output').prepend(mensagem_formatada);  
               $('#chat_output').scrollTop($('#chat_output')[0].scrollHeight);
             }

           });
           
   
      
      
});







            });
            
            const messages = document.getElementById('chat_output');

function appendMessage() {
	const message = document.getElementsById('chat_output')[0];
  const newMessage = message.cloneNode(true);
  messages.appendChild(newMessage);
}

function getMessages() {
	// Prior to getting your messages.
  shouldScroll = messages.scrollTop + messages.clientHeight === messages.scrollHeight;
  /*
   * Get your messages, we'll just simulate it by appending a new one syncronously.
   */
  appendMessage();
  // After getting your messages.
  if (!shouldScroll) {
    scrollToBottom();
  }
}

function scrollToBottom() {
  messages.scrollTop = messages.scrollHeight;
}

scrollToBottom();

setInterval(getMessages, 100);



            </script>
        </div>
    </body>
</html>