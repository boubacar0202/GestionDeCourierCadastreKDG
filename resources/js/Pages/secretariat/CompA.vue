<script setup></script>

<template>
    <div class="sm:col-span-6">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="sm:col-span-2">
                <label for="Dependant_domaine" class="block text-sm font-medium"
                    >Dépendant du domaine</label
                >
                <div class="mt-2">
                    <select
                        name="slt_dependant_domaine"
                        v-model="form.dependantDomaine"
                        id="Dependant_domaine"
                        class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                    >
                        <option value="Domaine National">
                            Domaine National 
                        </option>
                        <option value="Domaine Public">
                            Domaine Public
                        </option>
                        <option value="Domaine Fluvial">
                            Domaine Fluvial
                        </option>
                        <option value="Domaine Maritime">
                            Domaine Maritime
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>
