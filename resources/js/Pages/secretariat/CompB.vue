<script setup>
//v-model="form.ussuBornage"
// v-model="form.titreMere"
// v-model="form.lf"
//  v-model="surfaceBornage"
//     v-model="form.dateBornage"
//     v-model="form.numRequisition"
//     v-model="form.nomGeometre"
</script>

<template>
    <div class="sm:col-span-12">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="sm:col-span-1">
                <label for="Bornage">Issu de bornage</label>
                <div class="mt-2">
                    <select
                        name="ussu_bornage"
                        
                        id="Bornage"
                        class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                    >
                        
                        <option value="Immatriculation">
                            Immatriculation
                        </option>
                        <option value="Morcellement">
                            Morcellement
                        </option>
                        <option value="Rectificatif de Limite">
                            Rectificatif de Limite
                        </option>
                        <option value="Fusion">
                            Fusion
                        </option>
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
                        type="text"
                        name="slt_lf"
                        
                        id="LF"
                        autocomplete="address-level2"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                    >
                        <option selected desabled></option>
                        <option value="NO">
                            NO
                        </option>
                        <option value="KG">
                            KG
                        </option>
                        <option value="SM">
                            SM
                        </option>
                        <option value="SR">
                            SR
                        </option>
                    </select>
                </div>
            </div>
            <div class="sm:col-span-1">
                <label for="NumRequisition">N° Requisition</label>
                <div class="mt-2">
                    <input
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
                        type="text"
                        name="dt_date_bornage"
                    
                        id="Date_bornage"
                        autocomplete="address-level2"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                    />
                </div>
            </div>
            <div class="sm:col-span-2">
                <label for="NomGeometre">Nom Geometre</label>
                <div class="mt-2">
                    <input
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

