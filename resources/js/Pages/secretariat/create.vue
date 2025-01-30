<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import MazBtn from "maz-ui/components/MazBtn";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { onMounted, ref } from "vue";
import CompA from "./CompA.vue";
import CompB from "./CompB.vue";
import axios from "axios";
import { useToast } from "maz-ui";

const selectedRegion = ref();
const selectedArrondissement = ref();
const selectedDepartement = ref();
const selectedCommune = ref();

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

const form = useForm({
    numDossier: "",
    numDordre: "",
    serviceRendu: "",
    etatCession: "",
    cessionDefinitive: "",
    dateCreation: "",
    region: "",
    departement: "",
    commune: "",
    txt_lotissement: "",
    txt_num_lotissement: "",
    txt_num_section: "",
    txt_num_parcelle: "",
    txt_num_titre: "",
    txt_titre_mere: "",
    nbr_surface: "",
    slt_document_admin: "",
    txt_num_deliberation: "",
    dt_date_deliberation: "",
    txt_nicad: "",
    slt_dependant_domaine: "",
    slt_ussu_bornage: "",
    txt_titre_mere: "",
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
    txt_num_piece: "",
    dt_date_delivrance: "",
    dt_date_naissance: "",
    txt_lieu_naissance: "",
    txt_adresse: "",
    tel_telephone: "",
    txt_nicad: "",
    eml_email: "",
    txt_representant: "",
    tel_representant: "",
    // Ajouter d'autres champs nécessaires
});

// Les modification pour tenter de auvegarder dasn la base de donnéé

const fetchDepartements = async () => {
    if (!selectedRegion.value) {
        departements.value = [];
        return;
    }

    try {
        const response = await axios.get(
            `/departements/${selectedRegion.value}`
        );
        departements.value = response.data.departements;
        console.log("Départements: ", departements.value);
    } catch (error) {
        console.error("Erreur lors du chargement des départements :", error);
    }
};

const fetchArrondissements = async () => {
    if (!selectedDepartement.value) {
        arrondissements.value = [];
        return;
    }

    try {
        const response = await axios.get(
            `/arrondissements/${selectedDepartement.value}`
        );
        arrondissements.value = response.data.arrondissements;
        console.log("Arrondissements : ", arrondissements.value);
    } catch (error) {
        console.error("Erreur lors du chargement des arrondissements :", error);
    }
};

const fetchCommunes = async () => {
    if (!selectedArrondissement.value) {
        communes.value = [];
        return;
    }

    try {
        const response = await axios.get(
            `/communes/${selectedArrondissement.value}`
        );
        communes.value = response.data.communes;
        console.log("communes : ", communes.value);
    } catch (error) {
        console.error("Erreur lors du chargement des communes :", error);
    }
};

onMounted(() => {
    // console.log("Les régions: ", props.regions);
});

const submitForm = () => {
    console.log("Soumettre formulaire: ", form.data());

    // Décommenter cette ligne pour soumettre le formulaire dans la base de données.👇
    form.post(route("secretariat.store"), {
        onSuccess: (page) => {
            let message = ref("");
            if (page.props.flash.error) {
                message = page.props.flash.error;
            } else {
                message = page.props.flash.success;
            }
            toast.success(message);
        },
        onError: (errors) => {
            if (errors) {
                Object.values(errors).forEach((errorMessage) => {
                    toast.error(errorMessage);
                });
            }
        },
        // onFinish: () => form.reset("name"),
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
                                                for="Region"
                                                class="block text-sm font-medium"
                                            >
                                                Région
                                            </label>
                                            <div class="mt-2">
                                                <select
                                                    id="slt_region"
                                                    name="selectedRegion"
                                                    v-model="selectedRegion"
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
                                                for="Departement"
                                                class="block text-sm font-medium"
                                                >Département</label
                                            >
                                            <div class="mt-2">
                                                <select
                                                    id="departements"
                                                    name="selectedDepartement"
                                                    v-model="
                                                        selectedDepartement
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
                                                for="Arrondissement"
                                                class="block text-sm font-medium"
                                                >Arrondissement</label
                                            >
                                            <div class="mt-2">
                                                <select
                                                    id="arrondissements"
                                                    name="selectedArrondissement"
                                                    v-model="
                                                        selectedArrondissement
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
                                                for="Departement"
                                                class="block text-sm font-medium"
                                                >Commune</label
                                            >
                                            <div class="mt-2">
                                                <select
                                                    id="communes"
                                                    name="selectedCommune"
                                                    v-model="selectedCommune"
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
                                                for="Lotissement"
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
                                                    v-model="
                                                        form.txt_num_lotissement
                                                    "
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
                                                    type="numbrer"
                                                    name="nbr_surface"
                                                    v-model="form.nbr_surface"
                                                    id="surface"
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
                                    <MazTabs>
                                        <MazTabsBar :items="mazTabs" />
                                        <MazTabsContent>
                                            <MazTabsContentItem
                                                :tab="1"
                                                class="maz-py-4"
                                            >
                                                <!-- contenu du tab 1 ici.... -->
                                                <CompA />
                                            </MazTabsContentItem>
                                            <MazTabsContentItem
                                                :tab="2"
                                                class="maz-py-4"
                                            >
                                                <!-- contenu du tab 2 ici....  -->
                                                <CompB />
                                            </MazTabsContentItem>
                                        </MazTabsContent>
                                    </MazTabs>
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
                                                    v-model="form.txt_num_piece"
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
                                                    id="email"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="representant"
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
                                                    id="representant"
                                                    autocomplete="address-level2"
                                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <label
                                                for="telephoneRepresentant"
                                                class="block text-sm/6 font-medium text-gray-900"
                                                >Telephone Representant</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="tel_representant"
                                                    v-model="
                                                        form.tel_representant
                                                    "
                                                    id="telephoneRepresentant"
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
