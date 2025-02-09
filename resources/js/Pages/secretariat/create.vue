<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import MazBtn from "maz-ui/components/MazBtn";
import MazRadio from "maz-ui/components/MazRadio";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { onMounted, ref, watch } from "vue";
import CompA from "./CompA.vue";
import CompB from "./CompB.vue";
import axios from "axios";
import { useToast } from "maz-ui";
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
defineOptions({ layout: DefaultLayout });

const slt_region = ref();
const slt_arrondissement = ref();
const slt_departement = ref();
const slt_commune = ref();
const slt_dependant_domaine = ref();

const departements = ref(null);
const arrondissements = ref(null);
const communes = ref(null);


const toast = useToast();

const props = defineProps({
    regions: {
        type: Array,
        default: () => [],
    },
});

const dependantDomaine = ref("Domaine National")
// const activeTab = ref(1);
const activeTab = ref(''); // Valeur de la tab active

const handleTabClick = (event, tab) => {
    event.preventDefault();
    activeTab.value = tab;
};


const form = useForm({
    numDossier: "",
    numDordre: "",
    serviceRendu: "",
    etatCession: "",
    cessionDefinitive: "",
    dateCreation: "",

    slt_region: '',
    slt_departement: '',
    slt_arrondissement: '',
    slt_commune: '',
    txt_lotissement: "",
    txt_num_lotissement: "",
    txt_num_section: "",
    txt_num_parcelle: "",
    txt_num_titre: "",
    txt_titre_mere: "",  // Supprimez l'une des occurrences
    nbr_surface: "",
    slt_document_admin: "",
    txt_num_deliberation: "",
    dt_date_deliberation: "",
    txt_nicad: "",  // Supprimez l'une des occurrences

    rd_immatriculation_terrain:"",
    slt_dependant_domaine: "",
    ussu_bornage: "",
    slt_lf: "",
    txt_num_requisition: "",
    txt_surface_bornage: "",
    dt_date_bornage: "",
    txt_nom_geometre: "",

    slt_titulaire: "",
    txt_nationalite: "",
    slt_civilite: "",
    txt_prenom: "",
    txt_nom: "",
    slt_piece: "",
    txt_numPiece: "",
    dt_date_delivrance: "",
    dt_date_naissance: "",
    txt_lieu_naissance: "",
    txt_adresse: "",
    tel_telephone: "",
    txt_ninea:"",
    eml_email: "",
    txt_representant: "",
    tel_telRepresentant: "",
});


// Mettez à jour les watchers pour utiliser form.selectedRegion, etc.
watch(() => form.slt_region, (newValue) => {
    console.log("🔄 Région sélectionnée :", newValue);
    fetchDepartements();
});

watch(() => form.slt_departement, (newValue) => {
    console.log("🔄 Département sélectionné :", newValue);
    fetchArrondissements();
});

watch(() => form.slt_arrondissement, (newValue) => {
    console.log("🔄 Arrondissement sélectionné :", newValue);
    fetchCommunes();
});
// Les modification pour tenter de auvegarder dasn la base de donnéé

const fetchDepartements = async () => {
    if (!slt_region.value) {
        departements.value = [];
        return;
    }

    try {
        const response = await axios.get(
            `/departements/${slt_region.value}`
        );
        departements.value = response.data.departements;
        console.log("Départements: ", departements.value);
    } catch (error) {
        console.error("Erreur lors du chargement des départements :", error);
    }
};


const fetchArrondissements = async () => {
    if (!slt_departement.value) {
        arrondissements.value = [];
        return;
    }

    try {
        const response = await axios.get(
            `/arrondissements/${slt_departement.value}`
        );
        arrondissements.value = response.data.arrondissements;
        console.log("Arrondissements : ", arrondissements.value);
    } catch (error) {
        console.error("Erreur lors du chargement des arrondissements :", error);
    }
};

