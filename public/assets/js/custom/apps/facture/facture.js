
"use strict";
var GESTIONFACTURE = function() {
   
    /**
     * Vérifie s'il s'agit bien d'un élément HTML
     * @param element
     * @returns {boolean}
     */
    const isElement = (element) => {
        return element instanceof Element || element instanceof HTMLDocument;
    }
    const ajaxBeforeSend = () => {
        let loadingEl = document.createElement("div");
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
    }
    
    const ajaxComplete = () => {
        document.body.classList.remove('page-loading');
        document.body.removeAttribute('data-kt-app-page-loading');
    }


    const addFormDeleteLink = (form) => {
        if (isElement(form)) {
            const removeFormButton = form.querySelector('.collection-action');
            removeFormButton.addEventListener('click', (e) => {
                $.ajax({
                    type: "POST",
                    url: removeFormButton.dataset.url,
                    data: {token: removeFormButton.dataset.token},
                    dataType: "json",
                    beforeSend: ajaxBeforeSend,
                    success: function(data){
                        /*ajaxDeleteResult(form, data);*/
                    },
                    complete: ajaxComplete
                });
                delete form_children[form.getAttribute('id')];
                form.remove();
            });
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
            /**
             * Récupérer l'élément conteneur de formulaires imbriqués
             */
            const collectionHolder = container.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
            /**
             * Récupérer la forme brute du formulaire à imbriqué
             */
            const prototype = collectionHolder.dataset.prototype;
            const div = document.createElement('div');
            const protokey = collectionHolder.dataset.collectionKey;
            var regex = new RegExp(protokey, "g");
            div.setAttribute('data-index', collectionHolder.dataset.index);
            const prefix = collectionHolder.dataset.collectionPrefix.replace(regex, collectionHolder.dataset.index);
            div.setAttribute('id', prefix);
            div.classList.add("ligne");
            div.classList.add("form-child");
            div.innerHTML = prototype.replace(regex, collectionHolder.dataset.index);

            $(collectionHolder).find('[data-control="select2"]').select2();
          
            /**
             * Ajouter la forme html du formulaire imbriqué au conteneur
             */
            collectionHolder.appendChild(div);
            
            addFormDeleteLink(div);
            let seletcom = $(container).find('select[name*="[commandeCli]"]');
            var selected = $(seletcom).find('option:selected').val();
            if (selected) {
                console.log(seletcom);
                $.ajax({
                    url: app_select_loader,
                    dataType: "Json",
                data: {
                    id: isNaN(selected) ? 0 : selected,
                },
                type: "GET",
                beforeSend: function () {
                    ajaxBeforeSend();
                },
                success: function (data) {
                   
                },
                complete: function () {
                    ajaxComplete();
                },
            });
        }
            factures();
            eventChange();
            /** Incrémentation de l'index après insertion du formulaire imbriqué **/
            collectionHolder.dataset.index++;
            return collectionHolder;
        }           
    }

    var eventChange=()=>{
        $.each($($(container).find('select')), function (i, elt) {
            let select = $(elt);
            name = select.attr('name');
            let blocproduit = select.parent().parent();
            var selected = $(this).find('option:selected').val();
           
            console.log('select',select);
            if(name.includes('[produitComClit]')){
                if (selected) {
                    var option = $(this).find('option:selected');
                    var qtitecom  = option.data('qtite');
                    blocproduit.find('input[name*="[qtiteComd]"]').val(qtitecom);
                }
                select.change(function () {
                    var option = $(this).find('option:selected');
                    var qtitecom  = option.data('qtite');
                    var prix   = option.data('prix');

                    blocproduit.find('input[name*="[qtiteComd]"]').val(qtitecom);
                    blocproduit.find('input[name*="[prix]"]').val(prix);
                    blocproduit.find('input[name*="[qtite]"]').val('');
                    blocproduit.find('input[name*="[montant]"]').val('');
                    var totalsomme = 0;
                    let montantParProduit = $(".montant");
                    for(let i = 0; i < montantParProduit.length; i++) {
                        if($(montantParProduit[i]).val() !=''){
                            totalsomme += parseFloat($(montantParProduit[i]).val());
                        }
                    }
                    $(container).find('input[name*="[montantTotal]"]').val(totalsomme);
                })
            }
        });

        $.each($($(container).find("input:not([readonly=readonly]")),function (k, elt) {
            var name = $(this).attr("name");
            if (typeof name != "undefined"){
                var input = $(elt);
                let blocproduit = input.parent().parent();
                 input.keyup(function () {
                    var prix   = parseFloat(blocproduit.find('input[name*="[prix]"]').val());
                    var qtitecom = parseFloat(blocproduit.find('input[name*="[qtiteComd]"]').val());
                    var montant = 0;
                    var qtite= parseFloat(blocproduit.find('input[name*="[qtite]"]').val());
                    console.log('qtite',qtite);
                    if(parseFloat(qtite) > 0 && parseFloat(qtite) <=  parseFloat(qtitecom)){
                        montant= prix*qtite;
                        blocproduit.find('input[name*="[montant]"]').val(montant);  
                    }else{
                        $(container).find('input[name*="[montantTotal]"]').val(0);
                        blocproduit.find('input[name*="[montant]"]').val(0);
                        blocproduit.find('input[name*="[qtite]"]').val('');
                    }
                    var totalsomme = 0;
                    let montantParProduit = $(".montant");
                    for(let i = 0; i < montantParProduit.length; i++) {
                        if($(montantParProduit[i]).val() !=''){
                           totalsomme += parseFloat($(montantParProduit[i]).val());
                        }
                    }
                    $(container).find('input[name*="[montantTotal]"]').val(totalsomme);
                })
            }
        });
    }

    var handlelistes = () => {
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

    var loadnewForm =()=>{
        if($(container).find('form').attr('action') === undefined ){
            $(newform).find('[data-control="select2"]').select2();
            $(container).html(newform);
            $(container).find('[data-control="select2"]').select2();  
        }
    }

    const factures = (collectionHolder = null) => {
        let parent = container;
        if(collectionHolder != null)
            parent = collectionHolder;
        $(parent).find('[data-control="select2"]').select2();
        parent.querySelectorAll('.add_facture_item_link').forEach(btn => {
            btn.addEventListener("click", addFormToCollection);
            const holders = container.querySelectorAll('.' + btn.dataset.collectionHolderClass);
            holders.forEach(h => {
                const divs = h.querySelectorAll('div.form-child');
                divs.forEach(div => {
                    if(collectionHolder == null)
                        form_children[div.getAttribute('id')] = [];
                        addFormDeleteLink(div);
                });
            });
        });

        const btnS = container.querySelectorAll('button[type="submit"]');
        if(btnS != null)
        {
            btnS.forEach(btn => {
                btn.addEventListener("click", function(e) {
                    e.preventDefault();
                    const form = btn.closest('form');
                    if($(this).hasClass("imprimer") == true){
                        $("#facture_truc").val("OUI");
                    }else{
                        e.preventDefault();
                        $("#facture_truc").val("NON"); 
                    }
                    save(form);
                });
            });
        }

        let seletcom = $(container).find('select[name*="[commandeCli]"]');
        var selected = $(seletcom).find('option:selected').val();
        if(seletcom){
            seletcom.change(function () {
                selected = $(this).find('option:selected').val();
                var option = $(this).find('option:selected');
                var montantini  = option.data('montantini');
                var montantrest   = option.data('montantrest');

                if(montantrest == '0' || montantrest == '') montantrest = montantini;
                if(montantrest != '0') $(container).find('input[name*="facture[montantTotalCmd]"]').val(montantrest);

                $.each($($(container).find('.add_facture_item_link')), function (i, button) {
                    if(selected){
                        $(button).attr('disabled',false);
                    }else{
                        $(button).attr('disabled',true);
                    }
                });
                $(container).find('.collection-form').html(''); 
            });

            $.each($($(container).find('.add_facture_item_link')), function (i, button) {
                if(selected){
                    $(button).attr('disabled',false);
                }else{
                    $(button).attr('disabled',true);
                }
            });
        }
        loadDataTable();
    }

    return {
        init: function() {
            loadnewForm();
            factures();
            handlelistes();
            eventChange();
           
        }
    }
}();
KTUtil.onDOMContentLoaded((function() {
    GESTIONFACTURE.init();
}));