<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import MazBtn from "maz-ui/components/MazBtn";
import MazRadio from "maz-ui/components/MazRadio";
import { onMounted, ref, watch, watchEffect, computed  } from "vue";
import axios from "axios";
import { useToast } from "maz-ui"; 
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { Inertia } from '@inertiajs/inertia'; 
import { router } from '@inertiajs/vue3';
 
defineOptions({ layout: DefaultLayout });
const toast = useToast(); 

 
const { arrivees } = defineProps({
    arrivees: Object,  
})
 
const form = useForm({
    //  courrierarrivee
    txt_numdordre: arrivees?.txt_numdordre || '',
    dt_datearrivee: arrivees?.dt_datearrivee || '',  
    txt_caractere: arrivees?.txt_caractere || '', 
    txt_numcourier: arrivees?.txt_numcourier || '',
    dt_datecourier: arrivees?.dt_datecourier || '',
    txt_reference: arrivees?.txt_reference || '',
    txt_categorie: arrivees?.txt_categorie || '',
    txt_designation: arrivees?.txt_designation || '',
    dt_date: arrivees?.dt_date || '',
    txt_heure: arrivees?.txt_heure || '',
    txt_lieu: arrivees?.txt_lieu || '',
    txt_nombrepiece: arrivees?.txt_nombrepiece || '',
    txt_objet: arrivees?.txt_objet || '',
    txt_expediteur: arrivees?.txt_expediteur || '',
    txt_agenttraiteur: arrivees?.txt_agenttraiteur || '',
    txt_observation: arrivees?.txt_observation || '',
    fichierPDF: arrivees?.fichierPDF || null,

}); 
 
const show = ref(false);
const handleCategorieChange = () => {
    show.value = form.txt_categorie === "Convocation - Invitation";
};
watch(() => form.txt_categorie, (newValue) => {
    show.value = newValue === "Convocation - Invitation";
});


// récuperer les categories de courrier arrivee
const categories = {
    "1": "Demande SERVICES",
    "2": "Convocation - Invitation",
    "3": "Information",
    "4": "Réclamation - Signalement",
    "5": "Réquisition - Instruction"
};
const designationsParCategorie = {
    'Demande SERVICES': ['Morcellements', 'Réquisition d\'immatriculation', 'Demande de terrain / Echange', 'Prospection de terrain', 
        'Autorisation de construction', 'Autorisation de lotir', 'Demande d\états des lieux', 'Deamnde de délimitation/reconstruction', 
        'Réquisition DSCOS, Tribunal, Litiges','Demande de situation foncière', 'Demande de Cession définitive',
        'Demande de Cession définitive a Titre Gratuit', 'Demande de Régularisation', 'Demande d\'attestation du Cadastre', 
        'Réceptions de lotissements', 'Demande de CIC', 'Duplication de CIC', 'Demande de Titre foncier', 
        'Autirisationde morceler'
    ],
    'Convocation - Invitation': ['Réunion', 'Alerte', 'Visite de site', 
        'Rencontre', 'Randonnée - Marche', 'Session', 'Congré', 'Cérémonie', 'Inauguration', ' Congré',
        'Journée dédiée', 'Forum', 'Formation', 'Séminaire'
    ],
    'Information': ['Note de service - Curculaire', 'Rapport - PV Compte rendu', 
        'Arrêté - Décision - Délibération', 'Document administratif', 'Texte Juridique'
    ],
    'Réclamation - Signalement': ['Dénonciation', 'Plainte', 
        'Alerte'
    ],
    'Réquisition - Instruction': ['Réquisition matériel', 'Instruction à suivre', 'DE' 
    ], 
 
};
 

// récupérer les désignations et modifier la valeur de la désignation
const designations = ref([]);  

const chargerDesignations = (categorie) => {
    const libelleCategorie = categories[categorie];
    let liste = designationsParCategorie[libelleCategorie] || [];

    // Injecter la désignation existante si elle n’est pas dedans
    if (form.txt_designation && !liste.includes(form.txt_designation)) {
        liste = [form.txt_designation, ...liste];
        designations.value = liste;
    }


}; 
onMounted(() => {
    if (form.txt_categorie) {
        chargerDesignations(form.txt_categorie);
    }
}); 
watch(() => form.txt_categorie, (newCategorie, oldCategorie) => {
    chargerDesignations(newCategorie);

    if (newCategorie !== oldCategorie) {
        designations.value = designationsParCategorie[newCategorie] || [];
        form.txt_designation = '';
    }
});
 

// reupèration references courrier arrivée
watch(
    () => [form.txt_numcourier, form.dt_datecourier],
    ([newNum, newDate]) => {
        form.txt_reference = newNum + ' du ' + newDate;
    }
);
  
