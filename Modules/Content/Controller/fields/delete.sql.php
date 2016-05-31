<?php
defined('THINK_PATH') or exit('No permission resources.');

$this->db->query("ALTER TABLE `$tablename` DROP `$field`");
?>