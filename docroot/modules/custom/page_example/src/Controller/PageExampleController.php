<?php

namespace Drupal\page_example\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
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
    $simple_link = Url::fromRoute('page_example_simple')->toString();
    $arguments_url = Url::fromRoute('page_example_description', [], ['absolute' => TRUE]);
    $arguments_link = Link::fromTextAndUrl(t('arguments page'), $arguments_url)->toString();
    $build = [
      '#markup' => t('The Page example module provides two pages, 
    "@simple" and "@arguments".', [
      '@simple_link' =>
      $simple_link, '@arguments_link' => $arguments_link,
      '@arguments_url' => $arguments_url->toString(),
    ]),
    ];
    return $build;
  }

  /**
   * Simple method to display simple information.
   */
  public function simple() {

    // The server you wish to connect to - you can also use the server ip.
    $ftp_server = "ps530449.dreamhostps.com";

    $ftp_username  = 'tdftp1';
    $ftp_userpass = 'vYJw8?Zn';

    // Check if a file exist
    // the path where the file is located.
    $path = "/d8files/";

    // The file you are looking for.
    $file = "Amazon.pdf";

    // Connect and login to FTP server.
    $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
    $login = ftp_login($ftp_conn, $ftp_username, $ftp_userpass);

    // Get the file list for /.
    $filelist = ftp_rawlist($ftp_conn, "/");

    // Close connection.
    ftp_close($ftp_conn);

    // Output $filelist.
    var_dump($filelist);

    $data   = file_get_contents("ftp://" . $ftp_username . ":" . $ftp_userpass . "@"
      . $ftp_server . $path . $file);

    $file = file_save_data($data, 'public://' . $file, FILE_EXISTS_REPLACE);
    // Create node object with attached file.
    $node = Node::create([
      'type'        => 'page',
      'title'       => 'Druplicon test',
      'field_ftpfile' => [
        'target_id' => $file->id(),
      ],
    ]);
    $node->save();

    // Print '<pre>'.print_r($file_list, TRUE).'</pre>';.
    return ['#markup' => t('Node created')];
  }

}
