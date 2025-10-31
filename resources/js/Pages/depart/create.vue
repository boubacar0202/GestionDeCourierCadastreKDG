<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import MazBtn from "maz-ui/components/MazBtn";
import MazSelect from "maz-ui/components/MazBtn";
import MazRadio from "maz-ui/components/MazRadio";
import { onMounted, ref, watch, watchEffect, computed  } from "vue";
import axios from "axios";
import { useToast } from "maz-ui"; 
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { Inertia } from '@inertiajs/inertia';  
 

defineOptions({ layout: DefaultLayout });
const toast = useToast()
 
const props = defineProps({
    arrivee: Object,    
    arrivees: Array,
    departs: Array,
    depart: Object,
});
 
// useForm
const form = useForm({
    //  courierdepart 
    txt_numdordrecd:"",
    txt_caracterecd:"",
    dt_datecouriercd:"",
    txt_categoriecd:"",
    txt_referencecourierarriveecd:"",
    txt_referencecourierdepartcd:"",
    txt_nombrepiececd:"",
    txt_referencecd:"",
    txt_objetcd:"",
    txt_destinatairecd:"",
    dt_dateenvoicd:"",
    txt_referencereceptioncd:"",
    txt_observationcd:"",
    txt_dureetraitementcd:"",
    fichierPDFcd: null,
});
 
const fichierPDFcd = ref(null);  
// const fichierPDF = ref(null);

// Récuperer le fichier PDF 
function handleFileUploadcd(event) {
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

    fichierPDFcd.value = file;
    form.fichierPDFcd = file;

    console.log("Fichier PDF sélectionné :", fichierPDFcd.value);
}

// Comotement de la catégories
const showcd = ref(false);
const handleCategorieChangecd = () => {
    showcd.value = form.txt_categoriecd === "Reponse à un Courrier arrivé" || form.txt_categoriecd === "Dossier Retourne";
};
watch(() => form.txt_categoriecd, (newValue) => {
    showcd.value = newValue === "Reponse à un Courrier arrivé" || newValue === "Dossier Retourne";
});
  
// Automaiser le Numéro d'ordre
const fetchNextDossier = async (annee) => {
    try {
        const response = await axios.get(`/depart/next/${annee}`);
        const numero = response.data.num_dordre;

        // ✅ Formater la date au format DD-MM-YYYY
        const date = new Date(form.dt_datecouriercd);
        const day = String(date.getDate()).padStart(2, "0");
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const year = date.getFullYear();
        const dateFormatee = `${day}-${month}-${year}`;

        form.txt_numdordrecd = `${numero}/${dateFormatee}`;
        console.log("✅ Numéro généré :", form.txt_numdordrecd);

    } catch (error) {
        console.error("❌ Erreur :", error);

        const date = new Date();
        const day = String(date.getDate()).padStart(2, "0");
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const year = date.getFullYear();
        const dateFormatee = `${day}-${month}-${year}`;

        form.txt_numdordrecd = `0001/${dateFormatee}`;
    }
};

// Regénérer automatiquement quand la date change
watch(() => form.dt_datecouriercd, (nouvelleDate) => {
    if (nouvelleDate) {
        const annee = new Date(nouvelleDate).getFullYear();
        fetchNextDossier(annee);
    }
}); 
  
// Références disponibles 
const references = ref([]);  
const referenceToExpediteur = ref({});
const referenceToObject = ref({}); 
const referenceToReception = ref({});
  
// Filtrage dynamique des arrivées par catégorie 
// Surveille le changement de catégorie pour charger les références liées
watch(() => form.txt_categoriecd, async (newCategorie) => {
    if (!newCategorie) return;

    try {
        const res = await axios.post('/fetch-references-arrivee', {
            categorie: newCategorie
        });

        // ✅ Mise à jour des références 
        references.value = res.data.references || []; 

        // ✅ Mapping de référence vers expéditeur 
        referenceToExpediteur.value = res.data.map_ref_to_expediteur || {};
        referenceToObject.value = res.data.map_ref_to_objet || {};
        referenceToReception.value = res.data.map_ref_to_reception || {};
  
        form.txt_referencecourierarriveecd = '';
        form.txt_destinatairecd = '';
        form.txt_objetcd = ''; 
        form.txt_referencereceptioncd = ''; 

    } catch (e) {
        console.error('❌ Erreur lors de la récupération des références :', e);
        references.value = []; 
        referenceToExpediteur.value = {};
        referenceToObject.value = {};
        referenceToReception.value = {};
     }
});
 
