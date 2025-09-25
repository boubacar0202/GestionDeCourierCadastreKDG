<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { usePage, Link } from '@inertiajs/vue3'; 
import NavLink from '@/Components/NavLink.vue';
import { useToast } from 'maz-ui';
import axios from 'axios';

// --- USER & PAGE ---
const page = usePage();
const user = page.props.auth?.user;

// --- MENU ---
const isMenuOpen = ref(false);
const isDesktop = ref(false);
const showingNavigationDropdown = ref(false);
const unreadTotal = ref(0);
  
// --- TOAST ---
const toast = useToast();
onMounted(() => {
  if (page.props.flash.success) {
    toast.success(page.props.flash.success);
  }
});

// --- SCREEN & MENU HANDLING ---
function checkScreenSize() {
  isDesktop.value = window.innerWidth >= 768;
}

function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value;
}

onMounted(() => {
  checkScreenSize();
  window.addEventListener('resize', checkScreenSize);

  if (window.__menuOpenTemp === true) {
    isMenuOpen.value = true;
  }
  window.__menuOpenTemp = false;
});

onUnmounted(() => {
  window.removeEventListener('resize', checkScreenSize);
});

// --- PERSIST MENU STATE ---
watch(isMenuOpen, (val) => {
  window.__menuOpenTemp = val;
});


// Charger le total des messages non lus au montage
onMounted(async () => {
  try {
    const res = await axios.get("/messages/unread-total");
    unreadTotal.value = res.data.total;
  } catch (e) {
    console.error("Erreur fetch unread:", e);
  }
});

// --- MENU ITEMS ---
// const menuItems = [
//   { label: 'Dashboard', href: route('dashboard'), active: 'dashboard' },
//   { label: 'Enregistrement Arrivées', href: route('arrivee.create'), active: 'arrivee.create' },
//   { label: 'Enregistrement Départs', href: route('depart.create'), active: 'depart.create' },
//   { label: 'Instances', href: route('instance.create'), active: 'instance.create' }, 
//   { label: 'Liste des Arrivées', href: route('instancearrivee.create'), active: 'instancearrivee.create' }, 
//   { label: 'Liste des Départs', href: route('instancedepart.create'), active: 'instancedepart.create' }, 
// ];

</script>


