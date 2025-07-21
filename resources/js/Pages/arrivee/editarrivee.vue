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
    txt_caractere: arrivees?.txt_caractere || '',
    dt_datearrivee: arrivees?.dt_datearrivee || '',
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
        'Rencontre', 'Randonnée - Marche', 'Session - Congré',
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
 
 
function submit() {
    form.put(route('arrivee.update', arrivees?.id), {
        preserveScroll: true,
        onSuccess: (page) => {
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
    });
}
 
</script>

<template>
    <Head title="Courrier Arrivées" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Modification : Courriers Arrivées
            </h2>

        </template>

        <div class="py-12">
            <div class="flex justify-center">
                <div class="w-full max-w-6xl">
                    <div class="bg-white shadow-md rounded-lg">
                        <!-- En-tête du formulaire -->
                        <div   class="p-4 border-b bg-gray-100">
                            <h1 class="text-lg font-semibold">Formulaire Courriers Arrivées : Modification ({{ arrivees.txt_numdordre }})</h1>
                        </div>
                        <!-- Corps du formulaire -->
                        <form @submit.prevent="submit">
                            <div class="p-6">
                                <!-- Section Parcelle -->
                                <h5 class="text-lg font-bold">
                                    Réferences Courrier : {{ arrivees.txt_reference }}
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
                                                    class="block text-sm/6 font-medium text-gray-900">
                                                    N° Dordre Arrivée
                                                </label>
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_numdordre" 
                                                        required
                                                        v-model="form.txt_numdordre"  
                                                        id="txt_numdordre"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6" 
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-2">
                                                <label 
                                                    for="txt_caractere"
                                                    class="block text-sm/6 font-medium text-gray-900">
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
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
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
                                    </div>
                                    <br />
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"
                                    >
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label 
                                                    for="dt_datearrivee"
                                                    class="block text-sm/6 font-medium text-gray-900">
                                                    Date Réception
                                                </label>
                                                <div class="mt-2">
                                                    <input
                                                        type="date"
                                                        name="dt_datearrivee"
                                                        v-model="form.dt_datearrivee"  
                                                        required
                                                        id="dt_datearrivee"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6" 
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_numcourier"
                                                    class="block text-sm/6 font-medium text-gray-900"
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
                                                        class="h-8  scrollbar-thin scrollbar-thumb-primary scrollbar-track-gray-300 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    > 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="dt_datecourier"
                                                    class="block text-sm/6 font-medium text-gray-900"
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
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    > 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_reference"
                                                    class="block text-sm/6 font-medium text-gray-900"
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
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_categorie"
                                                    class="block text-sm/6 font-medium text-gray-900"
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
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
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
                                                    class="block text-sm/6 font-medium text-gray-900"
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
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
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
                                        <div  v-if="show"  class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="dt_date"
                                                    class="block text-sm/6 font-medium text-gray-900"
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
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div  v-if="show" class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_heure"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Heure Evenement</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_heure"
                                                        v-model="form.txt_heure" 
                                                        id="txt_heure"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div  v-if="show" class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_lieu"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Lieu Evenement</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_lieu"
                                                        v-model="form.txt_lieu" 
                                                        id="txt_lieu"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_nombrepiece"
                                                    class="block text-sm/6 font-medium text-gray-900"
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
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_objet"
                                                    class="block text-sm/6 font-medium text-gray-900"
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
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_expediteur"
                                                    class="block text-sm/6 font-medium text-gray-900"
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
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_expediteur"
                                                    class="block text-sm/6 font-medium text-gray-900"
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
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    >
                                                        <option value="Saliou FAYE">Saliou FAYE</option>
                                                        <option value="Assane Aidara DIOP">Assane Aidara DIOP</option>
                                                        <option value="El Hadji Malick GUEYE">El Hadji Malick GUEYE</option> 
                                                    </select>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="sm:col-span-4">
                                            <div class="sm:col-span-1">
                                                <label
                                                    for="txt_observation"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Observation</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_observation"
                                                        v-model="form.txt_observation"  
                                                        id="txt_observation"
                                                        autocomplete="address-level2"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
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

