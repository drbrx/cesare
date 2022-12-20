<?php
$dictionary = fopen("dictionary.txt", "r");
if (!feof($dictionary)) {
    $alphabet = explode(",", fgets($dictionary));
    //echo var_dump($alphabet);
    $wordList = array();
    while (!feof($dictionary)) {
        $wordList[] = trim(fgets($dictionary));
    }
    //echo var_dump($wordList);

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
                <input name="key" type="number" placeholder="Inserire chiave" value="<?php echo isset($_REQUEST) && ((isset($_REQUEST["encInputText"]) && $_REQUEST["encInputText"] != "" && $_REQUEST["key"]) && $_REQUEST["key"] != "") ? $_REQUEST["key"] : "" ?>" />
                <br></br>
                <textarea name="encInputText" placeholder="inserire testo da cifrare"><?php echo isset($_REQUEST) && ((isset($_REQUEST["encInputText"]) && $_REQUEST["encInputText"] != "")) ? $_REQUEST["encInputText"] : "" ?></textarea>
                <br></br>
                <input type="submit" value="Encrypt"></input>
            </form>
        </div>
        <div id="decripter">
            <form action="index.php">
                <input name="key" type="number" placeholder="Inserire chiave (opzionale)" value="<?php echo isset($_REQUEST) && ((isset($_REQUEST["decInputText"]) && $_REQUEST["decInputText"] != "" && $_REQUEST["key"]) && $_REQUEST["key"] != "") ? $_REQUEST["key"] : "" ?>" />
                <br></br>
                <textarea name="decInputText" placeholder="inserire testo da cifrare"><?php echo isset($_REQUEST) && ((isset($_REQUEST["decInputText"]) && $_REQUEST["decInputText"] != "")) ? $_REQUEST["decInputText"] : "" ?></textarea>
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
                //echo (strpos(implode($alphabet), $currentChar) + intval($key)) % count($alphabet) . "/";
                $charPos = (strpos(implode($alphabet), $currentChar) + intval($key)) % count($alphabet);
                if ($charPos < 0) {
                    $charPos = count($alphabet) + $charPos;
                }
                $result .= $alphabet[$charPos];
            }

            return $result;
        }

        if (isset($_REQUEST) && ((isset($_REQUEST["encInputText"]) && $_REQUEST["encInputText"] != "") || (isset($_REQUEST["decInputText"]) && $_REQUEST["decInputText"] != ""))) {
            if (isset($_REQUEST["key"]) && isset($_REQUEST["encInputText"])) {
                echo "Encrypted the following string: <br></br>" . $_REQUEST["encInputText"] . "<br></br>Into:<br></br>" . mycrypt($alphabet, $_REQUEST["encInputText"], $_REQUEST["key"]);
            } elseif (isset($_REQUEST["decInputText"])) {
                if (isset($_REQUEST["key"]) && $_REQUEST["key"] != "") {
                    echo "Decrypted the following string: <br></br>" . $_REQUEST["decInputText"] . "<br></br>Into:<br></br>" . mycrypt($alphabet, $_REQUEST["decInputText"], (-1 * intval($_REQUEST["key"])));
                } else {
                    $scores = array();
                    $length = strlen($_REQUEST["decInputText"]);


                    for ($i = 0; $i < count($alphabet); $i++) {
                        $scores[$i] = 0;
                        $attempt = strtolower(mycrypt($alphabet, $_REQUEST["decInputText"], $i));
                        //echo $i . " " . $attempt . " ";
                        $substring = explode(" ", $attempt);
                        for ($word = 0; $word < count($substring); $word++) {

                            if (in_array($substring[$word], $wordList)) {
                                $scores[$i] += strlen($substring[$word]);
                            }
                        }
                        //echo "<br></br>";
                    }
                    //echo "<br></br>";
                }
                //echo var_dump($scores);
                //echo implode($alphabet);
                //echo max($scores)."/";
                //echo array_search(max($scores), $scores);
                echo "Decrypted the following string: <br></br>" . $_REQUEST["decInputText"] . "<br></br>Into:<br></br>" . mycrypt($alphabet, $_REQUEST["decInputText"], -1 * (count($alphabet) - array_search(max($scores), $scores))) . "<br></br>Using key:<br></br>" . array_search(max($scores), $scores);
                //echo "Decryption failed";


            }
        }
        ?>
    </div>

</body>

</html>