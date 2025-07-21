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
 

defineOptions({ layout: DefaultLayout });
const toast = useToast()
 
 
const { departs } = defineProps({
    departs: Object,
    arrivees: Array, 
})

 

const form = useForm({
    //  courierdepart 
    txt_numdordrecd: departs?.txt_numdordrecd || '',
    txt_caracterecd: departs?.txt_caracterecd || '',
    dt_datecouriercd: departs?.dt_datecouriercd || '',
    txt_categoriecd: departs?.txt_categoriecd || '',
    txt_referencecourierarriveecd: departs?.txt_referencecourierarriveecd || '',
    txt_referencecourierdepartcd: departs?.txt_referencecourierdepartcd || '',
    txt_nombrepiececd: departs?.txt_nombrepiececd || '',
    txt_referencecd: departs?.txt_referencecd || '',
    txt_objetcd: departs?.txt_objetcd || '',
    txt_destinatairecd: departs?.txt_destinatairecd || '',
    dt_dateenvoicd: departs?.dt_dateenvoicd || '',
    txt_referencereceptioncd: departs?.txt_referencereceptioncd || '',
    txt_observationcd: departs?.txt_observationcd || '',
    txt_dureetraitementcd: departs?.txt_dureetraitementcd || '',
});
 

const show = ref(false);
const handleCategorieChangecd = () => {
    show.value = form.txt_categoriecd === "Reponse à un Courrier arrivé";
};
watch(() => form.txt_categoriecd, (newValue) => {
    show.value = newValue === "Reponse à un Courrier arrivé";
});


// récuperer les categories de courrier arrivee
const categoriescd = {
    "1": "Reponse à un Courrier arrivé",
    "2": "Demande",
    "3": "Transmission de Documents",
    "4": "Information", 
}; 
const designationsParCategiriecd = {
    'Reponse': ['27/C.C.C.KD du 24/01/2025', '0017/SRPS/K du 16/042025'
    ], 
};
 
// Références disponibles 
const references = ref([]);
const expediteurs = ref([]);

// Récupération des données depuis Laravel au montage du composant
onMounted(() => {
    axios.get('/references-arrivee')
        .then(response => {
            console.log(response.data);
            references.value = response.data.references;
        })
        .catch(error => {
            console.error('Erreur lors du chargement des références :', error);
        });
    axios.get('/references-arrivee')
        .then(response => {
            console.log(response.data);
            expediteurs.value = response.data.expediteurs;
        })
        .catch(error => {
            console.error('Erreur lors du chargement des références :', error);
        });
});
 
// reupèration references courrier depart
watch(
    () => [form.txt_numdordrecd, form.dt_datecouriercd],
    ([newNum, newDate]) => {
        form.txt_referencecd = newNum + '/MFB/DGID/CSF/CSF-KDG/BCAD' + ' du '  + newDate
    }
);
  
