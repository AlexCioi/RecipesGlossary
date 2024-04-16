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
            // {
            //     +id: 0
            //     +labels: array:1 [
            //         0 => "Recipe"
            //     ]
            //     +properties: array:6 [
            //         "preparationTime" => 900
            //         "name" => "Tomato & mozzarella couscous salad"
            //         "description" => "Keep some couscous in your cupboard as a stand-by, it's ready in minutes"
            //         "id" => "101233"
            //         "skillLevel" => "Easy"
            //         "cookingTime" => 0
            //     ]
            //     +element_id: "4:c9881f6e-7753-462b-a5d2-aded8bdf85c1:0"
            // }

            // Use map to iterate over the array and transform each recipe object
            const computedRecipes = array.map((recipe, ) => {
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
                });
        },
    },
    mounted() {
        this.fetchRecipes();
    },
};
</script>
