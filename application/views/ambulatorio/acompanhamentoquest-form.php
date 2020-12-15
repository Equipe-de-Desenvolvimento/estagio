<div class="content  "> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3><a href="#">Carregar Arquivo</a></h3>
        <div >
            <?// form_open_multipart(base_url() . 'ambulatorio/exame/gravaracompanhamentoquest/'. $acompanhamento_quest_id); ?>
                <form  method="POST" action="<?=base_url() . 'ambulatorio/exame/gravaracompanhamentoquest/'. $acompanhamento_quest_id?>" enctype="multipart/form-data"> 
                    <label>Nome do Documento</label><br>
                    <input type="text" name="nome" class="texto04"/>
                    <br>
                    <br>
                <!-- <table>                 -->
                    <label>Informe o arquivo para importa&ccedil;&atilde;o</label><br>
                    <input type="file" name="userfile"/>
                    <br>
                    <br>
                    
                    <button type="submit" name="btnEnviar">Enviar</button>
                    <input type="hidden" name="acompanhamento_quest_id" value="<?= $acompanhamento_quest_id; ?>" />
                <!-- </table> -->
             </form>
            <?// form_close(); ?>  
        </div>

      
 
    </div> <!-- Final da DIV content -->
    
</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });



</script>
