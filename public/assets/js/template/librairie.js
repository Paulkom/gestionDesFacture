var container = document.querySelector('[data-call-form="ajax"]');
var element;
var form_children = [];
var name_forme = ['livraison','commande_client','facture','depot','paiement'];;
var form_children_controls = [];
var currentForm;
var newform = container != null && container.dataset !== "undefined" ?  container.dataset.content : null;
var content;
var idMenu;
var unicite=[];
var fields={};
var validation;
//livraison,commande_client,facture,depot,paiement,
toastr.options = {
    "closeButton": true,
    "debug": true,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toastr-top-center",
    "preventDuplicates": true,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "9000",
    "extendedTimeOut": "1000",
    "showEasing" : "swing",
    "hideEasing" : "linear",
    "showMethod" : "fadeIn",
    "hideMethod" : "fadeOut"
};

// $(document).ready(function(){ 
    function datedate() {
        var d = new Date(); var strDate =  (d.getFullYear())+ '-' + ('0'+(d.getMonth()+1)).slice(-2) + '-' +('0'+d.getDate()).slice(-2) ;
        $("input[type='date']").each(function(){
            if($(this).val() == ""){
                $(this).val(strDate);
            }
        })
    }
//     datedate();
//  })

function steppers(){
    if(element != null)
    {
        var stepper = new KTStepper(element);
        stepper.on("kt.stepper.next", function (stepper) {
            stepper.goNext();
        });
        stepper.on("kt.stepper.previous", function (stepper) {
            stepper.goPrevious();
        });
    }
}
function isInt(n) {
    return n !== "" && !isNaN(n) && Math.round(n) === n;
}
function isFloat(n){
    return n !== "" && !isNaN(n) && Math.round(n) !== n;
}
function isElement (element) {
    return element instanceof Element || element instanceof HTMLDocument;
}

function ajaxBeforeSend(){
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
    // document.body.setAttribute('data-kt-app-page-loading-enabled', true);
    document.body.setAttribute('data-kt-app-page-loading', "on");
}

function ajaxComplete(){
    document.body.classList.remove('page-loading');
    document.body.removeAttribute('data-kt-app-page-loading');
}

function activateMenu(e) {
    const allItems = document.querySelectorAll('.kt-menu-proto');
    if (allItems != null) {
        allItems.forEach(i => {
            i.classList.remove("active");
            if(i.classList.toString().includes('remove'))
            {
                i.classList.add("d-none");
            }
        });
    }
    e.classList.add("active");
    if(container != null)
    {
        content = e.dataset.content;
        container.innerHTML = content;
        const menuContent = container.querySelector('[data-menu]');
        if(menuContent != null)
        {
            idMenu = menuContent.dataset.menu;
        }
        initializeContainer();
    }
}

function thousands_separators(num) {
    var num_parts = num.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    return num_parts.join(".");
}

