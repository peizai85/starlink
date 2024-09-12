
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
*/3 * * * * /usr/bin/php-cli /mnt/data/starlink/send_network_info.php
*/5 * * * * /usr/bin/php-cli /mnt/data/starlink/report_ip.php

##### PHP相关
ln -s /usr/bin/php-cli /usr/bin/php

#### 3proxy监控
#!/bin/bash

# 监控3proxy SOCKS5端口
conntrack -E
opkg update
opkg install conntrack
opkg install libmnl
opkg install libnetfilter-conntrack
ln -s /usr/lib/libmnl.so.0.2.0 /usr/lib/libmnl.so.0
export LD_LIBRARY_PATH=/usr/lib:$LD_LIBRARY_PATH


#!/bin/bash

# 定义端口到设备的映射（端口号对应的设备串口路径）
declare -A PORT_DEVICE_MAP=(
[1080]="/dev/ttyUSB0"
[1081]="/dev/ttyUSB1"
# 添加更多端口和设备的映射
)

# AT指令发送函数
send_at_command() {
local device=$1
echo "发送 AT 指令重启设备 $device"
echo "AT+CFUN=1,1" > $device
sleep 2
}

# 监控连接断开事件
monitor_disconnects() {
conntrack -E | while read event; do
for port in "${!PORT_DEVICE_MAP[@]}"; do
# 检查是否有某个端口的连接进入CLOSE状态
if echo "$event" | grep -q "dport=$port.*tcp.*[FIN_WAIT|CLOSE]"; then
echo "检测到端口 $port 的连接断开，重启设备 ${PORT_DEVICE_MAP[$port]}"
send_at_command "${PORT_DEVICE_MAP[$port]}"
fi
done
done
}

# 启动监控
monitor_disconnects




