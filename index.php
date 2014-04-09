<?php
require_once('Database.class.php');
define('DB_SERVER', 'localhost');
define('DB_USER', 'dbuser');
define('DB_PASS', 'dbpassword');
define('DB_DATABASE', 'virtnetlab');

# Temporary Hypervisor Variable
$hypervisor = "hyper1";

$db = Database::obtain(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Virtual Network Lab Config Generator</title>
		<link type="text/css" rel="stylesheet" href="stylesheet.css" />
	</head>
	<body>
	<a name="top"></a>
	<div>
		<p style="text-align: center;">| <a href="/">Home</a> | <a href="/?c=reset">Reset VLAN and Device ID</a> | <a href="#add_device">Add a Device</a> | <a href="#list_devices">List Devices</a> | <a href="#create_xconnect">Create a Device Cross Connect</a> | <a href="#list_xconnect">List Device Cross Connects</a> | <a href="#kvm_config">View KVM Configuration</a> |</p>
		<h1>Virtual Network Lab Config Generator</h1>
		<p>The "Virtual Network Lab Config Generator" is a simple web application that generates the KVM Hypervisor configuration so that you can easily and quickly spin up complex virtual networks utilizing the Cisco IOS-XR (XRv) and IOS-XE (CSR1000v) images. Both images are available from the <a href="http://www.cisco.com/" target="_blank">Cisco</a> Website. This will allow you to spend more time learning about networking and less time building the configurations that support Cisco studies. The code is available for download on <a href="https://github.com/jtdub/vnlcg" target="_blank">Github.</a></p>
		<a name="add_device"></a>
		<h3>Add a Device</h3>
		<p>| <a href="#top">Back to Top</a> |</p>
		<form action="?c=add#add_device" name="ADD_DEV" method="post">
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
						</select>
					</td>
				</tr>
			</table>
		<input type="submit" name="submit" value="Submit" />
		</form> 
	
		<a name="list_devices"></a>
		<h3>List Devices</h3>
		<p>| <a href="#top">Back to Top</a> |</p>
		<table>
			<tr>
				<td>Device ID</td>
				<td>Device Name</td>
				<td>Device Type</td>
				<td>Access Device</td>
				<td>Delete Device</td>
			</tr>
			<?php
			$sql = "SELECT * FROM DEVICE";
			$get_devices = $db->query($sql);
			while($device = $db->fetch($get_devices)) {
				?>
				<tr>
					<td><?php echo $device['DEV_ID']; ?></td>
					<td><?php echo $device['DEVNAME']; ?> </td>
					<td><?php echo $device['DEVTYPE']; ?> </td>
					<td><a href="telnet://<?php echo $hypervisor; ?>:81<?php echo $device['DEV_ID']; ?>">Connect</td>
					<td><a href="?d=<?php echo $device['DEV_ID']; ?>">Delete</td>
				</tr>
				<?php
			}
			?>
		</table>
		<a name="create_xconnect"></a>
		<h3>Create a Device Cross Connect</h3>
		<p>| <a href="#top">Back to Top</a> |</p>
		<form action="?c=xconnect#create_xconnect" name="ADD_XCONNECT" method="post"> 
			<table>
				<tr>
					<td>Listen Side</td>
					<td>Connect Side</td>
				</tr>
				<tr>
					<td>
						<select name="DEV_LISTEN">
						<?php
						$sql = "SELECT * FROM DEVICE";
						$get_a = $db->query($sql);
						while ($aside = $db->fetch($get_a)) {
							?>
							<option value="<?php echo $aside['DEVNAME']; ?>"><?php echo $aside['DEVNAME']; ?></option>
							<?php
						}
						?>
						</select>
					</td>
					<td>
						<select name="DEV_CONNECT">
						<?php
						$get_b = $db->query($sql);
						while ($bside = $db->fetch($get_b)) {
							?>
							<option value="<?php echo $bside['DEVNAME']; ?>"><?php echo $bside['DEVNAME']; ?></option>
							<?php
						}
						?>
						</select>
					</td>
				</tr>
			</table>
			<input type="submit" name="submit" value="Submit" />
		</form>
		<a name="list_xconnect"></a>
		<h3>List Device Cross Connects</h3>
		<p>| <a href="#top">Back to Top</a> |</p>
		<table>
			<tr>
				<td>VLAN</td>
				<td>Listen Side</td>
				<td>Connect Side</td>
				<td>Delete Cross Connect</td>
			</tr>
			<?php
			$sql = "SELECT * FROM XCONNECT";
			$get_xcon = $db->query($sql);
			while($xconnects = $db->fetch($get_xcon)) {
				?>
				<tr>
					<td><?php echo $xconnects['VLAN_ID']; ?></td>
					<td><?php echo $xconnects['DEV_LISTEN']; ?></td>
					<td><?php echo $xconnects['DEV_CONNECT']; ?></td>
					<td><a href="?dx=<?php echo $xconnects['VLAN_ID']; ?>">Delete</a></td>
				</tr>
				<?php
			}
			?>
		</table>
		<a name="kvm_config"></a>
		<h3>Generate Configuration</h3>
		<p>| <a href="#top">Back to Top</a> |</p>
		<textarea rows="50" cols="100">
<?php
$dev_sql = "SELECT * FROM DEVICE";
$devrows = $db->query($dev_sql);

while($devices = $db->fetch($devrows)) {
	?>
# <?php echo $devices['DEVNAME']; ?>

/bin/qemu-kvm -cpu kvm64 -nographic -m 2548 -hda /var/lib/libvirt/images/<?php echo $devices['DEVNAME']; ?>-<?php echo $devices['DEVTYPE']; ?>.raw \
 -serial telnet::81<?php echo $devices['DEV_ID']; ?>,server,nowait \
 -net nic,model=virtio,vlan=1000,macaddr=00:01:00:ff:66:<?php echo $devices['DEV_ID']; ?> \
<?php
	$xconn_sql = "SELECT * FROM XCONNECT";
	$xconnrows = $db->query($xconn_sql);
	while($xconnects = $db->fetch($xconnrows)) {
		if($xconnects['DEV_LISTEN'] == $devices['DEVNAME']) {
?>
 -net nic,model=virtio,vlan=<?php echo $xconnects['VLAN_ID']; ?>,macaddr=00:01:00:ff:<?php echo $devices['DEV_ID']; ?>:<?php echo $xconnects['VLAN_ID']; ?> \
 -net socket,vlan=<?php echo $xconnects['VLAN_ID']; ?>,listen=127.0.0.1:70<?php echo $xconnects['VLAN_ID']; ?> \
<?php
		}
		if($xconnects['DEV_CONNECT'] == $devices['DEVNAME']) {
?>
 -net nic,model=virtio,vlan=<?php echo $xconnects['VLAN_ID']; ?>,macaddr=00:01:00:ff:<?php echo $devices['DEV_ID']; ?>:<?php echo $xconnects['VLAN_ID']; ?> \
 -net socket,vlan=<?php echo $xconnects['VLAN_ID']; ?>,connect=127.0.0.1:70<?php echo $xconnects['VLAN_ID']; ?> \
<?php
		}
	}
?>
 -net tap,ifname=tap<?php echo $devices['DEV_ID']; ?>,vlan=1000,script=no &
sleep 2
<?php
}
?>
		</textarea>
	</div>
	</body>
</html>

<?php

if($_GET['c'] == "add") {
	$add['DEVNAME'] = $_POST['DEVNAME'];
	$add['DEVTYPE'] = $_POST['DEVTYPE'];

	$db->insert("DEVICE", $add);
}

if(isset($_GET['d'])) {
	$sql = "DELETE FROM DEVICE WHERE DEV_ID='{$_GET['d']}'";
	$db->query($sql);
}

if(isset($_GET['dx'])) {
	$sql = "DELETE FROM XCONNECT WHERE VLAN_ID='{$_GET['dx']}'";
	$db->query($sql);
}

if($_GET['c'] == "xconnect") {
	$xconnect['DEV_CONNECT'] = $_POST['DEV_CONNECT'];
	$xconnect['DEV_LISTEN'] = $_POST['DEV_LISTEN'];

	$db->insert("XCONNECT", $xconnect);
}

if($_GET['c'] == "reset") {
	$devsql = "ALTER TABLE DEVICE AUTO_INCREMENT = 10";
	$xconsql = "ALTER TABLE XCONNECT AUTO_INCREMENT = 10";

	$db->query($devsql);
	$db->query($xconsql);
}
$db->close();