function initializeContainer (collectionHolder = null){
    Inputmask({
        "mask": "9",
        "repeat": 15,
        "greedy": false,
    }).mask(".telephone");

    Inputmask({
        "mask": "9",
        "repeat": 13,
        "greedy": false,
    }).mask(".ifu");
    
    let parent = container;
    var fieldList= new Object;
    // var validation;
    if(collectionHolder != null)
        parent = collectionHolder;

    if(parent != null)
    {
        tableau = parent.querySelector('.kt_entity_table');
        etattable = parent.querySelector('.kt_etat_table');
        element = parent.querySelector('#kt_stepper_basic');
        //initialiser les collections
        parent.querySelectorAll('.add_item_link').forEach(btn => {
            btn.addEventListener("click", addFormToCollection);
            const holders = container.querySelectorAll('.' + btn.dataset.collectionHolderClass);
            holders.forEach(h => {
                const divs = h.querySelectorAll('div.form-child');
                divs.forEach(div => {
                    if(collectionHolder == null)
                    {
                        form_children[div.getAttribute('id')] = [];
                        form_children_controls[div.getAttribute('id')] = [];
                    }
                    addFormDeleteLink(div);
                });
            });
        });

        //evénement sur les selects
        const selects = parent.querySelectorAll('select');
        if(selects != null && jQuery.inArray($(parent).find('form').attr('name'),name_forme))
        {
            selects.forEach(s => {
                if(s.getAttribute('name'))
                {
                    if(s.closest('div.form-child') != null)
                    {
                        let bool = true;
                        if(s.hasAttribute('required') && s.getAttribute('required') === 'required')
                            bool = s.value !== "";
                        const prefix = s.closest('div.form-child').getAttribute('id');
                        form_children[prefix][s.getAttribute('name')] = s.value;
                        form_children_controls[prefix][s.getAttribute('name')] = bool;
                    }
                    $(s).select2().on('change', selectEvent);
                }
            });
        }
        //evénement sur les inputs
        const inputs = parent.querySelectorAll('input');
        inputs.forEach(i => {

            $('.ch_code').maxlength({ 
                // il sagit ici d'appliquer le badge de limitation de caractere sur les champs identitaires
                // le champs doit avoir donc pour class ch_code et un maxlengt dans ses attribut
                warningClass: "badge badge-warning",
                limitReachedClass: "badge badge-success"
            });

            i.addEventListener('keypress', inputPressEvent);
            if(i.getAttribute('name') && !i.getAttribute('name').includes('livraison') &&
                !i.getAttribute('name').includes('commande_client')  &&
            !i.getAttribute('name').includes('facture')  &&
                !i.getAttribute('name').includes('paiement'))
            {
                if(i.closest('div.form-child') != null)
                {
                    let bool = true;
                    if(i.hasAttribute('required') && i.getAttribute('required') === 'required')
                        bool = i.value !== "";
                    const prefix = i.closest('div.form-child').getAttribute('id');
                    form_children[prefix][i.getAttribute('name')] = i.value;
                    form_children_controls[prefix][i.getAttribute('name')] = bool;
                }
                i.addEventListener('focusout', inputEvent);
            }

        });

        //evenement sur les Chekboxs
        const checkboxs = parent.querySelectorAll('input[type=checkbox]');
        checkboxs.forEach(i => {
            i.addEventListener('change', checkboxEvent);
        });


        let btnS = parent.querySelectorAll('button[type="submit"]');
        if(btnS != null)
        {
            btnS.forEach(btn => {
                const form = btn.closest('form');
                // console.log(form,'form');
                if (form != null && form != undefined) {
                    validation = valitation(form,fieldList,validation)
                    btn.addEventListener("click", function(e) {
                        // console.log(form,'form');

                        // reactivation des champs desactivé avant enregistrement
                        const inputss = parent.querySelectorAll('input');
                        const selectss = parent.querySelectorAll('select');
                        inputss.disabled = false;
                        // .readOnly = true;
                        $(selectss).select2({disabled: false});

                        e.preventDefault();
                        var erreur = false;
                        for (const key in unicite) {
                            if (unicite[key]) {
                                erreur = true;
                                break;
                            }
                        }
                        
                        save(form,validation,btn,erreur);
                        //  $(form).submit();
                    });
                }
            });
        }
        //Initialiser les médias
        $(parent).find(".input-group.media").on("click", function () {
            var $input = $(this).find("input[type='text']");
            $(this).prev().click();
            $(this).prev().on("change", function () {
                $input.val($(this)[0].files[0].name);
                let bool = true;
                if(($input[0]).hasAttribute('required') && ($input[0]).getAttribute('required') === 'required')
                    bool = $input.val() !== "";
                const prefix = ($input[0]).closest('div.form-child').getAttribute('id');
                form_children[prefix][$input.attr('name')] = $input.val();
                form_children_controls[prefix][$input.attr('name')] = bool;
            });
        });

        $(parent.querySelectorAll('[data-request="ajaxify-search"]')).each(function () {
            $(this).ajaxifySearch();
        });

        //Initialiser la liste
        loadDataTable();
        // KTWizard.init();
        let formName = $(container).find('form').attr('name');
        if (formName == "detachement") {
            detachement(collectionHolder);
        }
        
        transfertChildren(collectionHolder);
        sortieChildren(collectionHolder);
        steppers();
        produit(parent);
        verifierunicite(parent,unicite);
        // numeric();
    }
}

function dataTa(table){
    $(table).dataTable({
        // search:         "Rechercher&nbsp;:",
        // searching : true,
        // language: {
        //     search:         "CHERCHER",
        //     lengthMenu:     "AFFICHER _MENU_ ELEMENTS",
        //     info:           "AFFICHAGE DE _START_ A _END_ SUR _TOTAL_ ENREGISTREMENTS",
        //     infoEmpty:      "AFFICHAGE DE 0 A 0 SUR 0 ENREGISTREMENT",
        //     loadingRecords: "CHARGEMENT...",
        //     zeroRecords:    "AUCUN ELEMENT A AFFICHER",
        //     emptyTable:     "AUCUNE DONNEE DISPONIBLE DANS LE TABLEAU",
        //     paginate: {
        //         First:      "<<",
        //         previous:   "<",
        //         next:       ">",
        //         Last:       ">>"
        //     },
        //     aria: {
        //         sortAscending:  ": activer pour trier la colonne par ordre croissant",
        //         sortDescending: ": activer pour trier la colonne par ordre décroissant"
        //     }
            
        // },

        language: {
            search:         "Rechercher&nbsp;:",
            lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments",
            info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            infoPostFix:    "",
            loadingRecords: "Chargement en cours...",
            zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
            emptyTable:     "Aucune donnée disponible dans le tableau",
            paginate: {
                First:      "<<",
                previous:   "<",
                next:       ">",
                Last:       ">>"
            },
            aria: {
                sortAscending:  ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            }
            
        },
        //dom: '<lf<t>ip>',
        dom: 'lSrftip',
    });
}

