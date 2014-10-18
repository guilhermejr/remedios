VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  
	config.vm.box = "guilhermejr/debian7-64"
	config.vm.box_url = "http://vagrant.guilhermejr.net/debian7-64.box"

	config.vm.network "forwarded_port", guest: 80, host: 8080
	config.vm.network "forwarded_port", guest: 443, host: 8443
	config.vm.network "forwarded_port", guest: 3306, host: 3306

	config.vm.network "private_network", ip: "192.168.33.10"

	config.vm.synced_folder "src", "/var/www", type: "nfs"

	config.vm.provision "puppet" do |puppet|
		puppet.manifest_file = "server.pp"
	end

end
