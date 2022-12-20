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
                <input name="key" type="number" placeholder="Inserire chiave" />
                <br></br>
                <textarea name="encInputText" placeholder="inserire testo da cifrare"></textarea>
                <br></br>
                <input type="submit" value="Encrypt"></input>
            </form>
        </div>
        <div id="decripter">
            <form action="index.php">
                <input name="key" type="number" placeholder="Inserire chiave (opzionale)" />
                <br></br>
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
                $result .= $alphabet[(strpos(implode($alphabet), $currentChar) + intval($key)) % count($alphabet)];
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

                        for ($start = 0; $start < $length; $start++) {
                            for ($end = $start + 1; $end <= $length; $end++) {
                                $substring = substr($_REQUEST["decInputText"], $start, $end - $start);
                                echo strtolower(mycrypt($alphabet, $substring, $i)) . "\n";
                                if (in_array(strtolower(mycrypt($alphabet, $substring, $i)), $wordList)) {
                                    $scores[$i] += $end - $start;
                                }
                            }
                        }
                        echo "<br></br>";
                    }

                    echo "Decrypted the following string: <br></br>" . $_REQUEST["decInputText"] . "<br></br>Into:<br></br>" . mycrypt($alphabet, $_REQUEST["decInputText"], -1 * $scores[array_search(max($scores), $scores)]);
                    //echo "Decryption failed";

                }
            }
        }
        ?>
    </div>

</body>

</html>