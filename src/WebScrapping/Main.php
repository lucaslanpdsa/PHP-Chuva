<?php

namespace Chuva\Php\WebScrapping;

libxml_use_internal_errors(TRUE); // Habilita o gerenciamento interno de erros do libxml

use Box\Spout\Writer\Common\Creator\WriterEntityFactory; // Importa uma classe do pacote box/spout

require 'Scrapper.php';
require_once 'vendor/autoload.php';

/**
 * Runner for the Webscrapping exercice.
 */
class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');

    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    $data = (new Scrapper())->scrap($dom);

    $xlsxFile = __DIR__ . '/output.xlsx'; // Define o caminho do arquivo Excel de saída

    $excelDoc = WriterEntityFactory::createXLSXWriter(); // Cria um escritor (writer) de arquivo Excel
    $excelDoc->openToFile($xlsxFile); // Abre o arquivo Excel para escrita

    // Criação do Header com as informações
    $headers = ['ID', 'Title', 'Type'];
    for ($i = 1; $i <= 20; ++$i) {
      $headers[] = "Author $i";
      $headers[] = "Author $i Institution";
    }

    $headerRow = WriterEntityFactory::createRowFromArray($headers); // Cria uma linha de cabeçalho
    $excelDoc->addRow($headerRow); // Adiciona o cabeçalho ao arquivo Excel

    // Itera sobre os dados raspados e escreve cada linha no arquivo Excel
    foreach ($data as $paper) {
      $rowData = [
        $paper->id,
        $paper->title,
        $paper->type,
      ];

      // Verificação de autores
      $authors = $paper->authors;

      for ($i = 0; $i < 20; ++$i) {
        if (isset($authors[$i])) {
          $author = $authors[$i];
          $rowData[] = $author->name;
          $rowData[] = $author->institution;
        }
        else {
          // Preencher com vazio quando não houver autores
          $rowData[] = '';
          $rowData[] = '';
        }
      }

      $row = WriterEntityFactory::createRowFromArray($rowData); // Cria uma linha de dados
      $excelDoc->addRow($row); // Adiciona a linha ao arquivo Excel
    }

    $excelDoc->close(); // Fecha o arquivo Excel
  }

}
