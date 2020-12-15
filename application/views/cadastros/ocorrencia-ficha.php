 
<div class="content ficha_ceatox"> <!-- Inicio da DIV content --> 
        <form name="form_exametemp" id="form_exametemp" action="<?= base_url() ?>ambulatorio/empresa/gravarimpressaocabecalho" method="post">     
            <fieldset>
                <legend>Adicionar Campo</legend>
                <label>Nome da Ocorrência</label>
                <input type="text" name="nomeTemplate" id="nomeTemplate" class="texto04" value="<?=@$template[0]->nome_template;?>" maxlength="200" /> 
                <input type="hidden" name="idTemplate" id="idTemplate" class="texto04" value="<?=@$template[0]->template_ocorrencia_id?>" />
                <label>Nome do campo</label>
                <input type="text" name="nomeCampo" id="nomeCampo" class="texto04" value=""/>
                <label>Tipo</label>
                <select name="tipoCampo" id="tipoCampo">
                    <option value="">Selecione</option>
                    <option value="textoCurto">Texto Curto</option>
                    <option value="textoLongo">Texto Longo</option> 
                    <option value="textoNumero">Númerico</option>
                    <option value="textoDecimal">Númerico Decimal</option>
                    <option value="select">Selecionar de Lista</option> 
                    <option value="checkbox">Verdadeiro Ou Falso (Caixa de checagem)</option>
                    <option value="textoTelefone">Telefone</option>
                </select>
                <div style="width: 100%">
                    <hr/>
                    <button type="button" id="adicionar" onclick="adicionarCampo();" name="adicionar">Adicionar</button>
                    <!-- <button type="button" id="console" onclick="teste();" name="console">Testar</button> -->
                </div>

            </fieldset>
            <fieldset>
                <legend>Campos</legend>
                <div id="camposDiv"> 
                    
                </div> 
            </fieldset>
            <fieldset>
                <legend>Salvar</legend>
                <button type="button" id="salvar" onclick="salvarTemplate();" name="salvar">Salvar</button> 
            </fieldset> 
        </form>
    <!--</div>  Final da DIV content -->
</div> <!-- Final da DIV content -->
<style>
    textarea{
        width: 90%;
        /*font-size: 18pt;*/
        /*height: 50pt;*/
    }
