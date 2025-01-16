import 'datatables.net';
import 'datatables.net-dt/css/dataTables.dataTables.min.css'; // CSS do DataTables
import 'datatables.net-buttons';
import 'datatables.net-select';           // Plugin de seleção para DataTables
import 'datatables.net-buttons/js/buttons.html5.min';
import '/resources/css/core/crud-index.css' // deixa as customizacoes de css por ultimo dos imports

import JSZip from 'jszip'; // Para exportação para Excel
import pdfMake from 'pdfmake/build/pdfmake'; // Para exportação para PDF
import { route } from 'ziggy-js';

import toastr from 'toastr'; // Importa Toastr
import 'toastr/build/toastr.min.css'; // Importa o estilo do Toastr

// Tornando as dependências globais
window.JSZip = JSZip;
window.pdfMake = pdfMake;

//-- crud datatables initaialization
$(function () {

    // Restaurar o valor de linhas por página
    const tableName = $('#crud-tablename').val();
    const savedPageLength = localStorage.getItem(tableName+'_pageLength');

    var table = $('#'+tableName+'-table').DataTable({
        dom: 'Bflrtip', // Adiciona os botões de exportação
        pageLength: savedPageLength ?? 100, // Define a quantidade de registros por página
        lengthMenu: [100, 200, 500], // Opções para o usuário selecionar o número de registros a exibir
        scrollY: 'calc(100vh - 303px)', // Define a altura para o grid ficar full screen
        scrollCollapse: false, // Permite que a tabela encolha quando houver menos dados
        select: true,
        processing: true,
        serverSide: true,

        stateSave: true,
        stateSaveCallback: function (settings, data) {
            localStorage.setItem(settings.sInstance+'_state', JSON.stringify(data));
        },
        stateLoadCallback: function (settings) {
            let state = JSON.parse(localStorage.getItem(settings.sInstance+'_state'));
            if(!state){
                state = new Object;
            }
            state.columns = JSON.parse($('#crud-datatables-columns').val());
            return state;
        },

        // fixedHeader: true,
        // responsive: true,
        autoWidth: false,

        ajax: {
            url: $('#crud-datatables-index-route').val(),
        },
        order: JSON.parse($('#crud-datatables-order').val()), // by pass - order by id default on backend
        buttons: [
            {
                extend: 'excelHtml5', // Extensão para exportar para Excel
                text: '<i class="fas fa-file-excel"></i>', // Texto do botão
                title: 'Relatório de Tabela', // Título do arquivo Excel
                className: 'dt-button text-sm px-3 py-2 bg-green-500 text-white rounded-sm hover:bg-green-600 mr-2'
            }
        ],
        columns: JSON.parse($('#crud-datatables-columns').val()),
        pagingType: "simple_numbers",  // Use "simple" para uma paginação mais compacta
        language: {
            "paginate": {
                "previous": '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"></path>',
                "next": '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path></svg>'
            },
            lengthMenu: "Exibir _MENU_ <span class=\"hidden sm:inline-block\">registros por página</span><span class=\"inline-block sm:hidden\">/pág.</span>", // Traduz o texto "Show _MENU_ entries"
            search: '', // Traduz o campo Search
            searchPlaceholder: "Digite para buscar...", // Adiciona o placeholder
            info: "Mostrando _START_ até _END_ de _TOTAL_ registros", // Traduz o texto "Showing ... to ... of ..."
            infoEmpty: "Nenhum registro", // Quando não há registros
            infoFiltered: "(filtrado de _MAX_ registros no total)" // Mensagem de filtro

        },
        initComplete: function (settings, json) {
            // Ajustar a aparência da paginação
            $(this).closest('.dt-container').find('.dt-paging').addClass('text-xs justify-self-end mt-2 mb-0');
            // $(this).closest('.dt-container').find('.dt-paging-button').addClass('');
            $(this).closest('.dt-container').find('.dt-info').addClass('hidden sm:inline-block text-sm justify-self-start float-left mt-4 mb-0');

            // Prevenir a ordenação enquanto redimensiona
            $('#'+tableName+'-table').closest('.dt-scroll').find('.dt-scroll-headInner>table th').on('click', function (e) {
                if (isResizing) {
                    e.stopPropagation();// Impede a ordenação se estiver redimensionando
                }
            });

            // scroll-x
            $('.dt-scroll-body').on('scroll', function () {
                // Sincroniza o scroll horizontal do cabeçalho com o corpo
                const scrollLeft = $(this).scrollLeft();
                $('.dt-scroll-headInner').scrollLeft(scrollLeft);
            });

            $('.dt-scroll-headInner .dataTable').width('');

            $('.crud-messages').each(function (i, e) {
                toastr[$(this).data('type')]($(this).text(), $(this).data('title'));
            });

        }

    });

    // table.state.clear();

    // Encontrar dinamicamente o índice da coluna `id`
    const idColumnIndex = table.settings().init().columns.findIndex(column => column.name === 'id');

    // Verificar se a coluna de ID foi encontrada
    if (idColumnIndex === -1) {
        console.error('Coluna com o atributo "id" não encontrada.');
        return;
    }

    /*
    $(window).on('resize',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
    });
    */

    //-- RESIZE COLUMNS CONTROL

    let startX, startWidth, newWidth;
    let isResizing = false; // Flag para rastrear se o redimensionamento está em andamento

    // Função para redimensionar as colunas
    const resizeColumns = () => {

        let tab_head = $('#'+tableName+'-table').closest('.dt-scroll').find('.dt-scroll-headInner>table');

        tab_head.find('th').append(
            $('<span>').addClass('dt-column-resizer')
        ).find('.dt-column-resizer').on('mousedown', function (e) {

            isResizing = true; // Inicia o redimensionamento
            startX = e.pageX;
            let startTableWidth = tab_head.width();

            const columnIndex = $(this).parent('th').index(); // Índice da coluna

            newWidth = startWidth = tab_head.find('colgroup').find('col').eq(columnIndex).width();

            // Ao arrastar o mouse, redimensiona a coluna
            $(document).on('mousemove', function (e) {
                const diff = e.pageX - startX; // Diferença do movimento
                newWidth = Math.round(startWidth + diff, 0);

                // console.log('pX: '+e.pageX+' sW: '+startWidth+ ' diff: '+diff+' nW: '+newWidth);

                // Redimensiona o cabeçalho
                tab_head.find('colgroup').find('col').eq(columnIndex).css('width', `${newWidth}px`);
                tab_head.css('width', `${startTableWidth + diff}px`);
            });

            // Ao soltar o mouse, para o redimensionamento
            $(document).on('mouseup', function (e) {

                $(document).off('mousemove');
                $(document).off('mouseup');

                // Redimensiona o corpo da tabela
                $('#'+tableName+'-table tbody tr').each(function () {
                    $(this).closest('table').find('colgroup').find('col').eq(columnIndex).css('width', newWidth + 'px');
                });
                $('#'+tableName+'-table').css('width', `${tab_head.width()}px`);

                //-- FAZ O AJUSTE FINO FINAL
                setTimeout(function () {
                    //table.columns.adjust();
                    isResizing = false; // Termina o redimensionamento
                }, 200);

            });
        });
    };

    // Iniciar a funcionalidade de redimensionamento
    resizeColumns();

    //-- PAGINATE - PAGE LENGTH COLTROL

    // Evento de mudança no seletor de quantidade de linhas
    $('select[name="'+tableName+'-table_length"]').on('change', function () {
        const newPageLength = $(this).val();
        localStorage.setItem(tableName+'_pageLength', newPageLength);
    });

    // Evento de mudança de página
    /*
    table.on('page', function () {
        const currentPage = table.page();
        localStorage.setItem('currentPage', currentPage);
        // console.log('armazenou pagina ' + currentPage);

    });
    */

    //-- SELECT LINE CONTROL

    window.selectedRow = null;

    // Captura o evento de seleção de linha
    table.on('select.dt', function (e, dt, type, indexes) {
        if (type === 'row') {
            // Obtém os dados da linha selecionada
            const rowData = table.row(indexes).data(); // Dados da linha selecionada
            window.selectedRow = rowData;
            localStorage.setItem(tableName+'_selectedRowId', rowData.id); // Salva o ID da linha no localStorage
        }        
    });

    table.on('draw', function () {

        // Carregar a seleção salva
        const savedRowId = localStorage.getItem(tableName+'_selectedRowId');
        if (savedRowId) {
            table.rows().every(function () {
                const data = this.data();
                if (String(data.id) === savedRowId) { // Compara pelo ID da linha
                    this.select(); // Seleciona a linha correspondente
                }
            });
        }

        if (table.rows({ selected: true }).count() == 0) {
            // Selecionar a primeira linha
            table.row(0).select();
        }

        //-- MOVE O SCROLL DO DATATABLES PARA MOSTRAR O REGISTRO SELECIONADO !!!! EXCELENT

        const rowOffset = $('#'+tableName+'-table tr.selected').offset().top; // Posição da linha no documento
        const tableOffset = $('#'+tableName+'-table').offset().top; // Posição da tabela

        // Calcula a rolagem necessária para levar a linha ao topo da tela
        const offset = rowOffset - tableOffset;

        // Faz a rolagem da tabela para o topo da linha selecionada
        $('#'+tableName+'-table').parent().scrollTop(offset);

    });

});

window.crudForm = function (model, action) {
    if (window.selectedRow) {
        window.location.href = route(model + '.' + action, window.selectedRow.id);
    }
}