<h1>Delete competition scores</h1>

<form action="?" method="post" >
<fieldset>
    <legend>Confirm delete</legend>
    <p>Are you sure you want to delete the scores for <?=__E($competition->name)?>?</p>

  </fieldset>
   <p><input type=hidden name="competition[confirm]" value="1" />
   <input class="button" type="submit" value="Yes"> <a class="button_link" href="<?=_BASE?>/settings/competitions">No</a></p>
</form>