import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import { createApp } from 'vue';

const app = createApp({
    data() {
        return {
            query: '',
            category: '',
            objects: [],  // Contiendra les objets récupérés
            totalObjects: 0,  // Total des objets pour gestion de la pagination
            currentPage: 1,  // Page actuelle de la pagination
            lastPage: 1,  // Dernière page
        };
    },
    methods: {
        // Fonction de récupération des objets
        async fetchObjects() {
            const url = `/api/objects?query=${this.query}&category=${this.category}&page=${this.currentPage}`;
            console.log('URL de l\'API:', url);  // Affichez l'URL dans la console pour vérifier

            try {
                const response = await fetch(url);
                const data = await response.json();
                console.log('Données récupérées :', data);  // Affichez les données dans la console

                this.objects = data.data;  // Assigner les objets à partir de `data`
                this.totalObjects = data.total;  // Nombre total d'objets
                this.lastPage = data.last_page;  // Dernière page de la pagination
            } catch (error) {
                console.error('Erreur lors de la récupération des objets:', error);
            }
        },

        // Fonction pour naviguer vers la page suivante
        nextPage() {
            if (this.currentPage < this.lastPage) {
                this.currentPage += 1;
                this.fetchObjects();
            }
        },

        // Fonction pour naviguer vers la page précédente
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage -= 1;
                this.fetchObjects();
            }
        }
    },
    mounted() {
        this.fetchObjects();  // Charger les objets au démarrage
    }
});

// Monter l'app Vue
app.mount('#app');
