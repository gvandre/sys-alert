<?php
  include_once('../core.php ');

  (new Firebase())->formatQuery(
    array(
      'executeStament'=> function ($body, $firebase) {
        return $firebase->getValues();
      },
      'onSuccess'=> function($output) {
        header('HTTP/1.1 200 Ok');
        echo json_encode(
          array(
            'code' => 0,
            'message' => 'Get all values',
            'data' => $output
          )
        );
      }
    )
  )
?>