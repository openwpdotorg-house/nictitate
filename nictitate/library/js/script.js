//Tooltip
jQuery(document).ready(function() {
    jQuery("a[rel='tooltip']").tooltip({
        'placement': 'top',
        'z-index': '3000'
    });
});
/* =========================================================
 Tabs
 ============================================================ */
jQuery(document).ready(function() {

    jQuery(".tab-content-1").hide(); //Hide all content
    jQuery("ul.tabs-1 li:first").addClass("active").show(); //Activate first tab
    jQuery(".tab-content-1:first").show(); //Show first tab content
    //On Click Event Product Tab
    jQuery("ul.tabs-1 li").click(function() {
        jQuery("ul.tabs-1 li").removeClass("active"); //Remove any "active" class
        jQuery(this).addClass("active"); //Add "active" class to selected tab
        jQuery(".tab-content-1").hide(); //Hide all tab content
        var activeTab = jQuery(this).find("span").attr("lang"); //Find the rel attribute value to identify the active tab + content
        jQuery(activeTab).fadeIn(); //Fade in the active content
        return false;
    });
});
/*======================================================================
 * 
 ======================================================================*/
jQuery(document).ready(function() {
    kopa_initialization();
});
function kopa_initialization() {
    jQuery(".kopa-sidebar-box-wrapper").hide(); //Hide all contentt
    jQuery(".kopa-cpanel-thumbnails").hide(); //Hide all content
    show_first_tab();
    show_on_checked("#kopa_custom_layout_setting");
}
function show_on_checked(obj) {
    if (jQuery(obj).hasClass("kopa_custom_layout_setting")) {
        jQuery(".kopa-layout-box").children().each(function() {
            if (jQuery(this).hasClass("kopa-sidebar-box-wrapper")) {
                jQuery(this).children().each(function() {
                    if (jQuery(this).hasClass('kopa-sidebar-box')) {
                        jQuery(this).children().each(function() {
                            if (jQuery(this).hasClass("kopa-sidebar-select")) {
                                jQuery(this).removeAttr("name");
                            }
                        });
                    }
                });
            }
        });
        if (jQuery(obj).prop('checked')) {
            jQuery(".kopa-layout-select").prop('disabled', false);
            jQuery(".kopa-sidebar-select").prop('disabled', false);
            jQuery(obj).val("Yes");
            jQuery(".active-box").children().each(function() {
                if (jQuery(this).hasClass('kopa-sidebar-box')) {
                    jQuery(this).children().each(function() {
                        if (jQuery(this).hasClass("kopa-sidebar-select")) {
                            jQuery(this).attr('name', "sidebar[]");
                        }
                    });
                }
            });
        }
        else {
            jQuery(".kopa-layout-select").prop('disabled', true);
            jQuery(".kopa-sidebar-select").prop('disabled', true);
            jQuery(obj).val("No");
            jQuery(".active-box").children().each(function() {
                if (jQuery(this).hasClass('kopa-sidebar-box')) {
                    jQuery(this).children().each(function() {
                        if (jQuery(this).hasClass("kopa-sidebar-select")) {
                            jQuery(this).removeAttr("name");
                        }
                    });
                }
            });
        }
    }
}
function show_onchange(obj) {
    temp_selected_layout = 'sidebar-position-' + jQuery(obj).val();
    temp_selected_image = 'kopa-cpanel-thumbnails-' + jQuery(obj).val();
    kopa_layout_box = jQuery(obj).parent().parent();
    kopa_box_body = jQuery(obj).parent().parent().parent();
    jQuery(kopa_box_body).children().each(function() {
        if (jQuery(this).hasClass('kopa-thumbnails-box')) {
            jQuery(this).children().each(function() {
                jQuery(this).hide();
                if (jQuery(this).hasClass(temp_selected_image)) {
                    jQuery(this).show();
                }
            })
        }
    });
    jQuery(kopa_layout_box).children().each(function() {
        if (jQuery(this).hasClass('kopa-sidebar-box-wrapper')) {
            jQuery(this).hide();
        }
        if (jQuery(this).hasClass(temp_selected_layout)) {
            jQuery('.kopa-sidebar-box-wrapper').removeClass('active-box');
            jQuery(this).addClass('active-box').show();
        }
    });
    show_on_checked("#kopa_custom_layout_setting");
}
function show_first_tab() {
    jQuery(".kopa-content-main-box").children().each(function() {
        if (jQuery(this).hasClass('kopa-box-body')) {
            kopa_box_body = jQuery(this);
            jQuery(this).children().each(function() {
                if (jQuery(this).hasClass('kopa-layout-box')) {
                    kopa_layout_box = jQuery(this);
                    jQuery(this).children().each(function() {
                        if (jQuery(this).hasClass('kopa-select-layout-box')) {
                            jQuery(this).children().each(function() {
                                if (jQuery(this).hasClass('kopa-layout-select')) {
                                    jQuery(this).children().each(function() {
                                        if (jQuery(this).attr("selected") === "selected") {
                                            temp_selected_layout = 'sidebar-position-' + jQuery(this).val();
                                            temp_selected_image = 'kopa-cpanel-thumbnails-' + jQuery(this).val();
                                        }
                                    ;
                                    });
                                }
                            });
                        }
                    });
                    //SHow Widget area
                    jQuery(kopa_layout_box).children().each(function() {
                        if (jQuery(this).hasClass(temp_selected_layout)) {
                            jQuery('.kopa-sidebar-box-wrapper').removeClass('active-box');
                            jQuery(this).addClass('active-box').show();
                        }
                    });
                }
            });
            //Show image 
            jQuery(kopa_box_body).children().each(function() {
                if (jQuery(this).hasClass("kopa-thumbnails-box")) {
                    jQuery(this).children().each(function() {
                        if (jQuery(this).hasClass(temp_selected_image)) {
                            jQuery(this).show();
                        }
                    });
                }
            });
        }
    });
}
/* =========================================================
 Sidebar Manager
 ============================================================ */
