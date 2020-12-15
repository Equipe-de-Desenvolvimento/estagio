<html>
    <head>
        <title>Impressao</title>
    </head>
    <body>
        <div style="text-align: center;">
           <b >INSTRUMENTO PARTICULAR DE CONFISSÃO DE DÍVIDA</b>
        </div>
        <br>
         <br>
       
         <b>CREDOR: <u><?= $empresas[0]->razao_social; ?>.</u></b>,
sociedade empresária limitada inscrita no CNPJ n° <?= preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $empresas[0]->cnpj); ?>, com sede estabelecida na Avenida Ibirapuera, n° 2907, conjunto 1620, Indianópolis, São Paulo/SP, CEP 04029-200;

DEVEDOR(A): <u><?= $pacientes[0]->nome; ?></u>, brasileiro, estado civil, profissão, portador da CIRG n.º <?= $pacientes[0]->rg; ?>, inscrito no CPF sob n.º <?= preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $pacientes[0]->cpf); ?>, residente e domiciliada em  <?= $pacientes[0]->logradouro; ?>          ,  <?= $pacientes[0]->numero; ?>  ,  <?= $pacientes[0]->bairro; ?> , <?= $pacientes[0]->cidade; ?> /<?= $pacientes[0]->estado; ?> , <?= $pacientes[0]->cep; ?>. 

Pelo presente instrumento particular e na melhor forma de direito, confessam e assumem como líquida e certa a dívida a seguir descrita:
<br>
<br>
<b><u>Cláusula 1º.</u></b> Ressalvadas quaisquer outras obrigações aqui não incluídas, pelo presente instrumento e na melhor forma de direito, o <b>DEVEDOR</b> confessa dever ao <b>CREDOR</b> a quantia líquida, certa e exigível no valor de R$ <?=number_format($valor_total,2,",","."); ; ?> (<?= $extenso; ?>).
<br>
<br>
<b><u>Cláusula 2º.</u></b> O valor pactuado entre as partes decorre de dívida oriunda   de contrato de serviços médico-hospitalares para procedimento <u><? foreach($procedimentos as $item){ echo $item->procedimento.", "; }?></u> firmado entre as partes em <?= date('d',strtotime($tcd[0]->data_cadastro))?>.<?= date('m',strtotime($tcd[0]->data_cadastro))?>.<?= date('Y',strtotime($tcd[0]->data_cadastro))?>, e que não foi devidamente quitado. 
<br>
<br>
 <b>PÁRAGRAFO ÚNICO:</b> Os serviços médico-hospitalares foram devidamente efetivados e confirmados pelo  <b>DEVEDOR</b>, dando-se por satisfeito com o trabalho desenvolvido. 
<br>
<br>
<b><u>Cláusula 3º.</u></b>  A dívida ora reconhecida e assumida pelo <b>DEVEDOR</b> como líquida, certa e exigível, no valor acima mencionado, aplica-se o disposto no artigo 784, III, do Código de Processo Civil Brasileiro, haja vista o caráter de título executivo extrajudicial do presente instrumento de confissão de dívida. 
<br>
<br>
<b>Parágrafo único:</b>  O <b>DEVEDOR</b> renuncia expressamente a qualquer contestação quanto ao valor e procedência da dívida. 
<br>
<br>
 <b><u>Cláusula 4º.</u></b>  O valor da referida dívida ora reconhecida será devidamente quitada na data de <?=  date('d/m/Y', strtotime("+ 60 days", strtotime($tcd[0]->data_cadastro)));?>, mediante transferência bancária ou depósito bancário junto ao Banco Santander, agência 2061, conta corrente 13001129-2, titularidade de <?= $empresas[0]->razao_social; ?>, inscrito no CNPJ/MF nº <?= preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $empresas[0]->cnpj); ?>.  	
<br>
<br>
<b><u>Cláusula 5º.</u></b>  O descumprimento da cláusula quarta, acarretará o vencimento antecipado da dívida e no ajuizamento imediato da ação pertinente, nos termos do art. 771 ao 778, do Novo Código de Processo Civil/2015, contra o <b>DEVEDOR</b> retro qualificado, acrescida de juros de 1% (um por cento), correção monetária, multa por inadimplemento de 10% (dez por cento) custas e honorários advocatícios na base de 20% (vinte por cento).
<br>
<br>
<b><u>Cláusula 6º.</u></b>  O descumprimento da cláusula quarta, ensejará no encaminhamento do presente termo para protesto, ficando as custas cartorárias de total responsabilidade do <b>DEVEDOR</b>, devendo ser adimplidas em cartório. 
<br>
<br>
<b><u>Cláusula 7º.</u></b>  A eventual tolerância à infringência de qualquer das cláusulas deste instrumento ou o não exercício de qualquer direito nele previsto constituirá mera liberalidade do <b>CREDOR</b>, não implicando em novação ou transação de qualquer espécie. 
<br>
<br>
<b><u>Cláusula 8º.</u></b>  Após o pagamento da importância pactuada na cláusula quarta, servirá a presente composição de instrumento de quitação, nos termos do art. 320 do Código Civil. 
<br>
<br>
<b><u>Cláusula 9º.</u></b>  O presente contrato é celebrado em caráter irrevogável e irretratável. 
<br>
<br>
<b>Parágrafo único:</b>   Serão consideradas como verdadeiras as assinaturas e declarações constantes no presente contrato, sujeitando-se as penalidades previstas no Código Civil.
<br>
<br>
<b><u>Cláusula 10º.</u></b>  O presente contrato obriga, em todos os termos, herdeiros e sucessores de ambas as partes, no seu fiel cumprimento, a qualquer título. 
<br>
<br>
<b><u>Cláusula 11º.</u></b> Fica eleito o Foro Central da Comarca de São Paulo, para dirimir quaisquer dúvidas oriundas do presente contrato, renunciando-se a qualquer outro por mais privilegiado que seja. 
<br>
<br>
E, assim, por estarem justos e contratados na forma acima, assinam e (rubricam) a presente confissão de dívida, em 3 (três) vias de igual teor e forma, na presença de 2(duas) testemunhas que a tudo assistiram.
<br>
<br>
<?php 
switch (date("m")) {
        case "01":    $mes = 'Janeiro';     break;
        case "02":    $mes = 'Fevereiro';   break;
        case "03":    $mes = 'Março';       break;
        case "04":    $mes = 'Abril';       break;
        case "05":    $mes = 'Maio';        break;
        case "06":    $mes = 'Junho';       break;
        case "07":    $mes = 'Julho';       break;
        case "08":    $mes = 'Agosto';      break;
        case "09":    $mes = 'Setembro';    break;
        case "10":    $mes = 'Outubro';     break;
        case "11":    $mes = 'Novembro';    break;
        case "12":    $mes = 'Dezembro';    break; 
 }
 
?>
São Paulo, <?= date('d')?> de <?= $mes; ?> de <?= date('Y');?>. 
<br>
<br>
______________________________________________________________
<br>
<b>NOME COMPLETO DO DEVEDOR <b>
<br>
<br>
__________________________________________________________
<br> 
<b><?= $empresas[0]->razao_social; ?> </b>
<br>
<br> 
TESTEMUNHAS: 
<br>
<br>
_________________________________________<br>
Nome <br>
CPF<br>
CIRG nº.
<br>
<br>


_________________________________________<br>
Nome<br>
CPF<br>
CIRG nº.<br>


    </body>
</html>