</style>
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

    function adicionarCampo(){
        var campoAdd = $('#nomeCampo').val();
        var campoAddName = limparEspacos(campoAdd);
        var campoSemAcento = limparEspacos(retira_acentos(campoAdd.toLowerCase()));
        console.log(campoSemAcento);
        var campoTipo = $('#tipoCampo').val();
        if(campoAddName == ''){
            alert('Não é possível adicionar um campo sem nome');
            return false;
        } 
        if(campos.indexOf(campoSemAcento) != -1){
            alert('Não podem existir dois campos com o mesmo nome no formulário');
            return false;
        }else{
            let objeto = new Obj();
            objeto.nome = campoAdd;
            objeto.nomeAbr = campoSemAcento;
            objeto.tipo = campoTipo;
            camposObj.push(objeto);
            campos.push(campoSemAcento);
            // console.log(camposObj);
            if(campoTipo == 'textoCurto'){
                var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><input type="text" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" /> Mínimo de dígitos:<input type="number" onkeyup="salvarMinQtdCampo('+"'" +campoSemAcento+ "'" +');"   onchange="salvarMinQtdCampo('+"'" +campoSemAcento+ "'" +');" name="minCaracter'+campoSemAcento+'" id="minCaracter'+campoSemAcento+'" class="texto01"  min=0 value="" /><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
            }
            else if(campoTipo == 'textoLongo'){
                var div = '<div id="'+campoSemAcento+'Div"><br><label>'+campoAdd+'</label><textarea cols="40" rows="10" type="text" name="'+campoSemAcento+'" id="'+campoSemAcento+'"></textarea><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
            }
            else if(campoTipo == 'textoData'){
                var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><input type="text" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
            }
            else if(campoTipo == 'textoNumero'){
                var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><input type="number" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
            }
            else if(campoTipo == 'textoDecimal'){
                var div = '<div  id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><input type="text" name="'+campoSemAcento+'" id="'+campoSemAcento+'"   class="texto04" value=""  /><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';  
            }
            else if(campoTipo == 'select'){
                var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><select name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="size2"></select><input type="text" class="texto04" name="'+campoSemAcento+'adc" id="'+campoSemAcento+'adc" ><button type="button" onclick="adicionarOpcao('+"'" +campoSemAcento+ "'" +');">+ Opção</button><button type="button" onclick="removerOpcao('+"'" +campoSemAcento+ "'" +');">- Opção</button><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
            }
            else if(campoTipo == 'multiplo'){
                var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><select data-placeholder="Selecione uma ou mais opções" multiple name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="size2 chosen-select"></select><input type="text" class="texto04" name="'+campoSemAcento+'adc" id="'+campoSemAcento+'adc" ><button type="button" onclick="adicionarOpcao('+"'" +campoSemAcento+ "'" +');">+ Opção</button><button type="button" onclick="removerOpcaoMultiplo('+"'" +campoSemAcento+ "'" +');">- Opção</button><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
            }
            else if(campoTipo == 'checkbox'){
                var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><input type="checkbox" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" /> Obrigatorio ?<input  type="checkbox" onclick="requiredCheckbox('+"'" +campoSemAcento+ "'" +');"  name="required'+campoSemAcento+'" id="required'+campoSemAcento+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
            }
            else if(campoTipo == 'textoTelefone'){
                var div = '<div  id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><input type="text" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';  
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
            $('#nomeCampo').val('');
        }
    }
    
    function teste(){
        console.log(camposObj);
    }

    function deletarCampo(id){ 
        var opcao = $('#'+ id).val();
        var indexRem = campos.indexOf(id); 
        camposObj.splice(indexRem, 1);
        campos.splice(indexRem, 1);
        $("#"+id+ "Div").remove();
        $("#"+id+ "br").remove();
        
    }

    function adicionarOpcao(id){
        
        var opcao = $('#'+ id +'adc').val();
        var index = campos.indexOf(id);
        if(index == -1){
            alert('Ocorreu um erro ao adicionar uma opção, por favor, tente novamente');
        }else{
            camposObj[index].opcoes.push(opcao);
            var option_str = '<option value="'+opcao+'">'+opcao+'</option>'
            $('#'+id).append(option_str);
            $('#'+id).trigger("chosen:updated");
            $('#'+ id +'adc').val('');
            
        }
        
    }

    function removerOpcao(id){
        // console.log(id);
        var opcao = $('#'+ id).val();
        var index = campos.indexOf(id);
        // console.log(index);
        if(index == -1){
            alert('Selecione uma opção a ser removida');
        }else{
            var indexRem = camposObj[index].opcoes.indexOf(opcao);
            if(indexRem != -1){
                camposObj[index].opcoes.splice(indexRem, 1);
                $("#"+id+" option[value='"+opcao+"']").remove();
            }
        }
    }

    function removerOpcaoMultiplo(id){
        // console.log(id);
        var opcao = $('#'+ id).chosen().val();
        var index = campos.indexOf(id);
        // console.log(opcao);
        if(index == -1){
            alert('Selecione uma opção a ser removida');
        }else{
            for (let indexFor = 0; indexFor < opcao.length; indexFor++) {
                const element = opcao[indexFor];
                var indexRem = camposObj[index].opcoes.indexOf(element);
                if(indexRem != -1){
                    camposObj[index].opcoes.splice(indexRem, 1);
                    
                }
                $("#"+id+" option[value='"+element+"']").remove();
            }
            
        }
    }

    function salvarTemplate(){
        var nomeTemplate = $('#nomeTemplate').val();
        var idTemplate = $('#idTemplate').val();
        var grupo = $('#grupo').val();
        if(nomeTemplate == ''){
            alert('Insira o nome da Ocorrência');
            $('#nomeTemplate').focus();
        }else{
            $.ajax({
                type: "POST",
                data: {nomeTemplate: nomeTemplate,
                    template: camposObj,
                    grupo: grupo,
                    template_id: idTemplate
                    },
                url: "<?= base_url() ?>cadastros/pacientes/gravartemplateocorrencia",
                success: function (data) {
                    window.open('<?= base_url() ?>cadastros/pacientes/listarocorrencia', '_self');
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    }

    function carregarTemplate(id){
        $.getJSON("<?= base_url() ?>cadastros/pacientes/carregartemplatejson/" + id, {teste: '', ajax: true}, function (data) {
        //    console.log(data);
           for (let index = 0; index < data.length; index++) {
               const element = data[index];
               carregarCampos(element);
           }
        });
        
    }
    <?
    if(count($template) > 0){?>
        carregarTemplate(<?=$template_ocorrencia_id?>);
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
        
        camposObj.push(obj);
        campos.push(campoSemAcento);
        // console.log(opcoesCampo);
        if(campoTipo == 'textoCurto'){
                var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><input type="text" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" /> Mínimo de dígitos:<input type="number" onkeyup="salvarMinQtdCampo('+"'" +campoSemAcento+ "'" +');"   onchange="salvarMinQtdCampo('+"'" +campoSemAcento+ "'" +');" name="minCaracter'+campoSemAcento+'" id="minCaracter'+campoSemAcento+'" class="texto01" min=0 value="" /><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
        }
        else if(campoTipo == 'textoLongo'){
            var div = '<div id="'+campoSemAcento+'Div"><br><label>'+campoAdd+'</label><textarea cols="40" rows="10" type="text" name="'+campoSemAcento+'" id="'+campoSemAcento+'"></textarea><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
        }
        else if(campoTipo == 'textoData'){
            var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><input type="text" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
        }
        else if(campoTipo == 'textoNumero'){
            var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><input type="number" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
        }
        else if(campoTipo == 'textoDecimal'){
            var div = '<div  id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><input type="text" name="'+campoSemAcento+'" id="'+campoSemAcento+'"   class="texto04" value=""  /><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';  
         }
        else if(campoTipo == 'select'){
            var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><select name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="size2"></select><input type="text" class="texto04" name="'+campoSemAcento+'adc" id="'+campoSemAcento+'adc" ><button type="button" onclick="adicionarOpcao('+"'" +campoSemAcento+ "'" +');">+ Opção</button><button type="button" onclick="removerOpcao('+"'" +campoSemAcento+ "'" +');">- Opção</button><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
        }
        else if(campoTipo == 'multiplo'){
            var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><select data-placeholder="Selecione uma ou mais opções" multiple name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="size2 chosen-select"></select><input type="text" class="texto04" name="'+campoSemAcento+'adc" id="'+campoSemAcento+'adc" ><button type="button" onclick="adicionarOpcao('+"'" +campoSemAcento+ "'" +');">+ Opção</button><button type="button" onclick="removerOpcaoMultiplo('+"'" +campoSemAcento+ "'" +');">- Opção</button><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
        }
        else if(campoTipo == 'checkbox'){
            var div = '<div id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><input type="checkbox" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" />Obrigatorio ?<input  type="checkbox" onclick="requiredCheckbox('+"'" +campoSemAcento+ "'" +');"  name="required'+campoSemAcento+'" id="required'+campoSemAcento+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
        }else if(campoTipo == 'textoTelefone'){
            var div = '<div  id="'+campoSemAcento+'Div"><label>'+campoAdd+'</label><input type="text" name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">Apagar Campo</button></div><br id="'+campoSemAcento+'br">';  
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
      // $("#"+id).attr('minlength',MinQtdCampo);
       $("#minCaracter"+id).val(MinQtdCampo);  
    }
    
    function adicionarRequiredCampo(id, required){   
        if (required == 'true') { 
           $('#required'+id).attr('checked',true);
        }
    }

    function limparEspacos(vlr) {

        while(vlr.indexOf(" ") != -1){
            vlr = vlr.replace(" ", "");
        }
        return vlr;
    }

    function calendario(id){
        $("#"+ id).datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
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
    
    
    
    function salvarMinQtdCampo(id){
       // console.log(id); 
        var Qtd = $("#minCaracter"+id).val();
        var index = campos.indexOf(id); 
        if(index == -1){
            alert('Ocorreu um erro ao adicionar uma opção, por favor, tente novamente');
         }else{
           camposObj[index].minQtdCaractes = Qtd;
         }  
    }
    
    function requiredCheckbox(id){  
        var check = $("#required"+id). is(":checked");  
        var index = campos.indexOf(id); 
        if(index == -1){
            alert('Ocorreu um erro ao adicionar uma opção, por favor, tente novamente');
         }else{
           camposObj[index].required = check;
         }
         console.log(camposObj); 
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
</script>
