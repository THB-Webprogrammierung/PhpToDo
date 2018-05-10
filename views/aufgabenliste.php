<?php include "header.php" ?>
  <header><span>Angemeldet als <?php print $this->username ?> (<a href="/index.php?logout=true"><span>logout</span></a>)</span></header>
  <h2>Aufgabenliste</h2>
  <ul id="todolist">
      <?php for($i = 0; $i < $this->anzahlTodos; $i++) : ?>
          <li>
              <a href="?done=<?php print $i ?>" class="done <?php $this->todos->getTodo($i)->getDone() ? print "checked" : print "" ?>"></a>
              <span><?php print $this->todos->getTodo($i)->getText() ?></span>
              <a href="?delete=<?php print $i ?>" class="delete">löschen</a>
          </li>
      <?php endfor; ?>
  </ul>
  <div class="spacer"></div>
  <form id="add-todo" method="post">
    <input type="text" placeholder="Text für neue Aufgabe" name="text">
    <input type="submit" value="hinzufügen">
  </form>
