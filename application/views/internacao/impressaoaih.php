
<style>
    @media print {
        a * {
            visibility: hidden;
        }
        /*  #printable, #printable * {
            visibility: visible;
          }
          a {
            position: fixed;
            left: 0;
            top: 0;
          }*/
        input{
            visibility: hidden;
        }
        #resultado{
            visibility: visible;
        }


    } 
    input{
        height: 20px;
    }

</style>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<input type="text" value="<?= @$obj->_paciente_id ?>" name="paciente_id" id="paciente_id" hidden="">
<div class="content ficha_ceatox">
    <meta charset="utf-8">
    <title>Impressão</title>
    <table border="1">
        <tbody>
            <tr>
                <td width="650px" colspan="4" BGCOLOR=GRAY><b><center>LAUDO PARA SOLICITA&Ccedil;&Atilde;O DE INTERNA&Ccedil;&Atilde;O HOSPITALAR</center></b></td>
            </tr>
            <tr>
                <td colspan="4" BGCOLOR=GRAY><font size = -1><b><center>IDENTIFICACAO DO ESTABELECIMENTO DE SAUDE</center></b></font></td>
        <input id="razo_sescondido"  value="&nbsp;<?= @$empresa[0]->razao_social; ?>"    hidden>  
        </tr>
        <tr height="30px">
            <td colspan="3" style='vertical-align: top; font-family: serif; font-size: 6pt;'><b style="font-weight: normal;"  onclick="calcular()">NOME DO ESTABELECIMENTO SOLICITANTE:  </b>

                <input id="razo_s"  value="<?= @$empresa[0]->razao_social; ?>"  onblur="mostrar()">  
                <div id="resultado" value="<?= @$empresa[0]->razao_social; ?>"  ><?= @$empresa[0]->razao_social; ?></div>    

            </td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>

                <b onclick="aparecerinputcnes()" style="font-weight: normal;"> CNES: </b>
                <input id="inputcnes"  value=""  onblur="apareceresultadocnes()">  
                <div id="resultadocnes" value="" ></div>    

            </td>
        </tr>
        <tr height="30px">
            <td colspan="3" style='vertical-align: top; font-family: serif; font-size: 6pt;'>
                <b onclick="aparecercamponome()" style="font-weight: normal;">NOME DO ESTABELECIMENTO EXECUTANTE:</b>  &nbsp;<? // @$obj->_nome                             ?>
                <input id="nomeexecutante"  value=""  onblur="desapararecernome()" >  
                <div id="resultadonomeexecutante" value="" ></div>    

            </td>



            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>


                <b onclick="aparecerinputcnes2()" style="font-weight: normal;"> CNES: </b>
                <input id="inputcnes2"  value=""  onblur="apareceresultadocnes2()">  
                <div id="resultadocnes2" value="" ></div>    


            </td>
        </tr>
        <tr>
            <td colspan="4" BGCOLOR=GRAY><font size = -1><b><center>IDENTIFICACAO DO PACIENTE</center></b></font></td>
        </tr>
        <tr height="30px">
            <td colspan="3" style='vertical-align: top; font-family: serif; font-size: 6pt;'>NOME DO PACIENTE: &nbsp;<?= @$obj->_nome ?></td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>PRONTUARIO:  &nbsp;<?= @$obj->_paciente_id ?></td>
        </tr>
        <tr height="30px">
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>CARTAO NACIONAL DE SAUDE:</td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>NASC.:  &nbsp;<?= @date('d-m-Y', strtotime($obj->_nascimento)); ?></td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>SEXO:  &nbsp;<?= @$obj->_sexo ?></td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>RACA/COR:</td>
        </tr>
        <tr height="30px">
            <td colspan="3" style='vertical-align: top; font-family: serif; font-size: 6pt;'>NOME MAE:  &nbsp;<?= @$obj->_nome ?></td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>CONTATO: </td>
        </tr>
        <tr height="30px">
            <td colspan="3" style='vertical-align: top; font-family: serif; font-size: 6pt;'>NOME RESP.:</td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>CONTATO: </td>
        </tr>
        <tr height="30px">
            <td colspan="3" style='vertical-align: top; font-family: serif; font-size: 6pt;'>ENDERE&Ccedil;O:  &nbsp;<?= @$obj->_logradouro ?>&nbsp;<?= @$obj->_numero ?></td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>CONTATO: </td>
        </tr>
        <tr height="30px">
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>MUNICIPIO:  &nbsp;<?= @$obj->_nome ?></td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>COD.IBGE:</td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>UF:</td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>CEP:</td>
        </tr>
        <tr>
            <td colspan="4" BGCOLOR=GRAY onclick="fechartinym()"><font size = -1><b ><center>JUSTIFICATIVA DA INTERNA&Ccedil;&Atilde;O</center></b></font></td>
        </tr>
        <tr height="200px">
            <td colspan="4" style='vertical-align: top; font-family: serif; font-size: 6pt;' ><b style="font-weight: normal;" onclick="aparecerjustificativa()">PRINCIPAIS SINAIS E SINTOMAS CLINICOS:</b>  
                <input id="inputjustificativaescondido"  value="<?= @$obj->_texto; ?>"  hidden="">
                <!--<input id="inputjustificativa"  value=""  >-->  
                <div id="resultadojustificativa" value=""  ><?
                    if (@$_GET['texto'] != "") {
                        echo @$_GET['texto'];
                    } else {
                        @$obj->_texto;
                    }
                    ?></div> 
                <textarea  id="inputjustificativa" onblur="apareceresultadojustifcativa()"  ><?
                    if (@$_GET['texto'] != "") {
                        echo @$_GET['texto'];
                    } else {
                        @$obj->_texto;
                    }
                    ?></textarea>
            </td>
        </tr>
        <tr height="30px">
            <td colspan="4" style='vertical-align: top; font-family: serif; font-size: 6pt;'>


                <b onclick="aparecerinputcondjusti()" style="font-weight: normal;">CONDIÇÕES QUE JUSTIFICAM A INTERNAÇÃO:</b>
                <input id="inputcondjusti"  value="Risco de complicação"  onblur="apareceresultadocondjusti()">  
                <div id="resultadocondjusti" value="" >Risco de complicação</div>    
            </td>
        </tr>
        <tr height="30px">
            <td colspan="4" style='vertical-align: top; font-family: serif; font-size: 6pt;'>

                <b onclick="aparecerinputprinresult()" style="font-weight: normal;">PRINCIPAIS RESULTADOS DE PROVAS DIAGNOSTICAS (RESULTADOS DE EXAMES REALIZADOS):</b>
                <input id="inputprinresult"  value="Anamnese Mex | Exame Fisico"  onblur="apareceresultadoprinresult()">  
                <div id="resultadoprinresult" value="" >Anamnese Mex | Exame Fisico</div> 

            </td>
        </tr>
        <tr height="30px">
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>
                <b onclick="aparecerinputdiagini()" style="font-weight: normal;">DIAGNOSTICO INICIAL:</b>

                <div id="resultadodiagini" value="" ></div> 
                <input id="inputdiagini"  value=""  onblur="apareceresultadodiagini()">  

            </td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>
                <b onclick="aparecerinputcid10()" style="font-weight: normal;">CID 10 PRINCIPAL:</b>
                <div id="resultadocid10" value="" ></div> 
                <input id="inputcid10"  value=""  onblur="apareceresultadocid10()">  

            </td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>  

                <b onclick="aparecerinputcid10secu()" style="font-weight: normal;">CID 10 SECUNDARIO:</b>
                <div id="resultadocid10secu" value="" ></div> 
                <input id="inputcid10secu"  value=""  onblur="apareceresultadocid10secu()">
            </td>

            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>
                <b onclick="aparecerinputcid10cau()" style="font-weight: normal;">CID 10 CAUSAS ASSOCIADAS:</b>
                <div id="resultadocid10cau" value="" ></div> 
                <input id="inputcid10cau"  value=""  onblur="apareceresultadocid10cau()">
            </td>
        </tr>
        <tr>
            <td colspan="4" BGCOLOR=GRAY><font size = -1><b><center>PROCEDIMENTO SOLICITADO</center></b></font></td>
        </tr>
        <tr height="30px">
            <td colspan="3" style='vertical-align: top; font-family: serif; font-size: 6pt;'>
                <b onclick="aparecerinputdescpro()" style="font-weight: normal;">DESCRICAO DO PROCEDIMENTO SOLICITADO:</b>
                <div id="resultadodescpro" value="" ></div> 
                <input id="inputdescpro"  value=""  onblur="apareceresultadodescpro()">

            </td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>

                <b onclick="aparecerinputcodpro()" style="font-weight: normal;">CÓDIGO DO PROCEDIMENTO: </b>
                <div id="resultadocodpro" value="" ></div> 
                <input id="inputcodpro"  value=""  onblur="apareceresultadocodpro()">


            </td>
        </tr>
        <tr height="30px">
            <td colspan="2" style='vertical-align: top; font-family: serif; font-size: 6pt;'> 

                <b onclick="aparecerinputclinica()" style="font-weight: normal;">CLÍNICA: </b>
                <div id="resultadoclinica" value="" ></div> 
                <input id="inputclinica"  value=""  onblur="apareceresultadoclinica()">

            </td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>
                <b onclick="aparecerinputcaraterint()" style="font-weight: normal;">CARATER DA INTERNAÇÃO: </b>
                <div id="resultadocaraterint" value="" ></div> 
                <input id="inputcaraterint"  value=""  onblur="apareceresultadocaraterint()">


            </td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>

                <b onclick="aparecerinputcpf()" style="font-weight: normal;">CPF</b>
                <div id="resultadocpf" value="" ></div> 
                <input id="inputcpf"  value=""  onblur="apareceresultadocpf()">

            </td>
        </tr>
        <tr height="30px">
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>


                <b onclick="aparecerinputnomeprof()" style="font-weight: normal;">NOME DO PROFISSIONAL SOLICITANTE: </b>
                <div id="resultadonomeprof" value="" > <?= @$operadores[0]->nome; ?></div> 
                <input id="inputnomeprof"  value=" <?= @$operadores[0]->nome; ?>"  onblur="apareceresultadonomeprof()">
            </td>
            <td style='vertical-align: top; font-family: serif; font-size: 6pt;'>


                <b onclick="aparecerinputdatasolic()" style="font-weight: normal;">DATA SOLICITAÇÃO:</b>
                <div id="resultadodatasolic" value="" > <?= date('d/m/Y'); ?></div> 
                <input id="inputdatasolic"  value="<?= date('d/m/Y'); ?>"  onblur="apareceresultadodatasolic()">

            </td>
            <td colspan="2" style='vertical-align: top; font-family: serif; font-size: 6pt;'>

                <b onclick="aparecerinputasscarimbo()" style="font-weight: normal;">ASSINATURA E CARIMBO (No. CR):</b>
                <div id="resultadoasscarimbo" value="" ></div> 
                <input id="inputasscarimbo"  value=""  onblur="apareceresultadoasscarimbo()">


            </td>
        </tr>
        </tbody>
    </table>   
