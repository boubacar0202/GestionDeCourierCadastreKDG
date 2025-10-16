<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed  } from 'vue';
import { Head } from '@inertiajs/vue3';
import { defineProps, onMounted } from 'vue';
import { router, usePage  } from '@inertiajs/vue3'
import { Link as InertiaLink } from '@inertiajs/vue3' 
 
const props = defineProps({
    arrivee: Object,    
    arrivees: Array,
    departs: Array,
    depart: Object,
});
  
const numeroCD = ref('');  
const annee = ref('');
const refCourrierArrivee = ref('');

// Normalisation pour éviter les problèmes d'espaces et de casse
function normalize(str) {
    return str?.toString().trim().toLowerCase();
}
 
//  Filtrer par date et par Numéro + Tri croissant par txt_numdordrecd
const filtereDeparts = computed(() => {
    const filtered = props.departs.filter(depart => {
        const matchNumero = numeroCD.value
            ? normalize(depart.txt_numdordrecd)?.includes(normalize(numeroCD.value))
            : true;

        const matchAnnee = annee.value
            ? new Date(depart.dt_datecouriercd).getFullYear().toString() === annee.value
            : true;

        const matchArrivee = refCourrierArrivee.value
            ? normalize(depart.txt_referencecourierarriveecd)?.includes(normalize(refCourrierArrivee.value))
            : true;

        return matchNumero && matchAnnee && matchArrivee;
    });

    // Tri par ordre numérique de la partie avant "/"
    return filtered.sort((a, b) => {
        const numA = parseInt(a.txt_numdordrecd?.split("/")[0] || 0, 10);
        const numB = parseInt(b.txt_numdordrecd?.split("/")[0] || 0, 10);
        return numA - numB; // ordre croissant
    });
});
 
// Compter le nombre total de courrier
const totalCourrier = computed(() => {
    return props.departs.filter(totalarrivee => !!totalarrivee.txt_numdordrecd).length;
});
 
// filtrer les instances par categorie  
const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};
 
function supprimerCourrierDepart(depart) {
    if (confirm(`Voulez-vous vraiment supprimer le courrier départ N° ${depart.txt_numdordrecd} ?`)) {
        router.delete(route('instancedepart.destroyDepart', depart.id), {
            preserveScroll: true,
            onSuccess: () => {
                console.log('Courrier départ supprimé avec succès');
            },
            onError: (errors) => {
                console.error('Erreur lors de la suppression', errors);
            }
        });
    }
}

</script>

