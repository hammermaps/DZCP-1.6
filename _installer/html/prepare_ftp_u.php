<table width="100%" cellpadding="2" cellspacing="1">
    <tr>
        <td colspan="4" align="justify" class="head"><br/><b>&raquo; Automatische Rechtevergabe</b></td>
    </tr>
    <tr>
        <td colspan="4" class="info"><b>Hinweis:</b> Als Pfad muss der FTP-Pfad zum Stammverzeichnis eingetragen werden!<br/>
            <b>Beispiel:</b> <i>/htdocs/dzcp</i></td>
    </tr>
    <tr>
        <td height="10"></td>
    </tr>
    <form action="update.php?action=prepare&amp;do=set_chmods" method="POST">
        <input type="hidden" name="check" value="<?php echo $formcheck; ?>">
        <tr>
            <td><b>FTP-Host:</b></td>
            <td><input type="text" name="host" value="<?php if (isset($_POST['host'])) echo htmlspecialchars($_POST['host'], ENT_QUOTES, 'UTF-8'); ?>"></td>
            <td><b>FTP-Pfad:</b></td>
            <td><input type="text" name="pfad" value="<?php if (isset($_POST['pfad'])) echo htmlspecialchars($_POST['pfad'], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
        <tr>
            <td><b>FTP-Benutzername:</b></td>
            <td><input type="text" name="user" value="<?php if (isset($_POST['user'])) echo htmlspecialchars($_POST['user'], ENT_QUOTES, 'UTF-8'); ?>"></td>
            <td><b>FTP-Passwort:</b></td>
            <td><input type="password" name="pwd" value="<?php if (isset($_POST['pwd'])) echo htmlspecialchars($_POST['pwd'], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
        <tr>
            <td colspan="4" align="center"><input style="width:400px;" type="submit"
                                                  value="Automatische Rechtevergabe!"></td>
        </tr>
    </form>
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>

    
      
    