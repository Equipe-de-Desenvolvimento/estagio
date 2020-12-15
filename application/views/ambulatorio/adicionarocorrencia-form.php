  
 <html>
     <head>
         <title>Ocorrência</title>
          <link href="<?= base_url() ?>css/reset.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" /> 
        <link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
       <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        
        <style>
            .label {
            background-color: #3498db;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            margin: 10px;
            padding: 6px 20px
            }
        </style>
     </head>
     <body>
         <input type="hidden" id="exames_id" name="exames_id" value="<?= $exames_id; ?>">
         <form  id="formocorrencia"  method="post"  enctype="multipart/form-data"  >
            <div class=" ficha_ceatox"> 
               <fieldset>
                   <legend>Campos</legend>

                   <div id="camposDiv">  
                       <div>
                           <label>Assunto</label>
                           <input type="text" name="txtAssunto" id="txtAssunto">
                        </div><br>
                   </div>  
                   <div>
                       <label>Mensagem</label>
                       <textarea name="txtMensagem" id="txtMensagem" cols="70" rows="3"></textarea>
                   </div>
               </fieldset>
            </div>  
            <div class="nomeArquivo"></div>
             <input type="file" name="txtAnexar" id="txtAnexar" hidden>         
       <button type="button" onclick="salvarTemplate()" >Enviar</button><label for="txtAnexar" class="  label"> Anexar </label> 
     </form>
     </body>
 </html>



<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>



<script type="text/javascript">
  
    $(function () {
        $("#accordion").accordion();
    });
    
    $(function () {
    $('#txtAnexar').change(function() {
         $('.nomeArquivo').html('<b>Arquivo Selecionado:</b>' + $(this).val());
    });
});

    var Obj = function(name) {
        this.nome = '';
        this.nomeAbr = '';
        this.tipo = '';
        this.opcoes = [];
        this.value = '';
        this.minQtdCaractes = 0;
        this.required = false;
    }
    var campos = [];
    var camposObj = []; 
    var Arquivo = []; 
   
  function carregarTemplate(){
        $.getJSON("<?= base_url() ?>cadastros/pacientes/carregarocorrenciasjson", {teste: '', ajax: true}, function (data) { 
           
           for (let index = 0; index < data.length; index++) {
               var data2 = JSON.parse(data[index]['template']);
               for (let index2 = 0; index2 < data2.length; index2++) {
                   const element = data2[index2];
                    console.log(element);
                     carregarCampos(element); 
               }
              
              
           } 
        });
        
    }
  
<?
 if(count($template) > 0){?> 
     carregarTemplate(); 
 <?}?>   
