<template>
    <div class="overflow-auto">
        <div class="container">
                <b-table
                    id="my-table"
                    :items="items"
                    :per-page="perPage"
                    :current-page="currentPage"
                    small
                ></b-table>

                <div class="mx-auto w-25">
                    <b-pagination
                        v-model="currentPage"
                        :total-rows="rows"
                        :per-page="perPage"
                        aria-controls="my-table"
                    ></b-pagination>

                    <p class="mt-3">Current Page: {{ currentPage }}</p>
                </div>
        </div>

    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            perPage: 20,
            currentPage: 1,
            items: [], //{ id: 1, name: 'Fred'},
        };
    },
    computed: {
        rows() {
            return this.items.length;
        },
    },
    methods: {
        processRecipesArray(array) {
            // Use map to iterate over the array and transform each recipe object
            const computedRecipes = array.map((recipe, index) => {
                // Extract properties from the recipe object
                const { name } = recipe.properties;

                // Create a new item object with the extracted properties
                return { name };
            });

            // Assign the computedRecipes array to this.items
            this.items = computedRecipes;
        },
        fetchRecipes() {
            axios
                .get('/api/recipes')
                .then((response) => {
                    //this.items = response.data;
                    this.processRecipesArray(response.data);
                    console.log(this.items);
                })
                .catch((error) => {
                    console.log('Error fetching recipes'.error);
                })
        }
    },
    mounted() {
        this.fetchRecipes();
    }
};
</script>
