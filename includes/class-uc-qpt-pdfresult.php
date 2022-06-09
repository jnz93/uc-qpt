<?php
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\FpdfTpl;
/**
 * The Helper functionalities for work with html > pdf
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/admin
 * @author     jnz93 <contato@unitycode.tech>
 */
class Uc_Qpt_PDFResult {
	
	/**
	 * Recebe $data e executa os processos em ordem cronológica
	 * 
	 * @param int $id identificação do voucher submetido
	 * 
	 */
	public function run( $id = null )
	{
		// Tratamento dos dados
		$result		= Uc_Qpt_PDFResult::_sanitize_result( $id );
		$profile	= $result['profile']['strengths'];
		$points		= $result['points'];

		// Produção
		$file_html 		= Uc_Qpt_PDFResult::_create_graphic_html( $points );
		$file_tpl 		= Uc_Qpt_PDFResult::_import_model( $profile );
		$content 		= Uc_Qpt_PDFResult::_sanitize_content( $file_html );
		$pdf 			= Uc_Qpt_PDFResult::_convert_to_pdf( $content );
		$file_img		= Uc_Qpt_PDFResult::_convert_to_image( $pdf );
		$file_result	= Uc_Qpt_PDFResult::_handle_graphic( $file_tpl, $file_img, $pdf, $id );
		$attach_id 		= Uc_Qpt_PDFResult::_upload( $file_result, $id );

		// Limpeza de arquivos temporários
		Uc_Qpt_PDFResult::_clean_trash();

		return $attach_id;
	}
	
	/**
	 * Recebe um id pega os dados no banco, organiza e retorna em um array
	 * 
	 * @param int $id
	 * @return array $data
	 * 
	 * @since 
	 */
	private function _sanitize_result( $id = null )
	{
		if ( $id == null ) return;

		$key 		= 'ucqpt_test_result_data';
		$data 		= get_post_meta( $id, $key, true );
		$data 		= explode( '|', $data );

		$sanitized_data = array(
			'ID'				=> $data[0],
			'profile'			=> array(
				'strengths' 	=> str_replace( ':', '', substr( $data[1], strpos( $data[1], ':' ) ) ),
				'weaknesses'	=> str_replace( ':', '', substr( $data[2], strpos( $data[2], ':' ) ) ),
			),
			'points'			=> array(
				'affective'		=> str_replace( ':', '', substr( $data[3], strpos( $data[3], ':' ) ) ),
				'pragmatic'		=> str_replace( ':', '', substr( $data[4], strpos( $data[4], ':' ) ) ),
				'rational'		=> str_replace( ':', '', substr( $data[5], strpos( $data[5], ':' ) ) ),
				'visionary'		=> str_replace( ':', '', substr( $data[6], strpos( $data[6], ':' ) ) ),
				'total'			=> str_replace( ':', '', substr( $data[7], strpos( $data[7], ':' ) ) ),
			),
		);

		return $sanitized_data;
	}

