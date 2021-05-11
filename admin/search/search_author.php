<?php
require_once('../../config/config.php');
require_once('functions.php');
require_once('../../config/check_cookie.php');
if (!empty($_POST['search'])) {
	$search = trim(htmlspecialchars($_POST['search']));
 
	$sth = $connectSearch->prepare("SELECT id, name FROM `users` WHERE `name` LIKE '{$search}%' ORDER BY `name`");
	$sth->execute();
	$result = $sth->fetchAll(PDO::FETCH_ASSOC);
 
	if ($result) {
		?>
		<div class="search_result">
			<table>
				<?php foreach ($result as $row): ?>
				<tr>
					<td >
						<a class="search_result-name" data-id="<?php echo $row['id']; ?>" href="#"><?php echo $row['name']; ?></a>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
		</div>
		<?php
	}
}