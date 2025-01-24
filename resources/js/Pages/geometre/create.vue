<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { InputCounter } from 'flowbite';
import { getCurrentWatcher, ref } from 'vue';


let showUsage = ref(true);
const form = ref({
    region: '',
    departement: '',
    lotissement: '',
    numLot: '',
    numSection: '',
    selecteTitulaire: '',
    // Ajouter d'autres champs nécessaires
});

let show = ref(true);
    show.value = !show;

let showC = ref(true);
    showC.value = !showC;

let showA = ref(true);
    showA.value = !showA;

let showI = ref(true);
    showI.value = !showI;

let showP = ref(true);
    showP.value = !showP;

let showCl = ref(true);
    showCl.value = !showCl;

let showAdm = ref(true);
    showAdm.value = !showAdm;

let showBati = ref(true);
    showBati.value = !showBati;
    
let showCAmenager = ref(true);
    showCAmenager.value = !showCAmenager;
    
let showCloture = ref(true);
    showCloture.value = !showCloture;
        
let showAmenagement = ref(true);
    showAmenagement.value = !showAmenagement;

let habitant = ref(true);

const submitForm = () => {
    console.log(form.value);
    // Ajoutez la logique d'envoi ici (par exemple, un appel Inertia.post())
};

// Tab actif (par défaut : 'stats')
const activeTab = ref('terrain');

// Fonction pour changer de tab
const setActiveTab = (tab) => {
    activeTab.value = tab;
};


const activeTabRU = ref('habitation');

// Fonction pour changer de tab
const setActiveTabRU = (tabRU) => {
    activeTabRU.value = tabRU;
};

document.addEventListener('DOMContentLoaded', function () {
    const Surface = document.getElementById('Surface');
    const Niveau = document.getElementById('Niveau');
    const Surface_bati_sol = document.getElementById('Surface_bati_sol');

    function calculate() {
        const val1 = parseFloat(Surface.value) || 0; // Le premier nombre est préchargé
        const val2 = parseFloat(Niveau.value) || 0; // Entrée utilisateur
        Surface_bati_sol.value = val1 * val2; // Changez + en -, *, / selon le besoin
    }

    Niveau.addEventListener('input', calculate);
});

document.addEventListener('DOMContentLoaded', function () {
    const Surface_bati_sol = document.getElementById('Surface_bati_sol');
    const Surface_utile = document.getElementById('Surface_utile');

    function calculate() {
        const val1 = parseFloat(Surface_bati_sol.value) || 0; // Si vide, considérer comme 0
        Surface_utile.value = val1 * 0.78; // Remplacez par `-`, `*`, `/` si nécessaire
    }

    Surface_bati_sol.addEventListener('input', calculate);
});

document.addEventListener('DOMContentLoaded', function () {
    const Surface_corriger = document.getElementById('Surface_corriger');
    const Surface_utile = document.getElementById('Surface_utile');
    const Coeff = document.getElementById('Coeff');

    function calculate() {
        const val1 = parseFloat(Surface_bati_sol.value) || 0; // Si vide, considérer comme 0
        const val2 = parseFloat(Coeff.value) || 0; // Si vide, considérer comme 0
        Surface_corriger.value = val1 * 0.78; // Remplacez par `-`, `*`, `/` si nécessaire
    }

    Surface_utile.addEventListener('input', calculate);
    Coeff.addEventListener('input', calculate);
});

</script>