<template>
    <Head title="Instances Départs">
        <link rel="icon" sizes="512x512" href="/logo-01.png">
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl text-primary-txt font-semibold leading-tight"
            >
                Liste des Courriers Departs
            </h2>
            
            <!-- Vérification pour afficher un message si aucune donnée -->
            <template v-if="(!departs || departs.length === 0)">
                <p>Aucun Instance Départ n'est trouvé.</p>
            </template>
           
        </template v-else>

        <div class="py-12">
            <div class="flex justify-center">
                <div class="w-full max-w-7xl">
                    <div class="bg-white shadow-md rounded-lg "> <br> 
                        <div class="mx-auto max-w-7xl sm:px-8 lg:px-12 mt-4 mb-4">
                            <div class="card-header">
                                <div class="p-4 border-b bg-primary-form">
                                    <h1 class="text-3xl text-primary-txt font-bold font-bold text-center">Base de donnée des Courriers Départs</h1>
                                </div>
                            </div> 
                             
                            <div class="relative overflow-x-auto p-4 border-b bg-primary-form mt-8">
                                <div class="flex justify-between items-center"> 
                                    <h1 class="text-xl text-primary-txt font-semibold">
                                        Liste des Courriers Départs : 
                                        <span v-if="totalCourrier>0" class="text-gray-600">
                                            ({{ totalCourrier }})
                                        </span>
                                        <span v-else class="text-red-600">
                                            Aucun enregistrement
                                        </span> 
                                    </h1>  

                                    <form @submit.prevent class="flex items-center ">
                                        <div  class="flex items-start space-x-4">
                                            <div>
                                                <input 
                                                    v-model="annee"
                                                    type="search"
                                                    maxlength="4"
                                                    id="default-search"
                                                    aria-label="Rechercher"
                                                    class="h-9 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                        border border-primary-menu  placeholder:text-gray-400 
                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    placeholder="année"
                                                /> 
                                            </div>
                                        
                                            <div>
                                                <input 
                                                    v-model="numeroCD"
                                                    type="search"
                                                    id="default-search"
                                                    aria-label="Rechercher"
                                                    class="h-9 block w-25 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                        border border-primary-menu  placeholder:text-gray-400 
                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    placeholder="Entrez numéro courrier"
                                                />
                                            </div>

                                            <div>
                                                <input 
                                                    v-model="refCourrierArrivee"
                                                    type="search"
                                                    id="default-search"
                                                    aria-label="Rechercher"
                                                    class="h-9 block w-25 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                        border border-primary-menu  placeholder:text-gray-400 
                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    placeholder="Reférence C .Arrivée"
                                                />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="max-h-[500px] overflow-y-auto shadow-md sm:rounded-lg mt-8">
                                <div class="container"> 
                                    <div class="card"> 
                                        <div class="card-body">
                                            <table class="table table-sm table-strictped table-bordered bg-primary text-white">

                                                <thead class="sticky top-0 z-10">
                                                    <tr class="h-20">
                                                        <th scope="col" class="sticky left-0 z-30 px-6 py-3 bg-primary text-white text-sm text-center whitespace-nowrap">N°</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">N° d'ordre départ</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">Caractère</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">Date Courrier</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">Categorie</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">Référence C.Arrivée</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">Référence C.Depart</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">Nombre Pièce</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">Référence</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">Objet</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">Destinataire</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">Date Envoi</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">Référence Réception</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">Observation</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">Durée Traitement</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">Fichier</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm text-center border-l border-primary-only font-bold whitespace-nowrap">ACTIONS</th>

                                                    </tr>
                                                </thead>
                                                        
                                                <tbody sortedDeparts>
                                                    <tr v-for="(depart, index) in filtereDeparts" :key="depart.id"  class="bg-white text-primary-txt h-10">
                                                        <td scope="col" class="sticky left-0 z-0 border bg-white px-6 py-3 text-sm text-center font-bold text-primary-txt whitespace-nowrap">{{ index + 1 }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold border border-primary-only whitespace-nowrap">{{ depart.txt_numdordrecd }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold border border-primary-only whitespace-nowrap">{{ depart.txt_caracterecd || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold border border-primary-only whitespace-nowrap">{{ formatDate(depart.dt_datecouriercd) || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold border border-primary-only whitespace-nowrap">{{ depart.txt_categoriecd || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold border border-primary-only whitespace-nowrap">{{ depart.txt_referencecourierarriveecd || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold border border-primary-only whitespace-nowrap">{{ depart.txt_referencecourierdepartcd || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold border border-primary-only whitespace-nowrap">{{ depart.txt_nombrepiececd || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold border border-primary-only whitespace-nowrap">{{ depart.txt_referencecd || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold border border-primary-only whitespace-nowrap">{{ depart.txt_objetcd || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold border border-primary-only whitespace-nowrap">{{ depart.txt_destinatairecd || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold border border-primary-only whitespace-nowrap">{{ formatDate(depart.dt_dateenvoicd) || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold border border-primary-only whitespace-nowrap">{{ depart.txt_referencereceptioncd || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold border border-primary-only whitespace-nowrap">{{ depart.txt_observationcd || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold border border-primary-only whitespace-nowrap">{{ depart.txt_dureetraitementcd || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only font-bold whitespace-nowrap"> 
                                                            <div v-if="depart.fichierPDFcd">
                                                                <a :href="`/storage/${depart.fichierPDFcd}`" target="_blank" class="text-blue-600 underline">
                                                                    Voir PDF
                                                                </a>
                                                            </div>
                                                            <div v-else class="text-gray-400 italic">Aucun fichier PDF</div>
                                                        </td>
                                                
                                                        <td class="flex items px-6 py-6">
                                                            <div class="mt-2">
                                                                <InertiaLink :href="`/depart/editdepart/${depart.id}`">
                                                                    <MazBtn 
                                                                        color="white" pastel size="sm"
                                                                        class="h-8 w-28 text-white bg-gradient-to-r from-green-400 via-green-500 
                                                                        to-green-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                                        focus:ring-green-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 
                                                                        dark:shadow-lg dark:shadow-green-800/80 font-medium rounded-lg text-sm px-5 
                                                                        py-2.5 text-center me-2 mb-2"
                                                                    >
                                                                        Modifier
                                                                    </MazBtn>
                                                                </InertiaLink> 
                                                            </div>
                                                            <div class="container">
                                                                <p></p>
                                                            </div>
                                                            <div class="mt-2">
                                                                <MazBtn 
                                                                    color="danger" size="sm"   
                                                                    @click="() => supprimerCourrierDepart(depart)"
                                                                    class="h-8 w-28 text-white bg-gradient-to-r from-danger-500 via-danger-600 
                                                                    to-danger-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                                    focus:ring-danger-300 dark:focus:ring-danger-800 shadow-lg shadow-danger-500/50 
                                                                    dark:shadow-lg dark:shadow-danger-800/80 font-medium rounded-lg text-sm px-5 
                                                                    py-2.5 text-center me-2 mb-2"
                                                                >
                                                                    Supprimer
                                                                </MazBtn>
                                                            </div>
                                                        </td> 
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
 
 
