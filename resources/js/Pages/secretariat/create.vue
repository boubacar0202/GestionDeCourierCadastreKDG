<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { onMounted, ref } from "vue";
import CompA from "./CompA.vue";
import CompB from "./CompB.vue";

const current = ref("CompA");

const selectedRegion = ref();
const selectedArrondissement = ref();
const selectedDepartement = ref();
const selectedCommune = ref();

const departements = ref(null);
const arrondissements = ref(null);
const communes = ref(null);

const props = defineProps({
    regions: {
        type: Array,
        default: () => [],
    },
    // departements: {
    //     type: Array,
    //     default: () => [],
    // },
    // arrondissements: {
    //     type: Array,
    //     default: () => [],
    // },
    // communes: {
    //     type: Array,
    //     default: () => [],
    // },
});

const form = useForm({
    region: "",
    departement: "",
    lotissement: "",
    numLot: "",
    numSection: "",
    selecteTitulaire: "",
    // Ajouter d'autres champs nécessaires
});

const fetchDepartements = () => {
    console.log("selectedRegion a changé", selectedRegion.value);

    // form.post(
    //     "/fetch-departements",
    //     { region_id: selectedRegion },
    //     {
    //         onSuccess: (page) => {
    //             departements.value = page.props.departements;
    //             arrondissements.value = [];
    //             communes.value = [];
    //         },
    //     }
    // );
};

const fetchArrondissements = () => {
    form.post(
        "/fetch-arrondissements",
        { departement_id: form.value.departement },
        {
            onSuccess: (page) => {
                arrondissements.value = page.props.arrondissements;
                communes.value = [];
            },
        }
    );
};

const fetchCommunes = () => {
    Inertia.post(
        "/fetch-communes",
        { arrondissement_id: form.value.arrondissement },
        {
            onSuccess: (page) => {
                communes.value = page.props.communes;
            },
        }
    );
};

onMounted(() => {
    console.log("Les régions: ", props.regions);
});

const submitForm = () => {
    console.log("Soumettre formulaire: ", form);

    // Décommenter cette ligne pour soumettre le formulaire dans la base de données.👇
    /* form.post(route("scretariat.store"), {
        onFinish: () => form.reset("name"),
    }); */
};

// Tab actif (par défaut : 'stats')
const activeTab = ref("stats");

// Fonction pour changer de tab
const setActiveTab = (tab) => {
    activeTab.value = tab;
};

const activeTabRU = ref("habitation");