// 📁 Gestion du changement de fichier
function handleFileChange(event) {
    const file = event.target.files[0];
    if (file) {
        form.fichierPDF = file;
        console.log("Fichier sélectionné:", file.name);
    } else {
        form.fichierPDF = null;
    }
}

async function submit() {
    try {
        console.log("📤 Envoi avec axios...");
        
        const formData = new FormData();
        
        // Ajouter tous les champs UNE SEULE FOIS
        formData.append('_method', 'PUT'); // Important pour Laravel
        formData.append('txt_numdordre', form.txt_numdordre);
        formData.append('dt_datearrivee', form.dt_datearrivee);
        formData.append('txt_caractere', form.txt_caractere);
        formData.append('txt_numcourier', form.txt_numcourier);
        formData.append('dt_datecourier', form.dt_datecourier);
        formData.append('txt_reference', form.txt_reference);
        formData.append('txt_categorie', form.txt_categorie);
        formData.append('txt_designation', form.txt_designation);
        formData.append('dt_date', form.dt_date);
        formData.append('txt_heure', form.txt_heure);
        formData.append('txt_lieu', form.txt_lieu);
        formData.append('txt_nombrepiece', form.txt_nombrepiece);
        formData.append('txt_objet', form.txt_objet);
        formData.append('txt_expediteur', form.txt_expediteur);
        formData.append('txt_agenttraiteur', form.txt_agenttraiteur);
        formData.append('txt_observation', form.txt_observation);
        
        // Ajouter le fichier
        if (form.fichierPDF instanceof File) {
            formData.append('fichierPDF', form.fichierPDF);
        }

        // Debug : vérifiez ce qui est envoyé
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ', value);
        }

        const response = await axios.post(route('arrivee.update', arrivees.id), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });

        toast.success('Modification réussie!');
        Inertia.visit(route("instancearrivee.create"), { replace: true });
        
    } catch (error) {
        console.error('Erreur détaillée:', error.response?.data);
        if (error.response?.data?.errors) {
            Object.values(error.response.data.errors).forEach(err => {
                toast.error(err);
            });
        } else {
            toast.error('Erreur lors de la modification');
        }
    }
}


</script>

