<?php

namespace Chuva\Php\WebScrapping;

require_once 'vendor/autoload.php';

libxml_use_internal_errors(true);
libxml_clear_errors();

/**
 * Runner for the Webscrapping exercice.
 * Executador para o exercício de Web Scraping.
 */
class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   * Executor principal, instancia um Scraper e executa.
   */
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    $data = (new Scrapper())->scrap($dom);

    // Write your logic to save the output file bellow.
    // Escreva sua lógica para salvar o arquivo de saída abaixo.
    print_r($data);
  }

}