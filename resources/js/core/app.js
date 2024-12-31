import './bootstrap';
import '@fortawesome/fontawesome-free/js/all.js';

import Alpine from 'alpinejs';

import $ from 'jquery';
window.$ = window.jQuery = $;  // Expondo globalmente

window.Alpine = Alpine;

Alpine.start();

