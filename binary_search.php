<?php
    function possible_key($file, $position_key){
      if ($position_key == 0){
        return read_word($file, $position_key);
      }

      fseek($file, $position_key, SEEK_SET);
      if (!feof($file)){
        $buff = fgetc($file);
        while  ($buff != '\\' and !feof($file)){
          $buff = fgetc($file);
        }
      } 
      if (!feof($file)){
        $buff = fgetc($file);
      }

      if ($buff == 't' || feof($file)){
        while ($position_key > 0 and $buff != 'A') {
          $position_key--;
          fseek($file, $position_key, SEEK_SET);
          $buff = fgetc($file);
        }
        if ($position_key == 0){
          return read_word($file, $position_key);
        } else{
          return read_word($file, $position_key+1);
        }
      }


      if ($buff == 'x'){
        $position_key = ftell($file)+2;
        return read_word($file, $position_key);
      }
      return undef;

    }

    function read_word($file, $position){

      fseek($file, $position, SEEK_SET);
      if (feof($file)){
        return undef;
      }

      $word = "";
      $buff = fgetc($file);
      while  ($buff != '\\' and !feof($file)){
        $word .=$buff;
        $buff = fgetc($file);
      }

      return $word;
    }


    function binary_search($fileName, $key){
      $file = fopen($fileName, "r");
      $start = ftell($file);
      $finish = filesize($fileName);
      $key = iconv('utf-8', 'ascii//TRANSLIT', $key);
      while ($start <= $finish) {
        $position = (int) floor(($finish + $start)/2);
        $word = possible_key($file, $position);
        if (strcmp($word, $key) == 0){
          $position = ftell($file);
                return read_word($file, $position+1);
            }
            if(strcmp($word, $key) > 0){
                $finish = $position - 1;
              }
              if(strcmp($word, $key) < 0){
                $start = $position + 1;
            }
      }
      fclose($file);
      return undef;

    }
?>
