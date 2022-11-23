<?php
$dictionary = fopen("dictionary.txt", "r");
if (!feof($dictionary)) {
    $alphabet = explode(",", fgets($dictionary));
echo var_dump($alphabet);
    $wordList = array();
    while (!feof($dictionary)) {
        $wordList[] = fgets($dictionary);
    }
echo var_dump($wordList);

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

        function mycrypt($alphabet, $input, $key)
        {
            $result = "";
            $input = str_split($input, 1);
            foreach ($input as $currentChar) {
                $result .= $alphabet[(strpos(implode($alphabet), $currentChar) + intval($key) % count($alphabet))];
            }
            return $result;
        }

        if (isset($_REQUEST)) {
            if (isset($_REQUEST["key"]) && isset($_REQUEST["encInputText"])) {

                echo "Encrypted the following string: <br></br>" . $_REQUEST["encInputText"] . "<br></br>Into:<br></br>" . mycrypt($alphabet, $_REQUEST["encInputText"], $_REQUEST["key"]);
            } elseif (isset($_REQUEST["decInputText"])) {
                $found = false;
                for ($i = 0; $i < count($alphabet); $i++) {
                    $tmpResult = mycrypt($alphabet, $_REQUEST["decInputText"], $i);
                    if (in_array($tmpResult, $wordList)) {
                        $result = $tmpResult;
                        $found = true;
                        break;
                    }
                }
                if ($found) {
                    echo "Decrypted the following string: <br></br>" . $_REQUEST["decInputText"] . "<br></br>Into:<br></br>" . $result;
                } else {
                    echo "Decryption failed";
                }
            }
        }
        ?>
    </div>

</body>

</html>