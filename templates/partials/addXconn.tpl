<div id="connections" class="section" style="display: none">
	<h3>Create a Device Cross Connect</h3>
	<form action="/xconn/add" method="post"> 
		<table>
			<tr>
				<th>Listen Side</th>
				<th>Connect Side</th>
			</tr>
			<tr>
				<td>
					<select name="DEV_LISTEN">
					<?php foreach($devices as $aside): ?>
					    <option value="<?php echo $aside['DEVNAME']; ?>"><?php echo $aside['DEVNAME']; ?></option>
					<?php endforeach; ?>
					</select>
				</td>
				<td>
					<select name="DEV_CONNECT">
					<?php foreach($devices as $bside): ?>
						<option value="<?php echo $bside['DEVNAME']; ?>"><?php echo $bside['DEVNAME']; ?></option>
					<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</table>
		<input type="submit" name="submit" value="Submit" />
	</form>
	
		<h3>List Device Cross Connects</h3>
		<table>
			<tr>
				<th>VLAN</th>
				<th>Listen Side</th>
				<th>Connect Side</th>
				<th>Delete Cross Connect</th>
			</tr>
		<?php foreach($xconns as $xconn): ?>
			<tr>
				<td><?php echo $xconn['VLAN_ID']; ?></td>
				<td><?php echo $xconn['DEV_LISTEN']; ?></td>
				<td><?php echo $xconn['DEV_CONNECT']; ?></td>
				<td><a href="/xconn/delete/<?php echo $xconn['VLAN_ID']; ?>">Delete</a></td>
			</tr>
		<?php endforeach;?>
		</table>
</div>