<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Donnee"/>

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Base de donnée
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    
                    <div class="p-4 border-b bg-gray-100">
                        <h1 class="text-lg font-semibold">Base de donnée</h1>
                    </div>
                                
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Product name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Color
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Category
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Accessories
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Available
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Price
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Weight
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        Apple MacBook Pro 17"
                                    </th>
                                    <td class="px-6 py-4">
                                        Silver
                                    </td>
                                    <td class="px-6 py-4">
                                        Laptop
                                    </td>
                                    <td class="px-6 py-4">
                                        Yes
                                    </td>
                                    <td class="px-6 py-4">
                                        Yes
                                    </td>
                                    <td class="px-6 py-4">
                                        $2999
                                    </td>
                                    <td class="px-6 py-4">
                                        3.0 lb.
                                    </td>
                                    <td class="flex items-center px-6 py-4">
                                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Modifier</a>
                                        <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3">Effacer</a>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        Apple MacBook Pro 17"
                                    </th>
                                    <td class="px-6 py-4">
                                        Silver
                                    </td>
                                    <td class="px-6 py-4">
                                        Laptop
                                    </td>
                                    <td class="px-6 py-4">
                                        Yes
                                    </td>
                                    <td class="px-6 py-4">
                                        Yes
                                    </td>
                                    <td class="px-6 py-4">
                                        $2999
                                    </td>
                                    <td class="px-6 py-4">
                                        3.0 lb.
                                    </td>
                                    <td class="flex items-center px-6 py-4">
                                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Modifier</a>
                                        <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3">Effacer</a>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        Apple MacBook Pro 17"
                                    </th>
                                    <td class="px-6 py-4">
                                        Silver
                                    </td>
                                    <td class="px-6 py-4">
                                        Laptop
                                    </td>
                                    <td class="px-6 py-4">
                                        Yes
                                    </td>
                                    <td class="px-6 py-4">
                                        Yes
                                    </td>
                                    <td class="px-6 py-4">
                                        $2999
                                    </td>
                                    <td class="px-6 py-4">
                                        3.0 lb.
                                    </td>
                                    <td class="flex items-center px-6 py-4">
                                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Modifier</a>
                                        <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3">Effacer</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
