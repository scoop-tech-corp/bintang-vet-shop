<div>
  <div class="inner-box-title">
    <button class="btn btn-info openFormAddBarang">Tambah</button>
    <div class="input-search-section">
      <input type="text" class="form-control" placeholder="search..">
      <i class="fa fa-search" aria-hidden="true"></i>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-striped text-nowrap">
      <thead>
        <tr>
          <th>No</th>
          <th class="onOrdering" data='item_name' orderby="none">Nama Barang <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='category_name' orderby="none">Kategori Barang <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='total_item' orderby="none">Jumlah Barang <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='unit_name' orderby="none">Satuan Barang <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='branch_name' orderby="none">Cabang <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='created_by' orderby="none">Dibuat Oleh <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='created_at' orderby="none">Tanggal dibuat <span class="fa fa-sort"></span></th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="list-harga-barang"></tbody>
    </table>
  </div>

  @component('gudang.pembagian-harga.harga-barang.modal-harga-barang') @endcomponent
</div>