<?php
/**Einsendeaufgabe Wunschzettel
*
*@author Sebastian Nadler
*@Version 1.0
*
*/

//eröffnen einer Session um die Eingaben über den reload der Seite zu speichern
session_start();

//Deklaration von Zwischenvariablen um sie auf den zweiten Reload zu uebergeben
$wunsch1 = $_SESSION["wunsch1"];
$wunsch2 = $_SESSION["wunsch2"];
$wunsch3 = $_SESSION["wunsch3"];


//Definition der Muster zu reglulaeren Ausdruecken
$keinesonderzeichen = "/^[a-zA-Z.,-_0-9]+$/";
$musterplzundort = "/^\d{5}\s[a-zA-Z]+$/";
$mustertelnummer = "/\+?([0-9]{2})-?([0-9]{3})-?([0-9]{6,7})/";
$mustername = '/^[a-zA-Z\s]+$/';


//regex-check des Namens

if (!preg_match($mustername, $_POST['name']) && isset($_POST['name']))
{
    $_SESSION['ErrorName'] = "Bitte Vor- und Nachnamen eingeben";

}else {
    $_SESSION['name'] = $_POST['name'];
}


//regex Check des Ortes mit PLZ
if (!preg_match($musterplzundort, $_POST['ort']) && isset($_POST['name']))
{
    $_SESSION['ErrorOrt'] = "Bitte PLZ und Ort eingeben";  
}else {
    $_SESSION['ort'] = $_POST['ort'];
}

//regex Check der Telefonnummer
if (!preg_match($mustertelnummer, $_POST['telnummer']) && isset($_POST['name']))
{
    $_SESSION['ErrorTel'] = "Telefonnummer eingeben"; 
}else {
    $_SESSION['telnummer'] = $_POST['telnummer'];
}

//regex Check Wunsch1
if (preg_match($keinesonderzeichen, $_POST['wunsch1']))
{
    $_SESSION['wunsch1'] = $_POST['wunsch1'];
}

//regex Check Wunsch2
if (preg_match($keinesonderzeichen, $_POST['wunsch2']))
{
    $_SESSION['wunsch2'] = $_POST['wunsch2'];
}

//regex Check Wunsch3
if (preg_match($keinesonderzeichen, $_POST['wunsch3']))
{
    $_SESSION['wunsch3'] = $_POST['wunsch3'];
}


//wenn Kontaktdaten noch nicht korrekt eingetragen wurden
if(!checkContactData())
    
    {
        //wenn kein einziger Wunsch eingetragen wurde
        if(!checkWishesSet())
        {
            writeHeader();
            writeFirstPage();
            writeStartOfForm("wuensche");
            if( isset($_SESSION['Error']) )
            {
                echo $_SESSION['Error'];
                unset($_SESSION['Error']);
            }
            writeWishesForm();
            writeFooter();
            
            
        }else
        {
        writeHeader();
        writeSecondPage();
        writeStartOfForm("lieferadresse");
        writeNameForm();
        writeOrtForm();
        writeTelForm();
        writeFooter(); 
        }
    }
else
    {
        writeHeader();
        writeThirdPage();
        writeFooter(); 
        $_SESSION = array();
    }





//HTML-Header bis Oeffnung des Body-Tags
function writeHeader()
    {
        echo "<!DOCTYPE html>
        <html lang=\"de\">
        <head><title>Formular</title>
        </head>
        <body>";
    }

//Body der ersten HTML-Seite
function writeFirstPage()
    {
        echo "<h1>Mein Wunschzettel</h1>
        <p>Bitte 3 Wünsche eingeben!</p>";
    }

//Einleitung des Form HTML
function writeStartOfForm($sitestatus)
    {
      echo "<form method=\"POST\" action=\"index.php?status=$sitestatus\">
        <table>";
    }

//Wunsch Form HTML
function writeWishesForm()
    {
        echo "<tr>
              <th><label for=\"wunsch1\">Wunsch 1</label></th>
              <td><input type=\"text\" name=\"wunsch1\" id=\"wunsch1\"></td>
            </tr>
            <tr>
              <th><label for= \"wunsch2\">Wunsch 2</label></th>
              <td><input type=\"text\" name=\"wunsch2\" id=\"wunsch2\"></td>
            </tr>
            <tr>
              <th><label for=\"wunsch3\">Wunsch 3</label></th>
              <td><input type=\"text\" name=\"wunsch3\" id=\"wunsch3\"></td>
            </tr>
          </table>";
    }

//Body der zweiten HTML-Seite
function writeSecondPage()
    {
     echo  "<h1>Lieferangaben</h1>

    <ol>
      <li>",$_SESSION['wunsch1'],"</li>
      <li>",$_SESSION['wunsch2'],"</li>
      <li>",$_SESSION['wunsch3'],"</li>
    </ol>

    <p>Lieferadresse</p>";
        

        

    }

//Funktion zum schreiben des Namens in HTML
function writeNameForm()
    {
        echo "<tr>
        <th><label for=\"name\">Vor- und Nachname</label></th>
        <td><input type=\"text\" name=\"name\" id=\"name\">",$_SESSION['ErrorName'],"</td>
        </tr>";
    }

//Funktion zum schreiben des Ortes in HTML
function writeOrtForm()
    {
        echo "<tr>
        <th><label for=\"ort\">PLZ, Ort</label></th>
        <td><input type=\"text\" name=\"ort\" id=\"ort\">",$_SESSION['ErrorOrt'],"</td>
      </tr>";
    }

//Funktion zum schreiben der Telefonnummerneingabe in HTML
function writeTelForm()
    {
        echo "<tr>
        <th><label for=\"telnummer\">Telefonnummer</label></th>
        <td><input type=\"text\" name=\"telnummer\" id=\"telnummer\">",$_SESSION['ErrorTel'],"</td>
        </tr>  
        </table>";
    }


//Body der dritten HTML-Seite
function writeThirdPage()
    {
    echo "  <h1>Zusammenfassung</h1>

    <p>Wünsche</p>
    <ol>
      <li>", $_SESSION['wunsch1'],"</li>
      <li>", $_SESSION['wunsch2'],"</li>
      <li>", $_SESSION['wunsch3'],"</li>
    </ol>

    <p>Lieferadresse</p>

    <ol>
      <li>", $_SESSION['name'],"</li>
      <li>", $_SESSION['ort'],"</li>
      <li>", $_SESSION['telnummer'],"</li>
    </ol>";
    }



//Schliessung des Body-Tags und Footer
function writeFooter()
    {
       echo "<input type=\"submit\" value=\"Abschicken\">
       <input type=\"reset\" value=\"Abbrechen\">
        </form>    
       </body></html>";
    }


//pruefe ob mind. ein Wunsch korrekt gesetzt wurde
function checkWishesSet()
    {
        if(($_SERVER["REQUEST_METHOD"] == "POST") && ($_SESSION['wunsch1'] !="" ||  $_SESSION['wunsch2'] !="" ||  $_SESSION['wunsch3'] !=""))
        {
            return true;
        }else{
            $_SESSION['Error'] = "Bitte mindestens einen Wunsch ohne Sonderzeichen eingeben";
        }
    }

//pruefe ob die Kontaktdaten korrekt gesetzt wurden
function checkContactData()
    {
            if ( !empty($_SESSION['name']) && !empty($_SESSION['telnummer']) && !empty($_SESSION['ort']))
            {
                return true;
            }
    }

 ?>