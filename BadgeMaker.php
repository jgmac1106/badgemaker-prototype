<?php

class BadgeMaker {
  var $host;
  var $username;
  var $password;
  var $table;

  public function display_public() {
    
  }

  public function display_admin() {
    
  }

  public function write() {
    
  }

  public function connect() {
    
  }

  private function buildDB() {
    
  }
}

?>

private function buildDB() {
    $sql = <<<MySQL_QUERY
        CREATE TABLE IF NOT EXISTS testDB (
            name VARCHAR(150),
            description TEXT,
            criteria TEXT,
            criteriaDescription Text
            published VARCHAR(100)
    )
    MySQL_QUERY;

    return mysql_query($sql);
}

public function connect() {
    mysql_connect($this->host,$this->username,$this->password) or die("Check your username or password used to connect to the database. " . mysql_error());
    mysql_select_db($this->table) or die("Could not select database. " . mysql_error());

    return $this->buildDB();
}

public function display_badge() {
    return <<<BADGE_FORM

    <form action="{$_SERVER['PHP_SELF']}" method="post">
      <form>
        <input id="name" type="text" placeholder="What is your badge called?"> <br>
        <input id="description" type="text" placeholder="What is your badge about?"><br>
        <input id="criteria" type="text"  placeholder="Insert a link to the assignment or criteria"><br>
        <!--Long term I would like to have a parser in the app so when people link to the assignment page the h-card of the teacher and the criteria are parsed-->
        <input id="criteriaDescription" type="textarea" maxlength="280" placeholder="Breifly describe the criteria someone must meet to earn this badge."><br>
        <button type="submit">post</button>
</form>
    </form>

BADGE_FORM;
}

public function write($p) {
    if ( $p['name'] )
      $name = mysql_real_escape_string($p['name']);
    if ( $p['description'])
      $description = mysql_real_escape_string($p['description']);
    if ( $p['criteria'])
      $criteria = mysql_real_escape_string($p['criteria']);
    if ( $p['criteriaDescription'])
      $criteriaDescription = mysql_real_escape_string($p['criteriaDescription']);    
    if ( $name && description && $criteria && CriteriaDescription ) {
      $published = time();
      $sql = "INSERT INTO testDB VALUES('$name','$description', 'criteria', 'criteriaDescription', $published')";
      return mysql_query($sql);
    } else {
      return false;
    }
}

public function display_public() {
    $q = "SELECT * FROM testDB ORDER BY created DESC LIMIT 1";
    $r = mysql_query($q);

    if ( $r !== false && mysql_num_rows($r) > 0 ) {
      while ( $a = mysql_fetch_assoc($r) ) {
        $name = stripslashes($a['name']);
        $description = stripslashes($a['desctiption']);
        $criteria = stripslashes($a['criteria']);
        $criteriaDescription = stripslashes($a['criteriaDescription']);

        $entry_display .= <<<ENTRY_DISPLAY

   <title>$Name</title>
<body>
  <div class="h-entry">
    <div class="u-author h-card">
      <img src="#">
      <a href="#">Insert Name</a>
    </div>
    <p>in reply to: <a class="u-in-reply-to" href="https://mrkean.com/uncategorized/daily-ponderance-8-2/">Daily Ponderance</a></p>
    <p class="summary">
      <img class="u-photo" src="#">
      You earned the <span class="p-name">$Name</span> in $class
    </p>
    <p class="e-content">To earn the <a href="$criteria">$name</a>: badge you had $CriteriaDescription</p>
    <p>
      <a href="https://edu-522.glitch.me/" class="u-url">
        <time class="dt-published" datetime="$published">$published/time>
      </a>
    </p>
  </div>
</body>

ENTRY_DISPLAY;
      }
    } else {
      $entry_display = <<<ENTRY_DISPLAY

    <h2>This Page Is Under Construction</h2>
    <p>
      No entries have been made on this page. 
      Please check back soon, or click the
      link below to add an entry!
    </p>

ENTRY_DISPLAY;
    }
    $entry_display .= <<<ADMIN_OPTION

    <p class="admin_link">
      <a href="{$_SERVER['PHP_SELF']}?admin=1">Add a New Badge</a>
    </p>

ADMIN_OPTION;

    return $entry_display;
  }
