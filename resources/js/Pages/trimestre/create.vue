<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed  } from 'vue';
import { Head } from '@inertiajs/vue3';
import { defineProps, onMounted } from 'vue';
import { router, usePage  } from '@inertiajs/vue3'
import { Link as InertiaLink } from '@inertiajs/vue3'
  
// import { toast } from 'vue3-toastify' 
const props = defineProps({
  trimestres: Array,
  morcellementsCount: Number
});

// const page = usePage();
// const reçus = ref(page.props.reçus || {}); // ✅ sécurise même si c'est undefined


// Morcellements
// Réquisition d’immatriculation
// Demande de terrain / Echange
// Prospection de terrain
// Autorisation de construire
// Autorisation de lotir
// Demande d’états des lieux
// Demande de délimitation/reconstitution de limites
// Réquisition DSCOS, Tribunal, Litiges…
// Demande d’extraits de plan (pour situation, autorisation de construire, régularisation …)
// Demande de situation foncière
// Demande de Cession définitive
// Demande de Cession définitive à Titre Gratuit
// Demande de Régularisation
// Demande d’attestation du Cadastre
// Projets de Lotissements reçus
// Réceptions de lotissements
// Lotissements réalisés sans respect des procédures
// Demande de CIC
// Duplicata Titre foncier
// Autorisation de morceler
// Demande d’évaluation (Surface corrigée, expertise judiciaire.)
// Nombre de fiches de mise à jour reçues 
// Nombre de dossiers techniques en attente de fiches de mise à jour

// filtrer les instances par Trimestre
const currentMonth = new Date().getMonth() + 1;

const trimestreLabel = computed(() => {
    if ([1, 2, 3].includes(currentMonth)) return '1er trimestre';
    if ([4, 5, 6].includes(currentMonth)) return '2ème trimestre';
    if ([7, 8, 9].includes(currentMonth)) return '3ème trimestre';
    if ([10, 11, 12].includes(currentMonth)) return '4ème trimestre';
    return 'Inconnu';
});

// Groupe les éléments par libellé de trimestre
const groupedByTrimestre = computed(() => {
    return props.trimestres.reduce((acc, t) => {
        const mois = new Date(t.dt_datearrivee).getMonth() + 1;
        const trimestre = currentTrimestre(mois);

        if (!acc[trimestre]) acc[trimestre] = [];
        acc[trimestre].push(t);

        return acc;
    }, {});
});
 
   
// Compter le nombre d'instance
const filteredTrimestres = computed(() => {
  return props.trimestres.filter(t => t.isOverdue);
});


const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

console.log('trimestres :', trimestres);
console.log('morcellementsCount :', morcellementsCount);
 
</script>

<template>
    <Head title="Trimestre" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-primary-txt">
                Trimestres
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div>
                        <h2 class="font-bold text-lg text-primary-txt mb-2"> Timestre : {{ trimestreLabel }}</h2>
                    </div>
 
                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="px-4 py-2 text-left text-white text-sm font-bold">N°</th>
                                <th class="px-4 py-2 text-left text-white text-sm font-bold">Désignation</th>
                                <th class="px-4 py-2 text-left text-white text-sm font-bold">Stock Fin Précédent</th>
                                <th class="px-4 py-2 text-left text-white text-sm font-bold">Reçus</th>
                                <th class="px-4 py-2 text-left text-white text-sm font-bold">Traités</th>
                                <th class="px-4 py-2 text-left text-white text-sm font-bold">Retournés</th>
                                <th class="px-4 py-2 text-left text-white text-sm font-bold">Instances</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(trimestre, index) in props.trimestres" :key="trimestre.id" class="border-t">
                                <td class="px-4 py-2 text-left text-primary-txt text-sm">{{ index + 1 }}</td>
                                <td class="px-4 py-2 text-left text-primary-txt text-sm ">{{ trimestre.txt_designationtri ?? '-' }}</td>
                                <td class="px-4 py-2 text-primary-txt text-sm text-center">{{ props.morcellementsCount  }}</td>
                                <td class="px-4 py-2 text-primary-txt text-sm text-center">{{ morcellementsCount }}</td>
                                <td class="px-4 py-2 text-primary-txt text-sm text-center">-</td>
                                <td class="px-4 py-2 text-primary-txt text-sm text-center">-</td>
                                <td class="px-4 py-2 text-primary-txt text-sm text-center">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

 
