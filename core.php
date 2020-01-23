<?php
  // required headers
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  
  require __DIR__.'/vendor/autoload.php';
  

  use Kreait\Firebase\Factory;
  
  class Core {
    private $firebase;
    private $config;

    function __construct() {
      $this->config = require(__DIR__."/utils/config.php");
      try {
        $serviceAccount = __DIR__.$this->config['fileConfig'];
        $factory = (new Factory)
          ->withServiceAccount(__DIR__.$this->config['fileConfig'])
          ->withDatabaseUri($this->config['urlProject']);
        $this->firebase = $factory->createDatabase();
      } catch (Throwable $th) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(
          array(
            'code' => -1,
            'message' => 'Problemas al conectarse al servidor.',
            'error' => 'Could not connect with firebase'
          )
        );
        exit();
      }
    }

    function getBodyRequest() {
      $body = array();
      $phpInput = array();
      $request = file_get_contents('php://input');
      parse_str($request, $phpInput);
      // Permite que $req al momento de codificar, sea un array.
      $req = json_decode($request, true);
  
      $body = $req ? $req : $phpInput;
      return $body;
    }

    public function formatQuery ($payload) {
      // $database = $this->firebase->getDatabase();
      $reference = $this->firebase->getReference($this->config['nodedb']);
      $body = $this->getBodyRequest();
      try {
        $result = $payload['executeStament']($body, $reference);
      } catch (Throwable $th) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(
          array(
            'code' => -1,
            'message' => 'Problemas en el proceso, verificar documentacion.',
            'error' => 'Request with errors, check documentation.',
            "sa" => $th
          )
        );
        exit();
      }

      if(array_key_exists("onSuccess", $payload)) {
        $payload["onSuccess"]($result);
      } else {
        echo json_encode($result);
      }

      unset($options);
      unset($output);
    }
  }
?>