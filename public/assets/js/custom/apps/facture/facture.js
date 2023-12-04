var GESTIONFACTURE = function () {
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
    function datedate() {
        var d = new Date(); var strDate =  (d.getFullYear())+ '-' + ('0'+(d.getMonth()+1)).slice(-2) + '-' +('0'+d.getDate()).slice(-2) ;
        $("input[type='date']").each(function(){
            if($(this).val() == ""){
                $(this).val(strDate);
            }
        })
    }

    

    const addFormDeleteLink = (form) => {
        if (isElement(form)) {
            console.log("OK");
            calculMontantParQte('.qte');
            calculMontantParValeur('.valeur');
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
                calculMontantGlobal();
                
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
                validation.addField($(div).find('input[name*="[designation]"]').attr('name'),{
                    validators: {
                        notEmpty: {
                            message: "Renseignez l'objet de cette ligne "
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
    
    }
    function calculMontantGlobal(){
        let montant = 0;
        $.each($(".mntTo"), function (i, elt) {
            if($(elt).val() != ''){
                montant = montant + parseFloat($(elt).val());
            }
        });
        $("#facture_montantFac").val(montant);
    }

    function calculMontantParQte(element){
       $(element).keyup(function(){
        let qte = $(this).val();
            let tr = $(this).parent().parent().parent();
            let valeur = $(tr).find('input[name*="[valeur]"]');
            let valeurValue = $(valeur).val();
            let mont = $(tr).find('input[name*="[mntTotal]"]');
            let montant =0;
            if($(valeur).val() == ''){
                montant = parseFloat(qte) * 0;
            }else{
                montant = parseFloat(qte) * parseFloat(valeurValue);
            }
            $(mont).val(montant);
            
       calculMontantGlobal();
       });
    }

    function calculMontantParValeur(element){
        $(element).keyup(function(){
            let valeur = $(this).val();
             let tr = $(this).parent().parent().parent();
             let qte = $(tr).find('input[name*="[qte]"]');
             let qteValue = $(qte).val();
             let mont = $(tr).find('input[name*="[mntTotal]"]');
             let montant =0;
             if($(qte).val() == ''){
                 montant = parseFloat(valeur) * 0;
             }else{
                 montant = parseFloat(valeur) * parseFloat(qteValue);
             }
             $(mont).val(montant);
             calculMontantGlobal();
        });
     }

    function facture() {
       
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
                                $("#facture_truc").val("OUI");
                            }else{
                                $("#facture_truc").val("NON"); 
                            }
                            if ($(container).find('input[name*="[dateFac]"]').val()=='') 
                            {
                                toastr.error("Veuillez renseigner la date");

                            }
                            if ($(container).find('input[name*="[montantFac]"]').val()=='') 
                            {
                                toastr.error("Veuillez renseigner la montant total de la facture");

                            }
                            let designations = $(".designation");
                            let er = 0;
                            $.each(designations, function (i, elt){
                                console.log()
                                if($(elt).val() == ''){
                                    er++;
                                }
                            });
                            
                            
                            save(form,validation,btn,erreur);
                                // $(container).find('form').submit();
                            
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
               

            });
            document.querySelector('#ajouter').addEventListener('click', function (e) {
               

             });

        }
    }
    

    //VERIFICATION DE CONNEXION INTERNET
    function checknet(form){

        let input = form.find('input[name*="facture[checknet]"]');
        var online = navigator.onLine;
        if (online) {
            input.val(1);
        } else {
            input.val(0);
            toastr.error("Connexion internet indisponible");
        }
    }


    var desactiverSelect=()=>{
        var mesoption=[];
        for(var key in selectedoptions) {
            mesoption.push(selectedoptions[key]);
        }
    }

    var eventchange =()=>{
        desactiverSelect()
        $.each($($(container).find('select')), function (i, elt) {
            let select = $(elt);
            name = select.attr('name');
        });

         $.each($($(container).find("input:not([readonly=readonly]")),function (k, elt) {
            var name = $(this).attr("name");
            if (typeof name != "undefined") {
                var input = $(elt);
                input.keyup(function () {
                    
                })
            }
        });
    }

    //calcule des totaux
    var calculeDestotaux=()=>{
        
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
                            facture();
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
            facture();
            datedate();
            selectedoptions = [];
            eventchange();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    GESTIONFACTURE.init();
});