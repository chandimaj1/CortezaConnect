(function($) {
    /**
     * 
     * Execute functions on DOM ready
     * 
     */
    $(document).ready(function() {
        console.log('CortezaConnect AdminJS - Scripts Ready...');
        
        //UI functions
        plugin_settings(); // Plugin settings
    })

/**
 * 
 * 
 * Plugin settings
 */
 function plugin_settings(){
    $('#update_settings').on('click', function(){
        console.log('updating settings...');
        
        let settings = {
            cc_instance_url:$('#settings_cc_instance_url').val(),
            cc_user_id:$('#settings_cc_user_id').val(),
            cc_secret:$('#settings_cc_secret').val(),
            cc_token:$('#settings_cc_token').val(),
        }
        console.log(settings);

        //Verify pluggin settings
        let verify_settings = new Promise(function(verify_settings_resolve, verify_settings_reject) {
            $.ajax({     
                url: '/wp-json/corteza_connect/v1/token/',
                method: "GET",
                data: {
                    auth_token:'',
                },
                success: function(response)
                { 
                    verify_settings_resolve(response);
                },
        
                error: function(e)
                {
                    verify_settings_reject(e);
                }
            });
        });
        verify_settings.then(
            function(response){
                console.log(response);
            },
            function(e){
                console.log('Error');
                console.log(e);
                alert ('Could not connect to verify Settings. Error:'+e);
            }
        );
        
    });
 }


 
    
//--- jQuery No Conflict
})(jQuery);