<?php
  include_once('../core.php ');

  (new Firebase())->formatQuery(
    array(
      'executeStament'=> function ($body, $firebase) {
        $dv1 = isset($body['gas']) ? $body['gas'] : '';
        $dv2 = isset($body['temp']) ? $body['temp'] : '';
        $dv3 = isset($body['hum']) ? $body['hum'] : '';

        if ($dv1 == '' || $dv2 == '' || $dv3 == '') {
          header('HTTP/1.1 412 Precondition Failed');
          echo json_encode(
            array(
              'code' => 1,
              'message' => 'Parameters [sensor1, sensor2, sensor3] are required',
              'error' => 'Parameters required',
            )
          );
          exit();
        }

        return $firebase->push(array(
          'gas' => $dv1,
          'temp' => $dv2,
          'hum' => $dv3,
          'createdAt' => date("Y-m-d H:i:s")
        ))->getKey();
      },
      'onSuccess'=> function($output) {
        if (is_null($output)) {
          header('HTTP/1.1 406 Not Acceptable');
          echo json_encode(
            array(
              'code' => 1,
              'message' => 'Process error',
              'error' => 'the values ​​could not be saved',
            )
          );
          exit();
        }

        header('HTTP/1.1 201 Created');
        echo json_encode(
          array(
            'code' => 0,
            'message' => 'Saved successfully',
            'data' => $output
          )
        );
      }
    )
  )
?>