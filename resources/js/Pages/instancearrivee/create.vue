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
});
 

const numeroCA = ref('');  
const annee = ref('');

// Normalisation pour éviter les problèmes d'espaces et de casse
function normalize(str) {
    return str?.toString().trim().toLowerCase();
}
 
const filtereArrivees = computed(() => {
    const now = new Date();

    const addLateFlag = (arrivee) => {
        const dtDate = arrivee.dt_date ? new Date(arrivee.dt_date) : null;

        return {
            ...arrivee,
            isLateConvocation:
                arrivee.txt_categorie === 'Convocation - Invitation' &&
                dtDate &&
                (dtDate - now) > 0 && // s'assurer que la date est dans le futur
                (dtDate - now) <= (72 * 60 * 60 * 1000) // inférieur ou égal à 72h
        };
    }; 

    const filtered = props.arrivees.filter(arrivee => {
        const matchNumero = numeroCA.value
            ? normalize(arrivee.txt_numdordre)?.includes(normalize(numeroCA.value))
            : true;

        const matchAnnee = annee.value
            ? new Date(arrivee.dt_datearrivee).getFullYear().toString() === annee.value
            : true;

        return matchNumero && matchAnnee;
    });

    return filtered.map(addLateFlag);
});
  
// Compter le nombre d'invitation dans 72H 
const totalConvocationsImminentes = computed(() => {
    const now = new Date();

    return props.arrivees.filter(arrivee => {
        const dtDate = arrivee.dt_date ? new Date(arrivee.dt_date) : null;

        return (
            arrivee.txt_categorie === 'Convocation - Invitation' &&
            dtDate &&
            (dtDate - now) > 0 &&
            (dtDate - now) <= (72 * 60 * 60 * 1000)
        );
    }).length;
});

// Compter le nombre total de courrier
const totalCourrier = computed(() => {
    return props.arrivees.filter(totalarrivee => !!totalarrivee.txt_numdordre).length;
});

// format de la date
const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

// fonction d'envoi du formulaire
function supprimerCourrierArrivee(arrivee) {
    if (confirm(`Voulez-vous vraiment supprimer le courrier arrivée N° ${arrivee.txt_numdordre} ?`)) {
        router.delete(route('instancearrivee.destroy', arrivee.id), {
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
   
</script>

<template>
    <Head title="Instances Arrivées">
        <link rel="icon" sizes="512x512" href="/logo-01.png">
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-primary-txt"
            >
                Liste des Courriers Arrivés
            </h2>
            
            <!-- Vérification pour afficher un message si aucune donnée -->
            <template class="text-xl font-semibold leading-tight text-primary-txt" v-if="(!arrivees || arrivees.length === 0)">
                <p>Aucun Courrier arrivée trouvé.</p>
            </template>
           
        </template v-else>

        <div class="py-12">
            <div class="flex justify-center">
                <div class="w-full max-w-7xl">
                    <div class="bg-white shadow-md rounded-lg "> <br> 
                        <div class="mx-auto max-w-7xl sm:px-8 lg:px-12 mt-4 mb-4">
                            <div class="card-header">
                                <div class="p-4 border-b bg-primary-form">
                                    <h1 class="text-3xl text-primary-txt font-bold text-center">Base de donnée des Courriers arrivées</h1>
                                </div>
                            </div>

                            <div class="relative overflow-x-auto p-4 border-b bg-primary-form mt-8">
                                <div class="flex justify-between items-center"> 
                                    <h1 class="text-2xl text-primary-txt font-semibold">
                                        Liste des Courriers Arrivés : 
                                        <span v-if="totalCourrier>0" class="text-gray-600">
                                            ({{ totalCourrier }})
                                        </span>
                                        <span v-else class="text-red-600">
                                            Aucun enregistrement
                                        </span> 
                                    </h1> 
                                    <h2 class="text-lg text-primary-txt">
                                        Nombre d'invitation dans 72H : 
                                        <span class="text-gray-600  font-bold">{{ totalConvocationsImminentes }}</span>
                                    </h2>
                                    <form @submit.prevent class="flex items-center space-x-4">
                                        <div  class="flex items-start space-x-4">
                                            <div>
                                                <input 
                                                    v-model="annee"
                                                    type="search"
                                                    maxlength="4"
                                                    id="default-search"
                                                    aria-label="Rechercher"
                                                    class="h-9 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                        border border-primary-menu  placeholder:text-gray-400 
                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    placeholder="année"
                                                /> 
                                            </div>
                                        
                                            <div>
                                                <input 
                                                    v-model="numeroCA"
                                                    type="search"
                                                    id="default-search"
                                                    aria-label="Rechercher"
                                                    class="h-9 block w-50 rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                        border border-primary-menu  placeholder:text-gray-400 
                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    placeholder="Entrez numéro courrier"
                                                />
                                            </div> 
                                        </div>
                                    </form>
                                </div>
                            </div>
 
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-8">
                                <div class="container"> 
                                    <div class="card"> 
                                        <div class="card-body">
                                            <table class="table table-sm table-strictped table-bordered bg-primary text-white ">

                                                <thead  sortedArrivees>
                                                    <tr class="h-20">
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">N°</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">N° d'ordre d'arrivée</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Date Récéption</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Categorie</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">N° Courier</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Date Courrier</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Référence</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Caractère</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Designation</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Date</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Heure</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Lieu</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Nombre Pièce</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Objet</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Expéditeur</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Agent Traiteur</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Observation</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-center border-l border-primary-only font-bold whitespace-nowrap">Fichier PDF</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary-only text-primary-txt text-center border-l border-primary-only font-bold whitespace-nowrap">ACTIONS</th>
                                                    </tr>
                                                </thead>
                                                        
                                                <tbody sortedArrivees>
                                                    <tr v-for="(arrivee, index) in filtereArrivees" :key="arrivee.id"  class="bg-white text-primary-txt h-10">
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only text-primary-txt font-bold whitespace-nowrap">{{ index + 1 }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap" :class="arrivee.isLateConvocation ? 'text-green-600 font-bold' : ''">{{ arrivee.txt_numdordre }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ formatDate(arrivee.dt_datearrivee) || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ arrivee.txt_categorie || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ arrivee.txt_numcourier || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ formatDate(arrivee.dt_datecourier) }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ arrivee.txt_reference || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ arrivee.txt_caractere || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ arrivee.txt_designation || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ formatDate(arrivee.dt_date) || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ arrivee.txt_heure || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ arrivee.txt_lieu || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ arrivee.txt_nombrepiece || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ arrivee.txt_objet || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ arrivee.txt_expediteur || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ arrivee.txt_agenttraiteur || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only whitespace-nowrap">{{ arrivee.txt_observation || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center border border-primary-only font-bold whitespace-nowrap"> 
                                                            <div v-if="arrivee.fichierPDF">
                                                                <a :href="`/storage/${arrivee.fichierPDF}`" target="_blank" class="text-blue-600 underline">
                                                                    Voir PDF
                                                                </a>
                                                            </div>
                                                            <div v-else class="text-primary-txt italic">Aucun fichier PDF</div>
                                                        </td>
                                                
                                                        <td class="flex items px-6 py-6 border border-primary-only whitespace-nowrap">
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
                                                                <p></p>
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
                            

                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

 
