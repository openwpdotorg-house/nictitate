//Color Picker
jQuery(document).ready(function(){
    var kopa_colorpicker_options = {      
        defaultColor: false,        
        change: function(event, ui){},        
        clear: function() {},        
        hide: true,        
        palettes: true
    };
    jQuery('.kopa_colorpicker').wpColorPicker(kopa_colorpicker_options);
});