	/**
	 * Criar o arquivo HTML com o gráfico de resultado
	 * 
	 * @param array $data dados do resultado
	 * 
	 * @since 
	 */
	private function _create_graphic_html( $data = null )
	{
		if ( $data == null ) return;

		$rand = wp_generate_password( 4 );
		$path = plugin_dir_path( dirname(__FILE__) ) . 'trash/' . $rand . '.html';

		$points = array();
		if ( ! empty( $data ) ) :
			foreach ( $data as $item => $value ) :
				
				$sum 			= ( $value * 3.2 ); # 3.2 = 480(width) / 150(maxptos)
				$points[$item] 	= $item != 'total' ? $sum : $value;

			endforeach;
		endif;
		$outputHtml .= "<style>.g-container{width: 760px; height: 380px; position: relative; display: block; margin: 50px 0;}.graphic-container{width: 480px;height: 360px;position: relative;}.boxCaption{width: 100%;position: relative;left: 0; top: 10px;}.boxCaption__children{width: 50%;position: absolute;}.boxCaption__text{font-size: 14px;text-align: left;color: black;line-height: 1;margin: 0 6px;}.boxCaption__before,.boxCaption__after{width: 25%;height: 1px;border-top: 1px dashed #000;top: 8px;position: absolute;}.boxBars{background: none;width: 100%; height: 200px;margin-top: 64px;z-index: 1;}.boxBars__item{width: 320px;height: 50px;margin: 1px 0;display: block;}.boxSubtitle{position: absolute; margin-left: 500px; top: 75px;}.middleLine{width: 1px;height: 380px;border-right: 1px dashed purple; position: absolute;bottom: -30px;z-index: -1;}		.boxGuideLine{width: 100%; display: inline-flex;bottom: -25px;left: 0; position: absolute; }.boxGuideLine__checkpoint{position: absolute;}.boxGuideLine__checkpoint--topDivisor{height: 280px; width: 1px; border-right: 1px black solid; bottom: 80px; left: 0;position: absolute; }.boxGuideLine__checkpoint--bottomDivisor{height: 20px; width: 1px;border-right: 1px black solid; bottom: 18px; left: 0;position: absolute;}.boxGuideLine__checkpoint--highScore{width: 400px; height: 3px; background: black; top: -6px; left: 0;position: absolute;}.boxSubtitle{min-width: 220px;top: 50px;left: 50px; position: absolute; }.boxSubtitle__item{font-size: 15px;text-transform: uppercase;display: block;position: relative;margin: 12px auto;}.boxSubtitle__square{width: 14px;height: 14px;left: -20px;top: 2px;background: black;position: absolute;}.c-pragmatic{color: red;}.c-affective{color: cornflowerblue;}.c-visionary{color: #F29F05;}.c-rational{color: green;}.bg-pragmatic{background: red;}.bg-affective{background: cornflowerblue;}.bg-visionary{background: #F29F05;}.bg-rational{background: green;}</style>";

		$outputHtml .= '<!-- #container  -->
		<div class="g-container">
			<div class="graphic-container">
				<div class="boxCaption">
					<div class="boxCaption__children" style="left: 0px;">
						<div class="boxCaption__before" style="left: 30px;"></div>
						<span class="boxCaption__text" style="margin-left: 90px;">Fraquezas</span>
						<div class="boxCaption__after" style="right: 18px;"></div>
					</div>
					
					<div class="boxCaption__children" style="right: 0px">
						<div class="boxCaption__before" style="left: 50px;"></div>
						<span class="boxCaption__text" style="margin-left: 112px;">Forcas</span>
						<div class="boxCaption__after" style="right: 0;"></div>
					</div>
				</div>
				
				<!-- bars -->
				<div class="boxBars">
					<div class="boxBars__item bg-pragmatic" style="width: '. $points['pragmatic'] .'"></div>
					<div class="boxBars__item bg-affective" style="width: '. $points['affective'] .'"></div>
					<div class="boxBars__item bg-visionary" style="width: '. $points['visionary'] .'"></div>
					<div class="boxBars__item bg-rational" style="width: '. $points['rational'] .'"></div>
				</div>
		
				<!-- Leg: Textos bottom -->
				<div class="boxCaption">
					<div class="boxCaption__children" style="left: 20px; top: 20px;">
						<span class="boxCaption__text">Muito fraco</span>
					</div>
					
					<div class="boxCaption__children" style="right: 30px; top: 20px; text-align: right;">
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
						<div class="boxGuideLine__checkpoint--highScore" style="left: -350px; height: 1px; top: -5px;"></div>
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
					<div class="c-pragmatic">Pragmatico</div>
				</div>
				<div class="boxSubtitle__item">
					<div class="boxSubtitle__square bg-affective"></div>
					<div class="c-affective">Afetivo</div>
				</div>
				<div class="boxSubtitle__item">
					<div class="boxSubtitle__square bg-visionary"></div>
					<div class="c-visionary">Visionario</div>
				</div>
				<div class="boxSubtitle__item">
					<div class="boxSubtitle__square bg-rational"></div>
					<div class="c-rational">Racional</div>
				</div>
			</div>
		</div>';

		$doc = new DOMDocument();
		$doc->loadHTML($outputHtml);
		$doc->saveHTMLFile( $path );

		return $path;
	}

