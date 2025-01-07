import 'datatables.net';
import 'datatables.net-dt/css/dataTables.dataTables.min.css'; // CSS do DataTables
import 'datatables.net-buttons';
import 'datatables.net-select';           // Plugin de seleção para DataTables
import 'datatables.net-buttons/js/buttons.html5.min';
import '/resources/css/core/crud-index.css' // deixa as customizacoes de css por ultimo dos imports

import JSZip from 'jszip'; // Para exportação para Excel
import pdfMake from 'pdfmake/build/pdfmake'; // Para exportação para PDF
import { route } from 'ziggy-js';
// import Swal from 'sweetalert2';

// Tornando as dependências globais
window.JSZip = JSZip;
window.pdfMake = pdfMake;

//-- crud datatables initaialization
$(document).ready(function () {

    var table = $('#crud-table').DataTable({
        dom: 'Bflrtip', // Adiciona os botões de exportação
        pageLength: 100, // Define a quantidade de registros por página
        lengthMenu: [100, 200, 500], // Opções para o usuário selecionar o número de registros a exibir
        scrollY: 'calc(100vh - 303px)', // Define a altura para o grid ficar full screen
        scrollCollapse: true, // Permite que a tabela encolha quando houver menos dados
        select: true,
        processing: true,
        serverSide: true,

        responsive: true,
        autoWidth: false,

        ajax: $('#crud-datatables-index-route').val(),
        order: [[0, "asc"]], // Ordena pelo primeiro campo
        buttons: [
            {
                extend: 'excelHtml5', // Extensão para exportar para Excel
                text: '<i class="fas fa-file-excel"></i>', // Texto do botão
                title: 'Relatório de Tabela', // Título do arquivo Excel
                className: 'dt-button text-sm px-3 py-2 bg-green-500 text-white rounded-sm hover:bg-green-600 mr-2'
            }
        ],
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', email: 'name' },
        ],
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
            // Prevenir a ordenação enquanto redimensiona

            $('#crud-table').closest('.dt-scroll').find('.dt-scroll-headInner>table th').on('click', function (e) {
                if (isResizing) {
                    e.stopPropagation();// Impede a ordenação se estiver redimensionando
                }
            });


            $('.dt-scroll-body').on('scroll', function () {
                // Sincroniza o scroll horizontal do cabeçalho com o corpo
                const scrollLeft = $(this).scrollLeft();
                $('.dt-scroll-headInner').scrollLeft(scrollLeft);
            });
            
        }

    });

    // Após atualizar a tabela via AJAX, ajusta as colunas
    $('#crud-table').on('init.dt', function () {
        table.columns.adjust().draw();
    });

    $(window).on('resize',function(){
        table.columns.adjust().draw();
    });

    //-- RESIZE COLUMNS CONTROL

    let startX, startWidth, newWidth;
    let isResizing = false; // Flag para rastrear se o redimensionamento está em andamento

    // Função para redimensionar as colunas
    const resizeColumns = () => {

        let tab_head = $('#crud-table').closest('.dt-scroll').find('.dt-scroll-headInner>table');

        tab_head.find('th').append(
            $('<div>').addClass('dt-column-resizer')
        ).find('.dt-column-resizer').on('mousedown', function (e) {

            isResizing = true; // Inicia o redimensionamento
            startX = e.pageX;
            let startTableWidth = tab_head.width();

            const columnIndex = $(this).parent('th').index(); // Índice da coluna

            startWidth = tab_head.find('colgroup').find('col').eq(columnIndex).width();
            
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
                $('#crud-table tbody tr').each(function () {
                    $(this).closest('table').find('colgroup').find('col').eq(columnIndex).css('width', newWidth + 'px');
                });
                $('#crud-table').css('width',`${tab_head.width()}px`);

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







    //-- SELECT LINE CONTROL

    window.selectedRow = null;

    // Detectar clique em uma linha
    $('#crud-table tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            window.selectedRow = null;
            $('.crud-depends-on-id-btn').prop('disabled', true);
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            window.selectedRow = table.row(this).data();
            $('.crud-depends-on-id-btn').prop('disabled', false);
        }
    });

});

window.crudForm = function (action) {
    if (window.selectedRow) {
        window.location.href = route('users.' + action, window.selectedRow.id);
    }
}