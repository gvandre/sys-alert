<?php
  include_once('../core.php ');

  (new Core())->formatQuery(
    array(
      'executeStament'=> function ($body, $firebase) {
        return $firebase->getValue();
      },
      'onSuccess'=> function($output) {
        header('HTTP/1.1 200 Ok');
        echo json_encode(
          array(
            'code' => 0,
            'message' => 'Proceso ejecutado',
            'data' => $output
          )
        );
      }
    )
  )
?>