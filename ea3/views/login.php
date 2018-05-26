<?php include "header.php" ?>
<body>
  <h2>Willkommen bei der EA3 Aufgaben-Web-App</h2>
  <div id="login">
    <p>
      Melden Sie sich mit ihren Login-Daten ein oder erstelllen Sie 
      <a href="registrieren/">hier</a> einen neuen Account.
    </p>
    <form id="login-form" action="<?php print $_SERVER['PHP_SELF']; ?>" method="POST">
      <input type="text" placeholder="Login" name="login">
      <input type="password" placeholder="Passwort" name="password">
      <input type="submit" value="anmelden">
    </form>
  </div>
  <div id="meldung">
      <p>
          <?php print $this->meldung ?>
      </p>
  </div>
<?php include "footer.php" ?>