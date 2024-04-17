<template>
    <div class="overflow-auto">
        <div class="container">
            <b-table
                class="w-50"
                id="my-table"
                :fields="fields"
                :items="items"
                :per-page="perPage"
                :current-page="currentPage"
                small
                @row-clicked="toggleRightBar(); changeRightBarContent(arguments[1])"
            >
                <template #cell(author)="data">
                    <a href="/home/" class="btn btn-light px-0 mx-0 w-100"> {{ data.value }} </a>
                </template>

                <template #cell(show_details)="row">
                    <b-button size="sm" @click="row.toggleDetails" class="mr-2">
                        {{ row.detailsShowing ? 'Hide' : 'Show'}} Details
                    </b-button>
                </template>

                <template #row-details="row">
                    <b-card>
                        <b-row class="mb-2">
                            <b-col sm="3" class="text-sm-right"><b>Cooking time:</b></b-col>
                            <b-col>{{ row.item.cookingTime }}</b-col>
                        </b-row>

                        <b-row class="mb-2">
                            <b-col sm="3" class="text-sm-right"><b>Description:</b></b-col>
                            <b-col>{{ row.item.description }}</b-col>
                        </b-row>

                        <b-row class="mb-2">
                            <b-col sm="3" class="text-sm-right"><b>Description:</b></b-col>
                            <b-col>{{ row.item.preparationTime }}</b-col>
                        </b-row>

<!--                        <b-row class="mb-2">-->
<!--                            <b-col sm="3" class="text-sm-right"><b>Description:</b></b-col>-->
<!--                            <b-col>{{ row.item.description }}</b-col>-->
<!--                        </b-row>-->

                        <b-button size="sm" @click="row.toggleDetails">Hide Details</b-button>
                    </b-card>
                </template>
            </b-table>

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
// eslint-disable-next-line import/no-extraneous-dependencies
import axios from 'axios';

export default {
    data() {
        return {
            perPage: 20,
            currentPage: 1,
            items: [],
            fields: [
                'name',
                'author',
                'numberOfIngredients',
                'skill',
                'show_details',
            ],
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
            this.items = array.map((relation) => {
                console.log(relation);
                const { nodes } = relation;

                const { name: author } = nodes[0].properties;

                const { name, skillLevel: skill } = nodes[1].properties;

                const { ingredients: ingredientsObjectArray } = relation;

                const numberOfIngredients = ingredientsObjectArray.length;

                const { cookingTime, description, preparationTime } = nodes[1].properties;
                return {
                    name, author, ingredientsObjectArray, numberOfIngredients, skill, cookingTime, description, preparationTime,
                };
            });
        },
        fetchRecipes() {
            axios
                .get('/api/recipes')
                .then((response) => {
                    this.processRecipesArray(response.data);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchIngredients(recipeId) {
            axios
                .get(`api/ingredients/${recipeId}`)
                .then((response) => {
                    console.log(response.data);
                })
                .catch((error) => {
                    console.log('Error fetching ingredients for recipeId'.recipeId.error);
                });
        },
    },
    mounted() {
        this.fetchRecipes();
    },
};
</script>
