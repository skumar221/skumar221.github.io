<?php
  
//
// Adapted from here: http://webcodingeasy.com/PHP-classes/Generate-shuffle-and-deal-custom-card-deck
//
  if (!interface_exists('JsonSerializable')) {
      interface JsonSerializable {
  /**
   * @return mixed Return data which should be serialized by json_encode().
   */
          function jsonSerialize();
      }
  }
  
  class Card implements JsonSerializable {
  /**
   * @var string The suit for the card
   */
      private $suit;
      
  /**
   * @var string The 'number' of the card.  A bit of a misnomer, A, J, Q, K can be included.
   */
      private $number;
      
  /**
   * Creates a new cards of suit $suit with number $number.
   * @param string $suit
   * @param string $number
   * @throws InvalidArgumentException if $suit is not a string.
   * @throws InvalidArgumentException if $number is not a string or an int.
   *
   * @todo More comprehensive checks to make sure each suit as number is valid.
   */
      public function __construct($suit, $number) {
          if (!is_string($suit)) {
              throw new InvalidArgumentException(
                  'First parameter to Card::__construct() must be a string.'
              );
          }
          
          if (!is_string($number) && !filter_var($number, FILTER_VALIDATE_INT)) {
              throw new InvalidArgumentException(
                  'Second parameter to Card::__construct() must be a string or an int.'
              );
          }
          $this->suit = $suit;
          $this->number = $number;
      }
      
  /**
   * @return string The suit for the card;
   */
      public function suit() {
          return $this->suit;
      }
      
  /**
   * @return string The number for the card;
   */
      public function number() {
          return $this->number;
      }
      
  /**
   * Returns a string depicting the card. Although it's json_encoded, don't
   * rely on that fact.  PHP 5.4 introduces the JsonSerializeable interface,
   * which should be used to json_encode an object.
   *
   * @return string The Card as a string.
   */
      public function __toString() {
          return json_encode($this->jsonSerialize());
      }
      
  /**
   * Returns the data that should be encoded  into JSON.
   * @return array Return data which should be serialized by json_encode().
   */
      public function jsonSerialize() {
          return get_object_vars($this);
      }
      
  }
  
  class Deck implements IteratorAggregate, ArrayAccess, Countable, JsonSerializable {
      
      private $deck;
      
  /**
   * Creates a new, unshuffled deck of cards, where the suits are in the order
   * of diamonds, hearts, clubs, spades, and each suit is ordered A, 2 .. 10,
   * J, Q, K.
   *
   * @param array $deck [optional] The deck of cards to be used.
   * @throws InvalidArgumentException if the any of the elements in $deck are not type Card.
   */
      public function __construct(array $deck=null) {
          if (isset($deck) && count($deck) > 0) {
              foreach ($deck as $card) {
                  if (!($card instanceof Card)) {
                      throw new InvalidArgumentException(
                          'The first parameter to Deck::__construct must be an array'
                          . ' containing only objects of type Card'
                      );
                  }
              }
              $this->deck = $deck;
          } 
          else {
              $this->deck = $this->createFullDeck();
          }
      }

      
  /**
   * Shuffle an array.  Uses PHP's shuffle if no function is provided. If a
   * function is provided, it must take an array of Cards as its only
   * parameter.
   * @param callable $function If $function isn't callable, shuffle will be used instead
   * @return mixed Returns the result of the shuffle function.
   */
      public function shuffle($function = null) {
          if (is_callable($function, false, $callable_name)) {
              return $callable_name($this->deck);
          } else {
              return shuffle($this->deck);
          }
      }
      
  /**
   * Used by IteratorAggregate to loop over the object.
   * @return ArrayIterator
   */
      public function getIterator() {
          return new ArrayIterator($this->deck);
      }
      
  /**
   * @param string $suit The suite to create.
   * @return array The cards for the suit.
   */
      private function createSuit($suit) {
          return array(
              new Card($suit, 'A'),
              new Card($suit, '2'),
              new Card($suit, '3'),
              new Card($suit, '4'),
              new Card($suit, '5'),
              new Card($suit, '6'),
              new Card($suit, '7'),
              new Card($suit, '8'),
              new Card($suit, '9'),
              new Card($suit, '10'),
              new Card($suit, 'J'),
              new Card($suit, 'Q'),
              new Card($suit, 'K')
          );
      }
      

  /**
   * Clears the deck
   * @return void
   */
      public function clearDeck() {
          $this->deck = array();
          return;
      }



  /**
   * Removes an item from the array and returns its value.
   *
   * @param array The cards to add.
   * @return void
   */
      public function addCards($cards) {
          foreach($cards as $card){
              array_push($this->deck, $card);
          }
          return;
      }


  /**
   * Removes an item from the array and returns its value.
   *
   * @param $key The key pointing to the desired value
   * @return The removed Card or null if none
   */
      private function removeCard($key) {
          if (array_key_exists($key, $this->deck)) {
              $val = $this->deck[$key];
              unset($this->deck[$key]);
              $this->deck = array_values($this->deck);
              return $val;
          }
          return null;
      }


  /**
   * Clears the deck
   * @param int $total
   * @return  array An array of type Card
   */
      public function draw($total) {
          $totalDrawn = 0;
          $drawnCards = array();
          while ((count($this->deck) > 0) && ($totalDrawn < $total)){
              array_push($drawnCards, $this->removeCard(rand(0, count($this->deck) - 1)));
              $totalDrawn++;
          };
          return $drawnCards;
      }



  /**
   * Echoes the deck
   * @param string $prefix
   * @return void
   */
      public function echoDeck($prefix) {
          echo $prefix . "\n";
          foreach ($this as $card) {
              echo $card . "\n";
          }
         return;
      }



  /**
   * Returns a new, unshuffled array of cards, where the suits are in the
   * order of diamonds, hearts, clubs, spades, and each suit is ordered:
   * A, 2 .. 10, J, Q, K.
   * @return array An array of type Card.
   */
      private function createFullDeck() {
          return array_merge(
              $this->createSuit('diamonds'),
              $this->createSuit('hearts'),
              $this->createSuit('clubs'),
              $this->createSuit('spades')
          );
      }
      
  /**
   * Resets the deck to an unshuffled order, and returns the deck.
   * @return \Deck
   */
      public function reset() {
          $this->deck = $this->createDeck();
          return $this;
      }
      
  /**
   * Returns the data that should be encoded into JSON. Note that any objects
   * inside must also be jsonSerialized for anything less than PHP 5.4.
   *
   * @return mixed Return data which should be serialized by json_encode().
   */
      public function jsonSerialize() {
          $array = $this->deck;
          
          foreach($array as &$card) {
  /**
   * @var Card $card
   */
              $card = $card->jsonSerialize();
          }
          
          return $array;
      }
      
  /**
   * Used by ArrayAccess.  Determine whether an offset(index) exists.
   * @param int $index The index to test for existence.
   * @return boolean Returns true of the offset exists.
   */
      public function offsetExists($index) {
          return array_key_exists($index, $this->deck);
      }
      
  /**
   * Used by ArrayAccess.  Returns an item from the index provided.
   * @param int $index The index to get..
   * @return boolean Returns the object at the location.
   * @throws OutOfBoundsException if you specify an index that does not exist.
   */
      public function offsetGet($index) {
          if (!$this->offsetExists($index)) {
              throw new OutOfBoundsException(
                  "The index '$index' does not exist."
              );
          }
          return $this->deck[$index];
      }
      
  /**
   * Used by ArrayAccess. Sets an index with the value, or adds a value if it
   * is null.
   * @param int|null $index The index to set, or null to add.
   * @param Card $value The card to set/add.
   * @return void
   * @throws InvalidArgumentException if the value provided is not a Card.
   * @throws InvalidArgumentException if the index provided is not an integer.
   * @throws OutOfBoundsException if the index provided does not exist.
   */
      public function offsetSet($index, $value) {
          if (!($value instanceof Card))
              throw new InvalidArgumentException('Decks only contain cards.');
          
          if ($index == null) {
              $this->deck[] = $value;
  return;
          }
          
          if (!is_numeric($index) || $index != (int) $index) {
              throw new InvalidArgumentException("Index '$index' must be an integer.");
          }
          
          if (!$this->offsetExists($index)) {
              throw new OutOfBoundsException("Index '$index' does not exist");
          }
          
          $this->deck[$index] = $value;
      }
      
  /**
   * Unsets the index location.
   * @param int $index
   * @return void
   * @throws InvalidArgumentException if the index provided does not exist.
   */
      public function offsetUnset($index) {
          if (!$this->offsetExists($index)) {
              throw new InvalidArgumentException("Index '$index' Does not exist.");
          }
          
          array_splice($this->deck, $index, 1);
      }
      
  /**
   * Returns a string depicting the card. Although it's json_encoded, don't
   * rely on that fact.  PHP 5.4 introduces the JsonSerializeable interface,
   * which should be used to json_encode an object.
   *
   * @return string The Card as a string.
   */
      public function __toString() {
          return json_encode($this->jsonSerialize());
      }
      
  /**
   * Used by interface Count.
   * @return int The size of the deck.
   */
      function count() {
          return count($this->deck);
      }

      
  }



