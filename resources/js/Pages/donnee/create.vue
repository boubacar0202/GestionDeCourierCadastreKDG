<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Donnee"/>

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Base de données
            </h2>
           
        </template>
     
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-8 lg:px-12">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div class="container">
                        <div class="card">
                            <div class="card-header">
                                <div class="p-4 border-b bg-gray-100">
                                    <h1 class="text-lg font-semibold">Base de données</h1>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-strictped table-bordered">
                                    <thead >
                                        <tr>

                                            <th scope="col" class="px-6 py-3">
                                                N°
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_dossier
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Region
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Departement
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Commune
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_dordre
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_service_dendu
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_etat_cession
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_cession_definitive
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                dt_date_creation
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Numéro_Parcelle
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_lotissement
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_lotissement
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_section
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_parcelle
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_titre
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_surface
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_document_admin
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_deliberation
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                dt_date_deliberation
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_nicad  
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                rd_immatricalation_terrain
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_dependant_domaine
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                ussu_bornage
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_titre_mere
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_lf
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_num_requisition
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_surface_bornage
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                dt_date_bornage
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_nom_geometre
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_titulaire
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_nationalite
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_civilite
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_prenom
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_nom
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_piece
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_numPiece
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                dt_date_delivrance
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                dt_date_naissance
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_lieu_naissance
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_adresse
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                tel_telephone
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_ninea
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                eml_email
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_representant
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_telRepresentant
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_reference_usage
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_occupan_habitaion_1
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_activite_principal_hbt_1
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_ninea_occupan_hbt_1
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                tel_tel_occupant_hbt_1
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_montant_loyer_hbt_1
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_activite_commercial
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_occopan_commercial
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_activite_industriel
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_occopan_industriel
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_activite_agricole
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_occopan_agricole
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_activite_professionnelle
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_occopan_professionnelle
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_activite_culte
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_occopan_culte
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_Activite_administratif
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_occupan_administratif
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_date_devaluation
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_superficie_totale
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_superficie_bati_sol
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_secteur
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_prix_metre_carré
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_terrain
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_type_residence
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                rd_type_maissons
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                chk_bati_principal
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_cat
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_prix_metre_carre
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_surface_bati_sol
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_niveau
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_surface_brute
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_surface_utile
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_coeff
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_surface_corriger
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_valeur_terrain_bati
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                chk_cours_amenager_totale
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_surface_ca_total
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_categorie_ca_total
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_prix_metre_carre_ca_total
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_coefficient_ca_total
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_ca_total
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_total_cours
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                chk_perimetre_cloture
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_longueur_avant_clo
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_categorie_clo
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_prix_metre_carre_clo
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_coefficient_clo
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_clo
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_total_clotur
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                txt_designation_am
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_unitaire_am
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_quantile_am
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                slt_coeficien_am
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_am
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                nbr_valeur_totale_ap
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                ACTIONS
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                N°
                                            </th>
                                            <td class="px-6 py-4">
                                                txt_num_dossier
                                            </td>
                                            <td class="px-6 py-4">
                                                Region
                                            </td>
                                            <td class="px-6 py-4">
                                                Departement
                                            </td>
                                            <td class="px-6 py-4">
                                                Commune
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_num_dordre
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_service_dendu
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_etat_cession
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_cession_definitive
                                            </td>
                                            <td class="px-6 py-4">
                                                dt_date_creation
                                            </td>
                                            <td class="px-6 py-4">
                                                Numéro_Parcelle
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_lotissement
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_num_lotissement
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_num_section
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_num_parcelle
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_num_titre
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_surface
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_document_admin
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_num_deliberation
                                            </td>
                                            <td class="px-6 py-4">
                                                dt_date_deliberation
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_nicad  
                                            </td>
                                            <td class="px-6 py-4">
                                                rd_immatricalation_terrain
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_dependant_domaine
                                            </td>
                                            <td class="px-6 py-4">
                                                ussu_bornage
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_titre_mere
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_lf
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_num_requisition
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_surface_bornage
                                            </td>
                                            <td class="px-6 py-4">
                                                dt_date_bornage
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_nom_geometre
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_titulaire
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_nationalite
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_civilite
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_prenom
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_nom
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_piece
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_numPiece
                                            </td>
                                            <td class="px-6 py-4">
                                                dt_date_delivrance
                                            </td>
                                            <td class="px-6 py-4">
                                                dt_date_naissance
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_lieu_naissance
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_adresse
                                            </td>
                                            <td class="px-6 py-4">
                                                tel_telephone
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_ninea
                                            </td>
                                            <td class="px-6 py-4">
                                                eml_email
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_representant
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_telRepresentant
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_reference_usage
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_occupan_habitaion_1
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_activite_principal_hbt_1
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_ninea_occupan_hbt_1
                                            </td>
                                            <td class="px-6 py-4">
                                                tel_tel_occupant_hbt_1
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_montant_loyer_hbt_1
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_activite_commercial
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_occopan_commercial
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_activite_industriel
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_occopan_industriel
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_activite_agricole
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_occopan_agricole
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_activite_professionnelle
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_occopan_professionnelle
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_activite_culte
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_occopan_culte
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_Activite_administratif
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_occupan_administratif
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_date_devaluation
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_superficie_totale
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_superficie_bati_sol
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_secteur
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_prix_metre_carré
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_valeur_terrain
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_type_residence
                                            </td>
                                            <td class="px-6 py-4">
                                                rd_type_maissons
                                            </td>
                                            <td class="px-6 py-4">
                                                chk_bati_principal
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_cat
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_prix_metre_carre
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_surface_bati_sol
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_niveau
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_surface_brute
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_surface_utile
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_coeff
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_surface_corriger
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_valeur
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_valeur_terrain_bati
                                            </td>
                                            <td class="px-6 py-4">
                                                chk_cours_amenager_totale
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_surface_ca_total
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_categorie_ca_total
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_prix_metre_carre_ca_total
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_coefficient_ca_total
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_valeur_ca_total
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_valeur_total_cours
                                            </td>
                                            <td class="px-6 py-4">
                                                chk_perimetre_cloture
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_longueur_avant_clo
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_categorie_clo
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_prix_metre_carre_clo
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_coefficient_clo
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_valeur_clo
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_valeur_total_clotur
                                            </td>
                                            <td class="px-6 py-4">
                                                txt_designation_am
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_valeur_unitaire_am
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_quantile_am
                                            </td>
                                            <td class="px-6 py-4">
                                                slt_coeficien_am
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_valeur_am
                                            </td>
                                            <td class="px-6 py-4">
                                                nbr_valeur_totale_ap
                                            </td>
                                            <td class="flex items px-6 py-6">
                                                <div class="mt-2">
                                                    <MazBtn pastel size="sm">Modifier</MazBtn>
                                                </div>
                                                <div class="container">
                                                    <p>.</p>
                                                </div>
                                                <div class="mt-2">
                                                    <MazBtn color="danger" pastel size="sm">Supprimer</MazBtn>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                
                                            </th>
                                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                               <!--{{$item->region}}    -->
                                               Debut
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               
                                            </td>
                                            <td class="px-6 py-4">
                                               Fin_Debut
                                            </td>
                                            <td class="flex items px-6 py-6">
                                                <div class="mt-2">
                                                    <MazBtn pastel size="sm">Modifier</MazBtn>
                                                </div>
                                                <div class="container">
                                                    <p>.</p>
                                                </div>
                                                <div class="mt-2">
                                                    <MazBtn color="danger" pastel size="sm">Supprimer</MazBtn>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
