<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { defineProps } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useToast } from "maz-ui";
import { ref, reactive, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

 
const page = usePage()
const toast = useToast();
const code = ref('')
const formVisible = ref(false)
const isLoading = ref(false) 
const maxOccupants = 25

// Déstructuration propre
const { terrain } = defineProps({
    terrain: Object,
    regions: Array,
    departements: Array,
    arrondissements: Array,
    communes: Array,
    references_usages: Array, 
    occupants: Array,
}) 

console.log(terrain);
// Initialisation du formulaire avec les données du terrain
const form = useForm({
  txt_nicad: terrain.txt_nicad || '', 
  // Table Dossier
  txt_num_dossier: terrain.dossier.txt_num_dossier || '',
  txt_num_dordre: terrain.dossier.txt_num_dordre || '',
  slt_service_rendu: terrain.dossier.slt_service_rendu || '',
  txt_etat_cession: terrain.dossier.txt_etat_cession || '',
  txt_cession_definitive: terrain.dossier.txt_cession_definitive || '',
  dt_date_creation: terrain.dossier.dt_date_creation || '',
  //Table Terrain
  slt_region: terrain.region.id || '',
  slt_departement: terrain.departement.id || '',
  slt_commune: terrain.commune.id || '',
  slt_arrondissement: terrain.arrondissement.id || '',
  txt_lotissement: terrain.txt_lotissement || '',
  txt_HorsLotissement: terrain.txt_HorsLotissement || '',
  txt_num_lotissement: terrain.txt_num_lotissement || '',
  // txt_num_section: "",
  txt_num_section:  terrain.txt_num_section || '',
  txt_num_parcelle: terrain.txt_num_parcelle || '',
  txt_appartement:  terrain.txt_appartement || '',
  
  nbr_surface: terrain.nbr_surface || '',
  slt_document_admin: terrain.slt_document_admin || '',
  txt_num_deliberation: terrain.txt_num_deliberation || '',
  dt_date_deliberation: terrain.dt_date_deliberation || '',
  // Table ReferenceCadastrale
  rd_immatriculation_terrain: terrain.references_cadastrales.rd_immatriculation_terrain  ||  '',
  slt_dependant_domaine:  terrain.references_cadastrales.slt_dependant_domaine || '',
  issu_bornage: terrain.references_cadastrales.issu_bornage || '',
  txt_num_titre:  terrain.references_cadastrales.txt_num_titre || '', 
  txt_titre_mere: terrain.references_cadastrales.txt_titre_mere || '', 
  txt_appartement: terrain.references_cadastrales.txt_appartement || '', 
  slt_lf: terrain.references_cadastrales.slt_lf || '',
  txt_num_requisition:  terrain.references_cadastrales.txt_num_requisition || '',
  txt_surface_bornage:  terrain.references_cadastrales.txt_surface_bornage || '',
  dt_date_bornage:  terrain.references_cadastrales.dt_date_bornage || '',
  txt_nom_geometre: terrain.references_cadastrales.txt_nom_geometre || '',
  // Table Titulaire
  slt_titulaire:  terrain.titulaire.slt_titulaire || '',
  txt_nationalite:  terrain.titulaire.txt_nationalite || '',
  slt_civilite: terrain.titulaire.slt_civilite || '',
  txt_prenom: terrain.titulaire.txt_prenom || '',
  txt_nom:  terrain.titulaire.txt_nom || '',
  slt_piece:  terrain.titulaire.slt_piece || '',
  txt_numPiece: terrain.titulaire.txt_numPiece || '',
  dt_date_delivrance: terrain.titulaire.dt_date_delivrance || '',
  dt_date_naissance:  terrain.titulaire.dt_date_naissance || '',
  txt_lieu_naissance: terrain.titulaire.txt_lieu_naissance || '',
  txt_adresse:  terrain.titulaire.txt_adresse || '',
  tel_telephone:  terrain.titulaire.tel_telephone || '',
  txt_ninea:  terrain.titulaire.txt_ninea || '',
  eml_email:  terrain.titulaire.eml_email || '',
  txt_representant: terrain.titulaire.txt_representant || '',
  tel_telRepresentant:  terrain.titulaire.tel_telRepresentant || '',
  // References Usage 
  slt_usage:  terrain.references_usages.slt_usage ||  '',
  slt_residence:  terrain.references_usages.slt_residence ||  '', 

  // occupants: (terrain.references_usages.occupants || [0]).map(occupant => ({
  //   txt_nomOccupantTG: occupant.txt_nomOccupantTG || '',
  //   txt_numAppartementTG: occupant.txt_numAppartementTG || '',
  //   txt_activiteTG: occupant.txt_activiteTG || '',
  //   txt_nineaTG: occupant.txt_nineaTG || '',
  //   tel_telephoneTG: occupant.tel_telephoneTG || '',
  //   nbr_montantLoyerTG: occupant.nbr_montantLoyerTG || 0,
  //   txt_dateLieuNaissanceTG: occupant.txt_dateLieuNaissanceTG || '',
  //   txt_cniPasseportTG: occupant.txt_cniPasseportTG || '',
  //   dt_dateDelivranceTG: occupant.dt_dateDelivranceTG || '',
  // }))

  terrain_id: terrain.terrain?.id || null,
  occupants: (terrain.references_usages.occupants || [0]).map(occupant => ({
    txt_nomOccupantTG: occupant.txt_nomOccupantTG || '',
    txt_numAppartementTG: occupant.txt_numAppartementTG || '',
    txt_activiteTG: occupant.txt_activiteTG || '',
    txt_nineaTG: occupant.txt_nineaTG || '',
    tel_telephoneTG: occupant.tel_telephoneTG || '',
    nbr_montantLoyerTG: occupant.nbr_montantLoyerTG || 0,
    txt_dateLieuNaissanceTG: occupant.txt_dateLieuNaissanceTG || '',
    txt_cniPasseportTG: occupant.txt_cniPasseportTG || '',
    dt_dateDelivranceTG: occupant.dt_dateDelivranceTG || ''
  }))

})


// Regference Usage
// bloc ajouter 
function addBlock() {
  if (form.occupants.length < maxOccupants) {
    form.occupants.push({
      id: Date.now() + Math.random(),
      txt_nomOccupantTG: '',
      txt_numAppartementTG: '',
      txt_activiteTG: '',
      txt_nineaTG: '',
      tel_telephoneTG: '',
      nbr_montantLoyerTG: 0,
      txt_dateLieuNaissanceTG: '',
      txt_cniPasseportTG: '',
      dt_dateDelivranceTG: ''
    })
  }
}
 

function removeBlock(i) {
  if (form.occupants.length > 1) {
    form.occupants.pop()
  }
}


// rechercher
const rechercherDossier = async () => {
  if (!code.value) {
    toast.error("Veuillez entrer un code d'accès.")
    return
  }

  isLoading.value = true

  try {
    const { data } = await axios.post(route('code.verify'), {
      code: code.value
    })

    if (data.exists) {
      formVisible.value = true
      toast.success("Modification autorisée !")
      code.value = ''
    } else {
      toast.error("Vérifie ton code d'accès !")
      formVisible.value = false
      code.value = ''
    }

  } catch (err) {
    formVisible.value = false

    if (err.response?.status === 422 && err.response.data.errors) {
      Object.values(err.response.data.errors).forEach(msg => toast.error(msg))
    } else {
      toast.error("Une erreur est survenue lors de la vérification.")
    }

  } finally {
    isLoading.value = false
  }
}

// Soumission du formulaire
function submit() {
  form.put(route('secretariat.update', terrain.id), {
    preserveScroll: true,
    onSuccess: () => {
      console.log("✅ Succès Laravel :", page);
      const message = page.props.flash?.success || "Modification réussie !";
      toast.success(message);  
    },
    onError: (errors) => {
      console.error('Erreur lors de la mise à jour', errors);
      Object.values(errors).forEach((error) => {
        toast.error(error);
      });
    }
  })
} 
 
</script>

<template>
  <Head title="Modifier Terrain" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Modification du Terrain
      </h2>
    </template>

    <div class="py-12">
      <div class="flex justify-center">  

        <div   v-if="!formVisible" class="w-full max-w-6xl">
          <div class="bg-white shadow-md rounded-lg">
            <div class="p-4 border-b bg-gray-100">
                <h1 class="text-lg font-semibold">Veillez entrer votre code d'accès !</h1>
            </div>
            <div class="py-6"> 
              <div class="flex justify-center">  
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 w-full max-w-2xl">
                  <div class="sm:col-span-4">
                    <input 
                      autocomplete="off"
                      autocorrect="off"
                      spellcheck="false"
                      v-model="code" 
                      type="text"
                      name="code"
                      aria-label="Code d'accès"
                      class="h-10 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                            outline outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                            focus:outline-2 focus:outline-primary sm:text-sm/6"
                      placeholder="Entrez votre code d'accès"
                      required
                    />
                  </div>

                  <MazBtn 
                    :loading="isLoading"
                    type="button"
                    title="Confirmer"
                    @click="rechercherDossier"
                    class="h-6 text-white bg-gradient-to-r from-primary via-primary-dark 
                    to-primary hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                    focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                    dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                    py-2.5 text-center me-2 mb-2"

                    >
                    Confirmer
                  </MazBtn>
                </div> 
              </div>
            </div>
          </div>
        </div>

      </div>
    </div><br>
  
    <div class="py-12">
      <div class="flex justify-center">  
        <div   v-if="formVisible" class="w-full max-w-6xl">
          <div class="bg-white shadow-md rounded-lg">
            <div class="p-4 border-b bg-gray-100">
                <h1 class="text-lg font-semibold">Modifiation !</h1>
            </div>
            <div class="py-6">   
                <div class="p-6 text-gray-900">
                  <template v-if="terrain">

                    <div class="flex justify-center"> 
                      <h1 class="text-lg font-bold">
                        Modifier le terrain numéro : {{ terrain.dossier.txt_num_dossier }}
                      </h1> 
                    </div>

                    <!-- Ici tu pourras mettre ton formulaire de modification -->
           
                      <form  @submit.prevent="submit" class="space-y-6"> 
                    
                          <div class="sm:col-span-12">

                            <h5 class="text-lg font-bold">
                              Reference Dossier
                            </h5><br>

                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">

                              <div class="sm:col-span-2">
                                <label for="txt_num_dossier" class="block text-sm/6 font-medium text-gray-900">Numéro de dossier</label>
                                <input v-model="form.txt_num_dossier" id="txt_num_dossier" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/>
                                <div v-if="form.errors.txt_num_dossier" class="text-red-500">{{ form.errors.txt_num_dossier }}</div>
                              </div>

                              <div>
                                <label for="slt_service_rendu" class="block font-medium">Service Rendu</label>
                                <input v-model="form.slt_service_rendu" id="slt_service_rendu" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.slt_service_rendu" class="text-red-500">{{ form.errors.slt_service_rendu }}</div>
                              </div>

                              <div>
                                <label for="txt_etat_cession" class="block font-medium">Etat Cession</label>
                                <input v-model="form.txt_etat_cession" id="txt_etat_cession" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_etat_cession" class="text-red-500">{{ form.errors.txt_etat_cession }}</div>
                              </div>
                              
                              <div>
                                <label for="txt_cession_definitive" class="block font-medium">Cession Definitive</label>
                                <input v-model="form.txt_cession_definitive" id="txt_cession_definitive" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_cession_definitive" class="text-red-500">{{ form.errors.txt_cession_definitive }}</div>
                              </div>
                                                              
                              <div>
                                <label for="dt_date_creation" class="block font-medium">Date creation</label>
                                <input v-model="form.dt_date_creation" id="dt_date_creation" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.dt_date_creation" class="text-red-500">{{ form.errors.dt_date_creation }}</div>
                              </div>
                            </div><br>
          
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">

                              <div>
                                <label for="slt_region" class="block font-medium">Région</label>
                                <select v-model="form.slt_region"  id="slt_region"
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                  <option v-for="region in regions" :value="region.id" :key="region.id">
                                    {{ region.slt_region }}
                                  </option>
                                </select>
                                <div v-if="form.errors.slt_region" class="text-red-500">{{ form.errors.slt_region }}</div>
                              </div>
                              
                              <div>
                                <label for="slt_departement" class="block font-medium">Département</label>
                                <select v-model="form.slt_departement"  id="slt_departement"
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                  <option v-for="departement in departements" :value="departement.id" :key="departement.id">
                                    {{ departement.slt_departement }}
                                  </option>
                                </select>
                                <div v-if="form.errors.slt_departement" class="text-red-500">{{ form.errors.slt_departement }}</div>
                              </div>
                              
                              <div>
                                <label for="slt_arrondissement" class="block font-medium">Arrondissement</label>
                                <select v-model="form.slt_arrondissement"  id="slt_arrondissement"
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                  <option v-for="arrondissement in arrondissements" :value="arrondissement.id" :key="arrondissement.id">
                                    {{ arrondissement.slt_arrondissement }}
                                  </option>
                                </select>
                                <div v-if="form.errors.slt_arrondissement" class="text-red-500">{{ form.errors.slt_arrondissement }}</div>
                              </div>
                              
                              <div>
                                <label for="slt_commune" class="block font-medium">Commune</label>
                                <select v-model="form.slt_commune"  id="slt_commune"
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                  <option v-for="commune in communes" :value="commune.id" :key="commune.id">
                                    {{ commune.slt_commune }}
                                  </option>
                                </select>
                                <div v-if="form.errors.slt_commune" class="text-red-500">{{ form.errors.slt_commune }}</div>
                              </div>  

                              <div>
                                <label for="txt_lotissement" class="block font-medium">Lotissement</label>
                                <input v-model="form.txt_lotissement" id="txt_lotissement" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_lotissement" class="text-red-500">{{ form.errors.txt_lotissement }}</div>
                              </div>
                              
                              <div>
                                <label for="txt_HorsLotissement" class="block font-medium">Hors Lotissement</label>
                                <input v-model="form.txt_HorsLotissement" id="txt_HorsLotissement" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_HorsLotissement" class="text-red-500">{{ form.errors.txt_HorsLotissement }}</div>
                              </div>
                              
                              <div>
                                <label for="txt_num_lotissement" class="block font-medium">N° Lot</label>
                                <input v-model="form.txt_num_lotissement" id="txt_num_lotissement" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_num_lotissement" class="text-red-500">{{ form.errors.txt_num_lotissement }}</div>
                              </div>
                              
                              <div>
                                <label for="nbr_surface" class="block font-medium">Surface</label>
                                <input v-model="form.nbr_surface" id="nbr_surface" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.nbr_surface" class="text-red-500">{{ form.errors.nbr_surface }}</div>
                              </div>
                              
                              <div>
                                <label for="slt_document_admin" class="block font-medium">Document admin</label>
                                <input v-model="form.slt_document_admin" id="slt_document_admin" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.slt_document_admin" class="text-red-500">{{ form.errors.slt_document_admin }}</div>
                              </div>
                                                            
                              <div>
                                <label for="txt_num_deliberation" class="block font-medium">N° Déliberation </label>
                                <input v-model="form.txt_num_deliberation" id="txt_num_deliberation" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_num_deliberation" class="text-red-500">{{ form.errors.txt_num_deliberation }}</div>
                              </div>
                    
                              <div>
                                <label for="dt_date_deliberation" class="block font-medium">Date Déliberation</label>
                                <input v-model="form.dt_date_deliberation" id="dt_date_deliberation" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.dt_date_deliberation" class="text-red-500">{{ form.errors.dt_date_deliberation }}</div>
                              </div>
 
                              <div>
                                <label for="txt_num_section" class="block font-medium">N° section</label>
                                <input v-model="form.txt_num_section" id="txt_num_section" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                  maxlength="3"
                                  minlength="3"/> 
                                <div v-if="form.errors.txt_num_section" class="text-red-500">{{ form.errors.txt_num_section }}</div>
                              </div>
                              
                              <div>
                                <label for="txt_num_parcelle" class="block font-medium">N° Parcelle</label>
                                <input v-model="form.txt_num_parcelle" id="txt_num_parcelle" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_num_parcelle" class="text-red-500">{{ form.errors.txt_num_parcelle }}</div>
                              </div>  
                              
                              <div class="sm:col-span-2">
                                <div class="sm:col-span-1">  
                                  <label for="txt_nicad" class="block font-medium">NICAD</label>
                                  <input v-model="form.txt_nicad" id="txt_nicad" type="text" 
                                    class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                  <div v-if="form.errors.txt_nicad" class="text-red-500">{{ form.errors.txt_nicad }}</div>
                                </div>
                              </div> 

                            </div><br>

                            <h5 class="text-lg font-bold">
                              Reference Cadastrale
                            </h5><br>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">  
                              <div class="sm:col-span-2">
                                <label for="rd_immatriculation_terrain" class="block font-medium">Ref Cadastrale</label>
                                <input v-model="form.rd_immatriculation_terrain" id="rd_immatriculation_terrain" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.rd_immatriculation_terrain" class="text-red-500">{{ form.errors.rd_immatriculation_terrain }}</div>
                              </div>
                              
                              <div class="sm:col-span-2">
                                <label for="slt_dependant_domaine" class="block font-medium">Dependant domaine</label>
                                <input v-model="form.slt_dependant_domaine" id="slt_dependant_domaine" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.slt_dependant_domaine" class="text-red-500">{{ form.errors.slt_dependant_domaine }}</div>
                              </div>
                              
                              <div>
                                <label for="issu_bornage" class="block font-medium">Issu Bornage</label>
                                <input v-model="form.issu_bornage" id="issu_bornage" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.issu_bornage" class="text-red-500">{{ form.errors.issu_bornage }}</div>
                              </div>
                              
                              <div>
                                <label for="txt_titre_mere" class="block font-medium">Titre mère </label>
                                <input v-model="form.txt_titre_mere" id="txt_titre_mere" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_titre_mere" class="text-red-500">{{ form.errors.txt_titre_mere }}</div>
                              </div>
                              
                              <div>
                                <label for="txt_num_titre" class="block font-medium">N° titre Mère</label>
                                <input v-model="form.txt_num_titre" id="txt_num_titre" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_num_titre" class="text-red-500">{{ form.errors.txt_num_titre }}</div>
                              </div>
                              
                              <div>
                                <label for="slt_lf" class="block font-medium">LF</label>
                                <input v-model="form.slt_lf" id="slt_lf" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.slt_lf" class="text-red-500">{{ form.errors.slt_lf }}</div>
                              </div>
                              
                              <div>
                                <label for="txt_num_requisition" class="block font-medium">N° Requisition</label>
                                <input v-model="form.txt_num_requisition" id="txt_num_requisition" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_num_requisition" class="text-red-500">{{ form.errors.txt_num_requisition }}</div>
                              </div>
                              
                              <div>
                                <label for="txt_surface_bornage" class="block font-medium">Surfacce bornage</label>
                                <input v-model="form.txt_surface_bornage" id="txt_surface_bornage" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_surface_bornage" class="text-red-500">{{ form.errors.txt_surface_bornage }}</div>
                              </div>
                              
                              <div>
                                <label for="dt_date_bornage" class="block font-medium">Date Bornage</label>
                                <input v-model="form.dt_date_bornage" id="dt_date_bornage" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.dt_date_bornage" class="text-red-500">{{ form.errors.dt_date_bornage }}</div>
                              </div>
                              
                              <div>
                                <label for="txt_appartement" class="block font-medium">Appartement</label>
                                <input v-model="form.txt_appartement" id="txt_appartement" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_appartement" class="text-red-500">{{ form.errors.txt_appartement }}</div>
                              </div>
                              
                              <div>
                                <label for="txt_nom_geometre" class="block font-medium">Nom Geometre</label>
                                <input v-model="form.txt_nom_geometre" id="txt_nom_geometre" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_nom_geometre" class="text-red-500">{{ form.errors.txt_nom_geometre }}</div>
                              </div>

                            </div><br>

                            <h5 class="text-lg font-bold">
                                Identité Titulaire de droit
                            </h5><br>

                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">  
                              <div class="sm:col-span-2">
                                <label for="slt_titulaire" class="block font-medium">Titulaire </label>
                                <input v-model="form.slt_titulaire" id="slt_titulaire" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.slt_titulaire" class="text-red-500">{{ form.errors.slt_titulaire }}</div>
                              </div>
                              
                              <div>
                                <label for="txt_nationalite" class="block font-medium">Nationalite</label>
                                <input v-model="form.txt_nationalite" id="txt_nationalite" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_nationalite" class="text-red-500">{{ form.errors.txt_nationalite }}</div>
                              </div>
                              
                              <div>
                                <label for="slt_civilite" class="block font-medium">Civilite</label>
                                <input v-model="form.slt_civilite" id="slt_civilite" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.slt_civilite" class="text-red-500">{{ form.errors.slt_civilite }}</div>
                              </div>
                              
                              <div>
                                <label for="txt_prenom" class="block font-medium">Prénom</label>
                                <input v-model="form.txt_prenom" id="txt_prenom" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_prenom" class="text-red-500">{{ form.errors.txt_prenom }}</div>
                              </div>
                                                            
                              <div>
                                <label for="txt_nom" class="block font-medium">Nom</label>
                                <input v-model="form.txt_nom" id="txt_nom" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_nom" class="text-red-500">{{ form.errors.txt_nom }}</div>
                              </div>
                                                            
                              <div>
                                <label for="txt_prenom" class="block font-medium">Prénom</label>
                                <input v-model="form.txt_prenom" id="txt_prenom" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_prenom" class="text-red-500">{{ form.errors.txt_prenom }}</div>
                              </div>
                                                            
                              <div>
                                <label for="slt_piece" class="block font-medium">Pièce</label>
                                <input v-model="form.slt_piece" id="slt_piece" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.slt_piece" class="text-red-500">{{ form.errors.slt_piece }}</div>
                              </div>
                                                            
                              <div>
                                <label for="txt_numPiece" class="block font-medium">N° Pièce</label>
                                <input v-model="form.txt_numPiece" id="txt_numPiece" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_numPiece" class="text-red-500">{{ form.errors.txt_numPiece }}</div>
                              </div>
                                                            
                              <div>
                                <label for="dt_date_delivrance" class="block font-medium">Date Délivrance</label>
                                <input v-model="form.dt_date_delivrance" id="dt_date_delivrance" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.dt_date_delivrance" class="text-red-500">{{ form.errors.dt_date_delivrance }}</div>
                              </div>
                                                            
                              <div>
                                <label for="dt_date_naissance" class="block font-medium">Date Naissance</label>
                                <input v-model="form.dt_date_naissance" id="dt_date_naissance" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.dt_date_naissance" class="text-red-500">{{ form.errors.dt_date_naissance }}</div>
                              </div>
                                                            
                              <div>
                                <label for="txt_lieu_naissance" class="block font-medium">Lieu Naissance</label>
                                <input v-model="form.txt_prenom" id="txt_lieu_naissance" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_lieu_naissance" class="text-red-500">{{ form.errors.txt_lieu_naissance }}</div>
                              </div>
                                                            
                              <div>
                                <label for="txt_adresse" class="block font-medium">Adresse</label>
                                <input v-model="form.txt_adresse" id="txt_adresse" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_adresse" class="text-red-500">{{ form.errors.txt_adresse }}</div>
                              </div>
                                                                                          
                              <div>
                                <label for="tel_telephone" class="block font-medium">Telephone</label>
                                <input v-model="form.tel_telephone" id="tel_telephone" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.tel_telephone" class="text-red-500">{{ form.errors.tel_telephone }}</div>
                              </div>
                                                                                          
                              <div>
                                <label for="txt_ninea" class="block font-medium">Ninea</label>
                                <input v-model="form.txt_ninea" id="txt_ninea" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_ninea" class="text-red-500">{{ form.errors.txt_ninea }}</div>
                              </div>
                                                                                          
                              <div>
                                <label for="eml_email" class="block font-medium">Email</label>
                                <input v-model="form.eml_email" id="eml_email" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.eml_email" class="text-red-500">{{ form.errors.eml_email }}</div>
                              </div>
                                                                                          
                              <div>
                                <label for="txt_representant" class="block font-medium">Representant</label>
                                <input v-model="form.txt_representant" id="txt_representant" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.txt_representant" class="text-red-500">{{ form.errors.txt_representant }}</div>
                              </div>
                                                                                          
                              <div>
                                <label for="tel_telRepresentant" class="block font-medium">Tel Representant</label>
                                <input v-model="form.tel_telRepresentant" id="tel_telRepresentant" type="text" 
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"/> 
                                <div v-if="form.errors.tel_telRepresentant" class="text-red-500">{{ form.errors.tel_telRepresentant }}</div>
                              </div><br>
                            </div>
                              <!--    References usages -->
                              
                            <h5 class="text-lg font-bold">
                                Réferences Usage
                            </h5><br>

                            <div  class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 

                              <div>
                                <label for="slt_usage" class="block font-medium">Usage</label>
                                <select v-model="form.slt_usage" id="slt_usage"
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline-2 focus:outline-primary sm:text-sm/6"> 
                                  <option>
                                    {{ references_usages.slt_usage }}
                                  </option>
                                  <option value="Habitation" >Habitation</option>
                                  <option value="Commercial">Commercial</option>
                                  <option value="Agricole">Agricole</option>
                                  <option value="Industriel">Industriel</option>
                                  <option value="Professionnelle">Professionnelle</option>
                                  <option value="Culte">Culte</option>
                                  <option value="Administratif">Administratif</option>
                                </select>
                                <div v-if="form.errors?.slt_usage" class="text-red-500">{{ form.errors.slt_usage }}</div>
                              </div>

                              <div>
                                <label for="slt_residence" class="block font-medium">Type de résidence</label>
                                <select v-model="form.slt_residence" id="slt_residence"
                                  class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                  outline outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                  focus:outline-2 focus:outline-primary sm:text-sm/6">
                                  <option>
                                    {{ references_usages.slt_residence }}
                                  </option>
                                  <option value="Residence Principal">Residence Principal</option>
                                  <option value="Residence Secondaire">Residence secondaire</option>
                                </select>
                                <div v-if="form.errors?.slt_residence" class="text-red-500">{{ form.errors.slt_residence }}</div>
                              </div>
  
                            </div>
 
                            <div  class="w-full flex flex-wrap justify-between gap-4 mt-4"> 
                              <TransitionGroup  name="slide-fade" tag="div">
                                <div 
                                  v-for="(occupant, i) in form.occupants" :key="occupant.id" class="sm:col-span-8"> 
                                  <div  class="flex grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                
                                      <div class="sm:col-span-1">
                                          <label :for="'NomOccupant_${i}'" class="block text-sm/6 font-medium text-gray-900">Nom Occupant {{ i }}</label>
                                          <input type="text" 
                                          v-model="form.txt_nomOccupantTG"
                                          :id="'NomOccupant_${i}'"  
                                          class="h-8 block w-28 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                          outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                          focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                      </div>

                                      <div class="sm:col-span-1">
                                          <label :for="'NunAppartement_${i}'" class="block text-sm/6 font-medium text-gray-900">N° Appart</label>
                                          <input type="text" 
                                          v-model="occupant.txt_numAppartementTG"
                                          :id="'NunAppartement_${i}'"  
                                          class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                          outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                          focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                      </div>

                                      <div class="sm:col-span-1">
                                          <label :for="'Activite_${i}'" class="block text-sm/6 font-medium text-gray-900">Activite</label>
                                          <input type="text" 
                                          v-model="occupant.txt_activiteTG"
                                          :id="'Activite_${i}'"  
                                          class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                          outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                          focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                      </div>

                                      <div class="sm:col-span-1">
                                          <label :for="'Ninea_${i}'" class="block text-sm/6 font-medium text-gray-900">Ninea </label>
                                          <input type="text" 
                                          v-model="occupant.txt_nineaTG"
                                          :id="'Ninea_${i}'"  
                                          class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                          outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                          focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                      </div>

                                      <div class="sm:col-span-1">
                                          <label :for="'Telephone_${i}'" class="block text-sm/6 font-medium text-gray-900">Téléphone </label>
                                          <input type="tel" 
                                          v-model="occupant.tel_telephoneTG"
                                          :id="'Telephone_${i}'"  
                                          class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                          outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                          focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                      </div>

                                      <div class="sm:col-span-1">
                                          <label :for="'Montant_loyer_${i}'" class="block text-sm/6 font-medium text-gray-900">Mnt Loyer</label>
                                          <input type="number" 
                                          v-model="occupant.nbr_montantLoyerTG"
                                          :id="'Montant_loyer_${i}'"  
                                          class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                          outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                          focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                      </div>

                                      <div class="sm:col-span-1">
                                          <label :for="'DateNaissnace_${i}'" class="block text-sm/6 font-medium text-gray-900">Date-lieu Nais</label>
                                          <input type="text" 
                                          v-model="occupant.txt_dateLieuNaissanceTG"
                                          :id="'DateNaissnace_${i}'"  
                                          class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                          outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                          focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                      </div>

                                      <div class="sm:col-span-1">
                                          <label :for="'CNIPasseport_${i}'" class="block text-sm/6 font-medium text-gray-900">CNI/Pasprt</label>
                                          <input type="text" 
                                          v-model="occupant.txt_cniPasseportTG"
                                          :id="'CNIPasseport_${i}'"  
                                          class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                          outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                          focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                      </div>

                                      <div class="sm:col-span-1">
                                          <label :for="'DateDelivrance_${i}'" class="block text-sm/6 font-medium text-gray-900">Date Déliv</label>
                                          <input type="date" 
                                          v-model="occupant.dt_dateDelivranceTG"
                                          :id="'DateDelivrance_${i}'"  
                                          class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                          outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                          focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                      </div>

                                    </div>
                                  </div>
                                </TransitionGroup> 
                                <!-- Boutons -->
                                <div class="flex gap-4 mt-6">
                                  <button @click="addBlock" type="button"
                                    class="h-8 text-white bg-primary hover:bg-primary-dark font-medium rounded-lg text-sm px-5 py-2.5">
                                    Ajouter
                                  </button>
                                  <button @click="removeBlock" type="button"
                                    class="h-8 text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5">
                                    Retirer
                                  </button>
                                </div>
                              </div>
  
                          </div><br>

                          <div class="sm:col-span-6 flex justify-center">
                            <MazBtn 
                              type="submit"    
                                class="h-8 text-white bg-gradient-to-r from-primary via-primary-dark 
                                to-primary hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                py-2.5 text-center me-2 mb-2"
                            >
                              Enregistrer modification
                            </MazBtn>
                          </div>

                        
                      </form>
                 
                  </template>
                  <template v-else>
                    <p class="text-red-500">Aucun terrain à modifier n’a été trouvé.</p>
                  </template>
                </div> 
            </div>
          </div> 
        </div>  
      </div>
    </div>
  </AuthenticatedLayout>
</template>
