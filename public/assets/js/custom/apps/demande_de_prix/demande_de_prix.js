"use strict";
var GESTIONNAIREDEMANDEDEPRIX = function() {

    var resetData = ()=>{
        $(document).ready(function() {

            var form = document.querySelector('form[name="demande_de_prix"]');
            var resetbtn = $(form).find('button[type="reset"]');
            var selects  = $(form).find('select');
                      
            resetbtn.on("click", function(e){
                e.preventDefault();
                $(form).find('input[type="text"]').val('');
                $(form).find('select').val('');
                $(form).find('select').change();
            });  
            
        });
    }

   
    return {
        init: function() {
            resetData();
        }
    }

}();
KTUtil.onDOMContentLoaded((function() {
    GESTIONNAIREDEMANDEDEPRIX.init();
}));



