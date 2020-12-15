<html>
    <head>
        <title> </title>
         <script languague="javascript">
function popup(){
window.open('<?php echo base_url()?>ambulatorio/exame/operadorescadastrados?chave=<?= $_GET['chave']; ?>','popup','width=600,height=800,scrolling=auto,top=0,left=0')
}
</script>
    </head>
    <?php 
//  echo $_GET['chave'];
  
    if (@$pop == "true") {
        echo "<body onload=(popup())>";
    }else{
        echo "<body>";
    }
    ?>
     
<div class="content  "> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3><a href="#">Carregar Arquivo de Produção Médica</a></h3>
        <div >
            <?// form_open_multipart(base_url() . 'ambulatorio/exame/importararquivoproducaomedica'); ?>
            <form method="POST" action="<?=base_url() . 'ambulatorio/exame/importararquivoproducaomedica'?>"  enctype="multipart/form-data">
                <table>                
                    <label>Informe o arquivo para importa&ccedil;&atilde;o</label><br>
                    <input type="file" name="userfile"/>
                    <button type="submit" name="btnEnviar">Enviar</button>
                </table>
            </form>
            <?// form_close(); ?>  
        </div>

         <!-- Final da DIV content -->
 
    </div> <!-- Final da DIV content -->
    
</div> <!-- Final da DIV content -->
  </body>
</html>


<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

 
</script>
