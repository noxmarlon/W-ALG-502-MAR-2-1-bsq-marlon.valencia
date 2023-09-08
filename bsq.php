<?php

const EMPTY_VALUE_BOARD = '.';
const SQUARE_BOARD = 'x';

function CrearTablero($file): array {
    $board = [];

    $handle = fopen($file, 'rb');
    if ($handle === null) {
        echo "un problème lors de l'ouverture du fichier";
        exit;
    }
    // obtener la primera línea del archivo
    $numLines = (int)fgets($handle);

    while ($line = fgets($handle)) {
        // eliminar \n y convertir a matriz y almacenar nueva línea de tablero
        $board[] = str_split(trim($line));
    }

    if ($numLines !== count($board)) {
        echo "Nombre de ligne invalide";
        exit;
    }

    return $board;
}

function ConvertirMatrix($board) {
   // llena el tablero por 1 y 0
    foreach ($board as $line => $items){
        foreach ($items as $col => $value) {
            if ($value === EMPTY_VALUE_BOARD) {
                $board[$line][$col] = 1;
            } else {
                $board[$line][$col] = 0;
            }
        }
    }

    return $board;
}

function EncontarCuadradoLargo($board) {
    $matrix = $board;
    $maxSizeSquare = 0;
    $x = null;
    $y = null;

    for ($line = 0; $line < count($board); $line++) {
        for ($col = 0; $col < count($board[0]); $col++) {
            if ($line === 0 || $col === 0) {} // hacer nada
            else if ($matrix[$line][$col] > 0) {
                $matrix[$line][$col] = 1 + min($matrix[$line][$col-1], $matrix[$line-1][$col], $matrix[$line-1][$col-1]);
            }

            if ($matrix[$line][$col] > $maxSizeSquare) {
                $maxSizeSquare = $matrix[$line][$col];

                // obtener la coord del cuadrado más grande
                $x = $col;
                $y = $line;
            }
        }
    }

    return [
        'size' => $maxSizeSquare,
        'x' => $x, // horizontal
        'y' => $y, // vertical
    ];
}

function LlenarCuadraLargo(array $board, array $largestSquare) {
    // comienza en la línea del cuadrado final
    for($i = 0, $line = $largestSquare['y']; $i  < $largestSquare['size']; $i++, $line--) {
       // comienza en la llamada del cuadrado final
        for($j = 0, $col = $largestSquare['x']; $j < $largestSquare['size']; $j++, $col--) {
            // llena el cuadrado por x
            $board[$line][$col]=SQUARE_BOARD;
        }
    }

    return $board;
}

function printResult($board) {
    foreach($board as $line) {
        foreach ($line as $col) {
            echo $col;
        }
        echo PHP_EOL;
    }
}

function bsq($file) {
    $board = CrearTablero($file);
    $matrix = ConvertirMatrix($board);
    $largestSquare = EncontarCuadradoLargo($matrix);
    $newBoard = LlenarCuadraLargo($board, $largestSquare);
    printResult($newBoard);
}

$file = $argv[1] ?? null;
if (file_exists($file)) {
    bsq($file);
} else {
    echo 'argument invalide';
}