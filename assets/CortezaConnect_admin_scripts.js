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
        refresh_selection(); // Refresh Shortcode selection
        add_shortcodes(); // Add shortcodes
        preview_shortcode();
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
            dataType : "json",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify({
                auth_token:'',
                data: settings
            }),
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
            }else{
                $('#settings_message').html('Settings not validated!. reason: '+response.msg);
            }
        },
        function(e){
            console.log('Error');
            console.log(e);
            $('#settings_message').html('Settings Validation failed!');
        }
    );
 }



 /**
  *  Refresh Selections
  */
function refresh_selection(){
    $('#refresh_selection').on('click', function(){
        console.log('Getting namespaces...');
        get_namespaces();
    });
 }
 
 //Get namespaces
function get_namespaces(){
    let fetch_namespaces = new Promise(function(resolve, reject) {
        $.ajax({     
            url: '/wp-json/corteza_connect/v1/curl/',
            method: "POST",
            dataType : "json",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify({
                data:{
                    method: "GET",
                    endpoint: "/api/compose/namespace/"
                }
            }),
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
    fetch_namespaces.then(
        function(response){
            console.log(response);
            add_namespaces_to_select(response.response.response.set);
        },
        function(e){
            console.log('Error');
            console.log(e);
            $('#shortcode_message').html('Namespaces could not be fetched!');
        }
    );
 }

//Add namespaces to select element
function add_namespaces_to_select(namespaces){
    if (namespaces.length>0){
        let x = '';
        namespaces.forEach(e => {
            x += `<option value='${e.namespaceID}'>${e.name}</option>`
        });

        $('#cc_select_namespace')
        .html('<option value="false" >Select Namespace</option>')
        .append(x);
    }else{
        $('#cc_select_namespace')
        .html('<option value="false" >Select Namespace</option>')
        $('#shortcode_message').html('Namespaces not found!');
    }


    //Get modules
    $('body').off().on('change', '#cc_select_namespace', function(){
        get_modules()
    });
}

//Set modules
function get_modules(){
        let namespace = $('#cc_select_namespace').val();
        let endpoint = "/api/compose/namespace/"+namespace+"/module/";

        let fetch_modules = new Promise(function(resolve, reject) {
            $.ajax({     
                url: '/wp-json/corteza_connect/v1/curl/',
                method: "POST",
                dataType : "json",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify({
                    data:{
                        method: "GET",
                        endpoint: endpoint
                    }
                }),
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
        fetch_modules.then(
            function(response){
                console.log(response);
                add_modules_to_select(response.response.response.set);
            },
            function(e){
                console.log('Error');
                console.log(e);
                $('#shortcode_message').html('Modules could not be fetched!');
            }
        );
}
        
//Add modules to select element
function add_modules_to_select(modules){
    if (modules.length>0){
        let x = '';
        modules.forEach(e => {
            x += `<option value='${e.moduleID}'>${e.name}</option>`
        });

        $('#cc_select_module')
        .html('<option value="false" >Select Module</option>')
        .append(x);
    }else{
        $('#cc_select_module')
        .html('<option value="false" >Select Module</option>')
        $('#shortcode_message').html('Modules not found!');
    }
}



/**
 * 
 * Add Shortcodes
 */

function add_shortcodes(){
    $('#add_shortcode').on('click', function(){
        console.log('Adding shortcode...');

        let shortcode_info = {
            cc_shortcode_label:$('#cc_shortcode_text').val(),
            cc_namespace_id:$('#cc_select_namespace').val(),
            cc_module_id:$('#cc_select_module').val(),
            cc_type:$('#cc_select_type').val(),
        }
        save_shortcode(shortcode_info);
    });
}

function save_shortcode(params){
    //
}
 
    
//--- jQuery No Conflict
})(jQuery);


/**
 * Preview shortcode
 */

function preview_shortcode(){
    
    $('#refresh_shortcode').on('click', function(){
        console.log('Previewing shortcode...');

        get_records();
    });
}


function get_records(){
    let shortcode_info = {
        cc_shortcode_label: $('#cc_shortcode_text').val(),
        cc_namespace_id: $('#cc_select_namespace').val(),
        cc_module_id: $('#cc_select_module').val(),
        cc_type: $('#cc_select_type').val()
    };
    
    let endpoint = "/api/compose/namespace/"
        +shortcode_info.cc_namespace_id+"/module/"
        +shortcode_info.cc_module_id+"/record/";

        let fetch_records = new Promise(function(resolve, reject) {
            $.ajax({     
                url: '/wp-json/corteza_connect/v1/curl/',
                method: "POST",
                dataType : "json",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify({
                    data:{
                        method: "GET",
                        endpoint: endpoint
                    }
                }),
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
        fetch_records.then(
            function(response){
                console.log(response);
                //preview_records(response.response.response.set);
            },
            function(e){
                console.log('Error');
                console.log(e);
                $('#shortcode_message').html('Modules could not be fetched!');
            }
        );
}