<div class="row">
  <form method="post">
    <div class="one-third column">
      <h3>Image files</h3>
      <fieldset id="image">
        <legend>Choose an image file to duplicate:</legend>
        <table>
          <thead>
            <tr>
              <th> </th>
              <th>Image file</th>
              <th>File size</th>
            </tr>
          </thead>
          <tbody id="image_list">
<?php
foreach($state->get_image_list() as $n => $img) {
        $i = $img['name'];
        $s = sprintf('%1.3f', ((float)$img['size'] * B2GIB));
        $x = ($state->get_selected_image() == $n) ? ' checked="checked"' : '';
?>
            <tr>
              <td><input type="radio" name="image" value="<?=$n?>"<?=$x?> /></td>
              <td><?=$i?></td>
              <td><?=$s?> GiB</td>
            </tr>
<?php
}
?>
          </tbody>
        </table>
      </fieldset>
      <fieldset>
<?php if ($state->get_image_list()) { ?>
        <input type="submit" name="scanimg" value="Re-scan for images" />
<?php } else { ?>
        <input type="submit" name="scanimg" value="Scan for images" />
<?php } ?>
      </fieldset>  
    </div>
    <div class="one-third column">
      <h3>Writer devices</h3>
      <fieldset id="writers">
        <legend>Select writer devices to use:</legend>
        <table>
          <thead>
            <tr>
              <th><input type="checkbox" id="allwrt" /> all</th>
              <th>Device</th>
              <th>Media size</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="device_list">
<?php
$size = @$state->get_image_list()[$state->get_selected_image()]['size'];
foreach($state->get_device_list() as $n => $writer) {
        $w = $writer['path'];
        $s = sprintf('%1.3f', ((float)$writer['size'] *B2GIB));
        $f = $writer['status'];
        $d = ($f != 'ok') ? ' disabled="disabled"' : '';
        $x = ($state->device_is_selected($n)) ? ' checked="checked"' : '';
?>
            <tr class="<?=$f?>">
              <td>
                <input type="checkbox" name="writer_<?=$n?>" value="1"<?=$x?><?=$d?> />
              </td>
              <td><?=$w?></td>
              <td><?=$s?> GiB</td>
              <td><?=$f?></td>
            </tr>
<?php
}
?>
          </tbody>
        </table>
      </fieldset>      
      <fieldset>
<?php if ($state->get_device_list()) { ?>
        <input type="submit" name="confwrt" value="Confirm selection" />
        <input type="submit" name="scanwrt" value="Re-scan for writers" />
<?php } else { ?>
        <input type="submit" name="scanwrt" value="Scan for writers" />
<?php } ?>
      </fieldset>
    </div>
    <div class="one-third column">
      <h3>Actions</h3>
      <fieldset id="actions">
<?php if ($state->is_ready()) { ?>
         <input type="submit" name="write" value="Write !" /><br />
<?php } ?>
         <input type="submit" name="parttab" value="Re-read partition table (slow)" /><br />
         <input type="submit" name="reset" value="Forget session data" /><br />
      </fieldset>
      <h4>Current command:</h4>
      <pre><code><?=$cmd?></code></pre>
      <h4>Caller IP address:</h4>
      <pre><code><?=$state->get_caller_ip()?></code></pre>
    </div>
  </form>
</div>
