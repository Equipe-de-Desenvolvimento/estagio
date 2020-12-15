
<div class="content ficha_ceatox"> <!-- Inicio da DIV content -->
    <h3>Configuração de Template na Anamnese</h3>
    <!--<div style="width: 100%">-->
        <form name="form_exametemp" id="form_exametemp" action="<?= base_url() ?>ambulatorio/empresa/gravarimpressaocabecalho" method="post">
            

            <fieldset>
                <legend>Adicionar Template</legend>
                <label>Nome</label>
                <input type="text" name="nomeTemplate" id="nomeTemplate" class="texto04" value="<?=@$template[0]->nome_template?>" />
                <input type="hidden" name="idTemplate" id="idTemplate" class="texto04" value="<?=@$template[0]->template_anamnese_id?>" />
                <label>Grupo</label>
                <select name="grupo" id="grupo" class="size2">
                    <option value="">Selecione</option>
                        <? foreach ($grupo as $value) : ?>
                            <option value="<?= $value->nome; ?>"
                            <? if (@$template[0]->grupo == $value->nome) echo 'selected' ?>>
                                <?= $value->nome; ?>
                            </option>
                        <? endforeach; ?>

                </select>

            </fieldset>
            <fieldset>
                <legend>Adicionar Campo</legend>
                <label>Nome</label>
                <input type="text" name="nomeCampo" id="nomeCampo" class="texto04" value=""/>
                <label>Tipo</label>
                <select name="tipoCampo" id="tipoCampo">
                    <option value="">Selecione</option>
                    <option value="textoCurto">Texto Curto</option>
                    <option value="textoLongo">Texto Longo</option>
                    <option value="textoData">Data</option>
                    <option value="textoNumero">Númerico</option>
                    <option value="select">Selecionar de Lista</option>
                    <!-- <option value="multiplo">Múltipla Escolha</option> -->
                    <option value="checkbox">Verdadeiro Ou Falso (Caixa de checagem)</option>
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
    }
    var campos = [];
    var camposObj = [];

    function adicionarCampo(){
        var campoAdd = $('#nomeCampo').val();
        var campoAddName = limparEspacos(campoAdd);
        var campoTipo = $('#tipoCampo').val();
        if(campoAddName == ''){
            alert('Não é possível adicionar um campo sem nome');
            return false;
        }
        if(campos.indexOf(campoAddName) != -1){
            alert('Não podem existir dois campos com o mesmo nome no formulário');
            return false;
        }else{
            let objeto = new Obj();
            objeto.nome = campoAdd;
            objeto.nomeAbr = campoAddName;
            objeto.tipo = campoTipo;
            camposObj.push(objeto);
            campos.push(campoAddName);
            // console.log(camposObj);
            if(campoTipo == 'textoCurto'){
                var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><input type="text" name="'+campoAddName+'" id="'+campoAddName+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoAddName+ "'" +');">Apagar Campo</button></div><br id="'+campoAddName+'br">';
            }
            else if(campoTipo == 'textoLongo'){
                var div = '<div id="'+campoAdd+'Div"><br><label>'+campoAdd+'</label><textarea cols="40" rows="10" type="text" name="'+campoAddName+'" id="'+campoAddName+'"></textarea><button type="button" onclick="deletarCampo('+"'" +campoAddName+ "'" +');">Apagar Campo</button></div><br id="'+campoAddName+'br">';
            }
            else if(campoTipo == 'textoData'){
                var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><input type="text" name="'+campoAddName+'" id="'+campoAddName+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoAddName+ "'" +');">Apagar Campo</button></div><br id="'+campoAddName+'br">';
            }
            else if(campoTipo == 'textoNumero'){
                var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><input type="number" name="'+campoAddName+'" id="'+campoAddName+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoAddName+ "'" +');">Apagar Campo</button></div><br id="'+campoAddName+'br">';
            }
            else if(campoTipo == 'select'){
                var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><select name="'+campoAddName+'" id="'+campoAddName+'" class="size2"></select><input type="text" class="texto04" name="'+campoAddName+'adc" id="'+campoAddName+'adc" ><button type="button" onclick="adicionarOpcao('+"'" +campoAddName+ "'" +');">+ Opção</button><button type="button" onclick="removerOpcao('+"'" +campoAddName+ "'" +');">- Opção</button><button type="button" onclick="deletarCampo('+"'" +campoAddName+ "'" +');">Apagar Campo</button></div><br id="'+campoAddName+'br">';
            }
            else if(campoTipo == 'multiplo'){
                var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><select data-placeholder="Selecione uma ou mais opções" multiple name="'+campoAddName+'" id="'+campoAddName+'" class="size2 chosen-select"></select><input type="text" class="texto04" name="'+campoAddName+'adc" id="'+campoAddName+'adc" ><button type="button" onclick="adicionarOpcao('+"'" +campoAddName+ "'" +');">+ Opção</button><button type="button" onclick="removerOpcaoMultiplo('+"'" +campoAddName+ "'" +');">- Opção</button><button type="button" onclick="deletarCampo('+"'" +campoAddName+ "'" +');">Apagar Campo</button></div><br id="'+campoAddName+'br">';
            }
            else if(campoTipo == 'checkbox'){
                var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><input type="checkbox" name="'+campoAddName+'" id="'+campoAddName+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoAddName+ "'" +');">Apagar Campo</button></div><br id="'+campoAddName+'br">';
            }
            $('#camposDiv').append(div);
            if(campoTipo == 'textoData'){
                calendario(campoAddName);
            }
            if(campoTipo == 'multiplo'){
                $('#' + campoAddName).chosen();
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
        // console.log(id);
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
            alert('Insira o nome do template');
            $('#nomeTemplate').focus();
        }else{
            $.ajax({
                type: "POST",
                data: {nomeTemplate: nomeTemplate,
                    template: camposObj,
                    grupo: grupo,
                    template_id: idTemplate
                    },
                url: "<?= base_url() ?>ambulatorio/empresa/gravartemplateanamnese",
                success: function (data) {
                    window.open('<?= base_url() ?>ambulatorio/empresa/listartemplatesconsulta', '_self');
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    }

    function carregarTemplate(id){
        $.getJSON("<?= base_url() ?>ambulatorio/empresa/carregartemplatejson/" + id, {teste: '', ajax: true}, function (data) {
        //    console.log(data);
           for (let index = 0; index < data.length; index++) {
               const element = data[index];
               carregarCampos(element);
           }
        });
        
    }
    <?
    if(count($template) > 0){?>
        carregarTemplate(<?=$template_anamnese_id?>);
    <?}?>

    function carregarCampos(obj){

        var campoAdd = obj.nome;
        var campoAddName = limparEspacos(campoAdd);
        var campoTipo = obj.tipo;

        if(typeof obj.opcoes !== 'undefined'){
            var opcoesCampo = obj.opcoes;
        }else{
            var opcoesCampo = [];
        }
        camposObj.push(obj);
        campos.push(campoAddName);
        // console.log(opcoesCampo);
        if(campoTipo == 'textoCurto'){
            var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><input type="text" name="'+campoAddName+'" id="'+campoAddName+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoAddName+ "'" +');">Apagar Campo</button></div><br id="'+campoAddName+'br">';
        }
        else if(campoTipo == 'textoLongo'){
            var div = '<div id="'+campoAdd+'Div"><br><label>'+campoAdd+'</label><textarea cols="40" rows="10" type="text" name="'+campoAddName+'" id="'+campoAddName+'"></textarea><button type="button" onclick="deletarCampo('+"'" +campoAddName+ "'" +');">Apagar Campo</button></div><br id="'+campoAddName+'br">';
        }
        else if(campoTipo == 'textoData'){
            var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><input type="text" name="'+campoAddName+'" id="'+campoAddName+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoAddName+ "'" +');">Apagar Campo</button></div><br id="'+campoAddName+'br">';
        }
        else if(campoTipo == 'textoNumero'){
            var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><input type="number" name="'+campoAddName+'" id="'+campoAddName+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoAddName+ "'" +');">Apagar Campo</button></div><br id="'+campoAddName+'br">';
        }
        else if(campoTipo == 'select'){
            var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><select name="'+campoAddName+'" id="'+campoAddName+'" class="size2"></select><input type="text" class="texto04" name="'+campoAddName+'adc" id="'+campoAddName+'adc" ><button type="button" onclick="adicionarOpcao('+"'" +campoAddName+ "'" +');">+ Opção</button><button type="button" onclick="removerOpcao('+"'" +campoAddName+ "'" +');">- Opção</button><button type="button" onclick="deletarCampo('+"'" +campoAddName+ "'" +');">Apagar Campo</button></div><br id="'+campoAddName+'br">';
        }
        else if(campoTipo == 'multiplo'){
            var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><select data-placeholder="Selecione uma ou mais opções" multiple name="'+campoAddName+'" id="'+campoAddName+'" class="size2 chosen-select"></select><input type="text" class="texto04" name="'+campoAddName+'adc" id="'+campoAddName+'adc" ><button type="button" onclick="adicionarOpcao('+"'" +campoAddName+ "'" +');">+ Opção</button><button type="button" onclick="removerOpcaoMultiplo('+"'" +campoAddName+ "'" +');">- Opção</button><button type="button" onclick="deletarCampo('+"'" +campoAddName+ "'" +');">Apagar Campo</button></div><br id="'+campoAddName+'br">';
        }
        else if(campoTipo == 'checkbox'){
            var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><input type="checkbox" name="'+campoAddName+'" id="'+campoAddName+'" class="texto04" value="" /><button type="button" onclick="deletarCampo('+"'" +campoAddName+ "'" +');">Apagar Campo</button></div><br id="'+campoAddName+'br">';
        }
        $('#camposDiv').append(div);
        if(campoTipo == 'textoData'){
            calendario(campoAddName);
        }
        if(campoTipo == 'multiplo'){
            $('#' + campoAddName).chosen();
        }
        adicionarOpcaoCarrado(campoAddName, opcoesCampo);
       
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
    

</script>
