<div id="config" class="section" style="display:none">
    
		<h3>Generate Configuration</h3>
		<p>| <a href="#top">Back to Top</a> |</p>
		<textarea rows="50" cols="100">
<?php

foreach($devices as $device) {

	?>
# <?php echo $device['DEVNAME']; ?>

/bin/qemu-kvm -cpu kvm64 -nographic -m 2548 -hda /var/lib/libvirt/images/<?php echo $device['DEVNAME']; ?>-<?php echo $device['DEVTYPE']; ?>.raw \
 -serial telnet::81<?php echo $device['DEV_ID']; ?>,server,nowait \
 -net nic,model=virtio,vlan=1000,macaddr=00:01:00:ff:66:<?php echo $device['DEV_ID']; ?> \
<?php
	foreach($xconns as $xconn) {
		if($xconn['DEV_LISTEN'] == $device['DEVNAME']) {
?>
 -net nic,model=virtio,vlan=<?php echo $xconn['VLAN_ID']; ?>,macaddr=00:01:00:ff:<?php echo $device['DEV_ID']; ?>:<?php echo $xconn['VLAN_ID']; ?> \
 -net socket,vlan=<?php echo $xconn['VLAN_ID']; ?>,listen=127.0.0.1:70<?php echo $xconn['VLAN_ID']; ?> \
<?php
		}
		if($xconn['DEV_CONNECT'] == $device['DEVNAME']) {
?>
 -net nic,model=virtio,vlan=<?php echo $xconn['VLAN_ID']; ?>,macaddr=00:01:00:ff:<?php echo $device['DEV_ID']; ?>:<?php echo $xconn['VLAN_ID']; ?> \
 -net socket,vlan=<?php echo $xconn['VLAN_ID']; ?>,connect=127.0.0.1:70<?php echo $xconn['VLAN_ID']; ?> \
<?php
		}
	}
?>
 -net tap,ifname=tap<?php echo $device['DEV_ID']; ?>,vlan=1000,script=no &
sleep 2
<?php
}
?>
		</textarea>
</div>