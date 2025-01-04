import 'datatables.net';
import 'datatables.net-dt/css/dataTables.dataTables.min.css'; // CSS do DataTables
import 'datatables.net-buttons';
import 'datatables.net-select';           // Plugin de seleção para DataTables
import 'datatables.net-buttons/js/buttons.html5.min';
import '/resources/css/core/crud-index.css' // deixa as customizacoes de css por ultimo dos imports

import JSZip from 'jszip'; // Para exportação para Excel
import pdfMake from 'pdfmake/build/pdfmake'; // Para exportação para PDF

// Tornando as dependências globais
window.JSZip = JSZip;
window.pdfMake = pdfMake;

//-- crud datatables initaialization
$(document).ready(function () {
    var table = $('#crud-table').DataTable({
        dom: 'Bflrtip', // Adiciona os botões de exportação
        buttons: [
            {
                extend: 'excelHtml5', // Extensão para exportar para Excel
                text: '<i class="fas fa-file-excel"></i>', // Texto do botão
                title: 'Relatório de Tabela', // Título do arquivo Excel
                className: 'dt-button text-sm px-3 py-2 bg-green-500 text-white rounded-sm hover:bg-green-600 mr-2'
            }
        ],
        pageLength: 100, // Define a quantidade de registros por página
        lengthMenu: [100, 200, 500], // Opções para o usuário selecionar o número de registros a exibir
        scrollY: 'calc(100vh - 303px)', // Define a altura para o grid ficar full screen
        scrollCollapse: true, // Permite que a tabela encolha quando houver menos dados
        select: true,
        processing: true,
        serverSide: true,
        ajax: $('#crud-datatables-index-route').val(),
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', email: 'name' },
        ],
        order: [[0, "asc"]], // Ordena pelo primeiro campo

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

            $(this).closest('.dt-container').find('.dt-buttons').addClass('inline-block');
            $(this).closest('.dt-container').find('.dt-search').addClass('inline-block');
            $(this).closest('.dt-container').find('.dt-length').addClass('inline-block float-right');

            // Caso queira evitar que quebre linha, forçando o layout em uma linha
            // $('dt-container').addClass('flex flex-nowrap items-center justify-between');

        }

    });

    // Após atualizar a tabela via AJAX, ajusta as colunas
    $('#crud-table').on('init.dt', function () {
        table.columns.adjust().draw();
    });

    //-- CONTROLE DA SELECAO DA LINHA

    let selectedRow = null;

    // Detectar clique em uma linha
    $('#crud-table tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            selectedRow = null;
            //$('.crud-depends-on-id-btn').prop('disabled', true);
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            selectedRow = table.row(this).data();
            //$('.crud-depends-on-id-btn').prop('disabled', false);
        }
    });

    // Manipular clique no botão de visualizar e editar
    $('#crud-show-btn,#crud-edit-btn').on('click', function (e) {
        if (selectedRow) {
            window.location.href = this.href.replace(':id', selectedRow.id);
            e.preventDefault();
        }
    });

});
