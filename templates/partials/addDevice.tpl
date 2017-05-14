<div id="devices" class="section" style="display:none">
	<h3>Add a Device</h3>
	<form action="/device/add" name="ADD_DEV" method="post">
		<table>
			<tr>
				<td>Device Name</td>
				<td>Device Type</td>
			</tr>
			<tr>
				<td><input type="text" name="DEVNAME" /></td>
				<td>
					<select name="DEVTYPE">
						<option value="xr">IOS-XR</option>
						<option value="xe">IOS-XE</option>	
						<option value="nx">NX-OS</option>
					</select>
				</td>
			</tr>
		</table>
	<input type="submit" name="submit" value="Submit" />
	</form> 
	<h3>List Devices</h3>
	<table>
		<tr>
			<td>Device ID</td>
			<td>Device Name</td>
			<td>Device Type</td>
			<td>Access Device</td>
			<td>Delete Device</td>
		</tr>
		<?php foreach($devices as $device): ?>
			<tr>
				<td><?php echo $device['DEV_ID']; ?></td>
				<td><?php echo $device['DEVNAME']; ?> </td>
				<td><?php echo $device['DEVTYPE']; ?> </td>
				<td><a href="telnet://<?php echo $hypervisor; ?>:81<?php echo $device['DEV_ID']; ?>">Connect</td>
				<td><a href="/device/delete/<?php echo $device['DEV_ID']; ?>">Delete</td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>