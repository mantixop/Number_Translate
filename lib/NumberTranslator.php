
<?php

//singelton pattern

class NumberTranslator{
     private $dictionary;
     private $language;
     private static $_instance;

    public static function getNumberTranslator() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function setLanguageRU(){
        if ($this->language != "ru") {
           $this->language = "ru";
            $this->updateDictionary();
        }
    }

    public function setLanguageUA(){
        if ($this->language != "ua") {
            $this->language = "ua";
            $this->updateDictionary();
        }
    }

    public function setLanguageENG(){
        if ($this->language != "eng") {
            $this->language = "eng";
            $this->updateDictionary();
        }
    }

    public function getLanguage(){
        return $language;
    }

    
    public  function  getStringForNumber($number){
        $numb = $number;
        $string = "";
        $degree = 0;
        if($numb < 0){
            $numb *= -1;
        }
    //    echo "$numb";
        $check = $this->checkNumber($numb);
        if($check == false){
            //parsing number
            while ($numb != 0) {
                $temp = $numb % 1000;
                if($temp != 0){
                    $string = $this->getTreeNumber($temp,$degree).$string;
                }
                $numb = intval($numb/1000);
                $degree +=1;
            }

            if($number < 0){
                if($this->language == "eng")
                    $string = "minus ".$string;
                if($this->language == "ru")
                    $string = "минус ".$string;
                if($this->language == "ua")
                    $string = "мінус ".$string;
            }

           return $string;
        } else {
            return $check;
        }
    }

  

    private function __construct() {
        $this->setLanguageENG();
    }

    private function __clone(){
    }

    private function checkNumber($number){
        if($number > 1000000000000){
                return "Must be less then trillion";
        }
    
        if(!is_numeric($number)){
                return "Must be integer";
        }
        if ($number - intval($number) != 0){
                return "Must be integer";
        }
        return false;
    }


    private function updateDictionary(){
         $this->dictionary = simplexml_load_file("xml/numerals_".$this->language.".xml");
    }

    private function getTreeNumber($numb,$degree){
        //parsing
        $firstDigit = intval($numb/100);
        $secondDigit = intval(($numb - $firstDigit * 100)/10);
        $thirdDigit = ($numb - $firstDigit * 100)%10;
        $string = "";
        //additional word for thousands
        if ($degree == 1) {
            if($secondDigit != 1){
                if ( $thirdDigit  == 1) {
                    $string = $this->dictionary->oneThousand.$string;
                } else if ((  $thirdDigit == 2) || ($thirdDigit  == 4) || ($thirdDigit == 3)) {
                    $string = $this->dictionary->twoThreeFourThousand.$string;
                } else {
                    $string = $this->dictionary->manyThousand.$string;
                }
            } else {
                $string = $this->dictionary->manyThousand.$string;
            }
        }   

        //additional word for millions
        if ($degree == 2) {
            if($secondDigit != 1){
                if ( $thirdDigit  == 1) {
                    $string = $this->dictionary->oneMillion.$string;
                } else if ((  $thirdDigit == 2) || ($thirdDigit  == 4) || ($thirdDigit == 3)) {
                    $string = $this->dictionary->twoThreeFourMillion.$string;
                } else {
                    $string = $this->dictionary->manyMillion.$string;
                }
            } else {
                $string = $this->dictionary->manyMillion.$string;
            }   
        }

         //additional word for billions
        if ($degree == 3) {
            if($secondDigit != 1){
                if ( $thirdDigit  == 1) {
                    $string = $this->dictionary->oneBillion.$string;
                } else if (($thirdDigit == 2) || ($thirdDigit == 4) || ($thirdDigit == 3)) {
                    $string = $this->dictionary->twoThreeFourBillion.$string;
                } else {
                    $string = $this->dictionary->manyBillion.$string;
                }
            } else {
                $string = $this->dictionary->manyBillion.$string;
            }
        }

        //words for two-digit number
        if($secondDigit == 1){    
                $string = $this->dictionary->units->digit[$thirdDigit + $secondDigit * 10].$string;
        } else {
                if(($degree == 1) && ($thirdDigit == 1)){
                    $string = $this->dictionary->units->alterOne.$string;
                } else if (($degree == 1) && ($thirdDigit == 2)){
                    $string = $this->dictionary->units->alterTwo.$string;
                } else {
                    $string = $this->dictionary->units->digit[$thirdDigit].$string;
                }
            $string = $this->dictionary->tens->digit[$secondDigit].$string;
        }

         //word for hundreds
        $string = $this->dictionary->hundreds->digit[$firstDigit].$string;

        return $string;
    }

    
}  