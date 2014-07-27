#!/bin/sh

# p1
/bin/qemu-kvm -cpu kvm64 -nographic -m 2548 -hda /var/lib/libvirt/images/p1-xr.raw \
 -serial telnet::8110,server,nowait \
 -net nic,model=virtio,vlan=1000,macaddr=00:01:00:ff:66:10 \
 -net nic,model=virtio,vlan=10,macaddr=00:01:00:ff:10:10 \
 -net socket,vlan=10,listen=127.0.0.1:7010 \
 -net nic,model=virtio,vlan=11,macaddr=00:01:00:ff:10:11 \
 -net socket,vlan=11,listen=127.0.0.1:7011 \
 -net nic,model=virtio,vlan=12,macaddr=00:01:00:ff:10:12 \
 -net socket,vlan=12,listen=127.0.0.1:7012 \
 -net tap,ifname=tap10,vlan=1000,script=no &
sleep 2
# p2
/bin/qemu-kvm -cpu kvm64 -nographic -m 2548 -hda /var/lib/libvirt/images/p2-xr.raw \
 -serial telnet::8111,server,nowait \
 -net nic,model=virtio,vlan=1000,macaddr=00:01:00:ff:66:11 \
 -net nic,model=virtio,vlan=10,macaddr=00:01:00:ff:11:10 \
 -net socket,vlan=10,connect=127.0.0.1:7010 \
 -net nic,model=virtio,vlan=13,macaddr=00:01:00:ff:11:13 \
 -net socket,vlan=13,listen=127.0.0.1:7013 \
 -net nic,model=virtio,vlan=14,macaddr=00:01:00:ff:11:14 \
 -net socket,vlan=14,listen=127.0.0.1:7014 \
 -net tap,ifname=tap11,vlan=1000,script=no &
sleep 2
# pe1
/bin/qemu-kvm -cpu kvm64 -nographic -m 2548 -hda /var/lib/libvirt/images/pe1-xe.raw \
 -serial telnet::8112,server,nowait \
 -net nic,model=virtio,vlan=1000,macaddr=00:01:00:ff:66:12 \
 -net nic,model=virtio,vlan=11,macaddr=00:01:00:ff:12:11 \
 -net socket,vlan=11,connect=127.0.0.1:7011 \
 -net nic,model=virtio,vlan=13,macaddr=00:01:00:ff:12:13 \
 -net socket,vlan=13,connect=127.0.0.1:7013 \
 -net nic,model=virtio,vlan=15,macaddr=00:01:00:ff:12:15 \
 -net socket,vlan=15,listen=127.0.0.1:7015 \
 -net nic,model=virtio,vlan=16,macaddr=00:01:00:ff:12:16 \
 -net socket,vlan=16,listen=127.0.0.1:7016 \
 -net tap,ifname=tap12,vlan=1000,script=no &
sleep 2
# pe2
/bin/qemu-kvm -cpu kvm64 -nographic -m 2548 -hda /var/lib/libvirt/images/pe2-xr.raw \
 -serial telnet::8113,server,nowait \
 -net nic,model=virtio,vlan=1000,macaddr=00:01:00:ff:66:13 \
 -net nic,model=virtio,vlan=12,macaddr=00:01:00:ff:13:12 \
 -net socket,vlan=12,connect=127.0.0.1:7012 \
 -net nic,model=virtio,vlan=14,macaddr=00:01:00:ff:13:14 \
 -net socket,vlan=14,connect=127.0.0.1:7014 \
 -net nic,model=virtio,vlan=17,macaddr=00:01:00:ff:13:17 \
 -net socket,vlan=17,listen=127.0.0.1:7017 \
 -net nic,model=virtio,vlan=18,macaddr=00:01:00:ff:13:18 \
 -net socket,vlan=18,listen=127.0.0.1:7018 \
 -net tap,ifname=tap13,vlan=1000,script=no &
sleep 2
# ce1-1
/bin/qemu-kvm -cpu kvm64 -nographic -m 2548 -hda /var/lib/libvirt/images/ce1-1-xe.raw \
 -serial telnet::8114,server,nowait \
 -net nic,model=virtio,vlan=1000,macaddr=00:01:00:ff:66:14 \
 -net nic,model=virtio,vlan=15,macaddr=00:01:00:ff:14:15 \
 -net socket,vlan=15,connect=127.0.0.1:7015 \
 -net tap,ifname=tap14,vlan=1000,script=no &
sleep 2
# ce1-2
/bin/qemu-kvm -cpu kvm64 -nographic -m 2548 -hda /var/lib/libvirt/images/ce1-2-xe.raw \
 -serial telnet::8115,server,nowait \
 -net nic,model=virtio,vlan=1000,macaddr=00:01:00:ff:66:15 \
 -net nic,model=virtio,vlan=16,macaddr=00:01:00:ff:15:16 \
 -net socket,vlan=16,connect=127.0.0.1:7016 \
 -net tap,ifname=tap15,vlan=1000,script=no &
sleep 2
# ce2-1
/bin/qemu-kvm -cpu kvm64 -nographic -m 2548 -hda /var/lib/libvirt/images/ce2-1-xe.raw \
 -serial telnet::8116,server,nowait \
 -net nic,model=virtio,vlan=1000,macaddr=00:01:00:ff:66:16 \
 -net nic,model=virtio,vlan=17,macaddr=00:01:00:ff:16:17 \
 -net socket,vlan=17,connect=127.0.0.1:7017 \
 -net tap,ifname=tap16,vlan=1000,script=no &
sleep 2
# ce2-2
/bin/qemu-kvm -cpu kvm64 -nographic -m 2548 -hda /var/lib/libvirt/images/ce2-2-xe.raw \
 -serial telnet::8117,server,nowait \
 -net nic,model=virtio,vlan=1000,macaddr=00:01:00:ff:66:17 \
 -net nic,model=virtio,vlan=18,macaddr=00:01:00:ff:17:18 \
 -net socket,vlan=18,connect=127.0.0.1:7018 \
 -net tap,ifname=tap17,vlan=1000,script=no &
sleep 2
		
