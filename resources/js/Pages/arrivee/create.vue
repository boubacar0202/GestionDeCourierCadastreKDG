<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import MazBtn from "maz-ui/components/MazBtn";
import MazSelect from "maz-ui/components/MazSelect";
import MazRadio from "maz-ui/components/MazRadio";
import { onMounted, ref, watch, watchEffect, computed  } from "vue";
import axios from "axios";
import { useToast } from "maz-ui"; 
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { Inertia } from '@inertiajs/inertia';

 

defineOptions({ layout: DefaultLayout });
const toast = useToast();
const fichierPDF = ref(null);
const today = new Date().toISOString().split('T')[0];


// Récuperer le fichier PDF 
function handleFileUpload(event) {
    const file = event.target.files[0];

    if (!file) return;

    if (file.type !== "application/pdf") {
        toast.error("Veuillez sélectionner un fichier PDF valide.");
        event.target.value = ""; // reset input
        return;
    }

    if (file.size > 100 * 1024 * 1024) { // 100 Mo
        toast.error("Le fichier dépasse 100 Mo !");
        event.target.value = ""; // reset input
        return;
    }

    fichierPDF.value = file;
    form.fichierPDF = file;

    console.log("Fichier PDF sélectionné :", fichierPDF.value);
}

// Formulaire 
const form = useForm({
    //  courrierarrivee
    txt_numdordre:"", 
    txt_caractere:"",
    dt_datearrivee: "",
    txt_numcourier:"",
    dt_datecourier:"",
    txt_reference:"",
    txt_categorie:"",
    txt_designation: '',
    dt_date:"",
    txt_heure:"",
    txt_lieu:"",
    txt_nombrepiece:"",
    txt_objet:"",
    txt_expediteur:"",
    txt_agenttraiteur:"",
    txt_observation:"",
    fichierPDF: null,

});
 
 
// Génération automatique du numéro d'ordre
const fetchNextDossier = async (annee) => {
    try {
        const response = await axios.get(`/arrivee/next/${annee}`);
        const numero = response.data.num_dordre;

        // ✅ Formater la date au format DD-MM-YYYY
        const date = new Date(form.dt_datearrivee);
        const day = String(date.getDate()).padStart(2, "0");
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const year = date.getFullYear();
        const dateFormatee = `${day}-${month}-${year}`;

        form.txt_numdordre = `${numero}/${dateFormatee}`;
        console.log("✅ Numéro généré :", form.txt_numdordre);

    } catch (error) {
        console.error("❌ Erreur :", error);

        const date = new Date();
        const day = String(date.getDate()).padStart(2, "0");
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const year = date.getFullYear();
        const dateFormatee = `${day}-${month}-${year}`;

        form.txt_numdordre = `00001/${dateFormatee}`;
    }
};

// Regénérer automatiquement quand la date change
watch(() => form.dt_datearrivee, (nouvelleDate) => {
    if (nouvelleDate) {
        const annee = new Date(nouvelleDate).getFullYear();
        fetchNextDossier(annee);
    }
});


// Afficher ou cacher les champs de la catégorie "Convocation - Invitation" 
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
const designationsParCategirie = {
    'Demande SERVICES': ['Morcellements', 'Réquisition d\'immatriculation', 'Demande Avis Technique', 'Demande de terrain / Echange', 'Prospection de terrain', 
        'Autorisation de construction', 'Autorisation de lotir', 'Demande d\'états des lieux', 'Demande de délimitation/reconstruction', 
        'Réquisition DSCOS, Tribunal, Litiges','Demande de situation foncière', 'Demande de Cession définitive',
        'Demande de Cession définitive a Titre Gratuit', 'Demande de Régularisation', 'Demande d\'attestation du Cadastre', 
        'Réceptions de lotissements', 'Demande de CIC', 'Duplication de CIC', 'Demande de Titre foncier', 
        'Autirisationde morceler'
    ],
    'Convocation - Invitation': ['Réunion', 'Audience Publique', 'Alerte', 'Visite de site', 
        'Rencontre', 'Randonnée', 'Marche', 'Session', 'Congré', 'Cérémonie', 'Inauguration',
        'Journée dédiée', 'Forum', 'Formation', 'Séminaire', 'Caravane'
    ],
    'Information': ['Note de service', 'Curculaire', 'Rapport', 'PV', 'Compte rendu', 
        'Arrêté', 'Décision', 'Délibération', 'Approbation délibération', 'Transmission lettre', 'Document administratif', 'Texte Juridique'
    ],
    'Réclamation - Signalement': ['Dénonciation', 'Plainte', 
        'Alerte'
    ],
    'Réquisition - Instruction': ['Réquisition matériel', 'Instruction à suivre', 'DE' 
    ], 
 
};
 
const designations = ref([]);
watch(() => form.txt_categorie, (newCategorie) => {
    designations.value = designationsParCategirie[newCategorie] || [];
    form.txt_designation = '' || 'choisis Catégiries'
});
 
