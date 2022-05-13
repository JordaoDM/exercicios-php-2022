<?php

namespace Galoa\ExerciciosPhp2022\War\GamePlay;

use Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface;

/**
 * A manager that will roll the dice and compute the winners of a battle.
 */
class Battlefield implements BattlefieldInterface {

  //rolls pseudo random numbers for each troop (minus 1 if attacking)
  /**
   * Roll dice
   *
   * Rolls a pseudo random number between 1 and 6 for each troop (minus one if attacking)
   * @param CountryInterface $country
   *  CountryInterface object.
   * @param bool $isAtacking
   *  Boolean variable.
   * 
   * @return $numbers
   *  Returns an array with the rolled numbers.
   */
  public function rollDice(CountryInterface $country, bool $isAtacking): array {
    $numbers = [];
    $troops = $country->getNumberOfTroops();

    if($isAtacking == TRUE){
        $troops--;
    }

    for($i = 0; $i < $troops; $i++){
        $rolledNumber = rand(1, 6);
        array_push($numbers, $rolledNumber);
    }
        
    rsort($numbers);
    return $numbers;
  }

  /**
   * Compute battle
   *
   * Compares the dices and verifies if the defending country gets conquered.
   * 
   * @param CountryInterface $attackingCountry
   *  CountryInterface object.
   * @param array $attackingDice
   *  Array of int.
   * @param CountryInterface $defendingCountry
   *  CountryInterface object.
   * @param array $defendingDice
   *  Array of int.
   */
  public function computeBattle(CountryInterface $attackingCountry, array $attackingDice, CountryInterface $defendingCountry, array $defendingDice): void {
    $attackingTroops = count($attackingDice);
    $defendingTroops = count($defendingDice);
    $numberOfComparisons = 0;

    if($attackingTroops > $defendingTroops) {
      $numberOfComparisons = $defendingTroops;
    } else {
      $numberOfComparisons = $attackingTroops;
    }

    for($i = 0; $i < $numberOfComparisons; $i++){
      if($attackingDice[$i] > $defendingDice[$i]){ //attacking win
          $defendingCountry->killTroops(1);
        } else { //defending win
          $attackingCountry->killTroops(1);
      }
    }

    if($defendingCountry->getNumberOfTroops() == 0){ //attacking attach the defending country
      $attackingCountry->conquer($defendingCountry);
      $attackingCountry->increaseConqueredCountries();
      $defendingCountry->setConquered();
    }
  }
}
