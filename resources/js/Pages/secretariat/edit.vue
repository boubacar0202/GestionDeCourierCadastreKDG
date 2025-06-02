<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { defineProps } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useToast } from "maz-ui";
import { ref } from 'vue' 
import { reactive, computed, watchEffect, watch } from 'vue';
import MazRadio from 'maz-ui/components/MazRadio'


 
const page = usePage()
const toast = useToast();
const code = ref('')
const formVisible = ref(false)
const isLoading = ref(false) 
const maxOccupants = 25;
const maxOccupantsBP = 4;
const maxOccupantsCA = 3;
const maxOccupantsCL = 3;
const maxOccupantsAP = 3; 
const currentCat = ref('');

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


let show = ref(true);
    show.value = show;

let showBati = ref(true);
    showBati.value = !showBati;
    
let showCAmenager = ref(true);
    showCAmenager.value = !showCAmenager;
    
let showCloture = ref(true);
    showCloture.value = !showCloture;
        
let showAmenagement = ref(true);
    showAmenagement.value = !showAmenagement;

const activeTab = ref('terrain');
const setActiveTab = (tab) => {
    activeTab.value = tab;
};

// Reference usage 
function formatOccupants(references_usages) {
  if (Array.isArray(references_usages)) {
    return references_usages.map(o => formatOccupant(o));
  }

  if (references_usages) {
    return [formatOccupant(references_usages)];
  }

  return [formatOccupant()];
} 
function formatOccupant(o = {}) {
  return {
    txt_nomOccupantTG: o.txt_nomOccupantTG || '',
    txt_numAppartementTG: o.txt_numAppartementTG || '',
    txt_activiteTG: o.txt_activiteTG || '',
    txt_nineaTG: o.txt_nineaTG || '',
    tel_telephoneTG: o.tel_telephoneTG || '',
    nbr_montantLoyerTG: o.nbr_montantLoyerTG || 0,
    txt_dateLieuNaissanceTG: o.txt_dateLieuNaissanceTG || '',
    txt_cniPasseportTG: o.txt_cniPasseportTG || '',
    dt_dateDelivranceTG: o.dt_dateDelivranceTG || ''
  };
}

// Evaluation Bati 
function formatOccupantsBP(evaluations_batis) {
  if (Array.isArray(evaluations_batis)) {
    return evaluations_batis.map(o => formatOccupantBP(o));
  }

  if (evaluations_batis) {
    return [formatOccupantBP(evaluations_batis)];
  }

  return [formatOccupantBP()];
}

function formatOccupantBP(o = {}) {
  return { 
    slt_dependant_domaineTG:  o.slt_dependant_domaineTG  || '',
    nbr_prix_metre_carreTG: o.nbr_prix_metre_carreTG  || '',
    nbr_surface_bati_solTG: o.nbr_surface_bati_solTG  || '',
    nbr_niveauTG: o.nbr_niveauTG  || '',
    nbr_surface_utileTG:  o.nbr_surface_utileTG  || '',
    slt_coeffTG:  o.slt_coeffTG  || '',
    nbr_surface_corrigerTG: o.nbr_surface_corrigerTG  || '',
    nbr_valeurTG: o.nbr_valeurTG  || '', 
  };
}


