<?php 
class Weather {
    private $city; 
    private $day;
    function __construct()
    {
        //Récupère la ville selectionnée, sinon selectionne Marseille de base
        if (isset($_SESSION['city']))
        {
        $this->city = $_SESSION['city'];
        } else {
            $this->city = 'Marseille';
        }

        if (isset($_SESSION['day']))
        {
        $this->day = $_SESSION['day'];
        } else {
            $this->day = '0';
        }
    }
    //Renvoie la lattitude et la longitude de la ville selectionée
    function geocoding()
    {
    
        $curl = curl_init("http://api.openweathermap.org/geo/1.0/direct?q={$this->city}&appid=819deea8facec2eb186e0d8c87bc0319");
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1,
        ]);
        $data = curl_exec($curl);
        $data = json_decode($data, true);
        $coordinate = [];

        if ($data === false || curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) 
        {
            echo 'Erreur';
        } else {
            
                $coordinate['lat'] = $data[0]['lat'];
                $coordinate['lon'] = $data[0]['lon'];
                return($coordinate);
            
        }
    }
    //Renvoie un tableau coordinate avec la temperature et la description de la ville selectionée
    function weatherIs ()
    {
        $coordinate = $this->geocoding(); //Recupère la longitude et lattitude
        $dt = new DateTime('now', new DateTimeZone ('Europe/Paris'));
        $curl = curl_init("https://api.openweathermap.org/data/3.0/onecall?lat={$coordinate['lat']}&lon={$coordinate['lon']}&units=metric&lang=fr&date={$dt->format('Y-m-d')}&appid=819deea8facec2eb186e0d8c87bc0319");
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1,
        ]);
        $data = curl_exec($curl);
        $data = json_decode($data, true);
        $condition = [];

        if ($data === false || curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) 
        {
            echo 'Erreur';
        } else {
            $condition['id']=$data['daily'][$this->day]['weather'][0]['id'];
            $condition['temperature']=$data['daily'][$this->day]['temp']['day'];
            $condition['description']=$data['daily'][$this->day]['weather'][0]['description'];
            return $condition;
        }
    }
}