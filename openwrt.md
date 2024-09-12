
mounts

fileassistant

startup

daemon
auth none
nserver 8.8.8.8
nserver 8.8.4.4
proxy -p3129 -i192.168.2.188 -Deusb0

###### 安装所需的驱动
根据你连接的 USB 网络设备，可能需要安装一些额外的驱动。以下是常见的驱动模块：

USB 总线驱动：
bash
复制代码
opkg install kmod-usb2 kmod-usb3
USB 网络驱动： 如果是 USB 网络适配器，常用的模块包括：
bash
复制代码
opkg install kmod-usb-net kmod-usb-net-asix kmod-usb-net-rtl8152 kmod-usb-net-cdc-ether
USB 无线网卡驱动： 如果你使用 USB 无线网卡，可能需要安装以下模块：
bash
复制代码
opkg install kmod-usb-net-rndis kmod-usb-net-rtl8192cu
opkg install kmod-usb-serial-option


###### 安装SSH密钥签名
opkg install openssh-client
opkg install openssh-keygen

#######3proxy配置
daemon
auth none
socks -p1080 -i0.0.0.0 -Deusb0
allow * 192.168.2.0/24
deny *
log /var/log/3proxy.log D

###### 计划任务
*/3* * * * /usr/bin/php-cli /mnt/data/starlink/send_network_info.php
*/5* * * * /usr/bin/php-cli /mnt/data/starlink/report_ip.php

##### PHP相关
ln -s /usr/bin/php-cli /usr/bin/php