function valitation(form,fieldList){
    let allform = $(form).find('input[type="text"],textarea,select').filter('[required]:visible');
    // console.log(allform);
    $.each($(allform), function (i, elt) {
        let itsform = $(elt);
        nameitsform = $(itsform).attr("name");
        message = $(itsform).prev().text()+' est obligatoire';
        fieldList[`${$(itsform).attr("name")}`] ={ validators:{notEmpty:{message:message}}};
    })
    // console.log(allform,fieldList);
   var validation = FormValidation.formValidation(form,
        {
            fields:fieldList,
            plugins: {
                declarative: new FormValidation.plugins.Declarative(),
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: '.fv-row',
                    eleInvalidClass: 'is-invalid',
                    eleValidClass: 'is-valid'
                }),
                icon: new FormValidation.plugins.Icon({
                    valid: 'fa fa-check',
                    invalid: 'fa fa-times',
                    validating: 'fa fa-refresh',
                }),
            }
        }
    );
    // Revalidate Select2 input. For more info, plase visit the official plugin site: https://select2.org/
    /*$.each($(form).find('select').filter('[required]:visible'), function (i, elt) {
        let itsform = $(elt);
        nameitsform = $(itsform).attr("name");
        $(itsform).select2().on('change', function () {
            validator.revalidateField(nameitsform);
        });
    });*/

    $(form).find('select').filter('[required]:visible').select2().on('change', function () {
        validation.revalidateField($(this).attr("name"));
    });

    return validation;
}

function verifierunicite(parent,unicite=[]) {
    $.each($($(parent).find(".unicite")),function (k, elt) {
        var input = $(elt);
        // console.log(input);

        // ajouté pour empêcher la soumission par la touche entrée sur un champ d'unicité
        $(document).on("keypress", "input", function(e){
            if(e.which == 13){
                e.preventDefault();
                input.trigger("focusout");
                return false;
            }
        });

        input.focusout(function () {
            // console.log(input);
            inputID= $(parent).find('input.keys');
            inputIdValue = $(inputID).data('id');
           // let $input = $(this);
            let $inputcol =  input.data('column').split('|');
            // console.log($inputcol);
            var parentbloc = $(input).parent();
            const btn = parent.querySelector('button[type="submit"]');
            // console.log($inputcol);
            if($(this).val() !='') {
                $.ajax({
                    type: "GET",
                    url: verifier_unicite_url,
                    data: {
                        valeur: input.val(),
                        column: $inputcol[1],
                        entity: $inputcol[0],
                        id: inputIdValue
                    },
                    dataType: "JSON",
                    beforeSend: ajaxBeforeSend,
                    success: function (result) {
                        // console.log(result);
                        if (result !== undefined && result != null && result.length > 0) {
                           // toastr.error("Cette valeur("+ input.val() +") existe déja");
                           // parentbloc.find('div.fv-plugins-message-container.invalid-feedback').text(" ");
                            parentbloc.find('div.fv-plugins-message-container.invalid-feedback').text("Cette valeur("+ input.val() +") existe déja");                        
                            $(btn).attr('disabled',true);  
                            unicite[input.attr("name")] = true;
                        }
                        else
                        {
                            parentbloc.find('div.fv-plugins-message-container.invalid-feedback').text(" ");
                            unicite[input.attr("name")]= false; 
                            var erreur = false;
                            for (const key in unicite) {
                                if (unicite[key]) {
                                    erreur = true;
                                    break;
                                }
                            }
                            if (erreur == false) {
                                $(btn).attr('disabled',false); 
                            }
                        }
                        // console.log(unicite,'unicite')
                    },
                    error: function (e) {
                    },
                    complete: ajaxComplete
                });
            }
        });
    //}
    });
}


