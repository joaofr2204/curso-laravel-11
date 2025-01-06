import './bootstrap';
import '@fortawesome/fontawesome-free/js/all.js';

import $ from 'jquery';
window.$ = window.jQuery = $;  // Expondo globalmente

import Swal from 'sweetalert2';
window.Swal = Swal;

import Alpine from 'alpinejs';
window.Alpine = Alpine;

Alpine.start();

