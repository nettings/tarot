<?php

define('INC', __DIR__ . '/includes');
define('HTM', __DIR__ . '/html');
require_once(INC . '/globals.php');
require(INC . '/card_devices.php');
require(INC . '/images.php');
require(INC . '/write_cmd.php');

include(HTM . '/header.php');
//include(HTM . '/content.php');


//$size = 12 * (2 ** 30);
$writers = get_card_devices($size);
//print_r($devices);
//print_r($sd_writers);
$images = get_images();
//print_r($images);
//print(make_write_cmd($images[0], $sd_writers));
?>
  <div class="one-third column">
    <h3>Image files</h3>
    <form>
      <fieldset>
        <legend>Choose an image file to duplicate:</legend>
<?php
    foreach($images as $img) {
        $i = $img['name'];
        $s = sprintf('%1.3f', ($img['size'] * B2GIB));
?>
        <input type="radio" name="image" value="<?=$i?>" />
        <?=$i?> (<?=$s?> GiB)
        <br />
<?php
    }
?>
      </fieldset>
    </form>
  </div>
  <div class="one-third-column">
    <h3>Writer devices</h3>
    <form>
      <fieldset>
        <legend>Select writer devices to use:</legend>
        <table>
          <tr>
            <th><input type="checkbox" name="allwriters" id="allwriters" /> all</th>
            <th>Device</th>
            <th>Media size</th>
            <th>Status</th>
          </tr>
<?php
    foreach($writers as $writer) {
        $w = $writer['path'];
        $s = sprintf('%1.3f', ($writer['size'] *B2GIB));
?>
          <tr>
            <td>
              <input type="checkbox" name="<?=$w?>" value="<?=$w?>" />
            </td>
            <td><?=$w?></td>
            <td><?=$s?> GiB</td>
            <td>unknown</td>
          </tr>
            
<?php
    }
?>  
        </table>
      </fieldset>      
    </form>
  </div>
<?php 
include(HTM . '/footer.php');
?>