<?php

/**
 * Template do gráfico de resultado
 * Esse template será utilizado para conversão em PDF.
 *
 *
 * @link       unitycode.tech
 * @since      1.6.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/public/partials/templates
*/
?>

<!-- CSS -->
<style>
	.checkpoint{
		position: relative;
	}
	.checkpoint:last-child{
		opacity: 0;
	}
	.checkpoint:before{
		content: '';
		width: 1px;
		height: 10px;
		background: black;
		position: absolute;
		top: -12px;
		left: 0;
	}
	.checkpoint:after{
		content: '';
		width: 1px;
		height: 240px;
		background: black;
		position: absolute;
		bottom: 64px;
		left: 0;
	}
	
	.si-horizontal:before{
		display: none;
	}
	.si-horizontal:after{
		width: 310px;
		height: 4px;
		left: 178px;
		bottom: 23px;
		background: black;
		z-index: -1;
	}
	
	.txt-top-wkn,
	.txt-top-str{
		width: 25%;
		margin-left: 54px;
		font-size: 12px;
        text-align: left;
        color: black;
        line-height: 1;
		position: absolute;
        left: 0;
	}
	.txt-top-wkn:before,
	.txt-top-wkn:after{
		content: '';
		position: absolute;
		
		width: 40px;
		height: 1px;
		border-bottom: 1px dashed black;
		left: -45px;
		top: 5px;
	}
	.txt-top-wkn:after{
		left: 70px;
	}
	
	.txt-top-str{
		margin-left: 260px;
	}
	.txt-top-str:before,
	.txt-top-str:after{
		content: '';
		position: absolute;
		
		width: 60px;
		height: 1px;
		border-bottom: 1px dashed black;
		left: -65px;
		top: 5px;
	}
	.txt-top-str:after{
		width: 100px;
		left: 45px;
	}
	
	.txt-bottom-wkn,
	.txt-bottom-str{
		width: 25%;
		margin-left: 10px;
		font-size: 12px;
        text-align: left;
		
		bottom: 15px;
        left: 0;
		position: absolute;
	}
	.txt-bottom-str{
		margin-left: 280px
	}
	
	
	.subtitle{
		font-size: 15px;
		text-transform: uppercase;
		display: block;
		position: relative;
		margin: 12px auto;
	}
	.subtitle:before{
		content: '';
		width: 14px;
		height: 14px;
		left: 0px;
		top: 0px;
		background: black;
		position: absolute;
	}
	
	
	.c-red{
		color: red;
	}
	.bg-red,
	.c-red:before{
		background: red;
	}
	
	.c-blue{
		color: cornflowerblue;
	}
	.bg-blue,
	.c-blue:before{
		background: cornflowerblue;
	}
	
	.c-yellow{
		color: #F29F05;
	}
	.bg-yellow,
	.c-yellow:before{
		background: #F29F05;
	}
	
	.c-green{
		color: green;
	}
	.bg-green,
	.c-green:before{
		background: green;
	}
	
</style>

