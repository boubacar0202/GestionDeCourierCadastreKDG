<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Pas besoin de créer terrain comme ref si c'est une prop
const props = defineProps({
  terrain: Object
});

// Computed pour obtenir les valeurs conditionnelles pour les champs
const dossier = computed(() => props.terrain.dossier || {});
const region = computed(() => props.terrain.region || {});
const departement = computed(() => props.terrain.departement || {});
const commune = computed(() => props.terrain.commune || {});
const referencesCadastrales = computed(() => props.terrain.references_cadastrales || {});
const titulaire = computed(() => props.terrain.titulaire || {});

const submitForm = () => {
  // Logique pour soumettre le formulaire de modification
  this.$inertia.put(`/donnee/update/${props.terrain.id}`, {
    dossier: dossier.value,
    region: region.value,
    departement: departement.value,
    commune: commune.value,
    references_cadastrales: referencesCadastrales.value,
    titulaire: titulaire.value,
    // Ajoute d'autres données que tu veux mettre à jour
  });
};
</script>

<template>
  <Head title="Donnee"/>

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Base de données
      </h2>
      <!-- Vérification pour afficher un message si aucune donnée -->
      <template v-if="!props.terrain || !props.terrain.length">
        <p>Aucun terrain trouvé.</p>
      </template>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-8 lg:px-12">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
          <div class="container">
            <div class="card">
              <div class="card-header">
                <div class="p-4 border-b bg-gray-100">
                  <h1 class="text-lg font-semibold">Base de données</h1>
                </div>
                <form @submit.prevent="submitForm">
                  <div class="grid gap-4">
                    <div>
                      <label for="dossier">Dossier</label>
                      <input type="text" v-model="dossier.txt_num_dossier" placeholder="Numéro de Dossier" />
                    </div>
                    <div>
                      <label for="region">Région</label>
                      <input type="text" v-model="region.slt_region" placeholder="Région" />
                    </div>
                    <div>
                      <label for="departement">Département</label>
                      <input type="text" v-model="departement.slt_departement" placeholder="Département" />
                    </div>
                    <div>
                      <label for="commune">Commune</label>
                      <input type="text" v-model="commune.slt_commune" placeholder="Commune" />
                    </div>

                    <!-- Dossier Section -->
                    <div>
                      <label for="dossier_num_dordre">Numéro d'ordre du Dossier</label>
                      <input type="text" v-model="dossier.txt_num_dordre" placeholder="Numéro d'ordre" />
                    </div>
                    <div>
                      <label for="dossier_service">Service Rendu</label>
                      <input type="text" v-model="dossier.slt_service_rendu" placeholder="Service Rendu" />
                    </div>

                    <!-- Informations sur la Cession -->
                    <div>
                      <label for="txt_etat_cession">État de la Cession</label>
                      <input type="text" v-model="dossier.txt_etat_cession" placeholder="État de la Cession" />
                    </div>
                    <div>
                      <label for="txt_cession_definitive">Cession Définitive</label>
                      <input type="text" v-model="dossier.txt_cession_definitive" placeholder="Cession Définitive" />
                    </div>

                    <!-- Autres champs -->
                    <div>
                      <label for="txt_num_parcelle">Numéro de Parcelle</label>
                      <input type="text" v-model="props.terrain.txt_num_parcelle" placeholder="Numéro de Parcelle" />
                    </div>
                    <div>
                      <label for="txt_lotissement">Lotissement</label>
                      <input type="text" v-model="props.terrain.txt_lotissement" placeholder="Lotissement" />
                    </div>
                    <div>
                      <label for="txt_num_lotissement">Numéro de Lotissement</label>
                      <input type="text" v-model="props.terrain.txt_num_lotissement" placeholder="Numéro de Lotissement" />
                    </div>

                    <!-- Référence Cadastrale -->
                    <div>
                      <label for="rd_immatriculation_terrain">Immatriculation du Terrain</label>
                      <input type="text" v-model="referencesCadastrales.rd_immatriculation_terrain" placeholder="Immatriculation du Terrain" />
                    </div>
                    <div>
                      <label for="slt_dependant_domaine">Domaine Dépendant</label>
                      <input type="text" v-model="referencesCadastrales.slt_dependant_domaine" placeholder="Domaine Dépendant" />
                    </div>
                    <div>
                      <label for="txt_surface_bornage">Surface Bornage</label>
                      <input type="text" v-model="referencesCadastrales.txt_surface_bornage" placeholder="Surface Bornage" />
                    </div>

                    <!-- Titulaire -->
                    <div>
                      <label for="txt_prenom">Prénom Titulaire</label>
                      <input type="text" v-model="titulaire.txt_prenom" placeholder="Prénom Titulaire" />
                    </div>
                    <div>
                      <label for="txt_nom">Nom Titulaire</label>
                      <input type="text" v-model="titulaire.txt_nom" placeholder="Nom Titulaire" />
                    </div>

                    <div>
                      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Sauvegarder les modifications</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
