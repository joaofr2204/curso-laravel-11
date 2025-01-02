import 'datatables.net';
import 'datatables.net-dt/css/dataTables.dataTables.min.css'; // CSS do DataTables
import 'datatables.net-buttons';
import 'datatables.net-select';           // Plugin de seleção para DataTables
import 'datatables.net-buttons/js/buttons.html5.min';

import JSZip from 'jszip'; // Para exportação para Excel
import pdfMake from 'pdfmake/build/pdfmake'; // Para exportação para PDF

// Tornando as dependências globais
window.JSZip = JSZip;
window.pdfMake = pdfMake;

