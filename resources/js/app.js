/**
 * Fichier principal JavaScript de l'application
 * 
 * Ce fichier initialise l'application JavaScript côté client.
 * Il importe les dépendances nécessaires, configure Vue.js et instancie l'application.
 * Les composants Vue, les directives et autres configurations sont définies ici.
 */

// Importation des dépendances requises pour l'application
import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import { createApp } from 'vue';

// Exporter Vue globalement
window.Vue = { createApp };
window.createApp = createApp;

// Bootstrap initialization for tooltips and popovers
document.addEventListener('DOMContentLoaded', () => {
    // Initialize all tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize all popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});
