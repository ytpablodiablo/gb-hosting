<div class="container">
			<div class="rows">
				<nav class="navbar navbar-default">
  					<div class="container">
  						<div class="rows">
    						<div class="navbar-header">
      							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        							<span class="sr-only">Toggle navigation</span>
        							<span class="icon-bar"></span>
        							<span class="icon-bar"></span>
        							<span class="icon-bar"></span>
      							</button>
      							<a class="navbar-brand visible-xs" href="/home">GB-Hoster.me</a>
    						</div>
    						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      							<ul class="nav navbar-nav">

        				<li><a href="/"><i class="fa fa-home"></i> Home</a></li>

                        <li class="dropdown">					
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<span><i class="fa fa-ticket"></i> Tiketi <span class="label label-warning">0</span></span>
							<b class="caret"></b>
						</a>	    
					
						<ul class="dropdown-menu">
							<li><a href="tiket_lista.php">Novi tiketi - <m>0</m></a></li>
							<li><a href="tiket_lista.php">Odgovoreni tiketi - <m>0</m></a></li>
							<li><a href="tiket_lista.php">Zakljucani tiketi - <m>0</m></a></li>
							<li><a href="tiket_lista.php">Billing tiketi - <m>0</m></a></li>
							<li><a href="tiket_lista.php">Svi billing tiketi - <m>0</m></a></li>
							<li><a href="tiket_lista.php">Uplate koje cekaju proveru - <m>0</m></a></li>
							<li><a href="tiket_lista.php">Prosledjeni tiketi - <m>0</m></a></li>
						</ul> 				
					</li>

                        <li class="dropdown" animations="wobble wobble wobble wobble">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" >
							<span><i class="fa fa-wrench"></i> Plugini</span>
							<b class="caret"></b>
						</a>	    
					
						<ul class="dropdown-menu">
							<li><a  href="" data-toggle="modal" data-target="#dodaj-plugin-modal">Dodaj plugin</a></li>
							<li><a href="/plugin_list.php">Lista plugina</a></li>
						</ul> 
					</li>

                        <li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<span><i class="fa fa-cogs"></i> Modovi</span>
							<b class="caret"></b>
						</a>	    
					
						<ul class="dropdown-menu">
							<li><a href="" data-toggle="modal" data-target="#dodaj-mod-modal">Dodaj mod</a></li>
							<li><a href="/mod_list.php">Lista modova</a></li>
						</ul> 
					</li>

                    <li class="dropdown">					
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<span><i class="fa fa-users"></i> Klijenti <span class="label label-warning">0</span></span>
							<b class="caret"></b>
						</a>	    
						
						<ul class="dropdown-menu">
							<li><a href="" data-toggle="modal" data-target="#dodaj-klijenta-modal" >Dodaj klijenta</a></li>
							<li><a href="klijenti.php">Klijenti (<m>0</m>)</a></li>
							<li><a href="">Klijenti - novac (<m>0</m>)</a></li>
							<li><a href="">Za aktivaciju (<m>0</m>)</a></li>
							<li><a href="">Banovani klijenti (<m>0</m>)</a></li>
						</ul> 				
					</li>

                   <li class="dropdown ">					
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<span><i class="fa fa-server"></i> Serveri</span>
							<b class="caret"></b>
						</a>
						
						<ul class="dropdown-menu">
							<li><a href="/servers/all">Serveri (<m><?php echo get_servers_number($conn); ?></m>)</a></li>
							<li><a href="/servers/suspend">Suspendovani Serveri ( <m><?php echo get_suspend_servers_number($conn); ?></m> )</a></li>
							<li><a href="/create_server">Kreiraj server</a></li>
						</ul> 				
					</li>

                    <li class="dropdown">					
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<span>Obavestenja</span>
							<b class="caret"></b>
						</a>	
					
						<ul class="dropdown-menu">
							<li><a href="" data-toggle="modal" data-target="#dodaj-obavestenje-modal">Dodaj obavestenje</a></li>
							<li><a href="/obavestenja.php">Obavestenja za klijente</a></li>
							<li><a href="">Obavestenja za admine</a></li>
							<li><a href="">Obavestenja za sve</a></li>
						</ul>    				
					</li>

                     <li class="dropdown">					
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<span><i class="fa fa-server"></i> Masine</span>
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li><a href="" data-toggle="modal" data-target="#dodaj-masinu-modal">Dodaj novu masinu</a></li>
							<li><a href="/box_list">Pregled masina (<m><?php echo get_box_number($conn); ?></m>)</a></li>
						</ul>    				
					</li>

					<li class="dropdown">					
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<span><i class="fa fa-user"></i>  Admini</span>
							<b class="caret"></b>
						</a>	
						<ul class="dropdown-menu">
							<li><a href="/admin_list.php">Pregled admina</a></li>
							<li><a href="" data-toggle="modal">Dodaj novog admina</a></li>	
							 </ul>
			                </li>

                           <li style="float: right;"><a href="/"><i class="fa fa-user"></i> Profile</a></li>

						             </ul>   
					             </li>
      							<ul class="nav navbar-nav navbar-right">
      							</ul>
    						</div>
  						</div>
  					</div>
				</nav>
			</div>
		</div>