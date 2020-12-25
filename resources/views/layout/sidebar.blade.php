<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<div class="section-logo-vet-clinic">
			<img src="{{ asset('assets/image/logo-vet-clinic.jpg') }}">
		</div>
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">MENU UTAMA</li>
			<li class="menuCabang"><a href="{{ url('/cabang') }}"><i class="fa fa-code-fork"></i> <span>Cabang</span></a></li>
			<li class="menuUser"><a href="{{ url('/user') }}"><i class="fa fa-users" aria-hidden="true"></i> <span>User Management</span></a></li>
			<li class="menuPasien"><a href="{{ url('/pasien') }}"><i class="fa fa-bed"></i> <span>Pasien</span></a></li>
			<li class="treeview menuPendaftaran">
				<a href="#">
					<i class="fa fa-file-text-o" aria-hidden="true"></i>
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
			<li class="menuDokter"><a href="{{ url('/dokter') }}"><i class="fa fa-stethoscope" aria-hidden="true"></i> <span>Dokter</span></a></li>
			<li class="menuPeriksa"><a href="{{ url('/periksa') }}"><i class="fa fa-heartbeat" aria-hidden="true"></i> <span>Hasil Pemeriksaan</span></a></li>
			<li class="menuTindakan"><a href="{{ url('/tindakan') }}"><i class="fa fa-medkit" aria-hidden="true"></i> <span>Tindakan</span></a></li>
			<li class="treeview menuGudang">
				<a href="#">
					<i class="fa fa-database" aria-hidden="true"></i>
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
			<li class="menuPembayaran"><a href="{{ url('/pembayaran') }}"><i class="fa fa-money" aria-hidden="true"></i> <span>Pembayaran</span></a></li>
			<li class="treeview menuKeuangan">
				<a href="#">
					<i class="fa fa-file-text" aria-hidden="true"></i>
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
			<li class="menuKunjungan"><a href="{{ url('/kunjungan') }}"><i class="fa fa-wheelchair" aria-hidden="true"></i> <span>Kunjungan</span></a></li>
		</ul>
	</section>
	<!-- /.sidebar -->
</aside>
