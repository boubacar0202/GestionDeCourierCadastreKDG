<script setup>


  
function calculeSurfaceBrute() {
        // Récupérer les valeurs des champs input
        const Surface = parseFloat(document.getElementById("Surface").value) || 0;
        const Niveau = parseFloat(document.getElementById("Niveau").value) || 0;

        // Calculer la somme
        const Surface_bati_sol = Surface * Niveau;

        // Afficher la somme dans l'input résultat
        document.getElementById("Surface_bati_sol").value = Surface_bati_sol;
    }

    function calculeSurfaceUtile() {
        // Récupération des valeurs 
        const Surface_bati_sol = parseFloat(document.getElementById("Surface_bati_sol").value) || 0;

        //calcule
        const Surface_utile = Surface_bati_sol * 0.78;

        //affichafge
        document.getElementById("Surface_Utile").value = Surface_utile;
    }

    function calculesurfaceCorriger() {
        // Récupération 
        const Surface_utile = parseFloat(document.getElementById("Surface_utile").value) || 0;
        const Coeff = parseFloat(document.getElementById("Coeff").valur) || 0;

        // Calcule surfece Corrriger
        const nbr_surface_corriger = Surface_utile * Coeff;

        //affichage
        document.getElementById("Surface_corriger").value = Surface_utile 
    }

</script>