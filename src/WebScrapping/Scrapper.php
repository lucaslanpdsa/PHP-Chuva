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

    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

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
    var_dump($id);

    function getauthors($dom){
      $divAuthors = $dom->getElementsByTagName('div');
      $authors = [];
  
      foreach ($divAuthors as $Author) {
        if ($Author->hasAttribute('class') && $Author->getAttribute('class') === 'authors') {
            $spans = $Author->getElementsByTagName('span');
            foreach ($spans as $span) {
                // Extrai o nome do autor
                $name = $span->textContent;
                // Extrai a instituição do autor do atributo title
                $institution = $span->getAttribute('title');
                // Adiciona o autor à lista de autores
                $authors[] = new Person($name, $institution);
            }
        }
      }
  
      var_dump($authors);
      return $authors;
    }
  
    $authors = getauthors($dom);
    var_dump($authors);

    return [
      new Paper(
        123,
        'The Nobel Prize in Physiology or Medicine 2023',
        'Nobel Prize',
        [
          new Person('Katalin Karikó', 'Szeged University'),
          new Person('Drew Weissman', 'University of Pennsylvania'),
        ]
      ),
    ];
    }
  }
  
  
$scrapper = new Scrapper();

// Crie um objeto DOMDocument e carregue o arquivo HTML
$dom = new \DOMDocument('1.0', 'utf-8');
$dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

// Chame o método scrap() passando o objeto DOMDocument como argumento
$scrapData = $scrapper->scrap($dom);

// Exiba o resultado
var_dump($scrapData);