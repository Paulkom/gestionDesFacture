var factureProforma = function () {
    /**
     * Vérifie s'il s'agit bien d'un élément HTML
     * @param element
     * @returns {boolean}
     */
    var selectedoptions = [];
    var unicite=[];
    var fieldList= new Object;
    var validation;
    const isElement = (element) => {
        return element instanceof Element || element instanceof HTMLDocument;
    }
    const ajaxDeleteResult = (form, data) => {
        console.log(form);
        form.remove();
        if(data != null)
        {
            console.log(data);
        }
    }
    const addFormDeleteLink = (form) => {
        if (isElement(form)) {
            console.log(form);
            const removeFormButton = form.querySelectorAll('button.remove');
            let succes = false;
            removeFormButton.forEach(btnremove => {
                btnremove.addEventListener('click', (e) => {
               /* $.ajax({
                    type: "POST",
                    url: removeFormButton.dataset.url,
                    data: {token: removeFormButton.dataset.token},
                    dataType: "json",
                    beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
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
                        succes = true;
                        /*ajaxDeleteResult(form, data);
                    },
                    complete: function () {
                        document.body.classList.remove('page-loading');
                        document.body.removeAttribute('data-kt-app-page-loading');
                    }
                });*/
                form.remove();
            });
        })
        }
    }
    /**
     * Permet d'ajouter un ou plusieurs formulairs imbriqués
     * à un formulaire parent après un clic sur le bouton d'ajout
     * @param e
     */
    const addFormToCollection = (e) => {
        if(container != null)
        {
           // var container = document.querySelector('[data-call-form="ajax"]');
           console.log(e);
           console.log(e.currentTarget);
           if(e.currentTarget){
                /**
                 * Récupérer l'élément conteneur de formulaires imbriqués
                 */
                const collectionHolder = container.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
                /**
                 * Récupérer la forme brute du formulaire à imbriqué
                 */
                const prototype = collectionHolder.dataset.prototype;
                const div = document.createElement('tr');
                div.classList.add('border-bottom');
                div.innerHTML = prototype.replace(
                    /__name__/g,
                    collectionHolder.dataset.index
                );
                /**
                 * Ajouter la forme html du formulaire imbriqué au conteneur
                 */
                collectionHolder.appendChild(div);
                addFormDeleteLink(div);

                let formName = $(container).find('form').attr('name');
                let arrayForm = ['facture_proforma'];
                if($.inArray(formName, arrayForm) !== -1){ unicRow(); } //control de select double

                $(div).find('[data-control="select2"]').select2();
                validation.addField($(div).find('select[name*="[produit]"]').attr('name'),{
                    validators: {
                        notEmpty: {
                            message: 'Selectionnez un produit '
                        }
                    }
                })
                validation.addField($(div).find('input[name*="[prix]"]').attr('name'),{
                    validators: {
                        notEmpty: {
                            message: 'Le prix est obligatoire'
                        }
                    }
                })
                validation.addField($(div).find('input[name*="[qtite]"]').attr('name'),{
                    validators: {
                        notEmpty: {
                            message: 'La qté est obligatoire'
                        }
                    }
                })

                Inputmask("decimal", {
                    "rightAlignNumerics": false
                }).mask(".decimal");
                /** Incrémentation de l'index après insertion du formulaire imbriqué **/
                collectionHolder.dataset.index++;
            }       
            eventchange();
           // vente();
        }
    }

    var loadnewForm =()=>{
        if($(container).find('form').attr('action') === undefined ){
            $(newform).find('[data-control="select2"]').select2();
            $(container).html(newform);
            $(container).find('[data-control="select2"]').select2();  
        }
    }

    var swishStatut = () => {
        let form = $(container).find('form');
        let moral = form.find('.moral').parent();
        let physique = form.find('.physique').parent();
    
        if(form.find('input[type=radio]:checked').val() == 'Physique')
            moral.hide();
        if(form.find('input[type=radio]:checked').val() == 'Moral')
            physique.hide();
    
        form.find('input[type=radio]').change(function() {
            if (this.value == 'Physique') {
                moral.hide();
                physique.show();
            }
            else if (this.value == 'Moral') {
                moral.show();
                physique.hide();
            }
        });
    }
    
   /* var comptoire = () => {
        let form = $(container).find('form');
        let comptoire = form.find('.comptoire').parent();
    
        if(form.find('input[type=radio]:checked').val() == 'Physique'){
            comptoire.hide();
        }else{
            comptoire.show();
        }
        form.find('select[name*="facture_proforma[typeCommande]"]').change(function() {
            if (this.value == 'Au comptoir') {
                comptoire.show();
            }
            else {
                comptoire.hide();
            }
        });
    }*/

    function facture_proforma() {
       
        if(container != null)
        {

            $(container).find('[data-control="select2"]').select2(); 
            container.querySelectorAll('.add_vente_item_link').forEach(btn => {
            console.log("click",btn);
            btn.addEventListener("click", addFormToCollection);
            const holders = document.querySelectorAll('.' + btn.dataset.collectionHolderClass);
                holders.forEach(h => {
                    const divs = h.querySelectorAll('div');
                    divs.forEach(div => {
                        addFormDeleteLink(div);
                    });
                });
            });
            const btnS = container.querySelectorAll('button[type="submit"]');
            console.log('btnS',btnS);
            if(btnS != null)
            {
                btnS.forEach(btn => {
                    const form = btn.closest('form');
                    if (form != null && form != undefined) {
                        validation = valitation(form,produit)
                        btn.addEventListener("click", function(e) {
                            console.log(form,'form');
                            e.preventDefault();
                            var erreur = false;
                            for (const key in unicite) {
                                if (unicite[key]) {
                                    erreur = true;
                                    break;
                                }
                            }
                            if ($(container).find('select[name*="[acheteur]"]').val()=='' &&
                                $(container).find('input[name*="[nom]"]').val()=='' &&
                                $(container).find('input[name*="[prenom]"]').val()=='' &&
                                $(container).find('input[name*="[denomination]"]').val()=='' &&
                                $(container).find('input[name*="[raisonSociale]"]').val()=='') 
                            {
                                toastr.error("veuillez selectionner ou ajouter un client ");
                                console.log($(container).find('input[name*="[prenom]"]').val());

                            }else{
                                if($(this).hasClass("imprimer") == true){
                                    $("#facture_proforma_truc").val("OUI");
                                    console.log("Oui");
                                }else{
                                    e.preventDefault();
                                    $("#facture_proforma_truc").val("NON");
                                    console.log("Non");   
                                }
                                save(form,validation,btn,erreur);
                            }
                            
                        });
                    }
                });
            }
            
            /*const addbtn = document.querySelector('[data-kt-entity-table-filter="new_form"]');
            //console.log('addbtn',addbtn)
            addbtn.addEventListener('click', function (e) {
                console.log('click',addbtn)
                loadnewForm();
                vente();
            });*/

            document.querySelector('#annuler').addEventListener('click', function (e) {
               $("#facture_proforma_nom").val('');
               $("#facture_proforma_prenom").val('');
               $("#facture_proforma_ifu").val('');
               $("#facture_proforma_telephone1").val('');
               $("#facture_proforma_telephone2").val('');
               $("#facture_proforma_email").val('');
               $("#facture_proforma_adresse").val('');
               $("#facture_proforma_dateNais").val('');
               $("#facture_proforma_denomination").val('');
               $("#facture_proforma_raisonSociale").val('');
               $("#facture_proforma_sigle").val('');
               $("#facture_proforma_acheteur").prop("disabled", false);
            });
            document.querySelector('#ajouter').addEventListener('click', function (e) {
                var text = $("#facture_proforma_nom").val()+' '+$("#facture_proforma_prenom").val();
                $("#facture_proforma_acheteur").prop("disabled", false);
                $("#facture_proforma_acheteur").prepend("<option selected value=''>" + text +"</option>");
                $("#facture_proforma_acheteur").select2();
                $("#facture_proforma_acheteur").prop("disabled", true);

             });

            /*document.querySelector('#newclient').addEventListener('click', function (e) {
                // console.log('click',$(this).parent().parent().find('select')[0],$(this).parent().parent());
                // $(this).parent().parent().find('select').prop("disabled", true);
            });*/
            swishStatut()
        }
    }


    // var desactiverSelect=()=>{
    //     var mesoption=[];
    //     console.log(mesoption,selectedoptions,'mesoption');
    //     for(var key in selectedoptions) {
    //         mesoption.push(selectedoptions[key]);
    //     }

    //     $.each($(container).find('select.produit'), function (i, elt) {
    //         console.log(elt,'elt',i);
    //         $.each($(elt).find('option:not(:selected,:disabled)'), function(i, item) {
    //             //console.log(item.value,selectvalu);
    //             if ($.inArray($(this).val() , mesoption) != -1) {
    //                 $(item).prop("disabled", true);
    //             } else {
    //                 $(item).prop("disabled", false);
    //             }
    //             //
    //             console.log($(item).attr("data-qtestockcond"),"qtestockcond");

    //             if(parseFloat($(item).attr("data-qtestockcond")) > 0 ) {
    //                //$(item).prop("disabled", false);
    //             }else{
    //                 //$(item).prop("disabled", true); 
    //             }
    //         });
    //     })
    // }


    var eventchange =()=>{
        $.each($($(container).find('select')), function (i, elt) {
            let select = $(elt);
            name = select.attr('name');
            let blocproduit=select.parent().parent().parent();
            console.log(blocproduit,'blocproduit');
            if(name.includes('[produit]')){
                select.change(function () {
                    var option = $(this).find('option:selected');
                    var qtitecom  = option.data('qtite');
                    var prixmin   = option.data('prixmin');
                    var prixmax  = option.data('prixmax');
                    var prix   = option.data('prixvent');
                    var qtestockcond = option.data('qtestockcond');
                    var qtestockcondLogique = option.data('qtestockcondlogique');
                    selectedoptions[$(this).attr('name')] = $(this).val();

                    blocproduit.find('input[name*="[prix]"]').val(parseFloat(prix));
                    blocproduit.find('input[name*="[qtite]"]').val(1);    
                    blocproduit.find('input[name*="[montant]"]').val(parseFloat(prix));
                    blocproduit.find('input[name*="[montantHtAprRse]"]').val(parseFloat(prix));
                    console.log('qteStockCond',qtestockcond,blocproduit.find('.stock'));
                    blocproduit.find('.stock').text('SR='+qtestockcond);
                    blocproduit.find('.stocklogique').text('SL='+qtestockcondLogique);

                    //calcule de la remise sur montant total de la facture

                    unicite[select.attr('name')] = false;

                    remises();
                    //calcule des montants totales
                    calculeDestotaux();
                })
            }

            if (name.includes('[produitCondFactures]')) {
                if(name.includes('[typeRemise]')){
                    select.change(function() {
                        remiseSurProduit(blocproduit);
                    });
                }
                remiseSurProduit(blocproduit);
            }

            if(name.includes('facture_proforma[typeRemise]')){
                select.change(function() {
                    remises();
                });
            }
        });

         $.each($($(container).find("input:not([readonly=readonly]")),function (k, elt) {
           // console.log($(elt));
            var name = $(this).attr("name");
           // console.log(name);
            if (typeof name != "undefined") {
                var input = $(elt);
                let blocproduit = input.parent().parent().parent();
                input.keyup(function () {
                    if(name.includes('[produitCondFactures]') && !name.includes('[prix]')){
                        var montant = 0;
                        var qtite= parseFloat(blocproduit.find('input[name*="[qtite]"]').val());
                        let seletproduit = $(blocproduit).find('select[name*="[produit]"]');
                        var option = $(seletproduit).find('option:selected');
                        var prix = parseFloat(blocproduit.find('input[name*="prix"]').val());
                        if (option.val()!='') {
                            montant = prix*qtite
                            console.log('montant',montant);
                            console.log('prix',prix);
                            console.log('qtite',qtite);
                            blocproduit.find('input[name*="[montant]"]').val(montant);
                            blocproduit.find('input[name*="[montantHtAprRse]"]').val(montant);
                            //remise sur un produit
                            remiseSurProduit(blocproduit);
                            //calcule des montants totales
                            calculeDestotaux(); 
                        } 
                    }
                    //gestion des remises
                    remises();    

                })
                
                if(name.includes('[produitCondFactures]')){
                    input.focusout(function () {
                        if(name.includes('[prix]')) {
                            var montant = 0;
                            var qtite= parseFloat(blocproduit.find('input[name*="[qtite]"]').val());
                            let seletproduit = $(blocproduit).find('select[name*="[produit]"]');
                            var option = $(seletproduit).find('option:selected');
                            var prix = parseFloat(blocproduit.find('input[name*="[prix]"]').val());
                            if (option.val()!='') {
                                var prixmin   = option.data('prixmin');
                                var prixmax  = option.data('prixmax');
                                var prixvent   = option.data('prixvent');
                                if(prix >= parseFloat(prixmin) && prix <= parseFloat(prixmax)){
                                    prix = parseFloat(blocproduit.find('input[name*="[prix]"]').val());
                                }else{
                                    var message = "Le prix du produit "+ option.text() + " doit être comprise entre "+prixmin+" et "+prixmax;
                                    toastr.error(message);
                                    blocproduit.find('input[name*="[prix]"]').val(prixvent);
                                    prix = parseFloat(prixvent);
                                } 
                                montant = prix*qtite
                                console.log('montant',montant);
                                console.log('prix',prix);
                                console.log('qtite',qtite);
                                blocproduit.find('input[name*="[montant]"]').val(montant);
                                blocproduit.find('input[name*="[montantHtAprRse]"]').val(montant);
                                //remise sur un produit
                                remiseSurProduit(blocproduit);
                                //calcule des montants totales
                                calculeDestotaux(); 
                            }
                            //gestion des remises
                            remises();   
                        }
                    });
                }
            }
        });
    }

   var infostock = (eltselect)=>{
        let blocproduit= eltselect.parent().parent();
        var option = eltselect.find('option:selected');
        blocproduit.find('.stock').text('SR='+ option.data('qtestockcond'));
        blocproduit.find('.stocklogique').text('SL='+ option.data('qtestockcondlogique'));
   }
    //calcule des totaux
    var calculeDestotaux=()=>{
        var totalsomme = 0;
        var totalsommeremise = 0;
        let montantParProduit = $(".montant");
        let montantParRseProduit = $(".remise");
        for ( let i = 0; i < montantParProduit.length; i++) {
            if($(montantParProduit[i]).val() !=''){
                totalsomme += parseFloat($(montantParProduit[i]).val());
            }
            if($(montantParRseProduit[i]).val() !=''){
                totalsommeremise += parseFloat($(montantParRseProduit[i]).val());
            }
        }
        $(container).find('input[name*="[montantHt]"]').val(totalsomme);
        $(container).find('input[name*="[montantHtToAprRse]"]').val(totalsommeremise);
        $(container).find('input[name*="[montantTtc]"]').val((totalsomme*1.18).toFixed(2));
        $(container).find('input[name*="[montantTotal]"]').val((totalsomme*1.18).toFixed(2));
    }

    var remises =()=>{
        let selettypeRemise = $(container).find('select[name*="facture_proforma[typeRemise]"]');
        var value = $(selettypeRemise).find('option:selected').val();
        let remise = 0;
        var valeurRemise = parseFloat(parseFloat($(container).find('input[name*="[valeurRemise]"]').val()));
        var montantTotal = parseFloat(parseFloat($(container).find('input[name*="[montantTotal]"]').val()));
        if (montantTotal) {
            if(valeurRemise) {
                if(value =='%' && valeurRemise !='' && montantTotal != '') {
                    remise = (parseFloat(montantTotal)*valeurRemise)/100
                }else if(value =='MT' && valeurRemise !='' && montantTotal != ''){
                    remise = valeurRemise;
                }
                $(container).find('input[name*="facture_proforma[remise]"]').val(remise);
                $(container).find('input[name*="[montantTtc]"]').val(montantTotal - remise); 
            }else{
                $(container).find('input[name*="facture_proforma[remise]"]').val(0);
                $(container).find('input[name*="[montantTtc]"]').val(montantTotal); 
            }
        }  
    }

    var remiseSurProduit =(element)=>{
        let blocproduit=element;
        let selettypeRemise = $(blocproduit).find('select[name*="[typeRemise]"]');
        var value = $(selettypeRemise).find('option:selected').val();
        var remise = 0;
        var valeurRemise = parseFloat($(blocproduit).find('input[name*="[valeurRemise]"]').val());
        var montantHt = parseFloat($(blocproduit).find('input[name*="[montant]"]').val());
        var prix = parseFloat($(blocproduit).find('input[name*="[prix]"]').val());
        var qtite = parseFloat($(blocproduit).find('input[name*="[qtite]"]').val());
        if(prix) {
            if(valeurRemise) {
                var prixremise=0;
                var montantHtAprRse =0;
                if(value =='%' && valeurRemise !='') {
                    remise = (prix*valeurRemise)/100;
                    prixremise = prix-remise;
                    montantHtAprRse = parseFloat(prixremise)*parseFloat(qtite);
                    console.log(remise);
                }else if(value =='MT' && valeurRemise !=''){
                    remise = valeurRemise;
                    prixremise = prix-remise;
                    montantHtAprRse = parseFloat(prixremise)*parseFloat(qtite);
                    console.log(remise);
                }
                $(blocproduit).find('input[name*="[remise]"]').val(remise);
                $(blocproduit).find('input[name*="[montantHtAprRse]"]').val(montantHtAprRse); 
            }else{
                $(blocproduit).find('input[name*="[remise]"]').val(0);
                $(blocproduit).find('input[name*="[montantHtAprRse]"]').val(montantHt); 
            }
        } 
    }

    var handlelistes = () => {
        // Select all delete buttons
        const buttons = document.querySelectorAll('[data-kt-entity-table-filter="list"]');
        const bloc = document.querySelector('[data-call-form="ajax"]');
        console.log(buttons);
        console.log(bloc);
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
                            facture_proforma();
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
    return {
        init: function () {
            loadnewForm();
            handlelistes();
            facture_proforma();
            selectedoptions = [];
            $.each($(container).find('select.produit'), function (i, elt) {
                selectedoptions[$(this).attr('name')] = $(this).val();
                infostock($(elt));
            })
            eventchange();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    factureProforma.init();
});