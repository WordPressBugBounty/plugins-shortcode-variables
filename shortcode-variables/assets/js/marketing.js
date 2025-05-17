jQuery( document ).ready(function ($) {
    /** 
     * Dismiss marketing messages
     */
    $( '.sh-cd-update-notice' ).on('click', '.notice-dismiss', function ( event ) {

      event.preventDefault();
     
      if( false == $( this ).parent().hasClass( 'sh-cd-update-notice' ) ){
        return;
      }
    
      $.post( ajaxurl, {
          action: 'sh_cd_dismiss_notice',
          url: ajaxurl,
          security: $( this ).parent().data( 'nonce' ),
          update_key: $( this ).parent().data('update-key')
      });
  });
});