<template>
    <Head title="Courrier Arrivées" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-primary-txt">
                Modification : Courriers Arrivées
            </h2>

        </template>

        <div class="py-12">
            <div class="flex justify-center">
                <div class="w-full max-w-6xl">
                    <div class="bg-white shadow-md rounded-lg">
                        <!-- En-tête du formulaire -->
                        <div   class="p-4 border-b bg-primary">
                            <h1 class="text-lg text-white font-semibold">Formulaire de Modification des Courriers Arrivées</h1>
                        </div>
                        <!-- Corps du formulaire -->
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div class="p-6">
                                <!-- Section Parcelle -->
                                <h5 class="text-lg text-primary-txt font-bold">
                                    Modification du Courrier N° : {{ arrivees.txt_reference }}
                                </h5>
                                <br />
                                <div class="mb-6">
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"
                                    > 
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-2">
                                                <label 
                                                    for="txt_numdordre"
                                                    class="block text-sm/6 font-medium text-primary-txt">
                                                    N° Dordre Arrivée
                                                </label>
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_numdordre" 
                                                        required
                                                        v-model="form.txt_numdordre"  
                                                        id="txt_numdordre"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6" 
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label 
                                                    for="dt_datearrivee"
                                                    class="block text-sm/6 font-medium text-primary-txt">
                                                    Date Reception
                                                </label>
                                                <div class="mt-2">
                                                    <input
                                                        type="date"
                                                        name="dt_datearrivee"
                                                        v-model="form.dt_datearrivee"  
                                                        required
                                                        id="dt_datearrivee"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6" 
                                                    />
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                    <br />
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"
                                    >

                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_numcourier"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >N° Courrier</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_numcourier"
                                                        v-model="form.txt_numcourier"
                                                        required
                                                        id="txt_numcourier"
                                                        autocomplete="address-level2"
                                                        class="h-8  scrollbar-thin scrollbar-thumb-primary scrollbar-track-gray-300 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    > 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="dt_datecourier"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Date Courrier</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="date"
                                                        name="dt_datecourier"
                                                        v-model="form.dt_datecourier"
                                                        required
                                                        id="dt_datecourier"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    > 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_reference"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Réf.Courrier Arrivée</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_reference"
                                                        v-model="form.txt_reference"
                                                        required
                                                        id="txt_reference"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_categorie"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Catégorie</label
                                                >
                                                <div class="mt-2">
                                                    <select
                                                        type="select"
                                                        name="txt_categorie"
                                                        v-model="form.txt_categorie"
                                                        @change="handleCategorieChange"
                                                       
                                                        required
                                                        id="txt_categorie"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                        
                                                    > 
                                                        <option selected disabled>Choisis Catégorie</option>
                                                        <option value="Demande SERVICES">Demande SERVICES</option>
                                                        <option value="Convocation - Invitation">Convocation - Invitation</option>
                                                        <option value="Information">Information</option>
                                                        <option value="Réclamation - Signalement">Réclamation - Signalement</option>
                                                        <option value="Réquisition - Instruction">Réquisition - Instruction</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_designation"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Designation</label
                                                >
                                                <div class="mt-1">
                                                    <select
                                                        type="select"
                                                        name="txt_designation"
                                                        v-model="form.txt_designation" 
                                                        required
                                                        id="txt_designation"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    >
                                                        <option selected disabled>Choisis Designation</option> 
                                                        <option v-for="(designation, index) in designations" :key="index" :value="designation">
                                                            {{ designation }} 
                                                        </option> 
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div  v-if="!show"  class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="dt_date"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >
                                                    Date Evenement
                                                </label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="date"
                                                        name="dt_date"
                                                        v-model="form.dt_date"  
                                                        id="dt_date"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div  v-if="!show" class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_heure"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Heure Evenement</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_heure"
                                                        v-model="form.txt_heure" 
                                                        id="txt_heure"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div  v-if="!show" class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_lieu"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Lieu Evenement</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_lieu"
                                                        v-model="form.txt_lieu" 
                                                        id="txt_lieu"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_nombrepiece"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Nbre Pièce</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_nombrepiece"
                                                        v-model="form.txt_nombrepiece"
                                                        required
                                                        id="txt_nombrepiece"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_objet"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Objet</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_objet"
                                                        v-model="form.txt_objet"
                                                        required
                                                        id="txt_objet"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_expediteur"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Expéditeur</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_expediteur"
                                                        v-model="form.txt_expediteur"
                                                        required
                                                        id="txt_expediteur"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_expediteur"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Agent Traiteur</label
                                                >
                                                <div class="mt-2">
                                                    <select
                                                        type="select"
                                                        name="txt_agenttraiteur"
                                                        v-model="form.txt_agenttraiteur"
                                                        required
                                                        id="txt_agenttraiteur"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    >
                                                        <option value="Saliou FAYE">Saliou FAYE</option>
                                                        <option value="Assane Aidara DIOP">Assane Aidara DIOP</option>
                                                        <option value="El Hadji Malick GUEYE">El Hadji Malick GUEYE</option> 
                                                        <option value="Moustapha Diop">Moustapha Diop</option>
                                                        <option value="Daouda Ndiaye">Daouda Ndiaye</option>
                                                        <option value="Abdoulaye Camara">Abdoulaye Camara</option>
                                                        <option value="Oumar Diop">Oumar Diop</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-2">
                                                <label 
                                                    for="txt_caractere"
                                                    class="block text-sm/6 font-medium text-primary-txt">
                                                    Caractères
                                                </label>
                                                <div class="mt-2">
                                                    <select
                                                        type="txt"
                                                        name="txt_caractere"
                                                        required
                                                        readonly
                                                        v-model="form.txt_caractere"  
                                                        id="txt_caractere"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6" 
                                                    >
                                                        <option selected disabled>Choisis Caractères</option>
                                                        <option value="Confidentiel">Confidentiel</option>
                                                        <option value="Urgent">Urgent</option>
                                                        <option value="Secret">Secret</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-4">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_observation"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Observation</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_observation"
                                                        v-model="form.txt_observation"  
                                                        id="txt_observation"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="fichier_PDF"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Importer le Fichier PDF</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="file"
                                                        name="fichierPDF"
                                                        accept="application/pdf"
                                                        @change="handleFileChange"
                                                        id="fichierPDF"
                                                        autocomplete="off"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>  
                                                <!-- Pour ajouter un bouton de suppression de fichier -->
                                                <div v-if="arrivees?.fichierPDF" class="mt-2 flex items-center space-x-2">
                                                    <a :href="`/storage/${arrivees.fichierPDF}`" target="_blank" 
                                                    class="text-blue-600 underline text-sm">
                                                        📄 Voir le PDF
                                                    </a>
                                                    <button @click="deleteFile" 
                                                            class="text-red-600 text-sm hover:text-red-800">
                                                        🗑️ Supprimer
                                                    </button>
                                                </div>
                                                <!-- Fin du bouton de suppression -->
                                                 
                                            </div>
                                        </div>
                                    </div>
                                    <br /> 
                                </div> 

                                <!-- Bouton de soumission -->

                                <div class="sm:col-span-6 flex justify-center">
                                    <MazBtn type="submit" no-shadow no-hover-effect
                                            class="bg-gradient-to-r from-primary via-primary-light to-primary-dark 
                                                hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-primary 
                                                dark:focus:ring-primary-dark shadow-lg shadow-primary/50 
                                                dark:shadow-lg dark:shadow-primary-dark font-medium rounded-lg text-sm 
                                                px-5 py-2.5 text-center">
                                        Enregistrer
                                    </MazBtn>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

