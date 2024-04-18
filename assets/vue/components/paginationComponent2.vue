<template>
    <div>
        <table class="table">

        </table>

        <template>
            <div class="overflow-auto">
                <b-pagination-nav :link-gen="linkGen" :number-of-pages="numberOfPages" use-router></b-pagination-nav>
            </div>
        </template>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'paginationComponent2',
    data() {
        return {
            items: [],
            numberOfPages: 1,
        };
    },
    methods: {
        linkGen(pageNum) {
            return `/home/${pageNum}`;
        },
        fetchPageData() {
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
        fetchNumberOfPages() {
            axios
                .get('/api/recipes/numberOfPages')
                .then((response) => {
                    this.numberOfPages = response.data;
                    console.log(this.numberOfPages);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    mounted() {
        this.fetchNumberOfPages();
    },
};
</script>

<style scoped>

</style>
