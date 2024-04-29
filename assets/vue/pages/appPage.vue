<script>
import recipesTable from '../components/recipesTable.vue';

export default {
    name: 'AppPage',
    components: {
        recipesTable,
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
    <div class="container-fluid">
        <recipesTable/>
        <footer class="bg-light">
            <div class="footer-content text-center mb-2">
                <div class="copyright">
                    <!-- Copyright Information -->
                    <p>© 2024 Alexandru Cioi</p>
                </div>

                <div class="social-media container-fluid">
                    <div class="mx-auto d-flex flex-row justify-content-center">
                        <p class="me-2"> Find me on: </p>
                        <!-- LinkedIn Icon and Link -->
                        <a href="https://www.linkedin.com/in/alexandru-cioi-886447277/" class="me-3">
                            <img src="../../../public/images/linkedin.png" alt="LinkedIn">
                        </a>
                        <a href="https://github.com/AlexCioi" class="me-3">
                            <img src="../../../public/images/github.png" alt="Github">
                        </a>
                        <a href="https://www.facebook.com/profile.php?id=100006881237060">
                            <img src="../../../public/images/facebook.png" alt="Facebook">
                        </a>
                        <!-- Other Social Media Icons/Links if applicable -->
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.social-media img {
    width: 25px;
    height: auto;
}
</style>
