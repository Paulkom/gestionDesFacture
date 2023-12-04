
"use strict";
var GESTIONPAIEMENT = function() {

    var fieldList= new Object;
   // var validation;
    var handlelistes = () => {
        const buttons = document.querySelectorAll('[data-kt-entity-table-filter="list"]');
        const bloc = document.querySelector('[data-call-form="ajax"]');
        // console.log(buttons);
        // console.log(bloc);
        buttons.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();
                console.log(console.log(e.target));
                $.ajax({
                    type: "GET",
                    url: e.target.dataset.url,
                    dataType: "json",
                    beforeSend: function () {
                        const loadingEl = document.createElement("div");
                        document.body.prepend(loadingEl);
                        loadingEl.classList.add("page-loader");
                        loadingEl.classList.add("flex-column");
                        loadingEl.classList.add("bg-dark");
                        loadingEl.classList.add("bg-opacity-25");
                        loadingEl.innerHTML = `
                                            <span class="spinner-border text-primary" role="status"></span>
                                            <span class="fs-6 fw-semibold mt-5" style="color:#ffffff">Chargement...</span>
                                        `;
                        document.body.classList.add('page-loading');
                        document.body.setAttribute('data-kt-app-page-loading', "on")
                    },
                    success: function(data){
                        $(bloc).html(data.html);
                        const form = bloc.querySelector('form');
                        if(form){
                            form.querySelectorAll('select').forEach(s => {
                                $(s).select2();
                            });
                        }else{
                            if($(bloc).find('.kt_entity_table').length > 0)
                            {
                                tableau = $(bloc).find('.kt_entity_table')[0];
                                loadDataTable();
                            }
                        }
                    },
                    complete: function () {
                        document.body.classList.remove('page-loading');
                        document.body.removeAttribute('data-kt-app-page-loading');
                    }
                });
            })
        });
    }

    var swishModePaiement =(key, select=null)=>{
        let form = $(container).find('form');
        let virement = form.find('.virement').parent();
        let cheque = form.find('.cheque').parent();
        let mobilemoney = form.find('.mobilemoney').parent();
        $(container).find('.solde-client').html("");
        //console.log(key,'key',cheque,)
        if (key) {
               if( key.includes('espèce')){
                    virement.hide();
                    cheque.hide();
                    mobilemoney.hide();
                    console.log('espèce',cheque)
               }
               if( key.includes('Chèque')){
                     mobilemoney.hide();
                     cheque.show();
                     cheque.find('input').attr('required',true);
                     console.log('Chèque',cheque);
               }
                if( key.includes('Virement')){
                    cheque.hide();
                    mobilemoney.hide();
                    virement.show();
                    virement.find('input').attr('required',true);
                    console.log('virement',cheque,)
               }
                if( key.includes('Mobile Money')){
                     virement.hide();
                     cheque.hide();
                     mobilemoney.show();
                     mobilemoney.find('input').attr('required',true);
                }
                if( key.includes('Compte')){
                    virement.hide();
                    cheque.hide();
                    mobilemoney.hide();
                    let facture = $(container).find('select[name*="paiement[facture]"]');
                    var select_option = $(facture).find('option:selected');
                    if(select_option.attr('data-solde') =="") {
                        $(container).find('.solde-client').html("");
                        $(container).find('.solde-client').html('<span class="badge badge-danger">Le client n\'a pas un compte</span>');
                    }else if(parseFloat(select_option.attr('data-montantRest')) > parseFloat(select_option.attr('data-solde'))){
                        $(container).find('.solde-client').html("");
                        $(container).find('.solde-client').html('<span class="badge badge-warning">Le solde du client est insuffisant</span>');
                        //$(container).find('.solde-client').html('<span class="badge badge-warning">Le solde du client est insuffisant: '+ select_option.attr('data-solde') +'</span>');
                    }else{
                        $(container).find('.solde-client').html("");
                        //$(container).find('.solde-client').html('<span class="badge badge-success">Le solde du client est: '+ select_option.attr('data-solde') +'</span>');
                    }
                }
        }else{
            virement.hide();
            cheque.hide();
            mobilemoney.hide();
        }
               
    }

    var loadnewForm =()=>{
        if($(container).find('form').attr('action') === undefined ){
            $(newform).find('[data-control="select2"]').select2();
            $(container).html(newform);
            $(container).find('[data-control="select2"]').select2();  
        }
        
    }

    function datedate() {
        var d = new Date(); var strDate =  (d.getFullYear())+ '-' + ('0'+(d.getMonth()+1)).slice(-2) + '-' +('0'+d.getDate()).slice(-2) ;
        $("input[type='date']").each(function(){
            if($(this).val() == ""){
                $(this).val(strDate);
            }
        })
    }
    

    const paiement = () => {
        let parent = container;
        $(parent).find('[data-control="select2"]').select2();
        const btnS = container.querySelectorAll('button[type="submit"]');
        if(btnS != null)
        {
            btnS.forEach(btn => {
                const form = btn.closest('form');
                if (form != null && form != undefined) {
                    var  validation = valitation(form,fieldList)
                    btn.addEventListener("click", function(e) {
                        console.log('ddd', fieldList);
                        console.log(form,'form');
                        e.preventDefault();
                        var erreur = false;
                        if($(this).hasClass("imprimer") == true){
                            $("#paiement_truc").val("OUI");
                        }else{
                            e.preventDefault();
                            $("#paiement_truc").val("NON"); 
                        }
                        save(form,validation,btn,erreur);
                    });
                }
            });
        }

        function calculResteAPayer(){
            $("#paiement_montantPaie").keyup(function(){
                let montFac = $("#paiement_montantFact").val();
                let montantAPaie = $(this).val();
                let reste = parseFloat(montFac) - parseFloat(montantAPaie);
                if(reste < 0 ){
                    toastr.error("Le montant à payer ne peut pas être supérieur au montant dû");
                    reste = 0;
                    $(this).val( parseFloat(montFac));
                }
                $("#paiement_restAPayer").val(reste);
            });
        }
        calculResteAPayer();
        
        

        let seletcom = $(container).find('select[id*="paiement_facture"]');
            var selected = $(seletcom).find('option:selected').val();
            if(seletcom){
                $(seletcom).change(function () {
                    var selected1 = $(seletcom).find('option:selected');
                    selected = $(this).find('option:selected').val();
                    var option = $(this).find('option:selected');
                   // let leto = $(option).attr("data-montantIni");
                    var montantini  = $(option).attr("data-montantIni");
                    var montantrest   = $(option).attr("data-montantRest");
                    // console.log(leto);
                    if(montantrest == '0' || montantrest=='') 
                        montantrest = montantini;

                    if(montantrest != '0'){
                        $(container).find('input[name*="paiement[montantFact]"]').val(montantrest);
                        $(container).find('input[name*="paiement[montantPaye]"]').val(montantrest);
                        $(container).find('input[name*="paiement[montantRAPayer]"]').val(0);
                    }
                    $(container).find(".historiquePaiementsFacture").addClass("d-none");
                    $(container).find(".historiquePaiementsFacture-title").html("");
                    $(container).find(".historiquePaiementsFacture-content").html("");
                    $(container).find(".historiquePaiementsFacture-name").html("");
                });

                if(selected) {
                    var option = $(seletcom).find('option:selected');
                    var montantrest  = option.data('montantRest');
                    $(container).find('input[name*="paiement[montantFacture]"]').val(montantrest);
                }
            }
        const addbtn = document.querySelector('[data-kt-entity-table-filter="new_form"]');
        // console.log('addbtn',addbtn)
        addbtn.addEventListener('click', function (e) {
            // console.log('click',addbtn)
            loadnewForm();
            paiement();
            swishModePaiement('');
        });
        handlelistes();

        let modePaiement = $(container).find('select[name*="[modePaiement]"]');
        var selectemodePaiement = $(modePaiement).find('option:selected').val();
        var selectedtext = $(modePaiement).find('option:selected').text();
        if(modePaiement){
            modePaiement.change(function(){
                selectemodePaiement = $(this).find('option:selected').val();
                selectedtext = $(modePaiement).find('option:selected').text();
                swishModePaiement(selectedtext);
            });
            swishModePaiement(selectedtext);
        }
        let paiementfacture = $(container).find('select[name*="paiement[facture]"]');
        var select_option = $(paiementfacture).find('option:selected');
        if(paiementfacture){
            paiementfacture.change(function(){
                let modePaiement = $(container).find('select[name*="[modePaiement]"]');
                var selectemodePaiement = $(modePaiement).find('option:selected').val();
                var selectedtext = $(modePaiement).find('option:selected').text();
                console.log(selectedtext);
                swishModePaiement(selectedtext);
            });
        }


        $.each($($(container).find("input:not([readonly=readonly]")),function (k, elt) {
             var name = $(this).attr("name");
            if (typeof name != "undefined") {
                var input = $(elt);
            }
        });
    }

    return {
        init: function() {
            loadnewForm();
            paiement();
            datedate();
            swishModePaiement('');
        }
    }
}();
KTUtil.onDOMContentLoaded((function() {
    GESTIONPAIEMENT.init();
}));