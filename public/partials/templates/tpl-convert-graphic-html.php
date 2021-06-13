<!-- 
	Tamanho total: 860px = 227.56mm
	Container do gráfico: 480px
	Medida de distância: 3,2 - Ou seja 480 / 150;
 -->

<!-- CSS -->
<style>
	.g-container{
		width: 760px; 
		min-height: 380px; 
		position: relative; 
		display: block; 
		margin: 50px auto; 
		border-bottom: 1px solid black;
	}
	.graphic-container{
		width: 480px;
		min-height: 360px;
		display: grid;
		position: relative;
	}

	/* boxCaption */
	.boxCaption{
		width: 100%;
		position: relative;
		left: 0;

		display: flex;
		justify-content: center;
	}
	.boxCaption__children{
		width: 50%;
		position: relative;
		padding: 0 8px;

		display: flex;
		align-items: center;
	}
	.boxCaption__text{
		font-size: 14px;
        text-align: left;
        color: black;
        line-height: 1;
		margin: 0 6px;
	}
	.boxCaption__before,
	.boxCaption__after{
		width: 25%;
		height: 1px;
		border-top: 1px dashed #000;
	}

	/* .boxBars */
	.boxBars{
		background: none;
		width: 100%; 
		height: 200px;
		margin-top: 24px;
		z-index: 1;
	}
	.boxBars__item{
		width: 320px;
		height: 50px;
		margin: 1px 0;
		display: block;
	}

	/* .BoxSubtitle */
	.boxSubtitle{
		position: absolute; 
		margin-left: 500px; 
		top: 75px;
	}

	/* .middleCheckpoint */
	.middleLine{
		width: 1px;
		height: 380px;
		border-right: 1px dashed purple; 
		position: absolute;
		bottom: -30px;
		z-index: -1;
	}

	/* .boxGuideLine */
	.boxGuideLine{
		width: 100%; 
		display: inline-flex;
		bottom: -25px;
		left: 0; 
		position: absolute; 
	}
	.boxGuideLine__checkpoint{
		position: absolute;
	}
	.boxGuideLine__checkpoint--topDivisor{
		height: 280px; 
		width: 1px; 
		border-right: 1px black solid; 
		bottom: 80px; 
		left: 0;
		position: absolute; 
	}
	.boxGuideLine__checkpoint--bottomDivisor{
		height: 20px; 
		width: 1px;
		border-right: 1px black solid; 
		bottom: 18px; 
		left: 0;
		position: absolute;
	}
	.boxGuideLine__checkpoint--highScore{
		width: 400px; 
		height: 3px; 
		background: black; 
		top: -6px; 
		left: 0;
		position: absolute;
	}

	/* .boxSubtitle */
	.boxSubtitle{
		min-width: 220px;
		top: 50px;
		left: 50px; 
		position: absolute; 
	}

	.boxSubtitle__item{
		font-size: 15px;
		text-transform: uppercase;
		display: block;
		position: relative;
		margin: 12px auto;
	}
	.boxSubtitle__square{
		content: '';
		width: 14px;
		height: 14px;
		left: -20px;
		top: 2px;
		background: black;
		position: absolute;
	}
	
	/* Colors */
	.c-pragmatic{
		color: red;
	}
	.c-affective{
		color: cornflowerblue;
	}
	.c-visionary{
		color: #F29F05;
	}
	.c-rational{
		color: green;
	}
	
	/* Backgrounds */
	.bg-pragmatic{
		background: red;
	}
	.bg-affective{
		background: cornflowerblue;
	}
	.bg-visionary{
		background: #F29F05;
	}
	.bg-rational{
		background: green;
	}
</style>

<!-- #container  -->
<div class="g-container">
	
	<div class="graphic-container">
		<!-- Leg: Fraquezas & Forças -->
		<div class="boxCaption">
			<div class="boxCaption__children">
				<div class="boxCaption__before"></div>
				<span class="boxCaption__text">Fraquezas</span>
				<div class="boxCaption__after"></div>
			</div>
			
			<div class="boxCaption__children" style="justify-content: flex-end;">
				<div class="boxCaption__before"></div>
				<span class="boxCaption__text">Forças</span>
				<div class="boxCaption__after"></div>
			</div>
		</div>
		
		<!-- bars -->
		<div class="boxBars">
			<div class="boxBars__item bg-pragmatic"></div>
			<div class="boxBars__item bg-affective"></div>
			<div class="boxBars__item bg-visionary"></div>
			<div class="boxBars__item bg-rational"></div>
		</div>

		<!-- Leg: Textos bottom -->
		<div class="boxCaption">
			<div class="boxCaption__children">
				<span class="boxCaption__text">Muito fraco</span>
			</div>
			
			<div class="boxCaption__children" style="justify-content: flex-end;">
				<span class="boxCaption__text">Muito forte</span>
			</div>
		</div>

		<!--  Checkpoints e guias	 -->
		<div class="boxGuideLine">
			
			<div class="boxGuideLine__checkpoint" style="left: 0;">0
				<div class="boxGuideLine__checkpoint--topDivisor"></div>
				<div class="boxGuideLine__checkpoint--bottomDivisor"></div>
			</div>
			<div class="boxGuideLine__checkpoint" style="left: 128px;">40 
				<div class="boxGuideLine__checkpoint--topDivisor"></div>
				<div class="boxGuideLine__checkpoint--bottomDivisor"></div>
			</div>
			<div class="boxGuideLine__checkpoint" style="left: 256px;">80
				<div class="boxGuideLine__checkpoint--topDivisor"></div>
				<div class="boxGuideLine__checkpoint--bottomDivisor"></div>
			</div>
			<div class="boxGuideLine__checkpoint" style="left: 352px;">110
				<div class="boxGuideLine__checkpoint--topDivisor"></div>
				<div class="boxGuideLine__checkpoint--bottomDivisor"></div>
				<div class="boxGuideLine__checkpoint--highScore"></div>
			</div>
			<div class="boxGuideLine__checkpoint" style="left: 480px;">150
				<div class="boxGuideLine__checkpoint--topDivisor"></div>
				<div class="boxGuideLine__checkpoint--bottomDivisor"></div>
			</div>
			
		</div>
		<div class="middleLine" style="left: 256px;"></div>
	</div>

	<!-- .boxSubtitle -->
	<div class="boxSubtitle">
		<div class="boxSubtitle__item">
			<div class="boxSubtitle__square bg-pragmatic"></div>
			<div class="c-pragmatic">Pragmático</div>
		</div>
		<div class="boxSubtitle__item">
			<div class="boxSubtitle__square bg-affective"></div>
			<div class="c-affective">Afetivo</div>
		</div>
		<div class="boxSubtitle__item">
			<div class="boxSubtitle__square bg-visionary"></div>
			<div class="c-visionary">Visionário</div>
		</div>
		<div class="boxSubtitle__item">
			<div class="boxSubtitle__square bg-rational"></div>
			<div class="c-rational">Racional</div>
		</div>
	</div>
</div> <!--/End-tpl -->