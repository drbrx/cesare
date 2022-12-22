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
?>

<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Cesare</title>
</head>

<body style="background-color: #040033;">
    <div class="container text-center">
        <div id="inputs" class="row">
            <div id="encrypter" class="col g-5" style="width: 100%;">
                <form action="index.php">
                    <input class="input-group-text" style="width: 100%;" name="key" type="number" placeholder="Inserire chiave" value="<?php echo isset($_REQUEST) && ((isset($_REQUEST["encInputText"]) && $_REQUEST["encInputText"] != "" && $_REQUEST["key"]) && $_REQUEST["key"] != "") ? $_REQUEST["key"] : "" ?>" />
                    <br></br>
                    <textarea class="input-group-text" style="width: 100%;" name="encInputText" placeholder="inserire testo da cifrare"><?php echo isset($_REQUEST) && ((isset($_REQUEST["encInputText"]) && $_REQUEST["encInputText"] != "")) ? $_REQUEST["encInputText"] : "" ?></textarea>
                    <br></br>
                    <input class="btn btn-danger mb-3" type="submit" value="Encrypt"></input>
                </form>
            </div>
            <div id="decripter" class="col g-5" style="width: 100%;">
                <form action="index.php">
                    <input class="input-group-text" style="width: 100%;" name="key" type="number" placeholder="Inserire chiave (opzionale)" value="<?php echo isset($_REQUEST) && ((isset($_REQUEST["decInputText"]) && $_REQUEST["decInputText"] != "" && $_REQUEST["key"]) && $_REQUEST["key"] != "") ? $_REQUEST["key"] : "" ?>" />
                    <br></br>
                    <textarea class="input-group-text" style="width: 100%;" name="decInputText" placeholder="inserire testo da cifrare"><?php echo isset($_REQUEST) && ((isset($_REQUEST["decInputText"]) && $_REQUEST["decInputText"] != "")) ? $_REQUEST["decInputText"] : "" ?></textarea>
                    <br></br>
                    <input class="btn btn-danger mb-3" type="submit" value="Decrypt"></input>
                </form>
            </div>
        </div>
        <div id="results" class="alert alert-danger row" style="width: 60%; margin: auto; justify-content: center;">
            <?php

            if (isset($_REQUEST) && ((isset($_REQUEST["encInputText"]) && $_REQUEST["encInputText"] != "") || (isset($_REQUEST["decInputText"]) && $_REQUEST["decInputText"] != ""))) {

                if (isset($_REQUEST["key"]) && isset($_REQUEST["encInputText"])) {
                    echo "Encrypted the following string: <br></br>" . $_REQUEST["encInputText"] . "<br></br>Into:<br></br>" . mycrypt($alphabet, $_REQUEST["encInputText"], $_REQUEST["key"]);
                } elseif (isset($_REQUEST["decInputText"])) {

                    if (isset($_REQUEST["key"]) && $_REQUEST["key"] != "") {
                        echo "Decrypted the following string: <br></br>" . $_REQUEST["decInputText"] . "<br></br>Into:<br></br>" . mycrypt($alphabet, $_REQUEST["decInputText"], (-1 * intval($_REQUEST["key"])));
                    } else {

                        $scores = array();
                        $attempts = array();
                        $length = strlen($_REQUEST["decInputText"]);


                        for ($i = 0; $i < count($alphabet); $i++) {
                            $scores[$i] = 0;
                            $attempts[$i] = mycrypt($alphabet, $_REQUEST["decInputText"], $i);
                            $attempt = strtolower($attempts[$i]);
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
                        //echo var_dump($scores);
                        //echo implode($alphabet);
                        //echo max($scores)."/";
                        //echo array_search(max($scores), $scores);
                        $plausibleSolution = mycrypt($alphabet, $_REQUEST["decInputText"], -1 * (count($alphabet) - array_search(max($scores), $scores)));
                        echo "Decrypted the following string: <br></br>" . $_REQUEST["decInputText"] . "<br></br>Into:<br></br>" . $plausibleSolution . "<br></br>";
            ?>
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                        Show all possible results
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body"><?php
                                                                foreach ($attempts as $attamptedString)
                                                                    echo "<div " . ($attamptedString == $plausibleSolution ? "style=\"color: #dc3545; font-weight: bold;\"" : "") . ">" . $attamptedString . "</div>"
                                                                ?></div>
                                </div>
                            </div>
                        </div>
            <?php                        //echo "Decryption failed";
                    }
                }
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>

<script>
    function showAttempts() {

        document.getElementById("results").innerHTML += "<div>Attempted decryptions:</div>"


        attemptList = <?php echo json_encode($attempts); ?>;
        attemptList.forEach(function(attempt) {
            console.log(attempt);
            document.getElementById("results").innerHTML += "<div>" + attempt + "</div>"
        })

        document.getElementById("showAll").style.visibility = 'hidden';
    }
</script>