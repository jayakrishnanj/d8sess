<?php

namespace Drupal\page_example\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;

// *** Define your host, username, and password.
define('FTP_HOST', 'ps530449.dreamhostps.com');
define('FTP_USER', 'tdftp1');
define('FTP_PASS', 'vYJw8?Zn');

/**
 * PageExampleController Class to mention the simple class.
 */
class PageExampleController extends ControllerBase {

  /**
   * Description method to display simple information.
   */
  public function description() {

    // Check if a file exist
    // the path where the file is located.
    $path = "/d8files/";

    // Connect and login to FTP server.
    $ftp_conn = ftp_connect(FTP_HOST) or die("Could not connect to FTP_HOST");
    $login = ftp_login($ftp_conn, FTP_USER, FTP_PASS);

    ftp_pasv($ftp_conn, TRUE);

    $contents_on_server = ftp_nlist($ftp_conn, $path);
    // Close connection.
    ftp_close($ftp_conn);

    foreach ($contents_on_server as $afile) {
      $file = str_replace($path, '', $afile);

      $data   = file_get_contents("ftp://" . FTP_USER . ":" . FTP_PASS . "@"
        . FTP_HOST . $path . $file);
      $file = file_save_data($data, 'public://' . $file, FILE_EXISTS_REPLACE);
      // Create node object with attached file.
      $node = Node::create([
        'type'        => 'page',
        'title'       => 'File uploaded with FID ' . $file->id() ,
        'field_ftpfile' => [
          'target_id' => $file->id(),
        ],
      ]);
      $node->save();

    }

    $build = [
      '#markup' => t('Node created with File attachment.'),
    ];
    return $build;
  }

  /**
   * Simple method to display simple information.
   */
  public function simple() {

    // Print '<pre>'.print_r($file_list, TRUE).'</pre>';.
    return ['#markup' => t('Node created')];
  }

}
