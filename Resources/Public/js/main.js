/**main Js**/
$(document).ready(function(){
     //tooltip settings
    $("[rel=tooltip-label]").tooltip({ placement: 'right'});
    //tooltip settings
     
    //select picker settings
    $(".selectpicker").selectpicker({
        style: 'btn-default',
        size: 10
     });
    $(".selectpicker").selectpicker('setSize');
    //select picker settings
    
    //set the chart svg height
    $('svg').height(475);
    
    //deletion of analytic account
     $(".allaccounts").on("click", "#delete_acc_btn", function () {
     var deleteId = $(this).data('id');
     var arr = deleteId.split(':');
     var deletion_id = arr[1];
     var uri_enc = $('#delete_link').attr('href');
     var uri_dec = decodeURIComponent(uri_enc);
     var uri_arr = uri_dec.split('&');
     var module_token = uri_arr[1];

     var new_uri = 'index.php?M=system_PitsSiteStatisticsPitssitestatistics&'+module_token+'&tx_pitssitestatistics_system_pitssitestatisticspitssitestatistics[statistics]='+deletion_id+'&tx_pitssitestatistics_system_pitssitestatisticspitssitestatistics[action]=delete&tx_pitssitestatistics_system_pitssitestatisticspitssitestatistics[controller]=Statistics';


     $('#delete_link').attr("href", new_uri);
      });
    //deletion of analytic account

});