function verifierNomPrenom(parent) {
    $.each($($(parent).find(".nomprenom")),function (k, elt) {
        var input = $(elt);
        input.focusout(function () {
            // console.log(input);
            inputID= $(parent).find('input.keys');
            inputIdValue = $(inputID).data('id');
            let $inputcol =  input.data('column');
            // console.log($inputcol);
            var parentbloc = $(input).parent();
            const btn = parent.querySelector('button[type="submit"]');
            // console.log($inputcol);
            if($(this).val() !='') {    
                $.ajax({
                    type: "GET",
                    url: verifier_unicite_nom_prenom_url,
                    data: {
                        valeur: input.val(),
                        column: $inputcol[1],
                        entity: $inputcol[0],
                        id: inputIdValue
                    },
                    dataType: "JSON",
                    beforeSend: ajaxBeforeSend,
                    success: function (result) {
                        // console.log(result);
                        if (result !== undefined && result != null && result.length > 0) {
                           // toastr.error("Cette valeur("+ input.val() +") existe déja");
                           // parentbloc.find('div.fv-plugins-message-container.invalid-feedback').text(" ");
                            parentbloc.find('div.fv-plugins-message-container.invalid-feedback').text("Cette valeur("+ input.val() +") existe déja");                        
                            $(btn).attr('disabled',true);   
                        }
                        else
                        {
                            parentbloc.find('div.fv-plugins-message-container.invalid-feedback').text(" ");
                            $(btn).attr('disabled',false); 
                        }
                    },
                    error: function (e) {
                    },
                    complete: ajaxComplete
                });
            }
        });
    //}
    });
}

// function numeric(){
//     console.log('je suis numeric');
//     $(document).find('.numeric').on("input", function(evt) {
//         var self = $(this);
//         self.val(self.val().replace(/[^0-9\.]/g, ''));
//         if((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
//         { evt.preventDefault(); }
//     });
// }

function addFormDeleteLink (form, method = 'POST'){
    if (isElement(form)) {
        
        const removeFormButton = form.querySelector('.collection-action');
        if(removeFormButton != null)
        {
            removeFormButton.addEventListener('click', (e) => {
                if(removeFormButton.dataset.url !== "undefined"
                    && removeFormButton.dataset.token !== "undefined")
                {
                    
                    if($(form).closest('form').hasClass("edit")){
                    // s'il s'agit d'une modification, exécuter l'Ajax pour supprimer l'élément de la base
                        // console.log('deschanel sup', removeFormButton.dataset.url);
                        $.ajax({
                            type: method,
                            url: removeFormButton.dataset.url,
                            data: {token: removeFormButton.dataset.token},
                            dataType: "json",
                            beforeSend: ajaxBeforeSend,
                            success: function(response){
                                console.log('Réponse du serveur D: ', response);
                            },
                            error: function (error) {
                                // console.log('Erreur : ', error);
                            },
                            complete: ajaxComplete
                        });
                    }

                    $.each($(form).find('input, select'), function (i, elt) {
                        if($(elt).prop('required')){
                            $(elt).prop('required', '');
                            validation.removeField($(elt).attr('name'));
                            $(elt).removeClass('is-invalid');
                            $(elt).parent().find('.form-label').removeClass('required');
                        }
                    })
                }

                let formParent = $(container).find('form');
                // bloquez le bouton de soumission si toutes les collections sont supprimées sauf sur produit
                if( ($(form).closest('.collection-form').find('.collection-action').length -1) == 0 && (formParent.attr('name') != 'produit') ){
                    $('button[type="submit"]').prop('disabled',true);
                    console.log('11');
                }


                delete form_children[form.getAttribute('id')];
                delete form_children_controls[form.getAttribute('id')];
                form.remove();
                calculMontant('transfert', 'prixUnitaire', 'qteCondTrans');
                calculMontant('sortie', 'prixUnitaire', 'qteCondSortie');
                calculMontant('approvisionnement', 'prixAchat', 'qteCondAppro');

                let formName = $(container).find('form').attr('name');
                let arrayForm = ['produit','societe','approvisionnement','utilisateur','commande_frs',
                'model_signataire','transfert','demande_de_prix'];
                if($.inArray(formName, arrayForm) !== -1){ unicRow(); } //control de select double
            });
        }
    }
}