// Initialisation du formulaire avec les données du terrain
const form = useForm({
  txt_nicad: terrain.txt_nicad || '', 
  // Table Dossier
  txt_num_dossier: terrain.dossier?.txt_num_dossier  ?? 'Non disponible' ,
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
  slt_usage: terrain.references_usages?.slt_usage || '',
  slt_residence: terrain.references_usages?.slt_residence || '', 
  terrain_id: terrain.terrain?.id ?? null,
  occupants: formatOccupants(terrain.references_usages), 
  nbr_montantLoyerTotal: terrain.references_usages.nbr_montantLoyerTotal  ||  '',
  nbr_TVATotal: terrain.references_usages.nbr_TVATotal  ||  '', 
  // Evaluation Terrain 
  nbr_surface: terrain.evaluations_terrains?.nbr_surface || '',
  txt_superficie_bati_sol: terrain.evaluations_terrains?.txt_superficie_bati_sol || '',
  slt_secteur: terrain.evaluations_terrains?.slt_secteur || '',
  nbr_prix_metre_carre: terrain.evaluations_terrains?.nbr_prix_metre_carre || '',
  nbr_valeur_terrain: terrain.evaluations_terrains?.nbr_valeur_terrain || '',
  //  Evaluation Bati 
  txt_dependant_domainePR: terrain.evaluations_batis.txt_dependant_domainePR  || '',
  slt_categoriePR:  terrain.evaluations_batis.slt_categoriePR ||  '',
  nbr_prix_metre_carrePR: terrain.evaluations_batis.nbr_prix_metre_carrePR  || null,
  nbr_surface_bati_solPR: terrain.evaluations_batis.nbr_surface_bati_solPR  ||  '',
  nbr_niveauPR: terrain.evaluations_batis.nbr_niveauPR  ||'',
  nbr_surface_utilePR:  terrain.evaluations_batis.nbr_surface_utilePR || '',
  slt_coeffPR:  terrain.evaluations_batis.slt_coeffPR || '',
  nbr_surface_corrigerPR: terrain.evaluations_batis.nbr_surface_corrigerPR  || '',
  nbr_valeurPR: terrain.evaluations_batis.nbr_valeurPR  || '',
  currentCat: terrain.evaluations_batis.currentCat  ||  '',
  occupantsBP: formatOccupantsBP(terrain.evaluations_batis), 
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

// Batiment Principal
// bloc ajouter 
function addBlockBP() {
    if (form.occupantsBP.length < maxOccupantsBP) {
        form.occupantsBP.push({
            id: Date.now() + Math.random(), 
            slt_dependant_domaineTG: '',
            nbr_prix_metre_carreTG: '',
            nbr_surface_bati_solTG: '',
            nbr_niveauTG: '',
            nbr_surface_utileTG: '',
            slt_coeffTG: '',
            nbr_surface_corrigerTG: '',
            nbr_valeurTG: '', 
        })                
    }else {
        toast.error('Impossible d\'ajouter plus de 5 occupants.');
    }
}
// bloc retirer 
function removeBlockBP(i) {
    if (form.occupantsBP.length > 1) {
        form.occupantsBP.pop()
    }
}

// // Cours Aménager
// // bloc ajouter 
// function addBlockCA() {
//     if (form.occupantsCA.length < maxOccupantsCA) {
//         form.occupantsCA.push({
//             id: Date.now() + Math.random(),
//             slt_cours_amenager_totale:'',
//             nbr_surface_ca_total:'', 
//             slt_categorie_ca_total:'', 
//             nbr_prix_metre_carre_ca_total:'', 
//             nbr_coefficient_ca_total:'',
//             nbr_valeur_ca_total:'',
//         })                
//     }else {
//         toast.error('Impossible d\'ajouter plus de 3 occupants.');
//     }
// }
// // bloc retirer 
// function removeBlockCA(i) {
//     if (form.occupantsCA.length > 1) {
//         form.occupantsCA.pop()
//     }
// }

// // Clôture
// // bloc ajouter 
// function addBlockCL() {
//     if (form.occupantsCL.length < maxOccupantsCL) {
//         form.occupantsCL.push({
//             id: Date.now() + Math.random(),
//             slt_cours_amenager_clo:'',  
//             nbr_longueur_avant_clo:'', 
//             slt_categorie_clo:'', 
//             nbr_prix_metre_carre_clo:'', 
//             nbr_coefficient_clo:'', 
//             nbr_valeur_clo:'',
//         })                
//     }else {
//         toast.error('Impossible d\'ajouter plus de 3 occupants.');
//     }
// }
// // bloc retirer 
// function removeBlockCL(i) {
//     if (form.occupantsCL.length > 1) {
//         form.occupantsCL.pop()
//     }
// }

// // Cours Aménager
// // bloc ajouter 
// function addBlockAP() {
//     if (form.occupantsAP.length < maxOccupantsAP) {
//         form.occupantsAP.push({
//             id: Date.now() + Math.random(),
//             txt_designation_am:'', 
//             nbr_valeur_unitaire_am:'', 
//             nbr_quantile_am:'', 
//             slt_coeficien_am:'', 
//             nbr_valeur_am:'',
//         })                
//     }else {
//         toast.error('Impossible d\'ajouter plus de 3 occupants.');
//     }
// }
// // Converire la récupération du choix de categorie  
// function removeBlockAP(i) {
//     if (form.occupantsAP.length > 1) {
//         form.occupantsAP.pop()
//     }
// }

// Calcule Montan Loayer 
const montantLoyerTotal = computed(() => {
  const totalExistants = (terrain.references_usages || []).reduce((acc, usage) => {
    return acc + (Number(usage.nbr_montantLoyerTG) || 0)
  }, 0)

  const totalOccupants = (form.occupants || []).reduce((acc, occupant) => {
    return acc + (Number(occupant.nbr_montantLoyerTG) || 0)
  }, 0)

  return totalExistants + totalOccupants
}) 
watchEffect(() => {
  form.nbr_montantLoyerTotal = montantLoyerTotal.value
})
 
// Calcule du TVA
const nbr_TVATotal = computed(() => {
  return (Number(form.nbr_montantLoyerTotal) || 0) * 0.18
}) 
watchEffect(() => {
  form.nbr_TVATotal = nbr_TVATotal.value.toFixed(2) // 2 décimales
})

// Calculer Valeur Terrain  
watchEffect(() => {
  form.nbr_prix_metre_carre = Number(form.slt_secteur) || 0;
});
const nbr_valeur_terrain = computed(() => {
    const prix = parseFloat(form.nbr_prix_metre_carre) || 0;
    const bati = parseFloat(form.txt_superficie_bati_sol) || 0;
    const total = parseFloat(form.nbr_surface) || 0;

  return ((prix / 2) * bati + prix * (total - bati)).toFixed(2);
});
 
watchEffect(() => {
    form.nbr_valeur_terrain = nbr_valeur_terrain.value;
});

const categories = {
    'Maison individuelle': {
        '1': 205891, '2': 190883, '3': 180937, '4': 158411,
        '5': 119942, '6': 88201, '7': 58801, '8': 40663,
        '9': 11117, '10': 10000, '11': 8000,
    },
    'Immeuble collectif': {
        'A': 244257, 'B': 226543, 'C': 204069, 'D': 177171,
        'E': 142293, 'F': 123552, 'G': 93878, 'H': 61602,
        'I': 53793, 'J': 25000, 'K': 22000, 'L': 20000, 'M': 8000,
    }
}

watch([currentCat, () => form.slt_categoriePR], ([type, cat]) => {
    if (type && cat && categories[type]?.[cat]) {
        form.nbr_prix_metre_carrePR = categories[type][cat]
    } else {
        form.nbr_prix_metre_carrePR = null
    } 
})
watchEffect(() => {
    form.occupantsBP.forEach((occupant) => {
        const type = currentCat.value
        const cat = occupant.slt_categorieTG

        if (categories[type] && categories[type][cat]) {
            occupant.nbr_prix_metre_carreTG = categories[type][cat]
        } else {
            occupant.nbr_prix_metre_carreTG = null
        }
    })
}) 
// Synchronisation automatique
// watch(form.currentCat, (newVal) => {
//   currentCat.value = newVal
// })

watch(() => form.currentCat, (newVal) => {
  currentCat.value = newVal
})

// Calcule Batiment : Princile  
// Prix m² PR
watchEffect(()  => {
    form.txt_dependant_domainePR  = "Primaire";
}),
// Surface Utile PR
watchEffect(() => {
    const surfaceBS = parseFloat(form.nbr_surface_bati_solPR) || 0;
    const nbrNiveau = parseFloat(form.nbr_niveauPR) || 0;
    form.nbr_surface_utilePR = (surfaceBS * nbrNiveau * 0.78).toFixed(2);
});
// Surface Corrigée PR
watchEffect(() => { 
    const utile = parseFloat(form.nbr_surface_utilePR) || 0;
    const coeff = parseFloat(form.slt_coeffPR) || 0;
    form.nbr_surface_corrigerPR = (utile * coeff).toFixed(2); 
});
// Valeur Bâtie PR
watchEffect(() => {
    const prixmetrecarre = parseFloat(form.nbr_prix_metre_carrePR) || 0; 
    const surfaceCorrige = parseFloat(form.nbr_surface_corrigerPR) || 0;
    form.nbr_valeurPR = (prixmetrecarre * surfaceCorrige).toFixed(2); 
}); 

// Calcule Surface Utule
watchEffect(() => {
  form.occupantsBP.forEach((occupant) => {
    const surfaceBS = parseFloat(occupant.nbr_surface_bati_solTG) || 0;
    const nbrNiveau = parseFloat(occupant.nbr_niveauTG) || 0;
    occupant.nbr_surface_utileTG = (surfaceBS * nbrNiveau * 0.78).toFixed(2);
  });
});
// Calcule Surface corrige 
watchEffect(() => {
  form.occupantsBP.forEach((occupant) => {
    const utile = parseFloat(occupant.nbr_surface_utileTG) || 0;
    const coeff = parseFloat(occupant.slt_coeffTG) || 0;
    occupant.nbr_surface_corrigerTG = (utile * coeff).toFixed(2);
  });
});
//  Calcule valeur TG : nbr_valeurTG
watchEffect(() => {
  form.occupantsBP.forEach((occupant) => {
    const prixmetrecarre = parseFloat(occupant.nbr_prix_metre_carreTG) || 0; 
    const surfaceCorrige = parseFloat(occupant.nbr_surface_corrigerTG) || 0;
    occupant.nbr_valeurTG = ( prixmetrecarre * surfaceCorrige).toFixed(2);
  });
});
//  Calculer montant valeur batiment  
const txt_valeur_terrain_bati = computed(() => {
    const valeurPR = parseFloat(form.nbr_valeurPR)  || 0;
    return form.occupantsBP.reduce((total, occupant) => {
        const montant = parseFloat(occupant.nbr_valeurTG) || 0;
        return total + montant + valeurPR;
    }, 0);
});
watchEffect(() => {
    form.txt_valeur_terrain_bati = txt_valeur_terrain_bati.value;
});
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
        <div class="w-full max-w-6xl">
          <div class="bg-white shadow-md rounded-lg">

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
        </div> 
      </div>
    </div><br>
  
    <div class="py-12">
      <div class="flex justify-center">  
        <div class="w-full max-w-6xl">
          <div class="bg-white shadow-md rounded-lg">
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

                                      <option disabled value="">Sélectionnez un usage</option>
                                      <option value="Habitation">Habitation</option>
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

                                      <option disabled value="">Sélectionnez un type de résidence</option>
                                      <option value="Residence Principal">Residence Principal</option>
                                      <option value="Residence Secondaire">Residence Secondaire</option>
                                    </select>
                                    <div v-if="form.errors?.slt_residence" class="text-red-500">{{ form.errors.slt_residence }}</div>
                                  </div>
                                </div><br>
    
                                <div  class="w-full flex flex-wrap justify-between gap-4 mt-4"> 
                                  <TransitionGroup  name="slide-fade" v-if="form.occupants.length" tag="div">
                                    
                                    <div 
                                      v-for="(occupant, i) in form.occupants" :key="i" class="sm:col-span-8"> 
                                        <div  class="flex grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                      
                                            <div class="sm:col-span-1">
                                                <label class="block text-sm/6 font-medium text-gray-900">Nom Occupant {{ i }}</label>
                                                <input type="text" 
                                                v-model="occupant.txt_nomOccupantTG"
                                                class="h-8 block w-28 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                            </div>

                                            <div class="sm:col-span-1">
                                                <label :for="'NunAppartement_${i}'" class="block text-sm/6 font-medium text-gray-900">N° Appart</label>
                                                <input type="text" 
                                                v-model="occupant.txt_numAppartementTG" 
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
                                  
                               

                                </div><br><br>
                                <div  class="flex grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                  <div class="sm:col-span-2">
                                      <label for="MontantLoyerTotal" class="block text-sm/6 font-medium text-gray-900">Montant Loyer Total</label>
                                      <div>
                                          <input type="number" 
                                          v-model="form.nbr_montantLoyerTotal" 
                                          readonly
                                          class="h-8 block w-64 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                          outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                          focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                      </div>
                                  </div>

                                  <div class="sm:col-span-2">
                                      <label for="TVATotal" class="block text-sm/6 font-medium text-gray-900">TVA Totale</label>
                                      <div>
                                          <input type="number" 
                                          :value="form.nbr_TVATotal"
                                          readonly
                                          class="h-8 block w-64 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                          outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                          focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                      </div>
                                  </div>
                                </div>
      
                              </div><br>
                              <h5 class="text-lg font-bold">Evaluation de l'immeuble</h5><br>
                              <div class="mb-6">
                                  <div class="border-b">
                                      <ul class="flex justify-center text-sm font-medium text-gray-500 divide-x divide-gray-200 dark:divide-gray-700">

                                          <li class="w-1/3 text-center">
                                              <button
                                                  type="button"
                                                  @click="setActiveTab('terrain')"
                                                  :class="{
                                                      'text-primary border-b-2 border-primary': activeTab === 'terrain',
                                                      'hover:text-gray-700 hover:border-gray-300': activeTab !== 'terrain'
                                                  }"
                                                  class="inline-block w-full p-4"
                                              >
                                                  Terrain
                                              </button>
                                          </li>

                                          <li class="w-1/3 text-center">
                                              <button
                                                  type="button"
                                                  @click="setActiveTab('bati')"
                                                  :class="{
                                                      'text-primary border-b-2 border-primary': activeTab === 'bati',
                                                      'hover:text-gray-700 hover:border-gray-300': activeTab !== 'bati'
                                                  }"
                                                  class="inline-block w-full p-4"
                                              >
                                                  Bâti
                                              </button>
                                          </li>

                                          <li class="w-1/3 text-center">
                                              <button
                                                  type="button"
                                                  @click="setActiveTab('Cours_amenagees')"
                                                  :class="{
                                                      'text-primary border-b-2 border-primary': activeTab === 'Cours_amenagees',
                                                      'hover:text-gray-700 hover:border-gray-300': activeTab !== 'Cours_amenagees'
                                                  }"
                                                  class="inline-block w-full p-4"
                                              >
                                                  Cours aménagées
                                              </button>
                                          </li>

                                          <li class="w-1/3 text-center">
                                              <button
                                                  type="button"
                                                  @click="setActiveTab('cloture')"
                                                  :class="{
                                                      'text-primary border-b-2 border-primary': activeTab === 'cloture',
                                                      'hover:text-gray-700 hover:border-gray-300': activeTab !== 'cloture'
                                                  }"
                                                  class="inline-block w-full p-4"
                                              >
                                                  Clôtures
                                              </button>
                                          </li>
                                          
                                          <li class="w-1/3 text-center">
                                              <button
                                                  type="button"
                                                  @click="setActiveTab('amenagement')"
                                                  :class="{
                                                      'text-primary border-b-2 border-primary': activeTab === 'amenagement',
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
                                                            <label for="Superficie_totale" class="block text-sm/6 font-medium text-gray-900">Superficie Totale Terrain</label>
                                                            <div>
                                                                <input 
                                                                    type="number"  
                                                                    v-model="form.nbr_surface"   
                                                                    readonly
                                                                    id="nbr_surface" 
                                                                    class="h-9 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="sm:col-span-2">
                                                        <div class="mt-6">
                                                            <label for="Superficie_bati_sol" class="block text-sm/6 font-medium text-gray-900">Superficie Bâti au sol</label>
                                                            <div>
                                                                <input 
                                                                type="number" 
                                                                v-model="form.txt_superficie_bati_sol"   
                                                                id="Superficie_bati_sol" 
                                                                min="0"
                                                                class="h-9 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                            </div>
                                                        </div>
                                                    </div>
                                            
                                                    <div class="sm:col-span-2">
                                                        <div class="mt-6">
                                                            <label for="Secteur" class="block text-sm/6 font-medium text-gray-900">Secteur</label>
                                                            <div> 
                                                                <select type="select" 
                                                                v-model="form.slt_secteur"   
                                                                class="h-9 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                    <option selected desabled></option>
                                                                    <option value="3000">1</option>
                                                                    <option value="2000">2</option>
                                                                    <option value="1000">3</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                
                                                    <div class="sm:col-span-2">
                                                        <div class="mt-6">
                                                            <label for="Prix_metre_carre" class="block text-sm/6 font-medium text-gray-900">Prix mètre Carré</label>
                                                            <div>
                                                                <input 
                                                                type="number" 
                                                                v-model="form.nbr_prix_metre_carre"  
                                                                readonly 
                                                                class="h-9 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                            </div>
                                                        </div>
                                                    </div>   
                                                    <div class="sm:col-span-2">
                                                        <div class="mt-6"> 
                                                            <label for="Valeur_terrain" class="block text-sm/6 font-medium text-gray-900">Valeur du Terrain</label>
                                                            <div>
                                                                <input type="number"
                                                                v-model="form.nbr_valeur_terrain"  
                                                                readonly 
                                                                class="h-9 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!--   BATI -->
                                        <div v-show="activeTab === 'bati'" class="space-y-4">
                                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                                <!-- Boutons radio -->
                                                <div class="sm:col-span-2">
                                                    <div class="h-10 flex items-center ps-8 border border-gray-200 rounded dark:border-primary">
                                                        <MazRadio v-model="form.currentCat" 
                                                        value="Maison individuelle" 
                                                        label="Maison individuelle" />
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <div class="h-10 flex items-center ps-8 border border-gray-200 rounded dark:border-primary">
                                                        <MazRadio v-model="form.currentCat" 
                                                        value="Immeuble collectif" 
                                                        label="Immeuble collectif" />
                                                    </div>
                                                </div>
                                                <br/>    
                                            </div>
                                            <!-- Batiment  Princil-->
                                            <center><h6 class="text-lg"><b>Bâtiment(s) Principal(aux)</b></h6></center>
                                            <div class="w-full flex flex-wrap justify-between gap-4 mt-4">
 
                                                <TransitionGroup >
                                                    <div 
                                                        class="sm:col-span-8">
                                                        <div  class="flex grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-2">
                                                            <div class="sm:col-span-1">
                                                                <label for="Dependant_domaine" class="block text-sm/6 font-medium text-gray-900">Bâtiment </label>
                                                                    <div>   
                                                                        <input 
                                                                        v-model="form.txt_dependant_domainePR" 
                                                                        name="txt_dependant_domainePR" 
                                                                        readonly
                                                                        id="Dependant_domaine" 
                                                                        class="h-8 block w-36 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                                outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                                focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"> 
                                                                    </input>
                                                                    </div>
                                                             </div>
                                                            <div  class="sm:col-span-2">
                                                                <div v-if="form.currentCat === 'Maison individuelle'" class="sm:col-span-1">
                                                                    <!-- Vous avez sélectionné une catégorie de type Maison individuelle. -->
                                                                    <label for="slt_categoriePR" class="block text-sm/6 font-medium text-gray-900">CAT</label>
                                                                    <div>   
                                                                        <select 
                                                                        v-model="form.slt_categoriePR"  
                                                                        id="slt_categoriePR"
                                                                        class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                                outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                                focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                            <option selected desable></option> 
                                                                            <option value="1">1</option>
                                                                            <option value="2">2</option>    
                                                                            <option value="3">3</option>
                                                                            <option value="4">4</option>
                                                                            <option value="5">5</option>
                                                                            <option value="6">6</option>
                                                                            <option value="7">7</option>
                                                                            <option value="8">8</option>
                                                                            <option value="9">9</option>
                                                                            <option value="10">10</option>
                                                                            <option value="11">11</option>  
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div v-else-if="form.currentCat === 'Immeuble collectif'" class="block text-sm/6 font-medium text-gray-900">
                                                                    <!-- Vous avez sélectionné une autre catégorie. -->
                                                                    <label for="slt_categoriePR" class="block text-sm/6 font-medium text-gray-900">CAT</label>
                                                                    <select 
                                                                    v-model="form.slt_categoriePR" 
                                                                    id="slt_categoriePR" 
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                        <option selected desable></option>  
                                                                        <option value="A">A</option>   
                                                                        <option value="B">B</option>  
                                                                        <option value="C">C</option>
                                                                        <option value="D">D</option>
                                                                        <option value="E">E</option>
                                                                        <option value="F">F</option>
                                                                        <option value="G">G</option>
                                                                        <option value="H">H</option>
                                                                        <option value="I">I</option>
                                                                        <option value="J">J</option>
                                                                        <option value="K">K</option>
                                                                        <option value="L">L</option>
                                                                        <option value="M">M</option> 
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1"> 
                                                                <label for="Prix_mettre_carre" class="block text-sm/6 font-medium text-gray-900">Prix m²</label>
                                                                <div>  
                                                                    <input 
                                                                    v-model="form.nbr_prix_metre_carrePR"  
                                                                    readonly
                                                                    type="number" 
                                                                    id="Prix_mettre_carre"  
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>  
                                                            <div  class="sm:col-span-1"> 
                                                                <label for="Surface_bati_sol" class="block text-sm/6 font-medium text-gray-900">Surf B S</label>
                                                                <div>
                                                                    <input  
                                                                    v-model="form.nbr_surface_bati_solPR"
                                                                    name="nbr_surface_bati_solPR"
                                                                    type="number" 
                                                                    id="Surface_bati_sol"  
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1">  
                                                                <label for="nbr_niveauPR" class="block text-sm/6 font-medium text-gray-900">Niveau</label>
                                                                <div>
                                                                    <input  
                                                                    v-model="form.nbr_niveauPR"
                                                                    name="nbr_niveauPR"
                                                                    type="number" 
                                                                    id="nbr_niveauPR" 
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div  class="sm:col-span-1"> 
                                                                <label for="Surface_utile" class="block text-sm/6 font-medium text-gray-900">Surf Utile</label>
                                                                <div>   
                                                                    <input  
                                                                    v-model="form.nbr_surface_utilePR"
                                                                    name="nbr_surface_utilePR"
                                                                    type="number" 
                                                                    id="Surface_utile" 
                                                                    readonly
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1"> 
                                                                <label for="Coeff" class="block text-sm/6 font-medium text-gray-900">Coeff</label>
                                                                <div>  
                                                                    <select  
                                                                    v-model="form.slt_coeffPR"
                                                                    name="slt_coeffPR"
                                                                    id="Coeff"  
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                        outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
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
                                                            <div  class="sm:col-span-1"> 
                                                                <label for="Surface_corriger" class="block text-sm/6 font-medium text-gray-900">S.Corrigee</label>
                                                                <div> 
                                                                    <input                                               
                                                                    v-model="form.nbr_surface_corrigerPR"
                                                                    name="nbr_surface_corrigerPR"
                                                                    type="number" 
                                                                    id="Surface_corriger" 
                                                                    readonly
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                        outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1"> 
                                                                <label for="Valeur" class="block text-sm/6 font-medium text-gray-900">Valeur</label>
                                                                <div>
                                                                    <input 
                                                                    type="number" 
                                                                    v-model="form.nbr_valeurPR"
                                                                    name="nbr_valeurPR"
                                                                    readonly
                                                                    id="Valeur"  
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                        outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </TransitionGroup> 
                                            </div>  
                                            <div class="w-full flex flex-wrap justify-between gap-4 mt-4">
                                                <TransitionGroup  name="slide-fade" tag="div">
                                                    <div 
                                                        v-for="(occupant, i) in form.occupantsBP" 
                                                        :key="occupant.id" 
                                                        class="sm:col-span-8">
                                                        <div  class="flex grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-2">
                                                            <div class="sm:col-span-1">
                                                                <label for="Dependant_domaine" class="block text-sm/6 font-medium text-gray-900">Bâtiment </label>
                                                                    <div>   
                                                                        <select 
                                                                        v-model="occupant.slt_dependant_domaineTG" 
                                                                        name="slt_dependant_domaine" 
                                                                        id="Dependant_domaine" 
                                                                        class="h-8 block w-36 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                                outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                                focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"> 
                                                                            <option value="Secondaire">Secondaire</option>
                                                                            <option value="Tertiaire">Tertiaire</option>
                                                                            <option value="Dépendance">Dépendance</option>
                                                                            <option value="Partie Saillante">Partie Saillante</option>
                                                                        </select>
                                                                    </div>
                                                             </div>
                                                            <div  class="sm:col-span-2">
                                                                <div v-if="currentCat === 'Maison individuelle'" class="sm:col-span-1">
                                                                    <!-- Vous avez sélectionné une catégorie de type Maison individuelle. -->
                                                                    <label for="Dependant_domaine" class="block text-sm/6 font-medium text-gray-900">CAT</label>
                                                                    <div>   
                                                                        <select 
                                                                        v-model="occupant.slt_categorieTG" 
                                                                        name="slt_categorieTG" 
                                                                        id="Dependant_domaine" 
                                                                        class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                                outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                                focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                            <option selected desable></option>
                                                                            <option value="1">1</option>
                                                                            <option value="2">2</option>    
                                                                            <option value="3">3</option>
                                                                            <option value="4">4</option>
                                                                            <option value="5">5</option>
                                                                            <option value="6">6</option>
                                                                            <option value="7">7</option>
                                                                            <option value="8">8</option>
                                                                            <option value="9">9</option>
                                                                            <option value="10">10</option>
                                                                            <option value="11">11</option> 
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div v-else-if="currentCat === 'Immeuble collectif'" class="block text-sm/6 font-medium text-gray-900">
                                                                    <!-- Vous avez sélectionné une autre catégorie. -->
                                                                    <label for="Dependant_domaine" class="block text-sm/6 font-medium text-gray-900">CAT</label>
                                                                    <select 
                                                                    v-model="occupant.slt_categorieTG"
                                                                    name="slt_categorieTG" 
                                                                    id="Dependant_domaine" 
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                        <option selected desable></option>
                                                                        <option value="A">A</option>   
                                                                        <option value="B">B</option>  
                                                                        <option value="C">C</option>
                                                                        <option value="D">D</option>
                                                                        <option value="E">E</option>
                                                                        <option value="F">F</option>
                                                                        <option value="G">G</option>
                                                                        <option value="H">H</option>
                                                                        <option value="I">I</option>
                                                                        <option value="J">J</option>
                                                                        <option value="K">K</option>
                                                                        <option value="L">L</option>
                                                                        <option value="M">M</option> 
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1"> 
                                                                <label for="Prix_mettre_carre" class="block text-sm/6 font-medium text-gray-900">Prix m²</label>
                                                                <div>  
                                                                    <input 
                                                                    v-model="occupant.nbr_prix_metre_carreTG" 
                                                                    readonly
                                                                    type="number" 
                                                                    id="Prix_mettre_carre"  
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>  
                                                            <div  class="sm:col-span-1"> 
                                                                <label for="Surface_bati_sol" class="block text-sm/6 font-medium text-gray-900">Surf B S</label>
                                                                <div>
                                                                    <input  
                                                                    v-model="occupant.nbr_surface_bati_solTG"
                                                                    type="number" 
                                                                    id="Surface_bati_sol"  
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1">  
                                                                <label for="Niveau" class="block text-sm/6 font-medium text-gray-900">Niveau</label>
                                                                <div>
                                                                    <input  
                                                                    v-model="occupant.nbr_niveauTG"
                                                                    name="nbr_niveauTG"
                                                                    type="number" 
                                                                    id="Niveau" 
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div  class="sm:col-span-1"> 
                                                                <label for="Surface_utile" class="block text-sm/6 font-medium text-gray-900">Surf Utile</label>
                                                                <div>   
                                                                    <input  
                                                                    v-model="occupant.nbr_surface_utileTG"
                                                                    name="nbr_surface_utileTG"
                                                                    type="number" 
                                                                    id="Surface_utile" 
                                                                    readonly
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1"> 
                                                                <label for="Coeff" class="block text-sm/6 font-medium text-gray-900">Coeff</label>
                                                                <div>  
                                                                    <select  
                                                                    v-model="occupant.slt_coeffTG"
                                                                    name="slt_coeffTG"
                                                                    id="Coeff"  
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                        outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
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
                                                            <div  class="sm:col-span-1"> 
                                                                <label for="Surface_corriger" class="block text-sm/6 font-medium text-gray-900">S.Corrigee</label>
                                                                <div> 
                                                                    <input                                               
                                                                    v-model="occupant.nbr_surface_corrigerTG"
                                                                    name="nbr_surface_corrigerTG"
                                                                    type="number" 
                                                                    id="Surface_corriger" 
                                                                    readonly
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                        outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1"> 
                                                                <label for="Valeur" class="block text-sm/6 font-medium text-gray-900">Valeur</label>
                                                                <div>
                                                                    <input 
                                                                    type="number" 
                                                                    v-model="occupant.nbr_valeurTG"
                                                                    name="nbr_valeurTG"
                                                                    readonly
                                                                    id="Valeur"  
                                                                    class="h-8 block w-20 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                        outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </TransitionGroup>
                                                <div class="flex mt-6 gap-2">
                                                    <div>
                                                        <button  
                                                            @click="addBlockBP"
                                                            type="button"
                                                            class="h-8 text-white bg-gradient-to-r from-primary via-primary-dark 
                                                            to-primary hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                            focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                            dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                            py-2.5 text-center">
                                                            Ajouter
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <button 
                                                            @click="removeBlockBP"
                                                            type="button"
                                                            class="h-8 text-white bg-gradient-to-r from-primary via-primary-dark 
                                                            to-primary hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                            focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                            dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                            py-2.5 text-center">
                                                            Retirer
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>  
                                            <div  class="flex justify-center gap-6 mb-8">
                                                <div> 
                                                    <label for="Valeur_terrain_bati">Valeur(s) bâtiment (s)</label>
                                                    <input 
                                                        type="number" 
                                                        :value="txt_valeur_terrain_bati"
                                                        readonly
                                                        id="Valeur_terrain_bati"
                                                        class="block w-full rounded-md bg-white 
                                                            px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                            outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                            focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Cours AMENAGEES-->
                                        <div v-show="activeTab === 'Cours_amenagees'" class="space-y-4">

                                            <!-- Cour Aménager Totale -->

                                            <div class="w-full flex flex-wrap justify-between gap-4 mt-4"> 
                                                <TransitionGroup  name="slide-fade" tag="div"> 
                                                    <div 
                                                        v-for="(occupant, i) in form.occupantsCA" 
                                                        :key="occupant.id" 
                                                        class="sm:col-span-8"> 
                                                        <div  class="flex grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"> 
                                                            <div class="sm:col-span-1">
                                                                    <label for="CoursAmenagerTotale" class="block text-sm/6 font-medium text-gray-900">Cours Aménagées Totale</label>
                                                                <div>   
                                                                    <select 
                                                                    v-model="occupant.slt_cours_amenager_totale" 
                                                                    name="slt_dependant_domaine" 
                                                                    id="Dependant_domaine" 
                                                                    class="h-8 block w-34 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                        <option selected desable></option>
                                                                        <option value="Cours Amenagées Totale">Cours Amenagées Totale</option>
                                                                        <option value="Cours Avant">Cours Avant</option> 
                                                                        <option value="Cours Arrière">Cours Arrière</option>
                                                                        <option value="Cours Latérale Droite">Cours Latérale Droite</option>
                                                                        <option value="Cours Latérale Gauche">Cours Latérale Gauche</option>
                                                                        <option value="Cours Restante">Cours Restante</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1">
                                                                    <label for="SurfaceCATotal" class="block text-sm/6 font-medium text-gray-900">Surface</label>
                                                                <div> 
                                                                    <input type="number"
                                                                    v-model="occupant.nbr_surface_ca_total" 
                                                                    name="nbr_surface_ca_total" id="Surface_ca_total"  
                                                                    class="h-8 block w-28 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">                         
                                                                </div>
                                                            </div>  
                                                            <div class="sm:col-span-1">
                                                                    <label for="Categorie_ca_total" class="block text-sm/6 font-medium text-gray-900">Catégorie</label>
                                                                <div> 
                                                                    <select type="select" 
                                                                    v-model="occupant.slt_categorie_ca_total" 
                                                                    name="slt_categorie_ca_total"  
                                                                    id="Categorie_ca_total"  
                                                                    class="h-8 block w-28 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                            <option selected sesabled></option>
                                                                            <option value="17500">1</option>
                                                                            <option value="13500">2</option>
                                                                            <option value="10500">3</option>
                                                                            <option value="5000" >4</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1">
                                                                    <label for="Prix_metre_carre_ca_total" class="block text-sm/6 font-medium text-gray-900">P/m²</label>
                                                                <div> 
                                                                    <input type="number" 
                                                                    v-model="occupant.nbr_prix_metre_carre_ca_total"  
                                                                    readonly
                                                                    name="nbr_prix_metre_carre_ca_total" 
                                                                    id="Prix_metre_carre_ca_total"
                                                                    class="h-8 block w-28 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1">
                                                                    <label for="Coefficient_ca_total" class="block text-sm/6 font-medium text-gray-900">Coefficient</label>
                                                                <div> 
                                                                    <select type="select" 
                                                                    v-model="occupant.nbr_coefficient_ca_total" 
                                                                    name="nbr_coefficient_ca_total" 
                                                                    id="Coefficient_ca_total"  
                                                                    class="h-8 block w-28 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
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
                                                            <div class="sm:col-span-1">
                                                                    <label for="Valeur_ca_total" class="block text-sm/6 font-medium text-gray-900">Valeur</label>
                                                                <div> 
                                                                    <input type="number" 
                                                                    v-model="occupant.nbr_valeur_ca_total" 
                                                                    readonly
                                                                    name="nbr_valeur_ca_total" 
                                                                    id="Valeur_ca_total"  
                                                                    class="h-8 block w-28 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </TransitionGroup>  
                                                <div class="flex gap-4 mt-6">
                                                    <button  
                                                        @click="addBlockCA"
                                                        type="button"
                                                        class="h-8 text-white bg-gradient-to-r from-primary via-primary-dark 
                                                        to-primary hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                        focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                        dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                        py-2.5 text-center">
                                                        Ajouter
                                                    </button>
                                                    
                                                    <button 
                                                        @click="removeBlockCA"
                                                        type="button"
                                                        class="h-8 text-white bg-gradient-to-r from-primary via-primary-dark 
                                                        to-primary hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                        focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                        dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                        py-2.5 text-center">
                                                        Retirer
                                                    </button>
                                                </div>
                                            </div> 
                                            <div  class="flex justify-center gap-6 mb-8">
                                                <div> 
                                                    <label for="Valeur_total_cours" class="block text-sm/6 font-medium text-gray-900">Valeur Total des Cours</label>
                                                    <input type="number" 
                                                    v-model="nbr_valeur_total_ca" 
                                                    readonly
                                                    name="nbr_valeur_total_cours" id="Valeur_total_cours"  class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div> 
                                        </div>

                                        <!-- CLOTURES -->

                                        <div v-show="activeTab === 'cloture'" class="space-y-4">
                                            <!-- <label for="Designation">Designation</label> -->

                                            <div class="w-full flex flex-wrap justify-between gap-4 mt-4">
                                                <TransitionGroup  name="slide-fade" tag="div"> 
                                                    <div 
                                                        v-for="(occupant, i) in form.occupantsCL" 
                                                        :key="occupant.id" 
                                                        class="sm:col-span-8"> 
                                                        <div class="flex grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                                            <div class="sm:col-span-1">
                                                                    <label for="CoursAmenagerTotale" class="block text-sm/6 font-medium text-gray-900">Clôtures</label>
                                                                <div>   
                                                                    <select 
                                                                    v-model="occupant.slt_cours_amenager_clo" 
                                                                    name="slt_dependant_domaine" 
                                                                    id="Dependant_domaine" 
                                                                    class="h-8 block w-34 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                        <option selected desable></option>
                                                                        <option value="Longueur Totale">Longueur Totale</option>
                                                                        <option value="Longueur Avant">Longueur Avant</option> 
                                                                        <option value="Longueur Arrière">Longueur Arrière</option>
                                                                        <option value="Longueur Latérale Droite">Longueur Latérale Droite</option>
                                                                        <option value="Longueur Latérale Gauche">Longueur Latérale Gauche</option>
                                                                        <option value="Longueur Restante">Longueur Restante</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div  class="sm:col-span-1">
                                                                    <label for="Longueur_avant_clo" class="block text-sm/6 font-medium text-gray-900">Linéaire</label>
                                                                <div>  
                                                                    <input type="number" 
                                                                    v-model="occupant.nbr_longueur_avant_clo" 
                                                                    name="nbr_longueur_avant_clo" 
                                                                    id="Longueur_avant_clo"  
                                                                    class="h-8 block w-28 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">                         
                                                                </div>
                                                            </div>  
                                                            <div  class="sm:col-span-1">
                                                                    <label for="Categorie_clo" class="block text-sm/6 font-medium text-gray-900">Catégorie</label>
                                                                <div>  
                                                                    <select type="select" 
                                                                    v-model="occupant.slt_categorie_clo" 
                                                                    name="slt_categorie_clo" 
                                                                    id="Categorie_clo"  
                                                                    class="h-8 block w-28 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                            <option selected sesabled></option>
                                                                            <option value="48269">1</option>
                                                                            <option value="35105">2</option>
                                                                            <option value="28084">3</option>
                                                                            <option value="25744">4</option>
                                                                            <option value="11848">5</option>
                                                                            <option value="4209">6</option>
                                                                            <option value="853">7</option>
                                                                    </select>
                                                                </div>
                                                            </div> 
                                                            <div  class="sm:col-span-1"> 
                                                                    <label for="Prix_metre_carre_clo" class="block text-sm/6 font-medium text-gray-900">P/mL</label>
                                                                <div> 
                                                                    <input type="number" 
                                                                    v-model="occupant.nbr_prix_metre_carre_clo" 
                                                                    name="nbr_prix_metre_carre_clo" 
                                                                    id="Prix_metre_carre_clo"  
                                                                    class="h-8 block w-28 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                            <div class="sm:col-span-1"> 
                                                                    <label for="Coefficient_clo" class="block text-sm/6 font-medium text-gray-900">Coefficient</label>
                                                                <div> 
                                                                    <select type="select"  
                                                                    v-model="occupant.nbr_coefficient_clo" 
                                                                    name="nbr_coefficient_clo" 
                                                                    id="Coefficient_clo"  
                                                                    class="h-8 block w-28 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
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
                                                            <div class="sm:col-span-1"> 
                                                                    <label for="Valeur_clo" class="block text-sm/6 font-medium text-gray-900">Valeur</label>
                                                                <div> 
                                                                    <input type="number"   
                                                                    v-model="occupant.nbr_valeur_clo" 
                                                                    name="nbr_valeur_clo" 
                                                                    id="Valeur_clo"  
                                                                    class="h-8 block w-28 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </TransitionGroup>
                                                <div class="flex gap-4 mt-6">
                                                    <button  
                                                        @click="addBlockCL"
                                                        type="button"
                                                        class="h-8 text-white bg-gradient-to-r from-primary via-primary-dark 
                                                        to-primary hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                        focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                        dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                        py-2.5 text-center">
                                                        Ajouter
                                                    </button>
                                                    
                                                    <button 
                                                        @click="removeBlockCL"
                                                        type="button"
                                                        class="h-8 text-white bg-gradient-to-r from-primary via-primary-dark 
                                                        to-primary hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                        focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                        dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                        py-2.5 text-center">
                                                        Retirer
                                                    </button>
                                                </div>  
                                            </div>
                                            <div class="flex justify-center gap-6 mb-8">
                                                <div> 
                                                    <label for="Valeur_total_clotur">Valeurs Totale des Clôtures</label>
                                                    <input type="number"         
                                                        v-model="nbr_valeur_total_clotur" 
                                                        readonly
                                                        name="nbr_valeur_total_clotur" 
                                                        id="Valeur_total_clotur"  
                                                        class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div>
                                        </div>

                                        <!--    AMENAGEMENTS PARTICULIERS   -->
                                        <div v-show="activeTab === 'amenagement'" class="space-y-4">
                                            <center><h6 class="text-lg">VALEUR</h6></center>  
                                
                                            <div class="w-full flex flex-wrap justify-between gap-4 mt-4">
                                                <TransitionGroup  name="slide-fade" tag="div"> 
                                                    <div 
                                                        v-for="(occupant, i) in form.occupantsAP" 
                                                        :key="occupant.id" 
                                                        class="sm:col-span-8"> 
                                                        <div class="flex grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">  

                                                            <div  class="sm:col-span-1">
                                                                <div>
                                                                    <label for="Designation_1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Designeation</label>
                                                                    <input id="Designation_1"          
                                                                    v-model="occupant.txt_designation_am" 
                                                                    type="text" 
                                                                    class="h-8 block w-34 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6" />
                                                                </div>
                                                            </div>
                                                            <div  class="sm:col-span-1"> 
                                                                <div>
                                                                    <label for="Valeur_unitaire_1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Valeur Unitaire</label>
                                                                    <input type="number"           
                                                                    v-model="occupant.nbr_valeur_unitaire_am"
                                                                    id="Valeur_unitaire_1"  
                                                                    class="h-8 block w-28 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">                         
                                                                </div>
                                                            </div>  
                                                            <div  class="sm:col-span-1">
                                                                <div> 
                                                                    <label for="Quantile_1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Quantité</label>
                                                                    <input type="number"            
                                                                    v-model="occupant.nbr_quantile_am" 
                                                                    id="Quantile_1" 
                                                                    class="h-8 block w-34 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div> 
                                                            <div  class="sm:col-span-1">
                                                                <div> 
                                                                    <label for="Coeficien_1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Coeficien</label>
                                                                    <select type="select"             
                                                                    v-model="occupant.slt_coeficien_am" 
                                                                    id="Coeficien_1"  
                                                                    class="h-8 block w-28 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
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
                                                            <div  class="sm:col-span-1">
                                                                <div> 
                                                                    <label for="Valeur" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Valeur</label>
                                                                    <input type="number"              
                                                                    v-model="occupant.nbr_valeur_am" 
                                                                    id="Valeur"  
                                                                    class="h-8 block w-34 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                                    outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                                    focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </TransitionGroup>
                                                <div class="flex gap-4 mt-6">
                                                    <button  
                                                        @click="addBlockAP"
                                                        type="button"
                                                        class="h-8 text-white bg-gradient-to-r from-primary via-primary-dark 
                                                        to-primary hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                        focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                        dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                        py-2.5 text-center">
                                                        Ajouter
                                                    </button>
                                                    
                                                    <button 
                                                        @click="removeBlockAP"
                                                        type="button"
                                                        class="h-8 text-white bg-gradient-to-r from-primary via-primary-dark 
                                                        to-primary hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                        focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                                        dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                                        py-2.5 text-center">
                                                        Retirer
                                                    </button>
                                                </div>  

                                            </div>
                                            <div  class="flex justify-center gap-6 mb-8">
                                                <div> 
                                                    <label for="valeur_totale_ap">Valeur Totale des A.P</label>
                                                    <input type="number"                    
                                                        v-model="nbr_valeur_totale_ap" 
                                                        readonly
                                                        name="nbr_valeur_totale_ap" 
                                                        id="valeur_totale_ap"  
                                                        class="block w-full rounded-md bg-white 
                                                        px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 
                                                        outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 
                                                        focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                              </div>

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
      </div>
    </div>
  </AuthenticatedLayout>
</template>
