<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meteo du jour</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php 
session_start();
// Vérifie si une ville est sélectionnée
if (isset($_POST['city'])) {
  $_SESSION['city'] = $_POST['city'];
}

// Vérifie si des jours sont sélectionnés
if (isset($_POST['day'])) {
  $_SESSION['day'] = $_POST['day'];
}

require 'weather.php';

$weather = new Weather(); //Nouvel objet Weather
$condition = $weather->weatherIs(); //Appel de la fonction weatherIs
$temperature = $condition['temperature']; //Recupère la temperature renvoyée par weatherIs
$description = $condition['description']; //Recupère la description renvoyée par weatherIs
$id = $condition['id'];
?>

<!-- Formulaire pour selectionner les villes -->
<form method="post" action="index.php" id="city-form">
    <select name="city" id="city-select">
        <option value=""><?php if (isset($_SESSION['city'])): echo $_SESSION['city']; else: echo 'Choisis ta ville'; endif;?></option>
        <?php if (!isset($_SESSION['city']) || $_SESSION['city'] !== 'Marseille'): echo '<option value="Marseille">Marseille</option>}'; endif; ?>
        <?php if (!isset($_SESSION['city']) || $_SESSION['city'] !== 'Lyon'): echo '<option value="Lyon">Lyon</option>'; endif; ?>
        <?php if (!isset($_SESSION['city']) || $_SESSION['city'] !== 'Montpellier'): echo '<option value="Montpellier">Montpellier</option>'; endif; ?>
        <?php if (!isset($_SESSION['city']) || $_SESSION['city'] !== 'Paris'): echo '<option value="Paris">Paris</option>'; endif; ?>

    </select>
</form>

<script> // Script pour se passer du bouton submit et envoyer directement la selection la ville
    document.getElementById('city-select').addEventListener('change', function() {
        document.getElementById('city-form').submit();
    });
</script>

<section class=affichage>
  <div class="image">
    <?php if (substr ((string)$id, 0, 3) == 800): 
        echo '<img src="/images/sun.png" alt="">';
      elseif(substr ((string)$id, 0, 3) == 801 || substr ((string)$id, 0, 3) == 802): 
        echo '<img src="/images/white.png" alt="">';
      elseif(substr ((string)$id, 0, 3) == 803 || substr ((string)$id, 0, 3) == 804): 
        echo '<img src="/images/grey.png" alt="">';
      elseif(substr ((string)$id, 0, 1) == 6): 
        echo '<img src="/images/snow.png" alt="">';
      elseif(substr ((string)$id, 0, 1) == 5 || substr ((string)$id, 0, 1) == 3): 
        echo '<img src="/images/rain.png" alt="">';
      elseif(substr ((string)$id, 0, 1) == 2): 
        echo '<img src="/images/strike.png" alt="">';
      endif;?>
  </div>

  <div class="description">
      <p class="text_description">À <?=$_SESSION['city']; if($_SESSION['day']>0): echo ' il fera '; else : echo ' il fait '; endif;?></p>
      <p id="temperature"><?= $temperature ?>°C</p>
      <p class="text_description">Le temps <?php if($_SESSION['day']>0): echo ' sera '; else : echo ' est '; endif;?> : </p>
      <p id="description"><?= $description?></p>
  </div>
</section>

<section class="button">
<form method="post" action="index.php">
<button type="submit" name="day" value="0" <?php if ($_SESSION['day']==0) echo 'class="selected"'?>>Aujourd'hui</button>
<button type="submit" name="day" value="1" <?php if ($_SESSION['day']==1) echo 'class="selected"'?>>Demain</button>
<button type="submit" name="day" value="2" <?php if ($_SESSION['day']==2) echo 'class="selected"'?>>Dans deux jours</button>
<button type="submit" name="day" value="3" <?php if ($_SESSION['day']==3) echo 'class="selected"'?>>Dans trois jours</button>
<button type="submit" name="day" value="4" <?php if ($_SESSION['day']==4) echo 'class="selected"'?>>Dans quatre jours</button>
</form>
</section>




</body>
</html>