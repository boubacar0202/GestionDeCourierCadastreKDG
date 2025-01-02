<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import "flowbite";

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
      
            <img class="h-auto max-w-full" src="" alt="image description">       

    </AuthenticatedLayout>
</template>
