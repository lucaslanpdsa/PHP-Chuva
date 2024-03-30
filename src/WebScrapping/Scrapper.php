<?php

namespace Chuva\Php\WebScrapping;

libxml_use_internal_errors(TRUE);
libxml_clear_errors();

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

require_once 'vendor/autoload.php';

/**
 * Does the scraping of a webpage.
 * Realiza o scraping de uma página da web.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   * Carrega informações do artigo do HTML e retorna o array com os dados.
   */
  public function scrap(\DOMDocument $dom): array {
    // Corrigido o nome da variável de $Pappers para $Papers.
    $papers = [];

    /**
     * Gets elements with the given class from the DOM.
     * Obtém elementos com a classe fornecida do DOM.
     */
    function getElementsByClass($dom, $class) {
      $elementsWithClass = $dom->getElementsByTagName('*');
      $elements = [];

      foreach ($elementsWithClass as $element) {
        if ($element->getAttribute('class') === $class) {
          // Adiciona o elemento ao array.
          $elements[] = $element->textContent;
        }
      }
      return $elements;
    }

    $title = getElementsByClass($dom, 'my-xs paper-title');
    $type = getElementsByClass($dom, 'tags mr-sm');
    $id = getElementsByClass($dom, 'volume-info');

    /**
     * Gets authors from the DOM.
     * Obtém autores do DOM.
     */
    function getAuthors($dom) {
      $divAuthors = $dom->getElementsByTagName('div');
      $allAuthors = [];

      foreach ($divAuthors as $author) {
        if ($author->hasAttribute('class') && $author->getAttribute('class') === 'authors') {
          $spans = $author->getElementsByTagName('span');
          $authorsOfPaper = [];

          foreach ($spans as $span) {
            // Extrai o nome do autor.
            $name = $span->textContent;
            // Extrai a instituição do autor do atributo title.
            $institution = $span->getAttribute('title');
            // Adiciona o autor à lista de autores.
            $authorObject = new Person($name, $institution);
            $authorsOfPaper[] = $authorObject;
          }
          $allAuthors[] = $authorsOfPaper;
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
      $papers[] = $paper;
    }

    // Retornar o array de Papers após o loop.
    var_dump($papers);
    return $papers;
  }

}
