
var selectedoptions = [];
function desactiverSelect(){
    var mesoption=[];
    for(var key in selectedoptions) {
        mesoption.push(selectedoptions[key]);
    }
    $.each($(container).find('select.produit'), function (i, elt) {
        console.log(elt,'elt');
        $.each($(elt).find('option:not(:selected,:disabled)'), function(i, item) {
            if ($.inArray($(this).val() , mesoption) != -1) {
                $(item).prop("disabled", true);
            }else {
                $(item).prop("disabled", false);
            }
        });
    })
}

function detachement(collectionHolder = null) {
    let formName = $(container).find('form').attr('name');
    $(container).find('button[type="submit"]').attr('disabled',false); 
    if (formName == "detachement") {
        desactiverSelect()
        let parent = container;
        if(collectionHolder != null)
            parent = collectionHolder;
        const mag = container.querySelector('select[name*="detachement[magasin]"]');
       let magasin = $(mag).find('option:selected').val();
        if(parent != null)
        {
            const allChildFormSelects = parent.querySelectorAll('select[name*="[produitCondMag]"]');
            const allChildFormSelectsCibles = parent.querySelectorAll('select[name*="[cible]"]');
            const allChildFormquantite = parent.querySelectorAll('input[name*="[quantite]"]');
            allChildFormSelects.forEach(a => {
                if(magasin){
                    let blocproduit=$(a).parent().parent().parent();
                    $(a).change(function () {
                        selectedoptions[$(this).attr('name')] = $(this).val();
                        $(blocproduit).find('input[name*="[quantite]"]').val(1);
                        $(blocproduit).find('input[name*="[quantiteCible]"]').val('');
                        var selected = $(this).find('option:selected');
                        console.log($(selected).val(),'$(selected).val()');
                        console.log(selected.data('idcond'),' idcond');
                        $.ajax({
                            url: app_loader_deta_prod_comd,
                            dataType: "Json",
                            data: {
                                id: isNaN($(selected).data('idprod')) ? 0 : $(selected).data('idprod'),
                                idcond:isNaN($(selected).data('idcond'))? 0: $(selected).data('idcond'),
                            },
                            type: "GET",
                            beforeSend: function () {
                                ajaxBeforeSend();
                            },
                            success: function (res) {
                                console.log(res,'res');
                                $(blocproduit).find('select[name*="[cible]"]').html(res);
                                //console.log('cible',$(blocproduit).find('select[name*="[cible]"]'));
                                desactiverSelect()
                            },
                            complete: function () {
                                ajaxComplete();
                            },
                        });
                    })
                }
            });
    
            allChildFormquantite.forEach(c => {
                $(c).focusout(function () {
                    let blocproduit = $(c).parent().parent().parent();
                    var produitCondMag = $(blocproduit).find('select[name*="[produitCondMag]"] option:selected');
                    var produitCondMagqtecond = produitCondMag.data('qtecond');
                    var produitCondMagstockmag = produitCondMag.data('stockmag');
                    var quantite = $(this).val();
                    var selected =  $(blocproduit).find('select[name*="[cible]"] option:selected');
                    var qtecondcible = $(selected).data('qtecond'); 
                    if (parseFloat($(this).val()) > parseFloat(produitCondMagstockmag)) {
                        quantite = 1;
                        $(this).val(1);
                        if ($(this).val() != 0) {
                            var msg  = 'La quantité en stock est insuffisante '
                            toastr.error(msg);
                        }
                    }
                    var resultat = (parseFloat(produitCondMagqtecond)*parseFloat(quantite))/parseFloat(qtecondcible);
                    if (resultat%1 == 0) {
                        $(blocproduit).find('input[name*="[quantiteCible]"]').val(resultat);
                    }else{
                        if ($(this).val() != 0) {
                            var msg  = 'La quantité est insuffisante pour rattacher le produit'
                            toastr.error(msg);
                        }
                    }
                })
            })
    
            allChildFormSelectsCibles.forEach(b => {
                $(b).change(function () { 
                    let blocproduit = $(b).parent().parent().parent();
                    var produitCondMag = $(blocproduit).find('select[name*="[produitCondMag]"] option:selected');
                    var produitCondMagqtecond = produitCondMag.data('qtecond');
                    var quantite = $(blocproduit).find('input[name*="[quantite]"]').val();
                    var selected = $(this).find('option:selected');
                    var qtecondcible = $(selected).data('qtecond'); 
                    var resultat = (parseFloat(produitCondMagqtecond)*parseFloat(quantite))/parseFloat(qtecondcible);
                    if (resultat%1 ==0) {
                        $(blocproduit).find('input[name*="[quantiteCible]"]').val(resultat);
                    }
                });
            });
    
            if(magasin){
                $.ajax({
                    url: app_loader_mag_prod_comd,
                    dataType: "Json",
                    data: {
                        id: isNaN(magasin) ? 0 : magasin,
                    },
                    type: "GET",
                    beforeSend: function () {
                        ajaxBeforeSend();
                    },
                    success: function (res) {
                        $(parent).find('select[name*="[produitCondMag]"]').html(res);
                        desactiverSelect();
                        console.log('resultat',res);
                    },
                    complete: function () {
                        ajaxComplete();
                    },
                });
            }
            if(mag){
                $(mag).change(function () {
                    var selected = $(this).find('option:selected').val();
                        $.each($($(container).find('.add_item_link')), function (i, button) {
                            if(selected){
                                $(button).attr('disabled',false);
                            }else{
                                $(button).attr('disabled',true);
                            }
                        });
                    $(container).find('.collection-form').html('');
                    $(container).find('button[type="submit"]').attr('disabled',true); 
                    selectedoptions = [];
                });
            }
        }
    }
}



