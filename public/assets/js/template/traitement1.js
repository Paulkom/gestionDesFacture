

function produit(element) {
    //currentForm = container.querySelector('form[name*="produit"]');
    const numMediaFields = element.querySelectorAll('input[name*="numMedia"]');
    numMediaFields.forEach(i => {
        if(i.getAttribute('name').includes('produit'))
        {
            const numMediaParentDiv = i.closest('div');
            numMediaParentDiv.classList.add('d-none');
        }
    });
    const fileFields = element.querySelectorAll('input[name*="file"]');
    fileFields.forEach(j => {
        if(j.getAttribute('name').includes('produit'))
        {
            const fileParentDiv = j.closest('div');
            fileParentDiv.classList.remove('col-sm-7');
            fileParentDiv.classList.add('col-sm-11');
        }
    });

}



function controlesProduit(e)
{
    let form = $(container).find('form');
    if($(form).attr('name') == "produit"){
    
        let val = "0";
        const elt = e.target;
        const name = elt.getAttribute('name');
        const parent = elt.closest('div.form-child');
        if(elt.value !== "")
            val = elt.value;
        if(parent != null)
        {
            let erreur = false;
            let msg = '';
            let errorTag = parent.querySelector('.' + parent.getAttribute('id'));
            if(name.includes('produit'))
            {
                const prixMinField = parent.querySelector('input[name*="prixMin"]');
                const prixVenteField = parent.querySelector('input[name*="prixDeVente"]');
                const prixVenteHTField = parent.querySelector('input[name*="prixDeVenteHT"]');
                const prixVenteTTCField = parent.querySelector('input[name*="prixDeVenteTTC"]');
                const prixMaxField = parent.querySelector('input[name*="prixMax"]');

                var conditionnement = parent.querySelector('select[name*="conditionnement"]');
                const qteCond = parent.querySelector('input[name*="qteCond"]');
                // console.log(conditionnement.options[conditionnement.selectedIndex].dataset.valeur);
                if(conditionnement.options[conditionnement.selectedIndex].dataset.valeur !=''){
                    qteCond.value = conditionnement.options[conditionnement.selectedIndex].dataset.valeur;
                }

                // prixVenteField.focus();
                
                // if(conditionnement.options[conditionnement.selectedIndex].dataset.valeur)
                //     prixVenteField.focus();


                if(!(errorTag !== null))
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

                if(name.includes('prixDeVente'))
                {
                    if(
                        (parseFloat(val) > parseFloat(prixMaxField.value) ||
                        parseFloat(val) < parseFloat(prixMinField.value)) &&
                        parseFloat(prixMinField.value) < parseFloat(prixMaxField.value)
                    )
                    {
                        erreur = true;
                        msg = `Prix de Vente : La valeur saisie doit être comprise entre ${parseFloat(prixMinField.value)} et ${parseFloat(prixMaxField.value)}.` ;
                    }
                    else if(
                        (parseFloat(val) > parseFloat(prixMaxField.value) ||
                        parseFloat(val) < parseFloat(prixMinField.value)) &&
                        parseFloat(prixMinField.value) > parseFloat(prixMaxField.value)
                    )
                    {
                        erreur = true;
                        msg = `Prix Min être strictement inférieure à Prix Max.` ;
                    }
                    else
                    {
                        erreur = false;
                    }

                    const mode = container.querySelector('select[name*="modeDefPrix"]');
                    const groupeTaxe = container.querySelector('select[name*="groupeTaxe"]');
                    const option = groupeTaxe.selectedOptions[0];
                    const tax = option.dataset.taux;

                    if(mode.value !== "" && groupeTaxe.value !== "")
                    {
                        if(isFloat(prixVenteField.value))
                        {
                            if(parseInt(mode.value) === 0)
                            {
                                prixVenteHTField.value = val;
                                prixVenteTTCField.value = parseFloat(prixVenteHTField.value) * (1 + parseFloat(tax));
                            }

                            if(parseInt(mode.value) === 1)
                            {
                                prixVenteTTCField.value = prixVenteField.value;
                                prixVenteHTField.value = (100 * parseFloat(prixVenteTTCField.value)) / (100 + (parseFloat(tax) * 100));
                            }
                            console.log('Mode : ', mode.value);
                            console.log('Prix de vente HT : ', prixVenteHTField.value);
                            console.log('Prix de vente TTC 2: ', prixVenteTTCField.value);
                        }
                    }
                }

                if(name.includes('prixMin'))
                {
                    if(parseFloat(val) >= parseFloat(prixMaxField.value))
                    {
                        erreur = true;
                        msg = `Prix Min : La valeur saisie doit être strictement inférieure à ${parseFloat(prixMaxField.value)}.` ;
                    }
                    else if(
                        parseFloat(prixVenteField.value) > parseFloat(prixMaxField.value) ||
                        parseFloat(prixVenteField.value) < parseFloat(val)
                    )
                    {
                        erreur = true;
                        msg = `Prix de Vente : La valeur saisie doit être comprise entre ${parseFloat(prixMinField.value)} et ${parseFloat(prixMaxField.value)}.` ;
                    }
                    else
                    {
                        erreur = false;
                    }
                }

                if(name.includes('prixMax'))
                {
                    if(parseFloat(val) <= parseFloat(prixMinField.value))
                    {
                        erreur = true;
                        msg = `Prix Max : La valeur saisie doit être strictement supérieure à ${parseFloat(prixMinField.value)}.` ;
                    }
                    else if(
                        parseFloat(prixVenteField.value) > parseFloat(val) ||
                        parseFloat(prixVenteField.value) < parseFloat(prixMinField.value)
                    )
                    {
                        erreur = true;
                        msg = `Prix de Vente : La valeur saisie doit être comprise entre ${parseFloat(prixMinField.value)} et ${parseFloat(prixMaxField.value)}.` ;
                    }
                    else
                    {
                        erreur = false;
                    }
                }

                if(erreur)
                {
                    parent.querySelector('.' + parent.getAttribute('id')).innerText = msg ;
                    form_children_controls[parent.getAttribute('id')][name] = false;
                    
                    $(container).find('button[type="submit"]').prop("disabled",true);
                    return false;
                }
                else
                {
                    parent.querySelector('.' + parent.getAttribute('id')).innerText = '';
                    form_children_controls[parent.getAttribute('id')][name] = true;
                    
                    $(container).find('button[type="submit"]').prop("disabled",false);
                }
            }
        }
    }
}

function showProduit(e) {
    e.preventDefault();
    const contenu = e.target.dataset.content;
    const idMenu = ($(contenu)[0]).dataset.menu;
    const menu = document.querySelector(`#${idMenu}`);
    menu.dataset.content = contenu;
    $(menu).trigger('click');
    menu.classList.remove('d-none');
    
}




