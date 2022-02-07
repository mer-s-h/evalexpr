<?php

// array filter => array value

function eval_expr($expression)
{
    $nbr = "";
    $tab = [];
    $open = 0;
    $close = 0;
    $par = 0;

    // echo $expression;

    //          *------------------------------------------------------------------------------------*
    //          |repere les chiffre et met chaque nombre et chaque operateur dans une case du tableau|
    //          *------------------------------------------------------------------------------------*

    $expr = str_split($expression);

    $expr = array_values(array_filter($expr, function ($element) {
        return strlen(trim($element));
    }));


    for ($i = 0; $i < count($expr); $i++) {

        if ($expr[$i] !== "+" && $expr[$i] !== "-" && $expr[$i] !== "/" && $expr[$i] !== "*" && $expr[$i] !== "%" && $expr[$i] !== "(" && $expr[$i] !== ")") {
            $expr[$i] = trim($expr[$i]);
            $nbr = $nbr . $expr[$i];
            if ($i == count($expr) - 1) {
                array_push($tab, $nbr);
            }
        } elseif ($expr[$i] == "-") {
            if ($expr[$i - 1] == "+" or $expr[$i - 1] == "-" or $expr[$i - 1] == "/" or $expr[$i - 1] == "*" or $expr[$i - 1] == "%" or $expr[$i - 1] == "(") {
                $expr[$i] = trim($expr[$i]);
                $nbr = $nbr . $expr[$i];
            } else {
                array_push($tab, $nbr, $expr[$i]);
                $nbr = "";
            }
        } else {
            array_push($tab, $nbr, $expr[$i]);
            $nbr = "";
        }
    }

    $tab = array_values(array_filter($tab, function ($element) {
        return strlen(trim($element));
    }));

    // var_dump($tab);



    //          *------------------------------*
    //          |partie qui gere les parenthese|
    //          *------------------------------*


    for ($i = 0; $i < count($tab); $i++) {
        $close++;
        if ($tab[$i] == "(") {
            $open = $close;
        }
        if ($tab[$i] == ")") {
            // $par++;
            
            $close = 0;
            $tab[$i] = " ";

            if ($tab[$i-1] == "(") {
                $tab[$i-1]=" ";
            }

            for ($j = $open ; $j < $i; $j++) {
                switch ($tab[$j]) {
                    case '*':
                        $tab[$j + 1] = $tab[$j - 1] * $tab[$j + 1];
                        $tab[$j - 1] = " ";
                        $tab[$j] = " ";
                        if ($tab[$j + 1] == 0) {
                            $tab[$j + 1] = "0";
                        }
                        $result = $tab[$j + 1];
                        break;
                    case '/':
                        $tab[$j + 1] = $tab[$j - 1] / $tab[$j + 1];
                        $tab[$j - 1] = " ";
                        $tab[$j] = " ";
                        if ($tab[$j + 1] == 0) {
                            $tab[$j + 1] = "0";
                        }
                        $result = $tab[$j + 1];
                        break;
                    case '%':
                        $tab[$j + 1] = $tab[$j - 1] % $tab[$j + 1];
                        $tab[$j - 1] = " ";
                        $tab[$j] = " ";
                        if ($tab[$j + 1] == 0) {
                            $tab[$j + 1] = "0";
                        }
                        $result = $tab[$j + 1];
                        break;
                    case '(':
                        $tab[$j] = " ";
                        // unset($tab[$j]);
                        break;
                }
            }

            for ($j = $open; $j < $i; $j++) {
                switch ($tab[$j]) {
                    case '+':
                        $tab[$j + 1] = $tab[$j - 1] + $tab[$j + 1];
                        $tab[$j - 1] = " ";
                        $tab[$j] = " ";
                        if ($tab[$j + 1] == 0) {
                            $tab[$j + 1] = "0";
                        }
                        $result = $tab[$j + 1];
                        break;
                    case '-':
                        $tab[$j + 1] = $tab[$j - 1] - $tab[$j + 1];
                        $tab[$j - 1] = " ";
                        $tab[$j] = " ";
                        if ($tab[$j + 1] == 0) {
                            $tab[$j + 1] = "0";
                        }
                        $result = $tab[$j + 1];
                        break;
                    case '(':
                        $tab[$j] = " ";
                        // unset($tab[$j]);
                        break;
                }
                // var_dump($tab);
            }
            $tab = array_values(array_filter($tab, function ($element) {
                return strlen(trim($element));
            }));

            $i = 0;
            // $close = 0;
        }
    }
    

    foreach ($tab as $key => &$value) {
        if ($value == "(") {
            $value = " ";
        }
    }

    $tab = array_values(array_filter($tab, function ($element) {
        return strlen(trim($element));
    }));

    // var_dump($tab); 
    // echo $result.PHP_EOL;

    // var_dump($expr);




    //          *------------------------------------------------------------------------*
    //          |calcule les nombre en fonction des operateur pour les mult div et modulo|
    //          *------------------------------------------------------------------------*


    for ($j = 0; $j < count($tab); $j++) {

        switch ($tab[$j]) {
            case '*':
                $tab[$j + 1] = $tab[$j - 1] * $tab[$j + 1];
                $tab[$j - 1] = " ";
                $tab[$j] = " ";
                if ($tab[$j + 1] == 0) {
                    $tab[$j + 1] = "0";
                }
                $result = $tab[$j + 1];
                break;
            case '/':
                $tab[$j + 1] = $tab[$j - 1] / $tab[$j + 1];
                $tab[$j - 1] = " ";
                $tab[$j] = " ";
                $result = $tab[$j + 1];
                if ($tab[$j + 1] == 0) {
                    $tab[$j + 1] = "0";
                }
                break;
            case '%':
                $tab[$j + 1] = $tab[$j - 1] % $tab[$j + 1];
                $tab[$j - 1] = " ";
                $tab[$j] = " ";
                $result = $tab[$j + 1];
                if ($tab[$j + 1] == 0) {
                    $tab[$j + 1] = "0";
                }
                break;
        }
    }

    //          *----------------------------------------------------------------*
    //          |reorganise le tableau et calcule les additions et soustractions |
    //          *----------------------------------------------------------------*

    $tab = array_values(array_filter($tab, function ($element) {
        return strlen(trim($element));
    }));

    for ($l = 0; $l < count($tab); $l++) {
        switch ($tab[$l]) {
            case '+':
                $tab[$l + 1] = $tab[$l - 1] + $tab[$l + 1];
                if ($tab[$l + 1] == 0) {
                    $tab[$l + 1] = "0";
                }
                $result = $tab[$l + 1];
                break;
            case '-':
                $tab[$l + 1] = $tab[$l - 1] - $tab[$l + 1];
                if ($tab[$l + 1] == 0) {
                    $tab[$l + 1] = "0";
                }
                $result = $tab[$l + 1];
                break;
        }
    }

    echo $result . PHP_EOL;
    // var_dump($tab);
}