function addFormToCollection(e){
    if(container != null)
    {
        /**
         * Récupérer l'élément conteneur de formulaires imbriqués
         */
        const collectionHolder = document.querySelector('.' + e.target.dataset.collectionHolderClass);
        /**
         * Récupérer la forme brute du formulaire à imbriqué
         */
        const prototype = collectionHolder.dataset.prototype;
        const div = document.createElement('div');
        const protokey = collectionHolder.dataset.collectionKey;
        var regex = new RegExp(protokey, "g");
        div.setAttribute('data-index', collectionHolder.dataset.index);
        const prefix = collectionHolder.dataset.collectionPrefix
            .replace(regex, collectionHolder.dataset.index);
        div.setAttribute('id', prefix);
        div.classList.add("ligne");
        div.classList.add("form-child");
        if(collectionHolder.dataset.collectionSurname){ div.classList.add(collectionHolder.dataset.collectionSurname); }
            
        div.innerHTML = prototype.replace(regex, collectionHolder.dataset.index);
       
        /**
         * Ajouter la forme html du formulaire imbriqué au conteneur
         */
        collectionHolder.appendChild(div);
        $(div).find('input, select').each( (e, elt) => {
            if($(elt).prop('required')){
                validation.addField($(elt).attr('name'),{
                    validators: {
                        notEmpty: {
                            message: $(elt).prev().text()+' est obligatoire'
                        }
                    }
                })
            }
        })

        Inputmask("decimal", {
            "rightAlignNumerics": false
        }).mask(".decimal");

        addFormDeleteLink(div);
        // $('button[type="submit"]').prop('disabled',false);

        let formName = $(container).find('form').attr('name');
        let arrayForm = ['produit','societe','approvisionnement','utilisateur','personnel','commande_frs',
        'model_signataire','transfert','demande_de_prix'];
        if($.inArray(formName, arrayForm) !== -1){ unicRow(); } //control de select double
        
        form_children[prefix] = [];
        form_children_controls[prefix] = [];
        initializeContainer(collectionHolder.querySelector('#' + prefix));
        //transfertChildren(collectionHolder.querySelector('#' + prefix));
        /** Incrémentation de l'index après insertion du formulaire imbriqué **/
        collectionHolder.dataset.index++;

        return collectionHolder;
    }
}
function calculMontant (formName, priceFieldKey, qteFieldKey){
    
    if(container != null)
    {
        let somme = 0;
        for (const key in form_children) {
            const ligne = form_children[key];
            if(ligne != null && typeof ligne !== "undefined")
            {
                const a = parseFloat(ligne[Object.keys(ligne).find(key => key.includes(formName) && key.includes(priceFieldKey))]);
                const b = parseFloat(ligne[Object.keys(ligne).find(key => key.includes(formName) && key.includes(qteFieldKey))]);
                if(a > 0 && b > 0){
                    
                    somme += (a * b);
                    const parent = container.querySelector('#' + key);
                    if(parent != null)
                    {
                        const subTotals = parent.querySelectorAll('.subTotal');
                        // console.log(subTotals);
                        subTotals.forEach(t => {
                            if(t.hasAttribute('name'))
                            {
                                if((a * b) > 0)
                                    t.value = (a * b);
                                
                            }
                        });
                    }
                }
            }
        }
        const totals = document.querySelectorAll('.total');
        totals.forEach(t => {
            if(t.hasAttribute('name'))
            {
                if(somme > 0)
                    t.value = somme;
            }
        });
    }
}

function inputPressEvent(e)
{
    const elt = e.target;
    if(elt.classList.toString().includes('float'))
    {
        if ((e.which !== 46 || $(this).val().indexOf('.') !== -1) &&
            ((e.which < 48 || e.which > 57) &&
                (e.which !== 0 && e.which !== 8))) {
            e.preventDefault();
        }

        var text = $(this).val();
        if ((text.indexOf('.') !== -1) &&
            (text.substring(text.indexOf('.')).length > 2) &&
            (e.which !== 0 && e.which !== 8) &&
            ($(this)[0].selectionStart >= text.length - 2)) {
            e.preventDefault();
        }
    }
}
function inputEvent(e){

    if(e.target.hasAttribute('name'))
    {
        const name = e.target.getAttribute('name');
        const parent = e.target.closest('div.form-child');
        if(parent != null)
        {
            let bool = true;
            if(e.target.hasAttribute('required') && e.target.getAttribute('required') === 'required')
                bool = e.target.value !== "";
            const prefix = parent.getAttribute('id');
            form_children[prefix][name] = e.target.value;
            form_children_controls[prefix][name] = bool;
        }
        controlesTransfert(e);
        controlesSortie(e);
        controlesProduit(e);
    }
}

function selectEvent(e){
    if(e.target.hasAttribute('name'))
    {
        const name = e.target.getAttribute('name');
        const parent = e.target.closest('div.form-child');
        if(parent != null)
        {
            let bool = true;
            if(e.target.hasAttribute('required') && e.target.getAttribute('required') === 'required')
                bool = e.target.value !== "";
            const prefix = parent.getAttribute('id');
            form_children[prefix][name] = e.target.value;
            form_children_controls[prefix][name] = bool;
        }
        controlesTransfert(e)
        controlesSortie(e)
        controlesProduit(e)
    }
}


