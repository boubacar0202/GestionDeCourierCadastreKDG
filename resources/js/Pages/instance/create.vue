<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed  } from 'vue';
import { Head } from '@inertiajs/vue3';
import { defineProps, onMounted } from 'vue';
import { router, usePage  } from '@inertiajs/vue3'
import { Link as InertiaLink } from '@inertiajs/vue3'
// import { toast } from 'vue3-toastify'
 
const props = defineProps({
    arrivee: Object,    
    arrivees: Array,
    departs: Array,
    depart: Object,
});
 
const numeroCA = ref(''); 
const numeroCD = ref('');  

// Normalisation pour éviter les problèmes d'espaces et de casse
function normalize(str) {
    return str?.toString().trim().toLowerCase();
}
  
// Filtrer automatiquement selon la saisie dans numero
// const filtereArrivees = computed(() => {
//     if (!numeroCA.value) return props.arrivees;

//     const search = normalize(numeroCA.value); 

//     return props.arrivees.filter(arrivee => {
//         const dossierNum = normalize(arrivee.txt_numdordre);
//         return dossierNum?.includes(search);
//     });
// });


// const filtereArrivees = computed(() => {
//     if (!numeroCA.value) return props.arrivees.map(arrivee => ({
//         ...arrivee,
//         isLateConvocation: arrivee.txt_categorie === 'Convocation - Invitation' &&
//             arrivee.dt_date &&
//             arrivee.dt_datearrivee &&
//             (new Date(arrivee.dt_datearrivee) - new Date(arrivee.dt_date)) > (72 * 60 * 60 * 1000)
//     }));

//     const search = normalize(numeroCA.value); 

//     return props.arrivees
//         .filter(arrivee => {
//             const dossierNum = normalize(arrivee.txt_numdordre);
//             return dossierNum?.includes(search);
//         })
//         .map(arrivee => ({
//             ...arrivee,
//             isLateConvocation: arrivee.txt_categorie === 'Convocation - Invitation' &&
//                 arrivee.dt_date &&
//                 arrivee.dt_datearrivee &&
//                 (new Date(arrivee.dt_datearrivee) - new Date(arrivee.dt_date)) > (72 * 60 * 60 * 1000)
//         }));
// });

const filtereArrivees = computed(() => {
    const now = new Date();

    const addLateFlag = (arrivee) => {
        const dtDate = arrivee.dt_date ? new Date(arrivee.dt_date) : null;

        return {
            ...arrivee,
            isLateConvocation:
                arrivee.txt_categorie === 'Convocation - Invitation' &&
                dtDate &&
                (dtDate - now) <= (72 * 60 * 60 * 1000) // inférieur ou égal à 72h
        };
    };

    if (!numeroCA.value) {
        return props.arrivees.map(addLateFlag);
    }

    const search = normalize(numeroCA.value);

    return props.arrivees
        .filter(arrivee => {
            const dossierNum = normalize(arrivee.txt_numdordre);
            return dossierNum?.includes(search);
        })
        .map(addLateFlag);
});
 

// filtrer les courriers départs par date de création
const filtereDeparts = computed(() => {
    if (!numeroCD.value) return props.departs;

    const search = normalize(numeroCD.value);

    return props.departs.filter(depart => {
        const dossierNum = normalize(depart.txt_numdordrecd);
        return dossierNum?.includes(search);
    });
}); 

// filtrer les instances par categorie 
const filteredInstance = computed(() => {
    return props.arrivees.filter(arrivee => {
        // On vérifie uniquement sur cet arrivee précis, sans tenir compte des autres
        const existeDansDepart = props.departs.some(depart => depart.txt_destinatairecd === arrivee.txt_expediteur);
        return arrivee.txt_categorie === 'Demande SERVICES' && !existeDansDepart;
    });
});

  
const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

function supprimerCourrierArrivee(arrivee) {
    if (confirm(`Voulez-vous vraiment supprimer le courrier arrivée N° ${arrivee.txt_numdordre} ?`)) {
        router.delete(route('instance.destroy', arrivee.id), {
            preserveScroll: true,
            onSuccess: () => {
                console.log('Arrivée supprimée avec succès');
            },
            onError: (errors) => {
                console.error('Erreur lors de la suppression', errors);
            }
        });
    }
}
 
