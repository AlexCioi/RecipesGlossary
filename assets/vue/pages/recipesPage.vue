<script>
import pagination from '../components/paginationComponent.vue';

export default {
    name: 'recipesPage',
    components: {
        pagination,
    },
    data() {
        return {
            recipes: [],
        };
    },
    methods: {

    },
    mounted() {
    }
}
</script>

<template>
    <div>
        <pagination/>
    </div>
</template>

<style scoped>

</style>
