<script setup>
import { ref } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
// import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';

const showingNavigationDropdown = ref(false);

// Récupérer l'utilisateur depuis les props Inertia
const page = usePage();
const user = page.props.auth?.user;

</script>

<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <nav class="w-64 h-screen bg-white border-r border-gray-200 fixed flex flex-col p-4">
            <!-- Logo -->
            <div class="flex items-center justify-center mb-6">
                <Link :href="route('dashboard')">
                    <!-- <logo class="h-10 w-auto fill-current text-gray-800" /> -->
                    <img src="/logo.jpg" alt="Logo" class="h-100 w-auto">
                </Link>
            </div>

            <!-- Navigation Links -->
            <div class="flex flex-col space-y-4">
                <NavLink :href="route('dashboard')" :active="route().current('dashboard')" 
                    class="hover:bg-primary-dark p-2 rounded font-bold text-lg border-l-5 flex items-center" :class="{'border-primary': route().current('dashboard')}">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-7 9 7v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V10z" />
                    </svg>
                    Dashboard
                </NavLink>
                <NavLink :href="route('secretariat.create')" :active="route().current('secretariat.create')" 
                    class="hover:bg-primary-dark p-2 rounded font-bold text-lg border-l-5 flex items-center" :class="{'border-primary': route().current('secretariat.create')}">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 14h10m-5-5a3 3 0 0 1-6 0m12 5V10a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8" />
                    </svg>
                    Secretariat
                </NavLink>
                <NavLink :href="route('geometre.create')" :active="route().current('geometre.create')" 
                    class="hover:bg-primary-dark p-2 rounded font-bold text-lg border-l-5 flex items-center" :class="{'border-primary': route().current('geometre.create')}">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18m9-9H3" />
                    </svg>
                    Enquête Cadastrale
                </NavLink>
                <NavLink :href="route('matriceCadastrale.create')" :active="route().current('matriceCadastrale.create')" 
                    class="hover:bg-primary-dark p-2 rounded font-bold text-lg border-l-5 flex items-center" :class="{'border-primary': route().current('matriceCadastrale.create')}">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                    Matrice Cadastrale
                </NavLink>
                <NavLink :href="route('donnee.create')" :active="route().current('donnee.create')" 
                    class="hover:bg-primary-dark p-2 rounded font-bold text-lg border-l-5 flex items-center" :class="{'border-primary': route().current('donnee.create')}">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4z" />
                    </svg>
                    Base de Données
                </NavLink>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="ml-64 flex-1">
            <header class="bg-white shadow flex justify-between items-center p-4 relative">
                <!-- Titre ou Header (si présent) -->
                <div>
                    <slot name="header" />
                </div>

                <!-- User Dropdown (En haut à droite) -->
                <div class="relative">
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="flex items-center space-x-2 px-4 py-2 bg-primary rounded-md hover:bg-primary-dark">
                        <span class="text-white font-semibold">{{ user?.name }}</span>
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div v-if="showingNavigationDropdown" class="absolute right-0 mt-2 w-48 bg-white border border-primary-dark rounded shadow-md z-10">
                        <div class="px-4 py-2 text-gray-800">{{ user?.name }}</div>
                        <hr>
                        <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-4 py-2 text-primary hover:bg-primary-dark">
                            Déconnexion
                        </Link>
                    </div>
                </div>
            </header>

            <!-- Contenu de la page -->
            <main class="p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
