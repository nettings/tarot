<?php

define('INC', __DIR__ . '/includes');
define('HTM', __DIR__ . '/html');
require_once(INC . '/globals.php');
require(INC . '/card_devices.php');
require(INC . '/images.php');
require(INC . '/write_cmd.php');

//$size = 12 * (2 ** 30);
$writers = get_card_devices($size);
//print_r($devices);
//print_r($writers);
$images = get_images();
$cmd = make_write_cmd($images[0], $writers);

include(HTM . '/header.php');
?>
  <div class="one-third column">
    <h3>Image files</h3>
    <form>
      <fieldset>
        <legend>Choose an image file to duplicate:</legend>
        <table>
          <tr>
            <th> </th>
            <th>Image file</th>
            <th>File size</th>
          </tr>
<?php
    foreach($images as $n => $img) {
        $i = $img['name'];
        $s = sprintf('%1.3f', ($img['size'] * B2GIB));
?>
          <tr>
            <td><input type="radio" name="image" value="<?=$n?>" /></td>
            <td><?=$i?></td>
            <td><?=$s?> GiB</td>
          </tr>
<?php
    }
?>
        </table>
      </fieldset>
    </form>
  </div>
  <div class="one-third column">
    <h3>Writer devices</h3>
    <form>
      <fieldset>
        <legend>Select writer devices to use:</legend>
        <table>
          <tr>
            <th><input type="checkbox" id="selectAllWriters" /> all</th>
            <th>Device</th>
            <th>Media size</th>
            <th>Status</th>
          </tr>
<?php
    foreach($writers as $n => $writer) {
        $w = $writer['path'];
        $s = sprintf('%1.3f', ($writer['size'] *B2GIB));
?>
          <tr>
            <td>
              <input type="checkbox" name="<?=$w?>" value="<?=$n?>" />
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
  <div class="one-third column">
    <h3>Actions</h3>
    <form>
      <fieldset>
         <input type="button" name="write" id="write" disabled="disabled" value="Write !" /><br />
         <input type="button" name="parttable" id="parttable" value="Re-read partition table (slow)" /><br />
      </fieldset>
    </form>
    <h4>Current command:</h4>
    <pre><code><?=$cmd?></code></pre>
  </div>
<?php 
include(HTM . '/footer.php');
?>