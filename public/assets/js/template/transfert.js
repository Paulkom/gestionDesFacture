
function transfertChildren(collectionHolder = null) {
    let parent = container;
    if(collectionHolder != null)
        parent = collectionHolder;
    const mag1 = container.querySelector('select[name*="magasinTrans1"]');
    const mag2 = container.querySelector('select[name*="magasinTrans2"]');
    const totalField = container.querySelector('input.total');
   
    if(parent != null)
    {
        const allChildFormSelects = parent.querySelectorAll('select[name*="produitCondTrans"]');
        allChildFormSelects.forEach(a => {
            $(a).select2({
                // allowClear: true,
                minimumInputLength: 1,
                placeholder: "Sélectionnez un produit...",
                language: {
                    inputTooShort: function(args) {
                        return "Entrez au moins un (01) caractère";
                    },
                    errorLoading: function() {
                        return "Erreur de chargement";
                    },
                    loadingMore: function() {
                        return "Charger plus de données";
                    },
                    noResults: function() {
                        return "Aucun résultat trouvé";
                    },
                    searching: function() {
                        return "Patientez...";
                    }
                },
                ajax: {
                    url: loadProduitCondTransSelectUrl,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term,
                            type: 'public',
                            id: mag1 != null ? mag1.value : 0,
                        };
                    },
                    processResults: function (response) {

                        // empêcher la sélection du même produit conditionnement
                        var selects = $('select[name*="produitCond"]'), 
                        svals = selects.find("option:disabled").prop("disabled",false).end().find("option:selected").map(function(){
                            if(this.value){ return $(this).val(); }
                        }).get();

                        response.forEach(e => {
                            var index = Object.values(svals).indexOf(String(e.id));
                            if (index !== -1) {
                                e['disabled'] = true;
                            }
                        });
                        // fin 

                        return {
                            results: response
                        };
                    },
                    cache: true
                },
                templateSelection: function (data, container) {

                    const parentDiv = a.closest('div.form-child');
                    form_children_controls[parentDiv.getAttribute('id')][a.getAttribute('name')] = data.id !== "";
                    const champPrix = parentDiv.querySelector('input[name*="prixUnitaire"]');
                    const champQte = parentDiv.querySelector('input[name*="qteCondTrans"]');
                    champPrix.focus();
                    champQte.focus();
                    if(typeof data.prixDeVenteTTC !== "undefined")
                    {
                        // Add custom attributes to the <option> tag for the selected option
                        $(data.element).attr('data-prix-vente', data.prixDeVenteTTC);
                        $(data.element).attr('data-stock-mag',  data.qteStockMag);
                        $(data.element).attr('data-stock-global',  data.qteStockCond);
                        $(data.element).attr('optionValue',  'prodCondTrans-'+data.id);
                        

                        //Initialiser le champ prix de vente
                        champPrix.value = typeof data.prixDeVenteTTC !== "undefined" ? data.prixDeVenteTTC : 0;

                        //Initialiser le champ Qté
                        champQte.placeholder = typeof data.qteStockMag !== "undefined" ? data.qteStockMag : 0;
                        
                    }
                    else
                    {
                        if (
                            typeof data !== "undefined" &&
                            typeof data.element !== "undefined" &&
                            typeof data.element.dataset !== "undefined" &&
                            typeof data.element.dataset.stockMag !== "undefined"
                        ) {
                            //Initialiser le champ prix de vente
                            champPrix.value = typeof data.element.dataset.prixVente !== "undefined" ? data.element.dataset.prixVente : 0;

                            //Initialiser le champ Qté
                            champQte.placeholder = typeof data.element.dataset.stockMag !== "undefined" ? data.element.dataset.stockMag : 0;
                        }
                    }
                    $(champPrix).trigger('focusout');
                    $(champQte).trigger('focusout');
                    return data.text;
                }
            });
        });

        // $(mag1).select2().on('change', function () {
        //     unicRow();
        // });
        // $(mag2).select2().on('change', function () {
        //     unicRow();
        // });

        $(mag1).select2().on('change', function () {
            $(mag2).val([]).trigger('change');
            allChildFormSelects.forEach(a => {
                const parentDiv = a.closest('div.form-child');
                parentDiv.remove();
                delete form_children[parentDiv.getAttribute('id')];
                delete form_children_controls[parentDiv.getAttribute('id')];
                /*$(a).val([]).trigger('change');*/
            });
            if(totalField != null)
                totalField.value = 0;
            calculMontant('transfert', 'prixUnitaire', 'qteCondTrans');
            $(mag2).select2({
                minimumInputLength: 1,
                placeholder: "Sélectionnez d\'abord le magasin A...",
                language: {
                    inputTooShort: function(args) {
                        return "Entrez au moins un (01) caractère";
                    },
                    errorLoading: function() {
                        return "Erreur de chargement";
                    },
                    loadingMore: function() {
                        return "Charger plus de données";
                    },
                    noResults: function() {
                        return "Aucun résultat trouvé";
                    },
                    searching: function() {
                        return "Patientez...";
                    }
                },
                ajax: {
                    url: loadMagasinTrans2SelectUrl,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term,
                            type: 'public',
                            id: mag1 != null ? mag1.value : 0,
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                },
            });
        });
    }
}

function controlesTransfert(e)
{
    let val = "";
    const elt = e.target;
    const name = elt.getAttribute('name');
    const parent = elt.closest('div.form-child');
    if(elt.value !== "")
        val = elt.value;
    if(parent != null)
    {
        currentForm = parent.closest('form[name*="transfert"]');
        let errorTag = parent.querySelector('.' + parent.getAttribute('id'));
        if(name.includes('qteCondTrans') && name.includes('transfert'))
        {
            const placeholder = elt.placeholder;
            if(isFloat(placeholder) && isFloat(val))
            {
                if(errorTag === null)
                {
                    errorTag = document.createElement('span');
                    errorTag.classList.add(parent.getAttribute('id'));
                    errorTag.classList.add('mx-auto');
                    errorTag.classList.add('badge');
                    errorTag.classList.add('bg-warning');
                    errorTag.classList.add('mt-2');
                    errorTag.classList.add('align-items-center');
                    if ('nextSibling' in parent.firstChild && parent.firstChild.nextSibling !== null)
                        parent.firstChild.nextSibling.appendChild(errorTag);
                    else
                        parent.firstChild.appendChild(errorTag);
                }
                if(parseFloat(val) > parseFloat(placeholder))
                {
                    parent.querySelector('.' + parent.getAttribute('id')).innerText = 'Attention ! le stock est insuffisant.';
                    form_children_controls[parent.getAttribute('id')][name] = false;
                    return false;
                }
                else
                {
                    parent.querySelector('.' + parent.getAttribute('id')).innerText = '';
                    form_children_controls[parent.getAttribute('id')][name] = true;
                }
            }
        }
    }
}

function showTransfert(e) {
    e.preventDefault();
    const contenu = e.target.dataset.content;
    const idMenu = ($(contenu)[0]).dataset.menu;
    const menu = document.querySelector(`#${idMenu}`);
    menu.dataset.content = contenu;
    $(menu).trigger('click');
    menu.classList.remove('d-none');
}