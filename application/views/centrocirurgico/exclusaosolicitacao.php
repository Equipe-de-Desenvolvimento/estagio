<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3><a href="#">Excluir cirúrgia</a></h3>
        <div>
            <form method="post" action="<?= base_url() ?>centrocirurgico/centrocirurgico/gravarobservacaocirurgia" >
             <dt>
               Observação     
              </dt>
            <dd >
                <textarea style="width: 65%;"  name="obs" id="obs"><?= $observacao[0]->obs;?></textarea>
              <input name="solicitacao_cirurgia_id" name="solicitacao_cirurgia_id" value="<?= $solicitacao_cirurgia_id; ?>" hidden>
            </dd>
            <input type="submit" value="Enviar">
            </form>
           
        </div>
    </div>


</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">
 

    $(function () {
        $("#accordion").accordion();
    });
 
</script>