(function () {
    var AjaxifySearch = window.AjaxifySearch = function (element, options) {
        this.element = element;
        this.options = options;
        this.total = 0;
        this.currentPage = 1;

        this.data = {
            entity: options.entity,
            perpage: options.perpage,
            search: '',
        }

        for (var option in options) {
            if (options.hasOwnProperty(option)) {
                if (AjaxifySearch.OPTIONS.indexOf(option) === -1) {
                    this.data[option] = options[option];
                }
            }
        }

        this.$tableFooterContainer = this.tableFooterContainer();
        this.$showRecordsContainer = this.showRecordsContainer();
        this.$paginationContainer = this.createPaginationContainer();
        this.$itemContainer = this.element.find(this.options.itemContainer);

        this.createLoader();
        this.$tableHeaderContainer = this.createFilterContainer();
        this.init();
    }

    AjaxifySearch.OPTIONS = ['request', 'entity', 'perpage', 'itemContainer', 'onload'];

    AjaxifySearch.prototype = {
        init: function () {
            var _self = this;

            if (typeof this.request != 'undefined') {
                this.request.abort();
            }

            this.request = $.ajax({
                url: AjaxifySearch.URL.count,
                type: 'GET',
                data: this.data,
                beforeSend: function () {
                    _self.showLoader();
                },
                complete: function () { // Set our complete callback, adding the .hidden class and hiding the spinner.
                    _self.hideLoader();
                }
            })
                .done(function (count) {
                    _self.total = count;
                    _self.showTableInfo();
                    _self.showPagination();
                    _self.request = void 0;
                });
        },

        showPagination: function () {
            var nbPage = Math.ceil(this.total / this.options.perpage);
            var currentPage3 = this.currentPage + 1;
            var end3 = nbPage - 4;

            this.$paginationContainer.html('');
            this.$paginationContainer.append(this.createPreviousLink(this.currentPage, nbPage));
            this.$paginationContainer.append(this.createPaginationLink(1));

            if (this.currentPage < 5) {
                for (let i = 2; i <= 5 && i <= nbPage; i++) {
                    this.$paginationContainer.append(this.createPaginationLink(i));
                }
            }
            else {

                this.$paginationContainer.append(this.createEllipsis());

                if(this.currentPage > end3 && this.currentPage <= nbPage)
                {
                    for (let i = end3; i <= nbPage; i++) {
                        this.$paginationContainer.append(this.createPaginationLink(i));
                    }
                }
                else
                {
                    for (let i = this.currentPage - 1; i <= this.currentPage + 1 && i <= nbPage; i++) {
                        this.$paginationContainer.append(this.createPaginationLink(i));
                    }
                }
            }

            for (let i = this.currentPage + 2; i <= currentPage3 && i <= nbPage; i++) {
                this.$paginationContainer.append(this.createPaginationLink(i));
            }

            if (this.currentPage <= end3) {
                this.$paginationContainer.append(this.createEllipsis());
                this.$paginationContainer.append(this.createPaginationLink(nbPage));
            }

            this.$paginationContainer.append(this.createNextLink(this.currentPage, nbPage));
        },

        showTableInfo: function () {
            let nbPage = Math.ceil(this.total / this.options.perpage);
            let rest = this.total % this.options.perpage;

            const prevDataCount = ((this.currentPage - 1) * this.options.perpage) + 1;
            const currentDataCount = this.currentPage < nbPage ? this.currentPage * this.options.perpage : (prevDataCount + rest - 1);

            this.$showRecordsContainer.text(`Affichage de ${prevDataCount} à ${currentDataCount} sur ${this.total} lignes`);
        },

        createFilterContainer: function () {
            let _self = this;
            let $row = $('<div class="row mb-3"></div>');
            let $filterCol = $('<div class="col-sm-2"></div>');
            let $searchCol = $('<div class="col-sm-4"></div>');

            let $searchTag = $(`
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"/>
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"/>
                        </svg>
                    </span>
                </div>
            `);

            let $input = $('<input type="text" data-kt-entity-table-filter="search" class="form-control form-control-solid ps-15" placeholder="Rechercher..."/>');
            $input.on('focusout', function (e) {
                let search = $(this).val();
                _self.search(search);

                _self.showLoader();
                setTimeout(function() {
                    _self.search(search);
                    _self.hideLoader();
                });
            });

            let $perpage = $(`
                <select class="form-select form-select-solid" data-control="select2">
                    <option value="10">Par page</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            `);

            $perpage.on('change', function () {
                var perpage = parseInt($(this).val());
                var selectedoption = $(this).find('option:selected');
                var firstoption = $(this).find('option:first');
                if ((perpage !== 0 && perpage !== _self.options.perpage) || selectedoption.text() === firstoption.text()) {
                    _self.data['page'] = 1;
                    _self.options.perpage = perpage;
                    _self.data.perpage = perpage;
                    _self.showLoader();
                    setTimeout(function() {
                        _self.search(_self.data['search']);
                        _self.hideLoader();
                    });
                }
            });
            $perpage.trigger("change");

            $searchTag.append($input);
            $searchCol.append($searchTag);
            $filterCol.append($perpage);

            let $actionsRow = $('<div class="col-sm-6"></div>');
            let $actionsGroup = $(`
            <div class="d-flex justify-content-end align-items-center d-none"
                    data-kt-entity-table-toolbar="selected">
                <div class="fw-bolder me-5">
                    <span class="me-2" data-kt-entity-table-select="selected_count"></span>Sélectionné(s)
                </div>
                <button type="button" class="btn btn-danger"
                        data-kt-entity-table-select="delete_selected">Mettre en corbeille
                </button>
            </div>
            `);
            $actionsRow.append($actionsGroup);

            $row.append($filterCol);
            $row.append($actionsRow);
            $row.append($searchCol);
            this.element.parent().before($row);

            return $row;
        },

        tableFooterContainer: function () {
            let $row = $(`<div class="row mt-3 pagination-container"></div>`);
            this.element.parent().parent().append($row);

            return $row;
        },

        showRecordsContainer: function () {
            let _self = this;
            let $firstDiv = $('<div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"></div>');
            let $secondDiv = $('<div class="dataTables_length"></div>');
            let $perpage1 = $(
                `<select class="form-select form-select-sm form-select-solid">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>`
            );
            $perpage1.on('change', function () {
                let perpage = parseInt($(this).val());
                let selectedoption = $(this).find('option:selected');
                let firstoption = $(this).find('option:first');
                if ((perpage !== 0 && perpage !== _self.options.perpage) || selectedoption.text() === firstoption.text()) {
                    _self.data['page'] = 1;
                    _self.options.perpage = perpage;
                    _self.data.perpage = perpage;
                    //_self.showLoader();
                    setTimeout(function() {
                        _self.search(_self.data['search']);
                        //_self.hideLoader();
                    });
                }
            });
            $perpage1.trigger("change");

            $secondDiv.append($perpage1);
            $firstDiv.append($secondDiv);
            let $datatableInfo = $(
                `<div class="dataTables_info" role="status" aria-live="polite">
                </div>`
            );

            $firstDiv.append($datatableInfo);
            this.$tableFooterContainer.append($firstDiv);

            return $datatableInfo;
        },

        createPaginationContainer: function () {
            let $rightdiv = $(`
                <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                    <div class="dataTables_paginate paging_simple_numbers">
                    </div>
                 </div>
            `);
            let $ul = $('<ul class="pagination"></ul>');

            $rightdiv.append($ul);
            this.$tableFooterContainer.append($rightdiv);

            return $ul;
        },

        createLoader: function () {
            var $loader = $('<div class="text-center"><i class="fa fa-spinner fa-spin"></i>&nbsp;&nbsp;Chargement...</div>')

            this.element.parent().css('position', 'relative');

            $loader.css({
                position: 'absolute',
                width: '100%',
                padding: '20px',
                textAlign: 'center',
                top: '50%',
                transform: 'translateY(-50%)',
                backgroundColor: '#fff',
            }).hide();

            this.$loader = $loader;
            this.element.parent().prepend($loader);
        },

        createPaginationLink: function (page) {
            let _self = this
            let $link = $('<li class="paginate_button page-item"><a class="page-link" href="javascript:;">' + page + '</a></li>');

            if (page === this.currentPage) {
                $link.addClass('active')
                $link.find('a').on('click', function (e) {
                    e.preventDefault()
                })
            } else {
                $link.find('a').on('click', function (e) {
                    e.preventDefault()

                    _self.loadMore(page)
                })
            }

            return $link
        },

        createPreviousLink: function (page, nbPage) {

            var $link = this.createPaginationLink(page - 1)

            $link.find('a').html('<i class="previous"></i>').on('click', function (e) {
                e.preventDefault()
            })

            if (page === 1) {
                $link.find('a').off('click')
                $link.addClass('previous disabled')
            }

            return $link
        },

        createNextLink: function (page, nbPage) {
            var $link = this.createPaginationLink(page + 1);

            $link.find('a').html('<i class="next"></i>').on('click', function (e) {
                e.preventDefault();
            });

            if (page === nbPage) {
                $link.find('a').off('click');
                $link.addClass('next disabled');
            }

            return $link;
        },

        createEllipsis: function () {
            var $link = this.createPaginationLink(0);

            $link.find('a').off('click').text('...').on('click', function (e) {
                e.preventDefault();
            });

            $link.addClass('disabled');

            return $link;
        },

        search: function (search) {
            var _self = this
            this.data['search'] = search

            if (typeof this.request != 'undefined') {
                this.request.abort();
            }

            this.request = $.ajax({
                url: AjaxifySearch.URL.search,
                type: 'GET',
                async: false,
                data: this.data,
                dataType: 'json',
                beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                    _self.showLoader();
                },
                success: function (data) {
                    _self.request = void 0;

                    _self.total = data.total;
                    _self.currentPage = 1;

                    _self.replaceContent(data.views);
                },
                error: function (error) {
                    console.log(error);
                    console.log("Sorry. Server unavailable. ");
                },
                complete: function () { // Set our complete callback, adding the .hidden class and hiding the spinner.
                    _self.hideLoader();
                },
            });

            return false;
        },

        loadMore: function (page) {
            var _self = this;
            var data = this.data;

            data['page'] = page;

            if (typeof this.request != 'undefined') {
                this.request.abort();
            }

            this.showLoader();

            this.request = $.ajax({
                url: AjaxifySearch.URL.search,
                type: 'GET',
                data: data,
                dataType: 'json',
                beforeSend: function () {
                    _self.showLoader();
                },
                complete: function () { // Set our complete callback, adding the .hidden class and hiding the spinner.
                    _self.hideLoader();
                },
            })
                .done(function (data) {
                    _self.request = void 0;
                    _self.currentPage = page;

                    _self.replaceContent(data.views);
                });
        },

        handleShowRow: function () {
            const showButtons = (this.element[0]).querySelectorAll('[data-kt-entity-table-filter="show_row"]');
            if(showButtons != null)
            {
                showButtons.forEach(d => {
                    const onclickEvent = d.dataset.onclickEvent;
                    d.addEventListener('click', window[onclickEvent]);
                });
            }
        },

        handleEditRows: function () {
            const editButtons = (this.element[0]).querySelectorAll('[data-kt-entity-table-filter="edit_row"]');
            if(editButtons != null)
            {
                editButtons.forEach(d => {
                    d.addEventListener('click', function (e) {
                        e.preventDefault();
                        if(
                            typeof e.target.dataset !== "undefined" &&
                            typeof e.target.dataset.url !== "undefined"
                        )
                        {
                            $.ajax({
                                type: "POST",
                                url: e.target.dataset.url,
                                dataType: "json",
                                beforeSend: ajaxBeforeSend,
                                success: function (data) {
                                    onEdit(e, data);
                                },
                                complete: ajaxComplete
                            });
                        }
                    })
                });
            }
        },

        handleDeleteRows: function() {
            let _self = this;
            const deleteButtons = (_self.element[0]).querySelectorAll('[data-kt-entity-table-filter="delete_row"]');
            if (deleteButtons != null) {
                deleteButtons.forEach(d => {
                    d.addEventListener('click', function (e) {
                        e.preventDefault();

                        let estProduit = false;
                        if ( (d.dataset.url).includes('-save-produit/del') ) {
                            estProduit = true;
                        }

                        const parent = e.target.closest('tr');
                        if(
                            typeof e.target.dataset !== "undefined" &&
                            typeof e.target.dataset.id !== "undefined"
                        )
                        {
                            $.ajax({
                                type: "POST",
                                url: (_self.element[0]).dataset.url,
                                data: { id: e.target.dataset.id },
                                dataType: "json",
                                success: function (data) {
                                    e.target.dataset.token = data.token;
                                }
                            });
                        }

                        Swal.fire({
                            text: (estProduit) ? "Etes-vous sûr de vouloir désactiver cette ligne ?" : "Etes-vous sûr de vouloir supprimer cette ligne ?",
                            icon: "warning",
                            showCancelButton: true,
                            buttonsStyling: false,
                            confirmButtonText: (estProduit) ? "Oui, désactiver!" : "Oui, supprimer!",
                            cancelButtonText: "Non, annuler",
                            customClass: {
                                confirmButton: "btn fw-bold btn-danger",
                                cancelButton: "btn fw-bold btn-active-light-primary"
                            }
                        }).then(function (result) {
                            if (result.value) {
                                Swal.fire({
                                    text: (estProduit) ? "Désactivation" : "Suppression",
                                    icon: "info",
                                    buttonsStyling: false,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(function () {
                                    Swal.fire({
                                        text: (estProduit) ? "Désactivation effectuée avec succès !" : "Suppression effectuée avec succès !",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    }).then(function () {
                                        $.ajax({
                                            type: "GET",
                                            url: e.target.dataset.url,
                                            data: { token: e.target.dataset.token },
                                            dataType: "json",
                                            success: function (data) {
                                                afterDelete(e, data);
                                                parent.remove();
                                            }
                                        });
                                    });
                                });
                            } else if (result.dismiss === 'cancel') {
                                Swal.fire({
                                    text: (estProduit) ? "La désactivation a été annulée!" : "La suppression a été annulée!",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                });
                            }
                        });
                    })
                });
            }
        },

        toggleToolbars: function () {
            let _self = this;
            const toolbarSelected = (_self.$tableHeaderContainer[0]).querySelector('[data-kt-entity-table-toolbar="selected"]');
            const selectedCount = (_self.$tableHeaderContainer[0]).querySelector('[data-kt-entity-table-select="selected_count"]');
            const allCheckboxes = (_self.element[0]).querySelectorAll('tbody .kt_entity_checkbox');
            // Detect checkboxes state & count
            let checkedState = false;
            let count = 0;

            // Count checked boxes
            allCheckboxes.forEach(c => {
                if (c.checked) {
                    checkedState = true;
                    count++;
                }
            });

            // Toggle toolbars
            if (checkedState) {
                selectedCount.innerHTML = count;
                toolbarSelected.classList.remove('d-none');
            } else {
                toolbarSelected.classList.add('d-none');
            }
        },

        initToggleToolbar: function() {
            let _self = this;
            const checkboxes = (_self.element[0]).querySelectorAll('.kt_entity_checkbox');
            // Select elements
            const deleteSelected = (_self.$tableHeaderContainer[0]).querySelector('[data-kt-entity-table-select="delete_selected"]');

            // Toggle delete selected toolbar
            checkboxes.forEach(c => {
                // Checkbox on click event
                c.addEventListener('click', function () {
                    setTimeout(function () {
                        _self.toggleToolbars();
                    }, 50);
                });
            });
            // Deleted selected rows
            deleteSelected.addEventListener('click', function () {
                Swal.fire({
                    text: "Etes-vous sûr de vouloir supprimer les ligne(s) sélectionnée(s)?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    showLoaderOnConfirm: true,
                    confirmButtonText: "Oui, supprimer!",
                    cancelButtonText: "Non, annuler",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    },
                }).then(function (result) {
                    if (result.value) {
                        // Simulate delete request -- for demo purpose only
                        Swal.fire({
                            text: "Suppression des ligne(s) sélectionnée(s)",
                            icon: "info",
                            buttonsStyling: false,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(function () {
                            Swal.fire({
                                text: "Vous avez supprimé toutes les ligne(s) sélectionnée(s)!.",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            }).then(function () {
                                // delete row data from server and re-draw datatable
                            });

                            // Remove header checked box
                            const headerCheckbox = (_self.element[0]).querySelectorAll('.kt_entity_checkbox')[0];
                            headerCheckbox.checked = false;
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: "Les ligne(s) sélectionnée(s) n'ont pas été supprimée(s).",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            });
        },

        replaceContent: function (view) {
            this.hideLoader();
            this.$itemContainer.html('');
            this.$itemContainer.append(view);

            //Réinitialiser le menu action
            KTMenu.createInstances();

            if (typeof window[this.options.onload] === 'function') {
                window[this.options.onload]();
            }

            this.handleShowRow();
            this.handleEditRows();
            this.handleDeleteRows();
            this.showPagination();
            this.showTableInfo();
            this.initToggleToolbar();
            this.toggleToolbars();
        },

        showLoader: function () {
            this.$loader.css({
                top: '50%',
                transform: 'translateY(-50%)',
            });

            this.$loader.show();
        },

        hideLoader: function () {
            this.$loader.hide();
        },
    }

    $.fn.ajaxifySearch = function () {
        return this.each(function () {
            var $el = $(this);
            var data = $el.data();
            if (typeof data.itemContainer == 'undefined') {
                data.itemContainer = 'tbody';
            }

            if (typeof data.onload === 'undefined') {
                data.onload = 'onContentLoaded';
            }

            $el.data('ajaxify-search', new AjaxifySearch($el, data));
        })
    }

    $(document).ready(function () {
        $('[data-request="ajaxify-search"]').each(function () {
            $(this).ajaxifySearch();
        });
    });
})()