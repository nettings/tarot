<div class="row">
  <div class="twelve columns">
    <h3>Your IP address does not match the current session.</h3>
  </div>
</div>
<div class="row">
  <div class="two-thirds column">
    <p>
      The previous session was created from
      <code><?=$state->get_caller_ip()?></code> <?=$lastmod?>.<br />
      Your current address is <code><?=$caller_ip?></code>.
    </p>
    <p>
      Since <?=PROGNAME?> depends on hardware resources, only one person can use an instance at the same time.<br />
      If you know that nobody else is currently working with this <?=PROGNAME?> installation, you can
      <strong>steal the running session</strong>.
    </p>
  </div>
  <div class="one-third column">
    <form method="post">
      <fieldset>
        <input type="submit" name="steal" value="Steal the session!"/>
      </fieldset>
    </form>
  </div>
</div>
