<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed  } from 'vue';
import { Head } from '@inertiajs/vue3';
import { defineProps, onMounted } from 'vue';
import { router, usePage  } from '@inertiajs/vue3'
import { Link as InertiaLink } from '@inertiajs/vue3'
// import { toast } from 'vue3-toastify'

const props = defineProps({
    terrain: Object,
    terrains: Array,  
});
 
 
const numero = ref('')
const numeroRecherche = ref('')

// Au clic sur "Rechercher"
function searchDossier() {
  numeroRecherche.value = numero.value.trim()
}

// Normalise une chaîne pour la comparaison
function normalize(str) {
  return str?.toString().trim().toLowerCase()
}

// Normaliser le champ : tableau même si un seul objet
const evaluationsBatis = computed(() => {
  const eb = props.terrain?.evaluations_batis;

  if (!eb) return [];
  if (Array.isArray(eb)) return eb;
  return [eb];
});

const terrains = computed(() => {
    if (!numero.value) return props.terrains

    const search = normalize(numero.value)

    return props.terrains.filter(terrain => {
        const dossierNum = normalize(terrain.dossier?.txt_num_dossier)
        return dossierNum?.includes(search)
    })
})


// Fonction pour formater la date
const formatDate = (dateString) => {
    if (!dateString) return 'Date inconnue';
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};


