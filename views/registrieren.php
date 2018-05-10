<?php include "header.php" ?>
<body>
  <h2>Registrieren</h2>
  <div id="register">
  <p>Registieren Sie sich, um Zugriff auf Ihre persönliche Aufgabenliste zu bekommen:</p>
  <p>Hinweise zu Eingabe-Mustern</p>
  <form id="register-form" action="<?php print $this->domain ?>/registrieren/" method="POST">
    <input type="text" placeholder="Login" name="login" value="<?php print htmlspecialchars($this->login) ?>">
      <input type="password" placeholder="Passwort" name="password" value="<?php print htmlspecialchars($this->password) ?>">
      <input type="password" placeholder="Passwort wiederholen" name="passwordrepeat">
      <input type="submit" value="registieren">
  </form>
  <div id="meldung">
      <p>
        <?php print $this->meldung ?>
      </p>
  </div>
</div>
<?php include "footer.php" ?>