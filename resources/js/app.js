import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import { createApp } from 'vue';

// Exporter Vue globalement
window.Vue = { createApp };
window.createApp = createApp;

// Importer les composants et les directives si nécessaire
// import ExampleComponent from './components/ExampleComponent.vue';

// Supprimer la création et le montage de l'application Vue.js
// const app = createApp({...});
// app.mount('#app');
