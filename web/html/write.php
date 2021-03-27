<div class="row">
  <div class="twelve columns">
    <h3>Writing...</h3>
  </div>
</div>
<div class="row">
  <div class="ten columns">
    <p id="job_info">
      Selected image:
<?php
print('  ' . $state->get_image_list()[$state->get_selected_image()]['name']);
?>
      <br />
      Target devices:
<?php
foreach($state->get_device_list() as $n => $device) {
        if ($state->device_is_selected($n))
              print('  ' . $device['path']);
}
?>
    </p>
    <div id="progress_bg">
      <div id="progress">
        <div id="progress_bar">0%</div>
      </div>
      <p id="status">
        Waiting for progress data...
      </p>
      <p id="time_remaining">
        No stats available.
      </p>
    </div>
  </div>
  <div class="two columns">
    <form method="post">
      <fieldset>
        <input id="back_button" type="submit" value="Back to main page" title="disabled while writing" />
      </fieldset>
    </form>
  </div>
  <script type="text/javascript">
updateProgress();
  </script>
</div>    
