<!DOCTYPE html>
<html>
	<head>
		<title><?= $title ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/w3.css">
		<link type="text/css" rel="stylesheet" href="css/stylesheet.css" />
	</head>
	<body>
	<a name="top"></a>
	<div id="header">
		<h1>Virtual Network Lab Config Generator</h1>
		<p>
		    The "Virtual Network Lab Config Generator" is a simple web application 
    		that generates the KVM Hypervisor configuration so that you can easily and
    		quickly spin up complex virtual networks utilizing the Cisco IOS-XR (XRv) 
    		and IOS-XE (CSR1000v) images. Both images are available from the 
    		<a href="http://www.cisco.com/" target="_blank">Cisco</a> Website. This will 
    		allow you to spend more time learning about networking and less time building the 
    		configurations that support Cisco studies. The code is available for download on 
    		<a href="https://github.com/jtdub/vnlcg" target="_blank">Github.</a>
	    </p>
    </div>
    <div class="w3-sidebar w3-bar-block w3-light-grey w3-card-2" style="width:130px">
        <h5 class="w3-bar-item">Menu</h5>
        <button class="w3-bar-item w3-button tablink" onclick="openSection(event, 'devices')">Devices</button>
        <button class="w3-bar-item w3-button tablink" onclick="openSection(event, 'connections')">Connections</button>
        <button class="w3-bar-item w3-button tablink" onclick="openSection(event, 'config')">Configuration</button>
    </div>
    <div id="content" style="margin-left:130px">
        <?php $this->insert("partials/addDevice", ['devices' => $devices]); ?>
        
        <?php $this->insert("partials/addXconn", ['devices' => $devices, 'xconns' => $xconns]); ?>
        
		    
        <?php $this->insert('partials/devConfig', ['devices' => $devices, 'xconns' => $xconns]); ?>
        
	</div>
	<script type="text/javascript">
        function openSection(evt, sectionName) {
          var i, x, tablinks;
          x = document.getElementsByClassName("section");
          for (i = 0; i < x.length; i++) {
             x[i].style.display = "none";
          }
          tablinks = document.getElementsByClassName("tablink");
          for (i = 0; i < x.length; i++) {
              tablinks[i].className = tablinks[i].className.replace(" w3-grey", ""); 
          }
          document.getElementById(sectionName).style.display = "block";
          evt.currentTarget.className += " w3-grey";
        }
        
        var active_page = '<?= $active_page ?>';
        var activeEl = document.getElementById(active_page);
        activeEl.style.display = "block";
        
        var btns = document.getElementsByTagName('button');
        
        for(var i = 0; i < btns.length; i++) {
            if(btns[i].innerHTML.toLowerCase() == active_page) {
                btns[i].className += " w3-grey";
            }
        }
        
    </script>
	</body>
</html>