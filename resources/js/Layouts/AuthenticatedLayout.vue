<script setup>
import { ref, onMounted, onUnmounted, watch  } from 'vue';
import { usePage, Link } from '@inertiajs/vue3'; 
import NavLink from '@/Components/NavLink.vue';
  
// Récupérer l'utilisateur depuis les props Inertia
const page = usePage();
const user = page.props.auth?.user;
const isMenuOpen = ref(false)
const isDesktop = ref(false)
const showingNavigationDropdown = ref(false)

const showSubMenu = ref(false); 

onMounted(() => {
    const currentRoute = page.url;
    if (currentRoute.includes('arrivee') || currentRoute.includes('depart') || currentRoute.includes('instance')) {
        showSubMenu.value = true;
    }
});

// Surveille la largeur d'écran
function checkScreenSize() {
  isDesktop.value = window.innerWidth >= 768
}

// Toggle menu (mobile uniquement)
function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value
}

// Garde l’état du menu entre pages, mais pas après F5
onMounted(() => {
    checkScreenSize()
    window.addEventListener('resize', checkScreenSize) 
        if (window.__menuOpenTemp === true) {
            isMenuOpen.value = true
    } 
    window.__menuOpenTemp = false
}) 

onUnmounted(() => {
  window.removeEventListener('resize', checkScreenSize)
})

// Mémoriser l'état temporairement pour navigation Inertia
watch(isMenuOpen, (val) => {
  window.__menuOpenTemp = val
})

// // Liens du menu
// const menuItems = [
//   { label: 'Dashboard', href: route('dashboard'), active: 'dashboard' },
//   { label: 'CuurierArrivee', href: route('CuurierArrivee.create'), active: 'CuurierArrivee.create' },
//   { label: 'CourierDepart', href: route('CourierDepart.create'), active: 'CourierDepart.create' },
//   { label: 'Instance', href: route('Instance.create'), active: 'Instance.create' }, 
// ]

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
                <!-- Bouton d'ouverture/fermeture -->
                <button @click="toggleMenu" class="p-2 bg-primary text-white rounded shadow-md">
                    ☰ Menu
                </button>
                <transition name="slide">
                    
                        <nav v-if="isMenuOpen || !isDesktop" class="flex flex-col space-y-4" >
                            <!-- Navigation Links -->
                            <div class="flex flex-col space-y-4">
                              
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')" 
                                    class="hover:bg-primary-dark p-2 rounded font-bold text-lg border-l-5 flex items-center" :class="{'border-primary': route().current('dashboard')}">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-7 9 7v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V10z" />
                                    </svg>
                                    Dashboard
                                </NavLink>
                                <NavLink :href="route('arrivee.create')" :active="route().current('arrivee.create')" 
                                    class="hover:bg-primary-dark p-2 rounded font-bold text-lg border-l-5 flex items-center" :class="{'border-primary': route().current('arrivee.create')}">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 14h10m-5-5a3 3 0 0 1-6 0m12 5V10a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8" />
                                    </svg>
                                    Courriers Arrivées
                                </NavLink>
                                <NavLink :href="route('depart.create')" :active="route().current('depart.create')" 
                                    class="hover:bg-primary-dark p-2 rounded font-bold text-lg border-l-5 flex items-center" :class="{'border-primary': route().current('depart.create')}">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18m9-9H3" />
                                    </svg>
                                    Courriers Départs
                                </NavLink> 
                                <NavLink :href="route('instance.create')" :active="route().current('instance.create')" 
                                    class="hover:bg-primary-dark p-2 rounded font-bold text-lg border-l-5 flex items-center" :class="{'border-primary': route().current('instance.create')}">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4z" />
                                    </svg> 
                                    Instances
                                </NavLink>
<!-- 
                                <div class="relative">
                         
                                    <button
                                        @click="showSubMenu = !showSubMenu"
                                        class="hover:bg-primary-dark p-2 rounded text-lg border-l-5 flex items-center w-full cursor-pointer"
                                        :class="{'border-primary': route().current('instance.indexa')}"
                                        type="button" 
                                    >
                                        <span class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#6d3500">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4z" />
                                            </svg>
                                            Instances
                                        </span>
                                        <svg
                                            :class="{ 'rotate-180': showSubMenu }" class="w-4 h-4 transition-transform duration-200 ml-auto" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                   
                                    <div v-show="showSubMenu" class="mt-2 bg-white rounded shadow border border-gray-200 overflow-hidden transition-all duration-300">
                                        <NavLink
                                            :href="route('instance.indexarrivee')"
                                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100" :active="route().current('instance.indexa')"
                                        >
                                            Courriers Arrivées
                                        </NavLink>
                                        <NavLink
                                            :href="route('instance.indexdepart')"
                                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100" :active="route().current('instance.create')"
                                        >
                                            Courriers Départs
                                        </NavLink>
                                        <NavLink
                                            :href="route('instance.indexinstance')"
                                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100" :active="route().current('instance.create')"
                                        >
                                            Instances
                                        </NavLink>
                                    </div>
                                </div> -->

                                
                            
                            </div>
                        </nav> 
                </transition>
            </div>
            <footer class="bg-white border-t border-gray-200 text-center text-sm text-gray-600 mt-auto py-4">
                <b>CENTRE DES SERVICES FISCAUX</b> CADASTRE KÉDOUGOU
            </footer>
 
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

            <!-- Contenu de la page -->
            <main class="p-6">
                <slot />
            </main>

        </div>
    </div>
</template>


<!-- <style scoped>
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
</style> -->