const fetchCommunes = async () => {
    if (!slt_arrondissement.value) {
        communes.value = [];
        return;
    }

    try {
        const response = await axios.get(
            `/communes/${slt_arrondissement.value}`
        );
        communes.value = response.data.communes;
        console.log("communes : ", communes.value);
    } catch (error) {
        console.error("Erreur lors du chargement des communes :", error);
    }
};

onMounted(() => {
    console.log("Les régions: ", props.regions);
    // Vous pouvez ajouter une logique pour initialiser les données ici
});



const submitForm = () => {

    form.nbr_surface = parseFloat(form.nbr_surface).toFixed(2);
    console.log({
        slt_region: slt_region?.value,
        slt_departement: slt_departement?.value,
        slt_arrondissement: slt_arrondissement?.value,
        slt_commune: slt_commune?.value,
        nbr_surface: form.nbr_surface,
    });

    form.post(route("secretariat.store"), {
        onSuccess: (page) => {
            const flash = page.props.flash || {};
            const message = flash.error || flash.success || "Opération réussie !";
            
            toast.success(message);
            console.log("✅ Succès Laravel :", page);
        },
        onError: (errors) => {
            if (errors && typeof errors === "object") {
                Object.values(errors).forEach((errorMessage) => {
                    if (errorMessage) {
                        toast.error(errorMessage);
                    }
                });
            }
            console.error("❌ Erreurs Laravel :", errors);
        },
        data: {
            slt_region: slt_region?.value || "",
            slt_departement: slt_departement?.value || "",
            slt_arrondissement: slt_arrondissement?.value || "",
            slt_commune: slt_commune?.value || "",
            rd_immatriculation_terrain: activeTab.value || "",
            slt_dependant_domaine: form.slt_dependant_domaine || "Non spécifié",
            numDossier: form.numDossier || "",
            
        },
    });

};


const mazTabs = [
    { label: "Terrain Non Immatriculé", disabled: false },
    {
        label: "Terrain Immatriculé",
        disabled: false,
    },
];

</script>