// Fonction pour changer de tab
const setActiveTabRU = (tabRU) => {
    activeTabRU.value = tabRU;
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
                                                    for="Num_dossier"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                >
                                                    N° Dossier
                                                </label>
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_num_dossier"
                                                        v-model="
                                                            form.numSection
                                                        "
                                                        id="Num_dossier"
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
                                                    for="Num_dordre"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >N° d'Ordre</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_num_dordre"
                                                        id="Num_dordre"
                                                        autocomplete="address-level2"
                                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="Service_rendu"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Service Rendu</label
                                                >
                                                <div class="mt-2">
                                                    <select
                                                        type="select"
                                                        name="slt_service_rendu"
                                                        id="Service_rendu"
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
                                                    for="Etat_cession"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Etat de cession</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_etat_cession"
                                                        id="Etat_cession"
                                                        autocomplete="address-level2"
                                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="Cession_definitive"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Cesssion définitive</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_cession_definitive"
                                                        id="Cession_definitive"
                                                        autocomplete="address-level2"
                                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="DateCreation"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Date de creation</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="Date"
                                                        name="dt_date_creation"
                                                        id="Date_creation"
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
                                                for="Region"
                                                class="block text-sm font-medium"
                                            >
                                                Région
                                            </label>
                                            <div class="mt-2">
                                                <select
                                                    id="slt_region"
                                                    v-model="selectedRegion"
                                                    @change="
                                                        fetchDepartements(
                                                            selectedRegion
                                                        )
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
                                                for="Departement"
                                                class="block text-sm font-medium"
                                                >Département</label
                                            >
                                            <div class="mt-2">
                                                <select
                                                    id="departments"
                                                    v-model="
                                                        selectedDepartement
                                                    "
                                                    @change="
                                                        fetchArrondissements(
                                                            selectedDepartement
                                                        )
                                                    "
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                >
                                                    <option value="">
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
                                                for="Arrondissement"
                                                class="block text-sm font-medium"
                                                >Arrondissement</label
                                            >
                                            <div class="mt-2">
                                                <select
                                                    id="arrondissements"
                                                    v-model="
                                                        selectedArrondissement
                                                    "
                                                    @change="fetchCommunes"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                >
                                                    <option value="">
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
                                                for="Departement"
                                                class="block text-sm font-medium"
                                                >Commune</label
                                            >
                                            <div class="mt-2">
                                                <select
                                                    id="communes"
                                                    v-model="selectedCommune"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                >
                                                    <option value="">
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
                                                for="Lotissement"
                                                class="block text-sm font-medium"
                                                >Lotissement/Quartier</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_lotissement"
                                                    v-model="form.lotissement"
                                                    id="Lotissement"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Num_lotissement"
                                                class="block text-sm font-medium"
                                                >N° Lot</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_num_lotissement"
                                                    v-model="form.numLot"
                                                    id="Num_lotissement"
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
                                                    v-model="form.numSection"
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
                                                    id="Num_parcelle"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Num_titre"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >N° Titre</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_num_titre"
                                                    id="Num_titre"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Titre_mere"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Titre mère N°</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_titre_mere"
                                                    id="Titre_mere"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Surface"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Surface</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="numbrer"
                                                    name="nbr_surface"
                                                    id="Surface"
                                                    oninput="calculeSurfaceBrute()"
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
                                                    type="select"
                                                    name="slt_document_admin"
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
                                                    id="Nicad"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- exemple de tabs avec MazUi -->
                                <div>
                                    Exemple de tabs avec MazUi
                                    <MazTabs>
                                        <MazTabsBar :items="mazTabs" />

                                        <MazTabsContent>
                                            <MazTabsContentItem
                                                :tab="1"
                                                class="maz-py-4"
                                            >
                                                contenu du tab 1 ici....
                                                <CompA />
                                            </MazTabsContentItem>
                                            <MazTabsContentItem
                                                :tab="2"
                                                class="maz-py-4"
                                            >
                                                contenu du tab 2 ici....
                                                <CompB />
                                            </MazTabsContentItem>
                                        </MazTabsContent>
                                    </MazTabs>
                                </div>
                                <!-- exemple de tabs avec MazUi -->

                                <!-- Reference Cadastral -->
                                <h5 class="text-lg font-bold">
                                    Réferences Cadastrales
                                </h5>
                                <br />
                                <div class="mb-6">
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"
                                    >
                                        <div class="sm:col-span-2">
                                            <div
                                                class="flex items-center ps-4 border border-gray-200 rounded dark:border-gray-700"
                                            >
                                                <input
                                                    v-model="current"
                                                    value="CompA"
                                                    id="bordered-radio-1"
                                                    type="radio"
                                                    name="rd_immatricalation_terrain"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                />
                                                <label
                                                    for="bordered-radio-1"
                                                    class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                                    >Terrain Non
                                                    Immatriculé</label
                                                >
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div
                                                class="flex items-center ps-4 border border-gray-200 rounded dark:border-gray-700"
                                            >
                                                <input
                                                    type="radio"
                                                    v-model="current"
                                                    value="CompB"
                                                    checked
                                                    id="bordered-radio-2"
                                                    name="rd_immatricalation_terrain"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                />
                                                <label
                                                    for="bordered-radio-2"
                                                    class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                                    >Terrain Immatriculé</label
                                                >
                                            </div>
                                        </div>
                                        <KeepAlive>
                                            <component
                                                :is="current"
                                            ></component>
                                        </KeepAlive>
                                    </div>
                                </div>

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
                                                    v-model="form.selectePiece"
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
                                                    id="Prenom"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Nom"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Nom</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_nom"
                                                    id="Nom"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Piece"
                                                class="block text-sm font-medium"
                                                >Types de Piece</label
                                            >
                                            <div class="mt-2">
                                                <select
                                                    name="slt_piece"
                                                    v-model="form.selectePiece"
                                                    id="Piece"
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
                                                for="Num_piece"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >N° Pièce</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_num_piece"
                                                    id="Num_piece"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Date_delivrance"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Date Delivrance</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="date"
                                                    name="dt_date_delivrance"
                                                    id="Date_delivrance"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Date_naissance"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Date de Naissance</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="date"
                                                    name="dt_date_naissance"
                                                    id="Date_naissance"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Lieu_naissance"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Lieu de Naissance</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_lieu_naissance"
                                                    id="Lieu_naissance"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Adresse"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Adresse</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_adresse"
                                                    id="Adresse"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Telephone"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Telephone</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="tel"
                                                    name="tel_telephone"
                                                    id="Telephone"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Ninea"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >NINEA</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_ninea"
                                                    id="Ninea"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="Representant"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Representant</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_representant"
                                                    id="Representant"
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
                                                    id="Email"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bouton de soumission -->

                                <div class="sm:col-span-6 flex justify-center">
                                    <MazBtn type="submit">Enregistrer</MazBtn>
                                    <!-- <button
                                        type="submit"
                                        class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"
                                    >
                                        Enregistrer
                                    </button> -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
