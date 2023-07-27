function kopa_shortcode_icon_click(shortcode, textarea){
    var caret = textarea.caret();    
    switch(shortcode){
        case 'one_half':
            textarea.insertAtCaret('[one_half last="no"]','[/one_half]');
            break;
        case 'one_third':
            textarea.insertAtCaret('[one_third last="no"]','[/one_third]');
            break;
        case 'two_third':
            textarea.insertAtCaret('[two_third last="no"]','[/two_third]');
            break;
        case 'one_fourth':
            textarea.insertAtCaret('[one_fourth last="no"]','[/one_fourth]');
            break;
        case 'three_fourth':
            textarea.insertAtCaret('[three_fourth last="no"]','[/three_fourth]');
            break;
        case 'dropcaps':
            textarea.insertAtCaret('[dropcaps round="no"]','[/dropcaps]');
            break;
        case 'button':
            textarea.insertAtCaret('[button size="e.g. small, medium, big" link="" target=""]','[/button]');
            break;
        case 'alert':
            textarea.insertAtCaret('[alert type="e.g. block, error, success, info" title=""]', '[/alert]');
            break;
        case 'tabs':
            var tabs_string = '[tabs tab1="Tab 1" tab2="Tab 2" tab3="Tab 3"]';
            tabs_string += '[tab id=1]Tab content 1[/tab]';
            tabs_string += '[tab id=2]Tab content 2[/tab]';
            tabs_string += '[tab id=3]Tab content 3[/tab]';
            tabs_string += '[/tabs]';
            textarea.insertAtCaret(tabs_string,'');
            break;
        case 'accordions':
            var accordions_string = '[accordions]';
            accordions_string += '[accordion title="Accordion 1"]Accordion content 1[/accordion]';
            accordions_string += '[accordion title="Accordion 2"]Accordion content 2[/accordion]';
            accordions_string += '[accordion title="Accordion 3"]Accordion content 3[/accordion]';
            accordions_string += '[/accordions]';
            textarea.insertAtCaret(accordions_string,'');
            break;
        case 'toggle':
            textarea.insertAtCaret('[toggles][toggle title=""]Toggle content 1[/toggle][toggle title=""]Toggle content 2[/toggle][toggle title=""]Toggle content 3[/toggle][/toggles]','');
            break;
        case 'contact_form':
            textarea.insertAtCaret('[contact_form caption=""][/contact_form]','');
            break;
        case 'posts_lastest':
            textarea.insertAtCaret('[posts count="10" orderby="lastest" cats="" tags="" relation="OR" ][/posts]','');
            break;
        case 'posts_popular':
            textarea.insertAtCaret('[posts count="10" orderby="popular" cats="" tags="" relation="OR" ][/posts]','');
            break;
        case 'posts_most_comment':
            textarea.insertAtCaret('[posts count="10" orderby="most_comment" cats="" tags="" relation="OR" ][/posts]','');
            break;
        case 'posts_random':
            textarea.insertAtCaret('[posts count="10" orderby="random" cats="" tags="" relation="OR" ][/posts]','');
            break;
        case 'youtube':
            textarea.insertAtCaret('[youtube]http://www.youtube.com/watch?v=ABCDEFGH[/youtube]','');
            break;
        case 'vimeo':
            textarea.insertAtCaret('[vimeo]http://vimeo.com/123456789[/vimeo]','');
            break;
    }
    return false;
}

jQuery.fn.extend({
    insertAtCaret: function(myValue, myValueE){
        return this.each(function(i) {
            if (document.selection) {
                //For browsers like Internet Explorer
                this.focus();
                sel = document.selection.createRange();
                sel.text = myValue + myValueE;
                this.focus();
            }
            else if (this.selectionStart || this.selectionStart == '0') {
                //For browsers like Firefox and Webkit based
                var startPos = this.selectionStart;
                var endPos = this.selectionEnd;
                var scrollTop = this.scrollTop;
                this.value = this.value.substring(0,     startPos)+myValue+this.value.substring(startPos,endPos)+myValueE+this.value.substring(endPos,this.value.length);
                this.focus();
                this.selectionStart = startPos + myValue.length;
                this.selectionEnd = ((startPos + myValue.length) + this.value.substring(startPos,endPos).length);
                this.scrollTop = scrollTop;
            } else {
                this.value += myValue;
                this.focus();
            }
        })
    }
});

jQuery.fn.caret = function (begin, end){
    if (this.length == 0) return;
    if (typeof begin == 'number'){
        end = (typeof end == 'number') ? end : begin;
        return this.each(function (){
            if (this.setSelectionRange)
            {
                this.setSelectionRange(begin, end);
            } else if (this.createTextRange){
                var range = this.createTextRange();
                range.collapse(true);
                range.moveEnd('character', end);
                range.moveStart('character', begin);
                try {
                    range.select();
                } catch (ex) { }
            }
        });
    }else{
        if (this[0].setSelectionRange)
        {
            begin = this[0].selectionStart;
            end = this[0].selectionEnd;
        } else if (document.selection && document.selection.createRange){
            var range = document.selection.createRange();
            begin = 0 - range.duplicate().moveStart('character', -100000);
            end = begin + range.text.length;
        }
        return {
            begin: begin, 
            end: end
        };
    }
}
function kopa_change_timeline(obj){
    if(jQuery(obj).val() == 'portfolio'){
        jQuery(obj).parent().parent().find(".kopa-wdt-category").hide();
        jQuery(obj).parent().parent().find(".kopa-wdt-and-or").hide();
        jQuery(obj).parent().parent().find(".kopa-wdt-tags").hide();
        jQuery(obj).parent().parent().find(".kopa-wdt-number-of-article").hide();
        jQuery(obj).parent().parent().find(".kopa-wdt-order-by").hide();
    }
    else{
        jQuery(obj).parent().parent().find(".kopa-wdt-category").show();
        jQuery(obj).parent().parent().find(".kopa-wdt-and-or").show();
        jQuery(obj).parent().parent().find(".kopa-wdt-tags").show();
        jQuery(obj).parent().parent().find(".kopa-wdt-number-of-article").show();
        jQuery(obj).parent().parent().find(".kopa-wdt-order-by").show();
    }    
}

jQuery(document).ready(function() {
    jQuery(".kopa-wdt-select-timeline").each(function(){
        if(jQuery(this).val() == 'portfolio'){
        jQuery(this).parent().parent().find(".kopa-wdt-category").hide();
        jQuery(this).parent().parent().find(".kopa-wdt-and-or").hide();
        jQuery(this).parent().parent().find(".kopa-wdt-tags").hide();
        jQuery(this).parent().parent().find(".kopa-wdt-number-of-article").hide();
        jQuery(this).parent().parent().find(".kopa-wdt-order-by").hide();
    }
    else{
        jQuery(this).parent().parent().find(".kopa-wdt-category").show();
        jQuery(this).parent().parent().find(".kopa-wdt-and-or").show();
        jQuery(this).parent().parent().find(".kopa-wdt-tags").show();
        jQuery(this).parent().parent().find(".kopa-wdt-number-of-article").show();
        jQuery(this).parent().parent().find(".kopa-wdt-order-by").show();
    }    
        
    });    
});

