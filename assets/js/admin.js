(function($) {
    'use strict';

    $(document).on('click', '.patropi-toggle', function(e) {
        var $toggle = $(this);
        var $checkbox = $toggle.find('input[type="checkbox"]');
        
        if (e.target.tagName !== 'INPUT') {
            e.preventDefault();
            var isChecked = $checkbox.prop('checked');
            $checkbox.prop('checked', !isChecked);
        }
        
        $checkbox.trigger('change');
    });

    $(document).on('change', '.patropi-toggle input[type="checkbox"]', function() {
        var $switch = $(this).siblings('.patropi-toggle-switch');
        if ($(this).is(':checked')) {
            $switch.css('background', '#2c3e50');
        } else {
            $switch.css('background', '#95a5a6');
        }
        
        // Handle icon section visibility for rotation toggle
        if ($(this).attr('name') === 'faq_icon_rotation') {
            var $container = $(this).closest('.patropi-card');
            if ($(this).is(':checked')) {
                $container.find('.icon-section-single').show();
                $container.find('.icon-section-dual').hide();
            } else {
                $container.find('.icon-section-single').hide();
                $container.find('.icon-section-dual').show();
            }
        }

        // Handle Mega Menu: toggle mega config visibility
        if ($(this).hasClass('patropi-has-mega-toggle')) {
            var $item = $(this).closest('.patropi-menu-item');
            if ($(this).is(':checked')) {
                $item.find('.patropi-mega-config').show();
                $item.find('.patropi-simple-link-config').hide();
            } else {
                $item.find('.patropi-mega-config').hide();
                $item.find('.patropi-simple-link-config').show();
            }
        }

        // Handle Mega Menu: toggle title visibility
        if ($(this).hasClass('patropi-has-title-toggle')) {
            var $col = $(this).closest('.patropi-column');
            if ($(this).is(':checked')) {
                $col.find('.patropi-title-config').show();
            } else {
                $col.find('.patropi-title-config').hide();
            }
        }
    });

    $(document).ready(function() {
        // Initialize toggle switch colors
        $('.patropi-toggle input[type="checkbox"]').each(function() {
            var $switch = $(this).siblings('.patropi-toggle-switch');
            if ($(this).is(':checked')) {
                $switch.css('background', '#2c3e50');
            } else {
                $switch.css('background', '#95a5a6');
            }
        });

        // Initialize icon section visibility based on current rotation setting
        $('input[name="faq_icon_rotation"]').each(function() {
            var $container = $(this).closest('.patropi-card');
            if ($(this).is(':checked')) {
                $container.find('.icon-section-single').show();
                $container.find('.icon-section-dual').hide();
            } else {
                $container.find('.icon-section-single').hide();
                $container.find('.icon-section-dual').show();
            }
        });

        // Highlight selected icon options
        $(document).on('click', '.icon-option', function() {
            var $container = $(this).closest('.icon-select-container');
            $container.find('.icon-option').css({
                'border-color': '#ddd',
                'background': '#fff'
            });
            $(this).css({
                'border-color': '#2c3e50',
                'background': '#f8f9fa'
            });
        });

        // MEGA MENU BUILDER FUNCTIONALITY
        
        // Handle column layout change
        $(document).on('change', '.patropi-col-layout', function() {
            var $col = $(this).closest('.patropi-column');
            if ($(this).val() === 'links') {
                $col.find('.patropi-links-config').show();
                $col.find('.patropi-image-config').hide();
            } else {
                $col.find('.patropi-links-config').hide();
                $col.find('.patropi-image-config').show();
            }
        });

        // Handle link type change
        $(document).on('change', '.patropi-link-type', function() {
            var $row = $(this).closest('.patropi-link-item');
            if ($(this).val() === 'custom') {
                $row.find('.patropi-page-select').hide();
                $row.find('.patropi-custom-url').show();
                $row.find('.patropi-custom-text').show();
            } else {
                $row.find('.patropi-page-select').show();
                $row.find('.patropi-custom-url').hide();
                $row.find('.patropi-custom-text').hide();
            }
        });

        // Handle simple link type change
        $(document).on('change', '.patropi-link-type-simple', function() {
            var $item = $(this).closest('.patropi-menu-item');
            if ($(this).val() === 'custom') {
                $item.find('.patropi-simple-page').hide();
                $item.find('.patropi-simple-custom').show();
            } else {
                $item.find('.patropi-simple-page').show();
                $item.find('.patropi-simple-custom').hide();
            }
        });

        // Add new menu item
        $('#patropi-add-menu-item').on('click', function() {
            var $container = $('#patropi-menu-items-container');
            var count = $container.find('.patropi-menu-item').length;
            
            var html = '<div class="patropi-menu-item card mb-3" data-index="' + count + '">' +
                '<div class="card-body">' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<label><strong>Texto do item</strong></label>' +
                '<input type="text" name="menu_items[' + count + '][text]" value="" class="form-control" placeholder="Nome do item">' +
                '</div>' +
                '<div class="col-md-6">' +
                '<label><strong>Tem mega menu?</strong></label>' +
                '<label class="patropi-toggle" style="margin-top: 5px;">' +
                '<input type="checkbox" name="menu_items[' + count + '][has_mega]" value="1" class="patropi-has-mega-toggle">' +
                '<span class="patropi-toggle-switch"></span>' +
                '<span class="patropi-toggle-label">Ativar mega menu</span>' +
                '</label>' +
                '</div>' +
                '</div>' +
                '<div class="patropi-mega-config" style="display: none;">' +
                '<hr><h5>Configurações do Mega Menu</h5>' +
                '<div class="mb-3">' +
                '<label><strong>Número de colunas</strong></label>' +
                '<select name="menu_items[' + count + '][num_columns]" class="form-control patropi-num-columns">' +
                '<option value="1">1</option><option value="2">2</option><option value="3">3</option>' +
                '<option value="4">4</option><option value="5">5</option><option value="6">6</option>' +
                '</select>' +
                '</div>' +
                '<div class="patropi-columns-container"></div>' +
                '</div>' +
                '<div class="patropi-simple-link-config mt-3">' +
                '<hr><h5>Link do item (sem mega menu)</h5>' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<label><strong>Tipo de link</strong></label>' +
                '<select name="menu_items[' + count + '][link_type]" class="form-control patropi-link-type-simple">' +
                '<option value="page">Página</option>' +
                '<option value="custom">URL customizada</option>' +
                '</select>' +
                '</div>' +
                '<div class="col-md-6 patropi-simple-page">' +
                '<label><strong>Selecionar página</strong></label>' +
                '<select name="menu_items[' + count + '][link_page_id]" class="form-control patropi-page-dropdown">' +
                '<option value="">Selecione uma página</option>' +
                '</select>' +
                '</div>' +
                '<div class="col-md-6 patropi-simple-custom" style="display: none;">' +
                '<label><strong>URL</strong></label>' +
                '<input type="text" name="menu_items[' + count + '][link_url]" value="" class="form-control" placeholder="https://...">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="mt-3">' +
                '<button type="button" class="btn btn-danger btn-sm patropi-remove-item">Remover item</button>' +
                '</div>' +
                '</div>' +
                '</div>';
            
            $container.append(html);
            
            // Initialize toggle for new item
            var $newItem = $container.find('.patropi-menu-item').last();
            $newItem.find('.patropi-toggle-switch').css('background', '#95a5a6');
            
            // Copy pages dropdown from first item if exists
            var $firstDropdown = $('#patropi-menu-items-container .patropi-menu-item').first().find('.patropi-page-dropdown');
            if ($firstDropdown.length) {
                var $newDropdown = $newItem.find('.patropi-page-dropdown');
                $newDropdown.html($firstDropdown.html());
            }
        });

        // Remove menu item
        $(document).on('click', '.patropi-remove-item', function() {
            var $container = $('#patropi-menu-items-container');
            if ($container.find('.patropi-menu-item').length > 1) {
                $(this).closest('.patropi-menu-item').remove();
            }
        });

        // Add link to column
        $(document).on('click', '.patropi-add-link', function() {
            var $list = $(this).prev('.patropi-links-list');
            var count = $list.find('.patropi-link-item').length;
            
            if (count >= 10) {
                alert('Máximo de 10 links por coluna.');
                return;
            }

            var $col = $(this).closest('.patropi-column');
            var colIndex = $col.data('col-index');
            var itemIndex = $col.closest('.patropi-menu-item').data('index');

            var html = '<div class="patropi-link-item row mb-2">' +
                '<div class="col-md-3">' +
                '<select name="menu_items[' + itemIndex + '][columns][' + colIndex + '][links][' + count + '][type]" class="form-control patropi-link-type">' +
                '<option value="page">Página</option>' +
                '<option value="custom">Link customizado</option>' +
                '</select>' +
                '</div>' +
                '<div class="col-md-3 patropi-page-select">' +
                '<select name="menu_items[' + itemIndex + '][columns][' + colIndex + '][links][' + count + '][page_id]" class="form-control">' +
                '<option value="">Selecione uma página</option>' +
                '</select>' +
                '</div>' +
                '<div class="col-md-3 patropi-custom-url" style="display: none;">' +
                '<input type="text" name="menu_items[' + itemIndex + '][columns][' + colIndex + '][links][' + count + '][url]" value="" class="form-control" placeholder="URL">' +
                '</div>' +
                '<div class="col-md-2 patropi-custom-text" style="display: none;">' +
                '<input type="text" name="menu_items[' + itemIndex + '][columns][' + colIndex + '][links][' + count + '][text]" value="" class="form-control" placeholder="Texto">' +
                '</div>' +
                '<div class="col-md-1">' +
                '<button type="button" class="btn btn-danger btn-sm patropi-remove-link">×</button>' +
                '</div>' +
                '</div>';
            
            $list.append(html);
        });

        // Remove link from column
        $(document).on('click', '.patropi-remove-link', function() {
            var $list = $(this).closest('.patropi-links-list');
            if ($list.find('.patropi-link-item').length > 1) {
                $(this).closest('.patropi-link-item').remove();
            }
        });

        // Add column button
        $(document).on('click', '.patropi-add-column', function() {
            console.log('Add column clicked');
            var $container = $(this).closest('.patropi-mega-config');
            var $columnsContainer = $container.find('.patropi-columns-container');
            var itemIndex = $container.closest('.patropi-menu-item').data('index');
            console.log('itemIndex:', itemIndex);
            var colCount = $columnsContainer.find('.patropi-column').length;
            console.log('colCount:', colCount);
            
            if (colCount >= 6) {
                alert('Máximo de 6 colunas permitidas.');
                return;
            }

            var $pagesDropdown = $('#patropi-menu-items-container .patropi-page-dropdown').first();
            var pagesHtml = $pagesDropdown.length ? $pagesDropdown.html() : '<option value="">Selecione uma página</option>';

            var colHtml = '<div class="patropi-column card mb-2" data-col-index="' + colCount + '">' +
                '<div class="card-header d-flex justify-content-between align-items-center">' +
                '<span>Coluna ' + (colCount + 1) + '</span>' +
                '<button type="button" class="btn btn-danger btn-sm patropi-remove-column">Remover</button>' +
                '</div>' +
                '<div class="card-body">' +
                '<div class="row">' +
                '<div class="col-4 col-md-4">' +
                '<label><strong>Largura (%)</strong></label>' +
                '<input type="number" name="menu_items[' + itemIndex + '][columns][' + colCount + '][width]" value="25" class="form-control" min="1" max="100">' +
                '</div>' +
                '<div class="col-4 col-md-4">' +
                '<label><strong>Tem título?</strong></label>' +
                '<label class="patropi-toggle" style="margin-top: 5px;">' +
                '<input type="checkbox" name="menu_items[' + itemIndex + '][columns][' + colCount + '][has_title]" value="1" class="patropi-has-title-toggle">' +
                '<span class="patropi-toggle-switch"></span>' +
                '<span class="patropi-toggle-label">Mostrar</span>' +
                '</label>' +
                '</div>' +
                '<div class="col-4 col-md-4">' +
                '<label><strong>Layout</strong></label>' +
                '<select name="menu_items[' + itemIndex + '][columns][' + colCount + '][layout]" class="form-control patropi-col-layout">' +
                '<option value="links">Links</option>' +
                '<option value="image">Imagem</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '<div class="patropi-title-config mt-2" style="display: none;">' +
                '<label><strong>Título da coluna</strong></label>' +
                '<input type="text" name="menu_items[' + itemIndex + '][columns][' + colCount + '][title]" value="" class="form-control">' +
                '</div>' +
                '<div class="patropi-links-config mt-3">' +
                '<label><strong>Adicionar links</strong></label>' +
                '<p class="text-muted" style="font-size: 12px;">Máximo 10 links por coluna.</p>' +
                '<div class="patropi-links-list">' +
                '<div class="patropi-link-item row mb-2">' +
                '<div class="col-md-3">' +
                '<select name="menu_items[' + itemIndex + '][columns][' + colCount + '][links][0][type]" class="form-control patropi-link-type">' +
                '<option value="page">Página</option>' +
                '<option value="custom">Link customizado</option>' +
                '</select>' +
                '</div>' +
                '<div class="col-md-3 patropi-page-select">' +
                '<select name="menu_items[' + itemIndex + '][columns][' + colCount + '][links][0][page_id]" class="form-control patropi-page-dropdown">' +
                pagesHtml +
                '</select>' +
                '</div>' +
                '<div class="col-md-3 patropi-custom-url" style="display: none;">' +
                '<input type="text" name="menu_items[' + itemIndex + '][columns][' + colCount + '][links][0][url]" value="" class="form-control" placeholder="URL">' +
                '</div>' +
                '<div class="col-md-2 patropi-custom-text" style="display: none;">' +
                '<input type="text" name="menu_items[' + itemIndex + '][columns][' + colCount + '][links][0][text]" value="" class="form-control" placeholder="Texto">' +
                '</div>' +
                '<div class="col-md-1">' +
                '<button type="button" class="btn btn-danger btn-sm patropi-remove-link">×</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<button type="button" class="btn btn-secondary btn-sm mt-2 patropi-add-link">+ Adicionar link</button>' +
                '</div>' +
                '<div class="patropi-image-config mt-3" style="display: none;">' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<label><strong>Ícone (dashicon)</strong></label>' +
                '<input type="text" name="menu_items[' + itemIndex + '][columns][' + colCount + '][image_data][icon]" value="" class="form-control" placeholder="dashicons-cart">' +
                '</div>' +
                '<div class="col-md-6">' +
                '<label><strong>URL da imagem</strong></label>' +
                '<input type="text" name="menu_items[' + itemIndex + '][columns][' + colCount + '][image_data][image_url]" value="" class="form-control" placeholder="https://...">' +
                '</div>' +
                '</div>' +
                '<div class="row mt-2">' +
                '<div class="col-md-6">' +
                '<label><strong>Título</strong></label>' +
                '<input type="text" name="menu_items[' + itemIndex + '][columns][' + colCount + '][image_data][title]" value="" class="form-control">' +
                '</div>' +
                '<div class="col-md-6">' +
                '<label><strong>Descrição</strong></label>' +
                '<input type="text" name="menu_items[' + itemIndex + '][columns][' + colCount + '][image_data][description]" value="" class="form-control">' +
                '</div>' +
                '</div>' +
                '<div class="mt-2">' +
                '<label><strong>Link (URL)</strong></label>' +
                '<input type="text" name="menu_items[' + itemIndex + '][columns][' + colCount + '][image_data][link_url]" value="" class="form-control" placeholder="https://...">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';
                
            $columnsContainer.append(colHtml);
            
            $columnsContainer.find('.patropi-toggle-switch').css('background', '#95a5a6');
        });

        // Remove column
        $(document).on('click', '.patropi-remove-column', function() {
            console.log('Remove column clicked');
            var $columnsContainer = $(this).closest('.patropi-columns-container');
            console.log('Columns count:', $columnsContainer.find('.patropi-column').length);
            if ($columnsContainer.find('.patropi-column').length > 1) {
                $(this).closest('.patropi-column').remove();
            } else {
                alert('Mínimo de 1 coluna permitidos.');
            }
        });
    });
})(jQuery);