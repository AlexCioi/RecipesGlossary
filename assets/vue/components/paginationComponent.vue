<template>
    <div class="overflow-auto">
        <div class="container">
            <b-table
                class="w-50"
                id="my-table"
                :items="items"
                :per-page="perPage"
                :current-page="currentPage"
                small
                @row-clicked="toggleRightBar(); changeRightBarContent(arguments[1])"
            ></b-table>

            <b-pagination
                v-model="currentPage"
                :total-rows="rows"
                :per-page="perPage"
                aria-controls="my-table"
            ></b-pagination>

            <p class="mt-3">Current Page: {{ currentPage }}</p>
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
        toggleRightBar() {
            console.log('hello asdf');
        },
        changeRightBarContent(res) {
            console.log(res);
        },
        processRecipesArray(array) {
            // Use map to iterate over the array and transform each recipe object
            const computedRecipes = array.map((relation) => {
                //console.log(relation);
                // Extract properties from the recipe object
                const name = relation.nodes[1].properties.name;
                const author = relation.nodes[0].properties.name;
                const skill = relation.nodes[1].properties.skillLevel;

                // Create a new item object with the extracted properties
                return { name , author, skill };
            });

            console.log(computedRecipes);

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
                });
        },
    },
    mounted() {
        this.fetchRecipes();
    },
};
</script>
