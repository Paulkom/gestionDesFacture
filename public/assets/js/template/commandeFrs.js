"use strict";
var gestionnaire_commande_fournisseur = function() {

    var func = ()=>{
        $(document).ready(function() { 

            var form = $(container).find('form');
            
            // verification pour activer ou desactiver le select de demande de prix
            if(form.find('input[name*="demandeDePrixToCommande"]').val() != '' ){
                form.find('select[name*="fournisseur"]').prop('disabled', true);
                form.find('select[name*="demandeDePrix"]').prop('disabled', true);
            }else{
                form.find('select[name*="demandeDePrix"]').prop('disabled', true);
            }
        
            var lignes = null;
            let btn = document.querySelector('.add_item_link');
            let supBtns = document.querySelectorAll('.collection-action');

            btn.addEventListener('click', function(){
                lignes = form.find('.ligne');
        
                lignes.each( (e, elt) => {
                    let qte = $(elt).find('input[name*="qteComfrs"]');
                    let prix = $(elt).find('input[name*="prixUnitaire"]');
                    let mt = $(elt).find('input[name*="montantCond"]');
            
                    $(qte).add(prix).on('keyup', () => {
                        if(parseFloat($(qte).val()) >= 0 && parseFloat($(prix).val()) >= 0){
                            $(mt).val(parseFloat($(qte).val()) * parseFloat($(prix).val()));
                            total();
                        }
                    });

                    $(elt).find('.collection-action').on('click', function(){
                        total();
                    })

                });
            });
            
        
            function total(){
                var t = 0;
                form.find('input[name*="montantCond"]').each(
                     (e, el) => {
                         if( parseFloat($(el).val()) > 0){
                            t += parseFloat($(el).val());
                         }
                     }
                 )
                form.find('input[name*="montantTotal"]').val(t);
            }
        
        });
    }

   
    return {
        init: function() {
            func();
        }
    }

}();
KTUtil.onDOMContentLoaded((function() {
    gestionnaire_commande_fournisseur.init();
}));



