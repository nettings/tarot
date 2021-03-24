<?php

define('INC', __DIR__ . '/includes');
define('HTM', __DIR__ . '/html');
require_once(INC . '/globals.php');
require(INC . '/card_devices.php');
require(INC . '/images.php');
require(INC . '/write_cmd.php');
require(INC . '/tarot_state.php');


$state = tarot_state::restore(STATEFILE);
if (!$state) $state = new tarot_state();


$state->set_caller_ip($_SERVER['REMOTE_ADDR']);
$state->set_image_list(get_images());
$state->set_device_list(get_card_devices());

$state->store(STATEFILE);

//var_dump($state);


include(HTM . '/header.php');
?>
  <div class="one-third column">
    <h3>Image files</h3>
    <form id="image">
      <fieldset>
        <legend>Choose an image file to duplicate:</legend>
        <table>
          <tr>
            <th> </th>
            <th>Image file</th>
            <th>File size</th>
          </tr>
<?php
    foreach($state->get_image_list() as $n => $img) {
        $i = $img['name'];
        $s = sprintf('%1.3f', ((float)$img['size'] * B2GIB));
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
      <fieldset>
        <input type="submit" name="scan" value="Rescan for images" />
      </fieldset>  
    </form>
  </div>
  <div class="one-third column">
    <h3>Writer devices</h3>
    <form id="writers">
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
    foreach($state->get_device_list() as $n => $writer) {
        $w = $writer['path'];
        $s = sprintf('%1.3f', ((float)$writer['size'] *B2GIB));
        $x = ($writer['size'] >= 16*(2**30)) ? $writer['status'] : 'overflow';
        $d = ($x != 'ok') ? 'disabled="disabled"' : '';
?>
          <tr class="<?=$x?>">
            <td>
              <input type="checkbox" name="writer" value="<?=$n?>" <?=$d?> />
            </td>
            <td><?=$w?></td>
            <td><?=$s?> GiB</td>
            <td><?=$x?></td>
          </tr>
<?php
    }
?>
        </table>
      </fieldset>      
      <fieldset>
        <input type="submit" name="scan" value="Rescan for writers" />
      </fieldset>
    </form>
  </div>
  <div class="one-third column">
    <h3>Actions</h3>
    <form id="actions">
      <fieldset>
         <input type="button" name="write" disabled="disabled" value="Write !" /><br />
         <input type="button" name="parttable" id="parttable" value="Re-read partition table (slow)" /><br />
      </fieldset>
    </form>
    <h4>Current command:</h4>
    <pre><code><?=$cmd?></code></pre>
  </div>
<?php 
include(HTM . '/footer.php');