function checkboxEvent(e){
    if(e.target.getAttribute('name') == 'societe[estModeMecef]')
    {
        if(e.target.checked){
            console.log('yes');
        }else{
            console.log('non');
        }  
    }

    if(e.target.getAttribute('name') == 'produit[estModeCarreau]')
    {
        e.target.parentNode.parentElement.classList.toggle("bg-light-primary");
        let element = document.getElementById('infoCarreau');
        element.classList.toggle("d-none");

        let form = $(container).find('form');

        if(e.target.checked){
            $.each($(form.find('.carreau')), function (i, elt) {
                $(elt).prop('required', 'required');
                validation.addField($(elt).attr('name'),{
                    validators: {
                        notEmpty: {
                            message: $(elt).prev().text()+' est obligatoire'
                        }
                    }
                })
                $(elt).parent().find('.form-label').addClass('required');
            })
        }else{
            $.each($(form.find('.carreau')), function (i, elt) {
                $(elt).prop('required', '');
                $(elt).val("");
                validation.removeField($(elt).attr('name'));
                $(elt).removeClass('is-invalid');
            })
        }

        
        
    }

    if(e.target.getAttribute('name') == 'produit[estService]')
    {
        let form = $(container).find('form');
        let element = document.getElementById('ignoreInEstService');
        element.classList.toggle("d-none");

        if(e.target.checked){
            fields = $(form).find('input.ignoreInEstService, select.ignoreInEstService');
            fields.each((o, el) => {
                if($(el).prop('required')){
                    validation.removeField($(el).attr('name'));
                    $(el).removeClass('is-invalid');
                    $(el).parent().find('.form-label').removeClass('required');
                }
            })
            
        }else{
            fields = $(form).find('input.ignoreInEstServiceActif, select.ignoreInEstServiceActif');
            fields.each((i, elt) => {
                $(elt).prop('required', 'required');
                validation.addField($(elt).attr('name'),{
                    validators: {
                        notEmpty: {
                            message: $(elt).prev().text()+' est obligatoire'
                        }
                    }
                })
                $(elt).parent().find('.form-label').addClass('required');
            })
        }
    }
}

function imprimable(){
    let date1 = jQuery("#date1").val();
    let date2 = jQuery("#date2").val();
    let client = jQuery("#client").val();
    let recherche = jQuery("#recherche").val();
    let magasin = jQuery("#magasin").val();
    let statut = jQuery("#statut").val();
    let statuLiv = jQuery("#statutLiv").val();
    let typeCommande = jQuery("#type_commande").val();
    let produit = jQuery("#produit").val();
    var url = jQuery('#imp').attr("lien");
    var type = jQuery('#imp').attr("typep");
    var columns = $(jQuery(".table")[0]).attr("data-table-columns");
    var donnees = $("#kt_accordion_1").attr("data-donnee");
    url = url + "?date1="+date1+"&date2="+date2+"&recherche="+recherche+"&client="+client+"&type="+type+"&coulums="+columns+"&magasin="+magasin+"&produit="+produit+"&statut="+statut+"&statutLiv="+statuLiv+"&typeCommande="+typeCommande+"&donnees="+donnees;
    window.open(url, '_blank');
}

