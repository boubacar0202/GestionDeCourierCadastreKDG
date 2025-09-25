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
  
// Normalisation pour éviter les problèmes d'espaces et de casse
function normalize(str) {
    return str?.toString().trim().toLowerCase();
}

// filtrer les instances par categorie  
const filteredInstance = computed(() => {
    const now = new Date();

    const addIsOverdueFlag = (arrivee) => {
        const dtCourrier = arrivee.dt_datearrivee ? new Date(arrivee.dt_datearrivee) : null;

        return {
            ...arrivee,
            isOverdue:
                dtCourrier &&
                (now - dtCourrier) >= (10 * 24 * 60 * 60 * 1000) // plus de 10 jours
        };
    };

    return props.arrivees
        .filter(arrivee => {
            const existeDansDepart = props.departs.some(
                depart => depart.txt_destinatairecd === arrivee.txt_expediteur
            );
            return arrivee.txt_categorie === 'Demande SERVICES' && !existeDansDepart;
        })
        .map(addIsOverdueFlag);
});
   
// Compter le nombre d'instance
const totalInstance = computed(() => {
    return filteredInstance.value.filter(instance => instance.isOverdue).length;
});

// Compter le nombre total de courrier
const totalCourrierInstance = computed(() => {
    return filteredInstance.value.filter(totalCourrierInstance => !!totalCourrierInstance.txt_numdordre).length;
});

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

 
</script>

<template>
    <Head title="Instances">
        <link rel="icon" sizes="512x512" href="/logo-01.png">
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-primary-txt"
            >
                Courriers en Insatnce
            </h2>
            
            <!-- Vérification pour afficher un message si aucune donnée -->
            <template class="text-xl font-semibold leading-tight text-primary-dark" v-if="(!arrivees || arrivees.length === 0)">
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
                                    <h1 class="text-3xl font-bold text-center text-primary-txt">Liste des Courriers en Instance</h1>
                                </div>
                            </div> 
 
                            <div class="relative overflow-x-auto p-4 border-b bg-primary-form mt-8">
                                <div class="flex justify-between items-center"> 
                                    <h1 class="text-2xl text-primary-txt font-semibold">
                                        Instances 
                                        <span v-if="totalCourrierInstance>0" class="text-gray-600">
                                            ({{ totalCourrierInstance }})
                                        </span>
                                        <span v-else class="text-red-600">
                                            Aucun enregistrement
                                        </span> 
                                    </h1>  
                                    <h1 class="text-lg text-primary-txt  font-semibold">Nombres d'Instances (+10 jours) :  {{ totalInstance }}</h1>   
                                </div>
                            </div>
                            <div class="max-h-[500px] overflow-y-auto shadow-md sm:rounded-lg mt-8">
                                <div class="container"> 
                                    <div class="card"> 
                                        <div class="card-body">
                                            <table class="table table-sm table-strictped table-bordered bg-primary text-white">

                                                <thead class="sticky top-0 z-10">
                                                    <tr class="h-20">  
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">N°</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">N° d'ordre d'arrivée</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Catégorie</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Date Arrivée</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">N° Courrier</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Date Courrier</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Référence</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Caractère</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Désignation</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Date</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Heure</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Lieu</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Nombre Pièces</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Objet</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Expéditeur</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Agent Traiteur</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Observation</th>
                                                        <th scope="col" class="px-6 py-3 bg-primary text-white text-sm border-l border-primary-only font-bold whitespace-nowrap">Fichier PDF</th> 
                                                    </tr>
                                                </thead>
                                                        
                                                <tbody sortedinstance>
                                                    <tr v-for="(arrivee, index) in filteredInstance" :key="arrivee.id"  class="bg-white text-gray-800">
                                                        <td scope="col" class="sticky left-0 z-0 border bg-white px-6 py-3 text-center text-sm text-primary-txt font-bold whitespace-nowrap border border-primary-only">{{ index + 1 }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only" :class="arrivee.isOverdue ? 'text-red-600 font-bold' : ''">{{ arrivee.txt_numdordre }}</td> 
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ arrivee.txt_categorie || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ formatDate(arrivee.dt_datearrivee) || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ arrivee.txt_numcourier || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ formatDate(arrivee.dt_datecourier) }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ arrivee.txt_reference || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ arrivee.txt_caractere || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ arrivee.txt_designation || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ formatDate(arrivee.dt_date) || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ arrivee.txt_heure || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ arrivee.txt_lieu || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ arrivee.txt_nombrepiece || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ arrivee.txt_objet || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ arrivee.txt_expediteur || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ arrivee.txt_agenttraiteur || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm whitespace-nowrap border border-primary-only">{{ arrivee.txt_observation || '-' }}</td>
                                                        <td scope="col" class="px-6 py-3 text-center text-sm font-bold whitespace-nowrap border border-primary-only"> 
                                                            <div v-if="arrivee.fichierPDF">
                                                                <a :href="`/storage/${arrivee.fichierPDF}`" target="_blank" class="text-blue-600 underline">
                                                                    Voir PDF
                                                                </a>
                                                            </div>
                                                            <div v-else class="text-gray-400 italic">Aucun fichier PDF</div>
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

 
