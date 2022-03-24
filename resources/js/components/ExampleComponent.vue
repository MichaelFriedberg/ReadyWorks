<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Ready works final assignment</div>

                    <div class="card-body">
                        Canidate: Michael Friedberg.
                        Please use naviatoin above or click on
                        <router-link to="/chart" >chart</router-link> or
                        <router-link to="/table">table</router-link>.
                    </div>
                </div>
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
