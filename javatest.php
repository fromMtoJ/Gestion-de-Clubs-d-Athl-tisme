<script>
    function chargerDisciplines() {
        var clubSelect = document.getElementById("clubs");
        var disciplineSelect = document.getElementById("disciplines");
        disciplineSelect.innerHTML = "<option value=''>Sélectionnez une discipline</option>";

        var selectedClub = clubSelect.value;
        if (selectedClub !== "") {
            // Effectuez une requête AJAX pour récupérer les disciplines associées au club sélectionné
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var disciplines = JSON.parse(xhr.responseText);
                    disciplines.forEach(function(discipline) {
                        disciplineSelect.innerHTML += "<option value='" + discipline + "'>" + discipline + "</option>";
                    });
                }
            };
            xhr.open("GET", "recuperer_disciplines.php?club=" + encodeURIComponent(selectedClub), true);
            xhr.send();
        }
    }

    function chargerInstallations() {
        var disciplineSelect = document.getElementById("disciplines");
        var installationSelect = document.getElementById("installations");
        installationSelect.innerHTML = "<option value=''>Séléctionnez une installation</option>";

        var selectedDiscipline = disciplineSelect.value;
        if (selectedDiscipline !== "") {
            // Effectuez une requête AJAX pour récupérer les installations associées à la discipline sélectionnée
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var installations = JSON.parse(xhr.responseText);
                    installations.forEach(function(installation) {
                        installationSelect.innerHTML += "<option value='" + installation + "'>" + installation + "</option>";
                    });
                }
            };
            xhr.open("GET", "recuperer_installations.php?discipline=" + encodeURIComponent(selectedDiscipline), true);
            xhr.send();
        }
    }
</script>

<label for="clubs">Club :</label>
<select name="clubs" id="clubs" onchange="chargerDisciplines()">
    <!-- Options de club ici -->
</select>

<div id="disciplineDiv">
    <label for="disciplines">Discipline :</label>
    <select name="disciplines" id="disciplines" onchange="chargerInstallations()">
        <option value="">Sélectionnez une discipline</option>
        <!-- Options de discipline chargées dynamiquement -->
    </select>
</div>

<div id="installationDiv">
    <label for="installations">Installations :</label>
    <select name="installations" id="installations">
        <option value="">Sélectionnez une installation</option>
        <!-- Options d'installation chargées dynamiquement -->
    </select>
</div>
<?php
// Récupérez le club sélectionné depuis la requête GET
$selectedClub = $_GET['club'];

// Effectuez une requête à la base de données pour récupérer les disciplines associées au club sélectionné
// Assurez-vous de sécuriser vos requêtes pour éviter les injections SQL

// Par exemple, vous pouvez retourner les disciplines sous forme de tableau JSON
$disciplines = ["Discipline 1", "Discipline 2", "Discipline 3"];
echo json_encode($disciplines);
?>
<?php
// Récupérez la discipline sélectionnée depuis la requête GET
$selectedDiscipline = $_GET['discipline'];

// Effectuez une requête à la base de données pour récupérer les installations associées à la discipline sélectionnée
// Assurez-vous de sécuriser vos requêtes pour éviter les injections SQL

// Par exemple, vous pouvez retourner les installations sous forme de tableau JSON
$installations = ["Installation 1", "Installation 2", "Installation 3"];
echo json_encode($installations);
?>