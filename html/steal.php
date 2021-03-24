<div class="row">
  <div class="twelve columns">
    <h3>Your IP address does not match the current session.</h3>
  </div>
</div>
<div class="row">
  <div class="two-thirds column">
    <div>
      The previous session was created from
      <code><?=$state->get_caller_ip()?></code>.<br />
      Your current address is <code><?=$caller_ip?></code>.<br />
      <?=PROGNAME?> can only be used by one person at a time. 
    </div>
    <div>
      If you know that
      nobody else is currently working with <?=PROGNAME?>, you can 
      <strong>steal the currently running session</strong>.
    </div>
  </div>
  <div class="one-third column">
    <form method="post">
      <fieldset>
        <input type="submit" name="steal" value="Steal the session!"/>
      </fieldset>
    </form>
  </div>
</div>