<template>
    <Head title="Geometre"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Enquête Cadastrale 
            </h2>
        </template>

        <div class="py-12">
            <div class="flex justify-center">
                <div class="w-full max-w-6xl">
                    <div class="bg-white shadow-md rounded-lg">
                        <!-- En-tête du formulaire -->
                        <div class="p-4 border-b bg-gray-100">
                            <h1 class="text-lg font-semibold">Formulaire</h1>
                        </div>

                        <!-- Corps du formulaire -->
                        <div class="p-6">
                            <div class="mb-6">  
                                <form class="max-w-md mx-auto">   
                                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Recherche</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                            </svg>
                                        </div>
                                        <input type="search" name="sh_search" id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Verifier Numero Dossier" required />
                                        <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-hex-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-pinck-800">Recherche</button>
                                    </div>
                                </form>
                            </div>

                            <!--    References usages -->
                            <h5 class="text-lg font-bold">Réferences Usage</h5><br>
                            <div class="mb-12">
                                <div class="border-b">
                                    <div class="flex justify-left text-sm font-medium text-gray-500 divide-x divide-gray-200 dark:divide-gray-700">
                                        <select 
                                            name = "slt_reference_usage"
                                            @change="setActiveTabRU($event.target.value)" 
                                            class="w-1/3 text-center p-4 border border-gray-300 rounded text-gray-500 dark:text-gray-300"
                                            :class="{
                                                'text-indigo-600': activeTabRU === 'habitation' || activeTabRU === 'commercial' 
                                                || activeTabRU === 'industriel' || activeTabRU === 'agricol' || activeTabRU === 'professionnelle'
                                                || activeTabRU === 'culte'  || activeTabRU === 'administratif'
                                            }"
                                        >
                                            <option 
                                                value="habitation" 
                                                :selected="activeTabRU === 'habitation'"
                                            >
                                                Habitation
                                            </option>
                                            <option 
                                                value="commercial" 
                                                :selected="activeTabRU === 'commercial'"
                                            >
                                                Commercial
                                            </option>
                                            <option 
                                                value="agricol" 
                                                :selected="activeTabRU === 'agricol'"
                                            >
                                                Agricole
                                            </option>
                                            <option 
                                                value="industriel" 
                                                :selected="activeTabRU === 'industriel'"
                                            >
                                                Industriel
                                            </option>
                                            <option 
                                                value="professionnelle" 
                                                :selected="activeTabRU === 'professionnelle'"
                                            >
                                                Professionnelle
                                            </option>
                                            <option 
                                                value="culte" 
                                                :selected="activeTabRU === 'culte'"
                                            >
                                                Culte
                                            </option>
                                            <option 
                                                value="administratif" 
                                                :selected="activeTabRU === 'administratif'"
                                            >
                                                Administratif
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!--- habitation -->
                                <div class="p-6"> 
                                    <div v-show="activeTabRU === 'habitation'"  class="sgrid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                        <div class="mb-4">
                                            <!---   occupant 1 -->
                                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                                <div class="sm:col-span-1">
                                                    <dic class="mt-6">
                                                        <label for="Occupan_habitaion_1" class="block text-sm/6 font-medium text-gray-900">Occupant</label>
                                                        <input type="text" name="txt_occupan_habitaion_1" id="Occupan_habitaion_1"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                    </dic>
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <label for="Activite_principal_hbt_1" class="block text-sm/6 font-medium text-gray-900">Activite Princpal</label>
                                                    <div>
                                                        <input type="text" name="txt_activite_principal_hbt_1" id="Activite_principal_hbt_1"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <label for="Ninea_occupan_hbt_1" class="block text-sm/6 font-medium text-gray-900">Ninea Occupant</label>
                                                    <div>
                                                        <input type="text" name="txt_ninea_occupan_hbt_1" id="Ninea_occupan_hbt_1"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <label for="tel_occupant_hbt_1" class="block text-sm/6 font-medium text-gray-900">Tel Occupant</label>
                                                    <div>
                                                        <input type="tel" name="tel_tel_occupant_hbt_1" id="tel_occupant_hbt_1"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <label for="Montant_loyer_hbt_1" class="block text-sm/6 font-medium text-gray-900">Montant Loyer</label>
                                                    <div>
                                                        <input type="number" name="nbr_montant_loyer_hbt_1" id="Montant_loyer_hbt_1"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <br><button  @click="show = !show"
                                                        type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 
                                                        to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                        focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                        dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                        py-2.5 text-center me-2 mb-2 ">+ Ajouter</button>
                                                </div>
                                            </div>
                                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                <Transition  name="slide-fade">
                                                    <div v-if="!show" class="sm:col-span-8">
                                                        <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                            <div class="sm:col-span-1">
                                                                <dic class="mt-6">
                                                                    <label for="Occupan_habitaion_1" class="block text-sm/6 font-medium text-gray-900">Occupant</label>
                                                                    <input type="text" name="txt_occupan_habitaion_1" id="Occupan_habitaion_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                </dic>
                                                            </div>
                                                            <div class="sm:col-span-1">
                                                                <label for="Activite_principal_hbt_1" class="block text-sm/6 font-medium text-gray-900">Activite Princpal</label>
                                                                <div>
                                                                    <input type="text" name="txt_activite_principal_hbt_1" id="Activite_principal_hbt_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1">
                                                                <label for="Ninea_occupan_hbt_1" class="block text-sm/6 font-medium text-gray-900">Ninea Occupant</label>
                                                                <div>
                                                                    <input type="text" name="txt_ninea_occupan_hbt_1" id="Ninea_occupan_hbt_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1">
                                                                <label for="tel_occupant_hbt_1" class="block text-sm/6 font-medium text-gray-900">Tel Occupant</label>
                                                                <div>
                                                                    <input type="tel" name="tel_tel_occupant_hbt_1" id="tel_occupant_hbt_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1">
                                                                <label for="Montant_loyer_hbt_1" class="block text-sm/6 font-medium text-gray-900">Montant Loyer</label>
                                                                <div>
                                                                    <input type="number" name="nbr_montant_loyer_hbt_1" id="Montant_loyer_hbt_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                        </div>   
                                                    </div>
                                                </Transition>
                                            </div>
                                        </div>  
                                    </div>
                                
                                    <!--    Commercial -->
                                    <div v-show="activeTabRU === 'commercial'" class="space-y-4">
                                        <div class="mb-4">
                                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                                <div class="sm:col-span-2">
                                                    <label for="Activite_commercial" class="block text-sm/6 font-medium text-gray-900">Activité</label>
                                                    <div>
                                                        <input type="text" name="txt_activite_commercial" id="Activite_commercial"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <label for="Occopan_commercial" class="block text-sm/6 font-medium text-gray-900">Occupant</label>
                                                    <div>
                                                        <input type="text" name="txt_occopan_commercial" id="Occopan_commercial"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <div>
                                                        <button  @click="showC = !showC"
                                                        type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 
                                                        to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                        focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                        dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                        py-2.5 text-center me-2 mb-2 ">+ Ajouter</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                <Transition  name="slide-fade">
                                                    <div v-if="!showC" class="sm:col-span-8">
                                                        <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                            <div class="sm:col-span-2">
                                                                <label for="Activite_commercial_1" class="block text-sm/6 font-medium text-gray-900">Activité</label>
                                                                <div>
                                                                    <input type="text" name="txt_activite_commercial_1" id="Activite_commercial_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-2">
                                                                <label for="Occopan_commercial_1" class="block text-sm/6 font-medium text-gray-900">Occupant</label>
                                                                <div>
                                                                    <input type="text" name="txt_occopan_commercial_1" id="Occopan_commercial_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                        </div>   
                                                    </div>
                                                </Transition>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <!--    industriel -->
                                    <div v-show="activeTabRU === 'industriel'" class="space-y-4">
                                        <div class="mb-4">
                                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                                <div class="sm:col-span-2">
                                                    <label for="Activite_industriel" class="block text-sm/6 font-medium text-gray-900">Activité</label>
                                                    <div>
                                                        <input type="text" name="txt_activite_industriel" id="Activite_industriel"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <label for="Occopan_industriel" class="block text-sm/6 font-medium text-gray-900">Occupant</label>
                                                    <div>
                                                        <input type="text" name="txt_occopan_industriel" id="Occopan_industriel"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <div>
                                                        <button  @click="showI = !showI"
                                                        type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 
                                                        to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                        focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                        dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                        py-2.5 text-center me-2 mb-2 ">+ Ajouter</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                <Transition  name="slide-fade">
                                                    <div v-if="!showI" class="sm:col-span-8">
                                                        <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                            <div class="sm:col-span-2">
                                                                <label for="Activite_industriel_1" class="block text-sm/6 font-medium text-gray-900">Activité</label>
                                                                <div>
                                                                    <input type="text" name="txt_activite_industriel_1" id="Activite_industriel_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-2">
                                                                <label for="Occopan_industriel_1" class="block text-sm/6 font-medium text-gray-900">Occupant</label>
                                                                <div>
                                                                    <input type="text" name="txt_occopan_industriel_1" id="Occopan_industriel_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                        </div>   
                                                    </div>
                                                </Transition>
                                            </div>
                                        </div>
                                    </div>

                                    <!--    agricol -->
                                    <div v-show="activeTabRU === 'agricol'" class="space-y-4">
                                        <div class="mb-4">
                                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                                <div class="sm:col-span-2">
                                                    <label for="Activite_agricole" class="block text-sm/6 font-medium text-gray-900">Activité</label>
                                                    <input type="text" name="txt_activite_agricole" id="Activite_agricole"  class="block w-full rounded-md bg-white 
                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <label for="Occopan_agricole" class="block text-sm/6 font-medium text-gray-900">Occupant</label>
                                                    <input type="text" name="txt_occopan_agricole" id="Occopan_agricole"  class="block w-full rounded-md bg-white 
                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <div>
                                                        <button  @click="showA = !showA"
                                                        type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 
                                                        to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                        focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                        dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                        py-2.5 text-center me-2 mb-2 ">+ Ajouter</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                <Transition  name="slide-fade">
                                                    <div v-if="!showA" class="sm:col-span-8">
                                                        <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                            <div class="sm:col-span-2">
                                                                <label for="Activite_agricole_1" class="block text-sm/6 font-medium text-gray-900">Activité</label>
                                                                <input type="text" name="txt_activite_agricole_1" id="Activite_agricole_1"  class="block w-full rounded-md bg-white 
                                                                px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                            <div class="sm:col-span-2">
                                                                <label for="Occopan_agricole_1" class="block text-sm/6 font-medium text-gray-900">Occupant</label>
                                                                <input type="text" name="txt_occopan_agricole_1" id="Occopan_agricole_1"  class="block w-full rounded-md bg-white 
                                                                px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                        </div>   
                                                    </div>
                                                </Transition>
                                            </div>
                                        </div>
                                    </div>
                       
                                    <!--    professionnelle -->
                                    <div v-show="activeTabRU === 'professionnelle'" class="space-y-4">
                                        <div class="mb-4">
                                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                                <div class="sm:col-span-2">
                                                    <label for="Activite_professionnelle" class="block text-sm/6 font-medium text-gray-900">Activité</label>
                                                    <input type="text" name="txt_activite_professionnelle" id="Activite_professionnelle"  class="block w-full rounded-md bg-white 
                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <label for="Occopan_professionnelle" class="block text-sm/6 font-medium text-gray-900">Occupant</label>
                                                    <input type="text" name="txt_occopan_professionnelle" id="Occopan_professionnelle"  class="block w-full rounded-md bg-white 
                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <div>
                                                        <button  @click="showP = !showP"
                                                        type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 
                                                        to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                        focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                        dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                        py-2.5 text-center me-2 mb-2 ">+ Ajouter</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                <Transition  name="slide-fade">
                                                    <div v-if="!showP" class="sm:col-span-8">
                                                        <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                            <div class="sm:col-span-2">
                                                                <label for="Activite_professionnelle_1" class="block text-sm/6 font-medium text-gray-900">Activité</label>
                                                                <input type="text" name="txt_activite_professionnelle_1" id="Activite_professionnelle_1"  class="block w-full rounded-md bg-white 
                                                                px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                            <div class="sm:col-span-2">
                                                                <label for="Occopan_professionnelle_1" class="block text-sm/6 font-medium text-gray-900">Occupant</label>
                                                                <input type="text" name="txt_occopan_professionnelle_1" id="Occopan_professionnelle_1"  class="block w-full rounded-md bg-white 
                                                                px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                        </div>   
                                                    </div>
                                                </Transition>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!--    culte -->
                                    <div v-show="activeTabRU === 'culte'" class="space-y-4">
                                        <div class="mb-4">
                                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                                <div class="sm:col-span-2">
                                                    <label for="Activite_culte" class="block text-sm/6 font-medium text-gray-900">Activité</label>
                                                    <input type="text" name="txt_activite_culte" id="Activite_culte"  class="block w-full rounded-md bg-white 
                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <label for="Occopan_culte" class="block text-sm/6 font-medium text-gray-900">Occupant</label>
                                                    <input type="text" name="txt_occopan_culte" id="Occopan_culte"  class="block w-full rounded-md bg-white 
                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <div>
                                                        <button  @click="showCl = !showCl"
                                                        type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 
                                                        to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                        focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                        dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                        py-2.5 text-center me-2 mb-2 ">+ Ajouter</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                <Transition  name="slide-fade">
                                                    <div v-if="!showCl" class="sm:col-span-8">
                                                        <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                            <div class="sm:col-span-2">
                                                                <label for="Activite_culte_1" class="block text-sm/6 font-medium text-gray-900">Activité</label>
                                                                <input type="text" name="txt_activite_culte_1" id="Activite_culte_1"  class="block w-full rounded-md bg-white 
                                                                px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                            <div class="sm:col-span-2">
                                                                <label for="Occopan_culte_1" class="block text-sm/6 font-medium text-gray-900">Occupant</label>
                                                                <input type="text" name="txt_occopan_culte_1" id="Occopan_culte_1"  class="block w-full rounded-md bg-white 
                                                                px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                        </div>   
                                                    </div>
                                                </Transition>
                                            </div>
                                        </div>
                                    </div>
                                    <!--        admin   -->
                                    <div v-show="activeTabRU === 'administratif'" class="space-y-4">
                                        <div class="mb-4">
                                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                                <div class="sm:col-span-2">
                                                    <label for="Activite_administratif" class="block text-sm/6 font-medium text-gray-900">Activité</label>
                                                    <input type="text" name="txt_Activite_administratif" id="Activite_administratif"  class="block w-full rounded-md bg-white 
                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <label for="Occupan_administratif" class="block text-sm/6 font-medium text-gray-900">Occupant</label>
                                                    <input type="text" name="txt_occupan_administratif" id="Occupan_administratif"  class="block w-full rounded-md bg-white 
                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <div>
                                                        <button  @click="showAdm = !showAdm"
                                                        type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 
                                                        to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                        focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                        dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                        py-2.5 text-center me-2 mb-2 ">+ Ajouter</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                <Transition  name="slide-fade">
                                                    <div v-if="!showAdm" class="sm:col-span-8">
                                                        <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                            <div class="sm:col-span-2">
                                                                <label for="Activite_administratif_1" class="block text-sm/6 font-medium text-gray-900">Activité</label>
                                                                <input type="text" name="txt_Activite_administratif_1" id="Activite_administratif_1"  class="block w-full rounded-md bg-white 
                                                                px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                            <div class="sm:col-span-2">
                                                                <label for="Occupan_administratif_1" class="block text-sm/6 font-medium text-gray-900">Occupant</label>
                                                                <input type="text" name="txt_occupan_administratif_1" id="Occupan_administratif_1"  class="block w-full rounded-md bg-white 
                                                                px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                        </div>   
                                                    </div>
                                                </Transition>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       

                            <!-- evaluation de limeuble-->
                            <h5 class="text-lg font-bold">Evaluation de l'immeuble</h5><br>
                            <div class="mb-6">
                                <div class="border-b">
                                    <ul class="flex justify-center text-sm font-medium text-gray-500 divide-x divide-gray-200 dark:divide-gray-700">

                                        <li class="w-1/3 text-center">
                                            <button
                                                @click="setActiveTab('terrain')"
                                                :class="{
                                                    'text-indigo-600 border-b-2 border-indigo-600': activeTab === 'terrain',
                                                    'hover:text-gray-700 hover:border-gray-300': activeTab !== 'terrain'
                                                }"
                                                class="inline-block w-full p-4"
                                            >
                                                Terrain
                                            </button>
                                        </li>

                                        <li class="w-1/3 text-center">
                                            <button
                                                @click="setActiveTab('bati')"
                                                :class="{
                                                    'text-indigo-600 border-b-2 border-indigo-600': activeTab === 'bati',
                                                    'hover:text-gray-700 hover:border-gray-300': activeTab !== 'bati'
                                                }"
                                                class="inline-block w-full p-4"
                                            >
                                                Bâti
                                            </button>
                                        </li>

                                        <li class="w-1/3 text-center">
                                            <button
                                                @click="setActiveTab('Cours_amenagees')"
                                                :class="{
                                                    'text-indigo-600 border-b-2 border-indigo-600': activeTab === 'Cours_amenagees',
                                                    'hover:text-gray-700 hover:border-gray-300': activeTab !== 'Cours_amenagees'
                                                }"
                                                class="inline-block w-full p-4"
                                            >
                                                Cours aménagées
                                            </button>
                                        </li>

                                        <li class="w-1/3 text-center">
                                            <button
                                                @click="setActiveTab('cloture')"
                                                :class="{
                                                    'text-indigo-600 border-b-2 border-indigo-600': activeTab === 'cloture',
                                                    'hover:text-gray-700 hover:border-gray-300': activeTab !== 'cloture'
                                                }"
                                                class="inline-block w-full p-4"
                                            >
                                                Clôtture
                                            </button>
                                        </li>
                                        
                                        <li class="w-1/3 text-center">
                                            <button
                                                @click="setActiveTab('amenagement')"
                                                :class="{
                                                    'text-indigo-600 border-b-2 border-indigo-600': activeTab === 'amenagement',
                                                    'hover:text-gray-700 hover:border-gray-300': activeTab !== 'amenagement'
                                                }"
                                                class="inline-block w-full p-4"
                                            >
                                                Aménagements particuliers
                                            </button>
                                        </li>

                                    </ul>
                                </div>

                                <!--     TERRAIN    -->
                                <div class="p-6">
                                    <div v-show="activeTab === 'terrain'" class="sgrid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                        <div class="mb-4">
                                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                                <div class="sm:col-span-2">
                                                    <div class="mt-6"> 
                                                        <label for="Date_devaluation" class="block text-sm font-medium text-gray-900">Date d'évaluation</label>
                                                        <input type="date" name="txt_date_devaluation" id="Date_devaluation" class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                    </div>
                                                </div>
                                            
                                                <div class="sm:col-span-2">
                                                    <div class="mt-6">
                                                        <label for="Superficie_totale" class="block text-sm/6 font-medium text-gray-900">Superficie Totale Terrain</label>
                                                        <input type="number" name="txt_superficie_totale" id="Superficie_totale" class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                    </div>
                                                </div>
                                            
                                                <div class="sm:col-span-2">
                                                    <div class="mt-6">
                                                        <label for="Superficie_bati_sol" class="block text-sm/6 font-medium text-gray-900">Superficie Bâti au sol</label>
                                                        <div>
                                                            <input type="number" name="txt_superficie_bati_sol" id="Superficie_bati_sol" class="block w-full rounded-md bg-white 
                                                            px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                            outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                            focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                        </div>
                                                    </div>
                                                </div>
                                        
                                                <div class="sm:col-span-2">
                                                    <div class="mt-6">
                                                        <label for="Secteur" class="block text-sm/6 font-medium text-gray-900">Secteur</label>
                                                        <div> 
                                                            <select type="select" name="slt_secteur" id="Secteur"  class="block w-full rounded-md bg-white 
                                                                px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                <option selected desabled></option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>  
                                            
                                                <div class="sm:col-span-2">
                                                    <div class="mt-6">
                                                        <label for="Prix_metre_carre" class="block text-sm/6 font-medium text-gray-900">Prix mètre Carré</label>
                                                        <div>
                                                            <input type="number" name="nbr_prix_metre_carré" id="Prix_metre_carre"  class="block w-full rounded-md bg-white 
                                                            px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                            outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                            focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                        </div>
                                                    </div>
                                                </div>  
                                            
                                                <div class="sm:col-span-2">
                                                    <div class="mt-6"> 
                                                        <label for="Valeur_terrain" class="block text-sm/6 font-medium text-gray-900">Valeur du Terrain</label>
                                                        <div>
                                                            <input type="number" name="nbr_valeur_terrain" id="Valeur_terrain"  class="block w-full rounded-md bg-white 
                                                            px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                            outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                            focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--           BATI                        -->
                                    <div v-show="activeTab === 'bati'" class="space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div class="flex justify-center">
                                                <label for="Type_residence" class="block text-sm font-medium text-gray-900">Type de residence</label>
                                                <select type="select" name="slt_type_residence" id="Type_residence" class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 border-gray-300 
                                                focus:ring-indigo-500 focus:border-indigo-500"
                                                >
                                                    <option selected disabled></option>
                                                    <option value="Residence Principal">Residence Principal</option>
                                                    <option value="Residence Secondaire">Residence secondaire</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                            <div class="sm:col-span-2">
                                                <div class="flex items-center ps-4 border border-gray-200 rounded dark:border-gray-700">
                                                    <input v-model="currentCat" id="Maison_indivielle" type="radio" value="catA" name="rd_type_maissons" class="w-4 h-4 
                                                        text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 
                                                        dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                    <label for="Maison_indivielle" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 
                                                            dark:text-gray-300">Maison individuelle</label>
                                                </div>
                                            </div>
                                            <div class="sm:col-span-2">
                                                <div class="flex items-center ps-4 border border-gray-200 rounded dark:border-gray-700">
                                                    <input v-model="currentCat" id="Immeuble_collectif" type="radio" value="catB" name="rd_type_maissons" 
                                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                                                dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700
                                                                dark:border-gray-600">
                                                    <label for="Immeuble_collectif" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 
                                                                dark:text-gray-300">Immeuble collectif</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Batiment  Princil-->
                                        <center><h6 class="text-lg">Bâtiment(s) Principal(aux)</h6></center>
                                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                            <div class="flex items-start mb-6">
                                                <div class="flex items-center h-5">
                                                    <input id="remember" name="chk_bati_principal" type="checkbox" value="Bâtiment principal" class="w-4 h-4 border border-gray-300 
                                                        rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 
                                                        dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" />
                                                </div>
                                                <label for="remember" class="ms-2 text-sm font-medium text-gray-900 
                                                    dark:text-gray-300">Bâtiment principal</label>
                                            </div>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-2">
                                                <div class="grid gap-6 mb-6 md:grid-cols-2"> 
                                                    <KeepAlive>
                                                        <component :is="currentCat"></component>
                                                    </KeepAlive>
                                                </div>
                                                <div > 
                                                    <label for="Prix_mettre_carre" class="block text-sm/6 font-medium text-gray-900">Prix m²</label>
                                                    <input type="number" name="nbr_prix_metre_carre" id="Prix_mettre_carre"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div>  
                                            <div  class="grid gap-6 mb-6 md:grid-cols-2">
                                                <div> 
                                                    <label for="Surface_bati_sol" class="block text-sm/6 font-medium text-gray-900">Surf B S</label>
                                                    <input type="number" name="nbr_surface_bati_sol" id="Surface_bati_sol" oninput="calculeSurfaceBrute()"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                                <div> 
                                                    <label for="Niveau" class="block text-sm/6 font-medium text-gray-900">Niveau</label>
                                                    <input type="number" name="nbr_niveau" id="Niveau" oninput="calculeSurfaceBrute()"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-2">
                                                <div> 
                                                    <label for="Surface_utile" class="block text-sm/6 font-medium text-gray-900">Surf Utile</label>
                                                    <input type="number" name="nbr_surface_utile" id="Surface_utile" oninput="calculeSurfaceUtile()"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                                <div> 
                                                    <label for="Coeff" class="block text-sm/6 font-medium text-gray-900">Coeff</label>
                                                    <select type="select" name="slt_coeff" id="Coeff"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            <option selected desabled></option>
                                                            <option value="0.1">0.1</option>
                                                            <option value="0.2">0.2</option>
                                                            <option value="0.3">0.3</option>
                                                            <option value="0.4">0.4</option>
                                                            <option value="0.5">0.5</option>
                                                            <option value="0.6">0.6</option>
                                                            <option value="0.7">0.7</option>
                                                            <option value="0.8">0.8</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-2">
                                                <div> 
                                                    <label for="Surface_corriger" class="block text-sm/6 font-medium text-gray-900">S.Corrigee</label>
                                                    <input type="number" name="nbr_surface_corriger" id="Surface_corriger" oninput="calculesurfaceCorriger()"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                                <div> 
                                                    <label for="Valeur" class="block text-sm/6 font-medium text-gray-900">Valeur</label>
                                                    <input type="number" name="nbr_valeur" id="Valeur"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div>
                                            <div class=" gap-6 mb-6 md:grid-cols-2">
                                                <div class="flex justify-end">
                                                        <div>
                                                            <br><button  @click="showBati = !showBati"
                                                            type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 
                                                            to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                            focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                            dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                            py-2.5 text-center me-2 mb-2 ">+ Ajouter</button>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>  
                                        <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                            <Transition  name="slide-fade">
                                                <div v-if="!showBati" class="sm:col-span-8">
                                                    <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                        <div class="flex items-start mb-6">
                                                            <div class="flex items-center h-5">
                                                                <input id="remember_1" name="chk_bati_principal_1" type="checkbox" value="Bâtiment principal" class="w-4 h-4 border border-gray-300 
                                                                    rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 
                                                                    dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" />
                                                            </div>
                                                            <label for="remember_1" class="ms-2 text-sm font-medium text-gray-900 
                                                                dark:text-gray-300">Bâtiment principal</label>
                                                        </div>
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-2">
                                                            <div class="grid gap-6 mb-6 md:grid-cols-2"> 
                                                                <KeepAlive>
                                                                    <component :is="currentCat"></component>
                                                                </KeepAlive>
                                                            </div>
                                                            <div > 
                                                                <label for="Prix_mettre_carre_1" class="block text-sm/6 font-medium text-gray-900">Prix m²</label>
                                                                <input type="number_1" name="nbr_prix_metre_carre_1" id="Prix_mettre_carre_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                        </div>  
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-2">
                                                            <div> 
                                                                <label for="Surface_bati_sol_1" class="block text-sm/6 font-medium text-gray-900">Surf B S</label>
                                                                <input type="number" name="nbr_surface_bati_sol_1" id="Surface_bati_sol_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                            <div> 
                                                                <label for="Niveau_1" class="block text-sm/6 font-medium text-gray-900">Niveau</label>
                                                                <input type="number" name="nbr_niveau_1" id="Niveau_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                        </div>
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-2">
                                                            <div> 
                                                                <label for="Surface_utile_1" class="block text-sm/6 font-medium text-gray-900">Surf Utile</label>
                                                                <input type="number" name="nbr_surface_utile_1" id="Surface_utile_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                            <div> 
                                                                <label for="Coeff_1" class="block text-sm/6 font-medium text-gray-900">Coeff</label>
                                                                <select type="select" name="slt_coeff_1" id="Coeff_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                        <option selected desabled></option>
                                                                        <option value="0.1">0.1</option>
                                                                        <option value="0.2">0.2</option>
                                                                        <option value="0.3">0.3</option>
                                                                        <option value="0.4">0.4</option>
                                                                        <option value="0.5">0.5</option>
                                                                        <option value="0.6">0.6</option>
                                                                        <option value="0.7">0.7</option>
                                                                        <option value="0.8">0.8</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-2">
                                                            <div> 
                                                                <label for="Surface_corriger_1" class="block text-sm/6 font-medium text-gray-900">S.Corrigee</label>
                                                                <input type="number" name="nbr_surface_corriger_1" id="Surface_corriger_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                            <div> 
                                                                <label for="Valeur_1" class="block text-sm/6 font-medium text-gray-900">Valeur</label>
                                                                <input type="number" name="nbr_valeur_1" id="Valeur_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </Transition>
                                        </div>
                                        <center>    
                                            <div  class="grid gap-6 mb-6 md:grid-cols-4">
                                                
                                                <div> 
                                                    <label for="Valeur_terrain_bati">Valeur bâtiment (s)</label>
                                                    <input type="text" name="txt_valeur_terrain_bati" id="Valeur_terrain_bati"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div>
                                        </center>
                                    </div>

                                    <!-- Cours AMENAGEES-->
                                    <div v-show="activeTab === 'Cours_amenagees'" class="space-y-4">

                                        <!-- Cour Aménager Totale -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                            <div class="flex items-start mb-6">
                                                <div class="flex items-center h-5">
                                                    <input id="CoursAmenagerTotale" name="chk_cours_amenager_totale" type="checkbox" value="Cours Aménagées Totale" class="w-4 h-4 border border-gray-300 
                                                        rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 
                                                        dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" />
                                                </div>
                                                <label for="CoursAmenagerTotale" class="ms-2 text-sm font-medium text-gray-900 
                                                    dark:text-gray-300">Cours Aménagées Totale</label>
                                            </div>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div> 
                                                    <label for="SurfaceCATotal" class="block text-sm/6 font-medium text-gray-900">Surface</label>
                                                    <input type="number" name="nbr_surface_ca_total" id="Surface_ca_total"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">                         
                                                </div>
                                            </div>  
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div> 
                                                    <label for="Categorie_ca_total" class="block text-sm/6 font-medium text-gray-900">Catégorie</label>
                                                    <select type="select" name="slt_categorie_ca_total" id="Categorie_ca_total"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            <option selected sesabled></option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div> 
                                                    <label for="Prix_metre_carre_ca_total" class="block text-sm/6 font-medium text-gray-900">P/m²</label>
                                                    <input type="number" name="nbr_prix_metre_carre_ca_total" id="Prix_metre_carre_ca_total"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div> 
                                                    <label for="Coefficient_ca_total" class="block text-sm/6 font-medium text-gray-900">Coefficient</label>
                                                    <select type="select" name="nbr_coefficient_ca_total" id="Coefficient_ca_total"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            <option selected desabled></option>
                                                            <option value="0.1">0.1</option>
                                                            <option value="0.2">0.2</option>
                                                            <option value="0.3">0.3</option>
                                                            <option value="0.4">0.4</option>
                                                            <option value="0.5">0.5</option>
                                                            <option value="0.6">0.6</option>
                                                            <option value="0.7">0.7</option>
                                                            <option value="0.8">0.8</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div> 
                                                    <label for="Valeur_ca_total" class="block text-sm/6 font-medium text-gray-900">Valeur</label>
                                                    <input type="number" name="nbr_valeur_ca_total" id="Valeur_ca_total"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div>
                                        </div>
                                        <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                            <Transition  name="slide-fade">
                                                <div v-if="!showCAmenager" class="sm:col-span-8">
                                                    <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                        <div class="flex items-start mb-6">
                                                            <div class="flex items-center h-5">
                                                                <input id="CoursAmenagerTotale_1" name="chk_cours_amenager_totale_1" type="checkbox" value="Cours Aménagées Totale" class="w-4 h-4 border border-gray-300 
                                                                    rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 
                                                                    dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" />
                                                            </div>
                                                            <label for="CoursAmenagerTotale_1" class="ms-2 text-sm font-medium text-gray-900 
                                                                dark:text-gray-300">Cours Aménagées Totale</label>
                                                        </div>
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                            <div> 
                                                                <label for="SurfaceCATotal_1" class="block text-sm/6 font-medium text-gray-900">Surface</label>
                                                                <input type="number" name="nbr_surface_ca_total_1" id="Surface_ca_total_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">                         
                                                            </div>
                                                        </div>  
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                            <div> 
                                                                <label for="Categorie_ca_total_1" class="block text-sm/6 font-medium text-gray-900">Catégorie</label>
                                                                <select type="select" name="slt_categorie_ca_total_1" id="Categorie_ca_total_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                        <option selected sesabled></option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                            <div> 
                                                                <label for="Prix_metre_carre_ca_total_1" class="block text-sm/6 font-medium text-gray-900">P/m²</label>
                                                                <input type="number" name="nbr_prix_metre_carre_ca_total_1" id="Prix_metre_carre_ca_total_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                        </div>
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                            <div> 
                                                                <label for="Coefficient_ca_total_1" class="block text-sm/6 font-medium text-gray-900">Coefficient</label>
                                                                <select type="select" name="nbr_coefficient_ca_total_1" id="Coefficient_ca_total_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                        <option selected desabled></option>
                                                                        <option value="0.1">0.1</option>
                                                                        <option value="0.2">0.2</option>
                                                                        <option value="0.3">0.3</option>
                                                                        <option value="0.4">0.4</option>
                                                                        <option value="0.5">0.5</option>
                                                                        <option value="0.6">0.6</option>
                                                                        <option value="0.7">0.7</option>
                                                                        <option value="0.8">0.8</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                            <div> 
                                                                <label for="Valeur_ca_total_1" class="block text-sm/6 font-medium text-gray-900">Valeur</label>
                                                                <input type="number" name="nbr_valeur_ca_total_1" id="Valeur_ca_total_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </Transition>
                                        </div>
                                        <div class="flex justify-end">
                                            <div class="flex justify-end">
                                                <div>
                                                    <button  @click="showCAmenager = !showCAmenager"
                                                    type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 
                                                    to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                    focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                    dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                    py-2.5 text-center me-2 mb-2 ">+ Ajouter</button>
                                                </div>
                                            </div>
                                        </div>
                                        <center>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-4">
                                                <div> 
                                                    <label for="Valeur_total_cours" class="block text-sm/6 font-medium text-gray-900">Valeur Total des Cours</label>
                                                    <input type="number" name="nbr_valeur_total_cours" id="Valeur_total_cours"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div>
                                        </center>
                                    </div>

                                    <!-- CLOTURES -->
                                    <div v-show="activeTab === 'cloture'" class="space-y-4">
                                        <label for="Designation">Designation</label>
                                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                            <div class="flex items-start mb-6">
                                                <div class="flex items-center h-5">
                                                    <input id="Perimetr_cloture" name="chk_perimetre_cloture" type="checkbox" value="Périmetre Total" class="w-4 h-4 border border-gray-300 
                                                        rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 
                                                        dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" />
                                                </div>
                                                <label for="Perimetr_cloture" class="ms-2 text-sm font-medium text-gray-900 
                                                    dark:text-gray-300">Périmetre Total</label>
                                            </div>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div> 
                                                    <label for="Longueur_avant_clo" class="block text-sm/6 font-medium text-gray-900">Linéaire</label>
                                                    <input type="number" name="nbr_longueur_avant_clo" id="Longueur_avant_clo"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">                         
                                                </div>
                                            </div>  
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div> 
                                                    <label for="Categorie_clo" class="block text-sm/6 font-medium text-gray-900">Catégorie</label>
                                                    <select type="select" name="slt_categorie_clo" id="Categorie_clo"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            <option selected sesabled></option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div> 
                                                    <label for="Prix_metre_carre_clo" class="block text-sm/6 font-medium text-gray-900">P/mL</label>
                                                    <input type="number" name="nbr_prix_metre_carre_clo" id="Prix_metre_carre_clo"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div> 
                                                    <label for="Coefficient_clo" class="block text-sm/6 font-medium text-gray-900">Coefficient</label>
                                                    <select type="select" name="nbr_coefficient_clo" id="Coefficient_clo"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            <option selected desabled></option>
                                                            <option value="0.1">0.1</option>
                                                            <option value="0.2">0.2</option>
                                                            <option value="0.3">0.3</option>
                                                            <option value="0.4">0.4</option>
                                                            <option value="0.5">0.5</option>
                                                            <option value="0.6">0.6</option>
                                                            <option value="0.7">0.7</option>
                                                            <option value="0.8">0.8</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div> 
                                                    <label for="Valeur_clo" class="block text-sm/6 font-medium text-gray-900">Valeur</label>
                                                    <input type="number" name="nbr_valeur_clo" id="Valeur_clo"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div>
                                        </div>
                                        <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                            <Transition  name="slide-fade">
                                                <div v-if="!showCloture" class="sm:col-span-8">
                                                    <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                        <div class="flex items-start mb-6">
                                                            <div class="flex items-center h-5">
                                                                <input id="Perimetr_cloture_1" name="chk_perimetre_cloture_1" type="checkbox" value="Périmetre Total" class="w-4 h-4 border border-gray-300 
                                                                    rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 
                                                                    dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" />
                                                            </div>
                                                            <label for="Perimetr_cloture_1" class="ms-2 text-sm font-medium text-gray-900 
                                                                dark:text-gray-300">Périmetre Total</label>
                                                        </div>
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                            <div> 
                                                                <label for="Longueur_avant_clo_1" class="block text-sm/6 font-medium text-gray-900">Linéaire</label>
                                                                <input type="number" name="nbr_longueur_avant_clo_1" id="Longueur_avant_clo_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">                         
                                                            </div>
                                                        </div>  
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                            <div> 
                                                                <label for="Categorie_clo_1" class="block text-sm/6 font-medium text-gray-900">Catégorie</label>
                                                                <select type="select" name="slt_categorie_clo_1" id="Categorie_clo_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                        <option selected sesabled></option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                            <div> 
                                                                <label for="Prix_metre_carre_clo_1" class="block text-sm/6 font-medium text-gray-900">P/mL</label>
                                                                <input type="number" name="nbr_prix_metre_carre_clo_1" id="Prix_metre_carre_clo_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                        </div>
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                            <div> 
                                                                <label for="Coefficient_clo_1" class="block text-sm/6 font-medium text-gray-900">Coefficient</label>
                                                                <select type="select" name="nbr_coefficient_clo_1" id="Coefficient_clo_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                        <option selected desabled></option>
                                                                        <option value="0.1">0.1</option>
                                                                        <option value="0.2">0.2</option>
                                                                        <option value="0.3">0.3</option>
                                                                        <option value="0.4">0.4</option>
                                                                        <option value="0.5">0.5</option>
                                                                        <option value="0.6">0.6</option>
                                                                        <option value="0.7">0.7</option>
                                                                        <option value="0.8">0.8</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                            <div> 
                                                                <label for="Valeur_clo_1" class="block text-sm/6 font-medium text-gray-900">Valeur</label>
                                                                <input type="number" name="nbr_valeur_clo_1" id="Valeur_clo_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </Transition>
                                        </div>
                                        <div class="flex justify-end">
                                            <div>
                                                <button  @click="showCloture = !showCloture"
                                                type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 
                                                to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                py-2.5 text-center me-2 mb-2 ">+ Ajouter</button>
                                            </div>
                                        </div>
                                        <div  class="grid gap-6 mb-6 md:grid-cols-4">
                                            <div> 
                                                <label for="Valeur_total_clotur">Valeurs Totale des Clôtures</label>
                                                <input type="number" name="nbr_valeur_total_clotur" id="Valeur_total_clotur"  class="block w-full rounded-md bg-white 
                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                            </div>
                                        </div>
                                    </div>

                                    <!--    AMENAGEMENTS PARTICULIERS   -->
                                    <div v-show="activeTab === 'amenagement'" class="space-y-4">
                                        <center><h6 class="text-lg">VALEUR</h6></center>  
                                        <!--    1    -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div>
                                                    <label for="Designation_1" class="ms-2 text-sm font-medium text-gray-900 
                                                        dark:text-gray-300">Designeation</label>
                                                    <input id="Designation_1" name="txt_designation_am" type="text" class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                                                </div>
                                            </div>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1"> 
                                                <div>
                                                    <label for="Valeur_unitaire_1" class="ms-2 text-sm font-medium text-gray-900 
                                                        dark:text-gray-300">Valeur Unitaire</label>
                                                    <input type="number" name="nbr_valeur_unitaire_am" id="Valeur_unitaire_1"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">                         
                                                </div>
                                            </div>  
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div> 
                                                    <label for="Quantile_1" class="ms-2 text-sm font-medium text-gray-900 
                                                    dark:text-gray-300">Quantité</label>
                                                    <input type="number" name="nbr_quantile_am" id="Quantile_1"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div> 
                                                    <label for="Coeficien_1" class="ms-2 text-sm font-medium text-gray-900 
                                                        dark:text-gray-300">Coeficien</label>
                                                    <select type="select" name="slt_coeficien_am" id="Coeficien_1"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            <option selected desabled></option>
                                                            <option value="0.1">0.1</option>
                                                            <option value="0.2">0.2</option>
                                                            <option value="0.3">0.3</option>
                                                            <option value="0.4">0.4</option>
                                                            <option value="0.5">0.5</option>
                                                            <option value="0.6">0.6</option>
                                                            <option value="0.7">0.7</option>
                                                            <option value="0.8">0.8</option>
                                                            <option value="0.9">0.9</option>
                                                            <option value="1">1</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div> 
                                                    <label for="Valeur" class="ms-2 text-sm font-medium text-gray-900 
                                                    dark:text-gray-300">Valeur</label>
                                                    <input type="number" name="nbr_valeur_am" id="Valeur"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div>
                                            <div class="flex justify-end">
                                                <div  class="grid gap-6 mb-6 md:grid-cols-1">   
                                                    <div>
                                                        <br><button  @click="showAmenagement = !showAmenagement"
                                                        type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 
                                                        to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                        focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                        dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                        py-2.5 text-center me-2 mb-2 ">+ Ajouter</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                            <Transition  name="slide-fade">
                                                <div v-if="!showAmenagement" class="sm:col-span-8">
                                                    <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                            <div>
                                                                <label for="Designation_1" class="ms-2 text-sm font-medium text-gray-900 
                                                                    dark:text-gray-300">Designeation</label>
                                                                <input id="Designation_1" name="txt_designation_am_1" type="text" class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                                                            </div>
                                                        </div>
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1"> 
                                                            <div>
                                                                <label for="Valeur_unitaire_1" class="ms-2 text-sm font-medium text-gray-900 
                                                                    dark:text-gray-300">Valeur Unitaire</label>
                                                                <input type="number" name="nbr_valeur_unitaire_am" id="Valeur_unitaire_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">                         
                                                            </div>
                                                        </div>  
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                            <div> 
                                                                <label for="Quantile_1" class="ms-2 text-sm font-medium text-gray-900 
                                                                dark:text-gray-300">Quantité</label>
                                                                <input type="number" name="nbr_quantile_am" id="Quantile_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                        </div>
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                            <div> 
                                                                <label for="Coeficien_1" class="ms-2 text-sm font-medium text-gray-900 
                                                                    dark:text-gray-300">Coeficien</label>
                                                                <select type="select" name="slt_coeficien_am" id="Coeficien_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                        <option selected desabled></option>
                                                                        <option value="0.1">0.1</option>
                                                                        <option value="0.2">0.2</option>
                                                                        <option value="0.3">0.3</option>
                                                                        <option value="0.4">0.4</option>
                                                                        <option value="0.5">0.5</option>
                                                                        <option value="0.6">0.6</option>
                                                                        <option value="0.7">0.7</option>
                                                                        <option value="0.8">0.8</option>
                                                                        <option value="0.9">0.9</option>
                                                                        <option value="1">1</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div  class="grid gap-6 mb-6 md:grid-cols-1">
                                                            <div> 
                                                                <label for="Valeur_1" class="ms-2 text-sm font-medium text-gray-900 
                                                                dark:text-gray-300">Valeur</label>
                                                                <input type="number" name="nbr_valeur_am_1" id="Valeur_1"  class="block w-full rounded-md bg-white 
                                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </Transition>
                                        </div>
                              
                                        <div  class="grid gap-6 mb-6 md:grid-cols-4">
                                            <div> 
                                                <label for="valeur_totale_ap">Valeur Totale des A.P</label>
                                                <input type="number" name="nbr_valeur_totale_ap" id="valeur_totale_ap"  class="block w-full rounded-md bg-white 
                                                    px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                    outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                    focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Bouton de soumission -->
                            
                            <div class="sm:col-span-6 flex justify-center">
                                <button type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                                hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 
                                shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm 
                                px-5 py-2.5 text-center me-2 mb-2 ">Enregistrer</button>
                            </div> 
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Ajoutez vos styles personnalisés ici si nécessaire */

</style>


<style >

.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(20px);
  opacity: 0;
}

</style>

<script > 
import catA from './catA.vue'
import catB from './catB.vue'

export default {
  components: { catA, catB },
  data() {
    return {
      currentCat: 'catA'
    }
  },
}
</script>









