<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

$response = (new Client())->get('http://www.guiatrabalhista.com.br/guia/salario_minimo.htm');

if ($response->getStatusCode() == '200') {

    $html = (string)$response->getBody();

    try {

        $crawler = new Crawler($html);
        $table_trs = $crawler->filter('#content > div > table > tbody > tr');
        $rows = $table_head = [];

        foreach ($table_trs as $tr_index => $tr_content) {

            $crawler = new Crawler($tr_content);
            $tds = $crawler->filter('td');
            $local_row = [];

            foreach ($tds as $td_index => $td_content) {

                if ($tr_index == 0) {
                    $table_head[] = trim($td_content->textContent);
                } else {
                    $local_row[$table_head[$td_index]] = trim($td_content->textContent);
                }
            }

            if (!empty($local_row)) {
                $rows[] = $local_row;
            }

        }

        echo '<pre>';
        return print_r($rows);


    } catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }

}

