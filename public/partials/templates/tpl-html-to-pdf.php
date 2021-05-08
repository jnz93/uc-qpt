<?php

/**
 * Template do gráfico de resulto
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
		width: 200px;
		height: 4px;
		left: -95px;
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
		margin-left: 370px
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
		left: -20px;
		top: 2px;
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
	<div class="" style="position: absolute; bottom: -25px; left: 0; width: 100%; display: inline-flex; justify-content: space-between">
		
		<span class="checkpoint">0</span> <!--  #footer-line	 -->
		<span class="checkpoint">10</span> <!--  #footer-line	 -->
		<span class="checkpoint">20</span> <!--  #footer-line	 -->
		<span class="checkpoint">30</span> <!--  #footer-line	 -->
		<span class="checkpoint">40</span> <!--  #footer-line	 -->
		<span class="checkpoint si-horizontal">50</span> <!--  #footer-line	 -->
		<span class="checkpoint"></span> <!--  #footer-line	 -->
		
	</div>
	<span class="middle-point-wkn-str" style="position: absolute; width: 1px; height: 350px; bottom: -20px; left: 0; margin-left: 185px; border-right: 1px dashed purple; z-index: -1"></span>
</div>