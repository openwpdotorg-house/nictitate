/*==================================================================
 * upload script
 ===================================================================*/

jQuery(document).ready(function() {

    jQuery(function() {
        jQuery(document).on('click', '.upload_image_button', function(evt) {
            var image_field;
            var clickedID = jQuery(this).attr('alt');   
            image_field = jQuery('#'+clickedID);
            tb_show('', 'media-upload.php?post_id=0&type=image&TB_iframe=1&width=640&height=554');

            var oldSendToEditor   = window.send_to_editor; 
            window.send_to_editor = function(html) {
                imgurl = jQuery('img', html).attr('src');
                image_field.val(imgurl);
                tb_remove();
                window.send_to_editor = oldSendToEditor;
            };
            return false;
        });

    });

});