// Suppromer enregistrement
function supprimerTerrain(terrain) {
    if (confirm(`Voulez-vous vraiment supprimer ce terrain avec le NICAD : ${terrain.txt_nicad} ?`)) {
        router.delete(route('terrains.destroy', terrain.id), {
            preserveScroll: true,
            onSuccess: () => {
                console.log('Terrain supprimé avec succès');
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
            <template v-if="!terrains || terrains.length === 0">
                <p>Aucun terrain trouvé.</p>
            </template>
           
        </template v-else>

        <div class="py-12">

            <div class="card">
                <form @submit.prevent="searchDossier" class="max-w-md mx-auto">
                    <div class="sm:col-span-4">
                        <label for="default-search"><b>Recherche Dossier</b></label>
                        <div class="flex items-center space-x-6">  
                            <input 
                                v-model="numero"
                                type="search"
                                id="default-search"
                                aria-label="Rechercher"
                                class="h-10 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                placeholder="Entrez le numéro du dossier"
                                required
                            />  
                        </div>
                    </div>
                </form>
            </div><br>
            
            <div class="mx-auto max-w-7xl sm:px-8 lg:px-12">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div class="container">

                        <div class="card">
                            <div class="card-header">
                                <div class="p-4 border-b bg-gray-100">
                                    <h1 class="text-lg font-semibold">Base de données</h1>
                                </div>
                            </div>

                            <div class="card-body">
                                <table class="table table-sm table-strictped table-bordered">
                                    <thead >
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                N°
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_dossier
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Region
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Departement
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Arrondisssement
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Commune
                                            </th> 
                                            <th scope="col" class="px-6 py-3">
                                                slt_service_dendu
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_etat_cession
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_cession_definitive
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                dt_date_creation
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Numéro_Parcelle
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_lotissement
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_HorsLotissement
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_lotissement
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_section
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_parcelle
                                            </th>
                                            <th>
                                                txt_appartement
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_titre
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_surface
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_document_admin
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_deliberation
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                dt_date_deliberation
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_nicad  
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                rd_immatricalation_terrain
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_dependant_domaine
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                ussu_bornage
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_titre_mere
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_lf
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_requisition
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_surface_bornage
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                dt_date_bornage
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_nom_geometre
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_titulaire
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_nationalite
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_civilite
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_prenom
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_nom
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_piece
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_numPiece
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                dt_date_delivrance
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                dt_date_naissance
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_lieu_naissance
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_adresse
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                tel_telephone
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_ninea
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                eml_email
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_representant
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_telRepresentant
                                            </th> 
                                            <thead> 
                                                <th scope="col" class="px-6 py-3">
                                                    <div class="justify-center">References Usages</div>
                                                    <tr> 
                                                        <th scope="col" class="px-6 py-3 font-normal">slt_usage</th>
                                                        <th scope="col" class="px-6 py-3 font-normal">slt_residence</th>
                                                        <th scope="col" class="px-6 py-3 font-normal">N° Appart</th>
                                                        <th scope="col" class="px-6 py-3 font-normal">Activité</th>
                                                        <th scope="col" class="px-6 py-3 font-normal">NINEA</th>
                                                        <th scope="col" class="px-6 py-3 font-normal">Téléphone</th>
                                                        <th scope="col" class="px-6 py-3 font-normal">Loyer</th>
                                                        <th scope="col" class="px-6 py-3 font-normal">Date/Lieu Naissance</th>
                                                        <th scope="col" class="px-6 py-3 font-normal">CNI / Passeport</th>
                                                        <th scope="col" class="px-6 py-3 font-normal">Date Délivrance</th>
                                                        <th scope="col" class="px-6 py-3 font-normal">Loyer Total</th>
                                                        <th scope="col" class="px-6 py-3 font-normal">TVA Total</th>
                                                    </tr>
                                                </th> 
                                            </thead>
                                            <!-- Bati -->
                                            <th scope="col" class="px-6 py-3">
                                                txt_surface_bati_sol
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_prix_metre_carre
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_superficie_totale
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_secteur
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_date_devaluation
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_terrain
                                            </th> 
                                            <th scope="col" class="px-6 py-3">
                                                currentCat
                                            </th>   
                                            <th scope="col" class="px-6 py-3">
                                                txt_valeur_terrain_bati
                                            </th>
                                            <!-- Domaine Principal -->
                                            <th scope="col" class="px-6 py-3">
                                                txt_dependant_domainePR
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_categoriePR
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_prix_metre_carrePR
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_surface_bati_solPR
                                            </th>    
                                            <th scope="col" class="px-6 py-3">
                                                nbr_niveauPR
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_surface_utilePR
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_coeffPR
                                            </th>        
                                            <th scope="col" class="px-6 py-3">
                                                nbr_surface_corrigerPR
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeurPR
                                            </th>
                                            <!-- Dimaine Suivants -->


                                            <th scope="col" class="px-6 py-3">
                                                slt_dependant_domaineTG
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_categorieTG
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_prix_metre_carreTG
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_surface_bati_solTG
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_niveauTG
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_surface_utileTG
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_coeffTG
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_surface_corrigerTG
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeurTG
                                            </th> 

                            

                                            <!-- Cours Amenagers -->
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_total_ca
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_cours_amenager_totale
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_surface_ca_total
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_categorie_ca_total
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_prix_metre_carre_ca_total
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_coefficient_ca_total
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_ca_total
                                            </th>
                                            <!-- Clotures -->
                                            <th scope="col" class="px-6 py-3">
                                                slt_cours_amenager_clo
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_longueur_avant_clo
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_categorie_clo
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_prix_metre_carre_clo
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_coefficient_clo
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_clo
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_total_clotur
                                            </th>
                                            <!-- Amenagements -->
                                            <th scope="col" class="px-6 py-3">
                                                txt_designation_am
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_unitaire_am
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_quantile_am
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_coeficien_am
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_am
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_totale_ap
                                            </th>

                                            <th scope="col" class="px-6 py-3">
                                                ACTIONS
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
 
                                        <tr v-for="terrain in terrains" :key="terrain.id"  
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ terrain.dossier ? terrain.dossier.txt_num_dordre : 'Dossier inconnu' }}
                                            </th>
                                            <td class="px-6 py-4">
                                                {{ terrain.dossier ? terrain.dossier.txt_num_dossier : 'Dossier inconnu' }} 
                                            </td> 
                                            <td class="px-6 py-4">
                                                {{ terrain.region ? terrain.region.slt_region : 'Région inconnue' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.departement ? terrain.departement.slt_departement : 'Département inconnu' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.arrondissement ? terrain.arrondissement.slt_arrondissement : 'Arrondissement inconnue' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.commune ? terrain.commune.slt_commune : 'Commune inconnue' }}
                                            </td> 
                                            <td class="px-6 py-4">
                                                {{ terrain.dossier.slt_service_rendu || 'Service inconnu' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.dossier.txt_etat_cession || 'Null'}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.dossier.txt_cession_definitive || 'Null'}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ formatDate(terrain.dt_date_deliberation) || 'Null' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.txt_num_parcelle || 'Null'}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.txt_lotissement || 'Null'}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.txt_HorsLotissement || 'Null'}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.txt_num_lotissement || 'Null'}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.txt_num_section || 'Null'}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.txt_num_parcelle || 'Null'}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.txt_appartement || 'Null'}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.txt_num_titre || 'Null'}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.nbr_surface || 'Null'}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.slt_document_admin || 'Null'}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.txt_num_deliberation || 'Null'}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ formatDate(terrain.dt_date_deliberation) || 'Null'}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ terrain.txt_nicad || 'Null'}}  
                                            </td>
                                            <!--  -->
                                            <td scope="col" class="px-6 py-3">
                                                {{ terrain.references_cadastrales?.rd_immatriculation_terrain || 'Null' }}   
                                            </td>
                                            <td scope="col" class="px-6 py-3">
                                                {{terrain.references_cadastrales?.slt_dependant_domaine || 'Null'}}
                                            </td>
                                            <td scope="col" class="px-6 py-3">
                                                {{terrain.references_cadastrales?.ussu_bornage || 'Null'}}
                                            </td>
                                            <td scope="col" class="px-6 py-3">{{terrain.references_cadastrales?.txt_titre_mere || 'Null'}}
                                            </td>
                                            <td scope="col" class="px-6 py-3">{{terrain.references_cadastrales?.slt_lf || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.references_cadastrales?.txt_num_requisition || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.references_cadastrales?.txt_surface_bornage || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{formatDate(terrain.references_cadastrales?.dt_date_bornage) || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.references_cadastrales?.txt_nom_geometre || 'Null'}}</td>
                                            <!-- Titulaire -->
                                            <td scope="col" class="px-6 py-3">{{ terrain.titulaire && terrain.titulaire.slt_titulaire ? terrain.titulaire.slt_titulaire : 'Nom definie' }} </td>
                                            <td scope="col" class="px-6 py-3">{{terrain.titulaire?.txt_nationalite || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.titulaire?.slt_civilite || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.titulaire?.txt_prenom || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.titulaire?.txt_nom || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.titulaire?.slt_piece || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.titulaire?.txt_numPiece || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{formatDate(terrain.titulaire?.dt_date_delivrance) || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{formatDate(terrain.titulaire?.dt_date_naissance) || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.titulaire?.txt_lieu_naissance || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.titulaire?.txt_adresse || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.titulaire?.tel_telephone || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.titulaire?.txt_ninea || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.titulaire?.eml_email || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.titulaire?.txt_representant || 'Null'}}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.titulaire?.tel_telRepresentant || 'Null'}}</td>

                                            <template v-if="terrain.references_usages && terrain.references_usages.length > 0">
                                                <tr v-for="usage in terrain.references_usages" :key="usage.id"
                                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    
                                                    <td scope="col" class="px-6 py-3">{{usage.slt_usage || 'Null' }}</td>
                                                    <td scope="col" class="px-6 py-3">{{usage.slt_residence || 'Null' }}</td>
                                                    <td scope="col" class="px-6 py-3">{{ usage.txt_numAppartementTG || 'Null' }}</td>
                                                    <td scope="col" class="px-6 py-3">{{ usage.txt_activiteTG || 'Null' }}</td>
                                                    <td scope="col" class="px-6 py-3">{{ usage.txt_nineaTG || 'Null' }}</td>
                                                    <td scope="col" class="px-6 py-3">{{ usage.tel_telephoneTG || 'Null' }}</td>
                                                    <td scope="col" class="px-6 py-3">{{ usage.nbr_montantLoyerTG || 'Null' }}</td>
                                                    <td scope="col" class="px-6 py-3">{{ usage.txt_dateLieuNaissanceTG || 'Null' }}</td>
                                                    <td scope="col" class="px-6 py-3">{{ usage.txt_cniPasseportTG || 'Null' }}</td>
                                                    <td scope="col" class="px-6 py-3">{{ usage.dt_dateDelivranceTG || 'Null' }}</td>
                                                    <td scope="col" class="px-6 py-3">{{ usage.nbr_montantLoyerTotal || 'Null' }}</td>
                                                    <td scope="col" class="px-6 py-3">{{ usage.nbr_TVATotal || 'Null' }}</td>
                                                </tr>
                                            </template> 
                                            <template v-else>
                                                    <tr><td colspan="10" class="text-center text-gray-500 italic">Aucune donnée d’usage (Null)</td></tr>
                                            </template>

                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_terrains?.txt_superficie_bati_sol || 'Null' }}</td> 
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_terrains?.nbr_prix_metre_carre || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_terrains?.nbr_surface || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_terrains?.slt_secteur || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_terrains?.txt_date_devaluation  || 'Null'   }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_terrains?.nbr_valeur_terrain || 'Null' }}</td>

                                            <!-- Domaine Principal -->
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_batis?.currentCat || 'Null' }}</td>   
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_batis?.txt_valeur_terrain_bati || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_batis?.txt_dependant_domainePR || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_batis?.slt_categoriePR || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_batis?.nbr_prix_metre_carrePR || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_batis?.nbr_surface_bati_solPR || 'Null' }}</td>    
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_batis?.nbr_niveauPR || 'Null'  }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_batis?.nbr_surface_utilePR  || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_batis?.slt_coeffPR || 'Null' }}</td>        
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_batis?.nbr_surface_corrigerPR ||  'Null'  }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_batis?.nbr_valeurPR || 'Null' }}</td>

                                            <!-- Domaine Suivants --> 
                                            <td  scope="col" class="px-6 py-3">{{ terrain.evaluations_batis?.slt_dependant_domaineTG || 'Null' }}</td>
                                            <td  scope="col" class="px-6 py-3">{{ terrain.evaluations_batis?.slt_categorieTG || 'Null' }}</td>
                                            <td  scope="col" class="px-6 py-3">{{ terrain.evaluations_batis?.nbr_prix_metre_carreTG || 'Null' }}</td>
                                            <td  scope="col" class="px-6 py-3">{{ terrain.evaluations_batis?.nbr_surface_bati_solTG || 'Null' }}</td>
                                            <td  scope="col" class="px-6 py-3">{{ terrain.evaluations_batis?.nbr_niveauTG || 'Null' }}</td>
                                            <td  scope="col" class="px-6 py-3">{{ terrain.evaluations_batis?.nbr_surface_utileTG || 'Null' }}</td>
                                            <td  scope="col" class="px-6 py-3">{{ terrain.evaluations_batis?.slt_coeffTG || 'Null' }}</td>
                                            <td  scope="col" class="px-6 py-3">{{ terrain.evaluations_batis?.nbr_surface_corrigerTG || 'Null' }}</td>
                                            <td  scope="col" class="px-6 py-3">{{ terrain.evaluations_batis?.nbr_valeurTG || 'Null' }}</td>
                                          
                                            <!-- Cours amenagers  -->
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_cours_amenagees?.nbr_valeur_total_ca || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_cours_amenagees?.slt_cours_amenager_totale || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_cours_amenagees?.nbr_surface_ca_total ||  'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_cours_amenagees?.slt_categorie_ca_total || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_cours_amenagees?.nbr_prix_metre_carre_ca_total  || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_cours_amenagees?.nbr_coefficient_ca_total ||  'Null'  }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_cours_amenagees?.nbr_valeur_ca_total || 'Null'  }}</td> 

                                            <!-- Cours cloture  -->
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_clotures?.slt_cours_amenager_clo || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_clotures?.nbr_longueur_avant_clo  ||  'Null'  }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_clotures?.slt_categorie_clo   ||  'Null'  }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_clotures?.nbr_prix_metre_carre_clo || 'Null'  }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_clotures?.nbr_coefficient_clo ||  'Null'  }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_clotures?.nbr_valeur_clo || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_clotures?.nbr_valeur_total_clotur || 'Null' }}</td>
                                            <!-- Amenagement -->
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_amenagements?.txt_designation_am  || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_amenagements?.nbr_valeur_unitaire_am  ||  'Null'  }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_amenagements?.nbr_quantile_am || 'Null' }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_amenagements?.slt_coeficien_am    ||  'Null'  }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_amenagements?.nbr_valeur_am   ||  'Null'  }}</td>
                                            <td scope="col" class="px-6 py-3">{{terrain.evaluations_amenagements?.nbr_valeur_totale_ap    ||  'Null'  }}</td>
 
                                            <td class="flex items px-6 py-6">
                                                <div class="mt-2">
                                                    <InertiaLink :href="`/secretariat/edit/${terrain.id}`">
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
                                                        @click="() => supprimerTerrain(terrain)"
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
    </AuthenticatedLayout>
</template>

 