// reupèration references courrier depart: Durée de traitement 

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

        // ✅ Réinitialisation
        form.txt_referencecourierarriveecd = '';
        form.txt_destinatairecd = '';
    } catch (e) {
        console.error('❌ Erreur lors de la récupération des références :', e);
        references.value = [];
        referenceToExpediteur.value = {};
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
 
// reupèration references courrier depart: Durée de traitement
watch(
    () => [form.dt_dateenvoicd, form.dt_datearriveeCA],
    ([newDateEnvoie, newDateArrivee]) => {
        if (newDateEnvoie && newDateArrivee) {
            const dateEnvoi = new Date(newDateEnvoie);
            const dateArrivee = new Date(newDateArrivee);

            const diffInTime = dateEnvoi.getTime() - dateArrivee.getTime();
            const diffInDays = Math.ceil(diffInTime / (1000 * 3600 * 24));

            form.txt_dureetraitementcd = `${diffInDays} jours`;
        } else {
            form.txt_dureetraitementcd = '';
        }
    }
); 


function submit() {
    form.put(route('depart.update', departs?.id), {
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
    <Head title="Courrier Départ" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Modification Courrier Départ
                
            </h2>

        </template>

        <div class="py-12">
            <div class="flex justify-center">
                <div class="w-full max-w-6xl">
                    <div class="bg-white shadow-md rounded-lg">
                        <!-- En-tête du formulaire -->
                        <div   class="p-4 border-b bg-gray-100">
                            <h1 class="text-lg font-semibold">Formulaire Courriers Départ : Modification N° {{ departs?.txt_numdordrecd }}</h1>
                        </div>
                        <!-- Corps du formulaire -->
                        <form @submit.prevent="submit">
                            <div class="p-6">
                                <!-- Section Parcelle -->
                                <h5 class="text-lg font-bold">
                                    Réferences Courriers
                                </h5>
                                <br />
                                <div class="mb-6">
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"
                                    > 
                                        <div class="sm:col-span-2">
                                            <label 
                                                for="txt_numdordrecd"
                                                class="block text-sm/6 font-medium text-gray-900">
                                                N° Dordre
                                            </label>
                                            <div class="mt-2">
                                                <input
                                                    type="text"
                                                    name="txt_numdordrecd"
                                                    v-model="form.txt_numdordrecd" 
                                                    required
                                                        
                                                    id="txt_numdordrecd"
                                                    class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                        outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                />
                                            </div>
                                        </div> 
                                        <div class="sm:col-span-2">
                                            <label 
                                                for="txt_caracterecd"
                                                class="block text-sm/6 font-medium text-gray-900">
                                                Caractères
                                            </label>
                                            <div class="mt-2">
                                                <select
                                                    type="text"
                                                    name="txt_caracterecd"
                                                    v-model="form.txt_caracterecd" 
                                                    required
                                                        
                                                    id="txt_numdordrecd"
                                                    class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                        outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                        focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                >
                                                    <option selected desabled></option>
                                                    <option value="Connfidentiel">Confidentiel</option>
                                                    <option value="Urgent">Urgent</option>
                                                    <option value="Secret">Secret</option> 
                                                </select>
                                            </div> 
                                        </div>
                                        <div class="sm:col-span-1">
                                            <div class="sm:col-span-1">
                                                <label 
                                                    for="dt_datecouriercd"
                                                    class="block text-sm/6 font-medium text-gray-900">
                                                    Date Courrier
                                                </label>
                                                <div class="mt-2">
                                                    <input
                                                        type="date"
                                                        name="dt_datecouriercd"
                                                        v-model="form.dt_datecouriercd"  
                                                        id="dt_datecouriercd"
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
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
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Catégorie</label
                                                >
                                                <div class="mt-2">
                                                    <select
                                                        name="txt_categoriecd"
                                                        v-model="
                                                            form.txt_categoriecd
                                                        "
                                                        id="txt_categoriecd"
                                                        autocomplete="address-level2"
                                                        @change="handleCategorieChangecd"
                                                        class="h-8  scrollbar-thin scrollbar-thumb-primary scrollbar-track-gray-300 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                        style="max-height: 200px;" 
                                                    >
                                                        <option selected desabled></option>
                                                        <option value="Reponse à un Courrier arrivé">Reponse à un Courrier arrivé</option>
                                                        <option value="Demande">Demande</option>
                                                        <option value="Transmission de Documents">Transmission de Documents</option>
                                                        <option value="Information">Information</option> 
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="show" class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label for="txt_referencecourierarriveecd" class="block text-sm/6 font-medium text-gray-900">
                                                    Ref.Courrier Arrivée à Repondre
                                                </label> 
                                                <div class="mt-2">
                                                    <select
                                                        name="txt_referencecourierarriveecd"
                                                        v-model="form.txt_referencecourierarriveecd"
                                                        id="txt_referencecourierarriveecd"
                                                        autocomplete="address-level2" 
                                                        class="h-8 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900
                                                            outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400
                                                            focus:outline focus:outline-2 focus:-outline-2 focus:outline-primary sm:text-sm/6"
                                                    >
                                                        <option disabled value="">Choisir une référence</option>
                                                        <option selected disabled>{{ departs?.txt_referencecourierarriveecd }}</option>
                                                        <option v-for="(ref, index) in references" :key="index" :value="ref">
                                                            {{ ref }}
                                                        </option>  
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="!show" class="sm:col-span-2">
                                            <div class="sm:col-span-1">
                                                <label for="txt_referencecourierarriveecd" class="block text-sm/6 font-medium text-gray-900">
                                                    Ref.Courrier Depart à Envoyer
                                                </label> 
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_referencecourierdepart"
                                                        v-model="form.txt_referencecourierdepart"
                                                        id="txt_referencecourierdepart"
                                                        autocomplete="off"
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
                                                    for="txt_nombrepiececd"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Nbre de pièces</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        name="txt_nombrepiececd"
                                                        v-model="
                                                            form.txt_nombrepiececd
                                                        "
                                                        id="txt_nombrepiececd"
                                                        autocomplete="address-level2"
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
                                                    for="txt_referencecd"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Référence Courrier</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_referencecd"
                                                        v-model="
                                                            form.txt_referencecd
                                                        "
                                                        required
                                                        id="txt_referencecd"
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
                                                    for="txt_objetcd"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Objet</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_objetcd"
                                                        v-model="
                                                            form.txt_objetcd
                                                        "
                                                        required
                                                        id="txt_objetcd"
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
                                                    for="txt_destinatairecd"
                                                    class="block text-sm/6 font-medium text-gray-900"
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
                                                    for="dt_dateenvoicd"
                                                    class="block text-sm/6 font-medium text-gray-900"
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
                                                    for="txt_referencereceptioncd"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Réf.Récéption</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_referencereceptioncd"
                                                        v-model="
                                                            form.txt_referencereceptioncd
                                                        "
                                                        required
                                                        id="txt_referencereceptioncd"
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
                                                    for="txt_dureetraitement"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Durrée de traitement</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_dureetraitementcd"
                                                        v-model="
                                                            form.txt_dureetraitementcd
                                                        "
                                                        required
                                                        id="txt_dureetraitementcd"
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
                                                    for="txt_observation"
                                                    class="block text-sm/6 font-medium text-gray-900"
                                                    >Observation</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="txt"
                                                        name="txt_observationcd"
                                                        v-model="
                                                            form.txt_observationcd
                                                        "
                                                        required
                                                        id="txt_observationcd"
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

