<?php

namespace Chuva\Php\WebScrapping;

// Habilita o gerenciamento interno de erros do libxml.
libxml_use_internal_errors(TRUE);

// Importa uma classe do pacote box/spout.
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

require 'Scrapper.php';
require_once 'vendor/autoload.php';

/**
 * Runner for the Webscraping exercise.
 */
class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');

    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    $data = (new Scrapper())->scrap($dom);

    // Define o caminho do arquivo Excel de saída.
    $xlsxFile = __DIR__ . '/output.xlsx';

    // Cria um escritor (writer) de arquivo Excel.
    $excelDoc = WriterEntityFactory::createXLSXWriter();

    // Abre o arquivo Excel para escrita.
    $excelDoc->openToFile($xlsxFile);

    // Criação do Header com as informações.
    $headers = ['ID', 'Title', 'Type'];
    for ($i = 1; $i <= 20; ++$i) {
      $headers[] = "Author $i";
      $headers[] = "Author $i Institution";
    }

    // Cria uma linha de cabeçalho.
    $headerRow = WriterEntityFactory::createRowFromArray($headers);
    // Adiciona o cabeçalho ao arquivo Excel.
    $excelDoc->addRow($headerRow);

    // Itera sobre os dados raspados e escreve cada linha no arquivo Excel.
    foreach ($data as $paper) {
      $rowData = [
        $paper->id,
        $paper->title,
        $paper->type,
      ];

      // Verificação de autores.
      $authors = $paper->authors;

      for ($i = 0; $i < 20; ++$i) {
        if (isset($authors[$i])) {
          $author = $authors[$i];
          $rowData[] = $author->name;
          $rowData[] = $author->institution;
        }
        else {
          // Preencher com vazio quando não houver autores.
          $rowData[] = '';
          $rowData[] = '';
        }
      }

      // Cria uma linha de dados.
      $row = WriterEntityFactory::createRowFromArray($rowData);
      // Adiciona a linha ao arquivo Excel.
      $excelDoc->addRow($row);
    }

    // Fecha o arquivo Excel.
    $excelDoc->close();
  }

}
