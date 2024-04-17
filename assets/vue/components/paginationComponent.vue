<template>
    <div class="overflow-auto">
        <div class="container">
            <template>
                <b-container fluid>
                    <b-row>
                        <b-col lg="6" class="my-1 px-0">
                            <b-form-group
                                label="Search"
                                label-for="filter-input"
                                label-cols-sm="3"
                                label-align-sm="right"
                                label-size="sm"
                                class="mb-0"
                            >
                                <b-input-group size="sm">
                                    <b-form-input
                                        id="filter-input"
                                        v-model="filter"
                                        type="search"
                                        placeholder="Type to search"
                                    ></b-form-input>

                                    <b-input-group-append>
                                        <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                                    </b-input-group-append>
                                </b-input-group>
                            </b-form-group>
                        </b-col>
                    </b-row>
                </b-container>
            </template>
            <b-table
                class=""
                id="my-table"
                :busy="isBusy"
                :fields="fields"
                :items="items"
                :per-page="perPage"
                :current-page="currentPage"
                bordered="bordered"
                hover
                @row-clicked="toggleRightBar(); changeRightBarContent(arguments[1])"
                @filtered="onFiltered"
                :filter="filter"
                :filter-included-fields="filterOn"
            >
                <template #table-busy>
                    <div class="text-center my-2">
                        <b-spinner class="align-middle"></b-spinner>
                        <strong>Loading...</strong>
                    </div>
                </template>

                <template #cell(authorName)="data">
                    <a href="/home/" class="btn btn-light w-100"> {{ data.value }} </a>
                </template>

                <template #cell(show_details)="row">
                    <b-button size="sm" @click="row.toggleDetails" class="mr-2 w-100">
                        {{ row.detailsShowing ? 'Hide' : 'Show'}} Details
                    </b-button>
                </template>

                <template #row-details="row">
                    <b-card>
                        <b-row class="mb-3">
                            <b-col sm="3" class="text-sm-right"><b>Cooking time:</b></b-col>
                            <b-col>{{ row.item.cookingTime }}</b-col>
                        </b-row>

                        <b-row class="mb-3">
                            <b-col sm="3" class="text-sm-right"><b>Description:</b></b-col>
                            <b-col>{{ row.item.description }}</b-col>
                        </b-row>

                        <b-row class="mb-3">
                            <b-col sm="3" class="text-sm-right"><b>Description:</b></b-col>
                            <b-col>{{ row.item.preparationTime }}</b-col>
                        </b-row>

                        <b-row class="mb-3">
                            <b-col sm="3" class="text-sm-right"><b>Ingredients:</b></b-col>
                            <b-col>
                                <ul>
                                    <li v-for="ingredient in row.item.ingredientList" :key="ingredient.id">{{ ingredient.name }}</li>
                                </ul>
                            </b-col>
                        </b-row>

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
            filter: null,
            filterOn: [],
            isBusy: false,
            perPage: 20,
            currentPage: 1,
            items: [],
            author: {},
            recipe: {},
            ingredients: [],
            fields: [
                'recipe',
                'authorName',
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
        toggleBusy() {
            this.isBusy = !this.isBusy;
        },
        toggleRightBar() {
            console.log('hello asdf');
        },
        changeRightBarContent(res) {
            console.log(res);
        },
        processDataArray(array) {
            this.items = array.map((node) => {
                const {
                    name: recipe, cookingTime, description, preparationTime, skillLevel: skill,
                } = node[1].properties;

                const { id: authorId } = node[0];

                const { name: authorName } = node[0].properties;

                const numberOfIngredients = node[2].length;

                const ingredientList = node[2].map((ingredient) => {
                    const { name } = ingredient.properties;

                    const { id } = ingredient;

                    return { name, id };
                });

                return {
                    recipe, cookingTime, description, preparationTime, skill, authorName, authorId, numberOfIngredients, ingredientList,
                };
            });

            console.log(this.items);
        },
        fetchRecipes() {
            this.toggleBusy();
            axios
                .get('/api/recipes')
                .then((response) => {
                    this.processDataArray(response.data);
                    this.toggleBusy();
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
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
    },
    mounted() {
        this.fetchRecipes();
    },
};
</script>

<style>
table.b-table[aria-busy='true'] {
    opacity: 0.6;
}
</style>
