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

// 1 - segunda
// 2 - terça
// 3 - quarta
// 4 - quinta
// 5 - sexta
// 6 - sábado
// 7 - domingo

$configs = array(
    61 => array(
        "PORTUGUÊS"        => array(2 => 2, 3 => 1, 4 => 2),
        "LÍNGUA INGLESA"   => array(4 => 2),
        "MATEMÁTICA"       => array(1 => 1, 2 => 2, 5 => 2),
        "CIÊNCIAS"         => array(3 => 2, 4 => 1),
        "GEOGRAFIA"        => array(1 => 2),
        "HISTÓRIA"         => array(2 => 1, 5 => 2),
        "ARTES"            => array(3 => 2),
        "EDUCAÇÃO FÍSICA"  => array(1 => 2),
        "ENSINO RELIGIOSO" => array(5 => 1),
    ),
    71 => array(
        "PORTUGUÊS"        => array(3 => 2, 4 => 2, 5 => 1),
        "LÍNGUA INGLESA"   => array(4 => 2),
        "MATEMÁTICA"       => array(2 => 2, 4 => 1, 5 => 2),
        "CIÊNCIAS"         => array(2 => 1, 3 => 2),
        "GEOGRAFIA"        => array(1 => 1, 3 => 1),
        "HISTÓRIA"         => array(2 => 1, 5 => 2),
        "ARTES"            => array(1 => 2),
        "EDUCAÇÃO FÍSICA"  => array(1 => 2),
        "ENSINO RELIGIOSO" => array(2 => 1),
    ),
    81 => array(
        "PORTUGUÊS"        => array(2 => 2, 4 => 1, 5 => 2),
        "LÍNGUA INGLESA"   => array(2 => 2),
        "MATEMÁTICA"       => array(1 => 2, 2 => 1, 4 => 2),
        "CIÊNCIAS"         => array(4 => 1, 5 => 2),
        "GEOGRAFIA"        => array(1 => 2),
        "HISTÓRIA"         => array(3 => 2, 5 => 1),
        "ARTES"            => array(1 => 1, 5 => 1),
        "EDUCAÇÃO FÍSICA"  => array(3 => 2),
        "ENSINO RELIGIOSO" => array(4 => 1),
    ),
    91 => array(
        "PORTUGUÊS"        => array(2 => 1, 3 => 2, 5 => 2),
        "LÍNGUA INGLESA"   => array(2 => 1, 4 => 1),
        "MATEMÁTICA"       => array(1 => 2, 4 => 2, 5 => 1),
        "CIÊNCIAS"         => array(4 => 1, 5 => 2),
        "GEOGRAFIA"        => array(2 => 1, 3 => 1),
        "HISTÓRIA"         => array(2 => 2, 3 => 1),
        "ARTES"            => array(1 => 2),
        "EDUCAÇÃO FÍSICA"  => array(1 => 1, 3 => 1),
        "ENSINO RELIGIOSO" => array(4 => 1),
    ),
);

$holydays  = array('2017-04-14', '2017-04-21', '2017-05-01');
$saturdays = array('2017-03-25' => 5, '2017-05-13' => 5, '2017-05-20' => 5, '2017-06-03' => 5);

echo "\n1º Trimestre: {$iniDate->format('d/m/Y')} a {$endDate->format('d/m/Y')}\n";

foreach ($configs as $class => $subjectConfigs) {

    $result = array();

    echo "\n+-----------+\n";
    echo "| Turma: {$class} |\n";
    echo "+-----------+\n";

    foreach ($subjectConfigs as $subject => $lessons) {

        $date   = clone $iniDate;
        $result[$subject] = array();

        while ($date <= $endDate) {
            if (isset($lessons[$date->format('N')]) && !in_array($date->format('Y-m-d'), $holydays)) {
                for ($i = 1; $i <= $lessons[$date->format('N')]; $i++) {
                    // $result[$subject][strftime("%B", $date->getTimestamp())][] = "{$date->format('d')}({$date->format('l')})";
                    $result[$subject][strftime("%B", $date->getTimestamp())][] = $date->format('d');
                }
            }

            // caso seja sábado letivo
            if (isset($saturdays[$date->format('Y-m-d')])) {
                if (isset($lessons[$saturdays[$date->format('Y-m-d')]])) {
                    for ($i = 1; $i <= $lessons[$saturdays[$date->format('Y-m-d')]]; $i++) {
                        // $result[$subject][strftime("%B", $date->getTimestamp())][] = "{$date->format('d')}({$date->format('l')})";
                        $result[$subject][strftime("%B", $date->getTimestamp())][] = $date->format('d');
                    }
                } else {
                    // $result[$subject][strftime("%B", $date->getTimestamp())][] = "{$date->format('d')}({$date->format('l')})";
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
}