/*--------Add sidebar --------------*/
function kopa_add_sidebar_clicked(obj) {
    if (jQuery("#kopa-sidebar-new").val().length > 0) {
        var new_sidebar_name = jQuery("#kopa-sidebar-new").val();

        jQuery.ajax({
            type: 'POST',
            url: kopa_variable.AjaxUrl,
            dataType: 'json',
            data: {
                action: "kopa_add_sidebar",
                new_sidebar_name: new_sidebar_name,
                wpnonce: jQuery('#nonce_id_save_sidebar').val()
            },
            beforeSend: function() {
                jQuery('#kopa-loading-gif').show();
            },
            success: function(data) {
                if (data.is_exist) {
                    alert(data.error_message);
                }
                else {
                    if (jQuery("#kopa-sidebar-list").parent().hasClass("hidden")) {
                        jQuery("#kopa-sidebar-list").parent().removeClass("hidden");
                        jQuery("#kopa-nosidebar-label").remove();
                    }
                    var temp_html = '<tr>' +
                    '<td>' + new_sidebar_name +
                    '</td>' +
                    '<td>' +
                    '<a onclick="kopa_remove_sidebar_clicked(jQuery(this),\''+data.sidebar_id +'\')" title="" lang="" rel="tooltip" class="button button-basic button-icon" data-original-title="Remove"><i class="icon-trash"></i></a>' +
                    '</td>' +
                    '</tr>';
                    jQuery("#kopa-sidebar-list").append(temp_html);
                }
            },
            complete: function(data) {
                jQuery('#kopa-loading-gif').hide();
            },
            error: function(errorThrown) {
                console.log(errorThrown);
            }
        });
    } else {
        alert("You have not enter sidebar name!");
    }
    return false;
}
function kopa_remove_sidebar_clicked(obj, removed_sidebar_id) {
    var answer = confirm("Are you sure to remove this sidebar?");
    if (answer === true) {
        jQuery.ajax({
            type: 'POST',
            url: kopa_variable.AjaxUrl,
            dataType: 'json',
            data: {
                action: "kopa_remove_sidebar",
                removed_sidebar_id: removed_sidebar_id,
                wpnonce: jQuery('#nonce_id_save_sidebar').val()
            },
            beforeSend: function() {
                jQuery('#kopa-loading-gif').show();
            },
            success: function(data) {
                console.log(data);

                if (data.is_exist) {
                    alert(data.error_message);
                }
                else {
                    jQuery(obj).parent().parent().remove();
                }
            },
            complete: function(data) {
                jQuery('#kopa-loading-gif').hide();
            },
            error: function(errorThrown) {
                console.log(errorThrown);
            }
        });
    }
    return false;
}
/*==================================================================
 * Save sidebar setting
 ===================================================================*/
