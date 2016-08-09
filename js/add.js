$(document).ready(function() {
    $( "#accordion" ).accordion();
    
    $( ".tabs" ).tabs({
        collapsible: true
    });
    
    $("a.add-btn-url").fancybox({
        width     : 10,
        height    : '60%',
        fitToView : true,
        autoSize : true,
        closeClick : false,
        openEffect : 'none',
        closeEffect : 'none'
    });
    
    //Click sur checkbos is_principale
    $('.check-is-principale').click(function(){
        //alert("ok principale") ;
        iID = $(this).attr('id') ;
        iNumId = $(this).attr('num') ;
        
        isCheck = 0 ;
        if($('#'+iID).prop("checked")){
            isCheck = 1 ;
        }
        //On desactive Tout
        if(isCheck === 1){
            $('.check-is-principale').each(function(){
                $(this).prop("checked", false) ;
                $(this).val('2') ;
                iThisNumId = $(this).attr('num') ;
                $('#check-value-'+iThisNumId).val('2') ;
            }) ;
            //On reatribut le check du click sur l'element clicqué
            $('#'+iID).prop("checked", true) ;
            $('#'+iID).val('1') ;
            $('#check-value-'+iNumId).val('1') ;
        }else{
            //Il faut qu'on choisie un agence principale
            $( "#confirm-popup-footer-choice-agence-principale" ).dialog({
                open: function(event, ui) {
                    jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span>x</span>');
                },
                resizable: false,
                height:200,
                modal: true,
                closeText: "hide",
                buttons: {
                  "Oui": function() {
                    $( this ).dialog( "close" );
                    $('#'+iID).prop("checked", true) ;
                    $('#'+iID).val('1') ;
                    $('#check-value-'+iNumId).val('1') ;
                  }
                }
            });
        }
        
    }) ;
    
    $('span.btn-action-suppr').click(function(){
        
        iId = $(this).attr('num') ;
        zURLAction = $(this).attr('ajax-url') ;
        
        $( "#confirm-popup-footer-delete-agence" ).dialog({
                open: function(event, ui) {
                    jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span>x</span>');
                },
                resizable: false,
                height:200,
                modal: true,
                closeText: "hide",
                buttons: {
                  "Oui": function() {
                    $( this ).dialog( "close" );
                    
                    $.ajax({
                        type: 'POST',
                        url: zURLAction,
                        data: "id-agence="+iId+"&action=delete_agence",
                        success: function(result) {

                          window.location.reload() ;
                        }
                    });
                  },
                    "Non": function() {
                        
                      $( this ).dialog( "close" );
                      return false;
                    }
                }
        });

    });
        
}) ;