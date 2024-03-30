<?php

namespace Chuva\Php\WebScrapping;

libxml_use_internal_errors(TRUE);
libxml_clear_errors();

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

require_once 'vendor/autoload.php';

/**
 * Does the scrapping of a webpage.
 * Realiza o scraping de uma página da web.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   * Carrega informações do artigo do HTML e retorna o array com os dados.
   */
  public function scrap(\DOMDocument $dom): array {
    // Corrigido o nome da variável de $Pappers para $Papers.
    $Papers = [];

    /**
     *
     */
    function getElement($dom, $class) {
      $elementosComClasse = $dom->getElementsByTagName('*');
      $elementos = [];

      foreach ($elementosComClasse as $elemento) {
        if ($elemento->getAttribute('class') === $class) {
          // Adiciona o elemento ao array.
          $elementos[] = $elemento->textContent;
        }

      }
      return $elementos;
    }

    $title = getElement($dom, 'my-xs paper-title');
    $type = getElement($dom, 'tags mr-sm');
    $id = getElement($dom, 'volume-info');

    /**
     *
     */
    function getAuthors($dom) {
      $divAuthors = $dom->getElementsByTagName('div');
      $allAuthors = [];

      foreach ($divAuthors as $Author) {
        if ($Author->hasAttribute('class') && $Author->getAttribute('class') === 'authors') {
          $spans = $Author->getElementsByTagName('span');
          $authorsOfPapper = [];

          foreach ($spans as $span) {
            // Extrai o nome do autor.
            $name = $span->textContent;
            // Extrai a instituição do autor do atributo title.
            $institution = $span->getAttribute('title');
            // Adiciona o autor à lista de autores.
            $authors = new Person($name, $institution);
            $authorsOfPapper[] = $authors;
          }
          $allAuthors[] = $authorsOfPapper;
        }
      }

      return $allAuthors;
    }

    $authors = getAuthors($dom);

    // Criar objetos Paper com base nos dados extraídos.
    foreach ($id as $index => $paperId) {
      // Criar objeto Person com base na lista de autores para este papel.
      $authorsForPaper = $authors[$index];

      // Criar objeto Paper com ID, título, tipo e autores.
      $paper = new Paper($paperId, $title[$index], $type[$index], $authorsForPaper);

      // Adicionar o objeto Paper ao array de Papers.
      $Papers[] = $paper;
    }

    // Retornar o array de Papers após o loop.
    var_dump($Papers);
    return $Papers;
  }

}
