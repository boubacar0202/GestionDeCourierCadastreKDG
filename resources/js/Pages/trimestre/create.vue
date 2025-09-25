<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed  } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { router } from '@inertiajs/vue3'
import { Link as InertiaLink } from '@inertiajs/vue3'
  
// import { toast } from 'vue3-toastify' 
const props = defineProps({

    arrivee: Object,    
    arrivees: Array,

    departs: Array,
    depart: Object,
 
    trimestres: Array,
    countsByTrimestre: Object,
    countsTraiterByTrimestre: Object,
    stockFin: Object,
    instancesRestantes: Object,
 
});
 
const designations = [
    "Morcellements",
    "Réquisition d\'immatriculation",
    "Demande de terrain / Echange",
    "Prospection de terrain",
    "Autorisation de construction",
    "Autorisation de lotir",
    "Demande d\'états des lieux",
    "Demande de délimitation/reconstruction",
    "Réquisition DSCOS, Tribunal, Litiges",
    "Demande d’extraits de plan",
    "Demande de situation foncière",
    "Demande de Cession définitive",
    "Demande de Cession définitive a Titre Gratuit",
    "Demande de Régularisation",
    "Demande d\'attestation du Cadastre",
    "Projets de Lotissements reçus",
    "Réceptions de lotissements",
    "Lotissements réalisés sans respect des procédures",
    "Demande de CIC",
    "Demande de Titre foncier",
    "Autirisationde morceler",
    "Demande d’évaluation",
    "Nombre de fiches de mise à jour reçues",
    "Nombre de dossiers techniques en attente de fiches de mise à jour"
];

const counts = computed(() => {
    const res = {};
    designations.forEach(d => res[d] = { recus: 0, traites: 0 });

    // sécuriser pour éviter l'erreur si props.arrivees ou props.departs est undefined
    const arrivees = props.arrivees ?? [];
    const departs = props.departs ?? [];

    arrivees.forEach(a => {
        for (const d of designations) {
        if (a.txt_designation && a.txt_designation.toLowerCase().includes(d.toLowerCase())) {
            res[d].recus++;

            const ref = (a.txt_reference ?? a.txt_reference ?? a.id ?? '').toString().trim();
            const estTraite = ref && departs.some(dep =>
            dep.txt_referencecourierarriveecd &&
            dep.txt_referencecourierarriveecd.toString().includes(ref) &&
            dep.txt_categoriecd === 'Reponse à un Courrier arrivé'
            );

            if (estTraite) res[d].traites++;
            break;
        }
        }
    });

    return res;
});
 

// Fonction pour obtenir le trimestre d’un mois
const currentTrimestre = (mois) => {
    if ([1, 2, 3].includes(mois)) return '1er trimestre';
    if ([4, 5, 6].includes(mois)) return '2ème trimestre';
    if ([7, 8, 9].includes(mois)) return '3ème trimestre';
    if ([10, 11, 12].includes(mois)) return '4ème trimestre';

    return 'Inconnu';
};

// Trimestre actuel (ex: "2ème trimestre")
const currentMonth = new Date().getMonth() + 1;
const trimestreLabel = computed(() => currentTrimestre(currentMonth));

// Groupe les éléments par trimestre
const groupedByTrimestre = computed(() => {
    return props.trimestres.reduce((acc, t) => {
        const mois = new Date(t.dt_datearrivee).getMonth() + 1;
        const trimestre = currentTrimestre(mois);

        if (!acc[trimestre]) acc[trimestre] = [];
        acc[trimestre].push(t);

        return acc;
    }, {});
});

// Filtre
const filteredTrimestres = computed(() => {
  return props.trimestres.filter(t => t.isOverdue);
});

// Formateur de dates
const formatDate = (dateString) => {
  if (!dateString) return '-';
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  });
};

// Debug (corrigé)
console.log('trimestres :', props.trimestres);
console.log('morcellementsCount :', props.morcellementsCount);
 

// Compte combien de morcellements par trimestre
const countsByTrimestre = computed(() => {
    return props.trimestres.reduce((acc, t) => {
        const mois = new Date(t.dt_datearrivee).getMonth() + 1;
        const trimestre = currentTrimestre(mois);

        if (!acc[trimestre]) acc[trimestre] = 0;
        acc[trimestre]++;

        return acc;
    }, {});
});

  
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
                                <th class="px-4 py-2 text-left border border-b text-white text-sm font-bold">N°</th>
                                <th class="px-4 py-2 text-left border border-b text-white whitespace-nowrap text-sm font-bold">Désignation</th>
                                <th class="px-4 py-2 text-left border border-b text-white text-sm font-bold">Stock Fin Précédent</th>
                                <th class="px-4 py-2 text-left border border-b text-white text-sm font-bold">Reçus</th>
                                <th class="px-4 py-2 text-left border border-b text-white text-sm font-bold">Traités</th>
                                <th class="px-4 py-2 text-left border border-b text-white text-sm font-bold">Retournés</th>
                                <th class="px-4 py-2 text-left border border-b text-white text-sm font-bold">Instances</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(designation, index) in designations" :key="index" class="border-t">
                                <td class="px-4 py-2 text-left border border-b text-primary-txt text-sm">{{ index + 1 }}</td>
                                <td class="px-4 py-2 text-left border border-b text-primary-txt text-sm "> {{ designation }}</td>
                                <td class="px-4 py-2 border border-b text-primary-txt font-bold text-sm text-center">
                                    {{ props.stockFin[designation] ?? 0 }}
                                </td>
                                <!-- 👇 On met la valeur trouvée ou 0 si absente -->
                                <td class="px-4 py-2 border border-b text-primary-txt font-bold text-sm text-center">
                                    {{ props.countsByTrimestre[designation] ?? 0 }}
                                </td>                                
                                <td class="px-4 py-2 border border-b text-primary-txt font-bold text-sm text-center">
                                    {{ props.countsTraiterByTrimestre[designation] ?? 0 }}
                                </td>
                                <td class="px-4 py-2 border border-b text-primary-txt text-sm text-center"> A.complèter</td>
                                <td class="px-4 py-2 border border-b text-primary-txt font-bold text-sm text-center">
                                    {{props.instancesRestantes[designation] ?? 0}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

 