<?php

error_reporting(0);
$data = array();


if ($_POST) {

    if ($_POST['words-length']) {

        $data = array(
            'name' => $_POST['topic'],
            'words' => array()
        );



        for ($i = 1; $i <= $_POST['words-length']; $i++) {

            if (!empty($_POST['desc-' . $i . '-1'])) {

                if (!empty($_POST['desc-' . $i . '-2'])) {

                    if (!empty($_POST['desc-' . $i . '-3'])) {

                        // üçü birlikte burada olacak
                        array_push($data['words'], array(
                            'word' => $_POST['word-' . $i],
                            'meanings' => array(
                                $_POST['desc-' . $i . '-1'],
                                $_POST['desc-' . $i . '-2'],
                                $_POST['desc-' . $i . '-3'],
                            ),
                        ));
                    } else {
                        if (!empty($_POST['desc-' . $i . '-1'])) {
                            if (!empty($_POST['desc-' . $i . '-2'])) {


                                // 2si birlikte burada olacak
                                array_push($data['words'], array(
                                    'word' => $_POST['word-' . $i],
                                    'meanings' => array(
                                        $_POST['desc-' . $i . '-1'],
                                        $_POST['desc-' . $i . '-2'],
                                    ),
                                ));
                            }
                        }
                    }
                } else {
                    if (!empty($_POST['desc-' . $i . '-1'])) {

                        // sadece  2 yok
                        array_push($data['words'], array(
                            'word' => $_POST['word-' . $i],
                            'meanings' => array(
                                $_POST['desc-' . $i . '-1'],
                            ),
                        ));
                    }
                }
            }
        }

        print_r(json_encode($data));
    }


    if ($_POST['getlastdata']) {
        if (isset($_COOKIE['last_data'])) {
            if ($_COOKIE['last_data'] == "[]") {
                echo 'error: bos';
            } else {
                echo $_COOKIE['last_data'];
            }
        } else {
            echo '{error: true}';
        }
    }


    setcookie("last_data", json_encode($data));
    // print_r(json_encode($data));
}