<!-- #container  -->
<div class="" style="width: 560px; min-height: 300px; position: relative; display: block; margin: 50px auto; border-bottom: 1px solid black">
	
	<!-- Textos top	 -->
	<span class="txt-top-wkn">Fraquezas</span>
	<span class="txt-top-str">Forças</span>
	
	<!-- subtitles	 -->
	<div class="" style="position: absolute; margin-left: 410px; top: 25px;">
		<span class="subtitle c-red">Pragmático</span>
		<span class="subtitle c-blue">Afetivo</span>
		<span class="subtitle c-yellow">Visionário</span>
		<span class="subtitle c-green">Racional</span>
	</div>
	
	<!-- bars	 -->
	<div class="" style="position: absolute; background: none; width: 100%; height: 200px; left: 0; top: 40px; z-index: 1;">
		<span class="bg-red" style="height: 50px; width: 320px; display: block;"></span>
		<span class="bg-blue" style="height: 50px; width: 260px; display: block;"></span>
		<span class="bg-yellow" style="height: 50px; width: 160px; display: block;"></span>
		<span class="bg-green" style="height: 50px; width: 180px; display: block;"></span>
	</div>
	
	<!-- Textos bottom	 -->
	<span class="txt-bottom-wkn">Muito fraco</span>
	<span class="txt-bottom-str">Muito forte</span>

	<!--  Checkpoints e guias	 -->
	<div class="" style="position: absolute; bottom: -25px; left: 0; width: 100%; display: inline-flex;">
		
		<span class="checkpoint min-ptos" data-value="0">0</span> <!--  #footer-line	 -->
		<span class="checkpoint" data-value="40">40</span> <!--  #footer-line	 -->
		<span class="checkpoint" data-value="80">80</span> <!--  #footer-line	 -->
		<span class="checkpoint" data-value="110">110</span> <!--  #footer-line	 -->
		<span class="checkpoint max-ptos" data-value="150">150</span> <!--  #footer-line	 -->
		<span class="checkpoint si-horizontal"></span> <!--  #footer-line	 -->
		<span class="checkpoint"></span> <!--  #footer-line	 -->
		
	</div>
	<span class="middle-point-wkn-str" style="position: absolute; width: 1px; height: 350px; bottom: -10px; left: 0; margin-left: 180px; border-right: 1px dashed purple; z-index: -1"></span>
</div> <!--/End-tpl --> 

<?php
$str_ptos 			= $data[1];
$wkn_ptos 			= $data[2];
$ptos_afetivo 		= $data[3];
$ptos_pragmatico 	= $data[4];
$ptos_racional 		= $data[5];
$ptos_visionario 	= $data[6];
$total_ptos 		= $data[7];

?>

<script>
function setCheckpointsPosition()
{
	/**
	 * Seta o posicionamento das guias de pontuação do gráfico;
	 */
	var checkpoints		= jQuery('.checkpoint'),
		distancePtos 	= 300 / 150;

	checkpoints.each(function (index) {
		var attr = jQuery(this).attr('data-value');

		if( typeof attr !== 'undefined' ){
			currEl 			= jQuery(this);
			currDataVal 	= attr;
			currEl.css({
				'left': (currDataVal * distancePtos) + 'px', // Não está ficando certo a distÂncia;
			})
		}
	});

}

function distanceIntoPtosValue()
{
	setCheckpointsPosition();
	// Cálculo do valor de distancePtos
	var maxPtosEl 		= document.getElementsByClassName('max-ptos');
		distanceRange 	= maxPtosEl[0].offsetLeft,
		distancePtos 	= distanceRange / 150;

	// Pontuação direto do backend ~após validar a poc isso deve ser passado por ajax~
	var pragmaticTotal  = '<?php echo $ptos_pragmatico; ?>',
		affectiveTotal 	= '<?php echo $ptos_afetivo; ?>',
		visionaryTotal 	= '<?php echo $ptos_visionario; ?>',
		rationalTotal 	= '<?php echo $ptos_racional; ?>';

	// Potuação > equivalente em pixels do gráfico
	var pragmaticValuePx 	= (pragmaticTotal * distancePtos) + 'px',
		affectiveValuePx 	= (affectiveTotal * distancePtos) + 'px',
		visionaryValuePx	= (visionaryTotal * distancePtos) + 'px',
		rationalValuePx 	= (rationalTotal * distancePtos) + 'px';

	// Elementos do gráfico
	var pragmaticBlock 	= document.getElementsByClassName('bg-red'),
		affectiveBlock 	= document.getElementsByClassName('bg-blue'),
		visionaryBlock 	= document.getElementsByClassName('bg-yellow'),
		rationalBlock 	= document.getElementsByClassName('bg-green');

	pragmaticBlock[0].style.width 	= pragmaticValuePx;
	affectiveBlock[0].style.width 	= affectiveValuePx;
	visionaryBlock[0].style.width 	= visionaryValuePx;
	rationalBlock[0].style.width 	= rationalValuePx;

}
distanceIntoPtosValue();
</script>