<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import "flowbite";
import { vLazyImg } from 'maz-ui'

</script>

<template>
    <Head title="Dashboard">
    
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>
        <div class="text-center">
        </div>
        
            <div class="container">
                <img
                    class="flex flex-center rounded"
                    v-lazy-img="'https://upload.wikimedia.org/wikipedia/commons/9/93/Map_of_the_departments_of_the_K%C3%A9dougou_region_of_Senegal.png'"
                />
                <!--      
                     <h1 class="text-balance text-5xl font-semibold tracking-tight text-gray-900 sm:text-7xl">Data to enrich your online business</h1>            
                -->
            </div>
               
       
    </AuthenticatedLayout>
</template>
