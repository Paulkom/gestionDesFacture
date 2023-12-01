// "use strict";
// var KTAll = function() {
//     const initializeForm = () => {
//
//     }
//     function swishStatut(){
//         let form = $(container).find('form.withRadio');
//         let moral = form.find('.moral').parent();
//         let physique = form.find('.physique').parent();
//
//         if(form.find('input[type=radio]:checked').val() == 'Physique')
//             moral.hide();
//         if(form.find('input[type=radio]:checked').val() == 'Moral')
//             physique.hide();
//
//         form.find('input[type=radio]').change(function() {
//             if (this.value == 'Physique') {
//                 moral.hide();
//                 physique.show();
//             }
//             else if (this.value == 'Moral') {
//                 moral.show();
//                 physique.hide();
//             }
//         });
//     }
//     /**
//      * Vérifie s'il s'agit bien d'un élément HTML
//      * @param element
//      * @returns {boolean}
//      */
//     const isElement = (element) => {
//         return element instanceof Element || element instanceof HTMLDocument;
//     }
//     const ajaxDeleteResult = (form, data) => {
//         form.remove();
//         if(data != null)
//         {
//
//         }
//     }
//     const ajaxBeforeSend = () => {
//         let loadingEl = document.createElement("div");
//         document.body.prepend(loadingEl);
//         loadingEl.classList.add("page-loader");
//         loadingEl.classList.add("flex-column");
//         loadingEl.classList.add("bg-dark");
//         loadingEl.classList.add("bg-opacity-25");
//         loadingEl.innerHTML = `
//                                             <span class="spinner-border text-primary" role="status"></span>
//                                             <span class="fs-6 fw-semibold mt-5" style="color:#ffffff">Chargement...</span>
//                                         `;
//         document.body.classList.add('page-loading');
//         document.body.setAttribute('data-kt-app-page-loading', "on")
//     }
//     const ajaxComplete = () => {
//         document.body.classList.remove('page-loading');
//         document.body.removeAttribute('data-kt-app-page-loading');
//     }
//     const addFormDeleteLink = (form) => {
//         if (isElement(form)) {
//             const removeFormButton = form.querySelector('.collection-action');
//             removeFormButton.addEventListener('click', (e) => {
//                 $.ajax({
//                     type: "POST",
//                     url: removeFormButton.dataset.url,
//                     data: {token: removeFormButton.dataset.token},
//                     dataType: "json",
//                     beforeSend: ajaxBeforeSend,
//                     success: function(data){
//                         /*ajaxDeleteResult(form, data);*/
//                     },
//                     complete: ajaxComplete
//                 });
//                 delete form_children[form.getAttribute('id')];
//                 form.remove();
//                 calculMontant('transfert', 'prixUnitaire', 'qteCondTrans');
//                 calculMontant('sortie', 'prixUnitaire', 'qteCondSortie');
//                 calculMontant('approvisionnement', 'prixAchat', 'qteCondAppro');
//             });
//         }
//     }
//     const inputEvent = (e) => {
//         if(e.target.hasAttribute('name'))
//         {
//             const prefix = e.target.closest('div.form-child').getAttribute('id');
//             form_children[prefix][e.target.getAttribute('name')] = e.target.value;
//
//             calculMontant('transfert', 'prixUnitaire', 'qteCondTrans');
//             calculMontant('sortie', 'prixUnitaire', 'qteCondSortie');
//             calculMontant('approvisionnement', 'prixAchat', 'qteCondAppro');
//         }
//     }
//     const selectEvent = (e) => {
//         if(e.target.hasAttribute('name'))
//         {
//             const name = e.target.hasAttribute('name');
//             const prefix = e.target.closest('div.form-child').getAttribute('id');
//             form_children[prefix][name] = e.target.value;
//         }
//     }
//     /**
//      * Permet d'ajouter un ou plusieurs formulairs imbriqués
//      * à un formulaire parent après un clic sur le bouton d'ajout
//      * @param e
//      */
//     const addFormToCollection = (e) => {
//         if(container != null)
//         {
//             /**
//              * Récupérer l'élément conteneur de formulaires imbriqués
//              */
//             const collectionHolder = container.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
//             /**
//              * Récupérer la forme brute du formulaire à imbriqué
//              */
//             const prototype = collectionHolder.dataset.prototype;
//             const div = document.createElement('div');
//             const protokey = collectionHolder.dataset.collectionKey;
//             var regex = new RegExp(protokey, "g");
//             div.setAttribute('data-index', collectionHolder.dataset.index);
//             const prefix = collectionHolder.dataset.collectionPrefix
//                 .replace(regex, collectionHolder.dataset.index);
//             div.setAttribute('id', prefix);
//             div.classList.add("ligne");
//             div.classList.add("form-child");
//             div.innerHTML = prototype.replace(regex, collectionHolder.dataset.index);
//             /**
//              * Ajouter la forme html du formulaire imbriqué au conteneur
//              */
//             collectionHolder.appendChild(div);
//             addFormDeleteLink(div);
//             form_children[prefix] = [];
//             initializeContainer(collectionHolder);
//             initializeSelectEvent(collectionHolder)
//             initializeInputEvent(collectionHolder);
//             /*const selects = collectionHolder.querySelectorAll('select.auto-load');
//             selects.forEach(s => {
//                 $(s).select2({
//                     /!*minimumInputLength: 3,*!/
//                     ajax: {
//                         url: s.dataset.selectUrl,
//                         dataType: 'json',
//                         data: function (params) {
//                             return {
//                                 search: params.term,
//                                 type: 'public',
//                                 columns: s.dataset.selectColumns,
//                                 text: s.dataset.selectText,
//                             };
//                         },
//                         processResults: function (response) {
//                             return {
//                                 results: response
//                             };
//                         },
//                         cache: true
//                     },
//                 });
//             });*/
//             /** Incrémentation de l'index après insertion du formulaire imbriqué **/
//             collectionHolder.dataset.index++;
//
//             return collectionHolder;
//         }
//     }
//     function initializeSelectEvent(collectionHolder) {
//         const selects = collectionHolder.querySelectorAll('select');
//         selects.forEach(s => {
//             if(s.getAttribute('name'))
//             {
//                 const prefix = s.closest('div.form-child').getAttribute('id');
//                 form_children[prefix][s.getAttribute('name')] = s.value;
//                 $(s).select2().on('change', selectEvent);
//             }
//         });
//     }
//     function initializeInputEvent(collectionHolder) {
//         //evénement sur les inputs
//         const inputs = collectionHolder.querySelectorAll('input');
//         inputs.forEach(i => {
//             if(i.getAttribute('name'))
//             {
//                 const prefix = i.closest('div.form-child').getAttribute('id');
//                 form_children[prefix][i.getAttribute('name')] = i.value;
//             }
//             i.addEventListener('focusout', inputEvent);
//         });
//     }
//     const produit = () => {
//
//     }
//     const calculMontant = (formName, priceFieldKey, qteFieldKey) => {
//         if(container != null)
//         {
//             let somme = 0;
//             for (const key in form_children) {
//                 const ligne = form_children[key];
//                 if(ligne != null && typeof ligne !== "undefined")
//                 {
//                     const a = parseFloat(ligne[Object.keys(ligne).find(key => key.includes(formName) && key.includes(priceFieldKey))]);
//                     const b = parseFloat(ligne[Object.keys(ligne).find(key => key.includes(formName) && key.includes(qteFieldKey))]);
//                     if(a > 0 && b > 0){
//                         somme += (a * b);
//                         const parent = container.querySelector('#' + key);
//                         if(parent != null)
//                         {
//                             const subTotals = parent.querySelectorAll('.subTotal');
//                             subTotals.forEach(t => {
//                                 if(t.hasAttribute('name'))
//                                 {
//                                     if((a * b) > 0)
//                                         t.value = (a * b);
//                                 }
//                             });
//                         }
//                     }
//                 }
//             }
//             const totals = document.querySelectorAll('.total');
//             totals.forEach(t => {
//                 if(t.hasAttribute('name'))
//                 {
//                     if(somme > 0)
//                         t.value = somme;
//                 }
//             });
//         }
//     }
//     const transfert = (parent) => {
//         if(container != null)
//         {
//             const mag1 = container.querySelector('[name*="magasinTrans1"]');
//             const addBtn = container.querySelector('[data-collection-holder-class="collection-form"]');
//             console.log(addBtn);
//             if(mag1 != null)
//             {
//                 const option = $(mag1).find('option:selected');
//                 console.log(option);
//                 //$(addBtn).addClass('d-none');
//                 $(mag1).select2().on('change', function () {
//                     let idMag1 = parseInt(mag1.value);
//                     const selects = container.querySelectorAll('select');
//                     if(idMag1 > 0)
//                     {
//                         $(addBtn).removeClass('d-none');
//                         console.log('Selects : ', selects);
//                     }
//                     else
//                         $(addBtn).addClass('d-none');
//                 });
//
//                 const j = container.querySelectorAll('select[name*="produitCondTrans"]');
//                 console.log('Oooooo : ', j);
//             }
//         }
//         //calculMontant('transfert', 'prixUnitaire', 'qteCondTrans');
//     }
//     const sortieStock = () => {
//         calculMontant('sortie', 'prixUnitaire', 'qteCondSortie');
//     }
//     const approvisionnement = () => {
//         calculMontant('approvisionnement', 'prixAchat', 'qteCondAppro');
//     }
//     // const initializeContainer = (collectionHolder = null) => {
//     //     let parent = container;
//     //     if(collectionHolder != null)
//     //         parent = collectionHolder;
//     //     //initialiser les collections
//     //     parent.querySelectorAll('.add_item_link').forEach(btn => {
//     //         btn.addEventListener("click", addFormToCollection);
//     //         const holders = container.querySelectorAll('.' + btn.dataset.collectionHolderClass);
//     //         holders.forEach(h => {
//     //             const divs = h.querySelectorAll('div.form-child');
//     //             divs.forEach(div => {
//     //                 if(collectionHolder == null)
//     //                     form_children[div.getAttribute('id')] = [];
//     //                 addFormDeleteLink(div);
//     //             });
//     //             initializeSelectEvent(h)
//     //             initializeInputEvent(h);
//     //         });
//     //     });
//     //     transfert();
//     //     /*//initialiser les événements sur les selects à charger via ajax
//     //     const selects = parent.querySelectorAll('select.auto-load');
//     //     selects.forEach(s => {
//     //         $(s).select2({
//     //             /!*minimumInputLength: 3,*!/
//     //             ajax: {
//     //                 url: s.dataset.selectUrl,
//     //                 dataType: 'json',
//     //                 data: function (params) {
//     //                     return {
//     //                         search: params.term,
//     //                         type: 'public',
//     //                         columns: s.dataset.selectColumns,
//     //                         text: s.dataset.selectText,
//     //                     };
//     //                 },
//     //                 processResults: function (response) {
//     //                     return {
//     //                         results: response
//     //                     };
//     //                 },
//     //                 cache: true
//     //             },
//     //         });
//     //     });*/
//     // }
//     return {
//         init: function() {
//             initializeContainer();
//             /*initializeForm();
//             swishStatut();
//             produit();
//             transfert();
//             sortieStock();
//             approvisionnement();*/
//         }
//     }
// }();
// KTUtil.onDOMContentLoaded((function() {
//     KTAll.init();
// }));