// reupèration references courrier arrivée
watch(
  () => [form.txt_numcourier, form.dt_datecourier],
  ([newNum, newDate]) => {
    if (!newDate) return;

    const date = new Date(newDate);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();

    const dateFormatee = `${day}-${month}-${year}`; // "29-08-2026"
    form.txt_reference = `${newNum} du ${dateFormatee}`;
  }
);


// Soumission du formulaire
const submitForm = function () {  // Ajoutez `async` ici
    console.log("📤 Envoi du formulaire :", form);
    console.log("✅ Données finales envoyées à Laravel :", form.data()); 
  
    // Formulaire Laravel
    form.post(route('arrivee.store'), {
        onSuccess: () => {
            toast.success('Enregistré avec succès')
            Inertia.visit(route("arrivee.create"), { replace: true });
        },
        onError: (errors) => {
            Object.values(errors).forEach(msg => toast.error(msg))
        }
    }) 
};
 
</script>

<template>
    <Head title="Courrier Arrivées" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-primary-txt">
                Enregistrement des Courriers Arrivées
            </h2>

        </template>

        <div class="py-12">
            <div class="flex justify-center">
                <div class="w-full border border-primary-only max-w-7xl">
                    <div class="bg-white shadow-md rounded-lg">
                        <!-- En-tête du formulaire -->
                        <div   class="p-4 border-b bg-primary">
                            <h1 class="text-lg text-white font-semibold">Formulaire d'enregistrement des Courriers Arrivés</h1>
                        </div>
                        <!-- Corps du formulaire -->
                        <form @submit.prevent="submitForm">
                            <div class="p-6">
                                <!-- Section Parcelle -->
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
                                                        type="text"
                                                        name="txt_numdordre"  
                                                        readonly
                                                        required
                                                        v-model="form.txt_numdordre"  
                                                        id="txt_numdordre"
                                                        class="h-8 block w-full bg-gray-100 cursor-not-allowed rounded-md bg-white px-3 py-1.5
                                                            text-base text-primary outline outline-1 -outline-offset-1 outline-primary-only 
                                                            placeholder:text-primary-dark focus:outline focus:outline-2 focus:-outline-2 
                                                            focus:outline-primary sm:text-sm/6" 
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label 
                                                    for="dt_datearrivee"
                                                    class="block text-sm/6 font-medium text-primary-txt">
                                                    Date Réception
                                                </label>
                                                <div class="mt-2">
                                                    <input
                                                        type="date"
                                                        name="dt_datearrivee"
                                                        v-model="form.dt_datearrivee"   
                                                        :max="today"
                                                        required
                                                        id="dt_datearrivee"
                                                        autocomplete="off"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary 
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
                                                        class="h-8  scrollbar-thin scrollbar-thumb-primary block w-full rounded-md bg-white 
                                                            px-3 py-1.5 text-base text-primary-txt outline outline-1 -outline-offset-1 outline-primary-only 
                                                            placeholder:text-primary-dark focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
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
                                                        :max="today"
                                                        required
                                                        id="dt_datecourier"
                                                        autocomplete="off"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
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
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
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
                                                    <MazSelect
                                                        type="select"
                                                        name="txt_categorie"
                                                        v-model="form.txt_categorie"
                                                        @change="handleCategorieChange" 
                                                        required
                                                        id="txt_categorie"
                                                        autocomplete="off"
                                                
                                                            :options="[
                                                                'Demande SERVICES',
                                                                'Convocation - Invitation',
                                                                'Information',
                                                                'Réclamation - Signalement',
                                                                'Réquisition - Instruction'
                                                            ]" 
                                                            class="h-8  w-full block w-full rounded-md bg-white px-2 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6
                                                            scrollbar-thin scrollbar-thumb-primary scrollbar-track-primary-darlk"
                                                            option-class="custom-option"   
                                                            option-selected-class="bg-primary text-white  "
                                                        
                                                    /> 
                                                        <!-- <option selected disabled>Choisis Catégorie</option>
                                                        <option value="Demande SERVICES">Demande SERVICES</option>
                                                        <option value="Convocation - Invitation">Convocation - Invitation</option>
                                                        <option value="Information">Information</option>
                                                        <option value="Réclamation - Signalement">Réclamation - Signalement</option>
                                                        <option value="Réquisition - Instruction">Réquisition - Instruction</option> -->
                                                 
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="sm:col-span-2">
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
                                                        autocomplete="off"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6
                                                            scrollbar-thin scrollbar-thumb-primary scrollbar-track-primary-darlk"
                                                    >
                                                        <option selected disabled>Choisis catégorie d'abord</option>
                                                        <option v-for="(designation, index) in designations" :key="index" :value="designation">
                                                            {{ designation }}
                                                        </option> 
                                                    </select>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                for="txt_designation"
                                                class="block text-sm/6 font-medium text-primary-txt"
                                                >
                                                Designation
                                                </label>
                                                <div class="mt-1">
                                                <MazSelect
                                                    v-model="form.txt_designation"
                                                    id="txt_designation"
                                                    name="txt_designation"
                                                    :options="designations"
                                                    placeholder="Choisis catégorie d'abord"
                                                    class="h-8  w-full block w-full rounded-md bg-white px-2 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6
                                                            scrollbar-thin scrollbar-thumb-primary scrollbar-track-primary-darlk"
                                                            option-class="custom-option"   
                                                            option-selected-class="bg-primary text-white"
                                                    required
                                                />
                                                </div>
                                            </div>
                                        </div>

                                        <div  v-if="show"  class="sm:col-span-1">
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
                                                        autocomplete="off"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div  v-if="show" class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_heure"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Heure Evenement</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_heure"
                                                        v-model="form.txt_heure" 
                                                        id="txt_heure"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div  v-if="show" class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_lieu"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Lieu Evenement</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_lieu"
                                                        v-model="form.txt_lieu" 
                                                        id="txt_lieu"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
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
                                                        type="text"
                                                        name="txt_nombrepiece"
                                                        v-model="form.txt_nombrepiece"
                                                        required
                                                        id="txt_nombrepiece"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
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
                                                        type="text"
                                                        name="txt_objet"
                                                        v-model="form.txt_objet"
                                                        required
                                                        id="txt_objet"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
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
                                                        type="text"
                                                        name="txt_expediteur"
                                                        v-model="form.txt_expediteur"
                                                        required
                                                        id="txt_expediteur"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_agenttraiteur"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Agent Traiteur</label
                                                >
                                                <div class="mt-2">
                                                    <MazSelect
                                                    type="select"
                                                    name="txt_agenttraiteur"
                                                    v-model="form.txt_agenttraiteur" 
                                                    required
                                                    id="txt_agenttraiteur"
                                                    autocomplete="off"
                                            
                                                        :options="[
                                                            'Saliou FAYE',
                                                            'Assane Aidara DIOP',
                                                            'El Hadji Malick GUEYE',
                                                            'Moustapha Diop',
                                                            'Daouda Ndiaye',
                                                            'Abdoulaye Camara',
                                                            'Oumar Diop'
                                                        ]" 
                                                        class="h-8  w-full block w-full rounded-md bg-white px-2 py-1.5 text-base text-primary-txt 
                                                        outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6
                                                        scrollbar-thin scrollbar-thumb-primary scrollbar-track-primary-darlk"
                                                        option-class="custom-option"   
                                                        option-selected-class="bg-primary text-white  "
                                                    
                                                    /> 
                                                </div>
                                               
                                                <!-- <div class="mt-2">
                                                    <select
                                                        type="select"
                                                        name="txt_agenttraiteur"
                                                        v-model="form.txt_agenttraiteur"
                                                        required
                                                        id="txt_agenttraiteur"
                                                        autocomplete="off"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    >
                                                        <option value="Saliou FAYE">Saliou FAYE</option>
                                                        <option value="Assane Aidara DIOP">Assane Aidara DIOP</option>
                                                        <option value="El Hadji Malick GUEYE">El Hadji Malick GUEYE</option> 
                                                    </select>
                                                </div> -->
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
                                                    <MazSelect
                                                    type="select"
                                                    name="txt_caractere"
                                                    v-model="form.txt_caractere" 
                                                    id="txt_caractere"
                                                    autocomplete="off"
                                                    placeholder="Choisis Caractère"
                                                        :options="[
                                                            'Confidentiel',
                                                            'Urgent',
                                                            'Secret'
                                                        ]" 
                                                        class="h-8  w-full block w-full rounded-md bg-white px-2 py-1.5 text-base text-primary-txt 
                                                        outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6
                                                        scrollbar-thin scrollbar-thumb-primary scrollbar-track-primary-darlk"
                                                        option-class="custom-option"   
                                                        option-selected-class="bg-primary text-white  "
                                                    
                                                    /> 
                                                    <!-- <select
                                                        type="select"
                                                        name="txt_caractere"
                                          
                                                        readonly
                                                        v-model="form.txt_caractere"  
                                                        autocomplete="off"
                                                        id="txt_caractere"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6" 
                                                    >
                                                        <option selected disabled>Choisis Caractères</option>
                                                        <option value="Confidentiel">Confidentiel</option>
                                                        <option value="Urgent">Urgent</option>
                                                        <option value="Secret">Secret</option>
                                                    </select> -->
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
                                                        type="text"
                                                        name="txt_observation"
                                                        v-model="form.txt_observation"txt_observation
                                                        
                                                        id="txt_observation"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
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
                                                        name="fichier_PDF"
                                                        accept="application/pdf"
                                                        @change="handleFileUpload"
                                                        id="fichier_PDF"
                                                        autocomplete="off"
                                                        placeholder="Importer le fichier"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-primary-dark 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
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


<style scoped>
/* ::v-deep permet de cibler les éléments dans le portal */
::v-deep(.custom-option:hover),
::v-deep(.custom-option:focus),
::v-deep(.custom-option[aria-selected="true"]) {
  background-color: #c7d2fe; /* Couleur claire type indigo-200 */
  color: #1e293b; /* Couleur du texte, exemple slate-800 */
}
</style>


