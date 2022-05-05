(function($) {
    /**
     * 
     * Execute functions on DOM ready
     * 
     */
    $(document).ready(function() {
        console.log('CortezaConnect AdminJS - Scripts Ready...');
        
        $('form button').on("click",function(e){
            e.preventDefault();
        });

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
        update_settings();
    });
 }

 //Update settings
 function update_settings(){
    let settings = {
        cc_instance_url:$('#settings_cc_instance_url').val(),
        cc_user_id:$('#settings_cc_user_id').val(),
        cc_secret:$('#settings_cc_secret').val(),
        cc_token:localStorage.getItem('cortezaconnect_token'),
    }

    let update_settings = new Promise(function(resolve, reject) {
        $.ajax({     
            url: '/wp-json/corteza_connect/v1/settings/',
            method: "POST",
            data: {
                auth_token:'',
                data: settings
            },
            success: function(response)
            { 
                resolve(response);
            },
    
            error: function(e)
            {
                reject(e);
            }
        });
    });

    update_settings.then(
        function(response){
            console.log(response);
            if (response.status){
                $('#settings_message').html('Settings Saved!');
                validate_settings();
            }
        },

        function(e){
            $('#settings_message').html('Update failed!. reson: \n'+e);
        }
    );
}


 //validate settings
 function validate_settings(){
    let verify_settings = new Promise(function(resolve, reject) {
        $.ajax({     
            url: '/wp-json/corteza_connect/v1/token/',
            method: "GET",
            data: {
                auth_token:'',
            },
            success: function(response)
            { 
                resolve(response);
            },
    
            error: function(e)
            {
                reject(e);
            }
        });
    });
    verify_settings.then(
        function(response){
            console.log(response);
            if (response.status){
                localStorage.setItem('cortezaconnect_token',response.token);
                $('#settings_message').html('Settings validated!');
            }
        },
        function(e){
            console.log('Error');
            console.log(e);
            $('#settings_message').html('Settings Validation failed!');
        }
    );
 }


 


 
    
//--- jQuery No Conflict
})(jQuery);