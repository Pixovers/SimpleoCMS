<?php

//require_once 'connection.php';

//genera nomi casuali
function getRandName()
{
    $names = [
        'ROBERTO', 'GIULIA', 'MARCO', 'GIOVANNI'
    ];

    $lastname = [
        'ROSSI', 'RAMON', 'SMITH', 'PRIVITERA', 'GIRGENTI'
    ];

    $rand1 = mt_rand(0, count($names) - 1);
    $rand2 = mt_rand(0, count($lastname) - 1);
    return $names[$rand1] . ' ' . $lastname[$rand2];
}


//genera email casuali
function getRandEmail($name)
{

    $domains = ['google.com', 'yahoo.com', 'hotmail.it'];
    $rand = mt_rand(0, count($domains) - 1);

    return strtolower(str_replace(' ', '.', $name) . mt_rand(10, 99) . '@' . $domains[$rand]);
}

//genera codice fiscale
function getRandFiscalCode()
{
    $i = 16;
    $res = '';  //risulatato

    while ($i > 0) {
        // .= concatenazione stringa   
        //chr()  Genera una stringa a byte singolo da un numero
        $res .= chr(mt_rand(65, 90));
        $i--;
    }

    return $res;
}

function getRandomAge()
{
    return mt_rand(0, 120);
}

function insertRandUser($totale, mysqli $conn) //inseriamo i dati generati casualmente nel database
{

    while ($totale > 0) { 

        //inseriamo i dati generati casualmente in delle variabili
        $username = getRandName();
        $email = getRandEmail($username);
        $fiscalcode = getRandFiscalCode();
        $age = getRandomAge();


        //query per inserire i valori 
        $sql = 'INSERT INTO users (username,email,fiscalcode,age) VALUES ';
        $sql .= "('$username','$email','$fiscalcode',$age)";
        echo $totale . ' ' . $sql . '<br>';
        $res = $conn->query($sql);

        //se non ci sono errori decrementiamo la variabile totale
        if (!$res) {
            echo $conn->error;
        } else $totale--;
    }
}


insertRandUser(30, $mysqli);
