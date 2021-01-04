<?php

/**
 * Set any link as active by adding active class
 * @param [uri] $uri    Current URI.
 * @param string $output CSS Class name.
 */

function set_active($uri, $output = 'active')
{
  if( is_array($uri) ) {
    foreach ($uri as $u) {
      if (Route::is($u)) {
        return $output;
      }
    }
  } else {
    if (Route::is($uri)){
      return $output;
    }
  }
}

function abbr_name($string)
{
  $names = explode(' ', ucwords($string));
  $first_part = array_slice($names, 0, 2);
  $last_part = array_slice($names, 2);
  $count = count($names);

  $abbr = [];
  if($count > 2) {
      if (in_array($names[0], array("Muhammad", "Muchammad", "Mochammad", "Mohammad"))) {
        switch ($names[0]) {
          case 'Muhammad':
            $first_name = "Muh.";
            break;
          case 'Muchammad':
            $first_name = "Much.";
            break;
          case 'Mochammad':
            $first_name = "Moch.";
            break;
          case 'Mohammad':
            $first_name = "Moh.";
            break;
          default:
            $first_name = $names[0];
            break;
        }
        $first = $first_name." ".$names[1];
      } else {
        $first = implode(" ", $first_part);
      }

      // make last name abbreviate
      for($i = 2; $i < $count; $i++){
          $char = substr($names[$i],0,1).".";
          array_push($abbr, $char);
      }
      $last = implode("", $abbr);

      // combine
      $final = $first." ".$last;
  } else {
      $final = ucwords($string);
  }

  return $final;
}

function first_name($string)
{
  $names = explode(" ", ucwords($string));
  if (count($names) > 1) {
    if (in_array($names[0], array("Muhammad", "Muchammad", "Mochammad", "Mohammad"))) {
      return ucfirst($names[1]);
    } else {
      return ucfirst($names[0]);
    }
  } else {
    return ucfirst($names[0]);
  }

}


?>
