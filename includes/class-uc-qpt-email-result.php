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