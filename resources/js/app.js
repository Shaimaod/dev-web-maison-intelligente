import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import { createApp } from 'vue';

// Créer l'application Vue
const app = createApp({
    data() {
        return {
            query: '',
            category: '',
            objects: [],
        };
    },
    methods: {
        fetchObjects() {
            let url = `/api/objects?query=${this.query}&category=${this.category}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    this.objects = data;
                });
        }
    },
    mounted() {
        this.fetchObjects();
    }
});

// Enregistrement des composants personnalisés si besoin
import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

// Monter l'app Vue
app.mount('#app');
