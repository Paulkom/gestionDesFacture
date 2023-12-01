var gestionVente = function () {
    /**
     * Vérifie s'il s'agit bien d'un élément HTML
     * @param element
     * @returns {boolean}
     */
    var selectedoptions = [];
    var unicite=[];
    var fieldList= new Object;
    var validation;
    var  montantTva = 0;
    const isElement = (element) => {
        return element instanceof Element || element instanceof HTMLDocument;
    }
    
    const ajaxDeleteResult = (form, data) => {
        form.remove();
        if(data != null)
        {
            // console.log(data);
        }
    }

    const addFormDeleteLink = (form) => {
        if (isElement(form)) {
            const removeFormButton = form.querySelectorAll('button.remove');
            let succes = false;
            removeFormButton.forEach(btnremove => {
                btnremove.addEventListener('click', (e) => {
                var namer =$(form).find('select[name*="[personnel]"]').attr('name');
                delete selectedoptions[namer];
                console.log(namer,selectedoptions,'namer');
                form.remove();
                desactiverSelect();    
                form.remove();
                remises();
                calculeDestotaux();
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
                $(div).find('[data-control="select2"]').select2();
                validation.addField($(div).find('select[name*="[produit]"]').attr('name'),{
                    validators: {
                        notEmpty: {
                            message: 'Selectionnez un produit '
                        }
                    }
                })
                validation.addField($(div).find('select[name*="[magasin]"]').attr('name'),{
                    validators: {
                        notEmpty: {
                            message: 'Selectionnez un magasin '
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

                validation.addField($(div).find('[name*="[mselectagasin]"]').attr('name'),{
                    validators: {
                        notEmpty: {
                            message: 'Selectionnez un magasin'
                        }
                    }
                })

                Inputmask("decimal", {
                    "rightAlignNumerics": false
                }).mask(".decimal");

                $(container).find("input:not([readonly=readonly]").attr('style',"text-align: left;")
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
        form.find('select[name*="commande_client[typeCommande]"]').change(function() {
            if (this.value == 'Au comptoir') {
                comptoire.show();
            }
            else {
                comptoire.hide();
            }
        });
    }*/

    function vente() {
       
        if(container != null)
        {

            $(container).find('[data-control="select2"]').select2(); 
            container.querySelectorAll('.add_vente_item_link').forEach(btn => {
            btn.addEventListener("click", addFormToCollection);
            const holders = document.querySelectorAll('.' + btn.dataset.collectionHolderClass);
                holders.forEach(h => {
                    const divs = h.querySelectorAll('div');
                    divs.forEach(div => {
                        addFormDeleteLink(div);
                    });
                });
            });
            
            container.querySelectorAll('.add_element_item_link').forEach(btn => {
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
            if(btnS != null)
            {
                btnS.forEach(btn => {
                    const form = btn.closest('form');
                    if (form != null && form != undefined) {
                        validation = valitation(form,produit)
                        btn.addEventListener("click", function(e) {
                            
                            e.preventDefault();
                            var erreur = false;
                            for (const key in unicite) {
                                if (unicite[key]) {
                                    erreur = true;
                                    break;
                                }
                            }
                            if($(this).hasClass("imprimer") == true){
                                $("#commande_client_truc").val("OUI");
                            }else{
                                $("#commande_client_truc").val("NON"); 
                            }
                            if ($(container).find('select[name*="[acheteur]"]').val()=='' &&
                                $(container).find('input[name*="[nom]"]').val()=='' &&
                                $(container).find('input[name*="[prenom]"]').val()=='' &&
                                $(container).find('input[name*="[denomination]"]').val()=='' &&
                                $(container).find('input[name*="[raisonSociale]"]').val()=='') 
                            {
                                toastr.error("veuillez selectionner ou ajouter un client ");

                            }else{
                               
                                checknet($(container).find('form'));
                                save(form,validation,btn,erreur);
                                // $(container).find('form').submit();
                            }
                            
                        });
                    }
                });
            }
            
            /*const addbtn = document.querySelector('[data-kt-entity-table-filter="new_form"]');
            addbtn.addEventListener('click', function (e) {
                loadnewForm();
                vente();
            });*/

            document.querySelector('#annuler').addEventListener('click', function (e) {
               $("#commande_client_nom").val('');
               $("#commande_client_prenom").val('');
               $("#commande_client_ifu").val('');
               $("#commande_client_telephone1").val('');
               $("#commande_client_telephone2").val('');
               $("#commande_client_email").val('');
               $("#commande_client_adresse").val('');
               $("#commande_client_dateNais").val('');
               $("#commande_client_denomination").val('');
               $("#commande_client_raisonSociale").val('');
               $("#commande_client_sigle").val('');
               $("#commande_client_acheteur").prop("disabled", false);
               if($("#commande_client_acheteur").hasClass("new")){
                   $("#commande_client_acheteur option:selected").first().remove();
                   $("#commande_client_acheteur").toggleClass("new");
               }

            });
            document.querySelector('#ajouter').addEventListener('click', function (e) {
                var statut  = $('input[name*="statut"]:checked').val();
                var text    = null;
                if( statut == 'Physique'){
                    text = $("#commande_client_nom").val()+' '+$("#commande_client_prenom").val();
                }else{
                    text = $("#commande_client_denomination").val();
                }
                $("#commande_client_acheteur").toggleClass("new");
                
                $("#commande_client_acheteur").prop("disabled", false);
                $("#commande_client_acheteur").prepend("<option selected value=''>" + text +"</option>");
                $("#commande_client_acheteur").select2();
                $("#commande_client_acheteur").prop("disabled", true);

             });

            document.querySelector('#newclient').addEventListener('click', function (e) {
                 $(this).parent().parent().find('select').prop("disabled", true);
            });
            swishStatut()
            swishTypeComd(validation);
        }
    }
    

    //VERIFICATION DE CONNEXION INTERNET
    function checknet(form){

        let input = form.find('input[name*="commande_client[checknet]"]');
        var online = navigator.onLine;
        if (online) {
            console.log('yes online');
            input.val(1);
        } else {
            console.log('no online');
            input.val(0);
            toastr.error("Connexion internet indisponible");
        }
    }

    function swishTypeComd(validation){
        let form = $(container).find('form');
        $.each($(form.find('select[name*="[typeCommande]')), function (i, elt) {
           var select = $(elt);
           $.each($(form.find('.comp')), function (i, elt) {
            $(elt).parent().find('.form-label').addClass('required');
            $(elt).prop('required', 'required');
                validation.addField($(elt).attr('name'),{
                    validators: {
                        notEmpty: {
                            message: $(elt).prev().text()+' est obligatoire'
                        }
                    }
                })
            })
            select.change(function () {
                if(select.val() == "Au comptoir") {
                    $.each($(form.find('.comp')), function (i, elt) {
                        $(elt).prop('required', 'required');
                        validation.addField($(elt).attr('name'),{
                            validators: {
                                notEmpty: {
                                    message: $(elt).prev().text()+' est obligatoire'
                                }
                            }
                        })
                    })
                }else{
                    $.each($(form.find('.comp')), function (i, elt) {
                        $(elt).prop('required', '');
                        validation.removeField($(elt).attr('name'));
                        $(elt).removeClass('is-invalid');
                    })
                }
            })
        })
    }


    var desactiverSelect=()=>{
        var mesoption=[];
        for(var key in selectedoptions) {
            mesoption.push(selectedoptions[key]);
        }

        $.each($(container).find('select.produit'), function (i, elt) {
            $.each($(elt).find('option:not(:selected,:disabled)'), function(i, item) {
                if ($.inArray($(this).val() , mesoption) != -1) {
                    $(item).prop("disabled", true);
                } else {
                    $(item).prop("disabled", false);
                }
                //

                if(parseFloat($(item).attr("data-qtestockcond")) > 0 ) {
                   //$(item).prop("disabled", false);
                }else{
                    //$(item).prop("disabled", true); 
                }
            });
        })
    }

    var eventchange =()=>{
        desactiverSelect()
        $.each($($(container).find('select')), function (i, elt) {
            let select = $(elt);
            name = select.attr('name');
            let blocproduit=select.parent().parent().parent();
            if(name.includes('[produit]')){
                select.change(function () {
                    var option = $(this).find('option:selected');
                    var qtitecom  = option.data('qtite');
                    var prixmin   = option.data('prixmin');
                    var prixmax  = option.data('prixmax');
                    var prix   = option.data('prixvent');
                    var prixttc  = option.data('prixttc');
                    var prixht   = option.data('prixht');
                    montantTva   = parseFloat(prixttc) - parseFloat(prixht);
                    var magdefault   = option.data('magdefault');
                    var magqte   = option.data('magqte');
                    var qtestockcond = option.data('qtestockcond');
                    var qtestockcondLogique = option.data('qtestockcondlogique');
                    let estService = option.attr('est-service');
                    var est_tasable = option.data('esttasable');

                    //calcul pièce et carton
                    let ppc = parseFloat(option.attr('piece-par-carton'));
                    let m2pc = parseFloat(option.attr('m2-par-carton'));
                    let modeCarreau = option.attr('est-mode-carreau');

                    if(modeCarreau == 1){
                        let result = calculPieceCarton(1, ppc, m2pc);
                        option.closest('tr').find('input[name*="carton"]').val(result['carton']);
                        option.closest('tr').find('input[name*="piece"]').val(result['piece']);
                        option.closest('.fv-row').find('.text-muted').html( result['carton'] + ' Carts ' + result['piece'] + ' pièces' );
                    }else{
                        option.closest('tr').find('input[name*="carton"]').val();
                        option.closest('tr').find('input[name*="piece"]').val();
                        option.closest('.fv-row').find('.text-muted').html('');
                    }


                    /*var textselected = $(this).find('option:selected').text();
                    var tab = textselected.split(" ");
                    var prix = tab[tab.length -1]; */

                    selectedoptions[$(this).attr('name')] = $(this).val();
                    
                    blocproduit.find('input[name*="[prix]"]').val(parseFloat(prix));
                    blocproduit.find('input[name*="[prixHt]"]').val(parseFloat(prixht));
                    // blocproduit.find('input[name*="[montantTvap]"]').val(parseFloat((prix-prixht)));
                    blocproduit.find('input[name*="[montantTvap]"]').val(parseFloat((prixttc - prixht)).toFixed(0)); // DESCHANEL
                    blocproduit.find('input[name*="[qtite]"]').val(1);
                    blocproduit.find('input[name*="[remise]"]').val(0);      
                    // blocproduit.find('input[name*="[montant]"]').val(parseFloat(prixht));
                    blocproduit.find('input[name*="[montant]"]').val(parseFloat(prixht).toFixed(0)); // DESCHANEL
                    blocproduit.find('input[name*="[montantTtcp]"]').val(parseFloat(prixttc).toFixed(0));
                    blocproduit.find('input[name*="[montantHtAprRse]"]').val(parseFloat(prixht).toFixed(0));

                    if(estService != 1){
                        blocproduit.find('.stock').text('SR='+qtestockcond);
                        blocproduit.find('.stocklogique').text('SL='+qtestockcondLogique);
                    }

                    if(est_tasable =="1"){
                        blocproduit.find('.taxable').val(1);
                    }else{
                        blocproduit.find('.taxable').val(0);
                    }

                    if(magqte > 0){
                        blocproduit.find('select[name*="[magasin]"]').val(magdefault);
                    }else{
                        blocproduit.find('select[name*="[magasin]"]').val('');
                        if(estService == 1){
                            //j'enleve la validation au niveau de magasin pour les services
                            let elt = blocproduit.find('select[name*="[magasin]"]');
                            $(elt).prop('required', '');
                            validation.removeField($(elt).attr('name'));
                            $(elt).removeClass('is-invalid');
                        }
                    }

                    

                    blocproduit.find('select[name*="[magasin]"]').select2();
                    $(select).parent().find('div.fv-plugins-message-container.invalid-feedback').text('');
                    

                    unicite[select.attr('name')] = false;

                    
                    //calcule de la remise sur montant total de la facture
                    remises();
                    //calcule des montants totales
                    calculeDestotaux();
                    desactiverSelect()
                })
            }

            if (name.includes('[produitCondComClits]')) {
                if(name.includes('[typeRemise]')){
                    select.change(function() {
                        if (select.val() != '') {
                            // blocproduit.find('input[name*="[valeurRemise]"]').attr('readonly',false);
                            // blocproduit.find('input[name*="[valeurRemise]"]').removeClass('form-control-solid')
                            remiseSurProduit(blocproduit);
                        }else{
                            // blocproduit.find('input[name*="[valeurRemise]"]').attr('readonly',true);
                            // blocproduit.find('input[name*="[valeurRemise]"]').addClass('form-control-solid')
                            remiseSurProduit(blocproduit);
                        }
                    });
                }
                remiseSurProduit(blocproduit);
            }

            if(name.includes('[typeFacture]')){
                select.change(function (){
                    let element = document.getElementById('ligne_exportation');
                    let form = $(container).find('form');

                    if($(this).val() == 'EV'){
                        
                        element.classList.remove("d-none");
                        $.each($(form.find('.vente_exportation')), function (i, elt) {
                            $(elt).prop('required', 'required');
                            validation.addField($(elt).attr('name'),{
                                validators: {
                                    notEmpty: { message: $(elt).prev().text()+' est obligatoire' }
                                }
                            })
                            $(elt).parent().find('.form-label').addClass('required');
                        });

                    }else{
                
                        $.each($(form.find('select.vente_exportation, input.vente_exportation')), function (i, elt) {
                            // console.log(elt);
                            $(elt).prop('required', '');
                            validation.removeField($(elt).attr('name'));
                            $(elt).removeClass('is-invalid');
                            $(elt).parent().find('.form-label').removeClass('required');
                        });
                        element.classList.add("d-none");
                    }
                });
            }

            if(name.includes('[typeCommande]')){
                let form = $(container).find('form');
                let comptoire = form.find('.comptoire').parent();
                if (select.find('option:selected').val() == 'Au comptoir') {
                    comptoire.show();
                } else {
                    comptoire.hide();
                }
                select.change(function() {
                    if (this.value == 'Au comptoir') {
                        comptoire.show();
                    }
                    else {
                        comptoire.hide();
                    }
                });
            }

            if(name.includes('commande_client[typeRemise]')){
                select.change(function() {
                    if (select.val() != '') {
                        $(container).find('input[name*="commande_client[valeurRemise]"]').attr('readonly',false);
                        $(container).find('input[name*="commande_client[valeurRemise]"]').removeClass('form-control-solid')
                        remises();
                    }else{
                        $(container).find('input[name*="commande_client[valeurRemise]"]').attr('readonly',true);
                        $(container).find('input[name*="commande_client[valeurRemise]"]').addClass('form-control-solid')
                    }
                });
            }

            if(name.includes('commande_client[tauxAib]')){
                select.change(function () {
                    calculeDestotaux();
                })
            }

            if(name.includes('[magasin]')){
                select.change(function () {
                let blocproduit=select.parent().parent().parent();
                var magid = $(this).val();
                var produitid = blocproduit.find('select[name*="[produit]"]').val();
                $.ajax({
                    url: app_loader_prod_par_magasin,
                    dataType: "Json",
                    data: {
                        produitid: isNaN(produitid) ? 0 : produitid,
                        magid: isNaN(magid) ? 0 : magid
                    },
                    type: "GET",
                    beforeSend: function () {
                        ajaxBeforeSend();
                    },
                    success: function (resulta) {
                        var qtite = blocproduit.find('input[name*="[qtite]"]').val();
                        if(parseInt(qtite) > parseInt(resulta[0])) {
                            var msg='La quantité en stock dans ce magasin est insuffisante ('+resulta[0] +')'
                            toastr.error(msg);
                            $(select).parent().find('div.fv-plugins-message-container.invalid-feedback').text(msg);
                            unicite[select.attr('name')] =true;
                        }else{
                            unicite[select.attr('name')] =false;
                            $(select).parent().find('div.fv-plugins-message-container.invalid-feedback').text('');
                        }

                    },
                    complete: function () {
                        ajaxComplete();
                    },
                });
            });

            }
        });

         $.each($($(container).find("input:not([readonly=readonly]")),function (k, elt) {
            var name = $(this).attr("name");
            if (typeof name != "undefined") {
                var input = $(elt);
                let blocproduit = input.parent().parent().parent();
                input.keyup(function () {
                    if(name.includes('[produitCondComClits]') && !name.includes('[prix]')){
                        var montant = 0;
                        var qtite = parseFloat(blocproduit.find('input[name*="[qtite]"]').val());
                        let seletproduit = $(blocproduit).find('select[name*="[produit]"]');
                        var option = $(seletproduit).find('option:selected');
                        var prix = parseFloat(blocproduit.find('input[name*="prix"]').val());
                        var prixht = parseFloat(blocproduit.find('input[name*="prixHt"]').val());

                        if(option.val()!='') {
                            // if(name.includes('[prix]')) {
                            //     var prixmin   = option.data('prixmin');
                            //     var prixmax  = option.data('prixmax');
                            //     var prixvent   = option.data('prixvent');
                            //     if(prix >= parseFloat(prixmin) && prix <= parseFloat(prixmax)){
                            //         prix = parseFloat(blocproduit.find('input[name*="prix"]').val());
                            //     }else{
                            //         prix = parseFloat(prixvent);
                            //     } 
                            // }

                            montant = prix*qtite;

                            //calcul de piece et carton
                            let ppc = parseFloat(option.attr('piece-par-carton'));
                            let m2pc = parseFloat(option.attr('m2-par-carton'));
                            let modeCarreau = option.attr('est-mode-carreau');

                            if(modeCarreau == 1){
                                if(qtite > 0){
                                    let result = calculPieceCarton(qtite, ppc, m2pc);
                                    option.closest('tr').find('input[name*="carton"]').val(result['carton']);
                                    option.closest('tr').find('input[name*="piece"]').val(result['piece']);
                                    option.closest('.fv-row').find('.text-muted').html( result['carton'] + ' Carts ' + result['piece'] + ' pièces' );
                                }else{
                                    option.closest('tr').find('input[name*="carton"]').val(0);
                                    option.closest('tr').find('input[name*="piece"]').val(0);
                                    option.closest('.fv-row').find('.text-muted').html('0 Carts 0 pièces');
                                }
                            }
                            
                            
                            //blocproduit.find('input[name*="[montantHtAprRse]"]').val(montant);
                            //blocproduit.find('input[name*="[remise]"]').val(montant);
                            //blocproduit.find('input[name*="[montantTvap]"]').val(mttva);
                            //remise sur un produit
                            remiseSurProduit(blocproduit);
                            //calcule des montants totales
                            calculeDestotaux(); 
                        } 
                    }
                    //gestion des remises
                    remises();

                    //gestion des reliquats
                    var montantttc = $(container).find('input[name*="[montantTtc]"]').val();
                    if (name.includes('[montantRecu]')) {
                        if(parseFloat($(container).find('input[name*="[montantRecu]"]').val()) >= parseFloat(montantttc)) {
                            $(container).find('input[name*="[reliquat]"]').val(parseFloat($(container).find('input[name*="[montantRecu]"]').val() - montantttc));
                        }else{
                            $(container).find('input[name*="[reliquat]"]').val(0);
                        }  
                    }else{
                        $(container).find('input[name*="commande_client[montantRecu]"]').val('');
                    }
                    
                })
                
                if(name.includes('[produitCondComClits]')){
                    input.focusout(function () {
                        if(name.includes('[prix]')) {
                            var montant = 0;
                            var qtite= parseFloat(blocproduit.find('input[name*="[qtite]"]').val());
                            let seletproduit = $(blocproduit).find('select[name*="[produit]"]');
                            var option = $(seletproduit).find('option:selected');
                            var prix = parseFloat(blocproduit.find('input[name*="[prix]"]').val());
                            var prixht  = parseFloat(blocproduit.find('input[name*="[prixHt]"]').val());
                            if (option.val()!='') {
                               
                                var prixmin   = option.data('prixmin');
                                var prixmax  = option.data('prixmax');
                                var prixvent   = option.data('prixvent');
                                if(prix >= parseFloat(prixmin) && prix <= parseFloat(prixmax)){
                                    prix = parseFloat(blocproduit.find('input[name*="[prix]"]').val());
                                    prixht =(prix/1.18);
                                    blocproduit.find('input[name*="[prixHt]"]').val(parseFloat(prixht).toFixed(2));
                                    blocproduit.find('input[name*="[montant]"]').val(parseFloat(parseFloat(prixht) * qtite).toFixed(2));
                                    // console.log(parseFloat(prixht).toFixed(2));
                                }else{
                                    if(prixmin != '' && prixmax != ''){
                                        var message = "Le prix du produit "+ option.text() + " doit être comprise entre "+prixmin+" et "+prixmax;
                                        toastr.error(message);
                                    }
                                    // console.log('prix min',prixmin);
                                    blocproduit.find('input[name*="[prix]"]').val(prixvent);
                                    prix = parseFloat(prixvent);
                                } 
                                montant = prixht*qtite;
                                blocproduit.find('input[name*="[montant]"]').val(montant);
                               //blocproduit.find('input[name*="[montantHtAprRse]"]').val(montant);
                                //remise sur un produit
                                remiseSurProduit(blocproduit);
                                //calcule des montants totales
                                calculeDestotaux(); 
                            }
                            //gestion des remises
                            remises();   
                        }

                        if(name.includes('[qtite]')){
                                let blocproduit=input.parent().parent().parent();
                                var magasin = blocproduit.find('select[name*="[magasin]"]');
                                var magid = $(magasin).val();
                                var produitid = blocproduit.find('select[name*="[produit]"]').val();
                                if (magid !='' && produitid != '' && magid != undefined && produitid != undefined) {
                                    $.ajax({
                                        url: app_loader_prod_par_magasin,
                                        dataType: "Json",
                                        data: {
                                            produitid: isNaN(produitid) ? 0 : produitid,
                                            magid: isNaN(magid) ? 0 : magid
                                        },
                                        type: "GET",
                                        beforeSend: function () {
                                            ajaxBeforeSend();
                                        },
                                        success: function (resulta) {
                                            var qtite = blocproduit.find('input[name*="[qtite]"]').val();
                                            if(parseInt(qtite) > parseInt(resulta[0])) {
                                                var msg='La quantité en stock dans cet magasin est insuffisante ('+resulta[0] +')'
                                                toastr.error(msg);
                                                $(magasin).parent().find('div.fv-plugins-message-container.invalid-feedback').text(msg);
                                                $(input).parent().find('div.fv-plugins-message-container.invalid-feedback').text(msg);
                                                unicite[$(magasin).attr('name')] =true;
                                            }else{
                                                unicite[$(magasin).attr('name')] =false;
                                                $(input).parent().find('div.fv-plugins-message-container.invalid-feedback').text('');
                                                $(magasin).parent().find('div.fv-plugins-message-container.invalid-feedback').text('');
                                            }
                                        },
                                        complete: function () {
                                            ajaxComplete();
                                        },
                                    });
                                }
                        }
                    });
                    // $(container).find('input[name*="commande_client[montantRecu]"]').val('')
                    // $(container).find('input[name*="commande_client[reliquat]"]').val('')
                }
                if(name.includes('commande_client[montantRecu]')){
                    input.focusout(function () {
                        var montantTtc= $(container).find('input[name*="commande_client[montantTtc]"]').val();
                        var montantRecu = $(container).find('input[name*="commande_client[montantRecu]"]').val();
                        
                        if(parseFloat(montantTtc) > 0 && parseFloat(montantRecu) > 0) {
                            if(parseFloat(montantRecu) < parseFloat(montantTtc)) {
                                var message = "Le montant reçu ("+ montantRecu +") doit être supérieur ou égal au montant TTC ("+montantTtc+")";
                                toastr.error(message);
                                $(container).find('button[type="submit"]').attr('disabled',true)
                            }else{
                                $(container).find('button[type="submit"]').attr('disabled',false)
                            }
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
        var totalsomme = 0.0;
        var totalsommeremise = 0.0;
        var totaltva = 0.0;
        var totalts=0.0;
        totalttc=0.0;
        let montantParProduit = $(".montant");
        let montantParRseProduit = $(".montanthtaprrsep");
        let montanttva = $(".montanttvap");
        let montantttcp = $(".montantttcp");
        let montantts = $(".montantts");
        let prixTtcLigne = $(".prixttcligne"); //deschanel
        let prixHtLigne  = $(".prixhtligne"); //deschanel
        let qteligne  = $(".qteligne"); //deschanel
        let lignesTaxables = $(".taxable"); //deschanel
        for(let i = 0; i < montantParProduit.length; i++) {
            if($(montantParProduit[i]).val() !=''){
                totalsomme += parseFloat($(montantParProduit[i]).val());
            }
            if($(montantParRseProduit[i]).val() !=''){
                totalsommeremise += parseFloat($(montantParRseProduit[i]).val());
            }
            if($(montanttva[i]).val() !=''){
                //Deschanel
                if( parseFloat($(prixHtLigne[i]).val()) == parseFloat($(prixTtcLigne[i]).val()) ){
                    totaltva += 0;
                }else{
                    totaltva += parseFloat($(prixTtcLigne[i]).val() / 1.18 * 0.18 * $(qteligne[i]).val());
                }
            }
            if($(montantttcp[i]).val() !=''){
                totalttc += parseFloat($(montantttcp[i]).val());
            }
            if($(montantts[i]).val() !=''){
                // console.log($(lignesTaxables[i]).val());
                if($(lignesTaxables[i]).val()== 1){
                    totalts += parseFloat($(montantts[i]).val() / 1.18);
                    totaltva += parseFloat($(montantts[i]).val() / 1.18 * 0.18);  // Deschanel
                    
                }else{
                    totalts += parseFloat($(montantts[i]).val());
                    totaltva += 0;  // Deschanel
                }
                
            }
        }
        var montantAib=0;
        let selecttauxAib = $(container).find('select[name*="[tauxAib]"]');
        var value = selecttauxAib.find('option:selected').val();
        if(value  != '') {
            montantAib = (totalsommeremise * parseFloat(value))/100
            totalttc +=parseFloat(montantAib);
        }
        // if(totalts  != '') {
        //     totalttc +=parseFloat(totalts);
        // }

        $(container).find('input[name*="[montantHt]"]').val(totalsomme.toFixed(0));
        $(container).find('input[name*="[montantHtToAprRse]"]').val(totalsommeremise.toFixed(0));
        $(container).find('input[name*="[montantTtc]"]').val(totalttc.toFixed(0));
        $(container).find('input[name*="[montantTva]"]').val(totaltva.toFixed(0));
        $(container).find('input[name*="[montantAib]"]').val(montantAib.toFixed(0));
        $(container).find('input[name*="[montantTS]"]').val(totalts.toFixed(0));

        $(container).find('input[name*="commande_client[montantRecu]"]').val('')
        $(container).find('input[name*="commande_client[reliquat]"]').val('')
        
    }

    var remises =()=>{
        let selettypeRemise = $(container).find('select[name*="commande_client[typeRemise]"]');
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
                $(container).find('input[name*="commande_client[remise]"]').val(remise);
                $(container).find('input[name*="[montantTtc]"]').val(montantTotal - remise); 
            }else{
                $(container).find('input[name*="commande_client[remise]"]').val(0);
                $(container).find('input[name*="[montantTtc]"]').val(montantTotal); 
            }
        }  
    }
    
    var remiseSurProduit =(element)=>{
        let blocproduit=element;
        let selettypeRemise = $(blocproduit).find('select[name*="[typeRemise]"]');
        let seletproduit = $(blocproduit).find('select[name*="[produit]"]');
        var option = $(seletproduit).find('option:selected');
        var esttasable = option.data('esttasable');
        var value = $(selettypeRemise).find('option:selected').val();
        var remise = 0;
        var valeurRemise = parseFloat($(blocproduit).find('input[name*="[valeurRemise]"]').val());
        var montantHt = parseFloat($(blocproduit).find('input[name*="[montant]"]').val());
        var prix = parseFloat($(blocproduit).find('input[name*="[prix]"]').val());
        var prixht = parseFloat($(blocproduit).find('input[name*="[prixHt]"]').val());
        var qtite = parseFloat($(blocproduit).find('input[name*="[qtite]"]').val());
        if(prix) {
            var prixremise = prixht;
            if(valeurRemise) {
                //var montantHtAprRse =0;
                if(value =='%' && valeurRemise !='') {
                    remise = (prixht*valeurRemise)/100;
                    prixremise = prixht-remise;
                   // montantHtAprRse = parseFloat(prixremise)*parseFloat(qtite);
                }else if(value =='MT' && valeurRemise !=''){
                    remise = valeurRemise;
                    prixremise = prixht-remise;
                   // montantHtAprRse = parseFloat(prixremise)*parseFloat(qtite);
                }
            }
            /*$(blocproduit).find('input[name*="[remise]"]').val(remise);
                $(blocproduit).find('input[name*="[montantHtAprRse]"]').val((*qtite)); 

            }else{
                $(blocproduit).find('input[name*="[remise]"]').val(0);
                $(blocproduit).find('input[name*="[montantHtAprRse]"]').val(montantHtAprRse); 
            }*/
            var ts =  parseFloat($(blocproduit).find('input[name*="[taxeSpecifique]"]').val());
            var ts_ht = 0;
            ts = isNaN(ts) ? 0 : ts;
            if(!isNaN(ts)){
                var ts_ht = ts /1.18;
                (esttasable)  ? (ts_ht = (ts /1.18)) : (ts_ht = ts);
            }
            // if(ts == ''){ ts = 0; }
            var mttcp = 0.0;
            $(blocproduit).find('input[name*="[montant]"]').val(parseFloat(prixht*qtite).toFixed(0));
            $(blocproduit).find('input[name*="[remise]"]').val(remise*qtite);
            
            // console.log(prixht, qtite);

            if(esttasable=="1") {
                // mttcp = ((prixremise*qtite)+(prixremise*qtite*0.18));
                $(blocproduit).find('input[name*="[montantHtAprRse]"]').val(parseFloat( (prix/1.18 - remise)*qtite + ts_ht).toFixed(0)); 
                mttcp = parseFloat(((prix / 1.18 - remise)*qtite)*1.18); //DESCHANEL
               
                // $(blocproduit).find('input[name*="[montantTvap]"]').val(parseFloat(prixremise*qtite*0.18).toFixed(2));
                // $(blocproduit).find('input[name*="[montantTvap]"]').val(((parseFloat(prixremise*0.18).toFixed(2))*qtite).toFixed(0)); //DESCHANEL      
                $(blocproduit).find('input[name*="[montantTvap]"]').val( (mttcp - parseFloat((prix*qtite)/1.18 - remise)).toFixed(0)  ); //DESCHANEL      
            }else{
                $(blocproduit).find('input[name*="[montantHtAprRse]"]').val(parseFloat( (prix - remise)*qtite + ts_ht).toFixed(0)); 
                $(blocproduit).find('input[name*="[montantTvap]"]').val(0);  
                // mttcp = ((prixremise*qtite));
                mttcp = parseFloat((prix - remise)*qtite); //DESCHANEL
            }
            
            $(blocproduit).find('input[name*="[montantTtcp]"]').val(parseFloat(mttcp + ts).toFixed(0)); 

        } 
    }

    var handlelistes =()=>{
        // Select all delete buttons
        const buttons = document.querySelectorAll('[data-kt-entity-table-filter="list"]');
        const bloc = document.querySelector('[data-call-form="ajax"]');
        buttons.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();
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
                            vente();
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

    // var handlelistes = () => {
    //     // Select all delete buttons
    //     const buttons = document.querySelectorAll('[data-kt-entity-table-filter="list"]');
    //     const bloc = document.querySelector('[data-call-form="ajax"]');
    //     buttons.forEach(d => {
    //         d.addEventListener('click', function (e) {
    //             e.preventDefault();
    //             $.ajax({
    //                 type: "GET",
    //                 url: e.target.dataset.url,
    //                 dataType: "json",
    //                 beforeSend: function () {
    //                     const loadingEl = document.createElement("div");
    //                     document.body.prepend(loadingEl);
    //                     loadingEl.classList.add("page-loader");
    //                     loadingEl.classList.add("flex-column");
    //                     loadingEl.classList.add("bg-dark");
    //                     loadingEl.classList.add("bg-opacity-25");
    //                     loadingEl.innerHTML = `
    //                                         <span class="spinner-border text-primary" role="status"></span>
    //                                         <span class="fs-6 fw-semibold mt-5" style="color:#ffffff">Chargement...</span>
    //                                     `;
    //                     document.body.classList.add('page-loading');
    //                     document.body.setAttribute('data-kt-app-page-loading', "on")
    //                 },
    //                 success: function(data){
    //                     $(bloc).html(data.html);
    //                     const form = bloc.querySelector('form');
    //                     if(form){
    //                         form.querySelectorAll('select').forEach(s => {
    //                             $(s).select2();
    //                         });
    //                         vente();
    //                     }else{
    //                         if($(bloc).find('.kt_entity_table').length > 0)
    //                         {
    //                             tableau = $(bloc).find('.kt_entity_table')[0];
    //                             loadDataTable();
    //                         }
    //                     }
    //                 },
    //                 complete: function () {
    //                     document.body.classList.remove('page-loading');
    //                     document.body.removeAttribute('data-kt-app-page-loading');
    //                 }
    //             });
    //         })
    //     });
    // }
    return {
        init: function () {
            loadnewForm();
            handlelistes();
            vente();
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
    gestionVente.init();
});