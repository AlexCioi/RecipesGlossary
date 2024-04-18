<script>
import pagination from '../components/paginationComponent.vue';
import pagination2 from '../components/paginationComponent2.vue';

export default {
    name: 'recipesPage',
    components: {
        pagination,
        pagination2,
    },
    data() {
        return {
            recipes: [],
        };
    },
    methods: {

    },
    mounted() {
    },
};
</script>

<template>
    <div>
<!--        <pagination/>-->
        <pagination2/>
    </div>
</template>

<style scoped>

</style>
