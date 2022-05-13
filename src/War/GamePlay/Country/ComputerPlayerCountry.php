<?php

namespace Galoa\ExerciciosPhp2022\War\GamePlay\Country;

/**
 * Defines a country that is managed by the Computer.
 */
class ComputerPlayerCountry extends BaseCountry {

  /**
   * Choose one country to attack, or none.
   *
   * The computer may choose to attack or not. If it chooses not to attack,
   * return NULL. If it chooses to attack, return a neighbor to attack.
   *
   * It must NOT be a conquered country.
   *
   * @return \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface|null
   *   The country that will be attacked, NULL if none will be.
   */
  public function chooseToAttack(): ?CountryInterface {
    $weakNeighbors = [];

    //selects only the countries that have the same number of troops or less
    foreach($this->neighbors as $neighbor){
      if(($this->getNumberOfTroops() >= $neighbor->getNumberOfTroops()) and ($neighbor->isConquered() == FALSE)){
        array_push($weakNeighbors, $neighbor);
      }
    }

    
    if(count($weakNeighbors) == 0){ //does not attack if there is no weak neighbors
      return NULL;
    } else { //attacks the weaker one
      uasort($weakNeighbors, array($this, 'compareCountryByTroops'));
      return $weakNeighbors[0];
    }
  }

  //compares two countries by the number of troops
  /**
   * Compare country by troops
   *
   * Compares two countries by the number of troops that each one have.
   * 
   * @param CountryInterface $a
   *  CountryInterface object.
   * @param CountryInterface $b
   *  CountryInterface object.
   * 
   * @return int
   *  Returns an integer 0 if equal, 1 if $a > $b and -1 if $a < $b
   * 
   */
  private function compareCountryByTroops(CountryInterface $a, CountryInterface $b){
    if($a->getNumberOfTroops() == $b->getNumberOfTroops()){
      return 0;
    }

    if($a->getNumberOfTroops() > $b->getNumberOfTroops()){
      return 1;
    } else {
      return -1;
    }
  }
}