<template>
 
    <div class="flex min-h-screen bg-primary-layout overflow-x-hidden">
        <!-- Sidebar -->
        <nav class="w-64 h-screen bg-white border-r border-primary-only fixed flex flex-col p-4">
            <!-- Logo -->
            <div class="flex items-center justify-center mb-6">
                <Link :href="route('dashboard')">
                    <!-- <logo class="h-10 w-auto fill-current text-gray-800" /> -->
                    <img src="/logo.jpg" alt="Logo" class="h-100 w-auto">
                </Link>
            </div>

            <!-- Navigation Links -->
            <div class="flex flex-col space-y-4">
                <!-- Bouton d'ouverture/fermeture -->
                <button @click="toggleMenu" class="p-2 bg-primary text-white rounded shadow-md">
                    ☰ Menu
                </button>
                <transition name="slide">
                    
                        <nav v-if="isMenuOpen || !isDesktop" class="flex flex-col space-y-4" >
                            <!-- Navigation Links -->
                            <div class="flex flex-col space-y-4">
                              
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')" 
                                    class="hover:bg-primary-menu hover:border hover:text-white hover:font-bold hover:text-1xl p-3 rounded font-bold text-primary-txt border border-l-8 flex items-center" 
                                    :class="{'border-primary-menu': route().current('dashboard')}">
                                    <svg class="w-6 h-5 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 10l9-7 9 7v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V10z" />
                                    </svg>
                                    Acceuil
                                </NavLink>
                                <NavLink :href="route('arrivee.create')" :active="route().current('arrivee.create')" 
                                    class="hover:bg-primary-menu hover:border hover:text-white hover:font-bold hover:text-1xl p-3 rounded font-bold text-primary-txt border border-l-8 flex items-center" 
                                    :class="{'border-primary-menu': route().current('arrivee.create')}">
                                    <svg class="w-6 h-5 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7V3m8 4V3m-9 14h10m-5-5a3 3 0 0 1-6 0m12 5V10a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8" />
                                    </svg>
                                    Enregistrement Arrivées
                                </NavLink>
                                <NavLink :href="route('depart.create')" :active="route().current('depart.create')" 
                                    class="hover:bg-primary-menu hover:border hover:text-white hover:font-bold hover:text-1xl p-3 rounded font-bold text-primary-txt border border-l-8 flex items-center" 
                                    :class="{'border-primary-menu': route().current('depart.create')}">
                                    <svg class="w-6 h-5 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 3v18m9-9H3" />
                                    </svg>
                                    Enregistrement Départs
                                </NavLink> 
                                <NavLink :href="route('instance.create')" :active="route().current('instance.create')" 
                                    class="hover:bg-primary-menu hover:border hover:text-white hover:font-bold hover:text-1xl p-3 rounded font-bold text-primary-txt border border-l-8 flex items-center" 
                                    :class="{'border-primary-menu': route().current('instance.create')}">
                                    <svg class="w-5 h-6 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#5f2e01">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16m-7 6h7" />
                                    </svg>
                                    Instances 
                                </NavLink>
                                <NavLink :href="route('instancearrivee.create')" :active="route().current('instancearrivee.create')" 
                                    class="hover:bg-primary-menu hover:border hover:text-white hover:font-bold hover:text-1xl p-3 rounded font-bold text-primary-txt border border-l-8 flex items-center" 
                                    :class="{'border-primary-menu': route().current('instancearrivee.create')}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-txt mr-2" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m0 8a2 2 0 01-2 2H5a2 2 0 01-2-2V8m16 0V6a2 2 0 00-2-2H5a2 2 0 00-2 2v2" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 12v6m0 0l-2-2m2 2l2-2" />
                                    </svg>
                                    Liste des Arrivées
                                </NavLink>
                                <NavLink :href="route('instancedepart.create')" :active="route().current('instancedepart.create')" 
                                    class="hover:bg-primary-menu hover:border hover:text-white hover:font-bold hover:text-1xl p-3 rounded font-bold text-primary-txt border border-l-8 flex items-center" 
                                    :class="{'border-primary-menu': route().current('instancedepart.create')}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-txt mr-2" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m0 8a2 2 0 01-2 2H5a2 2 0 01-2-2V8m16 0V6a2 2 0 00-2-2H5a2 2 0 00-2 2v2" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 12V6m0 0l-2 2m2-2l2 2" />
                                    </svg>
                                    Liste des Départs
                                </NavLink>
                                <NavLink :href="route('trimestre.create')" :active="route().current('trimestre.create')" 
                                    class="hover:bg-primary-menu hover:border hover:text-white hover:font-bold hover:text-1xl p-3 rounded font-bold text-primary-txt border border-l-8 flex items-center" 
                                    :class="{'border-primary-menu': route().current('trimestre.create')}">
                                    <svg class="w-6 h-5 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z" />
                                    </svg>
                                    Rapport Trimestre 
                                </NavLink>
                                <NavLink :href="route('message.create')" 
                                        :active="route().current('message.create')" 
                                        class="relative hover:bg-primary-menu hover:border hover:text-white hover:font-bold hover:text-1xl p-3 rounded font-bold text-primary-txt border border-l-8 flex items-center" 
                                        :class="{'border-primary-menu': route().current('message.create')}">
                                    <svg class="w-5 h-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10z"/>
                                    </svg> 
                                    Discussion
                                    <span v-if="unreadTotal > 0" class="ml-2 px-2 py-1 text-xs bg-green-500 text-white rounded-full">
                                        {{ unreadTotal }}
                                    </span>
                                </NavLink> 
  
                            </div>
                        </nav> 
                </transition>
            </div>
            <footer class="bg-white border-t border-primary-only text-center text-sm text-primary-txt mt-auto py-4">
                <b>CENTRE DES SERVICES FISCAUX</b> CADASTRE KÉDOUGOU
            </footer>
 
        </nav>

        <!-- Page Content -->
        <div class="flex-1 md:ml-64">
            <header class="bg-white shadow flex justify-between items-center p-4 relative">
                <!-- Titre ou Header (si présent) -->
                <div>
                    <slot name="header"/>
                </div>
 
                    <div class="relative">
 
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="flex items-center space-x-2 px-4 py-2 bg-primary rounded-md hover:bg-primary-dark">
                            <span class="text-white font-semibold">{{ user?.name }}</span>
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <transition name="slideUser">
                            <div v-if="showingNavigationDropdown" class="absolute right-0 mt-2 w-48 bg-white border border-primary-dark rounded shadow-md z-10">
                                <div class="px-4 py-2 text-gray-800">{{ user?.name }}</div>
                                <hr>
                                <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-4 py-2 text-primary hover:bg-primary-dark">
                                    Déconnexion
                                </Link>
                            </div>
                        </transition>
                    </div> 
            </header>

            <!-- Contenu de la p-menuage -->
            <main class="p-6">
                <slot />
            </main>

        </div>
    </div>
</template>

<!--  hover:border -->

<style scoped>
    .slide-enter-active,
    .slide-leave-active {
    transition: max-height 1s ease-in-out, opacity 1s ease-in-out;
    overflow: hidden;
    }

    .slide-enter-from,
    .slide-leave-to {
    max-height: 0;
    opacity: 0;
    }

    .slide-enter-to,
    .slide-leave-from {
    max-height: 500px;
    opacity: 1;
    }
  
    /* slideUser */
    .slideUser-enter-active,
    .slideUser-leave-active {
    transition: max-height 1s ease-in-out, opacity 1s ease-in-out;
    overflow: hidden;
    }

    .slideUser-enter-from,
    .slideUser-leave-to {
    max-height: 0;
    opacity: 0;
    }

    .slideUser-enter-to,
    .slideUser-leave-from {
    max-height: 500px;
    opacity: 1;
    }
</style>
