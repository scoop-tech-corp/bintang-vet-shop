<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<div class="section-logo-vet-clinic">
			<img src="{{ asset('assets/image/logo-vet-clinic.jpg') }}">
		</div>
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">MENU UTAMA</li>
			<li class="active"><a href="{{ url('/cabang') }}"><i class="fa fa-book"></i> <span>Cabang</span></a></li>
			<li class=""><a href="{{ url('/user') }}"><i class="fa fa-book"></i> <span>User Management</span></a></li>
			<li class=""><a href="{{ url('/pasien') }}"><i class="fa fa-book"></i> <span>Pasien</span></a></li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-pie-chart"></i>
					<span>Pendaftaran</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="#"><i class="fa fa-circle-o"></i> Rawat Jalan</a></li>
					<li><a href="#"><i class="fa fa-circle-o"></i> Rawat Inap</a></li>
				</ul>
			</li>
			<li class=""><a href="{{ url('/dokter') }}"><i class="fa fa-book"></i> <span>Dokter</span></a></li>
			<li class=""><a href="{{ url('/periksa') }}"><i class="fa fa-book"></i> <span>Hasil Pemeriksaan</span></a>
			</li>
			<li class=""><a href="{{ url('/tindakan') }}"><i class="fa fa-book"></i> <span>Tindakan</span></a></li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-pie-chart"></i>
					<span>Gudang</span>
					<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="{{ url('/gudang1') }}"><i class="fa fa-circle-o"></i> Gudang 1</a></li>
					<li><a href="{{ url('/gudang2') }}"><i class="fa fa-circle-o"></i> Gudang 2</a></li>
				</ul>
			</li>
			<li class=""><a href="{{ url('/pembayaran') }}"><i class="fa fa-book"></i> <span>Pembayaran</span></a></li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-pie-chart"></i>
					<span>Laporan Keuangan</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="#"><i class="fa fa-circle-o"></i> Harian</a></li>
					<li><a href="#"><i class="fa fa-circle-o"></i> Mingguan</a></li>
					<li><a href="#"><i class="fa fa-circle-o"></i> Bulanan</a></li>
					<li><a href="#"><i class="fa fa-circle-o"></i> Tahunan</a></li>
				</ul>
			</li>
			<li class=""><a href="{{ url('/kunjungan') }}"><i class="fa fa-book"></i> <span>Kunjungan</span></a></li>
		</ul>
	</section>
	<!-- /.sidebar -->
</aside>
