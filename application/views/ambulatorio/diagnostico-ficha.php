
<div class="content ficha_ceatox"> <!-- Inicio da DIV content --> 
        <form name="form_exametemp" id="form_exametemp" method="post">     
            <fieldset>
                <legend>Adicionar Diagnostico</legend>
                <label>Nome do Diagnostico</label>
                <input type="text" name="nomeDiagnostico" id="nomeDiagnostico" class="texto04" value="<?=@$diagnostico[0]->nome_diagnostico;?>" maxlength="200" /> 
                <input type="hidden" name="idDiagnostico" id="idDiagnostico" class="texto04" value="<?=@$diagnostico[0]->diagnostico_id;?>" />

                <!-- <label>Nome do campo (Diagnostico)</label>
                <input type="text" name="nomeCampo" id="nomeCampo" class="texto04" value=""/> -->
                <?

                $procedimento_id = -1;

                foreach($diagnostico as $item){
                    $procedimento_id  = json_decode($item->procedimentos);
                 } 
                ?>

                <label>Procedimentos</label>
                <select name="procedimentos[]" id="procedimentos" class="chosen-select size3" multiple data-placeholder="Selecione">
                            <!-- <option value="0">TODOS</option> -->
                            <? foreach ($procedimentos as $value) : ?>
                                <option value="<?= $value->procedimento_tuss_id; ?>" 
                                <?
                                if($procedimento_id != -1){
                                foreach($procedimento_id as $id){
                                    if ($id == $value->procedimento_tuss_id):echo 'selected';
                                endif;
                                }
                            }
                        ?>
                        ><?php echo $value->nome; ?></option>
                            <? endforeach; ?>
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
                <button type="button" id="salvar" onclick="salvarDiagnostico();" name="salvar">Salvar</button> 
            </fieldset> 
        </form>
    <!--</div>  Final da DIV content -->
</div> <!-- Final da DIV content -->

<style>
.fonte{
    font-size: 15px !important;
}
</style>

<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>

