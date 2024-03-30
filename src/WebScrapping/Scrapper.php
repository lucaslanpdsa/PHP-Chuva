<?php

namespace Chuva\Php\WebScrapping;

libxml_use_internal_errors(true);
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

    function getElement($dom, $class){
      $elementosComClasse = $dom->getElementsByTagName('*');
      $elementos = [];

      foreach ($elementosComClasse as $elemento) {
      if ($elemento->getAttribute('class') === $class) {
        // Adiciona o elemento ao array
        $elementos[] = $elemento->textContent;
      }

    }
    return $elementos;
    }

    $title = getElement($dom,'my-xs paper-title');
    $type = getElement($dom, 'tags mr-sm');
    $id = getElement($dom, 'volume-info');

    function getAuthors($dom){
      $divAuthors = $dom->getElementsByTagName('div');
      $allAuthors = [];
  
      foreach ($divAuthors as $Author) {
        if ($Author->hasAttribute('class') && $Author->getAttribute('class') === 'authors') {
            $spans = $Author->getElementsByTagName('span');
            $authorsOfPapper = [];

            foreach ($spans as $span) {
                // Extrai o nome do autor
                $name = $span->textContent;
                // Extrai a instituição do autor do atributo title
                $institution = $span->getAttribute('title');
                // Adiciona o autor à lista de autores
                $authors = new Person($name, $institution);
                $authorsOfPapper[] = $authors;
            }
          $allAuthors[] = $authorsOfPapper;
        }
      }
  
      var_dump($allAuthors);
      return $allAuthors;
    }
  
    $authors = getAuthors($dom);
    var_dump($authors);
    }
  }

  //dsasaddsad