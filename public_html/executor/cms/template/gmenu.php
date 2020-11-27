
		<ul class="box">
			<li><a href="index.php"><span>CMS管理</span></a></li>
<?php foreach ($resCMS as $key => $val) { ?>
			<li<?php if (isset($cms) && $cms == $val['system_id']) { ?> id="menu-active"<?php } ?>><a href="../cms/list.php?cms=<?php echo escapeHtml($val['system_id']) ?>"><span><?php echo escapeHtml($val['system_title']) ?>管理</span></a></li>
<?php } ?>
		</ul>
