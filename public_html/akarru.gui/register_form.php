<p>
<?= $bl_register_instructions ?>
</p>
<table border="0" cellpadding="4" cellspacing="2">
	<tr><td><?= $bl_user ?></td><td><input type="text" id="user" name="user" size="20" /></td><td class="error"><?= $bm_error_user ?></td></tr>
	<tr><td><?= $bl_password ?></td><td><input type="password" id="pass" name="pass" size="20" /></td><td class="error"><?= $bm_error_pass ?></td></tr>
	<tr><td><?= $bl_confirm_password ?></td><td><input type="password" id="confirm_pass" name="confirm_pass" size="20" /></td><td class="error"><?= $bm_error_confirm_pass ?></td></tr>
	<tr><td>Email:</td><td><input type="text" id="email" name="email" size="20" /></td><td class="error"><?= $bm_error_email ?></td></tr>
	<tr><td></td><td colspan="2" align="left"><input type="submit" value="<?= $bl_register_submit?>" /></td></tr>
</table>
<hr />
<p><a href="recover_pass.php"><?= $bl_recover_pass ?></a></p>