// Surveille le changement de la référence choisie
watch(() => form.txt_referencecourierarriveecd, (selectedRef) => {
    if (selectedRef && referenceToExpediteur.value[selectedRef]) {
        form.txt_destinatairecd = referenceToExpediteur.value[selectedRef];
    } else {
        form.txt_destinatairecd = '';
    }
});

// Surveiller le categorie et récupérer l'objet 
watch(() => form.txt_referencecourierarriveecd, (selectedRef) => {
    if (selectedRef && referenceToObject.value[selectedRef]) {
        form.txt_objetcd = referenceToObject.value[selectedRef];
    } else {
        form.txt_objetcd = '';
    }
});

  
// reupèration references courrier depart
watch(
  () => [form.txt_numdordrecd, form.dt_datecouriercd],
  ([newNum, newDate]) => {
    if (!newNum || !newDate){
        form.txt_referencecd = '';
        return;
    }  

    // Sécuriser le parsing de la date
    const parseDate = (str) => {
        if (!str) return null;
        // accepter "YYYY-MM-DD" ou "DD-MM-YYYY"
        let parts = str.includes("-") ? str.split("-") : str.split("/");
        if (parts.length !== 3) return null;

        if (parts[0].length === 4) {
            // format "YYYY-MM-DD"
            return new Date(`${parts[0]}-${parts[1]}-${parts[2]}`);
        } else {
            // format "DD-MM-YYYY"
            return new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
        }
    };

    const date = parseDate(newDate);
    if (!date || isNaN(date.getTime())) {
        form.txt_referencecd = "Date invalide";
        return;
    }

    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    const dateFormatee = `${day}-${month}-${year}`; // "29-08-2025"

    // Extraire la partie après le dernier "/"
    const partsNumero = newNum.split("/");
    const complementRef = partsNumero[partsNumero.length - 2].trim();

    form.txt_referencecd = `${complementRef}/MFB/DGID/CSF/CSF-KDG/BCAD du ${dateFormatee}`;

  }
);
 
 
// 🔹 1. Extraire et normaliser la date d'arrivée depuis la référence
const dateArrivee = ref(''); 
watch(
    () => form.txt_referencecourierarriveecd,
    (newReference) => {
        if (!newReference) {
            dateArrivee.value = '';
            return;
        }

        const parts = newReference.split(' du ');
        if (parts.length === 2) {
            let dateString = parts[1].trim();
            dateString = normalizeDate(dateString);
            dateArrivee.value = dateString;
            console.log("✅ Date extraite normalisée :", dateString);
        } else {
            dateArrivee.value = '';
            console.warn("⚠️ Format inattendu :", newReference);
        }
    }
);

// // 🔹 2. Fonction intelligente de normalisation
function normalizeDate(dateStr) {
    if (!dateStr) return "";

    // Remplacer ., / par -
    dateStr = dateStr.replace(/[./]/g, "-");

    // Vérifier si format AAAA-MM-JJ
    if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
        return dateStr; // déjà ISO
    }

    // Vérifier si format JJ-MM-AAAA
    if (/^\d{2}-\d{2}-\d{4}$/.test(dateStr)) {
        const [jour, mois, annee] = dateStr.split("-");
        return `${annee}-${mois}-${jour}`;
    }

    // Vérifier si format JJ-MM-AA (rare mais possible)
    if (/^\d{2}-\d{2}-\d{2}$/.test(dateStr)) {
        const [jour, mois, annee] = dateStr.split("-");
        return `20${annee}-${mois}-${jour}`;
    }

    // Sinon, invalide
    return "";
}

