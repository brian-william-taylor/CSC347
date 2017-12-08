#PUT YOUR ANSWERS TO THE FIREWALL QUESTION HERE.
# Submit your firewall script annotated so that it is clear which parts of your script accomplish which parts of this question.

/sbin/iptables -F
/sbin/iptables -t nat -F
/sbin/iptables -t mangle -F

# default policy for drop
/sbin/iptables -P INPUT DROP
/sbin/iptables -P OUTPUT DROP
/sbin/iptables -P FORWARD DROP

# enable related,established connections 
/sbin/iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
/sbin/iptables -A OUTPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
/sbin/iptables -A FORWARD -m state --state ESTABLISHED,RELATED -j ACCEPT

# (b) 
#	- Allow SMTP traffic to 192.168.0.100
/sbin/iptables -t nat -A PREROUTING -p tcp --dport 25 -i eth0 -j DNAT --to 192.168.0.100:25
/sbin/iptables -A FORWARD -p tcp -d 192.168.0.100 --dport 25 -j ACCEPT

# Port 80 HTTP:
#	- Allow HTTP traffic to 192.168.0.100
/sbin/iptables -t nat -A PREROUTING -p tcp --dport 80 -i eth0 -j DNAT --to 192.168.0.100:80
/sbin/iptables -A FORWARD -p tcp -d 192.168.0.100 --dport 80 -j ACCEPT

# (c) Allow 192.168.0.75 to ssh into the firewall. No other access into the firewall is permitted. 
/sbin/iptables -A INPUT -p tcp --dport 22 -s 192.168.0.75 -j ACCEPT

# (d) Allow 10.10.10.75 (outside the local network) to ssh into the web/mail
# server by using port 2222 on the external side of the firewall.
/sbin/iptables -t nat -A PREROUTING -p tcp -s 10.10.10.75 --dport 2222 -i eth0 -j DNAT --to 192.168.0.100:22
/sbin/iptables -A FORWARD -p tcp -d 192.168.0.100 --dport 22 -j ACCEPT

# (f) The CEO has a windows box inside the private network (192.168.0.33). 
# The CEO (with fixed IP 10.10.10.33) would like to have remote desktop access to his desktop on port 7784.
/sbin/iptables -t nat -A PREROUTING -s 10.10.10.33 -p tcp --dport 7784 -i eth0 -j DNAT --to 192.168.0.33:3389
/sbin/iptables -A FORWARD -p tcp -d 192.168.0.33 --dport 3389 -j ACCEPT

# (g) Sid has a windows box inside the private network (192.168.0.37). 
# Sid (with fixed IP 10.10.10.211) would like to have remote desktop access to his desktop on port _.
/sbin/iptables -t nat -A PREROUTING -s 10.10.10.37 -p tcp --dport 7785 -i eth0 -j DNAT --to 192.168.0.37:3389
/sbin/iptables -A FORWARD -p tcp -d 192.168.0.37 --dport 3389 -j ACCEPT
/sbin/iptables -A INPUT -s 10.10.10.128 -j DROP



# (i)  Finally, imagine that the only routable IP is 10.10.10.10. 
# All internal machines should share this IP for Internet traffic. 
/sbin/iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE 
#	- Allow http
#/sbin/iptables -A FORWARD -s 192.168.0.0/255.255.255.0 -p tcp --dport 80 -j ACCEPT