function echoJson($ddeck, $pdeck){
    $data = array(
        'dealer'    => $ddeck,
        'player'    => $pdeck,
    );
    echo(htmlentities(json_encode($data)));
}


/**
 * @param array 
 * @retrun new Deck
 */
function deckFromJsonArray($jsonArr){
    if (count($jsonArr) == 0){
        $d = new Deck();
        $d->clearDeck();
        return $d;
    }
    $cards = array();
    foreach ($jsonArr as $cardJson){
        array_push($cards, new Card($cardJson['suit'], $cardJson['number']));
    }
    return new Deck($cards);    
}


if ($_GET['format'] == 'json') {
    $dealerdeck = new Deck();
    $playerdeck = new Deck();
    $playerdeck->clearDeck();
    echoJson($dealerdeck, $playerdeck);

// SQL STUFF WOULD GO HERE
}


if(isset($_POST['decks'])){ 
    
    $dealerJson = $_POST['decks']['dealer'];
    $playerJson = $_POST['decks']['player'];
    $action = $_POST['decks']['action'];
    
    $dealer = deckFromJsonArray($dealerJson);
    $player = deckFromJsonArray($playerJson);
    
    if ($action == 'shuffle') {
        $dealer->shuffle();
        echoJson($dealer, $player);
    }
    else if ($action == 'draw') {
        $player->addCards($dealer->draw(intval($_POST['decks']['drawnum'])));
        echoJson($dealer, $player);
    }
    else if ($action == 'repack') {
        $dealer->addCards($player->draw(count($playerJson)));
        echoJson($dealer, $player);
    }

// SQL STUFF WOULD GO HERE

    return;
} 
?>
