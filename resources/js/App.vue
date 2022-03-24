<template>
    <div>
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <div class="navbar-brand col-md-3 col-lg-2 me-0 px-3">Ready Works</div>
            <div class="navbar-brand col-md-3 col-lg-2 me-0 px-3">
                <router-link to="/chart" class="nav-item nav-link">Chart</router-link>
            </div>
            <div class="navbar-brand col-md-3 col-lg-2 me-0 px-3">
                <router-link to="/table" class="nav-item nav-link">Table</router-link>
            </div>
        </header>

        <div class="container-fluid">
            <div class="row">

                <main class="col-md-12 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">{{ $route.name }}</h1>
                    </div>
                    <router-view></router-view>
                </main>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style scoped>

</style>
