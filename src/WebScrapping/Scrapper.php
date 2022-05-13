<?php

namespace Galoa\ExerciciosPhp2022\WebScrapping;

use DOMXPath;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  private function getIds(\DOMDocument $dom): array{
    $ids = [];
    $className = "volume-info";
    $xpath = new DOMXPath($dom);
    $idElements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $className ')]");
    
    foreach($idElements as $idElement){
      array_push($ids, $idElement->textContent);
    }

    return $ids;
  }

  private function getTitles(\DOMDocument $dom): array{
    $titles = [];
    $className = "paper-title";
    $xpath = new DOMXPath($dom);
    $titleElements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $className ')]");

    foreach($titleElements as $titleElement){
      array_push($titles, $titleElement->textContent);
    }

    return $titles;
  }

  private function getTypes(\DOMDocument $dom): array{
    $types = [];
    $className = "tags mr-sm";
    $xpath = new DOMXPath($dom);
    $typeElements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $className ')]");

    foreach($typeElements as $typeElement){
      array_push($types, $typeElement->textContent);
    }

    return $types;
  }

  private function getAuthors(\DOMDocument $dom): array{
    $authors = [];
    $className = "authors";
    $xpath = new DOMXPath($dom);
    $authorsElements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $className ')]");

    $authorsPerPaper = [];
    foreach($authorsElements as $authorsElement){
      $authors = [];
      $authorsTitles = $authorsElement->getElementsByTagName("span");
      foreach($authorsTitles as $author){
        array_push($authors, rtrim($author->textContent, " ;"));
      }
      array_push($authorsPerPaper, $authors);
    }

    return $authorsPerPaper;
  }

  private function getAuthorsInstitutions(\DOMDocument $dom): array{
    $authors = [];
    $className = "authors";
    $xpath = new DOMXPath($dom);
    $authorsElements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $className ')]");

    $authorsPerPaper = [];
    foreach($authorsElements as $authorsElement){
      $authors = [];
      $authorsTitles = $authorsElement->getElementsByTagName("span");
      foreach($authorsTitles as $author){
        array_push($authors, $author->getAttribute('title'));
      }
      array_push($authorsPerPaper, $authors);
    }

    return $authorsPerPaper;
  }

  private function writeFile(array $ids, array $titles, array $types, array $authorsPerPaper, array $authorsInstitutions, $outputFilePath): void{
    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile($outputFilePath);

    $numberOfRows = count($ids);
    $header = ["ID", "Title", "Type"];
    $headerStyle = (new StyleBuilder())
           ->setFontBold()
           ->build();

    //finds the maximum number of authors in one single paper
    $maxAuhtors = 0;
    foreach($authorsPerPaper as $authors){
      $numberOfAuthors = count($authors);
      if($numberOfAuthors > $maxAuhtors){
        $maxAuhtors = $numberOfAuthors;
      }
    }

    //builds the header with the maximum authors needded
    for($i=1; $i <= $maxAuhtors; $i++){
      array_push($header, "Author " . $i);
      array_push($header, "Author " . $i . " Institution");
    }

    $headerRow = WriterEntityFactory::createRowFromArray($header, $headerStyle);
    $writer->addRow($headerRow);

    $rows = [];
    
    for($i=0; $i < $numberOfRows; $i++){
      $row = [
        $ids[$i],
        $titles[$i],
        $types[$i]
      ];

      $numberOfAuthors = count($authorsPerPaper[$i]);
      for($j=0; $j < $numberOfAuthors; $j++){
        array_push($row, $authorsPerPaper[$i][$j]);
        array_push($row, $authorsInstitutions[$i][$j]);
      }
      
      array_push($rows, $row);
    }

    foreach($rows as $row){
      $rowFromArray = WriterEntityFactory::createRowFromArray($row);
      $writer->addRow($rowFromArray);
    }

    $writer->close();
  }

  /**
   * Loads paper information from the HTML and creates a XLSX file.
   */
  public function scrap(\DOMDocument $dom, string $outputFilePath): void {
    $ids = $this->getIds($dom);
    $titles = $this->getTitles($dom);
    $types = $this->getTypes($dom);
    $authorsPerPaper = $this->getAuthors($dom);
    $authorsInstitutions = $this->getAuthorsInstitutions($dom);

    $this->writeFile($ids, $titles, $types, $authorsPerPaper, $authorsInstitutions, $outputFilePath);
    
  }
}