// 🔹 3. Calcul automatique de la durée de traitement
watch(
    () => [dateArrivee.value, form.dt_dateenvoicd, form.txt_categoriecd],
    ([arrivee, envoie, categorie]) => {
        if (!["Reponse à un Courrier arrivé", "Dossier Retourne"].includes(categorie)) {
        form.txt_dureetraitementcd = "";
        return;
        }

        if (!arrivee || !envoie) return;

        // ✅ Convertir "JJ/MM/AAAA" → "AAAA-MM-JJ" si nécessaire
        const toISO = (date) =>
        /^\d{2}\/\d{2}\/\d{4}$/.test(date)
            ? date.split("/").reverse().join("-")
            : date;

        const dArr = new Date(toISO(arrivee));
        const dEnv = new Date(toISO(envoie));

        const diff = Math.ceil((dEnv - dArr) / (1000 * 3600 * 24));

        form.txt_dureetraitementcd = diff <= 0 ? "24 heures" : `${diff} jours`;
    }
);
 
// Soumission du formulaire
const submitForm = function () {  // Ajoutez `async` ici 
    const today = new Date().toISOString().split('T')[0];

    if (form.dt_dateenvoicd > today) {
        toast.error("La date d'envoi dépasse d'aujourd'hui. CORRIGER");
        return;
    }
    // Formulaire Laravel
    form.post(route('depart.store'), {   
        onSuccess: () => {
            toast.success('Enregistré avec succès')
            Inertia.visit(route("depart.create"), { replace: true });
        },
        onError: (errors) => {
            Object.values(errors).forEach(msg => toast.error(msg))
        }
    })

};

</script>

