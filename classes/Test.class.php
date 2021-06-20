<?php
    class Test {
        // defineerime muutujad ehk properties
        private $secret = 7;
        public $non_secret = 3;
        private $received_secret;

        // funktsioonid ehk methods
        // constructor - funktsioon mis käivitub alles siis kui class kasutusele võetakse
        function __construct($received) {      // construct sulgudesse lähevad parameetrid
            echo "Klass on laetud! Konstruktor töötab!";
            $this->received_secret = $received;
            echo "Saabunud salajane number on " .$this->received_secret .". ";
            $this->multiply();
        }

        function __destruct() {
            echo "Klass lõpetas!";
        }

        // methods can also be public or private
        public function reveal() {
            echo "Täiesti salajane number on " .$this->secret .". ";
        }

        private function multiply() {
            echo " Korrutis on: " .$this->secret * $this->non_secret * $this->received_secret .". ";
        }

    }   // class lõppeb