// eval_expr("1111111111111111111111111111111111111111111111111111111111111*111111111111111111111111111111111111111111111111111111111111+111111111111111111111111111111111111111111111111-111111111111111111111111111111111111111");

// eval_expr("3+4-(((4/2)+(-3))*1)");

// eval_expr("1+52 +-2+7--6.5");

// eval_expr("21 + 1 - -4 % -3");
// eval_expr("2 - 1");
// eval_expr("2 * 2");
// eval_expr("2 / 2");
// eval_expr("5 % 2");


// //ADVANCED 1

// eval_expr("2 + 2 + 1"); 
// eval_expr("2 - 2 + 1");
// eval_expr("2 * 2 * 1");
// eval_expr("2 * 2 / 2"); // 2
// eval_expr("2 * 2 % 3"); // 1

// //ADVANCED 2

// eval_expr("2 + 2 * 1");
// eval_expr("2 - 2 + 1");
// eval_expr("2 + 2 * 1");
// eval_expr("2 - 2 / 2"); // 1
// eval_expr("2 % 2 + 3");  // 3

// // ADVANCED 3

// eval_expr("(2 + 2) * 1");
// eval_expr("2 - (2 + 1)");
// eval_expr("2 + 2 * 1");
// eval_expr("2 - 2 / 2"); // 1
// eval_expr("(2 % 2) + (-3) * (2 -6)"); //12
eval_expr("((10 * 2) + (-3)) * (2 -6)"); // -68