</div>
<?php
$this->load->plugin('mpdf');


// pdf($saida, $filename, $cabecalho, $rodape, '', 0, 0, 0);
?>

<a href="#"  onClick="imprimir();"><button>Imprimir</button></a>
<script type="text/javascript" src="<?= base_url() ?>js/tinymce2/js/tinymce/tinymce.min.js"></script>
<!-- <script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/langs/pt_BR.js"></script> -->
<script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/plugins/spellchecker/plugin.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/themes/modern/theme.min.js"></script>


<script>
    window.onload = function () {
        document.getElementById('razo_s').style.visibility = "hidden";
        document.getElementById('nomeexecutante').style.visibility = "hidden";
        document.getElementById('inputcnes').style.visibility = "hidden";
        document.getElementById('inputcnes2').style.visibility = "hidden";
        document.getElementById('inputjustificativa').style.visibility = "hidden";
        document.getElementById('inputjustificativa').style.visibility = "hidden";
        document.getElementById('inputcondjusti').style.visibility = "hidden";
        document.getElementById('inputprinresult').style.visibility = "hidden";
        document.getElementById('inputdiagini').style.visibility = "hidden";
        document.getElementById('inputcid10').style.visibility = "hidden";
        document.getElementById('inputcid10secu').style.visibility = "hidden";
        document.getElementById('inputcid10cau').style.visibility = "hidden";
        document.getElementById('inputdescpro').style.visibility = "hidden";
        document.getElementById('inputcodpro').style.visibility = "hidden";
        document.getElementById('inputclinica').style.visibility = "hidden";
        document.getElementById('inputcaraterint').style.visibility = "hidden";
        document.getElementById('inputcpf').style.visibility = "hidden";
        document.getElementById('inputnomeprof').style.visibility = "hidden";
        document.getElementById('inputdatasolic').style.visibility = "hidden";
        document.getElementById('inputasscarimbo').style.visibility = "hidden";
    }


    function imprimir() {
        
        
         var paciente_id = $("#paciente_id").val();
 
        var resultado = encodeURIComponent($("#resultado").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadocnes = encodeURIComponent($("#resultadocnes").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadonomeexecutante = encodeURIComponent($("#resultadonomeexecutante").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadocnes2 = encodeURIComponent($("#resultadocnes2").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadojustificativa = encodeURIComponent($("#resultadojustificativa").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadocondjusti = encodeURIComponent($("#resultadocondjusti").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadoprinresult = encodeURIComponent($("#resultadoprinresult").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadodiagini = encodeURIComponent($("#resultadodiagini").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadocid10 = encodeURIComponent($("#resultadocid10").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadocid10secu = encodeURIComponent($("#resultadocid10secu").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadocid10cau = encodeURIComponent($("#resultadocid10cau").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadodescpro = encodeURIComponent($("#resultadodescpro").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadocodpro = encodeURIComponent($("#resultadocodpro").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadoclinica = encodeURIComponent($("#resultadoclinica").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadocaraterint = encodeURIComponent($("#resultadocaraterint").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadocpf = encodeURIComponent($("#resultadocpf").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadonomeprof = encodeURIComponent($("#resultadonomeprof").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadodatasolic = encodeURIComponent($("#resultadodatasolic").text()).replace(/~/g, '%7E').replace(/%20/g, '+');
        var resultadoasscarimbo = encodeURIComponent($("#resultadoasscarimbo").text()).replace(/~/g, '%7E').replace(/%20/g, '+');


//        alert(resultado);
        window.location = "<?= base_url() ?>ambulatorio/laudo/gerarpdfaih/"+ paciente_id +"?texto=" + resultado +
                "&resultadocnes=" + resultadocnes +
                "&resultadonomeexecutante=" + resultadonomeexecutante +
                "&resultadocnes2=" + resultadocnes2 +
                "&resultadojustificativa=" + resultadojustificativa +
                "&resultadocondjusti=" + resultadocondjusti +
                "&resultadoprinresult=" + resultadoprinresult +
                "&resultadodiagini=" + resultadodiagini +
                "&resultadocid10=" + resultadocid10 +
                "&resultadocid10secu=" + resultadocid10secu +
                "&resultadocid10cau=" + resultadocid10cau +
                "&resultadodescpro=" + resultadodescpro +
                "&resultadocodpro=" + resultadocodpro +
                "&resultadoclinica=" + resultadoclinica +
                "&resultadocaraterint=" + resultadocaraterint +
                "&resultadocpf=" + resultadocpf +
                "&resultadonomeprof=" + resultadonomeprof +
                "&resultadodatasolic=" + resultadodatasolic +
                "&resultadoasscarimbo=" + resultadoasscarimbo;
        
        window.print();
       
        
        
        
    }

///////NOME DO ESTABELECIMENTO SOLICITANTE:
    function calcular() {
        document.getElementById('razo_s').style.visibility = "visible";
    }
    function mostrar() {
        var n1 = document.getElementById('razo_s').value; //pega o valor do input
        document.getElementById('resultado').innerHTML = n1; //coloca na div de id resultado
        document.getElementById('resultado').style.visibility = "visible";
        document.getElementById('razo_s').style.visibility = "hidden";
    }
///////////////////////////////////////////


//////NOME DO ESTABELECIMENTO EXECUTANTE:
    function aparecercamponome() {
        document.getElementById('nomeexecutante').style.visibility = "visible";
    }
    function desapararecernome() {
        var n1 = document.getElementById('nomeexecutante').value;
        document.getElementById('resultadonomeexecutante').innerHTML = n1;
        document.getElementById('nomeexecutante').style.visibility = "hidden";
    }
/////////////////////////////////////////


////CNES:
    function aparecerinputcnes() {
        document.getElementById('inputcnes').style.visibility = "visible";
    }
    function apareceresultadocnes() {
        var n1 = document.getElementById('inputcnes').value;
        document.getElementById('resultadocnes').innerHTML = n1;
        document.getElementById('inputcnes').style.visibility = "hidden";
    }
/////////////////////////////////////////

////CNES: 2
    function aparecerinputcnes2() {
        document.getElementById('inputcnes2').style.visibility = "visible";
    }
    function apareceresultadocnes2() {
        var n1 = document.getElementById('inputcnes2').value;
        document.getElementById('resultadocnes2').innerHTML = n1;
        document.getElementById('inputcnes2').style.visibility = "hidden";
    }
/////////////////////////////////////////

////////////JUSTIFICATIVA DA INTERNAÇÃO
    function aparecerjustificativa() {
        tinymce.init({
            selector: "#inputjustificativa",
            themes: "modern",
            skin: "custom",
            language: 'pt_BR',
            setup: function (ed) {
                ed.on('change', function (e) {
                    var myText = tinyMCE.activeEditor.getContent();
                    var texto_adicional_html2 = "</html>";
                    var texto_adicional_body2 = "</body>";
                    //aqui ele tira todas as tags html e body
                    myText = myText.replace(texto_adicional_html2, "");
                    myText = myText.replace(texto_adicional_body2, "");
                    document.getElementById('resultadojustificativa').innerHTML = myText;
                });
            },
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen"
            ],
            toolbar: 'bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist | removeformat | mybutton ',
        });
    }


    function fechartinym() {
        tinymce.remove('#inputjustificativa');
    }
///////////////////////////////////////


////////CONDIÇÕES QUE JUSTIFICAM A INTERNAÇÃO: 
    function aparecerinputcondjusti() {
        document.getElementById('inputcondjusti').style.visibility = "visible";
    }
    function apareceresultadocondjusti() {
        var n1 = document.getElementById('inputcondjusti').value;
        document.getElementById('resultadocondjusti').innerHTML = n1;
        document.getElementById('inputcondjusti').style.visibility = "hidden";
    }
/////////////////////////////////////////////


////////PRINCIPAIS RESULTADOS DE PROVAS DIAGNOSTICAS (RESULTADOS DE EXAMES REALIZADOS): 
    function aparecerinputprinresult() {
        document.getElementById('inputprinresult').style.visibility = "visible";
    }
    function apareceresultadoprinresult() {
        var n1 = document.getElementById('inputprinresult').value;
        document.getElementById('resultadoprinresult').innerHTML = n1;
        document.getElementById('inputprinresult').style.visibility = "hidden";
    }
//////////////////////////////////////////////

//////////DIAGNOSTICO INICIAL:
    function aparecerinputdiagini() {
        document.getElementById('inputdiagini').style.visibility = "visible";
    }
    function apareceresultadodiagini() {
        var n1 = document.getElementById('inputdiagini').value;
        document.getElementById('resultadodiagini').innerHTML = n1;
        document.getElementById('inputdiagini').style.visibility = "hidden";
    }
//////////////////////////////////////////


//////////CID 10 PRINCIPAL:
    function aparecerinputcid10() {
        document.getElementById('inputcid10').style.visibility = "visible";
    }
    function apareceresultadocid10() {
        var n1 = document.getElementById('inputcid10').value;
        document.getElementById('resultadocid10').innerHTML = n1;
        document.getElementById('inputcid10').style.visibility = "hidden";
    }
/////////////////////////////////////

///////CID 10 SECUNDARIO:
    function aparecerinputcid10secu() {
        document.getElementById('inputcid10secu').style.visibility = "visible";
    }
    function apareceresultadocid10secu() {
        var n1 = document.getElementById('inputcid10secu').value;
        document.getElementById('resultadocid10secu').innerHTML = n1;
        document.getElementById('inputcid10secu').style.visibility = "hidden";
    }
/////////////////////////////////


/////CID 10 CAUSAS ASSOCIADAS:
    function aparecerinputcid10cau() {
        document.getElementById('inputcid10cau').style.visibility = "visible";
    }
    function apareceresultadocid10cau() {
        var n1 = document.getElementById('inputcid10cau').value;
        document.getElementById('resultadocid10cau').innerHTML = n1;
        document.getElementById('inputcid10cau').style.visibility = "hidden";
    }
///////////////////////////   




//////DESCRICAO DO PROCEDIMENTO SOLICITADO:
    function aparecerinputdescpro() {
        document.getElementById('inputdescpro').style.visibility = "visible";
    }
    function apareceresultadodescpro() {
        var n1 = document.getElementById('inputdescpro').value;
        document.getElementById('resultadodescpro').innerHTML = n1;
        document.getElementById('inputdescpro').style.visibility = "hidden";
    }
//////////////////////////////////////////////////


/////CÓDIGO DO PROCEDIMENTO:
    function aparecerinputcodpro() {
        document.getElementById('inputcodpro').style.visibility = "visible";
    }
    function apareceresultadocodpro() {
        var n1 = document.getElementById('inputcodpro').value;
        document.getElementById('resultadocodpro').innerHTML = n1;
        document.getElementById('inputcodpro').style.visibility = "hidden";
    }
//////////////////////////////////


//////CLÍNICA:
    function aparecerinputclinica() {
        document.getElementById('inputclinica').style.visibility = "visible";
    }
    function apareceresultadoclinica() {
        var n1 = document.getElementById('inputclinica').value;
        document.getElementById('resultadoclinica').innerHTML = n1;
        document.getElementById('inputclinica').style.visibility = "hidden";
    }
/////////////////////////////////////


/////////CARATER DA INTERNAÇÃO:
    function aparecerinputcaraterint() {
        document.getElementById('inputcaraterint').style.visibility = "visible";
    }
    function apareceresultadocaraterint() {
        var n1 = document.getElementById('inputcaraterint').value;
        document.getElementById('resultadocaraterint').innerHTML = n1;
        document.getElementById('inputcaraterint').style.visibility = "hidden";
    }
//////////////////////////


//////CPF
    function aparecerinputcpf() {
        document.getElementById('inputcpf').style.visibility = "visible";
    }
    function apareceresultadocpf() {
        var n1 = document.getElementById('inputcpf').value;
        document.getElementById('resultadocpf').innerHTML = n1;
        document.getElementById('inputcpf').style.visibility = "hidden";
    }
///////////////////////////////////////    


/////NOME DO PROFISSIONAL SOLICITANTE:
    function aparecerinputnomeprof() {
        document.getElementById('inputnomeprof').style.visibility = "visible";
    }
    function apareceresultadonomeprof() {
        var n1 = document.getElementById('inputnomeprof').value;
        document.getElementById('resultadonomeprof').innerHTML = n1;
        document.getElementById('inputnomeprof').style.visibility = "hidden";
    }
///////////////////////////////////////    


////DATA SOLICITAÇÃO:
    function aparecerinputdatasolic() {
        document.getElementById('inputdatasolic').style.visibility = "visible";
    }
    function apareceresultadodatasolic() {
        var n1 = document.getElementById('inputdatasolic').value;
        document.getElementById('resultadodatasolic').innerHTML = n1;
        document.getElementById('inputdatasolic').style.visibility = "hidden";
    }
///////////////////////////////////////    


///////ASSINATURA E CARIMBO (No. CR):
    function aparecerinputasscarimbo() {
        document.getElementById('inputasscarimbo').style.visibility = "visible";
    }
    function apareceresultadoasscarimbo() {
        var n1 = document.getElementById('inputasscarimbo').value;
        document.getElementById('resultadoasscarimbo').innerHTML = n1;
        document.getElementById('inputasscarimbo').style.visibility = "hidden";
    }
////////////////////////////////////

</script>
