<?php
  include_once('../core.php ');

  (new Firebase())->formatQuery(
    array(
      'executeStament'=> function ($body, $firebase) {
        $dv1 = isset($body['sensor1']) ? $body['sensor1'] : '';
        $dv2 = isset($body['sensor2']) ? $body['sensor2'] : '';
        $dv3 = isset($body['sensor3']) ? $body['sensor3'] : '';

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
          'value1' => $dv1,
          'value2' => $dv2,
          'value3' => $dv3
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