
jQuery(document).ready(function($){
    var loc = window.location.pathname;
    var dir = loc.substring(0, loc.lastIndexOf('/'));
    var regkey =  '8b142bc02b2247ff81386538561722';
    var target = 'post';
    var theText = '';
    var theSwitch = $("#billing_city_field, #billing_postcode_field, #billing_address_1_field, #billing_address_2_field, #shipping_city_field, #shipping_postcode_field, #shipping_address_1_field, #shipping_address_2_field ");

    $.get(document.URL, {get_url:1}, 'json')
        .done(function(returnURL){
            var returnURL = JSON.parse(returnURL);
                targetUrl = '/fetch.php';           
            $(document).on('keyup keydown keypress click',"#wc-address-korean, .chosen-search > input",  function(e){
                dummy = $(this).val();
                if ( dummy.length > 1 ) {
                    theText = $(this).val();
                  $.post( returnURL + targetUrl, { regkey : regkey, target : target, text : theText }, 'json')
                    .done(function(data){
                        data = JSON.parse(data);
                        $('#wc-address-korean').next().children().children().last().children().remove();
                        for (var i = 0; i < data.length; i++ ) {
                            //data[i]
                          $('#wc-address-korean').next().children().children().last().append('<li id="address_sel_chzn_o_'+i+'" class="active-result">' +data[i] +'</li>');
                        } 
                        $('#wc-address-korean').next().children().children().last().children().on('click', function(){
                            var str = '';
                            str = $(this).text();
                            space = str.search(' ');
                            city = str.slice(0, space);
                            $('#billing_city').val(city);
                            postcode = str.slice(-7);
                            $("#billing_postcode").val(postcode);
                            address = str.slice(space, -7);
                            $("#billing_address_1").val(address);
                            $(document).click();
                            $("#billing_address_2").focus();
                            theSwitch.slideDown();
                        });
                    });                            
                }   
            }); 

            $(document).on('keyup keydown keypress click',"#address_sel2_chosen .chosen-search > input", function(e){
                dummy = $(this).val();

                address = $(this);

                if ( dummy.length > 1 ) {
                    
                    theText2 = $(this).val();
                  $.post( returnURL + targetUrl, { regkey : regkey, target : target, text : theText2 }, 'json')
                    .done(function(data){
                        data = JSON.parse(data);

                        address.parent().next().children().remove();
                        for (var i = 0; i < data.length; i++ ) {
                            //data[i]
                          address.parent().next().append('<li id="address_sel2_chzn_o_'+i+'" class="active-result">' +data[i] +'</li>');
                        } 
                        address.parent().next().children().on('click', function(){
                            var str = '';
                            str = $(this).text();
                            space = str.search(' ');
                            city = str.slice(0, space);
                            $('#shipping_city').val(city);
                            postcode = str.slice(-7);
                            $("#shipping_postcode").val(postcode);
                            address = str.slice(space, -7);
                            $("#shipping_address_1").val(address);
                            $(document).click();
                            $("#shipping_address_2").focus();
                        });
                    });           
                }   
            }); 
        });
    if ( $('#billing_postcode').val() == '' ){
        theSwitch.hide();
    }        





    $('#wc-address-korean, #address-sel2').chosen();



    // Frontend Chosen selects
    $("select.country_select, select.state_select").chosen();

    $('body').bind('country_to_state_changed', function(){
        $("select.state_select").chosen().trigger("liszt:updated");
    });

});

