// Only run if jQuery exists
if ( typeof jQuery == 'function' ) {
jQuery(document).ready(function($){
	
	if ( typeof(enhanced_sb_default) != "undefined" )
	{
		$('input#s').each(function(){
			$(this).attr('placeholder', enhanced_sb_default);
		});
	}
    
/* = INPUT PLACEHOLDER SUPPORT FOR IE
------------------------------------------------------------- */
    if (! $.support.placeholder )
    {
        // Make placeholder work unobtrusively in ID
        var active = document.activeElement;
        $(':text').focus(function(){
            if ( typeof( $(this).attr('placeholder') ) != 'undefined' && $(this).attr('placeholder') != '' && $(this).val() == $(this).attr('placeholder') ) {
                $(this).val('').removeClass('hasPlaceholder');
            }
        }).blur(function(){
            if ( typeof( $(this).attr('placeholder') ) != 'undefined' && $(this).attr('placeholder') != '' && ($(this).val() == '' || $(this).val() == $(this).attr('placeholder')) ) {
                $(this).val($(this).attr('placeholder')).addClass('hasPlaceholder');
            }
        }).blur();
        $(active).focus();
        
        // Empty placeholder on submit
        $('form').submit(function(){
            $(this).find('.hasPlaceholder').each(function(){
                $(this).val('');
            });
        });
    }
    
});
};