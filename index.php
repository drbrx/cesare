<?php
$dictionary = fopen("dictionary.txt", "r");
if (!feof($dictionary)) {
    $alphabet = explode(",", fgets($dictionary));

    $wordList = array();
    while (!feof($dictionary)) {
        $wordList[] = fgets($dictionary);
    }
}
fclose($dictionary);
?>

<html>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="inputs">
        <div id="encrypter">
            <form action="index.php">
                <input name="key" type="number" placeholder="Inserire chiave" />
                <br></br>
                <textarea name="encInputText" placeholder="inserire testo da cifrare"></textarea>
                <br></br>
                <input type="submit" value="Encrypt"></input>
            </form>
        </div>
        <div id="decripter">
            <form action="index.php">
                <textarea name="decInputText" placeholder="inserire testo da cifrare"></textarea>
                <br></br>
                <input type="submit" value="Decrypt"></input>
            </form>
        </div>
    </div>
    <div style="clear: both"></div>
    <div id="results">
        <?php
        $result = "";
        if (isset($_REQUEST)) {
            if (isset($_REQUEST["key"]) && isset($_REQUEST["encInputText"])) {
                $input = str_split($_REQUEST["encInputText"], 1);
                foreach($input as $currentChar){
                    $result .= $alphabet[(strpos(implode($alphabet), $currentChar) + intval($_REQUEST["key"])) % count($alphabet)];
                }
                echo "Encrypted the following string: <br></br>" . $_REQUEST["encInputText"] . "<br></br>Into:<br></br>" . $result;
            } elseif (isset($_REQUEST["decInputText"])) {
                echo "Decrypted the following string: <br></br>" . $_REQUEST["decInputText"] . "<br></br>Into:<br></br>" . $result;
            }
        }
        ?>
    </div>

</body>

</html>