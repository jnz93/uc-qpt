<?php
/**
 * Classe responsável por disparar e-mails contendo o resultado de algum teste
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/includes
 * @author     jnz93 <contato@unitycode.tech>
 */
class Uc_Qpt_EmailResult{
    
    /**
     * Executa as ações de disparo de e-mail com resultado do teste
     * 
     * @param int $id; Id do voucher
     */
    public function send( $id )
    {
        if ( ! $id ) return;

        $is_voucher = Uc_Qpt_EmailResult::_isVoucher( $id );
        $sendEmail  = false;
        if ( $is_voucher ) :

            $attachment     = Uc_Qpt_EmailResult::_getDoc( $id );
            $userData       = Uc_Qpt_EmailResult::_getUserData( $id );
            $sendEmail      = Uc_Qpt_EmailResult::_sendEmail( $attachment, $userData );

        endif;
        
        return $sendEmail;
    }

    /**
     * Envia a notificação com log da conclusão do inventário
     * 
     * @param array     $data
     */
    public function sendAdminNotification( $data )
    {
        $log            = $data['log'];
        $attach         = $data['attach'];
        $attachLink     = get_permalink( $attach );

        $voucherID      = wp_get_post_parent_id( $attach );
        $voucher        = get_the_title( $voucherID );
        $companyID      = get_the_author_ID( $voucherID );
        $company        = get_userdata( $companyID );
        $companyName    = $company->display_name;
        $voucherUser    = get_post_meta( $voucherID, 'ucqpt_costumer_name', true );

        $adminId        = 9; #Emerson Pinduka
        $adminInfo      = get_userdata( $adminId );
        $adminEmail     = $adminInfo->user_email;
        $adminName      = $adminInfo->first_name .  ' ' . $adminInfo->last_name;

        $siteUrl        = get_site_url();
        // $send_to        = $adminEmail;
        $send_to        = 'joanes.andrades@hotmail.com';
        
        $tryList        = '';
        $result         = '';
        if( !empty($log) ){
            foreach( $log as $item => $value ){
              if( is_array($value) ){
                $tryList .= '<li style="text-align: left;">'. strtoupper( str_replace('-', ' ', $value['result']) ) .': '. ( $value['exist'] == true ? '<b>Modelo Disponível</b>' : '<i>Modelo Ausente</i>') .'</li>';
              
                if( $value['exist'] == false ){
                  $result .= strtoupper( str_replace('-', '/', $value['result']) );
                }
              }
            }
        }
        $subject = '[Notificação] Conclusão de Teste de personalidade';
        $message .= '<style type="text/css">

            .header {
              background: #0d4d77;
              padding: 12px 24px;
            }
            
            .header .columns {
              padding-bottom: 0;
            }
            
            .header h1 {
              color: #fff;
            }
            
            .body{
              padding: 12px 24px;
              background: #f3f3f3;
            }
        
        </style>
        <div class="header">
          <container>
            <row class="collapse">
              <columns small="6" valign="middle">
                <h1 class="text-right">Notificação de Conclusão</h1>
              </columns>
            </row>
          </container>
        </div>
        
        <div class="body">
          <spacer size="16"></spacer>
          <row>
            <div>
              <h2>Olá, '. $adminName .'!</h2>
              <p class="lead">Alguém concluiu com sucesso o teste de personalidade.</p>
              <h4>Detalhes:</h4>
              <ul class="vertical">
                <li style="text-align: left;">Usuário: <b>'. $voucherUser .'</b></li>
                <li style="text-align: left;">Empresa: <b>'. $companyName .'</b></li>
                <li style="text-align: left;">Voucher: <a href="'. $attachLink .'" class="" target="_blank" rel="nofollow"><b>'. $voucher .'</b></a></li>
                <li style="text-align: left;">Resultado: <b>'. $result .'</b></li>
              </ul>
            </div>

            <columns small="12" large="6">
                <h4>Tentativas para encontrar o modelo:</h4>
                <ul class="vertical">             
                  '. $tryList .'
                </ul>
              </columns>
          </row>        
        </div>';
        
        $headers = array();
        $headers[] = 'From: Mindflow <'. $adminEmail .'>';
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        
        return wp_mail( $send_to, $subject, $message, $headers );
    }

    /**
     * Retorna a url de um arquivo pdf vinculado ao $id
     * 
     * @param $id; voucher/parent
     * @return string
     * 
     * @since v1.6.1
     */
    private function _getDoc( $id )
    {
        $args           = array(
            'post_type' => 'attachment',
            'posts_per_page' => -1,
            'post_parent' => $id,
            'exclude'     => get_post_thumbnail_id()
        );
        $attachments    = get_posts( $args );

        $attachment_url = false;
        if ( ! is_wp_error( $attachments ) ) :

            $attachment_url = $attachments[0]->guid;

        endif;

        return $attachment_url;
    }


    /**
     * Returna um array com os dados do usuário vinculado ao voucher($id)
     * 
     * @param @id; voucher
     * @return array
     * 
     * @since v1.6.1
     */
    private function _getUserData( $id )
    {
        $keys = array(
            'ucqpt_costumer_name',
            'ucqpt_costumer_email',
            'ucqpt_costumer_cpf',
            'ucqpt_costumer_tel'
        );

        $sanitized_data = array();
        foreach ( $keys as $key ) :

            $_key = explode( '_', $key );
            $_key = end( $_key );
            
            $sanitized_data[$_key] = get_post_meta( $id, $key, true );

        endforeach;

		return $sanitized_data;
    }

    /**
     * Dispara um e-mail com o resultado do teste
     * 
     * @param path $doc; pdf com o resultado
     * @param array $user; dados do usuário
     * @return bool
     * 
     * @link https://developer.wordpress.org/reference/functions/wp_mail/
     * @since v1.6.1
     */
    private function _sendEmail( $doc, $user )
    {
        $admin_email    = get_option('admin_email');
        $site           = get_site_url(); 
        $user_name      = $user['name'];
        $user_email     = $user['email'];
        $user_doc       = $user['cpf'];
        $user_tel       = $user['tel'];
        $attachment     = array($doc);
        $send_to        = $admin_email . ',' . $user_email;
        
        $subject = '[Resultado] Inventário de Perfil - Mindflow Academy ';
        $message .= '<h1>Parabéns, ' . $user_name . '!</h1> <br>';
        $message .= '<p>Você completou com sucesso o <a href="'. $site .'" target="_blank">Inventário de Personalidade da Mindflow Academy.</a></p> <br>';
        $message .= '<p>Para baixar o resultado clique o no link abaixo.</p> <br>';
        $message .= '<p><a href="'. $doc .'" target="_blank">Clique para baixar o resultado</a></p> <br>';
        
        $headers = array();
        $headers[] = 'From: Mindflow <'. $admin_email .'>';
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        
        return wp_mail( $send_to, $subject, $message, $headers, $attachment );
    }

    /**
     * Checa se é um post type uc_voucher
     * 
     * @param int $id; voucher
     * @return bool 
     * 
     * @since v1.6.1
     */
    private function _isVoucher( $id )
    {
        if ( ! $id ) return;
        
        $post_type  = get_post_type( $id );
        $is_voucher = true;
        
        if ( $post_type != 'uc_voucher') :
            $is_voucher = false;
        endif;

        return $is_voucher;
    } 
}