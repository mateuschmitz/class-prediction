<?php

/**
 * @author Mateus Schmitz <mateus@m2sdigital.com>
 *
 * Esta PoC tem como objetivo verificar a viabilidade de cálculo de dias letivos de um trimestre de escolas
 * públicas. A contagem e divisão ocorre por meio de dados previamente informados tais como: início e fim do trimestre
 * letivo, matérias, dias das matérias e quantidade de períodos por dia.
 *
 */

setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('America/Sao_Paulo');

$iniDate = new DateTime('2017-03-06');
$endDate = new DateTime('2017-06-03');

$subjectConfigs = array(
    "PORTUGUÊS"        => array(2 => 2, 3 => 1, 4 => 2),
    "LÍNGUA INGLESA"   => array(4 => 2),
    "MATEMÁTICA"       => array(1 => 1, 2 => 2, 5 => 2),
    "CIÊNCIAS"         => array(3 => 2, 4 => 1),
    "GEOGRAFIA"        => array(1 => 2),
    "HISTÓRIA"         => array(2 => 1, 5 => 2),
    "ARTES"            => array(3 => 2),
    "EDUCAÇÃO FÍSICA"  => array(1 => 2),
    "ENSINO RELIGIOSO" => array(5 => 1),
);

echo "\n1º Trimestre: {$iniDate->format('d/m/Y')} a {$endDate->format('d/m/Y')}\n\n";

$result = array();
foreach ($subjectConfigs as $subject => $lessons) {

    $date   = clone $iniDate;
    $result[$subject] = array();

    while ($date <= $endDate) {
        if (isset($lessons[$date->format('N')])) {
            for ($i = 1; $i <= $lessons[$date->format('N')]; $i++) {
                $result[$subject][strftime("%B", $date->getTimestamp())][] = $date->format('d');
            }
        }
        $date->add(new DateInterval('P1D'));
    }
}

foreach ($result as $subject => $months) {
    echo "\n{$subject}\n";
    $total = 0;
    foreach ($months as $month => $days) {
        echo  utf8_encode($month) . " : " . implode(', ', $days) . "\n";
        $total += count($days);
    }
    echo "TOTAL DE AULAS: {$total}\n";
}