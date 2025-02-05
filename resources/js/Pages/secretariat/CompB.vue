<script setup>
import { ref, watch } from "vue";

const form = ref({
    slt_ussu_bornage: "",
    txt_titre_mere: "",
    slt_lf: "",
    txt_num_requisition: "",
    txt_surface_bornage: "",
    dt_date_bornage: "",
    txt_nom_geometre: "",
});

watch(
    form,
    (newForm) => {
        console.log("Formulaire mis à jour :", newForm);
    },
    { deep: true }
);
</script>

<template>
    <div class="sm:col-span-12">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="sm:col-span-1">
                <label for="Bornage">Issu de bornage</label>
                <div class="mt-2">
                    <select
                        v-model="form.slt_ussu_bornage"
                        name="slt_ussu_bornage"
                        id="Bornage"
                        class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                    >
                        <option value="Immatriculation">Immatriculation</option>
                        <option value="Morcellement">Morcellement</option>
                        <option value="Rectificatif de Limite">
                            Rectificatif de Limite
                        </option>
                        <option value="Fusion">Fusion</option>
                        <option value="Morcellement de Copropriété">
                            Morcellement de Copropriété
                        </option>
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
</template>