	/**
	 * Retorna PDF que será manipulado
	 * 
	 * @param string $result 	o perfil do resultado do teste, ex: Afetivo/Racional
	 * @since 
	 */
	private function _import_model( $result = null )
	{
		if ( $result == null ) return;

		$ext 				= '.pdf';
		$path 				= plugin_dir_path( dirname( __FILE__ ) ) . 'includes/library/results/';
		$sanitizedResult 	= strtolower( str_replace( array('/', 'á'), array('-', 'a'), $result ) );
		$file_name 			= $sanitizedResult;
		$file_path 			= $path . $file_name . $ext;
		$file_exists 		= file_exists( $file_path );

		if ( !$file_exists ){
			$resultArr 		= explode( '-', $sanitizedResult );
			$profilesTotal 	= count( $resultArr );

			if( $profilesTotal == 4 ){
				unset( $resultArr[$profilesTotal-1] );
				$profilesTotal 	= count( $resultArr );

				$file_name 		= implode( '-', $resultArr );
				$file_path 		= $path . $file_name . $ext;
				$file_exists 	= file_exists( $file_path );					

				if( $file_exists ) return $file_path;
			}

			if( $profilesTotal == 3 ){
				unset( $resultArr[$profilesTotal-1] );
				$profilesTotal 	= count( $resultArr );

				$file_name 		= implode( '-', $resultArr );
				$file_path 		= $path . $file_name . $ext;
				$file_exists 	= file_exists( $file_path );

				if( $file_exists ) return $file_path;
			}

			if( $profilesTotal == 2 ){
				$resultArr 		= array_reverse( $resultArr );

				$file_name 		= implode( '-', $resultArr );
				$file_path 		= $path . $file_name . $ext;
				$file_exists 	= file_exists( $file_path );

				return $file_path;
			}			
		}

		return $file_path;
	}

	/**
	 * Retorna o conteúdo HTML do arquivo recebido como parâmetro
	 * 
	 * @param string $archive caminho do arquivo html
	 * @return string $content conteúdo html
	 * 
	 * @since 
	 */
	private function _sanitize_content( $archive_path = null )
	{
		if ( $archive_path == null ) return;
		
		$html = file_get_contents( $archive_path );
		$html = stripcslashes($html);
		ob_start(); ?>
		
		<page style="font-size: 14px">
			<?php print $html; ?>
		</page>
		
		<?php
		$content = ob_get_contents();
		ob_clean();

		return $content;
	}

	/**
	 * Recebe o conteúdo HTML e converte para PDF no final retorna caminho/path do arquivo
	 * 
	 * @param string $content html que será convertido
	 * 
	 * @since
	 */
	private function _convert_to_pdf( $content = null )
	{
		if ( $content == null ) return;

		require_once( plugin_dir_path( __FILE__ ) . 'library/html2pdf_lib/html2pdf.class.php');
		require_once( plugin_dir_path( __FILE__ ) . 'library/FPDI-2.3.6/src/autoload.php');

		try
		{
			$rand		= wp_generate_password( 4, false );
			$path_pdf 	= plugin_dir_path( dirname( __FILE__ ) ) . 'trash/' . $rand . '.pdf';

			$html2pdf 	= new HTML2PDF('P', 'A4', 'en');
			$html2pdf->setDefaultFont( 'courier' );
			$html2pdf->writeHTML( $content );
			$file 		= $html2pdf->Output($path_pdf, 'F');
			
			return $path_pdf;
			# Remove temp pdf
			// unlink('temp.pdf');
		}
		catch ( HTML2PDF_exception $e )
		{
			echo $e;
			exit;
		}
	}

	/**
	 * Converte PDF em imagem com ajuda da classe Imagick
	 * 
	 * @param string $file_path caminho do arquivo que será manipulado
	 * @return string $image_path
	 */
	private function _convert_to_image( $file_path = null )
	{
		if ( $file_path == null ) return;

		$img = new imagick( $file_path );
		$img->setImageFormat( "jpg" );
		$image_path = plugin_dir_path( dirname( __FILE__ ) ) . 'trash/img/' . time(). '.png';
		$img->setSize(300, 200);
		$img->writeImage( $image_path );
		$img->clear();
		$img->destroy();

		return $image_path;
	}