<script type="text/javascript">
    
    $(function () {
    $("#accordion").accordion();
    }); 

    var Obj = function(name) {
        this.procedimentos = [];
        this.opcoes = [];
        this.nivel = [];
        this.nivel2 = [];
        this.nivel3 = [];
    }

    var campos = [];
    var camposObj = [];

    function adicionarCampo(){
        var campoAdd = $('#procedimentos').val();
        // var campoAddName = limparEspacos(campoAdd);
        // var campoSemAcento = limparEspacos_(retira_acentos(campoAdd.toLowerCase()));
        var campoSemAcento = 'Diagnosticos';
        // console.log(campoSemAcento);

        if(campoAdd == ''){
            alert('Não é possível adicionar um campo sem Procedimentos');
            return false;
        } 
        if(campos.indexOf(campoSemAcento) != -1){
            alert('Não podem existir dois campos com o mesmo nome no formulário');
            return false;
        }else{
            let objeto = new Obj();
            objeto.procedimentos = campoAdd;
            objeto.nome = campoSemAcento;
            camposObj.push(objeto);
            campos.push(campoSemAcento);
            // console.log(camposObj);


            
            var div = 
            '<div id="'+campoSemAcento+'Div"><label class="fonte">'+campoSemAcento+'</label>'+
            '<select name="'+campoSemAcento+'" id="'+campoSemAcento+'" class="size2"></select>'+
            '<input type="text" class="texto04" name="'+campoSemAcento+'adc" id="'+campoSemAcento+'adc" >'+
            '<button type="button" onclick="adicionarOpcao('+"'" +campoSemAcento+ "'" +');"> + Opção </button>'+
            '<button type="button" onclick="removerOpcao('+"'" +campoSemAcento+ "'" +');">- Opção</button>'+
            '<button type="button" onclick="adicionarNivel('+"'" +campoSemAcento+ "'" +');">Adicionar Nivel</button>'+
            '<br><button type="button" onclick="deletarCampo('+"'" +campoSemAcento+ "'" +');">'+
            'Apagar Campo</button></div><br id="'+campoSemAcento+'br">';
            
            $('#camposDiv').append(div);

            $('#nomeCampo').val('');
        }
        // console.log(camposObj);
    }

    function adicionarNivel(id){
        var texto = $('#'+ id).val();
        var campoAddName = limparEspacos(texto);
        var nv = limparEspacos_(retira_acentos(texto.toLowerCase()));
        var textobackup = nv;

        var index = campos.indexOf(id);
        if(nv == null){
            alert('Opcao não pode ser Nula');
        }else{
            if(camposObj[index].nivel.indexOf(id+'-campolivre') != -1){
            alert('Não é possivel criar um Novo Nivel, quando já possui um Campo Livre no Nivel');
            return false;
        }

        if(index == -1){
            alert('Selecione uma opção a ser removida');
        }else{
           var div = 
            '<div id="'+nv+'DivNivel1" class="DivNivel1"><label>'+textobackup+'(Nivel 1)</label>'+
            '<select name="'+nv+'" id="'+nv+'" class="size2"></select>'+
            '<input type="text" class="texto04" name="'+nv+'adc" id="'+nv+'adc" >'+
            '<button type="button" onclick="adicionarOpcaoNivel('+"'" +nv+ "'" +','+"'" +index+ "'" +');"> + Opção </button>'+
            '<button type="button" onclick="removerOpcaoNivel('+"'" +nv+ "'" +','+"'" +index+ "'" +');">- Opção</button>'+
            '<button type="button" onclick="Campo_Livre('+"'" +nv+ "'" +','+"'" +index+ "'" +','+"'" +textobackup+ "'" +');">Campo Livre</button>'+
            '<button type="button" onclick="AdicionarNivel2('+"'" +nv+ "'" +','+"'" +index+ "'" +','+"'" +textobackup+ "'" +');">Adicionar Nivel 2</button>'+
            '<br><button type="button" onclick="deletarCampoNivel1('+"'" +nv+ "'" +','+"'" +index+ "'" +');">'+
            'Apagar Campo</button></div><br id="'+nv+'brNivel1">';
            
            $('#camposDiv').append(div); 
        }
        }
        // console.log(camposObj);
            
    }

    function AdicionarNivel2(id, index, textobackup){
        var texto = $('#'+ id).val();
        var campoAddName = limparEspacos(texto);
        var nv2 = limparEspacos_(retira_acentos(texto.toLowerCase()));

        // var index = campos.indexOf(id);
        if(nv2 == null){
            alert('Opcao não pode ser Nula');
        }else{
            var textobackupnivel2 = backup(nv2, textobackup);

            if(camposObj[index].nivel2.indexOf(textobackupnivel2+'-campolivre') != -1){
            alert('Não é possivel criar um Novo Nivel em '+textobackup+', quando já possui um Campo Livre no mesmo');
            return false;
        }

        var textobackup = textobackupnivel2;

        if(index == -1){
            alert('Selecione uma opção a ser removida');
        }else{
           var div = 
            '<div id="'+textobackupnivel2+'DivNivel2" class="DivNivel2"><label>'+textobackupnivel2+'(Nivel 2)</label>'+
            '<select name="'+textobackupnivel2+'" id="'+textobackupnivel2+'" class="size2"></select>'+
            '<input type="text" class="texto04" name="'+textobackupnivel2+'adc" id="'+textobackupnivel2+'adc" >'+
            '<button type="button" onclick="adicionarOpcaoNivel2('+"'" +textobackupnivel2+ "'" +','+"'" +index+ "'" +');"> + Opção </button>'+
            '<button type="button" onclick="removerOpcaoNivel2('+"'" +textobackupnivel2+ "'" +','+"'" +index+ "'" +');">- Opção</button>'+
            '<button type="button" onclick="Campo_Livre_2('+"'" +textobackupnivel2+ "'" +','+"'" +index+ "'" +','+"'" +textobackup+ "'" +');">Campo Livre</button>'+
            '<button type="button" onclick="AdicionarNivel3('+"'" +textobackupnivel2+ "'" +','+"'" +index+ "'" +','+"'" +textobackup+ "'" +');">Adicionar Nivel 3</button>'+
            '<br><button type="button" onclick="deletarCampoNivel2('+"'" +textobackupnivel2+ "'" +','+"'" +index+ "'" +');">'+
            'Apagar Campo</button></div><br id="'+textobackupnivel2+'brNivel2" class="brNivel2">';
            
            $('#camposDiv').append(div); 
        }
        }
        // console.log(camposObj);
            
    }

    function AdicionarNivel3(id, index, textobackup){
        var texto = $('#'+ id).val();
        var campoAddName = limparEspacos(texto);
        var nv3 = limparEspacos_(retira_acentos(texto.toLowerCase()));
        var textobackupnivel3 = backup(nv3, textobackup);


        // var index = campos.indexOf(id);
        if(nv3 == null){
            alert('Opcao não pode ser Nula');
        }else{
            if(camposObj[index].nivel.indexOf(id+'-campolivre') != -1){
            alert('Não é possivel criar um Novo Nivel, quando já possui um Campo Livre no Nivel');
            return false;
        }

        if(index == -1){
            alert('Selecione uma opção a ser removida');
        }else{
           var div = 
            '<div id="'+textobackupnivel3+'DivNivel3" class="DivNivel3"><label>'+textobackupnivel3+'(Nivel 3)</label>'+
            '<select name="'+textobackupnivel3+'" id="'+textobackupnivel3+'" class="size2"></select>'+
            '<input type="text" class="texto04" name="'+textobackupnivel3+'adc" id="'+textobackupnivel3+'adc" >'+
            '<button type="button" onclick="adicionarOpcaoNivel3('+"'" +textobackupnivel3+ "'" +','+"'" +index+ "'" +');"> + Opção </button>'+
            '<button type="button" onclick="removerOpcaoNivel3('+"'" +textobackupnivel3+ "'" +','+"'" +index+ "'" +');">- Opção</button>'+
            '<br><button type="button" onclick="deletarCampoNivel3('+"'" +textobackupnivel3+ "'" +','+"'" +index+ "'" +');">'+
            'Apagar Campo</button></div><br id="'+textobackupnivel3+'brNivel3" class="brNivel3">';
            
            $('#camposDiv').append(div); 
        }
        }
        // console.log(camposObj);
            
    }

    function teste(){
        console.log(camposObj);
    }


    function limparEspacos(vlr) {

    while(vlr.indexOf(" ") != -1){
    vlr = vlr.replace(" ", "");
    }
    return vlr;
    }

    function limparEspacos_(vlr) {

    while(vlr.indexOf(" ") != -1){
    vlr = vlr.replace(" ", "_");
    }
    return vlr;
    }

    function backup(nv, textobackup){
        textobackup = textobackup+'-';
        nv = nv.replace(textobackup, ""); 
        return nv;
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

    function adicionarOpcao(id){
        
        var opcao = $('#'+ id +'adc').val();
        var opcao_semacento = limparEspacos_(retira_acentos(opcao.toLowerCase()));
        var index = campos.indexOf(id);
        if(index == -1){
            alert('Ocorreu um erro ao adicionar uma opção, por favor, tente novamente');
        }else{
            if(opcao == ''){
            alert('Não é possível adicionar um campo sem nome');
            return false;
            }else {
            camposObj[index].opcoes.push(opcao_semacento);
            var option_str = '<option value="'+opcao_semacento+'">'+opcao+'</option>'
            $('#'+id).append(option_str);
            $('#'+id).trigger("chosen:updated");
            $('#'+ id +'adc').val('');  
            }
            
            
        }
        //  console.log(camposObj);
    }

    function adicionarOpcaoNivel(id, index){
        var opcao = $('#'+ id +'adc').val();
         var opcao_semacento = limparEspacos_(retira_acentos(opcao.toLowerCase()));
        if(index == -1){
            alert('Ocorreu um erro ao adicionar uma opção, por favor, tente novamente');
        }else{
            if(opcao == ''){
            alert('Não é possível adicionar um campo sem nome');
            return false;
            }else {
            camposObj[index].nivel.push(id+'-'+opcao_semacento);
            // console.log(camposObj);
            var option_str = '<option value="'+id+'-'+opcao_semacento+'">'+opcao+'</option>'
            $('#'+id).append(option_str);
            $('#'+id).trigger("chosen:updated");
            $('#'+ id +'adc').val('');  
            }
            
            
        }
        //  console.log(camposObj);
    }

    function adicionarOpcaoNivel2(id, index){
        var opcao = $('#'+ id +'adc').val();
         var opcao_semacento = limparEspacos_(retira_acentos(opcao.toLowerCase()));
        if(index == -1){
            alert('Ocorreu um erro ao adicionar uma opção, por favor, tente novamente');
        }else{
            if(opcao == ''){
            alert('Não é possível adicionar um campo sem nome');
            return false;
            }else {
            camposObj[index].nivel2.push(id+'-'+opcao_semacento);
            // console.log(camposObj);
            var option_str = '<option value="'+id+'-'+opcao_semacento+'">'+opcao+'</option>'
            $('#'+id).append(option_str);
            $('#'+id).trigger("chosen:updated");
            $('#'+ id +'adc').val('');  
            }
            
            
        }
        //  console.log(camposObj);
    }

    function adicionarOpcaoNivel3(id, index){
        // var texto = $('#'+ id).val();
        // var campoAddName = limparEspacos(texto);
        // var nv2 = limparEspacos(retira_acentos(texto.toLowerCase()));
        var opcao = $('#'+ id +'adc').val();
         var opcao_semacento = limparEspacos_(retira_acentos(opcao.toLowerCase()));
        if(index == -1){
            alert('Ocorreu um erro ao adicionar uma opção, por favor, tente novamente');
        }else{
            if(opcao == ''){
            alert('Não é possível adicionar um campo sem nome');
            return false;
            }else {
            camposObj[index].nivel3.push(id+'-'+opcao_semacento);
            // console.log(camposObj);
            var option_str = '<option value="'+id+'-'+opcao_semacento+'">'+opcao+'</option>'
            $('#'+id).append(option_str);
            $('#'+id).trigger("chosen:updated");
            $('#'+ id +'adc').val('');  
            }
            
            
        }
        //  console.log(camposObj);
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

    function removerOpcaoNivel(id, index){
        var opcao = $('#'+ id).val();

        if(index == -1){
            alert('Selecione uma opção a ser removida');
        }else{
            var indexRem = camposObj[index].nivel.indexOf(opcao);
            if(indexRem != -1){
                camposObj[index].nivel.splice(indexRem, 1);
                $("#"+id+" option[value='"+opcao+"']").remove();
            }
        }
        // console.log(camposObj);
    }

    function removerOpcaoNivel2(id, index){
        var opcao = $('#'+ id).val();

        if(index == -1){
            alert('Selecione uma opção a ser removida');
        }else{
            var indexRem = camposObj[index].nivel2.indexOf(opcao);
            if(indexRem != -1){
                camposObj[index].nivel2.splice(indexRem, 1);
                $("#"+id+" option[value='"+opcao+"']").remove();
            }
        }
        // console.log(camposObj);
    }

    function removerOpcaoNivel3(id, index){
        var opcao = $('#'+ id).val();

        if(index == -1){
            alert('Selecione uma opção a ser removida');
        }else{
            var indexRem = camposObj[index].nivel3.indexOf(opcao);
            if(indexRem != -1){
                camposObj[index].nivel3.splice(indexRem, 1);
                $("#"+id+" option[value='"+opcao+"']").remove();
            }
        }
        // console.log(camposObj);
    }


    function Campo_Livre(id, index, textobackup){
        var nivel1 = $('#'+ id).val();
        if(nivel1 == null){
            alert('Opcao não pode ser Nula');
        }else{
        var textobackupnivel2 = backup(nivel1, textobackup);
       // var textobackup = textobackupnivel2;
        if(index == -1){
            alert('Selecione uma opção a ser removida');
        }else{
            if(camposObj[index].nivel2.indexOf(textobackupnivel2+'-campolivre') != -1){
            alert('Não é possivel criar um Novo Campo Livro, quando já possui um Campo Livre no mesmo');
            return false;
            }
           camposObj[index].nivel2.push(textobackupnivel2+'-campolivre');
        //    console.log(camposObj);
           var div = 
            '<div id="'+textobackupnivel2+'DivNivel2" class="DivNivel2"><label>'+textobackupnivel2+'(Nivel 2)</label>'+
            '<select name="'+textobackupnivel2+'" id="'+textobackupnivel2+'" class="size2"><option value="'+textobackupnivel2+'-campolivre">Campo Livre</option></select>'+
            '<br><button type="button" onclick="deletarCampoLivre('+"'" +textobackupnivel2+ "'" +','+"'" +index+ "'" +');">'+
            'Apagar Campo</button></div><br id="'+textobackupnivel2+'brNivel2" class="brNivel2">';
            
            $('#camposDiv').append(div); 
        }
        }
    }

    function Campo_Livre_2(id, index, textobackup){
        var nv2 = $('#'+ id).val();
        if(nv2 == null){
            alert('Opcao não pode ser Nula');
        }else{
            var textobackupnivel3 = backup(nv2, textobackup);
        if(index == -1){
            alert('Selecione uma opção a ser removida');
        }else{
            if(camposObj[index].nivel3.indexOf(textobackupnivel3+'-campolivre') != -1){
            alert('Não é possivel criar um Novo Nivel, quando já possui um Campo Livre no Nivel');
            return false;
            }
           camposObj[index].nivel3.push(textobackupnivel3+'-campolivre');
        //    console.log(camposObj);
           var div = 
            '<div id="'+textobackupnivel3+'DivNivel3" class="DivNivel3"><label>'+textobackupnivel3+'(Nivel 3)</label>'+
            '<select name="'+textobackupnivel3+'" id="'+textobackupnivel3+'" class="size2"><option value="'+textobackupnivel3+'-campolivre">Campo Livre</option></select>'+
            '<br><button type="button" onclick="deletarCampoLivre2('+"'" +textobackupnivel3+ "'" +','+"'" +index+ "'" +');">'+
            'Apagar Campo</button></div><br id="'+textobackupnivel3+'brNivel3" class="brNivel3">';
            
            $('#camposDiv').append(div); 
        }
        }
    }



    function deletarCampo(id){ 
        var opcao = $('#'+ id).val();
        var indexRem = campos.indexOf(id); 
        camposObj.splice(indexRem, 1);
        campos.splice(indexRem, 1);
        $("#"+id+ "Div").remove();
        $("#"+id+ "br").remove();
        $(".DivNivel1").remove();
        $(".brNivel1").remove();
        $(".DivNivel2").remove();
        $(".brNivel2").remove();
        $(".DivNivel3").remove();
        $(".brNivel3").remove();
        
    }


    function deletarCampoNivel1(id, index){


    var opcao = $('#'+ id).val();
    camposObj[index].nivel.splice(index, 1);
    console.log(index);
    console.log(camposObj[index].nivel.splice(index));
    $("#"+id+ "DivNivel1").remove();
    $("#"+id+ "brNivel1").remove();
        $(".DivNivel2").remove();
        $(".brNivel2").remove();
        $(".DivNivel3").remove();
        $(".brNivel3").remove();

    }

    function deletarCampoNivel2(id, index){


        var opcao = $('#'+ id).val();
        console.log(index);
        console.log(camposObj[index].nivel2.splice(index));
        console.log(camposObj[index].nivel2.splice(index, opcao));
        camposObj[index].nivel2.splice(index, 1);
        $("#"+id+ "DivNivel2").remove();
        $("#"+id+ "brNivel2").remove();
        $(".DivNivel3").remove();
        $(".brNivel3").remove();
        
    }

    function deletarCampoLivre(id, index){
        var opcao = $('#'+ id).val();

        if(index == -1){
            alert('Selecione uma opção a ser removida');
        }else{
            var indexRem = camposObj[index].nivel2.indexOf(opcao);
            if(indexRem != -1){
                camposObj[index].nivel2.splice(indexRem, 1);
                // $("#"+id+" option[value='"+opcao+"']").remove();
            }
            $("#"+id+ "DivNivel2").remove();
        $("#"+id+ "brNivel2").remove();
        $(".DivNivel3").remove();
        $(".brNivel3").remove();
        }
    }

    function deletarCampoNivel3(id, index){


    var opcao = $('#'+ id).val();
    camposObj[index].nivel3.splice(index, 1);
    $("#"+id+ "DivNivel3").remove();
    $("#"+id+ "brNivel3").remove();

}

    function deletarCampoLivre2(id, index){
        var opcao = $('#'+ id).val();

        if(index == -1){
            alert('Selecione uma opção a ser removida');
        }else{
            var indexRem = camposObj[index].nivel3.indexOf(opcao);
            if(indexRem != -1){
                camposObj[index].nivel3.splice(indexRem, 1);
                // $("#"+id+" option[value='"+opcao+"']").remove();
            }
            $("#"+id+ "DivNivel3").remove();
        $("#"+id+ "brNivel3").remove();
        }
    }


    function salvarDiagnostico(){
        var nomeDiagnostico = $('#nomeDiagnostico').val();
        var idDiagnostico = $('#idDiagnostico').val();
        if(nomeDiagnostico == ''){
            alert('Insira um nome para o Diagnostico');
            $('#nomeDiagnostico').focus();
        }else{
            $.ajax({
                type: "POST",
                data: {nomeDiagnostico: nomeDiagnostico,
                    procedimentos: camposObj[0].procedimentos,
                    nivel1 : camposObj[0].nivel,
                    nivel2 : camposObj[0].nivel2,
                    nivel3 : camposObj[0].nivel3,
                    opcoes : camposObj[0].opcoes,
                    nome : camposObj[0].nome,
                    Diagnostico : camposObj,
                    Diagnostico_id: idDiagnostico
                    },
                url: "<?= base_url() ?>ambulatorio/exame/gravardiagnostico",
                success: function (data) {
                    window.open('<?= base_url() ?>ambulatorio/exame/manterdiagnostico', '_self');
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    }


</script>