function recherche(){
    let date1 = jQuery("#date1").val();
    let date2 = jQuery("#date2").val();
    let magasin = jQuery("#magasin").val();
    let recherche = jQuery("#recherche").val();
    if(date1 =="" && date2 == "" && recherche == ""){
        Swal.fire(
            'Veuillez renseigner au moins les dates',
            'Clickez sur Ok',
            'error'
          )
          jQuery("#recherche").focus();
        return false;
    }else{
        if(date1 =="" || date2 == ""){
            Swal.fire(
                'Veuillez renseigner au moins les dates',
                'Clickez sur Ok',
                'error'
              )
              return false;
        }
    }
    $.ajax({
        method: "POST",
        url: urlImp,
        data: {date1: date1, date2: date2,magasin:magasin, recherche:recherche}
      })
    .done(function(msg) {
        let html = '';
        console.log(msg);
        $.each(msg, function( index, value ) {
            html+= 
                '<div class="accordion-item">'+
                    '<h2 class="accordion-header" id="kt_accordion_1_header_'+index+'">'+
                        '<button class="accordion-button fs-4 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_'+index+'">'+
                        value["magasin"] +
                        '</button>'+
                    '</h2>'+
                    '<div id="kt_accordion_1_body_'+index+'" class="accordion-collapse collapse show" aria-labelledby="kt_accordion_1_header_'+index+'" data-bs-parent="#kt_accordion_'+index+'">'+
                        '<div class="accordion-body">'+
                            '<div class="table-responsive">'+
                            '<table class="table">'+
                                '<thead>'+
                                   ' <tr class="fw-bold fs-6 text-gray-800">'+
                                   '<th class="min-w-125px">Produit</th>'+
                                   '<th class="min-w-125px">Stock Initial</th>'+
                                   '<th class="min-w-125px">Qté Appro</th>'+
                                   '<th class="min-w-125px">Qté Livré</th>'+
                                   '<th class="min-w-125px">Qté Vendu</th>'+
                                   '<th class="min-w-125px">Qté Sortie</th>'+
                                   '<th class="min-w-125px">Qté Retournée</th>'+
                                   '<th class="min-w-125px">Stock Final</th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody>';

                            $.each(value["produitsMag"], function( ind, val ) {
                                html+= '<tr>'+
                                        '<td>'+ val["produit"] +'</td>'+
                                        '<td>'+ thousands_separators(val["stockInitial"]) +'</td>'+
                                        '<td>'+ thousands_separators(val["qteAppro"]) +'</td>'+
                                        '<td>'+ thousands_separators(val["qteLivre"]) +'</td>'+
                                        '<td>'+ thousands_separators(val["qteVendu"]) +'</td>'+
                                        '<td>'+ thousands_separators(val["qteSortie"]) +'</td>'+
                                        '<td>'+ thousands_separators(val["qteRetou"]) +'</td>'+
                                        '<td>'+ thousands_separators(val["stockFinal"]) +'</td>'
                                    +
                                '</tr>';

                            });

                            // Content
                    html+= '</tbody></table></div></div></div></div>'
                ;
    
          });
          console.log(html);
        $("#kt_accordion_1").html(html);
        $("#kt_accordion_1").attr("data-donnee",JSON.stringify(msg));
        dataTa('.table');
    });
}

function loadDataTable() {
    if(tableau !== null){
        initDatatable();
        handleSearchDatatable();
        initToggleToolbar();
        handleEditRows();
        handleDeleteRows();
    }

    if (etattable !== null) {
        console.log(etattable,'etattable');
        etatDatatable();
        etatSearchDatatable();
        datedate();
        Searchbtn();
        SubmitBtn();
    }
}

function getCurrentURL () {
    let url = window.location.href ;
    let uri = "";

    if(url.includes('commande')){
        uri = url+"/impression/docu";
    }

    if(url.includes('inventaire')){
        uri = url+"fiche/inventaire/";
    }

    if(url.includes('livraison')){
        uri = url+"imprime/livraison/doc";
    }

    if(url.includes('facture') && !(url.includes('factureproforma'))){
        uri = url+"impression/docu/facture/facture";
    }

    if(url.includes('paiement') ){
        uri = url+"doc/paiement/imprime";
    }

    if(url.includes('factureproforma')){
        uri = url+"impression/docu/facture/pro/format";
    }

    if(url.includes('depot') && url.includes('transaction')){
        uri = (url.split("depot"))[0] +"transaction/imprime";
        console.log(uri);
    }

    if(url.includes('transaction') && !url.includes('depot')){
        uri = url+"transaction/imprime";
    }
    return uri;
}

function soumission(url,data = null, val = null, type = null, formatPapier = null){
    console.log(url);
    // console.log("je suis là",'url',url, 'data', data,'val', val,'type', type );
    var mapForm = document.createElement("form");
    mapForm.target = "_blank";
    mapForm.method = "POST"; // or "post" if appropriate
    mapForm.action = url;
    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "id";
    // mapInput.type = "liste";
    mapInput.value = data;
    mapForm.appendChild(mapInput);

    var inputType = document.createElement("input");
    mapInput.type = "text";
    inputType.name = "type";
    inputType.value = type;
    mapForm.appendChild(inputType);

    var inputCmd = document.createElement("input");
    mapInput.type = "text";
    inputCmd.name = "commande";
    inputCmd.value = val;
    mapForm.appendChild(inputCmd);

    // creation de l'input format papier pour gerer l'impression
    var inputFormatPapier = document.createElement("input");
    inputFormatPapier.type = "text";
    inputFormatPapier.name = "formatPapier";
    inputFormatPapier.value = formatPapier;
    mapForm.appendChild(inputFormatPapier);

    document.body.appendChild(mapForm);
    mapForm.submit();

    mapForm.remove();
    console.log("Form element supprimé");
}

function imprime(id=null, val = null, type = 'liste'){
    console.log('test');
    soumission(getCurrentURL(),id, val, type);
}