function carregarCampos(obj){ 
        var campoAdd = obj.nome;
        var campoAddName = limparEspacos(campoAdd);
        var campoSemAcento = limparEspacos(retira_acentos(campoAdd.toLowerCase())); 
        var campoTipo = obj.tipo;

        if(typeof obj.opcoes !== 'undefined'){
            var opcoesCampo = obj.opcoes;
        }else{
            var opcoesCampo = [];
        }
        
        if(typeof obj.minQtdCaractes  !== 'undefined'){
            var MinQtdCampo = obj.minQtdCaractes;
        }else{
            var MinQtdCampo = '';
        } 
        if(obj.requered  !== false){
            var required = obj.required;  
        }else{
            var required = ''; 
        }  
         var asterisco= ""; 
         if (required == 'true' || MinQtdCampo > 0) {
            var asterisco= "*"; 
         } 
        camposObj.push(obj);
        campos.push(campoSemAcento);
        // console.log(opcoesCampo);
        if(campoTipo == 'textoCurto'){
             var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+' '+asterisco+'</label><input type="text"  onkeyup="salvarValueCampo('+"'" +campoSemAcento+ "'" +');"   onchange="salvarValueCampo('+"'" +campoSemAcento+ "'" +');" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" />  </div><br id="'+campoSemAcento+'br">';
        }
        else if(campoTipo == 'textoLongo'){
            var div = '<div id="'+campoSemAcento+'Div"><br><label>'+campoAdd+' '+asterisco+'</label><textarea cols="40" rows="10" type="text" onkeyup="salvarValueCampo('+"'" +campoSemAcento+ "'" +');"   onchange="salvarValueCampo('+"'" +campoSemAcento+ "'" +');" name="'+campoSemAcento+'" id="'+campoSemAcento+'"></textarea></div><br id="'+campoSemAcento+'br">';
        }
        else if(campoTipo == 'textoData'){
            var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+' '+asterisco+'</label><input type="text" onkeyup="salvarValueCampo('+"'" +campoSemAcento+ "'" +');"   onchange="salvarValueCampo('+"'" +campoSemAcento+ "'" +');" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" /></div><br id="'+campoSemAcento+'br">';
        }
        else if(campoTipo == 'textoNumero'){
            var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+' '+asterisco+'</label><input type="number" onkeyup="salvarValueCampo('+"'" +campoSemAcento+ "'" +');"   onchange="salvarValueCampo('+"'" +campoSemAcento+ "'" +');" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" /></div><br id="'+campoSemAcento+'br">';
        }
        else if(campoTipo == 'textoDecimal'){
            var div = '<div  id="'+campoSemAcento+'Div"><label>'+campoAdd+' '+asterisco+'</label><input type="text" onkeyup="salvarValueCampo('+"'" +campoSemAcento+ "'" +');"   onchange="salvarValueCampo('+"'" +campoSemAcento+ "'" +');" name="'+campoSemAcento+'" id="'+campoSemAcento+'"   class="texto04" value=""  /></div><br id="'+campoSemAcento+'br">';  
         }
        else if(campoTipo == 'select'){
            var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+' '+asterisco+'</label><select name="'+campoSemAcento+'" onchange="salvarValueCampo('+"'" +campoSemAcento+ "'" +');" id="'+campoSemAcento+'" class="size2"></select></div><br id="'+campoSemAcento+'br">';
        } 
        else if(campoTipo == 'checkbox'){
            var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+' '+asterisco+'</label><input type="checkbox" onclick="requiredCheckbox('+"'" +campoSemAcento+ "'" +');" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" /></div><br id="'+campoSemAcento+'br">';
        }else if(campoTipo == 'textoTelefone'){
            var div = '<div  id="'+campoSemAcento+'Div"><label>'+campoAdd+' '+asterisco+'</label><input type="text" onkeyup="salvarValueCampo('+"'" +campoSemAcento+ "'" +');"   onchange="salvarValueCampo('+"'" +campoSemAcento+ "'" +');" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" /></div><br id="'+campoSemAcento+'br">';  
        }
        $('#camposDiv').append(div);
        if(campoTipo == 'textoData'){
            calendario(campoSemAcento);
        } 
        if(campoTipo == 'textoTelefone'){
                telefone(campoSemAcento);
        } 
        if(campoTipo == 'textoDecimal'){
               decimal(campoSemAcento);
        } 
        if(campoTipo == 'multiplo'){
            $('#' + campoSemAcento).chosen();
        } 
        adicionarOpcaoCarrado(campoSemAcento, opcoesCampo);
        adicionarQtdCampo(campoSemAcento, MinQtdCampo);
        adicionarRequiredCampo(campoSemAcento, required); 
        // $('#nomeCampo').val('');
    }
    
    function adicionarOpcaoCarrado(id, opcoesCampo){
        // console.log(id);
        var option_str = '';
        for (let index = 0; index < opcoesCampo.length; index++) {
            const element = opcoesCampo[index];
            option_str += '<option value="'+element+'">'+element+'</option>';
        } 
        $('#'+id).append(option_str);
        $('#'+id).trigger("chosen:updated");
        $('#'+ id +'adc').val('');
    }
    
    function adicionarQtdCampo(id, MinQtdCampo){  
        $("#"+id).attr('minlength',MinQtdCampo); 
        if (MinQtdCampo > 0) {
           $('#'+id).attr('required', "req"); 
        } 
    }
    
    function adicionarRequiredCampo(id, required){   
        if (required == 'true') { 
           $('#'+id).attr('required', "req"); 
        }
    }
    
    function limparEspacos(vlr) {

        while(vlr.indexOf(" ") != -1){
            vlr = vlr.replace(" ", "");
        }
        return vlr;
    }
      function telefone(id){ 
        jQuery("#"+ id)
            .mask("(99) 9999-9999?9")
            .focusout(function (event) {
                var target, phone, element;
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                phone = target.value.replace(/\D/g, '');
                element = $(target);
                element.unmask();
                if (phone.length > 10) {
                    element.mask("(99) 99999-999?9");
                } else {
                    element.mask("(99) 9999-9999?9");
                }
            });
    }
    
    function decimal(id){
        $("#"+ id).maskMoney({ decimal: ',', thousands: '.', precision: 2 });
    }
    
   
    
    function funcaoteste(){ 
      alert('s');  
    }
    
     function retira_acentos(str) 
{ 
    var com_acento = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝŔÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿŕ"; 
    var sem_acento = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYRsBaaaaaaaceeeeiiiionoooooouuuuybyr";
    
    var novastr="";
    for(i=0; i<str.length; i++) {
       var troca=false;
        for (var a=0; a<com_acento.length; a++) {
            if (str.substr(i,1)==com_acento.substr(a,1)) {
                novastr+=sem_acento.substr(a,1);
                troca=true;
                break;
            }
        }
        if (troca==false) {
            novastr+=str.substr(i,1);
        }
    }
    return novastr;
}  

 function salvarValueCampo(id){ 
        var value = $("#"+id).val();
        var index = campos.indexOf(id); 
        if(index == -1){
            alert('Ocorreu um erro ao adicionar uma opção, por favor, tente novamente');
         }else{
           camposObj[index].value = value;
         }  
        // console.log(camposObj);
    }
    var formData;
    function salvarTemplate(){  
        var verficar = "false";  
        $('#formocorrencia').find('input[required=required]').each(function(){
          if(!$(this).val()){
           var check = $("#"+$(this).attr('id')). is(":checked");    
            if (check == false) { 
                   alert('Favor, Preencher todos campos obrigatórios com (*)');
                   verficar = "true";
                   return false;  
               }  
          }
        });
         
        if (verficar == "false") {   
                    var txtAssunto = $("#txtAssunto").val();
                    var txtMensagem = $("#txtMensagem").val();
                    var exames_id = $("#exames_id").val(); 
                    var file = $("#txtAnexar").val();
                    var datat = new FormData();
                     
                    if ((jQuery('#txtAnexar')[0].files).length == 0) {
                        console.log((jQuery('#txtAnexar')[0].files).length); 
                        datat.append('template', JSON.stringify(camposObj));
                        datat.append('txtassunto', txtAssunto);
                        datat.append('txtmensagem', txtMensagem);
                        datat.append('txtexames_id', exames_id);  
                    }else{
                        jQuery.each(jQuery('#txtAnexar')[0].files, function(i, file) {
                            datat.append('userfile', file);
                            datat.append('template', JSON.stringify(camposObj));
                            datat.append('txtassunto', txtAssunto);
                            datat.append('txtmensagem', txtMensagem);
                            datat.append('txtexames_id', exames_id); 
                        });
                   }
                   
                  
                        $.ajax({
                            type: "POST", 
                            data:datat,
                            enctype: 'multipart/form-data',
                            processData: false,
                            contentType: false,
                            url: "<?= base_url() ?>ambulatorio/exame/gravarocorrencia",
                            success: function (data, textStatus, jqXHR) {  
                              alert('Ocorrência gravada com sucesso!');
                              window.open('<?= base_url() ?>seguranca/operador/pesquisarrecepcao', '_self'); 
                            },
                            error: function (data) {
                               alert('Ocorreu um erro ao gravar ocorrência, por favor, tente novamente');
                                console.log(data);
                            }
                        });
      }
        
        
       
    }
    
     function requiredCheckbox(id){  
        var check = $("#"+id). is(":checked");  
        var index = campos.indexOf(id); 
        if(index == -1){
            alert('Ocorreu um erro ao adicionar uma opção, por favor, tente novamente');
         }else{
           camposObj[index].required = check;
         }
         console.log(camposObj); 
    }
    
    
 $("#txtAnexar").on('change', function(e) {
        files = e.target.files;
        formData = new FormData(),
          file = []; 
        $.each(files, function(key, val) {
          file[key] = val;
        }); 
        formData.append('fileUpload', file);
       
        console.log(formData);
});
  
    
</script>