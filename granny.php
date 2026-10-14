<?php

echo "ЧЕГО СКАЗАТЬ-ТО ХОТЕЛ, МИЛОК?!\n";

$pokStreak = 0;

while (true) {
    echo "> ";
    $line = fgets(STDIN);

    if ($line === false) break;

    $line = trim($line);

    $isPok = (mb_strtoupper($line, 'UTF-8') === 'ПОКА!');

    if ($isPok) {
        $pokStreak++;
        if ($pokStreak >= 3) {
            echo "ДО СВИДАНИЯ, МИЛЫЙ!\n";
            break;
        }
    } else {
        $pokStreak = 0;
    }

    $isShout = (mb_substr($line, -1, 1, 'UTF-8') === '!');

    if ($isShout) {
        $year = rand(1930, 1950);
        echo "НЕТ, НИ РАЗУ С {$year} ГОДА!\n";
    } else {
        echo "АСЬ?! ГОВОРИ ГРОМЧЕ, ВНУЧЕК!\n";
    }
}