	/**
	 * Insere uma imagem(png,jpg) na página 4 do arquivo pdf
	 * Caso não encontre o modelo do resultado salva apenas o gráfico gerado em pdf
	 * 
	 * @param string $model_file - abs path do modelo de resultado
	 * @param string $graphic_png - abs path do gráfico em png
	 * @param string $graphic_pdf - abs path do gráfico em pdf
	 * 
	 * @return string $file
	 */
	private function _handle_graphic( $model_file = null, $graphic_png = null, $graphic_pdf, $voucher_id )
	{
		if ( $graphic_png == null ) return;
		
		$file_url;
		$file_name;
		$user_name = get_post_meta( $voucher_id, 'ucqpt_costumer_name', true );
		if ( ! $model_file ) : 

			# Se não houver pdf modelo
			$arr 		= explode( '/', $graphic_pdf );
			$file_name 	= end( $arr );

		else :

			require_once( plugin_dir_path( __FILE__ ) . 'library/fpdf/fpdf.php');
			require_once( plugin_dir_path( __FILE__ ) . 'library/FPDI-2.3.6/src/autoload.php');
	
			$pdf 		= new FPDI();
			$pagecount 	= $pdf->setSourceFile( $model_file );
	
			for ( $i = 1; $i <= $pagecount; $i++ ) :
				$pdf->AddPage();
				$tpl = $pdf->importPage( $i );
				
				if ( $i == 1 ) :
					$pdf->useTemplate( $tpl, 0, 0, 210 );
					$pdf->SetFont( 'Arial', '',14 );
					$pdf->SetXY( 65, 215 );
					$pdf->Write( 5, $user_name );
				endif;

				if ( $i == 4 ) :
	
					$pdf->useTemplate( $tpl, 0, 0, 210 );
					$pdf->SetXY( 90, 160 );
					$pdf->Image( $graphic_png, 16, 60, 110, 150 ); #Image(string file [, float x [, float y [, float w [, float h [, string type [, mixed link]]]]]])
	
				endif;
	
				$pdf->useImportedPage( $tpl );
			endfor;
	
			$trash_dir 	= plugin_dir_path( dirname( __FILE__ ) ) . 'trash/';
			$file_name	= wp_generate_password( 6, false ) . '.pdf';
			$file_path	= $trash_dir . $file_name;
	
			$pdf->Output( $file_path , 'F');

		endif;

		# Definição da url do arquivo que será upado
		$file_url 	= plugin_dir_url( dirname( __FILE__ ) ) . 'trash/' . $file_name;

		return $file_url;
	}

	/**
	 * Recebe uma url para upload utilizando media_handle_sideload();
	 * 
	 * @param string $file url
	 * @param int $id id parent/voucher
	 * @return int $attach_id
	 */
	private function _upload( $file = null, $id )
	{
		if ( $file == null ) return;

		$parent_id 		= $id;
		$file_name 		= basename( $file );
		$file_tmp 		= download_url( $file );

		$file_array = array(
			'name' => $file_name,
			'tmp_name' => $file_tmp
		);

		if ( is_wp_error( $file_tmp ) ) :
			@unlink( $file_array['tmp_name']);
			return $tpm;
		endif;

		$attach_id = media_handle_sideload( $file_array, $parent_id );

		if ( is_wp_error( $attach_id ) ) :
			@unlink( $file_array['tmp_name'] );
			return $attach_id;
		endif;

		return $attach_id;
	}

	/**
	 * Executa uma limpeza deletando todos os arquivos temporários dentro da pasta /trash
	 * 
	 * @since 
	 */
	private function _clean_trash()
	{
		$trash 		= plugin_dir_path( dirname( __FILE__ ) ) . 'trash/';
		$trash_img 	= plugin_dir_path( dirname( __FILE__ ) ) . 'trash/img/';

		array_map('unlink', glob( $trash . '*.html') );
		array_map('unlink', glob( $trash . '*.pdf') );
		array_map('unlink', glob( $trash_img . '*.png') );
	}

}
