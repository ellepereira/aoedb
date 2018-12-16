<?php
function getPlayerLevel($points)
{
    return min(max(1, ceil(pow($points, 0.4))), 40);
}

function start_tooltip()
{
    ?>
  <table class="tooltip">
  <tr><td class="topleft"></td><td class="top"></td><td class="topright"></td></tr>
  <tr><td class="left"></td><td class="mid">
<?php
}

function end_tooltip()
{
    ?>
  </td><td class="right"></td></tr>
  <tr><td class="botleft"></td><td class="bot"></td><td class="botright"></td></tr>
  </table>
<?php
}

function make_tooltip()
{
    ?>
  <div class="edge top"></div>
  <div class="edge bottom"></div>
  <div class="edge left"></div>
  <div class="edge right"></div>

  <div class="corner topLeft"></div>
  <div class="corner topRight"></div>
  <div class="corner bottomLeft"></div>
  <div class="corner bottomRight"></div>
<?php }