function save_sidebar_setting() {
    var kopa_sidebar = [];
    jQuery(".sidebar_name_input").each(function() {
        kopa_sidebar.push(jQuery(this).val());
    });
    jQuery.ajax({
        type: 'POST',
        url: kopa_variable.AjaxUrl,
        dataType: 'html',
        data: {
            action: "save_sidebar_setting",
            kopa_sidebar: kopa_sidebar,
            wpnonce: jQuery('#nonce_id_save_sidebar').val()
        },
        beforeSend: function() {
            jQuery('#kopa-loading-gif').show();
        },
        success: function(data) {
            console.log(data);
        },
        complete: function(data) {
            jQuery('#kopa-loading-gif').hide();
        },
        error: function(errorThrown) {
            console.log(errorThrown);
        }
    });
    return false;
}
/*===============================================================
 * Load layout setting
 ===============================================================*/
function load_layout_setting(obj) {

    jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class
    jQuery(this).addClass("active"); //Add "active" class to selected tab
    kopa_template_id = jQuery(obj).attr("title");
    jQuery.ajax({
        type: 'POST',
        url: kopa_variable.AjaxUrl,
        dataType: 'html',
        data: {
            action: "load_layout",
            kopa_template_id: kopa_template_id,
            wpnonce: jQuery('#nonce_id').val()
        },
        beforeSend: function() {
            jQuery('#kopa-loading-gif').show();
        },
        success: function(data) {

            jQuery("#kopa-admin-wrapper").html(data);
            kopa_initialization();
        },
        complete: function(data) {
            jQuery('#kopa-loading-gif').hide();
        },
        error: function(errorThrown) {
            console.log(errorThrown);
        }
    });
    return false;
}
/*===============================================================
 * Save layout
 ===============================================================*/
function save_layout_setting(obj) {
    var kopa_setting = [];
    var template_id = jQuery("#kopa_template_id").val();
    var layout_id;
    var sidebars = [];
    jQuery(".active-box").parent().children().each(function() {
        if (jQuery(this).hasClass("kopa-select-layout-box")) {
            jQuery(this).children().each(function() {
                if (jQuery(this).hasClass("kopa-layout-select")) {
                    layout_id = jQuery(this).val();
                }
            });
        }
    });
    jQuery(".active-box .kopa-sidebar-select").each(function() {
        sidebars.push(jQuery(this).val());
    });
    kopa_setting.push({
        layout_id: layout_id,
        sidebars: sidebars
    });
    jQuery.ajax({
        type: 'POST',
        url: kopa_variable.AjaxUrl,
        dataType: "json",
        data: {
            action: "save_layout",
            kopa_setting: kopa_setting,
            template_id: template_id,
            wpnonce: jQuery('#nonce_id_save').val()
        },
        beforeSend: function() {
            jQuery('#kopa-loading-gif').show();
        },
        success: function(data) {
            jQuery('#kopa-loading-gif').hide();
            console.log(data);
        },
        complete: function(data) {
            jQuery('#kopa-loading-gif').hide();
            console.log(data);
        },
        error: function(errorThrown) {
            console.log(errorThrown);
        }
    });
    return false;
}
/*===============================================================
 * General Setting
 ===============================================================*/
jQuery(document).ready(function() {
    var options = {
        url: kopa_variable.AjaxUrl,
        dataType: 'json',
        clearForm: false,
        resetForm: false,
        type: 'post',
        beforeSubmit: function(formData, jqForm, options) {
            jQuery('#kopa-loading-gif').show();
        },
        success: function(responseText, statusText, xhr, $form) {
            jQuery('#kopa-loading-gif').hide();
        }
    };

    jQuery('#kopa-theme-options').submit(function() {
        jQuery(this).ajaxSubmit(options);
        return false;
    });
});

function save_general_setting(obj) {
    jQuery('#kopa-theme-options').submit();
    return false;
}

function on_change_font(obj){
    var google_font_family = obj.find("option:selected").text();
    if(google_font_family == '-- Default --'){
        obj.parent().find(".font-sample").hide();       
    }
    else{        
        google_font_family = obj.find("option:selected").text().replace(" ", "+");
        var font_url = 'http://fonts.googleapis.com/css?family='+google_font_family+':300,300italic,400,400italic,700,700italic&subset=latin';
        jQuery('head').append('<link rel="stylesheet" type="text/css" href="' + font_url + '" >');
        obj.parent().find(".font-sample").css("font-family",google_font_family);
        obj.parent().find(".font-sample").show();  
    }
    return false;
}
function on_change_icon(obj){    
    jQuery(obj).parent().parent().parent().find(".icon_class").val(jQuery(obj).attr("lang"));
    jQuery(".selected").removeClass("selected");
    jQuery(obj).parent().addClass("selected");   
   return false;
}