function save(form,validator,btn, erreur) {
    // console.log(validator);    
    // Validate form before submit
    const bloc = document.querySelector('[data-call-form="ajax"]');
    
    if (validator) {
        validator.validate().then(function (status) {
        // console.log('validated!', status);
        if (status == 'Valid' && erreur == false) {
            // Show loading indication
            btn.setAttribute('data-kt-indicator', 'on');
            // Disable button to avoid multiple click
            $(container).find('button[type="submit"]').attr('disabled',true);
            let fd = new FormData(form);
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                enctype: 'multipart/form-data',
                data: fd,
                processData: false,
                contentType: false,
               // beforeSend: ajaxBeforeSend,
                success: function (msg) {
                    $cont = msg.split("§");
                if($cont[4]== 'msg_error'){
                    toastr.error($cont[0]);
                }else{
                    toastr.success($cont[0]);
                    $(form).trigger("reset");
                    url = getCurrentURL();
                    if(idMenu != null) {
                        $('#' + idMenu).trigger('click');
                    }
                    if(newform !== '' || newform !== 'undefiny'){
                        if($(form).attr('name') == 'facture'){
                            if($cont[2] == "OUI"){
                                let data = ($cont[1]).toString();
                                soumission(url,data);
                            }
                            GESTIONFACTURE.init();
                        }
                        if($(form).attr('name') === 'paiement'){
                            if($cont[2] == "OUI"){
                                let data = ($cont[1]).toString();
                                soumission(url,data);
                            }
                            newform = $cont[3];
                            GESTIONPAIEMENT.init();
                        }
                    }
                }
                    
                    btn.removeAttribute('data-kt-indicator');
                    $(container).find('button[type="submit"]').attr('disabled',false);
                    
                },
                error: function(e){
                    toastr.error("Une erreur s'est produite lors de l'enregistrement des informations. Veuillez vérifier si tous les champs obligatoires sont renseignés. Merci!!");
                    btn.removeAttribute('data-kt-indicator');
                    //btn.disabled = false;
                    $(container).find('button[type="submit"]').attr('disabled',false);
                },
                //complete: ajaxComplete,
                cache: false
            });
        }})
    }
}

function onShow(elt, data) {
    // console.log('Affichage des Infos :', data);
}

function onEdit(elt, data) {
    // console.log('Modification : ', container);
    $(data.edit).addClass('edit');
    $(container).html(data.edit);
    content = data.edit;
    const form = container.querySelector('form');
    $(form).addClass('edit');
    $('button[type="submit"]').prop('disabled',false);
    
    Inputmask("decimal", {
        "rightAlignNumerics": false
    }).mask(".decimal");

    initializeContainer();
    if($(form).attr('name').includes('facture')){
        GESTIONFACTURE.init();
    }
    if($(form).attr('name')==='paiement'){
        GESTIONPAIEMENT.init();
    }
}

function afterDelete(elt, data) {
    console.log('Suppression réussie : ', data);
}

function showDetailOption(e) {
    e.preventDefault();
    const contenu = e.target.dataset.content;
    const idMenu = ($(contenu)[0]).dataset.menu;
    const menu = document.querySelector(`#${idMenu}`);
    menu.dataset.content = contenu;
    $(menu).trigger('click');
    menu.classList.remove('d-none');
}


// 
function unicRow() {
    
    function setDropOptions() {
        let a = [];
        var selects=$('select'), 
        svals = selects.find("option:disabled").prop("disabled",false).end().find("option:selected").map(function(){
            if(this.value){
                return $(this).attr('optionvalue');
            }
        }).get();

        for(var i=0;i<svals.length;i++){
            // console.log(selects.find("option[optionvalue='"+svals[i]+"']:selected").length);
            selects.find("option[optionvalue='"+svals[i]+"']").prop("disabled",true);
            selects.find("option[optionvalue='"+svals[i]+"']:selected").prop("disabled",false);
            selects.find("option:first").prop("disabled",false);
        }
    }

    $('select').on('change', function(e) {
        setDropOptions();
    })
    setDropOptions();
    
}

function calculPieceCarton(quantite, pieceParCarton, metreCarreParCarton){

    let carton = parseInt(quantite / metreCarreParCarton);
    let rest = ((quantite / metreCarreParCarton) - carton) * pieceParCarton;
    let piece = parseInt(rest);
    let res = Math.round((rest - piece) * 100) / 100;
    if(res >= parseFloat(0.9))
        piece += 1;

    let re = [];
    re['piece'] = piece;
    re['carton'] = carton;
    return re;

}