<template>
    <Head title="Secretariat" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Secretariat Page Create
            </h2>
        </template>

        <div class="py-12">
            <div class="flex justify-center">
                <div class="w-full max-w-6xl">
                    <div class="bg-white shadow-md rounded-lg">
                        <!-- En-tête du formulaire -->
                        <div class="p-4 border-b bg-gray-100">
                            <h1 class="text-lg font-semibold">Formulaire</h1>
                        </div>

                        <!-- Corps du formulaire -->
                        <form @submit.prevent="submitForm">
                            <div class="p-6">
                                <!-- Section Parcelle -->
                                <h5 class="text-lg font-bold">
                                    Réferences Dossiers
                                </h5>
                                <br />
                                <div class="mb-6">
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"
                                    >
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="numDossier"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                >
                                                    N° Dossier
                                                </label>
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="numDossier"
                                                        v-model="
                                                            form.numDossier
                                                        "
                                                        id="numDossier"
                                                        autocomplete="address-level2"
                                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                    />
                                                    <InputError
                                                        class="mt-2"
                                                        :message="
                                                            form.errors.name
                                                        "
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="numDordre"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >N° d'Ordre</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="numDordre"
                                                        v-model="form.numDordre"
                                                        id="numDordre"
                                                        autocomplete="address-level2"
                                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="serviceRendu"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Service Rendu</label
                                                >
                                                <div class="mt-2">
                                                    <select
                                                        name="serviceRendu"
                                                        v-model="
                                                            form.serviceRendu
                                                        "
                                                        id="serviceRendu"
                                                        autocomplete="address-level2"
                                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                    >
                                                        <option
                                                            selected
                                                            desabled
                                                        ></option>
                                                        <option
                                                            value="Delimitation"
                                                        >
                                                            Delimitation
                                                        </option>
                                                        <option
                                                            value="Extrait de plan"
                                                        >
                                                            Extrait de plan
                                                        </option>
                                                        <option
                                                            value="Implatation"
                                                        >
                                                            Implatation
                                                        </option>
                                                        <option
                                                            value="Plan de situation"
                                                        >
                                                            Plan de situation
                                                        </option>
                                                        <option
                                                            value="Division"
                                                        >
                                                            Division
                                                        </option>
                                                        <option
                                                            value="Reconstitution des limites"
                                                        >
                                                            Reconstitution des
                                                            limites
                                                        </option>
                                                        <option
                                                            value="Etat des lieux"
                                                        >
                                                            Etat des lieux
                                                        </option>
                                                        <option
                                                            value="Expertise immobilière"
                                                        >
                                                            Expertise
                                                            immobilière
                                                        </option>
                                                        <option
                                                            value="Enquête cadastrale"
                                                        >
                                                            Enquête cadastrale
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="etatCession"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Etat de cession</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="etatCession"
                                                        v-model="
                                                            form.etatCession
                                                        "
                                                        id="etatCession"
                                                        autocomplete="address-level2"
                                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="cessionDefinitive"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Cesssion définitive</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="cessionDefinitive"
                                                        v-model="
                                                            form.cessionDefinitive
                                                        "
                                                        id="cessionDefinitive"
                                                        autocomplete="address-level2"
                                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="dateCreation"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Date de creation</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="Date"
                                                        name="dateCreation"
                                                        v-model="
                                                            form.dateCreation
                                                        "
                                                        id="dateCreation"
                                                        autocomplete="address-level2"
                                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br />

                                    <!-- Parcelle -->
                                    <h5 class="text-lg font-bold">
                                        Réferences Parcellelaires
                                    </h5>
                                    <br />
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"
                                    >
                                    
                                        <div class="sm:col-span-1">
                                            <label
                                                for="slt_region"
                                                class="block text-sm font-medium"
                                            >
                                                Région
                                            </label>
                                            <div class="mt-2">
                                                <select
                                                    id="slt_region"
                                                    name="slt_region"
                                                    v-model="slt_region"
                                                    @change="
                                                        fetchDepartements()
                                                    "
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                >
                                                    <option
                                                        v-for="region in regions"
                                                        :key="region.id"
                                                        :value="region.id"
                                                    >
                                                        {{ region?.slt_region }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="departements"
                                                class="block text-sm font-medium"
                                                >Département</label
                                            >
                                            <div class="mt-2">
                                                <select
                                                    id="departements"
                                                    name="slt_departement"
                                                    v-model="
                                                        slt_departement
                                                    "
                                                    @change="
                                                        fetchArrondissements()
                                                    "
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                >
                                                    <option
                                                        value=""
                                                        disabled
                                                        selected
                                                    >
                                                        Choisir ici
                                                    </option>
                                                    <option
                                                        v-for="departement in departements"
                                                        :key="departement.id"
                                                        :value="departement.id"
                                                    >
                                                        {{
                                                            departement.slt_departement
                                                        }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="arrondissements"
                                                class="block text-sm font-medium"
                                                >Arrondissement</label
                                            >
                                            <div class="mt-2">
                                                <select
                                                    id="arrondissements"
                                                    name="slt_arrondissement"
                                                    v-model="
                                                        slt_arrondissement
                                                    "
                                                    @change="fetchCommunes()"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                >
                                                    <option
                                                        value=""
                                                        disabled
                                                        selected
                                                    >
                                                        Choisir ici
                                                    </option>
                                                    <option
                                                        v-for="arrondissement in arrondissements"
                                                        :key="arrondissement.id"
                                                        :value="
                                                            arrondissement.id
                                                        "
                                                    >
                                                        {{
                                                            arrondissement.slt_arrondissement
                                                        }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="communes"
                                                class="block text-sm font-medium"
                                                >Commune</label
                                            >
                                            <div class="mt-2">
                                                <select
                                                    id="communes"
                                                    name="slt_commune  "
                                                    v-model="slt_commune"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                >
                                                    <option
                                                        value=""
                                                        disabled
                                                        selected
                                                    >
                                                        Choisir ici
                                                    </option>
                                                    <option
                                                        v-for="commune in communes"
                                                        :key="commune.id"
                                                        :value="commune.id"
                                                    >
                                                        {{
                                                            commune.slt_commune
                                                        }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="sm:col-span-1">
                                            <label
                                                for="txt_lotissement"
                                                class="block text-sm font-medium"
                                                >Lotissement/Quartier</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_lotissement"
                                                    v-model="
                                                        form.txt_lotissement
                                                    "
                                                    id="txt_lotissement"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="txt_num_lotissement"
                                                class="block text-sm font-medium"
                                                >N° Lot</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_num_lotissement"
                                                    v-model="
                                                        form.txt_num_lotissement
                                                    "
                                                    id="txt_num_lotissement"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Num_section"
                                                class="block text-sm font-medium"
                                                >N° Section</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_num_section"
                                                    v-model="
                                                        form.txt_num_section
                                                    "
                                                    id="Num_section"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Num_parcelle"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >N° Parcelle</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_num_parcelle"
                                                    v-model="
                                                        form.txt_num_parcelle
                                                    "
                                                    id="Num_parcelle"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="numTitre"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >N° Titre</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_num_titre"
                                                    v-model="form.txt_num_titre"
                                                    id="numTitre"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="titreMere"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Titre mère N°</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_titre_mere"
                                                    v-model="
                                                        form.txt_titre_mere
                                                    "
                                                    id="titreMere"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="surface"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Surface</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="number"
                                                    step="0.01" min="0" 
                                                    name="nbr_surface"
                                                    v-model="form.nbr_surface"
                                                    id="surface"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Document_admin"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Document adminitratif</label
                                            >
                                            <div class="mt-2">
                                                <select
                                                    name="slt_document_admin"
                                                    v-model="
                                                        form.slt_document_admin
                                                    "
                                                    id="Document_admin"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                >
                                                    <option
                                                        selected
                                                        desabled
                                                    ></option>
                                                    <option
                                                        value="Lettre D'atribution"
                                                    >
                                                        Lettre D'atribution
                                                    </option>
                                                    <option
                                                        value="Attestation Foncière"
                                                    >
                                                        Attestation Foncière
                                                    </option>
                                                    <option
                                                        value="Notification"
                                                    >
                                                        Notification
                                                    </option>
                                                    <option value="Bail">
                                                        Bail
                                                    </option>
                                                    <option
                                                        value="Acte adminitratif"
                                                    >
                                                        Acte adminitratif
                                                    </option>
                                                    <option
                                                        value="Permis d'occupation"
                                                    >
                                                        Permis d'occupation
                                                    </option>
                                                    <option
                                                        value="Déliberation"
                                                    >
                                                        Déliberation
                                                    </option>
                                                    <option value="Sans Papier">
                                                        Sans Papier
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Num_deliberation"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >N° Déliberation</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_num_deliberation"
                                                    v-model="
                                                        form.txt_num_deliberation
                                                    "
                                                    id="Num_deliberation"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Date_deliberation"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Date Déliberation</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="date"
                                                    name="dt_date_deliberation"
                                                    v-model="
                                                        form.dt_date_deliberation
                                                    "
                                                    id="Date_deliberation"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label
                                                for="Nicad"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Nicad</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_nicad"
                                                    v-model="form.txt_nicad"
                                                    id="Nicad"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- exemple de tabs avec MazUi -->

                                <h5 class="text-lg font-bold">
                                    Réferences Cadastrales
                                </h5>
                                <br />
                                <div>
                                    <!-- Exemple de tabs avec MazUi -->
                                    <MazRadio
                                        v-model="activeTab"
                                        value="Terrain Non Immatriculé"
                                        label="Terrain Non Immatriculé  ."
                                    />
                                    <MazRadio
                                        v-model="activeTab"
                                        value="Terrain Immatriculé"
                                        label="Terrain Immatriculé"
                                    />

                                        <!-- Contenus de chaque section selon les checkboxes sélectionnées -->
                                    <div v-if="activeTab.includes('Terrain Non Immatriculé')" class="maz-py-4">
                                        <!-- Contenu du Tab 1 ici -->
                                        <br/>
                                        <div class="sm:col-span-12">
                                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                                <div class="sm:col-span-2">
                                                    <label
                                                        for="slt_dependant_domaine"
                                                        class="block text-sm font-medium"
                                                    >
                                                        Dépendant du domaine
                                                    </label>
                                                    <div class="mt-2">
                                                        <select
                                                            v-model="slt_dependant_domaine"
                                                            name="slt_dependant_domaine"
                                                            id="slt_dependant_domaine"
                                                            class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                        >
                                                            <option selected disabled>choisi ici</option>
                                                            <option value="Domaine National">Domaine National</option>
                                                            <option value="Domaine Public">Domaine Public</option>
                                                            <option value="Domaine Fluvial">Domaine Fluvial</option>
                                                            <option value="Domaine Maritime">Domaine Maritime</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <br/>
                                    <div v-if="activeTab.includes('Terrain Immatriculé')" class="maz-py-4">
                                        <!-- Contenu du Tab 2 ici -->
                                         <br>
                                        <div class="sm:col-span-12">
                                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                                <div class="sm:col-span-1">
                                                    <label for="Bornage">Issu de bornage</label>
                                                    <div class="mt-2">
                                                        <select
                                                            v-model="form.ussu_bornage"
                                                            name="ussu_bornage"
                                                            id="Bornage"
                                                            class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                        >
                                                            <option value="Immatriculation">Immatriculation</option>
                                                            <option value="Morcellement">Morcellement</option>
                                                            <option value="Rectificatif de Limite">Rectificatif de Limite</option>
                                                            <option value="Fusion">Fusion</option>
                                                            <option value="Morcellement de Copropriété">Morcellement de Copropriété</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <label for="Titre_mere">Titre Mere</label>
                                                    <div class="mt-2">
                                                        <input
                                                            v-model="form.txt_titre_mere"
                                                            type="text"
                                                            name="txt_titre_mere"
                                                            id="Titre_mere"
                                                            autocomplete="address-level2"
                                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <label for="LF">LF</label>
                                                    <div class="mt-2">
                                                        <select
                                                            v-model="form.slt_lf"
                                                            name="slt_lf"
                                                            id="LF"
                                                            autocomplete="address-level2"
                                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                        >
                                                            <option selected desabled></option>
                                                            <option value="NO">NO</option>
                                                            <option value="KG">KG</option>
                                                            <option value="SM">SM</option>
                                                            <option value="SR">SR</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <label for="Num_requisition">N° Requisition</label>
                                                    <div class="mt-2">
                                                        <input
                                                            v-model="form.txt_num_requisition"
                                                            type="text"
                                                            name="txt_num_requisition"
                                                            id="Num_requisition"
                                                            autocomplete="address-level2"
                                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <label for="Surface_bornage">Surfacce au bornage</label>
                                                    <div class="mt-2">
                                                        <input
                                                            v-model="form.txt_surface_bornage"
                                                            type="text"
                                                            name="txt_surface_bornage"
                                                            id="Surface_bornage"
                                                            autocomplete="address-level2"
                                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-1">
                                                    <label for="Date_bornage">Date Bornage</label>
                                                    <div class="mt-2">
                                                        <input
                                                            v-model="form.dt_date_bornage"
                                                            type="date"
                                                            name="dt_date_bornage"
                                                            id="Date_bornage"
                                                            autocomplete="address-level2"
                                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <label for="Nom_geometre">Nom Geometre</label>
                                                    <div class="mt-2">
                                                        <input
                                                            v-model="form.txt_nom_geometre"
                                                            type="text"
                                                            name="txt_nom_geometre"
                                                            id="Nom_geometre"
                                                            autocomplete="address-level2"
                                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br />

                                <!-- Section Titulaire -->
                                <h5 class="text-lg font-bold">
                                    Identité Titulaire de droit
                                </h5>
                                <br />
                                <div class="mb-6">
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"
                                    >
                                        <div class="sm:col-span-2">
                                            <label
                                                for="Titulaire"
                                                class="block text-sm font-medium"
                                                >Titulaire</label
                                            >
                                            <div class="mt-2 grid grid-cols-1">
                                                <select
                                                    name="slt_titulaire"
                                                    v-model="form.slt_titulaire"
                                                    id="Titulaire"
                                                    class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                >
                                                    >
                                                    <option selected disabled>
                                                        Choisir ici
                                                    </option>
                                                    <option
                                                        value="Personne Physique"
                                                    >
                                                        Personne Physique
                                                    </option>
                                                    <option
                                                        value="Perssone Morale"
                                                    >
                                                        Personne Morale
                                                    </option>
                                                    <option value="Etat">
                                                        État
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Nationalite"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Nationalite</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_nationalite"
                                                    v-model="
                                                        form.txt_nationalite
                                                    "
                                                    id="Nationalite"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Civilite"
                                                class="block text-sm font-medium"
                                                >Civilité</label
                                            >
                                            <div class="mt-2">
                                                <select
                                                    name="slt_civilite"
                                                    v-model="form.slt_civilite"
                                                    id="Civilite"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                >
                                                    <option selected disabled>
                                                        Choisir ici
                                                    </option>
                                                    <option value="Mr">
                                                        Mr
                                                    </option>
                                                    <option value="Mme">
                                                        Mme
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Prenom"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Prénom</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_prenom"
                                                    v-model="form.txt_prenom"
                                                    id="Prenom"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="nom"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Nom</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_nom"
                                                    v-model="form.txt_nom"
                                                    id="nom"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="selectePiece"
                                                class="block text-sm font-medium"
                                                >Types de Piece</label
                                            >
                                            <div class="mt-2">
                                                <select
                                                    name="slt_piece"
                                                    v-model="form.slt_piece"
                                                    id="selectePiece"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                >
                                                    <option selected disabled>
                                                        Choisir ici
                                                    </option>
                                                    <option value="CNI">
                                                        CNI
                                                    </option>
                                                    <option value="Passeport">
                                                        Passeport
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="numPiece"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >N° Pièce</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_num_piece"
                                                    v-model="form.txt_numPiece"
                                                    id="numPiece"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="dateDelivrance"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Date Delivrance</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="date"
                                                    name="dt_date_delivrance"
                                                    v-model="
                                                        form.dt_date_delivrance
                                                    "
                                                    id="dateDelivrance"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="dateNaissance"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Date de Naissance</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="date"
                                                    name="dt_date_naissance"
                                                    v-model="
                                                        form.dt_date_naissance
                                                    "
                                                    id="dateNaissance"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="lieuNaissance"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Lieu de Naissance</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_lieu_naissance"
                                                    v-model="
                                                        form.txt_lieu_naissance
                                                    "
                                                    id="lieuNaissance"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="adresse"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Adresse</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_adresse"
                                                    v-model="form.txt_adresse"
                                                    id="adresse"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="telephone"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Telephone</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="tel"
                                                    name="tel_telephone"
                                                    v-model="form.tel_telephone"
                                                    id="telephone"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="ninea"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >NINEA</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_ninea"
                                                    v-model="form.txt_ninea"
                                                    id="ninea"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Email"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Email</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="email"
                                                    name="eml_email"
                                                    v-model="form.eml_email"
                                                    id="Email"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="txt_representant"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Representant</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_representant"
                                                    v-model="
                                                        form.txt_representant
                                                    "
                                                    id="txt_representant"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="tel_telRepresentant"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Telephone Representant</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="tel_telRepresentant"
                                                    v-model="
                                                        form.tel_telRepresentant
                                                    "
                                                    id="tel_telRepresentant"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br />

                                <!-- Bouton de soumission -->

                                <div class="sm:col-span-6 flex justify-center">
                                    <MazBtn type="submit">Enregistrer</MazBtn>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
