vnlcg
=====

Virtual Network Lab Config Generator

Install

mysql -u <username> -h <hostname> -p virtnetlab < virtlab.sql

Edit the following variables in index.php:

define('DB_SERVER', 'localhost');

define('DB_USER', 'dbuser');

define('DB_PASS', 'dbpassword');

define('DB_DATABASE', 'virtnetlab');


From there, you are good to go. You can create devices and create cross connects between the devices, which will generate a KVM config that looks like:


/bin/qemu-kvm -cpu kvm64 -nographic -m 2548 -hda /var/lib/libvirt/images/a-xr.raw \
 -serial telnet::8110,server,nowait \
 -net nic,model=virtio,vlan=1000,macaddr=00:01:00:ff:66:10 \
 -net nic,model=virtio,vlan=10,macaddr=00:01:00:ff:10:10 \
 -net socket,vlan=10,listen=127.0.0.1:7010 \
 -net nic,model=virtio,vlan=11,macaddr=00:01:00:ff:10:11 \
 -net socket,vlan=11,connect=127.0.0.1:7011 \
 -net nic,model=virtio,vlan=13,macaddr=00:01:00:ff:10:13 \
 -net socket,vlan=13,listen=127.0.0.1:7013 \
 -net nic,model=virtio,vlan=13,macaddr=00:01:00:ff:10:13 \
 -net socket,vlan=13,connect=127.0.0.1:7013 \
 -net nic,model=virtio,vlan=14,macaddr=00:01:00:ff:10:14 \
 -net socket,vlan=14,listen=127.0.0.1:7014 \
 -net nic,model=virtio,vlan=14,macaddr=00:01:00:ff:10:14 \
 -net socket,vlan=14,connect=127.0.0.1:7014 \
 -net nic,model=virtio,vlan=15,macaddr=00:01:00:ff:10:15 \
 -net socket,vlan=15,listen=127.0.0.1:7015 \
 -net nic,model=virtio,vlan=15,macaddr=00:01:00:ff:10:15 \
 -net socket,vlan=15,connect=127.0.0.1:7015 \
 -net tap,ifname=tap10,vlan=1000,script=no \


/bin/qemu-kvm -cpu kvm64 -nographic -m 2548 -hda /var/lib/libvirt/images/b-xr.raw \
 -serial telnet::8111,server,nowait \
 -net nic,model=virtio,vlan=1000,macaddr=00:01:00:ff:66:11 \
 -net nic,model=virtio,vlan=10,macaddr=00:01:00:ff:11:10 \
 -net socket,vlan=10,connect=127.0.0.1:7010 \
 -net tap,ifname=tap11,vlan=1000,script=no \
 
Nifty, right!? It's very rough, with almost no validation and error checking, but can be very useful for generating complex topologies for IOS-XE CSR1000v and IOS-XR XRVR virtual images.