<template>
    <Head title="Courrier Départ" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-primary-txt">
                Enregistrement des Courriers Départs 
            </h2>

        </template>

        <div class="py-12">
            <div class="flex justify-center">
                <div class="w-full max-w-7xl">
                    <div class="bg-white shadow-md">
                        <!-- En-tête du formulaire -->
                        <div   class="p-4 border-b bg-primary">
                            <h1 class="text-lg text-white font-semibold">Formulaire d'enregistrement des Courriers Départs</h1>
                        </div>
                        <!-- Corps du formulaire -->
                        <form @submit.prevent="submitForm">
                            <div class="p-6 rounded-lg">
                                <!-- Section Parcelle --> 
                                <div class="mb-6">
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"
                                    > 
                                        <div class="sm:col-span-2">
                                            <label 
                                                for="txt_numdordrecd"
                                                class="block text-sm/6 font-medium text-primary-txt">
                                                N° Dordre
                                            </label>
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_numdordrecd"
                                                    v-model="form.txt_numdordrecd" 
                                                    required
                                                    readonly 
                                                    autocomplete="off"
                                                    id="txt_numdordrecd"
                                                    class="h-8 block w-full bg-gray-100 cursor-not-allowed rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                        outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                />
                                            </div>
                                        </div>   
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label 
                                                    for="dt_datecouriercd"
                                                    class="block text-sm/6 font-medium text-primary-txt">
                                                    Date Courrier
                                                </label>
                                                <div class="mt-2">
                                                    <input
                                                        type="date"
                                                        name="dt_datecouriercd"
                                                        v-model="form.dt_datecouriercd"  
                                                        autocomplete="off"
                                                        id="dt_datecouriercd"
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
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_categoriecd"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Catégorie</label
                                                >
                                                <div class="mt-2">
                                                    <select
                                                        name="txt_categoriecd"
                                                        v-model="
                                                            form.txt_categoriecd
                                                        " 
                                                        @change="handleCategorieChangecd"
                                                        id="txt_categoriecd"
                                                        autocomplete="off"
                                                        class="h-8  scrollbar-thin scrollbar-thumb-primary scrollbar-track-gray-300 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                        style="max-height: 200px;" 
                                                    >
                                                        <option selected desabled></option>
                                                        <option value="Reponse à un Courrier arrivé">Reponse à un Courrier arrivé</option>
                                                        <option value="Demande">Demande</option>
                                                        <option value="Transmission de Documents">Transmission de Documents</option>
                                                        <option value="Information">Information</option> 
                                                        <option value="Alerte">Alerte</option>
                                                        <option value="Signalement">Signalement</option>
                                                        <option value="Dossier Retourne">Dossier Retourné</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div> 
                                        <div v-if="showcd" class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label for="txt_referencecourierarriveecd" class="block text-sm/6 font-medium text-primary-txt">
                                                    Ref.Courrier Arrivée à Repondre
                                                </label> 
                                                <div class="mt-2">
                                                    <select 
                                                        name="txt_referencecourierarriveecd"
                                                        v-model="form.txt_referencecourierarriveecd"
                                                        id="txt_referencecourierarriveecd"
                                                        autocomplete="off"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    >
                                                        <option disabled value="">Choisir une référence</option>
                                                        <option v-for="(ref, index) in references" :key="index" :value="ref">
                                                            {{ ref }}
                                                        </option> 
                                                    </select>
                                                </div>
                                            </div>
                                        </div>  
                              
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_nombrepiececd"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Nbre de pièces</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_nombrepiececd"
                                                        v-model="
                                                            form.txt_nombrepiececd
                                                        "
                                                        required
                                                        autocomplete="off"
                                                        min="1"
                                                        id="txt_nombrepiececd" 
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-2">
                                                <label
                                                    for="txt_referencecd"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Référence Courrier</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_referencecd"
                                                        v-model="
                                                            form.txt_referencecd
                                                        "
                                                        required
                                                        id="txt_referencecd"
                                                        autocomplete="off"
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
                                                    for="txt_objetcd"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Objet</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_objetcd"
                                                        v-model="
                                                            form.txt_objetcd
                                                        "
                                                        required
                                                        id="txt_objetcd"
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
                                                    for="txt_destinatairecd"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Destinataire</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_destinatairecd"
                                                        v-model="
                                                            form.txt_destinatairecd
                                                        "
                                                        required
                                                        id="txt_destinatairecd"
                                                            autocomplete="off"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />  
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-2">
                                                <label
                                                    for="txt_referencereceptioncd"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Ref. Décharge</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_referencereceptioncd"
                                                        v-model="
                                                            form.txt_referencereceptioncd
                                                        " 
                                                        id="txt_referencereceptioncd"
                                                        autocomplete="off"
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
                                                    for="dt_dateenvoicd"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Date d'envoi</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="date"
                                                        name="dt_dateenvoicd"
                                                        v-model="
                                                            form.dt_dateenvoicd
                                                        "
                                                        required
                                                        id="dt_dateenvoicd"
                                                        autocomplete="off"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div> 
                                        <div v-if="showcd" class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_dureetraitement"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Durrée de traitement</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_dureetraitementcd"
                                                        v-model="
                                                            form.txt_dureetraitementcd
                                                        " 
                                                        id="txt_dureetraitementcd"
                                                        autocomplete="off"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="sm:col-span-2">
                                            <label 
                                                for="txt_caracterecd"
                                                class="block text-sm/6 font-medium text-primary-txt">
                                                Caractères
                                            </label>
                                            <div class="mt-2">
                                                <select
                                                    type="text"
                                                    name="txt_caracterecd"
                                                    v-model="form.txt_caracterecd" 
                                                    autocomplete="off"
                                                    id="txt_numdordrecd"
                                                    class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                        outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                >
                                                    <option selected desabled></option>
                                                    <option value="Connfidentiel">Confidentiel</option>
                                                    <option value="Urgent">Urgent</option>
                                                    <option value="Secret">Secret</option> 
                                                </select>
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
                                                        name="txt_observationcd"
                                                        v-model="
                                                            form.txt_observationcd
                                                        " 
                                                        id="txt_observationcd"
                                                        autocomplete="off"
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
                                                    for="fichier_PDFcd"
                                                    class="block text-sm/6 font-medium text-primary-txt"
                                                    >Importer le Fichier PDF</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="file"
                                                        name="fichier_PDFcd"
                                                        accept="application/pdf"
                                                        @change="handleFileUploadcd"
                                                        id="fichier_PDFcd"
                                                        autocomplete="off"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-primary-txt 
                                                            outline outline-1 -outline-offset-1 outline-primary-only placeholder:text-gray-400 
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
                                    <MazBtn 
                                        type="submit" no-shadow no-hover-effect
                                        class="w-64 h-10 text-white bg-gradient-to-r from-primary via-primary-dark 
                                            to-primary hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                            focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                            dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 
                                            py-2.5 text-center me-2 mb-2"
                                            size="medium"
                                    >
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