function supprimerCourrierDepart(depart) {
    if (confirm(`Voulez-vous vraiment supprimer le courrier départ N° ${depart.txt_numdordrecd} ?`)) {
        router.delete(route('instance.destroyDepart', depart.id), {
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
    <Head title="Donnee">
        <link rel="icon" sizes="512x512" href="/logo-01.png">
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Base de données
            </h2>
            
            <!-- Vérification pour afficher un message si aucune donnée -->
            <template v-if="(!arrivees || arrivees.length === 0) && (!departs || departs.length === 0)">
                <p>Aucun Courrier arrivée trouvé.</p>
            </template>
           
        </template v-else>

        <div class="py-12">
            <div class="flex justify-center">
                <div class="w-full max-w-7xl">
                    <div class="bg-white shadow-md rounded-lg "> <br> 
                        <div class="mx-auto max-w-7xl sm:px-8 lg:px-12 mt-4 mb-4">
                            <div class="card-header">
                                <div class="p-4 border-b bg-gray-100">
                                    <h1 class="text-2xl font-semibold text-center">Base de données Courrier arrivée</h1>
                                </div>
                            </div>
                            <br>
                            <div class="relative overflow-x-auto p-4 border-b bg-gray-100">
                                <div class="flex justify-between items-center"> 
                                    <h1 class="text-lg font-semibold">Courrier arrivée</h1> 
                                    <form @submit.prevent class="flex items-center space-x-4">
                                        <input 
                                            v-model="numeroCA"
                                            type="search"
                                            id="default-search"
                                            aria-label="Rechercher"
                                            class="h-9 block w-96 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                border border-black placeholder:text-gray-400 
                                                focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                            placeholder="Entrez le numéro du courrier arrivée"
                                        />
                                    </form>
                                </div>
                            </div>
 
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <div class="container"> 
                                    <div class="card"> 
                                        <div class="card-body">
                                            <table class="table table-sm table-strictped table-bordered bg-gray-800 text-white">

                                                <thead sortedArrivees>
                                                    <tr>
                                                        <!-- <th scope="col"  class="bg-gray-800 text-white text-center">
                                                            N°
                                                        </th> -->
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            N° d'ordre d'arrivée
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Categorie
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Date Arrivée
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            N° Courier
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Date Courrier
                                                        </th> 
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Référence
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Caractère
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Designation
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Date
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Heure
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Lieu
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Nombre Pièce
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Objet
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Expéditeur
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Agent Traiteur
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Observation
                                                        </th>  
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            ACTIONS
                                                        </th>
                                                    </tr>
                                                </thead>
                                                        
                                                <tbody sortedArrivees>
                                                    <tr v-for="(arrivee, index) in filtereArrivees" :key="arrivee.id"  class="bg-white text-gray-800">
                                                        <!-- <td scope="col" class="px-6 py-3 text-center">
                                                            {{ index + 1 }}
                                                        </td> -->
                                                        <td scope="col" class="px-6 py-3 text-center"
                                                        :class="arrivee.isLateConvocation ? 'text-green-600 font-bold' : ''">
                                                            {{ arrivee.txt_numdordre }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_categorie || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ formatDate(arrivee.dt_datearrivee) || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_numcourier || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ formatDate(arrivee.dt_datecourier) }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_reference || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_caractere || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_designation || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ formatDate(arrivee.dt_date) || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_heure || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_lieu || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_nombrepiece || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_objet || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">  
                                                            {{ arrivee.txt_expediteur || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_agenttraiteur || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_observation || '-' }}
                                                        </td>
                                                
                                                        <td class="flex items px-6 py-6">
                                                            <div class="mt-2">
                                                                <InertiaLink :href="`/arrivee/editarrivee/${arrivee.id}`">
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
                                                                <p>.</p>
                                                            </div>
                                                            <div class="mt-2">
                                                                <MazBtn 
                                                                    color="danger" size="sm"   
                                                                    @click="() => supprimerCourrierArrivee(arrivee)"
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
                            </div><br>
                            
                            <br>
                            <div class="relative overflow-x-auto p-4 border-b bg-gray-100 mt-8">
                                <div class="flex justify-between items-center"> 
                                    <h1 class="text-lg font-semibold">Courrier Départ</h1> 
                                    <form @submit.prevent class="flex items-center space-x-4">
                                        <input 
                                            v-model="numeroCD"
                                            type="search"
                                            id="default-search"
                                            aria-label="Rechercher"
                                            class="h-9 block w-96 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                border border-black placeholder:text-gray-400 
                                                focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                            placeholder="Entrez le numéro du courrier départ"
                                        />
                                    </form>
                                </div>
                            </div>

                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <div class="container"> 
                                    <div class="card"> 
                                        <div class="card-body">
                                            <table class="table table-sm table-strictped table-bordered bg-gray-800 text-white">

                                                <thead sortedDeparts>
                                                    <tr>
                                                        <!-- <th scope="col"  class="bg-gray-800 text-white text-center">
                                                            N°
                                                        </th> -->
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            N° d'ordre départ
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Caractère
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Date Courrier
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Categorie
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Référence C.Arrivée
                                                        </th> 
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Référence C.Depart
                                                        </th> 
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Nombre Pièce
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Référence
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Objet
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Destinataire
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Date Envoi
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Référence Réception
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Observation
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Durée Traitement
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            ACTIONS
                                                        </th> 
                                                    </tr>
                                                </thead>
                                                        
                                                <tbody sortedDeparts>
                                                    <tr v-for="(depart, index) in filtereDeparts " :key="depart.id"  class="bg-white text-gray-800">
                                                        <!-- <td scope="col" class="px-6 py-3 text-center">
                                                            {{ index + 1 }}
                                                        </td> -->
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ depart.txt_numdordrecd }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ depart.txt_caracterecd || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ formatDate(depart.dt_datecourriercd) || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ depart.txt_categoriecd || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ depart.txt_referencecourierarriveecd || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ depart.txt_referencecourierdepartcd || '-' }}
                                                        </td> 
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ depart.txt_nombrepiececd || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ depart.txt_referencecd || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ depart.txt_objetcd || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ depart.txt_destinatairecd || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ formatDate(depart.dt_dateenvoicd) || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ depart.txt_referencereceptioncd || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ depart.txt_observationcd || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ depart.txt_dureetraitementcd || '-' }}
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
                                                                <p>.</p>
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
                            </div><br>

                            <br>
                            <div class="relative overflow-x-auto p-4 border-b bg-gray-100 mt-8">
                                <div class="flex justify-between items-center"> 
                                    <h1 class="text-lg font-semibold">Instances </h1>  
                                </div>
                            </div>
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <div class="container"> 
                                    <div class="card"> 
                                        <div class="card-body">
                                            <table class="table table-sm table-strictped table-bordered bg-gray-800 text-white">

                                                <thead sortedinstance>
                                                    <tr> 
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            N° d'ordre d'arrivée
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Categorie
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Date Arrivée
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            N° Courier
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Date Courrier
                                                        </th> 
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Référence
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Caractère
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Designation
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Date
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Heure
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Lieu
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Nombre Pièce
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Objet
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Expéditeur
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Agent Traiteur
                                                        </th>
                                                            <th scope="col" class="px-6 py-3 bg-gray-800 text-white text-center">
                                                            Observation
                                                        </th>   
                                                    </tr>
                                                </thead>
                                                        
                                                <tbody sortedinstance>
                                                    <tr v-for="(arrivee, index) in filteredInstance" :key="arrivee.id"  class="bg-white text-gray-800">
                                                        <!-- <td scope="col" class="px-6 py-3 text-center">
                                                            {{ index + 1 }}
                                                        </td> -->
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_numdordre }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_categorie || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ formatDate(arrivee.dt_datearrivee) || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_numcourier || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ formatDate(arrivee.dt_datecourier) }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_reference || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_caractere || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_designation || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ formatDate(arrivee.dt_date) || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_heure || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_lieu || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_nombrepiece || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_objet || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">  
                                                            {{ arrivee.txt_expediteur || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_agenttraiteur || '-' }}
                                                        </td>
                                                        <td scope="col" class="px-6 py-3 text-center">
                                                            {{ arrivee.txt_observation || '-' }}
                                                        </td>
                                                 
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><br>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

 
