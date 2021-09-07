<aside class="main-sidebar">
	<section class="sidebar">
		<div class="section-logo-vet-clinic">
			<img src="{{ asset('assets/image/logo-vet-clinic.jpg') }}">
		</div>
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">MENU UTAMA</li>
			<li class="menuCabang"><a href="{{ url('/cabang') }}"><i class="fa fa-code-fork"></i> <span>Cabang</span></a></li>
			<li class="menuUser"><a href="{{ url('/user') }}"><i class="fa fa-users" aria-hidden="true"></i> <span>User Management</span></a></li>
			<li class="treeview menuGudang">
				<a>
					<i class="fa fa-database" aria-hidden="true"></i>
					<span>Gudang</span>
					<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="{{ url('/gudang/cat-food') }}"><i class="fa fa-circle-o"></i> Cat Food</a></li>
					<li><a href="{{ url('/gudang/dog-food') }}"><i class="fa fa-circle-o"></i> Dog Food</a></li>
					<li><a href="{{ url('/gudang/animal-food') }}"><i class="fa fa-circle-o"></i> Animal Food</a></li>
					<li><a href="{{ url('/gudang/vitamin') }}"><i class="fa fa-circle-o"></i> Vitamin</a></li>
					<li><a href="{{ url('/gudang/pet-care') }}"><i class="fa fa-circle-o"></i> Pet Care</a></li>
					<li><a href="{{ url('/gudang/kandang') }}"><i class="fa fa-circle-o"></i> Kandang</a></li>
          <li><a href="{{ url('/gudang/aksesoris') }}"><i class="fa fa-circle-o"></i> Aksesoris</a></li>
          <li><a href="{{ url('/gudang/lain-lain') }}"><i class="fa fa-circle-o"></i> Lain-lain</a></li>
				</ul>
			</li>
			<li class="menuPembayaran"><a href="{{ url('/pembayaran') }}"><i class="fa fa-money" aria-hidden="true"></i> <span>Pembayaran</span></a></li>
			<li class="treeview menuKeuangan">
				<a>
					<i class="fa fa-file-text" aria-hidden="true"></i>
					<span>Laporan Keuangan</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="{{ url('/laporan-keuangan/harian') }}"><i class="fa fa-circle-o"></i> Harian</a></li>
					<li><a href="{{ url('/laporan-keuangan/mingguan') }}"><i class="fa fa-circle-o"></i> Mingguan</a></li>
					<li><a href="{{ url('/laporan-keuangan/bulanan') }}"><i class="fa fa-circle-o"></i> Bulanan</a></li>
				</ul>
			</li>
		</ul>
	</section>
</aside>
