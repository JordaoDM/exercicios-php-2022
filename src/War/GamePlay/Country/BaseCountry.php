<?php

namespace Galoa\ExerciciosPhp2022\War\GamePlay\Country;

/**
 * Defines a country, that is also a player.
 */
class BaseCountry implements CountryInterface {

  /**
   * The name of the country.
   *
   * @var string
   */
  protected $name;
  
  /**
   * Array of neighbors.
   *
   * @var array
   */
  protected $neighbors;

  /**
   * Number of troops.
   *
   * @var int
   */
  protected $troops;

  /**
   * Conquered flag, true if conquered.
   *
   * @var bool
   */
  protected $conquered;

  /**
   * Number of coutries conquered.
   *
   * @var int
   */
  protected $conqueredCountries;

  /**
   * Builder.
   *
   * @param string $name
   *   The name of the country.
   */
  public function __construct(string $name) {
    $this->name = $name;
    $this->troops = 3; //initialize country with 3 troops
    $this->conquered = FALSE;
    $this->conqueredCountries = 0;
  }

  /**
   * Get name.
   *
   * @return $name
   *   Returns the name of the country.
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * Set neighbors.
   * 
   * Adds an array of neighbors to $neighbors.
   *
   * @param array $neighbors
   *   Array containing the neighbors to be added.
   */
  public function setNeighbors(array $neighbors): void {
    $this->neighbors = $neighbors;
  }

  /**
   * Add neighbor.
   * 
   * Adds one specific country to the $neighbors array.
   *
   * @param array $neighbor
   *   Country object.
   */
  public function addNeighbor($neighbor): void{
    array_push($this->neighbors, $neighbor);
  }

  /**
   * Get neighbors.
   *
   * @return $this->neighbors.
   *   Returns an array containing all the neighbors.
   */
  public function getNeighbors(): array {
    return $this->neighbors;
  }

  /**
   * Is conquered.
   *
   * @return $this->conquered.
   *   Returns a bool value true if the country is conquered.
   */
  public function isConquered(): bool{
    return $this->conquered;
  }

  /**
   * Set conquered.
   *
   * Set the conquered flag to true.
   */
  public function setConquered(): void {
    $this->conquered = TRUE;
  }

  /**
   * Get number of conquered countries.
   *
   * @return $this->conqueredCountries.
   *   Returns an int with the number of conquered countries.
   */
  public function getNumberOfConqueredCountries(): int{
    return $this->conqueredCountries;
  }

  /**
   * Increase conquered countries.
   *
   * Increase by one the number of conquered countries in $conqueredCountries.
   */
  public function increaseConqueredCountries(): void{
    $this->conqueredCountries++;
  }

  /**
   * Get number of troops.
   *
   * @return $this->troops.
   *   Returns an int with the number of troops.
   */
  public function getNumberOfTroops(): int {
    return $this->troops;
  }

  /**
   * Add troops.
   * 
   * Adds a specific number of troops to the $troops variable.
   *
   * @param array $troops2add
   *   Int number.
   */
  public function addTroops($troops2add): void{
    $this->troops = $this->troops + $troops2add;
  }

  /**
   * Conquer.
   * 
   * Conquer other country by adding its neighbors to this country.
   *
   * @param CountryInterface $conqueredCountry
   *   CountryInterface object.
   */
  public function conquer(CountryInterface $conqueredCountry): void {
    $conqueredNeighbors = $conqueredCountry->getNeighbors();
    
    foreach($conqueredNeighbors as $conqueredNeighbor){
      if((!in_array($conqueredNeighbor, $this->neighbors)) and ($this->name != $conqueredNeighbor->getName())){
        array_push($this->neighbors, $conqueredNeighbor);
        $conqueredNeighbor->addNeighbor($this);
      }
    }
  }

  /**
   * Kill troops.
   * 
   * Subtract a specific number of troops from the $troops variable.
   *
   * @param int $killedTroops
   *   Int number.
   */
  public function killTroops(int $killedTroops): void {
    if($this->troops > 0){
      $this->troops = $this->troops - $killedTroops;
    }
  }
}
